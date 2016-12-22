<?php

// =============================================================================
// FUNCTIONS/OPTIONS.PHP
// -----------------------------------------------------------------------------
// Plugin options.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Set Options
//   02. Get Options
// =============================================================================

// Set Options
// =============================================================================

//
// Set $_POST variables to options array and update option.
//

GLOBAL $x_typekit_options;

if ( isset( $_POST['x_typekit_form_submitted'] ) ) {
  if ( strip_tags( $_POST['x_typekit_form_submitted'] ) == 'submitted' && current_user_can( 'manage_options' ) ) {

    require_once( X_TYPEKIT_PATH . '/functions/request.php' );

    $x_typekit_options['x_typekit_enable']  = ( isset( $_POST['x_typekit_enable'] ) ) ? strip_tags( $_POST['x_typekit_enable'] ) : '';
    $x_typekit_options['x_typekit_kit_id']  = strip_tags( $_POST['x_typekit_kit_id'] );
    $x_typekit_options['x_typekit_request'] = x_typekit_request( $x_typekit_options['x_typekit_kit_id'] );

    update_option( 'x_typekit', $x_typekit_options );

  }
}



// Get Options
// =============================================================================

$x_typekit_options = apply_filters( 'x_typekit_options', get_option( 'x_typekit' ) );

if ( $x_typekit_options != '' ) {

  $x_typekit_enable  = $x_typekit_options['x_typekit_enable'];
  $x_typekit_kit_id  = $x_typekit_options['x_typekit_kit_id'];
  $x_typekit_request = $x_typekit_options['x_typekit_request'];

}