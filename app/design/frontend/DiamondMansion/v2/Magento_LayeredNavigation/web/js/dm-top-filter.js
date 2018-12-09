define([
    'jquery',
    'uiComponent',
    'dm-ajax'
], function ($, Component, $http) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();

            var widget = this;

            var screenSizeBorder = 1279;
            var topFilterBarOffsetTop = $("#top-filter-bar").offset().top;
    
            $(window).scroll(function () {
                if (topFilterBarOffsetTop > $(window).scrollTop()) {
                    $("#top-filter-bar").removeClass('fixed');
                    $("#top-filter-bar").css('width', 'auto');
                } else {
                    $("#top-filter-bar").width($("#top-filter-bar").width());
                    $("#top-filter-bar").addClass('fixed');
                }
            });

            $('#top-filter-bar .content a, a.filter-anchor.see-all').on('click', function () {
                var url = $(this).attr('href');
                if (url == '#' || url == 'javascript:void(0)') {
                    return;
                }

                $http.get(url, {}, function (xhr) {
                    var _pageTitle = "";
                    $(xhr).each(function (index, elm) {
                        if (elm.className == 'page-wrapper') {
                            $(elm.innerHTML).each(function (index, subElm) {
                                if (subElm.className == 'breadcrumbs') {
                                    $('.breadcrumbs').html(subElm.innerHTML);
                                }
            
                                if (subElm.className == 'page-main') {
                                    $('.page-main').html(subElm.innerHTML);
                                }
                            });
                        }
                        
                        if (elm.tagName == 'TITLE') {
                            _pageTitle = elm.innerHTML;
                        }
                    }); 
                    document.title = _pageTitle;
                    window.history.replaceState({}, _pageTitle, url);
                    //initProductListPageTitle();
                    $('.filter-label').each(function (index, elm) {
                        $(this).css('width', $(this).width());
                    });

                    $('#view-more-products-wrapper').data('current-url', url);
                    $('#view-more-products-wrapper').data('current-page', 1);
    
                    if (!$('.toolbar .pages .pages-item-next').length) {
                        $('#view-more-products-wrapper').hide();
                    } else {
                        $('#view-more-products-wrapper').show();
                    }

                    widget.initialize();
    
                    $(window).scrollTop(0);
                });
    
                return false;
            });

            var topFilterInitialHeight;
            var topFilterInitialPadding;
    
            var initTopFilter = function () {
    
                $('.top-filters ul a.filter-anchor.active').removeClass('active');
                $('.top-filters ul .content').hide();
    
                $('.filter-label').each(function (index, elm) {
                    $(this).css('width', 'auto');
                    $(this).css('width', $(this).width());
                });
    
                $('.top-filters').css('height', 'auto');
                topFilterInitialHeight = $('.top-filters ul').height();
    
                if ($(window).width() <= screenSizeBorder) {
                    $('.top-filters ul a.filter-anchor').parent().hide();
                    $('#filter-title').removeClass('expanded');
                    $('#filter-title').addClass('collapsed');
                } else {
                    $('.top-filters ul a.filter-anchor').parent().show();
                }
    
                topFilterInitialPadding = ($(window).width() <= screenSizeBorder)?40:40;
            };
    
            initTopFilter();
    
            var resizeTimeout;
            $(window).resize(function () {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function () {
                    initTopFilter();
                }, 100);
            });
    
            $('#filter-title').on('click', function () {
                if ($(window).width() > screenSizeBorder) {
                    return false;
                }
                
                if ($(this).hasClass('expanded')) {
                    $('#filter-title').removeClass('expanded');
                    $('#filter-title').addClass('collapsed');
                    $('.top-filters').animate({
                        height: $(this).height() + 40
                    }, function () {
                        $('.top-filters ul a.filter-anchor').parent().hide();
                    });
                } else {
                    $('#filter-title').removeClass('collapsed');
                    $('#filter-title').addClass('expanded');
                    $('.top-filters ul a.filter-anchor').parent().show();
                    $('.top-filters').animate({
                        height: $('.top-filters ul').height()
                    });
                }
    
            });
    
            $('a.filter-anchor').on('click', function () {
                if ($(this).hasClass('see-all')) {
                    return false;
                }
    
                var isToggle = $(this).hasClass('active');
    
                var oldElm = false;
                var oldHeight = 0;
    
                if ($('a.filter-anchor.active').length) {
                    oldElm = $('a.filter-anchor.active').parent().find('.content');
                    oldHeight = oldElm.height();
    
                    oldElm.hide();
                    $('a.filter-anchor.active').removeClass('active');
                }
    
                if (isToggle) {
                    newHeight = topFilterInitialHeight;
                } else {
                    var newElm = $(this).parent().find('.content');
                    var newHeight = newElm.height() + topFilterInitialPadding + topFilterInitialHeight;
    
                    newElm.show();
                    $(this).addClass('active');
                }
    
                $('.top-filters').animate({
                    height: newHeight
                }, 100);
            });
    
            $('.top-filters').on('mouseleave', function () {
    
                if ($(window).width() <= screenSizeBorder) {
                    return false;
                }
    
                if ($('a.filter-anchor.active').length) {
                    var oldElm = $('a.filter-anchor.active').parent().find('.content');
                    oldElm.hide();
                    $('a.filter-anchor.active').removeClass('active');
                }
    
                $('.top-filters').animate({
                    height: topFilterInitialHeight
                });			
            });
    
            $("#filter-design-collection ol li a").each(function(index, value) {
                if ($(this).html() == "Top25") {
                    $(this).html("Top 25");
                }
            });
        }
    });
});
