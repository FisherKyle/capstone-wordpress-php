<?php

class TCO_Coalescence_Template {

  public $template;
  public $hydrator;
  public $declarations;
  public $writer;
  public $offset = 0;

  public function __construct( $template ) {
    $this->template = $template;
    $this->clean();
    $this->parse();
  }

  public function clean() {

    // Strip comments
    $css = preg_replace( '#/\*.*?\*/#s', '', $this->template );

    // Preserve variables
    $css = preg_replace( '/(\${\w+})/', '%%$1%%', $css );

    // Remove whitespace
    $css = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $css );
    $css = preg_replace( '/\s\s+(.*)/', '$1', $css );

    // Restore variables
    $this->template = preg_replace( '/%%(\${\w+})%%/', '$1', $css );
  }

  public function parse() {

    $parsed = false;
    $active_node = new TCO_Coalescence_Node();

    while ( ! $parsed ) {

      // Declaration
      if ( $this->consume( '/^([^{}]+?(?:\${\w+})*[^{}]*?);/' ) ) {
        $active_node->add( $this->match, 'TCO_Coalescence_Declaration' );
        continue;
      }

      // Open
      if ( $this->consume( '/^([^;{}]+?(?:\${\w+}.+?)*?){/' ) ) {
        $active_node = $active_node->add( $this->match, 'TCO_Coalescence_Node' );
        continue;
      }

      // Close
      if ( $this->consume( '/^.*?(})/' ) ) {
        $active_node = $active_node->parent;
        continue;
      }

      $parsed = true;

    }

    $this->declarations = $active_node->get_declarations();

  }

  public function consume( $regex ) {
    $offset = preg_match( $regex, substr( $this->template, $this->offset ), $matches );
    if ( empty( $matches ) ) {
      return false;
    }
    $this->offset += strlen( $matches[0] );
    $this->match = $matches[1];
    return true;
  }

  public function hydrate( $items, $options = array() ) {
    $this->hydrator = new TCO_Coalescence_Hydrator( $this->declarations );
    $this->hydrator->config( $options );
    return $this->hydrator->hydrate( $items );
  }

  public function write( $echo = true ) {
    $formation = new TCO_Coalescence_Formation;
    $formation->add_items( $this->hydrator->get_data() );
    $output = $formation->write();

    if ( $echo ) {
      echo $output;
    }
    return $output;
  }

}
