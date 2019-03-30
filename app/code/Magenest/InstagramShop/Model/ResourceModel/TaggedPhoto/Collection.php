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

namespace Magenest\InstagramShop\Model\ResourceModel\TaggedPhoto;

/**
 * Class Collection
 * @package Magenest\InstagramShop\Model\ResourceModel\TaggedPhoto
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
        $this->_init('Magenest\InstagramShop\Model\TaggedPhoto', 'Magenest\InstagramShop\Model\ResourceModel\TaggedPhoto');
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function addTagFilter($tag)
    {
        $this->addFieldToFilter('tag_name', $tag);
        return $this;
    }

    /**
     * @param $ids
     * @return $this
     */
    public function addIdsFilter($ids)
    {
        $this->addFieldToFilter($this->getIdFieldName(), ['in' => $ids]);
        return $this;
    }
}
