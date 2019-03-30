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

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;

/**
 * Class Report
 * @package Magenest\InstagramShop\Model
 */
class Report extends AbstractModel
{
    const ID_KEY           = 'id';
    const PHOTO_ID_KEY     = 'photo_id';
    const TYPE_KEY         = 'type';
    const TOTAL_CLICK_KEY  = 'total_click';
    const PRODUCT_DATA_KEY = 'product_data';
    const CREATED_AT_KEY   = 'created_at';
    const UPDATED_AT_KEY   = 'updated_at';

    const TOTAL_PRODUCTS_CLICK_KEY  = 'click';
    const TOTAL_CONVERSION_RATE_KEY = 'conversion_rate';
    const TOTAL_DROP_RATE_KEY       = 'drop_rate';
    const TOTAL_ADDED_TO_CART_KEY   = 'added_to_cart';

    const PRODUCT_DATA_DEFAULT_VALUE = 'a:0:{}';

    /**
     * @var bool
     */
    protected $hasClickedChange = false;
    /**
     * @var bool
     */
    protected $hasAddedChange = false;
    /**
     * @var bool
     */
    protected $hasOrderedChange = false;

    protected $_eventPrefix = 'magenest_instagramshop_report';

    protected $_eventObject = 'report';

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_dateTime;
    /**
     * @var PhotoFactory
     */
    protected $photoFactory;

    protected function _construct()
    {
        $this->_init('Magenest\InstagramShop\Model\ResourceModel\Report');
    }

    /**
     * Report constructor.
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param PhotoFactory $photoFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        PhotoFactory $photoFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        $this->_dateTime    = $dateTime;
        $this->photoFactory = $photoFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @param $photoId
     * @param $type
     * @return $this
     */
    public function loadByPhotoIdAndType($photoId, $type)
    {
        $this->getResource()->loadByPhotoIdAndType($this, $photoId, $type);
        return $this;
    }

    /**
     * @return Photo
     */
    public function getPhoto()
    {
        return $this->photoFactory->create()->loadByPhotoId($this->getPhotoId());
    }

    /**
     * @param int $number
     * @return $this
     */
    public function increaseTotalClicks($number = 1)
    {
        $this->setData(self::TOTAL_CLICK_KEY, $this->getData(self::TOTAL_CLICK_KEY) + intval($number));
        $this->hasClickedChange = true;
        return $this;
    }

    /**
     * @param string $productData
     * @return array|bool|float|int|mixed|string|null
     */
    public function getUnserializeProductData($productData = '')
    {
        if (!$productData) {
            $productData = $this->getProductData();
        }
        return unserialize($productData);
    }

    /**
     * @param array $productData
     * @return $this
     */
    public function setSerializeProductData($productData)
    {
        if (is_array($productData)) {
            $productData = serialize($productData);
        }
        $this->setProductData($productData);
        return $this;
    }

    /**
     * @param $productId
     * @param string $url
     */
    public function increaseProductClick($productId, $url = '')
    {
        $this->increaseProductData(self::TOTAL_PRODUCTS_CLICK_KEY, $productId, [
            'url' => $url
        ]);
        $this->updateClickedAt($productId);
    }

    /**
     * @param $productId
     */
    public function increaseConversionRate($productId)
    {
        $this->increaseProductData(self::TOTAL_CONVERSION_RATE_KEY, $productId);
        $this->hasOrderedChange = true;
    }

    /**
     * @param $productId
     */
    public function increaseDropRate($productId)
    {
        $this->increaseProductData(self::TOTAL_DROP_RATE_KEY, $productId);
    }

    /**
     * @param $productId
     */
    public function increaseAddedToCart($productId)
    {
        $this->increaseProductData(self::TOTAL_ADDED_TO_CART_KEY, $productId);
        $this->hasAddedChange = true;
    }

    /**
     * @param string $key
     * @param $productId
     * @param array $moreData
     */
    private function increaseProductData($key, $productId, $moreData = [])
    {
        $unserializeProductData = $this->getUnserializeProductData();
        if (is_array($unserializeProductData)) {
            isset($unserializeProductData[$productId]) ?: $unserializeProductData[$productId] = [];
            isset($unserializeProductData[$productId][$key]) ?: $unserializeProductData[$productId][$key] = 0;
            if (!empty($moreData)) {
                foreach ($moreData as $moreKey => $moreValue) {
                    isset($unserializeProductData[$productId][$moreKey]) ?: $unserializeProductData[$productId][$moreKey] = $moreValue;
                }
            }
            // increase +1 the value
            $unserializeProductData[$productId][$key]++;
            // set data
            $this->setSerializeProductData($unserializeProductData);
        }
    }

    /**
     * @param string $key
     * @return int
     */
    private function getProductDataByKey($key)
    {
        $total                  = 0;
        $unserializeProductData = $this->getUnserializeProductData();
        if (is_array($unserializeProductData)) {
            foreach ($unserializeProductData as $datum) {
                if (isset($datum[$key])) {
                    $total += $datum[$key];
                }
            }
        }
        return $total;
    }

