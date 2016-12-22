<?php

class Cornerstone_Controller_App extends Cornerstone_Plugin_Component {

  public function permissions() {

    $manage_options = current_user_can( 'manage_options' );
    $allowed_post_types = $this->plugin->common()->getAllowedPostTypes();

    return array(
      'canManageOptions' => $manage_options,
      'canUseHeaders'    => $manage_options && current_theme_supports( 'cornerstone_headers' ),
      'canUseBuilder'    => ! empty( $allowed_post_types ),
      'allowedPostTypes' => $allowed_post_types,
      'useUnfilteredHTML' => current_user_can( 'unfiltered_html' )
    );

  }

  public function font_data() {
    $font_data = $this->plugin->config( 'common/font-data' );

    return apply_filters( 'cs_font_data', $font_data );
  }

  public function font_weights() {
    return $this->plugin->config( 'common/font-weights' );
  }

}
