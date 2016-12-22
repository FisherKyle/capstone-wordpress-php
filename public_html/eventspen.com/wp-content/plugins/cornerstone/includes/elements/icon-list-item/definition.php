<?php

/**
 * Element Definition: Icon List Item
 */

class CSE_Icon_List_Item {

	public function ui() {
		return array(
      'title'       => __( 'Icon List Item', 'cornerstone' ),
    );
	}

	public function flags() {
		return array(
			'child' => true
		);
	}

	public function update_build_shortcode_atts( $atts ) {
		$atts['content'] = $atts['title'];
		return $atts;
	}
}