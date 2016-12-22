<?php
if(function_exists("smile_framework_add_options")){
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Modals',"special_offer",
		array(
			"style_name" 		=> "Special Offer",
			"demo_url"			=> plugins_url("../../assets/demos/special_offer/special_offer.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/special_offer/special_offer.html",
			"img_url"			=> plugins_url("../../assets/demos/special_offer/special_offer.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/special_offer/customizer.js",__FILE__),
			"category"          => "All,Optins,Offers",
			"tags"              => "Sale,Offer,Discount,Commerce,Coupon,Optin,Email,Subscribe",
			"options"			=> array(
				/****** Design ******/

			)
		)
	);
}