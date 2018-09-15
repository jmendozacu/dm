<?php
namespace DiamondMansion\Extensions\Model\Contact;

class Opinion extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'diamondmansion_contact_opinion';

    protected $_cacheTag = 'diamondmansion_contact_opinion';

    protected $_eventPrefix = 'diamondmansion_contact_opinion';

    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\ResourceModel\Contact\Opinion');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}