<?php

namespace DiamondMansion\Extensions\Override\Magento\Wishlist\Model;

class Item extends \Magento\Wishlist\Model\Item
{
    public function getProduct()
    {
        $product = parent::getProduct();
        $buyRequest = $this->getBuyRequest();
        if (isset($buyRequest['dm_options'])) {
            $filters = $product->getFilters();
            $filters['option'] = $buyRequest['dm_options'];
            $product->setFilters($filters);
        }
        return $product;
    }

    /**
     * Check product representation in item
     *
     * @param   \Magento\Catalog\Model\Product $product
     * @return  bool
     */
    public function representProduct($product)
    {
        $buyRequest = $this->getBuyRequest();
        if (isset($buyRequest['dm_options'])) {
            return false;
        }

        return parent::representProduct($product);
    }
}