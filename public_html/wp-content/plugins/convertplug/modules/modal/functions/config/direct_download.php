<?php
if(function_exists("smile_framework_add_options")){
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Modals',"direct_download",
		array(
			"style_name" 		=> "Direct Download",
			"demo_url"			=> plugins_url("../../assets/demos/direct_download/direct_download.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/direct_download/direct_download.html",
			"img_url"			=> plugins_url("../../assets/demos/direct_download/direct_download.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/direct_download/customizer.js",__FILE__),
			"category"          => "All,Exit Intent",
			"tags"              => "Ebook,Download,Freebie,Case Study,Image,Button",
			"options"			=> array(
			)
		)
	);
}