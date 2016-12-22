<?php

class Cornerstone_Preview_Frame extends Cornerstone_Plugin_Component {

  protected $state = false;

  public function setup() {

    if ( ! isset( $_POST['cs_preview_state'] ) ) {
      return;
    }

    // Nonce verification
    if ( ! isset( $_POST['_cs_nonce'] ) || ! wp_verify_nonce( $_POST['_cs_nonce'], 'cornerstone_nonce' ) ) {
      echo -1;
      die();
    }

    $this->state = json_decode( base64_decode( $_POST['cs_preview_state'] ), true );

    if ( isset( $this->state['mode'] ) ) {
      $component_name = cs_to_component_name( $this->state['mode'] ) . '_Preview_Frame';
      $frame = $this->plugin->loadComponent( $component_name );

      if ( ! $frame ) {
        throw new Exception( "Requested frame handler '$component_name' does not exist." );
      }
    }

    add_filter( 'show_admin_bar', '__return_false' );
    add_action( 'template_redirect', array( $this, 'load' ), 0 );
    add_action( 'shutdown', array( $this, 'frame_signature' ), 1000 );
    add_filter( 'wp_die_handler', array( $this, 'remove_frame_signature' ) );

  }

  public function load() {
    nocache_headers();
    $this->plugin->loadComponent( 'App' )->register_app_scripts( $this->plugin->settings(), true );
    wp_enqueue_script( 'cs-app' );
  }

  public function debug() {
    // header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

    ?><!DOCTYPE html><html <?php language_attributes(); ?>><head><title>Cornerstone Preview Frame Debug</title><meta charset="<?php bloginfo( 'charset' ); ?>"><meta name="viewport" content="width=device-width, initial-scale=1.0"><?php wp_head(); ?></head><body> <?php

    do_action('_cornerstone_preview_frame_debug');

    wp_footer();?></body></html><?php
    die();
  }

  public function get_state() {
    return $this->state;
  }

  public function data() {

    if ( ! $this->state ) {
      return array( 'timestamp' => $this->state);
    }

    return array(
      'timestamp' => $this->state['timestamp']
    );

  }

  public function frame_signature() {
    echo 'CORNERSTONE_FRAME';
  }

  public function remove_preview_signature( $return = null ) {
    remove_action( 'shutdown', array( $this, 'frame_signature' ), 1000 );
    return $return;
  }

}
