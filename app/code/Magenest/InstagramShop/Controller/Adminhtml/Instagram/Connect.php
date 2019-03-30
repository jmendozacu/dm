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
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Connect
 * @package Magenest\InstagramShop\Controller\Adminhtml\Instagram
 */
class Connect extends Action
{
    /** @var \Magento\Config\Model\ResourceModel\Config */
    protected $_config;

    protected $client;

    protected $_scopeConfig;

    /**
     * Connect constructor.
     * @param Context $context
     * @param \Magento\Config\Model\ResourceModel\Config $config
     * @param Client $client
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        \Magento\Config\Model\ResourceModel\Config $config,
        Client $client,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->client       = $client;
        $this->_config      = $config;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $this->connect();
            $this->_eventManager->dispatch('instagram_controller_connect_successful');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->_redirect(Client::INSTAGRAM_SHOP_CONFIGURATION_SECTION);
    }

    /**
     * @throws LocalizedException
     */
    protected function connect()
    {
        $error = $this->getRequest()->getParam('error');
        $code  = $this->getRequest()->getParam('code');

        if (!(isset($error) || isset($code))) {
            return;
        }

        $client = $this->client;
        if ($code) {
            /** use code exchange for access token */
            $data = $client->fetchAccessToken($code);
            if (!isset($data['access_token'])) {
                throw new LocalizedException(__('Unable to get access token from server'));
            }
            if (!isset($data['user']['id'])) {
                throw new LocalizedException(__('Unable to get user id from server.'));
            }
            $token = $data['access_token'];
            $id    = $data['user']['id'];

            /** save access token at store configuration */
            $this->_config->saveConfig($this->client->getPathAccessToken(), $token, 'default', 0);
            $this->_config->saveConfig($this->client->getPathAccountId(), $id, 'default', 0);
        }
    }

}
