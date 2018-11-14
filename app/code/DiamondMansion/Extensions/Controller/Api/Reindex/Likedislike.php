<?php

namespace DiamondMansion\Extensions\Controller\Api\Reindex;

class Likedislike extends \Magento\Framework\App\Action\Action
{
    protected $_productFactory;
    protected $_likedislikeCollectionFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \DiamondMansion\Extensions\Model\ResourceModel\LikeDislike\CollectionFactory $likedislikeCollectionFactory
    ) {
        $this->_productFactory = $productFactory;
        $this->_likedislikeCollectionFactory = $likedislikeCollectionFactory;

        return parent::__construct($context);
    }

    public function execute() {
        try {
            $collection = $this->_productFactory->create()->getCollection();

            foreach ($collection as $product) {
                $product = $this->_productFactory->create()->load($product->getId());
                $likes = $this->_likedislikeCollectionFactory->create()
                            ->addFieldToFilter('product_id', $product->getId())
                            ->addFieldToFilter('review', 1)
                            ->getSize();
                $dislikes = $this->_likedislikeCollectionFactory->create()
                            ->addFieldToFilter('product_id', $product->getId())
                            ->addFieldToFilter('review', 0)
                            ->getSize();
        
                if ($product->getDmLikes() != $likes || $product->getDmDislikes() != $dislikes) {
                    $product->setDmLikes($likes);
                    $product->setDmDislikes($dislikes);
                    $product->save();
                } else {
                }
            }

        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
}