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

namespace Magenest\InstagramShop\Model;

/**
 * Class ReportLog
 * @package Magenest\InstagramShop\Model
 */
class ReportLog extends \Magento\Framework\Model\AbstractModel
{
    protected $_eventPrefix = 'magenest_instagramshop_report_log';

    protected $_eventObject = 'report_log';

    protected function _construct()
    {
        $this->_init('Magenest\InstagramShop\Model\ResourceModel\ReportLog');
    }
}
