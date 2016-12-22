<?php

class CS_Column extends Cornerstone_Element_Base {

  public function data() {
    return array(
      'name'        => 'column',
      'title'       => __( 'Column', 'cornerstone' ),
      'section'     => '_internal',
      'description' => __( 'Column description.', 'cornerstone' ),
      'supports'    => array( 'text_align', 'id', 'class', 'style' ),
      'helpText'   => array(
        'title' => __( 'Want to add content?', 'cornerstone' ),
        'message' => sprintf( __( 'Click the <strong class="glue">%s Elements</strong> icon and drag your elements into a column.', 'cornerstone' ), '%%icon-nav-elements-solid%%' ),
      ),
      'render'      => false,
    );
  }

  public function controls() {

    $this->addControl(
      'bg_color',
      'color',
      __( 'Background Color', 'cornerstone' ),
      __( 'Select the background color of your Column.', 'cornerstone' ),
      ''
    );

    $this->addControl(
      'padding',
      'dimensions',
      __( 'Padding', 'cornerstone' ),
      __( 'Specify a custom padding for each side of this element. Can accept CSS units like px, ems, and % (default unit is px).', 'cornerstone' ),
      array( '0px', '0px', '0px', '0px', 'linked' )
    );

    $this->addSupport( 'border' );

    $this->addControl(
      'fade',
      'toggle',
      __( 'Enable Fade Effect', 'cornerstone' ),
      __( 'Activating will make this column fade into view when the user scrolls to it for the first time.', 'cornerstone' ),
      false
    );

    $this->addControl(
      'fade_animation',
      'choose',
      __( 'Fade Direction', 'cornerstone' ),
      __( 'Choose a direction to fade from. "None" will allow the column to fade in without coming from a particular direction.', 'cornerstone' ),
      'in',
      array(
        'condition' => array(
          'fade' => true
        ),
        'columns' => '5',
        'choices' => array(
          array( 'value' => 'in',             'tooltip' => __( 'None', 'cornerstone' ),   'icon' => fa_entity( 'ban' ) ),
          array( 'value' => 'in-from-bottom', 'tooltip' => __( 'Top', 'cornerstone' ),    'icon' => fa_entity( 'arrow-up' ) ),
          array( 'value' => 'in-from-left',   'tooltip' => __( 'Right', 'cornerstone' ),  'icon' => fa_entity( 'arrow-right' ) ),
          array( 'value' => 'in-from-top',    'tooltip' => __( 'Bottom', 'cornerstone' ), 'icon' => fa_entity( 'arrow-down' ) ),
          array( 'value' => 'in-from-right',  'tooltip' => __( 'Left', 'cornerstone' ),   'icon' => fa_entity( 'arrow-left' ) )
        )
      )
    );

    $this->addControl(
      'fade_animation_offset',
      'text',
      __( 'Offset', 'cornerstone' ),
      __( 'Determines how drastic the fade effect will be.', 'cornerstone' ),
      '45px',
      array(
        'condition' => array(
          'fade'           => true,
          'fade_animation' => array( 'in-from-top', 'in-from-left', 'in-from-right', 'in-from-bottom' )
        )
      )
    );

    $this->addControl(
      'fade_duration',
      'text',
      __( 'Duration', 'cornerstone' ),
      __( 'Determines how long the fade effect will be.', 'cornerstone' ),
      '750',
      array(
        'condition' => array(
          'fade' => true
        )
      )
    );

  }

  public function render( $atts ) {

    extract( $atts );

    if ( $fade == 'true' ) {
      $fade = ' fade="' . $fade . '" fade_animation="' . $fade_animation . '" fade_animation_offset="' . $fade_animation_offset . '"';
      if ( $fade_duration != '750' ) {
        $fade .= " fade_duration=\"{$fade_duration}\"";
      }
    } else {
      $fade = '';
    }

    $type = ( $size != '' ) ? 'type="' . $size . '" ' : '';

    if ( trim( $content ) == '' ) {
      $content = '<span>&nbsp;</span>';
    }

    $shortcode = "[x_column bg_color=\"{$bg_color}\" {$type}{$fade}{$extra}]{$content}[/x_column]";

    return $shortcode;

  }

}