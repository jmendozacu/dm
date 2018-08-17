<?php
namespace DiamondMansion\Extensions\Model\Ring\Design\Price;

class Stone extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'diamondmansion_ring_design_price_stone';

	protected $_cacheTag = 'diamondmansion_ring_design_price_stone';

	protected $_eventPrefix = 'diamondmansion_ring_design_price_stone';

	protected function _construct()
	{
		$this->_init('DiamondMansion\Extensions\Model\ResourceModel\Ring\Design\Price\Stone');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}
}