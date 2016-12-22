<?php
if(function_exists("smile_framework_add_options")){
// Social media Style
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '60';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Modals',"social_article",
		array(
			"style_name" 		=> "Social Article",
			"demo_url"			=> plugins_url("../../assets/demos/social_article/social_article.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/social_article/social_article.html",
			"img_url"			=> plugins_url("../../assets/demos/social_article/social_article
				.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/social_article/customizer.js",__FILE__),
			"category"          => "All,Social",
			"tags"              => "Social,Share,Facebook,Twitter,Google,Digg,Reddit,Pinterest,LinkedIn,Myspace,Blogger,Tumblr,StumbleUpon",
			"options"			=> array(
			),
		)
	);
}