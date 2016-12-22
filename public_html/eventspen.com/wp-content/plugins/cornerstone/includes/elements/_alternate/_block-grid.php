<?php

class CS_Block_Grid extends Cornerstone_Element_Base {

  public function data() {
    return array(
      'name'        => 'block-grid',
      'title'       => __( 'Block Grid', 'cornerstone' ),
      'section'     => 'content',
      'description' => __( 'Block Grid description.', 'cornerstone' ),
      'supports'    => array( 'id', 'class', 'style' ),
      'renderChild' => true
    );
  }

  public function controls() {

    $this->addControl(
      'elements',
      'sortable',
      __( 'Block Grid Items', 'cornerstone' ),
      __( 'Add a new item to your Block Grid.', 'cornerstone' ),
      array(
        array( 'title' => __( 'Block Grid Item 1', 'cornerstone' ) ),
        array( 'title' => __( 'Block Grid Item 2', 'cornerstone' ) )
      ),
      array(
      	'element'   => 'block-grid-item',
        'newTitle' => __( 'Block Grid Item %s', 'cornerstone' ),
        'floor'    => 2
      )
    );

    $this->addControl(
      'type',
      'select',
      __( 'Columns', 'cornerstone' ),
      __( 'Select how many columns of items should be displayed on larger screens. These will update responsively based on screen size.', 'cornerstone' ),
      'two-up',
      array(
        'choices' => array(
          array( 'value' => 'two-up',   'label' => __( '2', 'cornerstone' ) ),
          array( 'value' => 'three-up', 'label' => __( '3', 'cornerstone' ) ),
          array( 'value' => 'four-up',  'label' => __( '4', 'cornerstone' ) )
        )
      )
    );

  }

  public function render( $atts ) {

    extract( $atts );

    $contents = '';

    foreach ( $elements as $e ) {

      $item_extra = $this->extra( array(
        'id'    => $e['id'],
        'class' => $e['class'],
        'style' => $e['style']
      ) );

      $contents .= '[x_block_grid_item' . $item_extra . ']' . $e['content'] . '[/x_block_grid_item]';

    }

    $shortcode = "[x_block_grid type=\"$type\"{$extra}]{$contents}[/x_block_grid]";

    return $shortcode;

  }

}