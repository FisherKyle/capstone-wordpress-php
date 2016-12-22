<?php

class Cornerstone_Controller_Builder extends Cornerstone_Plugin_Component {

  public function config() {

    return array(
      'i18n' => $this->plugin->i18n( 'builder' ),
      'test' => true
    );

  }

}
