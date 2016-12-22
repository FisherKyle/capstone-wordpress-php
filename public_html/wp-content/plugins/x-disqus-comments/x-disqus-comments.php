<?php

/*

Plugin Name: X &ndash; Disqus Comments
Plugin URI: http://theme.co/x/
Description: Take advantage of powerful and unique features by integrating Disqus comments on your website instead of the standard WordPress commenting system.
Version: 1.0.0
Author: Themeco
Author URI: http://theme.co/
Text Domain: __x__
X Plugin: x-disqus-comments

*/

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Define Constants and Global Variables
//   02. Setup Menu
//   03. Initialize
// =============================================================================

// Define Constants and Global Variables
// =============================================================================

//
// Constants.
//

define( 'X_DISQUS_COMMENTS_VERSION', '1.0.0' );
define( 'X_DISQUS_COMMENTS_URL', plugins_url( '', __FILE__ ) );
define( 'X_DISQUS_COMMENTS_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );


//
// Global variables.
//

$x_disqus_comments_options = array();



// Setup Menu
// =============================================================================

function x_disqus_comments_options_page() {
  require( 'views/admin/options-page.php' );
}

function x_disqus_comments_menu() {
  add_submenu_page( 'x-addons-home', __( 'Disqus Comments', '__x__' ), __( 'Disqus Comments', '__x__' ), 'manage_options', 'x-extensions-disqus-comments', 'x_disqus_comments_options_page' );
}

add_action( 'admin_menu', 'x_disqus_comments_menu', 100 );



// Initialize
// =============================================================================

function x_disqus_comments_init() {

  //
  // Textdomain.
  //

  load_plugin_textdomain( '__x__', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );


  //
  // Styles and scripts.
  //

  require( 'functions/enqueue/styles.php' );
  require( 'functions/enqueue/scripts.php' );


  //
  // Notices.
  //

  require( 'functions/notices.php' );


  //
  // Output.
  //

  require( 'functions/output.php' );

}

add_action( 'init', 'x_disqus_comments_init' );