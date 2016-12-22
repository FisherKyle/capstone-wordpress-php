$ = jQuery;
var smile_panel_id = '';
jQuery(document).ready( function() {
	jQuery(document).on('smile_panel_loaded',function(e,smile_panel,id){
		smile_panel_id = smile_panel;
		var margin = function (options) {
			this.htmlElement = options['htmlElement'] || jQuery('#accordion-'+ smile_panel_id +' #margin-panel');
			this.htmlCode = jQuery('#accordion-'+ smile_panel_id +' #margin-code');
			this.all_sides = options['all_sides'] || 1;
			this.top = options['top'] || 1;
			this.left = options['left'] || 1;
			this.right = options['right'] || 1;
			this.bottom = options['bottom'] || 1;
		}
		
		margin.prototype.refresh = function () {	
			var inputCode = 'all_sides:'+this.all_sides+'|';
			inputCode += 'top:'+this.top+'|';   
			inputCode += 'left:'+this.left+'|';
			inputCode += 'right:'+this.right+'|';
			inputCode += 'bottom:'+this.bottom;
		
			this.htmlCode.html(inputCode);
			this.htmlCode.trigger('change');
		}
		
		margin.prototype.setall_sides = function (radius) {
			this.all_sides = radius;
			this.top = radius;
			this.left = radius;
			this.right = radius;
			this.bottom = radius;
		}
		
		function _getAllValuesFromPanelmargin() {
			var options = {};
			options['all_sides'] = parseFloat(jQuery("#accordion-"+ smile_panel_id +" #margin-all_sides").val());
			options['top'] = parseFloat(jQuery('#accordion-'+ smile_panel_id +' #margin-top').val());
			options['left'] = parseFloat(jQuery('#accordion-'+ smile_panel_id +' #margin-left').val());
			options['right'] = parseFloat(jQuery('#accordion-'+ smile_panel_id +' #margin-right').val());
			options['bottom'] = parseFloat(jQuery('#accordion-'+ smile_panel_id +' #margin-bottom').val());
			return options;
		}
		
		function _getFromFieldMargin(value, min, max, elem) {
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
	
		var opts = _getAllValuesFromPanelmargin();
		margin = new margin(opts);
		margin.refresh();
	
		/* Border Width */
		jQuery('#accordion-'+ smile_panel +' #slider-margin-all_sides').slider({
			value: jQuery('#accordion-'+ smile_panel +' #margin-all_sides').val(),
			min: 0,
			max: 500,
			step: 1,
			slide: function(event, ui) {
				var val = _getFromFieldMargin(ui.value, 0, 500);
				margin.setall_sides(val);       
	
				jQuery('#accordion-'+ smile_panel +' #margin-all_sides').val(val);
				jQuery('#accordion-'+ smile_panel +' #margin-top').val(val);
				jQuery('#accordion-'+ smile_panel +' #margin-left').val(val);
				jQuery('#accordion-'+ smile_panel +' #margin-right').val(val);
				jQuery('#accordion-'+ smile_panel +' #margin-bottom').val(val);
	
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
				jQuery('#accordion-'+ smile_panel +' #slider-margin-all_sides').slider('value', val).find('.range-quantity').css('width',leftMarginToSlider);
				jQuery('#accordion-'+ smile_panel +' #slider-margin-top').slider('value', val).find('.range-quantity').css('width',leftMarginToSlider);
				jQuery('#accordion-'+ smile_panel +' #slider-margin-left').slider('value', val).find('.range-quantity').css('width',leftMarginToSlider);
				jQuery('#accordion-'+ smile_panel +' #slider-margin-right').slider('value', val).find('.range-quantity').css('width',leftMarginToSlider);
				jQuery('#accordion-'+ smile_panel +' #slider-margin-bottom').slider('value', val).find('.range-quantity').css('width',leftMarginToSlider);
	
				margin.refresh();
				
			},
			stop: function( event, ui ) {
				margin.refresh();
			},
			create: function( event, ui ) {
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
			}
		});
	
		jQuery('#accordion-'+ smile_panel +' #slider-margin-top').slider({
			value: jQuery('#accordion-'+ smile_panel +' #margin-top').val(),
			min: 0,
			max: 500,
			step: 1,
			slide: function(event, ui) {
				var val = _getFromFieldMargin(ui.value, 0, 500, jQuery('#accordion-'+ smile_panel +' #margin-top'));
				margin.top = val;
				margin.refresh();
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
			},
			create: function( event, ui ){
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
			}
		});
	
		jQuery('#accordion-'+ smile_panel +' #slider-margin-left').slider({
			value: jQuery('#accordion-'+ smile_panel +' #margin-left').val(),
			min: 0,
			max: 500,
			step: 1,
			slide: function(event, ui) {
				var val = _getFromFieldMargin(ui.value, 0, 500, jQuery('#accordion-'+ smile_panel +' #margin-left'));
				margin.left = val;
				margin.refresh();
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
			},
			create: function( event, ui ){
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
			}
		});
	
		jQuery('#accordion-'+ smile_panel +' #slider-margin-right').slider({
			value: jQuery('#accordion-'+ smile_panel +' #margin-right').val(),
			min: 0,
			max: 500,
			step: 1,
			slide: function(event, ui) {
				var val = _getFromFieldMargin(ui.value, 0, 500, jQuery('#accordion-'+ smile_panel +' #margin-right'));
				margin.right = val;
				margin.refresh();
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
			},
			create: function( event, ui ) {
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
			}
		});
	
		jQuery('#accordion-'+ smile_panel +' #slider-margin-bottom').slider({
			value: jQuery('#accordion-'+ smile_panel +' #margin-bottom').val(),
			min: 0,
			max: 500,
			step: 1,
			slide: function(event, ui) {
				var val = _getFromFieldMargin(ui.value, 0, 500, jQuery('#accordion-'+ smile_panel +' #margin-bottom'));
				margin.bottom = val;
				margin.refresh();
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
			},
			create: function( event, ui ) {
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
			}
		});
	
		jQuery('#accordion-'+ smile_panel +' #margin-all_sides').on('keyup', function() {
	
			var val = _getFromFieldMargin(jQuery(this).val(), 0, 500, jQuery('#accordion-'+ smile_panel +' #all-sides'));
			margin.setall_sides(val);
			margin.refresh();
	
			jQuery('#accordion-'+ smile_panel +' #slider-margin-all_sides').slider('value', val);
			var leftMarginToSlider = jQuery('#accordion-'+ smile_panel +' #slider-margin-all_sides').find('.ui-slider-handle').css('left');
	
			jQuery('#accordion-'+ smile_panel +' #slider-margin-all_sides').find('.range-quantity').css('width',leftMarginToSlider);
			jQuery('#accordion-'+ smile_panel +' #margin-top').val(val);
			jQuery('#accordion-'+ smile_panel +' #margin-left').val(val);
			jQuery('#accordion-'+ smile_panel +' #margin-right').val(val);
			jQuery('#accordion-'+ smile_panel +' #margin-bottom').val(val);
	
			jQuery('#accordion-'+ smile_panel +' #slider-margin-all_sides').slider('value', val).find('.range-quantity').css('width',leftMarginToSlider);
			jQuery('#accordion-'+ smile_panel +' #slider-margin-top').slider('value', val).find('.range-quantity').css('width',leftMarginToSlider);
			jQuery('#accordion-'+ smile_panel +' #slider-margin-left').slider('value', val).find('.range-quantity').css('width',leftMarginToSlider);
			jQuery('#accordion-'+ smile_panel +' #slider-margin-right').slider('value', val).find('.range-quantity').css('width',leftMarginToSlider);
			jQuery('#accordion-'+ smile_panel +' #slider-margin-bottom').slider('value', val).find('.range-quantity').css('width',leftMarginToSlider);
		
		});
	
		jQuery('#margin-top').on('keyup', function() {
			var val = _getFromFieldMargin(jQuery(this).val(), 0, 500, jQuery('#accordion-'+ smile_panel +' #margin-top'));
			margin.top = val;
			margin.refresh();
	
			jQuery('#accordion-'+ smile_panel +' #slider-margin-top').slider('value', val);
	
			var leftMarginToSlider = jQuery('#accordion-'+ smile_panel +' #slider-margin-top').find('.ui-slider-handle').css('left');
			jQuery('#accordion-'+ smile_panel +' #slider-margin-top').find('.range-quantity').css('width',leftMarginToSlider);
		});
	
		jQuery('#accordion-'+ smile_panel +' #margin-left').on('keyup', function () {
			var val = _getFromFieldMargin(jQuery(this).val(), 0, 500, jQuery('#accordion-'+ smile_panel +' #margin-left'));
			margin.left = val;
			margin.refresh();
	
			jQuery('#accordion-'+ smile_panel +' #slider-margin-left').slider('value', val);
	
			var leftMarginToSlider = jQuery('#accordion-'+ smile_panel +' #slider-margin-left').find('.ui-slider-handle').css('left');
			jQuery('#accordion-'+ smile_panel +' #slider-margin-left').find('.range-quantity').css('width',leftMarginToSlider);
		});
	
		jQuery('#accordion-'+ smile_panel +' #margin-right').on('keyup', function() {
			var val = _getFromFieldMargin(jQuery(this).val(), 0, 500, jQuery('#accordion-'+ smile_panel +' #margin-right'));
			margin.right = val;
			margin.refresh();
	
			jQuery('#accordion-'+ smile_panel +' #slider-margin-right').slider('value', val);
	
			var leftMarginToSlider = jQuery('#accordion-'+ smile_panel +' #slider-margin-right').find('.ui-slider-handle').css('left');
			jQuery('#accordion-'+ smile_panel +' #slider-margin-right').find('.range-quantity').css('width',leftMarginToSlider);
		});
	
		jQuery('#accordion-'+ smile_panel +' #margin-bottom').on('keyup', function() {
			var val = _getFromFieldMargin(jQuery(this).val(), 0, 500, jQuery('#accordion-'+ smile_panel +' #margin-bottom'));
			margin.bottomRight = val;
			margin.refresh();
	
			jQuery('#accordion-'+ smile_panel +' #slider-margin-bottom').slider('value', val);
	
			var leftMarginToSlider = jQuery('#accordion-'+ smile_panel +' #slider-margin-bottom').find('.ui-slider-handle').css('left');
			jQuery('#accordion-'+ smile_panel +' #slider-margin-bottom').find('.range-quantity').css('width',leftMarginToSlider);
		});
	});
});