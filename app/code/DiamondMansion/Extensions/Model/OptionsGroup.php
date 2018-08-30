<?php
namespace DiamondMansion\Extensions\Model;

class OptionsGroup extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'diamondmansion_optionsgroup';

    protected $_cacheTag = 'diamondmansion_optionsgroup';

    protected $_eventPrefix = 'diamondmansion_optionsgroup';

    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\ResourceModel\OptionsGroup');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}