<?php

class TCO_Coalescence_Declaration {

  public $value;
  public $parent;
  public $selector = null;
  public $directive = null;
  public $conditions = array();
  public $keys = array();
  public $buckets = array();

  public function __construct( $value = null, $parent = null ) {
    $this->value = $value;
    $this->add_keys( $value );
    $this->parent = $parent;
  }

  public function add_keys( $value ) {

    preg_match_all( '/\$(\w+)|\${(\w+)}/', $value, $matches );
    $keys = array_merge( array(), array_filter( $matches[1] ) );
    $keys = array_merge( $keys,   array_filter( $matches[2] ) );
    $keys = array_merge( $this->keys, $keys );
    $this->keys = array_unique( $keys );

  }

  public function set_directive( $directive ) {
    $this->directive = $directive;
    $this->add_keys( $directive );
  }

  public function add_condition( $condition ) {
    if ( ! in_array( $condition, $this->conditions, true ) ) {
      $this->conditions[] = $condition;
    }
  }

  public function update_selector( $selector ) {
    if ( ! $this->selector ) {
      $this->selector = $selector;
    }
  }

  public function fill_buckets( $buckets, $hydrator ) {

    $this->buckets = array();

    foreach ( $buckets as $bucket) {
      $this->buckets[] = array(
        'selector'     => $hydrator->fill_selector( $this->selector, $bucket['ids'] ),
        'directive'    => $hydrator->expand_variables( $this->directive, $bucket['data'] ),
        'declarations' => $hydrator->expand_variables( $this->value, $bucket['data'] )
      );
    }

  }

  public function pour() {
    return $this->buckets;
  }

}
