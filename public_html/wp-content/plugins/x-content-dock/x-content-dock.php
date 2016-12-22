<?php

/*

Plugin Name: X &ndash; Content Dock
Plugin URI: http://theme.co/x/
Description: An incredibly simple and effective tool that allows you to place content or marketing offers in front of your users in an elegant, non-intrusive manner.
Version: 1.0.0
Author: Themeco
Author URI: http://theme.co/
Text Domain: __x__
X Plugin: x-content-dock

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

define( 'X_CONTENT_DOCK_VERSION', '1.0.0' );
define( 'X_CONTENT_DOCK_URL', plugins_url( '', __FILE__ ) );
define( 'X_CONTENT_DOCK_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );


//
// Global variables.
//

$x_content_dock_options = array();



// Setup Menu
// =============================================================================

function x_content_dock_options_page() {
  require( 'views/admin/options-page.php' );
}

function x_content_dock_menu() {
  add_submenu_page( 'x-addons-home', __( 'Content Dock', '__x__' ), __( 'Content Dock', '__x__' ), 'manage_options', 'x-extensions-content-dock', 'x_content_dock_options_page' );
}

add_action( 'admin_menu', 'x_content_dock_menu', 100 );



// Initialize
// =============================================================================

function x_content_dock_init() {

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

  require( 'functions/widgets.php' );
  require( 'functions/output.php' );

}

add_action( 'init', 'x_content_dock_init' );