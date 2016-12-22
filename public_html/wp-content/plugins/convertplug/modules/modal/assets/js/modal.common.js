(function($) {

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
        if( s.hasClass('cp-description') || s.hasClass('cp-short-description') || s.hasClass('cp-info-container') ) {
            s.fitText(1.7, {  minFontSize: '12px', maxFontSize: fs } );
        } else {
            s.fitText(1.2, {  minFontSize: '16px', maxFontSize: fs } );
        }
    }
    function CPAutoResponsiveResize() {
        jQuery('.cp_responsive').each(function(index, el) {
            var lh              = '',
                ww              = jQuery(window).width(),
                s               = jQuery(el),
                fs              = s.css( 'font-size' ),
                CKE_FONT        = s.attr( 'data-font-size' ),
                Def_FONT        = s.attr( 'data-font-size-init' ),
                CKE_LINE_HEIGHT = s.attr( 'data-line-height' ),
                Def_LineHeight  = s.attr( 'data-line-height-init' );

            if( CKE_FONT ) {
                fs = CKE_FONT;          //  1. CKEditor font sizes from editor
            } else if( Def_FONT ) {
                fs = Def_FONT;          //  2. Initially stored font size
            }

            //  Initially set empty line height
            if( CKE_LINE_HEIGHT ) {
                lh = CKE_LINE_HEIGHT;          //  1. CKEditor font sizes from editor
            } else if( Def_LineHeight ) {
                lh = Def_LineHeight;          //  2. Initially stored font size
            }

            if( ww <= 800 ) {

                //  Add line-height for cp-submit
                //  Skip display block which conflict in responsive view port
                if( s.hasClass('cp-submit') ) {
                    s.css({'line-height':'1.15em'});
                } else {
                    //  Apply default line-height - If it does not contain class - `cp_line_height`
                    s.css({'display':'block', 'line-height':'1.15em'});
                }
                CPApplyFlatText(s, fs);
            } else {
                //  Apply default line-height - If it does not contain class - `cp_line_height`
                s.css({'display':'', 'line-height': lh });

                //  Apply `fit-text` for all CKEditor elements - ( .cp-title,  .cp-description etc. )
                s.fitText(1.2, {  minFontSize: fs, maxFontSize: fs } );
            }
        });
    }

    jQuery(document).ready(function() {

        //  Set normal values in data attribute to reset these on window resize
        setTimeout(function() {
            CPResponsiveTypoInit();

            //for link color change
            cp_color_for_list_tag();

            cp_column_equilize();
         }, 500 );

        // hide image for small devices
    	hide_image_on_smalldevice();

    	// hide image for optin to win style
    	optin_to_win_hide_img();

		// hide image for direct download style
    	direct_download_hide_img();

    	// hide image for free book style
    	free_ebook_download_hide_img();

        // form separator for jugaad style
        cp_form_sep_setting();

        form_sep_position();

        cp_set_width_svg();

        setTimeout(function() {
            cp_column_equilize();

        }, 300);

        cp_social_responsive();

    });

    jQuery(window).resize(function(){

    	//  Model height
    	CPModelHeight();

    	/*  = Responsive Typography
        *-----------------------------------------------------------*/
        CPAutoResponsiveResize();

        jQuery(".cp-onload").each(function(t) {
            var class_id    = jQuery(this).data("class-id");
            var modal       = jQuery('.'+class_id);
            if( modal.hasClass('cp-window-size')){
                modal.windowSize();
            }
        });

        // hide image for small devices
        hide_image_on_smalldevice();

        // hide image for optin to win style
        optin_to_win_hide_img();

        // hide image for direct download style
        direct_download_hide_img();

        // hide image for free book style
        free_ebook_download_hide_img();

        // Equalize two columns content vertically center
        setTimeout(function() {
            cp_column_equilize();

            // Equalize blank style content vertically center
            cp_row_equilize();

        }, 300);

        cp_form_sep_top();

        form_sep_position();

        set_affiliate_link();

        cp_set_width_svg();

        cp_social_responsive();

    });

    jQuery(window).on( 'load', function() {
        set_affiliate_link();
    });

    jQuery.fn.windowSize = function(){
        var cp_content_container= this.find(".cp-content-container"),
            cp_modal            = this.find(".cp-modal"),
            cp_modal_content    = this.find(".cp-modal-content"),
            cp_modal_body       = this.find(".cp-modal-body");

        cp_modal.removeAttr('style');
        cp_modal_content.removeAttr('style');
        cp_content_container.removeAttr('style');
        cp_modal_body.removeAttr('style');
        var ww = jQuery(window).width() + 30;
        var wh = jQuery(window).height();
        jQuery(this).find("iframe").css("width",ww);

        cp_content_container.css({'max-width':ww+'px','width':'100%','height':wh+'px','padding':'0','margin':'0 auto'});
        cp_modal_content.css({'max-width':ww+'px','width':'100%'});
        cp_modal.css({'max-width':ww+'px','width':'100%','left':'0','right':'0'});
        cp_modal_body.css({'max-width':ww+'px','width':'100%','height':wh+'px'});
    }

    /**
      *	 This function will hide image on small devices
    */
    function hide_image_on_smalldevice(){
        jQuery(".cp-overlay").each(function() {
            var vw          = jQuery(window).innerWidth();
            var flag        = jQuery(this).data('image-position');
            var hidewidth   = jQuery(this).data('hide-img-on-mobile');
            if( hidewidth ) {
                if( vw <= hidewidth ){
                    jQuery(this).find('.cp-image-container').addClass('cp-hide-image');
                } else {
                    jQuery(this).find('.cp-image-container').removeClass('cp-hide-image');
                }
            }
        });
    }

    /**
      *	 This function will hide image for optin to win style
    */
    function optin_to_win_hide_img(){
        jQuery(".cp-overlay").each(function() {

            if( jQuery(this).find('.cp-modal-body').hasClass("cp-optin-to-win") ) {
                var vw = jQuery(window).innerWidth();
                var flag = jQuery(this).data('image-position');
                var hidewidth = jQuery(this).data('hide-img-on-mobile');

                if( vw <= hidewidth ){
                    if( hidewidth >= 768 ){
                        jQuery(this).find('.cp-text-container').removeClass('col-lg-7 col-md-7 col-sm-7').addClass('col-lg-12 col-md-12 col-sm-12  cp-bigtext-container');
                    }
                } else {
                    jQuery(this).find('.cp-text-container').removeClass('col-lg-12 col-md-12 col-sm-12  cp-bigtext-container').addClass('col-lg-7 col-md-7 col-sm-7 ');
                }
           }
        });
    }

    /**
      *	 This function will hide image for direct download style
    */
    function direct_download_hide_img(){
        jQuery(".cp-overlay").each(function() {
            if( jQuery(this).find('.cp-modal-body').hasClass("cp-direct-download") ) {
                var vw = jQuery(window).width();
                var flag = jQuery(this).data('image-position');
                //for hide on mobile below width
                var hidewidth = jQuery(this).data('hide-img-on-mobile');
                if( vw <= hidewidth ){
                    if( hidewidth >= 768 ){
                        jQuery(this).find('.cp-text-container').removeClass('col-lg-7 col-md-7 col-sm-7').addClass('col-lg-12 col-md-12 col-sm-12  cp-bigtext-container');
                    }
                } else {
                    jQuery(this).find('.cp-text-container').removeClass('col-lg-12 col-md-12 col-sm-12  cp-bigtext-container').addClass('col-lg-7 col-md-7 col-sm-7 ');
                }
           }
        });
    }

    /**
      *	 This function will hide image for ebook style
    */
    function free_ebook_download_hide_img(){
        jQuery(".cp-overlay").each(function() {
            if( jQuery(this).find('.cp-modal-body').hasClass("cp-free-ebook") ) {

                var vw = jQuery(window).outerWidth();
                var flag = jQuery(this).data('image-position');
                //for hide on mobile below width
                var hidewidth = jQuery(this).data('hide-img-on-mobile');
                if( vw <= hidewidth ){
                    if( hidewidth >= 768 ){
                        jQuery(this).find('.cp-text-container').removeClass('col-lg-7 col-md-7 col-sm-7').addClass('col-lg-12 col-md-12 col-sm-12  cp-bigtext-container');
                    }
                } else {
                    jQuery(this).find('.cp-text-container').removeClass('col-lg-12 col-md-12 col-sm-12  cp-bigtext-container').addClass('col-lg-7 col-md-7 col-sm-7 ');
                }
           }
        });
    }

})(jQuery);

