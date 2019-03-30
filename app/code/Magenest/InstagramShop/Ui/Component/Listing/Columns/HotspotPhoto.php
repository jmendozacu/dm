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

namespace Magenest\InstagramShop\Ui\Component\Listing\Columns;

use Magenest\InstagramShop\Helper\Helper;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * Class HotspotPhoto
 * @package Magenest\InstagramShop\Ui\Component\Listing\Columns
 */
class HotspotPhoto extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var Helper
     */
    protected $helper;

    /**
     * HotspotPhoto constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Helper $helper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Helper $helper,
        array $components = [],
        array $data = []
    )
    {
        $this->helper = $helper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $photo                          = $this->helper->getPhoto($item[$fieldName], $item['type']);
                $source                         = $photo->getSource();
                $item[$fieldName . '_src']      = $source;
                $item[$fieldName . '_orig_src'] = $source;
            }
        }

        return $dataSource;
    }

    /**
     * @param array $row
     *
     * @return null|string
     */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: 'image';
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}
