<?php

class Cornerstone_Header_Assignments extends Cornerstone_Plugin_Component {

  public function setup() {
    add_filter( 'cornerstone_option_model_whitelist', array( $this, 'whitelist_options' ) );
    add_filter( 'cornerstone_option_model_load_transform', array( $this, 'load_transform' ) );
    add_filter( 'cornerstone_option_model_save_transform', array( $this, 'save_transform' ) );
  }

  public function whitelist_options( $keys ) {
    $keys[] = 'cornerstone_header_assignments';
    return $keys;
  }

  public function load_transform( $data ) {

    $data = json_decode( wp_unslash( $data ), true );

    $uncompacted = array();

    if ( isset( $data['global'] ) ) {
      $uncompacted['global'] = $data['global'];
    }

    if ( isset( $data['indexes'] ) ) {
      foreach ($data['indexes'] as $key => $value) {
        $uncompacted[ 'indexes::' . $key] = $value;
      }
    }

    if ( isset( $data['post_types'] ) ) {
      foreach ($data['post_types'] as $key => $value) {
        $uncompacted[ 'post_type::' . $key] = $value;
      }
    }

    if ( isset( $data['meta'] ) && isset( $data['meta']['post_types'] ) && isset( $data['posts'] ) ) {
      foreach ($data['meta']['post_types'] as $key => $value) {
        foreach ($value as $id) {
          if ( isset( $data['posts'][ 'post-' . $id] ) ) {
            $uncompacted[ 'post_type::' . $key . '::' . $id ] = $data['posts'][ 'post-' . $id];
          }
        }
      }
    }

    ksort( $uncompacted );

    if ( empty($uncompacted)) {
      $uncompacted = new stdClass;
    }
    return $uncompacted;
  }

  public function assignment_schema() {
    return array(
      'global' => null,
      'indexes' => array(),
      'post_types' => array(),
      'posts' => array(),
      'meta' => array(
        'post_types' => array()
      )
    );
  }
  public function save_transform( $data ) {

    ksort($data);

    $compact = $this->assignment_schema();

    foreach ($data as $key => $value) {

      $address = explode( '::', $key );

      if ( 'global' === $key) {
        $compact['global'] = $value;
      } elseif ( 'indexes' === $address[0] ) {
        $compact['indexes'][ $address[1] ] = $value;
      } elseif ( 'post_type' === $address[0] ) {
        if ( ! isset( $address[2] ) ) {
          $compact['post_types'][ $address[1] ] = $value;
        } else {
          $compact['posts'][ 'post-' . $address[2] ] = $value;
          if ( ! isset( $compact['meta']['post_types'][$address[1]] )) {
            $compact['meta']['post_types'][$address[1]] = array();
          }
          $compact['meta']['post_types'][$address[1]][] = $address[2];
        }

      }

    }

    return wp_slash( json_encode( $compact ) );
  }


  public function get_assign_contexts() {

    $groups = array(
      'indexes' => array(
        'title' => false,
        'tag' => 'Indexes',
        'items' => array(
          array(
            'value' => 'front',
            'title' => 'Front Page',
          ),
          array(
            'value' => 'home',
            'title' => 'Posts Page',
          )
        )
      )
    );

    $post_types = get_post_types( array(
      'public'   => true,
      'show_ui' => true,
      'exclude_from_search' => false
    ) , 'objects' );

    unset( $post_types['attachment'] );

    $posts = get_posts( array(
      'post_type' => array_keys( $post_types ) ,
      'orderby' => 'type',
      'posts_per_page' => 2500
    ) );

    foreach ($posts as $post) {

      $post_type_obj = get_post_type_object( $post->post_type );

      $key = 'post_type::' . $post->post_type;

      if ( ! isset( $groups[ $key ] ) ) {
        $groups[ $key ] = array(
          'title' => sprintf( __( 'All %s', 'cornerstone' ), $post_type_obj->labels->name ),
          'tag'   => $post_type_obj->labels->singular_name,
          'items'   => array()
        );
      }

      $groups[ $key ]['items'][] = array(
        'value' => $post->ID,
        'title' => $post->post_title,
      );

    }

    // $taxonomies = get_taxonomies( array( 'public' => true), 'objects' );
    // foreach ( $taxonomies  as $taxonomy ) {
    //   $contexts[] = array(
    //     'value' => $taxonomy->name,
    //     'label' => $taxonomy->labels->singular_name,
    //     'group' => 'Taxonomy'
    //   );
    // }

    ksort($groups);

    $contexts = array();

    foreach ($groups as $key => $group) {
      $group['name'] = $key;
      $contexts[] = $group;
    }

    return $contexts;
  }

  public function get_assignments() {
    return wp_parse_args( json_decode( wp_unslash( get_option( 'cornerstone_header_assignments' ) ), true ), $this->assignment_schema() );
  }

  public function locate_assignment() {
    $assignments = $this->get_assignments();

    // Start by using the global header
    $match = $assignments['global'];
    $post = get_post();

    if ( is_front_page() && isset( $assignments['indexes']['front'] ) ) {
      $match = $assignments['indexes']['front'];
    } elseif ( is_home() && isset( $assignments['indexes']['home'] ) ) {
      $match = $assignments['indexes']['home'];
    } elseif ( is_a( $post, 'WP_POST' ) ) {

      if ( isset( $assignments['post_types'][ $post->post_type ] ) ) {
        $match = $assignments['post_types'][ $post->post_type ];
      }

      if ( isset( $assignments['posts'][ 'post-' . $post->ID ] ) ) {
        $match = $assignments['posts'][ 'post-' . $post->ID ];
      }

    }

    // Fallback to the oldest header
    if ( null === $match ) {
      $posts = get_posts( array(
        'post_type' => 'cs_header',
        'post_status' => 'any',
        'order' => 'ASC',
        'posts_per_page' => 1
      ) );

      if ( ! empty( $posts) ) {
        $match = $posts[0]->ID;
      }

    }

    if ( ! is_null( $match ) ) {
      $match = (int) $match;
    }

    return $match;
  }

}
