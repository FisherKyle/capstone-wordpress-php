/**
 * Add class 'cp-no-responsive' to manage the line height of cp-highlight
 */
function cp_set_no_responsive( sel, data ) {
	if ( data.toLowerCase().indexOf("cp_font") >= 0 && data.match("^<span") && data.match("</span>$") ) {
		sel.addClass('cp-no-responsive');
	} else {
		sel.removeClass('cp-no-responsive');
	}
}

/**
 * Removes &nbsp; and <br> tags from html string
 */
function cp_get_clean_string(string) {
	var cleanString = string.replace(/[<]br[^>]*[>]/gi, '').replace(/[&]nbsp[;]/gi, '').replace(/[\u200B]/g, '');
	cleanString = jQuery.trim(cleanString);
	return cleanString;
}

/**
 * Add cp-empty class
 */
function cp_add_empty_class(element,container) {

	var cleanString = cp_get_clean_string( jQuery(element).html() );

	// modal title
	if( cleanString.length == 0 ) {
		jQuery(container).addClass('cp-empty');
		jQuery(element).html(cleanString);
	} else {
		jQuery(container).removeClass('cp-empty');
	}
}

/**
 * Removes cp-empty class from container
 */
function cp_remove_empty_class(element) {
	if( jQuery(element).length !== 0 ) {
		jQuery(element).removeClass('cp-empty');
	}
}

/**
 * cp_affilate_settings()
 */
function cp_affilate_settings(data){

	var	cp_affilate 			= jQuery(".cp-affilate"),
		cp_affilate_link 		= jQuery(".cp-affilate-link");

	var	affiliate_setting 		= data.affiliate_setting,
		affiliate_title     	= data.affiliate_title,
		affiliate_username      = data.affiliate_username;

	//affiliate link settings
	var crlink = '';
	if( affiliate_setting == '1' ) {
		cp_affilate_link.css({"display":"inline-block"});
		if( affiliate_username != '' ){
			crlink = 'http://themeforest.net/user/brainstormforce/portfolio?ref='+affiliate_username;
		} else {
			crlink = 'http://themeforest.net/user/brainstormforce/portfolio?ref=BrainstormForce';
		}
	} else {
		cp_affilate_link.css({"display":"none"});
	}

	affiliate_title = htmlEntities(affiliate_title);
	cp_affilate_link.find("a").html(affiliate_title);
	if( affiliate_title !== "" && typeof affiliate_title !== "undefined" && jQuery("#afl_editor").length ) {
		CKEDITOR.instances.afl_editor.setData(affiliate_title);
	}
}

function cp_affilate_disable(data){
	var	affiliate_setting 		= data.affiliate_setting;
	if( affiliate_setting == '1' ) {
		cp_affilate_link.css({"display":"inline-block"});
	}else{
		cp_affilate_link.css({"display":"none"});
	}
}

/**
 * Tool tip related settings
 */
function cp_tooltip_settings(data) {

	var close_tooltip     		= '',
		close_tooltip_end 		= '',
		tip_position      		= '',
		tooltip_class     		= '',
		offset_position   		= '',
		innerclass        		= '',
		tooltip_title       	= data.tooltip_title,
		tooltip_title_color 	= data.tooltip_title_color,
		tooltip_background  	= data.tooltip_background,
		close_modal_tooltip 	= data.close_modal_tooltip,
		close_modal	      		= data.close_modal,
		close_img	      		= data.close_img,
		close_txt		  		= data.close_txt,
		overlay_close	  		= jQuery(".cp-overlay-close"),
		close_position    		= data.close_position,
		cp_modal		  		= jQuery(".cp-modal"),
		cp_animate_container 	= jQuery(".cp-animate-container");
		close_text_color  		= data.close_text_color,
		modal_overlay     		= jQuery(".cp-overlay"),
		close_img_size			= data.close_img_size,
		close_image_src         = data.close_image_src,
		cp_close_image_width    = data.cp_close_image_width,
		adjacent_close_position = data.adjacent_close_position;
		var adj_class ='';
//console.log('adjacent_close_position'+adjacent_close_position);
	switch(adjacent_close_position){
		case 'top_left': adj_class = 'cp-adjacent-left';
			break;
		case 'top_right': adj_class = 'cp-adjacent-right';
			break;
		/*case 'bottom_left': adj_class = 'cp-adjacent-bottom-left';
			break;
		case 'bottom_right': adj_class = 'cp-adjacent-bottom-right';
			break;*/
	}

	var modal_size			    = ( typeof data.modal_size == 'undefined' ? 'cp-modal-custom-size' : data.modal_size );

	var close_img_default = close_img;
	if( close_position == 'adj_modal' ) {
		overlay_close.removeClass('cp-outside-close');
		if( close_modal != 'close_txt' ) {
			overlay_close.appendTo(cp_animate_container);
		} else {
			overlay_close.appendTo(modal_overlay);
		}
		overlay_close.removeClass('cp-adjacent-left cp-adjacent-right cp-adjacent-bottom-left cp-adjacent-bottom-right');
		overlay_close.removeClass('cp-inside-close').addClass('cp-adjacent-close');
		overlay_close.addClass(adj_class);
	} else if( close_position == 'inside_modal' ){
		overlay_close.removeClass('cp-outside-close');
		if( close_modal != 'close_txt' ) {
			overlay_close.appendTo(cp_animate_container);
			overlay_close.addClass('cp-inside-close').removeClass('cp-adjacent-close');
			overlay_close.removeClass('cp-adjacent-left cp-adjacent-right cp-adjacent-bottom-left cp-adjacent-bottom-right');
			overlay_close.addClass(adj_class);
		} else {
			overlay_close.appendTo(modal_overlay);
		}
	} else {
		overlay_close.appendTo(modal_overlay);
		overlay_close.addClass('cp-outside-close');
		overlay_close.removeClass('cp-inside-close cp-adjacent-close');
		overlay_close.removeClass('cp-adjacent-left cp-adjacent-right cp-adjacent-bottom-left cp-adjacent-bottom-right');
		overlay_close.addClass(adj_class);
	}

	if( close_modal_tooltip == '1' ){
		var psid = modal_overlay.data('ps-id');

			if( close_position == 'adj_modal' ){
				if( modal_size !== 'cp-modal-custom-size' ){
					offset_position = 45;
				} else {
					offset_position = 35;
				}
				tooltip_class = '';
			} else {
				tooltip_class = 'cp-custom-tooltip';
				offset_position = 30;
			}

			if( modal_size !== 'cp-modal-custom-size' ){
				innerclass = 'cp-innertip';
			}
			jQuery('.has-tip:empty').remove();
	} else {
		jQuery('.has-tip:empty').remove();
	}

	tooltip_title = tooltip_title.replace( /\\/g, '' );
	jQuery('#smile_tooltip_title', window.parent.document).val( htmlEntities(tooltip_title) );

	close_tooltip = '<span class="'+tooltip_class+' cp-tooltip-icon has-tip cp-tipcontent-'+psid+'" data-classes="close-tip-content-'+psid+'"  title="'+tooltip_title+'" data-original-title ="'+tooltip_title+'" data-color="'+tooltip_title_color+'" data-bgcolor="'+tooltip_background+'" data-closeid ="cp-tipcontent-'+psid+'" data-offset="'+offset_position+'" >';
	close_tooltip_end ='</span>';

	if( close_modal == "close_txt" ) {

		jQuery(".cp-overlay-close").removeClass('cp-image-close cp-adjacent-close cp-inside-close').addClass('cp-text-close');
		overlay_close.html(close_tooltip+'<span class ="close-txt">'+close_txt+'</span>'+close_tooltip_end);
		overlay_close.css({"color":close_text_color});
		overlay_close.removeClass('cp-adjacent-left cp-adjacent-right cp-adjacent-bottom-left cp-adjacent-bottom-right');
		overlay_close.addClass(adj_class);

	} else if( close_modal == "close_img" ) {

		if ( close_image_src == 'upload_img' ) {

			if( close_img_default.indexOf('http') === -1 ) {

				if( close_modal == "close_img" && close_img !== "" ) {

					jQuery(".cp-overlay-close").removeClass('cp-text-close').addClass('cp-image-close');
					var img_data = {action:'cp_get_image',img_id:close_img,size:close_img_size};
					jQuery.ajax({
						url: smile_ajax.url,
						data: img_data,
						type: "POST",
						success: function(img){
							overlay_close.html(close_tooltip+'<img src="'+img+'" />'+close_tooltip_end);
							jQuery(document).trigger("cp_ajax_loaded",[data]);
						}
					});

				} else {
					jQuery(".cp-overlay-close").removeClass('cp-text-close cp-image-close');
					overlay_close.html('');
				}

			} else if( close_img_default.indexOf('http') != -1 ) {
				close_img_full_src = close_img.split('|');
				close_img_src = close_img_full_src[0];
				jQuery(".cp-overlay-close").removeClass('cp-text-close').addClass('cp-image-close');
				overlay_close.html(close_tooltip+'<img class="cp-default-close" src="'+close_img_src+'" />'+close_tooltip_end);
			}

		} else if( close_image_src == 'custom_url'  ) {

			var modal_close_img_custom_url = data.modal_close_img_custom_url;
			overlay_close.html(close_tooltip+'<img src="'+modal_close_img_custom_url+'" />'+close_tooltip_end);
			jQuery(".cp-overlay-close").removeClass('cp-text-close');

		} else if (close_image_src == 'pre_icons' ) {
			var close_icon = data.close_icon;
			var close_icon_url = cp.module_img_dir + "/" + close_icon + '.png';
			overlay_close.html(close_tooltip+'<img src="'+close_icon_url+'" />'+close_tooltip_end);
			jQuery(".cp-overlay-close").removeClass('cp-text-close');

		} else {
			jQuery(".cp-overlay-close").removeClass('cp-text-close cp-image-close');
			overlay_close.html('');
		}

	} else {
		jQuery(".cp-overlay-close").removeClass('cp-text-close cp-image-close');
		overlay_close.html('');
	}

	overlay_close.css('background',"transparent");

	if( close_modal == "do_not_close") {
		overlay_close.css('background',"none");
	}

	if( close_modal != 'close_txt' )
		overlay_close.css( 'width', cp_close_image_width+'px' );
	else
		overlay_close.css( 'width', 'auto' );
}

/**
 * Function to reinitialize tooltip
 */
