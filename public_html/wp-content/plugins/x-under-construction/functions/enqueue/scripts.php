<?php

// =============================================================================
// FUNCTIONS/ENQUEUE/SCRIPTS.PHP
// -----------------------------------------------------------------------------
// Plugin scripts.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Enqueue Site Scripts
//   02. Enqueue Admin Scripts
// =============================================================================

// Enqueue Site Scripts
// =============================================================================

function x_under_construction_enqueue_site_scripts() {

  require( X_UNDER_CONSTRUCTION_PATH . '/functions/options.php' );

  if ( isset( $x_under_construction_enable ) && $x_under_construction_enable == 1 ) {

    wp_enqueue_script( 'x-under-construction-site-js', X_UNDER_CONSTRUCTION_URL . '/js/site/countdown.js', array( 'jquery' ), NULL, true );

  }

}

add_action( 'wp_enqueue_scripts', 'x_under_construction_enqueue_site_scripts' );



// Enqueue Admin Scripts
// =============================================================================

function x_under_construction_enqueue_admin_scripts( $hook ) {

  if ( $hook == 'addons_page_x-extensions-under-construction' ) {

    wp_enqueue_script( 'x-under-construction-admin-js', X_UNDER_CONSTRUCTION_URL . '/js/admin/main.js', array( 'jquery' ), NULL, true );

  }

}

add_action( 'admin_enqueue_scripts', 'x_under_construction_enqueue_admin_scripts' );