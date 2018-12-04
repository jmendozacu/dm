<?php

namespace DiamondMansion\Extensions\Override\Amasty\ShopbyPage\Model\ResourceModel;

use Amasty\ShopbyPage\Api\Data\PageInterface;

class PageRepository extends \Amasty\ShopbyPage\Model\ResourceModel\PageRepository
{
    /**
     * @param \Magento\Catalog\Api\Data\CategoryInterface $category
     * @return \Amasty\ShopbyPage\Api\Data\PageSearchResultsInterface
     */
    public function getList(\Magento\Catalog\Api\Data\CategoryInterface $category)
    {
        $searchResults = $this->pageSearchResultsFactory->create();

        $collection = $this->pageFactory->create()->getCollection()
            ->addFieldToFilter('categories', [
                ['finset' => $category->getId()],
                ['eq' => 0],
                ['null' => true]
            ])
            ->addFieldToFilter('top_block_id', ['neq' => 'NULL'])
            ->addStoreFilter($category->getStoreId());

        $pagesData = [];

        /** @var \Amasty\ShopbyPage\Model\Page $page */
        foreach ($collection as $page) {
            $pagesData[] = $this->getPageData($page);
        }

        usort($pagesData, function (PageInterface $a, PageInterface $b) {
            return count($b->getConditions()) - count($a->getConditions());
        });

        $searchResults->setTotalCount($collection->getSize());

        return $searchResults->setItems($pagesData);
    }
}