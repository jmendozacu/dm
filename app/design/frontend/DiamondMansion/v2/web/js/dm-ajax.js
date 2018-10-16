define([
    "jquery"
], function ($) {
    'use strict';
    $.widget('dm.ajax', {
        _showPageLoader: function (text) { text = typeof text !== 'undefined' ? '<span>'+text+'</span>' : ""; $('body').append('<div id="page-loader">'+text+'</div>'); $('#page-loader').fadeIn('fast'); },
        _hidePageLoader: function () { $('#page-loader').fadeOut('fast', function () { $('#page-loader').remove(); }); },
        _ajax: function (url, method, data, callback) { 
            if (typeof method === 'undefined') { 
                method = "GET"; 
            }; 
            
            if (typeof data === 'undefined') { 
                data = {}; 
            }; 
            
            if (typeof callback === 'undefined') { 
                callback = function () {}; 
            }; 
            
            $.ajax({ 
                url: url, 
                method: method, 
                data: data, 
                beforeSend: this._showPageLoader, 
                complete: this._hidePageLoader, 
                success: callback 
            }); 
        },
        get: function (url, data, callback) { 
            this._ajax(url, "GET", data, callback); 
        }, 
        post: function (url, data, callback) { 
            this._ajax(url, "POST", data, callback); 
        }
    });

    return $.dm.ajax.prototype;
});
