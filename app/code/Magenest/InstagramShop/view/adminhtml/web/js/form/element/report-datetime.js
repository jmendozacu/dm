/*
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

define([
    'Magento_Ui/js/form/element/date'
], function (dateComponent) {
    return dateComponent.extend({
        defaults: {
            template: "ui/form/element/date",
            options: {
                pickerDefaultDateFormat: "y-MM-dd",
                dateFormat: "y-MM-dd",
                outputDateFormat: "y-MM-dd"
            },
            outputDateTimeToISO: false
        }
    });
});