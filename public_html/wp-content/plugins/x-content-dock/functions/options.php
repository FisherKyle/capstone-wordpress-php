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

GLOBAL $x_content_dock_options;

if ( isset( $_POST['x_content_dock_form_submitted'] ) ) {
  if ( strip_tags( $_POST['x_content_dock_form_submitted'] ) == 'submitted' && current_user_can( 'manage_options' ) ) {

    //
    // Set "Include" setting to an empty array if no value is set to avoid
    // array_map() notice if second parameter isn't an array.
    //

    $x_content_dock_entries_include_post = ( isset( $_POST['x_content_dock_entries_include'] ) ) ? $_POST['x_content_dock_entries_include'] : array();

    $x_content_dock_options['x_content_dock_enable']                   = ( isset( $_POST['x_content_dock_enable'] ) ) ? strip_tags( $_POST['x_content_dock_enable'] ) : '';
    $x_content_dock_options['x_content_dock_position']                 = strip_tags( $_POST['x_content_dock_position'] );
    $x_content_dock_options['x_content_dock_width']                    = strip_tags( $_POST['x_content_dock_width'] );
    $x_content_dock_options['x_content_dock_display']                  = strip_tags( $_POST['x_content_dock_display'] );
    $x_content_dock_options['x_content_dock_entries_include']          = array_map( 'strip_tags', $x_content_dock_entries_include_post );
    $x_content_dock_options['x_content_dock_text_color']               = strip_tags( $_POST['x_content_dock_text_color'] );
    $x_content_dock_options['x_content_dock_headings_color']           = strip_tags( $_POST['x_content_dock_headings_color'] );
    $x_content_dock_options['x_content_dock_link_color']               = strip_tags( $_POST['x_content_dock_link_color'] );
    $x_content_dock_options['x_content_dock_link_hover_color']         = strip_tags( $_POST['x_content_dock_link_hover_color'] );
    $x_content_dock_options['x_content_dock_close_button_color']       = strip_tags( $_POST['x_content_dock_close_button_color'] );
    $x_content_dock_options['x_content_dock_close_button_hover_color'] = strip_tags( $_POST['x_content_dock_close_button_hover_color'] );
    $x_content_dock_options['x_content_dock_border_color']             = strip_tags( $_POST['x_content_dock_border_color'] );
    $x_content_dock_options['x_content_dock_background_color']         = strip_tags( $_POST['x_content_dock_background_color'] );
    $x_content_dock_options['x_content_dock_box_shadow']               = ( isset( $_POST['x_content_dock_box_shadow'] ) ) ? strip_tags( $_POST['x_content_dock_box_shadow'] ) : '';

    update_option( 'x_content_dock', $x_content_dock_options );

  }

}



// Get Options
// =============================================================================

$x_content_dock_options = apply_filters( 'x_content_dock_options', get_option( 'x_content_dock' ) );

if ( $x_content_dock_options != '' ) {

  $x_content_dock_enable                   = $x_content_dock_options['x_content_dock_enable'];
  $x_content_dock_position                 = $x_content_dock_options['x_content_dock_position'];
  $x_content_dock_width                    = $x_content_dock_options['x_content_dock_width'];
  $x_content_dock_display                  = $x_content_dock_options['x_content_dock_display'];
  $x_content_dock_entries_include          = $x_content_dock_options['x_content_dock_entries_include'];
  $x_content_dock_text_color               = $x_content_dock_options['x_content_dock_text_color'];
  $x_content_dock_headings_color           = $x_content_dock_options['x_content_dock_headings_color'];
  $x_content_dock_link_color               = $x_content_dock_options['x_content_dock_link_color'];
  $x_content_dock_link_hover_color         = $x_content_dock_options['x_content_dock_link_hover_color'];
  $x_content_dock_close_button_color       = $x_content_dock_options['x_content_dock_close_button_color'];
  $x_content_dock_close_button_hover_color = $x_content_dock_options['x_content_dock_close_button_hover_color'];
  $x_content_dock_border_color             = $x_content_dock_options['x_content_dock_border_color'];
  $x_content_dock_background_color         = $x_content_dock_options['x_content_dock_background_color'];
  $x_content_dock_box_shadow               = $x_content_dock_options['x_content_dock_box_shadow'];

}