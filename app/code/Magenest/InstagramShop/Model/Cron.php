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

/**
 * Class Cron
 * @package Magenest\InstagramShop\Model
 */
class Cron
{
    /**
     * @var Client
     */
    protected $_client;

    /**
     * @var PhotoFactory
     */
    protected $_photoFactory;

    /**
     * @var TaggedPhotoFactory
     */
    protected $_taggedPhotoFactory;

    /**
     * @var Photo[]
     */
    protected $photoItems;
    /**
     * @var TaggedPhoto[]
     */
    protected $taggedPhotoItems;

    /**
     * Cron constructor.
     * @param Client $client
     * @param PhotoFactory $photoFactory
     * @param TaggedPhotoFactory $taggedPhotoFactory
     */
    public function __construct(
        Client $client,
        PhotoFactory $photoFactory,
        TaggedPhotoFactory $taggedPhotoFactory
    )
    {
        $this->_client             = $client;
        $this->_photoFactory       = $photoFactory;
        $this->_taggedPhotoFactory = $taggedPhotoFactory;
    }

    /**
     * Get tagged photo info from Instagram
     */
    public function getTaggedPhotos()
    {
        // api: https://api.instagram.com/v1/tags/{tag-name}/media/recent?access_token=ACCESS-TOKEN
        $tags = $this->_client->getTags();

        foreach ($tags as $tag) {
            /** Get min ID (get photos after this Min Id) */
            $minTagId = $this->_taggedPhotoFactory->create()
                ->getCollection()
                ->addFieldToFilter('tag_name', $tag)
                ->getLastItem()
                ->getMinTagId();

            if (!empty($minTagId)) {
                $param = ['min_tag_id' => $minTagId];
            } else {
                $param = ['count' => 20];
            }

            $photos = $this->_client->getMediasByTag($tag, $param);

            if (isset($photos['pagination']['min_tag_id']) && isset($photos['data']) && count($photos['data'])) {
                $minTagId = $photos['pagination']['min_tag_id'];
                $photos   = array_reverse($photos['data']);
                foreach ($photos as $photo) {
                    if ($photo['type'] == 'image' || $photo['type'] == 'carousel') {
                        $photoModel = $this->getTaggedPhoto($photo['id']);
                        $photoModel->setDataViaServer($photo, $tag, $minTagId);
                        $photoModel->save();
                    }
                }
            }
        }
    }

    /**
     * @param $photoId
     * @return TaggedPhoto
     */
    private function getTaggedPhoto($photoId)
    {
        $items = $this->getTaggedPhotoItems();
        return isset($items[$photoId]) ? $items[$photoId] : $this->_taggedPhotoFactory->create();
    }

    /**
     * @return TaggedPhoto[]
     */
    private function getTaggedPhotoItems()
    {
        if ($this->taggedPhotoItems === null) {
            $items      = [];
            $collection = $this->_taggedPhotoFactory->create()->getCollection();
            /** @var TaggedPhoto $item */
            foreach ($collection as $item) {
                $items[$item->getPhotoId()] = $item;
            }
            $this->taggedPhotoItems = $items;
        }
        return $this->taggedPhotoItems;
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function getAllPhotos()
    {
        // api: https://api.instagram.com/v1/users/self/media/recent/?access_token=ACCESS-TOKEN

        $allPhotos = $this->_client->getAllMedias();
        foreach ($allPhotos as $photo) {
            $photoModel = $this->getPhotoItem($photo['id']);
            $photoModel->setDataViaServer($photo);
            $photoModel->save();
        }
    }

    /**
     * @return Photo[]
     */
    private function getPhotoItems()
    {
        if ($this->photoItems === null) {
            $this->photoItems = $this->_photoFactory->create()->getCollection()->getPhotoIdItems();
        }
        return $this->photoItems;
    }

    /**
     * @param $photoId
     * @return Photo
     */
    private function getPhotoItem($photoId)
    {
        $items = $this->getPhotoItems();
        return isset($items[$photoId]) ? $items[$photoId] : $this->_photoFactory->create();
    }
}
