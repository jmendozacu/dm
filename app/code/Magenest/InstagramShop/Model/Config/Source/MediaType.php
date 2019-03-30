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

namespace Magenest\InstagramShop\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class MediaType
 * @package Magenest\InstagramShop\Model\Config\Source
 */
class MediaType implements ArrayInterface
{
    const ONLY_IMAGE           = 1;
    const BOTH_IMAGE_AND_VIDEO = 2;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            self::ONLY_IMAGE => __('Only Image'),
            self::BOTH_IMAGE_AND_VIDEO => __('Both Image And Video')
        ];

        return $options;
    }
}