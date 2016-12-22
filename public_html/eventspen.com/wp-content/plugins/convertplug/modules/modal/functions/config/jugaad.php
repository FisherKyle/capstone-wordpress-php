<?php
if(function_exists("smile_framework_add_options")){
// Jugaad style
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '60';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Modals',"jugaad",
		array(
			"style_name" 		=> "Jugaad",
			"demo_url"			=> plugins_url("../../assets/demos/jugaad/jugaad.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/jugaad/jugaad.html",
			"img_url"			=> plugins_url("../../assets/demos/jugaad/jugaad.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/jugaad/customizer.js",__FILE__),
			"category"          => "All,Optins,Offers,Exit Intent,Updates",
			"tags"              => "Jugaad,Custom,Easy,Offer,Coupon,Optin,Email",
			"options"			=> array(
			)
		)
	);
}