(function($) {
    "use strict";

    /**
     *  Check inner span has set font size
     */
    check_responsive_font_sizes();
    function check_responsive_font_sizes() {

        //  Apply font sizes
        jQuery(".cp_responsive[data-font-size-init]").each(function(index, el) {

            var p = jQuery(el);
            var data = jQuery( this ).html();

            if ( data.toLowerCase().indexOf("cp_font") >= 0 && data.match("^<span") && data.match("</span>$") ) {
                p.addClass('cp-no-responsive');
            } else {
                p.removeClass('cp-no-responsive');
            }
        });
    }

    //  Style - YouTube - CTA
    //  Check delay to show either Button or Form
    function youtube_show_cta(modal) {
        var cp_form = modal.find('.cp-form-container');
        if( modal.find('.cp-modal-body').hasClass('cp-youtube') && !cp_form.hasClass('cp-youtube-cta-none') ) {
            var cta_delay   = cp_form.attr('data-cta-delay') || '';

            if( typeof cta_delay != '' && cta_delay != null ) {

                cta_delay = parseInt(cta_delay * 1000);
                 cp_form.slideUp('500');
                setTimeout(function() {

                    //  show CTA after complete delay time
                    cp_form.slideDown('500');


                }, cta_delay );
            }
        }
    }

    function hideOnDevice(devices){
		if( typeof devices !== "undefined" ) {
			devices = devices.split("|");

            var returns = false,
            isDesktop   = false,
            isTablet    = false,
            isMobile    = false;

            //isDesktop   = isOs.Any();
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

    jQuery(document).on('smile_customizer_field_change',function(e){
        CPResponsiveTypoInit();
    });
    jQuery(document).on('smile_data_received',function(e,data){
        CPResponsiveTypoInit();
    });

    function getPrioritized(){
        var modal = 'none';
        jQuery(".cp-onload").each(function(t,v) {
            var class_id = jQuery(this).data("class-id");
            var hasClass = jQuery(this).hasClass("priority_modal");

            if( hasClass ){
                modal = jQuery('.'+class_id);
                return modal;
            }
        });
        return modal;
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
                    //if( _domain == dr_domain ){
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

    // Youtube API
    var cpExecuteVideoAPI = function( obj, status ){
        var iframes = obj.find('iframe');
        jQuery.each(iframes, function( index, frame ){
            var src = frame.src;
            // Youtube API
            var youtube = src.search('youtube.com');
            if( youtube >= 1 ){
                var youtube_frame = frame.contentWindow;
                if( status == 'play' ){
                    youtube_frame.postMessage('{"event":"command","func":"playVideo","args":""}','*');
                } else {
                    youtube_frame.postMessage('{"event":"command","func":"pauseVideo","args":""}','*');
                    youtube_frame.postMessage('{"event":"command","func":"stopVideo","args":""}','*');
                }
            }
            // Vimeo API
            var vimeo = src.search('vimeo.com');
            if( vimeo >= 1 ){
                var vimeo_frame = frame.contentWindow;
                if( status == 'play' ){
                    vimeo_frame.postMessage('{"method":"play"}','*');
                } else {
                    vimeo_frame.postMessage('{"method":"pause"}','*');
                }
            }
        });
    }

    // Display modal on page load after x seconds
    jQuery(window).on( 'load', function() {

         var styleArray = Array();
        jQuery(".cp-onload").each(function(t) {
            var $this = jQuery(this);
            var class_id            = jQuery(this).data("class-id");
            var dev_mode            = jQuery(this).data("dev-mode");
            var cookieName          = jQuery('.'+class_id).data('modal-id');
            var temp_cookie         = "temp_"+cookieName;
            removeCookie(temp_cookie);

            var exit                = jQuery(this).data("exit-intent");
            var opt                 = jQuery('.'+class_id).data('option');
            var style               = jQuery('.'+class_id).data('modal-style');
            var modal               = jQuery('.'+class_id);
            var delay               = jQuery(this).data("onload-delay");
            // convert delay time from seconds to miliseconds
            delay                   = delay * 1000;
            var load_on_refresh     = jQuery('.'+class_id).data('load-on-refresh');
            var scrollPercent       = 100 * jQuery(window).scrollTop() / (jQuery(document).height() - jQuery(window).height());
            var scrollTill          = jQuery(this).data("onscroll-value");
            if( modal.hasClass('cp-window-size')){
                 modal.windowSize();
            }

            var display = false;
            var scheduled = modal.isScheduled();

            var hide_on_device = jQuery(this).data('hide-on-devices');
            var hide_from_device = hideOnDevice(hide_on_device);

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
            } else {
                cookie = getCookie(cookieName);
            }
            if( cookie == null ){
                cookie = false;
            }

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();

            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = modal.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( !cookie && delay && display && scheduled && referred ){
                if( jQuery(".cp-open").length <= 0 ){

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
                        if( jQuery(".cp-open").length <= 0 ){
                            display = true;
                        } else {
                            display = false;
                        }

                        if( display ) {
                            jQuery(window).trigger('modalOpen',[modal]);
                            modal.show();
                            jQuery(document).trigger('resize');
                            var isAutoPlay = modal.find('.cp-youtube-frame').attr('data-autoplay') || '0';
                            if( isAutoPlay === '1' ) {
                                cpExecuteVideoAPI(modal,'play');
                            }
                            modal.addClass('cp-open');

                            if( !modal.hasClass( 'impression_counted' ) ){
                                styleArray.push( style );
                                modal.addClass( 'impression_counted' );
                                if(styleArray.length !== 0 ) {
                                    update_impressions(styleArray);
                                }
                            }

                            //  Show YouTube CTA form
                            youtube_show_cta(modal);

                        }
                    }, parseInt(delay));
                }
            }

            if( dev_mode == "enabled" ){
                removeCookie(cookieName);
            }
        });

    });

    //  Contact Form 7 - Height Issue fixed
    // jQuery(".wpcf7").on('wpcf7:submit', function(event){
    jQuery(".wpcf7").on('wpcf7:invalid', function(event){
        cp_column_equilize();
    });

    // Display modal on page scroll after x percentage
    jQuery(document).scroll(function(e){

        // count impressions for inline modal style
        count_inline_impressions();

        /*  = Responsive Typography
         *-----------------------------------------------------------*/
        //CPAutoResponsiveResize();

        // calculate the percentage the user has scrolled down the page
        var scrollPercent = 100 * jQuery(window).scrollTop() / (jQuery(document).height() - jQuery(window).height());
        var scrolled = scrollPercent.toFixed(0);
        var styleArray = Array();
        jQuery(".cp-onload").each(function(t) {
            var $this = jQuery(this);
            var exit        = jQuery(this).data("exit-intent");
            var class_id    = jQuery(this).data("class-id");
            var dev_mode    = jQuery(this).data("dev-mode");
            var cookieName  = jQuery('.'+class_id).data('modal-id');
            var temp_cookie     = "temp_"+cookieName;
            var opt         = jQuery('.'+class_id).data('option');
            var style       = jQuery('.'+class_id).data('modal-style');
            var modal       = jQuery('.'+class_id);
            var scrollTill  = jQuery(this).data("onscroll-value");

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

            if( modal.hasClass('cp-window-size')){
               modal.windowSize();
            }
            var scheduled = modal.isScheduled();

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();
            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = modal.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( !cookie && scrollTill && scheduled && referred ){
                if( jQuery(".cp-open").length <= 0 ){
                    if( scrolled >= scrollTill  ){
                        jQuery(window).trigger('modalOpen',[modal]);
                        modal.show();

                        var isAutoPlay = modal.find('.cp-youtube-frame').attr('data-autoplay') || '0';
                        if( isAutoPlay === '1' ) {
                            cpExecuteVideoAPI(modal,'play');
                        }

                        modal.addClass('cp-open');

                        //  Show YouTube CTA form
                        youtube_show_cta(modal);

                        if( !modal.hasClass( 'impression_counted' ) ) {
                            styleArray.push( style );
                            modal.addClass( 'impression_counted' );
                            if( styleArray.length !== 0 ) {
                                update_impressions(styleArray);
                            }
                        }
                    }
                }
            }

        });

    });


	// Display modal on page scroll after post content
    jQuery(document).scroll(function(e){

        // calculate the percentage the user has scrolled down the page

		var scrolled = jQuery(window).scrollTop();
        var styleArray = Array();
        jQuery(".cp-after-post").each(function(t) {
            var $this = jQuery(this);
            var exit        = jQuery(this).data("exit-intent");
            var class_id    = jQuery(this).data("class-id");
            var dev_mode    = jQuery(this).data("dev-mode");
            var scrollValue = jQuery(this).data("after-content-value");
            var cookieName  = jQuery('.'+class_id).data('modal-id');
            var temp_cookie     = "temp_"+cookieName;
            var opt         = jQuery('.'+class_id).data('option');
            var style       = jQuery('.'+class_id).data('modal-style');
            var modal       = jQuery('.'+class_id);
            var scrollTilllength  = jQuery(".cp-load-after-post").length;
            if( scrollTilllength > 0 ){
            var scrollTill  = jQuery(".cp-load-after-post").offset().top - 30;

            var hide_on_device = jQuery(this).data('hide-on-devices');
            var hide_from_device = hideOnDevice(hide_on_device);

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

            if( modal.hasClass('cp-window-size')){
               modal.windowSize();
            }
            var scheduled = modal.isScheduled();

			scrollTill = scrollTill - ( ( jQuery(window).height() * scrollValue ) / 100 );

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();
            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = modal.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( hide_from_device ) {
                cookie = scrollTill = scheduled = referred = false;
            }

            if( !cookie && scrollTill && scheduled && referred ){
                if( jQuery(".cp-open").length <= 0 ){
                    if( scrolled >= scrollTill  ){
                        jQuery(window).trigger('modalOpen',[modal]);
                        modal.show();

                        var isAutoPlay = modal.find('.cp-youtube-frame').attr('data-autoplay') || '0';
                        if( isAutoPlay === '1' ) {
                            cpExecuteVideoAPI(modal,'play');
                        }

                        modal.addClass('cp-open');

                        //  Show YouTube CTA form
                        youtube_show_cta(modal);

                        if( !modal.hasClass( 'impression_counted' ) ){
                            styleArray.push( style );
                            modal.addClass( 'impression_counted' );

                            if(styleArray.length !== 0 ) {
                                update_impressions(styleArray);
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
        var getPriorityModal = getPrioritized();
        jQuery(".cp-onload").each(function(t) {
            var $this = jQuery(this);

            if( getPriorityModal !== "none" ){
                var modal = getPriorityModal;
                var modal_id = modal.data("modal-id");
                $this = jQuery(".cp-onload.cp-"+modal_id);
            }

            var exit        = $this.data("exit-intent");
            var class_id    = $this.data("class-id");
            var dev_mode    = $this.data("dev-mode");
            var cookieName  = jQuery('.'+class_id).data('modal-id');
            var temp_cookie = "temp_"+cookieName;
            var opt         = jQuery('.'+class_id).data('option');
            var style       = jQuery('.'+class_id).data('modal-style');
            var modal       = jQuery('.'+class_id);

            var hide_on_device = jQuery(this).data('hide-on-devices');
			var hide_from_device = hideOnDevice(hide_on_device);

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

            var scheduled = modal.isScheduled();

            if( hide_from_device ) {
				exit = scheduled = false;
			}

            var referrer    = $this.data('referrer-domain');
            var ref_check   = $this.data('referrer-check');
            var doc_ref     = document.referrer.toLowerCase();
            var referred = false;
            if( typeof referrer !== "undefined" && referrer !== "" ){
                referred = modal.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( !cookie && referred ){
                if( exit == 'enabled' && scheduled ){
                    if ( e.clientY <= 0 ){
                        if(jQuery(".cp-open").length <= 0 ){
                            jQuery(window).trigger('modalOpen',[modal]);
                            modal.show();
                            if( modal.hasClass('cp-window-size')){
                                modal.windowSize();
                            }
                            var isAutoPlay = modal.find('.cp-youtube-frame').attr('data-autoplay') || '0';
                            if( isAutoPlay === '1' ) {
                                cpExecuteVideoAPI(modal,'play');
                            }

                            modal.addClass('cp-open');

                            //  Show YouTube CTA form
                            youtube_show_cta(modal);

                            if( !modal.hasClass( 'impression_counted' ) ){
                                styleArray.push( style );
                                modal.addClass( 'impression_counted' );

                                if(styleArray.length !== 0 ) {
                                    update_impressions(styleArray);
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

        var cls = new Array();
        var styleArray = Array();

        count_inline_impressions();

        jQuery('.blinking-cursor').remove();

        jQuery(".cp-onload").each(function(t) {
            var inactive_time = jQuery(this).data('inactive-time');
            if( typeof inactive_time !== "undefined" ) {
                inactive_time = inactive_time*1000;
                //console.log("inactive_time"+inactive_time);
                jQuery( document ).idleTimer( {
                    timeout: inactive_time,
                    idle: false
                });
            }


        });

        //  Set normal values in data attribute to reset these on window resize
        CPResponsiveTypoInit();

        // Check and enable js api on youtube videos
        jQuery.each(jQuery(".cp-onload"), function(){
            var cls_id      = jQuery(this).data('class-id');
            var modal       = jQuery('.'+cls_id);
            var iframes     = modal.find('iframe');
            if( modal.hasClass('cp-window-size') ){
                modal.windowSize();
            }
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

        // Display modal on click of custom class
        jQuery.each(jQuery('.cp-overlay'),function(){
            var modal_custom_class = jQuery(this).data('custom-class');
            if( typeof modal_custom_class !== "undefined" && modal_custom_class !== "" ){
                modal_custom_class = modal_custom_class.split(" ");
                jQuery.each( modal_custom_class, function(i,c){
                    cls.push(c);
                });
            }
        });

        jQuery.each(cls, function(i,v){
            jQuery("."+v).click(function(e){

                e.preventDefault();
                var target      = jQuery('.cp-modal-global.'+v);

                if( !target.siblings('.cp-modal-popup-container').find('.cp-animate-container').hasClass('cp-form-submit-success') ) {

                    var exit        = target.data("exit-intent");
                    var class_id    = target.data("class-id");
                    var cookieName  = jQuery('.'+class_id).data('modal-id');
                    var opt         = jQuery('.'+class_id).data('option');
                    var style       = jQuery('.'+class_id).data('modal-style');
                    var modal       = jQuery('.'+class_id);
                    var data        = {action:'smile_update_impressions',impression:true,style_id:style,option:opt};
                    if( modal.hasClass('cp-window-size') ){
                        modal.windowSize();
                    }

                    if(jQuery(".cp-open").length <= 0){

                        if( modal.hasClass('cp-window-size') ){
                            modal.windowSize();
                        }

                        var isAutoPlay = modal.find('.cp-youtube-frame').attr('data-autoplay') || '0';
                        if( isAutoPlay === '1' ) {
                            cpExecuteVideoAPI(modal,'play');
                        }

                        modal.addClass('cp-open');

                        jQuery(window).trigger('modalOpen',[modal]);
                        modal.show();

                        //  Show YouTube CTA form
                        youtube_show_cta(modal);

                        var isAutoPlay = modal.find('.cp-youtube-frame').attr('data-autoplay') || '0';
                        if( isAutoPlay === '1' ) {
                            cpExecuteVideoAPI(modal,'play');
                        }

                        if( !modal.hasClass( 'impression_counted' ) ){
                            styleArray.push( style );
                            modal.addClass( 'impression_counted' );
                        }
                        var cp_tooltip  =  modal.find(".cp-tooltip-icon").data('classes');
                        jQuery('head').append('<style class="cp-tooltip-close-css">.tip.'+cp_tooltip+'{ display:block; }</style>');

                    }

                    if(styleArray.length !== 0 ) {
                        if( !jQuery(this).hasClass('disabled') ){
                            update_impressions(styleArray);
                            jQuery(document).trigger("cp_custom_class_clicked",[this]);
                        }
                    }
                }
            });
        });

        // Affiliate settings
        set_affiliate_link();

        // Initialize tool tip
        setTimeout( function(){
            close_button_tootip();

        },1000);

    });

    // Close modal on click of close button
    jQuery(document).on("closeModal", function(e,modal){

        var container   = modal.parents(".cp-modal-popup-container");
        var template    = container.data('template');
        var cookieTime  = modal.data('closed-cookie-time');
        var cookieName  = modal.data('modal-id');
        var cp_animate  = modal.find('.cp-animate-container');
        var entry_anim  = modal.data('overlay-animation');
        var exit_anim   = cp_animate.data('exit-animation');
        var temp_cookie     = "temp_"+cookieName;
        jQuery('html').removeClass('cp-exceed-vieport cp-window-viewport');
        createCookie(temp_cookie,true,1);
        var cookie      = getCookie(cookieName);
        cpExecuteVideoAPI(modal,'pause');
        e.preventDefault();
        if(!cookie){
            if(cookieTime){
                createCookie(cookieName,true,cookieTime);
                cpExecuteVideoAPI(modal,'pause');
            }
        }

        var animatedwidth = cp_animate.data('disable-animationwidth');
        var vw = jQuery(window).width();
        if( exit_anim == "cp-overlay-none" || ( typeof animatedwidth !== 'undefined' && vw <= animatedwidth ) ){
            modal.removeClass("cp-open");
            
            if( modal.hasClass('cp-hide-inline-style') ){
                exit_anim = "cp-overlay-none";                
            }

            exit_anim = "cp-overlay-none";
            if( jQuery(".cp-open").length < 1 ){
                jQuery("html").removeAttr('style');
            }
        }

        if( !template ){
            cp_animate.removeClass( entry_anim );

            if( vw >= animatedwidth || typeof animatedwidth == 'undefined' ){
                cp_animate.addClass( exit_anim );
            }

            if( exit_anim !== "cp-overlay-none" ){
                setTimeout( function(){
                    cpExecuteVideoAPI(modal,'pause');
                    modal.removeClass("cp-open");
                    if( jQuery(".cp-open").length < 1 ){
                        jQuery("html").removeAttr('style');
                    }
                    setTimeout( function(){
                        cp_animate.removeClass(exit_anim);
                    });
                }, 1000 );
            }
        }

        // hide submit message container
        modal.find(".cp-msg-on-submit").css( "visibility", "hidden" );

    });

    jQuery(document).on("click", ".cp-overlay", function(e){
        if( !jQuery(this).hasClass('do_not_close') && jQuery(this).hasClass('close_btn_nd_overlay') ){
            var modal       = jQuery(this);
            cpExecuteVideoAPI(modal,'pause');
            jQuery(document).trigger('closeModal',[modal]);
        }
    });

    jQuery(document).on( "idle.idleTimer", function(event, elem, obj){
        var styleArray = Array();
        var getPriorityModal = getPrioritized();
        jQuery(".cp-onload").each(function(t) {
            var $this = jQuery(this);
            if( getPriorityModal !== "none" ){
                var modal = getPriorityModal;
                var modal_id = modal.data("modal-id");
                $this = jQuery(".cp-onload.cp-"+modal_id);
            }
            var exit        = $this.data("exit-intent");
            var class_id    = $this.data("class-id");
            var dev_mode    = $this.data("dev-mode");
            var cookieName  = jQuery('.'+class_id).data('modal-id');
            var temp_cookie = "temp_"+cookieName;
            var opt         = jQuery('.'+class_id).data('option');
            var style       = jQuery('.'+class_id).data('modal-style');
            var modal       = jQuery('.'+class_id);

            var hide_on_device   = jQuery(this).data('hide-on-devices');
            var hide_from_device = hideOnDevice(hide_on_device);

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
                referred = modal.isReferrer( referrer, doc_ref, ref_check );
            } else {
                referred = true;
            }

            if( !cookie && display && referred ){
                if( jQuery(".cp-open").length <= 0 ){
                    jQuery(window).trigger('modalOpen',[modal]);
                    modal.show();
                    if( modal.hasClass('cp-window-size') ){
                        modal.windowSize();
                    }

                    var isAutoPlay = modal.find('.cp-youtube-frame').attr('data-autoplay') || '0';
                    if( isAutoPlay === '1' ) {
                        cpExecuteVideoAPI(modal,'play');
                    }

                    modal.addClass('cp-open');

                    //  Show YouTube CTA form
                    youtube_show_cta(modal);

                    if( !modal.hasClass( 'impression_counted' ) ){
                        styleArray.push( style );
                        modal.addClass( 'impression_counted' );
                    }
                }
            }
        });

        if(styleArray.length !== 0 ) {
            update_impressions(styleArray);
        }
    });

    jQuery(".cp-overlay").on( "idle.idleTimer", function(event, elem, obj){
        var modal = jQuery(this);
        jQuery(document).trigger('closeModal',[modal]);
        var cp_tooltip  =  modal.find(".cp-tooltip-icon").data('classes');
        setTimeout(function(){
            jQuery('head').append('<style id="cp-tooltip-close-css">.tip.'+cp_tooltip+'{ display:none; }</style>');
        },1000);

    });

    jQuery(document).on("click", ".cp-overlay-close", function(e){
        if( !jQuery(this).hasClass('do_not_close') ){
            var container   = jQuery(this).parents(".cp-modal-popup-container")
            var modal       =  jQuery(this).parents(".cp-overlay");
            var cp_tooltip  =  modal.find(".cp-tooltip-icon").data('classes');
            cpExecuteVideoAPI(modal,'pause');
            jQuery(document).trigger('closeModal',[modal]);
            jQuery('head').append('<style id="cp-tooltip-close-css">.tip.'+cp_tooltip+'{ display:none; }</style>');
        }
    });

    //close modal on cp-close class
    jQuery(document).on("click", ".cp-close", function(e){
        if( !jQuery(this).parents(".cp-overlay").hasClass('do_not_close') ){
            var modal       =  jQuery(this).parents(".cp-overlay");
            cpExecuteVideoAPI(modal,'pause');
            jQuery(document).trigger('closeModal',[modal]);
        }
    });

     //close modal on cp-inner-close class
    jQuery(document).on("click", ".cp-inner-close", function(e){
        var modal       =  jQuery(this).parents(".cp-overlay");
        cpExecuteVideoAPI(modal,'pause');
        jQuery(document).trigger('closeModal',[modal]);
    });

    jQuery(document).on("click", ".cp-overlay .cp-modal", function(e){
        e.stopPropagation();
    });

    // Update impressions for style
    function update_impressions(styles) {
        var data = {action:'smile_update_impressions',impression:true,styles:styles,option:'smile_modal_styles'};

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

    jQuery(window).on("modalOpen", function(e,data) {

        var close_btn_delay               = data.data("close-btnonload-delay");

        // convert delay time from seconds to miliseconds
        close_btn_delay   = Math.round(close_btn_delay * 1000);

        // console.log("here"+close_btn_delay);
        if(close_btn_delay){
            setTimeout( function(){
                  data.find('.cp-overlay-close').removeClass('cp-hide-close');
            },close_btn_delay);
        }

        // set columns equalized
        cp_column_equilize();

        //  Model height
        CPModelHeight();

        cp_form_sep_top();

        cp_set_width_svg();

        cp_row_equilize();

        var cp_animate = data.find('.cp-animate-container');
        var animationclass = cp_animate.data('overlay-animation');
        var animatedwidth = cp_animate.data('disable-animationwidth');
        var vw = jQuery(window).width();
        if( vw >= animatedwidth || typeof animatedwidth == 'undefined' ){
            jQuery(cp_animate).addClass("smile-animated "+ animationclass);
        }

        jQuery("#cp-tooltip-close-css").remove();

        // remove scroller if modal is window size
        jQuery('.cp-modal-popup-container').each(function(index, element) {
           var t        = jQuery(element),
            modal       = t.find('.cp-modal');
            if( !modal.hasClass("cp-modal-exceed") ){
                if( modal.hasClass('cp-modal-window-size') ){
                    jQuery('html').addClass('cp-window-viewport');
                 }else{
                    jQuery("html").css({"overflow":'hidden'});
                 }
            }
        });

        //for close modal after x  sec of inactive
        var inactive_close_time = data.data('close-after');
        jQuery.idleTimer('destroy');
        if( typeof inactive_close_time !== "undefined" ) {
            inactive_close_time = inactive_close_time*1000;
            jQuery( ".cp-overlay" ).idleTimer( {
                timeout: inactive_close_time,
                idle: false
            });
        }

        close_button_tootip();


    });

jQuery( window ).resize(function() {
 close_button_tootip();
});

    function close_button_tootip(){
        jQuery(".cp-overlay").each(function(t) {
            var classname = jQuery(this).find(".cp-tooltip-icon").data('classes');
            var closeid = jQuery(this).find(".cp-tooltip-icon").data('closeid');
            var tcolor = jQuery(this).find(".cp-tooltip-icon").data("color");
            var tbgcolor = jQuery(this).find(".cp-tooltip-icon").data("bgcolor");
            var modalht = jQuery(this).find(".cp-modal-content").height();
            var vw = jQuery(window).width();
            var id = jQuery(this).data("modal-id");
            var new_tooltip_position = '' ;
            if(jQuery(this).find(".cp-overlay-close").hasClass('cp-adjacent-left')){
                new_tooltip_position ='right';
            }else if( jQuery(this).find(".cp-overlay-close").hasClass('cp-adjacent-right')){
                 new_tooltip_position ='left';
            }

            if( jQuery(this).find(".cp-overlay-close").hasClass('cp-inside-close')){
                 //new_tooltip_position ='top';
            }

            jQuery(this).find(".cp-tooltip-icon").removeAttr('data-position');
            jQuery(this).find(".cp-tooltip-icon").attr("data-position" , new_tooltip_position );

            var position = new_tooltip_position;
            var offsetval = '20';

            jQuery("body").addClass('customize-support');

            /*jQuery("."+classname).frosty({
                className: 'tip close-tip-content ' + classname ,
                position : position,
            });*/


            jQuery("."+classname).remove();
            jQuery('head').append('<style class="cp-tooltip-css '+classname+'">.customize-support .tip.'+classname+'{color: '+tcolor+';background-color:'+tbgcolor+';border-color:'+tbgcolor+' }</style>');

            if( position == 'left' ){
                jQuery('head').append('<style class="cp-tooltip-css '+classname+'">.customize-support .tip.'+classname+'[class*="arrow"]:before , .'+classname+'[class*="arrow"]:before {border-left-color: '+tbgcolor+' ;border-top-color:transparant}</style>');
            }else if( position == 'right' ) {
                jQuery('head').append('<style class="cp-tooltip-css '+classname+'">.customize-support .tip.'+classname+'[class*="arrow"]:before , .'+classname+'[class*="arrow"]:before{border-right-color: '+tbgcolor+';border-left-color:transparent }</style>');
            }else {
                jQuery('head').append('<style class="cp-tooltip-css '+classname+'">.customize-support .tip.'+classname+'[class*="arrow"]:before , .'+classname+'[class*="arrow"]:before{border-top-color: '+tbgcolor+';border-left-color:transparent }</style>');
            }
        });
}

    // Close modal on click of close button
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
        jQuery('head').append('<style class="cp-tooltip-css">.tip.'+cp_tooltip+'{display:block }</style>');

    });

    jQuery(document).ready(function(){

        check_responsive_font_sizes();

        jQuery(document).bind('keydown', function(e) {
            if (e.which == 27) {
                var cp_overlay = jQuery(".cp-open");
                var modal = cp_overlay;
                if( cp_overlay.hasClass("close_btn_nd_overlay") && !cp_overlay.hasClass("do_not_close") ){
                    jQuery(document).trigger('closeModal',[modal]);
                }
            }
        });
    });

    jQuery(document).on("cp_conversion_done", function(e, $this){
        // do your stuff
        if( !jQuery( $this ).parents(".cp-form-container").find(".cp-email").length > 0 ){
            var is_only_conversion = jQuery( $this ).parents(".cp-form-container").find('[name="only_conversion"]').length;

            if ( is_only_conversion > 0 ) {
                jQuery($this).addClass('disabled');
            }
        }
    });

    // Custom class impression count
    jQuery(document).on("cp_custom_class_clicked", function(e, $this){
        // do your stuff
        jQuery($this).addClass('disabled');
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
        jQuery(".cp-modal-inline-end").each(function(e) {
            var elem = jQuery(this);
            var is_visible = isScrolledIntoStyleView(elem);
            var style_id = jQuery(this).data('style');

            if( is_visible ) {
                var styleArray = Array();
                if( !jQuery(".cp-overlay[data-modal-style="+style_id+"]").hasClass('impression_counted') ) {
                    styleArray.push(style_id);
                    update_impressions(styleArray);
                }
                jQuery(".cp-overlay[data-modal-style="+style_id+"]").each(function() {
                    jQuery(this).addClass('impression_counted');
                });
            }
        });
    }

//Open modal scroll upto particular class/id
    var scrollcls = [];
        jQuery.each(jQuery('.cp-onload'),function(){
            var modal_scroll_class = jQuery(this).data('scroll-class');
            if( typeof modal_scroll_class !== "undefined" && modal_scroll_class !== "" ){
                modal_scroll_class = modal_scroll_class.split(" ");
                jQuery.each( modal_scroll_class, function(i,c){
                    scrollcls.push(c);
                });
            }

        });

    jQuery.each(scrollcls, function(i,v){

        jQuery(document).scroll(function(e){

            // count impressions for inline modal style
            count_inline_impressions();

            /*  = Responsive Typography
             *-----------------------------------------------------------*/
            //CPAutoResponsiveResize();

            // calculate the percentage the user has scrolled down the page
            var scrollPercent = 100 * jQuery(window).scrollTop() / (jQuery(document).height() - jQuery(window).height());
            var scrolled = scrollPercent.toFixed(0);
            //console.log(scrolled);
            var styleArray = Array();
            jQuery(".cp-onload").each(function(t) {
                var $this = jQuery(this);
                var exit        = jQuery(this).data("exit-intent");
                var class_id    = jQuery(this).data("class-id");
                var dev_mode    = jQuery(this).data("dev-mode");
                var cookieName  = jQuery('.'+class_id).data('modal-id');
                var temp_cookie     = "temp_"+cookieName;
                var opt         = jQuery('.'+class_id).data('option');
                var style       = jQuery('.'+class_id).data('modal-style');
                var modal       = jQuery('.'+class_id);
                var scrollclass = v;
                var scrollTill ='';
                if( typeof scrollclass !== 'undefined' && scrollclass !== ' ' ){
                    var position    = jQuery(scrollclass).position();
                    if( typeof position !== 'undefined' && position !== ' ' ){
                        scrollTill = jQuery(scrollclass).cp_modal_isOnScreen();
                    }

                }

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

                if( modal.hasClass('cp-window-size')){
                   modal.windowSize();
                }
                var scheduled = modal.isScheduled();

                var referrer    = $this.data('referrer-domain');
                var ref_check   = $this.data('referrer-check');
                var doc_ref     = document.referrer.toLowerCase();
                var referred = false;
                if( typeof referrer !== "undefined" && referrer !== "" ){
                    referred = modal.isReferrer( referrer, doc_ref, ref_check );
                } else {
                    referred = true;
                }

                if( !cookie && scrollTill && scheduled && referred ){
                    if( jQuery(".cp-open").length <= 0 ){

                          if( scrollTill == true ){
                            jQuery(window).trigger('modalOpen',[modal]);
                            modal.show();

                            var isAutoPlay = modal.find('.cp-youtube-frame').attr('data-autoplay') || '0';
                            if( isAutoPlay === '1' ) {
                                cpExecuteVideoAPI(modal,'play');
                            }

                            modal.addClass('cp-open');

                            //  Show YouTube CTA form
                            youtube_show_cta(modal);

                            if( !modal.hasClass( 'impression_counted' ) ) {
                                styleArray.push( style );
                                modal.addClass( 'impression_counted' );
                                if( styleArray.length !== 0 ) {
                                    update_impressions(styleArray);
                                }
                            }
                        }
                    }
                }

            });

        });
    });

    jQuery.fn.cp_modal_isOnScreen = function(){

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
