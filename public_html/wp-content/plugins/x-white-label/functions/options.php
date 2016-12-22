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

GLOBAL $x_white_label_options;

if ( isset( $_POST['x_white_label_form_submitted'] ) ) {
  if ( strip_tags( $_POST['x_white_label_form_submitted'] ) == 'submitted' && current_user_can( 'manage_options' ) ) {

    $kses_allowed_tags = array(
      'div'    => array( 'class' => array() ),
      'p'      => array( 'class' => array() ),
      'h1'     => array( 'class' => array() ),
      'h2'     => array( 'class' => array() ),
      'h3'     => array( 'class' => array() ),
      'h4'     => array( 'class' => array() ),
      'h5'     => array( 'class' => array() ),
      'h6'     => array( 'class' => array() ),
      'a'      => array( 'class' => array(), 'href' => array(), 'target' => array() ),
      'img'    => array( 'class' => array(), 'src' => array() ),
      'span'   => array( 'class' => array() ),
      'em'     => array( 'class' => array() ),
      'strong' => array( 'class' => array() ),
      'style'  => array(),
    );

    $x_white_label_options['x_white_label_enable']               = ( isset( $_POST['x_white_label_enable'] ) ) ? strip_tags( $_POST['x_white_label_enable'] ) : '';
    $x_white_label_options['x_white_label_login_image']          = strip_tags( $_POST['x_white_label_login_image'] );
    $x_white_label_options['x_white_label_login_bg_image']	     = strip_tags( $_POST['x_white_label_login_bg_image'] );
    $x_white_label_options['x_white_label_retina_enabled']       = ( isset( $_POST['x_white_label_retina_enabled'] ) ) ? strip_tags( $_POST['x_white_label_retina_enabled'] ) : '';
    $x_white_label_options['x_white_label_addons_home_heading']  = strip_tags( $_POST['x_white_label_addons_home_heading'] );
    $x_white_label_options['x_white_label_addons_home_content']  = stripslashes( wp_kses( $_POST['x_white_label_addons_home_content'], $kses_allowed_tags ) );
    $x_white_label_options['x_white_label_addons_home_position'] = strip_tags( $_POST['x_white_label_addons_home_position'] );

    update_option( 'x_white_label', $x_white_label_options );

  }
}



// Get Options
// =============================================================================

$x_white_label_options = apply_filters( 'x_white_label_options', get_option( 'x_white_label' ) );

if ( $x_white_label_options != '' ) {

  $x_white_label_enable               = $x_white_label_options['x_white_label_enable'];
  $x_white_label_login_image          = $x_white_label_options['x_white_label_login_image'];
  $x_white_label_login_bg_image				= $x_white_label_options['x_white_label_login_bg_image'];
  $x_white_label_retina_enabled       = $x_white_label_options['x_white_label_retina_enabled'];
  $x_white_label_addons_home_heading  = $x_white_label_options['x_white_label_addons_home_heading'];
  $x_white_label_addons_home_content  = $x_white_label_options['x_white_label_addons_home_content'];
  $x_white_label_addons_home_position = $x_white_label_options['x_white_label_addons_home_position'];

}
