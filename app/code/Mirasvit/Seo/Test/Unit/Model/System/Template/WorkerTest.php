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



namespace Mirasvit\Seo\Test\Unit\Model\System\Template;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;
use Mirasvit\Seo\Model\Config as Config;

/**
 * @covers \Mirasvit\Seo\Model\System\Template\Worker
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class WorkerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Model\System\Template\Worker|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $workerModel;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\ProducturlFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectProducturlFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\Producturl|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectProducturlMock;

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
     * @var \Mirasvit\Core\Helper\Version|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $mstcoreVersionMock;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeManagerMock;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeConfigMock;

    /**
     * @var \Magento\Framework\App\Resource|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $dbResourceMock;

    /**
     * @var \Magento\Framework\Model\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * setup tests.
     */
    public function setUp()
    {
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
        $this->mstcoreVersionMock = $this->getMock('\Mirasvit\Core\Helper\Version', [], [], '', false);
        $this->storeManagerMock = $this->getMockForAbstractClass(
            '\Magento\Store\Model\StoreManagerInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );
        $this->scopeConfigMock = $this->getMockForAbstractClass(
            '\Magento\Framework\App\Config\ScopeConfigInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );
        $this->dbResourceMock = $this->getMock('\Magento\Framework\App\ResourceConnection', [], [], '', false);
        $this->objectManager = new ObjectManager($this);
        $this->contextMock = $this->objectManager->getObject(
            '\Magento\Framework\Model\Context',
            [
            ]
        );
        $this->workerModel = $this->objectManager->getObject(
            '\Mirasvit\Seo\Model\System\Template\Worker',
            [
                'objectProducturlFactory' => $this->objectProducturlFactoryMock,
                'productCollectionFactory' => $this->productCollectionFactoryMock,
                'config' => $this->configMock,
                'mstcoreVersion' => $this->mstcoreVersionMock,
                'storeManager' => $this->storeManagerMock,
                'scopeConfig' => $this->scopeConfigMock,
                'dbResource' => $this->dbResourceMock,
                'context' => $this->contextMock,
            ]
        );
    }

    /**
     * dummy test.
     */
    public function testDummy()
    {
        $this->assertEquals($this->workerModel, $this->workerModel);
    }
}
