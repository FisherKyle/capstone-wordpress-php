<?php

// =============================================================================
// FUNCTIONS/ENQUEUE/STYLES.PHP
// -----------------------------------------------------------------------------
// Plugin styles.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Output Site Styles
//   02. Enqueue Admin Styles
// =============================================================================

// Output Site Styles
// =============================================================================

function x_typekit_output_site_styles() {

  require( X_TYPEKIT_PATH . '/functions/options.php' );

  if ( isset( $x_typekit_enable ) && $x_typekit_enable == 1 ) :

  ?>

    /*
    // Hide text while Typekit is loading.
    */

    .wf-loading a,
    .wf-loading p,
    .wf-loading ul,
    .wf-loading ol,
    .wf-loading dl,
    .wf-loading h1,
    .wf-loading h2,
    .wf-loading h3,
    .wf-loading h4,
    .wf-loading h5,
    .wf-loading h6,
    .wf-loading em,
    .wf-loading pre,
    .wf-loading cite,
    .wf-loading span,
    .wf-loading table,
    .wf-loading strong,
    .wf-loading blockquote {
      visibility: hidden !important;
    }

  <?php

  endif;

}

add_action( 'x_head_css', 'x_typekit_output_site_styles' );



// Enqueue Admin Styles
// =============================================================================

function x_typekit_enqueue_admin_styles( $hook ) {

  if ( $hook == 'addons_page_x-extensions-typekit' ) {

    wp_enqueue_style( 'x-typekit-admin-css', X_TYPEKIT_URL . '/css/admin/style.css', NULL, NULL, 'all' );

  }

}

add_action( 'admin_enqueue_scripts', 'x_typekit_enqueue_admin_styles' );