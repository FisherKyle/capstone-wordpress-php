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

function x_disqus_comments_enqueue_admin_styles( $hook ) {

  if ( $hook == 'addons_page_x-extensions-disqus-comments' ) {

    wp_enqueue_style( 'x-disqus-comments-admin-css', X_DISQUS_COMMENTS_URL . '/css/admin/style.css', NULL, NULL, 'all' );

  }

}

add_action( 'admin_enqueue_scripts', 'x_disqus_comments_enqueue_admin_styles' );