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
 * Class GalleryTemplate
 * @package Magenest\InstagramShop\Model\Config\Source
 */
class GalleryTemplate implements ArrayInterface
{
    const DEFAULT_VALUE_TEMPLATE = 'default';
    const MIX_VALUE_TEMPLATE     = 'mix';

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [];
    }
}