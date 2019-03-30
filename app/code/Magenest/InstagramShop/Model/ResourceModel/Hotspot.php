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

class Hotspot extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface|false
     */
    protected $connection;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magenest_instagramshop_hotspot', 'hotspot_id');
        $this->connection = $this->getConnection();
    }

    /**
     * @param \Magenest\InstagramShop\Model\Hotspot $hotspot
     * @param $photoId
     * @param $type
     * @return Hotspot
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function loadByPhotoIdAndType(\Magenest\InstagramShop\Model\Hotspot $hotspot, $photoId, $type)
    {
        $select    = $this->connection->select()->from($this->getMainTable())
            ->where('photo_id=:photo_id')
            ->where('type=:type');
        $hotspotId = $this->connection->fetchOne($select, ['photo_id' => $photoId, 'type' => $type]);
        if ($hotspotId) {
            $this->load($hotspot, $hotspotId);
        } else {
            $hotspot->setData([]);
        }
        return $this;
    }
}
