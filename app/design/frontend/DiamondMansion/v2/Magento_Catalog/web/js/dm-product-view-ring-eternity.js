define([
    "jquery",
    "cloudzoom",
    "responsiveslides"
], function ($) {
    'use strict';

    return function (config) {

        function initialize() {

            $('.type-list li').on('click', function() {
                onTypeChange($(this)); return false;
            });
            
            $('.shape-list li').on('click', function() {
                onShapeChange($(this)); return false;
            });
            
            $('.carat-list li').on('click', function() {
                onCaratChange($(this)); return false;
            });
            
            $('.color-clarity-list li').on('click', function() {
                onColorClarityChange($(this)); return false;
            });

            $('.metal-list li').on('click', function() {
                onMetalChange($(this)); return false;
            });
            
            $('.ring-size-list2 li a').on('click', function() {
                onRingSizeChange($(this)); return false;
            });

            $('input.order-type').on('click', function() {
                config.order_type = $(this).data('code');
                updateUrl();
            });

            config.stone['qty'] = config.stone['qtys'][config.stone['shape']][config.stone['carat'] + '-' + config.ring_size];
            config.stone['price'] = config.stone['unitprice'][config.stone['shape'] + '-' + config.stone['carat'] + '-' + config.stone['color-clarity']] * config.stone['qty'];
            config.metal['price'] = $('.metal-list li.selected').data('price');

            updateTitle();
        }

        $(document).ready(function() {
            initialize();

            $('.more-views-holder').addClass('product-video-box desktop');

            $('#which-should-i-choose h4').bind('click', function() {
                var arrowElm = $(this).find('span.arrow');
                if (arrowElm.hasClass('hide')) {
                    $('#which-should-i-choose .content').slideUp('fast', function() {
                        arrowElm.removeClass('hide');
                        arrowElm.addClass('show');
                    });
                } else {
                    $('#which-should-i-choose .content').slideDown('fast', function() {
                        arrowElm.removeClass('show');
                        arrowElm.addClass('hide');
                    });
                }
            });

            $('img.product-image-current').attr('src', config.default_image);
            
            var tmp = location.href.indexOf(config.urlKey);
            if (tmp != -1) { 
                $("input.productlink").val('/' + location.href.slice(tmp));
            }

            $("a.btn-buy").on("click", function () {
                $('#form_add_product').submit();
                return false;
            });

            $("a.btn-wishlist").on('click', function () {
                var params = $(this).data('post');
                params.data = $.extend({}, params.data, {"dm_options": $('input.dm_custom_options').val()});
                $(this).data('post', params);
            });

            $('#quality-list li.item-f-g-vs label').html($('#item-f-g-vs').html()).addClass('quality-list-item');
            $('#quality-list li.item-g-h-si label').html($('#item-g-h-si').html()).addClass('quality-list-item');
            
            displayPrice();
            
            $('#eternity-ring-custom-options').insertBefore($('#product-options-wrapper'));
            $('#eternity-ring-summary').insertBefore($('#product-options-wrapper'));
            $('#eternity-ring-summary').removeClass('no-display');
            
            $('a.arrow').bind('click', function() {
                if ($(this).hasClass('hide')) {
                    var height = $(this).parent().height() + 10;
                    $(this).parent().parent().animate({height: height});
                    $(this).removeClass('hide');
                    $(this).addClass('show');
                } else {
                    var height = $(this).parent().parent().height() + $(this).parent().parent().find('ul').height() + 10;
                    $('a.arrow.hide').trigger('click');
                    $(this).parent().parent().animate({height: height});
                    $(this).removeClass('show');
                    $(this).addClass('hide');
                }
                return false;
            });
            
            rearrangeOptionsList();
            
            $('.custom-options-tabs, .custom-options-lists').removeClass('no-display');                            
            
            $(window).resize(function() {
                rearrangeOptionsList();
                refreshMobileGallery();
            });
            
            $('.custom-options-tab > a').click(function() {
                if ($(window).width() > 767 && $(window).width() <= 1024) {
                    $(this).next().toggle('fast');
                    return false;
                }
            });
    
            $('.arrows.mobile a.arrow-left').bind('click', function() {
                var listElm = $(this).parent().parent().parent().find('ul');
                var listLen = listElm.find('li').length;
                var currLi = listElm.find('li.selected');
                var newLi;
                if (listElm.find('li').index(currLi) > 0) {
                    newLi = currLi.prev();                                    
                    if (listElm.find('li').index(newLi) == 0) {
                        $(this).addClass('disabled');
                    }
                    if (listElm.find('li').index(newLi) < listLen - 1) {
                        $(this).next().removeClass('disabled');
                    }
                    newLi.find('label').trigger('click');
                } else {
                    if (listElm.find('li').index(currLi) == 0) {
                        $(this).addClass('disabled');
                    }
                }
                return false;
            });
            
            $('.arrows.mobile a.arrow-right').bind('click', function() {
                var listElm = $(this).parent().parent().parent().find('ul');
                var listLen = listElm.find('li').length;
                var currLi = listElm.find('li.selected');
                var newLi;
                if (listElm.find('li').index(currLi) < listLen - 1) {
                    newLi = currLi.next();                                    
                    if (listElm.find('li').index(newLi) == listLen - 1) {
                        $(this).addClass('disabled');
                    }
                    if (listElm.find('li').index(newLi) > 0) {
                        $(this).prev().removeClass('disabled');
                    }
                    newLi.find('label').trigger('click');
                } else {
                    if (listElm.find('li').index(currLi) == listLen - 1) {
                        $(this).addClass('disabled');
                    }
                }
                return false;
            });

            refreshMobileGallery();
            jQuery('.mobile.price-box').css('opacity', 1);
            
            jQuery("#product-img-slider").bind('swiperight', function(e) {
                if (jQuery('.transparent-btns_here').prev())
                    jQuery('.transparent-btns_here').prev().find('a').trigger('click');
            })
            .bind('swipeleft', function(e) {
                if (jQuery('.transparent-btns_here').next())
                    jQuery('.transparent-btns_here').next().find('a').trigger('click');
            });                        

            config.isLoaded = true;
            $(".product-view").slideDown("slow");
        });

        function rearrangeOptionsList() {
            if ($(window).width() > 767) {
                $('#metal-tab').parent().append($('ul.options-list.metal-list'));
                $('#center-diamond-tab').parent().append($('ul.options-list.type-list'));
                $('a.arrow').parent().parent().css('height', 'auto');
                $('a.arrow').removeClass('hide');
                $('a.arrow').addClass('show');
            } else {
                $('#metal-list').append($('ul.options-list.metal-list'));
                $('.custom-options-list.type-list.mobile').append($('ul.options-list.type-list'));
                $('a.arrow').each(function(index, value) {
                    $(this).parent().parent().height(30);
                });
            }                            
        }

        function refreshMobileGallery() {
            var html = "";
            jQuery('.product-video-box ul li').each(function(index, value) {
                html += '<li><img src="'+jQuery(this).find('a.cloud-zoom-gallery').attr('href')+'" alt="" /></li>';
            });
            jQuery('.product-video-box a.video').each(function(index, value) {
                html += '<li><iframe src="'+jQuery(this).attr('href').replace('autoplay=1', 'autoplay=0')+'"/></li>';
            });
            
            jQuery('#product-img-slider ul.transparent-btns_tabs').remove();
            jQuery('#product-img-slider ul.rslides').html(html);
            jQuery('#product-img-slider ul.rslides iframe').each(function(index, value) {
                jQuery(this).height(jQuery(this).parent().width() * 3 / 4);
                jQuery(this).width(jQuery(this).parent().width());
            });

            jQuery(".rslides").responsiveSlides({
                auto: false,             // Boolean: Animate automatically, true or false
                speed: 500,            // Integer: Speed of the transition, in milliseconds
                timeout: 4000,          // Integer: Time between slide transitions, in milliseconds
                pager: true,           // Boolean: Show pager, true or false
                nav: false,             // Boolean: Show navigation, true or false
                random: false,          // Boolean: Randomize the order of the slides, true or false
                pause: false,           // Boolean: Pause on hover, true or false
                pauseControls: false,    // Boolean: Pause when hovering controls, true or false
                prevText: "Previous",   // String: Text for the "previous" button
                nextText: "Next",       // String: Text for the "next" button
                maxwidth: "",           // Integer: Max-width of the slideshow, in pixels
                navContainer: "",       // Selector: Where controls should be appended to, default is after the 'ul'
                manualControls: "",     // Selector: Declare custom pager navigation
                namespace: "transparent-btns",   // String: Change the default namespace used
                before: function(){},   // Function: Before callback
                after: function(){}     // Function: After callback
            });

            jQuery('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
        }

        function onTypeChange() {
            jQuery(".type-list li.selected").removeClass("selected");
            elm.addClass("selected");
            jQuery('#center-diamond-tab sub, .product-summary #stone-type').html(elm.find('span.label label').html().replace('Natural', '100% Natural'));
            jQuery('.custom-options-list.type-list.mobile label.list-title a.arrow').html(elm.find('span.label label').html().replace('Colorless', 'Natural'));
            
            dependentOptions.select(elm.find('input:radio').get(0));
            
            jQuery('.eternityshapes-list input:radio').each(function(index, value) {
                if (jQuery(this).is(':disabled')) {
                    jQuery(this).parent().removeClass('enabled');
                    jQuery(this).parent().addClass('disabled');
                } else {
                    jQuery(this).parent().removeClass('disabled');
                    jQuery(this).parent().addClass('enabled');
                }
            });
            
            if (jQuery(".eternityshapes-list li.selected").hasClass('disabled')) {
                onShapeChange(jQuery(".eternityshapes-list li.enabled").first());
            }
            
            config.stone["type"] = elm.attr("rel");
            updateImage();
            updateStonePrice();
            changeUrl();    
        }

        function onShapeChange(elm) {
            jQuery(".shape-list li.selected").removeClass("selected");
            elm.addClass("selected");
            
            jQuery('.custom-options-list.shape-list label.list-title a.arrow').html(elm.find('span.label label').html());
            
            config.stone["shape"] = elm.attr("rel");
            updateImage();
            updateStonePrice();
            updateUrl();
        }

        function onCaratChange(elm) {
            if (jQuery(".carat-list li.selected").length) {
                jQuery(".carat-list li.selected span.label label").html(jQuery(".carat-list li.selected").data('carat'));
                jQuery(".carat-list li.selected").removeClass("selected");
            }
            elm.addClass("selected");

            config.stone["carat"] = parseFloat(elm.attr("rel"));
            elm.html('<span class="label"><label><span>' + elm.data('carat') + '</span><span>' + elm.attr("rel") + ' ct. each</span><span>WIDTH:' + config.stone["widths"][config.stone['shape']][config.stone['carat']] + '<sub>MM</sub></span></label></span>');
            config.stone['qty'] = parseFloat(config.stone['qtys'][config.stone['shape']][config.stone['carat'] + '-' + config.ring_size]);
        
            updateImage();
            updateStonePrice();
            updateUrl();
        }

        function onColorClarityChange(elm) {
            jQuery(".color-clarity-list li.selected").removeClass("selected");
            
            elm.addClass("selected");      
            jQuery('.product-summary #quality').html(elm.attr("rel"));
            if (elm.find('span.item-title').length) {
                jQuery('.custom-options-list.quality-list label.list-title a.arrow').html(elm.find('span.item-title').html());            
            } else {
                jQuery('.custom-options-list.quality-list label.list-title a.arrow').html(elm.find('span.label label').html());
            }
            
            config.stone["color-clarity"] = elm.attr("rel");
            updateStonePrice();
            updateUrl();
        }    

        function onMetalChange(elm) {
            jQuery(".metal-list li.selected").removeClass("selected");
            elm.addClass("selected");
            jQuery('#metal-tab sub, .product-summary .metal').html(elm.find('span.label label').html());
            jQuery('.custom-options-list.metal-list label.list-title a.arrow').html(elm.find('span.label label').html());
            
            config.metal['type'] = elm.attr("rel");

            updateImage();
            
            var newPrice = elm.data('price');
    
            config.current_price = parseFloat(config.current_price) - parseFloat(config.metal['price']) + parseFloat(newPrice);
            config.metal['price'] = newPrice;
            
            displayPrice();
            updateUrl();
        }
    
        function onRingSizeChange(elm) {
            ring_size = elm.attr('rel');
            jQuery('.ring-size-list2 a.active').removeClass('active');
            jQuery('a#ring-size-'+ring_size).addClass('active');
            
            jQuery('#ring-size-list label.list-title a.arrow').html(ring_size);
            
            jQuery('#rign-size-tab sub').html(ring_size);

            config.ring_size = ring_size;
            updateStonePrice();
            updateUrl();
        }
        
        function displayPrice() {
            if (!isNaN(config.current_price)) {
                var price = '$'+parseFloat(Math.round(config.current_price / 10) * 10);
                var downPrice = '$'+parseFloat(Math.round(config.current_price / 10));
                var appraisedPrice = '$'+parseFloat(Math.round(config.current_price * 1.8 / 10) * 10);
                $(".price-box span.price, #order-type-order-now").html(price);
                
                $(".color-clarity-list li").each(function(index, value) {
                    var colorclarity = $(this).attr('rel');
                    var key = config.stone['shape'] + '-' + config.stone['carat'] + '-' + colorclarity;
                    var unitprice = isNaN(config.stone['unitprice'][key]) ? 0 : parseFloat(config.stone['unitprice'][key]);
                    var price = config.current_price - config.stone['price'] + unitprice * config.stone["qty"];
                    var appraisedPrice = '$'+parseFloat(Math.round(price * 1.8 / 10) * 10);
                    var retailPrice = '$'+parseFloat(Math.round(price / 10) * 10);
                    
                    $(this).find('span.appraised-value').html(appraisedPrice);
                    $(this).find('span.retail-value').html(retailPrice);
                });
                
                $('p.appraised, p.retail').css('visibility', 'visible');
                $("#order-type-down-payment").html(downPrice);
                $('.sold-out').addClass('no-display');
                $(".product-available, a.btn-buy, a.btn-reserve").show();
            } else {
                var price = 'SOLD OUT';
                $('.sold-out').removeClass('no-display');
                $(".price-box span.price").html(price);
                $(".product-available, a.btn-buy, a.btn-reserve").hide();
                $('p.appraised, p.retail').css('visibility', 'hidden');
            }
        }
    
        function updateImage() {
            if (config.stone["type"] == "" || config.metal == "" || config.stone["carat"] == "" || config.stone["shape"] == "") {
                return;
            }
            
            if (config.isLoaded == false || config.isTypeChanged == true) {
                return;
            }
            
            new $.ajax(
                '/dm/api_ring_eternity_reload/image/',
                {
                    method:'post',
                    asynchronous: config.isLoaded,
                    data: getOptionSet(),
                    beforeSend: function() {
                        jQuery('body').append('<div id="loading-screen"></div>');
                    },
                    complete: function(xhr) {
                        var json = JSON.parse(xhr.responseText);
                        
                        if (json.length <= 0) return;
                        if (json[0].main == jQuery('#image').attr('src')) {
                            jQuery('#loading-screen').remove();
                            return;
                        }
                        
                        if (config.isLoaded) {
                            jQuery('#image').fadeTo(500, 0, function() {
                              jQuery('#image').attr('src',json[0].main).bind('onreadystatechange load', function(){
                                 if (this.complete) jQuery(this).fadeTo(300, 1);
                              });
                            });
                        } else {
                            jQuery('#image').attr('src',json[0].main).bind('onreadystatechange load', function(){
                                if (this.complete) jQuery(this).fadeTo(300, 1);
                            });
                        }
                        
                        jQuery('img.product-image-current').attr('src',json[0].main);

                        if ($(".pinterest a").attr("href")) {
                            var pinterest_href = $(".pinterest a").attr("href").split("?");
                            var pinterest_params = pinterest_href[1].split("&");
                            for(var i=0;i<pinterest_params.length;i++) {
                                if (pinterest_params[i].indexOf('media=') === 0) {
                                    pinterest_params[i] = "media="+encodeURIComponent(json[0].main);
                                    break;
                                }
                            }
                            $(".pinterest a").attr("href", pinterest_href[0] + "?" + pinterest_params.join("&"));
                        }
        
                        if ($(".pinterest a").attr("data-pin-href")) {
                            var pinterest_href = $(".pinterest a").attr("data-pin-href").split("?");
                            var pinterest_params = pinterest_href[1].split("&");
                            for(var i=0;i<pinterest_params.length;i++) {
                                if (pinterest_params[i].indexOf('media=') === 0) {
                                    pinterest_params[i] = "media="+encodeURIComponent(json[0].main);
                                    break;
                                }
                            }
                            $(".pinterest a").attr("data-pin-href", pinterest_href[0]+"?"+pinterest_params.join("&"));
                        }
                                
                        jQuery("#zoom").attr("href", json[0].pop);
        
                        $(".more-views-container ul li:first").html('<a title="" href="'+json[0].pop+'" data-zoom=\'"useZoom": "zoom", "smallImage": "'+json[0].main+'"\' class="cloud-zoom-gallery"><img alt="" src="'+json[0].thumb+'"></a>');
                        
                        jQuery('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();

                        jQuery('#loading-screen').remove();
                    },
                    error: function() {
                        alert('Something went wrong...');
                        jQuery('#loading-screen').remove();                        
                    }
                }
            );
        }

        function updateStonePrice() {
            config.current_price = parseFloat(config.current_price) - parseFloat(config.stone["price"]);
            var key = config.stone['shape'] + '-' + config.stone['carat'] + '-' + config.stone['color-clarity'];
            config.stone["price"] = parseFloat(config.stone['unitprice'][key]) * config.stone["qty"];
            config.current_price += config.stone["price"];
            
            displayPrice();
            return;
        }            

        function updateTitle() {
            var title = "";
            var totalCarat = parseFloat(config.stone['qty'] * config.stone['carat']).toFixed(2);
            if (totalCarat > 0) {
                title = totalCarat + " Ct.";
            }
            if (config.stone["shape"] != "") {
                if (config.stone["shape"] == "heart") {
                    title += " " + ucfirst(config.stone["shape"]) + " Shape";
                } else {
                    title += " " + ucfirst(config.stone["shape"]) + " Cut";
                }
            }
            if (config.stone["type"] == "natural") {
                title += " Natural"
            } else if (config.stone["type"] == "yellow") {
                title += " Canary Yellow";
            } else if (config.stone["type"] == "black") {
                title += " Black";
            } else if (config.stone["type"] == "pink") {
                title += " Pink";
            } else if (config.stone["type"] == "no-center-stone") {
                title = "";
            }
            
            title = title.trim();
            if (title != "") {
                title += " Diamond Eternity Ring";
                title = "<h1>"+title+"</h1>";
            } else {
                title = "<h1>" + config.name + "</h1>";
            }
            
            jQuery(".product-name").html(title);
            jQuery("input.productname").val(title);

            $('#total-carat').html(totalCarat);
        }
            
        function updateUrl() {
            var url = "";                           

            url += config.option_skus['stone-type'][config.stone['type']];
            url += config.option_skus['stone-shape'][config.stone['shape']];
            url += config.option_skus['stone-carat'][config.stone['carat']];
            url += config.option_skus['stone-color-clarity'][config.stone['color-clarity']];
            url += config.option_skus['metal'][config.metal['type']];
            url += config.option_skus['ring-size'][config.ring_size];
            url += config.option_skus['order-type'][config.order_type];
    
            updateTitle();
            
            if (!config.isRequestedOptions && !config.isLoaded) {
                return;
            }

            $("input.dm_custom_options").val(url.toLowerCase());
            url = "option=" + url;            
            window.history.replaceState({},"","?"+url.toLowerCase());
        }

        function ucfirst(str) {
            return str.substr(0, 1).toUpperCase() + str.substr(1);
        }    
        
        function getOptionSet() {
            return Object.assign({}, {
                "product_id": config.product_id,
                "stone-type": config.stone["type"], 
                "stone-shape": config.stone["shape"], 
                "stone-carat": config.stone["carat"], 
                "stone-color-clarity": config.stone["color-clarity"], 
                "metal": config.metal['type'],
                "ring-size": config.ring_size
            });
        }    
    }
});
