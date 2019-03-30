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

namespace Magenest\InstagramShop\Block\Instagram\Renderer;

use Magenest\InstagramShop\Helper\Helper;
use Magenest\InstagramShop\Model\Config\Source\LinkedProductsLayout;
use Magenest\InstagramShop\Model\Photo;
use Magenest\InstagramShop\Model\TaggedPhoto;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;

class LinkedProducts extends Template
{
    protected $_template = 'instagram/renderer/linked-products.phtml';
    /**
     * @var Photo|TaggedPhoto
     */
    protected $photo;
    /**
     * @var Product[]
     */
    protected $productList = [];
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;
    /**
     * @var Helper
     */
    protected $helper;

    protected $additionalClass;

    /**
     * LinkedProducts constructor.
     * @param Template\Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param \Magento\Catalog\Helper\Image $image
     * @param Helper $helper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Helper\Image $image,
        Helper $helper,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->productRepository = $productRepository;
        $this->imageHelper       = $image;
        $this->helper            = $helper;
    }

    /**
     * @param Photo|TaggedPhoto $photo
     * @return $this
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
        if ($photo)
            $this->setProductList($photo->getExplodedProductId());
        return $this;
    }

    /**
     * @param $photoId
     * @param int $type
     * @return $this
     */
    public function setPhotoByIdAndType($photoId, $type = 1)
    {
        $photo = $this->helper->getPhoto($photoId, $type);
        if ($photo) {
            $this->photo = $photo;
            $this->setProductList($photo->getExplodedProductId());
        }
        return $this;
    }

    /**
     * @return Photo|TaggedPhoto
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $productIds
     * @return $this
     */
    private function setProductList($productIds)
    {
        if (is_array($productIds)) {
            foreach ($productIds as $productId) {
                try {
                    $this->productList[] = $this->productRepository->getById($productId);
                } catch (NoSuchEntityException $e) {
                    continue;
                }
            }
        }
        return $this;
    }

    /**
     * @return Product[]
     */
    public function getProductList()
    {
        return $this->productList;
    }

    /**
     * @param Product $product
     * @param string $imageId
     * @param int $width
     * @param int $height
     * @return \Magento\Catalog\Helper\Image
     */
    public function getProductImageUrl($product, $imageId = 'product_page_image_small', $width = 150, $height = 150)
    {
        return $this->imageHelper->init($product, $imageId)
            ->setImageFile($product->getFile())
            ->resize($width, $height);
    }

    /**
     * @return bool
     */
    public function isLayoutList()
    {
        return $this->getLinkedProductsLayout() == LinkedProductsLayout::LIST_VALUE;
    }

    /**
     * @return string
     */
    public function getLinkedProductsLayout()
    {
        return $this->_scopeConfig->getValue('magenest_instagram_shop/general/linked_products_layout');
    }

    /**
     * @return string
     */
    public function getAdditionalClass()
    {
        if (!$this->additionalClass) {
            $this->additionalClass = $this->isLayoutList() ? 'list-' : '';
        }
        return $this->additionalClass;
    }
}