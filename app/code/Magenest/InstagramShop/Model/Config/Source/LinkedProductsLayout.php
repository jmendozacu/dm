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
/**
 * Class LinkedProductsLayout
 * @package Magento\Config\Model\Config\Source
 */
class LinkedProductsLayout implements \Magento\Framework\Option\ArrayInterface
{
    const TILE_VALUE = 'tile';
    const LIST_VALUE = 'list';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::TILE_VALUE, 'label' => __('Product Name And Image')],
            ['value' => self::LIST_VALUE, 'label' => __('Product Name')]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [self::TILE_VALUE => __('Product Name And Image'), self::LIST_VALUE => __('Product Name')];
    }
}
