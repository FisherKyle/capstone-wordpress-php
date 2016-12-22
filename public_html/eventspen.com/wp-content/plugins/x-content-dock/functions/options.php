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
  if ( sanitize_text_field( $_POST['x_content_dock_form_submitted'] ) == 'submitted' && current_user_can( 'manage_options' ) ) {

    //
    // Set "Include" setting to an empty array if no value is set to avoid
    // array_map() notice if second parameter isn't an array.
    //

    $x_content_dock_entries_include_post      = ( isset( $_POST['x_content_dock_entries_include'] ) ) ? $_POST['x_content_dock_entries_include'] : array();
    $x_content_dock_posts_include_post        = ( isset( $_POST['x_content_dock_posts_include'] ) ) ? $_POST['x_content_dock_posts_include'] : array();
    $x_content_dock_woo_products_include_post = ( isset( $_POST['x_content_dock_woo_products_include'] ) ) ? $_POST['x_content_dock_woo_products_include'] : array();

    $x_content_dock_options['x_content_dock_enable']                   = ( isset( $_POST['x_content_dock_enable'] ) ) ? sanitize_text_field( $_POST['x_content_dock_enable'] ) : '';
    $x_content_dock_options['x_content_dock_position']                 = sanitize_text_field( $_POST['x_content_dock_position'] );
    $x_content_dock_options['x_content_dock_width']                    = sanitize_text_field( $_POST['x_content_dock_width'] );
    $x_content_dock_options['x_content_dock_display']                  = sanitize_text_field( $_POST['x_content_dock_display'] );
    $x_content_dock_options['x_content_dock_all_pages_active']         = ( isset( $_POST['x_content_dock_enable'] ) ) ? sanitize_text_field( $_POST['x_content_dock_all_pages_active'] ) : '';
    $x_content_dock_options['x_content_dock_entries_include']          = array_map( 'sanitize_text_field', $x_content_dock_entries_include_post );
    $x_content_dock_options['x_content_dock_all_posts_active']         = ( isset( $_POST['x_content_dock_enable'] ) ) ? sanitize_text_field( $_POST['x_content_dock_all_posts_active'] ) : '';
    $x_content_dock_options['x_content_dock_posts_include']            = array_map( 'sanitize_text_field', $x_content_dock_posts_include_post );
    $x_content_dock_options['x_content_dock_all_woo_products_active']  = ( isset( $_POST['x_content_dock_enable'] ) ) ? sanitize_text_field( $_POST['x_content_dock_all_woo_products_active'] ) : '';
    $x_content_dock_options['x_content_dock_woo_products_include']     = array_map( 'sanitize_text_field', $x_content_dock_woo_products_include_post );
    $x_content_dock_options['x_content_dock_text_color']               = sanitize_text_field( $_POST['x_content_dock_text_color'] );
    $x_content_dock_options['x_content_dock_headings_color']           = sanitize_text_field( $_POST['x_content_dock_headings_color'] );
    $x_content_dock_options['x_content_dock_link_color']               = sanitize_text_field( $_POST['x_content_dock_link_color'] );
    $x_content_dock_options['x_content_dock_link_hover_color']         = sanitize_text_field( $_POST['x_content_dock_link_hover_color'] );
    $x_content_dock_options['x_content_dock_close_button_color']       = sanitize_text_field( $_POST['x_content_dock_close_button_color'] );
    $x_content_dock_options['x_content_dock_close_button_hover_color'] = sanitize_text_field( $_POST['x_content_dock_close_button_hover_color'] );
    $x_content_dock_options['x_content_dock_border_color']             = sanitize_text_field( $_POST['x_content_dock_border_color'] );
    $x_content_dock_options['x_content_dock_background_color']         = sanitize_text_field( $_POST['x_content_dock_background_color'] );
    $x_content_dock_options['x_content_dock_box_shadow']               = ( isset( $_POST['x_content_dock_box_shadow'] ) ) ? sanitize_text_field( $_POST['x_content_dock_box_shadow'] ) : '';
    $x_content_dock_options['x_content_dock_trigger_timeout']          = sanitize_text_field( $_POST['x_content_dock_trigger_timeout'] );
    $x_content_dock_options['x_content_dock_cookie_timeout']           = sanitize_text_field( $_POST['x_content_dock_cookie_timeout'] );
    $x_content_dock_options['x_content_dock_image_override_enable']    = ( isset( $_POST['x_content_dock_image_override_enable'] ) ) ? sanitize_text_field( $_POST['x_content_dock_image_override_enable'] ) : '';
    $x_content_dock_options['x_content_dock_image_override_image']     = sanitize_text_field( $_POST['x_content_dock_image_override_image'] );
    $x_content_dock_options['x_content_dock_image_override_url']       = sanitize_text_field( $_POST['x_content_dock_image_override_url'] );

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
  $x_content_dock_all_pages_active         = $x_content_dock_options['x_content_dock_all_pages_active'];
  $x_content_dock_entries_include          = $x_content_dock_options['x_content_dock_entries_include'];
  $x_content_dock_all_posts_active         = $x_content_dock_options['x_content_dock_all_posts_active'];
  $x_content_dock_posts_include            = $x_content_dock_options['x_content_dock_posts_include'];
  $x_content_dock_all_woo_products_active  = $x_content_dock_options['x_content_dock_all_woo_products_active'];
  $x_content_dock_woo_products_include     = $x_content_dock_options['x_content_dock_woo_products_include'];
  $x_content_dock_text_color               = $x_content_dock_options['x_content_dock_text_color'];
  $x_content_dock_headings_color           = $x_content_dock_options['x_content_dock_headings_color'];
  $x_content_dock_link_color               = $x_content_dock_options['x_content_dock_link_color'];
  $x_content_dock_link_hover_color         = $x_content_dock_options['x_content_dock_link_hover_color'];
  $x_content_dock_close_button_color       = $x_content_dock_options['x_content_dock_close_button_color'];
  $x_content_dock_close_button_hover_color = $x_content_dock_options['x_content_dock_close_button_hover_color'];
  $x_content_dock_border_color             = $x_content_dock_options['x_content_dock_border_color'];
  $x_content_dock_background_color         = $x_content_dock_options['x_content_dock_background_color'];
  $x_content_dock_box_shadow               = $x_content_dock_options['x_content_dock_box_shadow'];
  $x_content_dock_trigger_timeout          = $x_content_dock_options['x_content_dock_trigger_timeout'];
  $x_content_dock_cookie_timeout           = $x_content_dock_options['x_content_dock_cookie_timeout'];
  $x_content_dock_image_override_enable    = $x_content_dock_options['x_content_dock_image_override_enable'];
  $x_content_dock_image_override_image     = $x_content_dock_options['x_content_dock_image_override_image'];
  $x_content_dock_image_override_url       = $x_content_dock_options['x_content_dock_image_override_url'];

}
