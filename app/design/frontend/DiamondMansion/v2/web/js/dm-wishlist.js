define([
    "jquery",
    "uiComponent"
], function ($, Component) {
    'use strict';

    return function (config) {
        $(document).ready(function() {
            if (config.is_logged_in) {
                if (config.guest_email != "") {
                    localStorage.setItem('guestemail', config.guest_email);
                }
            }

            function reloadGuestWishlist(showLoader) {
                var guestEmail = localStorage.getItem('guestemail');

                if (guestEmail === null || guestEmail == "") {
                    return;
                }

                new $.ajax(
                    '/dm/api/guestwishlist/',
                    {
                        method: 'post',
                        data: {
                            email: guestEmail
                        },
                        showLoader: showLoader,
                        complete: function (xhr) {
                            var result = JSON.parse(xhr.responseText);
                            if (result.length) {
                                if (!$('#miniwishlist').length) {
                                    $('.wishlist-content .item-list').html('<ol id="miniwishlist"></ol>');
                                }
                            }

                            for (var i=0;i<result.length;i++) {
                                var item = result[i];
                                var html = '<li class="product-item" rel="' + item.id + '"><div class="product-item-info"><a class="product-item-photo" href="' + item.url + '" title="' + item.name + '"><span class="product-image-container"><span class="product-image-wrapper"><img class="product-image-photo" src="' + item.image + '" alt="' + item.name + '" style="width: 158px; height: 158px;"></span></span></a><div class="product-item-details"><strong class="product-item-name"><a  class="product-item-link" href="' + item.url + '"><span>' + item.name + '</span></a></strong><div><span class="price">' + item.price + '</span></div><div class="product-item-actions"><div class="actions-primary no-display"></div><div class="actions-secondary"><a href="#" data-key="' + item.id + '" title="Remove This Item" class="btn-remove action delete"><span>Remove This Item</span></a></div></div></div></div></li>';
                                $('#miniwishlist').prepend(html);

                                if ($('#guestwishlist').length) {
                                    html = '<li class="product-item grid_3 item" rel="' + item.id + '"><div class="product-item-info"><a class="product-item-photo" href="' + item.url + '" title="' + item.name + '"><span class="product-image-container"><span class="product-image-wrapper"><img class="product-image-photo" src="' + item.image + '" alt="' + item.name + '"></span></span></a><div class="product-item-details"><strong class="product-item-name"><a  class="product-item-link" href="' + item.url + '"><span>' + item.name + '</span></a></strong><div><span class="price">' + item.price + '</span></div><div class="product-item-actions"><div class="actions-secondary"><a href="#" data-key="' + item.id + '" title="Remove This Item" class="btn-remove action delete"><span>Remove This Item</span></a></div><div class="actions-primary"><a href="#" data-key="' + item.id + '" data-uenc="' + item.data.data.uenc + '" data-dm-options="' + item.dm_options + '" class="btn-add-to-cart action"><span>Add To Cart</span></a></div></div></div></div></li>';
                                    $('#guestwishlist').append(html);
                                }
                            }

                            if (result.length) {
                                $('.actions-toolbar').removeClass('no-display');

                                $('.block-wishlist span.counter').html($('#miniwishlist > li').length);                                
                            }
                        }
                    }
                );
            }

            reloadGuestWishlist(false);

            $('body').delegate('.action.show-wishlist', 'click', function () {

                if ($('.action.show-cart').hasClass('active')) {
                    $('.action.show-cart').trigger('click');
                }

                var guestEmail = localStorage.getItem('guestemail');
                if (guestEmail === null) {
                    $('.wishlist-popup').data('from', 'miniwishlist');
                    $('.wishlist-popup').addClass('show');
                    return false;
                } else {
                    $(this).toggleClass('active');
                    $('.wishlist-content').toggleClass('no-display');
                }

                event.stopPropagation();
                
                return false;
            });

            $('.wishlist-popup-content').on('click', function () {
                event.stopPropagation();
            });

            $('.wishlist-popup-close').on('click', function () {
                $('.wishlist-popup').removeClass('show');
            });

            $('.wishlist-popup button.submit').on('click', function () {
                if ($('.wishlist-popup').data('from') == 'miniwishlist') {
                    var guestEmail = $('#wishlist-email').val().trim();

                    if (guestEmail == '') {
                        return false;
                    }

                    localStorage.setItem('guestemail', guestEmail);

                    reloadGuestWishlist(true);

                    $('.wishlist-popup').removeClass('show');
                }
            });            
/*
            $('body').delegate('.miniwishlist-wrapper', 'mouseleave', function () {
                $(this).removeClass('active');
                $('.wishlist-content').addClass('no-display');
            });
*/

            $('body').delegate('.miniwishlist-wrapper a.btn-remove', 'click', function () {

                var key = $(this).data('key');
                var guestEmail = localStorage.getItem('guestemail');

                new $.ajax(
                    '/dm/api/likedislike/',
                    {
                        method:'post',
                        data: {
                            "email": guestEmail,
                            "product_id": key,
                            "liked": false
                        },
                        showLoader: true,
                        complete : function(xhr) {
                            var result = JSON.parse(xhr.responseText);

                            if ($('.like-dislike-buttons-set a.like[data-key=\'' + key + '\']').length) {
                                $('.like-dislike-buttons-set a.like[data-key=\'' + key + '\']').html(result.likes);

                                $('.like-dislike-buttons-set a.like[data-key=\'' + key + '\']').toggleClass('active');
                            }

                            $('li.product-item[rel=\'' + key + '\']').remove();
                            $('.block-wishlist span.counter').html($('#miniwishlist > li').length);

                            if ($('#miniwishlist > li').length == 0) {
                                $('#miniwishlist').parent().html('<strong class="empty subtitle">You have no items in your wish list</strong>');
                                $('.actions-toolbar').addClass('no-display');
                            }
                        }
                    }
                );
                return false;
            });

            $('body').delegate('.miniwishlist-wrapper a.details', 'click', function () {
                location.href = $(this).attr('href');
            });
            $('body').delegate('.miniwishlist-wrapper a.product-item-link', 'click', function () {
                location.href = $(this).attr('href');
            });
            $('body').delegate('.miniwishlist-wrapper a.product-item-photo', 'click', function () {
                location.href = $(this).attr('href');
            });

            $('body').delegate('.wishlist-content .action.close', 'click', function () {
                $('.miniwishlist-wrapper').removeClass('active');
                $('.wishlist-content').addClass('no-display');
            });
        });
    }
});
