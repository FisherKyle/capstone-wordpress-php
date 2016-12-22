$ = jQuery;
var smile_panel_id = '';
jQuery(document).ready( function() {
	jQuery(document).on('smile_panel_loaded',function(e,smile_panel,id){
		smile_panel_id = smile_panel;
		function padding (options) {
			this.htmlElement = options['htmlElement'] || jQuery('#accordion-'+ smile_panel_id +' #padding-panel');
			this.htmlCode = jQuery('#accordion-'+ smile_panel_id +' #padding-code');
			this.all_sides = options['all_sides'] || 1;
			this.top = options['top'] || 1;
			this.left = options['left'] || 1;
			this.right = options['right'] || 1;
			this.bottom = options['bottom'] || 1;
		}
		
		padding.prototype.refresh = function () {	
			var inputCode = 'all_sides:'+this.all_sides+'|';
			inputCode += 'top:'+this.top+'|';   
			inputCode += 'left:'+this.left+'|';
			inputCode += 'right:'+this.right+'|';
			inputCode += 'bottom:'+this.bottom;
		
			this.htmlCode.html(inputCode);
			this.htmlCode.trigger('change');
		}
		
		padding.prototype.setall_sides = function (radius) {
			this.all_sides = radius;
			this.top = radius;
			this.left = radius;
			this.right = radius;
			this.bottom = radius;
		}
		
		function _getAllValuesFromPanelpadding() {
			var options = {};
			options['all_sides'] = parseFloat(jQuery("#accordion-"+ smile_panel_id +" #padding-all_sides").val());
			options['top'] = parseFloat(jQuery('#accordion-'+ smile_panel_id +' #padding-top').val());
			options['left'] = parseFloat(jQuery('#accordion-'+ smile_panel_id +' #padding-left').val());
			options['right'] = parseFloat(jQuery('#accordion-'+ smile_panel_id +' #padding-right').val());
			options['bottom'] = parseFloat(jQuery('#accordion-'+ smile_panel_id +' #padding-bottom').val());
			return options;
		}
		
		function _getFromFieldpadding(value, min, max, elem) {
			var val = parseFloat(value);
			if (isNaN(val) || val < min) {
				val = 0;
			} else if (val > max) {
				val = max;
			}
		
			if (elem)
				elem.val(val);
		
			return val;
		}
		padding = new padding(_getAllValuesFromPanelpadding());
		padding.refresh();
	
		/* Border Width */
		jQuery('#accordion-'+ smile_panel +' #slider-padding-all_sides').slider({
			value: jQuery('#accordion-'+ smile_panel +' #padding-all_sides').val(),
			min: 0,
			max: 500,
			step: 1,
			slide: function(event, ui) {
				var val = _getFromFieldpadding(ui.value, 0, 500);
				padding.setall_sides(val);       
	
				jQuery('#accordion-'+ smile_panel +' #padding-all_sides').val(val);
				jQuery('#accordion-'+ smile_panel +' #padding-top').val(val);
				jQuery('#accordion-'+ smile_panel +' #padding-left').val(val);
				jQuery('#accordion-'+ smile_panel +' #padding-right').val(val);
				jQuery('#accordion-'+ smile_panel +' #padding-bottom').val(val);
	
				var leftpaddingToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftpaddingToSlider);
				jQuery('#accordion-'+ smile_panel +' #slider-padding-all_sides').slider('value', val).find('.range-quantity').css('width',leftpaddingToSlider);
				jQuery('#accordion-'+ smile_panel +' #slider-padding-top').slider('value', val).find('.range-quantity').css('width',leftpaddingToSlider);
				jQuery('#accordion-'+ smile_panel +' #slider-padding-left').slider('value', val).find('.range-quantity').css('width',leftpaddingToSlider);
				jQuery('#accordion-'+ smile_panel +' #slider-padding-right').slider('value', val).find('.range-quantity').css('width',leftpaddingToSlider);
				jQuery('#accordion-'+ smile_panel +' #slider-padding-bottom').slider('value', val).find('.range-quantity').css('width',leftpaddingToSlider);
	
				padding.refresh();
				
			},
			stop: function( event, ui ) {
				padding.refresh();
			},
			create: function( event, ui ) {
				var leftpaddingToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftpaddingToSlider);
			}
		});
	
		jQuery('#accordion-'+ smile_panel +' #slider-padding-top').slider({
			value: jQuery('#accordion-'+ smile_panel +' #padding-top').val(),
			min: 0,
			max: 500,
			step: 1,
			slide: function(event, ui) {
				var val = _getFromFieldpadding(ui.value, 0, 500, jQuery('#accordion-'+ smile_panel +' #padding-top'));
				padding.top = val;
				padding.refresh();
				var leftpaddingToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftpaddingToSlider);
			},
			create: function( event, ui ){
				var leftpaddingToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftpaddingToSlider);
			}
		});
	
		jQuery('#accordion-'+ smile_panel +' #slider-padding-left').slider({
			value: jQuery('#accordion-'+ smile_panel +' #padding-left').val(),
			min: 0,
			max: 500,
			step: 1,
			slide: function(event, ui) {
				var val = _getFromFieldpadding(ui.value, 0, 500, jQuery('#accordion-'+ smile_panel +' #padding-left'));
				padding.left = val;
				padding.refresh();
				var leftpaddingToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftpaddingToSlider);
			},
			create: function( event, ui ){
				var leftpaddingToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftpaddingToSlider);
			}
		});
	
		jQuery('#accordion-'+ smile_panel +' #slider-padding-right').slider({
			value: jQuery('#accordion-'+ smile_panel +' #padding-right').val(),
			min: 0,
			max: 500,
			step: 1,
			slide: function(event, ui) {
				var val = _getFromFieldpadding(ui.value, 0, 500, jQuery('#accordion-'+ smile_panel +' #padding-right'));
				padding.right = val;
				padding.refresh();
				var leftpaddingToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftpaddingToSlider);
			},
			create: function( event, ui ) {
				var leftpaddingToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftpaddingToSlider);
			}
		});
	
		jQuery('#accordion-'+ smile_panel +' #slider-padding-bottom').slider({
			value: jQuery('#accordion-'+ smile_panel +' #padding-bottom').val(),
			min: 0,
			max: 500,
			step: 1,
			slide: function(event, ui) {
				var val = _getFromFieldpadding(ui.value, 0, 500, jQuery('#accordion-'+ smile_panel +' #padding-bottom'));
				padding.bottom = val;
				padding.refresh();
				var leftpaddingToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftpaddingToSlider);
			},
			create: function( event, ui ) {
				var leftpaddingToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftpaddingToSlider);
			}
		});
	
		jQuery('#accordion-'+ smile_panel +' #padding-all_sides').on('keyup', function() {
	
			var val = _getFromFieldpadding(jQuery(this).val(), 0, 500, jQuery('#accordion-'+ smile_panel +' #all-sides'));
			padding.setall_sides(val);
			padding.refresh();
	
			jQuery('#accordion-'+ smile_panel +' #slider-padding-all_sides').slider('value', val);
			var leftpaddingToSlider = jQuery('#accordion-'+ smile_panel +' #slider-padding-all_sides').find('.ui-slider-handle').css('left');
	
			jQuery('#accordion-'+ smile_panel +' #slider-padding-all_sides').find('.range-quantity').css('width',leftpaddingToSlider);
			jQuery('#accordion-'+ smile_panel +' #padding-top').val(val);
			jQuery('#accordion-'+ smile_panel +' #padding-left').val(val);
			jQuery('#accordion-'+ smile_panel +' #padding-right').val(val);
			jQuery('#accordion-'+ smile_panel +' #padding-bottom').val(val);
	
			jQuery('#accordion-'+ smile_panel +' #slider-padding-all_sides').slider('value', val).find('.range-quantity').css('width',leftpaddingToSlider);
			jQuery('#accordion-'+ smile_panel +' #slider-padding-top').slider('value', val).find('.range-quantity').css('width',leftpaddingToSlider);
			jQuery('#accordion-'+ smile_panel +' #slider-padding-left').slider('value', val).find('.range-quantity').css('width',leftpaddingToSlider);
			jQuery('#accordion-'+ smile_panel +' #slider-padding-right').slider('value', val).find('.range-quantity').css('width',leftpaddingToSlider);
			jQuery('#accordion-'+ smile_panel +' #slider-padding-bottom').slider('value', val).find('.range-quantity').css('width',leftpaddingToSlider);
		
		});
	
		jQuery('#padding-top').on('keyup', function() {
			var val = _getFromFieldpadding(jQuery(this).val(), 0, 500, jQuery('#accordion-'+ smile_panel +' #padding-top'));
			padding.top = val;
			padding.refresh();
	
			jQuery('#accordion-'+ smile_panel +' #slider-padding-top').slider('value', val);
	
			var leftpaddingToSlider = jQuery('#accordion-'+ smile_panel +' #slider-padding-top').find('.ui-slider-handle').css('left');
			jQuery('#accordion-'+ smile_panel +' #slider-padding-top').find('.range-quantity').css('width',leftpaddingToSlider);
		});
	
		jQuery('#accordion-'+ smile_panel +' #padding-left').on('keyup', function () {
			var val = _getFromFieldpadding(jQuery(this).val(), 0, 500, jQuery('#accordion-'+ smile_panel +' #padding-left'));
			padding.left = val;
			padding.refresh();
	
			jQuery('#accordion-'+ smile_panel +' #slider-padding-left').slider('value', val);
	
			var leftpaddingToSlider = jQuery('#accordion-'+ smile_panel +' #slider-padding-left').find('.ui-slider-handle').css('left');
			jQuery('#accordion-'+ smile_panel +' #slider-padding-left').find('.range-quantity').css('width',leftpaddingToSlider);
		});
	
		jQuery('#accordion-'+ smile_panel +' #padding-right').on('keyup', function() {
			var val = _getFromFieldpadding(jQuery(this).val(), 0, 500, jQuery('#accordion-'+ smile_panel +' #padding-right'));
			padding.right = val;
			padding.refresh();
	
			jQuery('#accordion-'+ smile_panel +' #slider-padding-right').slider('value', val);
	
			var leftpaddingToSlider = jQuery('#accordion-'+ smile_panel +' #slider-padding-right').find('.ui-slider-handle').css('left');
			jQuery('#accordion-'+ smile_panel +' #slider-padding-right').find('.range-quantity').css('width',leftpaddingToSlider);
		});
	
		jQuery('#accordion-'+ smile_panel +' #padding-bottom').on('keyup', function() {
			var val = _getFromFieldpadding(jQuery(this).val(), 0, 500, jQuery('#accordion-'+ smile_panel +' #padding-bottom'));
			padding.bottomRight = val;
			padding.refresh();
	
			jQuery('#accordion-'+ smile_panel +' #slider-padding-bottom').slider('value', val);
	
			var leftpaddingToSlider = jQuery('#accordion-'+ smile_panel +' #slider-padding-bottom').find('.ui-slider-handle').css('left');
			jQuery('#accordion-'+ smile_panel +' #slider-padding-bottom').find('.range-quantity').css('width',leftpaddingToSlider);
		});
	});
});