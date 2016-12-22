<?php
class Cornerstone_Builder_Renderer extends Cornerstone_Plugin_Component {

	public $raw_markup = false;
	public $sandbox_the_content = true;
	public $dependencies = array( 'Front_End' );

	public function ajax_handler( $data ) {

		add_shortcode( 'cs_render_wrapper', array( $this, 'wrapping_shortcode' ) );

		CS_Shortcode_Preserver::init();

		if ( $this->sandbox_the_content )
			CS_Shortcode_Preserver::sandbox( 'cs_render_the_content' );

		add_filter('cs_preserve_shortcodes_no_wrap', '__return_true' );

		$this->orchestrator = $this->plugin->component( 'Element_Orchestrator' );
		$this->orchestrator->load_elements();

		$this->mk1 = $this->plugin->loadComponent( 'Legacy_Renderer' );

		global $post;
		if ( !isset( $data['post_id'] ) || ! $post = get_post( (int) $data['post_id'] ) ) {
      return cs_send_json_error( array( 'message' => 'post_id not set' ) );
		}

    $cap = $this->plugin->common()->get_post_capability( $post, 'edit_post' );
		if ( ! current_user_can( $cap, $data['post_id'] ) ) {
			return cs_send_json_error( array( 'message' => sprintf( '%s capability required.', $cap ) ) );
		}

    setup_postdata( $post );


    $this->enqueue_extractor = $this->plugin->loadComponent( 'Enqueue_Extractor' );
    $this->enqueue_extractor->start();

    if ( isset( $data['raw_markup'] ) )
    	$this->raw_markup = (bool) $data['raw_markup'];

    if ( !isset( $data['batch'] ) )
			return cs_send_json_error( array('message' => 'No element data recieved' ) );

		$jobs = $this->batch( $data['batch'] );
		$scripts = $this->enqueue_extractor->get_scripts();
		$styles = $this->enqueue_extractor->get_styles();

		if ( is_wp_error( $jobs ) )
			return cs_send_json_error( array( 'message' => $jobs->get_error_message() ) );

		$result = array( 'jobs' => $jobs );

		if ( ! empty( $scripts ) ) {
			$result['scripts'] = $scripts;
		}

		if ( ! empty( $styles ) ) {
			$result['styles'] = $styles;
		}

		return cs_send_json_success( $result );

	}

	/**
	 * Run a batch of render jobs.
	 * This helps reduce AJAX request, as the javascript will send as many
	 * elements as it can to be rendered at once.
	 * @param  array $data list of jobs with element data
	 * @return array       finished jobs
	 */
	public function batch( $batch ) {

		$results = array();

		foreach ($batch as $job) {

			if ( !isset( $job['jobID'] ) || !isset( $job['data'] ) || !isset( $job['provider'] ) )
				return new WP_Error( 'cs_renderer', 'Malformed render job request');

			$markup =  $this->render_element( $job['data'], ( $job['provider'] != 'mk2' ) );

			$scripts = $this->enqueue_extractor->extract_scripts();
			$styles  = $this->enqueue_extractor->extract_styles();

			$results[$job['jobID']] = array( 'markup' => $markup, 'ts' => $job['ts'] );

			if ( ! empty( $scripts ) ) {
				$results[ $job['jobID'] ]['scripts'] = $scripts;
			}

			if ( ! empty( $styles ) ) {
				$results[ $job['jobID'] ]['styles'] = $styles;
			}

		}

		return $results;

	}

	/**
	 * Return an element that has been rendered with data formatted for the preview window
	 * @param  array   $data   element data
	 * @param  boolean $legacy Whether or not to use the old render system.
	 * @return string          shortcode to be processed for preview window
	 */
	public function render_element( $element, $legacy = false ) {

		$transient = null;
		if ( isset( $element['_transient'] ) ) {
			$transient = $element['_transient'];
			unset( $element['_transient'] );
		}

		if ( $legacy ) {
			$markup = $this->mk1->renderElement( $element );
		} else {
			$definition = $this->orchestrator->get( $element['_type'] );
			$markup = $definition->preview( $element, $this->orchestrator, null, $transient );
		}

		if ( '' != $markup )
			$markup = '[cs_render_wrapper]' . $markup . '[/cs_render_wrapper]';

		$filter = ( $this->sandbox_the_content ) ? 'cs_render_the_content': 'the_content';
		$markup = ( $this->raw_markup ) ? $markup : apply_filters( $filter, $markup );

		if ( !is_string( $markup ) )
			$markup = '';

		return $markup;

	}

	public function wrapping_shortcode( $atts, $content = '' ) {
		return do_shortcode( cs_noemptyp( $content ) );
	}

}
