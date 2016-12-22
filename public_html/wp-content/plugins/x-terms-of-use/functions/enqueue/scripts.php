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

function x_terms_of_use_enqueue_admin_scripts( $hook ) {

  if ( $hook == 'addons_page_x-extensions-terms-of-use' ) {

    wp_enqueue_script( 'x-terms-of-use-admin-js', X_TERMS_OF_USE_URL . '/js/admin/main.js', array( 'jquery' ), NULL, true );

  }

}

add_action( 'admin_enqueue_scripts', 'x_terms_of_use_enqueue_admin_scripts' );