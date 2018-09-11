<?php
/**
 * Created by May.
 * User: May
 * Date: 8/19/2018
 * Time: 2:51 PM
 */

namespace DiamondMansion\Extensions\Controller\Adminhtml\Import;

use \PhpOffice\PhpSpreadsheet\IOFactory;
use \Magento\Framework\App\Filesystem\DirectoryList;

class Category extends \DiamondMansion\Extensions\Controller\Adminhtml\Base
{
    protected $_categoryFactory;
    protected $_helper;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \DiamondMansion\Extensions\Helper\Data $helper
    ) {
        parent::__construct($context, $resultPageFactory);

        $this->_categoryFactory = $categoryFactory;
        $this->_helper = $helper;
    }

    public function execute()
    {
        $fp = fopen(BP . DIRECTORY_SEPARATOR . 'pub' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'category.csv', 'r');

        $list = [];
        while (($line = fgetcsv($fp)) !== false) {
            $list[] = $line;
        }

        fclose($fp);

        try {
            foreach ($list as $line) {
                if ($line[0] < 3) {
                    continue;
                }
                $categoryModel = $this->_categoryFactory->create();
                $path = explode("/", $line[9]);
                $data = [
                    'name' => $line[1],
                    'is_active' => $line[2],
                    'url_key' => $line[3],
                    'description' => $line[4],
                    'meta_title' => $line[5],
                    'meta_keywords' => $line[6],
                    'meta_description' => $line[7],
                    'include_in_menu' => $line[8],
                    'path' => $line[9],//implode('/', array_slice($path, 0, count($path) - 1)),
                    'parent_id' => $line[10],
                    'store_id' => $this->_helper->getStoreManager()->getStore()->getStoreId(),
                ];

                $categoryModel->load($line[0]);
                $categoryModel->setData($data);
                $categoryModel->setId($line[0]);
                $categoryModel->save();

                echo "\r\nCategory : " . $line[1] . " is created successfully.\r\n";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        exit;
    }
}