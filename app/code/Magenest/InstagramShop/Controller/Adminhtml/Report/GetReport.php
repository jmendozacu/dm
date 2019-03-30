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

use Magenest\InstagramShop\Block\Adminhtml\Report\FullReport;
use Magenest\InstagramShop\Controller\Adminhtml\Report;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class GetReport extends Report
{
    /**
     * @var FullReport
     */
    protected $fullReportBlock;

    /**
     * @return FullReport
     */
    public function getFullReportBlock()
    {
        if ($this->fullReportBlock === null) {
            $this->fullReportBlock = $this->_view->getLayout()->createBlock(FullReport::class);
        }
        return $this->fullReportBlock;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $from     = $this->getRequest()->getParam('from');
        $from     .= ' 00:00:00';
        $to       = $this->getRequest()->getParam('to');
        $to       .= ' 23:59:59';
        $dateUsed = $this->getRequest()->getParam('date_used');

        // Reload loadedCollectionBlock every request
        $this->getFullReportBlock()->clearLoadedCollection();

        if ($from && $to && $dateUsed) {
            $this->getFullReportBlock()->setLoadedCollection([
                [$dateUsed => ['gteq' => $from]],
                [$dateUsed => ['lteq' => $to]]
            ]);
        }
        $httpBadRequestCode = 400;
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        if ($this->getRequest()->getMethod() !== 'POST' || !$this->getRequest()->isXmlHttpRequest()) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }

        $response['totalClicks']                = $this->getFullReportBlock()->getLoadedCollection()->getTotalClicks();
        $response['totalAddedToCart']           = $this->getFullReportBlock()->getLoadedCollection()->getTotalAddedToCart();
        $response['totalConversionRate']        = $this->getFullReportBlock()->getTotalConversionRate(true);
        $response['mostViewImage']              = json_encode($this->getFullReportBlock()->getMostViewImage()->getData());
        $response['mostViewProductFromImage']   = json_encode($this->getFullReportBlock()->getMostViewProductFromImage());
        $response['highestConversionRateImage'] = json_encode($this->getFullReportBlock()->getHighestConversionRateImage()->getData());

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        return $resultJson->setData($response);
    }
}