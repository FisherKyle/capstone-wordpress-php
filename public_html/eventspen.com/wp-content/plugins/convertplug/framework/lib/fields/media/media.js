jQuery(document).ready(function(jQuery){
	jQuery('.smile-upload-media').click(function(e) {

		_wpPluploadSettings['defaults']['multipart_params']['admin_page']= 'customizer';
		var button = jQuery(this);
		var id = 'smile_'+button.attr('id');
		var uid = button.data('uid');
		var rmv_btn = 'remove_'+button.attr('id');
		var img_container = button.attr('id')+'_container';
		//Extend wp.media object
		 var uploader = wp.media.frames.file_frame = wp.media({
			title: 'Select or Upload Image',
			button: {
				text: 'Choose Image'
			},
			library: {
				type: 'image'
			},
			multiple: false,
		});
		uploader.on('select', function(props, attachment){

			attachment = uploader.state().get('selection').first().toJSON();

			var data = attachment.id+"|"+attachment.url;
			var sz = jQuery(".cp-media-"+uid).val();
			var alt = attachment.alt;//console.log(id);
			var val = attachment.id+"|"+sz+"|"+alt;
			var a = jQuery("#"+id);
			var name = jQuery("#"+id).attr('name');
			a.val(val);
			a.attr('value',val);
			jQuery(".cp-media-"+uid).attr('data-id',attachment.id);
			jQuery(".cp-media-"+uid).attr('data-alt',attachment.alt);
			jQuery(".cp-media-"+uid).parents(".cp-media-sizes").removeClass("hide-for-default");
			jQuery("."+img_container).html('<img src="'+attachment.url+'"/>');
			jQuery("#"+rmv_btn).show();

			button.text('Change Image');

			//  Partial Refresh
            //  -   Apply background, background-color, color etc.
            var css_preview = a.attr('data-css-preview') || '';
            var selector    = a.attr('data-css-selector') || '';
            var property    = a.attr('data-css-property') || '';
            var unit        = a.attr('data-unit') || 'px';
            var url       	= attachment.url;

            partial_refresh_image( css_preview, selector, property, unit, url );

            jQuery(document).trigger('cp-image-change', [name,url, val] );
			jQuery("#"+id).trigger('change');
		});
		uploader.open(button);
		return false;
	});

	jQuery('.smile-remove-media').on('click', function(e){

		e.preventDefault();
		var button = jQuery(this);
		var id = button.attr('id').replace("remove_","smile_");
		var upload = button.attr('id').replace("remove_","");
		var img_container = button.attr('id').replace("remove_","")+'_container';
		jQuery("#"+id).attr('value','');

		//  Partial Refresh
        //  -   Apply background, background-color, color etc.
        var a = jQuery("#"+id);
        var css_preview = a.attr('data-css-preview') || '';
        var selector    = a.attr('data-css-selector') || '';
        var property    = a.attr('data-css-property') || '';
        var unit        = a.attr('data-unit') || 'px';
        var value       = ''; 	//	Empty the background image
        var name 		= jQuery("#"+id).attr('name');
        partial_refresh_image( css_preview, selector, property, unit, value );

		var html = '<p class="description">No Image Selected</p>';
		jQuery("."+img_container).html(html);

		button.hide();
		jQuery("#"+upload).text('Select Image');

		jQuery(document).trigger('cp-image-remove', [name,value] );
		jQuery("#"+id).trigger('change');
	});

	jQuery('.smile-default-media').on('click', function(e){

		e.preventDefault();
		var button = jQuery(this);
		var id = button.attr('id').replace("default_","smile_");
		var upload = button.attr('id').replace("default_","");
		var img_container = button.attr('id').replace("default_","")+'_container';
		var container = jQuery(this).parents('.content');
		var default_img = jQuery(this).data('default');
		jQuery("#"+id).attr('value',default_img);

		//  Partial Refresh
        //  -   Apply background, background-color, color etc.
        var a = jQuery("#"+id);
        var css_preview = a.attr('data-css-preview') || '';
        var selector    = a.attr('data-css-selector') || '';
        var property    = a.attr('data-css-property') || '';
        var unit        = a.attr('data-unit') || 'px';
        var value       = default_img; 	//	Empty the background image
        var name 		= jQuery("#"+id).attr('name');
        partial_refresh_image( css_preview, selector, property, unit, value );

		var html = '<p class="description">No Image Selected</p>';
		jQuery("."+img_container).html('<img src="'+default_img+'"/>');

		jQuery(document).trigger('cp-image-default', [name,value] );
		jQuery("#"+id).trigger('change');
		container.find(".cp-media-sizes").hide().addClass('hide-for-default');
	});

	jQuery(".cp-media-size").on("change", function(e){

		var img_id = jQuery(this).attr('data-id');
		var alt = jQuery(this).attr('data-alt');
		var input = 'smile_'+jQuery(this).parents('.cp-media-sizes').data('name');
		var val = "";
		if( img_id !== '' ) {
			val = img_id+"|"+jQuery(this).val();
		}
		if( alt !== '' ) {
			val = val+"|"+alt;
		}
		jQuery("#"+input).val(val);
		jQuery("#"+input).attr('value',val);
	});

	function partial_refresh_image( css_preview, selector, property, unit, value ) {

        //  apply css by - inline
        if( css_preview != 1 || null == css_preview || 'undefined' == css_preview ) {

        	var frame = jQuery("#smile_design_iframe").contents();

        	switch( property ) {
        		case 'src': 		frame.find( selector ).attr( 'src' , value );
        			break;
        		default:
            						frame.find( selector ).css( property , 'url(' + value + ')' );
        			break;
        	}
        }
        //  apply css by - after css generation
        jQuery(document).trigger('updated', [css_preview, selector, property, value, unit]);
    }
});
