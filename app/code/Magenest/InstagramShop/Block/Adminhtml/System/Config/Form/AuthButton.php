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

use Magenest\InstagramShop\Model\Client;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class AuthButton
 * @package Magenest\InstagramShop\Block\System\Config\Form
 */
class AuthButton extends \Magento\Config\Block\System\Config\Form\Field
{
    const AUTHORIZATION_BUTTON_TEMPLATE_PATH = 'system/config/authorization.phtml';
    const BUTTON_LABEL = 'Get Authorization';

    /**
     * @var Client
     */
    protected $_client;

    /**
     * @param Client $client
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Client $client,
        Context $context,
        array $data = array()
    )
    {
        $this->_client = $client;
        parent::__construct($context, $data);
    }


    protected function _prepareLayout()
    {
        if (!$this->getTemplate()) {
            $this->setTemplate(self::AUTHORIZATION_BUTTON_TEMPLATE_PATH);
        }
        return parent::_prepareLayout();
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

    public function getButtonLabel()
    {
        return self::BUTTON_LABEL;
    }
    /**
     * @return string
     */
    public function getButtonUrl()
    {
        return $this->_client->createAuthUrl();
    }

    /**
     * create element for authorization button in store configuration
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $this->setData([
            'html_id' => $element->getHtmlId()
        ]);

        return $this->_toHtml();
    }
}
