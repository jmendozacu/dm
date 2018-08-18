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
}