/**
  * This function will apply height to cp-columns-equalized class
  */
function cp_column_equilize() {

    setTimeout(function() {
        jQuery(".cp-columns-equalized").each(function() {

            // if modal is open then only apply equalize properties
            if( jQuery(this).closest('.cp-overlay').hasClass('cp-open') || jQuery(this).closest('.global_modal_container').hasClass('cp-modal-inline') ) {

                var wh = jQuery(window).width();

                var childClasses = Array();
                jQuery(this).children('.cp-column-equalized-center').each(function () {
                    var contHeight = jQuery(this).outerHeight();
                    childClasses.push(contHeight);
                });

                var count = 0;
                if(jQuery(this).find('.cp-image-container').length > 0){
                    jQuery(this).find(".cp-highlight").each(function(index, el) {
                        count++;
                    });
                }

                var pad_top = parseInt(jQuery(this).css('padding-top'));
                var pad_bottom = parseInt(jQuery(this).css('padding-top'));

                var tot_padding = pad_top + pad_bottom;

                var maxHeight = Math.max.apply(Math, childClasses) + tot_padding;
                maxHeight = maxHeight-count;

                if( wh > 768 ) {
                    jQuery(this).css( 'height', maxHeight );
                } else {
                    jQuery(this).css( 'height', 'auto' );
                }
            }
        });
    }, 200 );
}

