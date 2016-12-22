<?php

/**
 * Element Definition: Pricing Table
 */

class CSE_Pricing_Table {

	public function ui() {
		return array(
      'title'       => __( 'Pricing Table', 'cornerstone' ),
    );
	}

	public function flags() {
		return array(
			'dynamic_child' => true
		);
	}

	public function register_shortcode() {
  	return false;
  }

	public function update_build_shortcode_atts( $atts ) {
		$atts['columns'] = count( $atts['elements'] );
		return $atts;
	}

}