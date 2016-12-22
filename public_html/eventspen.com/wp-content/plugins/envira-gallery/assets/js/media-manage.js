/* global envira_gallery_metabox, wp */
/**
* Handles Mangement functions, deselection and sorting of media in an Envira gallery
*/
var envira_manage = window.envira_manage || {};

;(function ( $, window, document, envira_manage, envira_gallery_metabox ) {

	"use strict";
	// Setup some vars
	var output 				= '#envira-gallery-output',
		list			 	= $( output + ' li' ).length,
		shift_key_pressed 	= false,
		last_selected_image = false;

	window.envira_manage = envira_manage = {

		init: function(){

			//Select Functions
			this.select();
			this.select_all();
			this.clear_selected();

			//Sortable
			this.sortable();
			//this.sort_images();

			//List/Grid Display
			this.display_toggle();

			//Items
			this.delete_item();
			this.bulk_delete();
			this.edit_meta();
			this.toggle_status();


			this.tooltip();

			// Determine whether the shift key is pressed or not
			$( document ).on( 'keyup keydown', function( e ) {
				shift_key_pressed = e.shiftKey;
			} );

			//Envira Admin Init Trigger
			$( document ).trigger('envriaAdminInit');

		},

		//Toggle Image States
		toggle_status: function(){

			$( output ).on( 'click.enviraStatus', '.envira-item-status', function( e ) {

				// Prevent default action
				   e.preventDefault();
				   e.stopPropagation();

				var $this		= $(this),
					$data 		= $this.data('status'),
					$parent		= $this.hasClass('list-status') ? $this.parent().parent().parent() : $this.parent(),
					$list_view 	= $parent.find('.envira-item-status.list-status'),
					$grid_view 	= $parent.find('.envira-item-status.grid-status'),
					id 			= $this.data('id'),
					$icon 		= $grid_view.find('span.dashicons'),
					$text 		= $list_view.find('span'),
					$status 	= $data === 'active' ? 'pending' : 'active',
					opts = {
						  url:		envira_gallery_metabox.ajax,
						  type:		'post',
						  async:	true,
						  cache:	false,
						  dataType: 'json',
						  data: {
							  action:  	'envira_change_image_status',
							  post_id: 	envira_gallery_metabox.id,
							  gallery_id: id,
							  status:		$status,
							  nonce:  	envira_gallery_metabox.save_nonce
						  },
						  success: function( response ) {

							if ( response.success ){

								if( $status === 'active' ){

								 	//Toggle Classes
									 $grid_view.removeClass('envira-draft-item').addClass('envira-active-item');
									 $list_view.removeClass('envira-draft-item').addClass('envira-active-item');

									//Set the proper icons
									 $icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');

									//Set the Text
								 	$text.text( envira_gallery_metabox.active );

	 								$grid_view.attr('data-envira-tooltip',  envira_gallery_metabox.active );

									 //Set the Data
									$list_view.data('status','active');
									$grid_view.data('status','active');

								}else{

									 //Toggle Classes
									 $grid_view.removeClass('envira-active-item').addClass('envira-draft-item');
									 $list_view.removeClass('envira-active-item').addClass('envira-draft-item');

									//Set the proper icons
									 $icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');

									//Set the text
									$text.text( envira_gallery_metabox.draft );
									 //Set the Data
									$list_view.data('status','pending');
									$grid_view.data('status','pending');
									$grid_view.attr('data-envira-tooltip',	 envira_gallery_metabox.draft );

								}

								$( document ).trigger( 'envriaChangeStatus ');

							}

						  },
						  error: function(xhr, textStatus ,e) {

							  return;
						  }
					  };

				  $.ajax( opts );


			});

		},

		//Simple Tooltip
		tooltip: function(){
			$('[data-envira-tooltip]').on( 'mouseover', function(e){
				e.preventDefault();
				var $this = $(this),
					$data = $this.data('envira-tooltip');


			});
		},

		//Select All images
		select_all: function(){

			// Toggle Select All / Deselect All
			$( document ).on( 'change', 'nav.envira-tab-options input', function( e ) {

				if ( $( this ).prop( 'checked' ) ) {
					$( 'li', $( output ) ).addClass( 'selected' );
					$( 'nav.envira-select-options' ).fadeIn();

					var selected = $( output + ' li.selected').length;
					$('.select-all').text( envira_gallery_metabox.selected );
					$('.envira-count').text( selected.toString() );
					$('.envira-clear-selected').fadeIn();

				} else {
					$( 'li', $( output ) ).removeClass( 'selected' );
					$( 'nav.envira-select-options' ).fadeOut();
						list = $( output + ' li').length;

						$('.select-all').text( envira_gallery_metabox.select_all );
						$('.envira-count').text( list.toString() );
						$('.envira-clear-selected').fadeOut();
				}

				$( document ).trigger( 'enviraSelectAll' );

			} );
		},

		//Sort Images
		sort_images: function(){

			$(document).on('change', '#envira-config-image-sort', function(){

				var $this = $(this),
					$sort = $this.val(),
					$direction = '',
					opts = {
						  url:		envira_gallery_metabox.ajax,
						  type:		'post',
						  async:	true,
						  cache:	false,
						  dataType: 'json',
						  data: {
							  action:  		'envira_sort_publish',
							  post_id: 		envira_gallery_metabox.id,
							  order:		$sort,
							  direction: 	$direction,
							  nonce:  		envira_gallery_metabox.save_nonce
						  },
						  success: function( response ) {

							  // Response should be a JSON success with the HTML for the image grid
							  if ( response ) {

								  // Set the image grid to the HTML we received
								  $( output ).html( response.data );

								  EnviraGalleryImagesUpdate( false );

								  if ( $sort === 'manual' ){

									   $( output ).attr('data-sortable', "1" );

								  } else {

									  $( output ).attr('data-sortable', "0" );

								  }

								  //Re-Trigger sortable
								envira_manage.sortable();

							  }

						  },
						  error: function(xhr, textStatus ,e) {
							  return;
						  }
					  };

				  $.ajax( opts );

			});

		},
		//Drag and drop
		sortable: function(){

			// Add sortable support to Envira Gallery Media items
			$( output ).sortable( {
				containment: output,
				items: 'li',
				cursor: 'move',
				forcePlaceholderSize: true,
				placeholder: 'dropzone',
				helper: function( e, item ) {

					// Basically, if you grab an unhighlighted item to drag, it will deselect (unhighlight) everything else
					if ( ! item.hasClass( 'selected' ) ) {
						item.addClass( 'selected' ).siblings().removeClass( 'selected' );
					}

					// Clone the selected items into an array
					var elements = item.parent().children( '.selected' ).clone();

					// Add a property to `item` called 'multidrag` that contains the
					// selected items, then remove the selected items from the source list
					item.data( 'multidrag', elements ).siblings( '.selected' ).remove();

					// Now the selected items exist in memory, attached to the `item`,
					// so we can access them later when we get to the `stop()` callback

					// Create the helper
					var helper = $( '<li/>' );
					return helper.append( elements );

				},
				stop: function( e, ui ) {

					// Remove the helper so we just display the sorted items
					var elements = ui.item.data( 'multidrag' );
					ui.item.after(elements).remove();

					// Send AJAX request to store the new sort order
					$.ajax( {
						url:		 envira_gallery_metabox.ajax,
						type:	  'post',
						async:	  true,
						cache:	  false,
						dataType: 'json',
						data: {
							action:	 'envira_gallery_sort_images',
							order:	 $( output ).sortable( 'toArray' ).toString(),
							post_id: envira_gallery_metabox.id,
							nonce:	 envira_gallery_metabox.sort
						},
						success: function( response ) {
							// Repopulate the Envira Gallery Backbone Image Collection
							EnviraGalleryImagesUpdate( false );
							return;
						},
						error: function( xhr, textStatus, e ) {
							// Inject the error message into the tab settings area
							$( output ).before( '<div class="error"><p>' + textStatus.responseText + '</p></div>' );
						}
					} );
				}
			} );

		},

		//Select Single Images
		select: function(){

			// Select / deselect images
			$( document ).on( 'click', 'ul#envira-gallery-output li.envira-gallery-image > img, li.envira-gallery-image > div, li.envira-gallery-image > a.check', function( e ) {

				// Prevent default action
				e.preventDefault();

				// Get the selected gallery item
				var $this 		 	= $( this ),
					$gallery_item 	= $this.parent(),
					selected		= '';

				if ( $gallery_item.hasClass( 'selected' ) ) {

					$gallery_item.removeClass( 'selected' );

					//Get the new selected count
					selected = $( output + ' li.selected' ).length;

					last_selected_image = false;

					   if( selected !== 0 ){

						$('.select-all').text( envira_gallery_metabox.selected );
						$('.envira-count').text( selected.toString() );
						$('.envira-clear-selected').fadeIn();


					} else{

						list = $( output + ' li').length;

						$('.select-all').text( envira_gallery_metabox.select_all );
						$('.envira-count').text( list.toString() );
						$('.envira-clear-selected').fadeOut();

					}

				} else {

					// If the shift key is being held down, and there's another image selected, select every image between this clicked image
					// and the other selected image
					if ( shift_key_pressed && last_selected_image !== false ) {

						// Get index of the selected image and the last image
						var start_index = $( 'ul#envira-gallery-output li' ).index( $( last_selected_image ) ),
							end_index = $( 'ul#envira-gallery-output li' ).index( $( $gallery_item ) ),
							i = 0;

						// Select images within the range
						if ( start_index < end_index ) {

							for ( i = start_index; i <= end_index; i++ ) {

								$( 'ul#envira-gallery-output li:eq( ' + i + ')' ).addClass( 'selected' );

							}

						} else {

							for ( i = end_index; i <= start_index; i++ ) {

								$( 'ul#envira-gallery-output li:eq( ' + i + ')' ).addClass( 'selected' );

							}

						}

					}

					// Select the clicked image
					$( $gallery_item ).addClass( 'selected' );

					last_selected_image = $( $gallery_item );

					selected = $( output + ' li.selected' ).length;
					$('.envira-clear-selected').fadeIn();

					$('.select-all').text( envira_gallery_metabox.selected );
					$('.envira-count').text( selected.toString() );

				}

				// Show/hide buttons depending on whether
				// any galleries have been selected
				if ( $( 'ul#envira-gallery-output > li.selected' ).length > 0 ) {

					$( 'nav.envira-select-options' ).fadeIn();

				} else {

					$( 'nav.envira-select-options' ).fadeOut();

				}

			} );

		},

		//Clear Selection
		clear_selected: function(){

			$('.envira-clear-selected').on('click', function(e){

				e.preventDefault();

				$( output + ' li.selected' ).removeClass( 'selected' );

				list = $( output + ' li').length;

				$('.select-all').text( envira_gallery_metabox.select_all );
				$('.envira-count').text( list.toString() );
				$('.envira-select-all').prop('checked', false);
				$( 'nav.envira-select-options' ).fadeOut();

				$( this ).fadeOut();

				$( document ).trigger( 'enviraClearSelected' );

			});

		},

		// Toggle List / Grid View
		display_toggle: function(){

			$( document ).on( 'click', 'nav.envira-tab-options a', function( e ) {

				e.preventDefault();

				// Get the view the user has chosen
				var envira_tab_nav			= $( this ).closest( '.envira-tab-options' ),
					envira_tab_view			= $( this ).data( 'view' ),
					envira_tab_view_style	= $( this ).data( 'view-style' );

				// If this view style is already displayed, don't do anything
				if ( $( envira_tab_view ).hasClass( envira_tab_view_style ) ) {
					return;
				}

				// Update the view class
				$( envira_tab_view ).removeClass( 'list' ).removeClass( 'grid' ).addClass( envira_tab_view_style );

				// Mark the current view icon as selected
				$( 'a', envira_tab_nav ).removeClass( 'selected' );
				$( this ).addClass( 'selected' );

				// Send an AJAX request to store this user's preference for the view
				// This means when they add or edit any other Gallery, the image view will default to this setting
				$.ajax( {
					url:		envira_gallery_metabox.ajax,
					type:		 'post',
					dataType: 'json',
					data: {
						action:	 'envira_gallery_set_user_setting',
						name:	 'envira_gallery_image_view',
						value:	 envira_tab_view_style,
						nonce:	 envira_gallery_metabox.set_user_setting_nonce
					},
					success: function( response ) {

						$( document ).trigger( 'enviraDisplayToggle' );

					},
					error: function( xhr, textStatus, e ) {
						// Inject the error message into the tab settings area
						$( envira_gallery_output ).before( '<div class="error"><p>' + textStatus.responseText + '</p></div>' );
					}
				} );

			} );

		},

		//Chosen Select boxes
		chosen: function(){
			 //Create the Select boxes
			$('.envira-chosen').each(function (){

				//Get the options from the data.
				var data_options = $(this).data('envira-chosen-options');

				$(this).chosen( data_options );

			});
		},

		//Update Item Count
		update_count: function(){

			list = $( output + ' li').length;

			//update the count value
			$('.envira-count').text( list.toString() );

			if ( list > 0 ){

				   $('#envira-empty-itemr').fadeOut().addClass('envira-hidden');
				   $('.envira-item-header').removeClass('envira-hidden').fadeIn();
				   $( '.envira-bulk-actions' ).fadeOut();

			}

		},

		//Deletes an items out of the gallery
		delete_item: function(){
			 /**
			  * Delete Single Image
			  */
			  $( document ).on( 'click', '#envira-gallery-main .envira-gallery-remove-image', function( e ) {

				  e.preventDefault();

				  // Bail out if the user does not actually want to remove the image.
				  var confirm_delete = confirm( envira_gallery_metabox.remove );
				  if ( ! confirm_delete ) {
					  return;
				  }

				  // Send an AJAX request to delete the selected items from the Gallery
				  var attach_id = $( this ).parent().attr( 'id' );
				  $.ajax( {
					  url:		envira_gallery_metabox.ajax,
					  type:		'post',
					  dataType: 'json',
					  data: {
						  action:		 'envira_gallery_remove_image',
						  attachment_id: attach_id,
						  post_id:		 envira_gallery_metabox.id,
						  nonce:		 envira_gallery_metabox.remove_nonce
					  },
					  success: function( response ) {
						  $( '#' + attach_id ).fadeOut( 'normal', function() {
							  $( this ).remove();

							  // Refresh the modal view to ensure no items are still checked if they have been removed.
							  $( '.envira-gallery-load-library' ).attr( 'data-envira-gallery-offset', 0 ).addClass( 'has-search' ).trigger( 'click' );

							  // Repopulate the Envira Gallery Image Collection
							  EnviraGalleryImagesUpdate( false );

							  envira_manage.start_screen();

						  } );
					  },
					  error: function( xhr, textStatus, e ) {
						  // Inject the error message into the tab settings area
						  $( envira_gallery_output ).before( '<div class="error"><p>' + textStatus.responseText + '</p></div>' );
					  }
				  } );
			  } );

		},
		//Bulk Deletes selected items
		bulk_delete: function(){
			/**
			  * Delete Multiple Images
			  */
			  $( document ).on( 'click', 'a.envira-gallery-images-delete', function( e ) {

				  e.preventDefault();

				  // Bail out if the user does not actually want to remove the image.
				  var confirm_delete = confirm(envira_gallery_metabox.remove_multiple);
				  if ( ! confirm_delete ) {
					  return false;
				  }

				  // Build array of image attachment IDs
				  var attach_ids = [];
				  $( 'ul#envira-gallery-output > li.selected' ).each( function() {
					  attach_ids.push( $( this ).attr( 'id' ) );
				  } );

				  // Send an AJAX request to delete the selected items from the Gallery
				  var attach_id = $( this ).parent().attr( 'id' );
				  $.ajax( {
					  url:		envira_gallery_metabox.ajax,
					  type:		'post',
					  dataType: 'json',
					  data: {
						  action:		 'envira_gallery_remove_images',
						  attachment_ids:attach_ids,
						  post_id:		 envira_gallery_metabox.id,
						  nonce:		 envira_gallery_metabox.remove_nonce
					  },
					  success: function( response ) {

						   if ( response.success ){

							   // Remove each image
							   $( 'ul#envira-gallery-output > li.selected' ).remove();

							   // Hide Select Options
							   $( 'nav.envira-select-options' ).fadeOut();

							   // Refresh the modal view to ensure no items are still checked if they have been removed.
							   $( '.envira-gallery-load-library' ).attr( 'data-envira-gallery-offset', 0 ).addClass( 'has-search' ).trigger( 'click' );

							   // Repopulate the Envira Gallery Image Collection
							   EnviraGalleryImagesUpdate( false );

							envira_manage.start_screen();

						}

					  },
					  error: function( xhr, textStatus, e ) {
						  // Inject the error message into the tab settings area
						  $( envira_gallery_output ).before( '<div class="error"><p>' + textStatus.responseText + '</p></div>' );
					  }
				  } );

			  } );

		},
		//Trigger edit meta screen
		edit_meta: function(){

			  // Edit Image
			  $( document ).on( 'click', '#envira-gallery-main a.envira-gallery-modify-image', function( e ) {

				  // Prevent default action
				  e.preventDefault();

				  // (Re)populate the collection
				  // The collection can change based on whether the user previously selected specific images
				  EnviraGalleryImagesUpdate( false );

				  // Get the selected attachment
				  var attachment_id = $( this ).parent().data( 'envira-gallery-image' );

				  // Pass the collection of images for this gallery to the modal view, as well
				  // as the selected attachment
				  EnviraGalleryModalWindow.content( new EnviraGalleryEditView( {
					  collection:	  EnviraGalleryImages,
					  child_views:	  EnviraGalleryChildViews,
					  attachment_id:  attachment_id,
				  } ) );

				  // Open the modal window
				  EnviraGalleryModalWindow.open();

				  $( document ).trigger( 'enviraEditOpen');

			  } );

		},
		start_screen: function(){

			//Get Slide Count
			list =  $( output + ' li').length;

			//If there are no slides
			if ( list === 0 ){

				//Make sure bulk actions are out of view
				$( 'nav.envira-select-options' ).fadeOut();

				//Fade out Settings header
				$('.envira-content-images').fadeOut().addClass('envira-hidden');

				//Add Empty Slider Content
				$('#envira-empty-gallery').removeClass('envira-hidden').fadeIn();

			}else{

				//Fade out Settings header
				$('#envira-empty-gallery').fadeOut().addClass('envira-hidden');

				//Add Empty Slider Content
				$('.envira-content-images').removeClass('envira-hidden').fadeIn();
			}

		}

	}

	//DOM ready
	$(function(){

		envira_manage.init();

	});

	//Re init on type change
	$(document).on( 'enviraGalleryType', function(){

		envira_manage.init();

	});

	//Update slide count
	$(document).on( 'enviraInsert', function(){

		envira_manage.start_screen();
		envira_manage.update_count();

	});

})( jQuery , window, document, envira_manage, envira_gallery_metabox );