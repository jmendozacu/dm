<?php

namespace DiamondMansion\Extensions\Override\Magento\Sales\Model\Order;

class Item extends \Magento\Sales\Model\Order\Item
{
    /**
     * Get product options array
     *
     * @return array
     */
    public function getProductOptions()
    {
        $options = parent::getProductOptions();

        if (isset($options['info_buyRequest'])) {
            if (!isset($options['options'])) {
                $options['options'] = [];
            }
    
            $buyRequest = $options['info_buyRequest'];
    
            if (isset($buyRequest['dm_options'])) {
                $product = $this->getProduct();
                
                $filters = $product->getFilters();
                $filters['option'] = $buyRequest['dm_options'];
                $product->setFilters($filters);
    
                if (method_exists($product->getTypeInstance(), 'getDmOptionListForCart')) {
                    $options['options'] = array_merge($product->getTypeInstance()->getDmOptionListForCart($product), $options['options']);
                }
            }    
        }

        return $options;
    }
}