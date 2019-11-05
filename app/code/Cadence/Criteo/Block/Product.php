<?php
/**
 * @author Cadence Labs <info@cadence-labs.com>
 */
namespace Cadence\Criteo\Block;

class Product extends Base {

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    protected $_request;

    /**
     * Product constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Cadence\Criteo\Helper\Data $helper
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Cadence\Criteo\Helper\Data $helper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\Http $request,
        array $data
    ) {
        $this->_registry = $registry;
        $this->_request = $request;
        parent::__construct($context, $helper, $data);
    }

    /**
     * @return string|null
     */
    public function getProductId() {

        $sku = $this->_request->getParam('option');

        $product = $this->_registry->registry('current_product');

        return $product->getSku() . (empty($sku) ? '' : '-' . $sku);
    }
}
