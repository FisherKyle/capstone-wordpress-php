<?php

class Cornerstone_Injector extends Cornerstone_Plugin_Component {


	public function setup() {

		//
		// Keys will be stripped from the output $atts
		// Callbacks will process
		//

		$this->mixin_expanders = apply_filters( 'cornerstone_control_mixin_expanders', array(
			'_text_color'  => array( $this, 'text_color' ), // text_color is multipurpose, so we don't want to strip the key
			'margin'       => array( $this, 'margin' ),
			'padding'      => array( $this, 'padding' ),
			'border_style' => array( $this, 'border_style' ),
			'border'       => null,
			'border_width' => null,
			'border_color' => null,
			'visibility'   => array( $this, 'visibility' ),
			'text_align'   => array( $this, 'text_align' ),
			'class'        => array( $this, 'class_handler' ),
			'style'        => array( $this, 'style' ),
		) );

		add_filter( 'cornerstone_control_injections', array( $this, 'injector' ) );

	}

	/**
	 * Main Process. Filter attributes and run the expansion handlers
	 */

	public function injector( $atts ) {

		$expanders = apply_filters( 'cornerstone_control_mixin_expanders_dynamic', $this->mixin_expanders );

		$inject = array(
			'classes' => array(),
			'styles'  => array()
		);

		//
		// Run handlers
		//

		$expanded_callbacks = array_values( $expanders );
		foreach ( $expanded_callbacks as $callback ) {
			if ( is_callable( $callback ) ) {
				$inject = call_user_func( $callback, $inject, $atts );
			}
		}

		//
		// Remove processed keys
		//

		$expanded_keys = array_keys( $expanders );
		foreach ( $expanded_keys as $key ) {
			unset( $atts[$key] );
		}

		//
		// Inject attributes
		//

		$classes = array_values( $inject['classes'] );
		if ( count( $classes ) > 0 ) {
			$atts['class'] = trim( implode( $classes, ' ' ) );
		}

		$styles = array_values( $inject['styles'] );
		if ( count( $styles ) > 0 ) {
			$atts['style'] = Cornerstone_Style_Reducer::reduce( implode( ';', $styles ) );
		}

		return $atts;

	}

	/**
	 * Injection Handlers
	 */

	public function text_color( $inject, $atts ) {

		if ( isset( $atts['text_color'] ) && $atts['text_color'] != '' ) {
			$inject['styles'][] = 'color: ' . $atts['text_color'] . ';';
		}

		return $inject;

	}

	public function margin( $inject, $atts ) {

		if ( isset( $atts['margin'] ) && $atts['margin'] != '' ) {
			if ( is_array( $atts['margin'] ) ) {
				$atts['margin'] = Cornerstone_Control_Dimensions::simplify( $atts['margin'] );
			}
			$inject['styles'][] = 'margin: ' . $atts['margin'] . ';';
    }

		return $inject;

	}

	public function padding( $inject, $atts ) {

		if ( isset( $atts['padding'] ) && $atts['padding'] != '' ) {
			if ( is_array( $atts['padding'] ) ) {
				$atts['padding'] = Cornerstone_Control_Dimensions::simplify( $atts['padding'] );
			}
			$inject['styles'][] = 'padding: ' . $atts['padding'] . ';';
    }

		return $inject;

	}

	public function border_style( $inject, $atts ) {

		if ( isset( $atts['border_style'] ) && $atts['border_style'] != 'none' ) {

			$inject['styles'][] = 'border-style: ' . $atts['border_style'] . ';';

			if ( isset( $atts['border'] ) && $atts['border'] != '' ) {
				$atts['border_width'] = $atts['border'];
			}

			if ( isset( $atts['border_width'] ) && $atts['border_width'] != '' ) {
				if ( is_array( $atts['border_width'] ) ) {
					$atts['border_width'] = Cornerstone_Control_Dimensions::simplify( $atts['border_width'] );
				}
				$inject['styles'][] = 'border-width: ' . $atts['border_width'] . ';';
			}

			if ( isset( $atts['border_color'] ) && $atts['border_color'] != '' ) {
				$inject['styles'][] = 'border-color: ' . $atts['border_color'] . ';';
			}

		}

		return $inject;

	}

	public function visibility( $inject, $atts ) {

		if ( isset( $atts['visibility'] ) ) {

			if ( is_array( $atts['visibility'] ) ) {
				$visibilty_classes = $atts['visibility'];
			} else {
				$visibilty_classes = explode(' ', $atts['visibility'] );
			}

			$visibilty_classes = CS()->common()->classMap( 'visibility', $visibilty_classes );

			if ( count( $visibilty_classes ) > 0 ) {
				$inject['classes'] = array_merge( $inject['classes'], $visibilty_classes );
			}

    }

		return $inject;

	}

	public function text_align( $inject, $atts ) {

		if ( isset( $atts['text_align'] ) && $atts['text_align'] != 'none' ) {
			$inject['classes'][] = CS()->common()->classMap( 'text_align', $atts['text_align'] );
		}

		return $inject;

	}

	public function class_handler( $inject, $atts ) {

		if ( isset( $atts['class'] ) ) {
			$inject['classes'][] = $atts['class'];
		}

		return $inject;

	}

	public function style( $inject, $atts ) {

		if ( isset( $atts['style'] ) ) {
			$inject['styles'][] = $atts['style'];
		}

		return $inject;

	}

}
