<?php

/*

Plugin Name: X &ndash; Terms of Use
Plugin URI: http://theme.co/x/
Description: This plugin will allow you to add a simple terms of use that visitors must agree to before completing user registration.
Version: 1.0.0
Author: Themeco
Author URI: http://theme.co/
Text Domain: __x__
X Plugin: x-terms-of-use

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

define( 'X_TERMS_OF_USE_VERSION', '1.0.0' );
define( 'X_TERMS_OF_USE_URL', plugins_url( '', __FILE__ ) );
define( 'X_TERMS_OF_USE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );


//
// Global variables.
//

$x_terms_of_use_options = array();



// Setup Menu
// =============================================================================

function x_terms_of_use_options_page() {
  require( 'views/admin/options-page.php' );
}

function x_terms_of_use_menu() {
  add_submenu_page( 'x-addons-home', __( 'Terms of Use', '__x__' ), __( 'Terms of Use', '__x__' ), 'manage_options', 'x-extensions-terms-of-use', 'x_terms_of_use_options_page' );
}

add_action( 'admin_menu', 'x_terms_of_use_menu', 100 );



// Initialize
// =============================================================================

function x_terms_of_use_init() {

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

add_action( 'init', 'x_terms_of_use_init' );