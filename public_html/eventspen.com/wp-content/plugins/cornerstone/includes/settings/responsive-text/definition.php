<?php
class CS_Settings_Responsive_Text {

	public $priority = 30;

	public function ui() {
		return array( 'title' => __( 'Responsive Text', 'cornerstone' ) );
	}

	public function defaults() {
		return array(
			'elements' => array()
		);
	}

	public function controls() {
		return array(
			'elements' => array(
				'type' => 'sortable',
				'options' => array(
					'element' => 'responsive-text'
				)
			)
		);
	}

	public function get_data( $key ) {

		global $post;

		$settings = array();

		if ( isset( $this->manager->post_meta['_cornerstone_settings'] ) ) {
			$settings = cs_maybe_json_decode( maybe_unserialize( $this->manager->post_meta['_cornerstone_settings'][0] ) );
			$settings = ( is_array( $settings ) ) ? $settings : array();
		}

		if ( 'elements' == $key && isset( $settings['responsive_text'] ) ) {
			$controller = CS()->loadComponent( 'Data_Controller' );
			return $controller->migrate($settings['responsive_text']);
		}

		return null;

	}

	public function handler( $data ) {

    global $post;

    $settings = CS()->common()->get_post_settings( $post->ID );
    $settings['responsive_text'] = ( isset( $data['elements'] ) ) ? $data['elements'] : array();

    cs_update_serialized_post_meta( $post->ID, '_cornerstone_settings', $settings );

    $save_handler = CS()->component( 'Save_Handler' );

    foreach ($settings['responsive_text'] as $element ) {
    	$save_handler->append_element( $element );
    }
	}

}