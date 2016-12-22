<?php

// Supports matching scss style variables and string interpolation.

class Cornerstone_Style_Template_Parser {

	protected $template;
	protected $data;

	public function __construct( $template = '' ) {
		$this->template = $template;
	}

	// public function identify_variables() {
	// 	preg_match_all('/(?<=\$)\w+|(?<=#{\$)\w+(?=})/', $this->template, $matches );
	// 	return $matches[0];
	// }

	public function run( $data ) {
    $this->data = $data;
		return preg_replace_callback('/\$\w+|#{\$\w+}/', array( $this, 'replacer' ), $this->template );
	}

	public function replacer( $matches ) {
    $key = str_replace( '$', '', trim( substr( $matches[0], 1 ), '{}' ) );
		return ( isset( $this->data[$key] ) ) ? $this->data[$key] : '';
	}

	// public function get_rules() {
	// 	// Split template into rules, and capture selectors
	// 	preg_match_all('/(.+?)((?<!#){.+?(?<!\w)})/', $this->template, $matches );
	// }
  //
}
