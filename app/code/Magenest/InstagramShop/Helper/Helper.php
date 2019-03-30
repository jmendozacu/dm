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

class Helper extends AbstractHelper
{
    const CAN_SHOW_VIDEO_PATH   = 'magenest_instagram_shop/general/media_type';
    const GALLERY_TEMPLATE_GRID = 'magenest_instagram_shop/general/gallery_template';
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var \Magenest\InstagramShop\Model\HotspotFactory
     */
    protected $hotspotFactory;

    /**
     * Helper constructor.
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param \Magenest\InstagramShop\Model\HotspotFactory $hotspotFactory
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        \Magenest\InstagramShop\Model\HotspotFactory $hotspotFactory
    )
    {
        $this->_objectManager = $objectManager;
        $this->hotspotFactory = $hotspotFactory;
        parent::__construct($context);
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
     * @param $imageSource string Image URL
     * @param $x int px
     * @param $y int px
     * @return array
     */
    public function getPercentResolution($imageSource, $x, $y)
    {
        try {
            list($width, $height) = getimagesize($imageSource);
        } catch (\Exception $e) {
            list($width, $height) = [640, 640];
        }
        return [
            'x' => 100 * round(intval($x) / $width, 2),
            'y' => 100 * round(intval($y) / $height, 2)
        ];
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
        return (string)$this->scopeConfig->getValue(self::GALLERY_TEMPLATE_GRID);
    }
}