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



namespace Mirasvit\SeoSitemap\Test\Unit\Helper;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;
use Mirasvit\SeoSitemap\Model\Config as Config;

/**
 * @covers \Mirasvit\SeoSitemap\Helper\Data
 */
class DataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\SeoSitemap\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $dataHelper;

    /**
     * @var \Mirasvit\SeoSitemap\Model\Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var \Mirasvit\Core\Api\UrlRewriteHelperInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $mstcoreUrlrewriteMock;

    /**
     * @var \Magento\Framework\App\Helper\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * setup tests.
     */
    public function setUp()
    {
        $this->configMock = $this->getMock(
            '\Mirasvit\SeoSitemap\Model\Config',
            [],
            [],
            '',
            false
        );
        $this->mstcoreUrlrewriteMock = $this->getMock(
            '\Mirasvit\Core\Api\UrlRewriteHelperInterface',
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
        $this->dataHelper = $this->objectManager->getObject(
            '\Mirasvit\SeoSitemap\Helper\Data',
            [
                'config' => $this->configMock,
                'mstcoreUrlrewrite' => $this->mstcoreUrlrewriteMock,
                'context' => $this->contextMock,
            ]
        );
    }

    /**
     * dummy test.
     */
    public function testDummy()
    {
        $this->assertEquals($this->dataHelper, $this->dataHelper);
    }
}
