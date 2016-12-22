<?php
if(function_exists("smile_framework_add_options")){
// Optin to Win Style
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '60';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Slide_Ins',"blank",
		array(
			"style_name" 		=> "Blank",
			"demo_url"			=> plugins_url("../../assets/demos/blank/blank.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/blank/blank.html",
			"img_url"			=> plugins_url("../../assets/demos/blank/blank.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/blank/customizer.js",__FILE__),
			"category"          => "All,offers",
			"tags"              => "offers,default,special,discount,mascot,blank",
			"options"			=> array(
			),
		)
	);
}