<?php

// =============================================================================
// VIEWS/SITE/CUSTOM-404.PHP
// -----------------------------------------------------------------------------
// Plugin site output.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Require Options
//   02. Output
// =============================================================================

// Require Options
// =============================================================================

function x_custom_404_filter_template( $template ) {

  require( X_CUSTOM_404_PATH . '/functions/options.php' );

  if ( ! isset( $x_custom_404_enable ) || ! $x_custom_404_enable ) {
    return $template;
  }

  $custom_404_post = get_post( (int) $x_custom_404_entry_include );

  if ( ! is_a( $custom_404_post, 'WP_Post' ) ) {
    return $template;
  }

  GLOBAL $wp_query;
  GLOBAL $post;

  $post = $custom_404_post;

  $wp_query->posts             = array( $post );
  $wp_query->queried_object_id = $post->ID;
  $wp_query->queried_object    = $post;
  $wp_query->post_count        = 1;
  $wp_query->found_posts       = 1;
  $wp_query->max_num_pages     = 0;
  $wp_query->is_404            = false;
  $wp_query->is_page           = true;
  $wp_query->is_singular	     = true;

  return get_page_template();

}

add_filter( '404_template', 'x_custom_404_filter_template' );
