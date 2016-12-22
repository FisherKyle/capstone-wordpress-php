<?php

class Cornerstone_Customizer_Manager extends Cornerstone_Plugin_Component {

  /**
   * List of option names and default values
   * @var array
   */
  private $defaults;

  /**
   * Register hooks
   */
  public function setup() {

    if ( apply_filters( 'cornerstone_options_use_native', true ) ) {
      add_action( 'customize_register', array( $this, 'register' ) );
    }

    $this->defaults = $this->plugin->config( 'options/defaults' );

    if ( defined('WP_DEBUG') && WP_DEBUG ) {
      add_shortcode( 'cornerstone_customizer_debug', array( $this, 'debugShortcode' ) );
    }

    add_action( 'customize_controls_print_footer_scripts', array( $this, 'customize_scripts' ) );
    add_action( 'customize_preview_init', array( $this, 'customize_preview_scripts' ) );
    add_action( 'customize_controls_print_styles', array( $this, 'customize_styles' ) );

  }

  /**
   * Return all registered options as an array of keys.
   * @return array
   */
  public function optionList() {
    return array_keys( $this->defaults );
  }

  /**
   * Get all of our registered options and apply their defaults
   * @return array
   */
  public function optionData() {
    $retrieved = array();
    foreach ($this->defaults as $name => $default) {
      $retrieved[$name] = get_option( $name, $default );
    }
    return $retrieved;
  }

  public function debugShortcode() {
    ob_start();
    echo '<pre>';
    print_r( $this->optionData() );
    echo '</pre>';
    return ob_get_clean();
  }

  public function customize_styles() {
    wp_enqueue_style( 'cs-customize-css', $this->plugin->css( 'admin/customizer' ), array(), $this->plugin->version() );
  }

	public function customize_scripts() {

		// Register
		wp_register_script(
			'cs-customize',
			$this->plugin->js( 'admin/customize' ),
			array(),
			$this->plugin->version(),
			true
		);

		// Enqueue with Data
		wp_localize_script( 'cs-customize', 'csCustomizeData', array(
			'buttonText' => __( 'Edit', 'cornerstone' ),
			'logo' => $this->view( 'svg/logo-flat-custom', false ),
		) );
		wp_enqueue_script( 'cs-customize' );

	}

	public function customize_preview_scripts() {

		// Register
		wp_register_script(
			'cs-customize-preview',
			$this->plugin->js( 'admin/customize-preview' ),
			array(),
			$this->plugin->version(),
			true
		);

		// Enqueue with Data
		wp_localize_script( 'cs-customize-preview', 'csCustomizePreviewData', array() );
		wp_enqueue_script( 'cs-customize-preview' );

	}

