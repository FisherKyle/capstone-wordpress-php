<?php

class Cornerstone_Coalescence extends Cornerstone_Plugin_Component {

  public $type_sets = array();
  public $coalescence;
  public $template_loader = '__return_empty_string';
  public $config;

  public function setup() {
    require_once( $this->path( 'includes/vendor/coalescence/class-coalescence.php' ) );
    require_once( $this->path( 'includes/vendor/coalescence/class-formation.php' ) );
    require_once( $this->path( 'includes/vendor/coalescence/class-declaration.php' ) );
    require_once( $this->path( 'includes/vendor/coalescence/class-hydrator.php' ) );
    require_once( $this->path( 'includes/vendor/coalescence/class-node.php' ) );
    require_once( $this->path( 'includes/vendor/coalescence/class-template.php' ) );
    $this->set_config();
  }

  public function set_config( $config = array() ) {

    $this->config = array_merge( array(
        'id_key'       => '_id',
        'type_key'     => '_type',
        'class_prefix' => 'el',
    ), $config );

  }

  public function set_template_loader( $callable ) {
    if ( is_callable( $callable ) ) {
      $this->template_loader = $callable;
    }
  }

  public function prepare( $items ) {

    $this->type_sets = array();
    $this->coalescence = new TCO_Coalescence( $this->config );

    $walker = new Cornerstone_Walker( $items );

    $walker->walk( array( $this, 'reduce_items' ) );

    ksort($this->type_sets);

    foreach ( $this->type_sets as $type => $set ) {
      $this->coalescence->add_items( $type, $set );
    }

  }

  public function get_type_field( $walker ) {
    $item = $walker->data();
    return $item[ $this->config['type_key'] ];
  }

  public function get_id_field( $walker ) {
    return '_id';
  }

  public function get_data( $walker ) {
    return $walker->data();
  }

  public function reduce_items( $walker ) {

    $type = $this->get_type_field( $walker );

    $data = $this->get_data( $walker );

    unset( $data[ $walker->child_key ] );

    if ( !isset( $this->type_sets[$type] ) ) {
      $this->type_sets[$type] = array();
      $this->coalescence->add_template( $type, call_user_func( $this->template_loader, $type, $data ) );
    }

    $this->type_sets[$type][] = $data;

  }

  public function run() {
    return $this->coalescence->run();
  }

}
