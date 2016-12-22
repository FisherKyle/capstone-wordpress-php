<?php
if(function_exists("smile_framework_add_options")){
// Optin to Win Style
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Info_Bars',"weekly_article",
		array(
			"style_name" 		=> "Weekly Article",
			"demo_url"			=> plugins_url("../../assets/demos/weekly_article/weekly_article.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/weekly_article/weekly_article.html",
			"img_url"			=> plugins_url("../../assets/demos/weekly_article/weekly_article.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/weekly_article/customizer.js",__FILE__),
			"category"          => "All,Optins",
			"tags"              => "form",
			"options"			=> array()
		)
	);
}
