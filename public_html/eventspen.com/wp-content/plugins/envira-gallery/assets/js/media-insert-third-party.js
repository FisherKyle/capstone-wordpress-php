/**
* Creates and handles wp.media views and a controller for Envira Gallery Addons
* which import images from third party data sources (such as Dropbox)
*
* @since 1.4.3.0
*/

/**
* View: Error
* - Renders a WordPress style error message when something goes wrong.
*
* @since 1.4.3.0
*/
wp.media.view.EnviraGalleryError = wp.Backbone.View.extend( {

	// The outer tag and class name to use. The item is wrapped in this
    tagName   : 'div',
    className : 'notice error envira-gallery-error',

    render: function() {

    	// Load the template to render
    	// See includes/admin/media-views.php
    	this.template = wp.media.template( 'envira-gallery-error' );

    	// Define the HTML for the template
       	this.$el.html( this.template( this.model ) );

       	// Return the template
        return this;

    }

} );

/**
* View: Item
* - Renders an individual Folder or Image within an unordered attachment list
*
* @since 1.4.3.0
*/
wp.media.view.EnviraGalleryItem = wp.Backbone.View.extend( {

	// The outer tag and class name to use. The item is wrapped in this
    tagName   : 'li',
    className : 'attachment envira-gallery-item',

    render: function() {

    	// Load the template to render
    	// See includes/admin/media-views.php
    	this.template = wp.media.template( 'envira-gallery-item' );

    	// Define the HTML for the template
       	this.$el.html( this.template( this.model.toJSON() ) );

       	// Return the template
        return this;

    }

} );

/**
* View: Bottom Toolbar
* - Renders the bottom toolbar, which displays the "Insert into Gallery" button
* - Conditionally enables / disables the button depending on whether any images
* have been selected
*
* @since 1.4.3.0
*/
wp.media.view.Toolbar.EnviraGalleryToolbar = wp.media.view.Toolbar.extend( {

	/**
	* Initialize
	*/
	initialize: function() {
		_.defaults( this.options, {
		    event: 'envira_gallery_insert',
		    close: false,
			items: {
				/**
				* Insert Button
				*/
			    envira_gallery_insert: {
			    	id: 'envira-gallery-insert-button',
			    	style: 'primary',
			        text: wp.media.view.l10n.insertIntoPost, // Will read "Insert into Gallery", as we modify this in Envira Gallery metaboxes.php::media_view_strings()
			        priority: 80,
			        requires: false,

			        /**
			        * On Click
			        */
			        click: function() {
			        	// Run the controller's main action to import the selected image(s) into the Gallery
			        	this.controller.state().insert();
			        }
			    }
			}
		} );

		// Initialize the bottom toolbar
		wp.media.view.Toolbar.prototype.initialize.apply( this, arguments );

	},

    /**
    * Refreshes the toolbar items (i.e. button) enable/disabled state, depending on whether any items were selected
    * Fired by the main controller when an item is selected or deselected
    */
	refresh: function() {

		// Get selected item(s)
		var selection = this.controller.state().props;
		
		// Disable the Insert into Gallery Button if nothing was selected
		this.get( 'envira_gallery_insert' ).model.set( 'disabled', ! selection.length );

		// Apply the refresh
		wp.media.view.Toolbar.prototype.refresh.apply( this, arguments );

	}

} );

