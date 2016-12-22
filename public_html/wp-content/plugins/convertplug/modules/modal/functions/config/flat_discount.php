<?php
if(function_exists("smile_framework_add_options")){
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Modals',"flat_discount",
		array(
			"style_name" 		=> "Flat Discount",
			"demo_url"			=> plugins_url("../../assets/demos/flat_discount/flat_discount.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/flat_discount/flat_discount.html",
			"img_url"			=> plugins_url("../../assets/demos/flat_discount/flat_discount.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/flat_discount/customizer.js",__FILE__),
			"category"          => "All,Optins,Offers,Exit Intent",
			"tags"              => "Sale,Offer,Discount, Commerce,Coupon,Day,Optin,Email",
			"options"			=> array(
			)
		)
	);
}