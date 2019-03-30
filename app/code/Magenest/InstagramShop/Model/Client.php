<?php
/**
 *
 * Copyright © 2018 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_InstagramShop extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_InstagramShop
 * @author    dangnh@magenest.com
 */

namespace Magenest\InstagramShop\Model;

use Magento\Backend\App\ConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Framework\HTTP\Adapter\CurlFactory;

/**
 * Class Client
 * @package Magenest\InstagramShop\Model
 */
class Client
{
    const REDIRECT_URI_PATH                    = 'instagram/instagram/connect/';
    const INSTAGRAM_SHOP_CONFIGURATION_SECTION = 'adminhtml/system_config/edit/section/magenest_instagram_shop';

    protected $path_client_id = 'magenest_instagram_shop/instagram/client_id';
    protected $path_client_secret = 'magenest_instagram_shop/instagram/client_secret';
    protected $path_access_token = 'magenest_instagram_shop/instagram/access_token';
    protected $path_account_id = 'magenest_instagram_shop/instagram/account_id';

    protected $path_tags = 'magenest_instagram_shop/instagram_tags/tags';

    protected $oauth2_service_uri = 'https://api.instagram.com/v1';
    protected $oauth2_auth_uri = 'https://api.instagram.com/oauth/authorize';
    protected $oauth2_token_uri = 'https://api.instagram.com/oauth/access_token';

    protected $scope = ['basic', 'public_content'];

    /**
     * @var CurlFactory
     */
    protected $_curlFactory;
    /**
     * @var ConfigInterface
     */
    protected $_config;

    /**
     * @var UrlInterface
     */
    protected $_url;
    /**
     * @var null|string
     */
    protected $clientId = null;

    /**
     * @var null|string
     */
    protected $clientSecret = null;

    /**
     * @var null|string
     */
    protected $redirectUri = null;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var TaggedPhotoFactory
     */
    protected $_taggedPhotoFactory;

    /**
     * Client constructor.
     * @param CurlFactory $curlFactory
     * @param ConfigInterface $config
     * @param UrlInterface $url
     * @param TaggedPhotoFactory $taggedPhotoFactory
     */
    public function __construct(
        CurlFactory $curlFactory,
        ConfigInterface $config,
        UrlInterface $url,
        TaggedPhotoFactory $taggedPhotoFactory
    )
    {
        $this->_taggedPhotoFactory = $taggedPhotoFactory;
        $this->_curlFactory        = $curlFactory;
        $this->_config             = $config;
        $this->_url                = $url;
        $this->_config             = $config;
        $this->initAppInformation();
        $this->initAppRedirectUri();
    }


    private function initAppInformation()
    {
        $this->clientId     = $this->_getClientId();
        $this->clientSecret = $this->_getClientSecret();
    }

    private function initAppRedirectUri()
    {
        $this->redirectUri = $this->_url->getUrl(self::REDIRECT_URI_PATH);
    }

    /**
     * url to instagram authorization site
     * @param string $clientId
     * @return string
     */
    public function createAuthUrl($clientId = '')
    {
        $query = [
            'client_id'     => $clientId ? $clientId : $this->getClientId(),
            'redirect_uri'  => $this->getRedirectUri(),
            'scope'         => implode(' ', $this->getScope()),
            'response_type' => "code"
        ];
        $url   = $this->oauth2_auth_uri . '?' . http_build_query($query);

        return $url;
    }

    /**
     * @param string $endpoint
     * @param string $method
     * @param array $params
     * @return array
     * @throws LocalizedException
     */
    public function api($endpoint, $method = 'GET', $params = [])
    {
        if (empty($this->token)) {
            $this->_getAccessToken();
        }
        $url      = $this->oauth2_service_uri . $endpoint;
        $method   = strtoupper($method);
        $params   = array_merge([
            'access_token' => $this->token
        ], $params);
        $response = $this->_httpRequest($url, $method, $params);
        if (isset($response['meta']['error_type']) && $response['meta']['error_type'] == 'OAuthAccessTokenError') {
            $this->_config->setValue($this->getPathAccessToken(), 'Expired');
        }

        return $response;
    }

