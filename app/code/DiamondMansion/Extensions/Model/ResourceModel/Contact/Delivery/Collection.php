<?php
namespace DiamondMansion\Extensions\Model\ResourceModel\Contact\Delivery;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'diamondmansion_contact_delivery_collection';
    protected $_eventObject = 'diamondmansion_contact_delivery_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\Contact\Delivery', 'DiamondMansion\Extensions\Model\ResourceModel\Contact\Delivery');
    }
}
