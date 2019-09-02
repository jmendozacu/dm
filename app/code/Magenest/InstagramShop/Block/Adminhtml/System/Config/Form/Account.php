<?php

namespace Magenest\InstagramShop\Block\Adminhtml\System\Config\Form;

use Magenest\InstagramShop\Model\Client;

/**
 * Class Account
 * @package Magenest\InstagramShop\Block\Adminhtml\System\Config\Form
 */
class Account extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var string
     */
    protected $_template = 'system/config/account.phtml';

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue()->unsLabel();
        return parent::render($element);
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getInstagramAccount()
    {
        $account = $this->_scopeConfig->getValue(Client::XML_PATH_ACCOUNT);
        $account = json_decode($account, true);
        if (!is_array($account)) {
            $account = [];
        }
        return new \Magento\Framework\DataObject($account);
    }
}