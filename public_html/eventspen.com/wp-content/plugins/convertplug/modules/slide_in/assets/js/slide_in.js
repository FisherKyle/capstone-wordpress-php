(function($) {
    "use strict";


    jQuery(document).on('smile_data_received',function(e,data){
        CPResponsiveTypoInit();
    });

    function getSlidePrioritized(){
        var slide = 'none';
        jQuery(".si-onload").each(function(t,v) {
            var class_id = jQuery(this).data("class-id");
            var hasClass = jQuery(this).hasClass("priority_slidein");
            if( hasClass ){
                slide = jQuery('.'+class_id);
                return slide;
            }
        });
        return slide;
    }

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

    jQuery.fn.isScheduled = function(){
        var y = new Date(gmt) ;
        var timestring = this.data('timezonename');
        var tzoffset = this.data('tz-offset');

        var gtime = y.toGMTString();
        var ltime = y.toLocaleString();

        var date = new Date();

        // turn date to utc
        var utc = date.getTime() + (date.getTimezoneOffset() * 60000);

        // set new Date object
        var new_date = new Date(utc + (3600000*tzoffset));

        var scheduled = this.data('scheduled');

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

    // Set cookies.

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

    // Removes cookies.
    var removeCookie = function(name){
        createCookie(name, '', -1);
    }

    // Display slide in on page load after x seconds
    jQuery(window).on( 'load', function() {

         var styleArray = Array();
        jQuery(".si-onload").each(function(t) {
            var $this = jQuery(this);
            var class_id            = jQuery(this).data("class-id");
            var dev_mode            = jQuery(this).data("dev-mode");
            var cookieName          = jQuery('.'+class_id).data('slidein-id');
            var toggle_visible      = jQuery(this).data('toggle-visible');
            var temp_cookie         = "temp_"+cookieName;
            removeCookie(temp_cookie);

            var exit                = jQuery(this).data("exit-intent");
            var opt                 = jQuery('.'+class_id).data('option');
            var style               = jQuery('.'+class_id).data('slidein-style');
            var slidein             = jQuery('.'+class_id);
            var delay               = jQuery(this).data("onload-delay");
            // convert delay time from seconds to milliseconds
            delay                   = delay * 1000;
            var load_on_refresh     = jQuery('.'+class_id).data('load-on-refresh');
            var scrollPercent       = 100 * jQuery(window).scrollTop() / (jQuery(document).height() - jQuery(window).height());
            var scrollTill          = jQuery(this).data("onscroll-value");

            var display = false;
            var scheduled = slidein.isScheduled();
            var slidein_container = jQuery(this).closest('.cp-slidein-popup-container');

            var hide_on_device = jQuery(this).data('hide-on-devices');
            var hide_from_device = hideOnDevice(hide_on_device);

            if( load_on_refresh == "disabled" ) {
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

            if( hide_from_device ) {
                display = false;
            }


            var cookie     = getCookie(cookieName);
            var tmp_cookie = getCookie(temp_cookie);
            if( dev_mode == "enabled") {
                if( tmp_cookie ) {
                    cookie = true;
                } else {
                    cookie = getCookie(cookieName);
                }
                 removeCookie(cookieName+'-conversion');
            } else {
                cookie = getCookie(cookieName);              
                if( cookie && slidein.hasClass('always-minimize-widget')){ 
                    slidein.addClass('minimize-widget');
                    cookie = false;
                }
               var conversion_cookies  = getCookie(cookieName+'-conversion');
               if(conversion_cookies && slidein.hasClass('always-minimize-widget')){   
                  //          
                  cookie = true;
               }
            }

            if( cookie == null ){
                cookie = false;
            }

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();

            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = slidein.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }
           
            //console.log("conversion_cookies-"+conversion_cookies);
            //console.log("cookie-"+cookie);

            if( !cookie && delay && display && scheduled && referred ){
                 if( jQuery(".si-open").length <= 0 ) {
                    setTimeout(function() {
                        cookie = getCookie(cookieName);
                        var tmp_cookie = getCookie(temp_cookie);
                        var display = false;
                        if( dev_mode == "enabled" ) {
                            if( tmp_cookie ) {
                                display = false;
                            } else {
                                if( cookie == null )
                                    display = true;
                                else
                                    display = false;
                            }
                        } else {
                            if( cookie == null  )
                                display = true;
                            else
                                display = false;
                        }
                        if( jQuery(".si-open").length <= 0 ){
                            display = true;
                        } else {
                            display = false;
                        }

                        if( display ) {
                            adjustToggleButton(slidein_container);
                            jQuery(window).trigger('slideinOpen',[slidein]);
                            slidein.show();
                            jQuery(document).trigger('resize');
                            slidein.addClass('si-open');

                            if( !slidein.hasClass('impression_counted') ) {
                                styleArray.push(style);
                                if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {
                                    update_impressions(styleArray);

                                    jQuery("[data-slidein-style="+style+"]").each(function(e) {
                                        jQuery(this).addClass('impression_counted');
                                    });
                                }
                            }

                        }
                    }, parseInt(delay));
                }
            }

            if( dev_mode == "enabled" ){
                removeCookie(cookieName);
            }
        });

    });

    // Display slide in on page scroll after x percentage
    jQuery(document).scroll(function(e){

        // count inline impressions
        count_inline_impressions();

        // calculate the percentage the user has scrolled down the page
        var scrollPercent = 100 * jQuery(window).scrollTop() / (jQuery(document).height() - jQuery(window).height());
        var scrolled = scrollPercent.toFixed(0);
        var styleArray = Array();
        jQuery(".si-onload").each(function(t) {
            var $this = jQuery(this);
            var exit        = jQuery(this).data("exit-intent");
            var class_id    = jQuery(this).data("class-id");
            var dev_mode    = jQuery(this).data("dev-mode");
            var cookieName  = jQuery('.'+class_id).data('slidein-id');
            var toggle_visible      = jQuery(this).data('toggle-visible');
            var temp_cookie     = "temp_"+cookieName;
            var opt         = jQuery('.'+class_id).data('option');
            var style       = jQuery('.'+class_id).data('slidein-style');
            var slidein       = jQuery('.'+class_id);
            var scrollTill  = jQuery(this).data("onscroll-value");

            var hide_on_device = jQuery(this).data('hide-on-devices');
            var hide_from_device = hideOnDevice(hide_on_device);
            var slidein_Container = jQuery(this).closest('.cp-slidein-popup-container');

            var data        = {action:'smile_update_impressions',impression:true,style_id:style,option:opt};
            if( dev_mode == "enabled" ){
                removeCookie(cookieName);
            }
            var cookie      = getCookie(cookieName);
            var tmp_cookie  = getCookie(temp_cookie);
            if( !temp_cookie ){
                createCookie( temp_cookie, true, 1 );
            } else if( dev_mode == "enabled" && tmp_cookie ) {
                cookie = true;
            }

            var scheduled = slidein.isScheduled();

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();
            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = slidein.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( hide_from_device ) {
                cookie = scrollTill = scheduled = referred = false;
            }

            if( dev_mode == "disabled") {
                if( cookie && slidein.hasClass('always-minimize-widget')){ 
                    slidein.addClass('minimize-widget');
                    cookie = false;
                }
               var conversion_cookies  = getCookie(cookieName+'-conversion');
               if(conversion_cookies && slidein.hasClass('always-minimize-widget')){                         
                  cookie = true;
               }
            }


            if( !cookie && scrollTill && scheduled && referred ){
                if(jQuery(".si-open").length <= 0 ){
                    if( scrolled >= scrollTill  ) {
                        adjustToggleButton(slidein_Container);
                        jQuery(window).trigger('slideinOpen',[slidein]);
                        slidein.show();
                        jQuery(document).trigger('resize');
                        slidein.addClass('si-open');

                        if( !slidein.hasClass('impression_counted') ) {
                            styleArray.push(style);
                            if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {
                                update_impressions(styleArray);

                                jQuery("[data-slidein-style="+style+"]").each(function(e) {
                                    jQuery(this).addClass('impression_counted');
                                });
                            }
                        }
                    }
                }
            }
        });

    });

    // Display slide in on page scroll after post content
    jQuery(document).scroll(function(e){

        // calculate the percentage the user has scrolled down the page
        var scrollPercent = 100 * jQuery(window).scrollTop() / (jQuery(document).height() - jQuery(window).height());
        var scrolled = jQuery(window).scrollTop();
        var styleArray = Array();
        jQuery(".si-after-post").each(function(t) {
            var $this = jQuery(this);
            var exit        = jQuery(this).data("exit-intent");
            var class_id    = jQuery(this).data("class-id");
            var dev_mode    = jQuery(this).data("dev-mode");
            var scrollValue = jQuery(this).data("after-content-value");
            var cookieName  = jQuery('.'+class_id).data('slidein-id');
            var temp_cookie     = "temp_"+cookieName;
            var opt         = jQuery('.'+class_id).data('option');
            var style       = jQuery('.'+class_id).data('slidein-style');
            var slidein       = jQuery('.'+class_id);
            var scrollTilllength  = jQuery(".cp-load-after-post").length;
            if( scrollTilllength > 0 ){
                var scrollTill  = jQuery(".cp-load-after-post").offset().top - 30;
            var hide_on_device = jQuery(this).data('hide-on-devices');
            var hide_from_device = hideOnDevice(hide_on_device);
            var slidein_Container = jQuery(this).closest('.cp-slidein-popup-container');

            var data        = {action:'smile_update_impressions',impression:true,style_id:style,option:opt};
            if( dev_mode == "enabled" ){
                removeCookie(cookieName);
            }
            var cookie      = getCookie(cookieName);
            var tmp_cookie  = getCookie(temp_cookie);
            if( !temp_cookie ){
                createCookie( temp_cookie, true, 1 );
            } else if( dev_mode == "enabled" && tmp_cookie ) {
                cookie = true;
            }

            var scheduled = slidein.isScheduled();
            scrollTill = scrollTill - ( ( jQuery(window).height() * scrollValue ) / 100 );

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();
            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = slidein.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( hide_from_device ) {
                cookie = scrollTill = scheduled = referred = false;
            }

            if( dev_mode == "disabled") {
                if( cookie && slidein.hasClass('always-minimize-widget')){ 
                    slidein.addClass('minimize-widget');
                    cookie = false;
                }
               var conversion_cookies  = getCookie(cookieName+'-conversion');
               if(conversion_cookies && slidein.hasClass('always-minimize-widget')){   
                  cookie = true;
               }
            }

            if( !cookie && scrollTill && scheduled && referred ){
                if(jQuery(".si-open").length <= 0 ){
                    if( scrolled >= scrollTill  ){
                        adjustToggleButton(slidein_Container);
                        jQuery(window).trigger('slideinOpen',[slidein]);
                        slidein.show();
                        jQuery(document).trigger('resize');
                        slidein.addClass('si-open');

                        if( !slidein.hasClass('impression_counted') ) {
                            styleArray.push(style);
                            if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {
                                update_impressions(styleArray);

                                jQuery("[data-slidein-style="+style+"]").each(function(e) {
                                    jQuery(this).addClass('impression_counted');
                                });
                            }
                        }
                    }
                }
            }
        }
    });

});

    // Load the exit intent handler.
    jQuery(document).on('mouseleave', function(e){
        var styleArray = Array();
        var getPrioritySlidein = getSlidePrioritized();

        jQuery(".si-onload").each(function(t) {
            var $this = jQuery(this);
            if( getPrioritySlidein !== "none" ){
                var slide = getPrioritySlidein;
                var slidein_id = slide.data("slidein-id");
                $this = jQuery(".cp-"+slidein_id);
            }

            var exit        = $this.data("exit-intent");
            var class_id    = $this.data("class-id");
            var dev_mode    = $this.data("dev-mode");
            var cookieName  = jQuery('.'+class_id).data('slidein-id');
            var toggle_visible      = jQuery(this).data('toggle-visible');
            var temp_cookie = "temp_"+cookieName;
            var opt         = jQuery('.'+class_id).data('option');
            var style       = jQuery('.'+class_id).data('slidein-style');
            var slidein     = jQuery('.'+class_id);

            var hide_on_device = jQuery(this).data('hide-on-devices');
            var hide_from_device = hideOnDevice(hide_on_device);
            var slidein_container = jQuery(this).closest('.cp-slidein-popup-container');

            var data        = {action:'smile_update_impressions',impression:true,style_id:style,option:opt};
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

            var scheduled = slidein.isScheduled();

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();
            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ) {
                referred = slidein.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( hide_from_device ) {
                exit = scheduled = referred = false;
            }

            if( dev_mode == "disabled") {
                if( cookie && slidein.hasClass('always-minimize-widget')){ 
                    slidein.addClass('minimize-widget');
                    cookie = false;
                }
               var conversion_cookies  = getCookie(cookieName+'-conversion');
               if(conversion_cookies && slidein.hasClass('always-minimize-widget')){   
                  cookie = true;
               }
            }

            if( !cookie && referred ){
                if( exit == 'enabled' && scheduled ){
                    if ( e.clientY <= 0 ){
                        if( jQuery(".si-open").length <= 0 ){

                            adjustToggleButton(slidein_container);
                            jQuery(window).trigger('slideinOpen',[slidein]);
                            slidein.show();
                            jQuery(document).trigger('resize');
                            slidein.addClass('si-open');

                            if( !slidein.hasClass('impression_counted') ) {
                                styleArray.push(style);
                                if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {
                                    update_impressions(styleArray);

                                    jQuery("[data-slidein-style="+style+"]").each(function(e) {
                                        jQuery(this).addClass('impression_counted');
                                    });
                                }
                            }
                        }
                    }
                }
            }
        });
    });

    // Load the user activity handler
    jQuery(document).ready(function(){

        // count inline impressions
        count_inline_impressions();

        jQuery('.blinking-cursor').remove();

        jQuery(".si-onload").each(function(t) {
            var inactive_time = jQuery(this).data('inactive-time');
            if( typeof inactive_time !== "undefined" ) {
                inactive_time = inactive_time*1000;
                jQuery(document).idleTimer( {
                    timeout: inactive_time,
                    idle: false
                });
            }
        });

        //  Set normal values in data attribute to reset these on window resize
        CPResponsiveTypoInit();

        //for open and close slidein on click of button
        cp_slide_slidein();

        // Check and enable js api on youtube videos
        jQuery.each(jQuery(".si-onload"), function(){
            var cls_id      = jQuery(this).data('class-id');
            var slidein       = jQuery('.'+cls_id);
            var iframes     = slidein.find('iframe');

            jQuery.each(iframes, function( index, iframe ){
                var src = iframe.src;
                var youtube = src.search('youtube.com');
                var vimeo = src.search('vimeo.com');
                src = src.replace("&autoplay=1","");
                if( youtube !== -1 ){
                    var yt_src = ( src.indexOf("?") === -1 ) ? src+'?enablejsapi=1' : src+'&enablejsapi=1';
                    iframe.src = yt_src;
                    iframe.id = 'yt-'+cls_id;
                }
                if( vimeo !== -1 ){
                    var vm_src = ( src.indexOf("?") === -1 ) ? src+'?api=1' : src+'&api=1';
                    iframe.src = iframe.src+'?api=1';
                    iframe.id = 'vim-'+cls_id;
                }
            });
        });

        var cls = new Array();
        var styleArray = Array();

        // Display slide in on click of custom class
        jQuery.each(jQuery('.slidein-overlay'),function(){
            var slidein_custom_class = jQuery(this).data('custom-class');

            if( typeof slidein_custom_class !== "undefined" && slidein_custom_class !== "" ){
                slidein_custom_class = slidein_custom_class.split(" ");

                jQuery.each( slidein_custom_class, function(i,c){
                    cls.push(c);
                });
            }
        });

        jQuery.each(cls, function(i,v){

                jQuery("."+v).click(function(e){

                    e.preventDefault();
                    var target      = jQuery(".cp-slidein-global."+v);

                if( !target.siblings('.cp-slidein-popup-container').find('.cp-animate-container').hasClass('cp-form-submit-success') ) {
                    var exit        = target.data("exit-intent");
                    var class_id    = target.data("class-id");
                    var cookieName  = jQuery('.'+class_id).data('slidein-id');
                    var toggle_visible = target.data('toggle-visible');
                    var opt         = jQuery('.'+class_id).data('option');
                    var style       = jQuery('.'+class_id).data('slidein-style');
                    var slidein     = jQuery('.'+class_id);

                    var data        = {action:'smile_update_impressions',impression:true,style_id:style,option:opt};

                    var hide_on_device = target.data('hide-on-devices');
                    var hide_from_device = hideOnDevice(hide_on_device);

                    if( jQuery(".si-open").length <= 0 ){

                        jQuery(window).trigger('slideinOpen',[slidein]);
                        slidein.show();
                        slidein.addClass('si-open');

                        if( !slidein.hasClass('impression_counted') ) {
                            styleArray.push(style);
                            if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {
                                update_impressions(styleArray);

                                jQuery("[data-slidein-style="+style+"]").each(function(e) {
                                    jQuery(this).addClass('impression_counted');
                                });
                            }
                        }
                        var cp_tooltip  =  slidein.find(".cp-tooltip-icon").data('classes');
                        jQuery('head').append('<style class="cp-tooltip-hide">.tip.'+cp_tooltip+'{ display:block; }</style>');

                    }
                }
            });
        });

        // Placeholder css
        add_placeholdercolor_css();

        // Initialize tool tip
        close_button_tootip();

    });

    // Close Slide In on click of close button
    jQuery(document).on("closeSlideIn", function(e,slidein){
        var container   = slidein.parents(".cp-slidein-popup-container");
        var template    = container.data('template');
        var cookieTime  = slidein.data('closed-cookie-time');
        var cookieName  = slidein.data('slidein-id');
        var cp_animate  = slidein.find('.cp-animate-container');
        var entry_anim  = slidein.data('overlay-animation');
        var exit_anim   = cp_animate.data('exit-animation');
        var temp_cookie = "temp_"+cookieName;
        createCookie(temp_cookie,true,1);
        var cookie      = getCookie(cookieName);
        e.preventDefault();
        if(!cookie){
            if(cookieTime){
                createCookie(cookieName,true,cookieTime);
            }
        }

        if( slidein.hasClass('cp-hide-inline-style') || slidein.hasClass('cp-close-slidein') ){
            exit_anim = "cp-overlay-none";                
        }

        if( slidein.hasClass('cp-close-slidein') ){            
            slidein.removeClass("si-open");
        }

        var animatedwidth = cp_animate.data('disable-animationwidth');
        var vw = jQuery(window).width();
        if( exit_anim == "cp-overlay-none" || ( typeof animatedwidth !== 'undefined' && vw <= animatedwidth ) ){
            if(slidein.hasClass('cp-slide-without-toggle')){
                slidein.removeClass("si-open");
            }
            exit_anim = "cp-overlay-none";
            if( jQuery(".cp-slidein-global.si-open").length < 1 ){
                jQuery("html").removeAttr('style');
            }
        }

        if( !template ){
            cp_animate.removeClass( entry_anim );

            var animatedwidth = cp_animate.data('disable-animationwidth');
            var vw = jQuery(window).width();
            if( vw >= animatedwidth || typeof animatedwidth == 'undefined' ){
                cp_animate.addClass( exit_anim );
            }

            if( exit_anim !== "cp-overlay-none" ){

                setTimeout( function(){

                    if(slidein.hasClass('cp-slide-without-toggle')){
                        slidein.removeClass("si-open");
                    }

                    if( jQuery(".cp-slidein-global.si-open").length < 1 ){
                        jQuery("html").removeAttr('style');
                    }
                    setTimeout( function(){
                           if( !slidein.hasClass('do_not_close')){
                                cp_animate.removeClass(exit_anim);
                            }
                        });
                }, 1000 );
            }
        }
    });

    jQuery(document).on("click", ".slidein-overlay", function(e){
        if( !jQuery(this).hasClass('do_not_close') && jQuery(this).hasClass('close_btn_nd_overlay') ){
            var slidein       = jQuery(this);
            jQuery(document).trigger('closeSlideIn',[slidein]);
        }
    });

    jQuery(document).on( "idle.idleTimer", function(event, elem, obj){
        var styleArray = Array();
        var getPrioritySlidein = getSlidePrioritized();
        jQuery(".si-onload").each(function(t) {
            var $this = jQuery(this);
            if( getPrioritySlidein !== "none" ){
                var slide = getPrioritySlidein;
                var slide_id = slide.data("slidein-id");
                $this = jQuery(".cp-"+slide_id);
            }
            var exit        = $this.data("exit-intent");
            var class_id    = $this.data("class-id");
            var dev_mode    = $this.data("dev-mode");
            var cookieName  = jQuery('.'+class_id).data('slidein-id');
            var toggle_visible = $this.data("toggle-visible");
            var opt         = jQuery('.'+class_id).data('option');
            var style       = jQuery('.'+class_id).data('slidein-style');
            var slidein       = jQuery('.'+class_id);

            var hide_on_device   = jQuery(this).data('hide-on-devices');
            var hide_from_device = hideOnDevice(hide_on_device);
            var slidein_container = jQuery(this).closest('.cp-slidein-popup-container');

            var data        = {action:'smile_update_impressions',impression:true,style_id:style,option:opt};
            if( dev_mode == "enabled" ){
                removeCookie(cookieName);
            }
            var cookie      = getCookie(cookieName);

            var display = false;
            var inactive_time = jQuery(this).data('inactive-time');
            if( typeof inactive_time !== "undefined" ){
                display = true;
            }

            if( hide_from_device ) {
                display = false;
            }

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();
            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = slidein.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( dev_mode == "disabled") {
                if( cookie && slidein.hasClass('always-minimize-widget')){                  
                    slidein.addClass('minimize-widget');
                    cookie = false;
                }
               var conversion_cookies  = getCookie(cookieName+'-conversion');
               if(conversion_cookies && slidein.hasClass('always-minimize-widget')){                             
                  cookie = true;
               }
            }

            if( !cookie && display && referred ){
                if(jQuery(".si-open").length <= 0 ){
                    adjustToggleButton(slidein_container);
                    jQuery(window).trigger('slideinOpen',[slidein]);
                    slidein.show();
                    if( slidein.hasClass('cp-window-size') ){
                        slidein.windowSize();
                    }

                    jQuery(document).trigger('resize');
                    slidein.addClass('si-open');

                    if( !slidein.hasClass('impression_counted') ) {
                        styleArray.push(style);
                        if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {
                            update_impressions(styleArray);

                            jQuery("[data-slidein-style="+style+"]").each(function(e) {
                                jQuery(this).addClass('impression_counted');
                            });
                        }
                    }
                }
            }
        });
    });

    jQuery(document).on( "idle.idleTimer", function(event, elem, obj){
        if( jQuery(this).find(".slidein-overlay").hasClass('cp-close-after-x')){
            //console.log("here");
            var slidein = jQuery(this).find(".slidein-overlay");
            jQuery(document).trigger('closeSlideIn',[slidein]);
        }

    });

    //close slide in on cp-close class
    jQuery(document).on("click", ".cp-close", function(e){
        if( !jQuery(this).parents(".slidein-overlay").hasClass('do_not_close') ){
            var slidein       =  jQuery(this).parents(".slidein-overlay");
            jQuery(document).trigger('closeSlideIn',[slidein]);
        }
    });

     //close slide in on cp-inner-close class
    jQuery(document).on("click", ".cp-inner-close", function(e){
        var slidein       =  jQuery(this).parents(".slidein-overlay");
        jQuery(document).trigger('closeSlideIn',[slidein]);
    });

    jQuery(document).on("click", ".slidein-overlay .cp-slidein", function(e){
        e.stopPropagation();
    });

    // Update impressions for style
    function update_impressions(styles) {
        var data = {action:'smile_update_impressions',impression:true,styles:styles,option:'smile_slide_in_styles'};

        jQuery.ajax({
            url:smile_ajax.url,
            data: data,
            type: "POST",
            dataType:"HTML",
            success: function(result){
                // do your stuff
            }
        });
    }

    // This function will add placeholder css to head
    function add_placeholdercolor_css(){

        jQuery(".slidein-overlay").each(function() {
            var placeholder_color = jQuery(this).data("placeholder-color");
            var placeholder_font = jQuery(this).data("placeholder-font");
            var uid = jQuery(this).data("class");
            var defaultColor = placeholder_color;
            var styleContent = '.'+uid+' input { font-family: '+placeholder_font+' } .'+uid +' ::-webkit-input-placeholder {color: ' + defaultColor + '!important; font-family: '+ placeholder_font +'; } .'+uid+' :-moz-placeholder {color: ' + defaultColor + '!important; font-family: '+ placeholder_font +';} .'+uid+' ::-moz-placeholder {color: ' + defaultColor + '!important; font-family: '+ placeholder_font +'; }';
            jQuery("<style id="+uid+" type='text/css'>"+styleContent+"</style>").appendTo("head");
        });

        jQuery(".cp-slidein-inline").each(function() {
            var placeholder_color = jQuery(this).data("placeholder-color");
            var placeholder_font = jQuery(this).data("placeholder-font");
            var uid = jQuery(this).data("slidein-id");
            var defaultColor = placeholder_color;
            var styleContent = '.'+uid+' input { font-family: '+placeholder_font+' } .'+uid +' ::-webkit-input-placeholder {color: ' + defaultColor + '!important; font-family: '+ placeholder_font +'; } .'+uid+' :-moz-placeholder {color: ' + defaultColor + '!important; font-family: '+ placeholder_font +';} .'+uid+' ::-moz-placeholder {color: ' + defaultColor + '!important; font-family: '+ placeholder_font +'; }';
            jQuery("<style id="+uid+" type='text/css'>"+styleContent+"</style>").appendTo("head");
        });
    }

    jQuery(window).on("slideinOpen", function(e,data) {

        //show close btn after x sec
        var close_btn_delay               = data.data("close-btnonload-delay");

        // convert delay time from seconds to miliseconds
        close_btn_delay               = Math.round(close_btn_delay * 1000);

        if(close_btn_delay){
            setTimeout( function(){
                  data.find('.slidein-overlay-close').removeClass('cp-hide-close');
            },close_btn_delay);
        }

        //  slide in height
        CP_slide_in_height();
        var cp_animate       = data.find('.cp-animate-container'),
         animationclass      = cp_animate.data('overlay-animation'),
         animatedwidth       = cp_animate.data('disable-animationwidth'),
         cp_slidein_content  = data.find('.cp-slidein-content'),
         vw                  = jQuery(window).width();

        if( vw >= animatedwidth || typeof animatedwidth == 'undefined' ){
            jQuery(cp_animate).addClass("smile-animated "+ animationclass);
        }

        if( data.find('.cp-slidein-toggle').length > 0 ) {
            setTimeout(function(){
                data.find('.cp-animate-container').css( "height", 'auto' );
                data.find('.cp-animate-container').animate({"opacity":"1"}, '1000' );
            }, '400');
        }

        //for close modal after x  sec of inactive
        var inactive_close_time = data.data('close-after');

        jQuery.idleTimer('destroy');
        if( typeof inactive_close_time !== "undefined" ) {
            inactive_close_time = inactive_close_time*1000;
            setTimeout(function(){
                data.addClass('cp-close-after-x');
            }, inactive_close_time );
             //console.log(inactive_close_time);
            jQuery(document).idleTimer( {
                timeout: inactive_close_time,
                idle: false
            });
        }

    });

    function close_button_tootip(){
        jQuery(".slidein-overlay").each(function(t) {
            var classname = jQuery(this).find(".has-tip").data('classes');
            var closeid = jQuery(this).find(".has-tip").data('closeid');
            var tcolor = jQuery(this).find(".has-tip").data("color");
            var tbgcolor = jQuery(this).find(".has-tip").data("bgcolor");
            var slideinht = jQuery(this).find(".cp-slidein-content").height();
            var vw = jQuery(window).width();

            var position = jQuery(this).find(".has-tip").data("position");
            var offsetval = 20;

            jQuery("body").addClass('customize-support');

            jQuery('head').append('<style class="cp-tooltip-css">.customize-support .tip.'+classname+'{color: '+tcolor+';background-color:'+tbgcolor+';font-size:13px;border-color:'+tbgcolor+' }</style>');
            if( position == 'left' ){
                jQuery('head').append('<style class="cp-tooltip-css">.customize-support .tip.'+classname+'[class*="arrow"]:before , .'+classname+'[class*="arrow"]:before {border-left-color: '+tbgcolor+' ;border-top-color:transparent}</style>');
            } else if( position == 'right' ){
                jQuery('head').append('<style class="cp-tooltip-css">.customize-support .tip.'+classname+'[class*="arrow"]:before , .'+classname+'[class*="arrow"]:before{border-right-color: '+tbgcolor+';border-left-color:transparent }</style>');
            }else {
                jQuery('head').append('<style class="cp-tooltip-css">.customize-support .tip.'+classname+'[class*="arrow"]:before , .'+classname+'[class*="arrow"]:before{border-top-color: '+tbgcolor+';border-left-color:transparent }</style>');
            }
        });
    }

    // Close slide in on click of close button
   jQuery(document).on("click", ".cp-form-submit-error", function(e){

        var cp_form_processing_wrap = jQuery(this).find(".cp-form-processing-wrap") ,
            cp_tooltip              = jQuery(this).find(".cp-tooltip-icon").data('classes'),
            cp_msg_on_submit        = jQuery(this).find(".cp-msg-on-submit"),
            cp_form_processing      = jQuery(this).find(".cp-form-processing");

        cp_form_processing_wrap.hide();
        jQuery(this).removeClass('cp-form-submit-error');
        cp_msg_on_submit.html('');
        cp_msg_on_submit.removeAttr("style");

        //show tool tip
        jQuery('head').append('<style class="cp-tooltip-hide">.tip.'+cp_tooltip+'{display:block }</style>');

    });

    jQuery(document).ready(function(){

        jQuery(document).bind('keydown', function(e) {
            if (e.which == 27) {
                var cp_overlay = jQuery(".si-open");
                var slidein = cp_overlay;
                if( cp_overlay.hasClass("close_btn_nd_overlay") && !cp_overlay.hasClass("do_not_close") ){
                    jQuery(document).trigger('closeSlideIn',[slidein]);
                }
            }
        });
    });

function cp_slide_slidein(){

        jQuery( ".cp-toggle-container" ).click(function() {

            var slidein_overlay   = jQuery(this).closest(".slidein-overlay");
            var slide_in_id       = slidein_overlay.data('slidein-id');
            var toggle_visibility = slidein_overlay.closest('.cp-slidein-popup-container').siblings('.overlay-show').data('toggle-visible');
            if( !slidein_overlay.hasClass("cp-slide-without-toggle") ) {

                jQuery(this).toggleClass("cp-slide-hide-btn");

                var cp_animate_container = slidein_overlay.find(".cp-animate-container"),
                    entryanimation       = cp_animate_container.data("overlay-animation"),
                    exitanimation        = cp_animate_container.data("exit-animation"),
                    cp_slide_edit_btn    = jQuery(".cp-toggle-container"),
                    animatedwidth        = cp_animate_container.data('disable-animationwidth'),
                    vw                   = jQuery(window).width(),
                    animateclass         = '',
                    cp_tooltip           = slidein_overlay.find(".cp-tooltip-icon").data('classes');


                var is_imp_added = slidein_overlay.data('impression-added');

                if( toggle_visibility == true ) {
                    if( typeof is_imp_added == 'undefined' ) {
                        var styleArray = [slide_in_id];
                        update_impressions( styleArray );
                        slidein_overlay.data('impression-added','true');
                    }
                }

                if( vw >= animatedwidth || typeof animatedwidth == 'undefined' ){
                    animateclass ='smile-animated ';
                }

                var tootltip = slidein_overlay.find('.has-tip').attr("class");
                if( typeof tootltip != 'undefined' ) {
                    jQuery('head').append('<style class="cp-tooltip-hide">.tip.'+cp_tooltip+'{display:block }</style>');
                }

                cp_animate_container.attr('class' , 'cp-animate-container cp-hide-slide');

                setTimeout(function() {
                   cp_animate_container.attr('class' , 'cp-animate-container '+ animateclass + ' '+entryanimation);
                   cp_slide_edit_btn.addClass("cp-slide-hide-btn");
                }, 10);

                cp_slide_in_column_equilize();
            }

        });

        jQuery( ".slidein-overlay-close" ).click(function() {

            if( !jQuery(this).hasClass('do_not_close') ) {
                var container   = jQuery(this).parents(".cp-slidein-popup-container")
                var slidein       =  jQuery(this).parents(".slidein-overlay");
                var cp_tooltip  =  slidein.find(".cp-tooltip-icon").data('classes');
                jQuery(document).trigger('closeSlideIn',[slidein]);
                jQuery('head').append('<style class="cp-tooltip-hide">.tip.'+cp_tooltip+'{ display:none; }</style>');
            }

            var slidein_overlay      = jQuery(this).closest(".slidein-overlay");
            if( !slidein_overlay.hasClass("cp-slide-without-toggle") ) {
                var slidein_overlay      = jQuery(this).parents(".slidein-overlay");
                var cp_animate_container = slidein_overlay.find(".cp-animate-container"),
                    exitanimation        = cp_animate_container.data("exit-animation"),
                    cp_slide_edit_btn    = jQuery(".cp-toggle-container"),
                    animatedwidth        = cp_animate_container.data('disable-animationwidth'),
                    vw                   = jQuery(window).width(),
                    animateclass         = '',
                    form                 = slidein_overlay.find('.cp-form').attr('class');

                if( vw >= animatedwidth || typeof animatedwidth == 'undefined' ){
                    animateclass ='smile-animated ';
                }

                cp_animate_container.attr('class', 'cp-animate-container');
                cp_animate_container.attr('class' , 'cp-animate-container '+ animateclass +' '+exitanimation);
                    if(typeof form !== 'undefined'){
                        slidein_overlay.find('#smile-optin-form')[0].reset();
                        slidein_overlay.find(".cp-form-processing-wrap").css('display', 'none');
                        slidein_overlay.find(".cp-form-processing").removeAttr('style');
                        slidein_overlay.find(".cp-msg-on-submit").removeAttr('style');
                        slidein_overlay.find(".cp-msg-on-submit").html();
                        slidein_overlay.find(".cp-m-success").remove();
                    }

                setTimeout(function() {
                    cp_animate_container.addClass("cp-hide-slide");
                    cp_slide_edit_btn.removeClass("cp-slide-hide-btn");
                    cp_animate_container.removeClass(exitanimation);

                }, 500);
            }

        });
}

jQuery(document).on("si_conversion_done", function(e, $this){
    // do your stuff
    if( !jQuery( $this ).parents(".cp-form-container").find(".cp-email").length > 0 ){
        var is_only_conversion = jQuery( $this ).parents(".cp-form-container").find('[name="only_conversion"]').length;
        if ( is_only_conversion > 0 ) {
            jQuery($this).addClass('disabled');
        }
    }

});

function adjustToggleButton(container) {
    if( container.find('.cp-slidein-toggle').length > 0 ) {
        var slide_in_head = container.find('.cp-slidein-head').outerHeight();
        container.find('.cp-animate-container').css( { "height":slide_in_head + 'px', "opacity":"0" } );
    }
}

jQuery(window).on('slideinOpen' , function(e, slidein) {
    var always_visible = slidein.find('.cp-slidein-toggle').data('visible');
    if(slidein.hasClass("minimize-widget")){
        slidein.addClass('cp-hide-slide-widget');
        setTimeout(function() {
            var this_cls = jQuery(".cp-slidein-head .cp-slidein-toggle");
            toggle_widget(this_cls , 500);  
        } ,0);
    }else{
        slidein.find('.cp-slidein-toggle').addClass('cp-widget-open');
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
        jQuery(".cp-slide_in-inline-end").each(function(e) {
            var elem = jQuery(this);
            var is_visible = isScrolledIntoStyleView(elem);
            var style_id = jQuery(this).data('style');

            if( is_visible ) {
                var styleArray = Array();
                if( !jQuery("[data-slidein-style="+style_id+"]").hasClass('impression_counted') ) {
                    styleArray.push(style_id);
                    update_impressions(styleArray);
                }
                jQuery("[data-slidein-style="+style_id+"]").each(function() {
                    jQuery(this).addClass('impression_counted');
                });
            }
        });
    }


//Open modal when user scroll to specific class /Id
    var slidein_scrollcls = [];
        jQuery.each(jQuery('.si-onload'),function(){
            var sldein_scroll_class = jQuery(this).data('scroll-class');
            if( typeof sldein_scroll_class !== "undefined" && sldein_scroll_class !== "" ){
                sldein_scroll_class = sldein_scroll_class.split(" ");
                jQuery.each( sldein_scroll_class, function(i,c){
                    slidein_scrollcls.push(c);
                });
            }

        });


 jQuery.each(slidein_scrollcls, function(i,v){

    jQuery(document).scroll(function(e){

        // count inline impressions
        count_inline_impressions();

        // calculate the percentage the user has scrolled down the page
        var scrollPercent = 100 * jQuery(window).scrollTop() / (jQuery(document).height() - jQuery(window).height());
        var scrolled = scrollPercent.toFixed(0);
        var styleArray = Array();
        jQuery(".si-onload").each(function(t) {
            var $this = jQuery(this);
            var exit        = jQuery(this).data("exit-intent");
            var class_id    = jQuery(this).data("class-id");
            var dev_mode    = jQuery(this).data("dev-mode");
            var cookieName  = jQuery('.'+class_id).data('slidein-id');
            var toggle_visible = jQuery(this).data('toggle-visible');
            var temp_cookie     = "temp_"+cookieName;
            var opt           = jQuery('.'+class_id).data('option');
            var style         = jQuery('.'+class_id).data('slidein-style');
            var slidein       = jQuery('.'+class_id);
            var scrollclass = v;
            var scrollTill ='';//console.log(jQuery(v).position());
            if( typeof scrollclass !== 'undefined' && scrollclass !== ' ' ){
                var position    = jQuery(scrollclass).position();
                 if( typeof position !== 'undefined' && position !== ' ' ){
                      scrollTill = jQuery(scrollclass).cp_slidein_isOnScreen();
                }
            }
            var hide_on_device = jQuery(this).data('hide-on-devices');
            var hide_from_device = hideOnDevice(hide_on_device);
            var slidein_Container = jQuery(this).closest('.cp-slidein-popup-container');

            var data        = {action:'smile_update_impressions',impression:true,style_id:style,option:opt};
            if( dev_mode == "enabled" ){
                removeCookie(cookieName);
            }
            var cookie      = getCookie(cookieName);
            var tmp_cookie  = getCookie(temp_cookie);
            if( !temp_cookie ){
                createCookie( temp_cookie, true, 1 );
            } else if( dev_mode == "enabled" && tmp_cookie ) {
                cookie = true;
            }

            var scheduled = slidein.isScheduled();

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();
            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = slidein.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( hide_from_device ) {
                cookie = scrollTill = scheduled = referred = false;
            }
           if( dev_mode == "disabled") {
                if( cookie && slidein.hasClass('always-minimize-widget')){ 
                 
                    slidein.addClass('minimize-widget');
                    cookie = false;
                }
               var conversion_cookies  = getCookie(cookieName+'-conversion');
               if(conversion_cookies && slidein.hasClass('always-minimize-widget')){   
                            
                  cookie = true;
               }
            }

            if( !cookie && scrollTill && scheduled && referred ){
                if(jQuery(".si-open").length <= 0 ){
                      if( scrollTill == true ){
                        adjustToggleButton(slidein_Container);
                        jQuery(window).trigger('slideinOpen',[slidein]);
                        slidein.show();
                        jQuery(document).trigger('resize');
                        slidein.addClass('si-open');

                        if( !slidein.hasClass('impression_counted') ) {
                            styleArray.push(style);
                            if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {
                                update_impressions(styleArray);

                                jQuery("[data-slidein-style="+style+"]").each(function(e) {
                                    jQuery(this).addClass('impression_counted');
                                });
                            }
                        }
                    }
                }
            }
        });

    });
 });

jQuery.fn.cp_slidein_isOnScreen = function(){

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

//set cookies for optin widget style
jQuery("body").on("click", ".cp-slidein-head .cp-widget-open", function(e){
    var slidein = jQuery(this).parents(".slidein-overlay");
    var cookieTime  = slidein.data('closed-cookie-time');
    var cookieName  = slidein.data('slidein-id');
    var cp_animate  = slidein.find('.cp-animate-container');
    var entry_anim  = slidein.data('overlay-animation');
    var exit_anim   = cp_animate.data('exit-animation');
    var conversion  = slidein.data('conversion-cookie-time');
    var temp_cookie = "temp_"+cookieName;
    createCookie(temp_cookie,true,1);
    var cookie      = getCookie(cookieName);
    if(!cookie){
        if(cookieTime){           
            slidein.addClass("always-minimize-widget");
            createCookie(cookieName,true,cookieTime); 
        }
    }

});

})(jQuery);
