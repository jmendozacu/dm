<?php
/**
 *
  * Copyright © 2018 Magenest. All rights reserved.
  * See COPYING.txt for license details.
  *
  * Magenest_InstagramShop extension
  * NOTICE OF LICENSE
  *
  * @category Magenest
  * @package  Magenest_InstagramShop
  * @author    dangnh@magenest.com

 */

namespace Magenest\InstagramShop\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magenest\InstagramShop\Api\Data\HotspotInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magenest\InstagramShop\Model\ResourceModel\Hotspot\CollectionFactory as HotspotCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magenest\InstagramShop\Api\Data\HotspotSearchResultsInterfaceFactory;
use Magenest\InstagramShop\Model\ResourceModel\Hotspot as ResourceHotspot;
use Magenest\InstagramShop\Api\HotspotRepositoryInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;

class HotspotRepository implements HotspotRepositoryInterface
{

    protected $dataObjectHelper;

    protected $dataHotspotFactory;

    protected $resource;

    protected $hotspotFactory;

    protected $hotspotCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    private $storeManager;

    /**
     * @param ResourceHotspot $resource
     * @param HotspotFactory $hotspotFactory
     * @param HotspotInterfaceFactory $dataHotspotFactory
     * @param HotspotCollectionFactory $hotspotCollectionFactory
     * @param HotspotSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceHotspot $resource,
        HotspotFactory $hotspotFactory,
        HotspotInterfaceFactory $dataHotspotFactory,
        HotspotCollectionFactory $hotspotCollectionFactory,
        HotspotSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->hotspotFactory = $hotspotFactory;
        $this->hotspotCollectionFactory = $hotspotCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataHotspotFactory = $dataHotspotFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Magenest\InstagramShop\Api\Data\HotspotInterface $hotspot
    ) {
        /* if (empty($hotspot->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $hotspot->setStoreId($storeId);
        } */
        try {
            $this->resource->save($hotspot);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the hotspot: %1',
                $exception->getMessage()
            ));
        }
        return $hotspot;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($hotspotId)
    {
        $hotspot = $this->hotspotFactory->create();
        $this->resource->load($hotspot, $hotspotId);
        if (!$hotspot->getId()) {
            throw new NoSuchEntityException(__('Hotspot with id "%1" does not exist.', $hotspotId));
        }
        return $hotspot;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->hotspotCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $fields[] = $filter->getField();
                $condition = $filter->getConditionType() ?: 'eq';
                $conditions[] = [$condition => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
        
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Magenest\InstagramShop\Api\Data\HotspotInterface $hotspot
    ) {
        try {
            $this->resource->delete($hotspot);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Hotspot: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($hotspotId)
    {
        return $this->delete($this->getById($hotspotId));
    }
}
