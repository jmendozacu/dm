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

namespace Magenest\InstagramShop\Api\Data;

interface HotspotInterface
{

    const HOTSPOT5_SKU = 'hotspot5_sku';
    const HOTSPOT_ID = 'hotspot_id';
    const PHOTO_ID = 'photo_id';
    const HOTSPOT1_SKU = 'hotspot1_sku';
    const HOTSPOT3_SKU = 'hotspot3_sku';
    const HOTSPOT2_SKU = 'hotspot2_sku';
    const HOTSPOT2_X = 'hotspot2_x';
    const HOTSPOT4_X = 'hotspot4_x';
    const HOTSPOT3_Y = 'hotspot3_y';
    const HOTSPOT1_X = 'hotspot1_x';
    const HOTSPOT4_SKU = 'hotspot4_sku';
    const HOTSPOT5_X = 'hotspot5_x';
    const HOTSPOT5_Y = 'hotspot5_y';
    const TYPE = 'type';
    const HOTSPOT1_Y = 'hotspot1_y';
    const HOTSPOT2_Y = 'hotspot2_y';
    const HOTSPOT3_X = 'hotspot3_x';
    const HOTSPOT4_Y = 'hotspot4_y';

    /**
     * Get hotspot_id
     * @return string|null
     */
    public function getHotspotId();

    /**
     * Set hotspot_id
     * @param string $hotspotId
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspotId($hotspotId);

    /**
     * Get photo_id
     * @return string|null
     */
    public function getPhotoId();

    /**
     * Set photo_id
     * @param string $photoId
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setPhotoId($photoId);

    /**
     * Get hotspot1_sku
     * @return string|null
     */
    public function getHotspot1Sku();

    /**
     * Set hotspot1_sku
     * @param string $hotspot1Sku
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot1Sku($hotspot1Sku);

    /**
     * Get hotspot1_x
     * @return string|null
     */
    public function getHotspot1X();

    /**
     * Set hotspot1_x
     * @param string $hotspot1X
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot1X($hotspot1X);

    /**
     * Get hotspot1_y
     * @return string|null
     */
    public function getHotspot1Y();

    /**
     * Set hotspot1_y
     * @param string $hotspot1Y
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot1Y($hotspot1Y);

    /**
     * Get hotspot2_sku
     * @return string|null
     */
    public function getHotspot2Sku();

    /**
     * Set hotspot2_sku
     * @param string $hotspot2Sku
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot2Sku($hotspot2Sku);

    /**
     * Get hotspot2_x
     * @return string|null
     */
    public function getHotspot2X();

    /**
     * Set hotspot2_x
     * @param string $hotspot2X
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot2X($hotspot2X);

    /**
     * Get hotspot2_y
     * @return string|null
     */
    public function getHotspot2Y();

    /**
     * Set hotspot2_y
     * @param string $hotspot2Y
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot2Y($hotspot2Y);

    /**
     * Get hotspot3_sku
     * @return string|null
     */
    public function getHotspot3Sku();

    /**
     * Set hotspot3_sku
     * @param string $hotspot3Sku
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot3Sku($hotspot3Sku);

    /**
     * Get hotspot3_x
     * @return string|null
     */
    public function getHotspot3X();

    /**
     * Set hotspot3_x
     * @param string $hotspot3X
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot3X($hotspot3X);

    /**
     * Get hotspot3_y
     * @return string|null
     */
    public function getHotspot3Y();

    /**
     * Set hotspot3_y
     * @param string $hotspot3Y
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot3Y($hotspot3Y);

    /**
     * Get hotspot4_sku
     * @return string|null
     */
    public function getHotspot4Sku();

    /**
     * Set hotspot4_sku
     * @param string $hotspot4Sku
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot4Sku($hotspot4Sku);

    /**
     * Get hotspot4_x
     * @return string|null
     */
    public function getHotspot4X();

    /**
     * Set hotspot4_x
     * @param string $hotspot4X
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot4X($hotspot4X);

    /**
     * Get hotspot4_y
     * @return string|null
     */
    public function getHotspot4Y();

    /**
     * Set hotspot4_y
     * @param string $hotspot4Y
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot4Y($hotspot4Y);

    /**
     * Get hotspot5_sku
     * @return string|null
     */
    public function getHotspot5Sku();

    /**
     * Set hotspot5_sku
     * @param string $hotspot5Sku
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot5Sku($hotspot5Sku);

    /**
     * Get hotspot5_x
     * @return string|null
     */
    public function getHotspot5X();

    /**
     * Set hotspot5_x
     * @param string $hotspot5X
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot5X($hotspot5X);

    /**
     * Get hotspot5_y
     * @return string|null
     */
    public function getHotspot5Y();

    /**
     * Set hotspot5_y
     * @param string $hotspot5Y
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setHotspot5Y($hotspot5Y);

    /**
     * Get type
     * @return string|null
     */
    public function getType();

    /**
     * Set type
     * @param string $type
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     */
    public function setType($type);
}
