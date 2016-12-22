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

GLOBAL $x_terms_of_use_options;

if ( isset( $_POST['x_terms_of_use_form_submitted'] ) ) {
  if ( strip_tags( $_POST['x_terms_of_use_form_submitted'] ) == 'submitted' && current_user_can( 'manage_options' ) ) {

    $x_terms_of_use_options['x_terms_of_use_enable']        = ( isset( $_POST['x_terms_of_use_enable'] ) ) ? strip_tags( $_POST['x_terms_of_use_enable'] ) : '';
    $x_terms_of_use_options['x_terms_of_use_entry_include'] = strip_tags( $_POST['x_terms_of_use_entry_include'] );

    update_option( 'x_terms_of_use', $x_terms_of_use_options );

  }
}



// Get Options
// =============================================================================

$x_terms_of_use_options = apply_filters( 'x_terms_of_use_options', get_option( 'x_terms_of_use' ) );

if ( $x_terms_of_use_options != '' ) {

  $x_terms_of_use_enable        = $x_terms_of_use_options['x_terms_of_use_enable'];
  $x_terms_of_use_entry_include = $x_terms_of_use_options['x_terms_of_use_entry_include'];

}