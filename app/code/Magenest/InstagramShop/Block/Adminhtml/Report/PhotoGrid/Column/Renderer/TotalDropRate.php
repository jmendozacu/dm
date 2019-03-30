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

namespace Magenest\InstagramShop\Block\Adminhtml\Report\PhotoGrid\Column\Renderer;

use Magenest\InstagramShop\Model\Report;

/**
 * Class TotalConversionRate
 * @package Magenest\InstagramShop\Block\Adminhtml\Report\PhotoGrid\Column\Renderer
 */
class TotalDropRate extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{
    /**
     * @param Report|\Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        return $row->getTotalDropRateOnImage(true);
    }
}
