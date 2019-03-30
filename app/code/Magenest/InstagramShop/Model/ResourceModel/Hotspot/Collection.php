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

namespace Magenest\InstagramShop\Model\ResourceModel\Hotspot;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Magenest\InstagramShop\Model\Hotspot::class,
            \Magenest\InstagramShop\Model\ResourceModel\Hotspot::class
        );
    }

    /**
     * @param int $type
     * @return $this
     */
    public function addTypeFilter($type)
    {
        $this->addFieldToFilter('type', $type);
        return $this;
    }
}
