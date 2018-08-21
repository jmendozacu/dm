<?php
namespace DiamondMansion\Extensions\Controller\Adminhtml\Designer;

use Complex\Exception;
use DiamondMansion\Extensions\Model\Designer as Designer;

class Save extends \DiamondMansion\Extensions\Controller\Adminhtml\Base
{
    protected $helper;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \DiamondMansion\Extensions\Helper\Data $helper
    ) {
        parent::__construct($context, $resultPageFactory);

        $this->helper = $helper;
    }

    public function execute()
    {
        try{
            $designerModel = $this->_objectManager->create(Designer::class);
            $designerData = $this->getRequest()->getParam('designer');

            if (isset($designerData['photo'])) {
                $photoData = is_array($designerData['photo']) ? $designerData['photo'][0] : $designerData['photo'];
                if (isset($photoData['name']) && !empty($photoData['name'])) {
                    $designerPhotoDir = $this->helper->getDesignerPhotoDir();
                    if (!file_exists($designerPhotoDir)) {
                        mkdir($designerPhotoDir, 0777);
                    }

                    $designerPhotoTmpDir = $this->helper->getDesignerPhotoTmpDir();
                    if (!rename($designerPhotoTmpDir . $photoData['name'], $designerPhotoDir . $photoData['name'])) {
                        $designerData['photo'] = "";
                    } else {
                        $designerData['photo'] = $photoData['name'];
                    }
                } else {
                    $designerData['photo'] = "";
                }
            }

            $designerModel->setData($designerData);
            $designerModel->save();
            $this->messageManager->addSuccess(__('Designer has been saved!'));
        } catch (Exception $e) {
            $this->messageManager->addError(__('Error while trying to save designer: '));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}