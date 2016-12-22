/**
* Handles moving media from the on-screen Gallery to another Gallery,
* by displaying the gallery-select.js Backbone Modal and running
* the necessary AJAX command once the user has chosen a Gallery and
* clicked the Move button
*
* @since 1.5.0.3
*/
jQuery( document ).ready( function( $ ) {
	
	// Edit Images
    $( '#envira-gallery-main' ).on( 'click', 'a.envira-gallery-images-move', function( e ) {

        // Prevent default action
        e.preventDefault();

        // Get the action
        var action = $( this ).data( 'action' );

        // Define the modal's view
        EnviraGalleryModalWindow.content( new EnviraGallerySelectionView( {
            action:             action,     // gallery|album
            multiple:           false,      // Allow multiple Galleries / Albums to be selected
            sidebar_view:       'envira-meta-move-media-sidebar',
            modal_title:        envira_gallery_metabox.move_media_modal_title,
            insert_button_label:envira_gallery_metabox.move_media_insert_button_label,
            onInsert:           function() {
                // Refresh the underlying collection of selected images now
                EnviraGalleryImagesUpdate( true ); // true = only selected images
                
                // Build array of images
                var envira_gallery_move_image_ids = [];
                EnviraGalleryImages.forEach( function( image ) {
                    envira_gallery_move_image_ids.push( image.get( 'id' ) );
                } );

                // Get the chosen Gallery
                // This forEach loop will only run once, as we only allow the user
                // to select a single gallery.
                this.selection.forEach( function( gallery ) {

                    // Perform AJAX request to move the given images from this gallery
                    // to the selected gallery.
                    // Action will be either:
                    // envira_gallery_move_media
                    // envira_albums_move_media
                    wp.media.ajax( 'envira_' + action + '_move_media', {
                        context: this,
                        data: {
                            nonce:          envira_gallery_metabox.move_media_nonce,
                            from_gallery_id:envira_gallery_metabox.id,
                            to_gallery_id:  gallery.id,
                            image_ids:      envira_gallery_move_image_ids,
                        },
                        success: function( response ) {

                            // Remove each image from this Gallery, as the move was successful.
                            $( 'ul#envira-gallery-output > li.selected' ).remove();

                            // Hide Select Options
                            $( 'nav.envira-select-options' ).fadeOut();

                            // Repopulate the Envira Gallery Image Collection
                            EnviraGalleryImagesUpdate( false );

                            // Close the modal
                            EnviraGalleryModalWindow.close();

                        },
                        error: function( error_message ) {
                            alert( error_message );

                        }
                    } );
                } );
            }
        } ) );

        // Open the modal window
        EnviraGalleryModalWindow.open();

    } );

} );