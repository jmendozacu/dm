<?php

namespace DiamondMansion\Extensions\Controller\Api;

class SaveViewedItem extends \Magento\Framework\App\Action\Action
{
    protected $_coreSession;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Session\SessionManagerInterface $coreSession
    ) {
        $this->_coreSession = $coreSession;

        return parent::__construct($context);
    }

    public function execute() {
        $productId = $this->getRequest()->getParam('id');
        $url = $this->getRequest()->getParam('url');

        $viewedItems = $this->_coreSession->getViewedItems();
        if (!$viewedItems) {
            $viewedItems = [];
        }

        $viewedItems[$url] = (int) $this->getRequest()->getParam('id');
        $this->_coreSession->setViewedItems($viewedItems);
        print_r($this->_coreSession->getViewedPages());
    }
}