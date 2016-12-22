;(function ( $, window, undefined ) {


	$(document).ready(function($) {
		$('.smile-select').each(function(index, el) {
			$(el).change(function(event) {

				//  Partial Refresh
	     		//  -   Apply text-align, border-style etc.
	     		var a 			= $(el);
	     		var o 			= $(el).val();
	     		var css_preview = a.attr('data-css-preview') || '';
	     		var selector    = a.attr('data-css-selector') || '';
	     		var property    = a.attr('data-css-property') || '';
	     		var unit        = a.attr('data-unit') || 'px';
	     		var value       = o;
	     		partial_refresh_dropdown( css_preview, selector, property, unit, value );
				$(document).trigger('smile-select-change', [el] );
			});
		});

		//  Partial Refresh
	    //  -   Apply text-align, border-style etc.
		var a 			= $('.smile-select');
		var o 			= $('.smile-select').val();
	    var css_preview = a.attr('data-css-preview') || '';
	    var selector    = a.attr('data-css-selector') || '';
	    var property    = a.attr('data-css-property') || '';
	    var unit        = a.attr('data-unit') || 'px';
	    var value       = o;
	    partial_refresh_dropdown( css_preview, selector, property, unit, value );
		
	});

	function partial_refresh_dropdown( css_preview, selector, property, unit, value ) {
        //  apply css by - inline
        if( css_preview != 1 || null == css_preview || 'undefined' == css_preview ) {
            jQuery("#smile_design_iframe").contents().find( selector ).css( property , value );
        }
        //  apply css by - after css generation
        jQuery(document).trigger('updated', [css_preview, selector, property, value, unit]);
 	}

}(jQuery, window));