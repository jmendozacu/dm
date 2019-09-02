<?php

namespace Magenest\InstagramShop\Model;

/**
 * Class Instagram
 * @package Magenest\InstagramShop\Model
 */
class Instagram
{
    /**
     * @var ResourceModel\Photo\CollectionFactory
     */
    protected $photoCollectionFactory;

    /**
     * @var ResourceModel\TaggedPhoto\CollectionFactory
     */
    protected $taggedPhotoCollectionFactory;

    /**
     * @var ResourceModel\Report\CollectionFactory
     */
    protected $reportCollectionFactory;

    /**
     * Instagram constructor.
     * @param ResourceModel\Photo\CollectionFactory $photoCollectionFactory
     * @param ResourceModel\TaggedPhoto\CollectionFactory $taggedPhotoCollectionFactory
     * @param ResourceModel\Report\CollectionFactory $reportCollectionFactory
     */
    public function __construct(
        \Magenest\InstagramShop\Model\ResourceModel\Photo\CollectionFactory $photoCollectionFactory,
        \Magenest\InstagramShop\Model\ResourceModel\TaggedPhoto\CollectionFactory $taggedPhotoCollectionFactory,
        \Magenest\InstagramShop\Model\ResourceModel\Report\CollectionFactory $reportCollectionFactory
    ) {
        $this->photoCollectionFactory       = $photoCollectionFactory;
        $this->taggedPhotoCollectionFactory = $taggedPhotoCollectionFactory;
        $this->reportCollectionFactory      = $reportCollectionFactory;
    }

    /**
     * Instagram Account Changed
     */
    public function processAccountChanged()
    {
        $this->removeAllPhotos();
        $this->removeAllTaggedPhotos();
        $this->remoteAllReports();
    }

    /**
     * Remove all photos
     */
    private function removeAllPhotos()
    {
        $collection = $this->photoCollectionFactory->create();
        $this->removeAllItems($collection);
    }

    /**
     * Remove all tagged photos
     */
    private function removeAllTaggedPhotos()
    {
        $collection = $this->taggedPhotoCollectionFactory->create();
        $this->removeAllItems($collection);
    }

    /**
     *
     */
    private function remoteAllReports()
    {
        $collection=$this->reportCollectionFactory->create();
        $this->removeAllItems($collection);
    }
    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection
     */
    private function removeAllItems($collection)
    {
        if ($collection instanceof \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection) {
            foreach ($collection as $item) {
                $item->delete();
            }
        }
    }
}