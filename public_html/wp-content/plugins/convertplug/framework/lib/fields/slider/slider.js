jQuery(document).ready(function(jQuery) {
	var slider_input = jQuery(".smile-slider");
	jQuery.each(slider_input,function(index,obj){
		var $this 		= jQuery(this);
		var slider_id 	= $this.attr('id').replace("smile_","slider_");
		var input_id 	= $this.attr('id');
		var val 		= $this.val();
		var minimum 	= $this.data('min');
		var maximum 	= $this.data('max');
		var step 		= $this.data('step');

		//  Partial Refresh
        //  -	Apply to height, width, font-size, line-height etc.
        var css_preview = $this.attr('data-css-preview') || '';
        var selector    = $this.attr('data-css-selector') || '';
        var property    = $this.attr('data-css-property') || '';
        var value 		= $this.val();
        var unit        = $this.attr('data-unit') || 'px';
		partial_slider_css( css_preview, selector, property, value, unit );

		jQuery( '#'+input_id ).on('keyup change', function() {
				value = jQuery(this).val();
				jQuery( '#'+slider_id ).slider('value', value);
				var leftMarginToSlider = jQuery( '#'+slider_id ).find('.ui-slider-handle').css('left');
				jQuery( '#'+slider_id ).find('.range-quantity').css('width',leftMarginToSlider);

				//  Partial Refresh
                //  -	Apply to height, width, font-size, line-height etc.
                var a 		= jQuery( '#'+input_id );
                css_preview = a.attr('data-css-preview') || '';
                selector    = a.attr('data-css-selector') || '';
                property    = a.attr('data-css-property') || '';
                unit        = a.attr('data-unit') || 'px';
                partial_slider_css( css_preview, selector, property, value, unit );
                
                //  Trigger
                jQuery(document).trigger('cp-slider-change', [a, value]);
		});
		jQuery( '#'+slider_id ).slider({
				value : val,
				min   : minimum,
				max   : maximum,
				step  : step,
				slide : function( event, ui ) {
					jQuery( '#'+input_id ).val(ui.value).keyup(); 
					var leftMarginToSlider = jQuery( '#'+slider_id ).find('.ui-slider-handle').css('left');
					jQuery( '#'+slider_id ).find('.range-quantity').css('width',leftMarginToSlider);

	                //  Partial Refresh
                    //  -	Apply to height, width, font-size, line-height etc.
                    var a 			= jQuery( '#'+input_id );
                    css_preview = a.attr('data-css-preview') || '';
                    selector    = a.attr('data-css-selector') || '';
                    property    = a.attr('data-css-property') || '';
                    unit        = a.attr('data-unit') || 'px';
	                partial_slider_css( css_preview, selector, property, value, unit );
                    
                    //  Trigger
                    jQuery(document).trigger('cp-slider-slide', [a, value]);
				}
		});
		jQuery( '#'+input_id ).val( jQuery( '#'+slider_id ).slider( "value" ) );		
		var leftMarginToSlider = jQuery( '#'+slider_id ).find('.ui-slider-handle').css('left');
		jQuery( '#'+slider_id ).find('.range-quantity').css('width',leftMarginToSlider);

		//  apply css by - inline
    	function partial_slider_css( css_preview, selector, property, value, unit ) {
            if( css_preview != 1 || null == css_preview || 'undefined' == css_preview ) {
                var apply_to = jQuery("#smile_design_iframe").contents().find(selector);

            	switch( property ) {
            		case 'padding-tb': 		apply_to.css({'padding-top' : value + 'px', 'padding-bottom' : value + 'px'});
            			break;
            		case 'padding-lr': 		apply_to.css({'padding-left' : value + 'px', 'padding-right' : value + 'px'});
            			break;
            		case 'margin-tb': 		apply_to.css({'margin-top' : value + 'px', 'margin-bottom' : value + 'px'});
            			break;
            		case 'margin-lr': 		apply_to.css({'margin-left' : value + 'px', 'margin-right' : value + 'px'});
            			break;
                    case 'width-max':       apply_to.css({'max-width' : value + 'px', 'width' : value + 'px'});
                        break;
            		default:
                							apply_to.css(property, value + 'px' );
            			break;
            	}
            }
            //  apply css by - after css generation
            jQuery(document).trigger('updated', [css_preview, selector, property, value, unit]);
        }
	});
});