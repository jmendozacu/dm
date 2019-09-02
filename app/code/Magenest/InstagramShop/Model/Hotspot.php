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

namespace Magenest\InstagramShop\Model;

use Magenest\InstagramShop\Api\Data\HotspotInterface;
use Magenest\InstagramShop\Helper\Helper;
use Magento\Framework\DataObject\IdentityInterface;

class Hotspot extends \Magento\Framework\Model\AbstractModel implements HotspotInterface, IdentityInterface
{
    const CACHE_TAG = 'magenest_instagramshop_hotspot';
    const KEY       = 'hotspot';

    protected $_eventPrefix = 'magenest_instagramshop_hotspot';

    protected $_eventObject = 'hotspot';

    protected $photoFactory;

    protected $productFactory;

    /**
     * Hotspot constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param PhotoFactory $photoFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        PhotoFactory $photoFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->photoFactory   = $photoFactory;
        $this->productFactory = $productFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Prepare deleting photo product ids and linked products
     *
     * @return \Magento\Framework\Model\AbstractModel
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeDelete()
    {
        $photo = $this->photoFactory->create()->loadByPhotoId($this->getPhotoId());
        if ($photo && $photo->getProductIds()) {
            $productIds = $photo->getExplodedProductId();
            foreach ($productIds as $productId) {
                $product = $this->productFactory->create()->load($productId);
                $product->setHasDataChanges(false);
                if ($product->getCustomAttribute(Helper::INSTAGRAM_PHOTOS_ATTRIBUTE_CODE) &&
                    ($photoIds = $product->getCustomAttribute(Helper::INSTAGRAM_PHOTOS_ATTRIBUTE_CODE)->getValue())) {
                    $photoIds = explode(', ', $photoIds);
                    foreach ($photoIds as $key => $photoId) {
                        if (in_array($photo->getId(), $photoIds)) {
                            unset($photoIds[$key]);
                            $product->setHasDataChanges(true);
                        }
                    }
                    if ($product->hasDataChanges()) {
                        $product->setCustomAttribute(Helper::INSTAGRAM_PHOTOS_ATTRIBUTE_CODE, implode(', ', $photoIds));
                        $product->save();
                    }
                }
            }
            $photo->setProductId(null)->save();
        }

        return parent::beforeDelete();
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Magenest\InstagramShop\Model\ResourceModel\Hotspot::class);
    }

    /**
     * @param $photoId
     * @param $type
     * @return $this
     */
    public function loadByPhotoIdAndType($photoId, $type)
    {
        $this->getResource()->loadByPhotoIdAndType($this, $photoId, $type);
        return $this;
    }

    /**
     * @return array
     */
    public function getHotspotData()
    {
        $result = [];
        for ($i = 1; $i <= 5; $i++) {
            $funcX   = sprintf('getHotspot%sX', $i);
            $funcY   = sprintf('getHotspot%sY', $i);
            $funcSku = sprintf('getHotspot%sSku', $i);
            $x       = $this->{$funcX}();
            $y       = $this->{$funcY}();
            $sku     = $this->{$funcSku}();
            if (($sku && $x && $y) || ($sku && !$x && !$y))
                $result[$i] = [
                    'x'   => $this->{$funcX}(),
                    'y'   => $this->{$funcY}(),
                    'sku' => $this->{$funcSku}(),
                ];
        }
        return $result;
    }

    /**
     * Get hotspot_id
     * @return string
     */
    public function getHotspotId()
    {
        return $this->getData(self::HOTSPOT_ID);
    }

    /**
     * Set hotspot_id
     * @param string $hotspotId
     * @return $this
     */
    public function setHotspotId($hotspotId)
    {
        $this->setData(self::HOTSPOT_ID, $hotspotId);
        return $this;
    }

    /**
     * Get photo_id
     * @return string
     */
    public function getPhotoId()
    {
        return $this->getData(self::PHOTO_ID);
    }

    /**
     * Set photo_id
     * @param string $photoId
     * @return $this
     */
    public function setPhotoId($photoId)
    {
        $this->setData(self::PHOTO_ID, $photoId);
        return $this;
    }

    /**
     * @param $sku
     * @param null $x
     * @param null $y
     * @return $this
     */
    public function setHotspot1($sku, $x = null, $y = null)
    {
        $this->setHotspot1Sku($sku)
            ->setHotspot1X($x)
            ->setHotspot1Y($y);
        return $this;
    }

