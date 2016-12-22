/**
* Single Image View
* - Renders an <li> element within the bulk edit view
*/
var EnviraGalleryBulkEditImageView = wp.Backbone.View.extend( {

	/**
    * The Tag Name and Tag's Class(es)
    */
    tagName:    'li',
    className:  'attachment',

    /**
    * Template
    * - The template to load inside the above tagName element
    */
    template:   wp.template( 'envira-meta-bulk-editor-image' ),

    /**
    * Initialize
    *
    * @param object model   EnviraGalleryImage Backbone Model
    */
    initialize: function( args ) {

    	// Assign the model to this view
        this.model = args.model;

    },

    /**
    * Render
    * - Binds the model to the view, so we populate the view's fields and data
    */
    render: function() {

        // Get HTML
        this.$el.html( this.template( this.model.attributes ) );
        return this;

    }

} );

/**
* Bulk Edit View
*/
var EnviraGalleryBulkEditView = wp.Backbone.View.extend( {

    /**
    * The Tag Name and Tag's Class(es)
    */
    tagName:    'div',
    className:  'edit-attachment-frame mode-select hide-menu hide-router',

    /**
    * Template
    * - The template to load inside the above tagName element
    */
    template:   wp.template( 'envira-meta-bulk-editor' ),

    /**
    * Events
    * - Functions to call when specific events occur
    */
    events: {
        'keyup input':                                  'updateItem',
        'keyup textarea':                               'updateItem',
        'change input':                                 'updateItem',
        'change textarea':                              'updateItem',
        'blur textarea':                                'updateItem',
        'change select':                                'updateItem',

        'click .actions a.envira-gallery-meta-submit':  'saveItem',

        'keyup input#link-search':                      'searchLinks',
        'click div.query-results li':                   'insertLink',

        'click button.media-file':                      'insertMediaFileLink',
        'click button.attachment-page':                 'insertAttachmentPageLink',
    },

    /**
    * Initialize
    *
    * @param object model   EnviraGalleryImage Backbone Model
    */
    initialize: function( args ) {

        // Define loading and loaded events, which update the UI with what's happening.
        this.on( 'loading', this.loading, this );
        this.on( 'loaded',  this.loaded, this );

        // Set some flags
        this.is_loading = false;
        this.collection = args.collection;
        this.child_views = args.child_views;

        // The model will be blank, as we want the user's settings for each
        // option to then apply to the entire collection
        this.model = new EnviraGalleryImage();

    },

    /**
    * Render
    * - Binds the collection to the view, so we populate the view's attachments grid
    */
    render: function() {

        // Get HTML
        this.$el.html( this.template( this.model.toJSON() ) );

        // Render selected items
        this.collection.forEach( function( model ) {
			// Init with model
            var child_view = new EnviraGalleryBulkEditImageView( {
                model: model
            } );

            // Render view within our main view
            this.$el.find( 'ul.attachments' ).append( child_view.render().el );
        }, this );

        // If any child views exist, render them now
        if ( this.child_views.length > 0 ) {
            this.child_views.forEach( function( view ) {
                // Init with model
                var child_view = new view( {
                    model: this.model
                } );

                // Render view within our main view
                this.$el.find( 'div.addons' ).append( child_view.render().el );
            }, this );
        }

        // Init QuickTags on the caption editor
        // Delay is required for the first load for some reason
        setTimeout( function() {
            quicktags( {
                id:     'caption',
                buttons:'strong,em,link,ul,ol,li,close'
            } );
            QTags._buttonsInit();
        }, 500 );

        // Init Link Searching
        wpLink.init;

        // Return
        return this;

    },

    /**
    * Renders an error using
    * wp.media.view.EnviraGalleryError
    */
    renderError: function( error ) {

        // Define model
        var model = {};
        model.error = error;

        // Define view
        var view = new wp.media.view.EnviraGalleryError( {
            model: model
        } );

        // Return rendered view
        return view.render().el;

    },

    /**
    * Tells the view we're loading by displaying a spinner
    */
    loading: function() {

        // Set a flag so we know we're loading data
        this.is_loading = true;

        // Show the spinner
        this.$el.find( '.spinner' ).css( 'visibility', 'visible' );

    },

    /**
    * Hides the loading spinner
    */
    loaded: function( response ) {

        // Set a flag so we know we're not loading anything now
        this.is_loading = false;

        // Hide the spinner
        this.$el.find( '.spinner' ).css( 'visibility', 'hidden' );

        // Display the error message, if it's provided
        if ( typeof response !== 'undefined' ) {
            this.$el.find( 'ul.attachments' ).before( this.renderError( response ) );
        }

    },

    /**
    * Updates the model based on the changed view data
    */
    updateItem: function( event ) {

        // Check if the target has a name. If not, it's not a model value we want to store
        if ( event.target.name == '' ) {
            return;
        }

        // Update the model's value, depending on the input type
        if ( event.target.type == 'checkbox' ) {
            value = ( event.target.checked ? 1 : 0 );
        } else {
            value = event.target.value;
        }

        // Update the model
        this.model.set( event.target.name, value );

    },

    /**
    * Saves the image metadata
    */
    saveItem: function( event ) {

	    event.preventDefault();

        // Tell the View we're loading
        this.trigger( 'loading' );

    	// Build an array of image IDs
    	var image_ids = [];
    	this.collection.forEach( function( model ) {
			image_ids.push( model.id );
        }, this );

        // Make an AJAX request to save the image metadata for the collection's images
        wp.media.ajax( 'envira_gallery_save_bulk_meta', {
            context: this,
            data: {
                nonce:     envira_gallery_metabox.save_nonce,
                post_id:   envira_gallery_metabox.id,
                meta:      this.model.attributes,
                image_ids: image_ids,
            },
            success: function( response ) {

                // For each image, update the model based on the edited information before inserting it as JSON
                // into the underlying image.
                this.collection.forEach( function( model ) {

                    for ( var key in this.model.attributes ) {
                        value = this.model.attributes[ key ];

                        // If the value is not blank, assign the value to the image model
                        if ( value.length > 0 ) {
                            model.set( key, value );
                        }
                    }

                    // Assign the model to the underlying image item in the DOM
                    var item = JSON.stringify( model.attributes );
                    jQuery( 'ul#envira-gallery-output li#' + model.get( 'id' ) ).attr( 'data-envira-gallery-image-model', item );
                    jQuery( 'ul#envira-gallery-output li#' + model.get( 'id' ) + ' div.title' ).text( model.get( 'title' ) );

                }, this );

                // Deselect all images by triggering the change event on the 'Select All' checkbox
                jQuery( 'nav.envira-tab-options input[type=checkbox]' ).prop( 'checked', false ).trigger( 'change' );

                // Tell the view we've finished successfully
                this.trigger( 'loaded loaded:success' );

                // Close the modal
                EnviraGalleryModalWindow.close();

            },
            error: function( error_message ) {

                // Tell wp.media we've finished, but there was an error
                this.trigger( 'loaded loaded:error', error_message );

            }
        } );

    },

    /**
    * Inserts the direct media link for the Media Library item
    *
    * The button triggering this event is only displayed if we are editing a
    * Media Library item, so there's no need to perform further checks
    */
    insertMediaFileLink: function( event ) {

        // Tell the View we're loading
        this.trigger( 'loading' );

        // Update model
		this.model.set( 'link', response.media_link );

		// Tell the view we've finished successfully
		this.trigger( 'loaded loaded:success' );

		// Re-render the view
        this.render();

    },

    /**
    * Inserts the attachment page link for the Media Library item
    *
    * The button triggering this event is only displayed if we are editing a
    * Media Library item, so there's no need to perform further checks
    */
    insertAttachmentPageLink: function( event ) {

        // Tell the View we're loading
        this.trigger( 'loading' );

        // Update model
		this.model.set( 'link', response.media_link );

		// Tell the view we've finished successfully
		this.trigger( 'loaded loaded:success' );

		// Re-render the view
        this.render();

    }

} );

jQuery( document ).ready( function( $ ) {

	// Edit Images
    $( '#envira-gallery-main' ).on( 'click', 'a.envira-gallery-images-edit', function( e ) {

        // Prevent default action
        e.preventDefault();

        // (Re)populate the collection
        // The collection can change based on whether the user previously selected specific images
        EnviraGalleryImagesUpdate( true ); // true = only selected images

        // Pass the collection of images for this gallery to the modal view, as well
        // as the selected attachment
        EnviraGalleryModalWindow.content( new EnviraGalleryBulkEditView( {
            collection:     EnviraGalleryImages,
            child_views:    EnviraGalleryChildViews,
        } ) );

        // Open the modal window
        EnviraGalleryModalWindow.open();

    } );

} );