<?php
if(function_exists("smile_framework_add_options")){
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '60';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Modals',"optin_to_win",
		array(
			"style_name" 		=> "Optin to Win",
			"demo_url"			=> plugins_url("../../assets/demos/optin_to_win/optin_to_win.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/optin_to_win/optin_to_win.html",
			"img_url"			=> plugins_url("../../assets/demos/optin_to_win/optin_to_win.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/optin_to_win/customizer.js",__FILE__),
			"category"          => "All,Optins",
			"tags"              => "Ebook,Download,Freebie,Case Study,Image,Free,Optin,Email,Subscribe",
			"options"			=> array(
			),
		)
	);
}