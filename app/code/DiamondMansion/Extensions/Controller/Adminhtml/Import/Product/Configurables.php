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

class Configurables extends \DiamondMansion\Extensions\Controller\Adminhtml\Base
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

            $fp = fopen(BP . DIRECTORY_SEPARATOR . 'pub' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'configurable.csv', 'r');

            $list = false;
            while (($line = fgetcsv($fp)) !== false) {
                if ($list === false) {
                    $list = [];
                    continue;
                }
                if (empty($line[5])) {
                    continue;
                }
                $list[] = $line;
            }

            fclose($fp);

            $attributes = [$eavAttribute = $this->_eavConfig->getAttribute('catalog_product', 'dmc_metal')->getId()];

            foreach ($list as $line) {
                $productId = $line[1];
                $productModel = $this->_productFactory->create();
                $productModel->load($productId);

                $attributeModel = $this->_helper->getObjectManager()->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute');
                $attributeModel->setData([
                    'attribute_id' => $attributes[0],
                    'product_id' => $productId,
                    'position' => 0,
                ])->save();

                $productModel->setAffectConfigurableProductAttributes(4);
                $this->_helper->getObjectManager()->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable')->setUsedProductAttributeIds($attributes, $productModel);
                $configurableAttributesData = $productModel->getTypeInstance()->getConfigurableAttributesAsArray($productModel);
                $productModel->setConfigurableAttributesData($configurableAttributesData);

                $productModel->setAssociatedProductIds(explode(',', $line[5]));
                $productModel->setCanSaveConfigurableAttributes(true);
                $productModel->save();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        exit;
    }
}