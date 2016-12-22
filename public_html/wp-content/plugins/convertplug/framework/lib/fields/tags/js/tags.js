/*global Taggle*/
(function() {
    'use strict';
    var domains = ['google.com', 'facebook.com', 'yahoo.com'];

    jQuery(".smile-tags").tagit({
        availableTags: domains
    });

}());