<?php

/*

Plugin Name: X &ndash; Custom 404
Plugin URI: http://theme.co/x/
Description: Redirect all of your site's 404 errors to a custom page that you have complete control over. Easily create any layout you want using page templates, shortcodes, and more!
Version: 1.1.1
Author: Themeco
Author URI: http://theme.co/
Text Domain: __x__
X Plugin: x-custom-404

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

define( 'X_CUSTOM_404_VERSION', '1.1.1' );
define( 'X_CUSTOM_404_URL', plugins_url( '', __FILE__ ) );
define( 'X_CUSTOM_404_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );


//
// Global variables.
//

$x_custom_404_options = array();



// Setup Menu
// =============================================================================

function x_custom_404_options_page() {
  require( 'views/admin/options-page.php' );
}

function x_custom_404_menu() {
  add_submenu_page( 'x-addons-home', __( 'Custom 404', '__x__' ), __( 'Custom 404', '__x__' ), 'manage_options', 'x-extensions-custom-404', 'x_custom_404_options_page' );
}

add_action( 'admin_menu', 'x_custom_404_menu', 100 );



// Initialize
// =============================================================================

function x_custom_404_init() {

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

add_action( 'init', 'x_custom_404_init' );
