/**
 * Handles changing Gallery Types, for example from Default to Instagram
 */
;( function( $ ) {
    $( function() {

        // Change the radio checked option and fire the change event when a Gallery Type is clicked
        $( '#envira-gallery-types-nav' ).on( 'click', 'li', function( e ) {

            $( 'input[name="_envira_gallery[type]"]', $( this ) ).prop( 'checked', true ).trigger( 'change' );

        });

        // Retrieve the settings HTML when the Gallery Type is changed, so the relevent options are displayed
        $( document ).on( 'change', 'input[name="_envira_gallery[type]"]:radio', function( e ) {

            // Setup some vars
            var envira_gallery_type     = $( this ).val(),
                envira_spinner          = $( '#envira-tabs #envira-tab-images .spinner' ),
                envira_tab_settings     = $( '#envira-tabs #envira-tab-images #envira-gallery-main' );

            // Display the spinner, so the user knows something is happening
            $( envira_spinner ).css( 'visibility', 'visible' );

            // Remove the envira-active class from all Gallery Types
            $( 'li', $( this ).closest( '#envira-gallery-types-nav' ) ).removeClass( 'envira-active' );

            // Add the envira-active class to the chosen Gallery Type
            $( this ).closest( 'li' ).addClass( 'envira-active' );
            
            // Switch the Settings Metabox to the first tab (Images)
            $( 'a', $( '#envira-tabs-nav li' ).first() ).trigger( 'click' );

            // Remove the content from the now displayed tab settings
            $( envira_tab_settings ).html( '' );
            
            // Make an AJAX call to get the content for the tab
            $.ajax( {
                type:       'post',
                url:        envira_gallery_metabox.ajax,
                dataType:   'json',
                data: {
                    action:  'envira_gallery_change_type',
                    post_id: envira_gallery_metabox.id,
                    type:    envira_gallery_type,
                    nonce:   envira_gallery_metabox.change_nonce
                },
                success: function ( response ) {

                    // Inject the response into the tab settings area
                    $( envira_tab_settings ).html( response.html );

                    // Fire an event to tell Addons that the Gallery Type has changed.
                    // (e.g. Featured Content Addon uses this to initialize some JS with the DOM).
                    $( document ).trigger( 'enviraGalleryType', response );

                    // Hide the spinner
                    $( envira_spinner ).hide();
                    
                },
                error: function ( textStatus, errorThrown ) {

                    // Inject the error message into the tab settings area
                    $( envira_tab_settings ).html( '<div class="error"><p>' + textStatus.responseText + '</p></div>' );

                    // Hide the spinner
                    $( envira_spinner ).hide();

                }
            } );

        } );  

    } );
} ( jQuery ) );   