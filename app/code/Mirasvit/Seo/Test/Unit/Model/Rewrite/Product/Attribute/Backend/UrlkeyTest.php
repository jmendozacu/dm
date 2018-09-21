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



namespace Mirasvit\Seo\Test\Unit\Model\Rewrite\Product\Attribute\Backend;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;
use Mirasvit\Seo\Model\Config as Config;

/**
 * @covers \Mirasvit\Seo\Model\Rewrite\Product\Attribute\Backend\Urlkey
 */
class UrlkeyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Model\Rewrite\Product\Attribute\Backend\Urlkey|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlkeyModel;

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
     */
    public function setUp()
    {
        $this->markTestSkipped('ERROR message here');

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
        $this->registryMock = $this->getMock('\Magento\Framework\Registry', [], [], '', false);
        $this->resourceMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Rewrite\Product\Attribute\Backend\Urlkey',
            [],
            [],
            '',
            false
        );
        $this->resourceCollectionMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Rewrite\Product\Attribute\Backend\Urlkey\Collection',
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
        $this->urlkeyModel = $this->objectManager->getObject(
            '\Mirasvit\Seo\Model\Rewrite\Product\Attribute\Backend\Urlkey',
            [
                'objectProducturlFactory' => $this->objectProducturlFactoryMock,
                'config' => $this->configMock,
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
        $this->assertEquals($this->urlkeyModel, $this->urlkeyModel);
    }
}