function cp_tooltip_reinitialize(data) {

	var close_modal_tooltip 	 	= data.close_modal_tooltip,
		cp_overlay_close            = jQuery(".cp-overlay-close"),
		cp_modal_width			    = data.cp_modal_width,
		modal_overlay     			= jQuery(".cp-overlay"),
		innerclass                  = '',
		tooltip_background 			= data.tooltip_background,
		tooltip_title_color         = data.tooltip_title_color,
		close_position    			= data.close_position,
		psid 						= modal_overlay.data('ps-id'),
		cp_overlay_close     		= jQuery(".cp-overlay-close"),
		modalht  					= jQuery(".cp-modal-content").outerHeight(),
		adjacent_close_position		= data.adjacent_close_position;

	var modal_size				    = ( typeof data.modal_size == 'undefined' ? 'cp-modal-custom-size' : data.modal_size );
	var new_tip_position = '';
	var tip_position = '';
	switch(adjacent_close_position){
	    case 'top_left': new_tip_position = 'right';
			break;
		case 'top_right': new_tip_position = 'left';
			break;
	}


	//tool tip for modal close
	if( close_modal_tooltip == '1' ){
        var tooltip_classname = "cp-tipcontent-"+psid;
        var tclass = "close-tip-content-"+psid;
        var vw = jQuery(window).width();
      	var ht = jQuery(".cp-modal-body").outerHeight();
      	//jQuery(".has-tip").data("offset" , "20");

		if( modal_size == 'cp-modal-window-size' ){
			jQuery(".has-tip").data("position" , new_tip_position);
	        tip_position = new_tip_position;
		}else{
			switch(close_position){
				case "out_modal" :
					  jQuery(".has-tip").data("position" , new_tip_position);
					  tip_position = new_tip_position;
					  break;
				case "adj_modal" :
					  jQuery(".has-tip").data("position" , new_tip_position);
					  tip_position = new_tip_position;
					  break;
				case "inside_modal" :
					  jQuery(".has-tip").data("position" , new_tip_position);
					  tip_position = new_tip_position;
					  break;

			}
			/*if(modalht >= 500){
	           jQuery(".has-tip").data("position" ,"left");
	            tip_position = "left";
	        }*/

		}

		if(cp_overlay_close.hasClass('cp-text-close')){
			jQuery(".has-tip").data("position" ,new_tip_position);
			tip_position = new_tip_position;
		}

		jQuery(".has-tip").attr("data-position" , tip_position);

    	jQuery("."+tooltip_classname).frosty({
            className: 'tip close-tip-content-'+psid
        });

    	jQuery(".cp-backend-tooltip-css").remove();

    	jQuery('head').append('<style class="cp-backend-tooltip-css">.customize-support .tip.'+tclass+'{color: '+tooltip_title_color+';background-color:'+tooltip_background+';border-color:'+tooltip_background+';border-radius:7px;padding:15px 30px;font-size:13px; }</style>');

        if( tip_position == 'left' ){
           jQuery('head').append('<style class="cp-backend-tooltip-css">.customize-support .'+tclass+'[class*="arrow"]:before{border-left-color: '+tooltip_background+';border-width:8px;margin-top:-8px;border-top-color:transparent }</style>');
        } else if(tip_position == 'right') {
            jQuery('head').append('<style class="cp-backend-tooltip-css">.customize-support .'+tclass+'[class*="arrow"]:before{border-right-color: '+tooltip_background+';border-width:8px;margin-top:0px; border-left-color:transparent}</style>');
        }else {
            jQuery('head').append('<style class="cp-backend-tooltip-css">.customize-support .'+tclass+'[class*="arrow"]:before{border-top-color: '+tooltip_background+';border-width:8px;margin-top:0px; border-left-color:transparent}</style>');
        }
	}
}

/**
 * Modal image related settings
 */
function cp_image_processing(data) {

	var vw 						= jQuery(window).width(),
		vh 						= jQuery(window).height(),
	 	image_displayon_mobile  = data.image_displayon_mobile,
		image_resp_width 		= "768",
		cp_text_container 		= jQuery(".cp-text-container"),
		cp_img_container		= jQuery(".cp-image-container"),
		image_position 			= data.image_position;

	// hide image on mobile devices
	var image_on_left = '';
	if( image_position == 1 ){
		image_on_left = 'cp-right-contain';
	}

	if( image_displayon_mobile == 1 ) {
		if( vw <= image_resp_width ) {
            if( image_resp_width >= 768 ){
                cp_text_container.removeClass('col-lg-7 col-md-7 col-sm-7').addClass('col-lg-12 col-md-12 col-sm-12 cp-bigtext-container');
            } else {
                cp_text_container.removeClass('col-lg-12 col-md-12 col-sm-12 cp-bigtext-container').addClass('col-lg-7 col-md-7 col-sm-7');
            }
        } else {
        	cp_text_container.removeClass('col-lg-12 col-md-12 col-sm-12 cp-bigtext-container').addClass('col-lg-7 col-md-7 col-sm-7');
        }

		if( vw <= image_resp_width ) {
			cp_img_container.addClass('cp-hide-image');
		} else {
			cp_img_container.removeClass('cp-hide-image');
		}
	} else {
		cp_text_container.removeClass('col-lg-12 col-md-12 col-sm-12').addClass('col-lg-7 col-md-7 col-sm-7 '+image_on_left);
		cp_img_container.removeClass('cp-hide-image');
	}
}

/**
 * Reinitialize Affiliate
 */
function cp_affilate_reinitialize(data){
	var affiliate_setting = data.affiliate_setting;
	set_affiliate_link(affiliate_setting);
}

/**
 * Adds custom css
 */
function cp_add_custom_css(data) {
	var custom_css	= data.custom_css || null;
	if( cp_isValid( custom_css ) ) {
		jQuery("#cp-custom-style").remove();
		jQuery("head").append('<style id="cp-custom-style">'+custom_css+'</style>');
	}
}

/**
 * Animations in customizer
 */
function cp_apply_animations(data) {

	var cp_animate	   			= jQuery(".cp-animate-container");

	var disable_overlay_effect 	= data.disable_overlay_effect,
		hide_animation_width 	= data.hide_animation_width,
		overlay_effect			= data.overlay_effect,
		exit_animation			= data.exit_animation,
		after_exit			= overlay_effect;

	if( disable_overlay_effect == 1 ){
		var vw = jQuery(window).width();
		if( vw <= hide_animation_width ){
			overlay_effect = exit_animation = 'cp-overlay-none';
		}
	} else {
		cp_animate.removeClass('cp-overlay-none');
	}

	var entry_anim = ( typeof cp_animate.attr("data-entry-animation") !== "undefined" ) ? cp_animate.attr("data-entry-animation") : '';
	var exit_anim = ( typeof cp_animate.attr("data-exit-animation") !== "undefined" ) ? cp_animate.attr("data-exit-animation") : '';

	cp_animate.removeClass('smile-animated');

	if( !cp_animate.hasClass(exit_animation) && exit_animation !== exit_anim ){
		cp_animate.attr('data-exit-animation', exit_animation );
		setTimeout(function(){
			if( exit_animation !== "none" ) {
				cp_animate.removeClass(exit_anim);
				cp_animate.removeClass(entry_anim);
				cp_animate.addClass('smile-animated '+exit_animation);
				cp_animate.attr('data-entry-animation', overlay_effect );
			}
			setTimeout( function(){
				cp_animate.removeClass(exit_anim);
				cp_animate.removeClass(exit_animation);
				cp_animate.removeClass(entry_anim);
				cp_animate.addClass('smile-animated '+entry_anim);
			}, 1000 );
		},500);
	}

	if( !cp_animate.hasClass(overlay_effect) && overlay_effect !== entry_anim ){
		setTimeout(function(){
			if( overlay_effect !== "none" ) {
				cp_animate.removeClass(exit_anim);
				cp_animate.removeClass(entry_anim);
				cp_animate.addClass('smile-animated '+overlay_effect);
				cp_animate.attr('data-entry-animation', overlay_effect );
			}
		},500);
	}
}


/**
 * Update Image URL by AJAX
 */
function cp_update_ajax_modal_image_src( smile_global_data ) {
console.log("inside ajax");
	var modal_image_size = smile_global_data.modal_image_size,
		modal_image 	 = smile_global_data.modal_image,
		modal_img_src    = smile_global_data.modal_img_src;
		modal_img 		 = jQuery('.cp-image-container img');

	switch( modal_img_src ) {

		case "upload_img":

				// 	file not exists
			if( typeof modal_image !== 'undefined' && modal_image.indexOf('http') === -1 && modal_image !== '' ) {

				var image_details = modal_image.split("|"),
                        img_id = image_details[0],
                        img_size = modal_image_size;
                 console.log("image_details"+image_details);
				var img_data = {
					action:'cp_get_image',
					img_id: img_id,
					size: img_size
				};
				jQuery.ajax({
					url: smile_ajax.url,
					data: img_data,
					type: "POST",
					success: function(img_url){
						modal_img.attr( "src", img_url);
					}
				});
			} else if( typeof modal_image !== 'undefined' && modal_image.indexOf('http') != -1 ) {
				if( modal_image.indexOf('|') ) {
					var url = modal_image.split('|');
					modal_img.attr( "src", url[0]);
				} else {
                	modal_img.attr( "src", modal_image );
                }
			} else {
				var img_src = jQuery('.smile-input[name="modal_image"]', window.parent.document ).attr('data-css-image-url');
				modal_img.attr( "src", img_src );
			}
		break;

		case "custom_url":

			var custom_url = smile_global_data.modal_img_custom_url;
			modal_img.attr( "src", custom_url );
		break;

		case "none":
			modal_img.attr( "src", "" );
		break;
	}

}

parent.jQuery(window.parent.document).on('cp-image-default', function( e, name, url, val) {

	//	Modal - Background Image
	// Process for modal background image - for variable 'modal_bg_image'
	if( name == 'modal_bg_image' && name != 'undefined' && name != null ) {
		cp_change_bg_img( smile_global_data, '.cp-modal-body' , '.cp-modal-content' , name, 'opt_bg', url, val );
	}

	if( name == 'form_bg_image' && name != 'undefined' && name != null ) {
		cp_change_bg_img( smile_global_data, '.cp-form-section', '', name, 'form_opt_bg', url, val );
	}

	if( name == 'content_bg_image' && name != 'undefined' && name != null ) {
		cp_change_bg_img( smile_global_data, '.cp-content-section' , '' , name, 'content_opt_bg', url, val );
	}

	//	Modal - Image
	// Process for modal image - for variable 'modal_image'
	if( name == 'modal_image' && name != 'undefined' && name != null ) {

		var modal_image_size	= smile_global_data.modal_image_size,
			modal_image		    = smile_global_data.modal_image,
			modal_img			= jQuery('.cp-image-container img');

		smile_global_data.modal_image = url;
		//	Changed images is always big.
		//	So, If image size is != full then call the image though AJAX
		if( modal_image_size != "full" ) {
			//	Update image - ID|SIZE
			cp_update_ajax_modal_image_src( smile_global_data );
		} else {
			modal_img.attr( "src", url);
		}
	}

});


