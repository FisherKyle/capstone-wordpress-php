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

function x_custom_404_enqueue_admin_styles( $hook ) {

  if ( $hook == 'addons_page_x-extensions-custom-404' ) {

    wp_enqueue_style( 'x-disqus-comments-admin-css', X_CUSTOM_404_URL . '/css/admin/style.css', NULL, NULL, 'all' );

  }

}

add_action( 'admin_enqueue_scripts', 'x_custom_404_enqueue_admin_styles' );