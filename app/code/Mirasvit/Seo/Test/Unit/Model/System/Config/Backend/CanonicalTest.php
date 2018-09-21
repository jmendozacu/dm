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



namespace Mirasvit\Seo\Test\Unit\Model\System\Config\Backend;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;
use Mirasvit\Seo\Model\Config as Config;

/**
 * @covers \Mirasvit\Seo\Model\Config\Backend\Canonical
 */
class CanonicalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Model\Config\Backend\Canonical|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $canonicalModel;

    /**
     * @var \Mirasvit\Core\Model\Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

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
        $this->resourceModelConfigMock = $this->getMock(
            '\Magento\Config\Model\ResourceModel\Config',
            [],
            [],
            '',
            false
        );
        $this->registryMock = $this->getMock('\Magento\Framework\Registry', [], [], '', false);
        $this->configMock = $this->getMock('\Magento\Framework\App\Config\ScopeConfigInterface', [], [], '', false);
        $this->resourceMock = $this->getMock(
            '\Magento\Framework\Model\ResourceModel\AbstractResource',
            [],
            [],
            '',
            false
        );
        $this->resourceCollectionMock = $this->getMock(
            '\Magento\Framework\Data\Collection\AbstractDb',
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
        $this->canonicalModel = $this->objectManager->getObject(
            '\Mirasvit\Seo\Model\Config\Backend\Canonical',
            [
                'resourceModelConfigMock' => $this->resourceModelConfigMock,
                'registry' => $this->registryMock,
                'config' => $this->configMock,
                'resource' => $this->resourceMock,
                'resourceCollection' => $this->resourceCollectionMock,
                'context' => $this->contextMock,
            ]
        );
    }

    /**
     * dummy test.
     */
    public function testDummy()
    {
        $this->assertEquals($this->canonicalModel, $this->canonicalModel);
    }
}
