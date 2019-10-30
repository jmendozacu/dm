<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace DiamondMansion\Extensions\Override\Magento\Reports\Block\Adminhtml\Shopcart\Abandoned;

/**
 * Adminhtml abandoned shopping carts report grid block
 *
 * @method \Magento\Reports\Model\ResourceModel\Quote\Collection getCollection()
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Grid extends \Magento\Reports\Block\Adminhtml\Shopcart\Abandoned\Grid
{
    /**
     * Prepare columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumnAfter(
            'items',
            [
                'header' => __('Items'),
                'index' => 'entity_id',
                'sortable' => false,
                'renderer' => \DiamondMansion\Extensions\Block\Adminhtml\Shopcart\Abandoned\Grid\Column\Renderer\Items::class,
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'
            ],
            'email'
        );

        return parent::_prepareColumns();
        //return $this;
    }

    public function getRowUrl($row) {
        return false;
    }
}
