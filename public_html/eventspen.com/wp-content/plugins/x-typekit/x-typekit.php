<?php

/*

Plugin Name: X &ndash; Typekit
Plugin URI: http://theme.co/x/
Description: Create beautiful designs by incorporating Typekit fonts into your website. Our custom Extension makes this premium service easy to setup and use.
Version: 1.0.1
Author: Themeco
Author URI: http://theme.co/
Text Domain: __x__
X Plugin: x-typekit

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

define( 'X_TYPEKIT_VERSION', '1.0.1' );
define( 'X_TYPEKIT_URL', plugins_url( '', __FILE__ ) );
define( 'X_TYPEKIT_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );


//
// Global variables.
//

$X_TYPEKIT_options = array();



// Setup Menu
// =============================================================================

function x_typekit_options_page() {
  require( 'views/admin/options-page.php' );
}

function x_typekit_menu() {
  add_submenu_page( 'x-addons-home', __( 'Typekit', '__x__' ), __( 'Typekit', '__x__' ), 'manage_options', 'x-extensions-typekit', 'x_typekit_options_page' );
}

add_action( 'admin_menu', 'x_typekit_menu', 100 );



// Initialize
// =============================================================================

function x_typekit_init() {

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

add_action( 'init', 'x_typekit_init' );