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

namespace Magenest\InstagramShop\Controller\Adminhtml\Instagram;

use Magenest\InstagramShop\Model\Client;
use Magenest\InstagramShop\Model\Instagram;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Cache\Manager;
use Magento\Framework\App\Cache\Type\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Connect
 * @package Magenest\InstagramShop\Controller\Adminhtml\Instagram
 */
class Connect extends Action
{
    /**
     * @var Instagram
     */
    protected $instagram;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var WriterInterface
     */
    protected $_writer;

    /**
     * @var Manager
     */
    protected $_cacheManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Connect constructor.
     * @param Context $context
     * @param Client $client
     * @param WriterInterface $writer
     * @param Manager $_cacheManager
     * @param Instagram $instagram
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        Client $client,
        WriterInterface $writer,
        Manager $_cacheManager,
        Instagram $instagram,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig  = $scopeConfig;
        $this->client        = $client;
        $this->_writer       = $writer;
        $this->_cacheManager = $_cacheManager;
        $this->instagram     = $instagram;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $this->connect();
            $this->messageManager->addSuccessMessage(__('Get access token successfully'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e);
        }

        return $this->_redirect(Client::INSTAGRAM_SHOP_CONFIGURATION_SECTION);
    }

    /**
     * @throws LocalizedException
     */
    protected function connect()
    {
        if ($error = $this->getRequest()->getParam('error_description')) {
            throw new LocalizedException(__($error));
        }

        $code = $this->getRequest()->getParam('code');

        if (!$code) {
            throw new LocalizedException(__('Unable to retrieve access code from server.'));
        } else {
            $clientId     = $this->_session->getClientId();
            $clientSecret = $this->_session->getClientSecret();
            /** use code exchange for access token */
            $data = $this->client->fetchAccessToken($clientId, $clientSecret, $code);
            if (!isset($data['access_token'])) {
                throw new LocalizedException(__('Unable to get access token from server'));
            }
            if (!isset($data['user']['id'])) {
                throw new LocalizedException(__('Unable to get user id from server.'));
            }
            $token = $data['access_token'];
            $id    = $data['user']['id'];

            $this->processAccountChanged($id);

            /** save access token at store configuration */
            $this->_writer->save(Client::XML_PATH_ACCESS_TOKEN, $token);
            $this->_writer->save(Client::XML_PATH_ACCOUNT_ID, $id);
            $this->_writer->save(Client::XML_PATH_ACCOUNT, json_encode($data['user']));

            $this->reInit();
        }
    }

    /**
     * @param $accountId
     */
    private function processAccountChanged($accountId)
    {
        $oldAccountId = $this->_scopeConfig->getValue(Client::XML_PATH_ACCOUNT_ID);
        if ($oldAccountId && $accountId && ($oldAccountId != $accountId)) {
            $this->instagram->processAccountChanged();
        }
    }

    /**
     * Re-Init config data
     */
    private function reInit()
    {
        $this->_writer->save(Client::XML_PATH_CLIENT_ID, $this->_session->getClientId());
        $this->_writer->save(Client::XML_PATH_CLIENT_SECRET, $this->_session->getClientSecret());
        $this->_session->unsClientId();
        $this->_session->unsClientSecret();
        $this->cleanConfigCache();
    }

    /**
     * Clean config cache
     */
    private function cleanConfigCache()
    {
        $this->_cacheManager->clean([Config::TYPE_IDENTIFIER]);
    }
}
