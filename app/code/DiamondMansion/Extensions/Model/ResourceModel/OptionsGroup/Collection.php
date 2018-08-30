<?php
namespace DiamondMansion\Extensions\Model\ResourceModel\OptionsGroup;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'diamondmansion_optionsgroup_collection';
    protected $_eventObject = 'diamondmansion_optionsgroup_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\OptionsGroup', 'DiamondMansion\Extensions\Model\ResourceModel\OptionsGroup');
    }
}
