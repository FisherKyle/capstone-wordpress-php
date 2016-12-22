<?php
if(function_exists("smile_framework_add_options")){
	// You-tube Style
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Modals',"YouTube",
		array(
			"style_name" 		=> "YouTube",
			"demo_url"			=> plugins_url("../../assets/demos/youtube/youtube.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/youtube/youtube.html",
			"img_url"			=> plugins_url("../../assets/demos/youtube/youtube.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/youtube/customizer.js",__FILE__),
			"category"          => "All,Videos",
			"tags"              => "Video,YouTube,Play,Media",
			"options"			=> array(
			)
		)
	);
}