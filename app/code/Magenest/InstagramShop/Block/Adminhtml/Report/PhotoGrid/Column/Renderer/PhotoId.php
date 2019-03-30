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

namespace Magenest\InstagramShop\Block\Adminhtml\Report\PhotoGrid\Column\Renderer;

use Magenest\InstagramShop\Helper\Helper;
use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;

/**
 * Class PhotoId
 * @package Magenest\InstagramShop\Block\Adminhtml\Report\PhotoGrid\Column\Renderer
 */
class PhotoId extends AbstractRenderer
{
    /**
     * @var Helper
     */
    protected $helper;

    /**
     * PhotoId constructor.
     * @param Context $context
     * @param Helper $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Helper $helper,
        array $data = [])
    {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $photoId = $row->getData($this->getColumn()->getIndex());
        $type    = $row->getType();
        if ($photoId) {
            $photo = $this->helper->getPhoto($photoId, $type);
            return '<img src="' . $photo->getSource() . '" height="100px" width="100px" alt="' . $photo->getPhotoId() . '"><p><span>' . $photo->getPhotoId() . '</span></p>';
        }
        return '';
    }
}
