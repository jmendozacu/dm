<?php
namespace DiamondMansion\Extensions\Controller\Adminhtml\Designer;

use DiamondMansion\Extensions\Model\Designer as Designer;

class Delete extends \DiamondMansion\Extensions\Controller\Adminhtml\Base
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if (!($designer = $this->_objectManager->create(Designer::class)->load($id))) {
            $this->messageManager->addError(__('Unable to proceed. Please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }
        try{
            $designer->delete();
            $this->messageManager->addSuccess(__('Designer has been deleted !'));
        } catch (Exception $e) {
            $this->messageManager->addError(__('Error while trying to delete designer: '));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}