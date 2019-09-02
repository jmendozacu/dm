require([
    'jquery',
    'domReady!'
], function ($) {
    $('img.zoomable').css({width: '150px', height: '150px'}).on('click', function () {

        var img = $(this);
        var bigImg = $('<img />').css({
            'max-width': '100%',
            'max-height': '100%',
            'display': 'inline',
            'position': 'fixed',
            'top': '50%',
            'left': '50%',
            'transform': 'translate(-50%,-50%)'
        });
        bigImg.attr({
            src: img.attr('src'),
            alt: img.attr('alt')
        });
        $('<div />').text(' ').css({
            'height': '100%',
            'width': '100%',
            'background': 'rgba(0,0,0,.82)',
            'position': 'fixed',
            'top': 0,
            'left': 0,
            'opacity': 0.0,
            'cursor': 'pointer',
            'z-index': 9999,
            'text-align': 'center'
        }).append(bigImg).bind('click', function () {
            $(this).fadeOut(300, function () {
                $(this).remove();
            });
        }).insertAfter(this).animate({
            'opacity': 1
        }, 300);
    });
});