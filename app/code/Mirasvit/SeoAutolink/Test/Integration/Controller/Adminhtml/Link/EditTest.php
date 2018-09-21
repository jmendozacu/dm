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



namespace Mirasvit\SeoAutolink\Controller\Adminhtml\Link;

/**
 * @magentoAppArea adminhtml
 */
class EditTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    /**
     * setUp.
     */
    public function setUp()
    {
        $this->resource = 'Mirasvit_SeoAutolink::seoautolink_link';
        $this->uri = 'backend/seoautolink/link/edit';
        parent::setUp();
    }

    /**
     * @magentoDataFixture Mirasvit/SeoAutolink/_files/links.php
     *
     * @covers  Mirasvit\SeoAutolink\Controller\Adminhtml\Link\Edit::execute
     */
    public function testEditAction()
    {
        $this->getRequest()->setParam('id', 5);
        $this->dispatch('backend/seoautolink/link/edit');
        $body = $this->getResponse()->getBody();
        $this->assertContains('<h1 class="page-title">Edit Link \'snow\'</h1>', $body);
    }
}
