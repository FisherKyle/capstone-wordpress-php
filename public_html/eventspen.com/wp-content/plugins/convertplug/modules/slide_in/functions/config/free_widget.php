<?php
if(function_exists("smile_framework_add_options")){
// optin style
	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
	$style = isset( $_GET['style'] ) ? $_GET['style'] : '';
	smile_framework_add_options('Smile_slide_ins',"free_widget",
		array(
			"style_name" 		=> "Free Widget",
			"demo_url"			=> plugins_url("../../assets/demos/free_widget/free_widget.html",__FILE__),
			"demo_dir"			=> plugin_dir_path( __FILE__ )."../../assets/demos/free_widget/free_widget.html",
			"img_url"			=> plugins_url("../../assets/demos/free_widget/free_widget.png",__FILE__),
			"customizer_js"		=> plugins_url("../../assets/demos/free_widget/customizer.js",__FILE__),
			"category"          => "All,Optins,Updates",
			"tags"              => "Hangout,Optin,Update,Training,Optin,Email,Subscribe",
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