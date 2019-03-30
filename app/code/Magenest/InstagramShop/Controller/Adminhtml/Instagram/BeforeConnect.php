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

use Magento\Backend\App\Action;

class BeforeConnect extends Action
{
    protected $client;

    protected $_config;

    protected $_scopeConfig;

    /**
     * BeforeConnect constructor.
     * @param Action\Context $context
     * @param \Magenest\InstagramShop\Model\Client $client
     * @param \Magento\Config\Model\ResourceModel\Config $config
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Action\Context $context,
        \Magenest\InstagramShop\Model\Client $client,
        \Magento\Config\Model\ResourceModel\Config $config,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->_config      = $config;
        $this->client       = $client;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $clientId     = $this->getRequest()->getParam('client_id');
        $clientSecret = $this->getRequest()->getParam('client_secret');
        if ($clientId == '' || $clientSecret == '') {
            $this->messageManager->addErrorMessage(__("Please fill in ClientId and ClientSecret."));
            return $this->_redirect(\Magenest\InstagramShop\Model\Client::INSTAGRAM_SHOP_CONFIGURATION_SECTION);
        } else {
            $this->_config->saveConfig($this->client->getPathClientId(), $clientId, 'default', 0);
            $this->_config->saveConfig($this->client->getPathClientSecret(), $clientSecret, 'default', 0);

            $this->_eventManager->dispatch('instagram_controller_connect_before',
                [
                    'client_id'     => $clientId,
                    'client_secret' => $clientSecret
                ]);

            $requestUrl = $this->client->createAuthUrl($clientId);
            return $this->_redirect($requestUrl);
        }
    }
}
