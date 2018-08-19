<?php
namespace DiamondMansion\Extensions\Block\Adminhtml\Ring\Eternity;

use \Magento\Backend\Block\Template;

class Width extends \Magento\Backend\Block\Template
{
    public $helper;
    public $scopeConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \DiamondMansion\Extensions\Helper\Data $helper
    ) {
        $this->helper = $helper;
        $this->scopeConfig = $scopeConfig;

        parent::__construct($context);
    }
}