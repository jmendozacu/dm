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

namespace Magenest\InstagramShop\Block\Adminhtml\Hotspot\Edit\Form;

use Magenest\InstagramShop\Model\Hotspot;
use Magenest\InstagramShop\Model\Photo;
use Magenest\InstagramShop\Model\TaggedPhoto;

class View extends \Magento\Backend\Block\Template
{
    protected $_template = 'hotspot/edit/form/hotspot.phtml';

    /**
     * @var Photo
     */
    protected $photoModel;
    /**
     * @var Hotspot
     */
    protected $currentHotspot;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magenest\InstagramShop\Helper\Helper
     */
    protected $_helper;

    /**
     * View constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magenest\InstagramShop\Helper\Helper $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magenest\InstagramShop\Helper\Helper $helper,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->_registry = $registry;
        $this->_helper   = $helper;

        $this->currentHotspot = $this->_registry->registry('current_hotspot');
    }

    /**
     * @return Hotspot
     */
    public function getCurrentHotspot()
    {
        return $this->currentHotspot;
    }

    /**
     * @return Photo|TaggedPhoto
     */
    public function getPhotoModel()
    {
        if ($this->photoModel === null) {
            $this->photoModel = $this->_helper->getPhoto($this->getCurrentHotspot()->getPhotoId(), $this->getCurrentHotspot()->getType());
        }
        return $this->photoModel;
    }

    /**
     * @param string $image
     * @return array
     */
    public function getImageSize($image)
    {
        try {
            list($width, $height) = getimagesize($image);
        } catch (\Exception $e) {
            list($width, $height) = [640, 640];
        }
        return [$width, $height];
    }

    /**
     * @param $number
     * @return string
     */
    public function getHotspotStyle($number)
    {
        $getX = sprintf('getHotspot%dX', $number);
        $getY = sprintf('getHotspot%dY', $number);
        $x    = $this->getCurrentHotspot()->{$getX}();
        $y    = $this->getCurrentHotspot()->{$getY}();
        return $x && $y ? 'position: absolute; left: ' . $x . '; top: ' . $y : '';
    }

    /**
     * @return array
     */
    public function getKeyOrder()
    {
        return [
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five'
        ];
    }
}