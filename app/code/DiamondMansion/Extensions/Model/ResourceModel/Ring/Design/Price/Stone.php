<?php
namespace DiamondMansion\Extensions\Model\ResourceModel\Ring\Design\Price;

class Stone extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('dm_design_ring_stone_price_entity', 'entity_id');
	}
}