/**
 * Remove - Modal Image
 *
 * Also, Replaced [data-css-image-url] with empty. [Which is used to updated image URL without AJAX.]
 */
parent.jQuery(window.parent.document).on('cp-image-remove', function( e, name, url) {

	switch (name) {
		case "modal_bg_image":
			var sel1 = '.cp-modal-body';
			var sel2 = '.cp-modal-content';
			break;
		case "form_bg_image":
			var sel1 = '.cp-form-section';
			var sel2 = '';
		break;
		case "content_bg_image":
			var sel1 = '.cp-content-section';
			var sel2 = '';
		break;

	}

	var cp_modal_content = jQuery(sel2),
		cp_modal_body	 = jQuery(sel1);

	cp_modal_content.css({ 	"background-image" 	: "" });
	cp_modal_body.css({ 	"background-image" 	: "" });

	//	REMOVE - [data-css-image-url] to get updated image for FULLWIDTH
	jQuery('.smile-input[name='+name+']', window.parent.document ).attr('data-css-image-url', '' );
});

/**
 * Change - Modal Image
 */
parent.jQuery(window.parent.document).on('cp-image-change', function( e, name, url, val) {

	//	Modal - Background Image
	// Process for modal background image - for variable 'modal_bg_image'
	if( name == 'modal_bg_image' && name != 'undefined' && name != null ) {
		cp_change_bg_img( smile_global_data, '.cp-modal-body' , '.cp-modal-content' , name, 'opt_bg', url, val );
	}

	if( name == 'form_bg_image' && name != 'undefined' && name != null ) {
		cp_change_bg_img( smile_global_data, '.cp-form-section', '', name, 'form_opt_bg', url, val );
	}

	if( name == 'content_bg_image' && name != 'undefined' && name != null ) {
		cp_change_bg_img( smile_global_data, '.cp-content-section' , '' , name, 'content_opt_bg', url, val );
	}

	//	Modal - Image
	// Process for modal image - for variable 'modal_image'
	if( name == 'modal_image' && name != 'undefined' && name != null ) {

		var modal_image_size	= smile_global_data.modal_image_size,
			modal_image		    = smile_global_data.modal_image,
			modal_img			= jQuery('.cp-image-container img');

		smile_global_data.modal_image = val;
		//	Changed images is always big.
		//	So, If image size is != full then call the image though AJAX
		if( modal_image_size != "full" ) {
			//	Update image - ID|SIZE
			cp_update_ajax_modal_image_src( smile_global_data );
		} else {
			modal_img.attr( "src", url);
		}
	}

});


/**
 * decode HTML char
 */
function escapeHtml(text) {
    var decoded = jQuery('<div/>').html(text).text();
    return decoded;
}

/**
 * This function set modal width
 */
function cp_modal_width_settings(data) {

	var	cp_modal		= jQuery(".cp-modal"),
		cp_modal_width	= data.cp_modal_width,
		modal_size      = data.modal_size,
		cp_modal_body	= jQuery(".cp-modal-body");

	if( modal_size == 'cp-modal-custom-size' ){
		cp_modal.css({'max-width':cp_modal_width+'px','width':'100%'});
		cp_modal_body.css( 'max-width', 'none' );
		cp_modal.removeClass('cp-modal-window-size');
		if( jQuery('.cp-modal-body').hasClass('cp-youtube') ) {
		 	var wh = jQuery(window).height()*(55.5/100);
			jQuery(".cp-content-container").css({'max-width':cp_modal_width+'px','height':wh+'px'});
		}
		jQuery(".cp_fs_overlay").hide();
		jQuery(".cp_cs_overlay").show();
	} else {

		//	Skip `YouTube` style form window Width
		if( !jQuery('.cp-modal-body').hasClass('cp-youtube') ) {
			cp_modal_body.css('max-width', cp_modal_width+'px' );
			cp_modal.css({'max-width':'auto','width':'auto'});
			cp_modal.removeClass('cp-modal-custom-size');

			jQuery(".cp_cs_overlay").hide();
			jQuery(".cp_fs_overlay").show();
		}
	}
	cp_modal.addClass(modal_size);
}

var cp_empty_classes = {
		".cp-title" 			: ".cp-title-container",
		".cp-sec-title"          : ".cp-sec-title-container",
		".cp-description"  		: ".cp-desc-container",
		".cp-info-container" 	: ".cp-info-container",
		".cp-short-description"  : ".cp-short-desc-container",
		".cp-desc-bottom"        : ".cp-desc-timetable",
		".cp-mid-description"    : ".cp-mid-desc-container",
		".cp-short-title"        : ".cp-short-title-container",
		".cp-modal-note"         : ".modal-note-container",
		".cp-modal-note-2"       : ".modal-note-container-2"
	};

/**
 * Document Ready
 */