/**
 *  Set normal values in data attribute to reset these on window resize
 */
function CPResponsiveTypoInit() {

    //  1. Add font size attribute
    jQuery('.cp_responsive').each(function(index, el) {
        var s = jQuery(el);

        //  Add attribute `data-line-height-init` for all `cp_responsive` classes. Except `.cp_line_height` which is added from editor.
        if( !s.hasClass('cp_line_height') ) {
            //  Set `init` font size data attribute
            var fs      = s.css('font-size');
            var hasData = s.attr('data-font-size');
            if(!hasData) {
                s.attr('data-font-size-init', fs);
            }
        }

        //  Add attribute `data-line-height-init` for all `cp_responsive` classes. Except `.cp_font` which is added from editor.
        if( !s.hasClass('cp_font') ) {
            //  Set `init` line height data attribute
            var lh      = s.css('line-height');
            var hasData = s.attr('data-line-height');
            if(!hasData) {
                s.attr('data-line-height-init', lh);
            }
        }

    });
}


/**
  *	This function adjust height for modal
  * Loop for all live modal's
  *
  */
function CPModelHeight() {

    setTimeout(function() {

        //  Loop all live modal's
        jQuery('.cp-modal-popup-container').each(function(index, element) {

            var t           = jQuery(element),
                modal       = t.find('.cp-modal'),
                overlay     = t.find('.cp-overlay'),
                overlay_height     = t.find('.cp-overlay').outerHeight(),
                modal_body_height  = t.find('.cp-modal-body').outerHeight(),
                ww          = jQuery(window).width();


            if ( jQuery(this).find('.cp-overlay').hasClass('cp-open') ) {
                if( !jQuery( this ).hasClass( 'cp-inline-modal-container' ) ){
                    if( ( modal_body_height > overlay_height ) ) {
                        modal.addClass('cp-modal-exceed');
                        overlay.each(function( i, el ) {
                            if( jQuery(el).hasClass('cp-open') ) {
                                jQuery('html').addClass('cp-exceed-vieport');
                            }
                            jQuery('html').removeClass('cp-window-viewport');
                        });
                    } else {
                        modal.removeClass('cp-modal-exceed');
                        jQuery('html').removeClass('cp-exceed-vieport');
                        modal.css('height', '' );

                    }
                }
            }

            set_affiliate_link();

        });
    }, 1200);


}

// function to reinitialize affiliate
function set_affiliate_link(data){
        jQuery(".cp-overlay").each(function() {
            var modal_size           = jQuery(this).find(".cp-modal").hasClass('cp-modal-window-size'),
                affiliate_setting    = jQuery(this).data('affiliate_setting');
                vw                   = jQuery(window).width(),
                cp_affilate_link     = jQuery(this).find(".cp-affilate-link"),
                cp_animate_container = jQuery(this).find(".cp-animate-container"),
                cp_overlay           = jQuery(this);

               if(jQuery(this).hasClass('ps-container')){
                 var affiliate_setting = data;
               }

           if( affiliate_setting == '1' ){
                if( !modal_size ){
                    if( vw <= 768 ){
                        cp_affilate_link.appendTo(cp_animate_container);
                        cp_affilate_link.addClass('cp-afl-for-smallscreen');
                    } else {
                        cp_affilate_link.removeClass('cp-afl-for-smallscreen')
                        cp_affilate_link.appendTo(cp_overlay);
                    }
                } else {
                    if( vw <= 768 ){
                        cp_affilate_link.addClass('cp-afl-for-smallscreen');
                        cp_affilate_link.appendTo(cp_animate_container);
                        var ht = jQuery(this).find(".cp-modal-content").outerHeight() - 40;
                    } else {
                        cp_affilate_link.removeClass('cp-afl-for-smallscreen');
                        cp_affilate_link.appendTo(cp_overlay);
                        cp_affilate_link.css( 'top', '' );
                    }
                }
            }
        });
    }

