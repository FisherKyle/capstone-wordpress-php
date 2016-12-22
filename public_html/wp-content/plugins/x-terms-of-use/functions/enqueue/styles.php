<?php

// =============================================================================
// FUNCTIONS/ENQUEUE/STYLES.PHP
// -----------------------------------------------------------------------------
// Plugin styles.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Enqueue Admin Styles
// =============================================================================

// Enqueue Admin Styles
// =============================================================================

function x_terms_of_use_enqueue_admin_styles( $hook ) {

  if ( $hook == 'addons_page_x-extensions-terms-of-use' ) {

    wp_enqueue_style( 'x-terms-of-use-admin-css', X_TERMS_OF_USE_URL . '/css/admin/style.css', NULL, NULL, 'all' );

  }

}

add_action( 'admin_enqueue_scripts', 'x_terms_of_use_enqueue_admin_styles' );