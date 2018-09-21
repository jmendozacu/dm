<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-seo
 * @version   2.0.85
 * @copyright Copyright (C) 2018 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Seo\Test\Unit\Helper;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;

/**
 * @covers \Mirasvit\Seo\Helper\Data
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class DataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $dataHelper;

    /**
     * @var \Mirasvit\Seo\Model\ConfigFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\StoreFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectStoreFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\Store|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectStoreMock;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\PagerFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectPagerFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\Pager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectPagerMock;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\Wrapper\FilterFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectWrapperFilterFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\Wrapper\Filter|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectWrapperFilterMock;

    /**
     * @var \Mirasvit\Seo\Model\TemplateFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $templateFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\Template|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $templateMock;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $categoryFactoryMock;

    /**
     * @var \Magento\Catalog\Model\Category|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $categoryMock;

    /**
     * @var \Magento\Catalog\Model\Product\Type\GroupedFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productTypeGroupedFactoryMock;

    /**
     * @var \Magento\Catalog\Model\Product\Type\Grouped|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productTypeGroupedMock;

    /**
     * @var \Magento\Bundle\Model\Product\TypeFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productTypeFactoryMock;

    /**
     * @var \Magento\Bundle\Model\Product\Type|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productTypeMock;

    /**
     * @var \Magento\Directory\Model\CurrencyFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $currencyFactoryMock;

    /**
     * @var \Magento\Directory\Model\Currency|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $currencyMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Type\ConfigurableFactory|
     * \PHPUnit_Framework_MockObject_MockObject
     */
    protected $productTypeConfigurableFactoryMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Type\Configurable|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productTypeConfigurableMock;

    /**
     * @var \Mirasvit\Seo\Model\ResourceModel\Rewrite\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $rewriteCollectionFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\ResourceModel\Rewrite\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $rewriteCollectionMock;

    /**
     * @var \Mirasvit\Seo\Model\ResourceModel\Template\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $templateCollectionFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\ResourceModel\Template\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $templateCollectionMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $categoryCollectionFactoryMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $categoryCollectionMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productCollectionFactoryMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productCollectionMock;

    /**
     * @var \Magento\Catalog\Model\Layer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $layerMock;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\Product|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectProductMock;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\Filter|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectFilterMock;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\Category|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectCategoryMock;

    /**
     * @var \Mirasvit\Core\Model\Url|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlMock;

    /**
     * @var \Mirasvit\Core\Helper\Debug|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $mstcoreDebugMock;

    /**
     * @var \Mirasvit\Seo\Helper\Parse|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $seoParseMock;

    /**
     * @var \Mirasvit\Core\Api\TextHelperInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $coreStringMock;

    /**
     * @var \Magento\Catalog\Helper\Category\Flat|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $catalogCategoryFlatMock;

    /**
     * @var \Mirasvit\Seo\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $seoDataMock;

    /**
     * @var \Magento\Tax\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $taxDataMock;

    /**
     * @var \Magento\Framework\App\Helper\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeManagerMock;

    /**
     * @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * @var \Magento\Framework\App\RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    /**
     * setup tests.
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function setUp()
    {
        $this->configFactoryMock = $this->getMock('\Mirasvit\Seo\Model\ConfigFactory', ['create'], [], '', false);
        $this->configMock = $this->getMock('\Mirasvit\Seo\Model\Config', ['load', 'save', 'delete'], [], '', false);
        $this->configFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->configMock));
        $this->objectStoreFactoryMock = $this->getMock(
            '\Mirasvit\Seo\Model\SeoObject\StoreFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->objectStoreMock = $this->getMock(
            '\Mirasvit\Seo\Model\SeoObject\Store',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->objectStoreFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->objectStoreMock));
        $this->objectPagerFactoryMock = $this->getMock(
            '\Mirasvit\Seo\Model\SeoObject\PagerFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->objectPagerMock = $this->getMock(
            '\Mirasvit\Seo\Model\SeoObject\Pager',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->objectPagerFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->objectPagerMock));
        $this->objectWrapperFilterFactoryMock = $this->getMock(
            '\Mirasvit\Seo\Model\SeoObject\Wrapper\FilterFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->objectWrapperFilterMock = $this->getMock(
            '\Mirasvit\Seo\Model\SeoObject\Wrapper\Filter',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->objectWrapperFilterFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->objectWrapperFilterMock));
        $this->templateFactoryMock = $this->getMock('\Mirasvit\Seo\Model\TemplateFactory', ['create'], [], '', false);
        $this->templateMock = $this->getMock(
            '\Mirasvit\Seo\Model\Template',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->templateFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->templateMock));
        $this->categoryFactoryMock = $this->getMock(
            '\Magento\Catalog\Model\CategoryFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->categoryMock = $this->getMock(
            '\Magento\Catalog\Model\Category',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->categoryFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->categoryMock));
        $this->productTypeGroupedFactoryMock = $this->getMock(
            '\Magento\Catalog\Model\Product\Type\GroupedFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->productTypeGroupedMock = $this->getMock(
            '\Magento\Catalog\Model\Product\Type\Grouped',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->productTypeGroupedFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->productTypeGroupedMock));
        $this->productTypeFactoryMock = $this->getMock(
            '\Magento\Bundle\Model\Product\TypeFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->productTypeMock = $this->getMock(
            '\Magento\Bundle\Model\Product\Type',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->productTypeFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->productTypeMock));
        $this->currencyFactoryMock = $this->getMock(
            '\Magento\Directory\Model\CurrencyFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->currencyMock = $this->getMock(
            '\Magento\Directory\Model\Currency',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->currencyFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->currencyMock));
        $this->productTypeConfigurableFactoryMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Product\Type\ConfigurableFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->productTypeConfigurableMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Product\Type\Configurable',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->productTypeConfigurableFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->productTypeConfigurableMock));
        $this->rewriteCollectionFactoryMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Rewrite\CollectionFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->rewriteCollectionMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Rewrite\Collection',
            ['load', 'save', 'delete', 'addFieldToFilter', 'setOrder', 'getFirstItem', 'getLastItem'],
            [],
            '',
            false
        );
        $this->rewriteCollectionFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->rewriteCollectionMock));
        $this->templateCollectionFactoryMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Template\CollectionFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->templateCollectionMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Template\Collection',
            ['load', 'save', 'delete', 'addFieldToFilter', 'setOrder', 'getFirstItem', 'getLastItem'],
            [],
            '',
            false
        );
        $this->templateCollectionFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->templateCollectionMock));
        $this->categoryCollectionFactoryMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->categoryCollectionMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Category\Collection',
            ['load', 'save', 'delete', 'addFieldToFilter', 'setOrder', 'getFirstItem', 'getLastItem'],
            [],
            '',
            false
        );
        $this->categoryCollectionFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->categoryCollectionMock));
        $this->productCollectionFactoryMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->productCollectionMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Product\Collection',
            ['load', 'save', 'delete', 'addFieldToFilter', 'setOrder', 'getFirstItem', 'getLastItem'],
            [],
            '',
            false
        );
        $this->productCollectionFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->productCollectionMock));
        $this->layerMock = $this->getMock('\Magento\Catalog\Model\Layer', [], [], '', false);
        $this->objectProductMock = $this->getMock('\Mirasvit\Seo\Model\SeoObject\Product', [], [], '', false);
        $this->objectFilterMock = $this->getMock('\Mirasvit\Seo\Model\SeoObject\Filter', [], [], '', false);
        $this->objectCategoryMock = $this->getMock('\Mirasvit\Seo\Model\SeoObject\Category', [], [], '', false);
        $this->urlMock = $this->getMock('\Mirasvit\Core\Model\Url', [], [], '', false);
        $this->mstcoreDebugMock = $this->getMock('\Mirasvit\Core\Helper\Debug', [], [], '', false);
        $this->seoParseMock = $this->getMock('\Mirasvit\Seo\Helper\Parse', [], [], '', false);
        $this->coreStringMock = $this->getMock('\Mirasvit\Core\Api\TextHelperInterface', [], [], '', false);
        $this->catalogCategoryFlatMock = $this->getMock('\Magento\Catalog\Helper\Category\Flat', [], [], '', false);
        $this->seoDataMock = $this->getMock('\Mirasvit\Seo\Helper\Data', [], [], '', false);
        $this->taxDataMock = $this->getMock('\Magento\Tax\Helper\Data', [], [], '', false);
        $this->storeManagerMock = $this->getMockForAbstractClass(
            '\Magento\Store\Model\StoreManagerInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );
        $this->registryMock = $this->getMock('\Magento\Framework\Registry', [], [], '', false);
        $this->requestMock = $this->getMockForAbstractClass(
            '\Magento\Framework\App\RequestInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );
        $this->objectManager = new ObjectManager($this);
        $this->contextMock = $this->objectManager->getObject(
            '\Magento\Framework\App\Helper\Context',
            [
            ]
        );
        $this->dataHelper = $this->objectManager->getObject(
            '\Mirasvit\Seo\Helper\Data',
            [
                'configFactory' => $this->configFactoryMock,
                'objectStoreFactory' => $this->objectStoreFactoryMock,
                'objectPagerFactory' => $this->objectPagerFactoryMock,
                'objectWrapperFilterFactory' => $this->objectWrapperFilterFactoryMock,
                'templateFactory' => $this->templateFactoryMock,
                'categoryFactory' => $this->categoryFactoryMock,
                'productTypeGroupedFactory' => $this->productTypeGroupedFactoryMock,
                'productTypeFactory' => $this->productTypeFactoryMock,
                'currencyFactory' => $this->currencyFactoryMock,
                'productTypeConfigurableFactory' => $this->productTypeConfigurableFactoryMock,
                'rewriteCollectionFactory' => $this->rewriteCollectionFactoryMock,
                'templateCollectionFactory' => $this->templateCollectionFactoryMock,
                'categoryCollectionFactory' => $this->categoryCollectionFactoryMock,
                'productCollectionFactory' => $this->productCollectionFactoryMock,
                'layer' => $this->layerMock,
                'objectProduct' => $this->objectProductMock,
                'objectFilter' => $this->objectFilterMock,
                'objectCategory' => $this->objectCategoryMock,
                'url' => $this->urlMock,
                'mstcoreDebug' => $this->mstcoreDebugMock,
                'seoParse' => $this->seoParseMock,
                'coreString' => $this->coreStringMock,
                'catalogCategoryFlat' => $this->catalogCategoryFlatMock,
                'seoData' => $this->seoDataMock,
                'taxData' => $this->taxDataMock,
                'context' => $this->contextMock,
                'storeManager' => $this->storeManagerMock,
                'registry' => $this->registryMock,
                'request' => $this->requestMock,
            ]
        );
    }

    /**
     * dummy test.
     */
    public function testDummy()
    {
        $this->assertEquals($this->dataHelper, $this->dataHelper);
    }
}
