<?php

class Cornerstone_Header {

  protected $id = null;
  protected $title;
  protected $content = array();
  protected $new;
  protected $dirty;

  public function __construct( $post ) {

    if ( is_array( $post ) ) {
      if ( isset( $post['id'] ) ) {
        $post = $post['id'];
      } else {
        $this->create_new( $post );
      }
    } else {
      $this->load_from_post( $post );
    }

  }

  protected function create_new( $data ) {

    $this->set_title( isset( $data['title'] ) ? $data['title'] : false );
    // TODO: Populate from incoming data.

    $this->set_content( array(
      'regions' => isset( $data['regions'] ) ? $data['regions'] : array(),
      'settings' => isset( $data['settings'] ) ? $data['settings'] : array()
    ) );

  }

  protected function load_from_post( $post ) {

    if ( is_int( $post ) ) {
      $post = get_post( $post );
    }

    if ( ! is_a( $post, 'WP_POST' ) ) {
      throw new Exception( 'Unable to load header from post.' );
    }

    if ( 'cs_header' !== $post->post_type ) {
      throw new Exception( 'Attempted to load header from incorrect post_type.' );
    }

    $this->id = $post->ID;
    $this->set_title( $post->post_title ? $post->post_title : '' );
    $this->set_content( cs_maybe_json_decode( $post->post_content ) );

  }

  protected function set_content( $content ) {

    $content = wp_parse_args( is_array( $content ) ? $content : array(), array(
      'regions' => array(),
      'settings' => array(),
    ) );

    $this->set_regions( $content['regions'] );
    $this->set_settings( $content['settings'] );

  }

  public function save() {

    $args = array(
      'post_title' => $this->get_title(),
      'post_type'  => 'cs_header',
      'post_content' => wp_slash( json_encode( array(
        'regions' => $this->get_regions(),
        'settings' => $this->get_settings()
      ) ) )
    );

    if ( is_int( $this->id ) ) {
      $args['ID'] = $this->id;
    }

    $id = wp_insert_post( $args );

    if ( 0 === $id || is_wp_error( $id ) ) {
      throw new Exception( "Unable to update header: $id" );
    }

    $this->load_from_post( (int) $id );

    return $this->serialize();

  }

  public function get_title() {
    return $this->title;
  }

  public function get_regions() {
    if ( ! isset( $this->content['regions'] ) ) {
      $this->content['regions'] = array();
    }
    return $this->content['regions'];
  }

  public function get_settings() {
    if ( ! isset( $this->content['settings'] ) ) {
      $this->content['settings'] = array();
    }
    return $this->content['settings'];
  }

  public function get_assignments() {
    $assignments = isset( $this->content['settings']['assignments'] ) ? $this->content['settings']['assignments'] : array();
    return array_map( array( $this, 'decorate_assignments' ), $assignments );
  }

  public function decorate_assignments( $assignment ) {

    $parts = explode( ':', $assignment );
    $key = $parts[0];
    $id = ( isset( $parts[1] ) ) ? $id : null;
    $label = '';

    return array( 'type' => $key, 'label' => $label );
  }

  public function serialize() {
    return array(
      'id' => $this->id,
      'title' => $this->get_title(),
      'regions'  => $this->get_regions(),
      'settings' => $this->get_settings(),
      'assignments' => $this->get_assignments(),
    );
  }

  public function set_title( $title ) {
    return $this->title = sanitize_text_field( $title, __( 'Untitled Header', 'cornerstone' ) );
  }

  public function set_settings( $settings ) {
    $this->content['settings'] = $settings;
  }

  public function set_regions( $regions ) {
    $this->content['regions'] = $regions;
  }

  public function delete() {
    return wp_delete_post( $this->id, true );
  }
}
