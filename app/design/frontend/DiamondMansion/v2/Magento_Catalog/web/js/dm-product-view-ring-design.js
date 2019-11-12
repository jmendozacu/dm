define([
    "jquery",
    "cloudzoom"
], function ($) {
    'use strict';

    return function (config) {

        function showOptionList($elm) {
            //$elm.parent().find('td.label').hide();
            var $clone = $elm.clone();
            $clone.addClass('clone');
            $elm.before($clone);
            $elm.addClass('expanded');
            $elm.find('.option').css('marginLeft', -1 * $elm.find('.option').outerWidth() / 2);
            $elm.find('.option').css('left', '50%');
            $elm.find('.option').css('marginTop', -1 * Math.min($elm.find('.option').outerHeight(), $elm.outerHeight() - 60) / 2);
        }

        function hideOptionList($elm) {
            //$elm.parent().find('td.label').show();
            $elm.parent().find('.clone').remove();
            $elm.removeClass('expanded');
            $elm.find('.option').css('marginLeft', 'auto');
            $elm.find('.option').css('left', '0');
            $elm.find('.option').css('marginTop', 'auto');
        }

        function initialize() {

            $('td.collapsed ul').each(function () {
                if ($(this).find('a.selected').length == 0) {
                    $(this).find('li:first-child a').addClass('selected');
                }

                if ($(this).find('li').length == 1) {
                    $(this).addClass('only-one');
                }
            })

            $('td.collapsed li').on('click', function() {
                if ($(this).closest('tr').attr('id') == 'size-list-wrapper') {
                    return;
                }

                if ($(this).parent().hasClass('only-one')) {
                    return;
                }

                showOptionList($(this).closest('td'));
            });

            $('td.collapsed .list-title i').on('click', function() {
                hideOptionList($(this).closest('td'));
            });

            $('a[id^=\'main-stone-type-\']').on('click', function() {
                onTypeChange($(this));

                var $parent = $(this).closest('td');
                if ($parent.hasClass('expanded')) {
                    hideOptionList($parent);
                }

                return false;
            });

            $('a[id^=\'main-stone-shape-\']').on('click', function() {
                onShapeChange($(this));

                var $parent = $(this).closest('td');
                if ($parent.hasClass('expanded')) {
                    hideOptionList($parent);
                }

                return false;
            });
            
            $('a[id^=\'setting-options-stone-\']').on('click', function() {
                onStoneChange($(this));

                var $parent = $(this).closest('td');
                if ($parent.hasClass('expanded')) {
                    hideOptionList($parent);
                }
               
                return false;
            });

            $('input[id^=\'size-\']').on('change', function() {
                onSizeChange();
            });

            $('input[id^=\'size-\']').on('blur', function() {
                onSizeChange();
            });

            $('a.general-options').on('click', function(event) {
                event.stopPropagation();

                onOptionChange($(this));

                var $parent = $(this).closest('td');
                if ($parent.hasClass('expanded')) {
                    hideOptionList($parent);
                }
               
                return false;
            });

            $('a[id^=\'metal-\']').on('click', function() {
                onMetalChange($(this)); return false;
            });

            $('a[id^=\'band-\']').on('click', function() {
                onBandChange($(this)); return false;
            });

            $('a[id^=\'side-stone-shape-\']').on('click', function() {
                onSideStoneChange($(this));

                var $parent = $(this).closest('td');
                if ($parent.hasClass('expanded')) {
                    hideOptionList($parent);
                }
               
                return false;
            });

            $('a[id^=\'side-stone-carat-\']').on('click', function() {
                onSideCaratChange($(this));

                var $parent = $(this).closest('td');
                if ($parent.hasClass('expanded')) {
                    hideOptionList($parent);
                }
               
                return false;
            });

            $('a[id^=\'side-stone-color-clarity-\']').on('click', function() {
                onSideColorClarityChange($(this));

                var $parent = $(this).closest('td');
                if ($parent.hasClass('expanded')) {
                    hideOptionList($parent);
                }
               
                return false;
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

            $('img.product-image-current').attr('src', config.default_image);
            
            updateTotalCarat();

            $('.options-details .block-title').on("click", function () {
                var $parent = $(this).parent();
                var isActive = $parent.hasClass('active');

                if (!isActive) {
                    $parent.find('.block-options').fadeIn(100, function () {
                        $parent.addClass('active');
                    });
                } else {
                    $parent.find('.block-options').fadeOut(100, function () {
                        $parent.removeClass('active');
                    });
                }
            });
            
            $(".custom-options-types a, #summary-details .option span a").on("click", function () {
                $(".custom-options-types a.active").removeClass("active");
                $(".custom-options-details div.active").removeClass("active");
                $(".custom-options-types a[href='"+$(this).attr("href")+"']").addClass("active");
                $($(this).attr("href")).addClass("active");
                return false;
            });

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

            config.isLoaded = true;

            reloadPrice();    

            $("#design-ring-options").fadeIn("fast");
        });

        function onTypeChange($elm) {
            var code = $elm.data('code');
            if (code == "") return;

            $(".type-list a.selected").removeClass("selected");
            $elm.addClass("selected");

            config.center_stone["type"] = code;
            
            if (config.center_stone["type"] == "setting") {
                $("#stone-list-wrapper, #size-list-wrapper").show();
                $("#carat-list-wrapper, #cert-list-wrapper, #cut-list-wrapper").closest('tr').hide();
    
                $("#center-diamond-details .block-summary li.stone, #center-diamond-details .block-summary li.size, #center-diamond-details .block-summary span#center-diamond-setting-label").show();                
                $("#center-diamond-details .block-summary li.carat, #center-diamond-details .block-summary li.cert, #center-diamond-details .block-summary span#center-diamond-details-label").hide();
            } else {
                $("#stone-list-wrapper, #size-list-wrapper").hide();
                $("#carat-list-wrapper, #cert-list-wrapper, #color-list-wrapper, #clarity-list-wrapper, #cut-list-wrapper").closest('tr').show();
                
                $("#center-diamond-details .block-summary li.stone, #center-diamond-details .block-summary li.size, #center-diamond-details .block-summary span#center-diamond-setting-label").hide();                
                $("#center-diamond-details .block-summary li.carat, #center-diamond-details .block-summary li.color, #center-diamond-details .block-summary li.clarity, #center-diamond-details .block-summary li.cert, #center-diamond-details .block-summary span#center-diamond-details-label").show();
            }
    
            $(".block-options .option ul.dependent").hide();
            $(".block-options .option ul.dependent." + code).show();
            
            if (config.isLoaded) {
                config.isTypeChanged = true;

                ['.shape-list', '.carat-list', '.color-list', '.clarity-list', '.cert-list', '.cut-list'].forEach(function(element) {
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

            $("#center-diamond-details .block-summary li.shape a").html($(".shape-list a.selected span.caption").text() + ' ' + $(".shape-list a.selected span.cut").text());

            var $si1Elm = $("#center-diamond-details .clarity-list.natural a[data-code='si1']");
            if ($si1Elm.length) {
                var $si1ElmParent = $si1Elm.closest('li');
                if (code == 'asscher' || code == 'emerald') {
                    $si1ElmParent.addClass('skip');
                    $si1ElmParent.hide();
                    if ($si1Elm.hasClass('selected')) {
                        updateImage();
                        if ($si1ElmParent.prev().length) {
                            $si1ElmParent.prev().find('a').trigger('click');
                        } else if ($si1ElmParent.next().length) {
                            $si1ElmParent.next().find('a').trigger('click');
                        }
                        return;
                    }
                } else {
                    $si1ElmParent.removeClass('skip');
                    $si1ElmParent.show();
                }    
            }

            reloadPrice();
            updateImage();
            updateUrl();
        }

        function onOptionChange($elm) {
            var code = $elm.data('code');
            var list = $elm.data('list');

            $("." + list + "-list a.selected").removeClass("selected");
            $elm.addClass("selected");

            if (list == 'carat') {
                $("#center-diamond-details .block-summary li." + list + " a").html(code * 100 / 100 + ' Carat');
            } else if (list == 'cut') {
                $("#center-diamond-details .block-summary li." + list + " a").html($("." + list + "-list a.selected span.caption").html() + ' Cut');
            } else {
                $("#center-diamond-details .block-summary li." + list + " a").html($("." + list + "-list a.selected span.caption").html());
            }
            
            config.center_stone[list] = code;

            if (list == "carat" || list == "color" || list == "clarity" || list == 'cut') {
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

            $("#metal-details .block-summary li.metal-type a").html($(".metal-list a.selected").html());

            reloadPrice();
            updateImage();
            updateUrl();
        }

        function onBandChange($elm) {
            var code= $elm.data('code');
            if (code == "") return;

            $(".band-list a.selected").removeClass("selected");
            $elm.addClass("selected");

            config.band = code;
            
            $(".sidecarats.fixed ul a.selected").each(function(index, value) {
                if (code == "bridal-set") {
                    $(this).html($(this).attr("data-carat-band"));
                } else {
                    $(this).html($(this).attr("data-carat"));
                }
            });
        
            if (config.isLoaded) {
                $(".sideshapes ul").each(function(index, value) {
                    if (config.band == "bridal-set") {
                        config.side_stone["qty"][$(this).attr("rel")] = parseInt($(this).find("a.selected").attr("data-qty2"));
                    } else {
                        config.side_stone["qty"][$(this).attr("rel")] = parseInt($(this).find("a.selected").attr("data-qty"));
                    }
                    
                    var sidestones_qty = config.side_stone["qty"][$(this).attr("rel")];
                    
                    $(this).closest('table').find("sub").html("x " + config.side_stone["qty"][$(this).attr("rel")]);
                    $(this).closest('table').find(".sidecarats ul li a").each (function (index, value) {
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
            if (config.center_stone['type'] != 'setting') {
                return;
            }

            var code = $elm.data('code');

            $(".stone-list a.selected").removeClass("selected");
            $elm.addClass("selected");

            $("#center-diamond-details .block-summary li.stone a").html($(".stone-list a.selected").html());
            
            config.center_stone['stone'] = code;
            
            if (code == "white-diamond") {
                $("#color-list-wrapper, #clarity-list-wrapper").closest('tr').show();
                $("#center-diamond-details .block-summary li.color, #center-diamond-details .block-summary li.clarity").show();
    
            } else {
                $("#color-list-wrapper, #clarity-list-wrapper").closest('tr').hide();
                $("#center-diamond-details .block-summary li.color, #center-diamond-details .block-summary li.clarity").hide();
            }
            
            updateUrl();
        }

        function onSizeChange() {

            var height = $("#size-height").val();
            var width = $("#size-width").val();
            var depth = $("#size-depth").val();
            
            $("#center-diamond-details .block-summary li.size a").html(height + " x " + width + " x " + depth);
            
            updateUrl();
        }

        function onRingSizeChange($elm) {
            $(".ring-size-list a.selected").removeClass("selected");
            $elm.addClass("selected");

            $("#ring-size-details .block-summary li.ring-size a").html($(".ring-size-list a.selected").html());
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
            
            $("." + group + "-list").closest('tr').find("sub").html("x " + config.side_stone["qty"][group]);
            $("." + group + "-list").closest('tr').find(".sidecarats ul li a").each (function (index, value) {
                $(this).html(Math.round(parseFloat($(this).attr("rel")) * parseFloat(config.side_stone["qty"][group])*10000)/10000 + ' Carat');
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

            $("#side-stones-details .block-summary li.color-clarity a").html($(".sidecolorclarity-list a.selected").html());
            
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
                            var json = JSON.parse(xhr.responseText);
                            config.current_price = parseFloat(json.price);

                            if (json.diff_for_bridal_set > 0) {
                                $('#matching-band-details li a b').html('+$' + json.diff_for_bridal_set.toLocaleString());
                            } else {
                                $('#matching-band-details li a b').html('');
                            }

                            displayPrice();
                        }
                    }
                );
            } 
            
            return;
        }

        function displayPrice() {
            if (config.current_price) {
                $("#maincontent .price-box span.price, #order-type-order-now").html('$'+parseFloat(Math.round(config.current_price / 10) * 10).toLocaleString());
                $("#maincontent .price-box span.value").html('$'+parseFloat(Math.round(config.current_price / 10) * 20).toLocaleString());
                $("#maincontent .price-box span.appraised-value").show();
                if ($('#order-type-down-payment').length) {
                    var downPrice = '$'+parseFloat(Math.round(config.current_price / 10)).toLocaleString();
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
                $("#maincontent .price-box span.price").html('SOLD OUT');
                $("#maincontent .price-box span.appraised-value").hide();
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
            if (config.band == "" || config.center_stone["type"] == "" || config.center_stone["type"] == "setting" || config.metal == "" || config.center_stone["shape"] == "") {
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
                                    if (this.complete) {
                                        $(this).fadeTo(300, 1);
                                    }
                                });
                            });
                        } else {
                            $('#image').attr('src',json[0].main).bind('onreadystatechange load', function() {
                                if (this.complete) $(this).fadeTo(300, 1);
                            });
                        }
                        
                        $('img.product-image-current').attr('src',json[0].main);
                
                        $('#gallery').slick('slickGoTo', 0);
                        $(window).scrollTop(0);

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
        
                        $("#gallery li:first img").attr('src', json[0].pop);
                        $(".gallery-thumbnail li:first img").attr('src', json[0].pop);
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
                    sideStoneQty = parseInt($(this).find('a.selected').data('qty2'));
                }
                sideStonesCarat += sideStoneQty * parseFloat($(this).parent().parent().find(".sidecarats ul a.selected").data("code"));
            });
            
            if (Math.round(sideStonesCarat * 100) / 100 > 0) {
                $("#side-stones-details").show();
            } else {
                $("#side-stones-details").hide();
            }

            $("#side-stones-details .block-summary li.carat a").html(Math.round(sideStonesCarat * 100) / 100 + ' Carat');
            config.totalCarat = parseFloat(Math.floor($(".carat-list a.selected").data('code') * 10) / 10) + sideStonesCarat;
            config.totalCarat = Math.round(config.totalCarat * 100) / 100;
            $("#total-carat").html(Math.round(config.totalCarat * 100) / 100 + " CT.");
            if (sideStonesCarat > 0) {
                $("span.ad-total-carat").each (function () {
                    var centerStoneCarat = parseFloat(Math.floor($(this).parent().data('code') * 10) / 10) + sideStonesCarat;
                    centerStoneCarat = Math.round(centerStoneCarat * 100) / 100;
                    $(this).html("(" + centerStoneCarat + " total)");
                });
            }

            updateTitle();    
        }

        function updateUrl() {
            if (config.isTypeChanged) {
                return;
            }
            
            var url = "";
            var prefix = "";
            var available = true;
            
            /*
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
            */

            var prefix = config.center_stone["shape"];
            if (prefix == 'heart') {
                prefix += '-shape-';
            } else {
                prefix += '-cut-';
            }
            
            url = "option=";

            var newOptions = "";

            config.optionsSortOrder.forEach(function (group, index) {
                if ((config.center_stone["type"] != "setting" && group == "setting-options-stone") || group == "setting-options-size") {
                    return;
                }

                var $selected = $(".block-options .option ul[data-group='" + group + "'] .selected");
                if ($selected.length) {
                    var code = $selected.data('code');
                    if (code) {
                        url += config.option_skus[group][code];
                    }
                }
            });

            if (config.center_stone["type"] == "setting") {
                if ($(".stone-list").find(".selected").length) {
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
    
                $("input.dm_custom_options").val(url.toLowerCase().replace('option=', ''));
                
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
                var titleHTML = "<h2>" + config.subname + "</h2><h1 class=\"page-title\"><span class=\"base\" data-ui-id=\"page-title-wrapper\" itemprop=\"name\">" + title + " " + config.name + " (GIA Certified)</span></h1>";
            } else {
                var titleHTML = "<h2>" + config.subname + "</h2><h1 class=\"page-title\"><span class=\"base\" data-ui-id=\"page-title-wrapper\" itemprop=\"name\">" + title + " " + config.name + " (GIA Certified)</span></h1>";
            }

            document.title = title + " " + config.name + " (GIA Certified)";
            $(".product-name").html(titleHTML);
            $("input.productname").val(config.name + " - " + title);
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
                html += '<li class="filter"><a title="" href="'+config.categoryUrl+breadcrumbList["band"]["param"]+'/'+breadcrumbList["type"]["param"]+'/">'+breadcrumbList["band"]["label"]+"</a><span>&gt;</span></li>";
            }
            if (breadcrumbList.hasOwnProperty("shape")) {
                if (breadcrumbList.hasOwnProperty("band")) {
                    html += '<li class="filter"><a title="" href="'+config.categoryUrl+breadcrumbList["shape"]["param"]+'/'+breadcrumbList["type"]["param"]+'?band='+breadcrumbList["band"]["param"]+'">'+breadcrumbList["shape"]["label"]+"</a><span>&gt;</span></li>";                
                } else {
                    html += '<li class="filter"><a title="" href="'+config.categoryUrl+breadcrumbList["shape"]["param"]+'/'+breadcrumbList["type"]["param"]+'/">'+breadcrumbList["shape"]["label"]+"</a><span>&gt;</span></li>";                
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
                "main-stone-cut": config.center_stone["cut"],
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
