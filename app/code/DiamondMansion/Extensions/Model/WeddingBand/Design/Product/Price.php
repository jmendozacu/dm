<?php
namespace DiamondMansion\Extensions\Model\WeddingBand\Design\Product;

class Price extends \Magento\Catalog\Model\Product\Type\Price
{
    protected $_helper;

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
        \DiamondMansion\Extensions\Helper\Data $helper
    ) {
        $this->_helper = $helper;

        parent::__construct($ruleFactory, $storeManager, $localeDate, $customerSession, $eventManager, $priceCurrency, $groupManagement, $tierPriceFactory, $config, $tierPriceExtensionFactory);
    }

    public function getPrice($product) {
        $allDmOptions = $product->getAllDmOptions();
        $params = $product->getDmOptions();

        $price = 0;

        if (isset($params['width'])) {
            $values = json_decode($allDmOptions['width'][$params['width']]->getValues(), true);
            $price = (isset($values["price"])) ? floatval($values["price"]) : 0;                
        }

        if ((isset($params['ring-size'])) && floor($params['ring-size']) > 7) {
            $price = $price * (10 + floor($params['ring-size']) - 7) / 10;
        }

        if (isset($params['metal']) && strpos($params['metal'], "18k") !== false) {
            $price = $price * 1.5;
        } else if (isset($params['metal']) && strpos($params['metal'], "platinum") !== false) {
            $price = $price * 2;
        }

        if (isset($params['finish']) && $params['finish'] != 'no-finish') {
            $price += 60;
        }

        $total = round((
            $price
        ), 2);

        return round($total / 10) * 10;
    }

    public function getFinalPrice($qty, $product) {
        return $this->getPrice($product);
    }
}