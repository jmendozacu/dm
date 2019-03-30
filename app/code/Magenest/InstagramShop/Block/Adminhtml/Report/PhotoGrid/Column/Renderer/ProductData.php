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

namespace Magenest\InstagramShop\Block\Adminhtml\Report\PhotoGrid\Column\Renderer;

use Magenest\InstagramShop\Model\Report;
use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Backend\Model\UrlInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NotFoundException;

/**
 * Class ProductData
 * @package Magenest\InstagramShop\Block\Adminhtml\Report\PhotoGrid\Column\Renderer
 */
class ProductData extends AbstractRenderer
{
    protected $_productRepository;

    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        array $data = []
    )
    {
        $this->_productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    /**
     * @param \Magento\Framework\DataObject|Report $row
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $html            = '';
        $unserializeData = $row->getUnserializeProductData();
        if (is_array($unserializeData) && !empty($unserializeData)) {
            $html .= '<table width="100%">';
            $html .= '<thead><tr><th>ID</th><th>SKU</th><th>Viewed</th><th>Ordered / Rate</th><th>Dropped / Rate</th></tr></thead>';
            $html .= '<tbody>';
            foreach ($unserializeData as $productId => $datum) {
                try {
                    $html    .= '<tr>';
                    $product = $this->_productRepository->getById($productId);
                    if ($product->getId()) {
                        $viewed  = $this->getDefaultDataFromProductData(Report::TOTAL_PRODUCTS_CLICK_KEY, $datum);
                        $ordered = $this->getDefaultDataFromProductData(Report::TOTAL_CONVERSION_RATE_KEY, $datum);
                        $dropped = $this->getDefaultDataFromProductData(Report::TOTAL_DROP_RATE_KEY, $datum);
                        $added   = $this->getDefaultDataFromProductData(Report::TOTAL_ADDED_TO_CART_KEY, $datum);
                        $html    .= '<td>' . $productId . '</td>
                                    <td><a target="_blank" href="' . $this->_urlBuilder->getUrl('catalog/product/edit', ['id' => $productId]) . '">' . $product->getSku() . '</a></td>
                                    <td>' . $viewed . '</td>
                                    <td>' . $ordered . ' / ' . $row->getRate($ordered, $viewed, true) . '</td >
                                    <td > ' . $dropped . ' / ' . $row->getRate($dropped, $viewed, true) . '</td > ';
                    }
                    $html .= '</tr > ';
                } catch (NotFoundException $e) {
                    continue;
                }
            }
            $html .= '</tbody>';
            $html .= '</table>';
        }
        return $html;
    }

    private function getDefaultDataFromProductData($key, $data, $defaultValue = 0)
    {
        return isset($data[$key]) ? $data[$key] : $defaultValue;
    }
}