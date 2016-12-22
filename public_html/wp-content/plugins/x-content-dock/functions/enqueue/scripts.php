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

function x_content_dock_enqueue_admin_scripts( $hook ) {

  if ( $hook == 'addons_page_x-extensions-content-dock' ) {

    wp_enqueue_script( 'x-content-dock-admin-js', X_CONTENT_DOCK_URL . '/js/admin/main.js', array( 'jquery' ), NULL, true );

  }

}

add_action( 'admin_enqueue_scripts', 'x_content_dock_enqueue_admin_scripts' );