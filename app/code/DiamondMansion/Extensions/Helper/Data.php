<?php

namespace DiamondMansion\Extensions\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_imageFactory;
    protected $_variable;
    protected $_productRepository;

    public function __construct(
        Context $context,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Variable\Model\Variable $variable,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    )
    {
        $this->_imageFactory = $imageFactory;
        $this->_variable = $variable;
        $this->_productRepository = $productRepository;

        parent::__construct($context);
    }

    public function getObjectManager()
    {
        return \Magento\Framework\App\ObjectManager::getInstance();
    }

    public function getStoreManager()
    {
        return $this->getObjectManager()->get('\Magento\Store\Model\StoreManagerInterface');
    }

    public function getBaseUrl()
    {
        return $this->getStoreManager()->getStore()->getBaseUrl();
    }

    public function getMediaDir()
    {
        return BP . DIRECTORY_SEPARATOR . 'pub' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR;
    }

    public function getMediaUrl()
    {
        return $this->getStoreManager()->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    public function getDesignerPhotoDir()
    {
        return BP . '/pub/media/diamondmansion/designer/';
    }

    public function getDesignerPhotoTmpDir()
    {
        return BP . '/pub/media/catalog/tmp/category/';
    }

    public function getDesignerPhotoUrl($photo)
    {
        return $this->getMediaUrl() . 'diamondmansion/designer/' . $photo;
    }

    public function getDesignRingStoneShapes()
    {
        return ["asscher", "cushion", "emerald", "heart", "marquise", "oval", "pear", "princess", "radiant", "round", "trilliant", "square cushion", "long cushion", "square radiant", "long radiant"];
    }

    public function getDesignRingStoneCarats()
    {
        return ["0.75", "1.00", "1.25", "1.50", "1.75", "2.00", "2.25", "2.50", "2.75", "3.00"];
    }

    public function getDesignRingStoneColors()
    {
        return ["d", "e", "f", "g", "h", "i", "j", "fancy light", "fancy yellow", "fancy intense", "fancy black", "d-e", "e-f", "f-g", "g-h", "i-j", "i+"];
    }

    public function getDesignRingStoneClarities()
    {
        return ["fl", "vvs1", "vvs2", "vs1", "vs2", "si1", "si2", "aaa", "vvs1-vvs2", "vs1-vs2", "si1-si2", "si1+"];
    }

    public function getDesignRingSidestoneShapes()
    {
        return ["round", "princess", "asscher", "emerald", "cushion", "radiant", "oval", "trillion", "pear", "marquise", "heart", "baguette", "trapezoid", "halfmoon", "bullet"];
    }

    public function getDesignRingSidestoneCarats()
    {
        $carats = [];
        for ($carat = 0.005; $carat < 0.046; $carat = $carat + 0.005) {
            $carats[] = $carat . "";
        }
        for ($carat = 0.05; $carat < 1.05; $carat = $carat + 0.05) {
            $carats[] = $carat . "";
        }

        return $carats;
    }

    public function getDesignRingSidestoneColorClarities()
    {
        return ["g-h/si", "f-g/vs"];
    }

    public function getEternityRingStoneShapes()
    {
        return ["round", "princess", "asscher", "emerald", "cushion", "radiant", "oval", "trillion", "pear", "marquise", "heart", "baguette", "trapezoid", "halfmoon", "bullet"];
    }

    public function getEternityRingStoneCarats()
    {
        return ["0.05", "0.1", "0.15", "0.2", "0.25", "0.33", "0.4", "0.5"];
    }

    public function getEternityRingStoneColorClarities()
    {
        return ["g-h/si", "f-g/vs"];
    }

    public function getEternityRingStoneWidth(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        $shape,
        $carat)
    {
        return $scopeConfig->getValue('dm/eternity/width/' . $shape . '/' . $carat);
    }

    public function getMetals()
    {
        return [
            '14k-white-gold' => '14k White Gold',
            '18k-white-gold' => '18k White Gold',
            '14k-yellow-gold' => '14k Yellow Gold',
            '18k-yellow-gold' => '18k Yellow Gold',
            '14k-rose-gold' => '14k Rose Gold',
            '18k-rose-gold' => '18k Rose Gold',
            '14k-tri-color-gold' => '14k Tri-Color Gold',
            '18k-tri-color-gold' => '18k Tri-Color Gold',
            '14k-two-tone-yellow-gold' => '14k Two-Tone Yellow Gold',
            '18k-two-tone-yellow-gold' => '18k Two-Tone Yellow Gold',
            '14k-two-tone-rose-gold' => '14k Two-Tone Rose Gold',
            '18k-two-tone-rose-gold' => '18k Two-Tone Rose Gold',
            'platinum' => 'Platinum',
            'platinum-two-tone-yellow-gold' => 'Platinum Two-Tone Yellow Gold',
            'platinum-two-tone-rose-gold' => 'Platinum Two-Tone Rose Gold',
        ];
    }

    public function getMetalPrice(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        $metal
    ) {
        return $scopeConfig->getValue('dm/metal/price/' . $this->getSlug($metal));
    }

    public function getSlug($value) {
        return strtolower(str_replace(" ", "-", $value));
    }

    public function getCustomValue($code, $type = 'plain') {
        return $this->_variable->loadByCode($code)->getValue($type);
    }

    public function getRingDesignOptions($product, $params) {
        if (isset($params["option"])) {
            $optionParams = explode("-", $params["option"]);
            $skus = str_split($optionParams[0]);
        } else {
            $currentUrl = $this->getStoreManager()->getStore()->getCurrentUrl(false);
            $urlPaths = explode('/', trim(str_replace($this->getStoreManager()->getStore()->getBaseUrl(), "", $currentUrl), '/'));
            $urlPath = current($urlPaths);

            foreach ($this->getDesignRingStoneShapes() as $shape) {
                if (strpos($urlPath, $this->getSlug($shape)) !== false) {
                    $mainStoneShape = $this->getSlug($shape);
                }
            }
        }

        $allOptions = $product->getAllDmOptions(true);
        $defaultOptions = [];
        if (isset($skus)) {
            reset($skus);
            $sku = current($skus);
            foreach ($allOptions as $group => $optionGroup) {
                if (isset($defaultOptions['main-stone-type']) && 
                    $defaultOptions['main-stone-type']->getCode() != 'setting' && 
                    strpos($group, 'setting-options') !== false) {
                    continue;
                }

                foreach ($optionGroup as $code => $option) {
                    if ($option->getCode() == 'si1' &&
                        isset($defaultOptions['main-stone-shape']) &&
                        ($defaultOptions['main-stone-shape']->getCode() == 'asscher' || $defaultOptions['main-stone-shape']->getCode() == 'emerald')) {
                        continue;
                    }
                    if ($option->getSlug() == $sku) {
                        $defaultOptions[$group] = $option;
                        break;
                    }
                }
                if (!isset($defaultOptions[$group])) {
                    $defaultOptions[$group] = current($optionGroup);
                    foreach ($optionGroup as $code => $option) {
                        if (isset($defaultOptions['main-stone-shape']) &&
                            ($defaultOptions['main-stone-shape']->getCode() == 'asscher' || $defaultOptions['main-stone-shape']->getCode() == 'emerald')) {
                            if ($option->getCode() == 'si1') {
                                continue;
                            } else if ($option->getCode() == 'vs1-vs2') {
                                $defaultOptions[$group] = $option;
                                break;    
                            }
                        } else if ($option->getIsDefault()) {
                            $defaultOptions[$group] = $option;
                            break;
                        }
                    }
                }

                $sku = next($skus);
            }
        } else {
            $defaultOptions = $product->getDefaultDmOptions(true);
            if (isset($mainStoneShape) && isset($defaultOptions['main-stone-shape'])) {
                $defaultOptions['main-stone-shape'] = $allOptions['main-stone-shape'][$mainStoneShape];
            }
        }

        return [
            'allOptions' => $allOptions,
            'defaultOptions' => $defaultOptions,
            "mainStone"=>array("main-stone-type" => 'Type', "main-stone-shape" => 'Shape', "main-stone-carat" => 'Carat', "main-stone-color" => 'Color', "main-stone-clarity" => 'Clarity', "main-stone-cert" => 'Cert', "main-stone-cut" => 'Cut'),
            "setting"=>array("metal", "band", "side-stone-color-clarity"),
            "sideStone"=>array("side-stone-shape", "side-stone-carat"),
            "settingSize"=>isset($optionParams[1])?explode("x", $optionParams[1]):[],
            "params" => $params,
        ];        
    }

    public function getRingEternityOptions($product, $params) {
        if (isset($params["option"])) {
            $skus = str_split($params["option"]);
        }

        $allOptions = $product->getAllDmOptions(true);
        $defaultOptions = [];
        if (isset($skus)) {
            reset($skus);
            $sku = current($skus);
            foreach ($allOptions as $group => $optionGroup) {
                foreach ($optionGroup as $code => $option) {
                    if ($option->getSlug() == $sku) {
                        $defaultOptions[$group] = $option;
                        break;
                    }
                }
                $sku = next($skus);
            }
        } else {
            $defaultOptions = $product->getDefaultDmOptions(true);
        }

        return [
            'allOptions' => $allOptions,
            'defaultOptions' => $defaultOptions,
            "stone"=>array("stone-type" => 'Type', "stone-shape" => 'Shape', "stone-carat" => 'Carat', "stone-color-clarity" => 'Quality'),
            "params" => $params,
        ];        
    }

    public function getWeddingBandDesignOptions($product, $params) {
        if (isset($params["option"])) {
            $skus = str_split($params["option"]);
        } else {
            $currentUrl = $this->getStoreManager()->getStore()->getCurrentUrl(false);
            $urlPaths = explode('/', trim(str_replace($this->getStoreManager()->getStore()->getBaseUrl(), "", $currentUrl), '/'));
            $urlPath = current($urlPaths);

            for ($w=3;$w<=10;$w++) {
                if (strpos($urlPath, $w . "mm-") !== false) {
                    $width = $w;
                    break;
                }
            }
        }

        $allOptions = $product->getAllDmOptions(true);
        $defaultOptions = [];
        if (isset($skus)) {
            reset($skus);
            $sku = current($skus);
            foreach ($allOptions as $group => $optionGroup) {
                foreach ($optionGroup as $code => $option) {
                    if ($option->getSlug() == $sku) {
                        $defaultOptions[$group] = $option;
                        break;
                    }
                }
                if (!isset($defaultOptions[$group])) {
                    $defaultOptions[$group] = current($optionGroup);
                    foreach ($optionGroup as $code => $option) {
                        if ($option->getIsDefault()) {
                            $defaultOptions[$group] = $option;
                            break;
                        }
                    }
                }

                $sku = next($skus);
            }
        } else {
            $defaultOptions = $product->getDefaultDmOptions(true);
            if (isset($width) && isset($defaultOptions['width'])) {
                $defaultOptions['width'] = $allOptions['width'][$width . ""];
            }
        }

        return [
            'allOptions' => $allOptions,
            'defaultOptions' => $defaultOptions,
            "params" => $params,
            'groupLabels' => [
                'metal' => 'Metal',
                'width' => 'Width',
                'ring-size' => 'Ring Size',
                'finish' => 'Special Finishes'
            ]
        ];        
    }
}