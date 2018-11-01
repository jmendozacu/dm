<?php
namespace DiamondMansion\Extensions\Model\ResourceModel\LikeDislike;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'diamondmansion_likedislike_collection';
    protected $_eventObject = 'diamondmansion_likedislike_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\LikeDislike', 'DiamondMansion\Extensions\Model\ResourceModel\LikeDislike');
    }
}