  /**
   * Register Customizer Sections, Settings, and Controls.
   */
  public function register( $wp_customize ) {

    $cs = array();


    //
    // Lists.
    //

    $list_on_off = array(
      '1' => 'On',
      ''  => 'Off'
    );

    $list_button_styles = array(
      'real'        => __( '3D', 'cornerstone' ),
      'flat'        => __( 'Flat', 'cornerstone' ),
      'transparent' => __( 'Transparent', 'cornerstone' )
    );

    $list_button_shapes = array(
      'square'  => __( 'Square', 'cornerstone' ),
      'rounded' => __( 'Rounded', 'cornerstone' ),
      'pill'    => __( 'Pill', 'cornerstone' )
    );

    $list_button_sizes = array(
      'mini'    => __( 'Mini', 'cornerstone' ),
      'small'   => __( 'Small', 'cornerstone' ),
      'regular' => __( 'Regular', 'cornerstone' ),
      'large'   => __( 'Large', 'cornerstone' ),
      'x-large' => __( 'Extra Large', 'cornerstone' ),
      'jumbo'   => __( 'Jumbo', 'cornerstone' )
    );



    //
    // Options - Layout.
    //

    $cs[] = array( 'cs_v1_base_margin', 'text', __( 'Base Margin', 'cornerstone' ), 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_base_margin_extended', 'select', __( 'Extended Base Margin', 'cornerstone' ), $list_on_off, 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_container_width', 'text', __( 'Container Width', 'cornerstone' ), 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_container_max_width', 'text', __( 'Container Max Width', 'cornerstone' ), 'cs_v1_customizer_section' );



    //
    // Options - Typography.
    //

    $cs[] = array( 'cs_v1_text_color', 'color', __( 'Text Color', 'cornerstone' ), 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_link_color', 'color', __( 'Link Color', 'cornerstone' ), 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_link_color_hover', 'color', __( 'Link Hover Color', 'cornerstone' ), 'cs_v1_customizer_section' );



    //
    // Options - Buttons.
    //

    $cs[] = array( 'cs_v1_button_style', 'select', __( 'Button Style', 'cornerstone' ), $list_button_styles, 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_button_shape', 'select', __( 'Button Shape', 'cornerstone' ), $list_button_shapes, 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_button_size', 'select', __( 'Button Size', 'cornerstone' ), $list_button_sizes, 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_button_color', 'color', __( 'Button Text', 'cornerstone' ), 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_button_bg_color', 'color', __( 'Button Background', 'cornerstone' ), 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_button_border_color', 'color', __( 'Button Border', 'cornerstone' ), 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_button_bottom_color', 'color', __( 'Button Bottom', 'cornerstone' ), 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_button_color_hover', 'color', __( 'Button Text Hover', 'cornerstone' ), 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_button_bg_color_hover', 'color', __( 'Button Background Hover', 'cornerstone' ), 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_button_border_color_hover', 'color', __( 'Button Border Hover', 'cornerstone' ), 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_button_bottom_color_hover', 'color', __( 'Button Bottom Hover', 'cornerstone' ), 'cs_v1_customizer_section' );



    //
    // Options - Custom
    //

    $cs[] = array( 'cs_v1_custom_css', 'cscodeeditor', __( 'Custom Code', 'cornerstone' ), array( 'buttonText' => __( 'Edit Global CSS', 'cornerstone' ), 'mode' => 'css' ), 'cs_v1_customizer_section' );
    $cs[] = array( 'cs_v1_custom_js', 'cscodeeditor', __( 'Custom Code', 'cornerstone' ), array( 'buttonText' => __( 'Edit Global JavaScript', 'cornerstone' ), 'mode' => 'javascript', 'lint' => true ), 'cs_v1_customizer_section' );


    //
    // Output - Section.
    //

    $wp_customize->add_section( 'cs_v1_customizer_section', array(
      'title'       => __( 'Cornerstone', 'cornerstone' ),
      'description' => __( '<p><strong>How does Cornerstone utilize the Customizer?</strong><p>Cornerstone attempts to follow your theme&apos;s provided styling. If suitable styling isn&apos;t found in the theme, you can use the options below to set a compatibility baseline.</p><p>If you notice the settings below are not making a visible difference, chances are that your theme is handling the styling in that area. Cornerstone prefers to give the theme precedence when possible.</p><p>Finally, changing settings here won&apos;t affect elements that you have already configured directly (buttons for example).', 'cornerstone' ),
      'priority'    => null,
    ) );


    //
    // Output - Controls.
    //

    foreach ( $cs as $control ) {

      if ( ! isset( $this->defaults[$control[0]] ) ) {
        continue;
      }

      $wp_customize->add_setting( $control[0], array(
        'type'      => 'option',
        'default'   => $this->defaults[$control[0]],
        'transport' => 'refresh'
      ));

      static $i = 1;

      if ( $control[1] == 'radio' ) {

        $wp_customize->add_control( $control[0], array(
          'type'     => $control[1],
          'label'    => $control[2],
          'section'  => $control[4],
          'priority' => $i,
          'choices'  => $control[3]
        ));

      } elseif ( $control[1] == 'select' ) {

        $wp_customize->add_control( $control[0], array(
          'type'     => $control[1],
          'label'    => $control[2],
          'section'  => $control[4],
          'priority' => $i,
          'choices'  => $control[3]
        ));

      } elseif ( $control[1] == 'text' ) {

        $wp_customize->add_control( $control[0], array(
          'type'     => $control[1],
          'label'    => $control[2],
          'section'  => $control[3],
          'priority' => $i
        ));

      } elseif ( $control[1] == 'cstextarea' ) {

        $wp_customize->add_control(
          new Cornerstone_Customize_Control_Textarea( $wp_customize, $control[0], array(
            'label'    => $control[2],
            'section'  => $control[3],
            'settings' => $control[0],
            'priority' => $i
          ))
        );

      } elseif ( $control[1] == 'cscodeeditor' ) {

        $wp_customize->add_control(
          new Cornerstone_Customize_Control_Code_Editor( $wp_customize, $control[0], array(
            'label'    => $control[2],
            'section'  => $control[4],
            'settings' => $control[0],
            'options'  => $control[3],
            'priority' => $i
          ))
        );

      } elseif ( $control[1] == 'checkbox' ) {

        $wp_customize->add_control( $control[0], array(
          'type'     => $control[1],
          'label'    => $control[2],
          'section'  => $control[3],
          'priority' => $i
        ));

      } elseif ( $control[1] == 'color' ) {

        $wp_customize->add_control(
          new Cornerstone_Customize_Control_Huebert( $wp_customize, $control[0], array(
            'label'    => $control[2],
            'section'  => $control[3],
            'settings' => $control[0],
            'priority' => $i
          ))
        );

      } elseif ( $control[1] == 'image' ) {

        $wp_customize->add_control(
          new WP_Customize_Image_Control( $wp_customize, $control[0], array(
            'label'    => $control[2],
            'section'  => $control[3],
            'settings' => $control[0],
            'priority' => $i
          ))
        );

      }

      $i++;

    }
  }

}
