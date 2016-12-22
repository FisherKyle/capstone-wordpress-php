<?php

class CS_Section extends Cornerstone_Element_Base {

  public function data() {
    return array(
      'name'        => 'section',
      'title'       => __( 'Section', 'cornerstone' ),
      'section'     => '_internal',
      'description' => __( 'Section description.', 'cornerstone' ),
      'supports'    => array( 'text_align', 'visibility', 'id', 'class', 'style' ),
      'render'      => false
    );
  }

  public function controls() {

    $this->addControl(
      'bg_type',
      'choose',
      __( 'Background Type', 'cornerstone' ),
      __( 'Configure the background appearance for this Section.', 'cornerstone' ),
      'none',
      array(
        'columns' => '4',
        'choices' => array(
          array( 'value' => 'none',  'icon' => fa_entity( 'ban' ),        'tooltip' => __( 'None', 'cornerstone' ) ),
          array( 'value' => 'color', 'icon' => fa_entity( 'eyedropper' ), 'tooltip' => __( 'Color', 'cornerstone' ) ),
          array( 'value' => 'image', 'icon' => fa_entity( 'image' ),      'tooltip' => __( 'Image', 'cornerstone' ) ),
          array( 'value' => 'video', 'icon' => fa_entity( 'film' ),       'tooltip' => __( 'Video', 'cornerstone' ) ),
        )
      )
    );

    $this->addControl(
      'bg_color',
      'color',
      __( 'Background Color', 'cornerstone' ),
      __( 'Select the background color of your Section.', 'cornerstone' ),
      '',
      array(
        'condition' => array(
          'bg_type' => 'color'
        )
      )
    );

    $this->addControl(
      'bg_image',
      'image',
      __( 'Background Pattern', 'cornerstone' ),
      __( 'Background patterns will tile and repeat across your Section.', 'cornerstone' ),
      '',
      array(
        'condition' => array(
          'bg_type'           => 'image',
          'bg_pattern_toggle' => true
        )
      )
    );

    $this->addControl(
      'bg_image',
      'image',
      __( 'Background Image', 'cornerstone' ),
      __( 'Background images are resized to fill the entire Section, regardless of screen size. Keep this in mind when using images that are already cropped.', 'cornerstone' ),
      '',
      array(
        'condition' => array(
          'bg_type'           => 'image',
          'bg_pattern_toggle' => false
        )
      )
    );

    $this->addControl(
      'bg_pattern_toggle',
      'toggle',
      __( 'Pattern', 'cornerstone' ),
      __( 'Switch how the image is applied to the background.', 'cornerstone' ),
      false,
      array(
        'condition' => array(
          'bg_type' => 'image'
        )
      )
    );

    $this->addControl(
      'parallax',
      'toggle',
      __( 'Parallax', 'cornerstone' ),
      __( 'Activates the parallax effect with background patterns and images.', 'cornerstone' ),
      false,
      array(
        'condition' => array(
          'bg_type' => 'image'
        )
      )
    );

    $this->addControl(
      'bg_video',
      'text',
      __( 'Background Video URL &amp; Poster', 'cornerstone' ),
      __( 'Include your video URL(s) here. If using multiple sources, separate them using the pipe character (|) and place fallbacks towards the end (i.e. .webm then .mp4 then .ogv). For performance reasons, videos are not loaded into the editor but are shown live.', 'cornerstone' ),
      '',
      array(
        'condition' => array(
          'bg_type' => 'video'
        ),
        'expandable' => false,
        'placeholder' => home_url( __( 'video.mp4', 'cornerstone' ) )
      )
    );

    $this->addControl(
      'bg_video_poster',
      'image',
      NULL,
      NULL,
      '',
      array(
        'condition' => array(
          'bg_type' => 'video'
        )
      )
    );

    $this->addControl(
      'margin',
      'dimensions',
      __( 'Margin', 'cornerstone' ),
      __( 'Specify the margins for your Section utilizing the controls below. For most situations you will likely want no margin. Can accept CSS units like px, ems, and % (default unit is px).', 'cornerstone' ),
      array( '0px', '0px', '0px', '0px', 'unlinked' )
    );

    $this->addControl(
      'padding',
      'dimensions',
      __( 'Padding', 'cornerstone' ),
      __( 'Specify a custom padding for each side of this element. Can accept CSS units like px, ems, and % (default unit is px).', 'cornerstone' ),
      array( '45px', '0px', '45px', '0px', 'unlinked' )
    );

    $this->addSupport( 'border' );

  }

  public function render( $atts ) {

    extract( $atts );

    switch ( $bg_type ) {
      case 'video' :
        $bg = ' bg_video="' . $bg_video . '" bg_video_poster="' . $bg_video_poster . '" bg_color="' . $bg_color . '"';
        break;
      case 'image' :
        $bg  = ' parallax="' . $parallax . '"';
        $bg .= ( $bg_pattern_toggle == 'true' ) ? ' bg_pattern="' . $bg_image . '"' : ' bg_image="' . $bg_image . '" bg_color="' . $bg_color . '"';
        break;
      case 'color' :
        $bg = ' bg_color="' . $bg_color . '"';
        break;
      default :
        $bg = '';
    }

    $shortcode = "[x_section{$bg}{$extra}]{$content}[/x_section]";

    return $shortcode;

  }

}