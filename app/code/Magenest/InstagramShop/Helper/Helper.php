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

namespace Magenest\InstagramShop\Helper;

use Magenest\InstagramShop\Model\Config\Source\MediaType;
use Magenest\InstagramShop\Model\Hotspot;
use Magenest\InstagramShop\Model\Photo;
use Magenest\InstagramShop\Model\TaggedPhoto;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Helper extends AbstractHelper
{
    const ADD_INSTAGRAM_LINK_TO_STOREFRONT = 'magenest_instagram_shop/general/add_link_to_frontend';
    const MENU_TITLE                       = 'magenest_instagram_shop/general/menu_title';
    const GALLERY_URL                      = 'magenest_instagram_shop/general/gallery_url';
    const CAN_SHOW_VIDEO_PATH              = 'magenest_instagram_shop/general/media_type';
    const GALLERY_TEMPLATE_GRID            = 'magenest_instagram_shop/general/gallery_template';
    const ADD_INSTAGRAM_TO_PRODUCT_DETAIL  = 'magenest_instagram_shop/general/add_to_product_detail';
    const MEDIA_TYPE                       = 'magenest_instagram_shop/general/media_type';
    const BLOCK_TITLE                      = 'magenest_instagram_shop/general/block_title';
    const BLOCK_CONTENT                    = 'magenest_instagram_shop/general/block_content';
    const BUTTON_TITLE                     = 'magenest_instagram_shop/general/button_title';
    const BUTTON_CSS                       = 'magenest_instagram_shop/general/button_css';
    const HOVER_TEXT                       = 'magenest_instagram_shop/general/hover_text';
    const HASH_TAG                         = 'magenest_instagram_shop/general/hash_tag';
    const FEATURED_PRODUCT_LAYOUT          = 'magenest_instagram_shop/general/linked_products_layout';
    const INSTAGRAM_PHOTOS_ATTRIBUTE_CODE  = 'instagram_photos';

    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magenest\InstagramShop\Model\HotspotFactory
     */
    protected $hotspotFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Helper constructor.
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param \Magenest\InstagramShop\Model\HotspotFactory $hotspotFactory
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        \Magenest\InstagramShop\Model\HotspotFactory $hotspotFactory
    ) {
        parent::__construct($context);
        $this->hotspotFactory = $hotspotFactory;
        $this->_objectManager = $objectManager;
        $this->_storeManager  = $storeManager;
    }

    /**
     * @param $photoId
     * @param $type
     * @return Photo|TaggedPhoto
     */
    public function getPhoto($photoId, $type = Photo::TYPE)
    {
        switch ((int)$type) {
            case 1:
                $factory = $this->_objectManager->create(Photo::class);
                break;
            case 2:
                $factory = $this->_objectManager->create(TaggedPhoto::class);
                break;
            default:
                $factory = $this->_objectManager->create(Photo::class);
        }
        $photo = $factory->loadByPhotoId($photoId);
        return $photo;
    }

    /**
     * @param Photo $photo string Image URL
     * @param $x int px
     * @param $y int px
     * @return array
     */
    public function getPercentResolution($photo, $x, $y)
    {
        list($width, $height) = $this->getImageSize($photo);
        return [
            'x' => 100 * round(intval($x) / $width, 2),
            'y' => 100 * round(intval($y) / $height, 2)
        ];
    }

    /**
     * @param Photo $photo
     * @return array
     */
    protected function getImageSize($photo)
    {
        if ($response = $photo->getResponse()) {
            $response = json_decode($response, true);
            if (json_last_error() == JSON_ERROR_NONE) {
                $standardResolution = $response['images']['standard_resolution'];
                return [
                    $standardResolution['width'],
                    $standardResolution['height']
                ];
            }
        }
        return [640, 640];
    }

    /**
     * @param string $photoId
     * @param int $type
     * @return Hotspot
     */
    public function getHotspotByPhoto($photoId, $type = Photo::TYPE)
    {
        return $this->hotspotFactory->create()->loadByPhotoIdAndType($photoId, $type);
    }

    /**
     * @param string $url
     * @return string
     */
    public function encodeUrl($url)
    {
        return $this->urlEncoder->encode($url);
    }

    /**
     * @param string $url
     * @param array $params
     * @param string $action
     * @param string $controller
     * @param string $route
     * @param string $delimiter
     * @return string
     */
    public function getEncodedLink($url, $params = [], $action = 'link', $controller = 'instagram', $route = 'instagram', $delimiter = '/')
    {
        $routePath = $route . $delimiter . $controller . $delimiter . $action;
        return $this->_urlBuilder->getUrl($routePath, array_merge(['key' => $this->encodeUrl($url)], $params));
    }

    /**
     * @return bool
     */
    public function canShowVideo()
    {
        return (int)$this->scopeConfig->getValue(self::CAN_SHOW_VIDEO_PATH) === MediaType::BOTH_IMAGE_AND_VIDEO;
    }

    /**
     * @return string
     */
    public function getGalleryTemplate()
    {
        return (string)$this->scopeConfig->getValue(self::GALLERY_TEMPLATE_GRID, ScopeInterface::SCOPE_STORES);
    }

    /**
     * @return string
     */
    public function getGalleryUrl()
    {
        $url = $this->scopeConfig->getValue(self::GALLERY_URL);
        if (empty($url)) {
            $url = 'instagram/gallery';
        }
        return (string)$url;
    }

    /**
     * @return string
     */
    public function getBaseGalleryUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl() . $this->getGalleryUrl();
    }

    /**
     * @return int
     */
    public function getMediaType()
    {
        return (int)$this->scopeConfig->getValue(self::MEDIA_TYPE);
    }

    /**
     * @return bool
     */
    public function isAddLinkToFrontend()
    {
        return $this->scopeConfig->isSetFlag(self::ADD_INSTAGRAM_LINK_TO_STOREFRONT, ScopeInterface::SCOPE_STORES);
    }

    /**
     * @return string
     */
    public function getMenuTitle()
    {
        return $this->scopeConfig->getValue(self::MENU_TITLE, ScopeInterface::SCOPE_STORES);
    }

    /**
     * @return bool
     */
    public function isAddInstagramToProduct()
    {
        return $this->scopeConfig->isSetFlag(self::ADD_INSTAGRAM_TO_PRODUCT_DETAIL);
    }

    /**
     * @return string
     */
    public function getViewFullGalleryTitle()
    {
        return (string)$this->scopeConfig->getValue(self::BUTTON_TITLE, ScopeInterface::SCOPE_STORES);
    }

    /**
     * @return string
     */
    public function getViewFullGalleryCss()
    {
        return (string)$this->scopeConfig->getValue(self::BUTTON_CSS, ScopeInterface::SCOPE_STORES);
    }

    /**
     * @return string
     */
    public function getHoverText()
    {
        return (string)$this->scopeConfig->getValue(self::HOVER_TEXT, ScopeInterface::SCOPE_STORES);
    }

    /**
     * @return string
     */
    public function getBlockTitle()
    {
        return (string)$this->scopeConfig->getValue(self::BLOCK_TITLE, ScopeInterface::SCOPE_STORES);
    }

    /**
     * @return string
     */
    public function getBlockContent()
    {
        return (string)$this->scopeConfig->getValue(self::BLOCK_CONTENT, ScopeInterface::SCOPE_STORES);
    }

    /**
     * @return string
     */
    public function getFeaturedProductLayout()
    {
        return $this->scopeConfig->getValue(self::FEATURED_PRODUCT_LAYOUT, ScopeInterface::SCOPE_STORES);
    }

    /**
     * @return string
     */
    public function getHashTag()
    {
        return (string)$this->scopeConfig->getValue(self::HASH_TAG);
    }
}