<?php
namespace DiamondMansion\Extensions\Model\Contact;

class Delivery extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'diamondmansion_contact_delivery';

    protected $_cacheTag = 'diamondmansion_contact_delivery';

    protected $_eventPrefix = 'diamondmansion_contact_delivery';

    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\ResourceModel\Contact\Delivery');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}