<?php
namespace DiamondMansion\Extensions\Controller\Adminhtml\Designer;

use DiamondMansion\Extensions\Model\Designer as Designer;

class MassDelete extends \DiamondMansion\Extensions\Controller\Adminhtml\Base
{
    protected $_filter;

    protected $_collectionFactory;

    /**
     * @param Context           $context
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \DiamondMansion\Extensions\Model\ResourceModel\Designer\CollectionFactory $collectionFactory
    ) {
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;

        parent::__construct($context, $resultPageFactory);
    }

    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());

        $recordDeleted = 0;
        foreach ($collection->getItems() as $record) {
            $record->setId($record->getEntityId());
            $record->delete();
            $recordDeleted++;
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $recordDeleted));

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}