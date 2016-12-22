(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.CS_dashboardPostEditor = f()}})(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
jQuery( window ).ready( function( $ ) {

	var csData = csDashboardPostEditorData;

	function csl18n( key ) {
		return csData.strings[key] || '';
	};

	//
	// Cornerstone Editor Tab
	//

	var $csEditor, $wpEditor, $csTab, $editButton;

	$csEditor = $( csData.editorTabMarkup );
	$wpEditor = $( '#postdivrich' );
	$wpEditor.after( $csEditor );

	$csTab = $( '<button type="button" id="content-cornerstone" class="wp-switch-editor switch-cornerstone">' + csl18n( 'cornerstone-tab' ) + '</button>' );
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

	$csEditor.find( '#content-tmce, #content-html' ).click(function() {

		var visual = ( -1 !== $( this ).attr( 'id' ).indexOf( 'html' ) );

		function switchBack() {
			$wpEditor.show();
			$( window ).trigger( 'scroll.editor-expand', 'scroll' ); // Fix WP editor's width
			$csEditor.hide();
			$( '.composer-switch' ).css( { visibility: 'visible' } );
			switchEditors.go( 'content', ( visual ) ? 'html' : 'tmco' );
		}

		if ( 'true' == csData.usesCornerstone ) {

			tco.confirm( {
				message: csl18n( 'manual-edit-warning' ),
				acceptClass: 'tco-btn-nope',
				acceptBtn: csl18n( 'manual-edit-accept' ),
				declineBtn: csl18n( 'manual-edit-decline' ),
				accept: function() {
					if ( 'new' != csData.post_id ) {
						wp.ajax.post( 'cs_override', { post_id: csData.post_id });
					}
					csData.usesCornerstone = 'false';
					switchBack();
				}
			} );
			return;
		}

		switchBack();

	});

	$csTab.click(function() {

		if ( 'false' == csData.usesCornerstone && 'new' != csData.post_id && '' != wp.autosave.getPostData().content ) {
			tco.confirm( {
				message: csl18n( 'overwrite-warning' ),
				acceptClass: 'tco-btn-nope',
				acceptBtn: csl18n( 'overwrite-accept' ),
				declineBtn: csl18n( 'overwrite-decline' ),
				accept: function() {
					csData.usesCornerstone = 'none';
					switchToCornerstone();
				}
			} );
			return;
		}

		switchToCornerstone();

	});

	if ( 'true' == csData.usesCornerstone ) {
		switchToCornerstone();
	}

	//
	// "Edit with Cornerstone" button logic.
	//

	window.localStorage.removeItem( 'cornerstone_auto_draft' );
	$csEditor.find( '#cs-edit-button' ).on( 'click', function( e ) {

		e.preventDefault();
		e.stopPropagation();

		if ( null != csData.editURL ) {
			window.location = csData.editURL;
			return;
		}

		$( '#title-prompt-text' ).hide();

		var $title = $( '#title' );
		var val = $title.val();

		if ( ! val || 'title' == val ) {
			$title.val( csl18n( 'default-title' ) );
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

			window.localStorage.setItem( 'cornerstone_auto_draft', data.post_id );
			$( window ).off( 'beforeunload.edit-post' );
			window.location = csData.homeURL + context + data.post_id + '&preview=true&cornerstone=1';

		});

	});

} );

},{}]},{},[1])(1)
});
//# sourceMappingURL=dashboard-post-editor.map
