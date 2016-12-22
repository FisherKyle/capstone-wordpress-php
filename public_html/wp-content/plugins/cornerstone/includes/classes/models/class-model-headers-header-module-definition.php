<?php

class Cornerstone_Model_Headers_Header_Module_Definition extends Cornerstone_Plugin_Component {

  public $resources = array();
  public $name = 'headers/header/module-definition';

  public function setup() {

    $records = $this->plugin->loadComponent( 'Headers' )->get_modules();

    if ( ! isset( $records['bar'] ) ) {
      $records['bar'] = array( 'title' => __( 'Bar', 'cornerstone' ) );
    }

    if ( ! isset( $records['container'] ) ) {
      $records['container'] = array( 'title' => __( 'Container', 'cornerstone' ) );
    }

    foreach ($records as $id => $record) {
      $record['id'] = $id;
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

    $record['control-groups'] = array();

    if ( isset( $record['control_groups'] ) ) {
      $record['control-groups'] = $record['control_groups'];
      unset( $record['control_groups'] );
    }

    unset( $record['id'] );
    $resource['attributes'] = $record;

    return $resource;

  }

}
