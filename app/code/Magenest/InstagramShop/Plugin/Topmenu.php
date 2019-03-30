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

namespace Magenest\InstagramShop\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;

/**
 * Class Topmenu
 * @package Magenest\InstagramShop\Plugin
 */
class Topmenu
{
    protected $_scopeConfig;

    protected $_urlInterface;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        UrlInterface $url
    )
    {
        $this->_urlInterface = $url;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @param \Magento\Theme\Block\Html\Topmenu $subject
     * @param $result
     * @return string
     */
    public function afterGetHtml(\Magento\Theme\Block\Html\Topmenu $subject, $result)
    {
        if ($this->isAddLinkToFrontend())
            $result .= '<li class="level0 nav-10 level-top parent ui-menu-item"><a href="' . $this->_urlInterface->getUrl('instagram/gallery') . '" class="level-top ui-corner-all" role="presentation">Instagram Gallery</a></li>';

        return $result;
    }

    protected function isAddLinkToFrontend()
    {
        return $this->_scopeConfig->getValue('magenest_instagram_shop/general/add_link_to_frontend', 'default', 0);
    }
}
