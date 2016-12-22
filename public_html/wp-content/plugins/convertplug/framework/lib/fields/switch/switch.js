jQuery(document).ready(function(){
	var switch_btn = jQuery(".smile-switch-btn");
	jQuery(document).on('click', '.smile-switch-btn', function(e){
		var id = jQuery(this).data('id');
		var value = jQuery(this).parents(".switch-wrapper").find("#"+id).val();

		if( value == 1 || value == '1' ) {
			jQuery(this).parents(".switch-wrapper").find("#"+id).attr('value','0');
		} else {
			jQuery(this).parents(".switch-wrapper").find("#"+id).attr('value','1');
		}
		jQuery(this).parents(".switch-wrapper").find(".smile-switch-input").trigger('change');
		$(document).trigger('smile-switch-change', [id] );
	
	});
});