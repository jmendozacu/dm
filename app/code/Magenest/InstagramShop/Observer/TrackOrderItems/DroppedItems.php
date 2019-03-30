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

/**
 * Class DroppedItems
 * @package Magenest\InstagramShop\Observer\TrackOrderItems
 */
class DroppedItems extends AbstractOrderItems
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        try {
            $viewedInstagramProducts = $this->getViewedInstagramProducts();
            $reportCollection        = $this->getReportCollection();

            if (is_array($viewedInstagramProducts) && !empty($viewedInstagramProducts)) {
                foreach ($viewedInstagramProducts as $productId => $data) {
                    if (isset($data['photo_id']) && isset($data['type'])) {
                        $report = isset($reportCollection[$data['type']][$data['photo_id']]) ? $reportCollection[$data['type']][$data['photo_id']] : false;
                        if ($report && $report instanceof Report) {
                            $report->increaseDropRate($productId);
                            $report->save();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->setViewedInstagramProducts();
            return;
        }

        $this->setViewedInstagramProducts();
    }
}