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

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Url\DecoderInterface;

class Link extends Action
{
    /**
     * @var DecoderInterface
     */
    protected $urlDecoder;

    /**
     * Link constructor.
     * @param Context $context
     * @param DecoderInterface $urlDecoder
     */
    public function __construct(
        Context $context,
        DecoderInterface $urlDecoder
    )
    {
        $this->urlDecoder = $urlDecoder;
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
        /** @var \Magento\Framework\Controller\Result\Redirect $redirectFactory */
        $redirectFactory = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($key = $this->getRequest()->getParam('key')) {
            try {
                $decodedUrl = $this->urlDecoder->decode($key);
                $url        = $decodedUrl;
                $this->_eventManager->dispatch('controller_instagram_access_product', ['url' => $decodedUrl, 'params' => $this->getRequest()->getParams()]);
            } catch (\Exception $e) {
                $url = $this->_url->getBaseUrl();
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        } else {
            $url = $this->_url->getBaseUrl();
            $this->messageManager->addErrorMessage(__('Request Key for that URL is no longer exists.'));
        }
        return $redirectFactory->setUrl($url);
    }
}