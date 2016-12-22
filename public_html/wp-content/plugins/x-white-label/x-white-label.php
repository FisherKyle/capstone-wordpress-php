<?php

/*

Plugin Name: X &ndash; White Label
Plugin URI: http://theme.co/x/
Description: Customize the WordPress login screen, Addons home page, and much more. This is a great tool to use if handing X off to a client to provide them with tailored content right in the WordPress admin area.
Version: 1.3.0
Author: Themeco
Author URI: http://theme.co/
Text Domain: __x__
X Plugin: x-white-label

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

define( 'X_WHITE_LABEL_VERSION', '1.3.0' );
define( 'X_WHITE_LABEL_URL', plugins_url( '', __FILE__ ) );
define( 'X_WHITE_LABEL_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );


//
// Global variables.
//

$x_white_label_options = array();



// Setup Menu
// =============================================================================

function x_white_label_options_page() {
  require( 'views/admin/options-page.php' );
}

function x_white_label_menu() {
  add_submenu_page( 'x-addons-home', __( 'White Label', '__x__' ), __( 'White Label', '__x__' ), 'manage_options', 'x-extensions-white-label', 'x_white_label_options_page' );
}

add_action( 'admin_menu', 'x_white_label_menu', 100 );



// Initialize
// =============================================================================

function x_white_label_init() {

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

add_action( 'init', 'x_white_label_init' );
