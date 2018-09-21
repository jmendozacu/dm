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
 * @covers \Mirasvit\Seo\Model\Opengraph
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OpengraphTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Model\Opengraph|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $opengraphModel;

    /**
     * @var \Magento\Catalog\Model\ProductFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productFactoryMock;

    /**
     * @var \Magento\Catalog\Model\Product|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productMock;

    /**
     * @var \Mirasvit\Seo\Model\Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var \Magento\Catalog\Helper\Image|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $catalogImageMock;

    /**
     * @var \Mirasvit\Seo\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $seoDataMock;

    /**
     * @var \Magento\Framework\UrlInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlManagerMock;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeManagerMock;

    /**
     * @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * @var \Magento\Backend\Model\Auth|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $authMock;

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
     */
    public function setUp()
    {
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
        $this->configMock = $this->getMock('\Mirasvit\Seo\Model\Config', [], [], '', false);
        $this->catalogImageMock = $this->getMock('\Magento\Catalog\Helper\Image', [], [], '', false);
        $this->seoDataMock = $this->getMock('\Mirasvit\Seo\Helper\Data', [], [], '', false);
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
        $this->registryMock = $this->getMock('\Magento\Framework\Registry', [], [], '', false);
        $this->authMock = $this->getMock('\Magento\Backend\Model\Auth', [], [], '', false);
        $this->resourceMock = $this->getMock('\Mirasvit\Seo\Model\ResourceModel\Opengraph', [], [], '', false);
        $this->resourceCollectionMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Opengraph\Collection',
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
        $this->opengraphModel = $this->objectManager->getObject(
            '\Mirasvit\Seo\Model\Opengraph',
            [
                'productFactory' => $this->productFactoryMock,
                'config' => $this->configMock,
                'catalogImage' => $this->catalogImageMock,
                'seoData' => $this->seoDataMock,
                'urlManager' => $this->urlManagerMock,
                'storeManager' => $this->storeManagerMock,
                'registry' => $this->registryMock,
                'auth' => $this->authMock,
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
        $this->assertEquals($this->opengraphModel, $this->opengraphModel);
    }
}
