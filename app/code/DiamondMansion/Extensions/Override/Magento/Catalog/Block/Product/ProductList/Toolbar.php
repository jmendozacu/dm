<?php

namespace DiamondMansion\Extensions\Override\Magento\Catalog\Block\Product\ProductList;

class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar
{
    protected $_coreSession;

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
        \Magento\Framework\Session\SessionManagerInterface $coreSession
    ) {
        $this->_coreSession = $coreSession;

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
	        $url = $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);

	        $viewedPages = $this->_coreSession->getViewedPages();

	        if (isset($viewedPages[$url]) && $viewedPages[$url] > 1) {
	            return $this->getCollection()->getPageSize() * ($viewedPages[$url]);
	        }
        }

        return parent::getLimit();
	}
}