<?php
namespace DiamondMansion\Extensions\Model\ResourceModel\Ring\Eternity\Price\Stone;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'entity_id';
	protected $_eventPrefix = 'diamondmansion_ring_eternity_price_stone_collection';
	protected $_eventObject = 'diamondmansion_ring_eternity_price_stone_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('DiamondMansion\Extensions\Model\Ring\Eternity\Price\Stone', 'DiamondMansion\Extensions\Model\ResourceModel\Ring\Eternity\Price\Stone');
	}
}
