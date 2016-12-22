<?php

class CS_Alert extends Cornerstone_Element_Base {

  public function data() {
    return array(
      'name'        => 'alert',
      'title'       => __( 'Alert', 'cornerstone' ),
      'section'     => 'information',
      'description' => __( 'Alert description.', 'cornerstone' ),
      'supports'    => array( 'id', 'class', 'style' ),
      'autofocus' => array(
    		'heading' => '.x-alert .h-alert',
				'content' => '.x-alert'
    	)
    );
  }

  public function controls() {

    $this->addControl(
      'heading',
      'text',
      __( 'Heading &amp; Content', 'cornerstone' ),
      __( 'Text for your alert heading and content.', 'cornerstone' ),
      __( 'Alert Title', 'cornerstone' )
    );

    $this->addControl(
      'content',
      'textarea',
      NULL,
      NULL,
      __( 'Click to inspect, then edit as needed.', 'cornerstone' ),
      array(
        'expandable' => true
      )
    );

    $this->addControl(
      'type',
      'choose',
      __( 'Type', 'cornerstone' ),
      __( 'There are multiple alert types for different situations. Select the one that best suits your needs.', 'cornerstone' ),
      'success',
      array(
        'columns' => '5',
        'choices' => array(
          array( 'value' => 'muted',   'tooltip' => __( 'Muted', 'cornerstone' ),   'icon' => fa_entity( 'ban' ) ),
          array( 'value' => 'success', 'tooltip' => __( 'Success', 'cornerstone' ), 'icon' => fa_entity( 'check' ) ),
          array( 'value' => 'info',    'tooltip' => __( 'Info', 'cornerstone' ),    'icon' => fa_entity( 'info' ) ),
          array( 'value' => 'warning', 'tooltip' => __( 'Warning', 'cornerstone' ), 'icon' => fa_entity( 'exclamation-triangle' ) ),
          array( 'value' => 'danger',  'tooltip' => __( 'Danger', 'cornerstone' ),  'icon' => fa_entity( 'exclamation-circle' ) )
        )
      )
    );

    $this->addControl(
      'close',
      'toggle',
      __( 'Close Button', 'cornerstone' ),
      __( 'Enabling the close button will make the alert dismissible, allowing your users to remove it if desired.', 'cornerstone' ),
      false
    );

  }

  public function render( $atts ) {
  	// jsond( $atts );
    extract( $atts );

    $shortcode = "[x_alert type=\"$type\" close=\"$close\" heading=\"$heading\"{$extra}]{$content}[/x_alert]";

    return $shortcode;

  }

}