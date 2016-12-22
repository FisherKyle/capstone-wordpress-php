<?php

class CS_Responsive_Text extends Cornerstone_Element_Base {

  public function data() {
    return array(
      'name'        => 'responsive-text',
      'title'       => __('Responsive Text', 'cornerstone' ),
      'section'     => '_typography',
      'description' => __( 'Responsive Text description.', 'cornerstone' ),
      'render'      => false
    );
  }

  public function controls() {

    $this->addControl(
      'title',
      'title',
      null,
      null,
      __( 'Responsive Text Item', 'cornerstone' )
    );

    $this->addControl(
      'selector',
      'text',
      __( 'Selector', 'cornerstone' ),
      __( 'Enter in the selector for your Responsive Text (e.g. if your class is "h-responsive" enter ".h-responsive").', 'cornerstone' ),
      '.h-responsive'
    );

    $this->addControl(
      'compression',
      'text',
      __( 'Compression', 'cornerstone' ),
      __( 'Enter the compression for your Responsive Text. Adjust up and down to desired level in small increments (e.g. 0.95, 1.15, et cetera).', 'cornerstone' ),
      '1.0'
    );

    $this->addControl(
      'min_size',
      'text',
      __( 'Minimum Size', 'cornerstone' ),
      __( 'Enter the minimum size of your Responsive Text.', 'cornerstone' ),
      ''
    );

    $this->addControl(
      'max_size',
      'text',
      __( 'Maximum Size', 'cornerstone' ),
      __( 'Enter the maximum size of your Responsive Text.', 'cornerstone' ),
      ''
    );
  }

  public function render( $atts ) {

    extract( $atts );

    $shortcode = "[x_responsive_text selector=\"$selector\" compression=\"$compression\" min_size=\"$min_size\" max_size=\"$max_size\"]";

    return $shortcode;

  }

}