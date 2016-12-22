<?php

class Cornerstone_Model_Headers_Header extends Cornerstone_Plugin_Component {

  public $dependencies = array( 'Headers' );
  public $resources = array();
  public $name = 'headers/header';

  public function setup() {

    $posts = get_posts( array(
      'post_type' => 'cs_header',
      'post_status' => 'any',
      'orderby' => 'type',
      'posts_per_page' => 2500
    ) );

    $records = array();

    foreach ($posts as $post) {
      $header = new Cornerstone_Header( $post );
      $records[] = $header->serialize();
    }

    // Filter $records here?

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

  public function create( $params ) {
    $atts = $this->atts_from_request( $params );
    $header = new Cornerstone_Header( $atts );
    return $this->make_response( $this->to_resource( $header->save() ) );
  }

  protected function atts_from_request( $params ) {

    if ( ! isset( $params['model'] ) || ! isset( $params['model']['data'] ) || ! isset( $params['model']['data']['attributes'] ) ) {
      throw new Exception( 'Request to Header model missing attributes.' );
    }

    $atts = $params['model']['data']['attributes'];

    if ( isset( $params['model']['data']['id'] ) ) {
      $atts['id'] = $params['model']['data']['id'];
    }

    return $atts;
  }

  public function delete( $params ) {
    $atts = $this->atts_from_request( $params );

    if ( ! $atts['id'] ) {
      throw new Exception( 'Attempting to delete Header without specifying an ID.' );
    }

    $id = (int) $atts['id'];

    $header = new Cornerstone_Header( $id );
    $header->delete();

    return $this->make_response( array( 'id' => $id, 'type' => $this->name ) );
  }

  public function update( $params ) {

    $atts = $this->atts_from_request( $params );

    if ( ! $atts['id'] ) {
      throw new Exception( 'Attempting to update Header without specifying an ID.' );
    }

    $id = (int) $atts['id'];

    $header = new Cornerstone_Header( $id );

    if ( isset( $atts['regions'] ) ) {
      $header->set_regions( $atts['regions'] );
    }

    return $this->make_response( $this->to_resource( $header->save() ) );
  }

}
