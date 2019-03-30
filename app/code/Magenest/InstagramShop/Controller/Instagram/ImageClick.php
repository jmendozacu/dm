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

namespace Magenest\InstagramShop\Controller\Instagram;

use Magenest\InstagramShop\Model\Report;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Json\DecoderInterface;

class ImageClick extends Action
{
    /**
     * @var \Magenest\InstagramShop\Model\ReportFactory
     */
    protected $reportFactory;

    protected $jsonDecoder;

    /**
     * ImageClick constructor.
     * @param Context $context
     * @param DecoderInterface $decoder
     * @param \Magenest\InstagramShop\Model\ReportFactory $reportFactory
     */
    public function __construct(
        Context $context,
        DecoderInterface $decoder,
        \Magenest\InstagramShop\Model\ReportFactory $reportFactory
    )
    {
        $this->jsonDecoder   = $decoder;
        $this->reportFactory = $reportFactory;
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $httpBadRequestCode = 400;
        $credentials        = null;
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        try {
            $credentials = $this->jsonDecoder->decode($this->getRequest()->getContent());
        } catch (\Exception $e) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }
        if (!$credentials || $this->getRequest()->getMethod() !== 'POST' || !$this->getRequest()->isXmlHttpRequest()) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }

        $response = [
            'errors' => false,
            'message' => __('Update successful.')
        ];
        $photoId  = $credentials['photo_id'];
        $type     = $credentials['type'];
        /** @var Report $reportModel */
        $reportModel = $this->reportFactory->create();
        $report      = $reportModel->loadByPhotoIdAndType($photoId, $type);
        if (!$report->getId()) {
            $report->setPhotoId($photoId)->setType($type);
        }

        try {
            $report->increaseTotalClicks()->save();
        } catch (\Exception $e) {
            $response = [
                'errors' => true,
                'message' => __('Update failed.')
            ];
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        return $resultJson->setData($response);
    }
}