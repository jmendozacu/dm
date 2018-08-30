<?php
namespace DiamondMansion\Extensions\Model;

class ProductOptions extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'diamondmansion_productoptions';

    protected $_cacheTag = 'diamondmansion_productoptions';

    protected $_eventPrefix = 'diamondmansion_productoptions';

    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\ResourceModel\ProductOptions');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}