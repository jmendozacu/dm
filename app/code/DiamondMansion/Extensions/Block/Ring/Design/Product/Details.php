<?php

namespace DiamondMansion\Extensions\Block\Ring\Design\Product;

class Details extends \Magento\Catalog\Block\Product\View
{
    protected $_helper;
    protected $_scopeConfig;
    protected $_designerFactory;
    protected $_eavConfig;
    protected $_storeInfo;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \DiamondMansion\Extensions\Helper\Image $helper,
        \DiamondMansion\Extensions\Model\DesignerFactory $designerFactory,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Store\Model\Information $storeInfo,
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
        $this->_designerFactory = $designerFactory;
        $this->_eavConfig = $eavConfig;
        $this->_storeInfo = $storeInfo;

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

    public function getDesigner() {
        $designerAttributeValues = explode(',', $this->getProduct()->getDmDesigner());

        if (count($designerAttributeValues) == 0) {
            return false;
        }

        $designerName = "";
        $eavAttribute = $this->_eavConfig->getAttribute('catalog_product', 'dm_designer');
        foreach ($eavAttribute->getSource()->getAllOptions() as $eavOption) {
            if ($eavOption['value'] == $designerAttributeValues[0]) {
                $designerName = $eavOption['label'];
                break;
            }
        }

        if (empty($designerName)) {
            return false;
        }

        $designer = $this->_designerFactory->create()->getCollection()->addFieldToFilter('name', $designerName)->getFirstItem();

        return $designer;
    }

    public function getPhoneNumber() {
        return $this->_storeInfo->getStoreInformationObject($this->_helper->getStoreManager()->getStore())->getPhone();
    }
}