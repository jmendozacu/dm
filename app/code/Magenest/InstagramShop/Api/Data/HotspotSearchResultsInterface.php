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

interface HotspotSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Hotspot list.
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface[]
     */
    public function getItems();

    /**
     * Set photo_id list.
     * @param \Magenest\InstagramShop\Api\Data\HotspotInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
