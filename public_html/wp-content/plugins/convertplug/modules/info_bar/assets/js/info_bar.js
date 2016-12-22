    (function($) {
    "use strict";

    /**
     *  1. FitText.js 1.2 - (http://sam.zoy.org/wtfpl/)
     *-----------------------------------------------------------*/
    (function( $ ){
      $.fn.fitText = function( kompressor, options ) {
        // Setup options
        var compressor = kompressor || 1,
            settings = $.extend({
              'minFontSize' : Number.NEGATIVE_INFINITY,
              'maxFontSize' : Number.POSITIVE_INFINITY
            }, options);
        return this.each(function(){
          // Store the object
          var $this = $(this);
          // Resizer() resizes items based on the object width divided by the compressor * 10
          var resizer = function () {
            $this.css('font-size', Math.max(Math.min($this.width() / (compressor*10), parseFloat(settings.maxFontSize)), parseFloat(settings.minFontSize)));
          };
          // Call once to set.
          resizer();
          // Call on resize. Opera debounces their resize by default.
          $(window).on('resize.fittext orientationchange.fittext', resizer);
        });
      };
    })( jQuery );

    /**
     *  2. CP Responsive - (Required - FitText.js)
     *
     *  Required to call on READY & LOAD
     *-----------------------------------------------------------*/
    function CPApplyFlatText(s, fs) {
        if( s.hasClass('cp-description') ) {
            s.fitText(1.7, {  minFontSize: '12px', maxFontSize: fs } );
        } else {
            s.fitText(1.2, {  minFontSize: '16px', maxFontSize: fs } );
        }
    }

    function getPriorityInfoBar(){
        var info_bar = 'none';
        jQuery(".cp-info-bar").each(function(t,v) {
            var hasClass = jQuery(this).hasClass("priority_infobar");
            if( hasClass ){
                info_bar = jQuery(this);
                return info_bar;
            }
        });
        return info_bar;
    }

    function stripTrailingSlash( url ) {
        if( url.substr(-1) === '/') {
            return url.substr(0, url.length - 1);
        }
        return url;
    }

    // Referrer detection
    jQuery.fn.isReferrer = function( referrer, doc_ref, ref_check ){
        var display = true;
        // referrer    = stripTrailingSlash( referrer.replace(/.*?:\/\//g, "") );
        doc_ref     = stripTrailingSlash( doc_ref.replace(/.*?:\/\//g, "") );

        var referrers = referrer.split( ",");

        jQuery.each( referrers, function(i, url ){

            url     = stripTrailingSlash( url );

            doc_ref = doc_ref.replace("www.","");
            var dr_arr = doc_ref.split(".");
            var ucount = url.match(/./igm).length;
            var dr_domain = dr_arr[0];

            url = stripTrailingSlash( url.replace(/.*?:\/\//g, "") );
            url = url.replace("www.","");
            var url_arr = url.split("*");

            if(doc_ref.indexOf("t.co") !== -1 ){
                doc_ref = 'twitter.com';
            }

            if( doc_ref.indexOf("plus.google.co") !== -1 ){
                doc_ref = 'plus.google.com';
            } else if( doc_ref.indexOf("google.co") !== -1 ) {
                doc_ref = 'google.com';
            }

            var _domain = url_arr[0];
            _domain = stripTrailingSlash( _domain );

            if( ref_check =="display" ) {
                if( url.indexOf('*') !== -1 ) {
                    if( _domain == doc_ref ){
                        display = true;
                        return false;
                    } else if( doc_ref.indexOf( _domain ) !== -1 ){
                        display = true;
                        return false;
                    } else {
                        display = false;
                        return false;
                    }
                } else if( url == doc_ref ){
                    display = true;
                    return false;
                } else {
                    display = false;
                }
            } else if( ref_check == "hide" ) {
                if( url.indexOf('*') !== -1 ) {
                    if( _domain == doc_ref ){
                        display = false;
                        return false;
                    } else if( doc_ref.indexOf( _domain ) !== -1 ){
                        display = false;
                        return false;
                    } else {
                        display = true;
                        return false;
                    }
                } else if( url == doc_ref ){
                    display = false;
                    return false;
                } else if( doc_ref.indexOf( _domain ) !== -1 ){
                    display = false;
                    return false;
                } else {
                    display = true;
                }
            }
        });
        return display;
    }

    // detect mobile devices
    var isMob = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPod/i);
        },
        Tablet: function() {
            return navigator.userAgent.match(/iPad/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        Any: function() {
            return (isMob.Android() || isMob.BlackBerry() || isMob.iOS() || isMob.Opera() || isMob.Windows());
        }
    };

    // detect OS
    var isOs = {
        Win: function(){
            return navigator.appVersion.indexOf("Win")!=-1 ;
        },
        Mac: function(){
            return navigator.appVersion.indexOf("Mac")!=-1;
        },
        Unix: function(){
            return navigator.appVersion.indexOf("X11")!=-1;
        },
        Linux: function(){
            return navigator.appVersion.indexOf("Linux")!=-1;
        },
        Android: function(){
            return navigator.userAgent.match(/Android/i);
        },
        Any: function(){
            return ( isOs.Win() || isOs.Mac() || isOs.Unix() || isOs.Linux() );
        }
    };

    // detect browsers
    var isBrowser = {
        Chrome: function(){
            return jQuery.browser.chrome && parseInt( jQuery.browser.version ) > 43;
        },
        Mozilla: function(){
            return jQuery.browser.mozilla && parseInt( jQuery.browser.version ) > 12;
        },
        Safari: function(){
            return jQuery.browser.safari;
        },
        IE: function(){
            return ( jQuery.browser.mozilla && parseInt( jQuery.browser.version ) == 11 )
                || ( jQuery.browser.chrome && parseInt( jQuery.browser.version ) <= 43 )
                || ( jQuery.browser.msie );
        },
    };


   function hideOnDevice(devices){
        if( typeof devices !== "undefined" ) {
            devices = devices.split("|");

            var returns = false,
            isDesktop   = false,
            isTablet    = false,
            isMobile    = false;

            isTablet    = DetectTierTablet();
            isMobile    = DetectTierIphone();

            jQuery.each(devices, function(){
                var device = jQuery(this).selector;
                if( ( device == "desktop"   && (!isMobile) && (!isTablet) )
                ||  ( device == "tablet"    && isTablet )
                ||  ( device == "mobile"    && isMobile ) ){
                    returns = true;
                }
            });
        } else {
              returns =false;
        }
        return returns;
    }

    jQuery.fn.windowSize = function(){
        var cp_content_container= this.find(".cp-content-container"),
            cp_info_bar            = this.find(".cp-info-bar"),
            cp_info_bar_content    = this.find(".cp-info-bar-content"),
            cp_info_bar_body       = this.find(".cp-info-bar-body");

        cp_info_bar.removeAttr('style');
        cp_info_bar_content.removeAttr('style');
        cp_content_container.removeAttr('style');
        cp_info_bar_body.removeAttr('style');
        var ww = jQuery(window).width() + 30;
        var wh = jQuery(window).height();
        jQuery(this).find("iframe").css("width",ww);

        cp_content_container.css({'max-width':ww+'px','width':'100%','height':wh+'px','padding':'0','margin':'0 auto'});
        cp_info_bar_content.css({'max-width':ww+'px','width':'100%'});
        cp_info_bar.css({'max-width':ww+'px','width':'100%','left':'0','right':'0'});
        cp_info_bar_body.css({'max-width':ww+'px','width':'100%','height':wh+'px'});
    }

    jQuery.fn.isScheduled = function(){
        var y = new Date(gmt);
        var timestring = this.data('timezonename');
        var tzoffset   = this.data('tz-offset');
        var gtime = y.toGMTString();
        var ltime = y.toLocaleString();

        var scheduled = this.data('scheduled');

        var date = new Date();
        // turn date to utc
        var utc = date.getTime() + (date.getTimezoneOffset() * 60000);

        // set new Date object
        var new_date = new Date(utc + (3600000*tzoffset));

        if( typeof scheduled !== "undefined" && scheduled == true ) {

            var start = this.data('start');
            var end = this.data('end');

            start = Date.parse(start);
            end = Date.parse(end);

            if( timestring == 'system' ){
                ltime = Date.parse(date);
            } else {
                ltime = Date.parse(new_date);
            }

            if( ltime >= start && ltime <= end ){
                return true;
            } else {
                return false;
            }

        } else {
            return true;
        }
    }

    // Sets cookies.

    var createCookie = function(name, value, days){

        // If we have a days value, set it in the expiry of the cookie.
        if ( days ) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            var expires = '; expires=' + date.toGMTString();
        } else {
            var expires = '';
        }

        // Write the cookie.
        document.cookie = name + '=' + value + expires + '; path=/';
    }

    // Retrieves cookies.
    var getCookie = function(name){
        var nameEQ = name + '=';
        var ca = document.cookie.split(';');
        for ( var i = 0; i < ca.length; i++ ) {
            var c = ca[i];
            while ( c.charAt(0) == ' ' ) {
                c = c.substring(1, c.length);
            }
            if ( c.indexOf(nameEQ) == 0 ) {
                return c.substring(nameEQ.length, c.length);
            }
        }

        return null;
    }

    var infoBarPos = function( cp_info_bar ){

        if( cp_info_bar.hasClass("cp-pos-top") ) {
            cp_info_bar.css('top','0');
        } else {

            if( cp_info_bar.hasClass("ib-fixed") ){
                cp_info_bar.css('top','auto');
            } else {
                var toggle = cp_info_bar.data("toggle");
                var body_ht = jQuery("body").parent("html").height();
                var toggle_ht = cp_info_bar.find('.cp-ifb-toggle-btn').outerHeight();
                var cp_height  = cp_info_bar.find(".cp-info-bar-body").outerHeight();
                if( toggle == 1 ) {
                     body_ht = body_ht - cp_height + toggle_ht;
                }
                if( !cp_info_bar.hasClass('cp-info-bar-inline') ) {
                    cp_info_bar.css('top',body_ht+'px');
                }
                cp_info_bar.css("min-height",cp_height+"px");
            }
        }
        if( jQuery("body").hasClass("admin-bar") ){
            if( cp_info_bar.hasClass("cp-pos-top")){
                var ab_height = jQuery("#wpadminbar").outerHeight();
                if( !cp_info_bar.hasClass('cp-info-bar-inline') ) {
                    cp_info_bar.css("top", ab_height+"px");
                }
            }
        }
    }

    // Removes cookies.
    var removeCookie = function(name){
        createCookie(name, '', -1);
    }

    jQuery(window).on( 'load', function() {
        var styleArray = Array();
        jQuery(".cp-info-bar").each(function(t) {
            var class_id          = jQuery(this).data("class-id");
            var dev_mode          = jQuery(this).data("dev-mode");
            var cookieName      = jQuery(this).data('info_bar-id');
            var temp_cookie     = "temp_"+cookieName;
            removeCookie(temp_cookie);
            cp_set_ifb_ht( this );
            cp_ifb_color_for_list_tag(this);

        });
    });

    jQuery(window).resize(function() {

        jQuery(".cp-info-bar").each(function(t) {
            infoBarPos(jQuery( this ) );
        });

        cp_infobar_social_responsive();

    });

    // Display info_bar on page load after x seconds
    jQuery(window).on( 'load', function() {
        var styleArray = Array();
        jQuery(".cp-ib-onload").each(function(t) {
            var $this = jQuery(this);
            var exit                = jQuery(this).data("exit-intent");
            var dev_mode            = jQuery(this).data("dev-mode");
            var cookieName          = jQuery(this).data('info_bar-id');
            var ib_id               = jQuery(this).attr("id");
            var temp_cookie         = "temp_"+cookieName;
            var opt                 = jQuery(this).data('option');
            var style               = jQuery(this).data('info_bar-style');
            var info_bar            = jQuery(this);
            var delay               = jQuery(this).data("onload-delay");
            delay                   = delay*1000;
            var load_on_refresh     = jQuery(this).data('load-on-refresh');
            var hide_on_device      = jQuery(this).data('hide-on-devices');
            var hide_from_device    = hideOnDevice(hide_on_device);

            var scrollPercent       = 100 * jQuery(window).scrollTop() / (jQuery(document).height() - jQuery(window).height());
            var scrollTill          = jQuery(this).data("onscroll-value");
            var toggle_visible      = jQuery(this).data('toggle-visible');

            infoBarPos( info_bar );

            var display = false;
            var scheduled = info_bar.isScheduled();

            if( load_on_refresh == "disabled" ){
                var refresh_cookie  = getCookie(cookieName+'-refresh');
                if(refresh_cookie){
                    display = true;
                } else {
                    createCookie(cookieName+'-refresh',true,1);
                    display = false;
                }
            } else {
                display = true;
                removeCookie(cookieName+'-refresh');
            }

            var cookie      = getCookie(cookieName);
            var tmp_cookie = getCookie(temp_cookie);

            if( !temp_cookie ){
                createCookie(temp_cookie,true,1);
            } else if( dev_mode == "enabled" && tmp_cookie ) {
                cookie = true;
            }

            if( hide_from_device ) {
                display = false;
            }

            var page_down = jQuery(this).data('push-down');
            page_down = parseInt( page_down );

            var infobar_container = jQuery(this);
            var ib_height = '';

            setTimeout(function() {
                 ib_height = infobar_container.outerHeight();
            }, 100 );

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();

            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = info_bar.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if(!cookie && delay && display && scheduled && referred ){
                setTimeout(function() {
                    if( jQuery(".ib-display").length <= 0 ) {

                        //info_bar.show();

                        if( !info_bar.hasClass('impression_counted') ) {
                            styleArray.push(style);
                            if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {
                                update_impressions(styleArray);

                                jQuery("[data-info_bar-style="+style+"]").each(function(e) {
                                    jQuery(this).addClass('impression_counted');
                                });
                            }
                        }

                        if( info_bar.hasClass("cp-pos-top")){
                            if( jQuery("body").hasClass("admin-bar") ){
                                var ab_height = jQuery("#wpadminbar").outerHeight();
                                info_bar.css("top", ab_height+"px");
                            }
                        } else {
                            var cp_height  = info_bar.find(".cp-info-bar-body").outerHeight();
                            info_bar.css("min-height",cp_height+"px");
                        }

                        apply_push_page_down(info_bar);

                        jQuery(document).trigger('resize');
                        info_bar.addClass('ib-display');
                        jQuery(document).trigger('infobarOpen',[info_bar]);
                        setTimeout( function(){
                            var anim = info_bar.find(".cp-submit").data("animation");
                            info_bar.find(".cp-submit").addClass(anim);
                        }, 2000 );
                    }
                }, parseInt(delay));
            }

            if( dev_mode == "enabled" ){
                removeCookie(cookieName);
            }
        });

    });

    // Display info_bar on page scroll after x percentage
    jQuery(document).scroll(function(e){

        // count inline impressions
        count_inline_impressions();

        // calculate the percentage the user has scrolled down the page
        var scrollPercent = 100 * jQuery(window).scrollTop() / (jQuery(document).height() - jQuery(window).height());
        var scrolled = scrollPercent.toFixed(0);
        var styleArray = Array();
        jQuery(".cp-ib-onload").each(function(t) {
            var $this = jQuery(this);
            var exit                = jQuery(this).data("exit-intent");
            var class_id            = jQuery(this).data("class");
            var dev_mode            = jQuery(this).data("dev-mode");
            var cookieName          = jQuery(this).data('info_bar-id');
            var temp_cookie         = "temp_"+cookieName;
            var opt                 = jQuery(this).data('option');
            var style               = jQuery(this).data('info_bar-style');
            var info_bar            = jQuery(this);
            var scrollTill          = jQuery(this).data("onscroll-value");

            var hide_on_device      = jQuery(this).data('hide-on-devices');
            var hide_from_device    = hideOnDevice(hide_on_device);

            var toggle_visible      = jQuery(this).data('toggle-visible');

            var data                = {action:'smile_update_impressions',impression:true,style_id:style,option:opt};
            if( dev_mode == "enabled" ){
                removeCookie(cookieName);
            }
            var cookie              = getCookie(cookieName);
            var tmp_cookie          = getCookie(temp_cookie);
            if( !temp_cookie ){
                createCookie(temp_cookie,true,1);
            } else if( dev_mode == "enabled" && tmp_cookie ) {
                cookie = true;
            }

            var scheduled = info_bar.isScheduled();

            if( hide_from_device ) {
                cookie = scrollTill = scheduled = false;
            }

            var page_down = jQuery(this).data('push-down');
            page_down = parseInt( page_down );
            var ib_height = jQuery(this).outerHeight();

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();
            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = info_bar.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( !cookie && scrollTill && scheduled && referred ){
                if( jQuery(".ib-display").length <= 0 ){
                    if( scrolled >= scrollTill  ){

                        apply_push_page_down(info_bar);

                        if( !info_bar.hasClass('impression_counted') ) {
                            styleArray.push(style);
                            if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {
                                update_impressions(styleArray);

                                jQuery("[data-info_bar-style="+style+"]").each(function(e) {
                                    jQuery(this).addClass('impression_counted');
                                });
                            }
                        }

                        //info_bar.show();
                        if( info_bar.hasClass("cp-pos-top")){
                            if( jQuery("body").hasClass("admin-bar") ){
                                var ab_height = jQuery("#wpadminbar").outerHeight();
                                info_bar.css("top", ab_height+"px");
                            }
                        } else {
                            var cp_height       = info_bar.find(".cp-info-bar-body").outerHeight();
                            info_bar.css("min-height",cp_height+"px");
                        }
                        info_bar.addClass('ib-display');
                        jQuery(document).trigger('infobarOpen',[info_bar]);
                        setTimeout( function(){
                            var anim = info_bar.find(".cp-submit").data("animation");
                            info_bar.find(".cp-submit").addClass(anim);
                        }, 2000 );
                    }
                }
            }
        });

    });

    // Display info_bar on page scroll after post content
    jQuery(document).scroll(function(e){

        var scrolled = jQuery(window).scrollTop();
        var styleArray = Array();
        jQuery(".ib-after-post").each(function(t) {
            var $this = jQuery(this);
            var exit                = jQuery(this).data("exit-intent");
            var class_id            = jQuery(this).data("class");
            var dev_mode            = jQuery(this).data("dev-mode");
            var scrollValue         = jQuery(this).data("after-content-value");
            var cookieName          = jQuery(this).data('info_bar-id');
            var temp_cookie         = "temp_"+cookieName;
            var opt                 = jQuery(this).data('option');
            var style               = jQuery(this).data('info_bar-style');
            var info_bar            = jQuery(this);
            var scrollTill          = jQuery(".cp-load-after-post").offset().top - 30;

            var hide_on_device      = jQuery(this).data('hide-on-devices');
            var hide_from_device    = hideOnDevice(hide_on_device);

            var toggle_visible      = jQuery(this).data('toggle-visible');

            var data                = {action:'smile_update_impressions',impression:true,style_id:style,option:opt};
            if( dev_mode == "enabled" ){
                removeCookie(cookieName);
            }
            var cookie              = getCookie(cookieName);
            var tmp_cookie          = getCookie(temp_cookie);
            if( !temp_cookie ){
                createCookie(temp_cookie,true,1);
            } else if( dev_mode == "enabled" && tmp_cookie ) {
                cookie = true;
            }

            var scheduled = info_bar.isScheduled();
            scrollTill = scrollTill - ( ( jQuery(window).height() * scrollValue ) / 100 );

            if( hide_from_device ) {
                cookie = scrollTill = scheduled = false;
            }

            var page_down = jQuery(this).data('push-down');
            page_down = parseInt( page_down );
            var ib_height = jQuery(this).outerHeight();

            var page_down = jQuery(this).data('push-down');
            page_down = parseInt( page_down );
            var ib_height = jQuery(this).outerHeight();

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();
            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = info_bar.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( !cookie && scrollTill && scheduled && referred ){
                if( jQuery(".ib-display").length <= 0 ){
                    if( scrolled >= scrollTill  ){

                        apply_push_page_down(info_bar);

                        //info_bar.show();
                        if( info_bar.hasClass("cp-pos-top")){
                            if( jQuery("body").hasClass("admin-bar") ){
                                var ab_height = jQuery("#wpadminbar").outerHeight();
                                info_bar.css("top", ab_height+"px");
                            }
                        } else {
                            var cp_height       = info_bar.find(".cp-info-bar-body").outerHeight();
                            info_bar.css("min-height",cp_height+"px");
                        }
                        info_bar.addClass('ib-display');
                        jQuery(document).trigger('infobarOpen',[info_bar]);
                        if( !info_bar.hasClass('impression_counted') ) {
                            styleArray.push(style);
                            if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {
                                update_impressions(styleArray);

                                jQuery("[data-info_bar-style="+style+"]").each(function(e) {
                                    jQuery(this).addClass('impression_counted');
                                });
                            }
                        }

                        setTimeout( function(){
                            var anim = info_bar.find(".ib-subscribe").data("animation");
                            info_bar.find(".ib-subscribe").addClass(anim);
                        }, 2000 );
                    }
                }
            }
        });
    });


    // Load the exit intent handler.
    jQuery(document).on('mouseleave', function(e){
        var styleArray = Array();
        var getPriorityIB = getPriorityInfoBar();
        jQuery(".cp-ib-onload").each(function(t) {
            var $this = jQuery(this);
            if( getPriorityIB !== "none" ){
                var info_bar = getPriorityIB;
                $this = info_bar;
            }
            var exit                = $this.data("exit-intent");
            var class_id            = $this.data("class-id");
            var dev_mode            = $this.data("dev-mode");
            var cookieName          = $this.data('info_bar-id');
            var temp_cookie         = "temp_"+cookieName;

            var opt                 = $this.data('option');
            var style               = $this.data('info_bar-style');
            var info_bar            = $this;

            var hide_on_device      = $this.data('hide-on-devices');
            var hide_from_device    = hideOnDevice(hide_on_device);

            var toggle_visible      = jQuery(this).data('toggle-visible');

            var data                = {action:'smile_update_impressions',impression:true,style_id:style,option:opt};
            if( dev_mode == "enabled" ){
                removeCookie(cookieName);
            }
            var cookie      = getCookie(cookieName);
            var tmp_cookie  = getCookie(temp_cookie);
            if( !temp_cookie ){
                createCookie(temp_cookie,true,1);
            } else if( dev_mode == "enabled" && tmp_cookie ) {
                cookie = true;
            }

            var display = false;
            var inactive_time = $this.data('inactive-time');
            if( typeof inactive_time !== "undefined" ){
                display = true;
            }

            var page_down = $this.data('push-down');
            page_down = parseInt( page_down );
            var ib_height = $this.outerHeight();

            var scheduled = info_bar.isScheduled();

            if( hide_from_device ) {
                exit = scheduled = false;
            }

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();
            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = info_bar.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( !cookie && referred ){
                if( exit == 'enabled' && scheduled ){
                    if ( e.clientY <= 0 ){
                        if( jQuery(".ib-display").length <= 0 ){

                            apply_push_page_down(info_bar);

                            //info_bar.show();
                            if( info_bar.hasClass("cp-pos-top")){
                                if( jQuery("body").hasClass("admin-bar") ){
                                    var ab_height = jQuery("#wpadminbar").outerHeight();
                                    info_bar.css("top", ab_height+"px");
                                }
                            } else {
                                var cp_height  = info_bar.find(".cp-info-bar-body").outerHeight();
                                info_bar.css("min-height",cp_height+"px");
                            }

                            jQuery(document).trigger('playYoutube');

                            info_bar.addClass('ib-display');
                            jQuery(document).trigger('infobarOpen',[info_bar]);
                            if( !info_bar.hasClass('impression_counted') ) {
                                styleArray.push(style);
                                if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {
                                    update_impressions(styleArray);

                                    jQuery("[data-info_bar-style="+style+"]").each(function(e) {
                                        jQuery(this).addClass('impression_counted');
                                    });
                                }
                            }

                            setTimeout( function(){
                                var anim = info_bar.find(".cp-submit").data("animation");
                                info_bar.find(".cp-submit").addClass(anim);
                            }, 2000 );

                        }
                    }
                }
            }
        });
    });

    // Load the user activity handler
    jQuery(document).ready(function(){

        jQuery('.blinking-cursor').remove();

        cp_ifb_toggle();

        cp_infobar_social_responsive();

        // count inline impressions
        count_inline_impressions();

        jQuery(".cp-info-bar").each(function(t) {
            if( jQuery("body").hasClass("admin-bar") ){
                var admin_bar_ht = jQuery("#wpadminbar").outerHeight();
                if( jQuery(this).hasClass("cp-pos-top") && !jQuery(this).hasClass("cp-info-bar-inline") ) {
                    jQuery(this).css("top", admin_bar_ht + 'px' );
                }
            }
            var inactive_time = jQuery(this).data('inactive-time');
            if( typeof inactive_time !== "undefined" ) {
                inactive_time = inactive_time*1000;
                jQuery( document ).idleTimer( {
                    timeout: inactive_time,
                    idle: false
                });
            }

        });

        // close info bar
        jQuery(".ib-close").click( function(e){
            e.preventDefault();
            var info_bar = jQuery(this).parents(".cp-info-bar");
            jQuery(document).trigger("cp_close_info_bar",[info_bar]);
        });
    });

    jQuery(document).on("cp_close_info_bar", function( e, info_bar ) {
        var entry_anim        = info_bar.data('entry-animation');
        var exit_anim         = info_bar.data('exit-animation');
        var cookieTime        = info_bar.data('closed-cookie-time');
        var cookieName        = info_bar.data('info_bar-id');
        var animate_push_page = info_bar.data('animate-push-page');
        var temp_cookie     = "temp_"+cookieName;
        var page_push_down = info_bar.data('push-down') || null;

        //  If not has 'cp-ifb-with-toggle' class for smooth toggle
        if( !info_bar.hasClass('cp-ifb-with-toggle') ){
            info_bar.removeClass(entry_anim);
            info_bar.addClass(exit_anim);
        }

        if( info_bar.hasClass("cp-pos-top")){

            if( page_push_down ) {
            	var cp_top_offset_container = jQuery("#cp-top-offset-container").val();
            	var offset_def_settings = jQuery("#cp-top-offset-container").data('offset_def_settings');

                var mTop = offset_def_settings.margin_top;
                var top = offset_def_settings.top;

                if( animate_push_page == 1 ) {
                	if( cp_top_offset_container == '' ) {
                        jQuery('body').animate({
                            'marginTop' : mTop,
                            'top'       : top
                        });
                    }
                    else {
                        jQuery(cp_top_offset_container).animate({
                            'margin-top' : mTop,
                            'top'        : top
                        });
                    }
                } else {
                	if( cp_top_offset_container == '' ) {
                        jQuery('body').css({
                            'margin-top' : mTop,
                            'top'        : top
                        });
                    }
                    else {
                        jQuery(cp_top_offset_container).css({
                            'margin-top' : mTop,
                            'top'        : top
                        });
                    }
                }
            }
            if( jQuery(".ib-display").length == 1 ) {
                var admin_bar_height = jQuery('#wpadminbar').outerHeight();
                var cp_push_down_support_container = jQuery("#cp-push-down-support").val();
                if( jQuery('#wpadminbar').length ) {
                    if( animate_push_page == 1 ) {
                        jQuery(cp_push_down_support_container).animate({ 'top': admin_bar_height }, 1000 );
                    } else {
                        jQuery(cp_push_down_support_container).css( 'top',  admin_bar_height );
                    }
                } else {
                    if( animate_push_page == 1 ) {
                        jQuery(cp_push_down_support_container).animate({ 'top': '0px' }, 1000 );
                    } else{
                        jQuery(cp_push_down_support_container).css( 'top', '0px' );
                    }
                }
            }
        }
        createCookie(temp_cookie,true,1);
        if(cookieTime) {
            createCookie(cookieName,true,cookieTime);
        }

        if( info_bar.hasClass('cp-hide-inline-style') || info_bar.hasClass('cp-close-ifb') ){
            exit_anim = "cp-overlay-none";
        }

        if( info_bar.hasClass('cp-close-ifb') ){
            setTimeout( function(){
                //if(!info_bar.hasClass('cp-ifb-with-toggle') ){
                    info_bar.hide();
                    info_bar.removeClass("ib-display");

                    //  If not has 'cp-ifb-with-toggle' class for smooth toggle
                    info_bar.removeClass(exit_anim);
                    info_bar.addClass(entry_anim);
               // }
                jQuery("html").css("overflow-x","auto");
            }, 3000);
        }

        if( exit_anim !== "cp-overlay-none" ){
            setTimeout( function(){

                if(!info_bar.hasClass('cp-ifb-with-toggle') ){

                    info_bar.hide();
                    info_bar.removeClass("ib-display");

                    //  If not has 'cp-ifb-with-toggle' class for smooth toggle
                    info_bar.removeClass(exit_anim);
                    info_bar.addClass(entry_anim);
                }
                jQuery("html").css("overflow-x","auto");
            }, 3000);
        } else {
            setTimeout( function(){
                if(!info_bar.hasClass('cp-ifb-with-toggle')){
                    info_bar.hide();
                    info_bar.removeClass("ib-display");
                    //  If not has 'cp-ifb-with-toggle' class for smooth toggle
                    exit_anim = "cp-overlay-none";
                    info_bar.removeClass(exit_anim);
                    info_bar.addClass(entry_anim);
                }
                jQuery("html").css("overflow-x","auto");
              }, 100);
        }

    });
    jQuery(document).on( "idle.idleTimer", function(event, elem, obj){
        var styleArray = Array();
        var getPriorityIB = getPriorityInfoBar();
        jQuery(".cp-ib-onload").each(function(t) {
            var $this = jQuery(this);
            if( getPriorityIB !== "none" ){
                var info_bar = getPriorityIB;
                $this = info_bar;
            }
            var class_id            = $this.data("class-id");
            var dev_mode            = $this.data("dev-mode");
            var cookieName          = $this.data('info_bar-id');
            var temp_cookie         = "temp_"+cookieName;
            var opt                 = $this.data('option');
            var style               = $this.data('info_bar-style');
            var info_bar            = $this;

            var hide_on_device      = $this.data('hide-on-devices');
            var hide_from_device    = hideOnDevice(hide_on_device);

            var toggle_visible      = jQuery(this).data('toggle-visible');

            var data                = {action:'smile_update_impressions',impression:true,style_id:style,option:opt};
            if( dev_mode == "enabled" ){
                removeCookie(cookieName);
            }
            var cookie      = getCookie(cookieName);
            var tmp_cookie  = getCookie(temp_cookie);
            if( !temp_cookie ){
                createCookie(temp_cookie,true,1);
            } else if( dev_mode == "enabled" && tmp_cookie ) {
                cookie = true;
            }

            var display = false;
            var inactive_time = $this.data('inactive-time');
            if( typeof inactive_time !== "undefined" ){
                display = true;
            }

            var page_down = $this.data('push-down');
            page_down = parseInt( page_down );
            var ib_height = $this.outerHeight();

            if( hide_from_device ) {
                display = false;
            }

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();
            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = info_bar.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( !cookie && display && referred ){
                if( jQuery(".ib-display").length <= 0 ){

                   apply_push_page_down(info_bar);

                    //info_bar.show();
                    if( info_bar.hasClass("cp-pos-top")){
                        if( jQuery("body").hasClass("admin-bar") ){
                            var ab_height = jQuery("#wpadminbar").height();
                            info_bar.css("top", ab_height+"px");
                        }
                    } else {
                        var cp_height       = info_bar.find(".cp-info-bar-body").outerHeight();
                         info_bar.css("min-height",cp_height+"px");
                    }

                    info_bar.addClass('ib-display');
                    jQuery(document).trigger('infobarOpen',[info_bar]);
                    if( !info_bar.hasClass('impression_counted') ) {
                        styleArray.push(style);
                        if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {
                            update_impressions(styleArray);

                            jQuery("[data-info_bar-style="+style+"]").each(function(e) {
                                jQuery(this).addClass('impression_counted');
                            });
                        }
                    }

                    setTimeout( function(){
                        var anim = info_bar.find(".cp-submit").data("animation");
                        info_bar.find(".cp-submit").addClass(anim);
                    }, 2000 );

                }
            }
        });
    });

    //close modal after few second
    jQuery(document).on( "idle.idleTimer", function(event, elem, obj){
        if( jQuery(".ib-display").hasClass('cp-close-after-x')){
            var info_bar = jQuery(".ib-display");
            jQuery(document).trigger('cp_close_info_bar',[info_bar]);
        }
    });

    // Display info bar on click of custom class
    jQuery(document).ready(function() {

        var cls = {};
        jQuery.each(jQuery('.cp-info-bar'),function(){
            var info_bar_container = jQuery(this);
            var info_bar_custom_class = jQuery(this).data('custom-class');
            if( typeof info_bar_custom_class !== "undefined" && info_bar_custom_class !== "" ){
                info_bar_custom_class = info_bar_custom_class.split(" ");
                jQuery.each( info_bar_custom_class, function(i,c){
                    if( typeof c !== "undefined" && c !== "" ){
                        cls[c] = info_bar_container;
                    }
                });
            }
        });

        jQuery.each(cls, function(i,v){
            if( '' != i && 'undefined' != i && null != i ) {
                jQuery("."+i).click(function(e){

                    var target      = v;
                    var styleArray = Array();

                    if( !target.hasClass('cp-form-submit-success') ) {

                        var exit        = target.data("exit-intent");
                        var class_id    = target.data("custom-class");
                        var cookieName  = target.data('info_bar-id');
                        var opt         = target.data('option');
                        var style       = target.data('info_bar-style');
                        var info_bar    = target;
                        var data        = {action:'smile_update_impressions',impression:true,style_id:style,option:opt};

                        var page_down = target.data('push-down');
                        page_down = parseInt( page_down );
                        var ib_height = target.outerHeight();

                        if(jQuery(".ib-display").length <= 0){

                            apply_push_page_down(info_bar);

                            //info_bar.show();
                            if( info_bar.hasClass("cp-pos-top")){
                                if( jQuery("body").hasClass("admin-bar") ){
                                    var ab_height = jQuery("#wpadminbar").outerHeight();
                                    info_bar.css("top", ab_height+"px");
                                }
                            } else {
                                var cp_height  = info_bar.find(".cp-info-bar-body").outerHeight();
                                info_bar.css("min-height",cp_height+"px");
                            }
                            info_bar.addClass('ib-display');
                            jQuery(document).trigger('infobarOpen',[info_bar]);
                            if( !info_bar.hasClass('impression_counted') ) {
                                styleArray.push(style);
                                if( styleArray.length !== 0 ) {
                                    update_impressions(styleArray);

                                    jQuery("[data-info_bar-style="+style+"]").each(function(e) {
                                        jQuery(this).addClass('impression_counted');
                                    });
                                }
                            }

                            setTimeout( function(){
                                var anim = info_bar.find(".ib-subscribe").data("animation");
                                info_bar.find(".ib-subscribe").addClass(anim);
                            }, 2000 );
                            jQuery.ajax({
                                url:smile_ajax.url,
                                data: data,
                                type: "POST",
                                dataType:"HTML",
                                success: function(result){
                                }
                            });
                        }

                    }
                });
            }
        });
    });

    function update_impressions(styles) {
        var data = {action:'smile_update_impressions',impression:true,styles:styles,option:'smile_info_bar_styles'};

        jQuery.ajax({
            url:smile_ajax.url,
            data: data,
            type: "POST",
            dataType:"HTML",
            success: function(result){
            }
        });
    }

    // calculate margin top for push down functionality
    function cal_top_margin_push_down(info_bar,animate_push_page, toggle ) {

        var cp_push_down_support_container = jQuery("#cp-push-down-support").val(); // Retrieve class / ID which user enter in &author setting
        var cp_top_offset_container = jQuery("#cp-top-offset-container").val();
        var wpadminbar = jQuery("#wpadminbar").outerHeight(); // Calculate WP admin Bar Height
        var ib_height = info_bar.outerHeight(); // Calculate Info Bar Height

        if( cp_top_offset_container == '' ) {
            var site_offset = jQuery('body').offset().top;
        	var offset_def_settings = {
                    margin_top: jQuery('body').css('margin-top'),
                    top:  jQuery('body').css('top'),
            };
        } else {

            if( jQuery(cp_top_offset_container).length > 0 ) {
                var site_offset = jQuery(cp_top_offset_container).offset().top;
                var offset_def_settings = {
                        margin_top: jQuery(cp_top_offset_container).css('margin-top'),
                        top:  jQuery(cp_top_offset_container).css('top'),
                };
            }
        }

        if( typeof offset_def_settings !== 'undefined' ) {
            var seetings_string = JSON.stringify(offset_def_settings);
            jQuery("#cp-top-offset-container").attr("data-offset_def_settings", seetings_string  );
        }

        var push_down_top = (ib_height + site_offset) - wpadminbar;
        var push_down_top_support = ib_height + site_offset;

        var cp_push_down_support_ht = jQuery(cp_push_down_support_container).outerHeight(); // Calculate height of user entered fixed class / ID
        var cp_push_down_support_htop = push_down_top_support - 0;

        if(toggle) {
            cp_push_down_support_htop = wpadminbar + ib_height;
            push_down_top = ib_height;
        }

        if( animate_push_page == 1 ) {
            jQuery(cp_push_down_support_container).stop().animate({ 'top': cp_push_down_support_htop + 'px' }, 1200 );
        } else {
            jQuery(cp_push_down_support_container).css( 'top', cp_push_down_support_htop + 'px' );
        }
        return push_down_top;
    }

    //placeholder color
    jQuery(document).ready(change_placeholdercolor);

    function change_placeholdercolor(){
        jQuery(".cp-info-bar").each(function() {
            var placeholder_color = jQuery(this).data("placeholder-color");
            var uid = jQuery(this).data("class");
            var defaultColor = placeholder_color;
            var styleContent = '.'+uid +' ::-webkit-input-placeholder {color: ' + defaultColor + '!important;} .'+uid+' :-moz-placeholder {color: ' + defaultColor + '!important;} .'+uid+' ::-moz-placeholder {color: ' + defaultColor + '!important;}';
            jQuery("<style type='text/css'>"+styleContent+"</style>").appendTo("head");

        });
    }

    //  Add height for - cp-flex
    //  min-height not works for IE 11
    function cp_set_ifb_ht( t ){
        var h   = parseInt(jQuery( t ).outerHeight()),
            vw  = jQuery(window).outerWidth();

        //  is IE browser?
        if( isBrowser.IE() ) {
            if( vw > 768 ) {
                jQuery( t ).find('.cp-info-bar-body').css({ 'height': h+'px' });
            } else {
                jQuery( t ).find('.cp-info-bar-body').css({ 'height': 'auto' });
            }

        }
    }

    function cp_ifb_color_for_list_tag( t ){
          var moadal_style    = jQuery(t).data('class');

        jQuery(t).find("li").each(function() {
            if(jQuery(this).parents(".cp_social_networks").length == 0){
               var parent_li   = jQuery(this).parents("div").attr('class').split(' ')[0];

               var  cnt         = jQuery(this).index()+1,
                    font_size   = jQuery(this).find(".cp_font").css("font-size"),
                    color       = jQuery(this).find("span").css("color"),
                    list_type   = jQuery(this).parent(),
                    list_type   = list_type[0].nodeName.toLowerCase(),
                    style_type  = '',
                    style_css   = '';

            //apply style type to list
            if( list_type == 'ul' ){
                    style_type = jQuery(this).closest('ul').css('list-style-type');
                    if( style_type == 'none' ){
                        jQuery(this).closest('ul').css( 'list-style-type', 'disc' );
                    }
            } else {
                style_type = jQuery(this).closest('ol').css('list-style-type');
                if( style_type == 'none' ){
                    jQuery(this).closest('ol').css( 'list-style-type', 'decimal' );
                }
            }

            //apply color to list
            jQuery(this).find("span").each(function(){
                var spancolor = jQuery(this).css("color");
                if(spancolor.length > 0){
                    color = spancolor;
                }
            });

            var font_style ='';
            jQuery(".cp-li-color-css-"+cnt).remove();
            jQuery(".cp-li-font-css-"+cnt).remove();
            if(font_size){
               font_style = 'font-size:'+font_size;
               jQuery('head').append('<style class="cp-li-font-css'+cnt+'">.'+moadal_style+' .'+parent_li+' li:nth-child('+cnt+'){ '+font_style+'}</style>');
            }
            if(color){
              jQuery('head').append('<style class="cp-li-color-css'+cnt+'">.'+moadal_style+' .'+parent_li+' li:nth-child('+cnt+'){ color: '+color+';}</style>');
            }
        }

        });
    }

    // This function will apply push page down to info bar
    function apply_push_page_down(info_bar) {

        setTimeout(function() {

            var has_toggle_btn  = info_bar.data('toggle');
            var toggle_visible = info_bar.data('toggle-visible') || null;
            var toggle = false;
            push_page_down( info_bar, toggle, toggle_visible );

        }, 300);
    }
    function push_page_down(info_bar, toggle, toggle_visible ) {
        var page_down = info_bar.data('push-down') || null;
        var animate_push_page = info_bar.data('animate-push-page');
        var cp_top_offset_container = jQuery("#cp-top-offset-container").val();

        if( page_down && !toggle_visible ) {
            if( info_bar.hasClass("cp-pos-top") ){

                var push_margin = cal_top_margin_push_down(info_bar,animate_push_page, toggle);
                if( animate_push_page == 1 )  {
                	if( cp_top_offset_container == '' ) {
                    	jQuery("body").stop().animate({'marginTop':push_margin+'px'}, 900 );
                	} else {
                		jQuery(cp_top_offset_container).stop().animate({'marginTop': push_margin+'px'}, 900 );
                	}
                } else {
                    if( cp_top_offset_container == '' ) {
                        jQuery("body").css( 'margin-top', push_margin+'px' );
                    } else {
                    	jQuery(cp_top_offset_container).css( 'margin-top', push_margin+'px' );
                    }
                }
            }
        }
    }

    //for open and close info bar on click of button
    function cp_ifb_toggle(){

        jQuery(".cp-info-bar").each(function(index, el) {

            var info_bar = jQuery( el );

            info_bar.find( ".cp-ifb-toggle-btn" ).click(function() {

                var cp_ifb_toggle_btn   = jQuery(this),
                    cp_info_bar         = jQuery(this).closest('.cp-info-bar'),
                    btn_animation       = 'smile-slideInDown',
                    exit_animation      = cp_info_bar.data("exit-animation"),
                    entry_animation     = cp_info_bar.data("entry-animation"),
                    cp_info_bar_body    = cp_info_bar.find(".cp-info-bar-body"),
                    toggle_visibility   = cp_info_bar.data('toggle-visible'),
                    is_imp_added       = cp_info_bar.data('impression-added'),
                    style_id           = cp_info_bar.data('info_bar-id');

                if( toggle_visibility == true ) {
                    if( typeof is_imp_added == 'undefined' ) {
                        var styleArray = [style_id];
                        update_impressions( styleArray );
                        cp_info_bar.data('impression-added','true');
                    }
                }

                var toggle = false;
                var toggle_visible = null;
                push_page_down( cp_info_bar, toggle, toggle_visible );

                cp_info_bar.removeClass( entry_animation );
                cp_info_bar.removeClass( exit_animation );

                if( cp_info_bar.hasClass('cp-pos-bottom') ) {
                    btn_animation = 'smile-slideInUp';
                }

                var  cp_info_bar_class    = cp_info_bar.attr('class');

                cp_ifb_toggle_btn.removeClass('cp-ifb-show smile-animated '+ btn_animation +'');
                cp_info_bar.attr('class',cp_info_bar_class);
                cp_info_bar.attr('class', cp_info_bar_class + ' smile-animated ' + entry_animation);
                cp_info_bar.removeClass('cp-ifb-hide');

                cp_ifb_toggle_btn.addClass('cp-ifb-hide');
                cp_info_bar_body.addClass('cp-flex');
                cp_info_bar.find( ".ib-close" ).css({
                    'visibility': 'visible'
                });

                var toggle = true;

                push_page_down( info_bar, toggle );

            });

            //click of close button
            info_bar.find( ".ib-close" ).click(function() {

                var cp_info_bar     =   jQuery(this).parents(".cp-info-bar"),
                cp_ifb_toggle_btn   =   cp_info_bar.find(".cp-ifb-toggle-btn"),
                cp_info_bar_body    =   cp_info_bar.find(".cp-info-bar-body"),
                btn_animation       =   'smile-slideInDown',
                exit_animation      =   cp_info_bar.data("exit-animation"),
                entry_animation     =   cp_info_bar.data("entry-animation"),
                data_toggle         =   cp_info_bar.data("toggle"),
                form                =   cp_info_bar.find('.form-main').attr('class');

                if(data_toggle == 1){

                    //  Toggle button animation class
                    if(cp_info_bar.hasClass('cp-pos-bottom')){
                       btn_animation = 'smile-slideInUp';
                    }

                    cp_info_bar.removeClass(entry_animation);
                    var  cp_info_bar_class   = cp_info_bar.attr('class');
                    cp_info_bar.attr('class', cp_info_bar_class + ' ' + exit_animation);

                    setTimeout(function() {
                        //  Toggle button animation
                        cp_ifb_toggle_btn.removeClass('cp-ifb-hide');
                        cp_ifb_toggle_btn.addClass('cp-ifb-show smile-animated '+btn_animation +'');
                        cp_info_bar.removeClass('smile-animated');
                        cp_info_bar.removeClass(exit_animation);
                        cp_info_bar.addClass('cp-ifb-hide');
                        cp_info_bar_body.removeClass('cp-flex');
                        cp_info_bar.find( ".ib-close" ).css({
                            'visibility': 'hidden'
                        });
                        if(typeof form !== 'undefined'){
                            cp_info_bar.find('#smile-optin-form')[0].reset();
                            cp_info_bar.find(".cp-form-processing-wrap").css('display', 'none');
                            cp_info_bar.find(".cp-form-processing").removeAttr('style');
                            cp_info_bar.find(".cp-msg-on-submit").removeAttr('style');
                            cp_info_bar.find(".cp-m-success").remove();
                            cp_info_bar.find(".cp-m-error").remove();
                        }
                    }, 1500 );
                }
            });

        });
    }

    jQuery(document).on("ib_conversion_done", function(e, $this){
        // do your stuff
        if( !jQuery( $this ).parents(".cp-form-container").find(".cp-email").length > 0 ){
            var is_only_conversion = jQuery( $this ).parents(".cp-form-container").find('[name="only_conversion"]').length;

            if ( is_only_conversion > 0 ) {
                jQuery($this).addClass('disabled');
            }
        }
    });

    // check if element is visible in view port
    function isScrolledIntoStyleView(elem) {
        var $elem = elem;
        var $window = $(window);

        var docViewTop = $window.scrollTop();
        var docViewBottom = docViewTop + $window.height();

        var elemTop = $elem.offset().top;
        var elemBottom = elemTop + $elem.height();

        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }

    function count_inline_impressions()  {
        jQuery(".cp-info_bar-inline-end").each(function(e) {
            var elem = jQuery(this);
            var is_visible = isScrolledIntoStyleView(elem);
            var style_id = jQuery(this).data('style');

            if( is_visible ) {
                var styleArray = Array();
                if( !jQuery("[data-info_bar-style="+style_id+"]").hasClass('impression_counted') ) {
                    styleArray.push(style_id);
                    update_impressions(styleArray);
                }
                jQuery("[data-info_bar-style="+style_id+"]").each(function() {
                    jQuery(this).addClass('impression_counted');
                });
            }
        });
    }

/*
 * for social media responsive icon*
 */
function cp_infobar_social_responsive(){

    var wh = jQuery(window).width();
     jQuery(".cp_social_networks").each(function() {
        var column_no = jQuery(this).data('column-no');
        var classname ='';
        if(wh < 768){
            jQuery(this).removeClass('cp_social_networks');
            jQuery(this).removeClass(column_no);
            classname =  jQuery(this).attr('class');
            jQuery(this).attr('class', 'cp_social_networks cp_social_autowidth ' + ' ' + classname );
        }else{
            jQuery(this).removeClass('cp_social_networks');
           jQuery(this).removeClass('cp_social_autowidth');
           jQuery(this).removeClass(column_no);
             classname =  jQuery(this).attr('class');
            jQuery(this).attr('class', 'cp_social_networks ' + ' ' + column_no + ' ' + classname );
        }
     });
}

 jQuery(document).on("infobarOpen", function(e,data) {

    var close_btn_delay               = data.data("close-btnonload-delay");

    // convert delay time from seconds to miliseconds
    close_btn_delay                   = Math.round(close_btn_delay * 1000);

    if(close_btn_delay){
        setTimeout( function(){
              data.find('.ib-close').removeClass('cp-hide-close');
        },close_btn_delay);
    }

        //for close modal after x  sec of inactive
        var inactive_close_time = data.data('close-after');

        jQuery.idleTimer('destroy');
        if( typeof inactive_close_time !== "undefined" ) {
            inactive_close_time = inactive_close_time*1000;
            setTimeout(function(){
                data.addClass('cp-close-after-x');
            }, inactive_close_time );

            jQuery(document).idleTimer( {
                timeout: inactive_close_time,
                idle: false
            });
        }

 });

 //Open infobar scroll upto particular class/id
    var ifb_scrollcls = [];
        jQuery.each(jQuery('.cp-info-bar'),function(){
            var ifb_scroll_class = jQuery(this).data('scroll-class');
            if( typeof ifb_scroll_class !== "undefined" && ifb_scroll_class !== "" ){
                ifb_scroll_class = ifb_scroll_class.split(" ");
                jQuery.each( ifb_scroll_class, function(i,c){
                    ifb_scrollcls.push(c);
                });
            }

        });
    jQuery.each(ifb_scrollcls, function(i,v){
       jQuery(document).scroll(function(e){

        // count inline impressions
        count_inline_impressions();

        // calculate the percentage the user has scrolled down the page
        var scrollPercent = 100 * jQuery(window).scrollTop() / (jQuery(document).height() - jQuery(window).height());
        var scrolled = scrollPercent.toFixed(0);
        var styleArray = Array();
        jQuery(".cp-ib-onload").each(function(t) {
            var $this = jQuery(this);
            var exit                = jQuery(this).data("exit-intent");
            var class_id            = jQuery(this).data("class");
            var dev_mode            = jQuery(this).data("dev-mode");
            var cookieName          = jQuery(this).data('info_bar-id');
            var temp_cookie         = "temp_"+cookieName;
            var opt                 = jQuery(this).data('option');
            var style               = jQuery(this).data('info_bar-style');
            var info_bar            = jQuery(this);
            var scrollclass         = v;
            var scrollTill          = '';
            if( typeof scrollclass !== 'undefined' && scrollclass !== ' ' ){
                var div_ht = jQuery(scrollclass).outerHeight();
                var position    = jQuery(scrollclass).position();
                 if( typeof position !== 'undefined' && position !== ' ' ){

                    scrollTill = jQuery(scrollclass).cp_ifb_isOnScreen();
                }
            }

            var hide_on_device      = jQuery(this).data('hide-on-devices');
            var hide_from_device    = hideOnDevice(hide_on_device);

            var toggle_visible      = jQuery(this).data('toggle-visible');

            var data                = {action:'smile_update_impressions',impression:true,style_id:style,option:opt};
            if( dev_mode == "enabled" ){
                removeCookie(cookieName);
            }
            var cookie              = getCookie(cookieName);
            var tmp_cookie          = getCookie(temp_cookie);
            if( !temp_cookie ){
                createCookie(temp_cookie,true,1);
            } else if( dev_mode == "enabled" && tmp_cookie ) {
                cookie = true;
            }

            var scheduled = info_bar.isScheduled();

            if( hide_from_device ) {
                cookie = scrollTill = scheduled = false;
            }

            var page_down = jQuery(this).data('push-down');
            page_down = parseInt( page_down );
            var ib_height = jQuery(this).outerHeight();

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();
            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = info_bar.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( !cookie && scrollTill && scheduled && referred ){
                if( jQuery(".ib-display").length <= 0 ){
                    if( scrollTill == true ){

                        apply_push_page_down(info_bar);

                        if( !info_bar.hasClass('impression_counted') ) {
                            styleArray.push(style);
                            if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {
                                update_impressions(styleArray);

                                jQuery("[data-info_bar-style="+style+"]").each(function(e) {
                                    jQuery(this).addClass('impression_counted');
                                });
                            }
                        }

                        //info_bar.show();
                        if( info_bar.hasClass("cp-pos-top")){
                            if( jQuery("body").hasClass("admin-bar") ){
                                var ab_height = jQuery("#wpadminbar").outerHeight();
                                info_bar.css("top", ab_height+"px");
                            }
                        } else {
                            var cp_height       = info_bar.find(".cp-info-bar-body").outerHeight();
                            info_bar.css("min-height",cp_height+"px");
                        }
                        info_bar.addClass('ib-display');
                        jQuery(document).trigger('infobarOpen',[info_bar]);
                        setTimeout( function(){
                            var anim = info_bar.find(".cp-submit").data("animation");
                            info_bar.find(".cp-submit").addClass(anim);
                        }, 2000 );
                    }
                }
            }
        });

    });
});

// check whether div is in viewport or not?
jQuery.fn.cp_ifb_isOnScreen = function(){

    var win = $(window);

    var viewport = {
        top : win.scrollTop(),
        left : win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();

    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();

    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));

};

})(jQuery);
