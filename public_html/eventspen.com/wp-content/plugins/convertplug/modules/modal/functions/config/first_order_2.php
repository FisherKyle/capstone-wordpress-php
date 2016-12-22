<?php
if(function_exists("smile_framework_add_options")){
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Modals',"first_order_2",
		array(
			"style_name" 		=> "First Order 2",
			"demo_url"			=> plugins_url("../../assets/demos/first_order_2/first_order_2.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/first_order_2/first_order_2.html",
			"img_url"			=> plugins_url("../../assets/demos/first_order_2/first_order_2.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/first_order_2/customizer.js",__FILE__),
			"category"          => "All,Offers,Exit Intent",
			"tags"              => "Sale,Offer,Discount,Commerce,Coupon,Day,Button",
			"options"			=> array(
			)
		)
	);
}