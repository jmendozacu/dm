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

namespace Magenest\InstagramShop\Block\Gallery;

use Magenest\InstagramShop\Block\Instagram\Renderer\Hotspot;
use Magenest\InstagramShop\Block\Instagram\Renderer\LinkedProducts;
use Magenest\InstagramShop\Block\Instagram\Renderer\Video;
use Magenest\InstagramShop\Helper\Helper;
use Magenest\InstagramShop\Model\Client;
use Magenest\InstagramShop\Model\Config\Source\GalleryTemplate;
use Magenest\InstagramShop\Model\PhotoFactory;
use Magenest\InstagramShop\Model\TaggedPhotoFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\View\Element\Template;

/**
 * Class Gallery
 * @package Magenest\InstagramShop\Block\Gallery
 */
class Gallery extends Template
{
    /**
     * @var PhotoFactory
     */
    protected $_photoFactory;

    /**
     * @var TaggedPhotoFactory
     */
    protected $_taggedPhotoFactory;

    /**
     * @var Client
     */
    protected $_client;

    /**
     * @var AbstractCollection
     */
    protected $_collection;

    /**
     * @var Helper
     */
    protected $helper;

    protected $photoPerPages = 16;

    /**
     * PhotoList constructor.
     * @param Template\Context $context
     * @param PhotoFactory $photoFactory
     * @param TaggedPhotoFactory $taggedPhotoFactory
     * @param Client $client
     * @param Helper $helper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        PhotoFactory $photoFactory,
        TaggedPhotoFactory $taggedPhotoFactory,
        Client $client,
        Helper $helper,
        array $data = []
    )
    {
        $this->_client             = $client;
        $this->_photoFactory       = $photoFactory;
        $this->_taggedPhotoFactory = $taggedPhotoFactory;
        $this->helper              = $helper;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setCollection($this->getViewParam());
        if (!$this->isDefaultGalleryTemplate()) {
            $this->setPhotoPerPages(18);
        }
    }

    /**
     * @param int $photoPerPages
     */
    public function setPhotoPerPages($photoPerPages)
    {
        $this->photoPerPages = $photoPerPages;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        /** @var \Magento\Theme\Block\Html\Pager */
        $pager = $this->getLayout()->createBlock(
            'Magento\Theme\Block\Html\Pager',
            'instagram.photo.list.pager'
        );
        $pager->setUseContainer(false)
            ->setShowPerPage(false)
            ->setShowAmounts(false)
            ->setFrameLength(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )
            ->setJump(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame_skip',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )
            ->setLimit($this->photoPerPages)
            ->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();

        return $this;
    }

    /**
     * Set photos collection
     * @param $tag
     * @return $this
     */
    public function setCollection($tag)
    {
        /** @var $collection AbstractCollection */
        if (empty($tag)) {
            $collection = $this->_photoFactory->create()->getCollection();
        } else {
            $collection = $this->_taggedPhotoFactory->create()->getCollection()->addTagFilter($tag);
        }
        $collection->setOrder('id', 'DESC');

        $collection->addFieldToFilter('show_in_gallery', 1);

        $this->_collection = $collection;
        return $this;
    }

    /**
     * @return AbstractCollection
     */
    public function getCollection()
    {
        return $this->_collection;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {

        return $this->getChildHtml('pager');
    }

    /**
     * return array of tags from store configuration
     * @return array
     */
    public function getTags()
    {
        return $this->_client->getTags();
    }

    /**
     * @return string
     */
    public function getViewParam()
    {
        return $this->getRequest()->getParam('view');
    }

    /**
     * @return int
     */
    public function getPageParam()
    {
        return $this->getRequest()->getParam('page');
    }

    /**
     * @return bool
     */
    public function canShowVideo()
    {
        return $this->helper->canShowVideo();
    }

    /**
     * @return bool
     */
    public function isDefaultGalleryTemplate()
    {
        return $this->helper->getGalleryTemplate() == GalleryTemplate::DEFAULT_VALUE_TEMPLATE;
    }

    /**
     * @param $photo
     * @return mixed
     */
    public function getHotspotHtml($photo)
    {
        try {
            return $this->getLayout()->createBlock(Hotspot::class)->setPhoto($photo)->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    /**
     * @param $photo
     * @return mixed
     */
    public function getLinkedProductsHtml($photo)
    {
        try {
            return $this->getLayout()->createBlock(LinkedProducts::class)->setPhoto($photo)->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    /**
     * @param $photo
     * @return mixed
     */
    public function getVideoHtml($photo)
    {
        try {
            return $this->getLayout()->createBlock(Video::class)->setPhoto($photo)->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }
}