    /**
     * @param $sku
     * @param null $x
     * @param null $y
     * @return $this
     */
    public function setHotspot2($sku, $x = null, $y = null)
    {
        $this->setHotspot2Sku($sku)
            ->setHotspot2X($x)
            ->setHotspot2Y($y);
        return $this;
    }

    /**
     * @param $sku
     * @param null $x
     * @param null $y
     * @return $this
     */
    public function setHotspot3($sku, $x = null, $y = null)
    {
        $this->setHotspot3Sku($sku)
            ->setHotspot3X($x)
            ->setHotspot3Y($y);
        return $this;
    }

    /**
     * @param $sku
     * @param null $x
     * @param null $y
     * @return $this
     */
    public function setHotspot4($sku, $x = null, $y = null)
    {
        $this->setHotspot4Sku($sku)
            ->setHotspot4X($x)
            ->setHotspot4Y($y);
        return $this;
    }

    /**
     * @param $sku
     * @param null $x
     * @param null $y
     * @return $this
     */
    public function setHotspot5($sku, $x = null, $y = null)
    {
        $this->setHotspot5Sku($sku)
            ->setHotspot5X($x)
            ->setHotspot5Y($y);
        return $this;
    }

    /**
     * @param $number
     * @param $key
     * @return string
     */
    public function getGetterFunctionName($number, $key)
    {
        return sprintf('getHotspot%d%s', $number, ucfirst($key));
    }

    /**
     * @param $number
     * @param $key
     * @return string
     */
    public function getSetterFunctionName($number, $key)
    {
        return sprintf('setHotspot%d%s', $number, ucfirst($key));
    }

    /**
     * Get hotspot1_sku
     * @return string
     */
    public function getHotspot1Sku()
    {
        return $this->getData(self::HOTSPOT1_SKU);
    }

    /**
     * Set hotspot1_sku
     * @param string $hotspot1Sku
     * @return $this
     */
    public function setHotspot1Sku($hotspot1Sku)
    {
        $this->setData(self::HOTSPOT1_SKU, $hotspot1Sku);
        return $this;
    }

    /**
     * Get hotspot1_x
     * @return string
     */
    public function getHotspot1X()
    {
        return $this->getData(self::HOTSPOT1_X);
    }

    /**
     * Set hotspot1_x
     * @param string $hotspot1X
     * @return $this
     */
    public function setHotspot1X($hotspot1X)
    {
        $this->setData(self::HOTSPOT1_X, $hotspot1X);
        return $this;
    }

    /**
     * Get hotspot1_y
     * @return string
     */
    public function getHotspot1Y()
    {
        return $this->getData(self::HOTSPOT1_Y);
    }

    /**
     * Set hotspot1_y
     * @param string $hotspot1Y
     * @return $this
     */
    public function setHotspot1Y($hotspot1Y)
    {
        $this->setData(self::HOTSPOT1_Y, $hotspot1Y);
        return $this;
    }

    /**
     * Get hotspot2_sku
     * @return string
     */
    public function getHotspot2Sku()
    {
        return $this->getData(self::HOTSPOT2_SKU);
    }

    /**
     * Set hotspot2_sku
     * @param string $hotspot2Sku
     * @return $this
     */
    public function setHotspot2Sku($hotspot2Sku)
    {
        $this->setData(self::HOTSPOT2_SKU, $hotspot2Sku);
        return $this;
    }

    /**
     * Get hotspot2_x
     * @return string
     */
    public function getHotspot2X()
    {
        return $this->getData(self::HOTSPOT2_X);
    }

    /**
     * Set hotspot2_x
     * @param string $hotspot2X
     * @return $this
     */
    public function setHotspot2X($hotspot2X)
    {
        $this->setData(self::HOTSPOT2_X, $hotspot2X);
        return $this;
    }

    /**
     * Get hotspot2_y
     * @return string
     */
    public function getHotspot2Y()
    {
        return $this->getData(self::HOTSPOT2_Y);
    }

    /**
     * Set hotspot2_y
     * @param string $hotspot2Y
     * @return $this
     */
    public function setHotspot2Y($hotspot2Y)
    {
        $this->setData(self::HOTSPOT2_Y, $hotspot2Y);
        return $this;
    }

