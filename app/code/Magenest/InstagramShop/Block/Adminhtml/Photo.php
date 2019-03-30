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

namespace Magenest\InstagramShop\Block\Adminhtml;

/**
 * Class Photo
 * @package Magenest\InstagramShop\Block\Adminhtml
 */
class Photo extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Photo constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->removeButton('add');
    }

    protected function _construct()
    {
        $this->_blockGroup = 'Magenest_InstagramShop';
        $this->_controller = 'adminhtml_instagram';
        parent::_construct();
    }
}
