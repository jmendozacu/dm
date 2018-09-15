<?php
namespace DiamondMansion\Extensions\Model\ResourceModel\Contact\Opinion;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'diamondmansion_contact_opinion_collection';
    protected $_eventObject = 'diamondmansion_contact_opinion_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\Contact\Opinion', 'DiamondMansion\Extensions\Model\ResourceModel\Contact\Opinion');
    }
}
