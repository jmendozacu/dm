/**
 * MageVision Mini Cart Coupon Extension
 *
 * @category     MageVision
 * @package      MageVision_MiniCartCoupon
 * @author       MageVision Team
 * @copyright    Copyright (c) 2018 MageVision (http://www.magevision.com)
 * @license      LICENSE_MV.txt or http://www.magevision.com/license-agreement/
 */
/*jshint browser:true jquery:true*/
define([
    'jquery',
    'jquery/ui'
], function ($) {
    "use strict";
        
    $.widget('mage.minicartDiscountCode', {
        options: {
            'discountElementMiniCart': '#block-minicart-discount',
            'messageElementCart'     : '#mini-cart-coupon-message'
        },
        _create: function () {
            this.minicartCouponCode = $(this.options.minicartCouponCodeSelector);
            this.minicartRemoveCoupon = $(this.options.minicartRemoveCouponSelector);

            $(this.options.minicartApplyButton).on('click', $.proxy(function (e) {
                this.minicartCouponCode.attr('data-validate', '{required:true}');
                this.minicartRemoveCoupon.attr('value', '0');
                if (this.element.validation().valid()) {
                    this.ajaxSubmit($(e.target).closest('form'));
                }
            }, this));

            $(this.options.minicartCancelButton).on('click', $.proxy(function (e) {
                this.minicartCouponCode.removeAttr('data-validate');
                this.minicartRemoveCoupon.attr('value', '1');
                this.ajaxSubmit($(e.target).closest('form'));
            }, this));
        },
        
        ajaxSubmit: function (form) {
            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
                type: 'post',
                dataType: 'json',
                context: this
            }).done(function (response) {
                    if (response.success) {
                        $(this.options.discountElementMiniCart).html(response.blockMiniCartCoupon).trigger('contentUpdated');
                        if (response.successMessage) {
                            $(this.options.messageElementCart).addClass("message-success success message").append('<div>' + response.message + '</div>').trigger('contentUpdated');
                        } else {
                            $(this.options.messageElementCart).addClass("message-error error message").append('<div>' + response.message + '</div>').trigger('contentUpdated');
                        }
                    } else {
                        var msg = response.error_message;
                        if (msg) {
                            alert({
                                content: $.mage.__(msg)
                            });
                        }
                    }
                })
            .fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }
    });

    return $.mage.minicartDiscountCode;
});