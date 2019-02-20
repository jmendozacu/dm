<?php

namespace DiamondMansion\Extensions\Override\Magento\Catalog\Block\Product\ProductList;

class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar
{
    protected $_coreSession;
    protected $_urlInterface;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Session $catalogSession
     * @param \Magento\Catalog\Model\Config $catalogConfig
     * @param ToolbarModel $toolbarModel
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param ProductList $productListHelper
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Catalog\Model\Config $catalogConfig,
        \Magento\Catalog\Model\Product\ProductList\Toolbar $toolbarModel,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Catalog\Helper\Product\ProductList $productListHelper,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        array $data = [],
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Framework\UrlInterface $urlInterface
    ) {
        $this->_coreSession = $coreSession;
        $this->_urlInterface = $urlInterface;

        parent::__construct($context, $catalogSession, $catalogConfig, $toolbarModel, $urlEncoder, $productListHelper, $postDataHelper, $data);
    }

	public function setCollection($collection) {
		parent::setCollection($collection);

		if ($this->getCurrentOrder()) {
			$this->_collection->setOrder("created_at", "desc");
        }

		return $this;
	}

	public function getLimit() {
        $page = $this->getRequest()->getParam('p');

        if (!$page) {
            $url = $this->_urlInterface->getCurrentUrl();
            $viewedItems = $this->_coreSession->getViewedItems();

            if (isset($viewedItems[$url])) {
                $url = rtrim($url, '/');

                $viewedPages = $this->_coreSession->getViewedPages();

                if (isset($viewedPages[$url]) && $viewedPages[$url] > 1) {
                    return parent::getLimit() * ($viewedPages[$url]);
                }
            }
        }

        return parent::getLimit();;
	}
}