// function to change color for list type according to span color
function cp_color_for_list_tag(){

    jQuery(".cp-overlay").each(function() {
            var ov                  = jQuery(this),
                is_responsive_cls   = jQuery(this).parents(".cp_responsive").length;

        jQuery(this).find("li").each(function() {
            if(jQuery(this).parents(".cp_social_networks").length == 0 && (jQuery(this).parents(".custom-html-form").length == 0) ){
            var moadal_style = jQuery( ov ).find(".cp-modal-body").attr('class').split(' ')[1];

            var parent_li   = jQuery(this).parents(".cp_responsiv").attr('class');

            if( parent_li !== null && typeof parent_li !== 'undefined' ) {
                parent_li   = jQuery(this).parents(".cp_responsive").attr('class').split(' ')[0];
            } else {
                parent_li   = jQuery(this).parents("div").attr('class').split(' ')[0];
            }

            var  cnt    = jQuery(this).index() + 1,
                font_size   = jQuery(this).find(".cp_font").css("font-size"),
                color       = jQuery(this).find("span").css("color"),
                list_type   = jQuery(this).parent(),
                list_type   = list_type[0].nodeName.toLowerCase(),
                style_type  = '',
                style_css   = '';

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

            jQuery(this).find("span").each(function(){
                 var spancolor = jQuery(this).css("color");
                 if(spancolor.length > 0){
                        color = spancolor;
                 }
            });

            var font_style ='';
            jQuery(".cp-li-color-css-"+cnt).remove();
            jQuery(".cp-li-font-css-"+cnt).remove();
            if( font_size ){
               font_style = 'font-size:'+font_size;
               jQuery('head').append('<style class="cp-li-font-css'+cnt+'">.'+moadal_style+' .'+parent_li+' li:nth-child('+cnt+'){ '+font_style+'}</style>');
            }
            if( color ){
              jQuery('head').append('<style class="cp-li-color-css'+cnt+'">.'+moadal_style+' .'+parent_li+' li:nth-child('+cnt+'){ color: '+color+';}</style>');
            }

        }

          });
    });
}
var smile_global_data = '';
jQuery(document).on('smile_data_continue_received',function(e){
    //  YouTube style need to continue call the padding
    addPaddingtoYoutubeFrame();
});
jQuery(document).on('smile_data_received',function(e,data){
     cp_modal_common( data );
});


function cp_modal_common( data ) {

    // Equalize two columns content vertically center
    cp_column_equilize();

     // Equalize blank style content vertically center
    cp_row_equilize();

    addPaddingtoYoutubeFrame();

    //apply inset box shaddow for count down style
   // apply_box_shaddow_to_count_down(data);

     //cp_form_sep_top();
}

function cp_form_sep_setting() {
    jQuery(".cp-overlay").each(function() {
        if( jQuery(this).find('.cp-modal-body').hasClass('cp-jugaad') ) {

            var form_sep_pos = jQuery(this).find('.cp-form-separator').data('form-sep-pos');
            var form_sep_part_of = jQuery(this).find('.cp-form-separator').data('form-sep-part');
            var form_separator = jQuery(this).find('.cp-form-separator').data('form-sep');

            if( form_sep_pos == 'horizontal' ) {
                if( form_sep_part_of == 'part-of-content' ) {
                    jQuery(this).find('.cp-form-separator').appendTo(jQuery(this).find(".cp-modal-body > .cp-row .cp-content-section"));
                } else {
                    jQuery(this).find('.cp-form-separator').appendTo(jQuery(this).find(".cp-modal-body > .cp-row .cp-form-section"));
                }
            } else {
                if( form_sep_part_of == 'part-of-content' ) {
                    jQuery(this).find('.cp-form-separator').appendTo( jQuery(this).find(".cp-modal-body > .cp-row"));
                } else {
                    jQuery(this).find('.cp-form-separator').appendTo( jQuery(this).find(".cp-modal-body > .cp-row"));
                }
            }

            if( form_sep_part_of == 'part-of-content' )
                var fillcolor = jQuery(this).find('.cp-content-section-overlay').css('background-color');
            else
                var fillcolor = jQuery(this).find('.cp-form-section-overlay').css('background-color');
            var viewbox = cp_get_viewbox_svg(form_separator);
            var svg = cp_get_svg(form_separator,fillcolor,viewbox,form_sep_part_of);
            jQuery(this).find('.cp-form-separator').html(svg);
        }
    });
}


