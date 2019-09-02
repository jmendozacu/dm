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

namespace Magenest\InstagramShop\Block\Adminhtml\Report;

use Magenest\InstagramShop\Model\ReportFactory;
use Magenest\InstagramShop\Model\ResourceModel\Report\Collection;
use Magento\Backend\Block\Template;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;

/**
 * Class FullReport
 * @package Magenest\InstagramShop\Block\Adminhtml\Report
 */
class FullReport extends Template
{
    private $_selectedProductFields = ['entity_id', 'name', 'thumbnail', 'sku', 'url_key'];
    /**
     * @var Collection
     */
    protected $loadedCollection;
    /**
     * @var ReportFactory
     */
    protected $reportFactory;
    /**
     * @var ProductFactory
     */
    protected $_productFactory;
    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $_imageHelper;
    /**
     * @var \Magenest\InstagramShop\Model\ResourceModel\ReportLog\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * FullReport constructor.
     * @param Template\Context $context
     * @param ReportFactory $reportFactory ,
     * @param ProductFactory $productFactory
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magenest\InstagramShop\Model\ResourceModel\ReportLog\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ReportFactory $reportFactory,
        ProductFactory $productFactory,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magenest\InstagramShop\Model\ResourceModel\ReportLog\CollectionFactory $collectionFactory,
        array $data = []
    )
    {
        $this->_productFactory   = $productFactory;
        $this->reportFactory     = $reportFactory;
        $this->_imageHelper      = $imageHelper;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return Collection
     */
    public function getLoadedCollection()
    {
        if ($this->loadedCollection === null) {
            $this->setLoadedCollection();
        }
        return $this->loadedCollection;
    }

    /**
     * Clear loaded Report Collection
     *
     * @return $this
     */
    public function clearLoadedCollection()
    {
        if ($this->loadedCollection instanceof Collection) {
            $this->loadedCollection->clear();
        }
        $this->loadedCollection = null;
        return $this;
    }

    /**
     * @param array $activeFilters
     * @return $this
     */
    public function setLoadedCollection($activeFilters = [])
    {
        $collection = $this->reportFactory->create()->getCollection();
        if (!empty($activeFilters)) {
            $collection->addIdsFilter($this->getIdsFromFilter($activeFilters));
        }
        $this->loadedCollection = $collection;
        return $this;
    }

    /**
     * @param array $filters
     * @return array
     */
    protected function getIdsFromFilter($filters)
    {
        /** @var \Magenest\InstagramShop\Model\ResourceModel\ReportLog\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect('report_id');
        if (!empty($filters)) {
            $fieldName = '';
            foreach ($filters as $filter) {
                $fieldName = key($filter);
                if (isset($filter[$fieldName]))
                    $collection->addFieldToFilter($fieldName, $filter[$fieldName]);
            }
            if ($fieldName) {
                $collection->setFilterNotNull($fieldName);
            }
        }
        if (($reportIds = $collection->getData()) !== null && is_array($collection->getData())) {
            $ids = array_unique(array_column(array_values($reportIds), 'report_id'));
            return is_array($ids) ? $ids : [];
        }
        return [];
    }

    /**
     * @return int
     */
    public function getTotalClicks()
    {
        $collection = $this->getLoadedCollection();
        return $collection->getTotalClicks();
    }

    /**
     * @return int
     */
    public function getTotalAddedToCart()
    {
        return $this->getLoadedCollection()->getTotalAddedToCart();
    }

    /**
     * @param bool $withPercent
     * @param int $round
     * @return float|string
     */
    public function getTotalConversionRate($withPercent = false, $round = 2)
    {
        return $this->getLoadedCollection()->getTotalConversionRate($withPercent, $round);
    }

    /**
     * @return \Magenest\InstagramShop\Model\Photo
     */
    public function getMostViewImage()
    {
        return $this->getLoadedCollection()->getMostViewImage();
    }

    /**
     * @return array|bool
     */
    public function getMostViewProductFromImage()
    {
        $productId = $this->getLoadedCollection()->getMostProductViewFromImage();
        if ($productId) {
            $product = $this->_productFactory->create()->load($productId);
            return $this->getSelectedProductData($product);
        }
        return [];
    }

    /**
     * @return string
     */
    public function getReportUrl()
    {
        return $this->getUrl('*/*/getReport');
    }

    /**
     * @return \Magenest\InstagramShop\Model\Photo
     */
    public function getHighestConversionRateImage()
    {
        return $this->getLoadedCollection()->getHighestConversionRateImage();
    }

    /**
     * @return array
     */
    private function getSelectedProductFields()
    {
        return $this->_selectedProductFields;
    }

    /**
     * @param $product
     * @return array
     */
    private function getSelectedProductData($product)
    {
        $data   = $product->getData();
        $result = [];
        foreach ($this->getSelectedProductFields() as $fieldName) {
            if (isset($data[$fieldName])) {
                switch ($fieldName) {
                    case 'thumbnail':
                        $result[$fieldName] = $this->getThumbnail($product);
                        break;
                    case 'url_key':
                        $result['edit_url'] = $this->getProductUrl($product);
                        break;
                    default:
                        $result[$fieldName] = $data[$fieldName];
                }
            }
        }
        return $result;
    }

    /**
     * @param $product
     * @return string
     */
    private function getThumbnail($product)
    {
        return $this->_imageHelper->init($product, 'product_listing_thumbnail')->resize(100, 100)->getUrl();
    }

    /**
     * @param Product $product
     * @return string
     */
    private function getProductUrl($product)
    {
        return $this->_urlBuilder->getUrl('catalog/product/edit', ['id' => $product->getId()]);
    }
}