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

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddVisitedProductToSession implements ObserverInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * AddVisitedProductToSession constructor.
     * @param \Magento\Checkout\Model\Session $session
     */
    public function __construct(
        \Magento\Checkout\Model\Session $session
    )
    {
        $this->_checkoutSession = $session;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $params = $observer->getEvent()->getParams();
        try {
            if (isset($params['product_id']) && isset($params['photo_id']) && isset($params['photo_type'])
                && ($productId = $params['product_id']) && ($photoId = $params['photo_id']) && ($type = $params['photo_type'])) {
                $productIds = $this->_checkoutSession->getViewedInstagramProducts() ? $this->_checkoutSession->getViewedInstagramProducts() : [];
                if (!isset($productIds[$productId])) {
                    $productIds[$productId] = ['photo_id' => $photoId, 'type' => $type];
                    $this->_checkoutSession->setViewedInstagramProducts($productIds);
                }
            }
        } catch (\Exception $e) {
            return;
        }
    }
}