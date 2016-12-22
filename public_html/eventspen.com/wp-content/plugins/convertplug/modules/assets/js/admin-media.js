(function($)
{
	"use strict";
	// Uploading files
	var file_frame, attachment;
	_wpPluploadSettings['defaults']['multipart_params']['admin_page']= 'import';
  jQuery('.cp-import-style').on('click', function( event ){

    event.preventDefault();

	var module = jQuery(this).data("module");

    // If the media frame already exists, reopen it.
    if ( file_frame ) {
      file_frame.open();
      return;
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: jQuery( this ).data( 'uploader_title' ),
      button: {
        text: jQuery( this ).data( 'uploader_button_text' ),
      },
	  library: {
		  type: 'application/zip'
	  },
      multiple: false  // Set to true to allow multiple files to be selected
    });

    // When the file is selected, run a callback.
    file_frame.on( 'select', function() {
      // We set multiple to false so only get one file from the uploader
      attachment = file_frame.state().get('selection').first().toJSON();
	  var file = attachment;
	  var loader = jQuery(".cp-loader.spinner");
	  var msg = jQuery(".message");
	  loader.css('visibility','visible');
	  console.log(file);
	  var data = {action:'cp_import_'+module,file:file, module:module}
	  jQuery.ajax({
		  url:ajaxurl,
		  data: data,
		  type: "POST",
		  dataType: "JSON",
		  success: function(result){
			  console.log(result);
			  loader.css('visibility','hidden');
			  var status = result.status;
			  var desc	 = result.description;
			  if( status == "error" ){
				swal("Error!", desc, "error");
			  } else {
				swal("Imported!", desc, "success");
			  }
			  setTimeout( function(){
			  	window.location = window.location;
			  },1000);
		  }
	  });
    });

    // Finally, open the modal
    file_frame.open();
  });
})(jQuery);
