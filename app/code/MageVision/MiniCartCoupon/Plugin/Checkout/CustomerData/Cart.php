<?php
/**
 * MageVision Mini Cart Coupon Extension
 *
 * @category     MageVision
 * @package      MageVision_MiniCartCoupon
 * @author       MageVision Team
 * @copyright    Copyright (c) 2018 MageVision (http://www.magevision.com)
 * @license      LICENSE_MV.txt or http://www.magevision.com/license-agreement/
 */
namespace MageVision\MiniCartCoupon\Plugin\Checkout\CustomerData;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Checkout\Helper\Data as CheckoutHelper;
use MageVision\MiniCartCoupon\Helper\Data as MiniCartCouponHelper;
use Magento\Framework\View\LayoutInterface;

class Cart
{
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;
    
    /**
     * @var CheckoutHelper
     */
    protected $checkoutHelper;

    /**
     * @var MiniCartCouponHelper
     */
    protected $miniCartCouponHelper;

    /**
     * @var \Magento\Quote\Model\Quote|null
     */
    protected $quote = null;

    /**
     * @var LayoutInterface
     */
    protected $layout;

    /**
     * @param CheckoutSession $checkoutSession
     * @param CheckoutHelper $checkoutHelper
     * @param MiniCartCouponHelper $miniCartCouponHelper
     * @param LayoutInterface $layout
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        CheckoutHelper $checkoutHelper,
        MiniCartCouponHelper $miniCartCouponHelper,
        LayoutInterface $layout
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->checkoutHelper = $checkoutHelper;
        $this->miniCartCouponHelper = $miniCartCouponHelper;
        $this->layout = $layout;
    }

    /**
     * Add grand total and discount data to result
     *
     * @param \Magento\Checkout\CustomerData\Cart $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetSectionData(\Magento\Checkout\CustomerData\Cart $subject, $result)
    {
        $totals = $this->getQuote()->getTotals();
        $discount = $this->getQuote()->getShippingAddress()->getDiscountAmount();
        $result['grand_total'] = isset($totals['grand_total'])
            ? $this->checkoutHelper->formatPrice($totals['grand_total']->getValue())
            : 0;
        $result['discountAmount'] = $discount;
        $result['discount'] = isset($discount)
            ? $this->checkoutHelper->formatPrice($discount)
            : 0;
        $result['minicartCouponEnabled'] = $this->miniCartCouponHelper->isEnabled();
        $result['displayGrandTotal'] = $this->miniCartCouponHelper->displayGrandTotal();
        $result['displayDiscount'] = $this->miniCartCouponHelper->displayDiscount();
        $result['mincartCouponBlock'] = $this->layout->createBlock('Magento\Checkout\Block\Cart\Coupon')
            ->setTemplate('MageVision_MiniCartCoupon::cart/minicart/coupon.phtml')->toHtml();

        return $result;
    }

    /**
     * Get active quote
     *
     * @return \Magento\Quote\Model\Quote
     */
    protected function getQuote()
    {
        if (null === $this->quote) {
            $this->quote = $this->checkoutSession->getQuote();
        }
        return $this->quote;
    }
}
