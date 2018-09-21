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



namespace Mirasvit\Seo\Controller\Adminhtml\System\Template;

/**
 * @magentoAppArea adminhtml
 */
class ApplyUrlTemplateStepTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    /**
     * setUp.
     */
    public function setUp()
    {
        $this->resource = 'Mirasvit_Seo::seo_template';
        $this->uri = 'backend/seo/template/applyurltemplatestep';
        parent::setUp();

        $this->markTestIncomplete();
    }

    /**
     * @covers  Mirasvit\Seo\Controller\Adminhtml\System\Template\ApplyUrlTemplateStep::execute
     */
    public function testApplyUrlTemplateStepAction()
    {
        $this->dispatch('backend/seo/template/applyurltemplatestep');
        $body = $this->getResponse()->getBody();
        $this->assertNotEmpty($body);
        $this->assertNotEquals('noroute', $this->getRequest()->getControllerName());
        $this->assertFalse($this->getResponse()->isRedirect());
    }
}
