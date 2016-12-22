<?php
if(function_exists("smile_framework_add_options")){
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Modals',"countdown",
		array(
			"style_name" 		=> "Countdown",
			"demo_url"			=> plugins_url("../../assets/demos/countdown/countdown.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/countdown/countdown.html",
			"img_url"			=> plugins_url("../../assets/demos/countdown/countdown.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/countdown/customizer.js",__FILE__),
			"category"          => "All,Offers,Updates",
			"tags"              => "Countdown,Offer,Update,Hurry,Limited,Time",
			"options"			=> array()
		)
	);
}