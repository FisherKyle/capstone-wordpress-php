<?php

class Cornerstone_Element_Reducer extends Cornerstone_Reducer {

  public $walker_class = 'Cornerstone_Element_Walker';

  public function get_type_field( $walker ) {
    return $walker->definition->name();
  }

  public function get_data( $walker ) {
    return $walker->data();
  }

}
