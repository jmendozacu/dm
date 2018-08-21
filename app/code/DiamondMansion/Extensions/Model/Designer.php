<?php
namespace DiamondMansion\Extensions\Model;

class Designer extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'diamondmansion_designer';

    protected $_cacheTag = 'diamondmansion_designer';

    protected $_eventPrefix = 'diamondmansion_designer';

    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\ResourceModel\Designer');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}