<?php
namespace DiamondMansion\Extensions\Model\Contact;

class PriceReserve extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'diamondmansion_contact_pricereserve';

    protected $_cacheTag = 'diamondmansion_contact_pricereserve';

    protected $_eventPrefix = 'diamondmansion_contact_pricereserve';

    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\ResourceModel\Contact\PriceReserve');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}