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

function x_white_label_enqueue_admin_scripts( $hook ) {

  if ( $hook == 'addons_page_x-extensions-white-label' ) {

    wp_enqueue_script( 'x-white-label-admin-js', X_WHITE_LABEL_URL . '/js/admin/main.js', array( 'jquery' ), NULL, true );
    wp_enqueue_media();
  }

}

add_action( 'admin_enqueue_scripts', 'x_white_label_enqueue_admin_scripts' );
