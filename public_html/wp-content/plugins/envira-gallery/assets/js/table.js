/**
 * Handles any Envira WP_List_Table events:
 * - Quick / Bulk Edit
 */
jQuery( document ).ready( function( $ ) {

    /**
	* Quick / Bulk Edit Support
	*/
	if ( typeof inlineEditPost !== 'undefined' ) {
		// we create a copy of the WP inline edit post function
		var wp_inline_edit = inlineEditPost.edit;

		// and then we overwrite the function with our own code
		inlineEditPost.edit = function( id ) {

			// "call" the original WP edit function
			// we don't want to leave WordPress hanging
			wp_inline_edit.apply( this, arguments );

			// get the post ID
			var post_id = 0;
			if ( typeof( id ) == 'object' ) {
				post_id = parseInt( this.getId( id ) );
			}

			if ( post_id > 0 ) {
				// Get the Edit and Post Row Elements
				var edit_row = $( '#edit-' + post_id );
				var post_row = $( '#post-' + post_id );

				// Get Envira Gallery Settings
				// These are stored in hidden input fields created by includes/admin/posttype.php
				// We populate via JS because there's no $post object for us to access in includes/admin/common.php's quick edit functions
				var columns = $( 'input[name="_envira_gallery_' + post_id + '[columns]"]', $(post_row) ).val();
				var gallery_theme = $( 'input[name="_envira_gallery_' + post_id + '[gallery_theme]"]', $(post_row) ).val();
				var gutter = $( 'input[name="_envira_gallery_' + post_id + '[gutter]"]', $(post_row) ).val();
				var margin = $( 'input[name="_envira_gallery_' + post_id + '[margin]"]', $(post_row) ).val();
				var crop_width = $( 'input[name="_envira_gallery_' + post_id + '[crop_width]"]', $(post_row) ).val();
				var crop_height = $( 'input[name="_envira_gallery_' + post_id + '[crop_height]"]', $(post_row) ).val();

				// Populate Quick Edit Fields with data from the above hidden fields
				$( 'select[name="_envira_gallery[columns]"]', $(edit_row) ).val( columns );
				$( 'select[name="_envira_gallery[gallery_theme]"]', $(edit_row) ).val( gallery_theme );
				$( 'input[name="_envira_gallery[gutter]"]', $(edit_row) ).val( gutter );
				$( 'input[name="_envira_gallery[margin]"]', $(edit_row) ).val( margin );
				$( 'input[name="_envira_gallery[crop_width]"]', $(edit_row) ).val( crop_width );
				$( 'input[name="_envira_gallery[crop_height]"]', $(edit_row) ).val( crop_height );
			}
		};	

		// Remove all hidden inputs when a search is performed
		// This stops them from being included in the GET URL, otherwise we'd have a really long search URL
		// which breaks some nginx configurations
		$('form#posts-filter').on('submit', function(e) {
			$('input.envira-quick-edit').remove();
		})
	}

} );