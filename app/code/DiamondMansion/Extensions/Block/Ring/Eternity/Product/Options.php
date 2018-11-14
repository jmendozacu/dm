<?php

namespace DiamondMansion\Extensions\Block\Ring\Eternity\Product;

class Options extends \Magento\Catalog\Block\Product\View
{
    protected $_helper;
    protected $_scopeConfig;
    protected $_storePriceFactory;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \DiamondMansion\Extensions\Model\Ring\Eternity\Price\StoneFactory $stonePriceFactory,
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
        $this->_storePriceFactory = $stonePriceFactory;

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

        return $this->_helper->getRingEternityOptions($this->getProduct(), $params);
    }

    public function getStonePrices() {
        $result = [];
        $collection = $this->_storePriceFactory->create()->getCollection();
        foreach ($collection as $item) {
            $key = [
                $item->getShape(),
                (double)$item->getCarat(),
                $item->getColorClarity()
            ];
            
            $result[implode("-", $key)] = $item->getPrice();
        }

        return $result;
    }

    public function getMetalPrice($metal) {
        return (double)$this->_helper->getMetalPrice($this->_scopeConfig, $metal);
    }

    public function getStoneWidth($shape, $carat) {
        return (double)$this->_helper->getEternityRingStoneWidth($this->_scopeConfig, $shape, $carat);
    }

    public function isRequestedOptions() {
        $params = $this->getRequest()->getParams();
        return isset($params['option'])?1:0;
    }
}