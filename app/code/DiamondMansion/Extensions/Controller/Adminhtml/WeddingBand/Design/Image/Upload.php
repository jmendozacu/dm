<?php
namespace DiamondMansion\Extensions\Controller\Adminhtml\WeddingBand\Design\Image;

use \Magento\Framework\App\Filesystem\DirectoryList;

class Upload extends \DiamondMansion\Extensions\Controller\Adminhtml\Base
{
    protected $_imageHelper;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \DiamondMansion\Extensions\Helper\Image $imageHelper
    ) {
        parent::__construct($context, $resultPageFactory);

        $this->_imageHelper = $imageHelper;
    }

    public function execute()
    {
        $validExtensions = array("png", "jpg", "jpeg", "gif");
        $error = "";
        $message = "";

        $elmId = $this->getRequest()->getParam('eid');

        $cats = explode("___", $elmId);

        if (count($cats) == 5) {
            $sku = $cats[0];
            $metal = $cats[1];
            $width = $cats[2];
            $finish = $cats[3];
            $index = $cats[4];

            if(isset($_FILES[$elmId]['name']) && $_FILES[$elmId]['name'] != '') {
                $fileName       = $_FILES[$elmId]['name'];
                $fileExt        = strtolower(substr(strrchr($fileName, "."), 1));
                $fileNamewoe    = rtrim($fileName, $fileExt);
                $fileName       = $index . '-' . str_replace(' ', '-', time() . "-" . $fileNamewoe ) . $fileExt;

                if (!in_array($fileExt, $validExtensions)) {
                    $error = "The uploaded file extension is not allowed";
                } else {
                    $relativePath = $this->_imageHelper->getProductImagePath([
                        'sku' => $sku,
                        'metal' => $metal,
                        'width' => $width,
                        'finish' => $finish
                    ]);

                    $dir = $this->_imageHelper->getProductImageDir() . $relativePath;

                    if(!is_dir($dir)){
                        mkdir($dir, 0777, true);
                    } else {
                        $list = glob($dir . '*.*');
                        if (isset($list[$index])) {
                            $file = $list[$index];
                            if (file_exists($file)) {
                                @unlink($file);
                            }
                        }

                        $resizedDir = $this->_imageHelper->getProductImageDir() . 'resized' . DIRECTORY_SEPARATOR . $relativePath;
                        if (is_dir($resizedDir)) {
                            foreach (glob($resizedDir . '*.*') as $file) {
                                if (file_exists($file)) {
                                    @unlink($file);
                                }
                            }
                        }
                    }

                    if(move_uploaded_file($_FILES[$elmId]['tmp_name'], $dir . $fileName)) {
                        $url = $this->_imageHelper->getMediaUrl() . 'catalog/design/' . str_replace(DIRECTORY_SEPARATOR, "/", $relativePath) . $fileName;
                    }
                }
            }
        } else {
            $error = "There was an error uploading the file, Please try again!";
            $url = "";
        }

        if (!empty($_FILES[$elmId]['error']))
        {
            switch($_FILES[$elmId]['error'])
            {
                case '1':
                    $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                    break;
                case '2':
                    $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
                    break;
                case '3':
                    $error = 'The uploaded file was only partially uploaded';
                    break;
                case '4':
                    $error = 'No file was uploaded.';
                    break;

                case '6':
                    $error = 'Missing a temporary folder';
                    break;
                case '7':
                    $error = 'Failed to write file to disk';
                    break;
                case '8':
                    $error = 'File upload stopped by extension';
                    break;
                case '999':
                default:
                    $error = 'No error code avaiable';
            }
        } elseif (empty($_FILES[$elmId]['tmp_name']) || $_FILES[$elmId]['tmp_name'] == 'none') {
            $error = 'No file was uploaded..' . $elmId;
        } else {
            @unlink($_FILES[$elmId]);
        }

        $result = [
            "error" => $error,
            "url" => $url,
            "eid" => $elmId,
            "time" => microtime()
        ];

        echo json_encode($result);
    }
}