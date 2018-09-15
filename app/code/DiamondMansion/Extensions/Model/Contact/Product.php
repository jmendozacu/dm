<?php
namespace DiamondMansion\Extensions\Model\Contact;

class Product extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'diamondmansion_contact_product';

    protected $_cacheTag = 'diamondmansion_contact_product';

    protected $_eventPrefix = 'diamondmansion_contact_product';

    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\ResourceModel\Contact\Product');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}