<?php
namespace DiamondMansion\Extensions\Model\Ring\Design\Product;

class Type extends \Magento\Catalog\Model\Product\Type\AbstractType {
    protected $_dmAttributes;
    protected $_dmOptionModel;
    protected $_dmOptionGroupModel;
    protected $_allDmOptions;
    protected $_defaultDmOptions;
    protected $_helper;
    protected $_cache;

    protected $_map = [
        'dm_stone_type' => 'main-stone-type',
        'dm_stone_shape' => 'main-stone-shape',
        'dm_band' => 'band',
        'dm_metal' => 'metal'
    ];

    protected $_dmOptionSortOrder = [
        'main-stone-type' => false,
        'main-stone-shape' => [
            'round', 'cushion', 'oval', 'princess', 'emerald', 'radiant', 'pear', 'asscher', 'marquise', 'heart', 
            'square-cushion', 'long-cushion', 'square-radiant', 'long-radiant'
        ],
        'main-stone-carat' => false,
        'main-stone-color' => [
            'd-e', 'e-f', 'f-g', 'g-h', 'i-j', 'i+',
            'd', 'e', 'f', 'g', 'h', 'i', 'j',
            'fancy-light', 'fancy-yellow', 'fancy-intense', 'fancy-black'
        ],
        'main-stone-clarity' => [
            'fl', 'vvs1', 'vvs1-vvs2', 'vvs2', 'vs1', 'vs1-vs2', 'vs2', 'si1+', 'si1', 'si1-si2', 'si2', 'aaa'
        ],
        'main-stone-cert' => false,
        'metal' => false,
        'band' => false,
        'side-stone-shape-1' => false,
        'side-stone-carat-1' => false,
        'side-stone-color-clarity-1' => false,
        'side-stone-shape-2' => false,
        'side-stone-carat-2' => false,
        'side-stone-color-clarity-2' => false,
        'side-stone-shape-3' => false,
        'side-stone-carat-3' => false,
        'side-stone-color-clarity-3' => false,
        'side-stone-shape-4' => false,
        'side-stone-carat-4' => false,
        'side-stone-color-clarity-4' => false,
        'ring-size' => false,
        'setting-options-stone' => false,
        'setting-options-size' => false,
        'main-stone-cut' => false,
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
                            if ($option->getCode() == 'si1' && 
                                ($this->_defaultDmOptions[$product->getId()]['main-stone-shape'] == 'emerald' || 
                                $this->_defaultDmOptions[$product->getId()]['main-stone-shape'] == 'asscher')
                            ) {
                                break;
                            }

                            $this->_defaultDmOptions[$product->getId()][$group] = $option;
                            break;
                        }
                    }

                    if (!isset($this->_defaultDmOptions[$product->getId()][$group])) {
                        $this->_defaultDmOptions[$product->getId()][$group] = current($options[$group]);
                        if (isset($this->_defaultDmOptions[$product->getId()]['main-stone-shape']) && 
                            ($this->_defaultDmOptions[$product->getId()]['main-stone-shape'] == 'emerald' || 
                            $this->_defaultDmOptions[$product->getId()]['main-stone-shape'] == 'asscher') && 
                            $this->_defaultDmOptions[$product->getId()][$group]->getCode() == 'si1'
                        ) {
                            $this->_defaultDmOptions[$product->getId()][$group] = next($options[$group]);
                        }
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

        if (isset($filters['option'])) {
            $optionSet = $this->_helper->getRingDesignOptions($product, $filters);
            $options = $optionSet['allOptions'];
            $defaultOptions = $optionSet['defaultOptions'];
        } else {
            $options = $this->getAllDmOptions($product, true);
            $defaultOptions = $this->getDefaultDmOptions($product, true);
        }

        $result = [];

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

            if (!isset($result[$this->_map[$attributeKey]])) {
                $result[$this->_map[$attributeKey]] = $defaultOptions[$this->_map[$attributeKey]]->getCode();
            }    
        }

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

        $attributeKey = 'dm_band';
        if (isset($filters[$attributeKey])) {
            foreach ($options[$this->_map[$attributeKey]] as $option) {
                if ($option->getCode() == $this->_dmAttributes[$attributeKey]['by_id'][$filters[$attributeKey]]['slug']) {
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

            if (is_array($children) && isset($children[$group]) && !in_array($defaultOptions[$group]->getCode(), $children[$group])) {
                $result[$group] = $children[$group][0];
            } else if (isset($defaultOptions[$group])) {
                $result[$group] = $defaultOptions[$group]->getCode();
            }
        }

        if ($sort) {
            $this->_sortDmOptions($result);
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
            if (strpos($group, 'side-stone-shape') !== false && isset($allDmOptions[$group])) {
                foreach ($allDmOptions[$group] as $option) {
                    $values = json_decode($option->getValues(), true);
                    if (isset($values['qty']) && count($values['qty']) == 2) {
                        $qty = (double)$values['qty'][0];
                        if (isset($params["band"]) && $params["band"] == "bridal-set") {
                            $qty += (double)$values['qty'][1];
                        }

                        $sideStoneCaratGroup = str_replace('-shape', '-carat', $group);
                        if (isset($params[$sideStoneCaratGroup])) {
                            $carat += $qty * (double)$params[$sideStoneCaratGroup];
                        }
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
                if ($params["main-stone-type"] != "setting" && $group == 'setting-options-stone') {
                    continue;
                }

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

        $options = $newOptions;
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