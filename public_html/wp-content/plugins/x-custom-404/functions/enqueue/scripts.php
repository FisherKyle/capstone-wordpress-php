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
// =============================================================================

// Enqueue Admin Scripts
// =============================================================================

function x_custom_404_enqueue_admin_scripts( $hook ) {

  if ( $hook == 'addons_page_x-extensions-custom-404' ) {

    wp_enqueue_script( 'x-custom-404-admin-js', X_CUSTOM_404_URL . '/js/admin/main.js', array( 'jquery' ), NULL, true );

  }

}

add_action( 'admin_enqueue_scripts', 'x_custom_404_enqueue_admin_scripts' );