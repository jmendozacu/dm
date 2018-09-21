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
class SaveTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    /**
     * setUp.
     */
    public function setUp()
    {
        $this->resource = 'Mirasvit_SeoAutolink::seoautolink_link';
        $this->uri = 'backend/seoautolink/link/save';
        parent::setUp();
    }

    /**
     * @magentoDataFixture Mirasvit/SeoAutolink/_files/links.php
     *
     * @covers  Mirasvit\SeoAutolink\Controller\Adminhtml\Link\Save::execute
     */
    public function testSaveAction()
    {
        $data = [
            'id' => 5,
            'keyword' => 'snow',
            'url' => 'http://snow.com',
            'is_active' => '1',
            'store_ids' => [1]
        ];
        $this->getRequest()->setParams($data);
        $this->dispatch('backend/seoautolink/link/save');

        $this->assertSessionMessages(
            $this->equalTo(['Link was successfully saved']),
            \Magento\Framework\Message\MessageInterface::TYPE_SUCCESS
        );
        $this->assertRedirect($this->stringContains('seoautolink/link/index/'));

        /** @var \Mirasvit\SeoAutolink\Model\Link $link */
        $link = $this->_objectManager->create('Mirasvit\SeoAutolink\Model\Link')->load(5);
        $this->assertEquals($data['keyword'], $link->getKeyword());
        $this->assertEquals($data['url'], $link->getUrl());
        $this->assertEquals($data['is_active'], $link->getIsActive());
        $this->assertEquals($data['store_ids'], $link->getStoreIds());
    }

    /**
     * @covers  Mirasvit\Helpdesk\Controller\Adminhtml\Status\Save::execute
     */
    public function testSaveNewAction()
    {
        $data = [
            'keyword' => 'integration',
            'url' => 'http://magento-integration.com',
            'is_active' => '1',
            'store_ids' => [0]
        ];
        $this->getRequest()->setParams($data);
        $this->dispatch('backend/seoautolink/link/save');

        $this->assertSessionMessages(
            $this->equalTo(['Link was successfully saved']),
            \Magento\Framework\Message\MessageInterface::TYPE_SUCCESS
        );
        $this->assertRedirect($this->stringContains('seoautolink/link/index/'));

        /** @var \Mirasvit\SeoAutolink\Model\Link $link */
        $link = $this->_objectManager->create('Mirasvit\SeoAutolink\Model\Link')->getCollection()->getLastItem();
        $this->assertEquals($data['keyword'], $link->getKeyword());
    }
}
