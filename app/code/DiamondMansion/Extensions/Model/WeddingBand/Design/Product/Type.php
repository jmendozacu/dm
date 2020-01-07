<?php
namespace DiamondMansion\Extensions\Model\WeddingBand\Design\Product;

class Type extends \Magento\Catalog\Model\Product\Type\AbstractType {
    protected $_dmAttributes;
    protected $_dmOptionModel;
    protected $_dmOptionGroupModel;
    protected $_allDmOptions;
    protected $_defaultDmOptions;
    protected $_helper;
    protected $_cache;

    protected $_map = [
        'dm_metal' => 'metal'
    ];

    protected $_dmOptionSortOrder = [
        'metal' => false,
        'width' => false,
        'ring-size' => false,
        'finish' => false,
    ];

    public function __construct(
        \Magento\Catalog\Model\Product\Option $catalogProductOption,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Catalog\Model\Product\Type $catalogProductType,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDb,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Registry $coreRegistry,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Serialize\Serializer\Json $serializer = null,
        \DiamondMansion\Extensions\Model\OptionsGroup $dmOptionGroupModel,
        \DiamondMansion\Extensions\Model\ProductOptions $dmOptionModel,
        \DiamondMansion\Extensions\Helper\Image $helper,
        \Magento\Framework\App\CacheInterface $cache
    ) {
        $this->_dmOptionModel = $dmOptionModel;
        $this->_dmOptionGroupModel = $dmOptionGroupModel;
        $this->_helper = $helper;
        $this->_cache = $cache;

        $this->_loadDmAttributes($eavConfig);
        
        parent::__construct(
            $catalogProductOption, 
            $eavConfig, 
            $catalogProductType, 
            $eventManager, 
            $fileStorageDb, 
            $filesystem, 
            $coreRegistry, 
            $logger, 
            $productRepository, 
            $serializer
        );
    }

    public function getAllDmOptions($product, $sort = false) {
        if ($data = $this->_cache->load('all_dm_options_' . $product->getId())) {
            $this->_allDmOptions[$product->getId()] = unserialize($data);
        }

        if (!isset($this->_allDmOptions[$product->getId()])) {

            $collection = $this->_dmOptionModel->getCollection()
                ->addFieldToFilter('product_id', $product->getId());
                //->joinDetails($product->getTypeId());

            $this->_allDmOptions[$product->getId()] = [];
            foreach ($collection as $item) {
                if (!isset($this->_allDmOptions[$product->getId()][$item->getGroup()])) {
                    $this->_allDmOptions[$product->getId()][$item->getGroup()] = [];
                }

                $this->_allDmOptions[$product->getId()][$item->getGroup()][$item->getCode()] = $item;
            }

            $this->_cache->save(serialize($this->_allDmOptions[$product->getId()]), 'all_dm_options_' . $product->getId());
        }

        if ($sort) {
            $this->_sortDmOptions($this->_allDmOptions[$product->getId()]);
        }

        return $this->_allDmOptions[$product->getId()];
    }

    public function getDefaultDmOptions($product, $sort = false) {
        if (!isset($this->_defaultDmOptions[$product->getId()])) {
            if ($data = $this->_cache->load('default_dm_options_' . $product->getId())) {
                $this->_defaultDmOptions[$product->getId()] = unserialize($data);
            } else {
                $this->_defaultDmOptions[$product->getId()] = [];
                $options = $this->getAllDmOptions($product);
                foreach ($options as $group => $optionGroup) {
                    foreach ($optionGroup as $option) {
                        if ($option->getIsDefault()) {
                            $this->_defaultDmOptions[$product->getId()][$group] = $option;
                            break;
                        }
                    }

                    if (!isset($this->_defaultDmOptions[$product->getId()][$group])) {
                        $this->_defaultDmOptions[$product->getId()][$group] = current($options[$group]);
                    }
                }

                $this->_cache->save(serialize($this->_defaultDmOptions[$product->getId()]), 'default_dm_options_' . $product->getId());            
            }
        }

        if ($sort) {
            $this->_sortDmOptions($this->_defaultDmOptions[$product->getId()]);
        }

        return $this->_defaultDmOptions[$product->getId()];
    }

    public function setDefaultDmOptions($product, $options) {
        $this->_defaultDmOptions[$product->getId()] = $options;
    }

