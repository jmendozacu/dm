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

namespace Magenest\InstagramShop\Api;


interface HotspotRepositoryInterface
{

    /**
     * Save Hotspot
     * @param \Magenest\InstagramShop\Api\Data\HotspotInterface $hotspot
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Magenest\InstagramShop\Api\Data\HotspotInterface $hotspot
    );

    /**
     * Retrieve Hotspot
     * @param string $hotspotId
     * @return \Magenest\InstagramShop\Api\Data\HotspotInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($hotspotId);

    /**
     * Retrieve Hotspot matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magenest\InstagramShop\Api\Data\HotspotSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Hotspot
     * @param \Magenest\InstagramShop\Api\Data\HotspotInterface $hotspot
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Magenest\InstagramShop\Api\Data\HotspotInterface $hotspot
    );

    /**
     * Delete Hotspot by ID
     * @param string $hotspotId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($hotspotId);
}
