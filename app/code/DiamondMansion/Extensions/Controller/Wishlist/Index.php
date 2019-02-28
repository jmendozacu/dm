<?php
namespace DiamondMansion\Extensions\Controller\Wishlist;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    protected $_resultRedirect;
    protected $_customerSession;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Controller\ResultFactory $resultRedirect
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_customerSession = $customerSession;
        $this->_resultRedirect = $resultRedirect;

        parent::__construct($context);
    }

    public function execute()
    {

        if ($this->_customerSession->isLoggedIn()) {
            $resultRedirect = $this->_resultRedirect->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('wishlist/');
            return $resultRedirect;
        }

        $resultPage = $this->_resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Wish List'));
        return $resultPage;
    }
}
