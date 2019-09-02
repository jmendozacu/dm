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

use Magenest\InstagramShop\Helper\Helper;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\UrlInterface;

/**
 * Class Topmenu
 * @package Magenest\InstagramShop\Plugin
 */
class Topmenu
{

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var UrlInterface
     */
    protected $_urlInterface;

    /**
     * @var Http
     */
    protected $_request;

    /**
     * Topmenu constructor.
     * @param Helper $helper
     * @param UrlInterface $url
     * @param Http $_request
     */
    public function __construct(
        Helper $helper,
        UrlInterface $url,
        Http $_request
    ) {
        $this->_request      = $_request;
        $this->helper        = $helper;
        $this->_urlInterface = $url;
    }

    /**
     * @param \Magento\Theme\Block\Html\Topmenu $subject
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @return array
     */
    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '', $childrenWrapClass = '', $limit = 0
    ) {
        if ($this->helper->isAddLinkToFrontend()) {
            $menu = $subject->getMenu();
            $tree = $menu->getTree();
            $data = [
                'name'      => $this->helper->getMenuTitle(),
                'url'       => $this->helper->getBaseGalleryUrl(),
                'id'        => 'instagram-gallery',
                'is_active' => $this->isActive()
            ];
            $node = new Node($data, 'id', $tree, $menu);
            $menu->addChild($node);
        }
        return [$outermostClass, $childrenWrapClass, $limit];
    }

    /**
     * @return bool
     */
    private function isActive()
    {
        return $this->_request->getRouteName() == 'instagram' && $this->_request->getControllerName() == 'gallery';
    }
}
