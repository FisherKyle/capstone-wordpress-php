<?php

class Cornerstone_Options_Preview_Frame extends Cornerstone_Plugin_Component {

  protected $updates = array();

  public function setup() {
    $state = $this->plugin->component( 'Preview_Frame' )->get_state();

    if ( isset( $state['updates']) && is_array( $state['updates'] ) ) {
      $this->updates = $state['updates'];
      $this->setup_pre_filtering( $state['updates'] );

      // $uuu = $this->updates;
      // add_action('wp_footer', function() use ($uuu){
      //   x_dump($uuu);
      // });
    }


    // add_action('_cornerstone_preview_frame_debug',function() use ($state){
    //   echo '<pre>';
    //   var_dump( $state );
    //   echo '</pre>';
    // });
  }

  public function setup_pre_filtering( $updates ) {
    foreach ($updates as $key => $value) {
      add_filter( "pre_option_$key", array( $this, 'pre_filter' ) );
    }
  }

  public function pre_filter() {

    $option_name = preg_replace( '/^pre_option_/', '', current_filter() );

    if ( isset( $this->updates[ $option_name ] ) ) {
      $value = $this->updates[ $option_name ];

      $value = apply_filters( 'option_' . $option_name, $value );
    }

    return $value;
  }
}
