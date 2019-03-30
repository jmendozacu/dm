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

namespace Magenest\InstagramShop\Block\Instagram\Renderer;

use Magenest\InstagramShop\Model\Photo;
use Magenest\InstagramShop\Model\TaggedPhoto;
use Magento\Framework\View\Element\Template;

class Video extends Template
{
    protected $_template = 'instagram/renderer/video.phtml';
    /**
     * @var Photo|TaggedPhoto
     */
    protected $photo;

    /**
     * @param Photo $photo
     * @return $this
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @return Photo|TaggedPhoto
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}