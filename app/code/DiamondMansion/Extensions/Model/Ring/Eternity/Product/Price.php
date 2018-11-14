<?php
namespace DiamondMansion\Extensions\Model\Ring\Eternity\Product;

class Price extends \Magento\Catalog\Model\Product\Type\Price
{
    protected $_helper;
    protected $_stonePriceModel;

    public function __construct(
        \Magento\CatalogRule\Model\ResourceModel\RuleFactory $ruleFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Customer\Api\GroupManagementInterface $groupManagement,
        \Magento\Catalog\Api\Data\ProductTierPriceInterfaceFactory $tierPriceFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Catalog\Api\Data\ProductTierPriceExtensionFactory $tierPriceExtensionFactory = null,
        \DiamondMansion\Extensions\Helper\Data $helper,
        \DiamondMansion\Extensions\Model\Ring\Eternity\Price\Stone $stonePriceModel
    ) {
        $this->_helper = $helper;
        $this->_stonePriceModel = $stonePriceModel;

        parent::__construct($ruleFactory, $storeManager, $localeDate, $customerSession, $eventManager, $priceCurrency, $groupManagement, $tierPriceFactory, $config, $tierPriceExtensionFactory);
    }

    public function getPrice($product) {
        $allDmOptions = $product->getAllDmOptions();
        $params = $product->getDmOptions();

        $metalWeight = 0;
        $metalFixedPrice = 0;

        $metalBaseWeight = 0;
        $metalBaseWeightTotal = 0;
        foreach ($allDmOptions['metal'] as $option) {
            if (strpos($option->getCode(), '14k') !== false) {
                $values = json_decode($option->getValues(), true);
                if ($metalBaseWeight == 0) { $metalBaseWeight = $values["weight"][0]; }
                if ($metalBaseWeightTotal == 0) { $metalBaseWeightTotal = $values["weight"][1]; }
            }
        }

        $values = json_decode($allDmOptions['metal'][$params['metal']]->getValues(), true);        

        if (strpos($params['metal'], "14k") !== false) {
            $metalWeight = ($values["weight"][0] == 0) ? ($metalBaseWeightTotal):$values["weight"][0];
        } else if (strpos($params['metal'], "18k") !== false) {
            $metalWeight = ($values["weight"][0] == 0) ? ($metalBaseWeightTotal * 1.16):$values["weight"][0];
        } else if (strpos($params['metal'], "platinum") !== false) {
            $metalWeight = ($values["weight"][0] == 0) ? ($metalBaseWeightTotal * 1.64):$values["weight"][0];
        }
        $metalFixedPrice = $values["price"][0];

        $metalPrice = ($metalFixedPrice > 0) ? $metalFixedPrice : (double)$this->_helper->getMetalPrice($this->config, $params["metal"]) * (double)$metalWeight;

        $stonePriceCollecion = $this->_stonePriceModel->getCollection();
        if (isset($params['stone-shape'])) {
            $stonePriceCollecion->addFieldToFilter('shape', $params['stone-shape']);
        }
        if (isset($params['stone-carat'])) {
            $stonePriceCollecion->addFieldToFilter('carat', $params['stone-carat']);
        }
        if (isset($params['stone-color-clarity'])) {
            $stonePriceCollecion->addFieldToFilter('color_clarity', $params['stone-color-clarity']);
        }
        if ($stonePriceCollecion->count()) {
            $stonePrice = $stonePriceCollecion->getFirstItem()->getPrice();
        }

        if ($stonePrice == 0) {
            return 0;
        }

        if (isset($params["stone-shape"]) && isset($params["stone-carat"]) && isset($params["ring-size"])) {
            $values = json_decode($allDmOptions["stone-shape"][$params["stone-shape"]]->getValues(), true);
            
            $amount = 0;
            if ($values["amount"]) {
                $amount = $values["amount"][$params["stone-carat"] . "-" . $params["ring-size"]];
            }

            if ($amount == 0) {
                return 0;
            }

            $stonePrice *= $amount;
        }

        $total = round((
            parent::getPrice($product) + 
            $stonePrice + 
            $metalPrice
        ), 2);

        if (!$product->getExcludeOrderType() && isset($params['order-type'])) {
            if ($params['order-type'] == '10%-deposit') {
                $total = round($total / 10, 2);
            } else if ($params['order-type'] == 'home-try-on') {
                $total = 0;
            }
        }
        
        return $total; 
    }
}