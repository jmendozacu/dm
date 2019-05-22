define([
    "jquery"
], function ($) {
    'use strict';

    return function (config) {
        var isStoppedLoad = config.isStoppedLoad;
        var isLast = false;
        var currentUrl;
        var page;

        $(document).ready(function() {
            if (!$('.toolbar .pages').length) {
                isLast = true;
                $('#view-more-products-wrapper').hide();
            }

            $('body').delegate('#view-more-products-wrapper', 'click', onViewMoreProducts);
            $('body').delegate('ul.products-grid a', 'click', function () {
                currentUrl = $('#view-more-products-wrapper').data('current-url');
                var url = $(this).attr('href');
                var productId = $(this).parents("li.item:first").data('id');

                $.ajax({
                    url: '/dm/api/savevieweditem',
                    method: "POST",
                    data: {
                        'id': productId,
                        'url': currentUrl
                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (xhr) {
                        location.href = url;
                    }
                });

                return false;
            });
    
            $(window).scroll(function () {
                if (isStoppedLoad === true && (($(document).height() - $(this).scrollTop()) < ($('ul.products-grid li.item').height() * 9))) {
                    onViewMoreProducts();
                }
            });

            onViewMoreProducts();
        });
    
        $(window).load(function () {
            var viewedItemIdentifier = config.viewedItemIdentifier;
            if (viewedItemIdentifier && $('li#item-' + viewedItemIdentifier).length) {
                $(window).scrollTop($('li#item-' + viewedItemIdentifier).offset().top - $(window).height() / 2 + $('li#' + viewedItemIdentifier).height() / 2);
            } else {
                $(window).scrollTop(0);
            }
        });
    
        function onViewMoreProducts() {
            if (!$('.toolbar .pages').length) {
                return;
            }

            currentUrl = $('#view-more-products-wrapper').data('current-url');
            page = $('#view-more-products-wrapper').data('current-page');

            var nextUrl = currentUrl;
            var startPos = currentUrl.indexOf("p=");
            if (startPos > -1) {
                var endPos = currentUrl.indexOf("&", startPos);
                if (endPos > -1) {
                    var pParam = currentUrl.substr(startPos, endPos - startPos);
                } else {
                    var pParam = currentUrl.substr(startPos);
                }
    
                nextUrl = currentUrl.replace(pParam, "p=" + (page + 1));
            } else {
                if (nextUrl.indexOf("?") > -1) {
                    nextUrl = nextUrl + "&p=" + (page + 1);
                } else {
                    nextUrl = nextUrl + "?p=" + (page + 1);
                }
            }

            $.ajax({
                url: nextUrl,
                method: "GET",
                beforeSend: function () {
                    $("#view-more-products").hide();
                    $("#view-more-products-loading").removeClass("no-display");
    
                    isStoppedLoad = false;
                },
                complete: function () {
                    if (isLast) {
                        $("#view-more-products-loading").addClass("no-display");
                        $("#view-more-products").show();
                    }
                },
                success: function (xhr) {
                    var elms = $(xhr).find('ul.products-grid > li.grid_4');
                    isLast = ($(xhr).find('.toolbar .pages .pages-item-next').length <= 0);
    
                    if (elms.length) {
                        elms.css("visibility", "hidden");
                        $("ul.products-grid").append(elms);
    
                        $("ul.products-grid").imagesLoaded(function() {
                            elms.css("visibility", "visible");
                            if ($("ul.products-grid").data('isotope')) {
                                $("ul.products-grid").isotope('appended', elms);
                            }
                        });
    
                        if (!isLast) {
                            page++;
                            $('#view-more-products-wrapper').data('current-page', page);
                            if (($(document).height() - $(window).scrollTop()) < ($('ul.products-grid li.item').height() * 9)) {
                                onViewMoreProducts();
                            } else {
                                isStoppedLoad = true;
                            }
                        }
                    }
    
                    if (isLast) {
                        $("#view-more-products").parent().hide();
                    }
                }
            });
        }        
    }
});
