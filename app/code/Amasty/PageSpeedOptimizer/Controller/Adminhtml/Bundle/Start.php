<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PageSpeedOptimizer
 */


namespace Amasty\PageSpeedOptimizer\Controller\Adminhtml\Bundle;

use Amasty\PageSpeedOptimizer\Model\Bundle\ResourceModel\Bundle;
use Amasty\PageSpeedOptimizer\Model\ConfigProvider;
use Amasty\PageSpeedOptimizer\Model\OptionSource\BundlingType;
use Magento\Backend\App\Action;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;

/**
 * @package Amasty\PageSpeedOptimizer
 */
class Start extends Action
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var TypeListInterface
     */
    private $cache;

    /**
     * @var WriterInterface
     */
    private $configWriter;

    /**
     * @var Bundle
     */
    private $bundleResource;

    /**
     * @var string
     */
    private $bundleHash;

    /**
     * @var string
     */
    private $rand;

    /**
     * @var UrlRewriteCollectionFactory
     */
    private $urlRewriteCollectionFactory;

    public function __construct(
        ConfigProvider $configProvider,
        StoreManagerInterface $storeManager,
        TypeListInterface $cache,
        WriterInterface $configWriter,
        Bundle $bundleResource,
        UrlRewriteCollectionFactory $urlRewriteCollectionFactory,
        Action\Context $context
    ) {
        parent::__construct($context);
        $this->configProvider = $configProvider;
        $this->storeManager = $storeManager;
        $this->cache = $cache;
        $this->configWriter = $configWriter;
        $this->bundleResource = $bundleResource;
        $this->urlRewriteCollectionFactory = $urlRewriteCollectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->bundleResource->clear();
        $this->bundleHash = $this->getRandHash();
        $this->configWriter->save(
            'amoptimizer/' . ConfigProvider::IS_CLOUD,
            (bool)$this->getRequest()->getParam('isCloud', false)
        );
        $this->configWriter->save('amoptimizer/' . ConfigProvider::BUNDLE_HASH, $this->bundleHash);
        $this->configWriter->save('amoptimizer/' . ConfigProvider::BUNDLING_TYPE, BundlingType::SUPER_BUNDLING);
        $this->cache->cleanType(\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER);
        $result = [];
        //phpcs:ignore
        $this->rand = md5(mt_rand());
        foreach ($this->storeManager->getStores() as $store) {
            $result[] = $this->getBundleUrl($store->getId(), '');
            $this->setSimpleProductUrl($result, $store->getId())
                ->setConfigurableProductUrl($result, $store->getId())
                ->setCategoryUrl($result, $store->getId());
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData([
            'links' => array_merge(array_unique($result))
        ]);
    }

    /**
     * @param int $storeId
     * @param string $entityType
     *
     * @return \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection
     */
    public function getRewriteCollection($storeId, $entityType)
    {
        return $this->urlRewriteCollectionFactory->create()
            ->addStoreFilter([$storeId], false)
            ->addFieldToFilter('entity_type', $entityType);
    }

    public function setSimpleProductUrl(&$result, $storeId)
    {
        $collection = $this->getRewriteCollection($storeId, 'product')
            ->join(
                'catalog_product_entity',
                'main_table.entity_id = catalog_product_entity.entity_id'
            )->addFieldToFilter('catalog_product_entity.type_id', 'simple');

        if ($item = $collection->getFirstItem()) {
            $result[] = $this->getBundleUrl($storeId, $item->getRequestPath());
        };

        return $this;
    }

    public function setConfigurableProductUrl(&$result, $storeId)
    {
        $collection = $this->getRewriteCollection($storeId, 'product')
            ->join(
                'catalog_product_entity',
                'main_table.entity_id = catalog_product_entity.entity_id'
            )->addFieldToFilter('catalog_product_entity.type_id', 'configurable');

        if ($item = $collection->getFirstItem()) {
            $result[] = $this->getBundleUrl($storeId, $item->getRequestPath());
        };

        return $this;
    }

    public function setCategoryUrl(&$result, $storeId)
    {
        $collection = $this->getRewriteCollection($storeId, 'category')->setPageSize(2);

        foreach ($collection->getItems() as $item) {
            $result[] = $this->getBundleUrl($storeId, $item->getRequestPath());
        }

        return $this;
    }

    public function getBundleUrl($storeId, $url)
    {
        return $this->storeManager->getStore($storeId)->getBaseUrl()
            . $url . '?amoptimizer_bundle_check=' . $this->bundleHash
            . '&bu=' . $this->_url->getBaseUrl()
            . '&amoptimizer_not_move=1&rand=' . $this->rand
            . '&___store=' . $this->storeManager->getStore($storeId)->getCode();
    }

    /**
     * @return string
     */
    private function getRandHash()
    {
        /** @codingStandardsIgnoreStart */
        mt_srand();
        $hash = md5(mt_rand());
        /** @codingStandardsIgnoreEnd */
        return $hash;
    }
}
