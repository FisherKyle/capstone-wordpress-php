<?php

// Accordion
// =============================================================================

function x_shortcode_accordion( $atts, $content = null ) {
  extract( shortcode_atts( array(
    'id'    => '',
    'class' => '',
    'style' => '',
    'link'  => ''
  ), $atts, 'x_accordion' ) );

  $id     = ( $id    != '' ) ? 'id="' . esc_attr( $id ) . '"' : '';
  $class  = ( $class != '' ) ? 'x-accordion ' . esc_attr( $class ) : 'x-accordion';
  $style  = ( $style != '' ) ? 'style="' . $style . '"' : '';
  $linked = ( $link === 'true' ) ? 'data-cs-collapse-linked ' : '';

  $output = "<div {$id} class=\"{$class}\" {$linked}{$style}>" . do_shortcode( $content ) . "</div>";

  return $output;
}

add_shortcode( 'x_accordion', 'x_shortcode_accordion' );



// Accordion Item
// =============================================================================

function x_shortcode_accordion_item( $atts, $content = null ) {
  extract( shortcode_atts( array(
    'id'        => '',
    'class'     => '',
    'style'     => '',
    'parent_id' => '',
    'title'     => '',
    'open'      => ''
  ), $atts, 'x_accordion_item' ) );

  $id        = ( $id        != ''     ) ? 'id="' . esc_attr( $id ) . '"' : '';
  $class     = ( $class     != ''     ) ? 'x-accordion-group ' . esc_attr( $class ) : 'x-accordion-group';
  $style     = ( $style     != ''     ) ? 'style="' . $style . '"' : '';
  $parent_id = ( $parent_id != ''     ) ? 'data-cs-collapse-parent="#' . $parent_id . '"' : '';
  $title     = ( $title     != ''     ) ? $title : 'Make Sure to Set a Title';
  $collapse  = ( $open      == 'true' ) ? 'collapse in' : 'collapse';
  $collapsed = ( $open      != 'true' ) ? ' collapsed' : '';

  $output = "<div {$id} class=\"{$class}\" {$style} data-cs-collapse-group>"
            . '<div class="x-accordion-heading">'
              . "<a class=\"x-accordion-toggle{$collapsed}\" data-cs-collapse-toggle {$parent_id} >{$title}</a>"
            . '</div>'
            . "<div class=\"x-accordion-body {$collapse}\" data-cs-collapse-content>"
              . '<div class="x-accordion-inner">'
                . do_shortcode( $content )
              . '</div>'
            . '</div>'
          . '</div>';

  return $output;
}

add_shortcode( 'x_accordion_item', 'x_shortcode_accordion_item' );