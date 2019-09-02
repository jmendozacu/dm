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

namespace Magenest\InstagramShop\Block\Product;

use Magenest\InstagramShop\Helper\Helper;
use Magenest\InstagramShop\Model\PhotoFactory;
use Magenest\InstagramShop\Ui\DataProvider\Product\Form\Modifier\InstagramPhotos;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

/**
 * Class Photo
 * @package Magenest\InstagramShop\Block\Product
 */
class Photo extends Template
{
    /**
     * @var ProductFactory
     */
    protected $_productFactory;

    /**
     * @var PhotoFactory
     */
    protected $_photoFactory;

    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * @var Helper
     */
    protected $_helper;

    /**
     * Photo constructor.
     * @param Template\Context $context
     * @param PhotoFactory $photoFactory
     * @param ProductFactory $productFactory
     * @param Registry $registry
     * @param Helper $helper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        PhotoFactory $photoFactory,
        ProductFactory $productFactory,
        Registry $registry,
        Helper $helper,
        array $data = [])
    {
        $this->_photoFactory   = $photoFactory;
        $this->_productFactory = $productFactory;
        $this->_registry       = $registry;
        $this->_helper=$helper;
        parent::__construct($context, $data);
    }

    /**
     * @param $productId
     * @return \Magenest\InstagramShop\Model\Photo[]
     */
    public function getPhotosByProduct($productId = '')
    {
        if ($productId == '')
            $productId = $this->getProduct()->getId();
        $ids    = $this->_productFactory->create()->load($productId)->getData(InstagramPhotos::INSTAGRAM_PHOTOS_ATTRIBUTE_CODE);
        $result = [];
        if ($ids == '')
            return $result;
        foreach (explode(', ', $ids) as $id) {
            $photo = $this->_photoFactory->create()->load($id);
            if ($photo->getId())
                $result[] = $photo;
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getCurrentProductUrl()
    {
        $currentProduct = $this->getProduct();
        return $currentProduct->getUrlModel()->getUrl($currentProduct);
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->_registry->registry('current_product');
    }

    /**
     * @return bool
     */
    public function canShowVideo()
    {
        return $this->_helper->canShowVideo();
    }

    /**
     * @return boolean
     */
    public function isAddInstagramToProduct()
    {
        return $this->_helper->isAddInstagramToProduct();
    }

    /**
     * @return string
     */
    public function getViewFullGalleryTitle()
    {
        return $this->_helper->getViewFullGalleryTitle();
    }

    /**
     * @return string
     */
    public function getViewFullGalleryCss()
    {
        return $this->_helper->getViewFullGalleryCss();
    }

    /**
     * @return string
     */
    public function getBlockTitle()
    {
        return $this->_helper->getBlockTitle();
    }

    /**
     * @return string
     */
    public function getBlockContent()
    {
        return $this->_helper->getBlockContent();
    }

    /**
     * @return string
     */
    public function getHashTag()
    {
        return $this->_helper->getHashTag();
    }
}