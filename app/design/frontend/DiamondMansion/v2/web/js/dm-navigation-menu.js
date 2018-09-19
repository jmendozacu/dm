define([
    'jquery',
    'uiComponent'
], function ($, Component) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();

            $('.header.content').append('<div id="shown-sub-wrapper" class="no-display"></div>');

            $('#nav a.level-top').bind('click', function() {
                if ($(this).parent().hasClass('over')) {
                    $('#shown-sub-wrapper').slideUp('slow', function() {
                        $('#shown-sub-wrapper').html("");
                        $('#shown-sub-wrapper').addClass('no-display');
                        $('#shown-sub-wrapper').css('height', 'auto');
                    });    
                    $(this).parent().removeClass('over');
                } else {
                    var elm = $(this).parent().find('.submenu-wrapper');

                    if ($('#shown-sub-wrapper').hasClass('no-display')) {
                        $('#shown-sub-wrapper').append(elm.clone());
                        $('#shown-sub-wrapper').slideDown('slow', function() {
                            $('#shown-sub-wrapper').removeClass('no-display');
                            $('#shown-sub-wrapper').css('height', $('#shown-sub-wrapper').height());
                        });
                    } else {
                        var oldHeight = $('#shown-sub-wrapper').height();
                        $('#shown-sub-wrapper').html("");
                        $('#shown-sub-wrapper').append(elm.clone());
                        var newHeight = $('#shown-sub-wrapper .submenu-wrapper').height();
                        $('#shown-sub-wrapper').animate({height: newHeight}, 500);
                    }
                            
                    $('#nav a.level-top').parent().removeClass('over');
                    $(this).parent().addClass('over');
                }

                return false;
            });
            

			$('#mobile-menu-open').bind('click', function() {
                if (!$('#mobile-menu-open').hasClass('active')) {
                    $('#mobile-menu-shopby').animate({opacity: 0}, 1000, function () {
                        $('#mobile-menu-shopby, #mobile-menu-shopby > .block').hide();
                        $('#mobile-menu-shopby, #mobile-menu-shopby > .block').css('opacity', 1);
                    });
                    
                    $('.nav-container-mobile').slideDown('fast', function() {
                        $('.nav-container-mobile').animate({height: $(window).height()}, 500, function() {
                            $('.nav-footer-wrapper').show();
                            $('#mobile-menu-open').addClass('active');
                        });
                    });        
                } else {
                    $('.nav-container-mobile').slideUp('fast', function() {
                        $('.nav-container-mobile').height('auto');
                        $('.nav-footer-wrapper').hide();
                        $('#mobile-menu-open').removeClass('active');
                    });        
                }
                return false;
            });
            
            $('ul#nav-mobile a.level-top').bind('click', function() {
                var evtElm = $(this);
                if (!evtElm.hasClass('active')) {
                    evtElm.nextAll('ul').slideDown('medium', function() {
                        evtElm.addClass('active');
                    });
                } else {
                    evtElm.nextAll('ul').slideUp('medium', function() {
                        evtElm.removeClass('active');
                    });
                }
            });
           
            $('ul#nav-mobile li.level1.parent a').bind('click', function() {
                $($(this).attr('href')).show();
                $('#mobile-menu-shopby').show();
                $('#mobile-menu-open').trigger('click');
            });
        }
    });
});
