/**
* You'll need to use CodeKit or similar, as this file is a placeholder to combine
* the following JS files into min/metabox-min.js:
*/
// @codekit-append "gallery-preview.js";
// @codekit-append "gallery-types.js";
// @codekit-append "gallery-help.js";
// @codekit-append "media-bulk-edit.js";
// @codekit-append "media-edit.js";
// @codekit-append "media-insert.js";
// @codekit-append "media-manage.js";
// @codekit-append "media-move.js";
// @codekit-append "media-upload.js";

// "media-delete.js";

jQuery( document ).ready( function( $ ) {

	// Image Size: Random
	// conditional-fields doesn't support multiple conditions, so we manually show/hide
	// the Random Image Sizes option depending on the Image Size value
	$( 'select[name="_envira_gallery[image_size]"]' ).on( 'change', function() {

		if ( $( this ).val() === 'envira_gallery_random' ) {
			$( 'tr#envira-config-image-sizes-random-box' ).show();
		} else {
			$( 'tr#envira-config-image-sizes-random-box' ).hide();
		}

	} );

	// Run the above conditions on load.
	$( 'select[name="_envira_gallery[image_size]"]' ).trigger( 'change' );

} );