// get svg for form separator
function cp_get_svg(shape,fillColor,viewbox,partof) {

    var svg = '';

    if( shape == 'waves' )
        aspRatio = 'preserveAspectRatio="none"';
    else
        aspRatio = '';

    if( partof == 0 )
        svgclass = ' right';
    else
        svgclass = ' left';

    var modal_content_ht = jQuery(".cp-modal-content").outerHeight()+ 10 +'px';

    svg += '<svg class="'+shape+svgclass+'" '+aspRatio+' fill="'+fillColor+'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" width="100%" height="30" preserveAspectRatio="none" viewBox="'+viewbox+'" enable-background="new 0 0 98.5 1097.757" xml:space="preserve">';
    switch(shape){

        case "waves":
            var path = '<path d="M0.199945 0c3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1l-0.200008 0c-0.0541102,0 -0.0981929,-0.0430079 -0.0999409,-0.0967008l0 0.0967008 0.0999409 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1zm0.200004 0c7.87402e-006,0.0552205 0.0447874,0.1 0.1,0.1l-0.2 0c0.0552126,0 0.0999921,-0.0447795 0.1,-0.1zm0.200004 0c3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1l-0.200008 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1zm0.200004 0c7.87402e-006,0.0552205 0.0447874,0.1 0.1,0.1l-0.2 0c0.0552126,0 0.0999921,-0.0447795 0.1,-0.1zm0.200004 0c3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1l-0.200008 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1zm0.200004 0c7.87402e-006,0.0552205 0.0447874,0.1 0.1,0.1l-0.2 0c0.0552126,0 0.0999921,-0.0447795 0.1,-0.1zm0.200004 0c3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1l-0.200008 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1zm0.200004 0c7.87402e-006,0.0552205 0.0447874,0.1 0.1,0.1l-0.2 0c0.0552126,0 0.0999921,-0.0447795 0.1,-0.1zm0.200004 0c3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1l-0.200008 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1zm0.200004 0c7.87402e-006,0.0552205 0.0447874,0.1 0.1,0.1l-0.2 0c0.0552126,0 0.0999921,-0.0447795 0.1,-0.1zm2.00004 0c7.87402e-006,0.0552205 0.0447874,0.1 0.1,0.1l-0.2 0c0.0552126,0 0.0999921,-0.0447795 0.1,-0.1zm-0.1 0.1l-0.200008 0c-0.0552126,0 -0.0999921,-0.0447795 -0.1,-0.1 -7.87402e-006,0.0552205 -0.0447874,0.1 -0.1,0.1l0.2 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1 3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1zm-0.400008 0l-0.200008 0c-0.0552126,0 -0.0999921,-0.0447795 -0.1,-0.1 -7.87402e-006,0.0552205 -0.0447874,0.1 -0.1,0.1l0.2 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1 3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1zm-0.400008 0l-0.200008 0c-0.0552126,0 -0.0999921,-0.0447795 -0.1,-0.1 -7.87402e-006,0.0552205 -0.0447874,0.1 -0.1,0.1l0.2 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1 3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1zm-0.400008 0l-0.200008 0c-0.0552126,0 -0.0999921,-0.0447795 -0.1,-0.1 -7.87402e-006,0.0552205 -0.0447874,0.1 -0.1,0.1l0.2 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1 3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1zm-0.400008 0l-0.200008 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1 3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1zm1.90004 -0.1c3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1l-0.200008 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1zm0.200004 0c7.87402e-006,0.0552205 0.0447874,0.1 0.1,0.1l-0.2 0c0.0552126,0 0.0999921,-0.0447795 0.1,-0.1zm0.200004 0c3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1l-0.200008 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1zm0.200004 0c7.87402e-006,0.0552205 0.0447874,0.1 0.1,0.1l-0.2 0c0.0552126,0 0.0999921,-0.0447795 0.1,-0.1zm0.200004 0c3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1l-0.200008 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1zm0.200004 0c7.87402e-006,0.0552205 0.0447874,0.1 0.1,0.1l-0.2 0c0.0552126,0 0.0999921,-0.0447795 0.1,-0.1zm0.200004 0c3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1l-0.200008 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1zm0.200004 0c7.87402e-006,0.0552205 0.0447874,0.1 0.1,0.1l-0.2 0c0.0552126,0 0.0999921,-0.0447795 0.1,-0.1zm0.200004 0c3.93701e-006,0.0552205 0.0447795,0.1 0.100004,0.1l-0.200008 0c0.0552244,0 0.1,-0.0447795 0.100004,-0.1zm0.199945 0.00329921l0 0.0967008 -0.0999409 0c0.0541102,0 0.0981929,-0.0430079 0.0999409,-0.0967008z"></path>'
            svg += path;
        break;

        case "triangle":
            var path = '<path class="fil0" d="M-0 0.333331l4.66666 0 0 -3.93701e-006 -2.33333 0 -2.33333 0 0 3.93701e-006zm0 -0.333331l4.66666 0 0 0.166661 -4.66666 0 0 -0.166661zm4.66666 0.332618l0 -0.165953 -4.66666 0 0 0.165953 1.16162 -0.0826181 1.17171 -0.0833228 1.17171 0.0833228 1.16162 0.0826181z"></path>';
            svg += path;
        break;

        case "big_triangle_right":
            var poly = '<polygon xmlns="http://www.w3.org/2000/svg" points="1600,-148 0,-148 428.067,-83.114 "/>';
            svg += poly;
        break;

        case "big_triangle_left":
            var poly = '<polygon xmlns="http://www.w3.org/2000/svg" points="1600,-148 0,-148 428.067,-83.114 "/>';
            svg += poly;
        break;

        case "clouds":
            var clip = '<path d="M369.112,0L369.112,0H0v63.065l0.032,0.559c21.29,9.47,44.537-15.028,44.537-15.028c61.847,30.504,89.625-27.994,89.625-27.994c18.674,10.285,46.32-0.138,46.32-0.138c52.377,76.808,103.636-5.729,103.636-5.729C336.792,42.609,369.104,0.009,369.112,0c22.006,15.26,55.156,1.585,55.156,1.585c19.499,33.14,52.647,32.087,52.647,32.087c42.064,2.626,60.171-11.971,60.171-11.971c37.22,28.195,71.603-12.78,71.603-12.78c19.771,29.328,55.433,2.259,55.433,2.259c28.254,73.546,83.571,19.989,83.571,19.989c20.144,40.313,79.514,47.483,99.412-9.316c11.586,28.465,39.627,23.784,52.524,20.7c29.937,64.271,88.996,43.13,110.192-25.715c17.479,34.709,54.434,16.065,59.901,0.525c21.138,56.183,74.132,32.033,79.915,30.876c3.375,11.047,21.676,45.967,57.934,41.716c36.262-4.245,43.799-39.459,47.08-58.545c33.985,10.523,54.651-15.098,54.651-15.098c59.06,69.967,101.394-1.052,101.394-1.052c31.481,16.827,55.432,1.582,55.432,1.582c16.566,31.292,41.514,38.394,41.514,38.394c36.49,8.943,62.033-28.718,62.033-28.718c11.555,7.383,30.326,7.909,30.326,7.909V0H369.112z"/>';
            svg += clip;
        break;

        case "curve_center":
            var path = '<path class="fil1" d="M4.66666 0l0 7.87402e-006 -3.93701e-006 0c0,0.0920315 -1.04489,0.166665 -2.33333,0.166665 -1.28844,0 -2.33333,-0.0746339 -2.33333,-0.166665l-3.93701e-006 0 0 -7.87402e-006 4.66666 0z"></path>';
            svg += path;
        break;

        case "tilt":
            var poly = '<polygon points="0,172 0,262 1600,172 "></polygon>';
            svg += poly;
        break;

        case "circle_bottom":
            var path = '<path d="M0.200004 0c-3.93701e-006,0.0552205 -0.0447795,0.1 -0.100004,0.1 -0.0552126,0 -0.0999921,-0.0447795 -0.1,-0.1l0.200004 0z"></path>';
            svg += path;
        break;
        case "round_split":
            var path = '<g xmlns="http://www.w3.org/2000/svg"><g><defs><rect id="SVGID_1_" y="-1" width="1600" height="90"/></defs><clipPath id="SVGID_2_"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#SVGID_1_" overflow="visible"/></clipPath><g clip-path="url(#SVGID_2_)"><g><path d="M1605,89c33,0,60-27,60-60V-7c0-33-27-60-60-60H860c-33,0-60,27-60,60v36c0,33,27,60,60,60H1605z"/></g></g></g><g><defs><rect id="SVGID_3_" y="-1" width="1600" height="90"/></defs><clipPath id="SVGID_4_"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#SVGID_3_" overflow="visible"/></clipPath><g clip-path="url(#SVGID_4_)"><g><path d="M740,89c33,0,60-27,60-60V-7c0-33-27-60-60-60H-5c-33,0-60,27-60,60v36c0,33,27,60,60,60H740z"/></g></g></g></g>';
            svg += path;
        break;
    }

    svg += '</svg>';
    return svg;
}


