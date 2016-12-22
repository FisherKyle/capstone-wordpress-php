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

GLOBAL $x_under_construction_options;

if ( isset( $_POST['x_under_construction_form_submitted'] ) ) {
  if ( strip_tags( $_POST['x_under_construction_form_submitted'] ) == 'submitted' && current_user_can( 'manage_options' ) ) {

    $x_under_construction_options['x_under_construction_enable']           = ( isset( $_POST['x_under_construction_enable'] ) ) ? strip_tags( $_POST['x_under_construction_enable'] ) : '';
    $x_under_construction_options['x_under_construction_heading']          = strip_tags( $_POST['x_under_construction_heading'] );   
    $x_under_construction_options['x_under_construction_subheading']       = strip_tags( $_POST['x_under_construction_subheading'] );
    $x_under_construction_options['x_under_construction_date']             = strip_tags( $_POST['x_under_construction_date'] );
    $x_under_construction_options['x_under_construction_facebook']         = strip_tags( $_POST['x_under_construction_facebook'] );
    $x_under_construction_options['x_under_construction_twitter']          = strip_tags( $_POST['x_under_construction_twitter'] );
    $x_under_construction_options['x_under_construction_google_plus']      = strip_tags( $_POST['x_under_construction_google_plus'] );
    $x_under_construction_options['x_under_construction_instagram']        = strip_tags( $_POST['x_under_construction_instagram'] );
    $x_under_construction_options['x_under_construction_background_image'] = strip_tags( $_POST['x_under_construction_background_image'] );
    $x_under_construction_options['x_under_construction_background_color'] = strip_tags( $_POST['x_under_construction_background_color'] );
    $x_under_construction_options['x_under_construction_heading_color']    = strip_tags( $_POST['x_under_construction_heading_color'] );
    $x_under_construction_options['x_under_construction_subheading_color'] = strip_tags( $_POST['x_under_construction_subheading_color'] );
    $x_under_construction_options['x_under_construction_date_color']       = strip_tags( $_POST['x_under_construction_date_color'] );
    $x_under_construction_options['x_under_construction_social_color']     = strip_tags( $_POST['x_under_construction_social_color'] );

    update_option( 'x_under_construction', $x_under_construction_options );

  }
}



// Get Options
// =============================================================================

$x_under_construction_options = apply_filters( 'x_under_construction_options', get_option( 'x_under_construction' ) );

if ( $x_under_construction_options != '' ) {

  $x_under_construction_enable           = $x_under_construction_options['x_under_construction_enable'];
  $x_under_construction_heading          = $x_under_construction_options['x_under_construction_heading'];
  $x_under_construction_subheading       = $x_under_construction_options['x_under_construction_subheading'];
  $x_under_construction_date             = $x_under_construction_options['x_under_construction_date'];
  $x_under_construction_facebook         = $x_under_construction_options['x_under_construction_facebook'];
  $x_under_construction_twitter          = $x_under_construction_options['x_under_construction_twitter'];
  $x_under_construction_google_plus      = $x_under_construction_options['x_under_construction_google_plus'];
  $x_under_construction_instagram        = $x_under_construction_options['x_under_construction_instagram'];
  $x_under_construction_background_image = $x_under_construction_options['x_under_construction_background_image'];
  $x_under_construction_background_color = $x_under_construction_options['x_under_construction_background_color'];
  $x_under_construction_heading_color    = $x_under_construction_options['x_under_construction_heading_color'];
  $x_under_construction_subheading_color = $x_under_construction_options['x_under_construction_subheading_color'];
  $x_under_construction_date_color       = $x_under_construction_options['x_under_construction_date_color'];
  $x_under_construction_social_color     = $x_under_construction_options['x_under_construction_social_color'];

}