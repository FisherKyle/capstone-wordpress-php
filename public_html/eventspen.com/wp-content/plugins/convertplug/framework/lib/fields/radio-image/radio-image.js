jQuery(document).ready(function(){
	var rm_container = jQuery(".smile-radio-image-holder");
	rm_container.click(function(){
		var $this = jQuery(this);
		jQuery.each(rm_container,function(index,element){
			jQuery(this).removeClass('selected');
		});
		$this.addClass('selected');
		$this.find('input:radio').prop('checked', true);
		$this.find('input.smile-input').trigger('change');
		
		var r = $this.find('input:radio');
		r.prop('checked', true);

		$this.find('input.smile-radio-image').trigger('change');
		jQuery(document).trigger('smile-radio-image-change', [r] );

		var value = $this.find('input:radio').val();
		var elem = $this.find('input:radio').attr('name');
		jQuery(document).trigger('radio_image_click',[elem,value]);
	});
});