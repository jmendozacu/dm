<?php

namespace DiamondMansion\Extensions\Controller\Api;

class LikeDislike extends \Magento\Framework\App\Action\Action
{
    protected $_likedislikeFactory;
    protected $_productFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \DiamondMansion\Extensions\Model\LikeDislikeFactory $likedislikeFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->_productFactory = $productFactory;
        $this->_likedislikeFactory = $likedislikeFactory;

        return parent::__construct($context);
    }

    public function execute() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        $params = $this->getRequest()->getParams();

        $item = $this->_likedislikeFactory->create()->getCollection()
            ->addFieldToFilter('product_id', $params['product_id'])
            ->addFieldToFilter('customer_ip', $ipaddress)
            ->getFirstItem();

        if ($item && $item->getId()) {
            $likedislike = $this->_likedislikeFactory->create()->load($item->getId());
            $likedislike->setReview($params['liked']);
            $likedislike->save();
        } else {
            $likedislike = $this->_likedislikeFactory->create();
            $likedislike->setData([
                'product_id' => $params['product_id'],
                'customer_ip' => $ipaddress,
                'review' => $params['liked']
            ])->save();
        }

        $likes = $this->_likedislikeFactory->create()->getCollection()
                    ->addFieldToFilter('product_id', $params['product_id'])
                    ->addFieldToFilter('review', 1)
                    ->getSize();
        $dislikes = $this->_likedislikeFactory->create()->getCollection()
                    ->addFieldToFilter('product_id', $params['product_id'])
                    ->addFieldToFilter('review', 0)
                    ->getSize();

        $product = $this->_productFactory->create()->load($params['product_id']);
        $product->setDmLikes($likes);
        $product->setDmDislikes($dislikes);
        $product->save();
        
        echo json_encode([
            'likes' => $likes,
            'dislikes' => $dislikes
        ]);
    }
}