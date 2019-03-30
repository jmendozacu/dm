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
 * Class Report
 * @package Magenest\InstagramShop\Model\ResourceModel
 */
class Report extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * DB connection
     *
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;

    protected function _construct()
    {
        $this->_init('magenest_instagramshop_report', 'id');
        $this->connection = $this->getConnection();
    }

    /**
     * @param \Magenest\InstagramShop\Model\Report $report
     * @param string $photoId
     * @param int $type
     * @return Report
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function loadByPhotoIdAndType(\Magenest\InstagramShop\Model\Report $report, $photoId, $type)
    {
        $select = $this->connection->select()->from($this->getMainTable())
            ->where('photo_id=:photo_id')
            ->where('type=:type');

        $reportId = $this->connection->fetchOne($select, ['photo_id' => $photoId, 'type' => $type]);
        if ($reportId) {
            $this->load($report, $reportId);
        } else {
            $report->setData([]);
        }
        return $this;
    }

    /**
     * @param array $data
     */
    public function saveToLog($data = array())
    {
        $reportLogTable = $this->getTable('magenest_instagramshop_reportlog');
        $this->connection->insert($reportLogTable, $data);
    }
}
