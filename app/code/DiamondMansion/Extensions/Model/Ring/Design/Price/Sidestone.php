<?php
namespace DiamondMansion\Extensions\Model\Ring\Design\Price;

class Sidestone extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'diamondmansion_ring_design_price_sidestone';

	protected $_cacheTag = 'diamondmansion_ring_design_price_sidestone';

	protected $_eventPrefix = 'diamondmansion_ring_design_price_sidestone';

	protected function _construct()
	{
		$this->_init('DiamondMansion\Extensions\Model\ResourceModel\Ring\Design\Price\Sidestone');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}
}