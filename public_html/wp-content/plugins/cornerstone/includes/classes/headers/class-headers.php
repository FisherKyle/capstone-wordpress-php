<?php

class Cornerstone_Headers extends Cornerstone_Plugin_Component {

  public $header_styles = '';
  public $dependencies = array( 'Coalescence', 'Header_Builder' );
  public $modules = array();
  public $modules_registered = false;

  public function setup() {
    $this->register_post_type();
    add_action( 'template_redirect', array( $this, 'identify_header' ) );
  }

  public function add_styling( $header, $class_prefix, $template_loader ) {

    $coalescence = $this->plugin->component( 'Coalescence' );
    $coalescence->set_template_loader( $template_loader );
    $coalescence->set_config( array( 'class_prefix' => 'hm' ) );
    $coalescence->prepare( array(
      '_type' => 'root',
      'modules' => $header['modules']
    ) );

    $this->header_styles = $coalescence->run();

  }

  public function get_fallback_header_data() {
    return apply_filters( 'cornerstone_fallback_header_data', array(
      'modules' => array(),
      'settings' => array(),
    ) );
  }

  public function get_active_header_data() {

    $id = $this->plugin->loadComponent('Header_Assignments')->locate_assignment();

    try {
      $header = new Cornerstone_Header( $id );
    } catch( Exception $e ) {
      return $this->get_fallback_header_data();
    }

    $modules = array();

    $regions = $header->get_regions();
    $modules = array();
    foreach ($regions as $name => $region ) {
      $region['_type'] = 'region';
      $region['region'] = $name;
      $region['_modules'] = $this->populate_module_ids( $region['_modules'] );
      $modules[] = $region;
    }

    return array(
      'id' => $id,
      'modules' => $modules,
      'settings' => $header->get_settings(),
    );
  }

  public function populate_module_ids( $modules ) {

    static $count = 0;

    foreach ( $modules as $index => $module ) {

      $modules[$index]['_id'] = ++$count;

      if ( isset( $module['_modules'] ) ) {
        $modules[$index]['_modules'] = x_module_id_populator( $module['_modules'] );
      }

    }

    return $modules;

  }

  public function get_styles() {
    return $this->header_styles;
  }

  public function register_post_type() {

    register_post_type( 'cs_header', array(
        'public'          => false,
        'capability_type' => 'page',
        'supports'        => false
    ) );

  }

  public function register_modules( $modules ) {

    foreach ($modules as $name => $module) {
      $this->register_module( $name, $module );
    }

  }

  public function register_module( $name, $module ) {

    $module = wp_parse_args( $module, array(
      'title'    => '',
      'defaults' => array(),
      'conditions' => array(),
      'controls' => array(),
      'control_groups' => array()
    ) );

    $module['controls'] = $this->normalize_controls( $module['controls'] );

    $this->modules[ $name ] = $module;
  }

  public function normalize_controls( $controls ) {

    $updated_controls = array();

    foreach ( $controls as $control ) {

      $control = wp_parse_args( $control, array(
        'type'       => '',
        'keys'       => array(),
        'label'      => '',
        'conditions' => array(),
        'options'    => array(),
        'group'      => '',
      ) );

      if ( isset( $control['controls'] ) ) {
        $control['controls'] = $this->normalize_controls( $control['controls'] );
      }

      if ( isset( $control['title'] ) ) {
        $control['label'] = $control['title'];
        unset($control['title']);
      }

      if ( isset( $control['key'] ) ) {
        $control['keys']['value'] = $control['key'];
        unset($control['key']);
      }

      if ( isset( $control['condition'] ) ) {
        $control['conditions'][] = $control['condition'];
        unset( $control['condition'] );
      }

      $control['conditions'] = $this->normalize_conditions( $control['conditions'] );
      $updated_controls[] = $control;

    }

    return $updated_controls;
  }

  public function normalize_conditions( $unnormalized ) {

    $ops = array( '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN' );

    $conditions = array();

    if ( isset( $unnormalized ) && is_array( $unnormalized ) ) {
      foreach ( $unnormalized as $condition ) {

        if ( isset( $condition['option'] ) && isset( $condition['value'] ) ) {

          $conditions[] = array(
            'option' => $condition['option'],
            'value'  => $condition['value'],
            'op'     => ( isset( $condition['op'] ) && in_array( $condition['op'], $ops, true ) ) ? $condition['op'] : '='
          );

        } else {
          // Add shorthand
          $keys = array_keys( $condition );
          $conditions[] = array(
            'option' => $keys[0],
            'value'  => $condition[ $keys[0] ],
            'op' => '='
          );
        }

      }
    }

    return $conditions;

  }

  public function unregister_module( $name ) {
    unset( $this->modules[ $name ] );
  }

  public function module_registration() {

    if ( $this->modules_registered ) {
      return;
    }

    do_action( 'cornerstone_register_bar_modules' );

    $this->modules_registered = true;

  }

  public function get_modules() {
    $this->module_registration();
    return $this->modules;
  }

  public function get_module_defaults( $name ) {
    $modules = $this->get_modules();
    return $modules[$name]['defaults'];
  }


}
