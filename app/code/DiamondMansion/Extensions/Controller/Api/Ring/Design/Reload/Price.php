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

        $result = [];
        if (isset($defaultOptions['band']) && isset($allOptions['band']['bridal-set'])) {
            $defaultOptions['band'] = $allOptions['band']['no-band'];
            $product->setDefaultDmOptions($defaultOptions);
            $priceNoBand = $product->getPrice();
            $defaultOptions['band'] = $allOptions['band']['bridal-set'];
            $product->setDefaultDmOptions($defaultOptions);
            $priceBridalSet = $product->getPrice();

            $result = [
                'price' => (isset($params['band']) && $params['band'] == 'bridal-set') ? $priceBridalSet : $priceNoBand,
                'diff_for_bridal_set' => round(($priceBridalSet - $priceNoBand) / 10) * 10
            ];
        } else {
            $product->setDefaultDmOptions($defaultOptions);
            $result = [
                'price' => $product->getPrice(),
                'diff_for_bridal_set' => 0
            ];
        }
        echo json_encode($result);
    }
}