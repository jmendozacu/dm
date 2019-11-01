<?php
namespace Magecomp\Emailquotepro\Model\Admin\Quote;

use Magecomp\Emailquotepro\Model\ResourceModel\Emailproductquote\CollectionFactory as EmailquoteCollection;
use Magento\Backend\Model\Auth\Session;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Notification\MessageInterface;

class Messages implements MessageInterface
{

    protected $backendUrl;
    protected $authSession;

    public function __construct(
        UrlInterface $backendUrl,
        Session $authSession,
        EmailquoteCollection $collectionFactory
    )
    {
        $this->authSession = $authSession;
        $this->backendUrl = $backendUrl;
        $this->collectionFactory = $collectionFactory;
    }

    public function getText()
    {
        $message = __('You Have ' . $this->getNewRequestForQuoteCount() . ' New Inquiries In Magecomp Email Quote');
        return $message;
    }

    public function getNewRequestForQuoteCount()
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', '2');
        return $collection->count();
    }

    public function getIdentity()
    {
        return hash('sha256','MAGECOMP_EMAILQUOTEPRO' . $this->authSession->getUser()->getLogdate());
    }

    public function isDisplayed()
    {
        return $this->getNewRequestForQuoteCount() > 0;
    }

    public function getSeverity()
    {
        return \Magento\Framework\Notification\MessageInterface::SEVERITY_CRITICAL;
    }
}