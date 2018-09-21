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
use Mirasvit\Seo\Model\Config as Config;

/**
 * @covers \Mirasvit\Seo\Helper\Mail
 */
class MailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Helper\Mail|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $mailHelper;

    /**
     * @var \Magento\Email\Model\TemplateFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $emailTemplateFactoryMock;

    /**
     * @var \Magento\Email\Model\Template|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $emailTemplateMock;

    /**
     * @var \Mirasvit\Seo\Model\Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var \Magento\Framework\App\Helper\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * setup tests.
     */
    public function setUp()
    {
        $this->emailTemplateFactoryMock = $this->getMock(
            '\Magento\Email\Model\TemplateFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->emailTemplateMock = $this->getMock(
            '\Magento\Email\Model\Template',
            ['load',
            'save',
            'delete', ],
            [],
            '',
            false
        );
        $this->emailTemplateFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->emailTemplateMock));
        $this->configMock = $this->getMock(
            '\Mirasvit\Seo\Model\Config',
            [],
            [],
            '',
            false
        );
        $this->objectManager = new ObjectManager($this);
        $this->contextMock = $this->objectManager->getObject(
            '\Magento\Framework\App\Helper\Context',
            [
            ]
        );
        $this->mailHelper = $this->objectManager->getObject(
            '\Mirasvit\Seo\Helper\Mail',
            [
                'emailTemplateFactory' => $this->emailTemplateFactoryMock,
                'config' => $this->configMock,
                'context' => $this->contextMock,
            ]
        );
    }

    /**
     * dummy test.
     */
    public function testDummy()
    {
        $this->assertEquals($this->mailHelper, $this->mailHelper);
    }
}
