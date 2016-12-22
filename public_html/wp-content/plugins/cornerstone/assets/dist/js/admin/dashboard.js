(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.CS_dashboard = f()}})(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
window.csAdmin.l18n =  function( key ) {
	return csAdmin.strings[key] || '';
};

if ( 'true' == csAdmin.isSettingsPage )
	jQuery( document ).ready( require( './settings-page' ) );

if ( 'true' == csAdmin.isPostEditor )
	jQuery( window ).ready( require( './post-editor' ) );

},{"./post-editor":2,"./settings-page":3}],2:[function(require,module,exports){
jQuery( window ).ready( function( $ ) {

	if ( 'true' !== csAdmin.isPostEditor ) return;

	//
	// Cornerstone Editor Tab
	//

	var $csEditor, $wpEditor, $csTab, $editButton;

	$csEditor = $( csAdmin.editorTabMarkup );
	$wpEditor = $( '#postdivrich' );
	$wpEditor.after( $csEditor );

	$csTab = $( '<button type="button" id="content-cornerstone" class="wp-switch-editor switch-cornerstone">' + csAdmin.l18n( 'cornerstone-tab' ) + '</button>' );
	$wpEditor.find( '.wp-editor-tabs' ).append( $csTab );

	var switchToCornerstone = function() {
		$wpEditor.hide();
		$csEditor.show();
		var hideVC = function() {
			$( '.composer-switch' ).css( { visibility:'hidden' } );
		};
		_.defer( hideVC );
		jQuery( window ).on( 'load', hideVC );
	};

	var switchBack = function( context ) {
		$wpEditor.show();
		$( window ).trigger( 'scroll.editor-expand', 'scroll' ); // Fix WP editor's width
		$csEditor.hide();
		$( '.composer-switch' ).css( { visibility: 'visible' } );
		switchEditors.switchto( context );
	};

	$csEditor.find( '#content-tmce, #content-html' ).click(function() {

		var mode = this;

		if ( 'true' == csAdmin.usesCornerstone ) {
			Confirm( 'error', csAdmin.l18n( 'manual-edit-warning' ), function() {
				if ( 'new' != csAdmin.post_id ) {
					wp.ajax.post( 'cs_override', { post_id: csAdmin.post_id });
				}
				csAdmin.usesCornerstone = 'false';
				switchBack( mode );
			});
			return;
		}

		switchBack( mode );

	});

	$csTab.click(function() {

		if ( 'false' == csAdmin.usesCornerstone && 'new' != csAdmin.post_id && '' != wp.autosave.getPostData().content ) {
			Confirm( 'error', csAdmin.l18n( 'overwrite-warning' ), function() {
				csAdmin.usesCornerstone = 'none';
				switchToCornerstone();
			});
			return;
		}

		switchToCornerstone();

	});

	if ( 'true' == csAdmin.usesCornerstone ) {
		switchToCornerstone();
	}

	//
	// "Edit with Cornerstone" button logic.
	//

	$csEditor.find( '#cs-edit-button' ).on( 'click', function( e ) {

		e.preventDefault();
		e.stopPropagation();

		if ( null != csAdmin.editURL ) {
			window.location = csAdmin.editURL;
			return;
		}

		$( '#title-prompt-text' ).hide();

		var $title = $( '#title' );
		var val = $title.val();

		if ( ! val || 'title' == val ) {
			$title.val( csAdmin.l18n( 'default-title' ) );
		}

		wp.autosave.server.triggerSave();

		$( document ).on( 'heartbeat-tick.autosave', function( event, data ) {

			var data = wp.autosave.getPostData();
			var context = '?page_id=';

			if ( 'post' == data.post_type ) {
				context = '?p=';
			}

			if ( 'post' != data.post_type && 'page' != data.post_type ) {
				context = '?post_type=' + data.post_type + '&p=';
			}

			$( window ).off( 'beforeunload.edit-post' );
			window.location = csAdmin.homeURL + context + data.post_id + '&preview=true&cornerstone=1';

		});

	});

} );

},{}],3:[function(require,module,exports){
jQuery( function( $ ) {

	//
	// Save button.
	//

	$( '#submit' ).click(function() {
		$( this ).addClass( 'saving' ).val( csAdmin.l18n( 'updating' ) );
	});

	//
	// Meta box toggle.
	//

	postboxes.add_postbox_toggles( pagenow );

} );

},{}]},{},[1])(1)
});
//# sourceMappingURL=dashboard.map

/* Modules bundled with Browserify */
