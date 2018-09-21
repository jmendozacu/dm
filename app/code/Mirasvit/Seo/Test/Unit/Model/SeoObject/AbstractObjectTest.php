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

/**
 * @covers \Mirasvit\Seo\Model\SeoObject\AbstractObject
 */
class AbstractObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Model\SeoObject\AbstractObject|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $abstractObjectModel;

    /**
     * @var \Mirasvit\Seo\Model\ConfigFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var \Mirasvit\Core\Helper\Debug|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $mstcoreDebugMock;

    /**
     * @var \Mirasvit\Seo\Helper\Parse|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $seoParseMock;

    /**
     * @var \Magento\Framework\Model\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * setup tests.
     */
    public function setUp()
    {
        $this->configFactoryMock = $this->getMock(
            '\Mirasvit\Seo\Model\ConfigFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->configMock = $this->getMock(
            '\Mirasvit\Seo\Model\Config',
            ['load',
            'save',
            'delete', ],
            [],
            '',
            false
        );
        $this->configFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->configMock));
        $this->mstcoreDebugMock = $this->getMock(
            '\Mirasvit\Core\Helper\Debug',
            [],
            [],
            '',
            false
        );
        $this->seoParseMock = $this->getMock(
            '\Mirasvit\Seo\Helper\Parse',
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
        $this->abstractObjectModel = $this->objectManager->getObject(
            '\Mirasvit\Seo\Model\SeoObject\AbstractObject',
            [
                'configFactory' => $this->configFactoryMock,
                'mstcoreDebug' => $this->mstcoreDebugMock,
                'seoParse' => $this->seoParseMock,
                'context' => $this->contextMock,
            ]
        );
    }

    /**
     * dummy test.
     */
    public function testDummy()
    {
        $this->assertEquals($this->abstractObjectModel, $this->abstractObjectModel);
    }
}
