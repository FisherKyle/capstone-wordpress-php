<?php

// =============================================================================
// FUNCTIONS/ENQUEUE/SCRIPTS.PHP
// -----------------------------------------------------------------------------
// Plugin scripts.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Enqueue Admin Scripts
//   02. Do not show callback
// =============================================================================

// Enqueue Admin Scripts
// =============================================================================

function x_content_dock_enqueue_admin_scripts( $hook ) {

  if ( $hook == 'addons_page_x-extensions-content-dock' ) {

    wp_enqueue_script( 'x-content-dock-admin-js', X_CONTENT_DOCK_URL . '/js/admin/main.js', array( 'jquery' ), NULL, true );
    wp_enqueue_media();

  }

}

add_action( 'admin_enqueue_scripts', 'x_content_dock_enqueue_admin_scripts' );

// Do not show callback
// =============================================================================

add_action( 'wp_ajax_x_content_dock_do_not_show', 'x_content_dock_do_not_show_callback' );
add_action( 'wp_ajax_nopriv_x_content_dock_do_not_show', 'x_content_dock_do_not_show_callback' );

function x_content_dock_do_not_show_callback() {

  $do_not_show = intval( $_POST['x_content_dock_do_not_show'] );
  setcookie( 'x_content_dock_do_not_show', 'true', $x_content_dock_cookie_timeout * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
  echo 'done';
	wp_die();

}
