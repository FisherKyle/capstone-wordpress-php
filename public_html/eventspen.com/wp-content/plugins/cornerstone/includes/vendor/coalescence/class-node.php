<?php

class TCO_Coalescence_Node {

  public $value;
  public $parent;
  public $nodes = array();

  public function __construct( $value = null, $parent = null ) {
    $this->value = $value;
    $this->parent = $parent;
  }

  public function add( $value, $class ) {
    $node = new $class( $value, $this );
    $this->nodes[] = $node;
    return $node;
  }

  public function get_declarations() {

    $nodes = array();

    foreach ( $this->nodes as $node ) {
      if ( ! is_a( $node, 'TCO_Coalescence_Declaration' ) ) {
        $nodes = array_merge( $nodes, $node->get_declarations() );
        continue;
      }

      $nodes[] = $node;
    }

    array_walk( $nodes, array( $this, 'transform' ) );

    unset( $this->nodes );
    return $nodes;

  }

  public function transform( $node ) {

    unset( $node->parent );

    // Conditions
    if ( preg_match( '/@if \$(\w+)|@if \${(\w+)}/', $this->value, $match ) ) {
      $node->add_condition( $match[1] );
      return $node;
    }

    // Directives (only supports media queries)
    if ( preg_match( '/@media/', $this->value ) ) {
      $node->set_directive( $this->value );
      return $node;
    }

    // Selectors
    $node->update_selector( $this->value );
    return $node;

  }

}
