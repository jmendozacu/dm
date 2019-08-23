<?php

namespace DiamondMansion\Extensions\Override\Magento\Catalog\Model;

class Product extends \Magento\Catalog\Model\Product
{
    protected $_filters = [];
    protected $_isCustomized = true;
    protected $_request;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $metadataService,
        \Magento\Catalog\Model\Product\Url $url,
        \Magento\Catalog\Model\Product\Link $productLink,
        \Magento\Catalog\Model\Product\Configuration\Item\OptionFactory $itemOptionFactory,
        \Magento\CatalogInventory\Api\Data\StockItemInterfaceFactory $stockItemFactory,
        \Magento\Catalog\Model\Product\OptionFactory $catalogProductOptionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $catalogProductStatus,
        \Magento\Catalog\Model\Product\Media\Config $catalogProductMediaConfig,
        \Magento\Catalog\Model\Product\Type $catalogProductType,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Catalog\Helper\Product $catalogProduct,
        \Magento\Catalog\Model\ResourceModel\Product $resource,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $resourceCollection,
        \Magento\Framework\Data\CollectionFactory $collectionFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry,
        \Magento\Catalog\Model\Indexer\Product\Flat\Processor $productFlatIndexerProcessor,
        \Magento\Catalog\Model\Indexer\Product\Price\Processor $productPriceIndexerProcessor,
        \Magento\Catalog\Model\Indexer\Product\Eav\Processor $productEavIndexerProcessor,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Catalog\Model\Product\Image\CacheFactory $imageCacheFactory,
        \Magento\Catalog\Model\ProductLink\CollectionProvider $entityCollectionProvider,
        \Magento\Catalog\Model\Product\LinkTypeProvider $linkTypeProvider,
        \Magento\Catalog\Api\Data\ProductLinkInterfaceFactory $productLinkFactory,
        \Magento\Catalog\Api\Data\ProductLinkExtensionFactory $productLinkExtensionFactory,
        \Magento\Catalog\Model\Product\Attribute\Backend\Media\EntryConverterPool $mediaGalleryEntryConverterPool,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $joinProcessor,
        \Magento\Eav\Model\Config $config = null,
        \Magento\Catalog\Model\FilterProductCustomAttribute $filterCustomAttribute = null,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $storeManager,
            $metadataService,
            $url,
            $productLink,
            $itemOptionFactory,
            $stockItemFactory,
            $catalogProductOptionFactory,
            $catalogProductVisibility,
            $catalogProductStatus,
            $catalogProductMediaConfig,
            $catalogProductType,
            $moduleManager,
            $catalogProduct,
            $resource,
            $resourceCollection,
            $collectionFactory,
            $filesystem,
            $indexerRegistry,
            $productFlatIndexerProcessor,
            $productPriceIndexerProcessor,
            $productEavIndexerProcessor,
            $categoryRepository,
            $imageCacheFactory,
            $entityCollectionProvider,
            $linkTypeProvider,
            $productLinkFactory,
            $productLinkExtensionFactory,
            $mediaGalleryEntryConverterPool,
            $dataObjectHelper,
            $joinProcessor,
            $data,
            $config,
            $filterCustomAttribute
        );

        $this->_request = $request;
    }    

    public function setFilters($filters) {
        $this->_filters = $filters;
    }

    public function getFilters() {
        return $this->_filters;
    }

    public function getAllDmOptions($sort = false) {
        return $this->getTypeInstance()->getAllDmOptions($this, $sort);
    }

    public function getDefaultDmOptions($sort = false) {
        return $this->getTypeInstance()->getDefaultDmOptions($this, $sort);
    }

    public function setDefaultDmOptions($options) {
        $this->getTypeInstance()->setDefaultDmOptions($this, $options);
    }

    public function getDmOptions($sort = false) {
        return $this->getTypeInstance()->getDmOptions($this, $sort);
    }

    public function getDmName() {
        if (method_exists($this->getTypeInstance(), 'getName')) {
            return $this->getTypeInstance()->getName($this, parent::getName());
        } else {
            return parent::getName();
        }
    }

    public function getMetaTitle() {
        if (method_exists($this->getTypeInstance(), 'getMetaTitle')) {
            $oldFilters = $this->getFilters();
            $params = $this->_request->getParams();
            if (isset($params['option'])) {
                $this->setFilters($params);
            }
            return $this->getTypeInstance()->getMetaTitle($this, parent::getName());
            $this->setFilters($oldFilters);
        } else {
            return parent::getMetaTitle();
        }
    }

    public function getProductUrl($useSid = NULL) {
        if (method_exists($this->getTypeInstance(), 'getProductUrl')) {
            return $this->getTypeInstance()->getProductUrl($this);
        } else {
            return parent::getProductUrl($useSid);
        }
    }

    public function getImage() {
        if ($this->getIsCustomized() && method_exists($this->getTypeInstance(), 'getImage')) {
            $result = $this->getTypeInstance()->getImage($this);
            if (strpos($result, 'placeholder')) {
                $this->load('media_gallery');
                $defaultImage = parent::getImage();
                foreach ($this->getMediaGalleryImages() as $image) {
                    if ($defaultImage == $image->getFile()) {
                        $result = $image->getUrl();
                        break;
                    }
                }
            }
        
            return $result;
        } else {
            return parent::getImage();
        }
    }

    public function getDefaultImage() {
        return parent::getImage();
    }

    public function setIsCustomized($isCustomized) {
        $this->_isCustomized = $isCustomized;
    }

    public function getIsCustomized() {
        return $this->_isCustomized;
    }
}