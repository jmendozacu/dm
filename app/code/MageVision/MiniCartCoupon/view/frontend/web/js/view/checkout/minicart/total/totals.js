/**
 * MageVision Mini Cart Coupon Extension
 *
 * @category     MageVision
 * @package      MageVision_MiniCartCoupon
 * @author       MageVision Team
 * @copyright    Copyright (c) 2018 MageVision (http://www.magevision.com)
 * @license      LICENSE_MV.txt or http://www.magevision.com/license-agreement/
 */
define([
    'ko',
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function (ko, Component, customerData) {
    'use strict';

    return Component.extend({
        
        /**
         * @override
         */
        initialize: function () {
            this._super();
            this.cart = customerData.get('cart');
        }
    });
});
