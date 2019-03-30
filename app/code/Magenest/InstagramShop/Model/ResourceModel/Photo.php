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

namespace Magenest\InstagramShop\Model\ResourceModel;

/**
 * Class Photo
 * @package Magenest\InstagramShop\Model\ResourceModel
 */
class Photo extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * DB connection
     *
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;

    protected function _construct()
    {
        $this->_init('magenest_instagram_photo', 'id');
        $this->connection = $this->getConnection();
    }

    /**
     * @param \Magenest\InstagramShop\Model\Photo $photo
     * @param $photoId
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function loadByPhotoId(\Magenest\InstagramShop\Model\Photo $photo, $photoId)
    {
        $select = $this->connection->select()->from($this->getMainTable())->where('photo_id=:photo_id');
        $id     = $this->connection->fetchOne($select, ['photo_id' => $photoId]);
        if ($id) {
            $this->load($photo, $id);
        } else {
            $photo->setData([]);
        }
        return $this;
    }
}
