<?php
if(function_exists("smile_framework_add_options")){
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Modals',"social_media_with_form",
		array(
			"style_name" 		=> "Social Media With Form",
			"demo_url"			=> plugins_url("../../assets/demos/social_media_with_form/social_media_with_form.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/social_media_with_form/social_media_with_form.html",
			"img_url"			=> plugins_url("../../assets/demos/social_media_with_form/social_media_with_form.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/social_media_with_form/customizer.js",__FILE__),
			"category"          => "All,Optins,Updates,Social",
			"tags"              => "Hangout,Social Media,Update,Training,Optin,Email,Subscribe",
			"options"			=> array(

			//field to set ckeditor for middle description
			array(
				"type" 		=> "textarea",
				"class" 	=> "",
				"name" 		=> "modal_middle_desc",
				"opts"		=> array(
					"title" 		=> __( "Middle Description", "smile" ),
					"value" 		=> __( "For more details click on the below link.",  "smile" ),
				),
				"panel" 	 => "Name",
				"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
				"section" => "Design",
				"section_icon" => "connects-icon-disc",
		),
			)
		)
	);
}
