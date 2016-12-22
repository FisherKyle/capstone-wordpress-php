<?php

// =============================================================================
// FUNCTIONS/OUTPUT.PHP
// -----------------------------------------------------------------------------
// Plugin output.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Under Construction
//   02. Output
// =============================================================================

// Under Construction
// =============================================================================

function x_under_construction_output( $original_template ) {

  require( X_UNDER_CONSTRUCTION_PATH . '/functions/options.php' );

  if ( isset( $x_under_construction_enable ) && $x_under_construction_enable == 1 && ! is_user_logged_in() ) {

    status_header( 503 );

    return X_UNDER_CONSTRUCTION_PATH . '/views/site/under-construction.php';

  } else {

    return $original_template;

  }

}



// Output
// =============================================================================

add_filter( 'template_include', 'x_under_construction_output' );