<?php

namespace DiamondMansion\Extensions\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function getObjectManager()
    {
        return \Magento\Framework\App\ObjectManager::getInstance();
    }

    public function getStoreManager()
    {
        return $this->getObjectManager()->get('\Magento\Store\Model\StoreManagerInterface');
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
        return $this->getStoreManager()->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'diamondmansion/designer/' . $photo;
    }

    public function getDesignRingStoneShapes()
    {
        return ["asscher", "cushion", "emerald", "heart", "marquise", "oval", "pear", "princess", "radiant", "round", "trilliant"];
    }

    public function getDesignRingStoneCarats()
    {
        return ["0.75", "1.00", "1.25", "1.50", "1.75", "2.00", "2.25", "2.50", "2.75", "3.00"];
    }

    public function getDesignRingStoneColors()
    {
        return ["d", "e", "f", "g", "h", "i", "j", "fancy light", "fancy yellow", "fancy intense", "fancy black"];
    }

    public function getDesignRingStoneClarities()
    {
        return ["fl", "vvs1", "vvs2", "vs1", "vs2", "si1", "si2", "aaa"];
    }

    public function getDesignRingSidestoneShapes()
    {
        return ["round", "princess", "asscher", "emerald", "cushion", "radiant", "oval", "trillion", "pear", "marquise", "heart", "baguette", "trapezoid", "halfmoon", "bullet"];
    }

    public function getDesignRingSidestoneCarats()
    {
        $carats = [];
        for ($carat = 0.005; $carat < 0.046; $carat = $carat + 0.005 ) {
            $carats[] = $carat."";
        }
        for ($carat = 0.05; $carat < 1.05; $carat = $carat + 0.05 ) {
            $carats[] = $carat."";
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
        $carat) {
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
}