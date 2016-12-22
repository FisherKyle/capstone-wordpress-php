<?php
class Cornerstone_Save_Handler extends Cornerstone_Plugin_Component {

	public $append;

	public function ajax_handler( $data ) {

		if ( ! isset( $data['elements'] )  ) {
			return cs_send_json_error( array( 'message' => 'No element data recieved' ) );
		}

		if ( ! isset( $data['settings'] ) ) {
			return cs_send_json_error( array( 'message' => 'No setting data recieved' ) );
		}

		if ( ! is_array( $data['elements'] )  ) {
			return cs_send_json_error( array( 'message' => 'Element data invalid' ) );
		}

		if ( ! is_array( $data['settings'] ) ) {
			return cs_send_json_error( array( 'message' => 'Setting data invalid' ) );
		}

		global $post;
		$post = get_post( (int) $data['post_id'] ); // WPCS: override ok.

		if ( ! isset( $data['post_id'] ) || ! $post ) {
			return cs_send_json_error( array( 'message' => 'post_id not set' ) );
		}

		$cap = $this->plugin->common()->get_post_capability( $post, 'edit_post' );
		if ( ! current_user_can( $cap, $data['post_id'] ) ) {
			return cs_send_json_error( array( 'message' => sprintf( '%s capability required.', $cap ) ) );
		}

		setup_postdata( $post );

		$this->append = array();

		$this->post_id = $data['post_id'];

		$this->legacy = $this->plugin->loadComponent( 'Legacy_Renderer' );

		$settings = $this->save_settings( $data['settings'] );

		if ( is_wp_error( $settings ) ) {
			return cs_send_json_error( array( 'message' => $settings->get_error_message() ) );
		}

		$element_buffer = $this->save_elements( $data['elements'] );

		wp_reset_postdata();

		if ( is_wp_error( $element_buffer ) ) {
			return cs_send_json_error( array( 'message' => $element_buffer->get_error_message() ) );
		}

		update_post_meta( $this->post_id, '_cornerstone_version', $this->plugin->version() );

		return cs_send_json_success();

	}

	public function save_settings( $settings ) {

		$this->settings_manager = $this->plugin->loadComponent( 'Settings_Manager' );
		$this->settings_manager->load();

		foreach ( $settings as $setting ) {
			$result = $this->save_setting( $setting );
			if ( is_wp_error( $result ) ) {
				return $result;
			}
		}

		return true;
	}

	public function save_elements( $elements ) {

		$this->orchestrator = $this->plugin->component( 'Element_Orchestrator' );
		$this->orchestrator->load_elements();

		// Santize values according to their kind
		foreach ( $elements as $index => $child ) {
			$elements[ $index ] = $this->sanitize_element( $child );
		}

		if ( ! isset( $this->append ) ) {
			$this->append = array();
		}

		foreach ( $this->append as $index => $child ) {
			$this->append[ $index ] = $this->sanitize_element( $child );
		}

		// Generate shortcodes
		$element_data = array_merge( $elements, $this->append );
		$buffer = '';
		foreach ( $element_data as $element ) {
			$content = $this->save_element( $element );
			if ( is_wp_error( $content ) ) {
				return $content;
			}
			$buffer .= $content;
		}

		cs_update_serialized_post_meta( $this->post_id, '_cornerstone_data', $elements );
		delete_post_meta( $this->post_id, '_cornerstone_override' );

		$buffer = $this->process_content( $buffer );
		$post_content = '[cs_content]' . $buffer . '[/cs_content]';

		wp_update_post( array(
			'ID'           => $this->post_id,
			'post_content' => wp_slash( $post_content ),
		) );

		$post_type = get_post_type( $this->post_id );

		if ( $post_type !== false && post_type_supports( $post_type, 'excerpt' ) ) {
			update_post_meta( $this->post_id, '_cornerstone_excerpt', cs_derive_excerpt( $post_content, true ) );
		}

		return $buffer;
	}

	public function save_setting( $setting ) {

		if ( ! isset( $setting['_section'] ) ) {
			return new WP_Error( 'Cornerstone_Save_Handler', 'Element _section not set: ' . maybe_serialize( $setting ) );
		}

		$section = $this->settings_manager->get( $setting['_section'] );
		if ( is_null( $section ) ) {
			return null;
		}

		unset( $setting['_section'] );
		return $section->save( $setting );

	}

	public function save_element( $element, $parent = null ) {

		if ( ! isset( $element['_type'] ) ) {
			return new WP_Error( 'Cornerstone_Save_Handler', 'Element _type not set: ' . maybe_serialize( $element ) );
		}

		$definition = $this->orchestrator->get( $element['_type'] );

		if ( 'mk1' === $definition->version() ) {
			return $this->legacy->save_element( $element );
		}

		$flags = $definition->flags();

		if ( ! isset( $flags['child'] ) || ! $flags['child'] ) {
			$parent = null;
		}

		if ( isset( $element['_csmeta'] ) ) {
			unset( $element['_csmeta'] );
		}

		$buffer = '';

		if ( isset( $element['elements'] ) ) {
			foreach ( $element['elements'] as $child ) {
				$content = $this->save_element( $child, $definition->compose( $element ) );
				if ( is_wp_error( $content ) ) {
					return $content;
				}
				$buffer .= $content;
			}
		}

		$output = $definition->build_shortcode( $element, $buffer, $parent );

		return $output;

	}

	public function sanitize_element( $element ) {

		if ( ! isset( $element['_type'] ) ) {
			return new WP_Error( 'Cornerstone_Save_Handler', 'Element _type not set: ' . maybe_serialize( $element ) );
		}

		$definition = $this->orchestrator->get( $element['_type'] );

		if ( isset( $element['elements'] ) ) {
			foreach ( $element['elements'] as $index => $child ) {
				$element['elements'][ $index ] = $this->sanitize_element( $child );
			}
		}

		return $definition->sanitize( $element );

	}

	public function append_element( $element ) {

		if ( ! isset( $this->append ) ) {
			$this->append = array();
		}

		$this->append[] = $element;

	}

	public function process_content( $content ) {

		// Move all <!--nextpage--> directives to outside their section.
		$content = preg_replace( '#(?:<!--nextpage-->.*?)(\[\/cs_section\])#', '$0<!--nextpage-->', $content );

		//Strip all <!--nextpage--> directives still within sections
		$content = preg_replace( '#(?<!\[\/cs_section\])<!--nextpage-->#', '', $content );

		$content = str_replace( '<!--more-->', '', $content );

		return $content;

	}

}
