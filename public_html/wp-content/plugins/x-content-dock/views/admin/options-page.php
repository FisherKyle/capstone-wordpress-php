<?php

// =============================================================================
// VIEWS/ADMIN/OPTIONS-PAGE.PHP
// -----------------------------------------------------------------------------
// Plugin options page.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Permissions Check
//   02. Require Options
//   03. Options Page Output
// =============================================================================

// Permissions Check
// =============================================================================

if ( ! current_user_can( 'manage_options' ) ) {
  wp_die( 'You do not have sufficient permissions to access this page.' );
}



// Require Options
// =============================================================================

require( X_CONTENT_DOCK_PATH . '/functions/options.php' );



// Options Page Output
// =============================================================================

//
// Setup array of all pages and posts.
//

$x_content_dock_list_entries_args   = array( 'posts_per_page' => -1 );
$x_content_dock_list_entries_merge  = array_merge( get_pages( $x_content_dock_list_entries_args ), get_posts( $x_content_dock_list_entries_args ) );
$x_content_dock_list_entries_master = array();

foreach ( $x_content_dock_list_entries_merge as $post ) {
  $x_content_dock_list_entries_master[$post->ID] = $post->post_title;
}

asort( $x_content_dock_list_entries_master );


//
// Check if variables are set to prevent notices. Variables are set after the
// first submission of data.
//

$x_content_dock_entries_include = ( isset( $x_content_dock_entries_include ) ) ? $x_content_dock_entries_include : array();

?>

<div class="wrap x-plugin x-content-dock">
  <h2><?php _e( 'Content Dock', '__x__' ); ?></h2>
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <form name="x_content_dock_form" method="post" action="">
        <input name="x_content_dock_form_submitted" type="hidden" value="submitted">

        <?php require( 'options-page-main.php' ); ?>
        <?php require( 'options-page-sidebar.php' ); ?>

      </form>
    </div>
    <br class="clear">
  </div>
</div>