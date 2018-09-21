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



namespace Mirasvit\SeoSitemap\Test\Unit\Model\System\Config\Backend;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;

/**
 * @covers \Mirasvit\SeoSitemap\Model\System\Config\Backend\Priority
 */
class PriorityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\SeoSitemap\Model\System\Config\Backend\Priority|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $priorityModel;

    /**
     * setup tests.
     */
    public function setUp()
    {
        $this->markTestIncomplete();

        $this->objectManager = new ObjectManager($this);
        $this->priorityModel = $this->objectManager->getObject(
            '\Mirasvit\SeoSitemap\Model\System\Config\Backend\Priority',
            [
            ]
        );
    }

    /**
     * dummy test.
     */
    public function testDummy()
    {
        $this->assertEquals($this->priorityModel, $this->priorityModel);
    }
}
