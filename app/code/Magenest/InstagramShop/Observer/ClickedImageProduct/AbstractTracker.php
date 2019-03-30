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

namespace Magenest\InstagramShop\Observer\ClickedImageProduct;

use Magenest\InstagramShop\Model\Photo;
use Magenest\InstagramShop\Model\Report;
use Magenest\InstagramShop\Model\TaggedPhoto;
use Magento\Framework\Event\ObserverInterface;
use Magenest\InstagramShop\Helper\Helper;

abstract class AbstractTracker implements ObserverInterface
{
    /**
     * @var Report
     */
    protected $reportObj;
    /**
     * @var Photo|TaggedPhoto
     */
    protected $photoObj;
    /**
     * @var \Magenest\InstagramShop\Model\ReportFactory
     */
    protected $reportFactory;
    /**
     * @var Helper
     */
    protected $helper;

    /**
     * TrackInstagramProduct constructor.
     * @param \Magenest\InstagramShop\Model\ReportFactory $reportFactory
     * @param Helper $helper
     */
    public function __construct(
        \Magenest\InstagramShop\Model\ReportFactory $reportFactory,
        Helper $helper
    )
    {
        $this->helper        = $helper;
        $this->reportFactory = $reportFactory;
    }

    /**
     * @param $photoId
     * @param $type
     * @return Report
     */
    public function getReportObject($photoId, $type)
    {
        if ($this->reportObj === null) {
            $this->reportObj = $this->reportFactory->create()->loadByPhotoIdAndType($photoId, $type);
        }
        return $this->reportObj;
    }

    /**
     * @param $photoId
     * @param $type
     * @return Photo|TaggedPhoto
     */
    protected function getPhotoObject($photoId, $type)
    {
        if ($this->photoObj === null) {
            $this->photoObj = $this->helper->getPhoto($photoId, $type);
        }
        return $this->photoObj;
    }
}