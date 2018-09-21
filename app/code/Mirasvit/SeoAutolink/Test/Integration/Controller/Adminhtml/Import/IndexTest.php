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



namespace Mirasvit\SeoAutolink\Controller\Adminhtml\Import;

/**
 * @magentoAppArea adminhtml
 */
class IndexTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    /**
     * setUp.
     */
    public function setUp()
    {
        $this->resource = 'Mirasvit_SeoAutolink::seoautolink_autolinks_seoautolinkimport';
        $this->uri = 'backend/seoautolink/import/index';
        parent::setUp();
    }

    /**
     * @covers  Mirasvit\SeoAutolink\Controller\Adminhtml\Import\Index::execute
     */
    public function testIndexAction()
    {
        $this->dispatch('backend/seoautolink/import/index');
        $body = $this->getResponse()->getBody();
        $this->assertContains('<h1 class="page-title">Import Links</h1>', $body);
    }
}
