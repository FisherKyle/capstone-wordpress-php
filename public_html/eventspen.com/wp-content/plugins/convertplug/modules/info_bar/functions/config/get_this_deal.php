<?php
if(function_exists("smile_framework_add_options")){

	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Info_Bars',"get_this_deal",
		array(
			"style_name" 		=> "Get This Deal",
			"demo_url"			=> plugins_url("../../assets/demos/get_this_deal/get_this_deal.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/get_this_deal/get_this_deal.html",
			"img_url"			=> plugins_url("../../assets/demos/get_this_deal/get_this_deal.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/get_this_deal/customizer.js",__FILE__),
			"category"          => "All,Offers",
			"tags"              => "image, cta, call to action",
			"options"			=> array()
		)
	);
}
