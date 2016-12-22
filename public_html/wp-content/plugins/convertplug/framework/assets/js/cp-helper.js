/**
 * ConvertPlug
 *
 * Triggers & Functions
 *
 *	1.	htmlEntities
 *	2. 	Google Fonts for CKEditor
 *	3. 	CKEditors Setup - ( Modal, SlideIn, InfoBar )
 */

/**
 * 	1. 	htmlEntities
 */
function htmlEntities(str) {
    return String(str).replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"');
}

/**
 * 	2. 	cp_isValid()
 */
function cp_isValid( s ) {
	if( 'undefined' != typeof s && null != s && '' != s ) {
		return true;
	} else {
		return false;
	}
}

/**
 * 	3.	JS Darken / Lighten color
 */
// CP Darker / Lighter colors - {Start}
var pad = function(num, totalChars) {
    var pad = '0';
    num = num + '';
    while (num.length < totalChars) {
        num = pad + num;
    }
    return num;
};

// Ratio is between 0 and 1
var changeColor = function(color, ratio, darker) {
    // Trim trailing/leading whitespace
    color = color.replace(/^\s*|\s*$/, '');

    // Expand three-digit hex
    color = color.replace(
        /^#?([a-f0-9])([a-f0-9])([a-f0-9])$/i,
        '#$1$1$2$2$3$3'
    );

    // Calculate ratio
    var difference = Math.round(ratio * 256) * (darker ? -1 : 1),
        // Determine if input is RGB(A)
        rgb = color.match(new RegExp('^rgba?\\(\\s*' +
            '(\\d|[1-9]\\d|1\\d{2}|2[0-4][0-9]|25[0-5])' +
            '\\s*,\\s*' +
            '(\\d|[1-9]\\d|1\\d{2}|2[0-4][0-9]|25[0-5])' +
            '\\s*,\\s*' +
            '(\\d|[1-9]\\d|1\\d{2}|2[0-4][0-9]|25[0-5])' +
            '(?:\\s*,\\s*' +
            '(0|1|0?\\.\\d+))?' +
            '\\s*\\)$'
        , 'i')),
        alpha = !!rgb && rgb[4] != null ? rgb[4] : null,

        // Convert hex to decimal
        decimal = !!rgb? [rgb[1], rgb[2], rgb[3]] : color.replace(
            /^#?([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])/i,
            function() {
                return parseInt(arguments[1], 16) + ',' +
                    parseInt(arguments[2], 16) + ',' +
                    parseInt(arguments[3], 16);
            }
        ).split(/,/),
        returnValue;

    // Return RGB(A)
    return !!rgb ?
        'rgb' + (alpha !== null ? 'a' : '') + '(' +
            Math[darker ? 'max' : 'min'](
                parseInt(decimal[0], 10) + difference, darker ? 0 : 255
            ) + ', ' +
            Math[darker ? 'max' : 'min'](
                parseInt(decimal[1], 10) + difference, darker ? 0 : 255
            ) + ', ' +
            Math[darker ? 'max' : 'min'](
                parseInt(decimal[2], 10) + difference, darker ? 0 : 255
            ) +
            (alpha !== null ? ', ' + alpha : '') +
            ')' :
        // Return hex
        [
            '#',
            pad(Math[darker ? 'max' : 'min'](
                parseInt(decimal[0], 10) + difference, darker ? 0 : 255
            ).toString(16), 2),
            pad(Math[darker ? 'max' : 'min'](
                parseInt(decimal[1], 10) + difference, darker ? 0 : 255
            ).toString(16), 2),
            pad(Math[darker ? 'max' : 'min'](
                parseInt(decimal[2], 10) + difference, darker ? 0 : 255
            ).toString(16), 2)
        ].join('');
};
var lighterColor = function(color, ratio) {
    return changeColor(color, ratio, false);
};
var darkerColor = function(color, ratio) {
    return changeColor(color, ratio, true);
};

/**
 *	2. 	Google Fonts for CKEditor
 *
 *	Add selected Google fonts from Google Font Manager to CKEditor
 */

