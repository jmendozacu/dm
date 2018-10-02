<?php
namespace DiamondMansion\Extensions\Override\Magento\Catalog\Model\Category\Attribute\Source;

class Mode extends \Magento\Catalog\Model\Category\Attribute\Source\Mode
{
    const DM_RING_DESIGN = 'PRODUCTS_RING_DESIGN';

    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['value' => \Magento\Catalog\Model\Category::DM_PRODUCT, 'label' => __('Products only')],
                ['value' => \Magento\Catalog\Model\Category::DM_PAGE, 'label' => __('Static block only')],
                ['value' => \Magento\Catalog\Model\Category::DM_MIXED, 'label' => __('Static block and products')],
                ['value' => self::DM_RING_DESIGN, 'label' => __('Products - Design Rings')],
            ];
        }
        return $this->_options;
    }
}
