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

function x_typekit_enqueue_admin_scripts( $hook ) {

  if ( $hook == 'addons_page_x-extensions-typekit' ) {

    wp_enqueue_script( 'x-typekit-admin-js', X_TYPEKIT_URL . '/js/admin/main.js', array( 'jquery' ), NULL, true );

  }

}

add_action( 'admin_enqueue_scripts', 'x_typekit_enqueue_admin_scripts' );