<?php

class CS_Pullquote extends Cornerstone_Element_Base {

  public function data() {
    return array(
      'name'        => 'pullquote',
      'title'       => __( 'Pullquote', 'cornerstone' ),
      'section'     => '_typography',
      'description' => __( 'Pullquote description.', 'cornerstone' ),
      'supports'    => array( 'id', 'class', 'style' ),
      'empty'       => array( 'content' => '', 'cite' => '' ),
      'autofocus' => array(
    		'cite'    => '.x-pullquote .x-cite',
    		'content' => '.x-pullquote'
    	)
    );
  }

  public function controls() {

    $this->addControl(
      'content',
      'textarea',
      __( 'Quote &amp Citation', 'cornerstone' ),
      __( 'Enter your quote in the textarea below. If you want to cite your quote, you can place that in the input following the textarea.', 'cornerstone' ),
      __( 'Input your quotation here. Also, you can cite your quotes if you would like.', 'cornerstone' ),
      array(
        'expandable' => __( 'Quote', 'cornerstone' )
      )
    );

    $this->addControl(
      'cite',
      'text',
      NULL,
      NULL,
      __( 'Mr. WordPress', 'cornerstone' )
    );

    $this->addControl(
      'align',
      'choose',
      __( 'Alignment', 'cornerstone' ),
      __( 'Select the alignment of the pullquote.', 'cornerstone' ),
      'right',
      array(
        'columns' => '2',
        'choices' => array(
          array( 'value' => 'left',  'label' => __( 'Left', 'cornerstone' ),  'icon' => fa_entity( 'align-left' ) ),
          array( 'value' => 'right', 'label' => __( 'Right', 'cornerstone' ), 'icon' => fa_entity( 'align-right' ) )
        )
      )
    );

  }

  public function render( $atts ) {

    extract( $atts );

    $shortcode = "[x_pullquote cite=\"$cite\" type=\"$align\"{$extra}]{$content}[/x_pullquote]";

    return $shortcode;

  }

}