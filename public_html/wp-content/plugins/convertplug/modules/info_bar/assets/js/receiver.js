function updateHTML(html, target){
	html = htmlEntities(html);
	jQuery("#"+target).val(html);
}

function setYoutubeVideoHeight(value){
	var smile_panel = jQuery(".customize").data('style');
	var input = jQuery("#accordion-"+smile_panel+" .cp_info_bar_height");
	var $this = input;
	var input_id 	= $this.attr('id');
	var slider_id 	= $this.attr('id').replace("smile_","slider_");
	jQuery( '#'+input_id ).val( value );
	jQuery( '#'+slider_id ).slider('value', value)
	var leftMarginToSlider = jQuery( '#'+slider_id ).find('.ui-slider-handle').css('left');
	jQuery( '#'+slider_id ).find('.range-quantity').css('width',leftMarginToSlider);;
}

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/'/g, '&#39;').replace(/&quot;/g, '"');
}

function setFocusElement(element){
	var section = jQuery('.'+element).parents('.cp-customizer-tab:first');
	var section_id = jQuery(section).attr('id');
	jQuery('.cp-vertical-nav').find('a').each(function(i,a){
		var data_sec_id = jQuery(a).attr('data-section-id');
		if(section_id === data_sec_id) {
			if( !jQuery(a).hasClass('active') ){
				jQuery(a).trigger('click');
				return;
			}
		}
	});

	var accordion = jQuery("."+element).parents(".accordion-frame:first");
	var accordion_anchor = jQuery("."+element).parents(".accordion-frame:first").find("a.heading");
	var accordion_content = jQuery("."+element).parents(".accordion-frame:first").find(".content");
	if( accordion_anchor.hasClass('collapsed') ){
		jQuery(".accordion-frame a.heading").addClass('collapsed');
		jQuery(".accordion-frame .content").slideUp();
		accordion_anchor.removeClass('collapsed');
		accordion_content.slideDown();
	}
	var ID = jQuery("."+element).attr("id");
	
	if( !jQuery("."+element).parents('.smile-element-container').hasClass( "cp-set-focus" ) ){
		setTimeout(function(){
			jQuery('.smile-element-container').removeClass("cp-set-focus");
		
			jQuery("."+element).parents('.smile-element-container').addClass('cp-hl-active cp-set-focus');
		
			jQuery('.design-form').animate({
				scrollTop: jQuery("#"+ID).parents(".smile-element-container").offset().top - 100
			}, 1000);
			setTimeout(function(){
				jQuery("."+element).parents('.smile-element-container').removeClass('cp-hl-active');
				jQuery("."+element).focus();
			},2000);
		},300);
	} else {
		jQuery("."+element).parents('.smile-element-container').addClass('cp-hl-active');
		setTimeout(function(){
			jQuery("."+element).parents('.smile-element-container').removeClass('cp-hl-active');
		},2000);
	}
	jQuery('body').trigger('click');
	
}

function customizerLoaded(){
	jQuery(document).trigger('customize_loaded');
}