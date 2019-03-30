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

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\UrlInterface;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Button
 * @package Magenest\InstagramShop\Block\Adminhtml\System\Config\Form
 */
class Button extends Field
{
    /**
     * @var string
     */
    protected $defaultButtonLabel = 'Get Photos';
    /**
     * @var UrlInterface
     */
    protected $_backendUrl;

    /**
     * Button constructor.
     * @param Context $context
     * @param UrlInterface $url
     * @param array $data
     */
    public function __construct(
        Context $context,
        UrlInterface $url,
        array $data = [])
    {
        $this->_backendUrl = $url;
        parent::__construct($context, $data);
    }

    /**
     * Unset some non-related element parameters
     *
     * @param  AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $originalData  = $element->getOriginalData();
        $buttonLabel   = !empty($originalData['button_label']) ? $originalData['button_label'] : $this->defaultButtonLabel;
        $buttonUrlPath = !empty($originalData['button_url']) ? $originalData['button_url'] : '';
        $element->setData([
            'type' => 'button',
            'class' => 'action-default scalable action-save action-secondary',
            'value' => _($buttonLabel),
            'onclick' => "setLocation('" . $this->_backendUrl->getUrl($buttonUrlPath) . "')"
        ]);
        return $element->getElementHtml();
    }
}
