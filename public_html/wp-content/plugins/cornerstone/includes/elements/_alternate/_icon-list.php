<?php

class CS_Icon_List extends Cornerstone_Element_Base {

  public function data() {
    return array(
      'name'        => 'icon-list',
      'title'       => __( 'Icon List', 'cornerstone' ),
      'section'     => 'typography',
      'description' => __( 'Icon List description.', 'cornerstone' ),
      'supports'    => array( 'id', 'class', 'style' ),
      'renderChild' => true
    );
  }

  public function controls() {

    $this->addControl(
      'elements',
      'sortable',
      __( 'Icon List Items', 'cornerstone' ),
      __( 'Add new items to your Icon List.', 'cornerstone' ),
      array(
        array( 'title' => __( 'Icon List Item 1', 'cornerstone' ), 'type' => 'check' ),
        array( 'title' => __( 'Icon List Item 2', 'cornerstone' ), 'type' => 'check' ),
        array( 'title' => __( 'Icon List Item 3', 'cornerstone' ), 'type' => 'times' )
      ),
      array(
        'element'  => 'icon-list-item',
        'newTitle' => __( 'Icon List Item %s', 'cornerstone' ),
        'floor'    => 1
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

      $contents .= '[x_icon_list_item type="' . $e['type'] .'" icon_color="' . $e['icon_color'] .'" href="' . $e['href'] .'" href_title="' . $e['href_title'] .'" href_target="' . $e['href_target'] .'"' . $item_extra . ']' . $e['title'] . '[/x_icon_list_item]';

    }

    $shortcode = "[x_icon_list{$extra}]{$contents}[/x_icon_list]";

    return $shortcode;

  }

}