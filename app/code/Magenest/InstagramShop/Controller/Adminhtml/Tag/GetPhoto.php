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

namespace Magenest\InstagramShop\Controller\Adminhtml\Tag;

use Magenest\InstagramShop\Model\Cron;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class GetPhoto
 * @package Magenest\InstagramShop\Controller\Adminhtml\Instagram
 */
class GetPhoto extends Action
{
    /**
     * @var Cron
     */
    protected $_updatePhoto;

    /**
     * @param Context $context
     * @param Cron $updatePhoto
     */
    public function __construct(
        Context $context,
        Cron $updatePhoto
    ) {
        $this->_updatePhoto = $updatePhoto;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $this->_updatePhoto->getPhotoByTags();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong while pulling hashtag photos from Instagram.') . $e->getMessage());
        }

        return $this->_redirect('*/*');
    }
}
