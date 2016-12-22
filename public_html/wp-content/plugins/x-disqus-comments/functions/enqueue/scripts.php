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

function x_disqus_comments_enqueue_admin_scripts( $hook ) {

  if ( $hook == 'addons_page_x-extensions-disqus-comments' ) {

    wp_enqueue_script( 'x-disqus-comments-admin-js', X_DISQUS_COMMENTS_URL . '/js/admin/main.js', array( 'jquery' ), NULL, true );

  }

}

add_action( 'admin_enqueue_scripts', 'x_disqus_comments_enqueue_admin_scripts' );