/* Embed Google font link to <head>  */
function cp_append_gfonts(Fonts) {
	jQuery('head').append('<link id="cp-google-fonts" rel="stylesheet" href="https://fonts.googleapis.com/css?family='+Fonts+'" type="text/css" media="all">');
}
/*	Append to CKEditor 	*/
function cp_append_to_ckeditor(CKFonts) {
	if( typeof CKFonts != 'undefined' && CKFonts != null && CKFonts != '') {
		CKEDITOR.config.font_names = CKFonts;
	}
}
/* 	Extract Google Fonts */
function cp_get_gfonts(GFonts) {

	var Fonts = CKFonts = '';

	if(typeof GFonts != 'undefined' && GFonts != null && GFonts != '' ) {

		//	for multiple fonts
		if(GFonts.indexOf(',') >= 0) {

			var basicFonts = [ "Arial",
					"Arial Black",
					"Comic Sans MS",
					"Courier New",
					"Georgia",
					"Impact",
					"Lucida Sans Unicode",
					"Palatino Linotype",
					"Tahoma",
					"Times New Roman",
					"Trebuchet MS",
					"Verdana"
			];

			//	Extract Added Google Fonts
			var pairs = GFonts.split(',');
			pairs.forEach(function(pair) {
				if( typeof pair != 'undefined' && pair != null && pair != '') {
					if( jQuery.inArray( pair , basicFonts ) < 0 ) {
						Fonts += pair.replace(' ','+') + '|';
					}
					CKFonts += pair+';';
				}
			});

			//	append google fonts
			cp_append_gfonts(Fonts);

			//	Append selected google fonts to - CKEditor
			cp_append_to_ckeditor(CKFonts);

		} else {

			//	for single font
			Fonts += GFonts.replace(' ','+') + '|';
			CKFonts += GFonts+';';

			//	append google fonts
			cp_append_gfonts(Fonts);

			//	Append selected google fonts to - CKEditor
			cp_append_to_ckeditor(CKFonts);
		}
	}
}


/**
 *	Adds blinking cursor
 *
 * @param container  ( HTML container class for cursor)
 * @param bgcolor ( background color for cursor )
 */
function cp_blinking_cursor(container,bgcolor) {
	setTimeout(function() {
		if( jQuery(container).find('.blinking-cursor').length == 0 ) {
			var font_size = parseInt(jQuery(container).data('font-size')) + 2;
			var fontArray = Array();

			if( jQuery(container+' span.cp_font').length ) {

				jQuery(container + " span.cp_font").each(function(){
					fontArray.push( parseInt( jQuery(this).data('font-size') ) );
				});

				var maxFontSize = Math.max.apply(Math,fontArray);
				font_size = maxFontSize + 2;
			}
            var style ='';
            if(bgcolor){
                style +='background-color:'+bgcolor+';';
            }
            if(font_size){
                style +='font-size: '+font_size+'px !important;';
            }
			jQuery(container).append('<i style="'+style+'" class="blinking-cursor">|</i>');
		}
	}, 500);
}

/**
 * background image
 */
