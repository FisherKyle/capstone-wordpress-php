<?php
if(function_exists("smile_framework_add_options")){
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Modals',"every_design",
		array(
			"style_name" 		=> "Every Design",
			"demo_url"			=> plugins_url("../../assets/demos/every_design/every_design.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/every_design/every_design.html",
			"img_url"			=> plugins_url("../../assets/demos/every_design/every_design.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/every_design/customizer.js",__FILE__),
			"category"          => "All,Optins,Exit Intent",
			"tags"              => "Newsletter,Email,Optin,Subscribe",
			"options"			=> array(

			)
		)
	);
}