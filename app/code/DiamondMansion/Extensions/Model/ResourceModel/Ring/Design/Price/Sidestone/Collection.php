<?php
namespace DiamondMansion\Extensions\Model\ResourceModel\Ring\Design\Price\Sidestone;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'entity_id';
	protected $_eventPrefix = 'diamondmansion_ring_design_price_sidestone_collection';
	protected $_eventObject = 'diamondmansion_ring_design_price_sidestone_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('DiamondMansion\Extensions\Model\Ring\Design\Price\Sidestone', 'DiamondMansion\Extensions\Model\ResourceModel\Ring\Design\Price\Sidestone');
	}
}
