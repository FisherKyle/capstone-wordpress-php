<?php

class CS_Pricing_Table_Column extends Cornerstone_Element_Base {

  public function data() {
    return array(
      'name'        => 'pricing-table-column',
      'title'       => __( 'Pricing Table Column', 'cornerstone' ),
      'section'     => '_marketing',
      'description' => __( 'Pricing Table Column description.', 'cornerstone' ),
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
      __( 'Specify your pricing column content.', 'cornerstone' ),
      __( "[x_icon_list]\n    [x_icon_list_item type=\"check\"]First Feature[/x_icon_list_item]\n    [x_icon_list_item type=\"times\"]Second Feature[/x_icon_list_item]\n    [x_icon_list_item type=\"times\"]Third Feature[/x_icon_list_item]\n[/x_icon_list]\n\n[x_button href=\"#\" size=\"large\"]Buy Now![/x_button]", 'cornerstone' )
    );

    $this->addControl(
      'featured',
      'toggle',
      __( 'Featured Column', 'cornerstone' ),
      __( 'Enable to specify this column as your featured item.', 'cornerstone' ),
      false
    );

    $this->addControl(
      'featured_sub',
      'text',
      __( 'Featured Subheading', 'cornerstone' ),
      __( 'Enter text for your featured column subheading here.', 'cornerstone' ),
      '',
      array(
        'condition' => array(
          'featured' => true
        )
      )
    );

    $this->addControl(
      'currency',
      'text',
      __( 'Currency', 'cornerstone' ),
      __( 'Enter your desired currency symbol here.', 'cornerstone' ),
      '$'
    );

    $this->addControl(
      'price',
      'text',
      __( 'Price', 'cornerstone' ),
      __( 'Enter the price for this column.', 'cornerstone' ),
      '29'
    );

    $this->addControl(
      'interval',
      'text',
      __( 'Interval', 'cornerstone' ),
      __( 'Enter the duration for this payment (e.g. "Weekly," "Per Year," et cetera).', 'cornerstone' ),
      __( 'Per Month', 'cornerstone' )
    );

  }

  // public function render( $atts ) {

  //   extract( $atts );

  //   $extra = $this->extra( array(
  //     'id'    => $id,
  //     'class' => $class,
  //     'style' => $style
  //   ) );

  //   $shortcode = "[x_pricing_table_column featured=\"$featured\" featured_sub=\"$featured_sub\" title=\"$title\" currency=\"$currency\" price=\"$price\" interval=\"$interval\"{$extra}]{$content}[/x_pricing_table_column]";

  //   return $shortcode;

  // }

}