// get view box value for form separator
function cp_get_viewbox_svg(form_separator) {

    var viewbox = '';
    switch(form_separator) {

            case "triangle" :
                var viewbox = '0 0 4.66666 0.333331';
            break;

            case "big_triangle_left" :
                var viewbox = '0 -148 1600 90';
            break;

            case "big_triangle_right" :
                var viewbox = '0 -148 1600 90';
            break;

            case "waves":
                var viewbox = '0 0 6 0.1';
            break;

            case "clouds":
                var viewbox = '0 0 1600 90';
            break;

            case "curve_center":
                var viewbox = '0 0 4.66666 0.333331';
            break;

            case "tilt":
                var viewbox = '0 172 1600 90';
            break;

            case "circle_bottom":
                var viewbox = '0 0 0.2 0.1';
            break;
            case "round_split":
                var viewbox = '0 0 1600 90';
            break;
    }
    return viewbox;
}


// set top value for form separator in jugaad style
function cp_form_sep_top() {

    setTimeout(function() {

        jQuery(".cp-overlay").each(function() {
            var is_jugaad_style = jQuery(this).find('.cp-modal-body').hasClass('cp-jugaad');
            if( is_jugaad_style ) {
                var form_sep  = jQuery(this).find('.cp-form-separator');
                var contentHt = jQuery(this).find('.cp-content-section').outerHeight() - 5 +'px';
                var formHt    = jQuery(this).find('.cp-form-section').outerHeight() - 5 +'px';

                if( form_sep.hasClass('cp-fs-horizontal') ) {
                    if( form_sep.hasClass('part-of-content') ) {
                        if( form_sep.hasClass('upward') ) {
                            form_sep.css( 'bottom', contentHt );
                        } else {
                            form_sep.css( 'top', contentHt );
                        }
                    } else {
                        if( form_sep.hasClass('upward') ) {
                            form_sep.css( 'bottom', formHt );
                        } else{
                            form_sep.css( 'top', formHt );
                        }
                    }
                }
            }
        });

    }, 500 );
}