    /**
     * Get hotspot3_sku
     * @return string
     */
    public function getHotspot3Sku()
    {
        return $this->getData(self::HOTSPOT3_SKU);
    }

    /**
     * Set hotspot3_sku
     * @param string $hotspot3Sku
     * @return $this
     */
    public function setHotspot3Sku($hotspot3Sku)
    {
        $this->setData(self::HOTSPOT3_SKU, $hotspot3Sku);
        return $this;
    }

    /**
     * Get hotspot3_x
     * @return string
     */
    public function getHotspot3X()
    {
        return $this->getData(self::HOTSPOT3_X);
    }

    /**
     * Set hotspot3_x
     * @param string $hotspot3X
     * @return $this
     */
    public function setHotspot3X($hotspot3X)
    {
        $this->setData(self::HOTSPOT3_X, $hotspot3X);
        return $this;
    }

    /**
     * Get hotspot3_y
     * @return string
     */
    public function getHotspot3Y()
    {
        return $this->getData(self::HOTSPOT3_Y);
    }

    /**
     * Set hotspot3_y
     * @param string $hotspot3Y
     * @return $this
     */
    public function setHotspot3Y($hotspot3Y)
    {
        $this->setData(self::HOTSPOT3_Y, $hotspot3Y);
        return $this;
    }

    /**
     * Get hotspot4_sku
     * @return string
     */
    public function getHotspot4Sku()
    {
        return $this->getData(self::HOTSPOT4_SKU);
    }

    /**
     * Set hotspot4_sku
     * @param string $hotspot4Sku
     * @return $this
     */
    public function setHotspot4Sku($hotspot4Sku)
    {
        $this->setData(self::HOTSPOT4_SKU, $hotspot4Sku);
        return $this;
    }

    /**
     * Get hotspot4_x
     * @return string
     */
    public function getHotspot4X()
    {
        return $this->getData(self::HOTSPOT4_X);
    }

    /**
     * Set hotspot4_x
     * @param string $hotspot4X
     * @return $this
     */
    public function setHotspot4X($hotspot4X)
    {
        $this->setData(self::HOTSPOT4_X, $hotspot4X);
        return $this;
    }

    /**
     * Get hotspot4_y
     * @return string
     */
    public function getHotspot4Y()
    {
        return $this->getData(self::HOTSPOT4_Y);
    }

    /**
     * Set hotspot4_y
     * @param string $hotspot4Y
     * @return $this
     */
    public function setHotspot4Y($hotspot4Y)
    {
        $this->setData(self::HOTSPOT4_Y, $hotspot4Y);
        return $this;
    }

    /**
     * Get hotspot5_sku
     * @return string
     */
    public function getHotspot5Sku()
    {
        return $this->getData(self::HOTSPOT5_SKU);
    }

    /**
     * Set hotspot5_sku
     * @param string $hotspot5Sku
     * @return $this
     */
    public function setHotspot5Sku($hotspot5Sku)
    {
        $this->setData(self::HOTSPOT5_SKU, $hotspot5Sku);
        return $this;
    }

    /**
     * Get hotspot5_x
     * @return string
     */
    public function getHotspot5X()
    {
        return $this->getData(self::HOTSPOT5_X);
    }

    /**
     * Set hotspot5_x
     * @param string $hotspot5X
     * @return $this
     */
    public function setHotspot5X($hotspot5X)
    {
        $this->setData(self::HOTSPOT5_X, $hotspot5X);
        return $this;
    }

    /**
     * Get hotspot5_y
     * @return string
     */
    public function getHotspot5Y()
    {
        return $this->getData(self::HOTSPOT5_Y);
    }

    /**
     * Set hotspot5_y
     * @param string $hotspot5Y
     * @return $this
     */
    public function setHotspot5Y($hotspot5Y)
    {
        $this->setData(self::HOTSPOT5_Y, $hotspot5Y);
        return $this;
    }

    /**
     * Get type
     * @return string
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Set type
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->setData(self::TYPE, $type);
        return $this;
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        $identities = [];
        if ($id = $this->getId()) {
            $identities[] = self::CACHE_TAG . '_' . $id;
        }
        return $identities;
    }
}
