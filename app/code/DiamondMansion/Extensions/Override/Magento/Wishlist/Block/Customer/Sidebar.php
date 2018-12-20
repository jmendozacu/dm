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

    public function __construct(
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    ) {
        $this->_pricingHelper = $pricingHelper;

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
}