    /**
     * @param $tag
     * @param array $param
     * @return array
     * @throws LocalizedException
     */
    public function getMediasByTag($tag, $param = [])
    {
        $handle   = sprintf('/tags/%s/media/recent', $tag);
        $response = $this->api($handle, 'GET', $param);
        return $response;
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getAllMedias()
    {
        $endpoint = '/users/self/media/recent';
        $param    = ['count' => 100000];
        $media    = $this->api($endpoint, 'GET', $param);

        return isset($media['data']) ? $media['data'] : [];
    }

    /**
     * @param null $code
     * @return array
     * @throws LocalizedException
     */
    public function fetchAccessToken($code = null)
    {
        $token_array = [
            'client_id'     => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->getRedirectUri(),
            'code'          => $code,
        ];

        if (empty($code)) {
            throw new LocalizedException(
                __('Unable to retrieve access code.')
            );
        }
        return $this->_httpRequest(
            $this->oauth2_token_uri,
            'POST',
            $token_array
        );
    }

    /**
     * @param $url
     * @param string $method
     * @param array $params
     * @return array
     * @throws LocalizedException
     */
    protected function _httpRequest($url, $method = 'GET', $params = array())
    {
        /** @var Curl $curl */
        $curl = $this->_curlFactory->create();
        $curl->setConfig([
            'timeout'   => 2,
            'useragent' => 'Magenest Instagram Shop',
            'referer'   => $this->_url->getUrl('*/*/*')
        ]);
        switch ($method) {
            case 'GET':
                $url .= '?' . http_build_query($params);
                $curl->write($method, $url);
                break;
            case 'POST':
                $curl->write($method, $url, '1.1', [], http_build_query($params));
                break;
        }
        $response = $curl->read();
        $curl->close();
        if ($response === false) {
            throw new LocalizedException(__('HTTP error occurred while issuing request. Please contact Administrator for more information.'));
        }
        $response        = preg_split('/^\r?$/m', $response, 2);
        $response        = trim($response[1]);
        $decodedResponse = json_decode($response, true);
        if (is_array($decodedResponse) && !empty($decodedResponse)) {
            $resultResponse = isset($decodedResponse['meta']) ? $decodedResponse['meta'] : $decodedResponse;
            if (isset($resultResponse['code']) && $resultResponse['code'] != 200) {
                throw new LocalizedException(__(implode(', ', $resultResponse)));
            }
            return $decodedResponse;
        } else {
            throw new LocalizedException(__('Empty response.'));
        }
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        if (empty($this->token)) {
            $this->_getAccessToken();
        }

        return $this->token;
    }

    /**
     * @param $token
     */
    public function setAccessToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return null|string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return null|string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @return null|string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @return array
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        $str  = preg_replace('/\s+/', '', $this->_getStoreConfig($this->path_tags));
        $tags = $str ? explode(',', $str) : [];
        foreach ($tags as $key => &$tag) {
            $tag = preg_replace('/[^A-Za-z0-9]/', '', $tag);
            if (!$tag) {
                unset($tags[$key]);
            }
        }
        return array_unique($tags);
    }

    /**
     * get access token from store configuration
     */
    public function _getAccessToken()
    {
        $this->setAccessToken($this->_getStoreConfig($this->path_access_token));
    }

    /**
     * @return string
     */
    protected function _getClientId()
    {
        return $this->_getStoreConfig($this->path_client_id);
    }

    /**
     * @return string
     */
    protected function _getClientSecret()
    {
        return $this->_getStoreConfig($this->path_client_secret);
    }

    /**
     * @param $xmlPath
     * @return mixed
     */
    protected function _getStoreConfig($xmlPath)
    {
        return $this->_config->getValue($xmlPath);
    }

    /**
     * @return string
     */
    public function getPathClientId()
    {
        return $this->path_client_id;
    }

    /**
     * @return string
     */
    public function getPathClientSecret()
    {
        return $this->path_client_secret;
    }

    /**
     * @return string
     */
    public function getPathAccessToken()
    {
        return $this->path_access_token;
    }


    /**
     * @return string
     */
    public function getPathAccountId()
    {
        return $this->path_account_id;
    }
}
