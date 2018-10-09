<?php
namespace DiamondMansion\Extensions\Model\Ring\Design\Product;

class Price extends \Magento\Catalog\Model\Product\Type\Price
{
    protected $_helper;
    protected $_mainStonePriceModel;
    protected $_sideStonePriceModel;

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
        \DiamondMansion\Extensions\Model\Ring\Design\Price\Stone $mainStonePriceModel,
        \DiamondMansion\Extensions\Model\Ring\Design\Price\Sidestone $sideStonePriceModel
    ) {
        $this->_helper = $helper;
        $this->_mainStonePriceModel = $mainStonePriceModel;
        $this->_sideStonePriceModel = $sideStonePriceModel;

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

        if (!isset($params['band']) || $params["band"] == "no-band") {
            if (strpos($params['metal'], "14k") !== false) {
                $metalWeight = ($values["weight"][0] == 0) ? ($metalBaseWeightTotal):$values["weight"][0];
            } else if (strpos($params['metal'], "18k") !== false) {
                $metalWeight = ($values["weight"][0] == 0) ? ($metalBaseWeightTotal * 1.16):$values["weight"][0];
            } else if (strpos($params['metal'], "platinum") !== false) {
                $metalWeight = ($values["weight"][0] == 0) ? ($metalBaseWeightTotal * 1.64):$values["weight"][0];
            }
            $metalFixedPrice = $values["price"][0];
        } else if ($params["band"] == "bridal-set") {
            if (strpos($params['metal'], "14k") !== false) {
                $metalWeight = ($values["weight"][1] == 0) ? ($metalBaseWeight):$values["weight"][1];
            } else if (strpos($params['metal'], "18k") !== false) {
                $metalWeight = ($values["weight"][1] == 0) ? ($metalBaseWeight * 1.16):$values["weight"][1];
            } else if (strpos($params['metal'], "platinum") !== false) {
                $metalWeight = ($values["weight"][1] == 0) ? ($metalBaseWeight * 1.64):$values["weight"][1];
            }
            $metalFixedPrice = $values["price"][1];
        }

        $metalPrice = ($metalFixedPrice > 0) ? $metalFixedPrice : $this->_helper->getMetalPrice($this->config, $params["metal"]) * $metalWeight;
        
        $isFixedMainStonePrice = isset($params['others']) && isset($params['others']['fixed-main-stone-price']) && $params['others']['fixed-main-stone-price'];
        if ($params["main-stone-type"] == "setting") {
            $mainStonePrice = $isFixedMainStonePrice ? 0 : 0.0000001;
        } else {
            $mainStonePrice = 0;

            if (!$isFixedMainStonePrice) {
                $mainStonePriceCollecion = $this->_mainStonePriceModel->getCollection();
                if (isset($params['main-stone-shape'])) {
                    $mainStonePriceCollecion->addFieldToFilter('shape', $params['main-stone-shape']);
                }
                if (isset($params['main-stone-carat'])) {
                    $mainStonePriceCollecion->addFieldToFilter('carat', $params['main-stone-carat']);
                }
                if (isset($params['main-stone-color'])) {
                    $mainStonePriceCollecion->addFieldToFilter('color', $params['main-stone-color']);
                }
                if (isset($params['main-stone-clarity'])) {
                    $mainStonePriceCollecion->addFieldToFilter('clarity', $params['main-stone-clarity']);
                }
                if ($mainStonePriceCollecion->count()) {
                    $mainStonePrice = $mainStonePriceCollecion->getFirstItem()->getPrice();
                }

                if ($mainStonePrice == 0) {
                    return 0;
                }
            }
        }

        $sideStonePriceTotal = 0;
        $isExcludeSideStonePrice = isset($params['others']) && isset($params['others']['exclude-side-stone-price']) && $params['others']['exclude-side-stone-price'];
        foreach ($params as $group => $param) {
            if (strpos($group, 'side-stone-shape') !== false) {
                $values = json_decode($allDmOptions[$group][$param]->getValues(), true);
                if ($params["band"] == "bridal-set") {
                    $sideStoneQty = $values["qty"][0] + $values["qty"][1];
                } else {
                    $sideStoneQty = $values["qty"][0];
                }

                $params["side-stone-color-clarity"] = "f-g/vs";

                if (!$isExcludeSideStonePrice) {
                    $sideStonePriceCollecion = $this->_sideStonePriceModel->getCollection();
                    if (isset($params['side-stone-shape'])) {
                        $sideStonePriceCollecion->addFieldToFilter('shape', $params['side-stone-shape']);
                    }
                    if (isset($params['side-stone-carat'])) {
                        $sideStonePriceCollecion->addFieldToFilter('carat', $params['side-stone-carat']);
                    }
                    if (isset($params['side-stone-color-clarity'])) {
                        $sideStonePriceCollecion->addFieldToFilter('color_clarity', $params['side-stone-color-clarity']);
                    }
                    if ($sideStonePriceCollecion->count()) {
                        $sideStonePrice = $sideStonePriceCollecion->getFirstItem()->getPrice();
                    } else {
                        $sideStonePrice = 0;
                    }
    
                    if ($sideStonePrice == 0 && $sideStoneQty > 0) {
                        return 0;
                    }

                    $sideStonePriceTotal += $sideStonePrice * $sideStoneQty;
                }    
            }
        }

        return round((
            parent::getPrice() + 
            $mainStonePrice + 
            $metalPrice + 
            $sideStonePriceTotal
        ), 2);
    }    
}