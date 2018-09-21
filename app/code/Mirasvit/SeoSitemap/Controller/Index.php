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



namespace Mirasvit\SeoSitemap\Controller;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Mirasvit\SeoSitemap\Api\Service\SeoSitemapUrlServiceInterface;
use Magento\Framework\App\Response\RedirectInterface;

abstract class Index extends Action
{
    /**
     * @var \Magento\Framework\App\Action\Context
     */
    protected $context;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param SeoSitemapUrlServiceInterface $seoSitemapUrlService
     */
    public function __construct(
        Context $context,
        SeoSitemapUrlServiceInterface $seoSitemapUrlService
    ) {
        $this->context = $context;
        $this->request = $context->getRequest();
        $this->response = $context->getResponse();
        $this->resultFactory = $context->getResultFactory();
        $this->seoSitemapUrlService = $seoSitemapUrlService;
        parent::__construct($context);
    }
}
