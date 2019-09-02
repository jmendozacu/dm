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

use Magenest\InstagramShop\Helper\Helper;
use Magenest\InstagramShop\Model\Config\Source\MediaType;
use Magenest\InstagramShop\Model\Photo;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Exception\LocalizedException;
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
     * @var Helper
     */
    protected $helper;

    /**
     * Slider constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magenest\InstagramShop\Model\PhotoFactory $photoFactory
     * @param ProductFactory $productFactory
     * @param Registry $registry
     * @param Helper $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\InstagramShop\Model\PhotoFactory $photoFactory,
        ProductFactory $productFactory,
        Registry $registry,
        Helper $helper,
        array $data = []
    ) {
        $this->_registry       = $registry;
        $this->_productFactory = $productFactory;
        $this->_photoFactory   = $photoFactory;
        $this->helper          = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|Photo[]
     */
    public function getPhotos()
    {
        $collection = $this->_photoFactory->create()
            ->getCollection()
            ->addFieldToFilter('show_in_widget', 1)//only visibility items are selected
            ->setOrder('position', 'DESC')
            ->setCurPage(1);
        $totalPages = empty($this->getTotalPages()) ? 0 : intval($this->getTotalPages() * 1);
        if ($totalPages)
            $collection->getSelect()->limit($this->getItemsPerSlide() * $totalPages);

        return $collection;
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
        return $this->helper->getMediaType();
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
    public function getBaseGalleryUrl()
    {
        return $this->helper->getBaseGalleryUrl();
    }

    /**
     * @return string
     */
    public function getViewFullGalleryTitle()
    {
        return $this->helper->getViewFullGalleryTitle();
    }

    /**
     * @return string
     */
    public function getViewFullGalleryCss()
    {
        return $this->helper->getViewFullGalleryCss();
    }

    /**
     * @return string
     */
    public function getHoverText()
    {
        return $this->helper->getHoverText();
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

    /**
     * @return mixed
     */
    public function getSliderHtml()
    {
        try {
            return $this->getLayout()->createBlock(\Magenest\InstagramShop\Block\Photo\Renderer::class)->setSliderBlockName($this->getNameInLayout())->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    /**
     * @param $photo
     * @return string
     */
    public function getLinkedProductsHtml($photo)
    {
        try {
            return $this->getLayout()->createBlock(\Magenest\InstagramShop\Block\Instagram\Renderer\LinkedProducts::class)->setPhoto($photo)->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    /**
     * @param $photo
     * @return string
     */
    public function getHotspotHtml($photo)
    {
        try {
            return $this->getLayout()->createBlock(\Magenest\InstagramShop\Block\Instagram\Renderer\Hotspot::class)->setPhoto($photo)->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    /**
     * @param $photo
     * @return string
     */
    public function getVideoHtml($photo)
    {
        try {
            return $this->getLayout()->createBlock(\Magenest\InstagramShop\Block\Instagram\Renderer\Video::class)->setPhoto($photo)->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }
}
