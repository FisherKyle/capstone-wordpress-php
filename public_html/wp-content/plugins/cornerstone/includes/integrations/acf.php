<?php

class Cornerstone_Integration_ACF {

  protected $do_shortcode = false;

	public static function should_load() {
		return class_exists( 'acf' ) && function_exists( 'acf_shortcode' );
	}

	public function __construct() {
		add_filter( 'cs_element_update_build_shortcode_content', array( $this, 'expand_fields' ) );
    add_filter( 'cornerstone_decode_shortcode_attribute', array( $this, 'expand_fields' ) );
    add_shortcode( 'cs_acf', array( $this, 'shortcode_handler' ) );
	}

  public function expand_fields( $content ) {

    if ( current_filter() === 'cornerstone_decode_shortcode_attribute' ) {
      $this->do_shortcode = true;
    }

    $expanded = preg_replace_callback( '/{{[aA][cC][fF]:([A-Za-z0-9_-]*?)}}/', array( $this, 'expand_callback' ), $content );
    $this->do_shortcode = false;

    return $expanded;

  }

  public function expand_callback( $matches ) {

    $result = '[cs_acf field="' . $matches[1] . '"]';

    if ( $this->do_shortcode ) {
      $result = do_shortcode( $result );
    }

    return $result;

  }

  public function shortcode_handler( $atts ) {
    return acf_shortcode( $atts );
  }

}
