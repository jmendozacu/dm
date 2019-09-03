<?php
/**
 * MageVision Mini Cart Coupon Extension
 *
 * @category     MageVision
 * @package      MageVision_MiniCartCoupon
 * @author       MageVision Team
 * @copyright    Copyright (c) 2018 MageVision (http://www.magevision.com)
 * @license      LICENSE_MV.txt or http://www.magevision.com/license-agreement/
 */
namespace MageVision\MiniCartCoupon\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Module\ModuleListInterface;

class Data extends AbstractHelper
{
    const XML_PATH_ENABLED            = 'minicartcoupon/general/enabled';
    const XML_PATH_DISPLAY_GRANDTOTAL = 'minicartcoupon/general/display_grand_total';
    const XML_PATH_DISPLAY_DISCOUNT   = 'minicartcoupon/general/display_discount';
    const MODULE_NAME                 = 'Mini Cart Coupon';

    /**
     * @var ModuleListInterface;
     */
    protected $moduleList;

    /**
     * @param Context $context
     * @param ModuleListInterface $moduleList
     */
    public function __construct(
        Context $context,
        ModuleListInterface $moduleList
    ) {
        $this->moduleList = $moduleList;
        parent::__construct($context);
    }

    /**
     * Check is Module enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Display Grand Total
     *
     * @return bool
     */
    public function displayGrandTotal()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_DISPLAY_GRANDTOTAL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Display Discount Amount
     *
     * @return bool
     */
    public function displayDiscount()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_DISPLAY_DISCOUNT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Returns extension version.
     *
     * @return string
     */
    public function getExtensionVersion()
    {
        $moduleInfo = $this->moduleList->getOne($this->getModuleName());
        return $moduleInfo['setup_version'];
    }

    /**
     * Returns module's name
     *
     * @return string
     */
    public function getModuleName()
    {
        $classArray = explode('\\', get_class($this));

        return count($classArray) > 2 ? "{$classArray[0]}_{$classArray[1]}" : '';
    }

    /**
     * Returns extension name.
     *
     * @return string
     */
    public function getExtensionName()
    {
        return self::MODULE_NAME;
    }
}
