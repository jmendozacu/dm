/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            'menu': 'js/dm-navigation-menu',
            'dm-ajax': 'js/dm-ajax',
            'dm-qazy': 'js/dm-qazy',
            'dm-wishlist': 'js/dm-wishlist',
            'jquery-fancybox': 'js/jquery.fancybox',
            'supersized': 'js/supersized',
            'responsiveslides': 'js/responsiveslides.min'
        }
    },
    paths: {
        'philanthropy-bootstrap': 'philanthropy/js/bootstrap.min',
        'philanthropy-jquery-easing-1.3': 'philanthropy/js/jquery-easing-1.3',
        'philanthropy-cbpscroller-modernizr-custom': 'philanthropy/js/cbpscroller/modernizr.custom',
        'philanthropy-cbpscroller-classie': 'philanthropy/js/cbpscroller/classie',
        'philanthropy-cbpscroller-cbpScroller': 'philanthropy/js/cbpscroller/cbpScroller',
        'philanthropy-jcarousellite-jcarousellite-1.0.1c4': 'philanthropy/js/jcarousellite/jcarousellite_1.0.1c4',
        'philanthropy-jcarousellite-setting': 'philanthropy/js/jcarousellite/setting',
        'philanthropy-filter-jquery-quicksand': 'philanthropy/js/filter/jquery.quicksand',
        'philanthropy-filter-setting': 'philanthropy/js/filter/setting',
        'philanthropy-flexslider-jquery-flexslider': 'philanthropy/js/flexslider/jquery.flexslider',
        'philanthropy-flexslider-setting': 'philanthropy/js/flexslider/setting',
        'philanthropy-validation': 'philanthropy/js/validation',
        'philanthropy-totop-jquery-ui-totop': 'philanthropy/js/totop/jquery.ui.totop',
        'philanthropy-totop-setting': 'philanthropy/js/totop/setting',
        'philanthropy-custom': 'philanthropy/js/custom',

        'wedding-modernizer': 'wedding/js/vendor/custom.modernizr',
        'wedding-foundation': 'wedding/js/foundation.min',
        'wedding-jquery-backstretch': 'wedding/js/vendor/jquery.backstretch.min',
        'wedding-jquery-scrollto': 'wedding/js/vendor/jquery.scrollTo-1.4.3-min',
        'wedding-jquery-nav': 'wedding/js/vendor/jquery.nav.min',
        'wedding-jquery-flexslider': 'wedding/js/vendor/jquery.flexslider.min',
        'wedding-jquery-nouislider': 'wedding/js/vendor/jquery.nouislider.min',
        'wedding-custom': 'wedding/js/custom',

        'slick': 'js/slick.min',
        'scrollmagic': 'js/scrollmagic.min',
        'modernizr': 'js/modernizr.min',

        'proposal-guide-preloader': 'proposal-guide/js/preloader/jquery.queryloader2',
        'proposal-guide-bootstrap': 'proposal-guide/js/bootstrap/bootstrap',
        'proposal-guide-easing': 'proposal-guide/js/supersized/jquery.easing.min',
        'proposal-guide-supersized': 'proposal-guide/js/supersized/supersized.3.2.7.min',
        'proposal-guide-shutter': 'proposal-guide/js/supersized/supersized.shutter.min',
        'proposal-guide-sticky': 'proposal-guide/js/sticky/jquery.sticky',
        'proposal-guide-nav': 'proposal-guide/js/navjs/jquery.nav',
        'proposal-guide-nicescroll': 'proposal-guide/js/nicescroll/jquery.nicescroll.min',
        'proposal-guide-waypoints': 'proposal-guide/js/waypoint/waypoints.min',
        'proposal-guide-gmap': 'proposal-guide/js/gmap/jquery.gmap.min',
        'proposal-guide-popup': 'proposal-guide/js/magnific-popup/jquery.magnific-popup.min',
        'proposal-guide-masonry': 'proposal-guide/js/masonry/masonry.pkgd.min',
        'proposal-guide-owlcarousel': 'proposal-guide/js/owlcarousel/owl.carousel',
        'proposal-guide-rsvp': 'proposal-guide/js/rsvp',
        'proposal-guide-swithcer': 'proposal-guide/js/style-swithcer/stylesheet-switcher',
        'proposal-guide-script': 'proposal-guide/js/script'
    },
    shim: {
        'philanthropy-bootstrap': {
            deps: ['jquery']
        },
        'philanthropy-jquery-easing-1.3': {
            deps: ['jquery']
        },
        'philanthropy-cbpscroller-modernizr-custom': {
            deps: ['jquery']
        },
        'philanthropy-jcarousellite-jcarousellite-1.0.1c4': {
            deps: ['jquery']
        },
        'philanthropy-jcarousellite-setting': {
            deps: ['jquery', 'philanthropy-jcarousellite-jcarousellite-1.0.1c4']
        },
        'philanthropy-filter-jquery-quicksand': {
            deps: ['jquery']
        },
        'philanthropy-filter-setting': {
            deps: ['jquery', 'philanthropy-filter-jquery-quicksand']
        },
        'philanthropy-flexslider-jquery-flexslider': {
            deps: ['jquery']
        },
        'philanthropy-flexslider-setting': {
            deps: ['jquery', 'philanthropy-flexslider-jquery-flexslider']
        },
        'philanthropy-validation': {
            deps: ['jquery']
        },
        'philanthropy-totop-jquery-ui-totop': {
            deps: ['jquery']
        },
        'philanthropy-totop-setting': {
            deps: ['jquery', 'philanthropy-totop-jquery-ui-totop']
        },
        'philanthropy-custom': {
            deps: ['jquery']
        },

        'wedding-modernizer': {
            deps: ['jquery']
        },
        'wedding-foundation': {
            deps: ['jquery']
        },
        'wedding-jquery-backstretch': {
            deps: ['jquery']
        },
        'wedding-jquery-scrollto': {
            deps: ['jquery']
        },
        'wedding-jquery-nav': {
            deps: ['jquery']
        },
        'wedding-jquery-flexslider': {
            deps: ['jquery']
        },
        'wedding-jquery-nouislider': {
            deps: ['jquery']
        },
        'wedding-custom': {
            deps: ['jquery', 'wedding-foundation']
        },

        'slick': {
            deps: ['jquery']
        },
        'scrollmagic': {
            deps: ['jquery']
        },
        'modernizr': {
            deps: []
        },

        'proposal-guide-preloader': {
            deps: ['jquery']
        },
        'proposal-guide-bootstrap': {
            deps: ['jquery']
        },
        'proposal-guide-easing': {
            deps: ['jquery']
        },
        'proposal-guide-supersized': {
            deps: ['jquery']
        },
        'proposal-guide-shutter': {
            deps: ['jquery']
        },
        'proposal-guide-sticky': {
            deps: ['jquery']
        },
        'proposal-guide-nav': {
            deps: ['jquery']
        },
        'proposal-guide-nicescroll': {
            deps: ['jquery']
        },
        'proposal-guide-waypoints': {
            deps: ['jquery']
        },
        'proposal-guide-gmap': {
            deps: ['jquery']
        },
        'proposal-guide-popup': {
            deps: ['jquery']
        },
        'proposal-guide-masonry': {
            deps: ['jquery']
        },
        'proposal-guide-owlcarousel': {
            deps: ['jquery']
        },
        'proposal-guide-rsvp': {
            deps: ['jquery']
        },
        'proposal-guide-swithcer': {
            deps: ['jquery']
        },
        'proposal-guide-script': {
            deps: ['jquery']
        }
    }
};
