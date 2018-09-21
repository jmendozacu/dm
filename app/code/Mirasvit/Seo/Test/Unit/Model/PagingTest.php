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

/**
 * @covers \Mirasvit\Seo\Model\Paging
 */
class PagingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Model\Paging|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $pagingModel;

    /**
     * @var \Magento\Catalog\Model\Layer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $layerMock;

    /**
     * @var \Magento\Backend\Block\Widget\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $widgetContextMock;

    /**
     * @var \Magento\Framework\UrlInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlManagerMock;

    /**
     * @var \Magento\Framework\Module\Manager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $moduleManagerMock;

    /**
     * @var \Magento\Framework\View\Element\Template\Context|\PHPUnit_Framework_MockObject_MockObject
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
        $this->layerMock = $this->getMock('\Magento\Catalog\Model\Layer', [], [], '', false);
        $this->widgetContextMock = $this->getMock('\Magento\Backend\Block\Widget\Context', [], [], '', false);
        $this->urlManagerMock = $this->getMockForAbstractClass(
            '\Magento\Framework\UrlInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );
        $this->moduleManagerMock = $this->getMock('\Magento\Framework\Module\Manager', [], [], '', false);
        $this->registryMock = $this->getMock('\Magento\Framework\Registry', [], [], '', false);
        $this->resourceMock = $this->getMock('\Mirasvit\Seo\Model\ResourceModel\Paging', [], [], '', false);
        $this->resourceCollectionMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Paging\Collection',
            [],
            [],
            '',
            false
        );
        $this->objectManager = new ObjectManager($this);
        $this->contextMock = $this->objectManager->getObject(
            '\Magento\Framework\View\Element\Template\Context',
            [
            ]
        );
        $this->pagingModel = $this->objectManager->getObject(
            '\Mirasvit\Seo\Model\Paging',
            [
                'layer' => $this->layerMock,
                'widgetContext' => $this->widgetContextMock,
                'urlManager' => $this->urlManagerMock,
                'moduleManager' => $this->moduleManagerMock,
                'context' => $this->contextMock,
                'registry' => $this->registryMock,
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
        $this->assertEquals($this->pagingModel, $this->pagingModel);
    }
}
