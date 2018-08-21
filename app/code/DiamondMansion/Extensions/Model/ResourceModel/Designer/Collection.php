<?php
namespace DiamondMansion\Extensions\Model\ResourceModel\Designer;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'diamondmansion_designer_collection';
    protected $_eventObject = 'diamondmansion_designer_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\Designer', 'DiamondMansion\Extensions\Model\ResourceModel\Designer');
    }
}
