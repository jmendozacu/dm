<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Wishlist sidebar block
 */
namespace DiamondMansion\Extensions\Override\Magento\Wishlist\Block\Customer;

use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\Render;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;

/**
 * @api
 * @since 100.0.2
 */
class Sidebar extends \Magento\Wishlist\Block\Customer\Sidebar
{
    protected $_pricingHelper;
    protected $_likedislikeFactory;
    protected $_session;
    protected $_customerSession;

    public function __construct(
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \DiamondMansion\Extensions\Model\LikeDislikeFactory $likedislikeFactory,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Customer\Model\SessionFactory $customerSession,
        array $data = []
    ) {
        $this->_pricingHelper = $pricingHelper;
        $this->_likedislikeFactory = $likedislikeFactory;
        $this->_session = $session;
        $this->_customerSession = $customerSession->create();

        parent::__construct(
            $context,
            $httpContext,
            $data
        );
    }

    public function getProductPriceHtml(
        Product $product,
        $priceType,
        $renderZone = Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }

        if (strpos($product->getTypeId(), 'dm_') !== false) {
            $price = '<span class="price">$' . round(doubleval($product->getPrice()) / 10) * 10 . '</span>';
        } else {
            $price = parent::getProductPriceHtml($product, $priceType, $renderZone, $arguments);
        }

        return $price;
    }

    public function isLoggedIn() {
        return $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    public function getGuestEmail() {
        if ($this->isLoggedIn()) {
            return $this->_customerSession->getCustomer()->getEmail();
        } else {
            return $this->_session->getGuestEmail();
        }
    }

    public function getGuestWishlist() {
        $guestEmail = $this->_session->getGuestEmail();

        $wishlist = [];
        if ($guestEmail) {
            $collection = $this->_likedislikeFactory->create()->getCollection()
                ->addFieldToFilter('email', $guestEmail)
                ->addFieldToFilter('review', 1);

            foreach ($collection as $item) {
                $wishlist[] = json_decode($item->getProductOptions());
            }
        }

        return $wishlist;
    }
}