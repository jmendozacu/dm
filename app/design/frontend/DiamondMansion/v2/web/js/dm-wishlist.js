define([
    "jquery",
    "uiComponent"
], function ($, Component) {
    'use strict';

    return function (config) {
        $(document).ready(function() {
            $('body').delegate('.miniwishlist-wrapper', 'click', function () {

                if ($('.action.show-cart').hasClass('active')) {
                    $('.action.show-cart').trigger('click');
                }

                $(this).toggleClass('active');
                $('.wishlist-content').toggleClass('no-display');

                event.stopPropagation();
                
                return false;
            });
/*
            $('body').delegate('.miniwishlist-wrapper', 'mouseleave', function () {
                $(this).removeClass('active');
                $('.wishlist-content').addClass('no-display');
            });
*/
            if (config.is_logged_in) {
                var wishlist = JSON.parse(localStorage.getItem('guestwishlist'));
                for (var key in wishlist) {
                    var post = wishlist[key].data;
                    post.data['dm_options'] = wishlist[key].dm_options;
                    post.data['form_key'] = $('input[name=\'form_key\']').val();
                    new $.ajax(
                        post.action,
                        {
                            method:'post',
                            asynchronous: false,
                            data: post.data,
                            showLoader: true,
                            complete : function(xhr) {
                                delete wishlist[key];
                            }
                        }
                    );
                }
                localStorage.removeItem('guestwishlist');
            } else {
                var wishlist = JSON.parse(localStorage.getItem('guestwishlist'));
                for (var key in wishlist) {
                    var html = '<li class="product-item" rel="' + key + '"><div class="product-item-info"><a class="product-item-photo" href="' + wishlist[key].url + '" title="' + wishlist[key].name + '"><span class="product-image-container"><span class="product-image-wrapper"><img class="product-image-photo" src="' + wishlist[key].image + '" alt="' + wishlist[key].name + '" style="width: 75px; height: 90px;"></span></span></a><div class="product-item-details"><strong class="product-item-name"><a  class="product-item-link" href="' + wishlist[key].url + '"><span>' + wishlist[key].name + '</span></a></strong><div><span class="price">' + wishlist[key].price + '</span></div><div class="product-item-actions"><div class="actions-primary no-display"></div><div class="actions-secondary"><a href="#" data-key="' + key + '" title="Remove This Item" class="btn-remove action delete"><span>Remove This Item</span></a></div></div></div></div></li>';

                    if (!$('#miniwishlist').length) {
                        $('.wishlist-content .item-list').html('<ol id="miniwishlist"></ol>');
                    }

                    $('#miniwishlist').prepend(html);
                    $('.wishlist-content .actions-toolbar').removeClass('no-display');
                }
                if ($('.block-wishlist span.counter').length) {
                    $('.block-wishlist span.counter').html('(' + $('#miniwishlist > li').length + ')');
                } else {
                    $('.block-wishlist block-title').append('<span class="counter">(' + $('#miniwishlist > li').length + ')</span>');
                }

                $('body').delegate('.miniwishlist-wrapper a.btn-remove', 'click', function () {
                    var key = $(this).data('key');
                    var wishlist = JSON.parse(localStorage.getItem('guestwishlist'));
                    delete wishlist[key];
                    localStorage.setItem('guestwishlist', JSON.stringify(wishlist));
                    $('li.product-item[rel=\'' + key + '\']').remove();
                    $('.block-wishlist span.counter').html('(' + $('#miniwishlist > li').length + ')');
                    return false;
                });    

                $('body').delegate('.miniwishlist-wrapper a.details', 'click', function () {
                    location.href = $(this).attr('href');
                });
            }
        });
    
        $(window).click(function () {
            $('.miniwishlist-wrapper').removeClass('active');
            $('.wishlist-content').addClass('no-display');
        });
    }
});