    public function getDmOptions($product, $sort = false) {
        $filters = $product->getFilters();

        $optionSet = $this->_helper->getWeddingBandDesignOptions($product, $filters);
        $options = $optionSet['allOptions'];
        $defaultOptions = $optionSet['defaultOptions'];

        $result = [];

        $attributeKey = 'dm_metal';
        if (isset($filters[$attributeKey])) {
            foreach ($options[$this->_map[$attributeKey]] as $option) {
                if (strpos($option->getCode(), $this->_dmAttributes[$attributeKey]['by_id'][$filters[$attributeKey]]['slug']) !== false) {
                    $result[$this->_map[$attributeKey]] = $option->getCode();
                    break;
                }
            }

            if (!isset($result[$this->_map[$attributeKey]])) {
                $result[$this->_map[$attributeKey]] = $defaultOptions[$this->_map[$attributeKey]]->getCode();
            }
        }

        foreach ($options as $group => $optionGroup) {
            if (isset($result[$group])) {
                continue;
            }

            if ($group == 'others') {
                $otherCodes = [];
                foreach ($optionGroup as $code => $option) {
                    $otherCodes[] = $code;
                }
                $result[$group] = implode(',', $otherCodes);
            } else if (isset($defaultOptions[$group])) {
                $result[$group] = $defaultOptions[$group]->getCode();
            }
        }

        if ($sort) {
            $this->_sortDmOptions($result);
        }

        return $result;
    }

    public function getDmOptionListForCart($product) {
        $result = [];

        $allDmOptions = $this->getAllDmOptions($product);
        $params = $this->getDmOptions($product);

        if (isset($params["metal"])) { 
            $result[] = [
                'label' => 'Metal',
                'value' => $allDmOptions['metal'][$params["metal"]]->getTitle()
            ];
        }

        if (isset($params["width"])) { 
            $result[] = [
                'label' => 'Width',
                'value' => $allDmOptions['width'][$params["width"]]->getTitle()
            ];
        }

        if (isset($params["ring-size"])) { 
            $result[] = [
                'label' => 'Ring Size',
                'value' => $allDmOptions['ring-size'][$params["ring-size"]]->getTitle()
            ];
        }

        if (isset($params["finish"])) { 
            $result[] = [
                'label' => 'Special Finish',
                'value' => $allDmOptions['finish'][$params["finish"]]->getTitle()
            ];
        }

        return $result;
    }

	public function getProductUrl($product) {
        $params = $this->getDmOptions($product);
        $filters = $product->getFilters();

        if (isset($params['others'])) {
            unset($params['others']);
        }

        $urlPrefix = "";
        if (isset($params["width"])) {
            $urlPrefix = $params["width"] . 'mm-';
        }

        $url = $this->_helper->getBaseUrl() . $urlPrefix . $product->getUrlKey() . "/";

        if (count($filters) && !(count($filters) == 1 && isset($filters['id']))) {
            $allDmOptions = $this->getAllDmOptions($product);

            $this->_sortDmOptions($params);

            $skus = [];
            
            foreach ($params as $group => $param) {
                if (!empty($allDmOptions[$group][$param]->getSlug())) {
                    $skus[$group] = $allDmOptions[$group][$param]->getSlug();
                }
            }

            $sku = implode("", $skus);
            
            $url .= "?option=" . $sku;
        }
        
        return $url;
    }
    
    public function getImage($product) {
        $param = $this->getDmOptions($product);
        
        $images = $this->_helper->getProductImages([
            'sku' => $product->getSku(),
            'width' => isset($param['width']) ? $param['width'] : "",
            'finish' => isset($param['finish']) ? $param['finish'] : "",
            'metal' => isset($param['metal']) ? $param['metal'] : "",
        ]);
        
        return $images['main'];
    }

    public function getDmOptionsSortOrder() {
        return array_keys($this->_dmOptionSortOrder);
    }

    /**
     * Delete data specific for Simple product type
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return void
     */
    public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {
    }

    private function _loadDmAttributes($eavConfig) {
        $eavOptions = [];
        foreach (array_keys($this->_map) as $attribute) {
            $eavAttribute = $eavConfig->getAttribute('catalog_product', $attribute);
            $eavOptions[$attribute] = [
                'by_id' => [],
                'by_slug' => []
            ];
            foreach ($eavAttribute->getSource()->getAllOptions() as $eavOption) {
                $eavOption['slug'] = $this->_helper->getSlug($eavOption['label']);
                $eavOptions[$attribute]['by_id'][$eavOption['value']] = $eavOption;
                $eavOptions[$attribute]['by_slug'][$eavOption['slug']] = $eavOption;
            }
        }

        $this->_dmAttributes = $eavOptions;
    }

    private function _sortDmOptions(&$options) {
        $newOptions = [];
        foreach ($this->_dmOptionSortOrder as $key => $codes) {
            foreach ($options as $group => $option) {
                $newOption = [];
                if ($codes && is_array($option)) {
                    foreach ($codes as $code) {
                        if (isset($option[$code])) {
                            $newOption[$code] = $option[$code];
                        }
                    }
                } else {
                    $newOption = $option;
                }

                if ($key == $group || strpos($key, $group) !== false) {
                    $newOptions[$group] = $newOption;
                }
            }
        }

        $options = array_merge($newOptions, array_diff_key($options, $newOptions));
    }

    protected function _prepareProduct(\Magento\Framework\DataObject $buyRequest, $product, $processMode) {
        if ($buyRequest['dm_options']) {
            $filters = $product->getFilters();
            $filters['option'] = $buyRequest['dm_options'];
            $product->setFilters($filters);
        }
        
        return parent::_prepareProduct($buyRequest, $product, $processMode);
    }
}