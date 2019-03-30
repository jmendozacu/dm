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

namespace Magenest\InstagramShop\Observer\TrackOrderItems;

use Magenest\InstagramShop\Model\Report;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order;

/**
 * Class OrderedItems
 * @package Magenest\InstagramShop\Observer\TrackOrderItems
 */
class OrderedItems extends AbstractOrderItems
{

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getOrder();
        try {
            $instagramProductIds = $this->getViewedInstagramProducts();
            if (is_array($instagramProductIds) && !empty($instagramProductIds)) {
                $orderProductIds  = $this->getOrderProductIds($order);
                $reportCollection = $this->getReportCollection();
                foreach ($orderProductIds as $productId => $value) {
                    if (isset($instagramProductIds[$productId])) {
                        $photoData = $instagramProductIds[$productId];
                        $photoId   = $photoData['photo_id'];
                        $type      = $photoData['type'];
                        /** @var Report $report */
                        $report = isset($reportCollection[$type][$photoId]) ? $reportCollection[$type][$photoId] : false;
                        if ($report && $report instanceof Report) {
                            $report->increaseConversionRate($productId);
                            $report->save();
                        }
                        unset($instagramProductIds[$productId]);
                    }
                }
            }
            $this->setViewedInstagramProducts($instagramProductIds);
        } catch (\Exception $e) {
            return;
        }
    }

    /**
     * @param Order $order
     * @return array
     */
    protected function getOrderProductIds($order)
    {
        $productIds = [];
        $items      = $order->getAllItems();
        foreach ($items as $item) {
            $productIds[$item->getProduct()->getId()] = true;
        }
        return $productIds;
    }
}