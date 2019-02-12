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
                    $('.nav-container-mobile').slideDown('fast', function() {
                            $('#mobile-menu-open').addClass('active');
                            $('.page-header').addClass('active');
                    });        
                } else {
                    $('.nav-container-mobile').slideUp('fast', function() {
                        $('#mobile-menu-open').removeClass('active');
                        $('.page-header').removeClass('active');
                    });        
                }
                return false;
            });
            
            $('ul#nav-mobile a.level-top').on('click', function() {
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
        }
    });
});
