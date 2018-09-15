<?php
namespace DiamondMansion\Extensions\Model\ResourceModel\Contact\Product;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'diamondmansion_contact_product_collection';
    protected $_eventObject = 'diamondmansion_contact_product_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\Contact\Product', 'DiamondMansion\Extensions\Model\ResourceModel\Contact\Product');
    }
}
