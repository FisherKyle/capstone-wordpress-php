/**
 * Handles retrieving and outputting images for the Gallery Preview metabox
 *
 * @since 1.5.0
 */
;( function( $ ) {
    $( function() {

        // Setup vars
        var envira_preview_updating = false;

        // Show or hide the Preview metabox, depending on the Gallery Type
        if ( $( 'input[name="_envira_gallery[type]"]:checked' ).val() == 'default' ) {
            $( '#envira-gallery-preview' ).hide();
        } else {
            $( '#envira-gallery-preview' ).show();
        }

        // Show or hide the Preview metabox, when the Gallery Type is changed, or the enviraGalleryPreview
        // action is fired.
        $( document ).on( 'enviraGalleryType enviraGalleryPreview', function() {

            // Setup some vars
            var envira_gallery_type     = $( 'input[name="_envira_gallery[type]"]:checked' ).val(),
                envira_spinner          = $( '#envira-gallery-preview .spinner' ),
                envira_gallery_preview  = $( '#envira-gallery-preview-main' );

            // If the gallery type is default, hide the preview and return.
            if ( envira_gallery_type == 'default' ) {
                $( envira_gallery_preview ).hide();
                return;
            }

            // If the preview is still updating from a previous AJAX call, don't do anything else.
            if ( envira_preview_updating ) {
                return;
            }

            // Update the flag to indicate we're running an AJAX request.
            envira_preview_updating = true;

            // Remove the content from the preview
            $( envira_gallery_preview ).html( '' );

            // Make an AJAX call to get the content for the tab
            $.ajax( {
                type:       'post',
                url:        envira_gallery_metabox.ajax,
                dataType:   'json',
                data: {
                    action:  'envira_gallery_change_preview',
                    post_id: envira_gallery_metabox.id,
                    type:    envira_gallery_type,
                    data:    $( 'form#post' ).serializeArray(),
                    nonce:   envira_gallery_metabox.preview_nonce
                },
                success: function ( response ) {

                    // Inject the response into the preview area
                    $( envira_gallery_preview ).html( response );

                    // Hide the spinner
                    $( envira_spinner ).hide();

                    // We've finished updating the preview.
                    envira_preview_updating = false;
                    
                },
                error: function ( textStatus, errorThrown ) {

                    // Inject the error message into the tab settings area
                    $( envira_gallery_preview ).html( '<div class="error"><p>' + textStatus.responseText + '</p></div>' );

                    // Hide the spinner
                    $( envira_spinner ).hide();

                    // We've finished updating the preview.
                    envira_preview_updating = false;

                }
            } );

        } );

    } );
} ( jQuery ) );   