jQuery(document).ready(function(){

	//  Model height
	CPModelHeight();

	jQuery.each( cp_empty_classes, function( key, value) {

		if( jQuery(value).length !== 0 ) {
			jQuery(value).focusout( function() {
				cp_add_empty_class(key,value);
			} );

			jQuery(value).focusin( function() {
				cp_remove_empty_class(value);
			} );
		}
	});

	jQuery("html").css('overflow','hidden');

	jQuery.each( cp_empty_classes, function( key, value) {

		if( jQuery(value).length !== 0 ) {
			jQuery(value).focusout( function() {
				cp_add_empty_class(key,value);
			} );

			jQuery(value).focusin( function() {
				cp_remove_empty_class(value);
			} );
		}
	});

	if( jQuery("#mid_desc_editor").length ) {

		var sel_mid_desc_editor = jQuery("#mid_desc_editor");

		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'mid_desc_editor' );

		CKEDITOR.instances.mid_desc_editor.on( 'change', function() {

			//	Set class - `cp-modal-exceed`
			CPModelHeight();

			//	Set equalize columns
			cp_column_equilize();

			//Set equalize row for blank style
			cp_row_equilize();

			//set color for li tags
	        	cp_color_for_list_tag();

	        	jQuery(document).trigger('ckeditorChange');

			var data = CKEDITOR.instances.mid_desc_editor.getData();
			parent.updateHTML(htmlEntities(data),'smile_modal_middle_desc');

			//	2. Add class 'cp-no-responsive' to manage the line height of cp-highlight
			cp_set_no_responsive( sel_mid_desc_editor, data );

		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.mid_desc_editor.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     		editor.setReadOnly( false );
		} );
	}

	if( jQuery("#main_title_editor").length ) {

		var sel_main_title_editor = jQuery("#main_title_editor");

		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'main_title_editor' );

		//	Initially set show CKEditor of 'cp-title'
		//	Ref: http://docs.ckeditor.com/#!/api/CKEDITOR.focusManager
		var focusManager = new CKEDITOR.focusManager( CKEDITOR.instances.main_title_editor );
		focusManager.focus();

		//	1. Add class 'cp-no-responsive' to manage the line height of cp-highlight
		CKEDITOR.instances.main_title_editor.on('instanceReady',function(){
			var data = CKEDITOR.instances.main_title_editor.getData();
			cp_set_no_responsive( sel_main_title_editor, data );
		});

		CKEDITOR.instances.main_title_editor.on( 'change', function() {

			// Remove Blinking cursor
			jQuery(".cp-modal-body").find(".blinking-cursor").remove();

			//	Set class - `cp-modal-exceed`
			CPModelHeight();

			//	Set equalize columns
			cp_column_equilize();

			//Set equalize row for blank style
			cp_row_equilize();

			//set color for li tags
	        	cp_color_for_list_tag();

	        	jQuery(document).trigger('ckeditorChange');

			var data = CKEDITOR.instances.main_title_editor.getData();
			parent.updateHTML(htmlEntities(data),'smile_modal_title1');

			//	2. Add class 'cp-no-responsive' to manage the line height of cp-highlight
			cp_set_no_responsive( sel_main_title_editor, data );

		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.main_title_editor.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     		editor.setReadOnly( false );
		} );
	}

	if( jQuery("#sec_title_editor").length ) {

		var sel_sec_title_editor = jQuery("#sec_title_editor");

		// Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'sec_title_editor' );

		//	1. Add class 'cp-no-responsive' to manage the line height of cp-highlight
		CKEDITOR.instances.sec_title_editor.on('instanceReady',function(){
		   	var data = CKEDITOR.instances.sec_title_editor.getData();
			cp_set_no_responsive( sel_sec_title_editor, data );
		});

		CKEDITOR.instances.sec_title_editor.on( 'change', function() {

			//	Set class - `cp-modal-exceed`
			CPModelHeight();

			//	Set equalize columns
			cp_column_equilize();

			//set color for li tags
        		cp_color_for_list_tag();

			var data = CKEDITOR.instances.sec_title_editor.getData();
			parent.updateHTML(htmlEntities(data),'smile_modal_sec_title');

			//	2. Add class 'cp-no-responsive' to manage the line height of cp-highlight
			cp_set_no_responsive( sel_sec_title_editor, data );

		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.sec_title_editor.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     		editor.setReadOnly( false );
		} );
	}


	if( jQuery("#desc_editor").length ) {

		var sel_desc_editor = jQuery("#desc_editor");

		// Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'desc_editor' );
		CKEDITOR.instances.desc_editor.config.toolbar = 'Small';

		//	1. Add class 'cp-no-responsive' to manage the line height of cp-highlight
		CKEDITOR.instances.desc_editor.on('instanceReady',function(){
		   	var data = CKEDITOR.instances.desc_editor.getData();
			cp_set_no_responsive( sel_desc_editor, data );
		});

		CKEDITOR.instances.desc_editor.on( 'change', function() {

			//	Set class - `cp-modal-exceed`
			CPModelHeight();

			//	Set equalize columns
			cp_column_equilize();

			//set color for li tags
	        	cp_color_for_list_tag();

	        	jQuery(document).trigger('ckeditorChange');

			var data = CKEDITOR.instances.desc_editor.getData();
			parent.updateHTML(data,'smile_modal_short_desc1');

			//	2. Add class 'cp-no-responsive' to manage the line height of cp-highlight
			cp_set_no_responsive( sel_desc_editor, data );

		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.desc_editor.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     		editor.setReadOnly( false );
		} );
	}

	if( jQuery("#info_editor").length ) {

		var sel_info_editor = jQuery("#info_editor");

		// Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'info_editor' );
		CKEDITOR.instances.info_editor.config.toolbar = 'Small';

		//	1. Add class 'cp-no-responsive' to manage the line height of cp-highlight
		CKEDITOR.instances.info_editor.on('instanceReady',function(){
		   	var data = CKEDITOR.instances.info_editor.getData();
			cp_set_no_responsive( sel_info_editor, data );
		});

		CKEDITOR.instances.info_editor.on( 'change', function() {

			//	Set class - `cp-modal-exceed`
			CPModelHeight();

			//	Set equalize columns
			cp_column_equilize();

			//set color for li tags
	        	cp_color_for_list_tag();

	        	jQuery(document).trigger('ckeditorChange');

			var data = CKEDITOR.instances.info_editor.getData();
			parent.updateHTML(data,'smile_modal_confidential');

			//	2. Add class 'cp-no-responsive' to manage the line height of cp-highlight
			cp_set_no_responsive( sel_info_editor, data );

		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.info_editor.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     		editor.setReadOnly( false );
		} );
	}

	if( jQuery("#short_desc_editor").length ) {

		var sel_short_desc_editor = jQuery("#short_desc_editor");

		// Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'short_desc_editor' );
		CKEDITOR.instances.short_desc_editor.config.toolbar = 'Small';

		//	1. Add class 'cp-no-responsive' to manage the line height of cp-highlight
		CKEDITOR.instances.short_desc_editor.on('instanceReady',function(){
		   	var data = CKEDITOR.instances.short_desc_editor.getData();
			cp_set_no_responsive( sel_short_desc_editor, data );
		});

		CKEDITOR.instances.short_desc_editor.on( 'change', function() {

			//	Set class - `cp-modal-exceed`
			CPModelHeight();

			//	Set equalize columns
			cp_column_equilize();

			//set color for li tags
        	cp_color_for_list_tag();

			var data = CKEDITOR.instances.short_desc_editor.getData();
			parent.updateHTML(data,'smile_modal_short_desc');

			//	2. Add class 'cp-no-responsive' to manage the line height of cp-highlight
			cp_set_no_responsive( sel_short_desc_editor, data );

		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.short_desc_editor.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     		editor.setReadOnly( false );
		} );
	}

	if( jQuery("#short_title_editor").length ) {

		var sel_short_title_editor = jQuery("#short_title_editor");

		// // Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'short_title_editor' );
		CKEDITOR.instances.short_title_editor.config.toolbar = 'Small';

		// Add class 'cp-no-responsive' to manage the line height of cp-highlight
		CKEDITOR.instances.short_title_editor.on('instanceReady',function(){
		   	var data = CKEDITOR.instances.short_title_editor.getData();
			cp_set_no_responsive( sel_short_title_editor, data );
		});

		CKEDITOR.instances.short_title_editor.on( 'change', function() {

			// Set class - `cp-modal-exceed`
			CPModelHeight();

			//	Set equalize columns
			cp_column_equilize();

			//set color for li tags
        		cp_color_for_list_tag();

			var data = CKEDITOR.instances.short_title_editor.getData();
			parent.updateHTML(data,'smile_modal_short_title');

			//	Add class 'cp-no-responsive' to manage the line height of cp-highlight
			cp_set_no_responsive( sel_short_title_editor, data );

		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.short_title_editor.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     		editor.setReadOnly( false );
		} );
	}

	if( jQuery("#modal_note_1").length ) {

		var sel_modal_note_1 = jQuery("#modal_note_1");

		// // Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'modal_note_1' );
		CKEDITOR.instances.modal_note_1.config.toolbar = 'Small';

		// Add class 'cp-no-responsive' to manage the line height of cp-highlight
		CKEDITOR.instances.modal_note_1.on('instanceReady',function(){
		   	var data = CKEDITOR.instances.modal_note_1.getData();
			cp_set_no_responsive( sel_modal_note_1, data );
		});

		CKEDITOR.instances.modal_note_1.on( 'change', function() {

			// Set class - `cp-modal-exceed`
			CPModelHeight();

			//	Set equalize columns
			cp_column_equilize();

			//set color for li tags
        		cp_color_for_list_tag();

			var data = CKEDITOR.instances.modal_note_1.getData();
			parent.updateHTML(data,'smile_modal_note_1');

			//	Add class 'cp-no-responsive' to manage the line height of cp-highlight
			cp_set_no_responsive( sel_modal_note_1, data );

		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.modal_note_1.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     		editor.setReadOnly( false );
		} );
	}

	if( jQuery("#modal_note_2").length ) {

		var sel_modal_note_2 = jQuery("#modal_note_2");

		// // Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'modal_note_2' );
		CKEDITOR.instances.modal_note_2.config.toolbar = 'Small';

		// Add class 'cp-no-responsive' to manage the line height of cp-highlight
		CKEDITOR.instances.modal_note_2.on('instanceReady',function(){
		   	var data = CKEDITOR.instances.modal_note_2.getData();
			cp_set_no_responsive( sel_modal_note_2, data );
		});

		CKEDITOR.instances.modal_note_2.on( 'change', function() {

			// Set class - `cp-modal-exceed`
			CPModelHeight();

			//	Set equalize columns
			cp_column_equilize();

			//set color for li tags
        		cp_color_for_list_tag();

			var data = CKEDITOR.instances.modal_note_2.getData();
			parent.updateHTML(data,'smile_modal_note_2');

			//	Add class 'cp-no-responsive' to manage the line height of cp-highlight
			cp_set_no_responsive( sel_modal_note_2, data );

		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.modal_note_2.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     		editor.setReadOnly( false );
		} );
	}

	if( jQuery("#afl_editor").length ) {

		var sel_afl_editor = jQuery("#afl_editor");

		// Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'afl_editor' );
		CKEDITOR.instances.afl_editor.config.toolbar = 'Small';

		//	1. Add class 'cp-no-responsive' to manage the line height of cp-highlight
		CKEDITOR.instances.afl_editor.on('instanceReady',function(){
		   	var data = CKEDITOR.instances.afl_editor.getData();
			cp_set_no_responsive( sel_afl_editor, data );
		});

		CKEDITOR.instances.afl_editor.on( 'change', function() {

			//	Set class - `cp-modal-exceed`
			CPModelHeight();

			//	Set equalize columns
			cp_column_equilize();

			//set color for li tags
        		cp_color_for_list_tag();

        		jQuery(document).trigger('ckeditorChange');

			var data = CKEDITOR.instances.afl_editor.getData();
			parent.updateHTML(data,'smile_affiliate_title');

			//	2. Add class 'cp-no-responsive' to manage the line height of cp-highlight
			cp_set_no_responsive( sel_afl_editor, data );

		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.afl_editor.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     		editor.setReadOnly( false );
		} );
	}

	if( jQuery("#description_bottom").length ) {

		var sel_description_bottom = jQuery("#description_bottom");

		// Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'description_bottom' );

		//	1. Add class 'cp-no-responsive' to manage the line height of cp-highlight
		CKEDITOR.instances.description_bottom.on('instanceReady',function(){
		   	var data = CKEDITOR.instances.description_bottom.getData();
			cp_set_no_responsive( sel_description_bottom, data );
		});

		//CKEDITOR.instances.description_bottom.config.toolbar = 'Small';
		CKEDITOR.instances.description_bottom.on( 'change', function() {


			//	Set class - `cp-modal-exceed`
			CPModelHeight();

			//	Set equalize columns
			cp_column_equilize();

			//set color for li tags
        		cp_color_for_list_tag();

			var data = CKEDITOR.instances.description_bottom.getData();
			parent.updateHTML(data,'smile_modal_content');

			//	2. Add class 'cp-no-responsive' to manage the line height of cp-highlight
			cp_set_no_responsive( sel_description_bottom, data );

		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.description_bottom.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     		editor.setReadOnly( false );
		} );
	}

	if( jQuery("#count_down_editor").length ) {

		var sel_count_down_editor = jQuery("#count_down_editor");

		// Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'count_down_editor' );

		//	1. Add class 'cp-no-responsive' to manage the line height of cp-highlight
		CKEDITOR.instances.count_down_editor.on('instanceReady',function(){
		   	var data = CKEDITOR.instances.count_down_editor.getData();
			cp_set_no_responsive( sel_count_down_editor, data );
		});

		//CKEDITOR.instances.description_bottom.config.toolbar = 'Small';
		CKEDITOR.instances.count_down_editor.on( 'change', function() {

			//	Set class - `cp-modal-exceed`
			CPModelHeight();

			//	Set equalize coloumns
			cp_column_equilize();

			//set color for li tags
        		cp_color_for_list_tag();

			var data = CKEDITOR.instances.count_down_editor.getData();
			parent.updateHTML(data,'smile_count_down_title');

			//	2. Add class 'cp-no-responsive' to manage the line height of cp-highlight
			cp_set_no_responsive( sel_count_down_editor, data );

		} );
	}


	jQuery("body").on("click", ".cp-social-form-form", function(e){ parent.setFocusElement('form_bg_color'); e.stopPropagation(); });
	jQuery("body").on("click", ".counter_overlay", function(e){ parent.setFocusElement('modal_desc_bg_color'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-short-desc-container", function(e){ parent.setFocusElement('modal_desc_bg_color'); e.stopPropagation(); });
	jQuery("body").on("click", ".counter-desc-overlay", function(e){ parent.setFocusElement('form_bg_color'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-count-down-container", function(e){ parent.setFocusElement('counter_container_bg_color'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-form-seperator", function(e){ parent.setFocusElement('form_bg_color'); e.stopPropagation(); });
	jQuery("body").on("click", "#cp_defaultCountdown", function(e){ parent.setFocusElement('date_time_picker'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-image", function(e){ parent.setFocusElement('modal_image'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-submit", function(e){ parent.setFocusElement('btn_style'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-modal-body", function(e){ parent.setFocusElement('modal_bg_color'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-overlay", function(e) { parent.setFocusElement('modal_overlay_bg_color'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-overlay-close", function(e){ parent.setFocusElement('close_modal'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-affilate-link", function(e){ parent.setFocusElement('affiliate_username'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-affilate", function(e){ parent.setFocusElement('affiliate_username'); e.stopPropagation(); });

	// remove blinking cursor
	jQuery("body").on("click select", ".cp-highlight,.cp-name,.cp-email", function(e){
		jQuery(".cp-modal-body").find(".blinking-cursor").remove();
	});

	// Preventing links and form navigation in an iframe
	jQuery('a').click(function(e){
		e.preventDefault();
	});
	jQuery('button').click(function(e){
		e.preventDefault();
	});
	jQuery(this).on('submit','form',function(e){
		e.preventDefault();
	});

});


function single__toggle_class(selector, toggle_class, value, required){
	if( typeof value !== "undefined" && value == "" || value == required ){
		jQuery( selector ).addClass( toggle_class );
	} else {
		jQuery( selector ).removeClass( toggle_class );
	}
}

function add_css( selector, property, value ) {
	jQuery(selector).css(property,value);
}

function update_modal_size( modal_size ) {
	var cp_modal = jQuery(".cp-modal");
	if( !cp_modal.hasClass("cp-modal-exceed") ){
		cp_modal.attr('class', 'cp-modal '+modal_size);
	} else {
		cp_modal.attr('class', 'cp-modal cp-modal-exceed '+modal_size);
	}
}

function apply_border_and_shadow( data ) {

	var v_horizontal 	= jQuery('#horizontal-length', window.parent.document ).val() || '',
		v_vertical 		= jQuery('#vertical-length', window.parent.document ).val() || '',
		v_blur 			= jQuery('#blur-radius', window.parent.document ).val() || '',
		v_spread 		= jQuery('#spread-field', window.parent.document ).val() || '',
		v_shadowColor 	= jQuery('#shadow-color', window.parent.document ).val() || '',
		v_shadow 		= jQuery('#smile_shadow_type', window.parent.document ).val() || '';

	var box_shadow_string  = 'type:'+v_shadow+'|';
		box_shadow_string += 'horizontal:'+v_horizontal+'|';
		box_shadow_string += 'vertical:'+v_vertical+'|';
		box_shadow_string += 'blur:'+v_blur+'|';
		box_shadow_string += 'spread:'+v_spread+'|';
		box_shadow_string += 'color:'+v_shadowColor;

	var br_all		= jQuery('#all-corners',  window.parent.document ).val() || '',
		br_tl		= jQuery('#top-left',  window.parent.document ).val() || '',
		br_tr		= jQuery('#top-right',  window.parent.document ).val() || '',
		br_bl		= jQuery('#bottom-left',  window.parent.document ).val() || '',
		br_br		= jQuery('#bottom-right',  window.parent.document ).val() || '',
		style		= jQuery('#select-border :selected',  window.parent.document ).val() || '',
		color		= jQuery('#br-color',  window.parent.document ).val() || '',
		br_type		= jQuery('#smile_adv_border_opt',  window.parent.document ).val() || '',
		bw_all		= jQuery('#width-allsides',  window.parent.document ).val() || '',
		bw_t		= jQuery('#width-top',  window.parent.document ).val() || '',
		bw_l		= jQuery('#width-left',  window.parent.document ).val() || '',
		bw_r		= jQuery('#width-right',  window.parent.document ).val() || '',
		bw_b		= jQuery('#width-bottom',  window.parent.document ).val() || '',
		bw_type		= jQuery('#smile_adv_borderwidth_opt',  window.parent.document ).val() || '';

	var border_string  = 'br_type:'+ br_type + '|';
		border_string += 'br_all:'+ br_all + '|';
		border_string += 'br_tl:'+ br_tl + '|';
		border_string += 'br_tr:'+ br_tr + '|';
		border_string += 'br_br:'+ br_br + '|';
		border_string += 'br_bl:'+ br_bl + '|';
		border_string += 'style:'+ style + '|';
		border_string += 'color:'+ color + '|';
		border_string += 'bw_type:'+ bw_type + '|';
		border_string += 'bw_all:'+ bw_all + '|';
		border_string += 'bw_t:'+ bw_t + '|';
		border_string += 'bw_l:'+ bw_l + '|';
		border_string += 'bw_r:'+ bw_r + '|';
		border_string += 'bw_b:'+ bw_b ;

	// style dependent variables
	var style 			= data.style,
		border_str 		= border_string, // data.border,
		box_shadow_str 	= box_shadow_string, 	//	data.box_shadow,
		cp_md_overlay    	= jQuery(".cp-modal-body-overlay"),
		cp_modal_content	= jQuery(".cp-modal-content");

	var box_shadow = generateBoxShadow(box_shadow_str);

	//	Update SELECTOR for 'Flat Discount' style - Apply - Border
	if( style === 'flat_discount' ) {
		generate_and_apply_border_css( '.cp-modal-body-inner', border_str );
	} else {
		generate_and_apply_border_css( '.cp-modal-content', border_str );
	}

	// if( jQuery('.cp-modal').hasClass('cp-modal-custom-size') ) {
	if( box_shadow.indexOf("inset") > -1 ) {

		// UPDATE - [data-css-selector] to set the box shadow target
		jQuery('.smile-input[name="box_shadow"]', window.parent.document ).attr('data-css-selector', '.cp-modal-body-overlay' );

		//	Apply - Box Shadow
		generate_and_apply_box_shadow_css( '.cp-modal-body-overlay', box_shadow_str );
		cp_modal_content.css('box-shadow', 'none');

	} else {

		// UPDATE - [data-css-selector] to set the box shadow target
		jQuery('.smile-input[name="box_shadow"]', window.parent.document ).attr('data-css-selector', '.cp-modal-content' );

		//	Apply - Box Shadow
		generate_and_apply_box_shadow_css( '.cp-modal-content', box_shadow_str );
		cp_md_overlay.css('box-shadow', 'none');

	}
}

/**
 * Window load
 */
jQuery(window).on( 'load', function(){
	parent.customizerLoaded();
});

/**
 * trigger after Ajax success
 */
jQuery(document).on("cp_ajax_loaded", function(e,data){
	// do your stuff here.
	cp_tooltip_reinitialize(data);
});


/**
 * Remove - Modal Image
 *
 * Also, Replaced [data-css-image-url] with empty. [Which is used to updated image URL without AJAX.]
 */
parent.jQuery(window.parent.document).on('radio_image_click', function( e, elm, val) {
	var elm_data = val;
	var elm_id = elm;

	var single_data = {};
	single_data[ elm_id ] = decodeURIComponent( elm_data );

	//	Update single instance from global variable 'smile_global_data'
	smile_global_data[ elm_id ] = decodeURIComponent( elm_data );

	jQuery(document).trigger('smile_customizer_field_change', [single_data] );

});

parent.jQuery(window.parent.document).on('smile-colorpicker-change', function( e, el, val) {

	if( jQuery(el).hasClass('content_bg_color') ) {
		smile_global_data.content_bg_color = val;
		jugaad_modal_layout_setup( smile_global_data );
	}

	var style = smile_global_data.style || null;

	if( jQuery(el).hasClass('form_bg_color') ) {
		smile_global_data.form_bg_color = val;
		if( style == 'jugaad' ){
			jugaad_modal_layout_setup( smile_global_data );
		}
	}

	
	if( jQuery(el).hasClass('modal_title_bg_color') ) {
		if( style == 'special_offer' ){
			smile_global_data.modal_title_bg_color = val;
			set_triangle_color(smile_global_data);
		}
	}

});

/**
 * trigger smile_customizer_field_change
 */
jQuery(document).on('smile_customizer_field_change',function(e, single_data){

	//console.log('single_data'+single_data);
	//	Update box shadow
	var shadow = single_data.shadow_type || null;
	if("shadow_type" in single_data) {
		apply_border_and_shadow( smile_global_data );
	}

	//	toggle - modal padding
	var content_padding = single_data.content_padding || null;
	if( "content_padding" in single_data ) {
		single__toggle_class('.cp-modal-body', 'cp-no-padding', content_padding, 1);
	}

	//	toggle - swap image & contents
	var image_position = single_data.image_position || null;
	if( "image_position" in single_data ) {
		single__toggle_class('.cp-text-container', 'cp-right-contain', image_position, 0);
	}

	/**
	 * Modal Image
	 */
	//	AJAX update image URL
	if( 'modal_image_size' in single_data || "modal_img_src" in single_data || "modal_img_custom_url" in single_data ) {
		cp_update_ajax_modal_image_src( smile_global_data );
	}

	/**
	 * Modal Background Image
	 */
	//	AJAX update image size - full / thumbnail / medium etc.
	if( 'modal_bg_image_size' in single_data ) {
		cp_update_ajax_bg_image_size( smile_global_data, '.cp-modal-body', '.cp-modal-content', 'modal_bg_image', 'opt_bg' );
	}

	//	Background Image - 	repeat
	var opt_bg_rpt = single_data.opt_bg_rpt || null;
	if( 'opt_bg_rpt' in single_data ) {
		if( 'modal_size' in smile_global_data && smile_global_data.modal_size == 'cp-modal-custom-size' ){
			add_css( '.cp-modal-body', "background-repeat", opt_bg_rpt );
		} else {
			add_css( '.cp-modal-content', "background-repeat", opt_bg_rpt );
		}
	}

	//	Background Image - 	position
	var opt_bg_pos = single_data.opt_bg_pos || null;
	if( 'opt_bg_pos' in single_data ) {
		if( 'modal_size' in smile_global_data && smile_global_data.modal_size == 'cp-modal-custom-size' ){
			add_css( '.cp-modal-body', "background-position", opt_bg_pos );
		} else {
			add_css( '.cp-modal-content', "background-position", opt_bg_pos );
		}
	}

	//	Background Image - 	size
	var opt_bg_size = single_data.opt_bg_size || null;
	if( 'opt_bg_size' in single_data ) {
		if( 'modal_size' in smile_global_data && smile_global_data.modal_size == 'cp-modal-custom-size' ){
			add_css( '.cp-modal-body', "background-size", opt_bg_size );
		} else {
			add_css( '.cp-modal-content', "background-size", opt_bg_size );
		}
	}

	//	Animations
	if( 'overlay_effect' in single_data || 'exit_animation' in single_data ) {
		cp_apply_animations( smile_global_data );
	}

	var style = smile_global_data.style || null;
	if( style != 'YouTube' ) {

		if( 'modal_bg_image_src' in single_data || 'modal_bg_image_custom_url' in single_data ) {
			cp_update_bg_image( smile_global_data, '.cp-modal-body', '.cp-modal-content', 'modal_bg_image', 'modal_bg_image_src' );

		} else if( 'form_bg_image_src' in single_data || 'form_bg_image_custom_url' in single_data ) {
			cp_update_bg_image( smile_global_data, '.cp-form-section', '', 'form_bg_image', 'form_bg_image_src' );

		} else if( 'content_bg_image_src' in single_data || 'content_bg_image_custom_url' in single_data ) {
			cp_update_bg_image( smile_global_data, '.cp-content-section', '', 'content_bg_image', 'content_bg_image_src' );
		}

	}

	//	Modal Size
	if( 'modal_size' in single_data || 'modal_bg_image_src' in single_data || 'modal_bg_image_custom_url' in single_data ) {
		update_modal_size( smile_global_data.modal_size );


		//	Modal width
		cp_modal_width_settings( smile_global_data );

		//	Reapply box_shadow
		apply_border_and_shadow( smile_global_data );

		//rearrange image 
		cp_update_bg_image( smile_global_data, '.cp-modal-body', '.cp-modal-content', 'modal_bg_image', 'modal_bg_image_src' );
	}

	/**
	 * Jugaad Background Image
	 */
	//	AJAX update image size - full / thumbnail / medium etc.
	if( 'form_bg_image_size' in single_data ) {
		cp_update_ajax_bg_image_size( smile_global_data, '.cp-form-section', '', 'form_bg_image', 'form_opt_bg' );
	}

	//	Background Image - 	repeat

	if( typeof smile_global_data.form_opt_bg !== 'undefined' ) {

 		var form_opt_bg_rpt = single_data.form_opt_bg_rpt || null;
		if( 'form_opt_bg_rpt' in single_data ) {
			add_css( '.cp-form-section', "background-repeat", form_opt_bg_rpt );
		}

		//	Background Image - 	position
		var form_opt_bg_pos = single_data.form_opt_bg_pos || null;
		if( 'form_opt_bg_pos' in single_data ) {
			add_css( '.cp-form-section', "background-position", form_opt_bg_pos );
		}

		//	Background Image - 	size
		var form_opt_bg_size = single_data.form_opt_bg_size || null;
		if( 'form_opt_bg_size' in single_data ) {
			add_css( '.cp-form-section', "background-size", form_opt_bg_size );
		}

		var content_opt_bg_rpt = single_data.content_opt_bg_rpt || null;
		if( 'content_opt_bg_rpt' in single_data ) {
			add_css( '.cp-content-section', "background-repeat", content_opt_bg_rpt );
		}

		//	Background Image - 	position
		var content_opt_bg_pos = single_data.content_opt_bg_pos || null;
		if( 'content_opt_bg_pos' in single_data ) {
			add_css( '.cp-content-section', "background-position", content_opt_bg_pos );
		}

		//	Background Image - 	size
		var content_opt_bg_size = single_data.content_opt_bg_size || null;
		if( 'content_opt_bg_size' in single_data ) {
			add_css( '.cp-content-section', "background-size", content_opt_bg_size );
		}
	}

	if( 'modal_layout' in single_data || 'modal_col_width' in single_data || 'form_separator' in single_data
		|| "form_sep_part_of" in single_data ) {
		jugaad_modal_layout_setup( smile_global_data );
	}

	//	Affiliate Settings
	if( 'affiliate_setting' in single_data ){
		cp_affilate_disable( single_data );
	}

});

jQuery(document).on('smile_data_received',function(e,data){
	global_initial_call( data );
});

jQuery(document).on('smile_data_continue_received', function(e,data) {
	cp_tooltip_settings( data );
	cp_tooltip_reinitialize( data );
	cp_add_custom_css( data );
	apply_custom_ht( data );
	cp_row_equilize();
});

parent.jQuery(window.parent.document).on('cp-slider-slide', function( e, el, val ) {

        if( jQuery(el).hasClass('submit_button_tb_padding') || jQuery(el).hasClass('form_input_padding_tb') || jQuery(el).hasClass('form_input_font_size') ) {
      	  CPModelHeight();
      	  cp_form_sep_top();
    	}
});

parent.jQuery(window.parent.document).on('smile-select-change smile-switch-change', function( e, el, val ) {
	cp_row_equilize();
});

parent.jQuery(window.parent.document).on('multiBoxUpdated', function( e, new_string, pre_id) {
       CPModelHeight();
       cp_column_equilize();
       cp_form_sep_top();
});

parent.jQuery(window.parent.document).on('smile-radio-image-change', function(e, el) {
    var v = jQuery(el).attr('class');
    var s = jQuery(el);
    if( s.hasClass('form_layout') ) {
       CPModelHeight();
   	   cp_column_equilize();
    }
    if( s.hasClass('form_layout') || s.hasClass('modal_layout') || s.hasClass('form_grid_structure') ) {
   	   cp_form_sep_top();//jugad style seperator
    }
});


function global_initial_call( data ) {

	var style = data.style || null;
	switch( style ) {
		case 'optin_to_win':
		case 'direct_download':
		case 'free_ebook':
			// Modal image
			cp_image_processing(data);
			single__toggle_class( '.cp-text-container', 'cp-right-contain', data.image_position, 0 );	//	Toggle - Image position Left or Right
			break;
		case 'special_offer' : set_triangle_color(data);break;
	}

	data.modal_size = ( typeof data.modal_size == 'undefined' ? 'cp-modal-custom-size' : data.modal_size );

	cp_set_image( data, 'modal' );

	update_modal_size( data.modal_size );

	//	Custom CSS
	cp_add_custom_css( data );

	//	Apply Animations To Modal
	cp_apply_animations( data );

	//	Affiliate Settings
	cp_affilate_settings( data );
	cp_affilate_reinitialize( data );

	// //	ToolTip Settings
	cp_tooltip_settings( data ); 		// close button and tooltip related settings
	cp_tooltip_reinitialize( data ); 	// reinitialize tooltip on modal resize

	//	Set Modal Width
	cp_modal_width_settings( data );

	if( typeof data.modal_layout !== 'undefined' ) {
		jugaad_modal_layout_setup( data );
	}

	//	Modal Background Image

	if( typeof data.form_bg_image !== 'undefined' ) {
		cp_update_bg_image( data, '.cp-form-section', '', 'form_bg_image', 'form_bg_image_src' );
	}

	if( typeof data.content_bg_image !== 'undefined' ) {
		cp_update_bg_image( data, '.cp-content-section', '', 'content_bg_image', 'content_bg_image_src' );
	}

	if( style != 'jugaad' && style != 'YouTube' ) {
		cp_update_bg_image( data, '.cp-modal-body', '.cp-modal-content', 'modal_bg_image', 'modal_bg_image_src' );
	}

	//	Border & Shadow
	apply_border_and_shadow( data );

	//	'cp_empty_classes' is a classes array defined in another file
	//	Add Cp-empty Class To Empty Containers
	jQuery.each( cp_empty_classes, function( key, value ) {
		if( jQuery(value).length !== 0 ) {
			cp_add_empty_class( key, value );
		}
	});

	if( typeof data.cp_custom_height !== 'undefined' ) {
		apply_custom_ht(data);
	}

	var content_padding = data.content_padding || null;
	if( typeof content_padding !== 'undefined'){
		single__toggle_class('.cp-modal-body', 'cp-no-padding', content_padding, 1);
	}
    CPModelHeight();
}


/**
 *	3. 	CKEditors Setup - ( Modal, SlideIn, InfoBar )
 *
 *	CKEditor setup of all modules.
 */
function cp_ckeditors_setup(data) {

	var cp_title 				=	jQuery(".cp-title"),
	cp_description 				=	jQuery(".cp-description"),
	cp_short_description        =   jQuery(".cp-short-description"),
	cp_confidential 			=	jQuery(".cp-info-container"),
	cp_desc_bottom 				=	jQuery(".cp-desc-bottom"),
	cp_mid_desc 				=	jQuery("#mid_desc_editor"),
	cp_modal_popup_container 	=	jQuery(".cp-modal-popup-container"),
	cp_count_down_desc 			=	jQuery(".cp-count-down-desc"),
	cp_sec_title 				=	jQuery(".cp-sec-title");

	var  modal_title 			=	data.modal_title1 || null,
	modal_title_color		 	=	data.modal_title_color || null,
	modal_sec_title_color 		= 	data.modal_sec_title_color || null,
	sec_title 					= 	data.modal_sec_title || null,
	modal_short_desc 			=	data.modal_short_desc1 || null,
	modal_short_description 	=	data.modal_short_desc || null,
	modal_confidential			=	data.modal_confidential || null,
	button_title				=	data.button_title || null,
	tip_color				 	=	data.tip_color || null,
	modal_desc_color		  	=	data.modal_desc_color || null,
	modal_content				=	data.modal_content || null,
	style_id 					=	data.style_id || null,
	varient_style_id 			=	data.variant_style_id || null,
	count_down_title 			=	data.count_down_title || null,
	modal_middle_desc 			=	data.modal_middle_desc || null;

	// 	Add Style Id As Class To Container
	if( cp_isValid( varient_style_id ) ){
		style_id = varient_style_id;
	}
	cp_modal_popup_container.addClass( style_id );

	// 	Modal Title Editor
	if( jQuery("#main_title_editor").length ) {
		if( cp_isValid( modal_title ) ) {
			modal_title = htmlEntities(modal_title);

			cp_title.html(modal_title);
			CKEDITOR.instances.main_title_editor.setData(modal_title);
		}

		//	Color
		if( cp_isValid( modal_title_color ) ) {
			cp_title.css('color', modal_title_color);
		}
	}

	// 	Secondary Title Editor
	if( jQuery("#sec_title_editor").length ) {

		if( cp_isValid( sec_title ) ) {
			modal_sec_title = htmlEntities(sec_title);
			cp_sec_title.html(modal_sec_title);
			CKEDITOR.instances.sec_title_editor.setData(modal_sec_title);
		}

		//	Color
		if( cp_isValid( modal_sec_title_color ) ) {
			cp_sec_title.css('color', modal_sec_title_color);
		}
	}

	//	Short Description Editor 1
	if( jQuery("#desc_editor").length ) {
		if( cp_isValid( modal_short_desc ) ){
			modal_short_desc = htmlEntities(modal_short_desc);
			cp_description.html(modal_short_desc);
			CKEDITOR.instances.desc_editor.setData(modal_short_desc);
		}

		//	Color
		if( cp_isValid( modal_desc_color ) ) {
			cp_description.css('color', modal_desc_color);
		}
	}

	//	Short Description Editor
	if( jQuery("#short_desc_editor").length ) {
		if( cp_isValid( modal_short_description ) ){
			modal_short_description = htmlEntities(modal_short_description);
			cp_short_description.html(modal_short_description);
			CKEDITOR.instances.short_desc_editor.setData(modal_short_description);
		}
	}

	//	Confidential Info Editor
	if( jQuery("#info_editor").length ){
		if( cp_isValid( modal_confidential ) ){
			modal_confidential = htmlEntities(modal_confidential);
			cp_confidential.html(modal_confidential);
			CKEDITOR.instances.info_editor.setData(modal_confidential);
		}

		//	Color
		if( cp_isValid( tip_color ) ) {
			cp_confidential.css('color', tip_color );
		}
	}

	//	Description Bottom
	if( jQuery("#description_bottom").length ) {
		if( cp_isValid( modal_content ) ) {
			modal_content = htmlEntities(modal_content);
			cp_desc_bottom.html(modal_content);
			CKEDITOR.instances.description_bottom.setData(modal_content);
		}
	}

	//	Count Down Editor
	if( jQuery("#count_down_editor").length ) {
		if( cp_isValid( count_down_title ) ) {
			count_down_title = htmlEntities(count_down_title);
			cp_count_down_desc.html( count_down_title );
			CKEDITOR.instances.count_down_editor.setData(count_down_title);
		}
	}

	// 	Extra middle editor
	if(jQuery("#mid_desc_editor").length ) {
		if( cp_isValid( modal_middle_desc ) ) {
			modal_middle_desc = htmlEntities(modal_middle_desc);
			cp_mid_desc.html(modal_middle_desc);
			CKEDITOR.instances.mid_desc_editor.setData(modal_middle_desc);
		}
	}

	//Short title
	if( typeof data.modal_short_title !== 'undefined' ) {
		var modal_short_title = data.modal_short_title;
		var cp_short_title    = jQuery(".cp-short-title");
		modal_short_title = htmlEntities(modal_short_title);
		cp_short_title.html(modal_short_title);
		if( jQuery("#short_title_editor").length ) {
			CKEDITOR.instances.short_title_editor.setData(modal_short_title);
		}
	}

	// Modal Note
	if( typeof data.modal_note_1 !== 'undefined' ) {
		var modal_note_1  = data.modal_note_1;
		var cp_modal_note = jQuery(".cp-modal-note");
		modal_note_1 = htmlEntities(modal_note_1);
		cp_modal_note.html(modal_note_1);
		if( jQuery("#modal_note_1").length ) {
			CKEDITOR.instances.modal_note_1.setData(modal_note_1);
		}
	}

	// Modal Note
	if( typeof data.modal_note_2 !== 'undefined' ) {
		var modal_note_2    = data.modal_note_2;
		var cp_modal_note_2 = jQuery(".cp-modal-note-2");
		modal_note_2 = htmlEntities(modal_note_2);
		cp_modal_note_2.html(modal_note_2);
		if( jQuery("#modal_note_2").length ) {
			CKEDITOR.instances.modal_note_2.setData(modal_note_2);
		}
	}
}


function jugaad_modal_layout_setup( data ) {

	// html container variables
	var  cp_submit 				= jQuery(".cp-submit"),
		cp_form_button      	= jQuery(".form-button"),
		cp_modal_body			= jQuery(".cp-modal-body"),
		cp_modal				= jQuery(".cp-modal"),
		cp_modal_content		= jQuery(".cp-modal-content"),
		modal_overlay			= jQuery(".cp-overlay"),
		cp_modal_body_inner		= jQuery(".cp-modal-body-inner"),
		cp_md_overlay       	= jQuery(".cp-modal-body-overlay"),
		form_with_name 			= jQuery(".cp-form-with-name"),
		form_without_name 		= jQuery(".cp-form-without-name"),
		cp_email_form       	= jQuery(".cp-email-form"),
		cp_email_input      	= jQuery(".cp-email.cp-input"),
		form_section        	= jQuery(".cp-form-section"),
		content_section     	= jQuery(".cp-content-section"),
		cp_short_title          = jQuery(".cp-short-title"),
		cp_modal_note           = jQuery(".cp-modal-note"),
		cp_modal_note_2         = jQuery(".cp-modal-note-2");

	// data variables
	var cp_modal_width				= data.cp_modal_width,
		modal_title 				= data.modal_title1,
		bg_color					= data.modal_bg_color,
		overlay_bg_color			= data.modal_overlay_bg_color,
		modal_title_color			= data.modal_title_color,
		tip_color					= data.tip_color,
		border_str 					= data.border,
		btn_disp_next_line  		= data.btn_disp_next_line,
		box_shadow_str 				= data.box_shadow,
		modal_content				= data.modal_content,
		close_txt					= data.close_txt,
		content_padding				= data.content_padding,
		modal_bg_image				= data.modal_bg_image,
		opt_bg						= data.opt_bg,
		modal_bg_image_size			= data.modal_bg_image_size,
		namefield 					= data.namefield,
		affiliate_title 			= data.affiliate_title,
		cp_google_fonts 			= data.cp_google_fonts,
		cp_name_form        		= jQuery(".cp-name-form"),
		form_position               = data.form_position,
		img_position                = data.img_position,
		modal_image 	    		= data.modal_image,
		cp_img_container			= jQuery(".cp-image-container"),
		cp_submit_container         = jQuery('.cp-submit-container'),
		image_vertical_position 	= data.image_vertical_position,
		image_horizontal_position 	= data.image_horizontal_position,
		image_size 				    = data.image_size,
		placeholder_font      		= data.placeholder_font,
		modal_image_size			= data.modal_image_size,
		form_bg_color               = data.form_bg_color,
		form_bg_image              	= data.form_bg_image,
		content_bg_color           	= data.content_bg_color,
		content_bg_image           	= data.content_bg_image,
		form_separator             	= data.form_separator,
		form_sep_part_of           	= data.form_sep_part_of,
		form_sep_fill_color        	= data.form_sep_fill_color,
		modal_layout               	= data.modal_layout,
		modal_col_width            	= data.modal_col_width,
		modal_short_title          	= data.modal_short_title,
		modal_note_1               	= data.modal_note_1,
		modal_note_2               	= data.modal_note_2;

	var addContentClasses = addFormClasses = '';

	var modal_size = ( typeof data.modal_size == 'undefined' ? 'cp-modal-custom-size' : data.modal_size );

	var rmClasses = 'col-md-6 col-sm-6 col-lg-6 col-md-5 col-sm-5 col-lg-5 col-md-7 col-sm-7 col-lg-7';

	if( modal_layout == 'form_left' || modal_layout == 'form_right' || modal_layout == 'form_left_img_top' || modal_layout == 'form_right_img_top' ) {
		if ( modal_col_width == 0 ) {
			addFormClasses = addContentClasses = 'col-md-6 col-sm-6 col-lg-6';
		} else {
			addFormClasses  = 'col-md-5 col-sm-5 col-lg-5';
			addContentClasses = 'col-md-7 col-sm-7 col-lg-7';
		}
	}

	content_section.removeClass( rmClasses + 'form-sep-padding cp-columns-equalized').addClass(addContentClasses);
	jQuery(".cp-form-section").removeClass(rmClasses +' form-sep-padding').addClass(addFormClasses);
	jQuery(".cp-jugaad-text-container,.cp-image-container").removeClass('col-md-5 col-sm-5 col-lg-5 col-md-7 col-sm-7 col-lg-7').addClass('col-md-12 col-sm-12');
	jQuery(".cp-jugaad-text-container,.cp-info-container").addClass('cp-text-center');

	form_section.insertBefore(".cp-content-section");
	jQuery(".cp-image-container").insertBefore(".cp-jugaad-text-container");
	jQuery(".cp-image-container").removeClass('cp-hidden-element');
	jQuery(".cp-image-container").show();
	var form_sep_pos = 'vertical';
	var form_sep_direction = 'upward';
	jQuery(".cp-modal-body > .cp-row").attr('class', "cp-row cp-table "+ modal_layout);
	jQuery(".cp-modal-body > .cp-row").css('height','auto');

	if ( modal_col_width == 0 ) {
		jQuery(".cp-modal-body > .cp-row").addClass('form-one-by-two');
	} else {
		jQuery(".cp-modal-body > .cp-row").addClass('form-one-third');
	}

	switch(modal_layout) {
		case "form_left":
			content_section.css('height','auto');
			form_section.addClass("form-pos-left").removeClass('form-pos-right form-pos-bottom');
			jQuery(".cp-image-container").addClass('cp-hidden-element');
			jQuery(".cp-jugaad-text-container").addClass('txt-pos-bottom').removeClass('txt-pos-left txt-pos-right');
			if( form_sep_part_of == 1 ) {
				form_sep_direction = 'downward';
			}
		break;
		case "form_right":
			form_section.addClass("form-pos-right").removeClass('form-pos-left form-pos-bottom');
			content_section.insertBefore(".cp-form-section").css('height','auto');
			jQuery(".cp-image-container").addClass('cp-hidden-element');
			jQuery(".cp-jugaad-text-container").addClass('txt-pos-bottom').removeClass('txt-pos-left txt-pos-right');
			if( form_sep_part_of == 0 ) {
				form_sep_direction = 'downward';
			}
		break;
		case "form_left_img_top":
				form_section.addClass("form-pos-left cp-column-equalized-center").removeClass('form-pos-right form-pos-bottom');
				jQuery(".cp-jugaad-text-container").addClass('txt-pos-bottom').removeClass('txt-pos-left txt-pos-right cp-column-equalized-center');
				content_section.addClass('cp-column-equalized-center');
				jQuery(".cp-image-container").insertBefore(".cp-jugaad-text-container").removeClass('cp-column-equalized-center');
				if( form_sep_part_of == 1 ) {
					form_sep_direction = 'downward';
				}
		break;
		case "form_right_img_top":
				form_section.addClass("form-pos-right cp-column-equalized-center").removeClass('form-pos-left form-pos-bottom');
				content_section.insertBefore(".cp-form-section").css('height','auto').addClass('cp-column-equalized-center');
				jQuery(".cp-image-container").insertBefore(".cp-jugaad-text-container").removeClass('cp-column-equalized-center');
				jQuery(".cp-jugaad-text-container").removeClass('cp-column-equalized-center');
				if( form_sep_part_of == 0 ) {
					form_sep_direction = 'downward';
				}
		break;
		case "img_left_form_bottom":
			jQuery(".cp-modal-body > .cp-row").addClass("cp-block").removeClass('cp-table');
			form_section.addClass("form-pos-bottom").removeClass('form-pos-right form-pos-left');
			jQuery(".cp-form-section,.cp-content-section").removeClass('col-md-5 col-sm-5 col-lg-5 col-md-7 col-sm-7 col-lg-7').addClass('col-md-12 col-sm-12 col-lg-12');
			jQuery(".cp-jugaad-text-container").removeClass('col-md-12 col-sm-12 col-sm-5 col-md-5 col-lg-5 cp-text-center').addClass('col-md-7 col-sm-7 col-lg-7 cp-column-equalized-center');
			jQuery(".cp-image-container").removeClass('col-md-12 col-sm-12 cp-text-center').addClass('col-md-5 col-sm-5 col-lg-5 cp-column-equalized-center');
			content_section.insertBefore(".cp-form-section").addClass('cp-columns-equalized');
			jQuery(".cp-image-container").insertBefore(".cp-jugaad-text-container");
			jQuery(".cp-info-container").removeClass('cp-text-center');
			form_sep_pos = 'horizontal';
			if( form_sep_part_of == 0 ) {
				form_sep_direction = 'downward';
			}

			jQuery('.cp-form-with-name .cp-name-form,.cp-form-with-name .cp-email-form').addClass('col-md-6 col-sm-6 col-lg-6').removeClass('col-md-12 col-sm-12 col-lg-12');
			if( namefield == '1' ) {
				jQuery('.cp-name-form,.cp-email-form,.cp-submit-container').addClass('col-md-5 col-sm-5 col-lg-5').removeClass('col-sm-6 col-md-6 col-lg-6 col-sm-12 col-lg-12 col-md-12 col-sm-7 col-md-7');
			}

		break;
		case "img_right_form_bottom":
			jQuery(".cp-modal-body > .cp-row").addClass("cp-block").removeClass('cp-table').css( 'height', 'auto' );
			form_section.addClass("form-pos-bottom").removeClass('form-pos-right form-pos-left');
			jQuery(".cp-form-section,.cp-content-section").removeClass('col-md-5 col-sm-5 col-lg-5 col-md-7 col-sm-7 col-lg-7').addClass('col-md-12 col-sm-12 col-lg-12');
			jQuery(".cp-jugaad-text-container").removeClass('col-md-12 col-sm-12 col-sm-5 col-md-5 col-lg-5 cp-text-center').addClass('col-md-7 col-sm-7 col-lg-7 cp-column-equalized-center');
			jQuery(".cp-image-container").removeClass('col-md-12 col-sm-12 cp-text-center').addClass('col-md-5 col-sm-5 col-lg-5 cp-column-equalized-center');
			content_section.insertBefore(".cp-form-section").addClass('cp-columns-equalized');
			jQuery(".cp-jugaad-text-container").insertBefore(".cp-image-container");
			jQuery(".cp-info-container").removeClass('cp-text-center');
			form_sep_pos = 'horizontal';
			if( form_sep_part_of == 0 ) {
				form_sep_direction = 'downward';
			}
			jQuery('.cp-form-with-name .cp-name-form,.cp-form-with-name .cp-email-form').addClass('col-md-6 col-sm-6 col-lg-6').removeClass('col-md-12 col-sm-12 col-lg-12');
			if( namefield == '1' ) {
				jQuery('.cp-name-form,.cp-email-form,.cp-submit-container').addClass('col-md-5 col-sm-5 col-lg-5').removeClass('col-sm-6 col-md-6 col-lg-6 col-sm-12 col-lg-12 col-md-12 col-sm-7 col-md-7');
			}
		break;
		case "form_bottom_img_top":
			jQuery(".cp-modal-body > .cp-row").addClass("cp-block").removeClass('cp-table');
			form_section.addClass("form-pos-bottom").removeClass('form-pos-right form-pos-left');
			jQuery(".cp-form-section,.cp-content-section").removeClass('col-md-5 col-sm-5 col-lg-5 col-md-7 col-sm-7 col-lg-7').addClass('col-md-12 col-sm-12 col-lg-12');
		    jQuery(".cp-jugaad-text-container").removeClass('col-md-7 col-sm-7 col-sm-5 col-md-5 col-lg-5 cp-column-equalized-center').addClass('col-md-12 col-sm-12 col-lg-12 cp-text-center');
			jQuery(".cp-image-container").removeClass('col-md-5 col-sm-5 cp-column-equalized-center').addClass('col-md-12 col-sm-12 col-lg-12 cp-text-center');
			content_section.insertBefore(".cp-form-section").css('height','auto');
			jQuery(".cp-image-container").insertBefore(".cp-jugaad-text-container");
			form_sep_pos = 'horizontal';
			if( form_sep_part_of == 0 ) {
				form_sep_direction = 'downward';
			}
			if( namefield == '1' ) {
				jQuery('.cp-name-form,.cp-email-form,.cp-submit-container').addClass('col-md-5 col-sm-5 col-lg-5').removeClass('col-sm-6 col-md-6 col-lg-6 col-sm-12 col-lg-12 col-md-12 col-sm-7 col-md-7');
			}
		break;
		case "form_bottom":
			jQuery(".cp-image-container").addClass('cp-hidden-element');
			jQuery(".cp-modal-body > .cp-row").addClass("cp-block").removeClass('cp-table');
			form_section.addClass("form-pos-bottom").removeClass('form-pos-right form-pos-left');
			jQuery(".cp-form-section,.cp-content-section").removeClass('col-md-5 col-sm-5 col-lg-5 col-md-7 col-sm-7 col-lg-7').addClass('col-md-12 col-sm-12 col-lg-12');
			jQuery(".cp-jugaad-text-container").removeClass('col-md-7 col-sm-7 col-sm-5 col-md-5 col-lg-5 cp-column-equalized-center').addClass('col-md-12 col-sm-12 col-lg-12 cp-text-center');
			jQuery(".cp-image-container").removeClass('col-md-5 col-sm-5 cp-column-equalized-center').addClass('col-md-12 col-sm-12 col-lg-12 cp-text-center');
			content_section.insertBefore(".cp-form-section").css('height','auto');
			form_sep_pos = 'horizontal';
			if( form_sep_part_of == 0 ) {
				form_sep_direction = 'downward';
			}
			if( namefield == '1' ) {
				jQuery('.cp-name-form,.cp-email-form,.cp-submit-container').addClass('col-md-5 col-sm-5 col-lg-5').removeClass('col-sm-6 col-md-6 col-lg-6 col-sm-12 col-lg-12 col-md-12 col-sm-7 col-md-7');
			}
		break;
	}

	var svg = '';
	if(form_sep_part_of == 0)  {
		var svgFillColor =  content_bg_color;
		var form_part_of = 'part-of-content';
	} else {
		var svgFillColor = form_bg_color;
		var form_part_of = 'part-of-form';
	}

	jQuery(".cp-form-separator").remove();

	var viewbox  = cp_get_viewbox_svg(form_separator);

	var shape = form_separator;

	if( form_separator !== 'none' ) {

		var svg = cp_get_svg(shape,svgFillColor,viewbox,form_sep_part_of);
		var cp_modal_height = jQuery('.cp-modal-body').height();
		var form_sep_width = cp_modal_height +'px';

		if( form_sep_pos == 'horizontal' ) {
			if( form_sep_part_of == 0 ) {
				jQuery(".cp-modal-body .cp-row .cp-content-section").append('<div class="cp-form-separator '+form_separator+' '+modal_layout+' '+form_sep_direction+' part-of-content cp-fs-'+form_sep_pos+' cp-fs-'+form_sep_pos+'-content" >'+svg+'<div>');
			} else {
				jQuery(".cp-modal-body .cp-row .cp-form-section").append('<div class="cp-form-separator '+form_separator+' '+modal_layout+' '+form_sep_direction+' part-of-form cp-fs-'+form_sep_pos+' cp-fs-'+form_sep_pos+'-form" >'+svg+'<div>');
			}
		} else {
			if( form_sep_part_of == 0 ) {
				jQuery(".cp-modal-body .cp-row").append('<div class="cp-form-separator '+form_separator+' '+modal_layout+' '+form_sep_direction+' part-of-content cp-fs-'+form_sep_pos+' cp-fs-'+form_sep_pos+'-content" >'+svg+'<div>');
			} else {
				jQuery(".cp-modal-body .cp-row").append('<div class="cp-form-separator '+form_separator+' '+modal_layout+' '+form_sep_direction+' part-of-form cp-fs-'+form_sep_pos+' cp-fs-'+form_sep_pos+'-form" >'+svg+'<div>');
			}
		}
	}

	cp_column_equilize();
	form_sep_position();
	cp_form_sep_top();
	cp_set_width_svg();
}

function apply_custom_ht(data){
	//apply custom height to body
	var cp_custom_height 	= data.cp_custom_height,
		cp_modal_height     = data.cp_modal_height,
		cp_modal_body		= jQuery(".cp-modal-body");
		if( cp_custom_height == 1 ) {
			if(!cp_modal_body.find(".cp-row").hasClass('cp-row-center')){
				cp_modal_body.find(".cp-row").addClass('cp-row-center');
			}
			if(!cp_modal_body.find(".cp-subtitle-container").hasClass('cp-row-equalized-center') || !cp_modal_body.find(".cp-text-container").hasClass('cp-row-equalized-center')){
				cp_modal_body.find(".cp-subtitle-container").addClass('cp-row-equalized-center');
				cp_modal_body.find(".cp-text-container").addClass('cp-row-equalized-center');
			}

			cp_modal_body.css('min-height', cp_modal_height+'px');
		} else {
			cp_modal_body.css('min-height', '');
			cp_modal_body.find(".cp-row").removeClass('cp-row-center');
			cp_modal_body.find(".cp-subtitle-container").removeClass('cp-row-equalized-center');
			cp_modal_body.find(".cp-text-container").removeClass('cp-row-equalized-center');
		}
}

function set_triangle_color( data ){
	var modal_title_bg_color 	= data.modal_title_bg_color,
		cp_description			= jQuery(".cp-description");
		cp_description.css( 'border-top-color', modal_title_bg_color );		
}