<?php
/**
 *
  * Copyright © 2018 Magenest. All rights reserved.
  * See COPYING.txt for license details.
  *
  * Magenest_InstagramShop extension
  * NOTICE OF LICENSE
  *
  * @category Magenest
  * @package  Magenest_InstagramShop
  * @author    dangnh@magenest.com

 */

namespace Magenest\InstagramShop\Controller\Adminhtml\Report;

use Magenest\InstagramShop\Controller\Adminhtml\Report;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Index
 * @package Magenest\InstagramShop\Controller\Instagram\Report
 */
class Index extends Report
{
    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Magenest_InstagramShop::report');
        $resultPage->addBreadcrumb(__('Magenest'), __('Magenest'));
        $resultPage->addBreadcrumb(__('Instagram Shop'), __('Instagram Shop'));
        $resultPage->getConfig()->getTitle()->prepend(__('Report'));
        return $resultPage;
    }
}