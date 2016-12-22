<?php

// =============================================================================
// FUNCTIONS/OUTPUT.PHP
// -----------------------------------------------------------------------------
// Plugin output.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Terms of Use
//   02. Error Handling
//   03. Output
// =============================================================================

// Terms of Use
// =============================================================================

function x_terms_of_use_output() {

  require( X_TERMS_OF_USE_PATH . '/views/site/terms-of-use.php' ); 

}



// Error Handling
// =============================================================================

function x_terms_of_use_check_fields( $errors, $sanitized_user_login, $user_email ) { 

  if ( ! isset( $_POST['agree'] ) ) {
    $errors->add( 'empty_agree', __( '<strong>ERROR</strong>: You must agree to our terms of use.', '__x__' ) );
  }

  return $errors;

}

add_action( 'registration_errors', 'x_terms_of_use_check_fields', 10, 3 );



// Output
// =============================================================================

require( X_TERMS_OF_USE_PATH . '/functions/options.php' );

if ( isset( $x_terms_of_use_enable ) && $x_terms_of_use_enable == 1 ) {

  add_action( 'register_form', 'x_terms_of_use_output', 9999 );

}