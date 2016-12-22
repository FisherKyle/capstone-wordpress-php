<?php

// =============================================================================
// FUNCTIONS/REQUEST.PHP
// -----------------------------------------------------------------------------
// Plugin API request.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Request
// =============================================================================

// Request
// =============================================================================

function x_typekit_request( $kit_id ) {

  //
  // Array to ultimately receive refined Typekit data to use in X.
  //

  $refined_kit_data = array();


  //
  // Typekit API request.
  //

  $uri     = 'https://typekit.com/api/v1/json/kits/' . $kit_id . '/published';
  $request = wp_remote_get( $uri, array( 'timeout' => 15 ) );


  //
  // Terminate and return empty array if kit ID is not valid.
  //

  if ( $request['response']['code'] != 200 ) {

    return $refined_kit_data;

  }


  //
  // Refine the Typekit API request.
  //

  $data     = json_decode( $request['body'], true );
  $families = $data['kit']['families'];

  foreach ( $families as $family ) :

    $refined_kit_data[sanitize_key( $family['name'] )] = array(
      'source'  => 'typekit',
      'family'  => $family['name'],
      'stack'   => $family['css_stack'],
      'weights' => array()
    );

    foreach ( $family['variations'] as $variation ) :

      $variation_deconstructed = str_split( $variation );

      switch ( $variation_deconstructed[0] ) {
        case 'n' :
          $style = 'normal';
          break;
        case 'i' :
          $style = 'italic';
          break;
        case 'o' :
          $style = 'oblique';
          break;
        default :
          $style = 'normal';
          break;
      }

      $weight    = $variation_deconstructed[1] . '00';
      $variation = ( $style == 'italic' ) ? $weight . $style : $weight;

      $refined_kit_data[sanitize_key( $family['name'] )]['weights'][] = $variation;

    endforeach;

  endforeach;


  //
  // Return the refined Typekit data.
  //

  return $refined_kit_data;

}