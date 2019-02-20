<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace DiamondMansion\Extensions\Override\Magento\Catalog\Controller\Product;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Catalog\Controller\Product as ProductAction;

/**
 * View a product on storefront. Needs to be accessible by POST because of the store switching.
 */
class View extends \Magento\Catalog\Controller\Product\View
{
    protected $_coreSession;
    protected $_urlInterface;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Helper\Product\View $viewHelper,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Framework\UrlInterface $urlInterface
    ) {
        $this->_coreSession = $coreSession;
        $this->_urlInterface = $urlInterface;

        parent::__construct($context, $viewHelper, $resultForwardFactory, $resultPageFactory);
    }

    /**
     * Product view action
     *
     * @return \Magento\Framework\Controller\Result\Forward|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $url = $this->_urlInterface->getCurrentUrl();

        $viewedItems = $this->_coreSession->getViewedItems();
        if (!$viewedItems) {
            $viewedItems = [];
        }

        $viewedItems[$url] = (int) $this->getRequest()->getParam('id');
        $this->_coreSession->setViewedItems($viewedItems);        

        return parent::execute();
    }
}
