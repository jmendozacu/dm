define([
    'jquery',
    'uiComponent'
], function ($, Component) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();

            $('.page-header').append('<div id="shown-sub-wrapper" class="no-display"></div>');

            $('#nav a.level-top').on('click', function() {
                $('.block-search .block-content').hide();
                $('.block-search').removeClass('over');

                $('.customer-link .customer-sub-links').hide();
                $('.customer-link').removeClass('over');

                if ($(this).parent().hasClass('over')) {
                    $('#shown-sub-wrapper').slideUp('fast', function() {
                        $('#shown-sub-wrapper').html("");
                        $('#shown-sub-wrapper').addClass('no-display');
                        $('#shown-sub-wrapper').css('height', 'auto');
                        $('#shown-sub-wrapper').css('paddingLeft', 0);
                    });    
                    $(this).parent().removeClass('over');

                    $('.page-header').removeClass('active');
                } else {
                    var elm = $(this).parent().find('.submenu-wrapper');

                    if ($('#shown-sub-wrapper').hasClass('no-display')) {
                        $('#shown-sub-wrapper').append(elm.clone());
                        $('#shown-sub-wrapper').slideDown('fast', function() {
                            $('#shown-sub-wrapper').removeClass('no-display');
                            $('#shown-sub-wrapper').css('height', $('#shown-sub-wrapper').height());
                        });
                    } else {
                        $('#shown-sub-wrapper').html("");
                        $('#shown-sub-wrapper').append(elm.clone());
                        var newHeight = $('#shown-sub-wrapper .submenu-wrapper').outerHeight();
                        $('#shown-sub-wrapper').animate({height: newHeight}, 200);
                    }

                    $('#shown-sub-wrapper').css('paddingLeft', $(this).offset().left);

                    $('#nav a.level-top').parent().removeClass('over');
                    $(this).parent().addClass('over');

                    $('.page-header').addClass('active');
                }

                return false;
            });
            

			$('#mobile-menu-open').on('click', function() {
                if (!$('#mobile-menu-open').hasClass('active')) {
                    $('.page-header').addClass('active');
                    $('.nav-mobile-container').css('minHeight', $(window).height());
                    $('.nav-mobile-container').fadeIn(100, function() {
                        $('#mobile-menu-open').addClass('active');
                        $('.page-main, .page-footer').hide();
                    });
                } else {
                    $('.nav-mobile-container').css('minHeight', 'auto');
                    $('.nav-mobile-container').fadeOut(100, function() {
                        $('#mobile-menu-open').removeClass('active');
                        $('.page-header').removeClass('active');
                        $('.page-main, .page-footer').show();
                    });
                }
                return false;
            });
            
            $('ul#nav-mobile a.level-top').on('click', function() {
                var evtElm = $(this);
                if (!evtElm.hasClass('active')) {
                    $('ul#nav-mobile a.level-top.active').nextAll('ul').hide();
                    $('ul#nav-mobile a.level-top.active').removeClass('active');
                    evtElm.nextAll('ul').fadeIn(100, function() {
                        evtElm.addClass('active');
                    });
                } else {
                    evtElm.nextAll('ul').fadeOut(100, function() {
                        evtElm.removeClass('active');
                    });
                }

                return false;
            });
        }
    });
});
