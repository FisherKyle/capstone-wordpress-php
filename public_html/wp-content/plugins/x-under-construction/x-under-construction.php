<?php

/*

Plugin Name: X &ndash; Under Construction
Plugin URI: http://theme.co/x/
Description: Got a little work that needs to be done under the hood? The Under Construction plugin is the easiest maintenance plugin you'll ever setup and the last one you'll ever need.
Version: 1.1.2
Author: Themeco
Author URI: http://theme.co/
Text Domain: __x__
X Plugin: x-under-construction

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

define( 'X_UNDER_CONSTRUCTION_VERSION', '1.1.2' );
define( 'X_UNDER_CONSTRUCTION_URL', plugins_url( '', __FILE__ ) );
define( 'X_UNDER_CONSTRUCTION_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );


//
// Global variables.
//

$x_under_construction_options = array();



// Setup Menu
// =============================================================================

function x_under_construction_options_page() {
  require( 'views/admin/options-page.php' );
}

function x_under_construction_menu() {
  add_submenu_page( 'x-addons-home', __( 'Under Construction', '__x__' ), __( 'Under Construction', '__x__' ), 'manage_options', 'x-extensions-under-construction', 'x_under_construction_options_page' );
}

add_action( 'admin_menu', 'x_under_construction_menu', 100 );



// Initialize
// =============================================================================

function x_under_construction_init() {

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

add_action( 'init', 'x_under_construction_init' );