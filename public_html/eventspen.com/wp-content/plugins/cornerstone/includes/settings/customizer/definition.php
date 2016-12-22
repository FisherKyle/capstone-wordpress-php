<?php
/**
 * 1. Setup definition.php with `ui` function
 * 2. Create defaults.php with keys for the attributes to be used
 * 3. Create controls.php to map controls. If dynamic options are needed, use
 * 		`$this->control_options('key_name')` so they can be fetched later
 * 4. Setup a condition_filter method returning true/false for every key in defaults
 * 5. Setup get_data method returning any information already stored (don't return defaults)
 */

class CS_Settings_Customizer {

	public $priority = 50;
	public $manager;

	public function ui() {
		return array( 'title' => __( 'Customizer', 'cornerstone' ) );
	}

	public function controls() {

		global $post;

    $url = add_query_arg(array(
      'url' => CS()->common()->getEditURL( get_the_id() )
    ), admin_url( 'customize.php' ) );

    $link = '<a href="' . $url . '">' . __( 'Customizer', 'cornerstone' ) . '</a>';
    $html = '<ul class="cs-controls"><li class="cs-control cs-control-info-box"><h4>' . __( 'Looking for global styling?', 'cornerstone' ) .'</h4><p>' . sprintf( __( 'Sitewide styles outside of the content area are managed via the %s.', 'cornerstone' ), $link ) . '</p></li></ul>';

    return array(
	'customizer_message' => array(
		'type' => 'custom-markup',
		'ui' => array(),
		'options' => array( 'html' => $html )
	)
    );

	}

	public function handler() { }
	public function defaults() {
		return array( 'customizer_message' => '' );
	}

}