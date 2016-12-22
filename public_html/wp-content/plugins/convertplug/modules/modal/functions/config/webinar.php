<?php
if(function_exists("smile_framework_add_options")){
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_Modals',"webinar",
		array(
			"style_name" 		=> "Webinar",
			"demo_url"			=> plugins_url("../../assets/demos/webinar/webinar.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/webinar/webinar.html",
			"img_url"			=> plugins_url("../../assets/demos/webinar/webinar.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/webinar/customizer.js",__FILE__),
			"category"          => "All,Optins,Updates",
			"tags"              => "Hangout,Webinar,Update,Training,Optin,Email,Subscribe",
			"options"			=> array(

			//field to set ckeditor for middle description
			array(
				"type" 		=> "textarea",
				"class" 	=> "",
				"name" 		=> "modal_middle_desc",
				"opts"		=> array(
					"title" 		=> __( "Middle Description", "smile" ),
					"value" 		=> __( "With John Doe",  "smile" ),
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