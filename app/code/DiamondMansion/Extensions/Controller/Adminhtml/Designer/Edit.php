<?php
namespace DiamondMansion\Extensions\Controller\Adminhtml\Designer;

use DiamondMansion\Extensions\Model\Designer;

class Edit extends \DiamondMansion\Extensions\Controller\Adminhtml\Base
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if (!($designer = $this->_objectManager->create(Designer::class)->load($id))) {
            $this->messageManager->addError(__('Unable to proceed. Please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        return parent::execute();
    }
}