<?php

class Cornerstone_Controller_Options extends Cornerstone_Plugin_Component {

  public function save( $data ) {

    $options = $this->plugin->loadComponent( 'Options_Bootstrap' );
    $response = array( 'updates' => array() );

    if ( isset( $data['updates'] ) ) {
      foreach ($data['updates'] as $key => $value) {

        $response['updates'][ $key ] = $value;
        $options->update_value( $key, $value );
      }
    }

    return $response;
  }

}
