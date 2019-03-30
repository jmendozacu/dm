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

namespace Magenest\InstagramShop\Observer\ClickedImageProduct;

use Magenest\InstagramShop\Model\Report;
use Magento\Framework\Event\Observer;

/**
 * Class TrackInstagramProduct
 * @package Magenest\InstagramShop\Observer\ClickedImageProduct
 */
class TrackInstagramProduct extends AbstractTracker
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $params = $observer->getEvent()->getParams();
        $url    = $observer->getEvent()->getUrl();
        try {
            if (isset($params['photo_id']) && isset($params['photo_type']) && isset($params['product_id'])
                && ($photoId = $params['photo_id']) && ($type = $params['photo_type']) && ($productId = $params['product_id'])) {
                /** @var Report $report */
                $report = $this->getReportObject($photoId, $type);
                $photo  = $this->getPhotoObject($photoId, $type);
                if ($report->getId() && $photo->getId() && is_array($productIds = $photo->getExplodedProductId())) {
                    // check if that photo has the product id
                    if (isset($productIds[$productId])) {
                        $report->increaseProductClick($productId, $url);
                        $report->save();
                    }
                }
            }
        } catch (\Exception $e) {
            return;
        }
    }
}