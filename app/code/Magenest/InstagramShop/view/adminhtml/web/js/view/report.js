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
    'jquery',
    'ko',
    'uiComponent',
    'underscore',
    'mage/translate',
    'uiRegistry',
], function ($, ko, Component, _, $t, registry) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Magenest_InstagramShop/view/report',
            notFoundMessage: 'Sorry. We couldn\'t find any records.',
            totalClicks: ko.observable(0),
            totalAddedToCart: ko.observable(0),
            totalConversionRate: ko.observable(0),
            mostViewImage: ko.observable(null),
            mostViewProductFromImage: ko.observable(null),
            highestConversionRateImage: ko.observable(null),
            getReportUrl: ''
        },

        initObservable: function () {
            this._super().observe([
                    'totalClicks',
                    'totalAddedToCart',
                    'totalConversionRate',
                    'totalConversionRate',
                    'mostViewImage',
                    'mostViewProductFromImage',
                    'highestConversionRateImage'
                ]
            );
            return this;
        },
        /**
         * @param object
         * @returns {*}
         */
        isEmptyObject: function (object) {
            return _.isObject(object) && !_.isEmpty(object);
        },
        /**
         * @returns {*}
         */
        getNotFoundMessage: function () {
            return $t(this.notFoundMessage);
        },
        getReport: function () {
            var from = registry.get('from-date').value(),
                to = registry.get('to-date').value(),
                dateUsed = $('select#date-used').val();
            if (from && to && dateUsed) {
                this.sendReportRequest({from: from, to: to, date_used: dateUsed})
            }
        },
        /**
         * Reset to default report
         */
        resetReport: function () {
            registry.get('from-date').value('');
            registry.get('to-date').value('');
            this.sendReportRequest({from: '', to: '', date_used: ''});
        },
        /**
         * Submit a request to get report
         * @param data
         */
        sendReportRequest: function (data) {
            var self = this,
                requestUrl = this.getReportUrl;
            $('body').trigger('processStart');
            $.ajax({
                url: requestUrl,
                data: data,
                type: 'POST'
            }).done(function (response) {
                self.totalClicks(response.totalClicks);
                self.totalAddedToCart(response.totalAddedToCart);
                self.totalConversionRate(response.totalConversionRate);
                self.mostViewImage(JSON.parse(response.mostViewImage));
                self.mostViewProductFromImage(JSON.parse(response.mostViewProductFromImage));
                self.highestConversionRateImage(JSON.parse(response.highestConversionRateImage));
                $('body').trigger('processStop');
            }).fail(function (res) {
                console.log(res);
                $('body').trigger('processStop');
            });
        }
    })
});