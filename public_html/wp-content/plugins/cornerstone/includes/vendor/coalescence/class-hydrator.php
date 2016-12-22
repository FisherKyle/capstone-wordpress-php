<?php

class TCO_Coalescence_Hydrator {

  public $declarations;
  public $data;
  public $index = array();

  public function __construct( $declarations ) {
    $this->declarations = $declarations;
    $this->generate_index();
  }

  public function generate_index() {

    foreach ( $this->declarations as $node ) {

      $hash = (string) crc32( serialize( array(
          'c' => $node->conditions,
          'k' => $node->keys,
      ) ) );

      if ( ! isset( $this->index[ $hash ] ) ) {
        $this->index[ $hash ] = array(
          'conditions' => $node->conditions,
          'keys'       => $node->keys,
          'nodes'      => array()
        );
      }

      $this->index[ $hash ]['nodes'][] = $node;

    }

  }

  public function config( $options ) {

    $opts = array_merge( array(
        'id_key'       => '_id',
        'class_prefix' => 'el',
    ), $options );

    $this->id_key = $opts['id_key'];
    $this->class_prefix = $opts['class_prefix'];

  }

  public function hydrate( $items ) {

    $this->items = array_filter( $items, array( $this, 'has_id_field' ) );

    foreach ($this->index as $key => $value) {
      $buckets = $this->fill_buckets( $value['conditions'], $value['keys'] );
      foreach ( $this->index[ $key ]['nodes'] as $node ) {
        $node->fill_buckets( $buckets, $this );
      }
    }

    $filled = array();

    foreach ( $this->declarations as $declaration ) {
      $poured = $declaration->pour();
      if ( ! empty( $poured ) ) {
        $filled = array_merge( $filled, $poured );
      }
    }

    unset( $this->declarations );
    $this->data = $filled;
    return $this->data;
  }

  public function get_data() {
    return $this->data;
  }

  public function fill_buckets( $conditions, $keys ) {

    $selections = array();
    $ids = array();
    $buckets = array();

    // Reduce to items that pass all conditions, and keys are present
    $items = $this->bucket_filter( $conditions, $keys );

    // Seperate into unique groups
    $keyed_keys = array_flip( $keys );

    foreach ( $items as $item) {

      $selection = array_intersect_key( $item, $keyed_keys );
      $hash = crc32( serialize( $selection ) );

      if ( isset( $ids[ $hash ] ) ) {
        $ids[ $hash ][] = $item[ $this->id_key ];
      } else {
        $ids[ $hash ] = array( $item[ $this->id_key ] );
        $selections[ $hash ] = $selection;
      }

    }

    // Pair IDs with their unique data selection
    foreach ($ids as $hash => $id_list) {
      $buckets[] = array(
        'ids'  => $id_list,
        'data' => $selections[ $hash ]
      );
    }

    return $buckets;

  }

  public function fill_selector( $selector, $ids ) {

    $selector_templates = explode( ',', $selector );
    $selector_templates = array_map( 'trim', $selector_templates );

    $selectors = array();
    foreach ( $selector_templates as $st ) {
      foreach ( $ids as $id ) {
        $class = '.' . $this->class_prefix . $id;
        $selectors[] = str_replace( '$'. $this->class_prefix, $class, $st );
      }
    }

    return implode( ', ', $selectors );
  }

  public function expand_variables( $template, $data ) {
    $this->replace_hash = $data;
    return preg_replace_callback( '/\$\w+|\${\w+}/', array( $this, 'expander' ), $template );
  }

  public function expander( $matches ) {
    $key = str_replace( '$', '', trim( substr( $matches[0], 1 ), '{}' ) );
    return ( isset( $this->replace_hash[ $key ] ) ) ? $this->replace_hash[ $key ] : '';
  }

  public function bucket_filter( $conditions, $keys ) {

    $items = $this->items;

    foreach ( $conditions as $condition ) {
      $this->filter_key = $condition;
      $items = array_filter( $items, array( $this, 'check_condition' ) );
    }

    foreach ( $keys as $key ) {
      $this->filter_key = $key;
      $items = array_filter( $items, array( $this, 'property_set' ) );
    }

    return $items;
  }

  public function has_id_field( $item ) {
    return ( isset( $item[ $this->id_key ] ) );
  }

  public function property_set( $item ) {
    return ( isset( $item[ $this->filter_key ] ) );
  }
  public function check_condition( $item ) {
    return ( $this->property_set( $item ) && $item[ $this->filter_key ] );
  }

}
