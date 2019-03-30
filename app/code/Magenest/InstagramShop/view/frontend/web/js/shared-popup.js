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
    'mage/template',
    'underscore',
    'mage/storage',
    'jquery/ui',
    'mage/validation'
], function ($, mageTemplate, _, storage) {
    'use strict';

    $.widget('magenest.instagram', {
        urlPathTrackImageClick: 'instagram/instagram/imageClick',
        options: {
            photos: [],
            photoType: 1,
            linkedProductsLayout: 'tile',
            linkedProductClass: '',
            prefixContainer: '',
            prefixElement: '',
            prefixDelimiter: ' ',
            imageContainer: '.widget-image-container',
            instagramImageUrlId: '#widget-instagram-image-url',
            instagramUrlId: '#widget-instagram-url',
            linkedProductsId: '#linked-products',
            featuredProductsContainer: '.featured-products',
            textLinkContainer: '.widget-text-link-container',
            hotspotId: '#hotspot',
            hotspotSectionId: '#hotspot-section',
            detailTitle: '.widget-detail-title',
            detailLeft: '.widget-detail-left',
            videoSectionId: '#video-section',
            videoBoxContent: '.box-content',
            createdAtId: '#created_at_instagram',
            timelineDetailId: '#widget-timeline-detail',

            closeButtonId: '#widget-detail-close',
            prePostId: '#widget-prev-post',
            nextPostId: '#widget-next-post',

            heartId: '#heart',
            commentId: '#comment',
            currentProductUrl: '',
            detailShare: '.widget-detail-shares',
            facebookShare: '.widget-facebook-share',
            mailShare: '.widget-mail-share',
            twitterShare: '.widget-twitter-share',
            pinterestShare: '.widget-pinterest-share',
            shareContent: 'Checkout our product on Instagram',

            suffixDelimiter: '#',

            isLinkedProductsShown: true,
            canShowVideo: false,
            optionGalleryPage: 'a:first'
        },

        /**
         *
         * @private
         */
        _create: function () {
            this._initListener();
            this._bindKeyboardEvent();
        },
        _initListener: function () {
            var self = this,
                photos = this.options.photos;
            _.each(photos, function (photo) {
                this._bindPhotoEventClick(photo);
            }, this);

            $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.timelineDetailId).on('click', function (e) {
                if ($(e.target).attr('id') !== undefined && $(e.target).attr('id') === self.options.timelineDetailId.replace('#', '')) {
                    self._closePopup();
                }
            });


            $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.closeButtonId).on('click', function () {
                self._closePopup();
            });

            $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.prePostId).on('click', function () {
                var photoId = $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.timelineDetailId).data('photo-id');
                _.each(photos, function (item, index) {
                    if (item.photo_id === photoId) {
                        var id = index === 0 ? photos.length : index;
                        return $(self.options.prefixElement + self.options.suffixDelimiter + photos[id - 1].photo_id + self.options.prefixDelimiter + self.options.optionGalleryPage).trigger('click');
                    }
                });
            });
            $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.nextPostId).on('click', function () {
                var photoId = $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.timelineDetailId).data('photo-id');
                _.each(photos, function (item, index) {
                    if (item.photo_id === photoId) {
                        var id = index === photos.length - 1 ? -1 : index;
                        return $(self.options.prefixElement + self.options.suffixDelimiter + photos[id + 1].photo_id + self.options.prefixDelimiter + self.options.optionGalleryPage).trigger('click');
                    }
                });
            });
        },
        /**
         * Close popup
         * @private
         */
        _closePopup: function () {
            var container = $(this.options.prefixContainer + this.options.prefixDelimiter + this.options.timelineDetailId);
            if (!container.hasClass('widget-hidden'))
                container.addClass('widget-hidden');
        },
        /**
         *
         * @param {Object} photo
         */
        updateImageClick: function (photo) {
            storage.post(
                this.urlPathTrackImageClick,
                JSON.stringify({photo_id: photo.photo_id, type: this.options.photoType})
            );
        }
        ,
        /**
         *
         * @param {Object} photo
         * @private
         */
        _bindPhotoEventClick: function (photo) {
            var self = this;
            $(self.options.prefixElement + self.options.suffixDelimiter + photo.photo_id + self.options.prefixDelimiter + self.options.optionGalleryPage).on('click', function (e) {
                e.preventDefault();
                if (photo) {
                    // check if photo has video
                    var videoSectionHtml = self._getContentHtml($(this).parent().children(self.options.videoSectionId).html());
                    if (self.options.canShowVideo && videoSectionHtml !== '') {
                        $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.videoBoxContent).html(videoSectionHtml).show();
                        $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.imageContainer).hide();
                    } else {
                        // hide video
                        $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.videoBoxContent).html('').hide();
                        $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.imageContainer).show();
                    }
                    // set photo image
                    $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.instagramImageUrlId).attr('src', photo.source);
                    if (self.options.isLinkedProductsShown) {
                        // remove all white space, tab, new line...
                        var linkedProductHtml = self._getContentHtml($(this).parent().children(self.options.linkedProductsId).html());
                        if (linkedProductHtml !== '') {
                            $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.featuredProductsContainer).show();
                            $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.textLinkContainer).html(linkedProductHtml);
                        } else {
                            $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.featuredProductsContainer).hide();
                            $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.textLinkContainer).html('');
                        }
                    }
                    //show hotspot html
                    var hotspotHtml = self._getContentHtml($(this).parent().children(self.options.hotspotId).html());
                    if (hotspotHtml !== '') {
                        $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.hotspotSectionId).html(hotspotHtml);
                    } else {
                        $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.hotspotSectionId).html('');
                    }

                    $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.heartId).text(photo.likes);
                    $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.commentId).text(photo.comments);

                    $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.instagramUrlId).attr('href', photo.url);
                    $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.detailTitle).text(photo.caption ? photo.caption : '');
                    $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.createdAtId).text(photo.created_at);
                    $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.timelineDetailId).data('photo-id', photo.photo_id).removeClass('widget-hidden');

                    // share button
                    if (self.options.currentProductUrl) {
                        $(self.options.detailShare).show();
                        // share buttons
                        var facebookUrl = 'http://www.facebook.com/sharer/sharer.php?s=100&p[url]=' + self.options.currentProductUrl + '&p[image]=' + photo.source;
                        $(self.options.facebookShare).attr('href', facebookUrl);
                        var mailUrl = 'mailto:?subject=' + self.options.shareContent + '&body=' + self.options.currentProductUrl;
                        $(self.options.mailShare).attr('href', mailUrl);
                        var twitterUrl = 'http://www.twitter.com/share?url=' + self.options.currentProductUrl + '&related=magenest&text=' + self.options.shareContent;
                        $(self.options.twitterShare).attr('href', twitterUrl);
                        var pinterestUrl = 'http://www.pinterest.com/pin/create/button/?url=' + self.options.currentProductUrl + '&media=' + photo.source + '&description=' + self.options.shareContent;
                        $(self.options.pinterestShare).attr('href', pinterestUrl);
                    }
                    self.updateImageClick(photo);
                }
            });
        }
        ,
        /**
         *
         * @param content
         * @returns {String}
         * @private
         */
        _getContentHtml: function (content) {
            if (_.isString(content)) {
                return content.replace(/[\r\n]+/g, '').trim();
            }
            return '';
        },
        _bindKeyboardEvent: function () {
            var self = this;
            $(document).on('keydown', function (e) {
                switch (e.keyCode) {
                    case 27: // ESC
                        self._closePopup();
                        return;
                    case 37:
                        $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.prePostId).trigger('click');
                        return;
                    case 39:
                        $(self.options.prefixContainer + self.options.prefixDelimiter + self.options.nextPostId).trigger('click');
                        return;
                }
            });
        }
    });

    return $.magenest.instagram;
});