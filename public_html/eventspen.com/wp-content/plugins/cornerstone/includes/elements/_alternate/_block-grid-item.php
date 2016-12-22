<?php

class CS_Block_Grid_Item extends Cornerstone_Element_Base {

  public function data() {
    return array(
      'name'        => 'block-grid-item',
      'title'       => __( 'Block Grid Item', 'cornerstone' ),
      'section'     => '_content',
      'description' => __( 'Block Grid Item description.', 'cornerstone' ),
      'supports'    => array( 'id', 'class', 'style' ),
      'render'      => false,
      'delegate'    => true
    );
  }

  public function controls() {

    $this->addControl(
      'title',
      'title',
      NULL,
      NULL,
      ''
    );

    $this->addControl(
      'content',
      'editor',
      __( 'Content', 'cornerstone' ),
      __( 'Include your desired content for your Block Grid Item here.', 'cornerstone' ),
      __( 'Add some content to your block grid item here. The block grid responds a little differently than traditional columns, allowing you to mix and match for cool effects.', 'cornerstone' )
    );

  }

  // public function render( $atts ) {

  //   extract( $atts );

  //   $extra = $this->extra( array(
  //     'id'    => $id,
  //     'class' => $class,
  //     'style' => $style
  //   ) );

  //   $shortcode = "[x_block_grid_item{$extra}][/x_block_grid_item]";

  //   return $shortcode;

  // }

}