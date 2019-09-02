<?php

namespace Magenest\InstagramShop\Model\Config\Backend;

/**
 * Class GalleryUrl
 * @package Magenest\InstagramShop\Model\Config\Backend
 */
class GalleryUrl extends \Magento\Framework\App\Config\Value
{
    /**
     * @return \Magento\Framework\App\Config\Value
     */
    public function beforeSave()
    {
        $value = (string)$this->getValue();
        if (empty($value)) {
            $this->setValue('instagram/gallery');
        }
        return parent::beforeSave();
    }

    /**
     * @return \Magento\Framework\App\Config\Value
     */
    public function afterSave()
    {
        if ($this->isValueChanged()) {
            $this->cacheTypeList->invalidate(\Magento\Framework\App\Cache\Type\Block::TYPE_IDENTIFIER);
        }
        return parent::afterSave();
    }
}