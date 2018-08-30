<?php
namespace DiamondMansion\Extensions\Observer\Product;

use Magento\Framework\Event\ObserverInterface;

class SaveDiamondMansionOptions implements ObserverInterface
{
    protected $_request;
    protected $_optionModelFactory;
    protected $_optionModel;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \DiamondMansion\Extensions\Model\ProductOptionsFactory $optionModelFactory
    ) {
        $this->_request = $request;
        $this->_optionModelFactory = $optionModelFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getProduct();
        if ($product->getTypeId() == 'dm_ring_design') {
            $this->_processRingDesign($product);
        } else if ($product->getTypeId() == 'dm_ring_eternity') {
            $this->_processRingEternity($product);
        }
    }

    private function _processRingDesign($product) {
        $options = $this->_request->getParam('dm-options');

        if (!$options) {
            return;
        }

        $dm = [];
        $tmp = [];
        foreach ($options as $option) {
            $tmp[] = "options".$option['name'] . '=' . $option['value'];
        }
        parse_str(implode("&", $tmp), $dm);

        $this->_optionModel = $this->_optionModelFactory->create();
        $records = $this->_optionModel->getCollection()
            ->addFieldToFilter('product_id', ['eq' => $product->getId()]);

        foreach ($records as $record) {
            $this->_optionModel->load($record->getId())->delete();
        }

        $this->_optionModel = $this->_optionModelFactory->create();

        if (isset($dm['options']['main-stone-type'])) {
            $children = [];
            foreach ($dm['options']['main-stone-type'] as $option) {
                if (isset($option['values']['children']) && is_array($option['values']['children'])) {
                    foreach ($option['values']['children'] as $group => $options) {
                        if (!isset($children[$group])) {
                            $children[$group] = [];
                        }
                        $children[$group] = array_merge($children[$group], $options);
                    }
                }
            }

            foreach ($children as $group => $options) {
                $dm['options'][$group] = array_merge($dm['options'][$group], array_unique($options));
            }
        }

        foreach ($dm['options'] as $group => $options) {
            switch ($group) {
                case 'side-stone-shape':
                    foreach ($options as $index => $subOptions) {
                        if ($index === 'is_default') {
                            continue;
                        }

                        foreach ($subOptions as $subIndex => $option) {
                            if (is_array($option)) {
                                continue;
                            }

                            $this->_saveOption([
                                'product_id' => $product->getId(),
                                'group' => $group . '-'. $index,
                                'code' => $option,
                                'is_default' => (isset($options['is_default']) && $options['is_default'] == $option),
                                'values' => isset($subOptions[$option]['values']) ? json_encode($subOptions[$option]['values']) : "",
                            ]);
                        }
                    }
                    break;
                case 'side-stone-carat':
                case 'side-stone-color-clarity':
                    foreach ($options as $index => $subOptions) {
                        if ($index == 'is_default') {
                            continue;
                        }

                        foreach ($subOptions as $option) {
                            $this->_saveOption([
                                'product_id' => $product->getId(),
                                'group' => $group . '-' . $index,
                                'code' => $option,
                                'is_default' => (isset($options['is_default']) && $options['is_default'] == $option),
                                'values' => "",
                            ]);
                        }
                    }
                    break;
                case 'metal':
                    foreach ($options as $code => $option) {
                        if ($code === 'is_default' || is_array($option)) {
                            continue;
                        }

                        $this->_saveOption([
                            'product_id' => $product->getId(),
                            'group' => $group,
                            'code' => $option,
                            'is_default' => (isset($options['is_default']) && $options['is_default'] == $option),
                            'values' => isset($options[$option]['values'])?json_encode($options[$option]['values']):"",
                        ]);
                    }
                    break;
                default:
                    foreach ($options as $code => $option) {
                        if ($code === 'is_default') {
                            continue;
                        }

                        $this->_saveOption([
                            'product_id' => $product->getId(),
                            'group' => $group,
                            'code' => is_array($option)?$code:$option,
                            'is_default' => (isset($options['is_default']) && $options['is_default'] == (is_array($option)?$code:$option)),
                            'values' => isset($option['values'])?json_encode($option['values']):"",
                        ]);
                    }
            }
        }
    }

    private function _processRingEternity($product) {
        $options = $this->_request->getParam('dm-options');

        if (!$options) {
            return;
        }

        $dm = [];
        $tmp = [];
        foreach ($options as $option) {
            $tmp[] = "options".$option['name'] . '=' . $option['value'];
        }
        parse_str(implode("&", $tmp), $dm);

        $this->_optionModel = $this->_optionModelFactory->create();
        $records = $this->_optionModel->getCollection()
            ->addFieldToFilter('product_id', ['eq' => $product->getId()]);

        foreach ($records as $record) {
            $this->_optionModel->load($record->getId())->delete();
        }

        $this->_optionModel = $this->_optionModelFactory->create();

        if (isset($dm['options']['stone-type'])) {
            $children = [];
            foreach ($dm['options']['stone-type'] as $option) {
                if (isset($option['values']['children']) && is_array($option['values']['children'])) {
                    foreach ($option['values']['children'] as $group => $options) {
                        if (!isset($children[$group])) {
                            $children[$group] = [];
                        }
                        $children[$group] = array_merge($children[$group], $options);
                    }
                }
            }

            foreach ($children as $group => $options) {
                $dm['options'][$group] = array_merge($dm['options'][$group], array_unique($options));
            }
        }

        foreach ($dm['options'] as $group => $options) {
            if ($group == 'stone-amount') {
                continue;
            }

            switch ($group) {
                case 'metal':
                    foreach ($options as $code => $option) {
                        if ($code === 'is_default' || is_array($option)) {
                            continue;
                        }

                        $this->_saveOption([
                            'product_id' => $product->getId(),
                            'group' => $group,
                            'code' => $option,
                            'is_default' => (isset($options['is_default']) && $options['is_default'] == $option),
                            'values' => isset($options[$option]['values'])?json_encode($options[$option]['values']):"",
                        ]);
                    }
                    break;
                case 'stone-shape':
                    foreach ($options as $code => $option) {
                        if ($code === 'is_default' || is_array($option)) {
                            continue;
                        }

                        $this->_saveOption([
                            'product_id' => $product->getId(),
                            'group' => $group,
                            'code' => $option,
                            'is_default' => (isset($options['is_default']) && $options['is_default'] == $option),
                            'values' => isset($dm['options']['stone-amount'][$option]['values'])?json_encode($dm['options']['stone-amount'][$option]['values']):"",
                        ]);
                    }
                    break;
                default:
                    foreach ($options as $code => $option) {
                        if ($code === 'is_default') {
                            continue;
                        }

                        $this->_saveOption([
                            'product_id' => $product->getId(),
                            'group' => $group,
                            'code' => is_array($option)?$code:$option,
                            'is_default' => (isset($options['is_default']) && $options['is_default'] == (is_array($option)?$code:$option)),
                            'values' => isset($option['values'])?json_encode($option['values']):"",
                        ]);
                    }
            }
        }
    }

    private function _saveOption($data)
    {
        $this->_optionModel->setData($data);
        $this->_optionModel->save();
    }
}