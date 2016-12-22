<?php
if(function_exists("smile_framework_add_options")){
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Modals',"locked_content",
		array(
			"style_name" 		=> "Locked Content",
			"demo_url"			=> plugins_url("../../assets/demos/locked_content/locked_content.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/locked_content/locked_content.html",
			"img_url"			=> plugins_url("../../assets/demos/locked_content/locked_content.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/locked_content/customizer.js",__FILE__),
			"category"          => "All,Optins",
			"tags"              => "Optin,Email,Locked,Premium,Access,Close",
			"options"			=> array(
				/****** Design ******/
			)
		)
	);
}