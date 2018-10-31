<?php

namespace DiamondMansion\Extensions\Controller\Api\Ring\Design\Reload;

class Price extends \Magento\Framework\App\Action\Action
{
    protected $_productFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->_productFactory = $productFactory;
        return parent::__construct($context);
    }

    public function execute() {
        $params = $this->getRequest()->getParams();

        $product = $this->_productFactory->create()->load($params['product_id']);

        $allOptions = $product->getAllDmOptions();
        $defaultOptions = $product->getDefaultDmOptions();
        foreach ($defaultOptions as $group => $option) {
            if (isset($params[$group]) && !empty($params[$group])) {
                $defaultOptions[$group] = $allOptions[$group][$params[$group]];
            }
        }

        $product->setDefaultDmOptions($defaultOptions);
        echo $product->getPrice();
    }
}