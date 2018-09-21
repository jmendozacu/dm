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



namespace Mirasvit\Seo\Test\Unit\Model\SeoObject;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;
use Mirasvit\Seo\Model\Config as Config;

/**
 * @covers \Mirasvit\Seo\Model\SeoObject\Category
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class CategoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\Category|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $categoryModel;

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
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $categoryCollectionFactoryMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $categoryCollectionMock;

    /**
     * @var \Mirasvit\Seo\Model\Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var \Mirasvit\Core\Helper\Debug|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $mstcoreDebugMock;

    /**
     * @var \Mirasvit\Core\Helper\String|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $coreStringMock;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeManagerMock;

    /**
     * @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * @var \Magento\Framework\Model\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var \Magento\Framework\Model\ResourceModel\AbstractResource|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resourceMock;

    /**
     * @var \Magento\Framework\Data\Collection\AbstractDb|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resourceCollectionMock;

    /**
     * setup tests.
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function setUp()
    {
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
        $this->configMock = $this->getMock('\Mirasvit\Seo\Model\Config', [], [], '', false);
        $this->mstcoreDebugMock = $this->getMock('\Mirasvit\Core\Helper\Debug', [], [], '', false);
        $this->coreStringMock = $this->getMock('\Mirasvit\Core\Helper\String', [], [], '', false);
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
        $this->resourceMock = $this->getMock('\Mirasvit\Seo\Model\ResourceModel\Object\Category', [], [], '', false);
        $this->resourceCollectionMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Object\Category\Collection',
            [],
            [],
            '',
            false
        );
        $this->objectManager = new ObjectManager($this);
        $this->contextMock = $this->objectManager->getObject(
            '\Magento\Framework\Model\Context',
            [
            ]
        );
        $this->categoryModel = $this->objectManager->getObject(
            '\Mirasvit\Seo\Model\SeoObject\Category',
            [
                'objectStoreFactory' => $this->objectStoreFactoryMock,
                'objectPagerFactory' => $this->objectPagerFactoryMock,
                'objectWrapperFilterFactory' => $this->objectWrapperFilterFactoryMock,
                'categoryCollectionFactory' => $this->categoryCollectionFactoryMock,
                'config' => $this->configMock,
                'mstcoreDebug' => $this->mstcoreDebugMock,
                'coreString' => $this->coreStringMock,
                'storeManager' => $this->storeManagerMock,
                'registry' => $this->registryMock,
                'context' => $this->contextMock,
                'resource' => $this->resourceMock,
                'resourceCollection' => $this->resourceCollectionMock,
            ]
        );
    }

    /**
     * dummy test.
     */
    public function testDummy()
    {
        $this->assertEquals($this->categoryModel, $this->categoryModel);
    }
}