function cp_update_bg_image( data, sel1, sel2, option, src_option ) {

    var sel1_elem      = jQuery(sel1),
        sel2_elem      = jQuery(sel2),
        bg_img_src     = data[src_option];

    if ( bg_img_src == 'custom_url' ) {

        var image_url = data[option+'_custom_url'];

        if( sel2 != '' ) {
            var modal_size = data.modal_size;
            if( modal_size == 'cp-modal-custom-size' ){
                sel2_elem.css('background-image', '');
                sel1_elem.css('background-image', 'url('+image_url+')');
            } else {
                sel1_elem.css('background-image', '');
                sel2_elem.css( 'background', 'url('+image_url+')');
            }
        } else {
            sel1_elem.css('background-image', 'url('+image_url+')');
        }

    } else if ( bg_img_src == 'upload_img' ) {

        var upload_img_url = jQuery('.smile-input[name="'+option+'"]', window.parent.document ).attr('data-css-image-url') || '';

        if( upload_img_url != '' ) {

            if( sel2 != '' ) {
                var modal_size = data.modal_size;
                if( modal_size == 'cp-modal-custom-size' ){
                    sel2_elem.css('background-image', '');
                    sel1_elem.css('background-image', 'url('+upload_img_url+')');
                } else {
                    sel1_elem.css('background-image', '');
                    sel2_elem.css( 'background', 'url('+upload_img_url+')');
                }
            } else {
                sel1_elem.css('background-image', 'url('+upload_img_url+')');
            }
        } else {
            if( sel2 != '' ) {
                if( modal_size == 'cp-modal-custom-size' ){
                    sel1_elem.css("background-image",'');
                } else {
                    sel2_elem.css("background-image",'');
                }
            } else {
                sel1_elem.css("background-image",'');
            }
        }

    } else {
        sel1_elem.css("background-image",'');
        sel2_elem.css("background-image",'');
    }

    //  Set Background Image - Position, Repeat & Size
    if( typeof data.opt_bg != 'undefined' ) {
        image_positions( data, sel1, sel2, 'opt_bg' );
    }

    if( typeof data.form_opt_bg != 'undefined' ) {
        image_positions( data, sel1, sel2, 'form_opt_bg' );
    }

    if( typeof data.content_opt_bg != 'undefined' ) {
        image_positions( data, sel1, sel2, 'content_opt_bg' );
    }
}


//  Set Background Image - Position, Repeat & Size
function image_positions( data, sel1, sel2, pos_option ) {

    var sel2_elem           = jQuery(sel2),
        sel1_elem           = jQuery(sel1),
        pos_option          = data[pos_option],
        pos_option          = pos_option.split("|"),
        bg_repeat           = pos_option[0],
        bg_pos              = pos_option[1],
        bg_size             = pos_option[2];

    if( sel2 !== '' ) {
        var modal_size = data.modal_size;
        if( modal_size == 'cp-modal-custom-size' ) {
            sel1_elem.css({ "background-position" :  bg_pos, "background-repeat" : bg_repeat, "background-size" : bg_size });
        } else {
            sel2_elem.css({ "background-position" :  bg_pos, "background-repeat" : bg_repeat, "background-size" : bg_size });
        }
    } else {
        sel1_elem.css({ "background-position" :  bg_pos, "background-repeat" : bg_repeat, "background-size" : bg_size });
    }
}

function cp_change_bg_img( smile_global_data, sel1, sel2, option, bg_option, url, val ) {

    var sel2_elem           = jQuery(sel2),
        sel1_elem           = jQuery(sel1),
        modal_bg_image_size = smile_global_data[option+"_size"],
        opt_bg              = smile_global_data[bg_option],
        opt_bg              = opt_bg.split("|"),
        bg_repeat           = opt_bg[0],
        bg_pos              = opt_bg[1],
        bg_size             = opt_bg[2];

    //  UPDATE - [data-css-image-url] to get updated image for FULLWIDTH
    jQuery('.smile-input[name='+option+']', window.parent.document ).attr('data-css-image-url', url );

    //  Changed images is always big.
    //  So, If image size is != full then call the image though AJAX
    if( modal_bg_image_size != "full" ) {
        //  Concat image - REPEAT/POSITION/SIZE
        smile_global_data[bg_option] = bg_repeat + '|' + bg_pos + '|' + bg_size;
        //  Update image - ID|SIZE
        smile_global_data[option] = val;

        cp_update_ajax_bg_image_size( smile_global_data, sel1 , sel2, option, bg_option );

    } else {

        if( sel2 !== '' ) {
            var modal_size = smile_global_data.modal_size;
            if( modal_size == 'cp-modal-custom-size' ){
                sel2_elem.css({  "background-image"  : "" });
                sel1_elem.css({     "background-image"  : 'url('+url+')' });
            } else {
                sel1_elem.css({     "background-image"  : "" });
                sel2_elem.css({  "background-image"  : 'url('+url+')' });
            }
        } else {
            sel1_elem.css({     "background-image"  : 'url('+url+')' });
        }
    }
    //  Set Background Image - Position, Repeat & Size
    image_positions( smile_global_data, sel1, sel2 , bg_option );
}


