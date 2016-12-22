<?php
require_once('modules-functions.php');
if(function_exists('convert_plug_add_module')){
	$modules = 	
		array(

			'Modal Popup' 	=> array(
								'file' => 'modal/modal.php',
								'img' => plugins_url( 'modal/assets/img/modal.jpg', __FILE__ ),
								'desc' => 'Create beautiful and interactive modal to capture your visitors attention.',
							),
			'Info Bar'	 => array(
								'file' => 'info_bar/info_bar.php',
								'img' => plugins_url( 'info_bar/assets/img/info_bar.jpg', __FILE__ ),
								'desc' => 'Create beautiful and interactive info bar to capture your visitors attention.',
							),
			'Slide In Popup' => array(
								'file' => 'slide_in/slide_in.php',
								'img' => plugins_url( 'slide_in/assets/img/slide_in.jpg', __FILE__ ),
								'desc' => 'Create beautiful and interactive slide in pop-up to capture your visitors attention.',
							),

		);
	convert_plug_add_module($modules);
		
	$stored_modules = get_option('convert_plug_modules');
	
	if( empty( $stored_modules ) || $stored_modules == "" ){
		$new_module_list = array();
		foreach($modules as $module => $file){
			$module = str_replace(' ', '_', $module);
			$new_module_list[] = $module;
		}
		update_option('convert_plug_modules',$new_module_list);
	}
	
	$stored_modules = get_option('convert_plug_modules');
	
	foreach($modules as $module => $options){
		$file = $options['file'];
		$module = str_replace(' ', '_', $module);
		if( is_array( $stored_modules ) && in_array($module,$stored_modules)){
			require_once($file);
		}
	}
}