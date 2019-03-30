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

use Magenest\InstagramShop\Block\Adminhtml\Report\PhotoGrid\Column\Renderer\PhotoId;
use Magenest\InstagramShop\Block\Adminhtml\Report\PhotoGrid\Column\Renderer\ProductData;
use Magenest\InstagramShop\Block\Adminhtml\Report\PhotoGrid\Column\Renderer\TotalAddedToCartRate;
use Magenest\InstagramShop\Block\Adminhtml\Report\PhotoGrid\Column\Renderer\TotalConversionRate;
use Magenest\InstagramShop\Block\Adminhtml\Report\PhotoGrid\Column\Renderer\TotalDropRate;
use Magenest\InstagramShop\Model\ResourceModel\Report\Collection;
use Magenest\InstagramShop\Model\ResourceModel\Report\CollectionFactory;

/**
 * Class PhotoGrid
 * @package Magenest\InstagramShop\Block\Adminhtml\Report
 */
class PhotoGrid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
    /**
     * @var CollectionFactory
     */
    protected $reportCollectionFactory;

    /**
     * PhotoGrid constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        CollectionFactory $collectionFactory,
        array $data = [])
    {
        $this->reportCollectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Prepare collection for grid
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended|$this
     */
    protected function _prepareCollection()
    {
        /** @var Collection $collection */
        $collection = $this->reportCollectionFactory->create();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Define grid columns
     *
     * @return PhotoGrid|\Magento\Backend\Block\Widget\Grid\Extended
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', ['header' => __('ID'), 'index' => 'id', 'width' => 10]);

        $this->addColumn(
            'photo_id',
            [
                'header' => __('Photo'),
                'index' => 'photo_id',
                'type' => 'text',
                'renderer' => PhotoId::class,
                'align' => 'center'
            ]
        );

        $this->addColumn(
            'total_click',
            [
                'header' => __('Total Clicks'),
                'index' => 'total_click',
                'align' => 'center',
                'type' => 'text'
            ]
        );

        $this->addColumn(
            'total_conversion_rate',
            [
                'header' => __('Conversion Rate'),
                'index' => 'total_conversion_rate',
                'align' => 'center',
                'type' => 'text',
                'renderer' => TotalConversionRate::class
            ]
        );

        $this->addColumn(
            'total_drop_rate',
            [
                'header' => __('Drop Rate'),
                'index' => 'total_drop_rate',
                'align' => 'center',
                'type' => 'text',
                'renderer' => TotalDropRate::class
            ]
        );

//        $this->addColumn(
//            'total_addedtocart_rate',
//            [
//                'header' => __('Added To Cart Rate'),
//                'index' => 'total_addedtocart_rate',
//                'align' => 'center',
//                'type' => 'text',
//                'renderer' => TotalAddedToCartRate::class
//            ]
//        );

        $this->addColumn(
            'product_data',
            [
                'header' => __('Product Data'),
                'index' => 'product_data',
                'type' => 'text',
                'renderer' => ProductData::class,
            ]
        );

//        $this->addColumn(
//            'created_at',
//            ['header' => __('Created At'), 'index' => 'created_at', 'type' => 'datetime']
//        );
//
//        $this->addColumn(
//            'updated_at',
//            ['header' => __('Updated At'), 'index' => 'updated_at', 'type' => 'datetime']
//        );

        return parent::_prepareColumns();
    }
}