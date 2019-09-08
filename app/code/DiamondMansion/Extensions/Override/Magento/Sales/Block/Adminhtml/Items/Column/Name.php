<?php

namespace DiamondMansion\Extensions\Override\Magento\Sales\Block\Adminhtml\Items\Column;

class Name extends \Magento\Sales\Block\Adminhtml\Items\Column\Name
{
    /**
     * Get order options
     *
     * @return array
     */
    public function getOrderOptions()
    {

        $result = parent::getOrderOptions();

        $buyRequest = $this->getItem()->getBuyRequest();

        if (isset($buyRequest['dm_options'])) {
            $product = $this->getItem()->getProduct();
            
            $filters = $product->getFilters();
            $filters['option'] = $buyRequest['dm_options'];
            $product->setFilters($filters);

            if (method_exists($product->getTypeInstance(), 'getDmOptionListForCart')) {
                $result = array_merge($product->getTypeInstance()->getDmOptionListForCart($product), $result);
            }
        }    

        return $result;
    }
}