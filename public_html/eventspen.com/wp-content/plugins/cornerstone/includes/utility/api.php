<?php

/**
 * Public API
 * These functions expose Cornerstone APIs, allowing it to be extended.
 * The processes represented here are otherwise handled internally.
 */

/**
 * Set which post types should be enabled by default when Cornerstone is first
 * activated.
 * @param  array $types Array of strings specifying post type names.
 * @return none
 */
function cornerstone_set_default_post_types( $types ) {
	CS()->component( 'Common' )->set_default_post_types( $types );
}

/**
 * Allows integrating themes to disable Themeco cross-promotion, and other
 * presentational items. Example:
 *
		cornerstone_theme_integration( array(
			'remove_global_validation_notice' => true,
			'remove_themeco_offers'           => true,
			'remove_purchase_link'            => true,
			'remove_support_box'              => true
		) );
 *
 * @param  array $args List of items to flag
 * @return none
 */
function cornerstone_theme_integration( $args ) {
	CS()->component( 'Integration_Manager' )->theme_integration( $args );
}

/**
 * Register a new element
 * @param  $class_name Name of the class you've created in definition.php
 * @param  $name       slug name of the element. "alert" for example.
 * @param  $path       Path to the folder containing a definition.php file.
 */
function cornerstone_register_element( $class_name, $name, $path ) {
	CS()->component( 'Element_Orchestrator' )->add( $class_name, $name, $path );
}

/**
 * Remove a previously added element from the Builder interface.
 * @param  string $name Name used when the element's class was added
 * @return none
 */
function cornerstone_remove_element( $name ) {
	CS()->component( 'Element_Orchestrator' )->remove( $name );
}

/**
 * Registers a class as a candidate for Cornerstone Integration
 * Call from within this hook: cornerstone_integrations (happens before init)
 * @param  string $name       unique handle
 * @param  string $class_name Class to test conditions for, and eventually load
 * @return  none
 */
function cornerstone_register_integration( $name, $class_name ) {
	CS()->component( 'Integration_Manager' )->register( $name, $class_name );
}

/**
 * Unregister an integration that's been added so far
 * Call from within this hook: cornerstone_integrations (happens before init)
 * You may need to call on a later priority to ensure it was already registered
 * @param  string $name       unique handle
 * @return  none
 */
function cornerstone_unregister_integration( $name ) {
	CS()->component( 'Integration_Manager' )->unregister( $name );
}

/**
 * Provide Cornerstone with data and a template loader so styles can be dynamically
 * generated. Should be used before `wp_head`, preferably in `template_redirect`
 * @param  array    $header          Header data object
 * @param  string   $class_prefix    CSS prefix to place before each id.
 * @param  function $template_loader Callback that will return template markup
 * @return none
 */
function cornerstone_setup_header_styles( $header, $class_prefix, $template_loader ) {
  $headers = CS()->loadComponent( 'Headers' );
  if ( $headers ) {
    $headers->add_styling( $header, $class_prefix, $template_loader );
  }
}

function cornerstone_options_register_option( $name, $default_value = null, $options = array() ) {
  $options_bootstrap = CS()->loadComponent( 'Options_Bootstrap' );
  $options_bootstrap->register_option( $name, $default_value, $options );
}

function cornerstone_options_register_options( $group, $options = array() ) {
  $options_bootstrap = CS()->loadComponent( 'Options_Bootstrap' );
  $options_bootstrap->register_options( $group, $options );
}

function cornerstone_options_get_defaults() {
  return CS()->loadComponent( 'Options_Bootstrap' )->get_defaults();
}

function cornerstone_options_get_default( $name ) {
  return CS()->loadComponent( 'Options_Bootstrap' )->get_default( $name );
}

function cornerstone_options_get_value( $name ) {
  return CS()->loadComponent( 'Options_Bootstrap' )->get_value( $name );
}

function cornerstone_options_update_value( $name, $value ) {
  return CS()->loadComponent( 'Options_Bootstrap' )->update_value( $name, $value );
}

function cornerstone_options_register_section( $name, $value = array() ) {
  return CS()->loadComponent( 'Options_Manager' )->register_section( $name, $value );
}

function cornerstone_options_register_sections( $groups ) {
  return CS()->loadComponent( 'Options_Manager' )->register_sections( $groups );
}

function cornerstone_options_register_control( $option_name, $control ) {
  return CS()->loadComponent( 'Options_Manager' )->register_control( $option_name, $control );
}

function cornerstone_options_unregister_option( $name ) {
  return CS()->loadComponent( 'Options_Bootstrap' )->unregister_option( $name );
}

function cornerstone_options_unregister_section( $name ) {
  return CS()->loadComponent( 'Options_Manager' )->unregister_section( $name );
}

function cornerstone_options_unregister_control( $option_name ) {
  return CS()->loadComponent( 'Options_Manager' )->unregister_control( $option_name );
}

function cornerstone_options_enable_custom_css( $option_name ) {
  return CS()->loadComponent( 'Options_Manager' )->enable_custom_css( $option_name );
}

function cornerstone_options_enable_custom_js( $option_name ) {
  return CS()->loadComponent( 'Options_Manager' )->enable_custom_js( $option_name );
}


function cornerstone_register_bar_modules( $modules ) {
  return CS()->loadComponent( 'Headers' )->register_modules( $modules );
}

function cornerstone_register_bar_module( $name, $atts ) {
  return CS()->loadComponent( 'Headers' )->register_module( $name, $atts );
}

function cornerstone_unregister_bar_module( $name ) {
  return CS()->loadComponent( 'Headers' )->register_module( $name );
}

/**
 * Returns the styling created by cornerstone_setup_header_styles
 * @return string
 */
function cornerstone_get_header_styles() {
  $headers = CS()->loadComponent( 'Headers' );
  return ( $headers ) ? $headers->get_styles() : '';
}

/**
 * Returns the styling created by cornerstone_setup_header_styles
 * Can be called as early as template_redirect
 * @return string
 */
function cornerstone_get_header_data() {
  $headers = CS()->loadComponent( 'Headers' );
  return ( $headers ) ? $headers->get_active_header_data() : '';
}

/**
 * Deprecated
 */
function cornerstone_add_element( $class_name ) {
	CS()->component( 'Element_Orchestrator' )->add_mk1_element( $class_name );
}