// set width for form separator
function cp_set_width_svg() {

    setTimeout(function() {
        jQuery(".cp-overlay").each(function() {
            if( jQuery(this).find('.cp-modal-body').hasClass('cp-jugaad') ) {

                var form_sep  = jQuery(this).find('.cp-form-separator');

                if( form_sep.length > 0 ) {

                    var content_section = jQuery(this).find('.cp-content-section');
                    var form_section = jQuery(this).find('.cp-form-section');
                    var form_sep_ht = form_sep.find('svg').outerHeight() + 10 + 'px';

                    if ( form_sep.hasClass('form_bottom') || form_sep.hasClass('img_left_form_bottom')
                        || form_sep.hasClass('img_right_form_bottom') || form_sep.hasClass('form_bottom_img_top') ) {
                        if( form_sep.hasClass('part-of-content') ) {
                            form_section.css( 'padding', form_sep_ht+ ' 15px 15px 15px' );
                            content_section.css( 'padding', '15px' );
                        } else {
                            content_section.css('padding', '15px 15px '+ form_sep_ht +' 15px' );
                            form_section.css( 'padding', '15px' );
                        }
                    } else {
                        if ( form_sep.hasClass('form_left') || form_sep.hasClass('form_left_img_botttom') || form_sep.hasClass('form_left_img_top') ) {

                            if( windowWidth >= 768 ) {
                                if( form_sep.hasClass('part-of-content') ) {
                                    form_section.css( 'padding', '15px ' +form_sep_ht+ ' 15px 15px' );
                                    content_section.css('padding', '15px' );
                                } else {
                                    content_section.css('padding', '15px 15px 15px '+ form_sep_ht );
                                    form_section.css( 'padding', '15px' );
                                }
                            } else {
                                if( form_sep.hasClass('part-of-content') ) {
                                    form_section.css( 'padding', '15px 15px '+ form_sep_ht +' 15px' );
                                    content_section.css('padding', '15px' );
                                } else {
                                    content_section.css('padding', form_sep_ht+ ' 15px 15px 15px' );
                                    form_section.css( 'padding', '15px' );
                                }
                            }
                        } else {
                            if( windowWidth >= 768 ) {
                                if( form_sep.hasClass('part-of-content') ) {
                                    form_section.css( 'padding', '15px 15px 15px ' + form_sep_ht );
                                    content_section.css('padding', '15px' );
                                } else {
                                    content_section.css('padding', '15px '+form_sep_ht+' 15px 15px' );
                                    form_section.css( 'padding', '15px' );
                                }
                            } else {
                                if( form_sep.hasClass('part-of-content') ) {
                                    form_section.css( 'padding', form_sep_ht + ' 15px 15px 15px' );
                                    content_section.css('padding', '15px' );
                                } else {
                                    content_section.css('padding', '15px 15px '+ form_sep_ht + ' 15px' );
                                    form_section.css( 'padding', '15px' );
                                }
                            }
                        }
                    }
                }

                var cp_row_ht = jQuery(this).find(".cp-modal-body > .cp-row").outerHeight() + 5 +'px';
                var windowWidth = jQuery(window).width();
                if( form_sep.hasClass('triangle') || form_sep.hasClass('round_split') || form_sep.hasClass('tilt') ) {

                    if( form_sep.hasClass('form_bottom') ||
                        form_sep.hasClass('img_left_form_bottom') ||
                        form_sep.hasClass('img_right_form_bottom') ||
                        form_sep.hasClass('form_bottom_img_top') ) {
                            form_sep.find('svg').attr('width', '100%' );
                    } else {
                        if( windowWidth >= 768 ) {
                            if(!form_sep.hasClass('tilt')) {
                                form_sep.find('svg').attr('width', cp_row_ht );
                            } else {
                                form_sep.find('svg').attr('width', '100%' );
                            }
                        }
                        else
                            form_sep.find('svg').attr('width', '100%' );
                    }
                }
            }
        });

    }, 200 );
}


