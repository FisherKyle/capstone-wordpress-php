$ = jQuery;
var smile_panel_id = '';
jQuery(document).on('smile_panel_loaded',function(e,smile_panel,id){
	smile_panel_id = smile_panel;
	function GoogleFonts (options) {
		this.htmlCode = jQuery('#accordion-'+ smile_panel_id +' .smile-input-gfonts' );
	};

	GoogleFonts.prototype.refresh = function () {

		var inputCode = this.htmlCode.val();
		var use_in = this.htmlCode.data('use-in');

		if( use_in == 'editor' ) {
			if( typeof inputCode != 'undefined' && inputCode != null ){
				var split_font = inputCode.split('|'); 								//font_family=xyz|font_call=xyz:100,200
				var font_family = split_font[0]; 									//font_family=xyz
				var font_call = split_font[1]; 										//font_call=xyz

				var inputCode = font_family+'|'+font_call+'|';
				jQuery(this).closest('.smile-element-container').find('.smile-input-gfonts').val(inputCode);
				this.htmlCode.html(inputCode);
				this.htmlCode.trigger('change');
			}
		} else {
			var sel = jQuery(this).closest('.smile-element-container').find('.smile-input-gfonts');
			var vl = jQuery(this).find('option:selected').val();
			sel.val(vl);
			sel.trigger('change');
		}
	};

	GoogleFonts.prototype.update = function (sel, vl) {

		var use_in = sel.data('use-in');
		if( use_in != 'editor' ) {
			if( typeof vl != 'undefined' && vl != null ){
				sel.val(vl);
				sel.trigger('change');
			}
		}
	};

	function process_vc_gfont_fields($select, random_num, is_font_change) {

		var temp_count = 0;
		var gFont = $select.closest('.smile-element-container').find('.smile-input-gfonts');
		var gFont_val = gFont.val() || '';
		var val = '';
		var fonts = [];

		if( is_font_change == 'false' )
		{
			if(gFont_val != '')
			{
				var gfont_name_attr = gFont_val.split('|');
				var gfont_name = gfont_name_attr[0].split(':');
				val = gfont_name[1];
				if(val == '')
					val = 'default';
			}
			else
				val = 'default';

			$select.find('option').each(function(index, option) {

				//	Add all fonts
				var val = jQuery(option).val();
				if( typeof val != 'undefined' && val != null && val != '' ) {
					fonts.push(val);
				}
			});
		} else {
			var val = $select.find('option:selected').val();
			var new_font_call = val.replace(/\s+/g,'+');
			var new_font = 'font_family:'+val+'|font_call:'+new_font_call;
		}


		gFont_val = gFont_val.split(",");

		// combine fonts from "ultimate_selected_fonts" option and style specific fonts
		var combine_arr = fonts.concat(gFont_val);

		// avoid duplicate fonts , create unique font array
		var uniqueFonts = [];
		jQuery.each( combine_arr, function(i, el) {
		    if(jQuery.inArray(el, uniqueFonts) === -1) uniqueFonts.push(el);
		});

		var fonts = uniqueFonts.join();

		//	Add all selected Google fonts
		if(gFont.data('use-in') == 'editor') {
			gFont.val(uniqueFonts);
		}

	};

	function _initCallGoogleFonts() {

		//	Ajax Call - on Initial
		jQuery('.ultimate_google_font_param_block select').each(function(index, element) {
			$select = jQuery(this);
			var random_num = Math.floor((Math.random() * 10000000) + index);
			process_vc_gfont_fields($select, random_num, change = 'false');
		});
	};
     GoogleFonts = new GoogleFonts();
     GoogleFonts.refresh();

    //	init
    _initCallGoogleFonts();

   	jQuery('.ultimate_google_font_param_block  select').each(function(index, element) {

   		//  Partial Refresh
		//  -   Apply text-align, border-style etc.
		var a 		 = jQuery(element);
		var o 		 = a.val();
		var css_preview = a.attr('data-css-preview') || '';
		var selector    = a.attr('data-css-selector') || '';
		var property    = a.attr('data-css-property') || '';
		var unit        = a.attr('data-unit') || 'px';
		var value       = o;
		partial_refresh_font_family( css_preview, selector, property, unit, value );

   		a.change(function() {
			var sel = jQuery(this).closest('.smile-element-container').find('.smile-input-gfonts');
			var vl = jQuery(this).find('option:selected').val();
			GoogleFonts.update(sel, vl);

			//  Partial Refresh
			//  -   Apply text-align, border-style etc.
			var a 		 = jQuery(this);
			var o 		 = a.val();
			var css_preview = a.attr('data-css-preview') || '';
			var selector    = a.attr('data-css-selector') || '';
			var property    = a.attr('data-css-property') || '';
			var unit        = a.attr('data-unit') || 'px';
			var value       = o;
			partial_refresh_font_family( css_preview, selector, property, unit, value );

			jQuery(document).trigger('smile-google-font-change', [a] );
		});
	});

	function partial_refresh_font_family( css_preview, selector, property, unit, value ) {
    		//  apply css by - inline
    		if( css_preview != 1 || null == css_preview || 'undefined' == css_preview ) {
    		    jQuery("#smile_design_iframe").contents().find( selector ).css( property , value );
    		}
    		//  apply css by - after css generation
    		jQuery(document).trigger('updated', [css_preview, selector, property, value, unit]);
 	}

});
