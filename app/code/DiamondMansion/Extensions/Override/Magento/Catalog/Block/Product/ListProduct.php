<?php

namespace DiamondMansion\Extensions\Override\Magento\Catalog\Block\Product;

class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    protected $_coreSession;

    /**
     * @param Context $context
     * @param PostHelper $postDataHelper
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Data $urlHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        array $data = [],
        \Magento\Framework\Session\SessionManagerInterface $coreSession
    ) {
        $this->_coreSession = $coreSession;

        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
    }

    public function getCurrentUrl() {
        return str_replace('\\', '/', $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]));
    }

    public function getCurrentPage() {
        return $this->getRequest()->getParam("p");
    }

    public function getViewedPage($url) {
        $viewedPages = $this->_coreSession->getviewedPages();
        if (isset($viewedPages[$url])) {
            $page = $viewedPages[$url] ?: 1;
        } else {
            $page = 1;
        }

        return $page;
    }

    public function setViewedPage($url, $page) {
        $this->excluedPageFromUrl($url, $page);
        $viewedPages = $this->_coreSession->getviewedPages();
        $viewedPages[$url] = $page;
        $this->_coreSession->setViewedPages($viewedPages);
    }

    public function getViewedItem($url) {
        $viewedItems = $this->_coreSession->getViewedItems();
        $viewedItemIdentifier = 0;
        if (isset($viewedItems[$url])) {
            $viewedItemIdentifier = $viewedItems[$url];
            unset($viewedItems[$url]);
            $this->_coreSession->setViewedItems($viewedItems);
        }

        return $viewedItemIdentifier;
    }

    public function excluedPageFromUrl(&$url, $page) {
        $url = str_replace('?p=' . $page, '', $url);
        $url = str_replace('&p=' . $page, '', $url);

        return $url;
    }
}