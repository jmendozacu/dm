<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace DiamondMansion\Extensions\Block\Adminhtml\Shopcart\Abandoned\Grid\Column\Renderer;

/**
 * Adminhtml Report Products Reviews renderer
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Items extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_quoteFactory;
    protected $_quoteModel;

    public function __construct(
        \Magento\Backend\Block\Context $context, 
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Quote\Model\ResourceModel\Quote $quoteModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_quoteFactory = $quoteFactory;
        $this->_quoteModel = $quoteModel;
    }

    /**
     * Renders grid column
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $customerId = $row->getId();

        $quote = $this->_quoteFactory->create();
        $this->_quoteModel->loadByCustomerId($quote, $customerId);

        $html = "";

        foreach ($quote->getAllItems() as $item) {
            $product = $item->getProduct();
            $html .= '<div style="display: inline-block; width: 70px; text-align: center; margin: 0 5px; border: 1px solid #e2e2e2;">';
            $html .= '<a href="' . $product->getProductUrl() . '" target="_blank" title="' . $product->getName() . '">';
            $html .= '<img style="width: 70px; height: 70px;" src="' . $product->getImage() . '"/>';
            $price = $product->getPrice();
            $html .= '<span style="line-height: 30px;">' . ($price ? '$' . $price : 'Sold Out') . '</span>';
            $html .= '</a>';
            $html .= '</div>';
        }

        return $html;
    }
}
