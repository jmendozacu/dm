<?php
namespace DiamondMansion\Extensions\Model\ResourceModel\ProductOptions;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'diamondmansion_productoptions_collection';
    protected $_eventObject = 'diamondmansion_productoptions_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\ProductOptions', 'DiamondMansion\Extensions\Model\ResourceModel\ProductOptions');
    }

    public function joinDetails($productType)
    {
        $joinTable = $this->getTable('dm_options_group');
        $this->getSelect()->join($joinTable.' as og', 'main_table.group like CONCAT(og.group, \'%\') and main_table.code = og.code and og.type=\'' . $productType . '\'', ['title', 'slug']);
        return $this;
    }
}
