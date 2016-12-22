<?php

class Cornerstone_Model_Headers_Header_Template extends Cornerstone_Plugin_Component {

  public $resources = array();
  public $name = 'headers/header/template';

  public function setup() {

    $records = apply_filters( 'cornerstone_header_templates', array() );

    if ( empty( $records ) ) {

      // TODO: Remove <<<
      $records[] = array(
        'id' => 'default',
        'title' => __( 'Sample', 'cornerstone' ),
        'regions' => $this->plugin->config( 'headers/sample-regions' ),
        'settings' => array()
      );
      // >>>

      $records[] = array(
        'id' => 'blank',
        'title' => __( 'Blank', 'cornerstone' ),
        'regions' => array(),
        'settings' => array()
      );



    }

    foreach ($records as $record) {
      $this->resources[] = $this->to_resource( $record );
    }
  }

  public function query( $params ) {

    // Find All
    if ( empty( $params ) || ! isset( $params['query'] ) ) {
      return $this->make_response( $this->resources );
    }

    $queried = array();
    $this->included = array();

    foreach ( $this->resources as $resource) {
      if ( $this->query_match( $resource, $params['query'] ) ) {
        $queried[] = $resource;
      } else {
        $this->included[] = $resource;
      }
    }

    return $this->make_response( ( isset( $params['single'] ) && isset( $queried[0] ) ) ? $queried[0] : $queried );

  }


  public function make_response( $data ) {

    $response = array(
      'data' => $data
    );

    if ( isset( $this->included ) ) {
      $response['included'] = $this->included;
    }

    return $response;

  }

  public function query_match( $resource, $query ) {

    if ( isset( $query['id'] ) ) {
      $query['id'] = (int) $query['id'];
    }

    foreach ( $query as $key => $value ) {

      // Check relationships
      if ( isset( $resource['relationships'][ $key ] )  ) {

        if ( ! isset( $resource['relationships'][ $key ]['data'] ) ) {
          return false;
        }

        $data = $resource['relationships'][ $key ]['data'];

        if ( isset( $data['id'] ) && $value !== $data['id'] ) {
          return false;
        } else {
          foreach ( $data as $child ) {
            if ( isset( $data['id'] ) && $value === $data['id'] ) {
              return true;
            }
          }
          return false;
        }

      } else {
        if ( ! isset( $resource[ $key ] ) || $resource[ $key ] !== $value ) {
          return false;
        }
      }

    }

    return true;
  }

  public function to_resource( $record ) {

    $resource = array(
      'id' => $record['id'],
      'type' => $this->name
    );

    unset( $record['id'] );
    $resource['attributes'] = $record;

    return $resource;

  }

}
