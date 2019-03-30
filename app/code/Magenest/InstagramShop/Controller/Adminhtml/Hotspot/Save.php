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

namespace Magenest\InstagramShop\Controller\Adminhtml\Hotspot;

use Magenest\InstagramShop\Model\Hotspot;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    )
    {
        $this->dataPersistor     = $dataPersistor;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data           = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('hotspot_id');

            $model = $this->_objectManager->create(\Magenest\InstagramShop\Model\Hotspot::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Hotspot no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $this->checkData($data);
                $model->save();

                $this->messageManager->addSuccessMessage(__('You saved the Hotspot.'));
                $this->dataPersistor->clear('magenest_instagramshop_hotspot');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['hotspot_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Hotspot.'));
            }

            $this->dataPersistor->set('magenest_instagramshop_hotspot', $data);
            return $resultRedirect->setPath('*/*/edit', ['hotspot_id' => $this->getRequest()->getParam('hotspot_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $data array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function checkData($data)
    {
        for ($i = 1; $i <= 5; $i++) {
            $hotspot = Hotspot::KEY . $i;
            $x       = $hotspot . '_x';
            $y       = $hotspot . '_y';
            $sku     = $hotspot . '_sku';
            if (isset($data[$x]) && isset($data[$y]) && isset($data[$sku]) && $data[$x] && $data[$y] && !$data[$sku])
                throw new LocalizedException(__('Please fill in SKU if there are X and Y.'));
            else if ($data[$sku]) {
                try {
                    $this->productRepository->get($data[$sku]);
                } catch (NoSuchEntityException $e) {
                    throw new NoSuchEntityException(__('Please enter a valid SKU.'));
                }
            }
        }
    }
}
