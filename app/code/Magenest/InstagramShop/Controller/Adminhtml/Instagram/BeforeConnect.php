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
use Magento\Framework\App\Config\Storage\WriterInterface;

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
        \Magento\Config\Model\ResourceModel\Config $config
    ) {
        $this->_config = $config;
        $this->client  = $client;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $clientId     = $this->filterSpace($this->getRequest()->getParam('client_id', ''));
        $clientSecret = $this->filterSpace($this->getRequest()->getParam('client_secret', ''));
        if (empty($clientId) || empty($clientSecret)) {
            $this->messageManager->addErrorMessage(__("Please fill in ClientId and ClientSecret."));
            return $this->_redirect(Client::INSTAGRAM_SHOP_CONFIGURATION_SECTION);
        } else {
            $this->_session->setClientId($clientId);
            $this->_session->setClientSecret($clientSecret);

            $requestUrl = $this->client->createAuthUrl($clientId);
            return $this->_redirect($requestUrl);
        }
    }

    /**
     * @param $string
     * @return string
     */
    private function filterSpace($string)
    {
        return str_replace(' ', '', $string);
    }
}
