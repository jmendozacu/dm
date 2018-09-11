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

class RingDesign extends \DiamondMansion\Extensions\Controller\Adminhtml\Base
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

            $fp = fopen(BP . DIRECTORY_SEPARATOR . 'pub' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'dm_options_ring_design.csv', 'r');

            $map_group = [
                'Type' => 'main-stone-type',
                'Shape' => 'main-stone-shape',
                'Carat' => 'main-stone-carat',
                'Color' => 'main-stone-color',
                'Clarity' => 'main-stone-clarity',
                'Cert' => 'main-stone-cert',
                'Metal' => 'metal',
                'Band' => 'band',
                'SideShapes' => 'side-stone-shape',
                'SideCarats' => 'side-stone-carat',
                'SideColorClarity' => 'side-stone-color-clarity',
                'SideShapes2' => 'side-stone-shape',
                'SideCarats2' => 'side-stone-carat',
                'SideShapes3' => 'side-stone-shape',
                'SideCarats3' => 'side-stone-carat',
                'SideShapes4' => 'side-stone-shape',
                'SideCarats4' => 'side-stone-carat',
                'Ring Size' => 'ring-size',
                'Stone' => 'setting-options-stone',
                'Size' => 'setting-options-size',
            ];
            $map_code = [
                'Colorless' => 'natural',
                'Include' => 'bridal-set',
                'No Center Stone' => 'setting',
            ];
            $productOptions = [];
            $childrenIds = [];
            $children = [];
            while (($line = fgetcsv($fp)) !== false) {
                if ($line[1] == 'Type') {
                    if (!empty($line[11])) {
                        $childrenIds[$line[0]][$line[2]] = explode(',', $line[11]);
                    }
                } else {
                    foreach ($childrenIds[$line[0]] as $type => $ids) {
                        if (in_array($line[10], $ids)) {
                            $children[$line[0]][isset($map_code[$type])?$map_code[$type]:$this->_helper->getSlug($type)][$map_group[$line[1]]][] = $this->_helper->getSlug($line[2]);
                        }
                    }
                }
                $productOptions[$line[0]][] = $line;
                $skus[$line[0]] = $line[12];
            }

            fclose($fp);

            foreach ($productOptions as $productId => $options) {
                foreach ($options as $index => $option) {
                    if ($option[1] == 'Type' && !empty($option[11])) {
                        $type = $option[2];
                        $tmp = isset($map_code[$type])?$map_code[$type]:$this->_helper->getSlug($type);
                        $productOptions[$productId][$index][11] = isset($children[$productId][$tmp])?['children'=>$children[$productId][$tmp]]:"";
                    }
                    $productOptions[$productId][$index][1] = $map_group[$option[1]];
                    $productOptions[$productId][$index][2] = isset($map_code[$option[2]])?$map_code[$option[2]]:$this->_helper->getSlug($option[2]);
                }
            }

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
                $productModel->setVisibility(4);
                $productModel->setTaxClassId(2);
                $productModel->setTypeId('dm_ring_design');
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

                $this->_categoryLinkManagement->assignProductToCategories(
                    $productModel->getSku(),
                    explode(',', $line['category_ids'])
                );

                $map = [
                    'dm_stone_type' => 'center_diamond_type',
                    'dm_stone_shape' => 'center_diamond_shape',
                    'dm_band' => 'band',
                    'dm_metal' => 'metal',
                    'dm_design_collection' => 'design_collection',
                    'dm_setting_style' => 'setting_style',
                    'dm_designer' => 'designer_filter',
                    'dm_order_type' => 'order_type',
                ];

                $eavOptions = [];
                foreach (array_keys($map) as $attribute) {
                    $eavAttribute = $this->_eavConfig->getAttribute('catalog_product', $attribute);
                    $eavOptions[$attribute] = [];
                    foreach ($eavAttribute->getSource()->getAllOptions() as $eavOption) {
                        $eavOptions[$attribute][$this->_helper->getSlug($eavOption['label'])] = $eavOption;
                    }
                }

                $productAttributeOptions = [];
                foreach ($map as $attribute => $field) {
                    $values = explode(',', $line[$field]);
                    foreach ($values as $code) {
                        $code = trim($code);
                        if ($attribute == 'dm_stone_type' && $code == 'Diamond') {
                            $code = 'natural';
                        }
                        $code = $this->_helper->getSlug($code);
                        if (isset($eavOptions[$attribute][$code])) {
                            $productAttributeOptions[] = $eavOptions[$attribute][$code]['value'];
                        }
                    }

                    $productModel->setData($attribute, implode(',', $productAttributeOptions));
                    $productModel->getResource()->saveAttribute($productModel, $attribute);
                }

                // likes, dislikes
                $productModel->setData('dm_likes', $line['likes']);
                $productModel->getResource()->saveAttribute($productModel, 'dm_likes');
                $productModel->setData('dm_dislikes', $line['dislikes']);
                $productModel->getResource()->saveAttribute($productModel, 'dm_dislikes');

                $productOptionsModel = $this->_productOptionsFactory->create();
                $records = $productOptionsModel->getCollection()
                    ->addFieldToFilter('product_id', ['eq' => $productId]);

                foreach ($records as $record) {
                    $productOptionsModel->load($record->getId())->delete();
                }

                if (isset($productOptions[$productId])) {
                    $sideStoneCount = [
                        'shape' => 0,
                        'carat' => 0,
                        'color-clarity' => 1
                    ];
                    foreach ($productOptions[$productId] as $productOption) {
                        $group = $productOption[1];
                        $productOptionsModel = $this->_productOptionsFactory->create();
                        if ($productOption[1] == 'metal') {
                            $values = json_encode([
                                'weight' => [floatval($productOption[4])?:"", floatval($productOption[5])?:""],
                                'price' => [floatval($productOption[8])?:"", floatval($productOption[9])?:""]
                            ]);
                        } else if ($productOption[1] == 'side-stone-shape') {
                            $values = json_encode([
                                'qty' => [floatval($productOption[6])?:"", floatval($productOption[7])?:""],
                            ]);
                            $sideStoneCount['shape'] ++;
                            $group .= '-' . $sideStoneCount['shape'];
                        } else if ($productOption[1] == 'side-stone-carat') {
                            $values = "";
                            $sideStoneCount['carat'] ++;
                            $group .= '-' . $sideStoneCount['carat'];
                        } else if ($productOption[1] == 'side-stone-color-clarity') {
                            $values = "";
                            $group .= '-' . $sideStoneCount['color-clarity'];
                        } else {
                            $values = $productOption[11]?json_encode($productOption[11]):"";
                        }
                        $productOptionsModel->setData([
                            'product_id' => $productOption[0],
                            'group' => $group,
                            'code' => $productOption[2],
                            'is_default' => $productOption[3],
                            'values' => $values
                        ]);
                        $productOptionsModel->save();
                    }
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        exit;
    }
}