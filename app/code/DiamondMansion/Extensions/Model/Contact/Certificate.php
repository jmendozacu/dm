<?php
namespace DiamondMansion\Extensions\Model\Contact;

class Certificate extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'diamondmansion_contact_certificate';

    protected $_cacheTag = 'diamondmansion_contact_certificate';

    protected $_eventPrefix = 'diamondmansion_contact_certificate';

    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\ResourceModel\Contact\Certificate');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}