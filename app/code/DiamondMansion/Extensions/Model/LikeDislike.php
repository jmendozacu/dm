<?php
namespace DiamondMansion\Extensions\Model;

class LikeDislike extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'diamondmansion_likedislike';

    protected $_cacheTag = 'diamondmansion_likedislike';

    protected $_eventPrefix = 'diamondmansion_likedislike';

    protected function _construct()
    {
        $this->_init('DiamondMansion\Extensions\Model\ResourceModel\LikeDislike');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}