/**
 * Update image size by AJAX
 *
 * Also, Replaced [data-css-image-url] with updated image size. [Which is used to updated image URL without AJAX.]
 */
function cp_update_ajax_bg_image_size( smile_global_data, sel1, sel2, option, bg_option ) {

    var sel2_elem           = jQuery(sel2),
        sel1_elem           = jQuery(sel1),
        modal_size          = smile_global_data.modal_size,
        modal_bg_image      = smile_global_data[option],
        modal_bg_image_size = smile_global_data[option+"_size"],
        opt_bg              = smile_global_data[bg_option];

                //file not exists
    if( modal_bg_image !== "" ) {
        var img_data = {
            action:'cp_get_image',
            img_id: modal_bg_image,
            size: modal_bg_image_size
        };
        jQuery.ajax({
            url: smile_ajax.url,
            data: img_data,
            type: "POST",
            success: function(img){

                if( sel2 !== '' ) {
                    if( modal_size == 'cp-modal-custom-size' ) {
                        sel2_elem.css({ "background-image" : ""});
                        sel1_elem.css({ "background-image" : 'url('+img+')'});
                    } else {
                        sel1_elem.css({ "background-image" : ""});
                        sel2_elem.css({"background-image" : 'url('+img+')'});
                    }
                } else {
                    sel1_elem.css({ "background-image" : 'url('+img+')'});
                }

                //  UPDATE - [data-css-image-url] to get updated image URL. [Which is used to updated image URL without AJAX.]
                jQuery('.smile-input[name='+option+']', window.parent.document ).attr('data-css-image-url', img );

                //  Set Background Image - Position, Repeat & Size

                image_positions( smile_global_data, sel1 , sel2 , bg_option );
            }
        });
    }
}


/*
 *  Background - (Background Color / Gradient)
 */
 function gradiant_background( data, selector, bg_option , grad_optin ) {

    var bg_gradient          = data[grad_optin],
        bg_color             = data[bg_option],
        background_style     = '',
        light                = lighterColor( bg_color, .3 );

    jQuery("#cp-background-style").remove();
    if( cp_isValid( bg_gradient ) && bg_gradient == '1' ) {

        //  store it!
        jQuery('#smile_bg_gradient_lighten', window.parent.document ).val( light );

        background_style +=  selector +' {'
                    + '     background: -webkit-linear-gradient(' + light + ', ' + bg_color + ');'
                    + '     background: -o-linear-gradient(' + light + ', ' + bg_color + ');'
                    + '     background: -moz-linear-gradient(' + light + ', ' + bg_color + ');'
                    + '     background: linear-gradient(' + light + ', ' + bg_color + ');'
                    + '}';
    } else {
        background_style +=  selector + ' { '
                    + '     background: ' + bg_color
                    + '}';
    }
    jQuery("head").append('<style id="cp-background-style">'+background_style+'</style>');
 }

// set image for style on load of customizer
function cp_set_image( smile_global_data, option ) {

    var image_size    = smile_global_data[option+'_image_size'],
        image         = smile_global_data[option+'_image'],
        img_src       = smile_global_data[option+'_img_src'];
        img_container = jQuery('.cp-image-container img');

    switch( img_src ) {
        case "upload_img":
            if( image !== "" ) {

                 if( image.indexOf('http') === -1 ) {

                    var image_details = image.split("|"),
                        img_id = image_details[0],
                        img_size = image_details[1],
                        img_alt = image_details[2];
                    var img_data = {
                        action:'cp_get_image',
                        img_id: img_id,
                        size: img_size
                    };
                    jQuery.ajax({
                        url: smile_ajax.url,
                        data: img_data,
                        type: "POST",
                        success: function( img_url ) {
                            img_container.attr( "src", img_url);
                        }
                    });

                } else {
                    var img_src = jQuery('.smile-input[name="'+option+'_image"]', window.parent.document ).attr('data-css-image-url');
                    img_container.attr( "src", img_src );
                }
            }
        break;

        case "custom_url":
            var custom_url = smile_global_data[option+'_img_custom_url'];
            img_container.attr( "src", custom_url );
        break;

        case "none":
            img_container.attr( "src", "" );
        break;
    }
}
