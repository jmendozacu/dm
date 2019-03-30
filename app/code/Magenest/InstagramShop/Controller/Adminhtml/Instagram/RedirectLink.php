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

use Magenest\InstagramShop\Model\Hotspot;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;

/**
 * Class RedirectLink
 * @package Magenest\InstagramShop\Controller\Adminhtml\Instagram
 */
class RedirectLink extends Action
{
    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Exception
     */
    public function execute()
    {
        $type      = $this->getRequest()->getParam('type');
        $photoType = $this->getRequest()->getParam('photo_type');
        $result    = [];
        switch ($type) {
            case 'hotspot':
                /** @var Hotspot $factory */
                $factory = $this->_objectManager->create(Hotspot::class);
                $photoId = $this->getRequest()->getParam('photo_id');
                $hotspot = $factory->loadByPhotoIdAndType($photoId, $photoType);
                if (!$hotspot->getId()) {
                    $hotspot->addData(['photo_id' => $photoId, 'type' => $photoType])->save();
                }
                $result['path']  = 'instagram/hotspot/edit';
                $result['name']  = 'hotspot_id';
                $result['value'] = $hotspot->getId();
                break;
            default:
        }
        if (isset($result['path']) && isset($result['name']) && isset($result['value'])) {
            return $this->_redirect($result['path'], [
                $result['name'] => $result['value']
            ]);
        } else {
            $this->messageManager->addErrorMessage(__('Something went wrong while access redirect link'));
            return $this->_redirect('*/*/');
        }
    }
}