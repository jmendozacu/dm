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



namespace Mirasvit\Seo\Test\Unit\Model\Template\Rule\Condition;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;
use Mirasvit\Seo\Model\Config as Config;

/**
 * @covers \Mirasvit\Seo\Model\Template\Rule\Condition\Validate
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class ValidateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Model\Template\Rule\Condition\Validate|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $validateModel;

    /**
     * @var \Magento\CatalogInventory\Model\Stock\ItemFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $stockItemFactoryMock;

    /**
     * @var \Magento\CatalogInventory\Model\Stock\Item|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $stockItemMock;

    /**
     * @var \Magento\CatalogRule\Model\ResourceModel\RuleFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $ruleFactoryMock;

    /**
     * @var \Magento\CatalogRule\Model\ResourceModel\Rule|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $ruleMock;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory|
     * \PHPUnit_Framework_MockObject_MockObject
     */
    protected $entityAttributeSetCollectionFactoryMock;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $entityAttributeSetCollectionMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\ProductFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productFactoryMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productMock;

    /**
     * @var \Magento\ConfigurableProduct\Model\Product\Type\ConfigurableFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productTypeConfigurableFactoryMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Type\Configurable|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productTypeConfigurableMock;

    /**
     * @var \Magento\Eav\Model\Config|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var \Magento\Framework\UrlInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlManagerMock;

    /**
     * @var \Magento\Backend\Model\Url|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $backendUrlManagerMock;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeManagerMock;

    /**
     * @var \Magento\Framework\Locale\FormatInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $localeFormatMock;

    /**
     * @var \Magento\Framework\View\Asset\Repository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $assetRepoMock;

    /**
     * @var \Magento\Customer\Model\Session|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $customerSessionMock;

    /**
     * @var \Magento\Framework\Model\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * setup tests.
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function setUp()
    {
        $this->stockItemFactoryMock = $this->getMock(
            '\Magento\CatalogInventory\Model\Stock\ItemFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->stockItemMock = $this->getMock(
            '\Magento\CatalogInventory\Model\Stock\Item',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->stockItemFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->stockItemMock));
        $this->ruleFactoryMock = $this->getMock(
            '\Magento\CatalogRule\Model\ResourceModel\RuleFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->ruleMock = $this->getMock(
            '\Magento\CatalogRule\Model\ResourceModel\Rule',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->ruleFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->ruleMock));
        $this->entityAttributeSetCollectionFactoryMock = $this->getMock(
            '\Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->entityAttributeSetCollectionMock = $this->getMock(
            '\Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection',
            ['load', 'save', 'delete', 'addFieldToFilter', 'setOrder', 'getFirstItem', 'getLastItem'],
            [],
            '',
            false
        );
        $this->entityAttributeSetCollectionFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->entityAttributeSetCollectionMock));
        $this->productFactoryMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\ProductFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->productMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Product',
            ['load', 'save', 'delete', 'loadAllAttributes'],
            [],
            '',
            false
        );
        $this->productMock->expects($this->atLeastOnce())->method('loadAllAttributes')
                ->will($this->returnValue($this->productMock));
        $this->productFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->productMock));
        $this->productTypeConfigurableFactoryMock = $this->getMock(
            '\Magento\ConfigurableProduct\Model\Product\Type\ConfigurableFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->productTypeConfigurableMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Product\Type\Configurable',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->productTypeConfigurableFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->productTypeConfigurableMock));
        $this->configMock = $this->getMock('\Magento\Eav\Model\Config', [], [], '', false);
        $this->urlManagerMock = $this->getMockForAbstractClass(
            '\Magento\Framework\UrlInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );
        $this->backendUrlManagerMock = $this->getMock('\Magento\Backend\Model\Url', [], [], '', false);
        $this->storeManagerMock = $this->getMockForAbstractClass(
            '\Magento\Store\Model\StoreManagerInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );
        $this->localeFormatMock = $this->getMockForAbstractClass(
            '\Magento\Framework\Locale\FormatInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );
        $this->assetRepoMock = $this->getMock('\Magento\Framework\View\Asset\Repository', [], [], '', false);
        $this->customerSessionMock = $this->getMock('\Magento\Customer\Model\Session', [], [], '', false);
        $this->registryMock = $this->getMock('\Magento\Framework\Registry', [], [], '', false);
        $this->objectManager = new ObjectManager($this);
        $this->contextMock = $this->objectManager->getObject(
            'Magento\Rule\Model\Condition\Context',
            [
            ]
        );
        $this->validateModel = $this->objectManager->getObject(
            '\Mirasvit\Seo\Model\Template\Rule\Condition\Validate',
            [
                'stockItemFactory' => $this->stockItemFactoryMock,
                'ruleFactory' => $this->ruleFactoryMock,
                'entityAttributeSetCollectionFactory' => $this->entityAttributeSetCollectionFactoryMock,
                'productFactory' => $this->productFactoryMock,
                'productTypeConfigurableFactory' => $this->productTypeConfigurableFactoryMock,
                'config' => $this->configMock,
                'urlManager' => $this->urlManagerMock,
                'backendUrlManager' => $this->backendUrlManagerMock,
                'storeManager' => $this->storeManagerMock,
                'localeFormat' => $this->localeFormatMock,
                'assetRepo' => $this->assetRepoMock,
                'customerSession' => $this->customerSessionMock,
                'context' => $this->contextMock,
                'registry' => $this->registryMock,
            ]
        );
    }

    /**
     * dummy test.
     */
    public function testDummy()
    {
        $this->assertEquals($this->validateModel, $this->validateModel);
    }
}
