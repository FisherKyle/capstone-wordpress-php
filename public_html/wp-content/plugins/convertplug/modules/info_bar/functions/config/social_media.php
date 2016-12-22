<?php
if(function_exists("smile_framework_add_options")){

	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Info_Bars',"social_info_bar",
		array(
			"style_name" 		=> "Social Info Bar",
			"demo_url"			=> plugins_url("../../assets/demos/social_info_bar/social_info_bar.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/social_info_bar/social_info_bar.html",
			"img_url"			=> plugins_url("../../assets/demos/social_info_bar/social_info_bar.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/social_info_bar/customizer.js",__FILE__),
			"category"          => "All,Social",
			"tags"              => "Social,Share,Facebook,Twitter,Google,Digg,Reddit,Pinterest,LinkedIn,Myspace,Blogger,Tumblr,StumbleUpon",
			"options"			=> array()
		)
	);
}
