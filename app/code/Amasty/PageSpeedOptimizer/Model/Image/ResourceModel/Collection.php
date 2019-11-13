<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PageSpeedOptimizer
 */


namespace Amasty\PageSpeedOptimizer\Model\Image\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init(
            \Amasty\PageSpeedOptimizer\Model\Image\ImageSetting::class,
            \Amasty\PageSpeedOptimizer\Model\Image\ResourceModel\ImageSetting::class
        );
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }
}
