/**
 * ConvertPlug
 *
 * Triggers & Functions
 *
 * 1. 	Trigger - smile_data_received
 * 2. 	Trigger - smile_data_on_load
 * 3. 	Trigger - smile_customizer_field_change
 */

/**
 * Global All FORM DATA
 */
var smile_global_data = '';

/**
 * Add this div for preview CSS
 */
jQuery('body').append('<div id="cp-preview-css"></div>');
jQuery('body').append('<div id="cp-form-css"></div>');

/**
 * 1. 	Trigger - smile_data_received
 *
 * Triggered after any customizer input change.
 */
window.onload = function() {
	function receiveMessage(e){

		var origin = e.origin;

        // If request is from our domain then only process data
		if(origin.indexOf(window.location.host) >= 0)
		{
			var test_data = '',
				e_data = '';
			var e_data = e.data.replace(/\"/g,'');
			var pairs = e_data.split('&');
			var result = {};
			pairs.forEach(function(pair) {
				pair = pair.split('=');
				if(result[pair[0]]){
					result[pair[0]] = result[pair[0]]+","+decodeURIComponent(pair[1]);
				} else {
					result[pair[0]] = decodeURIComponent(pair[1]);
				}
			});
			smile_global_data = result;

			jQuery(document).trigger('smile_data_continue_received',[smile_global_data]);
		}
	}
	window.addEventListener('message',receiveMessage);
}

/**
 * 2. 	Trigger - smile_data_on_load
 *
 * It works only once when page is loaded.
 */
jQuery(window).on( 'load', function() {
	smile_global_data = get_customizer_form_data();

	smile_global_data.modal_size = ( typeof smile_global_data.modal_size == 'undefined' ? 'cp-modal-custom-size' : smile_global_data.modal_size );

    /**
	 *	1.	Add Selected Google Fonts
	 */
	var cp_google_fonts = smile_global_data.cp_google_fonts || '';

	if( '' != cp_google_fonts && 'undefined' != cp_google_fonts ) {
		cp_get_gfonts(cp_google_fonts);
	}

	cp_ckeditors_setup( smile_global_data );

	/**
	 * 	3.	Individual style CSS
	 *
	 * 	Add CSS file of this style
	 */
	var style = smile_global_data.style || null;
	var modules = smile_global_data.option;
	var module_name = cp.demo_dir;

	switch(modules){
		case 'smile_info_bar_styles'  :
		case 'info_bar_variant_tests' :
					jQuery('html').addClass('cp-customizer-info_bar');
			break;
		case 'smile_modal_styles' :
		case 'modal_variant_tests':
					jQuery('html').addClass('cp-customizer-modal');
			break;
		case 'smile_slide_in_styles':
		case 'slide_in_variant_tests':
					jQuery('html').addClass('cp-customizer-slide_in');
			break;
	}

	if( cp_isValid( style ) ) {
		var css_file = '/' + style.toLowerCase() + '/' + style.toLowerCase() + '.min.css';

		jQuery('head').append('<link rel="stylesheet" href="' + module_name + css_file + '" type="text/css" />');
	}

	/**
	 * 	4.	Blinking cursor
	 *
	 * @param string style Module Style Name
	 */
	var modal_title_color = smile_global_data.modal_title_color || null;
	if( cp_isValid( modal_title_color ) ) {
		switch( style ) {
			case 'blank':
			case 'social_media':
										cp_blinking_cursor('#short_desc_editor', modal_title_color );
				break;
			case 'countdown':
			case 'direct_download':
			case 'every_design':
			case 'first_order':
			case 'first_order_2':
			case 'flat_discount':
			case 'free_ebook':
			case 'instant_coupon':
			case 'locked_content':
			case 'optin_to_win':
			case 'special_offer':
			case 'webinar':
										cp_blinking_cursor('.cp-title', modal_title_color );
				break;
		}
	}

	jQuery(document).trigger('smile_data_on_load', [smile_global_data] );
	jQuery(document).trigger('smile_data_received', [smile_global_data] );

});

/**
 *  Initially APPLY CSS
 *
 *	1. Apply INLINE
 *	2. Apply after CSS Generation
 */
function get_customizer_form_data() {
	var data = jQuery('.cp-cust-form', window.parent.document).serialize();
	var test_data = '',
		e_data = '';
	var e_data = data.replace(/\"/g,'');
	var e_data = e_data.replace(/\+/g,' ');
	var pairs = e_data.split('&');
	var result = {};
	pairs.forEach(function(pair) {
		pair = pair.split('=');
		if(result[pair[0]]){
			result[pair[0]] = result[pair[0]]+","+decodeURIComponent(pair[1]);
		} else {
			result[pair[0]] = decodeURIComponent(pair[1]);
		}
	});
	return result;
}
function get_customizer_form_single_data() {
	var data = jQuery('.cp-cust-form', window.parent.document).serialize();
	var test_data = '',
		e_data = '';
	var e_data = data.replace(/\"/g,'');
	var e_data = e_data.replace(/\+/g,' ');
	var pairs = e_data.split('&');
	var result = {};
	pairs.forEach(function(pair) {
		pair = pair.split('=');
		if(result[pair[0]]){
			result[pair[0]] = result[pair[0]]+","+decodeURIComponent(pair[1]);
		} else {
			result[pair[0]] = decodeURIComponent(pair[1]);
		}
	});
	return result;
}

/**
 * 3. 	Trigger - smile_customizer_field_change
 *
 * Trigger on form '.cp-cust-form' for customizer fields events:
 *
 * 	Input 			-	Change
 * 	Select 			- 	Change
 * 	MultiField 		-	Drag Drop
 * 	Slider			- 	Slide
 * 	ColorPicker		- 	Drag Drop
 */
jQuery(window).on( 'load', function() {
	jQuery('.cp-cust-form .smile-input', window.parent.document ).change(function( event ) {
		var self = jQuery( this );

		//	FIELD - SWITCH
		if( self.hasClass('smile-switch') ) {
			elm_id = self.siblings('input[type="text"]').attr('id');
			self = self.siblings('input[type="text"]');
		}

		var elm_data = self.val();
		var elm_id = self.attr('id');
		elm_id = elm_id.split("smile_").pop();

		var single_data = {};
		single_data[ elm_id ] = decodeURIComponent( elm_data );

		//	Update single instance from global variable 'smile_global_data'
		smile_global_data[ elm_id ] = decodeURIComponent( elm_data );

		jQuery(document).trigger('smile_customizer_field_change', [single_data] );

		//	Toggle Form - Show either CP (Default) Form or Custom form via ShortCode.
		if( elm_id === 'mailer' ) {
			dual__toggle_cp_form(smile_global_data);
		}

		input_shadow_change(smile_global_data);
		//field_submit_attached(smile_global_data);

	});
});

/** TRIGGER - SINGLE */
jQuery(document).on('smile_data_received',function(e,data){
    dual__toggle_cp_form(data);
    input_shadow_change(smile_global_data);
});

/** TRIGGER - CONTINUE */
jQuery(document).on('smile_data_continue_received',function(e,data){
	var custom_html_form = data.custom_html_form || '';

	if( '' != custom_html_form ) {
		single__live_custom_form_data( custom_html_form );
	}
});

//	Live Custom Form Data
function single__live_custom_form_data( custom_html_form ) {
	jQuery(".custom-html-form").html( custom_html_form );
}

//	Toggle Form - Show either CP (Default) Form or Custom form via ShortCode.
function dual__toggle_cp_form( data ) {
    var mailer       	= data.mailer,
        default_form 	= jQuery(".default-form"),
        custom_form  	= jQuery(".custom-html-form");
    if( mailer == "custom-form" ) {
        /* For InfoBar we use the display: flex !important */
        default_form.attr('style','display: none !important');
        custom_form.show();
    } else {
        default_form.attr('style','display: block');
        custom_form.css('display','none');
    }
}

function input_shadow_change( data ){
	if(data.input_shadow !='' && data.input_shadow == 1 ){
	 jQuery(".default-form").addClass('enable_input_shadow');
	}else{
	 jQuery(".default-form").removeClass('enable_input_shadow');
	}
}

function field_submit_attached( data ){
	if(data.btn_attached_email !='' && data.btn_attached_email == 1 ){
	 jQuery(".cp-submit-wrap").addClass('enable-field-attached');
	 jQuery('.cp-form-field:last-child').addClass('enable-field-attached');
	}else{
	 jQuery(".cp-submit-wrap").removeClass('enable-field-attached');
	 jQuery('.cp-form-field:last-child').removeClass('enable-field-attached');
	}
}



