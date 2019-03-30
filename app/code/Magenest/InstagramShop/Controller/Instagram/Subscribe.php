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

namespace Magenest\InstagramShop\Controller\Instagram;

use Magento\Framework\Controller\ResultFactory;

/**
 * Class Subscribe
 * @package Magenest\InstagramShop\Controller\Instagram
 */
class Subscribe extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magenest\InstagramShop\Model\PhotoFactory
     */
    protected $_photoFactory;

    /**
     * @var \Magento\Backend\App\ConfigInterface
     */
    protected $_config;

    /**
     * @var string
     */
    protected $create_tag;

    /**
     * @var string
     */
    protected $thumbnail_tag;

    /**
     * @var string
     */
    protected $account_id;

    /**
     * Subscribe constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magenest\InstagramShop\Model\PhotoFactory $photoFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magenest\InstagramShop\Model\PhotoFactory $photoFactory,
        \Magento\Backend\App\ConfigInterface $config
    ) {
        $this->_config = $config;
        $this->_photoFactory = $photoFactory;
        $this->create_tag = $this->_config->getValue('magenest_instagram_shop/instagram_product/product_tag');
        $this->thumbnail_tag = $this->_config->getValue('magenest_instagram_shop/instagram_product/thumbnail_tag');
        $this->account_id = $this->_config->getValue('magenest_instagram_shop/instagram/account_id');
        parent::__construct($context);
    }

    public function execute()
    {
        $hubChallenge = $this->getRequest()->getParam('hub_challenge');
        if (!empty($hubChallenge)) {
            /** answer instagram's subscribe challenge */
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_RAW);
            $resultPage->setContents($hubChallenge);
            return $resultPage;
        } else {
            /** Receive update notification and process data */
            $updates = json_decode(file_get_contents('php://input'), true);

            foreach ($updates as $update) {
                if ($update['object_id'] != $this->account_id) {
                    continue;
                }
                if (isset($update['data'])) {
                    $this->getPhoto($update['data']['media_id']);
                }
            }
        }
    }

    /**
     * Get new photo on instagram
     * @param $mediaId
     */
    protected function getPhoto($mediaId)
    {
//      https://api.instagram.com/v1/media/{media-id}?access_token=ACCESS-TOKEN

        /** @var \Magenest\InstagramShop\Model\Client $client */
        $client = $this->_objectManager->create('Magenest\InstagramShop\Model\Client');
        $endpoint = '/media/' . $mediaId;

        /** retrieve photo's info */
        $photo = $client->api($endpoint, 'GET');

        if (isset($photo['data'])) {
            $photo = $photo['data'];

            if ($photo['type'] == 'image') {
                $create = false;
                $thumbnail = false;

                /** check hashtags for creating products/thumbnails */
                foreach ($photo['tags'] as $key => $tag) {
                    if ($tag == $this->thumbnail_tag) {
                        $thumbnail = true;
                        break;
                    }
                    if ($tag == $this->create_tag) {
                        $create = true;
                    }
                    if (strpos($tag, "usd") !== false) {
                        str_replace("usd", "", $tag);
                        $price = (float)$tag;
                    }
                }
                if ($thumbnail) {
                    $productId = $this->saveThumbnail($photo['images']['standard_resolution']['url']);
                } elseif ($create && isset($price)) {
                    $name = 'Product-' . substr(md5($photo['link']), -10);
                    $productId = $this->createProduct(
                        $name,
                        $price,
                        $photo['caption']['text'],
                        $photo['images']['standard_resolution']['url']
                    );
                } else {
                    $productId = null;
                }
                $this->savePhoto($photo, $productId);
            }
        }
    }

    /**
     * save thumbnail to the previously created Product
     * @param string $imgUrl
     * @return null|int
     */
    protected function saveThumbnail($imgUrl)
    {
        $productId = $this->_photoFactory->create()
            ->getCollection()
            ->getLastItem()
            ->getProductId();
        if ($productId) {
            /** @var \Magento\Catalog\Model\Product $simple_product */
            $simple_product = $this->_objectManager
                ->create('\Magento\Catalog\Model\Product')
                ->load($productId);
            if ($simple_product->getId()) {
                //product loaded
                if ($this->thumbnailExist($simple_product, $imgUrl)) {
                    //thumnail existed
                    return null;
                }

                $simple_product->addImageToMediaGallery(
                    $this->saveImage($imgUrl),
                    ['thumbnail'],
                    false,
                    false
                );
                $simple_product->save();
            }
            return $productId;
        } else {
            return null;
        }
    }

    /**
     * save photo info to database
     * @param array $info
     * @param int $productId
     */
    protected function savePhoto($info, $productId)
    {
        if ($this->photoExist($info['id'])) {
            return;
        }
        $photo = $this->_photoFactory->create();
        $data = [
            'photo_id' => $info['id'],
            'url' => $info['link'],
            'source' => $info['images']['standard_resolution']['url'],
            'caption' => $info['caption']['text'],
            'product_id' => $productId,
            'likes' => $info['likes']['count'],
            'comments' => $info['comments']['count']
        ];
        $photo->setData($data);
        $photo->save();
    }

    /**
     * create a product with given info
     * @param string $name
     * @param float $price
     * @param string $imgUrl
     * @return int|null
     */
    protected function createProduct($name, $price, $caption, $imgUrl)
    {
        try {
            /** @var \Magento\Catalog\Model\Product $simple_product */
            $simple_product = $this->_objectManager->create('\Magento\Catalog\Model\Product');
            $simple_product->setSku($name);
            $simple_product->setName($name);
            $simple_product->setAttributeSetId(4);
            $simple_product->setStatus(1);
            $simple_product->setTypeId('simple');
            $simple_product->setPrice((float)$price);
            $simple_product->setWebsiteIds([1]);
            $simple_product->setStockData([
                'use_config_manage_stock' => 0,
                'manage_stock' => 1,
                'is_in_stock' => 1,
                'qty' => 1
            ]);
            $simple_product->setDescription($caption);
            /**  assigning image, thumb and small image to media gallery */
            $simple_product->addImageToMediaGallery(
                $this->saveImage($imgUrl),
                ['image', 'thumbnail', 'small_image'],
                false,
                false
            );
            $simple_product->save();
            return $simple_product->getId();
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        return null;
    }

    /**
     * save image from external url to ./media/import
     * @param string $imgUrl
     * @return string
     */
    protected function saveImage($imgUrl)
    {
        /** @var \Magento\Framework\App\Filesystem\DirectoryList $dir */
        $dir = $this->_objectManager->get('\Magento\Framework\App\Filesystem\DirectoryList');
        $image_type = 'jpg';
        $filename = md5($imgUrl) . '.' . $image_type;
        $filepath = $dir->getPath('media') . '/' . 'import' . '/' . $filename;
        file_put_contents($filepath, file_get_contents(trim($imgUrl)));
        return $filepath;
    }

    /**
     * check if photo already exist
     * @param string $photoId
     * @return int
     */
    protected function photoExist($photoId)
    {
        return $this->_photoFactory->create()
            ->getCollection()
            ->addFieldtoFilter('photo_id', $photoId)
            ->count();
    }

    /**
     * check if thumbnail already exist
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imgUrl
     * @return boolean
     */
    protected function thumbnailExist($product, $imgUrl)
    {
        $media = $product->getMediaGalleryImages()->toArray();
        if (isset($media['items'])) {
            $media = $media['items'];
            $imgFile = md5($imgUrl);
            foreach ($media as $image) {
                if (strpos($image['file'], $imgFile) !== false) {
                    return true;
                }
            }
        }
        return false;
    }
}
