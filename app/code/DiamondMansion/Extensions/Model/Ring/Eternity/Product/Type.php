<?php
namespace DiamondMansion\Extensions\Model\Ring\Eternity\Product;

class Type extends \Magento\Catalog\Model\Product\Type\AbstractType {
    /**
     * Delete data specific for Simple product type
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return void
     */
    public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {
    }
}