/**
* View: Media Content Area
* - Renders the main modal content area, including other views.
*
* @since 1.4.3.0
*/
wp.media.view.EnviraGalleryView = wp.media.View.extend( {

	/**
	 * The CSS class(es) to apply to the modal
	 * These ensure that the modal uses the WordPress grid style
	 *
	 * @var string
	 */
	className: 'attachments-browser envira-gallery',

	/**
	 * The path to browse on the third party data source
	 *
	 * @var string
	 */
	path: '/',

	/**
	 * Whether the view has requested to load some data
	 *
	 * @var bool
	 */
	is_loading: false,

	/**
	* Defines events to watch
	*
	* @var object
	*/
	events: {
		// Clicked an item
		'click .envira-gallery-item': 		'click',

		// Used the search input
		'keyup': 							'search',
		'search': 							'search',
	},

	/**
	* Defines the registered WordPress AJAX action to call when getting files and folders
	* This must be defined by the Addon e.g:
	* 	new wp.media.view.EnviraGalleryView( {
	*		get_action: 'envira_google_drive_importer_get_files_folders',
	*		...
    * 	} );
    *
    * @var string
    */
    get_action: '',

    /**
	* Defines the registered WordPress AJAX action to call when searching files and folders
	* This must be defined by the Addon e.g:
	* 	new wp.media.view.EnviraGalleryView( {
	*		search_action: 'envira_google_drive_importer_search_files_folders',
	*		...
    * 	} );
    *
    * @var string
    */
    search_action: '',

	/**
	* Initialize
	* - Fired when the main UI view is loaded
	*/
	initialize: function( options ) {

		// Define a collection, which will store the items (folders and images)
		this.collection = new Backbone.Collection();

		// Prepend the Search Bar to this view
		this.$el.prepend( wp.media.template( 'envira-gallery-search-bar' ) );

		// Prepend the Addon's Sidebar to this view
		// This should be defined in the addon's includes/admin/media-view.php, using 
		// <script id="tmpl-name">, where name is defined in options.sidebar_template
		// by the Addon's wp.media.view.MediaFrame.Post instance
		this.$el.prepend( wp.media.template( options.sidebar_template ) );

		// Prepend the Items Area to this view
		this.$el.prepend( wp.media.template( 'envira-gallery-items' ) );

		// Define loading and loaded events
		this.on( 'loading', this.loading, this );
		this.on( 'loaded',  this.loaded, this );

		// Define the get_action and search_action endpoints
		this.get_action = options.get_action;
		this.search_action = options.search_action;

		// Define the base path
		this.path = options.path;

		// Get items
		this.getItems( false, '' );

	},

	/**
	 * Called when the click event is fired (a file or folder is clicked)
	 *
	 * @param object 	event 	Event
	 */
	click: function( event ) {

		// Get the target element, whether it's a directory and its ID
		var target 	= jQuery( event.currentTarget ),
			is_dir 	= jQuery( 'div.attachment-preview', target ).attr( 'data-is-dir' ),
			id 		= jQuery( 'div.attachment-preview', target ).attr( 'data-id' );

		// Determine whether we clicked a folder or a file
		if ( is_dir == 'true' ) {
			// This is a folder, so update the path to match the item's ID and get items
			this.path = id;
			this.getItems( false, '' );
		} else {
			// Add or remove item from the selection, depending on its current state
			if ( target.hasClass( 'selected' ) ) {
				// Remove
				this.removeFromSelection( target, id );
			} else {
				// Add
				this.addToSelection( target, id );
			}
		}

	},

	/**
	 * Called when the search event is fired (the user types into the search field)
	 *
	 * @param object 	event 	Event
	 */
	search: function( event ) {

		// If we're already loading something, bail
		if ( this.is_loading ) {
			return;
		}

		// Check if a search term exists, and is at least 3 characters
		var search = event.target.value;

		// If search is empty, return the entire folder's contents
		if ( search.length == 0 ) {
			this.getItems( false, '' );
			return;
		}

		// If search isn't empty but less than 3 characters, don't do anything
		if ( search.length < 3 ) {
			return;
		}

		// Pass on the request to the main getItems() function
		this.getItems( true, search );

	},

	/**
	* Displays the loading spinner
	*/
	loading: function() {

		// Set a flag so we know we're loading data
		this.is_loading = true;

		// Show the spinner
		this.$el.find( '.spinner' ).addClass( 'is-active' );

	},

	/**
	* Hides the loading spinner, and optionally displays an error if there's some response data
	*/
	loaded: function( response ) {

		// Set a flag so we know we're not loading anything now
		this.is_loading = false;

		// Hide the spinner
		this.$el.find( '.spinner' ).removeClass( 'is-active' );

		// Remove any existing errors
		this.$el.find( 'div.envira-gallery-error' ).remove();

		// Display the error message, if it's provided
		if ( typeof response !== 'undefined' ) {
			this.$el.find( 'div.media-toolbar' ).after( this.renderError( response ) );
		}

		// Update toolbar
		this.controller.toolbar.get().refresh();

	},

	/**
	* Clears items from the view
	*/
	clearItems: function() {

		this.$el.find( 'ul.envira-gallery-attachments li.attachment.selected' ).removeClass( 'selected details' );
		this.$el.find( 'ul.envira-gallery-attachments' ).empty();

	},

	/**
	* Gets folders and images from the remote source by sending an AJAX request
	*
	* @param 	bool 	is_search 		Is a search request
	* @param 	string 	search_terms 	Search Terms
	*/
	getItems: function( is_search, search_terms ) {

		// If we're already loading something, bail
		if ( this.is_loading ) {
			return;
		}

		// Update the loading flag
		this.trigger( 'loading' );

		// Clear the view
		this.clearItems();

		// Depending on whether this is a search request or not, build the action and data vars
		var action = ( is_search ? this.search_action : this.get_action ),
			data = {
				nonce: 	envira_gallery_media_insert.nonce,
				path: 	this.path
			};
		if ( is_search ) {
			data.search = search_terms
		}
				
		wp.media.ajax( action, {
			context: this,
			data: data,
			success: function( items ) {
				// Define a collection
				var collection = new Backbone.Collection( items );
				
                // Reset the collection
                this.collection.reset();
                
				// Add the collection's models (items) to this class' collection
				this.collection.add( collection.models );

				// Iterate through each item, adding it to the list of items
				this.collection.each( function( model ) {
					// Append the rendered item to the container view
					this.$el.find( 'ul.envira-gallery-attachments' ).append( this.renderItem( model ) );
				}, this );

				// Tell wp.media we've finished loading items
				this.trigger( 'loaded' );
			},
			error: function( error_message ) {
				// Tell wp.media we've finished loading items, and send the error message
				// for output
				this.trigger( 'loaded', error_message );
			}
		} );

	},

	/**
	* Renders an individual item (folder / image) using the
	* wp.media.view.EnviraGalleryItem view
	*/
	renderItem: function( model ) {

		var view = new wp.media.view.EnviraGalleryItem( {
			model   : model,
		} );

		return view.render().el;

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
	* Adds the given target to the selection
	*
	* @param 	object 	target 	Selected Element
	* @param 	string 	id 		Unique Identifier (i.e. third party API item's UID)
	*/
	addToSelection: function( target, id ) {

		// Trigger the loading event
		this.trigger( 'loading' );

		// Iterate through the current collection of models until we find the model
		// that has a path matching the given path
		this.collection.each( function( model ) {
			// If this model matches the model the user selected, add it to the selection
			if ( model.get( 'id' ) == id ) {
				this.getSelection().add( model );
			}
		}, this );

		// Mark the item as selected in the media view
		target.addClass( 'selected details' );

		// Trigger the loaded event
		this.trigger( 'loaded' );

	},

	/**
	* Removes the given target from the selection
	*
	* @param 	object 	target 	Deselected Element
	* @param 	string 	id 		Unique Identifier (i.e. third party API item's UID)
	*/
	removeFromSelection: function( target, id ) {

		// Trigger the loading event
		this.trigger( 'loading' );

		// Iterate through the current collection of selected models until we find the model
		// that has a path matching the given path
		this.getSelection().each( function( model ) {
			// remove this model from the collection of selected models
			this.getSelection().remove([{ cid: model.cid }]);
			// this.controller.state().props.remove([{ cid: model.cid }]);
		}, this );

		// Mark the item as deselected in the media view
		target.removeClass( 'selected details' );

		// Trigger the loaded event
		this.trigger( 'loaded' );

	},

	/**
	* Get the selected items
	*/
	getSelection: function() {

		return this.controller.state().props;

	},

	/**
	* Clears all selected items
	*/
	clearSelection: function() {

		// Get selection
		this.selection = this.getSelection();

		// Iterate through each item, removing the selected state from the UI
		this.selection.each( function( model ) {
			this.$el.find( 'div[data-id="' + model.get( 'id' ) + '"]' ).parent().removeClass( 'selected details' );
		}, this );

		// Clear the selected models
		this.selection.reset();

	}

} );

/**
* Controller
*/
wp.media.controller.EnviraGalleryController = wp.media.controller.State.extend( {

	/**
	* Defines the registered WordPress AJAX action to call when inserting images into the Gallery
	* This must be defined by the Addon e.g:
	* 	new wp.media.view.EnviraGalleryController( {
	*		insert_action: 'envira_google_drive_importer_insert_images',
	*		...
    * 	} );
    *
    * @var string
    */
    insert_action: '',

	/**
	* Init
	*/
    initialize: function( options ) {

        this.props = new Backbone.Collection();

        // Store actions
        this.insert_action = options.insert_action;

    },

    /**
	* Called when the Insert button is clicked
	*/
	insert: function() {

		// Get selected images
		var frame 		= this.frame.content.get(),
			images	  	= [];

		// Get toolbar button
		this.button = this.frame.toolbar.get().get( 'envira_gallery_insert' );

		// Disable button and change label
		this.button.model.set( 'text', wp.media.view.l10n.inserting );
		this.button.model.set( 'disabled', true );

		// Tell wp.media we're loading items
		this.trigger( 'loading' );

		// Build an array of each image
		frame.getSelection().each( function( model ) {
			images.push( model.get( 'id' ) );
		}, this );

		// Make an AJAX request to import these items into the gallery
		wp.media.ajax( this.insert_action, {
			context: this,
			data: {
				nonce: 	 envira_gallery_media_insert.nonce,
				post_id: envira_gallery_media_insert.post_id,
				images:  images
			},
			success: function( response ) {
				// Set the image grid to the HTML we received
                jQuery( 'ul#envira-gallery-output' ).html( response );

                // Tell wp.media we've finished
				this.trigger( 'loaded' );

				// Revert the button back to its original state
				this.button.model.set( 'text', wp.media.view.l10n.insertIntoPost );
				this.button.model.set( 'disabled', false );

				// Reset the selection
				frame.clearSelection();

				// Close the modal
				this.frame.close();
			},
			error: function( error_message ) {
				// Revert the button back to its original state
				this.button.model.set( 'text', wp.media.view.l10n.insertIntoPost );
				this.button.model.set( 'disabled', false );

				// Tell wp.media we've finished, but there was an error 
				frame.trigger( 'loaded', error_message );
			}
		} );

	}

} );

/**
* Loads Addons when the "Insert Images from Other Sources" button is clicked
*/
var EnviraGalleryPostFrame = wp.media.view.MediaFrame.Post;
wp.media.view.MediaFrame.Post = EnviraGalleryPostFrame.extend({

	/** 
	* Init
	*/
	initialize: function() {

		EnviraGalleryPostFrame.prototype.initialize.apply( this, arguments );

		// Iterate through each of the Addons that were added via localization, creating a new controller
		// for each.
		// This ensures that Addons don't clash with each other
		_.each( envira_gallery_media_insert.addons, function( addon_action_base, addon_slug ) {

			// Register the controller for this Addon
			this.states.add( [
	            new wp.media.controller.EnviraGalleryController( {
	                id:         addon_slug,
	                content: 	addon_slug + '-content',
	                toolbar: 	addon_slug + '-toolbar',
	                menu:       'default',
	                title:      wp.media.view.l10n[ addon_slug ],
					priority:   200,
					type: 		'link',
					insert_action: addon_action_base + '_insert_images'
	            } )
	        ] );

	        // Main UI (where attachments are displayed)
        	this.on( 'content:render:' + addon_slug + '-content', _.bind( this.renderContent, this, addon_slug, addon_action_base ) );

        	// Bottom Toolbar (where the selected items and button are displayed)
        	this.on( 'toolbar:create:' + addon_slug + '-toolbar', this.renderToolbar, this );
		}, this );

	},

	/**
	* Content Area
	*/
	renderContent: function( addon_slug, addon_action_base ) {

        this.content.set( new wp.media.view.EnviraGalleryView( {
            controller: 		this,
            model: 				this.state().props,
            addon: 				addon_slug,
            sidebar_template: 	addon_slug + '-side-bar',
            get_action: 		addon_action_base + '_get_files_folders',
            search_action: 		addon_action_base + '_search_files_folders',
            insert_action: 		addon_action_base + '_insert_images',
            path: 				'/'
        } ) );

	},

	/**
	* Toolbar Area
	*/
	renderToolbar: function( toolbar ) {

		toolbar.view = new wp.media.view.Toolbar.EnviraGalleryToolbar( {
			controller: this
		} );

	},

} );

/**
* Useful to output all events that are triggered by wp.media
var originalTrigger = wp.media.view.MediaFrame.Post.prototype.trigger; 
wp.media.view.MediaFrame.Post.prototype.trigger = function(){ 
	console.log('Event Triggered:', arguments); 
	originalTrigger.apply(this, Array.prototype.slice.call(arguments)); 
}
*/