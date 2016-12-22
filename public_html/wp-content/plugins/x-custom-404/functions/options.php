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

GLOBAL $x_custom_404_options;

if ( isset( $_POST['x_custom_404_form_submitted'] ) ) {
  if ( strip_tags( $_POST['x_custom_404_form_submitted'] ) == 'submitted' && current_user_can( 'manage_options' ) ) {

    $x_custom_404_options['x_custom_404_enable']        = ( isset( $_POST['x_custom_404_enable'] ) ) ? strip_tags( $_POST['x_custom_404_enable'] ) : '';
    $x_custom_404_options['x_custom_404_entry_include'] = strip_tags( $_POST['x_custom_404_entry_include'] );

    update_option( 'x_custom_404', $x_custom_404_options );

  }
}



// Get Options
// =============================================================================

$x_custom_404_options = apply_filters( 'x_custom_404_options', get_option( 'x_custom_404' ) );

if ( $x_custom_404_options != '' ) {

  $x_custom_404_enable        = $x_custom_404_options['x_custom_404_enable'];
  $x_custom_404_entry_include = $x_custom_404_options['x_custom_404_entry_include'];

}