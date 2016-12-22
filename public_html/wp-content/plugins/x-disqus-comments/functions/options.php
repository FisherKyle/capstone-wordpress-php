<?php

// =============================================================================
// FUNCTIONS/OPTIONS.PHP
// -----------------------------------------------------------------------------
// Plugin options.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Set Options
//   02. Get Options
// =============================================================================

// Set Options
// =============================================================================

//
// Set $_POST variables to options array and update option.
//

GLOBAL $x_disqus_comments_options;

if ( isset( $_POST['x_disqus_comments_form_submitted'] ) ) {
  if ( strip_tags( $_POST['x_disqus_comments_form_submitted'] ) == 'submitted' && current_user_can( 'manage_options' ) ) {

    $x_disqus_comments_options['x_disqus_comments_enable']    = ( isset( $_POST['x_disqus_comments_enable'] ) ) ? strip_tags( $_POST['x_disqus_comments_enable'] ) : '';
    $x_disqus_comments_options['x_disqus_comments_shortname'] = strip_tags( $_POST['x_disqus_comments_shortname'] );

    update_option( 'x_disqus_comments', $x_disqus_comments_options );

  }
}



// Get Options
// =============================================================================

$x_disqus_comments_options = apply_filters( 'x_disqus_comments_options', get_option( 'x_disqus_comments' ) );

if ( $x_disqus_comments_options != '' ) {

  $x_disqus_comments_enable    = $x_disqus_comments_options['x_disqus_comments_enable'];
  $x_disqus_comments_shortname = $x_disqus_comments_options['x_disqus_comments_shortname'];

}