<?php

class Cornerstone_Reducer {

  public $data_sets = array();
  public $walker_class = 'Cornerstone_Walker';
  public $set_ids = array();

  public function __construct( $items, $walker_class = null ) {

    if ( $walker_class && class_exists( $walker_class ) ) {
      $this->walker_class = $walker_class;
    }

    $walker = new $this->walker_class( $items );

    $walker->walk( array( $this, 'reduce_elements' ) );
    $this->sort();

  }

  public function get_type_field( $walker ) {
    $item = $walker->data();
    return $item['_type'];
  }

  public function get_id_field( $walker ) {
    return '_id';
  }

  public function get_data( $walker ) {
    return $walker->data();
  }

  public function sort() {
    ksort($this->data_sets);
  }

  public function reduce_elements( $walker ) {

    $type = $this->get_type_field( $walker );

    if ( !isset( $this->data_sets[$type] ) ) {
      $this->data_sets[$type] = array();
    }

    $data = $this->get_data( $walker );

    $id_field = $this->get_id_field( $walker );

    if ( !isset( $data[$id_field] ) ) {
      return;
    }
    $id = $data[$id_field];
    unset( $data[$id_field] );
    unset( $data[$walker->child_key]);

    $key = md5(serialize($data));

    if ( !isset( $this->set_ids[$key] ) ) {
      $this->set_ids[$key] = array();
    }

    $this->set_ids[$key][] = $id;
    $this->data_sets[$type][$key] = $data;

  }

  public function iterate( $callable ) {
    foreach ( $this->data_sets as $type => $set ) {
      foreach ( $set as $id_key => $reduced_set ) {
        call_user_func_array( $callable, array( $type, $this->set_ids[$id_key], $reduced_set ) );
      }
    }
  }

}
