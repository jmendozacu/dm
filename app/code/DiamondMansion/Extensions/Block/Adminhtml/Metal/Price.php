<?php
/**
 * Created by May.
 * User: May
 * Date: 8/19/2018
 * Time: 5:25 PM
 */

namespace DiamondMansion\Extensions\Block\Adminhtml\Metal;

use \Magento\Backend\Block\Template;

class Price extends \Magento\Backend\Block\Template
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