// change position for form separator as window size
function form_sep_position() {

    jQuery(".cp-overlay").each(function() {
        if( jQuery(this).find('.cp-modal-body').hasClass('cp-jugaad') ) {
            var form_separator = jQuery(this).find('.cp-form-separator');
            var windowWidth    = jQuery(window).width();
            if( !form_separator.hasClass('form_bottom') && !jQuery('.cp-form-separator').hasClass('form_bottom_img_top') ) {

                if( windowWidth < 768 ) {
                    if( form_separator.hasClass('part-of-form') ) {
                        form_separator.removeClass('cp-fs-vertical cp-fs-vertical-form').addClass('cp-fs-horizontal cp-fs-horizontal-form');
                        jQuery(this).find('.cp-form-separator').appendTo( jQuery(this).find('.cp-form-section') );
                    } else {
                        jQuery(this).find('.cp-form-separator').appendTo( jQuery(this).find('.cp-content-section') );
                        form_separator.removeClass('cp-fs-vertical cp-fs-vertical-content').addClass('cp-fs-horizontal cp-fs-horizontal-content');
                    }
                } else {

                    if( !form_separator.hasClass('img_left_form_bottom') && !form_separator.hasClass('img_right_form_bottom') ) {
                        jQuery(this).find('.cp-form-separator').appendTo( jQuery(this).find(".cp-modal-body > .cp-row") );
                        if( jQuery(this).find('.cp-form-separator').hasClass('part-of-form') ) {
                            jQuery(this).find('.cp-form-separator').removeClass('cp-fs-horizontal cp-fs-horizontal-form cp-fs-vertical-content').addClass('cp-fs-vertical cp-fs-vertical-form');
                        } else {
                            jQuery(this).find('.cp-form-separator').removeClass('cp-fs-horizontal cp-fs-horizontal-content').addClass('cp-fs-vertical cp-fs-vertical-content');
                        }

                        jQuery(this).find('.cp-form-separator').css({
                            'bottom' : '',
                            'top'    : ''
                        });
                    }
                }
            }
        }
    });
}

jQuery(document).on('after_cp_column_equilize', function(e) {

    jQuery(".cp-overlay").each(function() {
        if( jQuery(this).find(".cp-modal-body").hasClass('cp-jugaad') ) {
            if( jQuery(this).find(".cp-modal-body .cp-form-separator").length ) {
                var form_sep_ht = jQuery(this).find(".cp-modal-body .cp-form-separator").outerHeight();
                var cp_column_ht = jQuery(this).find(".cp-modal-body .cp-columns-equalized").outerHeight() + form_sep_ht;
                jQuery(this).find(".cp-modal-body .cp-columns-equalized").css( 'height', cp_column_ht );
            }
        }
    });
});

//  Style - YouTube
//  YouTube Add padding for <iframe> if modal size is 'cp-modal-window-size'
function addPaddingtoYoutubeFrame() {
    if( jQuery('.cp-youtube-container').length ) {
        if( jQuery('.cp-modal').hasClass('cp-modal-window-size') ) {
            var oh = jQuery('.cp-form-container').outerHeight();
            var style= jQuery('.cp-form-container').css('display');
            if(style !='none'){
                jQuery('.cp-youtube-frame').css('padding-bottom', oh + 'px');
            }else{
                jQuery('.cp-youtube-frame').css('padding-bottom', '');
            }
            //jQuery('.cp-youtube-frame').css('padding-bottom', oh + 'px');
        } else {
            jQuery('.cp-youtube-frame').css('padding-bottom', '');
        }
    }
}

// Equalize blank style content vertically center
 function cp_row_equilize() {
   setTimeout( function() {
        jQuery(".cp-row-equalized-center").each(function() {

            var wh = jQuery(window).width();

            var contHeight = jQuery(this).closest(".cp-row-equalized-center").outerHeight();
            var bodyHeight = jQuery(this).closest(".cp-modal-body").css("min-height").replace('px', '');

            if( bodyHeight < contHeight ) {
                jQuery(this).parent(".cp-row-center").addClass('cp-big-content');
            } else {
                jQuery(this).parent(".cp-row-center").removeClass('cp-big-content');
            }

        });
    },200);

 }

//apply_box_shaddow_to_count_down
 function apply_box_shaddow_to_count_down(data){
    jQuery(".cp-overlay").each(function() {
        var cp_modal_body_overlay   = jQuery(this).find(".cp-modal-body-overlay"),
            shadow_type             = cp_modal_body_overlay.css("box-shadow"),
            bg_color                = cp_modal_body_overlay.css("background-color"),
            cp_modal_content        = jQuery(this).find(".cp-modal-content");

        if (shadow_type.indexOf("inset") >= 0){
            cp_modal_body_overlay.addClass('count-down-shadow');
        }else{
             cp_modal_body_overlay.removeClass('count-down-shadow');
        }
    });
 }


function cp_social_responsive(){

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
