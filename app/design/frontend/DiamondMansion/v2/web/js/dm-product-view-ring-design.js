define([
    "jquery",
    "cloudzoom"
], function ($) {
    'use strict';

    return function (config) {

        function initialize() {

            $('a[id^=\'main-stone-type-\']').on('click', function() {
                onTypeChange($(this)); return false;
            });

            $('a[id^=\'main-stone-shape-\']').on('click', function() {
                onShapeChange($(this)); return false;
            });
            
            $('a[id^=\'setting-options-stone-\']').on('click', function() {
                onStoneChange($(this)); return false;
            });

            $('input[id^=\'size-\']').on('change', function() {
                onSizeChange();
            });

            $('input[id^=\'size-\']').on('blur', function() {
                onSizeChange();
            });

            $('a.general-options').on('click', function() {
                onOptionChange($(this)); return false;
            });

            $('a[id^=\'metal-\']').on('click', function() {
                onMetalChange($(this)); return false;
            });

            $('a[id^=\'band-\']').on('click', function() {
                onBandChange($(this)); return false;
            });

            $('a[id^=\'side-stone-shape-\']').on('click', function() {
                onSideStoneChange($(this)); return false;
            });

            $('a[id^=\'side-stone-carat-\']').on('click', function() {
                onSideCaratChange($(this)); return false;
            });

            $('a[id^=\'side-stone-color-clarity-\']').on('click', function() {
                onSideColorClarityChange($(this)); return false;
            });

            $('a[id^=\'ring-size-\']').on('click', function() {
                onRingSizeChange($(this)); return false;
            });

            for (var group in config.default_options) {
                if ($('a#' + config.default_options[group]).length > 1) {
                    $('ul.dependent.' + $('.type-list a.selected').data('code') + ' a#' + config.default_options[group]).trigger('click');
                } else {
                    $('a#' + config.default_options[group]).trigger('click');
                }
            }
        }

        $(document).ready(function() {
            initialize();

            $("#more-view-ideal10-section").bind("click", function () {
                $("#ideal10-section-content").slideToggle('fast', function () {
                    if ($("#more-view-ideal10-section").hasClass("more")) {
                        $("#more-view-ideal10-section").removeClass("more");
                        $("#more-view-ideal10-section").addClass("less");
                    } else {
                        $("#more-view-ideal10-section").removeClass("less");
                        $("#more-view-ideal10-section").addClass("more");
                    }
                });
                
                return false;
            });
            
            $('img.product-image-current').attr('src', config.default_image);
            
            reloadPrice();    
            updateTotalCarat();
            
            $(".prev-arrow").on("click", function () {
                var tabIndex = $(".custom-options-tabs-mobile .custom-options-types a.active").index();
                onChangeTab(tabIndex - 1);
                return false;
            });

            $(".next-arrow").on("click", function () {
                var tabIndex = $(".custom-options-tabs-mobile .custom-options-types a.active").index();
                onChangeTab(tabIndex + 1);
                return false;
            });

            $(".custom-options-types a, #summary-details .option span a").on("click", function () {
                $(".custom-options-types a.active").removeClass("active");
                $(".custom-options-details div.active").removeClass("active");
                $(".custom-options-types a[href='"+$(this).attr("href")+"']").addClass("active");
                $($(this).attr("href")).addClass("active");
                return false;
            })

            config.isLoaded = true;
            $(".product-view").slideDown("slow");
        });

        function onChangeTab(tabIndex) {
            var elmId = $(".custom-options-tabs-mobile .custom-options-types a").eq(tabIndex).attr("href");
            //$(".custom-options-tabs-mobile .custom-options-types a").eq(tabIndex).trigger("click");
            $(".custom-options-types a.active").removeClass("active");
            $(".custom-options-details div.active").removeClass("active");
            $(".custom-options-types a[href='"+elmId+"']").addClass("active");
            $(elmId).addClass("active");
            
            if (tabIndex == 0) { $(".prev-arrow").hide(); $(".prev-arrow-label").hide(); } else { $(".prev-arrow").show(); $(".prev-arrow-label").show(); }
            if (tabIndex == $(".custom-options-tabs-mobile .custom-options-types a").length - 1) { $(".next-arrow").hide(); $(".next-arrow-label").hide(); } else { $(".next-arrow").show(); $(".next-arrow-label").show(); }
        }

        function onTypeChange($elm) {
            var code = $elm.data('code');
            if (code == "") return;

            $(".type-list a.selected").removeClass("selected");
            $elm.addClass("selected");

            config.center_stone["type"] = code;
            
            if (config.center_stone["type"] == "setting") {
                $("#stone-list-wrapper, #size-list-wrapper").show();
                $("#carat-list-wrapper, #cert-list-wrapper").hide();
    
                $("#summary-details .option.center-stone li.stone, #summary-details .option.center-stone li.size, #summary-details .option.center-stone span#center-diamond-setting-label").show();                
                $("#summary-details .option.center-stone li.carat, #summary-details .option.center-stone li.cert, #summary-details .option.center-stone span#center-diamond-details-label").hide();
            } else {
                $("#stone-list-wrapper, #size-list-wrapper").hide();
                $("#carat-list-wrapper, #cert-list-wrapper, #color-list-wrapper, #clarity-list-wrapper").show();
                
                $("#summary-details .option.center-stone li.stone, #summary-details .option.center-stone li.size, #summary-details .option.center-stone span#center-diamond-setting-label").hide();                
                $("#summary-details .option.center-stone li.carat, #summary-details .option.center-stone li.color, #summary-details .option.center-stone li.clarity, #summary-details .option.center-stone li.cert, #summary-details .option.center-stone span#center-diamond-details-label").show();
            }
    
            $(".block-options .option ul.dependent").hide();
            $(".block-options .option ul.dependent." + code).show();
            
            if (config.isLoaded) {
                config.isTypeChanged = true;

                ['.shape-list', '.carat-list', '.color-list', '.clarity-list', '.cert-list'].forEach(function(element) {
                    var list = $(element + "." + code);
                    var selectedId = $(element + " a.selected").attr("id");
                    $(element + " a.selected").removeClass("selected");
                    if (list.find("#"+selectedId).length) {
                        list.find("#"+selectedId).trigger("click");
                    } else {
                        list.find("li:first-child a").trigger("click");
                    }
                });
                
                var list = $(".stone-list");
                var selectedId = $(".stone-list a.selected").attr("id");
                $(".stone-list a.selected").removeClass("selected");
                if (code == "setting") {
                    if (list.find("#"+selectedId).length) {
                        list.find("#"+selectedId).trigger("click");
                    } else {
                        list.find("li:first-child a").trigger("click");
                    }
                    
                    onSizeChange();
                }
                
                config.isTypeChanged = false;
                reloadPrice();
            }
            
            updateImage();
            updateUrl();
        }

        function onShapeChange($elm) {
            var code = $elm.data('code');
            if (code == "") return;

            $(".shape-list a.selected").removeClass("selected");
            $elm.addClass("selected");

            config.center_stone["shape"] = code;
            
            $("#summary-details .option.center-stone li.shape a").html("<img/>");
            $("#summary-details .option.center-stone li.shape a img").replaceWith($(".shape-list a.selected img.active").clone());

            reloadPrice();
            updateImage();
            updateUrl();
        }

        function onOptionChange($elm) {
            var code = $elm.data('code');
            var list = $elm.data('list');

            $("." + list + "-list a.selected").removeClass("selected");
            $elm.addClass("selected");

            $("#summary-details .option.center-stone li." + list + " a").html($("." + list + "-list a.selected").html());
            
            config.center_stone[list] = code;

            if (list == "carat" || list == "color" || list == "clarity") {
                reloadPrice();
            }
            
            if (list == "carat") {
                updateTotalCarat();
            }

            updateUrl();
        }    

        function onMetalChange($elm) {
            var code= $elm.data('code');
            if (code == "") return;

            $(".metal-list a.selected").removeClass("selected");
            $elm.addClass("selected");

            config.metal = code;

            $("#summary-details .option.metal li.metal-type a").html("<img/>");
            $("#summary-details .option.metal li.metal-type a img").replaceWith($(".metal-list a.selected img").clone());

            reloadPrice();
            updateImage();
            updateUrl();
        }

        function onBandChange($elm) {
            var code= $elm.data('code');
            if (code == "") return;

            $(".band-list a.selected").removeClass("selected");
            $elm.addClass("selected");

            $("#summary-details .option.matching-band li.matching-band a").html($(".band-list a.selected").html());
            config.band = code;
            
            $(".sub-block-options .sub-option .sidecarats.fixed ul a.selected").each(function(index, value) {
                if (code == "bridal-set") {
                    $(this).html($(this).attr("data-carat-band"));
                } else {
                    $(this).html($(this).attr("data-carat"));
                }
            });
        
            if (config.isLoaded) {
                $(".sub-block-options .sub-option .sideshapes ul").each(function(index, value) {
                    if (config.band == "bridal-set") {
                        config.side_stone["qty"][$(this).attr("rel")] = parseInt($(this).find("a.selected").attr("data-qty2"));
                    } else {
                        config.side_stone["qty"][$(this).attr("rel")] = parseInt($(this).find("a.selected").attr("data-qty"));
                    }
                    
                    var sidestones_qty = config.side_stone["qty"][$(this).attr("rel")];
                    
                    $(this).parent().find("sub").html("x " + config.side_stone["qty"][$(this).attr("rel")]);
                    $(this).parent().parent().find(".sidecarats ul li a").each (function (index, value) {
                        $(this).html(Math.round(parseFloat($(this).attr("rel")) * parseFloat(sidestones_qty) * 10000)/10000);
                    });
                });
            }

            reloadPrice();
            updateTotalCarat();
            updateImage();
            updateUrl();
        }    

        function onStoneChange($elm) {
            var code = $elm.data('code');

            $(".stone-list a.selected").removeClass("selected");
            $elm.addClass("selected");

            $("#summary-details .option.center-stone li.stone a").html($(".stone-list a.selected").html());
            
            config.center_stone['stone'] = code;
            
            if (code == "white-diamond") {
                $("#color-list-wrapper, #clarity-list-wrapper").show();
                $("#summary-details .option.center-stone li.color, #summary-details .option.center-stone li.clarity").show();
    
            } else {
                $("#color-list-wrapper, #clarity-list-wrapper").hide();
                $("#summary-details .option.center-stone li.color, #summary-details .option.center-stone li.clarity").hide();
            }
            
            updateUrl();
        }

        function onSizeChange() {

            var height = $("#size-height").val();
            var width = $("#size-width").val();
            var depth = $("#size-depth").val();
            
            $("#summary-details .option.center-stone li.size a").html(height + " x " + width + " x " + depth);
            
            updateUrl();
        }

        function onRingSizeChange($elm) {
            $(".ring-size-list a.selected").removeClass("selected");
            $elm.addClass("selected");

            $("#summary-details .option.ring-size li.ring-size a").html($(".ring-size-list a.selected").html());
            updateUrl();
        }    

        function onSideStoneChange($elm) {
        
            $(".sideshape-list a.selected").removeClass("selected");
            $elm.addClass("selected");

            var group = $elm.data('group');
            
            $("img#" + group + "-selected").attr("src", $elm.find("img").attr("src"));
            
            if (config.band == "bridal-set") {
                config.side_stone["qty"][group] = $elm.data("qty2");
            } else {
                config.side_stone["qty"][group] = $elm.data("qty");
            }
            
            $("." + group + "-list").parent().find("sub").html("x " + config.side_stone["qty"][group]);
            $("." + group + "-list").parent().parent().find(".sidecarats ul li a").each (function (index, value) {
                $(this).html(Math.round(parseFloat($(this).attr("rel")) * parseFloat(config.side_stone["qty"][group])*10000)/10000);
            });
            
            config.side_stone[group] = $elm.data('code');
            config.band_qty["side_stone"][group] = parseFloat($elm.data("qty2")) - parseFloat($elm.data("qty"));
            
            reloadPrice();
            updateUrl();
        }

        function onSideCaratChange($elm) {
            
            $(".sidecarats-list a.selected").removeClass("selected");
            $elm.addClass("selected");
            
            config.side_stone[$elm.data('group')] = $elm.data('code');
            
            updateTotalCarat();
            reloadPrice();
            updateUrl();
        }

        function onSideColorClarityChange($elm) {
            
            $(".sidecolorclarity-list a.selected").removeClass("selected");
            $elm.addClass("selected");

            $("#summary-details .option.side-stones li.color-clarity a").html($(".sidecolorclarity-list a.selected").html());
            
            config.side_stone[$elm.data('group')] = $elm.data('code');  
            
            reloadPrice();
            updateUrl();
        }
    
        function reloadPrice() {
        
            if (config.isTypeChanged || config.is_fixed_main_stone_price) {
                return;
            }
            if (!config.isLoaded) {
                displayPrice();
                return;
            }
            
            if (config.center_stone["type"] != "" &&
                config.center_stone["shape"] != "" &&
                config.center_stone["carat"] != "" &&
                config.center_stone["color"] != "" &&
                config.center_stone["clarity"] != "") {
                
                new $.ajax(
                    '/dm/api_ring_design_reload/price/',
                    {
                        method:'post',
                        asynchronous: false,
                        data: getOptionSet(),
                        complete : function(xhr) {
                            
                            config.current_price = parseFloat(xhr.responseText);

                            displayPrice();
                        }
                    }
                );
            } 
            
            return;
        }

        function displayPrice() {
            if (config.current_price) {
                $(".price-box span.price, #order-type-order-now").html('$'+parseFloat(Math.round(config.current_price / 10) * 10));
                if ($('#order-type-down-payment').length) {
                    var downPrice = '$'+parseFloat(Math.round(config.current_price / 10));
                    $("#order-type-down-payment").html(downPrice);
                }
    
                $("input.productprice").val(parseFloat(Math.round(config.current_price / 10) * 10));
                $(".product-options-bottom").css("visibility", "");
                //$(".btn-buy").css("visibility", "");
                //$(".btn-reserve").css("visibility", "");
                $(".btn-buy").show();
                $(".btn-reserve").show();
                
                $(".order-options").show();               
            } else {
                $(".price-box span.price").html('SOLD OUT');
                $(".product-options-bottom").css("visibility", "hidden");
                //$(".btn-buy").css("visibility", "hidden");
                //$(".btn-reserve").css("visibility", "hidden");            
                $(".btn-buy").hide();
                $(".btn-reserve").hide();            
                $("#matching-band-details .band-image p").css("visibility", "hidden");
                $(".order-options").hide();
            }
        }
    
        function updateImage() {
            if (config.band == "" || config.center_stone["type"] == "" || config.metal == "" || config.center_stone["shape"] == "") {
                return;
            }
            
            if (config.isLoaded == false || config.isTypeChanged == true) {
                return;
            }
            
            new $.ajax(
                '/dm/api_ring_design_reload/image/',
                {
                    method:'post',
                    asynchronous: config.isLoaded,
                    data: getOptionSet(),
                    complete : function(xhr) {
                        var json = JSON.parse(xhr.responseText);
                        
                        if (json.length <= 0) {
                            return;
                        }

                        if (json[0].main == $('#image').attr('src')) {
                            return;
                        }
                        
                        if (config.isLoaded) {
                            $('#image').fadeTo(500, 0, function() {
                                $('#image').attr('src',json[0].main).bind('onreadystatechange load', function() {
                                    if (this.complete) $(this).fadeTo(300, 1);
                                });
                            });
                        } else {
                            $('#image').attr('src',json[0].main).bind('onreadystatechange load', function() {
                                if (this.complete) $(this).fadeTo(300, 1);
                            });
                        }
                        
                        $('img.product-image-current').attr('src',json[0].main);
                
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
        
                        $("#zoom").attr("href", json[0].pop);
        
                        $(".more-views-container ul li:first").html('<a title="" href="'+json[0].pop+'" data-zoom="\"useZoom\": \'zoom\', \"smallImage\": \''+json[0].main+'\' " class="cloud-zoom-gallery"><img alt="" src="'+json[0].thumb+'"></a>');
                        
                        $('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();

                        if (json.length > 1 && $('#band-image').length) {
                            $('#band-image').fadeOut(300, function(){
                                $('#band-image').attr('src',json[1].main).bind('onreadystatechange load', function(){
                                   if (this.complete) $(this).fadeIn(300);
                                });
                            });      
                        }
                    }
                }
            );
            
            if ($("#matching-band-details").hasClass("active")) { 
                return;
            }
        }

        function updateTotalCarat() {
            //if (!config.isLoaded) {
            //    return;
            //}
            
            var sideStonesCarat = 0;
            $(".sideshapes ul").each (function (index, value) {
                var sideStoneQty = parseInt($(this).find('a.selected').data('qty'));
                if (config.band == 'bridal-set') {
                    sideStoneQty += parseInt($(this).find('a.selected').data('qty2'));
                }
                sideStonesCarat += sideStoneQty * parseFloat($(this).parent().parent().find(".sidecarats ul a.selected").data("code"));
            });
            
            $("#summary-details .option.side-stones li.carat a").html(Math.round(sideStonesCarat * 100) / 100);
            config.totalCarat = parseFloat($(".carat-list a.selected").data('code')) + sideStonesCarat;
            config.totalCarat = Math.round(config.totalCarat * 100) / 100;
            $("#total-carat").html(Math.round(config.totalCarat * 100) / 100 + " CT.");

            updateTitle();    
        }

        function updateUrl() {
            if (config.isTypeChanged) {
                return;
            }
            
            var url = "";
            var prefix = "";
            var available = true;
            
            if ($(".carat-list .selected").length && $(".carat-list .selected").data('code') != "") {
                if (url != "") url += "-";
                url += $(".carat-list .selected").data('code') + "ctw";
            }
                                            
            if ($(".shape-list .selected").length && $(".shape-list .selected").data('code') != "") {
                if (url != "") url += "-";
                url += $(".shape-list .selected").data('code');
                prefix = $(".shape-list .selected").data('code');
                if (prefix == 'heart') {
                    prefix += '-shape-';
                } else {
                    prefix += '-cut-';
                }
            }
    
            if ($(".type-list .selected").length && $(".type-list .selected").data('code') != "") {
                if (url != "") url += "-";
                url += $(".type-list .selected").data('code') + "-diamond";
            }

            if ($(".metal-list .selected").length && $(".metal-list .selected").data("code") != "") {
                if (url != "") url += "-";
                url += $(".metal-list .selected").data("code") + "-ring";
            }
                                            
            if ($(".clarity-list .selected").length && $(".clarity-list .selected").data('code') != "") {
                if (url != "") url += "-";
                url += $(".clarity-list .selected").data('code');
            }
                                            
            if ($(".color-list .selected").length && $(".color-list .selected").data('code') != "") {
                if (url != "") url += "-";
                url += $(".color-list .selected").data('code');
            }
                                            
            if ($(".cert-list .selected").length && $(".cert-list .selected").data('code') != "") {
                if (url != "") url += "-";
                url += $(".cert-list .selected").data('code');
            }
            
            url = "option=";
    
            $(".block-options .option ul, .sub-block-options .sub-option ul").each(function (index, value) {
                var group = $(this).data("group");
                
                if (group == "setting-options-stone" || group == "setting-options-size") {
                    return;
                }
                
                if ($(this).find(".selected").length) {
                    var code = $(this).find(".selected").data("code");
                    if (code)
                        url += config.option_skus[group][code];
                } else {
                    if (($(this).tagName == "UL" && $(this).hasClass(config.center_stone["type"])) || $(this).tagName == "SELECT") {
                        available = false;
                    }
                }
            });
            
            if (config.center_stone["type"] == "setting") {
                if ($(".stone-list").find(".selected").length) {
                    var code = $(".stone-list").find(".selected").data("code");
                    if (code) {
                        url += config.option_skus["setting-options-stone"][code];
                    }

                    var height = $("#size-height").val();
                    var width = $("#size-width").val();
                    var depth = $("#size-depth").val();
                    if (!isNaN(height) && !isNaN(width) && !isNaN(depth)) { 
                        url += "-"+height+"x"+width+"x"+depth;
                    }
                }
            }

            if (available) {
                updateTitle();
                updateBreadcrumbs();
    
                if (!config.isRequestedOptions && !config.isLoaded) {
                    return;
                }
                
                $("input.productlink").val("/" + config.urlKey + "?" + url.toLowerCase());
                
                window.history.replaceState({},"","/"+prefix+config.urlKey+"/?"+url.toLowerCase());
                if ($(".pinterest a").attr("href")) {
                    var pinterest_href = $(".pinterest a").attr("href").split("?");
                    var pinterest_params = pinterest_href[1].split("&");
                    for(var i=0;i<pinterest_params.length;i++) {
                        if (pinterest_params[i].indexOf('url=') === 0) {
                            pinterest_params[i] = "url="+encodeURIComponent(location.href);
                            break;
                        }
                    }
                    $(".pinterest a").attr("href", pinterest_href[0]+"?"+pinterest_params.join("&"));
                }
    
                if ($(".pinterest a").attr("data-pin-href")) {
                    var pinterest_href = $(".pinterest a").attr("data-pin-href").split("?");
                    var pinterest_params = pinterest_href[1].split("&");
                    for(var i=0;i<pinterest_params.length;i++) {
                        if (pinterest_params[i].indexOf('url=') === 0) {
                            pinterest_params[i] = "url="+encodeURIComponent(location.href);
                            break;
                        }
                    }
                    $(".pinterest a").attr("data-pin-href", pinterest_href[0]+"?"+pinterest_params.join("&"));
                }
            }
        }

        function updateTitle() {
            var title = "";
            if (config.totalCarat > 0) {
                title = config.totalCarat + " Ct.";
            }
            if (config.center_stone["shape"] != "") {
                if (config.center_stone["shape"] == "heart") {
                    title += " " + config.center_stone["shape"] + " Shape";
                } else {
                    title += " " + config.center_stone["shape"] + " Cut";
                }
            }
            if (config.center_stone["type"] == "natural") {
                title += " Natural"
            } else if (config.center_stone["type"] == "yellow") {
                title += " Canary Yellow";
            } else if (config.center_stone["type"] == "black") {
                title += " Black";
            } else if (config.center_stone["type"] == "pink") {
                title += " Pink";
            } else if (config.center_stone["type"] == "setting") {
                title = "";
            }
            
            title = title.trim();
            if (title != "") {
                title += " Diamond";
                title = "<h1 class=\"page-title\"><span class=\"base\" data-ui-id=\"page-title-wrapper\" itemprop=\"name\">" + config.name + "</span></h1><h2>" + title + "</h2>";
            } else {
                title = "<h1 class=\"page-title\"><span class=\"base\" data-ui-id=\"page-title-wrapper\" itemprop=\"name\">" + config.name + "</span></h1>";
            }
            
            $(".product-name").html(title);
            $("input.productname").val(title);
        }

        function ucfirst(str) {
            return str.substr(0, 1).toUpperCase() + str.substr(1);
        }    

        function updateBreadcrumbs() {
            if (!config.categoryUrl) {
                return;
            }

            var breadcrumbList = new Array();
            var breadcrumbItem = new Array();
            
            breadcrumbItem["filter"] = "type";
            if (config.center_stone["type"] == "natural") {
                breadcrumbItem["label"] = "Natural Diamond";
                breadcrumbItem["param"] = "diamond";
            } else if (config.center_stone["type"] == "setting") {
                breadcrumbItem["label"] = "Settings";
                breadcrumbItem["param"] = "setting";
            } else {
                breadcrumbItem["label"] = ucfirst(config.center_stone["type"]) + " Diamond";
                breadcrumbItem["param"] = config.center_stone["type"];
            }
            
            breadcrumbList["type"] = breadcrumbItem;
            
            breadcrumbItem = new Array();
            if (config.band == "brial-set") {
                breadcrumbItem["filter"] = "band";
                breadcrumbItem["label"] = "Bridal Set"
                breadcrumbItem["param"] = "bridal-set";
                breadcrumbList["band"] = breadcrumbItem;
            }
            
            if (config.center_stone["type"] != "setting") {
                breadcrumbItem = new Array();
                breadcrumbItem["filter"] = "shape";
                breadcrumbItem["param"] = config.center_stone["shape"];
    
                if (config.center_stone["type"] == "heart") {
                    breadcrumbItem["label"] = "Heart Shape";
                } else {
                    breadcrumbItem["label"] = ucfirst(config.center_stone["shape"]) + " Cut";
                }
                
                breadcrumbList["shape"] = breadcrumbItem;
            }
            
            breadcrumbItem = new Array();
            breadcrumbItem["filter"] = "metal";
            if (config.metal.indexOf("white-gold") != -1) {
                breadcrumbItem["label"] = "White Gold"
                breadcrumbItem["param"] = "white-gold";
            } else if (config.metal.indexOf("yellow-gold") != -1) {
                breadcrumbItem["label"] = "Yellow Gold"
                breadcrumbItem["param"] = "yellow-gold";
            } else if (config.metal.indexOf("rose-gold") != -1) {
                breadcrumbItem["label"] = "Rose Gold"
                breadcrumbItem["param"] = "rose-gold";
            } else if (config.metal.indexOf("platinum") != -1) {
                breadcrumbItem["label"] = "Platinum"
                breadcrumbItem["param"] = "platinum";
            }
            
            breadcrumbList["metal"] = breadcrumbItem;
            
            var html = "";
            if (breadcrumbList.hasOwnProperty("type")) {
                html += '<li class="filter"><a title="" href="'+config.categoryUrl+breadcrumbList["type"]["param"]+'/">'+breadcrumbList["type"]["label"]+"</a><span>&gt;</span></li>";
            }
            if (breadcrumbList.hasOwnProperty("band")) {
                html += '<li class="filter"><a title="" href="'+config.categoryUrl+breadcrumbList["band"]["param"]+'/'+breadcrumbList["type"]["param"]+'">'+breadcrumbList["band"]["label"]+"</a><span>&gt;</span></li>";
            }
            if (breadcrumbList.hasOwnProperty("shape")) {
                if (breadcrumbList.hasOwnProperty("band")) {
                    html += '<li class="filter"><a title="" href="'+config.categoryUrl+breadcrumbList["shape"]["param"]+'/'+breadcrumbList["type"]["param"]+'?band='+breadcrumbList["band"]["param"]+'">'+breadcrumbList["shape"]["label"]+"</a><span>&gt;</span></li>";                
                } else {
                    html += '<li class="filter"><a title="" href="'+config.categoryUrl+breadcrumbList["shape"]["param"]+'/'+breadcrumbList["type"]["param"]+'">'+breadcrumbList["shape"]["label"]+"</a><span>&gt;</span></li>";                
                }
            }
            
            jQuery(".breadcrumbs li.filter").remove();
            jQuery(".breadcrumbs li.product").before(html);
        }
        
        function getOptionSet() {
            return Object.assign({}, {
                "product_id": config.product_id,
                "main-stone-type": config.center_stone["type"], 
                "main-stone-shape": config.center_stone["shape"], 
                "main-stone-carat": config.center_stone["carat"], 
                "main-stone-color": config.center_stone["color"], 
                "main-stone-clarity": config.center_stone["clarity"],
                "side-stone-shape-1": config.side_stone["side-stone-shape-1"],
                "side-stone-shape-2": config.side_stone["side-stone-shape-2"],
                "side-stone-shape-3": config.side_stone["side-stone-shape-3"],
                "side-stone-shape-4": config.side_stone["side-stone-shape-4"],
                "side-stone-carat-1": config.side_stone["side-stone-carat-1"],
                "side-stone-carat-2": config.side_stone["side-stone-carat-2"],
                "side-stone-carat-3": config.side_stone["side-stone-carat-3"],
                "side-stone-carat-4": config.side_stone["side-stone-carat-4"],
                "side-stone-color-clarity-1": config.side_stone["side-stone-color-clarity-1"],
                "side-stone-color-clarity-2": config.side_stone["side-stone-color-clarity-2"],
                "side-stone-color-clarity-3": config.side_stone["side-stone-color-clarity-3"],
                "side-stone-color-clarity-4": config.side_stone["side-stone-color-clarity-4"],
                "metal": config.metal,
                "band": config.band,
                "ring-size": config.ring_size,
                "setting-options-stone": config.setting_options_stone,
                "setting-options-size": config.setting_options_size
            });
        }    
    }
});
