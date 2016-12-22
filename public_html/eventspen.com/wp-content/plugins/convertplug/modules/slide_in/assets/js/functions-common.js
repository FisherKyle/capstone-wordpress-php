//	Add class 'cp-no-responsive' to manage the line height of cp-highlight
function cp_set_no_responsive( sel, data ) {
	if ( data.toLowerCase().indexOf("cp_font") >= 0 && data.match("^<span") && data.match("</span>$") ) {
		sel.addClass('cp-no-responsive');
	} else {
		sel.removeClass('cp-no-responsive');
	}
}

var cp_empty_classes = {
		".cp-title" 				: ".cp-title-container",
		".cp-sec-title"             : ".cp-sec-title-container",
		".cp-description"  			: ".cp-desc-container",
		".cp-info-container" 		: ".cp-info-container",
		".cp-short-description"  	: ".cp-short-desc-container",
		".cp-desc-bottom"           : ".cp-desc-timetable",
		".cp-mid-description"       : ".cp-mid-desc-container"
	};

jQuery(document).ready(function(){

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

	if( jQuery("#main_title_editor").length !== 0 ) {
		// Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'main_title_editor' );

		//	Initially set show CKEditor of 'cp-title'
		//	Ref: http://docs.ckeditor.com/#!/api/CKEDITOR.focusManager
		var focusManager = new CKEDITOR.focusManager( CKEDITOR.instances.main_title_editor );
		focusManager.focus();

		CKEDITOR.instances.main_title_editor.on( 'change', function() {

			//	Set class - `cp-modal-exceed`
			CP_slide_in_height();

			// Remove Blinking cursor
			jQuery(".cp-slidein-body").find(".blinking-cursor").remove();

			//	Check & update responsive font sizes
			check_responsive_font_sizes();

			//set color for li tags
        	cp_color_for_list_tag();

        	//	Set equalize columns
			cp_slide_in_column_equilize();

			var data = CKEDITOR.instances.main_title_editor.getData();
			parent.updateHTML(htmlEntities(data),'smile_slidein_title1');
		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.main_title_editor.on( 'instanceReady', function( ev ) {
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
			CP_slide_in_height();

			//set color for li tags
        	cp_color_for_list_tag();

			var data = CKEDITOR.instances.info_editor.getData();
			parent.updateHTML(data,'smile_slidein_confidential');

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

	if( jQuery("#sec_title_editor").length !== 0 ) {
		// Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'sec_title_editor' );
		CKEDITOR.instances.sec_title_editor.on( 'change', function() {

			//	Check & update responsive font sizes
			check_responsive_font_sizes();

			//	Set class - `cp-modal-exceed`
			CP_slide_in_height();

			//set color for li tags
        	cp_color_for_list_tag();

			var data = CKEDITOR.instances.sec_title_editor.getData();
			parent.updateHTML(htmlEntities(data),'smile_slidein_sec_title');
		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.sec_title_editor.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     		editor.setReadOnly( false );
		} );
	}


	if( jQuery("#desc_editor").length !== 0 ) {
		// Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'desc_editor' );
		CKEDITOR.instances.desc_editor.config.toolbar = 'Small';
		CKEDITOR.instances.desc_editor.on( 'change', function() {

			//	Check & update responsive font sizes
			check_responsive_font_sizes();

			//set color for li tags
        	cp_color_for_list_tag();

        	//	Set class - `cp-modal-exceed`
			CP_slide_in_height();

			var data = CKEDITOR.instances.desc_editor.getData();
			parent.updateHTML(data,'smile_slidein_short_desc1');
		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.desc_editor.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     		editor.setReadOnly( false );
		} );
	}

	if(jQuery("#short_desc_editor").length !== 0) {
		// Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'short_desc_editor' );
		CKEDITOR.instances.short_desc_editor.config.toolbar = 'Small';
		CKEDITOR.instances.short_desc_editor.on( 'change', function() {

			//	Set class - `cp-modal-exceed`
			CP_slide_in_height();

			//	Check & update responsive font sizes
			check_responsive_font_sizes();

			//set color for li tags
        	cp_color_for_list_tag();

        	//	Set equalize columns
			cp_slide_in_column_equilize();

			var data = CKEDITOR.instances.short_desc_editor.getData();
			parent.updateHTML(data,'smile_slidein_content');
		} );

		// Use below code to 'reinitialize' CKEditor
		// IN ANY CASE IF CKEDITOR IS NOT INITIALIZED THEN USE BELOW CODE
		CKEDITOR.instances.short_desc_editor.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     		editor.setReadOnly( false );
		} );
	}

	//open slide on click of button
	jQuery("body").on("click", ".cp-toggle-container", function(e){

		jQuery(this).toggleClass("cp-slide-hide-btn");

		parent.setFocusElement('slide_button_title');

		var	cp_animate_container = jQuery(".cp-animate-container"),
			entryanimation       = cp_animate_container.attr("data-entry-animation"),
			slidein_overlay 	 = jQuery(".slidein-overlay");

		slidein_overlay.addClass('cp-slidein-click');
		cp_animate_container.attr( 'class', 'cp-animate-container cp-hide-slide smile-animated' );

		setTimeout(function() {
			cp_animate_container.attr('class' , 'cp-animate-container smile-animated '+entryanimation);
		}, 10);

		jQuery('cp-backend-tooltip-hide').remove();
		jQuery('head').append('<style class="cp-backend-tooltip-hide">.customize-support .tip[class*="arrow"]:before{display:block} .tip[class*="arrow"]:before{display:block}.customize-support .tip[class*="arrow"]{display:block} .tip[class*="arrow"]{display:block}</style>');

		e.stopPropagation();
	});


	// close slide in on click of button
	jQuery("body").on("click", ".slidein-overlay-close", function(e){

		if( !jQuery(".slidein-overlay").hasClass('cp-slide-without-toggle') ) {
			var cp_toggle_container    = jQuery(".cp-toggle-container"),
				exitanimation 		 = jQuery(".cp-animate-container").attr('data-exit-animation'),
				cp_animate_container = jQuery(".cp-animate-container"),
				slidein_overlay 	 = jQuery(".slidein-overlay");

	         cp_animate_container.attr('class' , 'cp-animate-container smile-animated '+exitanimation);

	         slidein_overlay.removeClass('cp-slidein-click');

			setTimeout(function() {
				cp_animate_container.addClass("cp-hide-slide");
				cp_toggle_container.removeClass("cp-slide-hide-btn");
				cp_animate_container.removeClass(exitanimation);
			}, 500);

			jQuery('cp-backend-tooltip-hide').remove();
			jQuery('head').append('<style class="cp-backend-tooltip-hide">.customize-support .tip[class*="arrow"]:before{display:none} .tip[class*="arrow"]:before{display:none}.customize-support .tip[class*="arrow"]{display:none} .tip[class*="arrow"]{display:none}</style>');


		} else {
			e.stopPropagation();
		}

	});

	jQuery("body").on("click", ".cp-image", function(e){ parent.setFocusElement('slidein_image'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-submit", function(e){ parent.setFocusElement('button_bg_color'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-slidein-body", function(e){ parent.setFocusElement('slidein_bg_color'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-name", function(e){ parent.setFocusElement('name_text'); e.stopPropagation(); });
	jQuery("body").on("click", ".slidein-overlay-close", function(e){ parent.setFocusElement('close_slidein'); e.stopPropagation(); });

	// remove blinking cursor
	jQuery("body").on("click select", ".cp-highlight,.cp-name,.cp-email", function(e){
		jQuery(".cp-slidein-body").find(".blinking-cursor").remove();
	});

	jQuery(this).on('submit','form',function(e){
		e.preventDefault();
	});

});

jQuery(window).on( 'load', function(){
	parent.customizerLoaded();
});

// removes &nbsp; and <br> tags from html string
function cp_get_clean_string(string) {
	var cleanString = string.replace(/[<]br[^>]*[>]/gi, '').replace(/[&]nbsp[;]/gi, '').replace(/[\u200B]/g, '');
	cleanString = jQuery.trim(cleanString);
	return cleanString;
}

// Add cp-empty class
function cp_add_empty_class(element,container) {

	var cleanString 	=  cp_get_clean_string( jQuery(element).html() );

	// Slide In title
	if( cleanString.length == 0 ) {
		jQuery(container).addClass('cp-empty');
		jQuery(element).html(cleanString);
	} else {
		jQuery(container).removeClass('cp-empty');
	}
}

// removes cp-empty class from container
function cp_remove_empty_class(element) {
	if( jQuery(element).length !== 0 ) {
		jQuery(element).removeClass('cp-empty');
	}
}

function cp_image_settings(data) {
	var image_position 			= data.image_position,
		cp_text_container 		= jQuery(".cp-text-container"),
		cp_img_container		= jQuery(".cp-image-container");

	// image position left/right alignment
	if( image_position == 1 ){
		cp_text_container.removeClass('cp-right-contain');
	} else {
		cp_text_container.addClass('cp-right-contain');
	}
}

// tool tip related settings
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
		close_slidein_tooltip 	= data.close_slidein_tooltip,
		close_slidein	      	= data.close_slidein,
		close_img	      		= data.close_img,
		close_txt		  		= data.close_txt,
		overlay_close	  		= jQuery(".slidein-overlay-close"),
		cp_slidein		  		= jQuery(".cp-slidein"),
		cp_animate_container 	= jQuery(".cp-animate-container");
		close_text_color  		= data.close_text_color,
		slidein_overlay     	= jQuery(".slidein-overlay"),
		close_img_size			= data.close_img_size,
		close_si_image_src      = data.close_si_image_src,
		cp_close_image_width    = data.cp_close_image_width,
		adjacent_close_position = data.adjacent_close_position;

	var close_img_default = close_img;
	overlay_close.appendTo(cp_animate_container);
	overlay_close.addClass('cp-inside-close').removeClass('cp-adjacent-close');

	var adj_class ='';
	switch(adjacent_close_position){
		case 'top_left': adj_class = 'cp-adjacent-left';
			break;
		case 'top_right': adj_class = 'cp-adjacent-right';
			break;
		case 'bottom_left': adj_class = 'cp-adjacent-bottom-left';
			break;
		case 'bottom_right': adj_class = 'cp-adjacent-bottom-right';
			break;
	}

	overlay_close.removeClass('cp-adjacent-left cp-adjacent-right cp-adjacent-bottom-left cp-adjacent-bottom-right');
	overlay_close.addClass(adj_class);

	if( adjacent_close_position == 'top_left' ){
		jQuery(".cp-slidein-body").addClass('cp-top-img');
	}else{
		jQuery(".cp-slidein-body").removeClass('cp-top-img');
	}

	if( close_slidein_tooltip == '1' ){
		var psid = slidein_overlay.find(".cp-slidein-content").data('ps-id');

			tooltip_class = 'cp-custom-tooltip';
			tip_position = "left";
			offset_position = 30;

			jQuery('.has-tip:empty').remove();
	} else {
		jQuery('.has-tip:empty').remove();
	}

	close_tooltip = '<span class="'+tooltip_class+' cp-tooltip-icon has-tip cp-tipcontent-'+psid+'" data-classes="close-tip-content-'+psid+'"   title="'+tooltip_title+'" data-original-title ="'+tooltip_title+'" data-color="'+tooltip_title_color+'" data-bgcolor="'+tooltip_background+'" data-closeid ="cp-tipcontent-'+psid+'" data-offset="'+offset_position+'" >';
	close_tooltip_end ='</span>';

	if( typeof close_slidein != 'undefined' ) {

		if( close_slidein == "close_txt" ) {
			jQuery(".slidein-overlay-close").removeClass('cp-image-close').addClass('cp-text-close');
			overlay_close.html(close_tooltip+'<span class ="close-txt">'+close_txt+'</span>'+close_tooltip_end);
			overlay_close.css({"color":close_text_color});

		} else if( close_slidein == 'close_img' ) {

			if ( close_si_image_src == 'upload_img'  ) {
				if( close_img_default.indexOf('http') === -1 ) {

						if( close_slidein == "close_img" && close_img !== "" ) {
							jQuery(".slidein-overlay-close").removeClass('cp-text-close').addClass('cp-image-close');
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
							jQuery(".slidein-overlay-close").removeClass('cp-text-close cp-imnage-close');
							overlay_close.html('');
						}

				} else if( close_img_default.indexOf('http') != -1 ) {
					close_img_full_src = close_img.split('|');
					close_img_src = close_img_full_src[0];
					jQuery(".slidein-overlay-close").removeClass('cp-text-close').addClass('cp-image-close');
					overlay_close.html(close_tooltip+'<img class="cp-default-close" src="'+close_img_src+'" />'+close_tooltip_end);
				}
			} else if ( close_si_image_src == 'custom_url'  ) {
				var close_img_src = data.slide_in_close_img_custom_url;
				jQuery(".slidein-overlay-close").removeClass('cp-text-close').addClass('cp-image-close');
				overlay_close.html(close_tooltip+'<img class="" src="'+close_img_src+'" />'+close_tooltip_end);
			} else if (close_si_image_src == 'pre_icons' ) {
				var close_icon = data.close_icon;
				var close_icon_url = cp.module_img_dir + "/" + close_icon + '.png';
				overlay_close.html(close_tooltip+'<img src="'+close_icon_url+'" />'+close_tooltip_end);
				jQuery(".slidein-overlay-close").removeClass('cp-text-close').addClass('cp-image-close');
			}else {
				jQuery(".slidein-overlay-close").removeClass('cp-text-close cp-imnage-close');
				overlay_close.html('');
			}
		} else if( close_slidein == "do_not_close") {
			jQuery(".slidein-overlay-close").removeClass('cp-text-close cp-imnage-close');
			overlay_close.html('');
		}
	}

	overlay_close.css('background',"transparent");

	if( close_slidein == "do_not_close") {
		overlay_close.css('background',"none");
	}

	if( close_slidein != 'close_txt' )
		overlay_close.css( 'width', cp_close_image_width+'px' );
	else
		overlay_close.css( 'width', 'auto' );
}


// function to reinitialize tooltip
function cp_tooltip_reinitialize(data) {

	var close_slidein_tooltip 	 	= data.close_slidein_tooltip,
		slidein_overlay_close       = jQuery(".slidein-overlay-close"),
		cp_slidein_width			= data.cp_slidein_width,
		slidein_overlay     		= jQuery(".slidein-overlay"),
		innerclass                  = '',
		tooltip_background 			= data.tooltip_background,
		tooltip_title_color         = data.tooltip_title_color,
		psid 						= slidein_overlay.find(".cp-slidein-content").data('ps-id'),
		slidein_overlay_close     	= jQuery(".slidein-overlay-close"),
		slideinht  					= jQuery(".cp-slidein-content").outerHeight(),
		adjacent_close_position		= data.adjacent_close_position;

		var new_tip_position = '';
		var tip_position = '';
		switch( adjacent_close_position ){
		    case 'top_left': new_tip_position = 'right';
				break;
			case 'top_right': new_tip_position = 'left';
				break;
		}

		//tool tip for slide in close
		if( close_slidein_tooltip == '1' ){
	        var tooltip_classname = "cp-tipcontent-"+psid;
	        var tclass = "close-tip-content-"+psid;
	        var vw = jQuery(window).width();

			/*if( cp_slidein_width > 768 ){
	            jQuery(".has-tip").data( "position" ,new_tip_position );
	            tip_position = new_tip_position;
	        } else {
	    		jQuery(".has-tip").data( "position" ,new_tip_position );
	        	tip_position = new_tip_position;
	        }

			if(slideinht >= 500){
	           jQuery(".has-tip").data("position" ,new_tip_position);
	            tip_position = new_tip_position;
	        }

			if(slidein_overlay_close.hasClass('cp-text-close')){
				jQuery(".has-tip").data("position" ,new_tip_position);
				tip_position = new_tip_position;
			}
*/
			jQuery(".has-tip").data("position" ,new_tip_position);
			tip_position = new_tip_position;

	    	jQuery("."+tooltip_classname).frosty({
	            className: 'tip close-tip-content-'+psid
	        });

	    	jQuery(".cp-backend-tooltip-css").remove();

	    	jQuery('head').append('<style class="cp-backend-tooltip-css">.customize-support .tip.'+tclass+'{color: '+tooltip_title_color+';background-color:'+tooltip_background+';border-color:'+tooltip_background+';border-radius:7px;padding:15px 30px;font-size:13px; }</style>');

	        if( tip_position == 'left' ){
	           jQuery('head').append('<style class="cp-backend-tooltip-css">.customize-support .'+tclass+'[class*="arrow"]:before{border-left-color: '+tooltip_background+';border-width:8px;margin-top:-8px;border-top-color:transparent }</style>');
	        }else if( tip_position == 'right' ){
	            jQuery('head').append('<style class="cp-backend-tooltip-css">.customize-support .'+tclass+'[class*="arrow"]:before{border-right-color: '+tooltip_background+';border-width:8px;margin-top:0px; border-left-color:transparent}</style>');
	        }
	        else {
	            jQuery('head').append('<style class="cp-backend-tooltip-css">.customize-support .'+tclass+'[class*="arrow"]:before{border-top-color: '+tooltip_background+';border-width:8px;margin-top:0px; border-left-color:transparent}</style>');
	        }
		}
}

// slide in image related settings
function cp_image_processing(data) {

	var vw = jQuery(window).width(),
		vh = jQuery(window).height(),
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
                cp_text_container.removeClass('col-lg-8 col-md-8 col-sm-8').addClass('col-lg-12 col-md-12 col-sm-12 cp-bigtext-container');
            } else {
                cp_text_container.removeClass('col-lg-12 col-md-12 col-sm-12 cp-bigtext-container').addClass('col-lg-8 col-md-8 col-sm-8');
            }
        } else {
        	cp_text_container.removeClass('col-lg-12 col-md-12 col-sm-12 cp-bigtext-container').addClass('col-lg-8 col-md-8 col-sm-8');
        }

		//if( vw <= image_resp_width ) {
			cp_img_container.addClass('cp-hide-image');
		/*} else {
			cp_img_container.removeClass('cp-hide-image');
		}*/
	} else {
		cp_text_container.removeClass('col-lg-12 col-md-12 col-sm-12').addClass('col-lg-8 col-md-8 col-sm-8 '+image_on_left);
		cp_img_container.removeClass('cp-hide-image');
	}
}


// adds custom css
function cp_add_custom_css(data) {
	var custom_css	= data.custom_css;
	jQuery("#cp-custom-style").remove();
	jQuery("head").append('<style id="cp-custom-style">'+custom_css+'</style>');
}

// animations in customizer
function cp_apply_animations(data) {
	var disable_overlay_effect 	= data.disable_overlay_effect,
		hide_animation_width 	= data.hide_animation_width,
		overlay_effect			= data.overlay_effect,
		exit_animation			= data.exit_animation,
		cp_animate	   			= jQuery(".cp-animate-container"),
		slidein_overlay 		= jQuery(".slidein-overlay"),
        vw 						= jQuery(window).width(),
        toggle_btn 			= data.toggle_btn,
		toggle_btn_visible  = data.toggle_btn_visible;


	if(slidein_overlay.find('.cp-slidein-toggle').length > 0){
	  overlay_effect = 'slidein-smile-slideInUp';
	  disable_overlay_effect == 0;
	}

	if( disable_overlay_effect == 1 ){
		var vw = jQuery(window).width();
		if( vw <= hide_animation_width ){
			overlay_effect = exit_animation = 'slidein-overlay-none';
		}
	} else {
		cp_animate.removeClass('slidein-overlay-none');
	}

	var entry_anim = ( typeof cp_animate.attr("data-entry-animation") !== "undefined" ) ? cp_animate.attr("data-entry-animation") : '';
	var exit_anim = ( typeof cp_animate.attr("data-exit-animation") !== "undefined" ) ? cp_animate.attr("data-exit-animation") : '';

	cp_animate.attr('data-exit-animation', exit_animation );
	cp_animate.attr("data-entry-animation", overlay_effect );

	if( toggle_btn == '1' && toggle_btn_visible == '1' ) {
		// do not apply animations to info bar
	} else {

		if( !cp_animate.hasClass(exit_animation) && exit_animation !== exit_anim ){

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
}

// setup editors
function cp_ckeditors_setup(data) {

	var slidein_title 				= data.slidein_title1,
	cp_title 						= jQuery(".cp-title"),
	slidein_title_color		 		= data.slidein_title_color,
	slidein_short_desc 				= data.slidein_short_desc1,
	cp_description 					= jQuery(".cp-description"),
	slidein_confidential			= data.slidein_confidential,
	button_title					= data.button_title,
	tip_color				 		= data.tip_color,
	slidein_desc_color		  		= data.slidein_desc_color,
	cp_confidential 				= jQuery(".cp-info-container"),
	cp_submit 						= jQuery(".cp-submit"),
	slidein_content					= data.slidein_content,
	cp_desc_bottom 					= jQuery(".cp-desc-bottom"),
	slide_button_title 				= data.slide_button_title,
	cp_slide_edit_btn				= jQuery(".cp-slide-edit-btn"),
	style_id 						= data.style_id,
	varient_style_id 				= data.variant_style_id,
	cp_slidein_content 				= jQuery(".cp-slidein-popup-container");

	if( varient_style_id !==''  && typeof varient_style_id !== 'undefined' ){
		style_id = varient_style_id;
	}

	//add style id as class to container
	cp_slidein_content.addClass( style_id );

	// slide in title editor
	slidein_title = htmlEntities(slidein_title);
	cp_title.html(slidein_title);
	if(jQuery("#main_title_editor").length !== 0) {
		CKEDITOR.instances.main_title_editor.setData(slidein_title);
	}
	cp_title.css('color',slidein_title_color);

	// secondary title editor
	if( jQuery("#sec_title_editor").length !== 0 ) {
		sec_title = data.slidein_sec_title;
		slidein_sec_title_color = data.slidein_sec_title_color;
		CKEDITOR.instances.sec_title_editor.setData(sec_title);
		slidein_sec_title = htmlEntities(sec_title);
		jQuery(".cp-sec-title").html(slidein_sec_title);
		jQuery(".cp-sec-title").css('color',slidein_sec_title_color);
	}

	// short description editor
	slidein_short_desc = htmlEntities(slidein_short_desc);
	cp_description.html(slidein_short_desc);
	if(jQuery("#desc_editor").length !== 0) {
		if( slidein_short_desc !== "" && typeof slidein_short_desc !== "undefined" ){
			CKEDITOR.instances.desc_editor.setData(slidein_short_desc);
		}
	}
	cp_description.css('color',slidein_desc_color);

	// confidential info editor
	slidein_confidential = htmlEntities(slidein_confidential);
	cp_confidential.html(slidein_confidential);
	if(jQuery("#info_editor").length !== 0) {
		if( slidein_confidential !== "" && typeof slidein_confidential !== "undefined" && jQuery("#info_editor").length !== 0 ){
			CKEDITOR.instances.info_editor.setData(slidein_confidential);
		}
	}

	jQuery(".cp-info-container").css('color',tip_color);

	//description bottom
	slidein_content = htmlEntities(slidein_content);
	cp_desc_bottom.html(slidein_content);
	if(jQuery("#description_bottom").length !== 0) {
		CKEDITOR.instances.description_bottom.setData(slidein_content);
	}

	//slide in  button editor
	slide_button_title = htmlEntities(slide_button_title);
	cp_slide_edit_btn.html(slide_button_title);
}


//decode html char
function escapeHtml(text) {
    var decoded = jQuery('<div/>').html(text).text();
    return decoded;
}

//trigger after ajax sucess
jQuery(document).on("cp_ajax_loaded", function(e,data){
	// do your stuff here.
	cp_tooltip_reinitialize(data);
});


// This function set slidein width
function cp_slidein_width_settings(data) {

	var cp_slidein        = jQuery(".cp-slidein"),
		cp_slidein_width	= data.cp_slidein_width,
		cp_slidein_body	= jQuery(".cp-slidein-body");

	cp_slidein.css({'max-width':cp_slidein_width+'px'});
	cp_slidein_body.css( 'max-width', '' );
	jQuery(".cp_fs_overlay").css({"display":"none"});
	jQuery(".cp_cs_overlay").css({"display":"block"});
}


/**
 * Adds blinking cursor
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

			jQuery(container).append('<i style="background-color:'+bgcolor+';font-size: '+font_size+'px !important;" class="blinking-cursor">|</i>');
		}
	}, 500);
}


/**
 * Trigger ColorPicker Change
 */
parent.jQuery(window.parent.document).on('smile-colorpicker-change', function( e, el, val) {

	if(jQuery(el).hasClass('slidein_bg_color')){
		smile_global_data.slidein_bg_color = val;
		apply_gradient_color( smile_global_data );
	}

	//close image settings
	if( jQuery(el).hasClass('close_text_color') ) {
		smile_global_data.close_text_color = val;
		cp_info_bar_close_img_settings(smile_global_data);
	}

	//close image settings
	if( jQuery(el).hasClass('slide_button_bg_color') ) {
		smile_global_data.slide_button_bg_color = val;
		slide_button_setting(smile_global_data);
	}

});

function slide_button_setting(data){
	//	Append all CSS
	jQuery('head').append('<div id="cp-slide-button-inline-css"></div>');

	var slide_in_style					= '',
		side_btn_style 					= data.side_btn_style,
	    slidein_btn_position 			= data.slidein_btn_position,
	    slidein_position 				= data.slidein_position,
	    slide_button_title 				= data.slide_button_title,
	    slide_button_bg_color 			= data.slide_button_bg_color,
	    side_button_txt_hover_color   	= data.side_button_txt_hover_color,
	    side_button_bg_hover_color	  	= data.side_button_bg_hover_color,
	    side_button_bg_gradient_color 	= data.side_button_bg_gradient_color,
	    side_btn_border_radius 			= data.side_btn_border_radius,
	    side_btn_shadow 				= data.side_btn_shadow,
	    close_slidein 					= data.close_slidein,
	    button_animation				= data.button_animation,
	    hide_button_class				= '',
	    cp_animate_container 			= jQuery(".cp-animate-container"),
	    slidein_overlay 				= jQuery(".slidein-overlay"),
	    cp_slide_edit_btn				= jQuery(".cp-slide-edit-btn"),
	    cp_toggle_container 			= jQuery(".cp-toggle-container"),
	    slide_button_text_color 		= data.slide_button_text_color,
	    slide_btn_gradient 				= data.slide_btn_gradient,
	    toggle_btn						= data.toggle_btn,
	    toggle_btn_visible  			= data.toggle_btn_visible;

  	//	Disable the toggle button
  	if( close_slidein === 'do_not_close' ) {
		toggle_btn = 0;
  	}
  	if( toggle_btn == 1 ){
		slidein_overlay.removeClass('cp-slide-without-toggle');
	} else {
	    slidein_overlay.addClass('cp-slide-without-toggle');
	    slidein_overlay.removeClass('cp-slidein-click');
	}

	if( !cp_animate_container.hasClass('cp-hide-slide') ) {
		hide_button_class ='cp-slide-hide-btn';
	}

	if( slidein_position == 'center-right' ){
	   button_animation = 'smile-slideInUp';
	}

	if( slidein_position == 'center-left' ){
		button_animation = 'smile-slideInDown';
	}

	if( slidein_position == 'top-left' || slidein_position == 'top-center' || slidein_position == 'top-right' ) {
		button_animation = 'smile-slideInDown';
	}

	if( slidein_position == 'bottom-left' || slidein_position == 'bottom-center' || slidein_position == 'bottom-right' ) {
		button_animation = 'smile-slideInUp';
	}

	jQuery('#smile_button_animation', window.parent.document).val( button_animation );

	// button animation
	cp_slide_edit_btn.removeAttr('class');
	cp_slide_edit_btn.attr('class','cp-slide-edit-btn smile-animated '+button_animation +' ');
	cp_toggle_container.addClass(hide_button_class);

	//slide_toggle_button( smile_global_data );

	if( slide_btn_gradient == 1 ) {
    	side_btn_style = 'cp-btn-gradient';
    } else {
    	side_btn_style = 'cp-btn-flat';
    }

    //set button style
	jQuery('#smile_side_btn_style', window.parent.document).val( side_btn_style );

	//	button style
	var slideclassList = ['cp-btn-flat', 'cp-btn-3d', 'cp-btn-outline', 'cp-btn-gradient'];
	jQuery.each(slideclassList, function(i, v){
       cp_slide_edit_btn.removeClass(v);
    });


    cp_slide_edit_btn.addClass(side_btn_style);

	change_slidein_btn_position( data );

	if( typeof slide_button_bg_color !== 'undefined' ) {
		var c_normal 	= slide_button_bg_color;
		var c_hover  	= darkerColor( slide_button_bg_color, .05 );
		var light 		= lighterColor( slide_button_bg_color, .3 );

		cp_slide_edit_btn.css('background', c_normal);
	}

	//	Apply box shadow to submit button - If its set & equals to - 1
	var shadow = radius = '';
	if( side_btn_shadow == 1 ) {
		shadow += 'box-shadow: 1px 1px 2px 0px rgba(66, 66, 66, 0.6);';
	}
	//	Add - border-radius
	if( side_btn_border_radius != '' ) {
		radius += 'border-radius: ' + side_btn_border_radius + 'px;';
	}

	jQuery("#cp-slide-button-inline-css").remove();

	jQuery('head').append('<div id="cp-slide-button-inline-css"></div>');

	switch( side_btn_style ) {
		case 'cp-btn-flat': 		jQuery('#cp-slide-button-inline-css').html('<style>'
										+ '.slidein-overlay .cp-slide-edit-btn.' + side_btn_style + '{ background: '+c_normal+'!important;' + shadow + radius  +'; } '
										+ '.cp-slidein .cp-slide-edit-btn.' + side_btn_style + ':hover { background: '+c_hover+'!important; } '
										+ '</style>');
			break;

		case 'cp-btn-gradient': 	//	Apply box shadow to submit button - If its set & equals to - 1
									jQuery('#cp-slide-button-inline-css').html('<style>'
										+ '.slidein-overlay .cp-slide-edit-btn.' + side_btn_style + ' {'
										+ '     border: none ;'
										+ 		shadow + radius
										+ '     background: -webkit-linear-gradient(' + light + ', ' + c_normal + ') !important;'
										+ '     background: -o-linear-gradient(' + light + ', ' + c_normal + ') !important;'
										+ '     background: -moz-linear-gradient(' + light + ', ' + c_normal + ') !important;'
										+ '     background: linear-gradient(' + light + ', ' + c_normal + ') !important;'
										+ '}'
										+ '.slidein-overlay .cp-slide-edit-btn.' + side_btn_style + ':hover {'
										+ '     background: ' + c_normal + ' !important;'
										+ '}'
										+ '</style>');
			break;
	}

	//	Set either 10% darken color for 'HOVER'
	//	Or 0.10% darken color for 'GRADIENT'
	jQuery('#smile_side_button_bg_hover_color', window.parent.document).val( c_hover );
	jQuery('#smile_side_button_bg_gradient_color', window.parent.document).val( light );

}


function change_slidein_btn_position( data ) {

	var cp_toggle_container = jQuery(".cp-toggle-container"),
		slidein_position    = data.slidein_position;
	// button position
	var positionclassList = ['slidein-top-left','slidein-top-center','slidein-top-right','slidein-bottom-left','slidein-bottom-center','slidein-bottom-right','slidein-center-left','slidein-center-right'];
	jQuery.each(positionclassList, function(i, v){
       cp_toggle_container.removeClass(v);
    });
	cp_toggle_container.addClass( 'slidein-'+slidein_position );
}

// Add class to body for Slide In position
function cp_add_class_for_body(bodyclass) {
	jQuery("body").removeClass('cp-slidein-top-center cp-slidein-bottom-center cp-slidein-center-left cp-slidein-center-right cp-slidein-top-left cp-slidein-bottom-right cp-slidein-bottom-left cp-slidein-top-right');
	jQuery('body').addClass('cp-slidein-'+bodyclass);
}

/**
 * trigger after Ajax success
 */
jQuery(document).on("cp_ajax_loaded", function(e,data){
	// do your stuff here.
	cp_tooltip_reinitialize(data);
});

/**
 * trigger smile_data_received
 */
jQuery(document).on('smile_customizer_field_change',function(e, single_data){

	//	Update box shadow
	var shadow = single_data.shadow_type || null;
	if( "shadow_type" in single_data ) {
		var 	v_horizontal 	= jQuery('#horizontal-length', window.parent.document ).val() || '',
			v_vertical 	= jQuery('#vertical-length', window.parent.document ).val() || '',
			v_blur 		= jQuery('#blur-radius', window.parent.document ).val() || '',
			v_spread 		= jQuery('#spread-field', window.parent.document ).val() || '',
			v_shadowColor 	= jQuery('#shadow-color', window.parent.document ).val() || '';

		var new_box_shadow  = 'type:'+shadow+'|';
			new_box_shadow += 'horizontal:'+v_horizontal+'|';
			new_box_shadow += 'vertical:'+v_vertical+'|';
			new_box_shadow += 'blur:'+v_blur+'|';
			new_box_shadow += 'spread:'+v_spread+'|';
			new_box_shadow += 'color:'+v_shadowColor;

		//	Update box shadow
		smile_global_data.box_shadow = new_box_shadow;
		apply_shadow( smile_global_data );
	}

	//	toggle - modal padding
	var content_padding = single_data.content_padding || null;
	if("content_padding" in single_data) {
		single__toggle_class('.cp-slidein-body', 'cp-no-padding', content_padding, 1);
	}

	var minimize_widget = single_data.minimize_widget || null;
	if("minimize_widget" in single_data) {
		single__toggle_class('.slidein-overlay', 'minimize-widget', minimize_widget, 1);
		single__toggle_class('.cp-slidein-toggle', 'cp-widget-open', minimize_widget, 0);
		var this_cls = jQuery(".cp-slidein-head .cp-slidein-toggle");
   	    toggle_widget(this_cls , 600);
	}

	if( "slidein_position" in single_data) {
		jQuery(".cp-slidein").removeClass('slidein-top-center slidein-bottom-center slidein-center-left slidein-center-right slidein-top-left slidein-bottom-right slidein-bottom-left slidein-top-right').addClass('slidein-'+ single_data.slidein_position);
		change_slidein_btn_position( smile_global_data );
	}

	// gradient background
	if( 'slidein_bg_gradient' in single_data ) {
		apply_gradient_color( smile_global_data );
	}

	/**
	 * Slide in Background Image
	 */
	//	AJAX update image size - full / thumbnail / medium etc.
	if( 'slide_in_bg_image_size' in single_data ) {
		cp_update_ajax_bg_image_size( smile_global_data, '.cp-slidein-body', "", "slide_in_bg_image", "opt_bg" );
	}

	if( 'slide_in_bg_image_src' in single_data || 'slide_in_bg_image_custom_url' in single_data ) {
		// slide in background image
		cp_update_bg_image( smile_global_data, ".cp-slidein-body", "", 'slide_in_bg_image', 'slide_in_bg_image_src' );
	}

	//	Background Image - 	repeat
	var opt_bg_rpt = single_data.opt_bg_rpt || null;
	if( 'opt_bg_rpt' in single_data ) {
		add_css( '.cp-slidein-body', "background-repeat", opt_bg_rpt );
	}

	//	Background Image - 	position
	var opt_bg_pos = single_data.opt_bg_pos || null;
	if( 'opt_bg_pos' in single_data ) {
		add_css( '.cp-slidein-body', "background-position", opt_bg_pos );
	}

	//	Background Image - 	size
	var opt_bg_size = single_data.opt_bg_size || null;
	if( 'opt_bg_size' in single_data ) {
		add_css( '.cp-slidein-body', "background-size", opt_bg_size );
	}

	//enable border
	if( 'slidein_bg_gradient' in single_data ){
		//gradiant_background(smile_global_data, ".cp-slidein-body-overlay", "slidein_bg_color", "slidein_bg_gradient" );
	}

	toggle_button_text( smile_global_data );
	if( "side_button_bg_color" in single_data || "side_button_bg_color" in single_data || "slide_btn_gradient" in single_data ) {
		slide_button_setting( smile_global_data );
	}

	if( 'toggle_btn' in single_data ) {
		slide_toggle_button( smile_global_data );
	}

	//	Animations
	if( 'overlay_effect' in single_data || 'exit_animation' in single_data ) {
		cp_apply_animations( smile_global_data );
	}

	if( 'slide_button_title' in single_data ) {
		toggle_button_text( smile_global_data );
	}

	//	toggle - swap image & contents
	var image_position = single_data.image_position || null;
	if( "image_position" in single_data ) {
		single__toggle_class('.cp-text-container', 'cp-right-contain', image_position, 0);
	}

	var image_displayon_mobile = single_data.image_displayon_mobile || null;
	if( "image_displayon_mobile" in single_data ) {
		cp_image_processing(smile_global_data);
	}

	/**
	 * slidein Image
	 */
	//	AJAX update image URL
	if( 'slidein_image_size' in single_data || "slidein_img_src" in single_data || "slidein_img_custom_url" in single_data ) {
		cp_update_ajax_slidein_image_src( smile_global_data );
	}

});

jQuery(document).on('smile_data_received',function(e,data){
	global_initial_call( data );
	//hide_sidebar();
});

jQuery(document).on('smile_data_continue_received', function(e,data) {
	cp_tooltip_settings( data );
	cp_tooltip_reinitialize( data );
	cp_add_custom_css( data );
});


function global_initial_call( data ) {


	var style = data.style || null;
	switch( style ) {

		case 'free_widget':
			// Modal image
			cp_image_processing(data);
			single__toggle_class( '.cp-text-container', 'cp-right-contain', data.image_position, 0 );	//	Toggle - Image position Left or Right
			break;
	}

	var cp_slidein	= jQuery(".cp-slidein");

	var slidein_position = data.slidein_position;

	// Add Slide In position class to body
	cp_add_class_for_body(slidein_position);

	// add custom css
	cp_add_custom_css(data);

	cp_apply_animations(data);

	cp_tooltip_settings(data); // close button and tool tip related settings
	cp_tooltip_reinitialize(data); // reinitialize tool tip on slide in resize

	//set image
	cp_set_image( data, 'slidein' );


	// Shadow
	if( typeof smile_global_data.box_shadow !=='undefined' ){
		apply_shadow(data);
	}

	// border
	if( typeof smile_global_data.border !=='undefined' ){
		border_settings(data);
	}

	// set Slide In width
	cp_slidein_width_settings(data);

	cp_slidein.removeClass('slidein-top-center slidein-bottom-center slidein-center-left slidein-center-right slidein-top-left slidein-bottom-right slidein-bottom-left slidein-top-right').addClass('slidein-'+slidein_position);

	// slide in background image
	cp_update_bg_image(data, ".cp-slidein-body", "", 'slide_in_bg_image', 'slide_in_bg_image_src' );

	if( typeof data.slidein_bg_color !='undefined' ){
		apply_gradient_color( data );
	}

	// Slide In button settings
	if( typeof data.slide_button_title !== 'undefined' ) {
		toggle_button_text( data );
		slide_button_setting(data);
	}

	jQuery(".cp-name-form").removeClass('cp_big_name');

	//	'cp_empty_classes' is a classes array defined in another file
	//	Add Cp-empty Class To Empty Containers
	jQuery.each( cp_empty_classes, function( key, value) {
		if( jQuery(value).length !== 0 ) {
			cp_add_empty_class(key,value);
		}
	});

	//	toggle - modal padding
	if( style =='floating_social_bar' ){
		smile_global_data.content_padding = 1;
	}
	var content_padding = data.content_padding || null;
	single__toggle_class('.cp-slidein-body', 'cp-no-padding', content_padding, 1);
}

function apply_shadow( data ) {

	// style dependent variables
	var box_shadow_str 		= data.box_shadow;

	jQuery("#cp-box-shadow-style").remove();

	if( box_shadow_str.indexOf("inset") > -1 ) {

		// UPDATE - [data-css-selector] to set the box shadow target
		jQuery('.smile-input[name="box_shadow"]', window.parent.document ).attr('data-css-selector', '.cp-slidein-body-overlay' );

		generate_and_apply_box_shadow_css( '.cp-slidein-body-overlay', box_shadow_str );

	} else {

		generate_and_apply_box_shadow_css( '.cp-slidein-content', box_shadow_str );

		// UPDATE - [data-css-selector] to set the box shadow target
		jQuery('.smile-input[name="box_shadow"]', window.parent.document ).attr('data-css-selector', '.cp-slidein-content' );
	}

}


/**
 *Enable Border
 */
 function border_settings(data) {

 	var style 				= data.style,
		border_str 			= data.border,
		cp_slidein_content	= jQuery(".cp-slidein-content");
		smile_global_data   = get_customizer_form_data();

	var border = generateBorderCss(border_str);

	jQuery("#cp-border-style").remove();

	var border_style = '.cp-slidein-content {'
			+ border +  '}';

	jQuery("head").append('<style id="cp-border-style">'+border_style+'</style>');
 }


/**
 * Change - Modal Image
 */

parent.jQuery(window.parent.document).on('cp-image-change', function( e, name, url, val) {
	//console.log("change");
	//	Modal - Background Image
	// Process for modal background image - for variable 'modal_bg_image'
	if( name == 'slide_in_bg_image' && name != 'undefined' && name != null ) {
		cp_change_bg_img( smile_global_data, '.cp-slidein-body' , '' , name, 'opt_bg', url, val );
	}

	//	Slidein - Image
	// Process for modal image - for variable 'slidein_image'
	if( name == 'slidein_image' && name != 'undefined' && name != null ) {

		var slidein_image_size	= smile_global_data.slidein_image_size,
			slidein_image		    = smile_global_data.slidein_image,
			slidein_img			= jQuery('.cp-image-container img');

		smile_global_data.slidein_image = val;
		//	Changed images is always big.
		//	So, If image size is != full then call the image though AJAX
		if( slidein_image_size != "full" ) {
			//	Update image - ID|SIZE
			cp_update_ajax_slidein_image_src( smile_global_data );
		} else {
			slidein_img.attr( "src", url);
		}
	}

	 cp_slide_in_column_equilize();

});


/**
 * Update Image URL by AJAX
 */
function cp_update_ajax_slidein_image_src( smile_global_data ) {

	var slidein_image_size = smile_global_data.slidein_image_size,
		slidein_image 	   = smile_global_data.slidein_image,
		slidein_img_src    = smile_global_data.slidein_img_src;
		slidein_img 	   = jQuery('.cp-image-container img');

	switch( slidein_img_src ) {

		case "upload_img":

				// 	file not exists
			if( typeof slidein_image !== 'undefined' && slidein_image.indexOf('http') === -1 && slidein_image !== '' ) {

				var image_details = slidein_image.split("|"),
                    img_id = image_details[0],
                    img_size = slidein_image_size;

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
						slidein_img.attr( "src", img_url);
					}
				});
			} else if( typeof slidein_image !== 'undefined' && slidein_image.indexOf('http') != -1 ) {
				if( slidein_image.indexOf('|') ) {
					var url = slidein_image.split('|');
					slidein_img.attr( "src", url[0]);
				} else {
                	slidein_img.attr( "src", slidein_image );
                }
			} else {
				var img_src = jQuery('.smile-input[name="slidein_image"]', window.parent.document ).attr('data-css-image-url');
				slidein_img.attr( "src", img_src );
			}
		break;

		case "custom_url":
			var custom_url = smile_global_data.slidein_img_custom_url;
			slidein_img.attr( "src", custom_url );
		break;

		case "none":
			slidein_img.attr( "src", "" );
		break;
	}

}

parent.jQuery(window.parent.document).on('cp-image-default', function( e, name, url, val) {
	//	Slidein - Image
	// Process for modal image - for variable 'slidein_image'
	if( name == 'slidein_image' && name != 'undefined' && name != null ) {

		var slidein_image_size	= smile_global_data.slidein_image_size,
			slidein_image		    = smile_global_data.slidein_image,
			slidein_img			= jQuery('.cp-image-container img');

		smile_global_data.slidein_image = url;
		//	Changed images is always big.
		//	So, If image size is != full then call the image though AJAX
		if( slidein_image_size != "full" ) {
			//	Update image - ID|SIZE
			cp_update_ajax_slidein_image_src( smile_global_data );
		} else {
			slidein_img.attr( "src", url);
		}
	}

});


//	Set Background Image - Position, Repeat & Size
function image_positions( data ) {
	var cp_slidein_body	 	= jQuery(".cp-slidein-body"),
		slidein_size 		= data.slidein_size,
		opt_bg			 	= data.opt_bg,
		opt_bg 			 	= opt_bg.split("|"),
		bg_repeat 		 	= opt_bg[0],
		bg_pos 			 	= opt_bg[1],
		bg_size 		 	= opt_bg[2];

	cp_slidein_body.css({ "background-position" :  bg_pos, "background-repeat" : bg_repeat, "background-size" : bg_size });

}

function add_css( selector, property, value ) {
	jQuery(selector).css(property,value);
}


function single__toggle_class(selector, toggle_class, value, required){
	if( typeof value !== "undefined" && value == "" || value == required ){
		jQuery( selector ).addClass( toggle_class );
	} else {
		jQuery( selector ).removeClass( toggle_class );
	}
}

function apply_gradient_color( data ) {

	var bg_color 			= data.slidein_bg_color,
		slidein_bg_gradient = data.slidein_bg_gradient,
		slidelightbg 		= lighterColor( bg_color, .3 ),
		bg_color			= data.slidein_bg_color,
		slide_bg_style 		= '';

	if( typeof slidein_bg_gradient != 'undefined' && slidein_bg_gradient == '1' ) {

		//	store it!
		jQuery('#smile_slidein_bg_gradient_lighten', window.parent.document ).val( slidelightbg );

		slide_bg_style +=  '.cp-slidein-body-overlay {'
					+ '     background: -webkit-linear-gradient(' + slidelightbg + ', ' + bg_color + ');'
					+ '     background: -o-linear-gradient(' + slidelightbg + ', ' + bg_color + ');'
					+ '     background: -moz-linear-gradient(' + slidelightbg + ', ' + bg_color + ');'
					+ '     background: linear-gradient(' + slidelightbg + ', ' + bg_color + ');'
					+ '}';
	} else {
		slide_bg_style +=  '.cp-slidein-body-overlay {'
					+ '     background: ' + bg_color
					+ '}';
	}

	jQuery("#cp-slidein-bg-css").remove();

	jQuery('<style id="cp-slidein-bg-css">' + slide_bg_style + '</style>').insertAfter( "#cp-preview-css" );
}


/**
 * Remove - Slide in Background image
 *
 * Also, Replaced [data-css-image-url] with empty. [Which is used to updated image URL without AJAX.]
 */
parent.jQuery(window.parent.document).on('cp-image-remove', function( e, name, url) {

	jQuery(".cp-slidein-body").css({ 	"background-image" 	: "" });

	//	REMOVE - [data-css-image-url] to get updated image for FULLWIDTH
	jQuery('.smile-input[name='+name+']', window.parent.document ).attr('data-css-image-url', '' );

});


function slide_toggle_button( data ) {

	var cp_animate_container 			= jQuery(".cp-animate-container"),
	    cp_toggle_container 			= jQuery(".cp-toggle-container"),
	    toggle_btn						= data.toggle_btn,
	    toggle_btn_visible  			= data.toggle_btn_visible;

	if( toggle_btn == 1 ){
		slidein_overlay.removeClass('cp-slide-without-toggle');
	} else {
	    slidein_overlay.addClass('cp-slide-without-toggle');
	    slidein_overlay.removeClass('cp-slidein-click');
	}

	if( toggle_btn == 1 && toggle_btn_visible == 1 ) {

		if( !jQuery(".slidein-overlay").hasClass('cp-slide-without-toggle') ) {

			var cp_toggle_container    = jQuery(".cp-toggle-container"),
				exitanimation 		   = jQuery(".cp-animate-container").data("exit-animation"),
				cp_animate_container   = jQuery(".cp-animate-container");

			cp_animate_container.attr('class', 'cp-animate-container');
	        cp_animate_container.attr('class' , 'cp-animate-container smile-animated '+exitanimation);
	        cp_animate_container.addClass("cp-hide-slide");
			cp_toggle_container.removeClass("cp-slide-hide-btn");
			cp_animate_container.removeClass('exitanimation');

		} else {
			e.stopPropagation();
		}
	} else {

		if( !jQuery(".cp-toggle-container").hasClass('cp-slide-hide-btn') ) {
			jQuery(".cp-toggle-container").addClass("cp-slide-hide-btn");
			parent.setFocusElement('slide_button_title');
			var	cp_animate_container = jQuery(".cp-animate-container"),
				entryanimation       = cp_animate_container.data("entry-animation");
			cp_animate_container.attr('class', 'cp-animate-container cp-hide-slide smile-animated');

			setTimeout(function() {
			 cp_animate_container.attr('class' , 'cp-animate-container smile-animated '+entryanimation);
			}, 10);
		}
	}
}


/**
 * toggle_button_text
 */
function toggle_button_text(data){

	var slide_button_title 	= data.slide_button_title,
		cp_ifb_toggle_btn 	= jQuery(".cp-slide-edit-btn");

   	 cp_ifb_toggle_btn.html(slide_button_title);
}

