<?php

namespace DiamondMansion\Extensions\Block\Ring\Design\Product;

class Options extends \Magento\Catalog\Block\Product\View
{
    protected $_helper;
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \DiamondMansion\Extensions\Helper\Image $helper,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        $this->_helper = $helper;
        $this->_scopeConfig = $scopeConfig;

        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency
        );
    }

    public function getHelper() {
        return $this->_helper;
    }

    public function getOptions() {
        $params = $this->getRequest()->getParams();

        return $this->_helper->getRingDesignOptions($this->getProduct(), $params);
    }

    public function getMetalPrice($metal) {
        return (double)$this->_helper->getMetalPrice($this->_scopeConfig, $metal);
    }

    public function isRequestedOptions() {
        $params = $this->getRequest()->getParams();
        return isset($params['option'])?1:0;
    }
}