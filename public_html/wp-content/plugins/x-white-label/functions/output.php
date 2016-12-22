<?php

// =============================================================================
// FUNCTIONS/OUTPUT.PHP
// -----------------------------------------------------------------------------
// Plugin output.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. White Label
//   02. Output
// =============================================================================

// White Label
// =============================================================================

function x_white_label_output() {

  ob_start();

  require( X_WHITE_LABEL_PATH . '/views/admin/white-label.php' );

  $output = ob_get_clean();

  echo $output;

}



// Output
// =============================================================================

require( X_WHITE_LABEL_PATH . '/functions/options.php' );

if ( isset( $x_white_label_enable ) && $x_white_label_enable == 1 ) {

  add_action( $x_white_label_addons_home_position, 'x_white_label_output' );

}