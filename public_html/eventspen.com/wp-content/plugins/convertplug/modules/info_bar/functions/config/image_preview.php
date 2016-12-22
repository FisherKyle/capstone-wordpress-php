<?php
if(function_exists("smile_framework_add_options")){

	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Info_Bars',"image_preview",
		array(
			"style_name" 		=> "Image Preview",
			"demo_url"			=> plugins_url("../../assets/demos/image_preview/image_preview.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/image_preview/image_preview.html",
			"img_url"			=> plugins_url("../../assets/demos/image_preview/image_preview.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/image_preview/customizer.js",__FILE__),
			"category"          => "All,Offers",
			"tags"              => "image, cta, call to action",
			"options"			=> array()
		)
	);
}
