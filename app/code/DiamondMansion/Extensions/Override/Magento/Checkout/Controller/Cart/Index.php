<?php

namespace DiamondMansion\Extensions\Override\Magento\Checkout\Controller\Cart;

class Index extends \Magento\Checkout\Controller\Cart\Index
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Shopping Bag'));
        return $resultPage;
    }
}