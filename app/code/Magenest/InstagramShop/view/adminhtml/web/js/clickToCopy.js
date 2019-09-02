require([
    'jquery',
    'jquery/ui',
    'domReady!',
    'mage/translate'
], function ($) {
    'use strict';

    function removeTooltip(element) {
        element.css('position', '');
        $('.instagram-tooltip').remove();
    }

    function addTooltip(toolTipText, element) {
        element.attr('data-title', toolTipText);
        element.parent().append("<div class='instagram-tooltip'>" + toolTipText + "</div>");
        element.parent().css('position', 'relative');
    }

    $('.readonly-field')
        .click(function () {
            var toolTipText = $.mage.__('Copied!');

            $(this).select();
            removeTooltip($(this));
            addTooltip(toolTipText, $(this));
            setTimeout(function () {

                removeTooltip($(this));
            }, 850);
            document.execCommand("copy");
        })
        .hover(function () {
                var toolTipText = $.mage.__('Click to copy.');
                addTooltip(toolTipText, $(this));
            }
            , function () {
                removeTooltip($(this));
            }
        );

});

