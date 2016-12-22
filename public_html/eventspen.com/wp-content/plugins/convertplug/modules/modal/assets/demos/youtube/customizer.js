
jQuery(window).on( 'load',function(){
	parent.customizerLoaded();
});

//	Generate and return the YouTube URL
function generateURL( video_id, video_start, player_actions, player_controls, player_autoplay ) {
	var video_url = 'https://www.youtube.com/embed/'+video_id+'?rel=0&fs=0';
	if( player_controls == '1' || player_controls == 1 ){
		video_url += '&controls=1';
	} else {
		video_url += '&controls=0';
	}
	if( player_actions == '1' || player_actions == 1 ){
		video_url += '&showinfo=1';
	} else {
		video_url += '&showinfo=0';
	}
	if( video_start ){
		video_url += '&start='+video_start;
	} else {
		video_url += '&start=0';
	}
	return video_url;
}

//	Added padding for submit button
//	Only for customizer preview
//	Removed submit button padding on front end if CTA type is button.
jQuery('head').append('<style>.cp-modal .cp-youtube .cp-youtube-cta-button .cp-submit { padding: 10px 20px; }</style>');

jQuery(document).ready(function($) {
	jQuery("html").css('overflow','hidden');

	jQuery("body").on("click", ".cp-form-container", function(e) { parent.setFocusElement('cta_bg_color'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-overlay", function(e) { parent.setFocusElement('modal_overlay_bg_color'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-overlay-close", function(e){ parent.setFocusElement('close_modal'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-affilate-link", function(e){ parent.setFocusElement('affiliate_title'); e.stopPropagation(); });
	jQuery("body").on("click", ".cp-affilate", function(e){ parent.setFocusElement('affiliate_title'); e.stopPropagation(); });

	//	Highlight Background options
	jQuery("body").on("click", ".cp-form-container", function(e){ parent.setFocusElement('modal_bg_color'); e.stopPropagation(); });

	//	CKEditor
	if( jQuery("#youtube_submit_btn").length ) {
		// Turn off automatic editor creation first.
		CKEDITOR.disableAutoInline = true;
		CKEDITOR.inline( 'youtube_submit_btn' );
		CKEDITOR.instances.youtube_submit_btn.on( 'change', function() {
			var data = CKEDITOR.instances.youtube_submit_btn.getData();
			parent.updateHTML(htmlEntities(data),'smile_youtube_submit');
		} );

		//	In any case if CKEditor is not initialized then use below code
		CKEDITOR.instances.youtube_submit_btn.on( 'instanceReady', function( ev ) {
			var editor = ev.editor;
	     	editor.setReadOnly( false );
		} );
	}
	jQuery('#youtube_submit_btn').attr('contenteditable', true );

	//	Initially store options in data attribute & generate the URL
	var 	iframe 			= jQuery(".cp-content-container").find('iframe'),
		video_id 			= jQuery('#smile_video_id', window.parent.document).val() || '',
		video_start 		= jQuery('.video_start', window.parent.document).val() || '',
		player_actions 	= jQuery('#smile_player_actions', window.parent.document).val() || '',
		player_controls 	= jQuery('#smile_player_controls', window.parent.document).val() || '',
		player_autoplay 	= jQuery('#smile_player_autoplay', window.parent.document).val() || '';

		iframe.attr('data-video_id', video_id );
		iframe.attr('data-video_start', video_start );
		iframe.attr('data-player_actions', player_actions );
		iframe.attr('data-player_controls', player_controls );
		iframe.attr('data-player_autoplay', player_autoplay );
		var url = generateURL( video_id, video_start, player_actions, player_controls, player_autoplay );
		iframe.attr('data-url', url );
		iframe.attr('src', url );
});

jQuery(document).on('smile_data_received', function(e,data) {

	var 	cp_youtube_submit	= jQuery("#youtube_submit_btn"),
		youtube_submit 	= data.youtube_submit;

	// CKEditor submit button
	youtube_submit = htmlEntities(youtube_submit);
	cp_youtube_submit.html(youtube_submit);
	if( jQuery("#youtube_submit_btn").length ) {
		CKEDITOR.instances.youtube_submit_btn.setData(youtube_submit);
	}
});

/**
 * Update live preview YouTube URL
 * @return {[type]} [description]
 */
function update_video_url(data) {

	var video_id 		=	data.video_id,
	video_start 		=	data.video_start,
	player_actions		=	data.player_actions,
	player_controls	=	data.player_controls,
	player_autoplay 	=	data.player_autoplay,
	cp_youtube_iframe 	= 	jQuery(".cp-content-container > iframe");

	// 	Check & Udpate the YouTube url.
	// 	Set NEW URL if there is difference in between OLD & NEW url
	var new_url = generateURL( video_id, video_start, player_actions, player_controls, player_autoplay );
	var old_url = cp_youtube_iframe.attr('data-url');
	if( new_url !== old_url ) {
		cp_youtube_iframe.attr('src', new_url);
		cp_youtube_iframe.attr('data-url', new_url);
	}
}

/**
 *	Hide parent modal custom width option for this style only.
 */
function toggle_hide_custom_width() {
	var v = jQuery('#smile_modal_size', window.parent.document).val();
	if( typeof v != 'undefined' && v != null ) {
		var f_width = jQuery('input[name="cp_modal_width"]', window.parent.document).closest('.smile-element-container').hide();
		if( v == 'cp-modal-window-size' ) {
			f_width.hide();
		} else {
			f_width.show();
		}
	}
}

function toggle_form( cta_switch ) {
	if( cta_switch == '1' ) {
		jQuery('.cp-form-container').show();
	} else {
		jQuery('.cp-form-container').hide();
	}
}

jQuery(document).ready(function($) {

	/**
	 * trigger smile_data_received
	 */
	jQuery(document).on('smile_customizer_field_change',function(e, single_data){

		//	Update box shadow
		if( 	"video_id" in single_data ||
			"video_start" in single_data ||
			"player_actions" in single_data ||
			"player_controls" in single_data ||
			"player_autoplay" in single_data ) {
			update_video_url( smile_global_data );
		}

		if( 	"modal_size" in single_data ) {
			toggle_hide_custom_width();

			smile_global_data.cp_modal_width = single_data.modal_size;
			update_width( smile_global_data );
		}

		// cta_switch
		if( "cta_switch" in single_data ) {
			toggle_form( single_data.cta_switch );
		}
	});

	//	Data Received - Continue
	jQuery(document).on('smile_data_continue_received', function(e,data) {
		var 	cp_modal				= jQuery(".cp-modal"),
			modal_size			= data.modal_size;

		if( !cp_modal.hasClass("cp-modal-exceed") ){
			cp_modal.attr('class', 'cp-modal '+modal_size);
		} else {
			cp_modal.attr('class', 'cp-modal cp-modal-exceed '+modal_size);
		}
	});

	//	Data Received - Once
	jQuery(document).on('smile_data_received', function(e,data) {
		update_width(data);
	});

	jQuery(document).on('smile_data_received', function(e,data) {
		toggle_form( data.cta_switch );
	});

	parent.jQuery(window.parent.document).on('cp-slider-slide', function( e, el, value) {
		if( jQuery(el).hasClass('cp_modal_width') ) {
			smile_global_data.cp_modal_width = value;
			update_width( smile_global_data );
		}
	});
});

function update_width(data) {

	var 	style 				= data.style,
		modal_size			= data.modal_size,
		cp_modal_width			= data.cp_modal_width;
	var 	cp_content_container	= jQuery(".cp-content-container"),
		cp_modal				= jQuery(".cp-modal"),
		cp_modal_content		= jQuery(".cp-modal-content");

	var v_height = cp_modal_width;
	v_height *= 1;
	var valueHeight = Math.round((v_height/16)*9);

	switch (modal_size) {
		case 'cp-modal-custom-size':
					cp_modal.removeClass('cp-modal-window-size');
					cp_content_container.css({ 'float':'none', 'max-width':cp_modal_width+'px', 'width':'100%', 'height':valueHeight+'px', 'margin': '0 auto', 'padding': 0 });
					cp_modal_content.css({'max-width':cp_modal_width+'px','width':'100%'});
					cp_modal.css({'max-width':cp_modal_width+'px','width':'100%'});
			break;

		case 'cp-modal-window-size':
					cp_content_container.css({'float':'none', 'max-width':'', 'width':'100vw','height':'100vh', 'margin': '0 auto', 'padding': 0 });
					cp_modal_content.css({'max-width':'100vw','width':'100vw'});
					cp_modal.css({'max-width':'100vw','width':'100vw'});
			break;

		default:
					cp_modal.removeClass('cp-modal-custom-size');
					jQuery(".cp_cs_overlay").css({"display":"none"});
					jQuery(".cp_fs_overlay").css({"display":"block"});

					cp_modal.removeAttr('style');
					cp_modal_content.removeAttr('style');
					cp_content_container.removeAttr('style');
					var ww = jQuery(window).width();
					var wh = jQuery(window).height();
					cp_content_container.css({'float':'none', 'max-width':ww+'px','width':'100%','height':wh+'px','padding':'0','margin':'0 auto'});
					cp_modal_content.css({'max-width':ww+'px','width':'100%'});
					cp_modal.css({'max-width':ww+'px','width':'100%'});
			break;
	}
}
