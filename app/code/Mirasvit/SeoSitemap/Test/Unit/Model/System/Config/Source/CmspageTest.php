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



namespace Mirasvit\SeoSitemap\Test\Unit\Model\System\Config\Source;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;

/**
 * @covers \Mirasvit\SeoSitemap\Model\System\Config\Source\Cmspage
 */
class CmspageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\SeoSitemap\Model\System\Config\Source\Cmspage|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $cmspageModel;

    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $pageCollectionFactoryMock;

    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $pageCollectionMock;

    /**
     * @var \Magento\Framework\App\Request\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeManagerMock;

    /**
     * @var \Magento\Framework\Model\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * setup tests.
     */
    public function setUp()
    {
        $this->pageCollectionFactoryMock = $this->getMock(
            '\Magento\Cms\Model\ResourceModel\Page\CollectionFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->pageCollectionMock = $this->getMock(
            '\Magento\Cms\Model\ResourceModel\Page\Collection',
            ['load', 'save', 'delete', 'addFieldToFilter', 'setOrder', 'getFirstItem', 'getLastItem'],
            [],
            '',
            false
        );
        $this->pageCollectionFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->pageCollectionMock));
        $this->requestMock = $this->getMock('\Magento\Framework\App\Request\Http', [], [], '', false);
        $this->storeManagerMock = $this->getMockForAbstractClass(
            '\Magento\Store\Model\StoreManagerInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );
        $this->objectManager = new ObjectManager($this);
        $this->contextMock = $this->objectManager->getObject(
            '\Magento\Framework\Model\Context',
            [
            ]
        );
        $this->cmspageModel = $this->objectManager->getObject(
            '\Mirasvit\SeoSitemap\Model\System\Config\Source\Cmspage',
            [
                'pageCollectionFactory' => $this->pageCollectionFactoryMock,
                'request' => $this->requestMock,
                'storeManager' => $this->storeManagerMock,
                'context' => $this->contextMock,
            ]
        );
    }

    /**
     * dummy test.
     */
    public function testDummy()
    {
        $this->assertEquals($this->cmspageModel, $this->cmspageModel);
    }
}
