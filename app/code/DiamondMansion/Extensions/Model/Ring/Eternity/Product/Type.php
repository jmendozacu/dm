<?php
namespace DiamondMansion\Extensions\Model\Ring\Eternity\Product;

class Type extends \Magento\Catalog\Model\Product\Type\AbstractType {
    protected $_dmAttributes;
    protected $_dmOptionModel;
    protected $_dmOptionGroupModel;
    protected $_allDmOptions;
    protected $_defaultDmOptions;
    protected $_helper;

    protected $_map = [
        'dm_stone_type' => 'stone-type',
        'dm_stone_shape' => 'stone-shape',
        'dm_metal' => 'metal'
    ];

    protected $_dmOptionSortOrder = [
        'stone-type',
        'stone-shape',
        'stone-carat',
        'stone-color-clarity',
        'metal',
        'ring-size',
        'order-type',
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

    public function getAllDmOptions($product, $sort = false) {
        if (!isset($this->_allDmOptions[$product->getId()])) {
            $collection = $this->_dmOptionModel->getCollection()
                ->addFieldToFilter('product_id', $product->getId())
                ->joinDetails($product->getTypeId());

            $this->_allDmOptions[$product->getId()] = [];
            foreach ($collection as $item) {
                if (!isset($this->_allDmOptions[$product->getId()][$item->getGroup()])) {
                    $this->_allDmOptions[$product->getId()][$item->getGroup()] = [];
                }

                $this->_allDmOptions[$product->getId()][$item->getGroup()][$item->getCode()] = $item;
            }
        }

        if ($sort) {
            $this->_sortDmOptions($this->_allDmOptions[$product->getId()]);
        }

        return $this->_allDmOptions[$product->getId()];
    }

    public function getDefaultDmOptions($product, $sort = false) {
        if (!isset($this->_defaultDmOptions[$product->getId()])) {
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
            $optionSet = $this->_helper->getRingEternityOptions($product, $filters);
            $options = $optionSet['allOptions'];
            $defaultOptions = $optionSet['defaultOptions'];
        } else {
            $options = $this->getAllDmOptions($product);
            $defaultOptions = $this->getDefaultDmOptions($product);
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

        $name = [];
        if (isset($params["stone-carat"])) { 
            $name[] = (double)($this->getTotalCarat($product))."ct.";
        }

        if (isset($params["stone-type"])) { 
            $name[] = $allDmOptions['stone-type'][$params["stone-type"]]->getTitle();
        }
        $name[] = "Diamond";

        if (isset($params["stone-shape"])) { 
            $name[] = ($params["stone-shape"] == "heart") ? $allDmOptions['stone-shape'][$params["stone-shape"]]->getTitle() . " shape" : $allDmOptions['stone-shape'][$params["stone-shape"]]->getTitle() . " cut";
        }
        
        $name[] = $mainName;
        
        if (isset($params["metal"])) { 
            $name[] = $allDmOptions['metal'][$params["metal"]]->getTitle(); 
        }

        return implode(" ", $name);
    }

	public function getTotalCarat($product) {
        $allDmOptions = $this->getAllDmOptions($product);
        $params = $this->getDmOptions($product);

        $carat = 0;        
        if (isset($params["stone-shape"]) && isset($params["stone-carat"]) && isset($params["ring-size"])) {
            $values = json_decode($allDmOptions["stone-shape"][$params["stone-shape"]]->getValues(), true);
            
            $carat = (double) floor($params["stone-carat"] * 10) / 10 ;

            $amount = 0;
            if ($values["amount"]) {
                $amount = $values["amount"][$params["stone-carat"] . "-" . $params["ring-size"]];
            }

            $carat *= $amount;
        }

        return $carat;
    }

	public function getProductUrl($product) {
        $params = $this->getDmOptions($product);
        $filters = $product->getFilters();

        $urlPrefix = "";
        if (isset($params["stone-shape"])) {
            $urlPrefix = ($params["stone-shape"] == 'heart') ? $params["stone-shape"] . '-shape-' : $params["stone-shape"] . '-cut-';
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
            $url .= "?option=" . $sku;
        }
        
        return $url;
    }
    
    public function getImage($product) {
        $param = $this->getDmOptions($product);
        
        $images = $this->_helper->getProductImages([
            'sku' => $product->getSku(),
            'type' => $param['stone-type'],
            'shape' => $param['stone-shape'],
            'carat' => $param['stone-carat'],
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

    protected function _prepareProduct(\Magento\Framework\DataObject $buyRequest, $product, $processMode) {
        if ($buyRequest['dm_options']) {
            $filters = $product->getFilters();
            $filters['option'] = $buyRequest['dm_options'];
            $product->setFilters($filters);
        }
        
        return parent::_prepareProduct($buyRequest, $product, $processMode);
    }
}