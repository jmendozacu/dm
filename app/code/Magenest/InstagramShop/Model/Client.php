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
use Magento\Framework\App\Config\ScopeConfigInterface;
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

    const XML_PATH_CLIENT_ID     = 'magenest_instagram_shop/instagram/client_id';
    const XML_PATH_CLIENT_SECRET = 'magenest_instagram_shop/instagram/client_secret';
    const XML_PATH_ACCESS_TOKEN  = 'magenest_instagram_shop/instagram/access_token';
    const XML_PATH_ACCOUNT       = 'magenest_instagram_shop/instagram/account';
    const XML_PATH_ACCOUNT_ID    = 'magenest_instagram_shop/instagram/account_id';
    const XML_PATH_TAGS          = 'magenest_instagram_shop/instagram_tags/tags';

    protected $oauth2_service_uri = 'https://api.instagram.com/v1';
    protected $oauth2_auth_uri    = 'https://api.instagram.com/oauth/authorize';
    protected $oauth2_token_uri   = 'https://api.instagram.com/oauth/access_token';

    protected $scope = ['basic', 'public_content'];

    /**
     * @var CurlFactory
     */
    protected $_curlFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

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
     * Client constructor.
     * @param CurlFactory $curlFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param UrlInterface $url
     */
    public function __construct(
        CurlFactory $curlFactory,
        ScopeConfigInterface $scopeConfig,
        UrlInterface $url
    ) {
        $this->_curlFactory = $curlFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_url         = $url;
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
    public function createAuthUrl($clientId)
    {
        $query = [
            'client_id'     => $clientId,
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
     * @param string $clientId
     * @param string $clientSecret
     * @param string $code
     * @return array
     * @throws LocalizedException
     */
    public function fetchAccessToken($clientId, $clientSecret, $code)
    {
        $data = [
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->getRedirectUri(),
            'code'          => $code,
        ];

        return $this->_httpRequest(
            $this->oauth2_token_uri,
            'POST',
            $data
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
            if (isset($decodedResponse['pagination']['next_url'])) {
                $decodedResponse = array_merge_recursive($decodedResponse, $this->_httpRequest($decodedResponse['pagination']['next_url']));
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
        $str  = preg_replace('/\s+/', '', $this->_getStoreConfig(self::XML_PATH_TAGS));
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
        $this->setAccessToken($this->_getStoreConfig(self::XML_PATH_ACCESS_TOKEN));
    }

    /**
     * @return string
     */
    protected function _getClientId()
    {
        return $this->_getStoreConfig(self::XML_PATH_CLIENT_ID);
    }

    /**
     * @return string
     */
    protected function _getClientSecret()
    {
        return $this->_getStoreConfig(self::XML_PATH_CLIENT_SECRET);
    }

    /**
     * @param $xmlPath
     * @return mixed
     */
    protected function _getStoreConfig($xmlPath)
    {
        return $this->_scopeConfig->getValue($xmlPath);
    }
}
