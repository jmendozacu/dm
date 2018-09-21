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



namespace Mirasvit\SeoSitemap\Test\Unit\Model\Config\Source\Ignore\Cms;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;

/**
 * @covers \Mirasvit\SeoSitemap\Model\Config\Source\Ignore\Cms\Pages
 */
class PagesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\SeoSitemap\Model\Config\Source\Ignore\Cms\Pages|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $pagesModel;

    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $pageCollectionFactoryMock;

    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $pageCollectionMock;

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
        $this->objectManager = new ObjectManager($this);
        $this->contextMock = $this->objectManager->getObject(
            '\Magento\Framework\Model\Context',
            [
            ]
        );
        $this->pagesModel = $this->objectManager->getObject(
            '\Mirasvit\SeoSitemap\Model\Config\Source\Ignore\Cms\Pages',
            [
                'pageCollectionFactory' => $this->pageCollectionFactoryMock,
                'context' => $this->contextMock,
            ]
        );
    }

    /**
     * dummy test.
     */
    public function testDummy()
    {
        $this->assertEquals($this->pagesModel, $this->pagesModel);
    }
}
