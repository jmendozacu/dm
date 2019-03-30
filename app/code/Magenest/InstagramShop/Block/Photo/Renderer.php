<?php
/**
 *
 * Copyright © 2018 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_InstagramShop extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_InstagramShop
 * @author    dangnh@magenest.com
 */

namespace Magenest\InstagramShop\Block\Photo;


use Magento\Framework\Exception\LocalizedException;

/**
 * Class Renderer
 * @package Magenest\InstagramShop\Block\Photo
 */
class Renderer extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'slider/slider.phtml';
    /**
     * @var string
     */
    protected $sliderBlockName;

    /**
     * @return Slider|bool
     */
    public function getSliderBlock()
    {
        try {
            $allBlocks = $this->getLayout()->getAllBlocks();
            return isset($allBlocks[$this->getSliderBlockName()]) ? $allBlocks[$this->getSliderBlockName()] : false;
        } catch (LocalizedException $e) {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getSliderBlockName()
    {
        return $this->sliderBlockName;
    }

    /**
     * @param string $sliderBlockName
     * @return Renderer
     */
    public function setSliderBlockName($sliderBlockName)
    {
        $this->sliderBlockName = $sliderBlockName;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemsPerSlideClass()
    {
        if ($sliderBlock = $this->getSliderBlock()) {
            $itemsPerSlide = $sliderBlock->getItemsPerSlide();
            switch ($itemsPerSlide) {
                case 9:
                    return 'nine';
                case 12:
                    return 'twelve';
            }
        }
        return '';
    }
}
