<?php

/**
 * Manage all the front end code for Cornerstone
 * including shortcode styling and scripts
 */

class Cornerstone_Front_End extends Cornerstone_Plugin_Component {

	/**
	 * Setup hooks
	 */
	public function setup() {

		// Enqueue Scripts & Styles

		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		add_action( 'template_redirect', array( $this, 'postLoaded' ), 9998, 0 );
		add_filter( 'get_the_excerpt', array( $this, 'maybe_supply_excerpt' ), 100 );

		// Add Body Class
		add_filter( 'body_class', array( $this, 'addBodyClass' ), 10002 );

		add_filter( 'the_content', array( $this, 'cs_content_before_shortcodes' ), 10 );
		add_shortcode( 'cs_content', array( $this, 'cs_content_shortcode' ) );
	}

	/**
	 * Enqueue Styles
	 */
	public function styles() {

		if ( apply_filters( 'cornerstone_enqueue_styles', true ) ) {
			wp_enqueue_style( 'cornerstone-shortcodes', $this->plugin->css( 'site/style' ), array(), $this->plugin->version() );
		}

		if ( apply_filters( 'cornerstone_legacy_font_classes', false ) ) {
			wp_enqueue_style( 'x-fa-icon-classes', $this->plugin->css( 'site/fa-icon-classes' ), array(), $this->plugin->version() );
		}

	}

	/**
	 * Enqueue Scripts
	 */
	public function scripts() {

  	wp_register_script( 'cornerstone-site-head', $this->url( 'assets/dist/js/site/cs-head.min.js' ), array( 'jquery' ), $this->plugin->version(), false );
  	wp_register_script( 'cornerstone-site-body', $this->url( 'assets/dist/js/site/cs-body.min.js' ), array( 'cornerstone-site-head' ), $this->plugin->version(), true );
  	wp_register_script( 'vendor-ilightbox',      $this->url( 'assets/dist/js/site/vendor-ilightbox.min.js' ), array( 'jquery' ), $this->plugin->version(), true );
	//wp_register_script( 'vendor-google-maps',    'https://maps.googleapis.com/maps/api/js?sensor=false', array( 'jquery' ) );

  	wp_enqueue_script( 'cornerstone-site-head' );
  	wp_enqueue_script( 'cornerstone-site-body' );

	}

	public function postLoaded() {

		if ( apply_filters( '_cornerstone_front_end', true ) ) {
			add_action( 'wp_head', array( $this,  'inlineStyles' ), 9998, 0 );
			add_action( 'wp_footer', array( $this, 'inlineScripts' ), 9998, 0 );
		}

		$this->postSettings = $this->plugin->common()->get_post_settings( get_the_ID() );

	}

  /**
	 * Add Body class from Cornerstone Version number
	 */
	public function addBodyClass( $classes ) {
		$classes[] = 'cornerstone-v' . str_replace( '.', '_', $this->plugin->version() );
	  return $classes;
	}

	/**
	 * Load generated CSS output and place style tag in wp_head
	 */
	public function inlineStyles() {

		ob_start();

		if ( apply_filters( 'cornerstone_customizer_output', true ) ) {

			echo '<style id="cornerstone-generated-css" type="text/css">';

			$data = array_merge( $this->plugin->settings(), $this->plugin->component( 'Customizer_Manager' )->optionData() );
    	$this->view( 'frontend/styles', true, $data, true );

    	do_action( 'cornerstone_head_css' );

	  	echo '</style>';

		$custom_css = get_option( 'cs_v1_custom_css', '' );
			if ( $custom_css ) {
				echo '<style id="cornerstone-custom-css" type="text/css">' . $custom_css . '</style>';
			}

		}



		if ( apply_filters( '_cornerstone_custom_css', isset( $this->postSettings['custom_css'] ) ) ) {
			echo '<style id="cornerstone-custom-page-css" type="text/css">';
				echo $this->postSettings['custom_css'];
				do_action( 'cornerstone_custom_page_css' );
	  	echo '</style>';
		}

	  $css = ob_get_contents(); ob_end_clean();

	  //
	  // 1. Remove comments.
	  // 2. Remove whitespace.
	  // 3. Remove starting whitespace.
	  //

	  $output = preg_replace( '#/\*.*?\*/#s', '', $css );            // 1
	  $output = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $output ); // 2
	  $output = preg_replace( '/\s\s+(.*)/', '$1', $output );        // 3

	  echo $output;
	}

	public function inlineScripts() {

		if ( apply_filters( 'cornerstone_customizer_output', true ) ) {
			$custom_js = get_option( 'cs_v1_custom_js', '' );
			if ( $custom_js ) {
				echo '<script id="cornerstone-custom-js">' . $custom_js . '</script>';
			}
		}

		if ( apply_filters( '_cornerstone_custom_page_js', true ) && isset( $this->postSettings['custom_js_mini'] ) && $this->postSettings['custom_js_mini'] ) {
			echo '<script id="cornerstone-custom-page-js">';
				echo $this->postSettings['custom_js_mini'];
				do_action( 'cornerstone_custom_page_js' );
	  	echo '</script>';
		}

	}

	public function maybe_supply_excerpt( $excerpt ) {

		if ( '' === $excerpt ) {

			$post = get_post();

			if ( CS()->common()->uses_cornerstone( $post ) ) {

				$cs_excerpt = get_post_meta( $post->ID, '_cornerstone_excerpt', true );

				if ( $cs_excerpt ) {
					return wp_trim_words( $cs_excerpt, apply_filters( 'excerpt_length', 55 ), apply_filters( 'excerpt_more', ' [&hellip;]' ) );
				}

			}

		}

		return $excerpt;

	}

	/**
	 * Cornerstone adds a wrapping [cs_content] shortcode.Run the content through
	 * cs_noemptyp if we know it was originally generated by Cornerstone.
	 * This cleans up any empty <p> tags. Next We'll manually replace this with
	 * our wrapping div since it's much faster than adding another layer of
	 * nested do_shortcode calls.
	 * @param  string $content Early the_content. Before do_shortcode
	 * @return string          the_content with empty <p> tags removed and wrapping div
	 */
	public function cs_content_before_shortcodes( $content ) {

		if ( false !== strpos( $content, '[cs_content]' ) && false !== strpos( $content, '[/cs_content]' ) ) {
			$content = cs_noemptyp( $content );
			$content = str_replace( '[cs_content]', "<div id=\"cs-content\" class=\"cs-content\">", $content );
			$content = str_replace( '[/cs_content]', '</div>', $content );
		} else {
			$content = str_replace( '[cs_content]', '', $content );
			$content = str_replace( '[/cs_content]', '', $content );
		}

		return $content;

	}

	public function cs_content_shortcode( $atts, $content ) {
		$content = do_shortcode( $content );
		return "<div id=\"cs-content\" class=\"cs-content\">$content</div>";
	}

}
