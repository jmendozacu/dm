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

namespace Magenest\InstagramShop\Block\Adminhtml\System\Config\Form;

use Magento\Config\Block\System\Config\Form\Field;

/**
 * Class DisabledField
 * @package Magenest\InstagramShop\Block\Adminhtml\System\Config\Form
 */
class DisabledField extends Field
{
    /**
     * create element for Access token field in store configuration
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->setReadonly(true);
        return parent::_getElementHtml($element);
    }
}
