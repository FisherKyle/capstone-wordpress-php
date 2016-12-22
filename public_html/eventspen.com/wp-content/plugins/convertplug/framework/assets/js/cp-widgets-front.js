(function($) {

	$('.convertplug_widget').each(function(index) {

		var $this = $(this);
		var cp_inline_modal_container = $this.find('.cp-inline-modal-container');

		var container_width = cp_inline_modal_container.width();
		if ( container_width < 500 ) {
			cp_inline_modal_container.addClass("cp-widget-inline-support")
		}else{
			cp_inline_modal_container.removeClass( "cp-widget-inline-support" )
		};
	});
})( jQuery );