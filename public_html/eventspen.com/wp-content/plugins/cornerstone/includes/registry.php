<?php

/**
 * Pseudo autoloading system.
 *
 * files:
 *     Groups of files to require at different points in WordPress execution
 *     Generally, these files should only contain class and function
 *     definitions without initiating any application logic.
 *
 * components:
 *     Groups of componenets to load into our main plugin at different points
 *     in WordPress execution. Component names must match their class name,
 *     prefixed by the plugin name for example:
 *     Class: Cornerstone_MyComponent
 *     Component: MyComponent
 */

return array(

  'files' => array(
    'preinit' => array(
      'tco/tco',
      'utility/helpers',
      'utility/api',
      'utility/wp-shortcode-preserver',
    ),
    'loggedin' => array(
      'utility/wp-clean-slate',
    )
  ),

  'components' => array(
    'preinit' => array(
      'Tco',
      'Common',
      'Updates',
      'Integration_Manager',
      'Options_Bootstrap',
      'CLI'
    ),
    'init' => array(
      'Legacy_Elements',
      'Shortcode_Generator',
      'Element_Orchestrator',
      'Core_Scripts',
      'Front_End',
      'Customizer_Manager',
      'Style_Loader',
      'Headers:theme-support:cornerstone_headers'
    ),
    'loggedin' => array(
      'Admin',
      'Options_Manager', // MOVE
      'App',
      'Preview_Frame',
      'Validation',
      'Revision_Manager',
      'Builder',
      'Layout_Manager'
    ),

    'model/option' => array(
      'Header_Assignments'
    )
  )
);
