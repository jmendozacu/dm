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



namespace Mirasvit\Seo\Test\Unit\Model\Rewrite;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;
use Mirasvit\Seo\Model\Config as Config;

/**
 * @covers \Mirasvit\Seo\Model\Rewrite\Url
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Model\Rewrite\Url|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlModel;

    /**
     * @var \Magento\Store\Model\StoreFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeFactoryMock;

    /**
     * @var \Magento\Store\Model\Store|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeMock;

    /**
     * @var \Magento\Catalog\Model\ProductFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productFactoryMock;

    /**
     * @var \Magento\Catalog\Model\Product|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productMock;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\ProducturlFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectProducturlFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\Producturl|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectProducturlMock;

    /**
     * @var \Mirasvit\Seo\Model\Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var \Magento\Catalog\Helper\Category|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $catalogCategoryMock;

    /**
     * @var \Magento\Framework\Model\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

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
     */
    public function setUp()
    {
        $this->markTestSkipped('ERROR message here');

        $this->storeFactoryMock = $this->getMock('\Magento\Store\Model\StoreFactory', ['create'], [], '', false);
        $this->storeMock = $this->getMock('\Magento\Store\Model\Store', ['load', 'save', 'delete'], [], '', false);
        $this->storeFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->storeMock));
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
        $this->configMock = $this->getMock('\Mirasvit\Seo\Model\Config', [], [], '', false);
        $this->catalogCategoryMock = $this->getMock('\Magento\Catalog\Helper\Category', [], [], '', false);
        $this->registryMock = $this->getMock('\Magento\Framework\Registry', [], [], '', false);
        $this->resourceMock = $this->getMock('\Mirasvit\Seo\Model\ResourceModel\Rewrite\Url', [], [], '', false);
        $this->resourceCollectionMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Rewrite\Url\Collection',
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
        $this->urlModel = $this->objectManager->getObject(
            '\Mirasvit\Seo\Model\Rewrite\Url',
            [
                'storeFactory' => $this->storeFactoryMock,
                'productFactory' => $this->productFactoryMock,
                'objectProducturlFactory' => $this->objectProducturlFactoryMock,
                'config' => $this->configMock,
                'catalogCategory' => $this->catalogCategoryMock,
                'context' => $this->contextMock,
                'registry' => $this->registryMock,
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
        $this->assertEquals($this->urlModel, $this->urlModel);
    }
}
