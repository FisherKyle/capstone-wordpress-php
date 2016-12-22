<?php

class Cornerstone_Element_Walker extends Cornerstone_Walker {

  public $child_key = 'elements';
  public $definition = null;

  public function setup() {
    $type = isset( $this->data['_type'] ) ? $this->data['_type'] : 'undefined';
    $this->definition = CS()->component( 'Element_Orchestrator' )->get( $type );
  }

}
