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
 * @covers \Mirasvit\Seo\Model\Template
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class TemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Model\Template|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $templateModel;

    /**
     * @var \Mirasvit\Seo\Model\Template\Rule\Condition\CombineFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $templateRuleConditionCombineFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\Template\Rule\Condition\Combine|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $templateRuleConditionCombineMock;

    /**
     * @var \Magento\Catalog\Model\ProductFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productFactoryMock;

    /**
     * @var \Magento\Catalog\Model\Product|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productMock;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $categoryFactoryMock;

    /**
     * @var \Magento\Catalog\Model\Category|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $categoryMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productCollectionFactoryMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productCollectionMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $categoryCollectionFactoryMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $categoryCollectionMock;

    /**
     * @var \Mirasvit\Seo\Model\ResourceModel\Template\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $templateCollectionFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\ResourceModel\Template\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $templateCollectionMock;

    /**
     * @var \Magento\Framework\Model\ResourceModel\Iterator|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resourceIteratorMock;

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
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function setUp()
    {
        $this->templateRuleConditionCombineFactoryMock = $this->getMock(
            '\Mirasvit\Seo\Model\Template\Rule\Condition\CombineFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->templateRuleConditionCombineMock = $this->getMock(
            '\Mirasvit\Seo\Model\Template\Rule\Condition\Combine',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->templateRuleConditionCombineFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->templateRuleConditionCombineMock));
        $this->productFactoryMock = $this->getMock('\Magento\Catalog\Model\ProductFactory', ['create'], [], '', false);
        $this->productMock = $this->getMock(
            '\Magento\Catalog\Model\Product',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->productFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->productMock));
        $this->categoryFactoryMock = $this->getMock(
            '\Magento\Catalog\Model\CategoryFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->categoryMock = $this->getMock(
            '\Magento\Catalog\Model\Category',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->categoryFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->categoryMock));
        $this->productCollectionFactoryMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->productCollectionMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Product\Collection',
            ['load', 'save', 'delete', 'addFieldToFilter', 'setOrder', 'getFirstItem', 'getLastItem'],
            [],
            '',
            false
        );
        $this->productCollectionFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->productCollectionMock));
        $this->categoryCollectionFactoryMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->categoryCollectionMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Category\Collection',
            ['load', 'save', 'delete', 'addFieldToFilter', 'setOrder', 'getFirstItem', 'getLastItem'],
            [],
            '',
            false
        );
        $this->categoryCollectionFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->categoryCollectionMock));
        $this->templateCollectionFactoryMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Template\CollectionFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->templateCollectionMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Template\Collection',
            ['load', 'save', 'delete', 'addFieldToFilter', 'setOrder', 'getFirstItem', 'getLastItem'],
            [],
            '',
            false
        );
        $this->templateCollectionFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->templateCollectionMock));
        $this->resourceIteratorMock = $this->getMock(
            '\Magento\Framework\Model\ResourceModel\Iterator',
            [],
            [],
            '',
            false
        );
        $this->registryMock = $this->getMock('\Magento\Framework\Registry', [], [], '', false);
        $this->resourceMock = $this->getMock('\Mirasvit\Seo\Model\ResourceModel\Template', [], [], '', false);
        $this->resourceCollectionMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Template\Collection',
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
        $this->templateModel = $this->objectManager->getObject(
            '\Mirasvit\Seo\Model\Template',
            [
                'templateRuleConditionCombineFactory' => $this->templateRuleConditionCombineFactoryMock,
                'productFactory' => $this->productFactoryMock,
                'categoryFactory' => $this->categoryFactoryMock,
                'productCollectionFactory' => $this->productCollectionFactoryMock,
                'categoryCollectionFactory' => $this->categoryCollectionFactoryMock,
                'templateCollectionFactory' => $this->templateCollectionFactoryMock,
                'resourceIterator' => $this->resourceIteratorMock,
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
        $this->assertEquals($this->templateModel, $this->templateModel);
    }
}
