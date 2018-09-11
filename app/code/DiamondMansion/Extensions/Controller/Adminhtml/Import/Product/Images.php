<?php
/**
 * Created by May.
 * User: May
 * Date: 8/19/2018
 * Time: 2:51 PM
 */

namespace DiamondMansion\Extensions\Controller\Adminhtml\Import\Product;

use \PhpOffice\PhpSpreadsheet\IOFactory;
use \Magento\Framework\App\Filesystem\DirectoryList;

class Images extends \DiamondMansion\Extensions\Controller\Adminhtml\Base
{
    protected $_productFactory;
    protected $_productOptionsFactory;
    protected $_helper;
    protected $_eavConfig;
    protected $_categoryLinkManagement;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \DiamondMansion\Extensions\Model\ProductOptionsFactory $productOptionsFactory,
        \DiamondMansion\Extensions\Helper\Data $helper,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Catalog\Api\CategoryLinkManagementInterface $categoryLinkManagement
    ) {
        parent::__construct($context, $resultPageFactory);

        $this->_productFactory = $productFactory;
        $this->_productOptionsFactory = $productOptionsFactory;
        $this->_helper = $helper;
        $this->_eavConfig = $eavConfig;
        $this->_categoryLinkManagement = $categoryLinkManagement;
    }

    public function execute()
    {
        try {
            $fp = fopen(BP . DIRECTORY_SEPARATOR . 'pub' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'product.csv', 'r');

            $mainImages = [];
            while (($line = fgetcsv($fp)) !== false) {
                if (!isset($attributes)) {
                    $attributes = $line;
                } else {
                    $line = array_combine($attributes, $line);
                    $mainImages[$line['sku']] = [
                        'image' => $line['image'],
                        'small_image' => $line['small_image'],
                        'thumbnail' => $line['thumbnail']
                    ];
                }
            }

            fclose($fp);

            $fp = fopen(BP . DIRECTORY_SEPARATOR . 'pub' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'images.csv', 'r');

            $list = [];
            while (($line = fgetcsv($fp)) !== false) {
                $list[] = $line;
            }

            fclose($fp);

            $mediaGalleryProcessor = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Catalog\Model\Product\Gallery\Processor::class);
            foreach ($list as $line) {
                $productId = $line[0];
                $productModel = $this->_productFactory->create();
                $productModel->load($productId);

                $mediaGalleryImages = $productModel->getMediaGalleryImages();
                // foreach ($mediaGalleryImages as $image) {
                //     $mediaGalleryProcessor->removeImage($productModel, $image['file']);
                // }
                // $productModel->save();
                // break;

                if (count($mediaGalleryImages)) {
                    continue;
                }

                $images = explode(':::', $line[2]);
                $default = [];

                if (isset($mainImages[$line[1]])) {
                    $default['image'] = in_array($mainImages[$line[1]]['image'], $images) ? $mainImages[$line[1]]['image'] : $images[0];
                    $default['small_image'] = in_array($mainImages[$line[1]]['small_image'], $images) ? $mainImages[$line[1]]['small_image'] : $images[0];
                    $default['thumbnail'] = in_array($mainImages[$line[1]]['thumbnail'], $images) ? $mainImages[$line[1]]['thumbnail'] : $images[0];
                }

                foreach ($images as $image) {
                    if (empty($image)) {
                        continue;
                    }
                    
                    if (in_array($image, $default)) {
                        $productModel->addImageToMediaGallery('import/catalog/product' . $image, ['image', 'small_image', 'thumbnail'], false, false);
                    } else {
                        $productModel->addImageToMediaGallery('import/catalog/product' . $image, [], false, false);
                    }
                }
                $productModel->save();
                //break;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        exit;
    }
}