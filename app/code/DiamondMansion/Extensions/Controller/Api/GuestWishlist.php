<?php

namespace DiamondMansion\Extensions\Controller\Api;

class GuestWishlist extends \Magento\Framework\App\Action\Action
{
    protected $_likedislikeFactory;
    protected $_session;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \DiamondMansion\Extensions\Model\LikeDislikeFactory $likedislikeFactory,
        \Magento\Framework\Session\SessionManagerInterface $session
    ) {
        $this->_likedislikeFactory = $likedislikeFactory;
        $this->_session = $session;

        return parent::__construct($context);
    }

    public function execute() {
        $params = $this->getRequest()->getParams();

        $this->_session->setGuestEmail($params['email']);

        $collection = $this->_likedislikeFactory->create()->getCollection()
            ->addFieldToFilter('email', $params['email'])
            ->addFieldToFilter('review', 1);

        $wishlist = [];
        foreach ($collection as $item) {
            $wishlist[] = json_decode($item->getProductOptions());
        }
        echo json_encode($wishlist);
    }
}