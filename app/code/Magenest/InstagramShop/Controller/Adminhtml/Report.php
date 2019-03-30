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

namespace Magenest\InstagramShop\Controller\Adminhtml;

use Magento\Backend\App\Action;

/**
 * Class Report
 * @package Magenest\InstagramShop\Controller\Adminhtml
 */
abstract class Report extends Action
{
    const ADMIN_RESOURCE = 'Magenest_InstagramShop::report';
}