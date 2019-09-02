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

namespace Magenest\InstagramShop\Observer;

use Magenest\InstagramShop\Model\Hotspot;
use Magenest\InstagramShop\Model\PhotoFactory;
use Magenest\InstagramShop\Ui\DataProvider\Product\Form\Modifier\InstagramPhotos;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class AddPhotosToProduct
 * @package Magenest\InstagramShop\Observer
 */
class AddPhotosToProduct implements ObserverInterface
{
    /**
     * @var array
     */
    protected $_productIds = [];
    /**
     * @var PhotoFactory
     */
    protected $photoFactory;
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * AddPhotosToProduct constructor.
     * @param PhotoFactory $photoFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     */
    public function __construct(
        PhotoFactory $photoFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory
    )
    {
        $this->photoFactory    = $photoFactory;
        $this->_productFactory = $productFactory;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        /** @var Hotspot $hotspot */
        $hotspot     = $observer->getEvent()->getHotspot();
        $hotspotData = $hotspot->getHotspotData();
        $photo       = $this->photoFactory->create()->loadByPhotoId($hotspot->getPhotoId());
        $photoId     = $photo->getId();
        if (is_array($hotspotData)) {
            foreach ($hotspotData as $hotspotDatum) {
                if (isset($hotspotDatum['sku']) && $hotspotDatum['sku']) {
                    try {
                        /** @var \Magento\Catalog\Model\Product $productFactory */
                        $productFactory = $this->_productFactory->create();
                        $product        = $productFactory->load($productFactory->getResource()->getIdBySku($hotspotDatum['sku']));
                        $this->addToProductIds($product->getId());
                        $hasDataChange    = false;
                        $instagramProduct = $product->getCustomAttribute(InstagramPhotos::INSTAGRAM_PHOTOS_ATTRIBUTE_CODE);
                        if ($instagramProduct && ($instagramProductData = $instagramProduct->getValue()) !== '') {
                            $ids = explode(', ', $instagramProductData);
                            if (!in_array($photoId, array_values($ids))) {
                                $ids[]         = $photoId;
                                $hasDataChange = true;
                            }
                        } else {
                            $ids           = [$photoId];
                            $hasDataChange = true;
                        }

                        if ($hasDataChange) {
                            $product->setCustomAttribute(InstagramPhotos::INSTAGRAM_PHOTOS_ATTRIBUTE_CODE, implode(', ', $ids));
                            $product->save();
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
            $photo->setExplodedProductIds($this->_productIds)->save();
        }
    }

    /**
     * @param $productId
     */
    private function addToProductIds($productId)
    {
        if (!isset($this->_productIds[$productId])) {
            $this->_productIds[] = $productId;
        }
    }
}