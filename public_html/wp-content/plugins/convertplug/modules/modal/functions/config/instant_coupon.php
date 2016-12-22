 <?php
if(function_exists("smile_framework_add_options")){
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Modals',"instant_coupon",
		array(
			"style_name" 		=> "Instant Coupon",
			"demo_url"			=> plugins_url("../../assets/demos/instant_coupon/instant_coupon.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/instant_coupon/instant_coupon.html",
			"img_url"			=> plugins_url("../../assets/demos/instant_coupon/instant_coupon.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/instant_coupon/customizer.js",__FILE__),
			"category"          => "All,Optins,Offers,Exit Intent",
			"tags"              => "Sale,Offer,Discount,Coupon,Optin,Email,Tilt,Bold",
			"options"			=> array(

			)
		)
	);
}