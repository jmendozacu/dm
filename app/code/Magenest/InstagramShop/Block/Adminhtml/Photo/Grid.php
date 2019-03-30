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

namespace Magenest\InstagramShop\Block\Adminhtml\Photo;

use Magento\Backend\Block\Widget\Grid as WidgetGrid;

/**
 * Class Grid
 * @package Magenest\InstagramShop\Block\Adminhtml\Photo
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magenest\InstagramShop\Model\PhotoFactory
     */
    protected $_photoFactory;

    /**
     * Grid constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magenest\InstagramShop\Model\PhotoFactory $photoFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magenest\InstagramShop\Model\PhotoFactory $photoFactory,
        array $data = []
    ) {
        $this->_photoFactory = $photoFactory;
        $this->setEmptyText(__('No Photos Found'));
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Initialize the collection
     *
     * @return WidgetGrid
     */
    protected function _prepareCollection()
    {
        $this->setCollection($this->_photoFactory->create()->getCollection());
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     *
     * @return $this
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'id',
            [
                'header' => __('ID'),
                'index' => 'id',
            ]
        );
        $this->addColumn(
            'source',
            [
                'header' => __('Source'),
                'index' => 'source',
                'renderer' => '\Magenest\InstagramShop\Block\Adminhtml\Photo\Renderer\Image',
            ]
        );
        $this->addColumn(
            'url',
            [
                'header' => __('URL'),
                'index' => 'url',
                'renderer' => '\Magenest\InstagramShop\Block\Adminhtml\Photo\Renderer\Link',
            ]
        );
        $this->addColumn(
            'photo_id',
            [
                'header' => __('Photo Id on Instagram'),
                'index' => 'photo_id',
            ]
        );
        $this->addColumn(
            'likes',
            [
                'header' => __('Likes'),
                'index' => 'likes',
            ]
        );
        $this->addColumn(
            'comments',
            [
                'header' => __('Comments'),
                'index' => 'comments',
            ]
        );
        $this->addColumn(
            'caption',
            [
                'header' => __('Caption'),
                'index' => 'caption',
            ]
        );

        return $this;
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label' => __('Delete'),
                'url' => $this->getUrl('*/*/massdelete'),
                'confirm' => 'Are you sure?'
            )
        );

        return $this;
    }
}
