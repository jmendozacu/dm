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

/**
 * @covers \Mirasvit\Seo\Model\Template\Rule\Condition\Combine
 */
class CombineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mirasvit\Seo\Model\Template\Rule\Condition\Combine|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $combineModel;

    /**
     * @var \Mirasvit\Seo\Model\Template\Rule\Condition\ValidateFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $templateRuleConditionValidateFactoryMock;

    /**
     * @var \Mirasvit\Seo\Model\Template\Rule\Condition\Validate|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $templateRuleConditionValidateMock;

    /**
     * @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * @var \Magento\Framework\Model\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

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
        $this->templateRuleConditionValidateFactoryMock = $this->getMock(
            '\Mirasvit\Seo\Model\Template\Rule\Condition\ValidateFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->templateRuleConditionValidateMock = $this->getMock(
            '\Mirasvit\Seo\Model\Template\Rule\Condition\Validate',
            ['load', 'save', 'delete'],
            [],
            '',
            false
        );
        $this->templateRuleConditionValidateFactoryMock->expects($this->any())->method('create')
                ->will($this->returnValue($this->templateRuleConditionValidateMock));
        $this->registryMock = $this->getMock('\Magento\Framework\Registry', [], [], '', false);
        $this->resourceMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Template\Rule\Condition\Combine',
            [],
            [],
            '',
            false
        );
        $this->resourceCollectionMock = $this->getMock(
            '\Mirasvit\Seo\Model\ResourceModel\Template\Rule\Condition\Combine\Collection',
            [],
            [],
            '',
            false
        );
        $this->objectManager = new ObjectManager($this);
        $this->contextMock = $this->objectManager->getObject(
            'Magento\Rule\Model\Condition\Context',
            [
            ]
        );
        $this->combineModel = $this->objectManager->getObject(
            '\Mirasvit\Seo\Model\Template\Rule\Condition\Combine',
            [
                'templateRuleConditionValidateFactory' => $this->templateRuleConditionValidateFactoryMock,
                'registry' => $this->registryMock,
                'context' => $this->contextMock,
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
        $this->assertEquals($this->combineModel, $this->combineModel);
    }
}
