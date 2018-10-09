<?php
namespace DiamondMansion\Extensions\Model\Ring\Design\Product;

class Type extends \Magento\Catalog\Model\Product\Type\AbstractType {
    protected $_dmAttributes;
    protected $_dmOptionModel;
    protected $_dmOptionGroupModel;
    protected $_allDmOptions;
    protected $_defaultDmOptions;
    protected $_helper;

    protected $_map = [
        'dm_stone_type' => 'main-stone-type',
        'dm_stone_shape' => 'main-stone-shape',
        'dm_band' => 'band',
        'dm_metal' => 'metal'
    ];

    protected $_dmOptionSortOrder = [
        'main-stone-type',
        'main-stone-shape',
        'main-stone-carat',
        'main-stone-color',
        'main-stone-clarity',
        'main-stone-cert',
        'metal',
        'band',
        'side-stone-shape',
        'side-stone-carat',
        'side-stone-color-clarity',
        'ring-size',
        'setting-options-stone',
        'setting-options-size',
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
        \DiamondMansion\Extensions\Helper\Image $helper
    ) {
        $this->_dmOptionModel = $dmOptionModel;
        $this->_dmOptionGroupModel = $dmOptionGroupModel;
        $this->_helper = $helper;

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

    public function getAllDmOptions($product) {
        if (!$this->_allDmOptions) {
            $collection = $this->_dmOptionModel->getCollection()
                ->addFieldToFilter('product_id', $product->getId())
                ->joinDetails($product->getTypeId());

            $this->_allDmOptions = [];
            foreach ($collection as $item) {
                if (!isset($this->_allDmOptions[$item->getGroup()])) {
                    $this->_allDmOptions[$item->getGroup()] = [];
                }

                $this->_allDmOptions[$item->getGroup()][$item->getCode()] = $item;
            }
        }

        return $this->_allDmOptions;
    }

    public function getDefaultDmOptions($product) {
        if (!$this->_defaultDmOptions) {
            $this->_defaultDmOptions = [];
            $options = $this->getAllDmOptions($product);
            foreach ($options as $group => $optionGroup) {
                foreach ($optionGroup as $option) {
                    if ($option->getIsDefault()) {
                        $this->_defaultDmOptions[$group] = $option;
                        break;
                    }
                }

                if (!isset($this->_defaultDmOptions[$group])) {
                    $this->_defaultDmOptions[$group] = current($options[$group]);
                }
            }
        }

        return $this->_defaultDmOptions;
    }

    public function getDmOptions($product) {
        $options = $this->getAllDmOptions($product);
        $defaultOptions = $this->getDefaultDmOptions($product);

        $result = [];

        $filters = $product->getFilters();

        $attributeKey = 'dm_stone_type';
        $children = false;
        if (isset($filters[$attributeKey])) {
            foreach ($options[$this->_map[$attributeKey]] as $option) {
                if ($option->getCode() == $this->_dmAttributes[$attributeKey]['by_id'][$filters[$attributeKey]]['slug']) {
                    $result[$this->_map[$attributeKey]] = $option->getCode();
                    $values = json_decode($option->getValues(), true);
                    if (isset($values['children'])) {
                        $children = $values['children'];
                    }
                    break;
                }
            }
        }

        if (!isset($result[$this->_map[$attributeKey]])) {
            $result[$this->_map[$attributeKey]] = $defaultOptions[$this->_map[$attributeKey]]->getCode();
            $values = json_decode($defaultOptions[$this->_map[$attributeKey]]->getValues(), true);
            if (isset($values['children'])) {
                $children = $values['children'];
            }
        }

        $attributeKey = 'dm_stone_shape';
        if (isset($filters[$attributeKey])) {
            foreach ($options[$this->_map[$attributeKey]] as $option) {
                if ($option->getCode() == $this->_dmAttributes[$attributeKey]['by_id'][$filters[$attributeKey]]['slug']) {
                    if ((is_array($children) && isset($children[$this->_map[$attributeKey]]) && (in_array($option->getCode(), $children[$this->_map[$attributeKey]]))) || 
                        !is_array($children) || !isset($children[$this->_map[$attributeKey]])) {
                        $result[$this->_map[$attributeKey]] = $option->getCode();
                    }
                    break;
                }
            }
        }

        if (!isset($result[$this->_map[$attributeKey]])) {
            $result[$this->_map[$attributeKey]] = $defaultOptions[$this->_map[$attributeKey]]->getCode();
        }

        $attributeKey = 'dm_metal';
        if (isset($filters[$attributeKey])) {
            foreach ($options[$this->_map[$attributeKey]] as $option) {
                if (strpos($option->getCode(), $this->_dmAttributes[$attributeKey]['by_id'][$filters[$attributeKey]]['slug']) !== false) {
                    $result[$this->_map[$attributeKey]] = $option->getCode();
                    break;
                }
            }
        }

        if (!isset($result[$this->_map[$attributeKey]])) {
            $result[$this->_map[$attributeKey]] = $defaultOptions[$this->_map[$attributeKey]]->getCode();
        }

        $attributeKey = 'dm_band';
        if (isset($filters[$attributeKey])) {
            foreach ($options[$this->_map[$attributeKey]] as $option) {
                if ($option->getCode() == $this->_dmAttributes[$attributeKey]['by_id'][$filters[$attributeKey]]['slug']) {
                    $result[$this->_map[$attributeKey]] = $option->getCode();
                    break;
                }
            }
        }

        if (!isset($result[$this->_map[$attributeKey]])) {
            $result[$this->_map[$attributeKey]] = $defaultOptions[$this->_map[$attributeKey]]->getCode();
        }

        foreach ($options as $group => $optionGroup) {
            if (isset($result[$group])) {
                continue;
            }

            if (is_array($children) && isset($children[$group]) && !in_array($this->_defaultDmOptions[$group]->getCode(), $children[$group])) {
                $result[$group] = $children[$group][0];
            } else {
                $result[$group] = $this->_defaultDmOptions[$group]->getCode();
            }
        }

        return $result;
    }

    public function getName($product, $mainName = "") {
        $allDmOptions = $this->getAllDmOptions($product);
        $params = $this->getDmOptions($product);

        if ($params["main-stone-type"] == "setting") {
            
            $name = $mainName . " Setting";
            
            if (isset($params["metal"])) { 
                $name .= $allDmOptions["metal"][$params["metal"]]->getTitle();
            }
            
            return $name;
        }

        $name = [];
        if (isset($params["main-stone-carat"])) { 
            $name[] = (double)($this->getTotalCarat($product))."ct.";
        }

        if (isset($params["main-stone-type"])) { 
            $name[] = $allDmOptions['main-stone-type'][$params["main-stone-type"]]->getTitle();
        }
        $name[] = "Diamond";

        if (isset($params["main-stone-shape"])) { 
            $name[] = ($params["main-stone-shape"] == "heart") ? $allDmOptions['main-stone-shape'][$params["main-stone-shape"]]->getTitle() . " shape" : $allDmOptions['main-stone-shape'][$params["main-stone-shape"]]->getTitle() . " cut";
        }
        
        $name[] = $mainName;
        
        if (isset($params["metal"])) { 
            $name[] = $allDmOptions['metal'][$params["metal"]]->getTitle(); 
        }

        if (isset($params["main-stone-cert"])) { 
            $name[] = $allDmOptions['main-stone-cert'][$params["main-stone-cert"]]->getTitle(); 
        }
        
        return implode(" ", $name);
    }

	public function getTotalCarat($product) {
        $allDmOptions = $this->getAllDmOptions($product);
        $params = $this->getDmOptions($product);

        $carat = 0;
        
        if (isset($params["main-stone-carat"])) { $carat = (double) floor($params["main-stone-carat"] * 10) / 10 ; }
        
        foreach ($params as $group => $param) {
            if (strpos($group, 'side-stone-carat') !== false && isset($allDmOptions[$group])) {
                foreach ($allDmOptions[$group] as $option) {
                    $values = json_decode($option->getValues());
                    if (isset($values['qty']) && count($values['qty']) == 2) {
                        $qty = (double)$values['qty'][0];
                        if (isset($params["band"]) && $params["band"] == "bridal-set") {
                            $qty += (double)$values['qty'][1];
                        }
                        
                        $carat += $qty * (double)$param;
                    }
                }
            }
        }
        
        return $carat;	
    }

	public function getProductUrl($product) {
        $params = $this->getDmOptions($product);
        $filters = $product->getFilters();

        if (isset($params['others'])) {
            unset($params['others']);
        }

        $urlPrefix = "";
        if (isset($params["main-stone-shape"])) {
            $urlPrefix = ($params["main-stone-shape"] == 'heart') ? $params["main-stone-shape"] . '-shape-' : $params["main-stone-shape"] . '-cut-';
        }

        $url = $this->_helper->getBaseUrl() . $urlPrefix . $product->getUrlKey() . "/";

        if (count($filters) && !(count($filters) == 1 && isset($filters['dm_stone_shape']))) {
            $allDmOptions = $this->getAllDmOptions($product);

            $this->_sortDmOptions($params);

            $skus = [];
            
            foreach ($params as $group => $param) {
                if (!empty($allDmOptions[$group][$param]->getSlug())) {
                    $skus[$group] = $allDmOptions[$group][$param]->getSlug();
                }
            }

            $sku = implode("", $skus);
            
            if ($params["main-stone-type"] == "setting" && !empty($params["size"])) {
                $sku .= "-".$params["size"];
            }

            $url .= "?option=" . $sku;
        }
        
        return $url;
    }
    
    public function getImage($product) {
        $param = $this->getDmOptions($product);
        
        $images = $this->_helper->getProductImages([
            'sku' => $product->getSku(),
            'type' => $param['main-stone-type'],
            'shape' => $param['main-stone-shape'],
            'band' => $param['band'],
            'metal' => $param['metal'],
        ]);
        
        return $images['main'];        
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
        foreach ($this->_dmOptionSortOrder as $key) {           
            foreach ($options as $group => $option) {
                if ($key == $group || strpos($key, $group) !== false) {
                    $newOptions[$group] = $option;
                }
            }
        }

        $options = $newOptions;
    }
}