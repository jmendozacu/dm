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



namespace Mirasvit\SeoAutolink\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;

/**
 * @covers \Mirasvit\SeoAutolink\Observer\AddCustomAttributeOutputObserver
 */
class AddCustomAttributeOutputObserverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\SeoAutolink\Model\Observer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $observerModel;

    /**
     * @var \Mirasvit\SeoAutolink\Model\Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var \Mirasvit\SeoAutolink\Helper\Replace|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $seoAutolinkDataMock;

    /**
     * @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * @var \Magento\Framework\Model\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * setup tests.
     */
    public function setUp()
    {
        $this->configMock = $this->getMock(
            '\Mirasvit\SeoAutolink\Model\Config',
            [],
            [],
            '',
            false
        );
        $this->seoAutolinkDataMock = $this->getMock(
            '\Mirasvit\SeoAutolink\Helper\Replace',
            [],
            [],
            '',
            false
        );
        $this->registryMock = $this->getMock(
            '\Magento\Framework\Registry',
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
        $this->observerModel = $this->objectManager->getObject(
            '\Mirasvit\SeoAutolink\Observer\AddCustomAttributeOutputObserver',
            [
                'config' => $this->configMock,
                'seoAutolinkData' => $this->seoAutolinkDataMock,
                'registry' => $this->registryMock,
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
