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

namespace Magenest\InstagramShop\Model\ResourceModel\Report;

use Magenest\InstagramShop\Model\Report;

/**
 * Class Collection
 * @package Magenest\InstagramShop\Model\ResourceModel\Photo
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';

    /**
     * Initialize resource collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magenest\InstagramShop\Model\Report', 'Magenest\InstagramShop\Model\ResourceModel\Report');
    }

    /**
     * @return array
     */
    public function loadFilterByType()
    {
        $result = [];
        foreach ($this->getItems() as $item) {
            if (!isset($result[$item->getType()])) {
                $result[$item->getType()] = [];
            }
            $result[$item->getType()][$item->getPhotoId()] = $item;
        }
        return $result;
    }

    /**
     * @param array $ids
     * @return $this
     */
    public function addIdsFilter($ids)
    {
        $this->addFieldToFilter('id', ['in' => $ids]);
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalClicks()
    {
        $totalClicks = 0;
        $items       = $this->getItems();
        foreach ($items as $item) {
            $totalClicks += intval($item->getTotalClick());
        }
        return $totalClicks;
    }

    /**
     * @return int
     */
    public function getTotalAddedToCart()
    {
        $total = 0;
        $items = $this->getItems();
        foreach ($items as $item) {
            $total += $item->getTotalAddedToCart();
        }
        return $total;
    }

    /**
     * @param bool $withPercent
     * @param int $round
     * @return float|string
     */
    public function getTotalConversionRate($withPercent = false, $round = 2)
    {
        if (($totalProductClicks = $this->getTotalProductClicks()) == 0) {
            return $totalProductClicks . (!$withPercent ? '' : ' %');
        }
        return (100 * round($this->getTotalConversion() / $this->getTotalProductClicks(), $round)) . (!$withPercent ? '' : ' %');
    }

    /**
     * @return int
     */
    public function getTotalConversion()
    {
        return $this->getTotalData('getTotalConversion');
    }

    /**
     * @return int
     */
    public function getTotalProductClicks()
    {
        return $this->getTotalData('getTotalProductsClicks');
    }

    /**
     * @param string $functionName
     * @return int
     */
    private function getTotalData($functionName)
    {
        $total = 0;
        $items = $this->getItems();
        foreach ($items as $item) {
            $total += $item->{$functionName}();
        }
        return $total;
    }

    /**
     * @return \Magenest\InstagramShop\Model\Photo
     */
    public function getHighestConversionRateImage()
    {
        $items       = $this->getItems();
        $report      = $this->getNewEmptyItem();
        $highestRate = 0;

        foreach ($items as $item) {
            if (($totalConversionRate = $item->getTotalConversionRateOnImage()) > $highestRate) {
                $report      = $item;
                $highestRate = $totalConversionRate;
            }
        }
        return $report->getPhoto();
    }

    /**
     * @return \Magenest\InstagramShop\Model\Photo
     */
    public function getMostViewImage()
    {
        $items    = $this->getItems();
        $report   = $this->getNewEmptyItem();
        $mostView = 0;
        foreach ($items as $item) {
            if ($item->getTotalClick() > $mostView) {
                $mostView = $item->getTotalClick();
                $report   = $item;
            }
        }
        return $report->getPhoto();
    }

    /**
     * @return bool|int
     */
    public function getMostProductViewFromImage()
    {
        $items        = $this->getItems();
        $mostView     = 0;
        $productId    = false;
        $productsView = [];
        foreach ($items as $item) {
            $viewedProducts = $item->getViewedProducts();
            foreach ($viewedProducts as $product => $clicks) {
                isset($productsView[$product]) ?: $productsView[$product] = 0;
                $productsView[$product] += $clicks;
            }
        }
        foreach ($productsView as $product => $view) {
            if ($view > $mostView) {
                $mostView  = $view;
                $productId = $product;
            }
        }
        return $productId;
    }

    /**
     * @return Report[]|\Magento\Framework\DataObject[]
     */
    public function getItems()
    {
        return parent::getItems();
    }
}
