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
                if (isset($values["weight"])) {
                    $values["weight"][0] = floatval($values["weight"][0]);
                    $values["weight"][1] = floatval($values["weight"][1]);
                }
                if ($metalBaseWeight == 0) { $metalBaseWeight = $values["weight"][0]; }
                if ($metalBaseWeightTotal == 0) { $metalBaseWeightTotal = $values["weight"][1]; }
            }
        }

        $values = json_decode($allDmOptions['metal'][$params['metal']]->getValues(), true);        
        if (isset($values["weight"])) {
            $values["weight"][0] = floatval($values["weight"][0]);
            $values["weight"][1] = floatval($values["weight"][1]);
        }

        if (!isset($params['band']) || $params["band"] == "no-band") {
            if (strpos($params['metal'], "14k") !== false) {
                $metalWeight = ($values["weight"][0] == 0) ? ($metalBaseWeightTotal):$values["weight"][0];
            } else if (strpos($params['metal'], "18k") !== false) {
                $metalWeight = ($values["weight"][0] == 0) ? ($metalBaseWeightTotal * 1.16):$values["weight"][0];
            } else if (strpos($params['metal'], "platinum") !== false) {
                $metalWeight = ($values["weight"][0] == 0) ? ($metalBaseWeightTotal * 1.64):$values["weight"][0];
            }
            $metalFixedPrice = (isset($values["price"])) ? $values["price"][0] : 0.0;
        } else if ($params["band"] == "bridal-set") {
            if (strpos($params['metal'], "14k") !== false) {
                $metalWeight = ($values["weight"][1] == 0) ? ($metalBaseWeight):$values["weight"][1];
            } else if (strpos($params['metal'], "18k") !== false) {
                $metalWeight = ($values["weight"][1] == 0) ? ($metalBaseWeight * 1.16):$values["weight"][1];
            } else if (strpos($params['metal'], "platinum") !== false) {
                $metalWeight = ($values["weight"][1] == 0) ? ($metalBaseWeight * 1.64):$values["weight"][1];
            }
            $metalFixedPrice = (isset($values["price"])) ? $values["price"][1] : 0.0;
        }

        $metalPrice = ($metalFixedPrice > 0) ? $metalFixedPrice : (double)$this->_helper->getMetalPrice($this->config, $params["metal"]) * (double)$metalWeight;

        $others = isset($params['others']) ? explode(',', $params['others']) : [];
        
        $isFixedMainStonePrice = in_array('fixed-main-stone-price', $others);
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
        $isExcludeSideStonePrice = in_array('exclude-side-stone-price', $others);
        foreach ($params as $group => $param) {
            if (strpos($group, 'side-stone-shape') !== false) {
                $values = json_decode($allDmOptions[$group][$param]->getValues(), true);
                if ($params["band"] == "bridal-set") {
                    $sideStoneQty = (int)$values["qty"][0] + (int)$values["qty"][1];
                } else {
                    $sideStoneQty = (int)$values["qty"][0];
                }

                $sideIndex = str_replace('side-stone-shape-', '', $group);

                if (!isset($params["side-stone-color-clarity-" . $sideIndex])) {
                    $params["side-stone-color-clarity-" . $sideIndex] = "f-g/vs";
                }

                if (!$isExcludeSideStonePrice) {
                    $sideStonePriceCollecion = $this->_sideStonePriceModel->getCollection();
                    if (isset($params[$group])) {
                        $sideStonePriceCollecion->addFieldToFilter('shape', $params[$group]);
                    }
                    if (isset($params['side-stone-carat-' . $sideIndex])) {
                        $sideStonePriceCollecion->addFieldToFilter('carat', $params['side-stone-carat-' . $sideIndex]);
                    }
                    if (isset($params['side-stone-color-clarity-' . $sideIndex])) {
                        $sideStonePriceCollecion->addFieldToFilter('color_clarity', $params["side-stone-color-clarity-" . $sideIndex]);
                    }
                    if ($sideStonePriceCollecion->count()) {
                        $sideStonePrice = $sideStonePriceCollecion->getFirstItem()->getPrice();
                    } else {
                        $sideStonePrice = 0;
                    }
    
                    if ($sideStonePrice == 0 && $sideStoneQty > 0) {
                        return 0;
                    }

                    $sideStonePriceTotal += (double)$sideStonePrice * (double)$sideStoneQty;
                }    
            }
        }

        $appraised = 1;

        if (isset($params['main-stone-cut']) && $params['main-stone-cut'] == 'excellent') {
            $appraised = 1.1;             
        } else if (isset($params['main-stone-cut']) && $params['main-stone-cut'] == 'ideal-10') {
            $appraised = 1.2;
        }

        $total = round((
            parent::getPrice($product) + 
            $mainStonePrice + 
            $metalPrice + 
            $sideStonePriceTotal
        ) * $appraised, 2);
        return round($total / 10) * 10;
    }

    public function getFinalPrice($qty, $product) {
        return $this->getPrice($product);
    }
}