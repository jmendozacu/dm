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

use Magenest\InstagramShop\Model\Config\Source\MediaType;
use Magenest\InstagramShop\Model\Photo;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Registry;
use Magento\Widget\Block\BlockInterface;

/**
 * Class Slider
 * @package Magenest\InstagramShop\Block\Photo
 */
class Slider extends \Magento\Framework\View\Element\Template implements BlockInterface
{
    /**
     * @var int
     */
    protected $itemsPerSlide;
    /**
     * @var bool
     */
    protected $isDefaultTemplate = false;
    /**
     * @var \Magenest\InstagramShop\Model\PhotoFactory
     */
    protected $_photoFactory;
    /**
     * @var ProductFactory
     */
    protected $_productFactory;
    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * Slider constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magenest\InstagramShop\Model\PhotoFactory $photoFactory
     * @param ProductFactory $productFactory
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\InstagramShop\Model\PhotoFactory $photoFactory,
        ProductFactory $productFactory,
        Registry $registry,
        array $data = []
    )
    {
        $this->_registry       = $registry;
        $this->_productFactory = $productFactory;
        $this->_photoFactory   = $photoFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|Photo[]
     */
    public function getPhotos()
    {
        return $this->_photoFactory->create()
            ->getCollection()
            ->addFieldToFilter('show_in_widget', 1)//only visibility items are selected
            ->setOrder('position', 'DESC')
            ->setCurPage(1);
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->_registry->registry('current_product');
    }

    /**
     * @return int
     */
    public function getMediaType()
    {
        return (int)$this->_scopeConfig->getValue('magenest_instagram_shop/general/media_type');
    }

    /**
     * @return bool
     */
    public function canShowVideo()
    {
        return $this->getMediaType() === MediaType::BOTH_IMAGE_AND_VIDEO;
    }

    /**
     * @param $config
     * @param string $default
     * @return string as boolean on JS
     */
    public function getConfigSlider($config, $default = 'true')
    {
        if (is_null($config)) {
            return $default;
        }
        return intval($config) === 0 ? 'false' : 'true';
    }

    /**
     * @param null|string|int $config
     * @param int|string $default
     * @param bool $isString
     * @return int|string
     */
    public function getConfigSliderValue($config, $default, $isString = false)
    {
        if (is_null($config)) {
            return $default;
        }
        return $isString ? $config : intval($config);
    }

    /**
     * @return string
     */
    public function getViewFullGalleryTitle()
    {
        return $this->_scopeConfig->getValue('magenest_instagram_shop/general/button_title');
    }

    /**
     * @return string
     */
    public function getViewFullGalleryCss()
    {
        return $this->_scopeConfig->getValue('magenest_instagram_shop/general/button_css');
    }

    /**
     * @return string
     */
    public function getHoverText()
    {
        return $this->_scopeConfig->getValue('magenest_instagram_shop/general/hover_text');
    }

    /**
     * @return int
     */
    public function getItemsPerSlide()
    {
        return $this->itemsPerSlide;
    }

    /**
     * @param int $itemsPerSlide
     * @return $this
     */
    public function setItemsPerSlide($itemsPerSlide)
    {
        $this->itemsPerSlide = $itemsPerSlide;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDefaultTemplate()
    {
        return $this->isDefaultTemplate;
    }

    /**
     * @param bool $isDefaultTemplate
     * @return $this
     */
    public function setIsDefaultTemplate($isDefaultTemplate)
    {
        $this->isDefaultTemplate = $isDefaultTemplate;
        return $this;
    }
}
