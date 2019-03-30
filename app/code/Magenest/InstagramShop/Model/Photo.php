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

namespace Magenest\InstagramShop\Model;

use Magenest\InstagramShop\Model\Config\Source\MediaType;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;

/**
 * Class Photo
 * @package Magenest\InstagramShop\Model
 */
class Photo extends \Magento\Framework\Model\AbstractModel
{
    const TYPE = 1;
    protected $_photoId = 'photo_id';

    protected $_url = 'url';

    protected $_source = 'source';

    protected $_caption = 'caption';

    protected $_productId = 'product_id';

    protected $_likes = 'likes';

    protected $_comments = 'comments';

    const SHOW_IN_WIDGET   = 'show_in_widget';
    const CREATED_AT       = 'created_at';
    const VIDEO_SOURCE_KEY = 'video_source';

    protected $_eventPrefix = 'magenest_instagramshop_photo';

    protected $_eventObject = 'photo';
    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Photo constructor.
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ScopeConfigInterface $scopeConfig
     * @param AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        ScopeConfigInterface $scopeConfig,
        AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('Magenest\InstagramShop\Model\ResourceModel\Photo');
    }

    /**
     * @param $photoId
     * @return $this
     */
    public function loadByPhotoId($photoId)
    {
        $this->getResource()->loadByPhotoId($this, $photoId);
        return $this;
    }

    /**
     * @param $mediaInfo array
     * @throws \Exception
     */
    public function setDataViaServer($mediaInfo)
    {
        if (!$this->getId()) {
            $this->setPhotoId($mediaInfo['id'])->setUrl($mediaInfo['link'])->setCreatedAt(date('Y-m-d',$mediaInfo['created_time']));
        }
        // update likes, comments, caption
        $this->setLikes($mediaInfo['likes']['count'])
            ->setCaption($mediaInfo['caption']['text'])
            ->setComments($mediaInfo['comments']['count']);

        // fix url signature expired
        $this->setSource($mediaInfo['images']['standard_resolution']['url']);

        if ($this->canGetVideo() && isset($mediaInfo['videos']['standard_resolution'])) {
            $this->setVideoSource($mediaInfo['videos']['standard_resolution']['url']);
        }
    }

    /**
     * @return bool
     */
    protected function canGetVideo()
    {
        return (int)$this->_scopeConfig->getValue('magenest_instagram_shop/general/media_type') === MediaType::BOTH_IMAGE_AND_VIDEO;
    }

    /**
     * @param string $productIds
     * @return array|mixed|string
     */
    public function getExplodedProductId($productIds = '')
    {
        if (!$productIds) {
            $productIds = $this->getProductIds();
        }
        if (is_array($productIds)) {
            return $productIds;
        } else if (is_string($productIds)) {
            $productIds = str_replace(' ', '', $productIds);
            $productIds = explode(',', $productIds);

            $result = [];
            foreach ($productIds as $productId) {
                $result[$productId] = $productId;
            }
            return $result;
        } else return [];
    }

    /**
     * @param array $productIds
     * @return $this
     */
    public function setExplodedProductIds($productIds)
    {
        if (is_array($productIds)) {
            $productIds = implode(', ', $productIds);
            $this->setProductId($productIds);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoId()
    {
        return $this->getData($this->_photoId);
    }

    /**
     * @param mixed $photoId
     * @return $this
     */
    public function setPhotoId($photoId)
    {
        $this->setData($this->_photoId, $photoId);
        return $this;
    }

    /**
     * @param string $source
     * @return $this
     */
    public function setVideoSource($source)
    {
        $this->setData(self::VIDEO_SOURCE_KEY, $source);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVideoSource()
    {
        return $this->getData(self::VIDEO_SOURCE_KEY);
    }

    /**
     * @return int
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->getData($this->_url);
    }

    /**
     * @param mixed $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->setData($this->_url, $url);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->getData($this->_source);
    }

    /**
     * @param mixed $source
     * @return $this
     */
    public function setSource($source)
    {
        $this->setData($this->_source, $source);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCaption()
    {
        return $this->getData($this->_caption);
    }

    /**
     * @param mixed $caption
     * @return $this
     */
    public function setCaption($caption)
    {
        $this->setData($this->_caption, $caption);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductIds()
    {
        return $this->getData($this->_productId);
    }

    /**
     * @param mixed $productId
     * @return $this
     */
    public function setProductId($productId)
    {
        $this->setData($this->_productId, $productId);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->getData($this->_likes);
    }

    /**
     * @param mixed $likes
     * @return $this
     */
    public function setLikes($likes)
    {
        $this->setData($this->_likes, $likes);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->getData($this->_comments);
    }

    /**
     * @param mixed $comments
     * @return $this
     */
    public function setComments($comments)
    {
        $this->setData($this->_comments, $comments);
        return $this;
    }

    /**
     * @param mixed $visibility
     * @return $this
     */
    public function setShowInWidget($visibility)
    {
        $this->setData(self::SHOW_IN_WIDGET, $visibility);
        return $this;
    }

    /**
     * @return boolean
     */
    public function isShowedInWidget()
    {
        return $this->getData(self::SHOW_IN_WIDGET);
    }

    /**
     * @param $time
     * @return $this
     */
    public function setCreatedAt($time)
    {
        $this->setData(self::CREATED_AT, $time);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }
}
