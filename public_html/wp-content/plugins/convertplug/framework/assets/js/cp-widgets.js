jQuery( document ).ready(function() {

	jQuery( '.cp-active-modules').each(function( index ) {
		var active_module = jQuery(this).val() ;
		var widget_content = jQuery(this).closest('.widget-content');
		var cp_modal_id = widget_content.find('.cp-modal-id');
		var cp_infobar_id = widget_content.find('.cp-infobar-id');
		var cp_slidein_id = widget_content.find('.cp-slidein-id');


		if ( active_module == 'info_bar') {
			cp_modal_id.hide();
			cp_infobar_id.show();
			cp_slidein_id.hide();
		}else if( active_module == 'slide_in' ){
			cp_modal_id.hide();
			cp_infobar_id.hide();
			cp_slidein_id.show();
		}else{
			cp_modal_id.show();
			cp_infobar_id.hide();
			cp_slidein_id.hide();
		}
	});
});

jQuery( document ).on("change",".cp-active-modules", function(e) {
	var active_module = jQuery(this).val() ;
	var widget_content = jQuery(this).closest('.widget-content');
	var cp_modal_id = widget_content.find('.cp-modal-id');
	var cp_infobar_id = widget_content.find('.cp-infobar-id');
	var cp_slidein_id = widget_content.find('.cp-slidein-id');


	if ( active_module == 'info_bar') {
		cp_modal_id.hide();
		cp_infobar_id.show();
		cp_slidein_id.hide();
	}else if( active_module == 'slide_in' ){
		cp_modal_id.hide();
		cp_infobar_id.hide();
		cp_slidein_id.show();
	}else{
		cp_modal_id.show();
		cp_infobar_id.hide();
		cp_slidein_id.hide();
	}
}); 