<?php
namespace DiamondMansion\Extensions\Model\ResourceModel\Ring\Design\Price\Stone;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'entity_id';
	protected $_eventPrefix = 'diamondmansion_ring_design_price_stone_collection';
	protected $_eventObject = 'diamondmansion_ring_design_price_stone_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('DiamondMansion\Extensions\Model\Ring\Design\Price\Stone', 'DiamondMansion\Extensions\Model\ResourceModel\Ring\Design\Price\Stone');
	}
}
