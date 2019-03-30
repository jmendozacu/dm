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

namespace Magenest\InstagramShop\Controller\Adminhtml\Instagram;

use Magenest\InstagramShop\Model\Photo;
use Magenest\InstagramShop\Model\ResourceModel\Photo\CollectionFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Ui\Component\MassAction\Filter;

class MassShowInWidget extends AbstractMassAction
{
    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        parent::__construct($context, $filter);
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Set status to collection items
     *
     * @param AbstractCollection $collection
     * @return ResponseInterface|ResultInterface
     * @throws \Exception
     */
    protected function massAction(AbstractCollection $collection)
    {
        $count = 0;
        /** @var Photo $photo */
        foreach ($collection->getItems() as $photo) {
            if ($photo->isShowedInWidget()) {
                $photo->setShowInWidget(0)->save();
            } else {
                $photo->setShowInWidget(1)->save();
                $count++;
            }
        }
        $countUnShowPhotos = $collection->count() - $count;

        if ($count && $countUnShowPhotos) {
            $this->messageManager->addSuccessMessage(__("Total of %1 photo(s) were set show in widget.\nTotal of %2 photo(s) were set hide in widget.", $count, $countUnShowPhotos));
        } elseif ($countUnShowPhotos) {
            $this->messageManager->addSuccessMessage(__("Total of %1 photo(s) were set hide in widget.", $countUnShowPhotos));
        } elseif ($count) {
            $this->messageManager->addSuccessMessage(__("Total of %1 photo(s) were set show in widget.", $count));
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath($this->redirectUrl);
        return $resultRedirect;
    }
}
