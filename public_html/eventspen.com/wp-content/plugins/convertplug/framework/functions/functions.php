<?php

/**
 * Register Functions & Components
 */
require_once('functions.admin.php');
require_once('component_multi_form.php');
require_once('component_social_media.php');
require_once('component_count_down.php');

/**
 * 	Component - Multi Form - Generate DropDown HTML
 */
function mb_dropdown_string_to_html( $dropdown_options ) {

	$lines = explode(PHP_EOL, $dropdown_options);
	$all_options = '';
	foreach ($lines as $key => $line) {
		$line = trim($line);
		if($line === '')
			continue;
		$line_to_array = explode('+', $line);
		$label = (isset($line_to_array[0])) ? ucfirst($line_to_array[0]) : ucfirst($line);
		$value = (isset($line_to_array[1])) ? $line_to_array[1] : $line;
		$all_options .= '<option value="'.trim($value).'">'.trim($label).'</option>';
	}
	return $all_options;
}

/**
 * Generate Global shortcode variables
 */
function generate_global_shortcode_vars( $ar ) {

	$v = array();

	foreach ($ar as $key => $value) {

		if( isset( $value['name'] ) && !empty( $value['name'] ) ) {
			$v[ $value['name'] ] = '';
		}
	}

	return $v;	
}

/**
 * Helper Functions - Smile Framework
 */
add_action( 'wp_ajax_framework_update_options', 'framework_update_options');
add_action( 'wp_ajax_framework_update_preview_data', 'framework_update_preview_data');

// function to return style settings array
if(!function_exists('smile_get_style_settings')){
	function smile_get_style_settings($option,$style){
		$prev_styles = get_option($option);
		$styles = array();
		foreach($prev_styles as $key => $settings){
			if($settings['style_id'] == $style){
				$styles = unserialize($prev_styles[$key]['style_settings']);
			}
		}
		
		$style_settings = array();
		foreach($styles as $key => $setting){
			$style_settings[$key] = apply_filters('smile_render_setting',$setting);;
		}
		return $style_settings;
	}
}