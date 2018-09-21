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



namespace Mirasvit\Seo\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;
use Mirasvit\Seo\Model\Config as Config;

/**
 * @covers \Mirasvit\Seo\Model\Observer
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class ObserverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Model\Observer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $observerModel;

    /**
     * @var \Mirasvit\Seofilter\Model\ConfigFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configFactoryMock;

    /**
     * @var \Magento\Catalog\Model\LayerFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $layerFactoryMock;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $categoryFactoryMock;

    /**
     * @var \Magento\Catalog\Model\Category|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $categoryMock;

    /**
     * @var \Magento\Catalog\Model\ProductFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productFactoryMock;

    /**
     * @var \Magento\Catalog\Model\Product|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productMock;

    /**
     * @var \Mirasvit\Seo\Model\PagingFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $pagingFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\Paging|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $pagingMock;

    /**
     * @var \Mirasvit\Seo\Model\System\Template\WorkerFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $systemTemplateWorkerFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\ProducturlFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectProducturlFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\Producturl|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectProducturlMock;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory|
     * \PHPUnit_Framework_MockObject_MockObject
     */
    protected $entityAttributeGroupCollectionFactoryMock;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $entityAttributeGroupCollectionMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productCollectionFactoryMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productCollectionMock;

    /**
     * @var \Mirasvit\Seo\Model\Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var \Magento\Catalog\Model\Layer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $layerMock;

    /**
     * @var \Mirasvit\Seo\Model\Opengraph|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $opengraphMock;

    /**
     * @var \Magento\Catalog\Model\Product\Url|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productUrlMock;

    /**
     * @var \Mirasvit\Seo\Model\System\Template\Worker|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $systemTemplateWorkerMock;

    /**
     * @var \Mirasvit\Core\Helper\Version|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $mstcoreVersionMock;

    /**
     * @var \Magento\Catalog\Helper\Product\Flat|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $catalogProductFlatMock;

    /**
     * @var \Mirasvit\Core\Helper\File\Storage\Database|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $coreFileStorageDatabaseMock;

    /**
     * @var \Mirasvit\Seo\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $seoDataMock;

    /**
     * @var \Magento\Framework\App\Request\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    /**
     * @var \Magento\Backend\Block\Widget\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $widgetContextMock;

    /**
     * @var \Magento\Framework\UrlInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlManagerMock;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeManagerMock;

    /**
     * @var \Magento\Framework\Module\Manager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $moduleManagerMock;

    /**
     * @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * @var \Magento\Framework\Filesystem|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $filesystemMock;

    /**
     * @var \Magento\Framework\View\DesignInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $designMock;

    /**
     * @var \Magento\Framework\App\Resource|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $dbResourceMock;

    /**
     * @var \Magento\Framework\App\ResponseInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $responseMock;

    /**
     * @var \Magento\Framework\View\Element\Template\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * setup tests.
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function setUp()
    {
        $this->configFactoryMock = $this->getMock('\Mirasvit\Seofilter\Model\ConfigFactory', ['create'], [], '', false);
        $this->configMock = $this->getMock(
            '\Mirasvit\Seofilter\Model\Config',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->configFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->configMock));
        $this->layerFactoryMock = $this->getMock('\Magento\Catalog\Model\LayerFactory', ['create'], [], '', false);
        $this->layerMock = $this->getMock('\Magento\Catalog\Model\Layer', ['load', 'save', 'delete'], [], '', false);
        $this->layerFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->layerMock));
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
        $this->productFactoryMock = $this->getMock('\Magento\Catalog\Model\ProductFactory', ['create'], [], '', false);
        $this->productMock = $this->getMock(
            '\Magento\Catalog\Model\Product',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->productFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->productMock));
        $this->pagingFactoryMock = $this->getMock('\Mirasvit\Seo\Model\PagingFactory', ['create'], [], '', false);
        $this->pagingMock = $this->getMock('\Mirasvit\Seo\Model\Paging', ['load', 'save', 'delete'], [], '', false);
        $this->pagingFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->pagingMock));
        $this->systemTemplateWorkerFactoryMock = $this->getMock(
            '\Mirasvit\Seo\Model\System\Template\WorkerFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->systemTemplateWorkerMock = $this->getMock(
            '\Mirasvit\Seo\Model\System\Template\Worker',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->systemTemplateWorkerFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->systemTemplateWorkerMock));
        $this->objectProducturlFactoryMock = $this->getMock(
            '\Mirasvit\Seo\Model\SeoObject\ProducturlFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->objectProducturlMock = $this->getMock(
            '\Mirasvit\Seo\Model\SeoObject\Producturl',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->objectProducturlFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->objectProducturlMock));
        $this->entityAttributeGroupCollectionFactoryMock = $this->getMock(
            '\Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->entityAttributeGroupCollectionMock = $this->getMock(
            '\Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\Collection',
            ['load', 'save', 'delete', 'addFieldToFilter', 'setOrder', 'getFirstItem', 'getLastItem'],
            [],
            '',
            false
        );
        $this->entityAttributeGroupCollectionFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->entityAttributeGroupCollectionMock));
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
        $this->configMock = $this->getMock('\Mirasvit\Seo\Model\Config', [], [], '', false);
        $this->layerMock = $this->getMock('\Magento\Catalog\Model\Layer', [], [], '', false);
        $this->opengraphMock = $this->getMock('\Mirasvit\Seo\Model\Opengraph', [], [], '', false);
        $this->productUrlMock = $this->getMock('\Magento\Catalog\Model\Product\Url', [], [], '', false);
        $this->systemTemplateWorkerMock = $this->getMock(
            '\Mirasvit\Seo\Model\System\Template\Worker',
            [],
            [],
            '',
            false
        );
        $this->mstcoreVersionMock = $this->getMock('\Mirasvit\Core\Helper\Version', [], [], '', false);
        $this->catalogProductFlatMock = $this->getMock('\Magento\Catalog\Helper\Product\Flat', [], [], '', false);
        $this->coreFileStorageDatabaseMock = $this->getMock(
            '\Mirasvit\Core\Helper\File\Storage\Database',
            [],
            [],
            '',
            false
        );
        $this->seoDataMock = $this->getMock('\Mirasvit\Seo\Helper\Data', [], [], '', false);
        $this->requestMock = $this->getMock('\Magento\Framework\App\Request\Http', [], [], '', false);
        $this->widgetContextMock = $this->getMock('\Magento\Backend\Block\Widget\Context', [], [], '', false);
        $this->urlManagerMock = $this->getMockForAbstractClass(
            '\Magento\Framework\UrlInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );
        $this->storeManagerMock = $this->getMockForAbstractClass(
            '\Magento\Store\Model\StoreManagerInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );
        $this->moduleManagerMock = $this->getMock('\Magento\Framework\Module\Manager', [], [], '', false);
        $this->registryMock = $this->getMock('\Magento\Framework\Registry', [], [], '', false);
        $this->filesystemMock = $this->getMock('\Magento\Framework\Filesystem', [], [], '', false);
        $this->designMock = $this->getMockForAbstractClass(
            '\Magento\Framework\View\DesignInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );
        $this->dbResourceMock = $this->getMock('\Magento\Framework\App\Resource', [], [], '', false);
        $this->responseMock = $this->getMockForAbstractClass(
            '\Magento\Framework\App\ResponseInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );
        $this->objectManager = new ObjectManager($this);
        $this->contextMock = $this->objectManager->getObject(
            '\Magento\Framework\View\Element\Template\Context',
            [
            ]
        );
        $this->observerModel = $this->objectManager->getObject(
            '\Mirasvit\Seo\Model\Observer',
            [
                'configFactory' => $this->configFactoryMock,
                'layerFactory' => $this->layerFactoryMock,
                'categoryFactory' => $this->categoryFactoryMock,
                'productFactory' => $this->productFactoryMock,
                'pagingFactory' => $this->pagingFactoryMock,
                'systemTemplateWorkerFactory' => $this->systemTemplateWorkerFactoryMock,
                'objectProducturlFactory' => $this->objectProducturlFactoryMock,
                'entityAttributeGroupCollectionFactory' => $this->entityAttributeGroupCollectionFactoryMock,
                'productCollectionFactory' => $this->productCollectionFactoryMock,
                'config' => $this->configMock,
                'layer' => $this->layerMock,
                'opengraph' => $this->opengraphMock,
                'productUrl' => $this->productUrlMock,
                'systemTemplateWorker' => $this->systemTemplateWorkerMock,
                'mstcoreVersion' => $this->mstcoreVersionMock,
                'catalogProductFlat' => $this->catalogProductFlatMock,
                'coreFileStorageDatabase' => $this->coreFileStorageDatabaseMock,
                'seoData' => $this->seoDataMock,
                'request' => $this->requestMock,
                'widgetContext' => $this->widgetContextMock,
                'urlManager' => $this->urlManagerMock,
                'storeManager' => $this->storeManagerMock,
                'moduleManager' => $this->moduleManagerMock,
                'registry' => $this->registryMock,
                'filesystem' => $this->filesystemMock,
                'design' => $this->designMock,
                'dbResource' => $this->dbResourceMock,
                'response' => $this->responseMock,
                'context' => $this->contextMock,
            ]
        );
    }

    /**
     * dummy test.
     */
    public function testDummy()
    {
        $this->assertEquals($this->observerModel, $this->observerModel);
    }
}
