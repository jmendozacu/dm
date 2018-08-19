<?php

namespace DiamondMansion\Extensions\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
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
}