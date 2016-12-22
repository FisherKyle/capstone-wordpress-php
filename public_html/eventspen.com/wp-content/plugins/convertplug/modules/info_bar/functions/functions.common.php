<?php
if( function_exists( "smile_update_settings" ) ) {

	//get style id
	$style_id_for_ifbcustomcss ='';
	if( isset( $_GET['variant-style']) ) {
		$style_id_for_ifbcustomcss = $_GET['variant-style'];
		$style = $_GET['variant-style'];
	} else {
		if( isset( $_GET['style'] ) ){
	    	$style_id_for_ifbcustomcss = $_GET['style'];
		}
	}

	/* Get ConvertPlug Form Option Array */
	global $cp_form;
	global $cp_social;

	$name = array(
		array(
		    "type"         => "google_fonts",
		    "name"         => "cp_google_fonts",
		    "opts"         => array(
		        "title"     => __( "Google Fonts", "smile" ),
		        "value"     => "",
		    ),
		    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" 	 => "Design",
			"panel" => "Name",
			"section_icon" => "connects-icon-image",
		),

		array(
			"type" 		=> "textarea",
			"class" 	=> "",
			"name" 		=> "infobar_title",
			"opts"		=> array(
				"title" 		=> __( "Info Bar Title", "smile" ),
				"value" 		=> "Subscribe to our newsleter",
				"description" 	=> __( "Enter the main heading title.", "smile" ),
			),
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" 	 => "Design",
			"panel" => "Name",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "textarea",
			"class" 	=> "",
			"name" 		=> "infobar_description",
			"opts"		=> array(
				"title" 		=> __( "Info Bar Description", "smile" ),
				"value" 		=> "Loreal Epsum doller ",
				"description" 	=> __( "Enter the main heading title.", "smile" ),
			),
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" 	 => "Design",
			"panel" => "Name",
			"section_icon" => "connects-icon-image",
		),

	);

	/******* Background *****/
	$background = array (
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "bg_color",
			"opts"		=> array(
				"title" 		=> __( "Background Color", "smile" ),
				"value" 		=> "#dddddd",
				"description" 	=> __( "Select the background color for info bar.", "smile" ),
				"css_property" => "background-color",
				"css_selector" => ".cp-info-bar-body-overlay",
				"css_preview" => true,
			),
			"panel" 	 		=> "Background",
			"section" 			=> "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "bg_gradient",
			"opts"		=> array(
				"title" 	=> __( "Enable Gradient Background", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "Enhance your background with gradient effect.", "smile" ),
			),
			"panel" 	 		=> "Background",
			"section" 			=> "Design",
			"section_icon" => "connects-icon-image",
		),
		//	Hidden variable to store the (lighten border color)
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "bg_gradient_lighten",
			"opts"		=> array(
				"title" 		=> __( "Gradient Lighten", "smile" ),
				"value" 		=> __( "", "smile" ),
				"description" 	=> __( "Enter the short description of this optin.(HTML is Allowed)", "smile" ),
			),
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"panel" 	 		=> "Background",
			"section" 			=> "Design",
			"section_icon" => "connects-icon-image",
		),
		//    Hidden variable to store the (darken button color)
		array(
		    "type"         => "textfield",
		    "class"     => "",
		    "name"         => "btn_darken",
		    "opts"        => array(
		        "title"         => __( "Button Darken", "smile" ),
		        "value"         => __( "", "smile" ),
		        "description"     => __( "Enter the short description of this optin.(HTML is Allowed)", "smile" ),
		    ),
		    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
		    "section"      => "Design",
		    "panel" => "Background",
		    "section_icon" => "connects-icon-image",
		),
		//    Hidden variable to store the (gradient button color)
		array(
		    "type"         => "textfield",
		    "class"     => "",
		    "name"         => "btn_gradiant",
		    "opts"        => array(
		        "title"         => __( "Button Darken", "smile" ),
		        "value"         => __( "", "smile" ),
		        "description"     => __( "Enter the short description of this optin.(HTML is Allowed)", "smile" ),
		    ),
		    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
		    "section"      => "Design",
		    "panel" => "Background",
		    "section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "info_bar_bg_image_src",
			"opts" 		=> array(
				"title" 	=> __( "Background Image source","smile"),
				"value" 	=> "upload_img",
				"options" 	=> array(
						__( "Custom URL", "smile" ) 	 => "custom_url",
						__( "Upload Image", "smile" ) 	 => "upload_img",
						__( "None", "smile" ) 	 		 => "none",
					)
				),
			"panel" => "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "info_bar_bg_image_custom_url",
			"opts"		=> array(
				"title" 		=> __( "Custom URL", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "Enter custom URL for your image.", "smile" ),
			),
			"panel" 	=> "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'info_bar_bg_image_src', 'operator' => '==', 'value' => 'custom_url')
		),
		array(
			"type" 		=> "media",
			"class" 	=> "",
			"name" 		=> "info_bar_bg_image",
			"opts"		=> array(
				"title" 		=> __( "Background Image", "smile" ),
				"value" 		=> "",
				 "css_selector"	=> ".cp-info-bar-body",
				 "css_property"	=> "background-image",
				 "css_preview"	=> true,
				"description" 	=> __( "You can provide an image that would be appear behind the content in the Info Bar area. For this setting to work, the background color you've chosen must be transparent.", "smile" ),
			),
			"panel" 	 		=> "Background",
			"section" 			=> "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'info_bar_bg_image_src', 'operator' => '==', 'value' => 'upload_img')
		),
		array(
			"type" 		=> "background",
			"class" 	=> "",
			"name" 		=> "opt_bg",
			"opts"		=> array(
				"title" 		=> "",
				"value" 		=> "no-repeat|center|cover"
			),
			"panel" => "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			// "dependency" => array('name' => 'info_bar_bg_image_src', 'operator' => '!==', 'value' => 'none')
		),
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "infobar_size_section",
			"opts"		=> array(
				"title" 		=> __( "Size", "smile" ),
				"value" 		=> "",
			),

			"section" => "Design",
			"panel" => "Background",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "infobar_height",
			"opts"			=> array(
				"title" 		=> __( "Height", "smile" ),
				"value" 		=> 50,
				"min" 			=> 30,
				"max" 			=> 700,
				"step" 			=> 1,
				"suffix" 		=> "px",
				"description" 	=> __( "Set the height for info bar? (value in px).", "smile" ),
				"css_property"  => "min-height",
				"css_selector" => ".cp-info-bar-body",
			),
			"section" 	 => "Design",
			"panel" => "Background",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "infobar_width",
			"opts"			=> array(
				"title" 		=> __( "Width", "smile" ),
				"value" 		=> 1000,
				"min" 			=> 320,
				"max" 			=> 1600,
				"step" 			=> 1,
				"suffix" 		=> "px",
				"description" 	=> __( "Set the width for info bar? (value in px).", "smile" ),
				"css_property" => "width",
				"css_selector" => ".cp-ib-container",
			),
			"section" 	 => "Design",
			"panel" => "Background",
			"section_icon" => "connects-icon-image",
		)

	);

	/******* Advance Design Options *****/
	$advance_options = array (

		//	Position
		array(
			"type" 	=> "dropdown",
			"class" => "",
			"name" 	=> "infobar_position",
			"opts" 	=> array(
				"title" 	=> __( "Info Bar Position", "smile" ),
				"value" 	=> "cp-pos-top",
				"options" 	=> array(
					__( "Top of the page", "smile" ) 	 => "cp-pos-top",
					__( "Bottom of the page", "smile" )	 => "cp-pos-bottom"
				)
			),
			"section" 	 => "Design",
			"panel" => "Advanced",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "fix_position",
			"opts"		=> array(
				"title" 	=> __( "Sticky", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "Enable to stick the info bar at its position and follow the scroll.", "smile" ),
			),
			"section" 	 => "Design",
			"panel" => "Advanced",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "page_down",
			"opts"		=> array(
				"title" 	=> __( "Push Page", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "Enable to push the page down and display the info bar above the page.", "smile" ),
			),
			"section" 	 => "Design",
			"panel" => "Advanced",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'infobar_position', 'operator' => '==', 'value' => 'cp-pos-top'),
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "animate_push_page",
			"opts"		=> array(
				"title" 	=> __( "Animate Push Page", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "Enable to animate page towards down while loading Info Bar.", "smile" ),
			),
			"section" 	 => "Design",
			"panel" => "Advanced",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'page_down', 'operator' => '==', 'value' => true ),
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "position_link",
			"opts"		=> array(
				"link" 		=> __( "If your theme has fixed header feature then there might be chances of Push Page will not work. In that case find ID or class of fixed header div & enter it <a target=\"_blank\" href=\"".admin_url('admin.php?page=convertplug&view=debug&author=true')."\">here.</a>", "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"section" 	 => "Design",
			"panel" => "Advanced",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'infobar_position', 'operator' => '==', 'value' => 'cp-pos-top','name' => 'page_down', 'operator' => '==', 'value' => 'true'),
		),

		//	Border
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "custom_code_sec_title",
			"opts"		=> array(
				"title" 		=> __( "Border", "smile" ),
				"value" 		=> "",
			),
			"section" 	 => "Design",
			"panel" => "Advanced",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "enable_border",
			"opts"		=> array(
				"title" 	=> __( "Border", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "Enable Border", "smile" ),
			),
			"section" 	 => "Design",
			"panel" => "Advanced",
			"section_icon" => "connects-icon-image",
		),
		//	Hidden variable to store the (darken border color)
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "border_darken",
			"opts"		=> array(
				"title" 		=> __( "Border Color", "smile" ),
				"value" 		=> "#2c8dd7",
				"css_property"  => "border-color",
				"css_selector" => ".cp-info-bar",
			),
			"dependency" => array('name' => 'enable_border', 'operator' => '==', 'value' => true),
			"section" 	 => "Design",
			"panel" => "Advanced",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "custom_code_sec_title",
			"opts"		=> array(
				"title" 		=> __( "Shadow", "smile" ),
				"value" 		=> "",
			),
			"section" 	 => "Design",
			"panel" => "Advanced",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "enable_shadow",
			"opts"		=> array(
				"title" 	=> __( "Shadow", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "Enable Shadow", "smile" ),
			),
			"section" 	 => "Design",
			"panel" => "Advanced",
			"section_icon" => "connects-icon-image",
		),

		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "custom_code_sec_title",
			"opts"		=> array(
				"title" 		=> __( "Custom Code", "smile" ),
				"value" 		=> "",
			),
			"section" 	 => "Design",
			"panel" => "Advanced",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "textarea",
			"class" 	=> "",
			"name" 		=> "custom_css",
			"opts"		=> array(
				"title" 	=> __( "Custom CSS", "smile" ),
				"value" 	=> '',
				"description" 	=> __( "Enter your custom css code for this Info Bar here.", "smile" ),
			),
			"section" 	 => "Design",
			"panel" => "Advanced",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "custom_css_link",
			"opts"		=> array(
				"link" 		=> __( "Add custom CSS to your style. Write custom css statement with prefixed the following unique class :<br><br/><span style='color:#444;font-size:18px;font-family: monospace;' ><b>.".$style_id_for_ifbcustomcss."</span> </b> ".__("", "smile" )."", "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"section" 	 => "Design",
			"panel" => "Advanced",
			"section_icon" => "connects-icon-image",
		),
	);

	/****** Behaviour ******/
	$behavior = array(
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "ib_exit_intent",
			"opts"		=> array(
				"title" 	=> __( "Before User Leaves / Exit Intent", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "If enabled, Info Bar will load right before user is about to leave your website.", "smile" ),
			),
			"panel" 	=> "Smart Launch",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "autoload_on_duration",
			"opts"		=> array(
				"title" 	=> __( "After Few Seconds", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "If enabled, Info Bar will load automatically after few seconds.", "smile" ),
			),
			"panel" 	=> "Smart Launch",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "load_on_duration",
			"opts"			=> array(
				"title" 		=> __( "Load After Seconds", "smile" ),
				"value" 		=> 1,
				"min" 			=> 0.1,
				"max" 			=> 100,
				"step" 			=> 0.1,
				"suffix" 		=> "Sec",
				"description" 	=> __( "How long the Info Bar should take to be displayed after the page is loaded? (value in seconds).", "smile" ),
			),
			"panel" 		=> "Smart Launch",
			"dependency" => array('name' => 'autoload_on_duration', 'operator' => '==', 'value' => '1'),
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "autoload_on_scroll",
			"opts"		=> array(
				"title" 	=> __( "After User Scrolls", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "If enabled, Info Bar will load as user scrolls down on the page.", "smile" ),
			),
			"panel" 	=> "Smart Launch",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "load_after_scroll",
			"opts"			=> array(
				"title" 		=> __( "Load After Scroll %", "smile" ),
				"value" 		=> 75,
				"min" 			=> 1,
				"max" 			=> 100,
				"step" 			=> 1,
				"suffix" 		=> "%",
				"description" 	=> __( "How much should the user scroll the page to display the Info Bar? (value in %).", "smile" ),
			),
			"panel" 		=> "Smart Launch",
			"dependency" => array('name' => 'autoload_on_scroll', 'operator' => '==', 'value' => '1'),
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "inactivity",
			"opts"		=> array(
				"title" 	=> __( "When User Is Inactive", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" => __( "If enabled, a Info Bar will be displayed to visitor if he is idle on page for certain time.", "smile" )
			),
			"panel" 	=> "Smart Launch",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "inactivity_link",
			"opts"		=> array(
				"link" 		=> __( "Info Bar will trigger after `".$user_inactivity."  ".__("Seconds", "smile" )."` of user inactivity. If you would like, you can change the time <a target=\"_blank\" href=\"".admin_url('admin.php?page=convertplug&view=settings#user_inactivity')."\">here</a>", "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"panel" 	=> "Smart Launch",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
			"dependency" => array('name' => 'inactivity', 'operator' => '==', 'value' => 'true'),
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "enable_after_post",
			"opts"		=> array(
				"title" 	=> __( "Launch after content", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "Info Bar will be triggered when user scrolls to the end of post.", "smile" ),
			),
			"panel" 	=> "Smart Launch",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
		),

		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "enable_display_inline",
			"opts"		=> array(
				"title" 	=> __( "Display Inline", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "If enabled, module will display inline as a part of page / post content.", "smile" ),
			),
			"panel" 	=> "Smart Launch",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
		),
		array(
            "type"      => "dropdown",
            "class"     => "",
            "name"      => "inline_position",
            "opts"      => array(
                "title" 	=> __( "Display Inline Position", "smile" ),
                "value"     => "none",
                "description" => __( "Select the position, where you want to display module inline.", "smile" ),
                "options"   => array(
                        __( "Before Post", "smile" ) => "before_post",
                        __( "After Post", "smile" )  => "after_post",
                        __( "Both", "smile" )        => "both"
                    )
                ),
            "panel" => "Smart Launch",
            "section" => "Behavior",
            "section_icon" => "connects-icon-toggle",
            "dependency" => array('name' => 'enable_display_inline', 'operator' => '==', 'value' => 'true')
        ),
        array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "enable_custom_scroll",
			"opts"		=> array(
				"title" 	=> __( "After Scroll To Certain ID / Class", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "Inof Bar will be triggered when user scrolls to certain css class or id.", "smile" ),
			),
			"panel" 	=> "Smart Launch",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "enable_scroll_class",
			"opts"		=> array(
				"title" 		=> __( "Enter Class Name/Id", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "Enter CSS Class / ID <br/>[ You can enter multiple values here by separating with comma. Class name should start with '.' & id name should start with '#', example => #id, .class ]", "smile" ),
			),
			"panel" => "Smart Launch",
            "section" => "Behavior",
            "section_icon" => "connects-icon-toggle",
            "dependency" => array('name' => 'enable_custom_scroll', 'operator' => '==', 'value' => 'true')
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "enable_custom_class",
			"opts"		=> array(
				"title" 	=> __( "Launch With CSS Class", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "Info Bar can be triggered on click of any UI element. Just provide the unique CSS class of that element here and Info Bar will be trigger when you click on that element.", "smile" ),
			),
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"panel" 	=> "Manual Display",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "custom_class",
			"opts"		=> array(
				"title" 		=> __( "Launch With CSS Class", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "<br>Info Bar can be triggered on click of any UI element. Just provide the unique CSS class of that element here and Info Bar will be trigger when you click on that element.<br> If you have multiple classes, separate them with comma. Example - widget-title, site-description<br>", "smile" ),
			),
			"panel" 	=> "Manual Display",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "custom_shortcode",
			"opts"		=> array(
				"link" 		=> '[cp_info_bar id="'.$style.'"]' . __( 'Your Content', "smile" ) . '[/cp_info_bar]',
				"class" 	=> "cp-shortcode",
				"value" 		=> "",
				"title" 		=> __( "Launch With Shortcode", "smile" ),
				"description" 	=> __( "Place your text, image or HTML in-between the provided shortcode to launch the Info Bar.", "smile" ),
			),
			"panel" 	=> "Manual Display",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "inline_shortcode",
			"opts"		=> array(
				"link" 			=> '[cp_info_bar display="inline" id="'.$style.'"][/cp_info_bar]',
				"class" 		=> "cp-shortcode",
				"value" 		=> "",
				"title" 		=> __( "Display Inline", "smile" ),
				"description" 	=> __( "Use this shortcode to display Info Bar popup inline as a part of page content / Widget.", "smile" ),
			),
			"panel" 	=> "Manual Display",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
		),

		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "developer_mode",
			"opts"		=> array(
				"title" 	=> __( "Enable Cookies", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description"=> __( "ConvertPlug can check user history and limit repeat occurrence of Info Bar when cookies are enabled. No more annoying Info Bars!", "smile" ),
			),
			"panel" 	=> "Repeat Control",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "conversion_cookie",
			"opts"			=> array(
				"title" 		=> __( "Do Not Show After Conversion", "smile" ),
				"value" 		=> 90,
				"min" 			=> 0,
				"max" 			=> 365,
				"step" 			=> 1,
				"suffix" 		=> "days",
				"description" 	=> __( "How many days this Info Bar should not be displayed after user submits the form?", "smile" ),
			),
			"panel" 		=> "Repeat Control",
			"dependency" 	=> array('name' => 'developer_mode', 'operator' => '==', 'value' => '1'),
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "closed_cookie",
			"opts"			=> array(
				"title" 		=> __( "Do Not Show After Closing", "smile" ),
				"value" 		=> 15,
				"min" 			=> 0,
				"max" 			=> 365,
				"step" 			=> 1,
				"suffix" 		=> "days",
				"description" 	=> __( "How many days this Info Bar should not be displayed after user closes the Info Bar?", "smile" ),
			),
			"panel" 		=> "Repeat Control",
			"dependency" 	=> array('name' => 'developer_mode', 'operator' => '==', 'value' => '1'),
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
		),

		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "global",
			"opts"		=> array(
				"title" 	=> __( "Enable On Complete Site", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "If set YES, code of this Info Bar will be added throughout the website so it can function anywhere. If set NO - select the specific areas where you want the Info Bar to function and code will be automatically embedded there.", "smile" ),
			),
			"panel" 	=> "Target Pages",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog"
		),
		array(
			"type" 		=> "group_filters",
			"class" 	=> "",
			"name" 		=> "exclusive_on",
			"opts"		=> array(
				"title" 	=> __( "Enable Only On", "smile" ),
				"description" => __("Enable Info Bar on selected pages, posts, custom posts, special pages.", "smile" ),
				"value" 	=> '',
			),
			"panel" 	=> "Target Pages",
			"section" => "Behavior",
			"section_icon" => "connects-icon-eye",
			"dependency" => array('name' => 'global', 'operator' => '==', 'value' => '0'),
		),
		array(
			"type" 		=> "post-types",
			"class" 	=> "",
			"name" 		=> "exclusive_post_type",
			"opts"		=> array(
				"title" 	=> __( "", "smile" ),
				"description" => __("Enable Info Bar on all single posts of particular custom post types, taxonomies.", "smile" ),
				"value" 	=> '',
			),
			"panel" 	=> "Target Pages",
			"section" => "Behavior",
			"section_icon" => "connects-icon-eye",
			"dependency" => array('name' => 'global', 'operator' => '==', 'value' => '0'),
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "inactivity_link",
			"opts"		=> array(
				"link" 		=> __( "You can select the exceptional areas, where you want this Info Bar to function.", "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"panel" 	=> "Target Pages",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
			"dependency" => array('name' => 'global', 'operator' => '==', 'value' => 'false')
		),
		array(
			"type" 		=> "group_filters",
			"class" 	=> "",
			"name" 		=> "exclude_from",
			"opts"		=> array(
				"title" 	=> __( "Exceptionally, Disable On", "smile" ),
				"description" => __( 'Exceptionally disable Info Bar on selected pages, posts, custom posts, special pages.', 'smile' ),
				"value" 	=> '',
			),
			"panel" 	=> "Target Pages",
			"section" => "Behavior",
			"section_icon" => "connects-icon-eye",
			"dependency" => array('name' => 'global', 'operator' => '==', 'value' => '1'),
		),
		array(
			"type" 		=> "post-types",
			"class" 	=> "",
			"name" 		=> "exclude_post_type",
			"opts"		=> array(
				"title" 	=> __( "", "smile" ),
				"description" => __("Exceptionally disable Info Bar on all single posts of particular custom post types, taxonomies.", "smile" ),
				"value" 	=> '',
			),
			"panel" 	=> "Target Pages",
			"section" => "Behavior",
			"section_icon" => "connects-icon-eye",
			"dependency" => array('name' => 'global', 'operator' => '==', 'value' => '1'),
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "inactivity_link",
			"opts"		=> array(
				"link" 		=> __( "You can select the areas, where you do not want this Info Bar to function.", "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"panel" 	=> "Target Pages",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
			"dependency" => array('name' => 'global', 'operator' => '==', 'value' => 'true'),
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "show_for_logged_in",
			"opts"		=> array(
				"title" 	=> __( "Logged-in Users", "smile" ),
				"value" 	=> true,
				"on" 		=> "SHOW",
				"off"		=> "HIDE",
				"description"=> __( "If your website has login functionality, should the Info Bar be visible to logged users?", "smile" ),
			),
			"panel" 	=> "Target Visitors",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog"
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "display_on_first_load",
			"opts"		=> array(
				"title" 	=> __( "First Time Users", "smile" ),
				"value" 	=> true,
				"on" 		=> "SHOW",
				"off"		=> "HIDE",
				"description"=> __( "When user visits your site for the first time, should Info Bar be visible?", "smile" ),
			),
			"panel" 	=> "Target Visitors",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog"
		),
		array(
			"type" 		=> "checkbox",
			"class" 	=> "",
			"name" 		=> "hide_on_device",
			"opts" 		=> array(
				"title" 	=> __( "Hide on Devices","smile"),
				"value" 	=> "",
				"options" 	=> array(
						__( "Desktop", "smile" ) 	=> "desktop",
						__( "Tablet", "smile" ) 	=> "tablet",
						__( "Mobile", "smile" ) 	=> "mobile",
					)
				),
			"panel" 	=> "Target Visitors",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "inactivity_link",
			"opts"		=> array(
				"link" 		=> __( "By default, this Info Bar will be effective for all. However using controls above, you can hide it for certain visitors.", "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"panel" 	=> "Target Visitors",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "enable_referrer",
			"opts"		=> array(
				"title" 	=> __( "Referrer Detection", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "Display To", "smile" ),
				"off"		=> __( "Hide From", "smile" ),
				"description" 	=> __( "Info Bar can be displayed when the user is came from a website you would like to track. Eg. If you set to track google.com, all users coming from google will see this popup.", "smile" ),
			),
			"panel" 	=> "Target Visitors",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
		),
		array(
			"type" 		=> "tags",
			"class" 	=> "",
			"name" 		=> "display_to",
			"opts"		=> array(
				"title" 		=> __( "Display only to -", "smile" ),
				"value" 		=> "",
			),
			"dependency" => array('name' => 'enable_referrer', 'operator' => '==', 'value' => 'true'),
			"panel" 	=> "Target Visitors",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
		),
		array(
			"type" 		=> "tags",
			"class" 	=> "",
			"name" 		=> "hide_from",
			"opts"		=> array(
				"title" 		=> __( "Hide only from -", "smile" ),
				"value" 		=> "",
			),
			"dependency" => array('name' => 'enable_referrer', 'operator' => '==', 'value' => '0'),
			"panel" 	=> "Target Visitors",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "live",
			"opts"		=> array(
				"title" 	=> __( "Enable Info Bar On Site", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "LIVE", "smile" ),
				"off"		=> __( "PAUSE", "smile" ),
				"description" 	=> __( "When Info Bar set as pause, it won't be effective on your website.", "smile" ),
			),
			"panel" 	=> "Info Bar Status",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog"
		),
	);

	/****** Submission ******/
	$submission = array(
		array(
			"type" 		=> "mailer",
			"class" 	=> "",
			"name" 		=> "mailer",
			"opts"		=> array(
				"title" 	=> __( "Collect Leads Using -", "smile" ),
				"value" 	=> '',
			),
			"panel" 	=> "Form Setup",
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 		=> "textarea",
			"class" 	=> "",
			"name" 		=> "custom_html_form",
			"opts"		=> array(
				"title" 		=> __( "Paste HTML Code", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "Paste the HTML code of your form, that you can get in your CRM Software like MailChimp", "smile" ),
			),
			"panel" 	 => "Form Setup",
			"dependency"	=> array("name" => "mailer", "operator" => "==", "value" => "custom-form"),
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "inactivity_link",
			"opts"		=> array(
				"link" 		=> __( '"First" is the default and ready to use campaign. If you would like, you can create a new campaign <a href="'.admin_url('admin.php?page=contact-manager&view=new-list').'" target=\"_blank\">here</a>.', "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"panel" 	=> "Form Setup",
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
			"dependency"	=> array("name" => "mailer", "operator" => "!=", "value" => "custom-form"),
		),
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "on_success",
			"opts" 		=> array(
				"title" 	=> __( "Successful Submission ","smile"),
				"value" 	=> "message",
				"options" 	=> array(
						__( "Display a message", "smile" ) 		=> "message",
						__( "Redirect user", "smile" ) 			=> "redirect",
					)
				),
			"panel" => "Form Setup",
			"dependency"	=> array("name" => "mailer", "operator" => "!=", "value" => "custom-form"),
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "redirect_url",
			"opts"		=> array(
				"title" 		=> __( "Redirect URL", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "Enter the URL where you would like to redirect the user after successfully added to the list.<br/><br/> Please add http / https prefix to URL. e.g. http://convertplug.com", "smile" ),
			),
			"panel" 	=> "Form Setup",
			"dependency" => array('name' => 'on_success', 'operator' => '==', 'value' => 'redirect'),
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "on_redirect",
			"opts" 		=> array(
				"title" 	=> __( "Redirect User To","smile"),
				"value" 	=> "message",
				"options" 	=> array(
						__( "Same Tab", "smile" ) 		=> "self",
						//__( "New Tab", "smile" ) 		=> "blank",
						__( "Download File", "smile" ) 	=> "download",
					)
				),
			"panel" => "Form Setup",
			"dependency" => array('name' => 'on_success', 'operator' => '==', 'value' => 'redirect'),
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
		),
		/*array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "download_url",
			"opts"		=> array(
				"title" 		=> __( "Download URL", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "Enter the URL where you would like to download the file after successfully added to the list.<br/><br/> Please add http / https prefix to URL. e.g. http://convertplug.com", "smile" ),
			),
			"panel" 	=> "Form Setup",
			"dependency" => array('name' => 'on_redirect', 'operator' => '==', 'value' => 'download'),
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
		),*/
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "redirect_data",
			"opts"		=> array(
				"title" 	=> __( "Pass Lead Data To Redirect URL", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "Passes the lead email (and name if enabled) as query arguments to redirect URL.", "smile" ),
			),
			"panel" 	=> "Form Setup",
			"dependency" => array('name' => 'on_success', 'operator' => '==', 'value' => 'redirect'),
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 		=> "textarea",
			"class" 	=> "",
			"name" 		=> "success_message",
			"opts"		=> array(
				"title" 		=> __( "Message After Success", "smile" ),
				"value" 		=> __( 'Thank you.', 'smile' ),
				"description" 	=> __( "Enter the message you would like to display the user after successfully added to the list.<br/>This input field supports HTML too.", "smile" ),
			),
			"panel" 	=> "Form Setup",
			"dependency" => array('name' => 'on_success', 'operator' => '==', 'value' => 'message'),
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "message_color",
			"opts"		=> array(
				"title" 		=> __( "Message Text Color", "smile" ),
				"value" 		=> "#000000",
				"description" 	=> __( "Select the text color for success message.", "smile" ),
			),
			"panel" 	=> "Form Setup",
			"dependency" => array('name' => 'on_success', 'operator' => '==', 'value' => 'message'),
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
		),
		//infobar close After form submission Options
 		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "form_action_on_submit",
			"opts" 		=> array(
				"title" 	=> __( "Action After Submission","smile"),
				"value" 	=> "do_nothing",
				"options" 	=> array(
						__( "Reappear Form", "smile" ) 		=> "reappear",
						__( "Hide Form", "smile" ) 			=> "disappears",
						__( "Do Nothing", "smile" ) 		=> "do_nothing",
					),
				"description" 	=> __( "Select how your Info Bar behaves after successful form submission.", "smile" ),
				),
			"panel" => "Form Setup",
			"dependency" => array('name' => 'on_success', 'operator' => '==', 'value' => 'message'),
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "form_reappear_time",
			"opts"			=> array(
				"title" 		=> __( "Reappear Form After 'x' Seconds of Submission", "smile" ),
				"value" 		=> 1,
				"min" 			=> 0,
				"max" 			=> 100,
				"step" 			=> 1,
				"suffix" 		=> "s",
				"description" 	=> __( "Reappear form after successful form submission.", "smile" ),
			),
			"panel" => "Form Setup",
			"dependency"	=> array("name" => "form_action_on_submit", "operator" => "==", "value" => "reappear"),
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "form_disappears_time",
			"opts"			=> array(
				"title" 		=> __( "Hide Info Bar After 'x' Seconds of Submission", "smile" ),
				"value" 		=> 1,
				"min" 			=> 0,
				"max" 			=> 100,
				"step" 			=> 1,
				"suffix" 		=> "s",
				"description" 	=> __( "Hide Info Bar after successful form submission.", "smile" ),
			),
			"panel" => "Form Setup",
			"dependency"	=> array("name" => "form_action_on_submit", "operator" => "==", "value" => "disappears"),
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
		),
		//fail submission
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "msg_on_fail_submission",
			"opts"		=> array(
				"title" 		=> __( "Failed Submission", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Form Setup",
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
			"dependency"	=> array("name" => "mailer", "operator" => "!=", "value" => "custom-form"),
		),
		array(
			"type" 		=> "textarea",
			"class" 	=> "",
			"name" 		=> "msg_wrong_email",
			"opts"		=> array(
				"title" 		=> __( "Failed Submission", "smile" ),
				"value" 		=> __( "Please enter correct email address.", "smile" ),
				"description" 	=> __( "Enter the message you would like to display the user for invalid email address.<br/>This input field supports HTML too.", "smile" ),
			),
			"panel" 	=> "Form Setup",
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
			"dependency"	=> array("name" => "mailer", "operator" => "!=", "value" => "custom-form"),
		),
	);

/*---------- Animation-----------*/
$animation_array = array(
			"No Effect"			  	=> 'smile-none',
			"3D Slit"           	=> 'smile-3DSlit',
			"3D Sign"           	=> 'smile-3DSign',
			"3D Rotate Bottom"      => 'smile-3DRotateBottom',
			"3D Rotate In Left"     => 'smile-3DRotateInLeft',
			"3D Flip Vertical"      => 'smile-3DFlipVertical',
			"3D Flip Horizontal"    => 'smile-3DFlipHorizontal',
			"Bounce" 			  	=> 'smile-bounce',
			"Bounce In"           	=> 'smile-bounceIn',
			"Bounce In Down"      	=> 'smile-bounceInDown',
			"Bounce In Left"      	=> 'smile-bounceInLeft',
			"Bounce In Right"     	=> 'smile-bounceInRight',
			"Bounce In Up"        	=> 'smile-bounceInUp',
			"Fade In"               => 'smile-fadeIn',
			"Fade In & Scale"       => 'smile-fadeInScale',
			"Fade In Down"          => 'smile-fadeInDown',
			"Fade In Down Big"      => 'smile-fadeInDownBig',
			"Fade In Left"          => 'smile-fadeInLeft',
			"Fade In Left Big"      => 'smile-fadeInLeftBig',
			"Fade In Right"         => 'smile-fadeInRight',
			"Fade In Right Big"     => 'smile-fadeInRightBig',
			"Fade In Up"            => 'smile-fadeInUp',
			"Fade In Up Big"        => 'smile-fadeInUpBig',
			"Fall"           		=> 'smile-fall',
			"Flash"   			  	=> 'smile-flash',
			"Flip In X"             => 'smile-flipInX',
			"Flip In Y"             => 'smile-flipInY',
			"Jello"               	=> 'smile-jello',
			"Light Speed In"        => 'smile-lightSpeedIn',
			"Newspaper"           	=> 'smile-newsPaper',
			"Pulse"         	  	=> 'smile-pulse',
			"Roll In"               => 'smile-rollIn',
			"Rotate In"             => 'smile-rotateIn',
			"Rotate In Down Left"   => 'smile-rotateInDownLeft',
			"Rotate In Down Right"  => 'smile-rotateInDownRight',
			"Rotate In Up Left"     => 'smile-rotateInUpLeft',
			"Rotate In Up Right"    => 'smile-rotateInUpRight',
			"Rubber Band"   	  	=> 'smile-rubberBand',
			"Shake"         	  	=> 'smile-shake',
			"Side Fall"           	=> 'smile-sideFall',
			"Slide In Bottom"     	=> 'smile-slideInBottom',
			"Slide In Down"         => 'smile-slideInDown',
			"Slide In Left"         => 'smile-slideInLeft',
			"Slide In Right"        => 'smile-slideInRight',
			"Slide In Up"           => 'smile-slideInUp',
			"Super Scaled"          => 'smile-superScaled',
			"Swing"               	=> 'smile-swing',
			"Tada"                	=> 'smile-tada',
			"Wobble"              	=> 'smile-wobble',
			"Zoom In"               => 'smile-zoomIn',
			"Zoom In Down"          => 'smile-zoomInDown',
			"Zoom In Left"          => 'smile-zoomInLeft',
			"Zoom In Right"         => 'smile-zoomInRight',
			"Zoom In Up"            => 'smile-zoomInUp'
		);

$exit_animation = array(
		"No Effect"				=> 'cp-overlay-none',
		"Bounce Out"          	=> 'smile-bounceOutDown',
		"Bounce Out Down"     	=> 'smile-bounceOutDown',
		"Bounce Out Left"     	=> 'smile-bounceOutLeft',
		"Bounce Out Right"    	=> 'smile-bounceOutRight',
		"Bounce Out Up"       	=> 'smile-bounceOutUp',
		//"Fade"					=> 'cp-overlay-fade',
		"Fade Out"              => 'smile-fadeOut',
		"Fade Out Down"         => 'smile-fadeOutDown',
		"Fade Out Down Big"     => 'smile-fadeOutDownBig',
		"Fade Out Left"         => 'smile-fadeOutLeft',
		"Fade Out Left Big"     => 'smile-fadeOutLeftBig',
		"Fade Out Right"        => 'smile-fadeOutRight',
		"Fade Out Right Big"    => 'smile-fadeOutRightBig',
		"Fade Out Up"           => 'smile-fadeOutUp',
		"Fade Out Up Big"       => 'smile-fadeOutUpBig',
		"Flip Out X"            => 'smile-flipOutX',
		"Flip Out Y"            => 'smile-flipOutY',
		"Hinge"                 => 'smile-hinge',
		"Light Speed Out"       => 'smile-lightSpeedOut',
		"Rotate Out"            => 'smile-rotateOut',
		"Rotate Out Down Left"  => 'smile-rotateOutDownLeft',
		"Rotate Out Down Right" => 'smile-rotateOutDownRight',
		"Rotate Out Up Left"    => 'smile-rotateOutUpLeft',
		"Rotate Out Up Right"   => 'smile-rotateOutUpRight',
		"RollOut"               => 'smile-rollOut',
		"Slide Out Down"      	=> 'smile-slideOutDown',
		"Slide Out Left"        => 'smile-slideOutLeft',
		"Slide Out Right"       => 'smile-slideOutRight',
		"Slide Out Up"          => 'smile-slideOutUp',
		"Zoom Out"              => 'smile-zoomOut',
		"Zoom Out Down"         => 'smile-zoomOutDown',
		"Zoom Out Left"         => 'smile-zoomOutLeft',
		"Zoom Out Right"        => 'smile-zoomOutRight',
		"Zoom Out Up"           => 'smile-zoomOutUp'
);
$animation = array(

		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "entry_animation",
			"opts"		=> array(
				"title" 		=> __( "Entry Animation", "smile" ),
				"description" 	=> __( "Select the entry level animation for info bar.", "smile" ),
				"value"			=> "smile-slideInDown",
				"options" 		=> $animation_array
			),
			"panel" 		=> "Animation",
			"section" 		=> "Design",
			"section_icon" 	=> "connects-icon-image",
		),
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "exit_animation",
			"opts"		=> array(
				"title" 		=> __( "Exit Animation", "smile" ),
				"description" 	=> __( "Select the exit level animation for info bar.", "smile" ),
				"value"			=> "smile-slideOutUp",
				"options" 		=> $exit_animation
			),
			"panel" 		=> "Animation",
			"section" 		=> "Design",
			"section_icon" 	=> "connects-icon-image",
		),
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "button_animation",
			"opts"		=> array(
				"title" 		=> __( "Button Animation", "smile" ),
				"description" 	=> __( "Select the exit level animation for info bar submit button .", "smile" ),
				"value"			=> "smile-none",
				"options" 		=> $animation_array
			),
			"panel" 		=> "Animation",
			"section" 		=> "Design",
			"section_icon" 	=> "connects-icon-image",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "disable_overlay_effect",
			"opts"		=> array(
				"title" 	=> __( "Disable Animation on Small Screens", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "On smaller screens like mobile, disable animation with this setting.", "smile" ),
			),
			"dependency"	=> array("name" => "overlay_effect", "operator" => "!=", "value" => "cp-overlay-none"),
			"panel" 		=> "Animation",
			"section" 		=> "Design",
			"section_icon" 	=> "connects-icon-image",
		),
		array(
			"type" 		=> "slider",
			"class" 	=> "",
			"name" 		=> "hide_animation_width",
			"opts"		=> array(
				"title" 		=> __( "Disable When Browser Width Is Below -", "smile" ),
				"value" 		=> 768,
				"min" 			=> 240,
				"max" 			=> 1200,
				"step" 			=> 1,
				"description" 	=> __( "When width of the browser is below provided value, the Info Bar animation will disable.", "smile" ),
			),
			"dependency"	=> array("name" => "disable_overlay_effect", "operator" => "==", "value" => "1"),
			"panel" 		=> "Animation",
			"section" 		=> "Design",
			"section_icon" 	=> "connects-icon-image",
		),
	);

	$close_link = array(
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "close_info_bar",
			"opts" 		=> array(
				"title" 	=> __( "Type","smile"),
				"value" 	=> "close_img",
				"options" 	=> array(
						__( "Image", "smile" ) 			=> "close_img",
						__( "Text", "smile" ) 			 => "close_txt",
						__( "Do Not Close", "smile" ) 	 => "do_not_close",
					)
				),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "close_ib_image_src",
			"opts" 		=> array(
				"title" 	=> __( "Image source","smile"),
				"value" 	=> "upload_img",
				"options" 	=> array(
						__( "Custom URL", "smile" ) 	 => "custom_url",
						__( "Upload Image", "smile" ) 	 => "upload_img",
						__( "Predefined Icons", "smile" ) => "pre_icons"
					)
				),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'close_info_bar', 'operator' => '==', 'value' => 'close_img'),
		),
		array(
            "type"  =>  "radio-image",
            "name"  =>  "close_icon",
            "opts"  =>  array(
                "title" => __( "", "smile" ),
                "value" => 'default',
                "width" => '80px',
                "options" => array(
                     "black"        => CP_BASE_URL . 'modules/assets/images/black.png',
                    "blue_final"    => CP_BASE_URL . 'modules/assets/images/blue_final.png',
                    "circle_final"  => CP_BASE_URL . 'modules/assets/images/circle_final.png',
                    "default"    	=> CP_BASE_URL . 'modules/assets/images/default.png',
                    "grey_close"  	=> CP_BASE_URL . 'modules/assets/images/grey_close.png',
                    "red02" 		=> CP_BASE_URL . 'modules/assets/images/red02.png',
                    "rounded_black"    => CP_BASE_URL . 'modules/assets/images/rounded_black.png',
                    "white20"       => CP_BASE_URL . 'modules/assets/images/white20_bb.png'
                ),
				"imagetitle" => array(
					__( "title-0", "smile" ) 	=> "Black",
					__( "title-1", "smile" ) 	=> "Blue",
					__( "title-2", "smile" ) 	=> "Circle",
					__( "title-3", "smile" ) 	=> "Default",
					__( "title-4", "smile" ) 	=> "Grey",
					__( "title-5", "smile" ) 	=> "Red",
					__( "title-6", "smile" ) 	=> "Red",
					__( "title-7", "smile" ) 	=> "White"
				),
            ),
            "panel" => "Close Link",
            "section" => "Design",
            "section_icon" => "connects-icon-image",
            "dependency" => array('name' => 'close_ib_image_src', 'operator' => '==', 'value' => 'pre_icons')
        ),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "info_bar_close_img_custom_url",
			"opts"		=> array(
				"title" 		=> __( "Custom URL", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Close Link",
			"dependency" => array('name' => 'close_ib_image_src', 'operator' => '==', 'value' => 'custom_url'),
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "close_info_bar_pos",
			"opts" 		=> array(
				"title" 	=> __( "Position", "smile" ),
				"description" 	=> __( "Inline position will look good for smaller width Info Bar.", "smile" ),
				"value" 	=> true,
				"on" 		=> "FIXED",
				"off"		=> "INLINE"
			),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
		),

		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "close_txt",
			"opts"		=> array(
				"title" 		=> __( "Close Text", "smile" ),
				"value" 		=> "Close",
			),
			"panel" 	=> "Close Link",
			"dependency" => array('name' => 'close_info_bar', 'operator' => '==', 'value' => 'close_txt'),
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "close_text_color",
			"opts"		=> array(
				"title" 		=> __( "Close Text Color", "smile" ),
				"value" 		=> "rgb(238, 238, 238)",
			),
			"dependency" => array('name' => 'close_info_bar', 'operator' => '==', 'value' => 'close_txt'),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "media",
			"class" 	=> "",
			"name" 		=> "close_img",
			"opts"		=> array(
				"title" 		=> __( "Choose Image", "smile" ),
				"value" 		=> plugins_url('config/img/cross.png', __FILE__ ),
			),
			"panel" 	=> "Close Link",
			"dependency" => array('name' => 'close_ib_image_src', 'operator' => '==', 'value' => 'upload_img'),
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "close_img_width",
			"opts"			=> array(
				"title" 		=> __( "Close Image Width", "smile" ),
				"value" 		=> 22,
				"min" 			=> 14,
				"max" 			=> 128,
				"step" 			=> 1,
				"suffix" 		=> "px",
				"css_selector" => ".ib-img-close",
				"css_property" => "width",
			),
			"panel" 		=> "Close Link",
			"dependency" => array('name' => 'close_info_bar', 'operator' => '==', 'value' => 'close_img' , 'name' => 'close_ib_image_src','operator' => '!=', 'value' => 'none'),
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "adjacent_close_position",
			"opts" 		=> array(
				"title" 	=> __( "Close Image Position","smile"),
				"value" 	=> "top_right",
				"options" 	=> array(
						__( "Top Left", "smile" ) 		=> "top_left",
						__( "Top Right", "smile" ) 		=> "top_right",
						//__( "Bottom Left", "smile" ) 	=> "bottom_left",
						//__( "Bottom Right", "smile" )   => "bottom_right"
					),
				"description" 	=> __( "Choose position for close button.", "smile" ),
				),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'close_info_bar', 'operator' => '!=', 'value' => 'do_not_close'),
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "display_close_on_duration",
			"opts"		=> array(
				"title" 	=> __( "Display Close After Few Seconds", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "If enabled, close image / text will display after few seconds.", "smile" ),
			),
			"dependency" => array('name' => 'close_info_bar', 'operator' => '!=', 'value' => 'do_not_close'),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "close_btn_duration",
			"opts"			=> array(
				"title" 		=> __( "Display After Seconds", "smile" ),
				"value" 		=> 1,
				"min" 			=> 1,
				"max" 			=> 100,
				"step" 			=> 1,
				"suffix" 		=> "Sec",
				"description" 	=> __( "How long the close image / text to be displayed after Info Bar is loaded? (value in seconds).", "smile" ),
			),
			"dependency" => array('name' => 'display_close_on_duration', 'operator' => '==', 'value' => 'true'),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),

 		//	Toggle Button
 		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "ifb_button_options_title",
			"opts"		=> array(
				"title" 		=> __( "Toggle Button", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'close_info_bar', 'operator' => '!=', 'value' => 'do_not_close', 'name' => 'close_ib_image_src','operator' => '!=', 'value' => 'none'),
		),
 		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "toggle_btn",
			"opts"		=> array(
				"title" 	=> __( "Enable Toggle Button", "smile" ),
				"description" 	=> __( "Enable toggle button that will show or hide Info Bar on click event.", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
			),
			"panel" 	=> "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'close_info_bar', 'operator' => '!=', 'value' => 'do_not_close', 'name' => 'close_ib_image_src','operator' => '!=', 'value' => 'none'),
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "toggle_btn_visible",
			"opts"		=> array(
				"title" 	=> __( "Initially Display Toggle Button", "smile" ),
				"description" 	=> __( "Display toggle button by default.", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
			),
			"panel" 	=> "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'toggle_btn', 'operator' => '==', 'value' => true ),
		),
 		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "toggle_button_title",
			"opts"		=> array(
				"title" 		=> __( "Button Text", "smile" ),
				"value" 		=> "Click Me",
			),
			"panel" 	=> "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency"	=> array("name" => "toggle_btn", "operator" => "==", "value" => true),
		),
		array(
		    "type"         => "google_fonts",
		    "name"         => "toggle_button_font",
		    "opts"         => array(
		        "title"     => __( "Font Name", "smile" ),
		        "value"     => "",
		        "use_in"    => "panel"
		    ),
			"panel" 	=> "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency"	=> array("name" => "toggle_btn", "operator" => "==", "value" => true),
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "toggle_button_text_color",
			"opts"		=> array(
				"title" 		=> __( "Text Color", "smile" ),
				"value" 		=> "rgb(255, 255, 255)",
				"css_property" 	=> "color",
				"css_selector" 	=> ".cp-ifb-toggle-btn",
			),
			"dependency"	=> array("name" => "toggle_btn", "operator" => "==", "value" => true),
			"panel" 	=> "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "toggle_button_bg_color",
			"opts"		=> array(
				"title" 		=> __( "Background Color", "smile" ),
				"value" 		=> "rgb(0, 0, 0)",
				"css_property" 	=> "background",
				"css_selector" 	=> ".cp-ifb-toggle-btn",
			),
		    "dependency"	=> array("name" => "toggle_btn", "operator" => "==", "value" => true),
			"panel" 	=> "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
 		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "toggle_btn_gradient",
			"opts"		=> array(
				"value" 	=> false,
				"title" 	=> __( "Enable Gradient Background", "smile" ),
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "Enhance your button background with gradient effect.", "smile" ),
			),
			"panel" 	=> "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency"	=> array("name" => "toggle_btn", "operator" => "==", "value" => true),
		),

		//	store button darken on hover
		array(
		    "type"         => "textfield",
		    "name"         => "toggle_button_bg_hover_color",
		    "opts"         => array(
		        "title"     => __( "Button BG Hover Color", "smile" ),
		        "value"     => "",
		    ),
		    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"panel" 	=> "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		//	store button lighten gradient
		array(
		    "type"         => "textfield",
		    "name"         => "toggle_button_bg_gradient_color",
		    "opts"         => array(
		        "title"     => __( "Button Gradient Color", "smile" ),
		        "value"     => "",
		    ),
		    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"panel" 	=> "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		//	InfoBar Auto close Options
 		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "infobar_auto_close_section",
			"opts"		=> array(
				"title" 		=> __( "Auto Close Module", "smile" ),
				"value" 		=> "",
			),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency"	=> array("name" => "toggle_btn", "operator" => "!=", "value" => true ),
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "autoclose_on_duration",
			"opts"		=> array(
				"title" 	=> __( "Autoclose Module", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "If enabled, Info Bar will close automatically after 'x' seconds when user is inactive on webpage.", "smile" ),
			),
			"dependency"	=> array("name" => "toggle_btn", "operator" => "!=", "value" => true ),
			"panel" 	=> "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "close_module_duration",
			"opts"			=> array(
				"title" 		=> __( "Autoclose Duration", "smile" ),
				"value" 		=> 1,
				"min" 			=> 0.1,
				"max" 			=> 100,
				"step" 			=> 0.1,
				"suffix" 		=> "Sec",
				"description" 	=> __( "How long the Info Bar should take to be close after its loaded? (value in seconds).", "smile" ),
			),
			"dependency" => array('name' => 'autoclose_on_duration', 'operator' => '==', 'value' => '1'),
			"panel" 	=> "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),

	);

	$ib_content = array(
		array(
			"type" 		=> "textarea",
			"class" 	=> "",
			"name" 		=> "info_bar_content",
			"opts"		=> array(
				"title" 		=> __( "Info Bar Content", "smile" ),
				"value" 		=> __( "BLANK style is purely built for customization. This style supports text, images, shortcodes, HTML etc. Use Source button from Rich Text Editor toolbar & customize your Info Bar effectively.", "smile" ),
				"description" 	=> __( "Enter the short description of this optin.(HTML is Allowed)", "smile" ),
			),
			"panel" 	=> "Name",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		)
	);

	//separator color for border
	$seperator_color =array(
			array(
				"type" 		=> "colorpicker",
				"class" 	=> "",
				"name" 		=> "seperator_border_color",
				"opts"		=> array(
					"title" 		=> __( "Seperator Border Color", "smile" ),
					"value" 		=> "rgb(255, 255, 255)",
					"description" 	=> __( "Select the Seperator color for info bar.", "smile" ),
				),
				"panel" 	 		=> "Design",
				"section" 			=> "Design",
				"section_icon" 		=> "connects-icon-disc",
			)
		);

	/*** Array contains Info Bar image options ***/
	$ifb_img = array(

		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "info_bar_img_src",
			"opts" 		=> array(
				"title" 	=> __( "Image source","smile"),
				"value" 	=> "upload_img",
				"options" 	=> array(
						__( "Custom URL", "smile" ) 	 => "custom_url",
						__( "Upload Image", "smile" ) 	 => "upload_img",
						__( "None", "smile" ) 	 		 => "none",
					)
				),
			"panel" => "Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "info_bar_img_custom_url",
			"opts"		=> array(
				"title" 		=> __( "Custom URL", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "Enter custom URL for your image.", "smile" ),
			),
			"panel" 	=> "Image",
			"dependency" => array('name' => 'info_bar_img_src', 'operator' => '==', 'value' => 'custom_url'),
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "media",
			"class" 	=> "",
			"name" 		=> "info_bar_image",
			"opts"		=> array(
				"title" 		=> __( "Upload Image", "smile" ),
				"value" 		=> plugins_url('config/img/logo.png', __FILE__ ),
				"description" 	=> __( "Upload an image that will be displayed inside the content area.Image size will not bigger than its container.", "smile" ),
			),
			"panel" 	 => "Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'info_bar_img_src', 'operator' => '==', 'value' => 'upload_img')
		),
		array(
			"type" 		=> "slider",
			"class" 	=> "",
			"name" 		=> "image_size",
			"opts"			=> array(
				"title" 		=> __( "Resize Image", "smile" ),
				"value" 		=> 150,
				"min" 			=> 1,
				"max" 			=> 1000,
				"step" 			=> 1,
				"suffix" 		=> "px",
				"css_property" => "max-width",
				"css_selector" => ".cp-info-bar .cp-image-container img",
				"description" 	=> __( "The maximum size of an image is limited to the size of its container.", "smile" ),
			),
			"panel" 	 => "Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'info_bar_img_src', 'operator' => '!=', 'value' => 'none')
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "image_position",
			"opts"		=> array(
				"title" 	=> __( "Image Position", "smile" ),
				"value" 	=> true,
				"on" 		=> "RIGHT",
				"off"		=> "LEFT",
			),
			"panel" 	 => "Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "slider",
			"class" 	=> "",
			"name" 		=> "image_horizontal_position",
			"opts"			=> array(
				"title" 		=> __( "Horizontal Position", "smile" ),
				"value" 		=> 0,
				"min" 			=> -250,
				"max" 			=> 250,
				"step" 			=> 1,
			),
			"panel" 	 => "Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "slider",
			"class" 	=> "",
			"name" 		=> "image_vertical_position",
			"opts"			=> array(
				"title" 		=> __( "Vertical Position", "smile" ),
				"value" 		=> 0,
				"min" 			=> -250,
				"max" 			=> 250,
				"step" 			=> 1,
			),
			"panel" 	 => "Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),

		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "image_displayon_mobile",
			"opts"		=> array(
				"title" 	=> __( "Hide Image on Small Screens", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "On smaller screens like mobile, smaller Info Bars look more beautiful. To reduce the size of the Info Bar, you may hide the image with this setting.", "smile" ),
			),
			"panel" 	 => "Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
	);


//	ANOTHER SUBMIT BUTTON
$submit_btn = array(
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "ifb_button_options_title",
			"opts"		=> array(
				"title" 		=> __( "CTA Button Options", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Call to Action",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "ifb_btn_style",
			"opts" 		=> array(
				"title" 	=> __( "Button Style","smile"),
				"value" 	=> "cp-btn-flat",
				"description"=> __( "Style your button with nice effects.", "smile" ),
				"options" 	=> array(
					__( "Flat", "smile" )		=> 'cp-btn-flat',
					__( "3D", "smile" )			=> 'cp-btn-3d',
					__( "Outline", "smile" )	=> 'cp-btn-outline',
					__( "Gradient", "smile" )	=> 'cp-btn-gradient',
				),
			),
			"panel" 	=> "Call to Action",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "ifb_button_bg_color",
			"opts"		=> array(
				"title" 		=> __( "Submit Button Background Color", "smile" ),
				"value" 		=> "rgb(255, 153, 0)",
			),
			"panel" 	=> "Call to Action",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		//	This option is to store the default initial color of button text
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "ifb_button_txt_hover_color",
			"opts"		=> array(
				"title" 		=> __( "Submit Button Text Hover Color", "smile" ),
				"value" 		=> "#ffffff",
			),
		    "dependency" => array('name' => 'ifb_btn_style', 'operator' => '==', 'value' => 'cp-btn-outline'),
			"panel" 	=> "Call to Action",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		//	store button darken on hover
		array(
		    "type"         => "textfield",
		    "name"         => "ifb_button_bg_hover_color",
		    "opts"         => array(
		        "title"     => __( "Button BG Hover Color", "smile" ),
		        "value"     => "",
		    ),
		    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"panel" 	=> "Call to Action",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		//	store button lighten gradient
		array(
		    "type"         => "textfield",
		    "name"         => "ifb_button_bg_gradient_color",
		    "opts"         => array(
		        "title"     => __( "Button Gradient Color", "smile" ),
		        "value"     => "",
		    ),
		    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"panel" 	=> "Call to Action",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "textarea",
			"class" 	=> "",
			"name" 		=> "ifb_button_title",
			"opts"		=> array(
				"title" 		=> __( "Submit Button Title", "smile" ),
				"value" 		=> "Subscribe",
				"description" 	=> __( "Enter the main heading title.", "smile" ),
			),
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" 	 => "Design",
			"panel" => "Name",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "ifb_btn_border_radius",
			"opts"			=> array(
				"title" 		=> __( "Border Radius", "smile" ),
				"value" 		=> 3,
				"min" 			=> 0,
				"max" 			=> 20,
				"step" 			=> 1,
			),
			"panel" 	=> "Call to Action",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "ifb_btn_shadow",
			"opts"		=> array(
				"title" 	=> __( "Button Shadow", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
			),
			"panel" 	=> "Call to Action",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'ifb_btn_style', 'operator' => '!=', 'value' => 'cp-btn-3d'),
		),
		//  Hidden variable to store the (darken button color)
		array(
		    "type"         => "textfield",
		    "class"     => "",
		    "name"         => "ifb_btn_darken",
		    "opts"        => array(
		        "title"         => __( "Button Darken", "smile" ),
		        "value"         => __( "", "smile" ),
		        "description"     => __( "Enter the short description of this optin.(HTML is Allowed)", "smile" ),
		    ),
		    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
		    "section"      => "Design",
		    "panel" => "Background",
		    "section_icon" => "connects-icon-image",
		),
		//    Hidden variable to store the (gradient button color)
		array(
		    "type"         => "textfield",
		    "class"     => "",
		    "name"         => "ifb_btn_gradiant",
		    "opts"        => array(
		        "title"         => __( "Button Darken", "smile" ),
		        "value"         => __( "", "smile" ),
		        "description"     => __( "Enter the short description of this optin.(HTML is Allowed)", "smile" ),
		    ),
		    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
		    "section"      => "Design",
		    "panel" => "Background",
		    "section_icon" => "connects-icon-image",
		),

    );

	// Style - Blank info bar
	smile_update_options( "Smile_Info_Bars", "blank", array_merge(
			$name,
			$ib_content,
			$background,
			$close_link,
			$animation,
			$advance_options,
			$behavior
		)
	);

	// Style - newsletter
	smile_update_options( "Smile_Info_Bars", "newsletter", array_merge(
			$name,
			$background,
			$cp_form,
			$close_link,
			$animation,
			$behavior,
			$submission,
			$advance_options
		)
	);

	// Add options for simple image_preview
	smile_update_options( "Smile_Info_Bars", "image_preview", array_merge(
			$name,
			$background,
			$ifb_img,
			$cp_form,
			$close_link,
			$animation,
			$behavior,
			$submission,
			$advance_options
		)
	);

	// Style - get_this_deal
	smile_update_options( "Smile_Info_Bars", "get_this_deal", array_merge(
			$name,
			$background,
			$cp_form,
			$close_link,
			$animation,
			$behavior,
			$submission,
			$advance_options
		)
	);

	// Style - free_trial
	smile_update_options( "Smile_Info_Bars", "free_trial", array_merge(
			$name,
			$background,
			$ifb_img,
			$cp_form,
			$close_link,
			$animation,
			$behavior,
			$submission,
			$advance_options
		)
	);

	// Style - cp-weekly-article
	smile_update_options( "Smile_Info_Bars", "weekly_article", array_merge(
			$name,
			$background,
			$ifb_img,
			$cp_form,
			$close_link,
			$animation,
			$behavior,
			$submission,
			$advance_options
		)
	);

	// Style - Social_media info bar
	smile_update_options( "Smile_Info_Bars", "social_info_bar", array_merge(
			$name,
			$ib_content,
			$background,
			$cp_social,
			$close_link,
			$animation,
			$advance_options,
			$behavior
		)
	);
}

/**
 * Update Options
 */
if( function_exists( "smile_update_default" ) ){

	//	Blank
	$blank_default = array(
		'bg_color' 				=> '#dd433e',
		"infobar_height"		=> 30,
		"enable_shadow"         => false,
		"border_darken"			=> '#d03631',
		"enable_border"         => true,
		'border'				=> 'br_type:0|br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:#d03631|bw_type:1|bw_all:5|bw_t:0|bw_l:0|bw_r:0|bw_b:2',
		"infobar_title"			=> __( "BLANK style is purely built for customization. This style supports text, images, shortcodes, HTML etc. Use Source button from Rich Text Editor toolbar & customize your Info Bar effectively.", "smile" ),
	);

	foreach( $blank_default as $option => $value ){
		smile_update_default( "Smile_Info_Bars", "blank", $option, $value );
	}

	// NewsLetter
	$newsletter_optin_default = array(
		"form_fields" 			=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			=> "cp-form-layout-3",
		"form_input_align"		=> "left",
		"form_submit_align" 	=> "cp-submit-wrap-full",
		"form_grid_structure"	=> "cp-form-grid-structure-1",
		"form_input_font_size"	=> 13,
		"form_input_padding_tb"	=> 7,
		"form_input_padding_lr"	=> 10,
		"submit_button_tb_padding"	=> 8,
		"infobar_title" 	    => "Sign-up for exclusive content. Be the first to hear about ConvertPlug news.",
		"bg_color"			    => "#f26e27",
		"button_title"			=> "Subscribe",
		"button_bg_color"		=> "#444444",
		"button_border_color"	=> "#444444",
		"placeholder_font"		=> "Lato",
		'border'				=> 'br_type:0|br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:#e5611a|bw_type:1|bw_all:5|bw_t:0|bw_l:0|bw_r:0|bw_b:2',
		"name_text"				=> __( "Enter Your Name", "smile" ),
		"placeholder_text"		=> __( "Your Email", "smile" ),
	);
	foreach( $newsletter_optin_default as $option => $value ){
		smile_update_default( "Smile_Info_Bars", "newsletter", $option, $value );
	}

	// 	get_this_deal
	$get_this_deal_default = array(
		"form_fields" 				=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"				=> "cp-form-layout-4",
		"form_input_align"			=> "left",
		"form_submit_align" 		=> "cp-submit-wrap-full",
		"form_grid_structure"		=> "cp-form-grid-structure-1",
		"form_input_font_size"		=> 13,
		"form_input_padding_tb"		=> 7,
		"form_input_padding_lr"		=> 12,
		"submit_button_tb_padding"	=> 8,
		"bg_color"	    			=> "#db6d2c",
		"infobar_height"			=> 50,
		"btn_border_radius"			=> 25,
		"enable_border"			=> 1,
		"infobar_title"  	    		=> '<span style="font-weight: bold;font-size:20px;">'.__( "$25 Off ", "smile" ).'</span>'.__( " when you complete your order today.", "smile" ),
		"button_title"				=> "Apply Coupon",
		"button_bg_color"			=> "#333332",
		"button_border_color"		=> "#333332",
		"btn_border_radius" 		=> 8,
		"border"					=> 'br_type:0|br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:#ffffff|bw_type:1|bw_all:5|bw_t:0|bw_l:0|bw_r:0|bw_b:8',
		"bg_gradient"				=> false,
		"toggle_button_title"		=> "GET DEAL",
		"toggle_button_font" 		=> "Bitter",
		"toggle_button_bg_color"		=> "#db6d2c",
		"border_darken"			=> "#ffffff",
	);
	foreach( $get_this_deal_default as $option => $value ){
		smile_update_default( "Smile_Info_Bars", "get_this_deal", $option, $value );
	}

	// 	Image Preview
	$image_preview_default = array(
		"form_fields" 				=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"				=> "cp-form-layout-4",
		"form_input_align"			=> "left",
		"form_submit_align" 		=> "cp-submit-wrap-full",
		"form_grid_structure"		=> "cp-form-grid-structure-1",
		"form_input_font_size"		=> 13,
		"form_input_padding_tb"		=> 8,
		"form_input_padding_lr"		=> 15,
		"bg_color"	    		 	=> "#ffffff",
		"infobar_height"		 	=> 80,
		'border'					=> 'br_type:0|br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:#f2f2f2|bw_type:1|bw_all:5|bw_t:0|bw_l:0|bw_r:0|bw_b:2',
		"infobar_title"		  	=> __( 'Merry Christmas! Enjoy all time low prices and discount this festival season.', "smile" ),
		"image_size"			 	=> 165,
		"button_title"			 	=> "Shop Now",
		"button_bg_color"		 	=> "#db6d2c",
		"button_border_color"	 	=> "#db6d2c",
		"bg_gradient"			 	=> false,
		"image_displayon_mobile" 	=> true,
	);
	foreach( $image_preview_default as $option => $value ){
		smile_update_default( "Smile_Info_Bars", "image_preview", $option, $value );
	}

	// 	Free Trial
	$free_trial_default = array(
		"form_fields" 				=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"				=> "cp-form-layout-3",
		"form_input_align"			=> "left",
		"form_submit_align" 		=> "cp-submit-wrap-center",
		"form_grid_structure"		=> "cp-form-grid-structure-1",
		"form_input_font_size"		=> 13,
		"form_input_padding_tb"		=> 7,
		"form_input_padding_lr"		=> 12,
		"submit_button_tb_padding"	=> 8,
		"infobar_height"		 	=> 100,
		"infobar_title" 	     	=> __( "GROW YOUR BUSINESS!", "smile" ),
		"infobar_description"	 	=> __( "ConvertPlug&trade; is all-in-one software to generate more leads & drive more sales with onsite targeting. ", "smile" ),
		"bg_color"			     => "#2c8dd7",
		"bg_gradient"			 	=> true,
		"button_title"			 	=> "Book Free Trial",
		"button_bg_color"		 	=> "#1a2730",
		"button_border_color"	 	=> "#1a2730",
		"btn_border_radius" 	 	=> 4,
		"ifb_btn_shadow"		 	=> true,
		"placeholder_font"		 	=> "Lato",
		"name_text"				=> __( "Your Name", "smile" ),
		"placeholder_text"		 	=> __( "Your Email", "smile" ),
		"image_size"			 	=> 120,
		"image_displayon_mobile" 	=> true,
		"info_bar_image" 		 	=> CP_BASE_URL . 'modules/info_bar/assets/img/CP_Product_Box_Mockup.png',
		"infobar_position" 		 	=> "cp-pos-bottom",
		"close_info_bar"		 	=> "do_not_close",
		"entry_animation"		 	=> "smile-slideInUp",
		"exit_animation"		 	=> "smile-slideOutDown",
		"border_darken"				=> "#d1d1d1",
	);

	foreach( $free_trial_default as $option => $value ){
		smile_update_default( "Smile_Info_Bars", "free_trial", $option, $value );
	}

	// 	weekly_article
	$weekly_article_optin_default = array(
		"form_fields" 				=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"				=> "cp-form-layout-3",
		"form_input_align"			=> "left",
		"form_submit_align" 		=> "cp-submit-wrap-full",
		"form_grid_structure"		=> "cp-form-grid-structure-1",
		"form_input_font_size"		=> 15,
		"form_input_padding_tb"		=> 9,
		"form_input_padding_lr"		=> 10,
		"submit_button_tb_padding"	=> 10,
		"submit_button_lr_padding"	=> 20,
		"infobar_title" 			=> __( 'Get the week&rsquo;s best articles right in your inbox', 'smile' ),
		"bg_color"					=> "#324d5b",
		"button_title"				=> "Subscribe",
		"button_bg_color"			=> "#f4b22e",
		"button_border_color"		=> "#f4b22e",
		"placeholder_font"			=> "Lato",
		"name_text"					=> "Your Name",
		"placeholder_text"			=> "Your Email",
		"infobar_description"		=> "Join 15K subscribers",
		"infobar_width"				=> "1600",
		"bg_gradient"				=> false,
		"namefield"					=> true,
		"info_bar_image" 			=> CP_BASE_URL . 'modules/info_bar/assets/img/hellobar.png',
		"image_size" 				=> "50",
	);
	foreach( $weekly_article_optin_default as $option => $value ){
		smile_update_default( "Smile_Info_Bars", "weekly_article", $option, $value );
	}

	//	social media
	$social_info_bar_default = array(
		'bg_color' 				=> '#ffffff',
		"infobar_height"		=> 70,
		"enable_shadow"         => false,
		"border_darken"			=> '#ededed',
		"infobar_width"			=> '1600',
		"enable_border"         => true,
		'border'				=> 'br_type:0|br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:#d03631|bw_type:1|bw_all:5|bw_t:0|bw_l:0|bw_r:0|bw_b:2',
		"infobar_title"			=> __( "Share this Awesome Stuff with your Friends!", "smile" ),
		"cp_display_nw_name" 	=> false,
		"cp_social_icon_effect" => "flat",
		"cp_social_icon_align"  => "left",
		"cp_social_icon" 		=> "order:0|input_type:Facebook|network_name:|input_action:social_sharing|profile_link:|smile_adv_share_opt:0|input_share:|input_share_count:;order:1|input_type:StumbleUpon|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:2|input_type:Google|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:3|input_type:Blogger|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:4|input_type:LinkedIn|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:",
	);

	foreach( $social_info_bar_default as $option => $value ){
		smile_update_default( "Smile_Info_Bars", "social_info_bar", $option, $value );
	}
}

/**
 * Remove option from style
 */
if( function_exists( "smile_remove_option" ) ){

	//	Blank
	smile_remove_option( "Smile_Info_Bars", "blank", array( 'button_animation','placeholder_font') );

	//	get_this_deal
	smile_remove_option( "Smile_Info_Bars", "get_this_deal", array( 'new_line_optin','namefield','name_text','placeholder_text','placeholder_color','input_bg_color','input_border_color','image_position','new_line_optin','inactivity_link_for_form','image_vertical_position','image_horizontal_position','placeholder_font') );

	//	Image Preview
	smile_remove_option( "Smile_Info_Bars", "image_preview", array( 'new_line_optin','namefield','name_text','placeholder_text','placeholder_color','input_bg_color','input_border_color','image_position','new_line_optin','inactivity_link_for_form','image_vertical_position','image_horizontal_position','placeholder_font') );

	//	Big Image Bar
	smile_remove_option( "Smile_Info_Bars", "free_trial", array( 'new_line_optin','image_position','image_vertical_position','image_horizontal_position') );

	//	weekly_article
	smile_remove_option( "Smile_Info_Bars", "weekly_article", array( 'image_position','new_line_optin','image_vertical_position','image_horizontal_position') );

		//	social media
	smile_remove_option( "Smile_Info_Bars", "social_info_bar", array( 'button_animation','placeholder_font') );

}
