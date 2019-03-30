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
use Magento\Catalog\Model\Product;
use Magento\Framework\Event\Observer;

/**
 * Class AddedItems
 * @package Magenest\InstagramShop\Observer\TrackOrderItems
 */
class AddedItems extends AbstractOrderItems
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Product $product */
        $product                 = $observer->getEvent()->getProduct();

        try {
            $viewedInstagramProducts = $this->getViewedInstagramProducts();
            $reportCollection        = $this->getReportCollection();
            if (isset($viewedInstagramProducts[$product->getId()])) {
                $data   = $viewedInstagramProducts[$product->getId()];
                $report = isset($reportCollection[$data['type']][$data['photo_id']]) ? $reportCollection[$data['type']][$data['photo_id']] : false;
                if ($report && $report instanceof Report) {
                    $report->increaseAddedToCart($product->getId());
                    $report->save();
                }
            }
        } catch (\Exception $e) {
            return;
        }
    }
}