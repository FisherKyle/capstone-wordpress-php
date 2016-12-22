<?php

class TCO_Coalescence {

  public $config;
  public $templates;
  public $items;
  public $formation;

  public function __construct( $config = array() ) {

    $this->config = array_merge( array(
        'id_key'       => '_id',
        'class_prefix' => 'el',
    ), $config );

    $this->reset();
  }

  public function reset() {
    $this->formation = new TCO_Coalescence_Formation;
    $this->templates = array();
    $this->items = array();
  }

  public function add_template( $type, $template ) {

    $this->templates[$type] = new TCO_Coalescence_Template( $template );
  }

  public function add_items( $type, $items ) {

    if ( ! isset( $this->items[ $type ] ) ) {
      $this->items[ $type ] = array();
    }

    $this->items[ $type ] = array_merge( $this->items[ $type ], $items );

  }

  public function hydrate_template( $type ) {

    $items = $this->templates[ $type ]->hydrate( $this->items[ $type ], $this->config );

    if ( $items && is_array( $items ) ) {
      $this->formation->add_items( $items );
    }

  }

  public function hydrate() {
    $types = array_keys( $this->templates );
    foreach ( $types as $type) {
      $this->hydrate_template( $type );
    }
  }

  public function run() {

    if ( $this->formation->is_empty() ) {
      $this->hydrate();
    }

    return $this->formation->write();
  }

}
