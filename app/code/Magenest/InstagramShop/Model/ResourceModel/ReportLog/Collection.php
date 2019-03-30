<?php
/**
 *
  * Copyright © 2018 Magenest. All rights reserved.
  * See COPYING.txt for license details.
  *
  * Magenest_InstagramShop extension
  * NOTICE OF LICENSE
  *
  * @category Magenest
  * @package  Magenest_InstagramShop
  * @author    dangnh@magenest.com

 */

namespace Magenest\InstagramShop\Model\ResourceModel\ReportLog;

/**
 * Class Collection
 * @package Magenest\InstagramShop\Model\ResourceModel\ReportLog
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';

    /**
     * Initialize resource collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magenest\InstagramShop\Model\Report', 'Magenest\InstagramShop\Model\ResourceModel\ReportLog');
    }

    /**
     * @param string $field
     * @return $this
     */
    public function setFilterNotNull($field)
    {
        $this->addFieldToFilter($field, ['notnull' => true]);
        return $this;
    }
}