    /**
     * @return array
     */
    public function getViewedProducts()
    {
        $result                 = [];
        $unserializeProductData = $this->getUnserializeProductData();
        if (is_array($unserializeProductData)) {
            foreach ($unserializeProductData as $productId => $datum) {
                if (isset($datum[self::TOTAL_PRODUCTS_CLICK_KEY])) {
                    $result[$productId] = $datum[self::TOTAL_PRODUCTS_CLICK_KEY];
                }
            }
        }
        return $result;
    }

    /**
     * @return int
     */
    public function getTotalProductsClicks()
    {
        return $this->getProductDataByKey(self::TOTAL_PRODUCTS_CLICK_KEY);
    }

    /**
     * @return int
     */
    public function getTotalConversion()
    {
        return $this->getProductDataByKey(self::TOTAL_CONVERSION_RATE_KEY);
    }

    /**
     * @param bool $withPercent
     * @param int $round
     * @return float|string
     */
    public function getTotalConversionRateOnImage($withPercent = false, $round = 2)
    {
        return $this->getRate($this->getTotalConversion(), $this->getTotalProductsClicks(), $withPercent, $round);
    }

    /**
     * @param int $total
     * @param bool $withPercent
     * @param int $round
     * @return float|string
     */
    public function getTotalConversionRateOnTotal($total, $withPercent = false, $round = 2)
    {
        return $this->getRate($this->getTotalConversion(), $total, $withPercent, $round);
    }

    /**
     * @param int $needed
     * @param int $total
     * @param bool $withPercent
     * @param int $round
     * @return string
     */
    public function getRate($needed, $total, $withPercent = false, $round = 2)
    {
        if ($total == 0) {
            return $total . (!$withPercent ? '' : ' %');
        }
        return (100 * round($needed / $total, $round)) . (!$withPercent ? '' : ' %');
    }

    /**
     * @return int
     */
    public function getTotalDrop()
    {
        return $this->getProductDataByKey(self::TOTAL_DROP_RATE_KEY);
    }

    /**
     * @param bool $withPercent
     * @param int $round
     * @return float|string
     */
    public function getTotalDropRateOnImage($withPercent = false, $round = 2)
    {
        return $this->getRate($this->getTotalDrop(), $this->getTotalProductsClicks(), $withPercent, $round);
    }

    /**
     * @return int
     */
    public function getTotalAddedToCart()
    {
        return $this->getProductDataByKey(self::TOTAL_ADDED_TO_CART_KEY);
    }

    /**
     * @param bool $withPercent
     * @param int $round
     * @return string
     */
    public function getTotalAddedToCartRate($withPercent = false, $round = 2)
    {
        return $this->getRate($this->getTotalAddedToCart(), $this->getTotalProductsClicks(), $withPercent, $round);
    }

    /**
     * @param $productId
     */
    protected function updateClickedAt($productId)
    {
        $unserializeProductData = $this->getUnserializeProductData();
        $key                    = 'clicked_at';
        if (is_array($unserializeProductData)) {
            isset($unserializeProductData[$productId]) ?: $unserializeProductData[$productId] = [];
            $unserializeProductData[$productId][$key] = $this->_dateTime->gmtDate();

            // set data
            $this->setSerializeProductData($unserializeProductData);
        }
    }

    /**
     * @return string
     */
    public function getPhotoId()
    {
        return $this->getData(self::PHOTO_ID_KEY);
    }

    /**
     * @param string $photo_id
     * @return Report
     */
    public function setPhotoId($photo_id)
    {
        $this->setData(self::PHOTO_ID_KEY, $photo_id);
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT_KEY);
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT_KEY);
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->getData(self::TYPE_KEY);
    }

    /**
     * @param int $type
     * @return $this
     */
    public function setType($type)
    {
        $this->setData(self::TYPE_KEY, $type);
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalClick()
    {
        return $this->getData(self::TOTAL_CLICK_KEY);
    }

    /**
     * @param int $totalClick
     * @return Report
     */
    public function setTotalClick($totalClick)
    {
        $this->setData(self::TOTAL_CLICK_KEY, $totalClick);
        return $this;
    }

    /**
     * @return string
     */
    public function getProductData()
    {
        return $this->getData(self::PRODUCT_DATA_KEY);
    }

    /**
     * @param string serialize $productData
     * @return Report
     */
    public function setProductData($productData)
    {
        $this->setData(self::PRODUCT_DATA_KEY, $productData);
        return $this;
    }

    /**
     * Set default value for product_data if null
     * @return AbstractModel
     */
    public function beforeSave()
    {
        if (!$this->getProductData()) {
            $this->setProductData(self::PRODUCT_DATA_DEFAULT_VALUE);
        }
        return parent::beforeSave();
    }

    public function afterSave()
    {
        $this->saveToReport();
        return parent::afterSave();
    }

    protected function saveToReport()
    {
        $data = [];
        $date = $this->_dateTime->gmtDate();
        if ($this->hasClickedChange) {
            $data['clicked_at']     = $date;
            $this->hasClickedChange = false;
        }
        if ($this->hasAddedChange) {
            $data['added_at']     = $date;
            $this->hasAddedChange = false;
        }
        if ($this->hasOrderedChange) {
            $data['ordered_at']     = $date;
            $this->hasOrderedChange = false;
        }
        if (!empty($data)) {
            $data['report_id'] = $this->getId();
            $this->getResource()->saveToLog($data);
        }
    }
}
