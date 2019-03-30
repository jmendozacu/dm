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

use Magenest\InstagramShop\Helper\Helper;
use Magenest\InstagramShop\Model\Photo;
use Magenest\InstagramShop\Model\Report;
use Magenest\InstagramShop\Model\ResourceModel\Report\Collection;
use Magenest\InstagramShop\Model\TaggedPhoto;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class AbstractOrderItems
 * @package Magenest\InstagramShop\Observer\TrackOrderItems
 */
abstract class AbstractOrderItems implements ObserverInterface
{

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    /**
     * @var Helper
     */
    protected $helper;
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * TrackOrderedItems constructor.
     * @param \Magento\Checkout\Model\Session $session
     * @param Helper $helper
     */
    public function __construct(
        \Magento\Checkout\Model\Session $session,
        Helper $helper
    )
    {
        $this->_checkoutSession = $session;
        $this->helper           = $helper;
        $this->_construct();
    }

    protected function _construct()
    {
        $this->_initCollection();
    }

    private function _initCollection()
    {
        if ($this->collection === null) {
            /** @var Collection $collection */
            $collection       = ObjectManager::getInstance()->create(Collection::class);
            $this->collection = $collection->loadFilterByType();
        }
        if ($this->collection === null) {
            $this->collection = [];
        }
    }

    /**
     * @return Collection
     */
    protected function getReportCollection()
    {
        return $this->collection;
    }

    /**
     * @param string $photoId
     * @param int $type
     * @return Photo|TaggedPhoto
     */
    protected function getPhoto($photoId, $type = Photo::TYPE)
    {
        return $this->helper->getPhoto($photoId, $type);
    }

    /**
     * @return array
     */
    public function getViewedInstagramProducts()
    {
        return $this->_checkoutSession->getViewedInstagramProducts() ? $this->_checkoutSession->getViewedInstagramProducts() : [];
    }

    /**
     * @param array $products
     */
    public function setViewedInstagramProducts($products = [])
    {
        $this->_checkoutSession->setViewedInstagramProducts($products);
    }
}