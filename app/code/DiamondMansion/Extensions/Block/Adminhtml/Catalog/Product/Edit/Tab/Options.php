<?php
namespace DiamondMansion\Extensions\Block\Adminhtml\Catalog\Product\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;

class Options extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template;
    protected $_optionsGroupModel;
    protected $_productOptionsModel;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    public function __construct(
        Context $context,
        Registry $registry,
        \DiamondMansion\Extensions\Model\OptionsGroup $optionsGroupModel,
        \DiamondMansion\Extensions\Model\ProductOptions $productOptionsModel,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->_optionsGroupModel = $optionsGroupModel;
        $this->_productOptionsModel = $productOptionsModel;

        $this->_template = 'catalog/product/edit/options/' . $this->getProduct()->getTypeId() . '.phtml';

        parent::__construct($context, $data);
    }

    /**
     * Retrieve product
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

    public function getOptionsGroup() {
        $collection = $this->_optionsGroupModel->getCollection()
            ->addFieldToFilter('type', ['eq' => $this->getProduct()->getTypeId()]);

        $options = [];
        foreach ($collection as $item) {
            $tmp = $item->getData();
            if ($tmp['values'] !== "") {
                $tmp['values'] = json_decode($tmp['values'], true);
            }
            $options[$item->getGroup()][] = $tmp;
        }
        return $options;
    }

    public function getProductOptions() {
        if ($this->getProduct()->getTypeId() == 'dm_ring_design') {
            return $this->_getProductRingDesignOptions();
        }
    }

    private function _getProductRingDesignOptions() {
        $collection = $this->_productOptionsModel->getCollection()
            ->addFieldToFilter('product_id', ['eq' => $this->getProduct()->getId()]);

        $options = [];
        foreach ($collection as $item) {
            $tmp = $item->getData();
            if ($tmp['values'] !== "") {
                $tmp['values'] = json_decode($tmp['values'], true);
            }
            $options[$item->getGroup()][$item->getCode()] = $tmp;
        }

        return count($options) ? $options : false;
    }
}