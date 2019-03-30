/*
 *
  * Copyright © 2018 Magenest. All rights reserved.
  * See COPYING.txt for license details.
  *
  * Magenest_InstagramShop extension
  * NOTICE OF LICENSE
  *
  * @category Magenest
  * @package  Magenest_InstagramShop
  * @author    dangnh@magenest.com

 */

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
    var maxX = jQuery('input[name="maxX"]').val(),
        maxY = jQuery('input[name="maxY"]').val();

    var dataSourceName = "magenest_instagramshop_hotspot_form.magenest_instagramshop_hotspot_form.general.";
    try {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        var xaxis = 19;
        var yaxis = 12;

        document.getElementById(data).style.position = 'absolute';
        document.getElementById(data).style.left = fixSize(ev.layerX - xaxis, maxX) + 'px';
        document.getElementById(data).style.top = fixSize(ev.layerY - yaxis, maxY) + 'px';
        var xElementIdX = data + 'x';
        var xElementIdY = data + 'y';
        var xElementIdSKU = data + 'sku';
        require(['uiRegistry'], function (registry) {
            registry.get(dataSourceName + xElementIdX).value(fixSize(ev.layerX - xaxis, maxX) + 'px');
            registry.get(dataSourceName + xElementIdY).value(fixSize(ev.layerY - yaxis, maxY) + 'px');
            registry.get(dataSourceName + xElementIdSKU).required(true);
        });
        ev.target.appendChild(document.getElementById(data));
    } catch (error) {
        document.getElementById(data).style.position = 'absolute';
        document.getElementById(data).style.left = jQuery('input[name="lastPostX"]').val();
        document.getElementById(data).style.top = jQuery('input[name="lastPostY"]').val();
        ev.target.appendChild(document.getElementById(data));
    }
    jQuery('input[name="lastPostX"]').val(fixSize(ev.layerX - xaxis, maxX) + 'px');
    jQuery('input[name="lastPostY"]').val(fixSize(ev.layerY - yaxis, maxY) + 'px');
}

function fixSize(size, max) {
    return size > max ? max : size > 0 ? size : 0;
}



