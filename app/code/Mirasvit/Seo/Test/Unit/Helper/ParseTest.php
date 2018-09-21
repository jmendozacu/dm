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
 * @covers \Mirasvit\Seo\Helper\Parse
 */
class ParseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Helper\Parse|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $parseHelper;

    /**
     * @var \Magento\Framework\App\Helper\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $pricingHelperMock;

    /**
     * @var \Magento\Eav\Model\Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eavConfigMock;

    /**
     * setup tests.
     */
    public function setUp()
    {
        $this->pricingHelperMock = $this->getMock(
            '\Magento\Framework\Pricing\Helper\Data',
            [],
            [],
            '',
            false
        );
        $this->eavConfigMock = $this->getMock(
            '\Magento\Eav\Model\Config',
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
        $this->parseHelper = $this->objectManager->getObject(
            '\Mirasvit\Seo\Helper\Parse',
            [
                'context' => $this->contextMock,
                'pricingHelper' => $this->pricingHelperMock,
                'eavConfig' => $this->eavConfigMock,
            ]
        );
    }

    /**
     * dummy test.
     */
    public function testDummy()
    {
        $this->assertEquals($this->parseHelper, $this->parseHelper);
    }
}
