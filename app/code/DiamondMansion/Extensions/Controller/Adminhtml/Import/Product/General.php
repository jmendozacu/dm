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

class General extends \DiamondMansion\Extensions\Controller\Adminhtml\Base
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
            $skus = [];

            $fp = fopen(BP . DIRECTORY_SEPARATOR . 'pub' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'images.csv', 'r');

            while (($line = fgetcsv($fp)) !== false) {
                $skus[$line[0]] = $line[1];
            }

            fclose($fp);

            $fp = fopen(BP . DIRECTORY_SEPARATOR . 'pub' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'product.csv', 'r');

            $list = [];
            while (($line = fgetcsv($fp)) !== false) {
                if (!isset($attributes)) {
                    $attributes = $line;
                } else {
                    if (in_array($line[5], $skus)) {
                        $list[] = array_combine($attributes, $line);
                    }
                }
            }

            fclose($fp);

            foreach ($list as $line) {
                $productId = array_search($line['sku'], $skus);
                $productModel = $this->_productFactory->create();
                $productModel->load($productId);

                if ($productModel->getTypeId() == 'dm_ring_design' || $productModel->getSku()) {

                    if ($productModel->getSku()) {
                        if (!empty($line['dm_metal_type'])) {
                            $eavAttribute = $this->_eavConfig->getAttribute('catalog_product', 'dmc_metal');
                            $eavOptions = [];
                            foreach ($eavAttribute->getSource()->getAllOptions() as $eavOption) {
                                $eavOptions[$this->_helper->getSlug($eavOption['label'])] = $eavOption;
                            }
                            $metal = $this->_helper->getSlug(trim($line['dm_metal_type']));
                            $productModel->setData('dmc_metal', $eavOptions[$metal]['value']);
                            $productModel->getResource()->saveAttribute($productModel, 'dmc_metal');    
                        }
                    }

                    continue;
                }

                $productModel->setSku($line['sku']);
                $productModel->setName($line['name']);
                $productModel->setUrlKey($line['url_key']);
                $productModel->setUrlPath($line['url_path']);
                $productModel->setMetaTitle($line['meta_title']);
                $productModel->setMetaDescription($line['meta_description']);
                $productModel->setMetaKeyword($line['meta_keyword']);
                $productModel->setDescription($line['description']);
                $productModel->setShortDescription($line['short_description']);
                $productModel->setAttributeSetId(4);
                $productModel->setStatus(trim($line['status']) == 'Enabled' ? 1 : 2);
                $productModel->setVisibility(trim($line['visibility']) == 'Not Visible Individually' ? 1 : 4);
                $productModel->setTaxClassId(2);
                $productModel->setTypeId(trim($line['type']));
                $productModel->setPrice($line['price']);
                $productModel->setSpecialPrice($line['special_price']);
                $productModel->setStockData([
                    'use_config_manage_stock' => 0,
                    'manage_stock' => 0,
                    'is_in_stock' => 1,
                    'qty' => 1
                ]);
                $productModel->setId($productId);
                $productModel->save();

                if (trim($line['category_ids'] != '')) {
                    $this->_categoryLinkManagement->assignProductToCategories(
                        $productModel->getSku(),
                        explode(',', $line['category_ids'])
                    );
                }

                // likes, dislikes
                $productModel->setData('dm_likes', $line['likes']);
                $productModel->getResource()->saveAttribute($productModel, 'dm_likes');
                $productModel->setData('dm_dislikes', $line['dislikes']);
                $productModel->getResource()->saveAttribute($productModel, 'dm_dislikes');
                $productModel->setData('dm_delivery_dates', $line['delivery_dates']);
                $productModel->getResource()->saveAttribute($productModel, 'dm_delivery_dates');

                foreach ($line as $key => $value) {
                    if (strpos($key, 'dm_') === false || $key == 'dm_fixed_center_flag' || $key == 'dm_side_exclude_price' || $key == 'dm_metal_type') {
                        continue;
                    }

                    $attribute = str_replace('dm_', 'dmz_', $key);
                    $productModel->setData($attribute, $value);
                    $productModel->getResource()->saveAttribute($productModel, $attribute);
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        exit;
    }
}