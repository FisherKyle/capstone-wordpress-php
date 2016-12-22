<?php
if( function_exists( "smile_update_settings" ) ){

	/* Get ConvertPlug Form Option Array */
	global $cp_form;

	/* Get ConvertPlug Form social media Array */
	global $cp_social;

	//get style id
	$style_id_for_slideincustomcss = '';
	if( isset( $_GET['variant-style'])){
		$style_id_for_slideincustomcss = $_GET['variant-style'];
		$style = $_GET['variant-style'];
	} else {
		if( isset( $_GET['style'] ) ){
	    	$style_id_for_slideincustomcss = $_GET['style'];
		}
	}

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

	/*** Array contains name options ***/
	$name = array (
		array(
		    "type"         => "google_fonts",
		    "name"         => "cp_google_fonts",
		    "opts"         => array(
		        "title"     => __( "Google Fonts", "smile" ),
		        "value"     => "",
		    ),
		    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"panel" => "Name",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "slidein_title1",
			"opts"		=> array(
				"title" 		=> __( "Main Title", "smile" ),
				"value" 		=> __( "Stay Connected!", "smile" ),
				"description" 	=> __( "Enter the main heading title.", "smile" ),
			),
			"panel" 	 => "Name",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 		=> "textarea",
			"class" 	=> "",
			"name" 		=> "slidein_short_desc1",
			"opts"		=> array(
				"title" 		=> __( "Short Description", "smile" ),
				"value" 		=> __( "Download this free eBook to learn how to get 15,000 new, unique visitors per month with our proven techniques.",  "smile" ),
				"description" 	=> __( "Enter the short description that displays under the main title.", "smile" ),
			),
			"panel" 	 => "Name",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 		=> "textarea",
			"class" 	=> "",
			"name" 		=> "slidein_confidential",
			"opts"		=> array(
				"title" 		=> __( "Notice / Tip Under Form", "smile" ),
				"value" 		=> __( "Written by John Doe, who is well versed for his writings in Brainstorm Publication.", "smile" ),
				"description" 	=> __( "Enter the notice / tip that displays under the subscription form.", "smile" ),
			),
			"panel" 	 => "Name",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
		array(
			"type" 		=> "textarea",
			"class" 	=> "",
			"name" 		=> "slidein_content",
			"opts"		=> array(
				"title" 		=> __( "Slide In Content", "smile" ),
				"value" 		=> __( "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis viverra, urna vitae vehicula congue, purus nibh vestibulum lacus, sit amet tristique ante odio viverra orci. Nullam consectetur mollis lacinia.", "smile" ),
				"description" 	=> __( "Enter the short description of this optin.(HTML is Allowed)", "smile" ),
			),
			"panel" 	=> "Name",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		)
	);

	$secondary_title = array (
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "slidein_sub_title",
			"opts"		=> array(
				"title" 		=> __( "Secondary Title", "smile" ),
				"value" 		=> __( "Get on our mailing list", "smile" ),
				"description" 	=> __( "Enter the secondary heading title.", "smile" ),
			),
			"panel" 	 => "Name",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "slidein_sub_title_color",
			"opts"		=> array(
				"title" 		=> __( "Slide In Secondary Title Color", "smile" ),
				"value" 		=> "#FCA524",
				"description" 	=> __( "Select the secondary title text color.", "smile" ),
			),
			"panel" 	=> "Background",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
	);

	/*** Array contains background options ***/
	$background = array (
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "slidein_title_color",
			"opts"		=> array(
				"title" 		=> __( "Slide In Title Color", "smile" ),
				"value" 		=> "#000",
				"description" 	=> __( "Select the title text color.", "smile" ),
			),
			"panel" 	=> "Background",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "slidein_desc_color",
			"opts"		=> array(
				"title" 		=> __( "Description Color", "smile" ),
				"value" 		=> "#555555",
				"description" 	=> __( "Select the description text color.", "smile" ),
			),
			"panel" 	=> "Background",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "tip_color",
			"opts"		=> array(
				"title" 		=> __( "Notice / Tip Color", "smile" ),
				"value" 		=> "#838383",
				"description" 	=> __( "Select the text color for Notice / Tip under the form.", "smile" ),
			),
			"panel" 	=> "Background",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "slidein_bg_color",
			"opts"		=> array(
				"title" 		=> __( "Background Color", "smile" ),
				"value" 		=> "rgb(255, 255, 255)",
				"description" 	=> __( "Choose the background color for Slide In box area.", "smile" )
			),
			"panel" 	=> "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "slidein_bg_gradient",
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
			"name" 		=> "slidein_bg_gradient_lighten",
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
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "slide_in_bg_image_src",
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
			"name" 		=> "slide_in_bg_image_custom_url",
			"opts"		=> array(
				"title" 		=> __( "Custom URL", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "Enter custom URL for your image.", "smile" ),
			),
			"panel" 	=> "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'slide_in_bg_image_src', 'operator' => '==', 'value' => 'custom_url')
		),
		array(
			"type" 		=> "media",
			"class" 	=> "",
			"name" 		=> "slide_in_bg_image",
			"opts"		=> array(
				"title" 		=> __( "Background Image", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "You can provide an image that would be appear behind the content in the Slide In box area. For this setting to work, the background color you've chosen must be transparent.", "smile" ),
			),
			"panel" 	 => "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'slide_in_bg_image_src', 'operator' => '==', 'value' => 'upload_img')
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
			// "dependency" => array('name' => 'slide_in_bg_image_src', 'operator' => '!==', 'value' => 'none')
		),

		//	store button darken on hover
		array(
		    "type"         => "textfield",
		    "name"         => "side_button_bg_hover_color",
		    "opts"         => array(
		        "title"     => __( "Button BG Hover Color", "smile" ),
		        "value"     => "",
		    ),
		    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"panel" => "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		//	store button lighten gradient
		array(
		    "type"         => "textfield",
		    "name"         => "side_button_bg_gradient_color",
		    "opts"         => array(
		        "title"     => __( "Button Gradient Color", "smile" ),
		        "value"     => "",
		    ),
		    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"panel" => "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
	);

	/*** Array contains close link options ***/
	$close_link = array(
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "close_slidein",
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
			"name" 		=> "close_si_image_src",
			"opts" 		=> array(
				"title" 	=> __( "Image source","smile"),
				"value" 	=> "upload_img",
				"options" 	=> array(
						__( "Custom URL", "smile" ) 	 => "custom_url",
						__( "Upload Image", "smile" ) 	 => "upload_img",
						__( "Predefined Icons", "smile" ) => "pre_icons",
					)
				),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'close_slidein', 'operator' => '==', 'value' => 'close_img'),
		),
		array(
            "type"  =>  "radio-image",
            "name"  =>  "close_icon",
            "opts"  =>  array(
                "title" => __( "", "smile" ),
                "value" => 'default',
                "width" => '80px',
                "options" => array(
                    "black"         => CP_BASE_URL . 'modules/assets/images/black.png',
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
            "dependency" => array('name' => 'close_si_image_src', 'operator' => '==', 'value' => 'pre_icons')
        ),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "slide_in_close_img_custom_url",
			"opts"		=> array(
				"title" 		=> __( "Custom URL", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Close Link",
			"dependency" => array('name' => 'close_si_image_src', 'operator' => '==', 'value' => 'custom_url'),
			"section" => "Design",
			"section_icon" => "connects-icon-image"
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
			"dependency" => array('name' => 'close_slidein', 'operator' => '==', 'value' => 'close_txt'),
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
			"dependency" => array('name' => 'close_slidein', 'operator' => '==', 'value' => 'close_txt'),
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
				"value" 		=> CP_BASE_URL . 'modules/slide_in/assets/img/cross.png',
			),
			"panel" 	=> "Close Link",
			"dependency" => array('name' => 'close_si_image_src', 'operator' => '==', 'value' => 'upload_img'),
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "cp_close_image_width",
			"opts"			=> array(
				"title" 		=> __( "Close Image Width", "smile" ),
				"value" 		=> 22,
				"min" 			=> 15,
				"max" 			=> 128,
				"step" 			=> 1,
				"suffix" 		=> "px",
			),
			"panel" 		=> "Close Link",
			"dependency" => array('name' => 'close_slidein', 'operator' => '==', 'value' => 'close_img'),
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
			"dependency" => array('name' => 'close_slidein', 'operator' => '!=', 'value' => 'do_not_close'),
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "close_slidein_tooltip",
			"opts" 		=> array(
				"title" 	=> __( "Display Tooltip On Hover","smile"),
				"value" 	=> false,
				"on" 		=> __( "Yes", "smile" ),
				"off"		=> __( "No", "smile" ),
			),
			"dependency" => array('name' => 'close_slidein', 'operator' => '!=', 'value' => 'do_not_close'),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "tooltip_title",
			"opts"		=> array(
				"title" 		=> __( "Tooltip Text", "smile" ),
				"value" 		=> __( "Note: Slide Ins are displayed only once!", "smile" ),
			),
			"dependency" => array('name' => 'close_slidein_tooltip', 'operator' => '==', 'value' => 'true'),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "tooltip_title_color",
			"opts"		=> array(
				"title" 		=> __( "Tooltip Text  Color", "smile" ),
				"value" 		=> "rgb(255, 255, 255)",
			),
			"dependency" => array('name' => 'close_slidein_tooltip', 'operator' => '==', 'value' => 'true'),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "tooltip_background",
			"opts"		=> array(
				"title" 		=> __( "Tooltip Background Color", "smile" ),
				"value" 		=> "rgb(209, 37, 37)",
			),
			"dependency" => array('name' => 'close_slidein_tooltip', 'operator' => '==', 'value' => 'true'),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
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
			"dependency" => array('name' => 'close_slidein', 'operator' => '!=', 'value' => 'do_not_close'),
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
				"description" 	=> __( "How long the close image / text to be displayed after Slide In is loaded? (value in seconds).", "smile" ),
			),
			"dependency" => array('name' => 'display_close_on_duration', 'operator' => '==', 'value' => 'true'),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),

		/* Its working? */
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "button_animation",
			"opts"		=> array(
				"title" 		=> __( "Button Animation", "smile" ),
				"description" 	=> __( "Select the exit level animation for Slide In submit button .", "smile" ),
				"value"			=> "smile-slideInUp",
				"options" 		=> $animation_array
			),
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),

		// 	Slide In - Toggle Button
 		//	Slide Button Options
 		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "slidein_toggle_section",
			"opts"		=> array(
				"title" 		=> __( "Toggle Button", "smile" ),
				"value" 		=> "",
			),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'close_slidein', 'operator' => '!=', 'value' => 'do_not_close'),
		),
 		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "toggle_btn",
			"opts"		=> array(
				"title" 	=> __( "Enable Toggle Button", "smile" ),
				"description" 	=> __( "Enable toggle button that will show or hide Slide In on click event.", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
			),
			"dependency" => array('name' => 'close_slidein', 'operator' => '!=', 'value' => 'do_not_close'),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
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
			"name" 		=> "slide_button_title",
			"opts"		=> array(
				"title" 		=> __( "Button Text", "smile" ),
				"value" 		=> "Click Me",
				"description" 	=> __( "Enter the button title.", "smile" ),
			),
			"panel" => "Close Link",
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
		        "use_in"    => "panel",
		        "css_property" => "font-family",
				"css_selector" => ".slidein-overlay .cp-slide-edit-btn",
		    ),
			"panel" 	=> "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency"	=> array("name" => "toggle_btn", "operator" => "==", "value" => true),
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "slide_button_text_color",
			"opts"		=> array(
				"title" 		=> __( "Text Color", "smile" ),
				"value" 		=> "rgb(255, 255, 255)",
				"css_property" => "color",
				"css_selector" => ".slidein-overlay .cp-slide-edit-btn",
			),
			"dependency"	=> array("name" => "toggle_btn", "operator" => "==", "value" => true),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "slide_button_bg_color",
			"opts"		=> array(
				"title" 		=> __( "Background Color", "smile" ),
				"value" 		=> "rgb(0, 0, 0)",
				"css_property" => "background-color",
				"css_selector" => ".slidein-overlay .cp-slide-edit-btn",
			),
		    "dependency"	=> array("name" => "toggle_btn", "operator" => "==", "value" => true),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "slide_btn_gradient",
			"opts"		=> array(
				"title" 	=> __( "Enable Gradient Background", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
			),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency"	=> array("name" => "toggle_btn", "operator" => "==", "value" => true),
		),

		//	Slide Auto close Options
 		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "slidein_auto_close_section",
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
				"description" 	=> __( "If enabled, Slide In will close automatically after 'x' seconds when user is inactive on webpage.", "smile" ),
			),
			"dependency"	=> array("name" => "toggle_btn", "operator" => "!=", "value" => true ),
			"panel" => "Close Link",
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
				"description" 	=> __( "How long the Slide In should take to be close after its loaded? (value in seconds).", "smile" ),
			),
			"dependency" => array('name' => 'autoclose_on_duration', 'operator' => '==', 'value' => '1'),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),

	);

/*** Array contains animation options ***/
	$widget_close = array (
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "minimize_widget",
			"opts"		=> array(
				"title" 	=> __( "Initial Appearance", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "Minimize", "smile" ),
				"off"		=> __( "Open", "smile" ),
				"description" 	=> __( " Select how your module appears after page loads. You can minimize or open it for initial view.", "smile" ),
			),
			"panel" => "Appearance",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		)		
	);


	/*** Array contains animation options ***/
	$animations = array (
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "overlay_effect",
			"opts" 		=> array(
				"title" 	=> __( "Entry Animation Effect","smile" ),
				"value" 	=> "smile-fadeInUp",
				"description"=> __( "Animation effect while the Slide In appears.", "smile" ),
				"options" 	=> array(
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
						"Zoom In Up"            => 'smile-zoomInUp',
					)
				),
			"panel" => "Slide In Animation",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type"		=> "dropdown",
			"class"		=> "",
			"name"		=> "exit_animation",
			"opts"		=> array(
				"title"		=> __( "Exit Animation Effect", "smile" ),
				"value"		=> "smile-fadeOutDown",
				"description"	=> __( "Animation effect while the Slide In disappears.", "smile" ),
				"options"		=> array(
					"No Effect"				=> 'slidein-overlay-none',
					"Bounce Out"          	=> 'smile-bounceOut',
					"Bounce Out Down"     	=> 'smile-bounceOutDown',
					"Bounce Out Left"     	=> 'smile-bounceOutLeft',
					"Bounce Out Right"    	=> 'smile-bounceOutRight',
					"Bounce Out Up"       	=> 'smile-bounceOutUp',
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
				)
			),
			"panel" => "Slide In Animation",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
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
			"panel" => "Slide In Animation",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
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
				"description" 	=> __( "When width of the browser is below provided value, the Slide In animation will disable.", "smile" ),
			),
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			//"dependency"	=> array("name" => "disable_overlay_effect", "operator" => "==", "value" => "1"),
			"panel" => "Slide In Animation",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
	);

	/*** Array contains advance design options ***/
	$adv_design_options = array (
		array(
			"type" 		=> "dropdown",
			"class" 		=> "",
			"name" 		=> "slidein_position",
			"opts" 		=> array(
				"title" 	=> __( "Position","smile"),
				"value" 	=> "bottom-right",
				"options" 	=> array(
					__( "Top Left", "smile" )		  => "top-left",
					__( "Top Center", "smile" )	      => "top-center",
					__( "Top Right", "smile" )	      => "top-right",
					__( "Bottom Left", "smile" )	  => "bottom-left",
					__( "Bottom Center", "smile" )	  => "bottom-center",
					__( "Bottom Right", "smile" )	  => "bottom-right",
					__( "Center Left", "smile" )	  => "center-left",
					__( "Center Right", "smile" )	  => "center-right"
				)
			),
			"panel" => "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "cp_slidein_width",
			"opts"			=> array(
				"title" 		=> __( "Slide In Width", "smile" ),
				"css_property"  => "max-width",
				"css_selector"  => ".cp-slidein",
				"value" 		=> 520,
				"min" 			=> 50,
				"max" 			=> 3000,
				"step" 			=> 1,
			),
			"panel" 		=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "content_padding",
			"opts"		=> array(
				"title" 	=> __( "Remove Default Padding", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description"=> __( "Remove the default padding between content area and Slide In box edges.", "smile" ),
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "border_sub_title",
			"opts"		=> array(
				"title" 		=> __( "Border", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "border",
			"class" 	=> "",
			"name" 		=> "border",
			"opts"		=> array(
				"title" 	=> "",
				"css_selector" => ".cp-slidein-content",
				"css_property" => "border",
				"value" 	=> 'br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:rgb(255,255, 255)|bw_all:5|bw_t:5|bw_l:5|bw_r:5|bw_b:5',
				"description" 	=> __( "Using very customizable settings below, you can apply a border around the Slide In box.", "smile" ),
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "box_shadow_sub_title",
			"opts"		=> array(
				"title" 		=> __( "Box Shadow", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "box_shadow",
			"class" 	=> "",
			"name" 		=> "box_shadow",
			"opts"		=> array(
				"title" 	=> "",
				"css_selector" => ".cp-slidein-body-overlay",
				"css_property" => "box-shadow",
				"value" 	=> 'type:outset|horizontal:0|vertical:0|blur:5|spread:0|color:rgba(86,86,131,0.6)',
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "custom_code_sub_title",
			"opts"		=> array(
				"title" 		=> __( "Custom Code", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "textarea",
			"class" 	=> "",
			"name" 		=> "custom_css",
			"opts"		=> array(
				"title" 	=> __( "Custom CSS", "smile" ),
				"value" 	=> '',
				"description" 	=> __( "Enter your custom css code for this Slide In here.", "smile" ),
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "custom_css_link",
			"opts"		=> array(
				"link" 		=> __( "Add custom CSS to your style. Write custom css statement with prefixed the following unique class :<br><br/><span style='color:#444;font-size:18px;font-family: monospace;' ><b>.".$style_id_for_slideincustomcss."</span> </b> ".__("", "smile" )."", "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
	);


	/*** Array contains advance design options ***/
	$adv_design_options_widget = array (


		array(
			"type" 		=> "dropdown",
			"class" 		=> "",
			"name" 		=> "slidein_position",
			"opts" 		=> array(
				"title" 	=> __( "Position","smile"),
				"value" 	=> "bottom-right",
				"options" 	=> array(
						__( "Bottom Right", "smile" )	=> "bottom-right",
						__( "Bottom Left", "smile" )	=> "bottom-left"
					)
				),
			"panel" => "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "cp_slidein_width",
			"opts"			=> array(
				"title" 		=> __( "Slide In Width", "smile" ),
				"css_property"  => "max-width",
				"css_selector"  => ".cp-slidein",
				"value" 		=> 300,
				"min" 			=> 100,
				"max" 			=> 500,
				"step" 			=> 1,
			),
			"panel" 		=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "content_padding",
			"opts"		=> array(
				"title" 	=> __( "Remove Default Padding", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description"=> __( "Remove the default padding between content area and Slide In box edges.", "smile" ),
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "border_sub_title",
			"opts"		=> array(
				"title" 		=> __( "Border", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "border",
			"class" 	=> "",
			"name" 		=> "border",
			"opts"		=> array(
				"title" 	=> "",
				"css_selector" => ".cp-slidein-content",
				"css_property" => "border",
				"value" 	=> 'br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:rgb(255,255, 255)|bw_all:5|bw_t:5|bw_l:5|bw_r:5|bw_b:5',
				"description" 	=> __( "Using very customizable settings below, you can apply a border around the Slide In box.", "smile" ),
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "box_shadow_sub_title",
			"opts"		=> array(
				"title" 		=> __( "Box Shadow", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "box_shadow",
			"class" 	=> "",
			"name" 		=> "box_shadow",
			"opts"		=> array(
				"title" 	=> "",
				"css_selector" => ".cp-slidein-body-overlay",
				"css_property" => "box-shadow",
				"value" 	=> 'type:outset|horizontal:0|vertical:0|blur:5|spread:0|color:rgba(86,86,131,0.6)',
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "custom_code_sub_title",
			"opts"		=> array(
				"title" 		=> __( "Custom Code", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "textarea",
			"class" 	=> "",
			"name" 		=> "custom_css",
			"opts"		=> array(
				"title" 	=> __( "Custom CSS", "smile" ),
				"value" 	=> '',
				"description" 	=> __( "Enter your custom css code for this Slide In here.", "smile" ),
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		)
	);

	/*** Affiliate link array ***/

	$affiliate_link = array (
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "affiliate_sub_title",
			"opts"		=> array(
				"title" 		=> __( "Affiliate Link", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "affiliate_setting",
			"opts"		=> array(
				"title" 	=> __( "Become a ConvertPlug Affiliate", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "Yes", "smile" ),
				"off"		=> __( "No", "smile" ),
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "affiliate_username",
			"opts"		=> array(
				"title" 		=> __( "Envato Username", "smile" ),
				"value" 		=> "BrainstormForce",
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'affiliate_setting', 'operator' => '==', 'value' => '1'),
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "affiliate_title",
			"opts"		=> array(
				"title" 		=> __( "Affilate title", "smile" ),
				"value" 		=> "Powered by ConvertPlug<sup>&trade;</sup>",
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "affilaiate_info_link",
			"opts"		=> array(
				"link" 		=> __( 'Did you know that you can earn 30% for each sale you refer to ConvertPlug? Just enter your Envato username and get started!</br></br><a style="text-decoration:none;" href="http://themeforest.net/legal/affiliate" target="_blank">Curious how does it work?</a>', "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),

	);

	/*** Array contains optin form options ***/
	$optin_form = array (
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "form_options_title",
			"opts"		=> array(
				"title" 		=> __( "Form Options", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Optin Form",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "namefield",
			"opts"		=> array(
				"title" 	=> __( "Enable Name Field", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
			),
			"panel" 	 => "Optin Form",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "btn_disp_next_line",
			"opts"		=> array(
				"title" 	=> __( "Display Button On Next Line", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
			),
			"panel" 	 => "Optin Form",
			"section" => "Design",
			"dependency" => array('name' => 'namefield', 'operator' => '==', 'value' => '0'),
			"section_icon" => "connects-icon-disc"
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "name_text",
			"opts"		=> array(
				"title" 		=> __( "Placeholder Text for Name", "smile" ),
				"value" 		=> "Enter Your Name",
			),
			"dependency"	=> array("name" => "namefield", "operator" => "==", "value" => "true"),
			"panel" 	 => "Optin Form",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "placeholder_text",
			"opts"		=> array(
				"title" 		=> __( "Placeholder Text for Email", "smile" ),
				"value" 		=> "Enter your email",
			),
			"panel" 	 => "Optin Form",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "button_options_title",
			"opts"		=> array(
				"title" 		=> __( "Button Options", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Optin Form",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "button_title",
			"opts"		=> array(
				"title" 		=> __( "Button Title", "smile" ),
				"value" 		=> "DOWNLOAD",
				"description" 	=> __( "Enter the button title.", "smile" ),
			),
			"panel" 	 => "Optin Form",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
		),
		//	Note - Button Options
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "note_button_options",
			"opts"		=> array(
				"link" 		=> __( "Note - Above settings apply to only Built-In Forms. These won't be effective with Custom Forms.", "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"panel" 	=> "Optin Form",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
	);

	/*** Array contains bahavior options ***/
	$behavior = array(
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "slidein_exit_intent",
			"opts"		=> array(
				"title" 	=> __( "Before User Leaves / Exit Intent", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "If enabled, Slide In will load right before user is about to leave your website.", "smile" ),
			),
			"panel" 	=> "Smart Launch",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
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
				"description" 	=> __( "If enabled, Slide In will load automatically after few seconds.", "smile" ),
			),
			"panel" 	=> "Smart Launch",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
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
				"description" 	=> __( "How long the Slide In should take to be displayed after the page is loaded? (value in seconds).", "smile" ),
			),
			"panel" 		=> "Smart Launch",
			"dependency" => array('name' => 'autoload_on_duration', 'operator' => '==', 'value' => '1'),
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
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
				"description" 	=> __( "If enabled, Slide In will load as user scrolls down on the page.", "smile" ),
			),
			"panel" 	=> "Smart Launch",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
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
				"description" 	=> __( "How much should the user scroll the page to display the Slide In? (value in %).", "smile" ),
			),
			"panel" 		=> "Smart Launch",
			"dependency" => array('name' => 'autoload_on_scroll', 'operator' => '==', 'value' => '1'),
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
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
				"description" => __( "If enabled, a Slide In will be displayed to visitor if he is idle on page for certain time.", "smile" )
			),
			"panel" 	=> "Smart Launch",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "inactivity_link",
			"opts"		=> array(
				"link" 		=> __( "Slide In will trigger after `".$user_inactivity."  ".__("Seconds", "smile" )."` of user inactivity. If you would like, you can change the time <a target=\"_blank\" href=\"".admin_url('admin.php?page=convertplug&view=settings#user_inactivity')."\">here</a>", "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"panel" 	=> "Smart Launch",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
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
				"description" 	=> __( "Slide In will be triggered when user scrolls to the end of post.", "smile" ),
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
				"description" 	=> __( "Slide In will be triggered when user scrolls to certain css class or id.", "smile" ),
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
				"description" 	=> __( "Slide In can be triggered on click of any UI element. Just provide the unique CSS class of that element here and Slide In will be trigger when you click on that element.", "smile" ),
			),
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"panel" 	=> "Manual Display",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "custom_class",
			"opts"		=> array(
				"title" 		=> __( "Launch With CSS Class", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "<br>Slide In can be triggered on click of any UI element. Just provide the unique CSS class of that element here and Slide In will be trigger when you click on that element.<br> If you have multiple classes, separate them with comma. Example - widget-title, site-description<br>", "smile" ),
			),
			"panel" 	=> "Manual Display",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "custom_shortcode",
			"opts"		=> array(
				"link" 		=> '[cp_slide_in id="'.$style.'"]' . __( "Your Content", "smile" ) . '[/cp_slide_in]',
				"class" 	=> "cp-shortcode",
				"value" 		=> "",
				"title" 		=> __( "Launch With Shortcode", "smile" ),
				"description" 	=> __( "Place your text, image or HTML in-between the provided shortcode to launch the Slide In.", "smile" ),
			),
			"panel" 	=> "Manual Display",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "inline_shortcode",
			"opts"		=> array(
				"link" 			=> '[cp_slide_in display="inline" id="'.$style.'"][/cp_slide_in]',
				"class" 		=> "cp-shortcode",
				"value" 		=> "",
				"title" 		=> __( "Display Inline", "smile" ),
				"description" 	=> __( "Use this shortcode to display Slide In inline as a part of page content / Widget.", "smile" ),
			),
			"panel" 	=> "Manual Display",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
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
				"description"=> __( "ConvertPlug can check user history and limit repeat occurrence of Slide In when cookies are enabled. No more annoying Slide Ins!", "smile" ),
			),
			"panel" 	=> "Repeat Control",
			"section" => "Behavior",
			"section_icon" => "connects-icon-layers",
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
				"description" 	=> __( "How many days this Slide In should not be displayed after user submits the form?", "smile" ),
			),
			"panel" 		=> "Repeat Control",
			"dependency" 	=> array('name' => 'developer_mode', 'operator' => '==', 'value' => '1'),
			"section" => "Behavior",
			"section_icon" => "connects-icon-layers",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "closed_cookie",
			"opts"			=> array(
				"title" 		=> __( "Do Not Show After Closing", "smile" ),
				"value" 		=> 30,
				"min" 			=> 0,
				"max" 			=> 365,
				"step" 			=> 1,
				"suffix" 		=> "days",
				"description" 	=> __( "How many days this Slide In should not be displayed after user closes the Slide In?", "smile" ),
			),
			"panel" 		=> "Repeat Control",
			"dependency" 	=> array('name' => 'developer_mode', 'operator' => '==', 'value' => '1'),
			"section" => "Behavior",
			"section_icon" => "connects-icon-layers",
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
				"description" 	=> __( "If set YES, code of this Slide In will be added throughout the website so it can function anywhere. If set NO - select the specific areas where you want the Slide In to function and code will be automatically embedded there.", "smile" ),
			),
			"panel" 	=> "Target Pages",
			"section" => "Behavior",
			"section_icon" => "connects-icon-eye"
		),
		array(
			"type" 		=> "group_filters",
			"class" 	=> "",
			"name" 		=> "exclusive_on",
			"opts"		=> array(
				"title" 	=> __( "Enable Only On", "smile" ),
				"description" => __("Enable Slide In on selected pages, posts, custom posts, special pages.", "smile" ),
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
				"description" => __("Enable Slide In on all single posts of particular custom post types, taxonomies.", "smile" ),
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
				"link" 		=> __( "You can select the exceptional areas, where you want this Slide In to function.", "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"panel" 	=> "Target Pages",
			"section" => "Behavior",
			"section_icon" => "connects-icon-eye",
			"dependency" => array('name' => 'global', 'operator' => '==', 'value' => 'false')
		),
		array(
			"type" 		=> "group_filters",
			"class" 	=> "",
			"name" 		=> "exclude_from",
			"opts"		=> array(
				"title" 	=> __( "Exceptionally, Disable On", "smile" ),
				"description" => __( 'Exceptionally disable Slide In on selected pages, posts, custom posts, special pages.', 'smile' ),
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
				"description" => __("Exceptionally disable Slide In on all single posts of particular custom post types, taxonomies.", "smile" ),
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
				"link" 		=> __( "You can select the areas, where you do not want this Slide In to function.", "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"panel" 	=> "Target Pages",
			"section" => "Behavior",
			"section_icon" => "connects-icon-eye",
			"dependency" => array('name' => 'global', 'operator' => '==', 'value' => 'true'),
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "show_for_logged_in",
			"opts"		=> array(
				"title" 	=> __( "Logged-in Users", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "SHOW", "smile" ),
				"off"		=> __( "HIDE",  "smile" ),
				"description"=> __( "If your website has login functionality, should the Slide In be visible to logged users?", "smile" ),
			),
			"panel" 	=> "Target Visitors",
			"section" => "Behavior",
			"section_icon" => "connects-icon-location-2"
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "display_on_first_load",
			"opts"		=> array(
				"title" 	=> __( "First Time Users", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "SHOW", "smile" ),
				"off"		=> __( "HIDE",  "smile" ),
				"description"=> __( "When user visits your site for the first time, should Slide In be visible?", "smile" ),
			),
			"panel" 	=> "Target Visitors",
			"section" => "Behavior",
			"section_icon" => "connects-icon-location-2"
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
			"section_icon" => "connects-icon-cog",
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "inactivity_link",
			"opts"		=> array(
				"link" 		=> __( "By default, this Slide In will be effective for all. However using controls above, you can hide it for certain visitors.", "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"panel" 	=> "Target Visitors",
			"section" => "Behavior",
			"section_icon" => "connects-icon-toggle",
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
				"description" 	=> __( "Slide In can be displayed when the user is came from a website you would like to track. Eg. If you set to track google.com, all users coming from google will see this popup.", "smile" ),
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
				"title" 	=> __( "Enable Slide In On Site", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "LIVE", "smile" ),
				"off"		=> __( "PAUSE",  "smile" ),
				"description" 	=> __( "When Slide In set as pause, it won't be effective on your website.", "smile" ),
			),
			"panel" 	=> "Slide In Status",
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
				"value" 	=> '0',
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
			"name" 		=> "custom_html_notice",
			"opts"		=> array(
				"link" 		=> __( 'Preview of the custom form might not be accurate here. For the best accuracy, please check output on the frontend.', "smile" ),
				"value" 		=> "",
				"title" 		=> "",
			),
			"panel" 	=> "Form Setup",
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
			"dependency"	=> array("name" => "mailer", "operator" => "==", "value" => "custom-form"),
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
				"value" 		=> __( "Thank you.", "smile" ),
				"description" 	=> __( "Enter the message you would like to display the user after successfully added to the list.<br/>This input field supports HTML too.", "smile" ),
			),
			"panel" 	=> "Form Setup",
			"dependency" => array('name' => 'on_success', 'operator' => '==', 'value' => 'message'),
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
		),
		//Slidein close After form submission Options
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
				"description" 	=> __( "Select how your Slide In behaves after successful form submission.", "smile" ),
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
				"title" 		=> __( "Hide Slide In After 'x' Seconds of Submission", "smile" ),
				"value" 		=> 1,
				"min" 			=> 0,
				"max" 			=> 100,
				"step" 			=> 1,
				"suffix" 		=> "s",
				"description" 	=> __( "Hide Slide In after successful form submission.", "smile" ),
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
				"description" 	=> __( "Enter the message you would like to display the user for invalid email address<br/>This input field supports HTML too..", "smile" ),
			),
			"panel" 	=> "Form Setup",
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
			"dependency"	=> array("name" => "mailer", "operator" => "!=", "value" => "custom-form"),
		),
	);

//form color and form border array
$form_bg_color = array (
			array(
				"type" 		=> "section",
				"class" 	=> "",
				"name" 		=> "form_bg_option",
				"opts"		=> array(
					"title" 		=> __( "Optin Area", "smile" ),
					"value" 		=> "",
				),
				"panel" 	=> "Optin Form",
				"section" => "Design",
				"section_icon" => "connects-icon-disc"
			),
			array(
					"type" 		=> "colorpicker",
					"class" 	=> "",
					"name" 		=> "form_border_color",
					"opts"		=> array(
						"title" 		=> __( "Optin Area Border Color", "smile" ),
						"value" 		=> "#fff",
					),
					"panel" 	=> "Optin Form",
					"section" => "Design",
					"section_icon" => "connects-icon-disc",
				),
				array(
					"type" 		=> "colorpicker",
					"class" 	=> "",
					"name" 		=> "form_bg_color",
					"opts"		=> array(
						"title" 		=> __( "Optin Area Background Color", "smile" ),
						"value" 		=> "rgba(46, 46, 46, 0.41)",
					),
					"panel" 	=> "Optin Form",
					"section" => "Design",
					"section_icon" => "connects-icon-disc",
				),
	);

 //for special offer title background color option
	$title_bg_color = array(
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "slidein_title_bg_color",
			"opts"		=> array(
				"title" 		=> __( "Title Background Color", "smile" ),
				"value" 		=> "rgb(225, 225, 225)",
				"description" 	=> __( "Choose the background color for Slide In title area.", "smile" ),
			),
			"panel" 	=> "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
	);

	//for special offer title background color option
	$desc_bg_color = array(
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "slidein_desc_bg_color",
			"opts"		=> array(
				"title" 		=> __( "Description Background Color", "smile" ),
				"value" 		=> "rgba(230, 145, 56, 0.4)",
				"description" 	=> __( "Choose the background color for Slide In description area.", "smile" ),
			),
			"panel" 	=> "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
	);


	//Slide In button
 	$slidein_btn = array(
 	//	Slide Button Options
 		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "toggle_btn",
			"opts"		=> array(
				"title" 	=> __( "Toggle Button", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
			),
			"panel" 	=> "Toggle Button",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
 		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "slide_button_title",
			"opts"		=> array(
				"title" 		=> __( "Button Title", "smile" ),
				"value" 		=> "CLICK ME",
				"description" 	=> __( "Enter the button title.", "smile" ),
			),
			"panel" 	=> "Toggle Button",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
			"dependency"	=> array("name" => "toggle_btn", "operator" => "==", "value" => true),
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "slide_button_text_color",
			"opts"		=> array(
				"title" 		=> __( "Slide Button Text Color", "smile" ),
				"value" 		=> "rgb(255, 255, 255)",
			),
			"dependency"	=> array("name" => "toggle_btn", "operator" => "==", "value" => true),
			"panel" 	=> "Toggle Button",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),

		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "side_button_bg_color",
			"opts"		=> array(
				"title" 		=> __( " Button Background Color", "smile" ),
				"value" 		=> "rgb(0, 0, 0)",
				//"description" 	=> __( "Select the button background color.", "smile" ),
			),
		    "dependency"	=> array("name" => "toggle_btn", "operator" => "==", "value" => true),
			"panel" 	=> "Toggle Button",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
		//	store button darken on hover
		array(
		    "type"         => "textfield",
		    "name"         => "side_button_bg_hover_color",
		    "opts"         => array(
		        "title"     => __( "Button BG Hover Color", "smile" ),
		        "value"     => "",
		    ),
		    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"panel" 	=> "Toggle Button",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
		//	store button lighten gradient
		array(
		    "type"         => "textfield",
		    "name"         => "side_button_bg_gradient_color",
		    "opts"         => array(
		        "title"     => __( "Button Gradient Color", "smile" ),
		        "value"     => "",
		    ),
		    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"panel" 	=> "Toggle Button",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
 	);

	// optin widget border color
	$optin_border = array(
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "optin_border_color",
			"opts"		=> array(
				"title" 		=> __( "Form Border Color", "smile" ),
				"value" 		=> "#999999",
				"css_property" => "border-bottom-color",
				"css_selector" => ".cp-optin-widget .cp-slidein-head",
			),
			"panel" 	=> "Optin Form",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "optin_border_width",
			"opts"			=> array(
				"title" 		=> __( "Border Size", "smile" ),
				"value" 		=> 1,
				"min" 			=> 0,
				"max" 			=> 40,
				"step" 			=> 1,
				"suffix" 		=> "px",
				"css_property" => "border-bottom-width",
				"css_selector" => ".cp-optin-widget .cp-slidein-head",
			),
			"panel" 	 => "Optin Form",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
	);

	// optin widget border color
	$social_optin_border = array(
		array(
				"type" 		=> "section",
				"class" 	=> "",
				"name" 		=> "widget_media_layout",
				"opts"		=> array(
					"title"  => "Seperator Border",
					"link" => "",
					"value"  => "",
				),
				"panel" 	=> "Background",
				"section" => "Design",
				"section_icon" => "connects-icon-image"
			),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "optin_border_color",
			"opts"		=> array(
				"title" 		=> __( "Border Color", "smile" ),
				"value" 		=> "#999999",
				"css_property" => "border-bottom-color",
				"css_selector" => ".cp-optin-widget .cp-slidein-head",
			),
			"panel" 	=> "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "optin_border_width",
			"opts"			=> array(
				"title" 		=> __( "Border Size", "smile" ),
				"value" 		=> 1,
				"min" 			=> 0,
				"max" 			=> 40,
				"step" 			=> 1,
				"suffix" 		=> "px",
				"css_property" => "border-bottom-width",
				"css_selector" => ".cp-optin-widget .cp-slidein-head",
			),
			"panel" 	=> "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
	);

		/*** Array contains Slide In Image options ***/
	$slidein_img = array(

		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "slidein_img_src",
			"opts" 		=> array(
				"title" 	=> __( "Image source","smile"),
				"value" 	=> "upload_img",
				"options" 	=> array(
						__( "Custom URL", "smile" ) 	 => "custom_url",
						__( "Upload Image", "smile" ) 	 => "upload_img",
						__( "None", "smile" ) 	 		 => "none",
					)
				),
			"panel" => "Slide In Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "slidein_img_custom_url",
			"opts"		=> array(
				"title" 		=> __( "Custom URL", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "Enter custom URL for your image.", "smile" ),
			),
			"panel" 	=> "Slide In Image",
			"dependency" => array('name' => 'slidein_img_src', 'operator' => '==', 'value' => 'custom_url'),
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "media",
			"class" 	=> "",
			"name" 		=> "slidein_image",
			"opts"		=> array(
				"title" 		=> __( "Upload Image", "smile" ),
				"value" 		=> plugins_url('config/img/default-image.png', __FILE__ ),
				"description" 	=> __( "Upload an image that will be displayed inside the content area.Image size will not bigger than its container.", "smile" ),
			),
			"panel" 	 => "Slide In Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'slidein_img_src', 'operator' => '==', 'value' => 'upload_img')
		),
		array(
			"type" 		=> "slider",
			"class" 	=> "",
			"name" 		=> "image_size",
			"opts"			=> array(
				"title" 		=> __( "Resize Image", "smile" ),
				"css_property" => "max-width",
				"css_selector" => ".cp-image-container img",
				"value" 		=> 298,
				"min" 			=> 1,
				"max" 			=> 1000,
				"step" 			=> 1,
				"suffix" 		=> "px",
				"description" 	=> __( "The maximum size of an image is limited to the size of its container.", "smile" ),
			),
			"panel" 	 => "Slide In Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'slidein_img_src', 'operator' => '!=', 'value' => 'none')
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "image_position",
			"opts"		=> array(
				"title" 	=> __( "Image Position", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "RIGHT", "smile" ),
				"off"		=> __( "LEFT", "smile" ),
			),
			"panel" 	 => "Slide In Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'slidein_img_src', 'operator' => '!=', 'value' => 'none')
		),
		array(
			"type" 		=> "slider",
			"class" 	=> "",
			"name" 		=> "image_horizontal_position",
			"opts"			=> array(
				"title" 		=> __( "Horizontal Position", "smile" ),
				"css_property" => "left",
				"css_selector" => ".cp-image-container img",
				"value" 		=> 0,
				"min" 			=> -250,
				"max" 			=> 250,
				"step" 			=> 1,
			),
			"panel" 	 => "Slide In Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'slidein_img_src', 'operator' => '!=', 'value' => 'none')
		),
		array(
			"type" 		=> "slider",
			"class" 	=> "",
			"name" 		=> "image_vertical_position",
			"opts"			=> array(
				"title" 		=> __( "Vertical Position", "smile" ),
				"css_property" => "top",
				"css_selector" => ".cp-image-container img",
				"value" 		=> 0,
				"min" 			=> -250,
				"max" 			=> 250,
				"step" 			=> 1,
			),
			"panel" 	 => "Slide In Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'slidein_img_src', 'operator' => '!=', 'value' => 'none')
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
				"description" 	=> __( "On smaller screens like mobile, smaller modals look more beautiful. To reduce the size of the modal, you may hide the image with this setting.", "smile" ),
			),
			"panel" 	 => "Slide In Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'slidein_img_src', 'operator' => '!=', 'value' => 'none')
		),
	);

	// Add options and manage their orders
    // blank theme
	smile_update_options( "Smile_Slide_Ins", "blank",
		array_merge(
			$name,
			$secondary_title,
			$background,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior
		)
	);

	//optin
	smile_update_options( "Smile_Slide_Ins", "optin",
		array_merge(
			$name,
			$cp_form,
			$background,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);

	//optin_widget
	smile_update_options( "Smile_Slide_Ins", "optin_widget",
		array_merge(
			$name,
			$cp_form,
			$background,
			$widget_close,
			$optin_border,
			$animations,
			$adv_design_options_widget,
			$behavior,
			$submission
		)
	);

    // social media theme
	smile_update_options( "Smile_Slide_Ins", "social_fly_in",
		array_merge(
			$name,
			$secondary_title,
			$background,
			$cp_social,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior
		)
	);

	 // floating_social_bar  theme
	smile_update_options( "Smile_Slide_Ins", "floating_social_bar",
		array_merge(
			$name,
			$cp_social,
			$adv_design_options,
			$behavior
		)
	);

	//social_widget_box
	smile_update_options( "Smile_Slide_Ins", "social_widget_box",
		array_merge(
			$name,
			$background,
			$widget_close,
			$cp_social,
			$social_optin_border,
			$animations,
			$adv_design_options_widget,
			$behavior
		)
	);

	//free_widget
	smile_update_options( "Smile_Slide_Ins", "free_widget",
		array_merge(
			$name,
			$cp_form,
			$background,
			$slidein_img,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);

}

// update default values of optin
if( function_exists( "smile_update_default" ) ){
	$optin_default = array(
		"form_fields" 				=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			 	=> "cp-form-layout-3",
		"form_input_align"		 	=> "left",
		"form_submit_align" 	 	=> "cp-submit-wrap-full",
		"form_grid_structure"		=> "cp-form-grid-structure-2",
		"form_lable_font_size"		=> 14,
		"form_input_font_size"		=> 14,
		"submit_button_tb_padding" 	=> 13,
		"submit_button_lr_padding" 	=> 20,
		"form_input_padding_tb"		=> 11,
		"form_input_padding_lr"		=> 20,
		"slidein_short_desc1"  		=> '<span style="">Get email marketing pro tips delivered straight to your inbox! </span>',
		"slidein_title1"		 	=> "Stay Connected!",
		"slidein_confidential" 		=> "",
		"slidein_bg_color"     		=> "#ffffff",
		"button_title"       		=> "SUBSCRIBE",
		"button_bg_color"    		=> "#ff8201",
		"button_border_color"		=> "#ff8201",
		"cp_slidein_width"     		=> "480",
		"cp_close_image_width" 		=> 26,
		"border" 			 		=> "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:#ff8201|bw_type:1|bw_all:5|bw_t:5|bw_l:0|bw_r:0|bw_b:0",
		"btn_disp_next_line" 		=> false,
		"close_position"     		=> "adj_slidein",
		"slidein_title_color" 		=> "#000000",
		"slidein_desc_color" 		=> "#000000",
		"tip_color"          		=> "#000000",
		"placeholder_text"   		=> "Enter Your Email Here",
		"name_text"  		 		=> "Enter Your Name",
		"placeholder_font"   		=> "Raleway",
		"overlay_effect" 	 		=> "smile-slideInUp",
		"exit_animation"     		=> "smile-slideOutDown",
		"close_slidein" 	 		=> "close_img",
		"close_text_color"	 		=> "#898989",
	);
	foreach( $optin_default as $option => $value ){
		smile_update_default( "Smile_Slide_Ins", "optin", $option, $value );
	}

	//	Optin Widget
	$optin_default = array(
		"form_fields" 				=> "order->0|input_type->textfield|input_label->Name|input_name->name|input_placeholder->Enter Your Name|input_require->true;order->1|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			 	=> "cp-form-layout-1",
		"form_input_align"		 	=> "center",
		"form_submit_align" 	 	=> "cp-submit-wrap-full",
		"form_lable_font_size"		=> 13,
		"form_input_font_size"		=> 13,
		"submit_button_tb_padding" 	=> 5,
		"submit_button_lr_padding" 	=> 20,
		"form_input_padding_tb"		=> 5,
		"form_input_padding_lr"		=> 20,
		"slidein_short_desc1"    	=> 'Sign-up to get the latest news straight to your inbox.',
		"slidein_title1"		 	=> "Subscribe to our newsletter",
		"close_slidein"          	=> "do_not_close",
		"slidein_confidential"   	=> "Give it a try, you can unsubscribe anytime.",
		"slidein_bg_color"     	 	=> "#414042",
		"button_title"           	=> "SUBSCRIBE!",
		"button_bg_color"        	=> "#ff8204",
		"button_border_color"    	=> "#ff8204",
		"border" 			     	=> "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:rgb(255,255,255)|bw_all:1|bw_t:1|bw_l:1|bw_r:1|bw_b:1",
		"btn_disp_next_line"     	=> false,
		"close_position"         	=> "adj_slidein",
		"slidein_title_color"    	=> "rgb(250, 250, 255)",
		"slidein_desc_color" 	 	=> "rgb(250, 250, 250)",
		"tip_color"              	=> "rgb(250, 250, 250)",
		"placeholder_text"       	=> "Email Address",
		"name_text"  		     	=> "Name",
		"placeholder_font"       	=> "Verdana",
		"overlay_effect" 		 	=> "smile-slideInUp",
		"exit_animation"     	    => "smile-slideOutDown",
		"namefield" 			 	=> true,
		"cp_slidein_width"		 	=> 320,
		"submit_button_tb_padding" 	=> 7,
	);
	foreach( $optin_default as $option => $value ){
		smile_update_default( "Smile_Slide_Ins", "optin_widget", $option, $value );
	}

	//	Blank
	$blank_default = array(
		"slidein_title1"	=> "BLANK style is purely built for customization. This style supports text, images, shortcodes, HTML etc. Use Source button from Rich Text Editor toolbar & customize your Slide In effectively.",
		"overlay_effect" 	=> "smile-slideInUp",
		"exit_animation"    => "smile-slideOutDown"
	);
	foreach( $blank_default as $option => $value ){
		smile_update_default( "Smile_Slide_Ins", "blank", $option, $value );
	}

	//	social_fly_in
	$social_fly_in_default = array(
		"slidein_title1"		  => "STAY IN TOUCH",
		"slidein_short_desc1"     => '',
		"overlay_effect" 		  => "smile-slideInUp",
		"exit_animation"    	  => "smile-slideOutDown",
		"cp_slidein_width"		  => 390,
		"cp_social_icon_column"   => "2",
		"social_container_border" => "4",
		"cp_social_icon_shape" 	  => "flat",
		"cp_social_icon_effect"   => "gradient",
		"cp_social_icon" 		  => "order:0|input_type:Facebook|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:1|input_type:Google|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:2|input_type:Twitter|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:3|input_type:Blogger|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:4|input_type:LinkedIn|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:5|input_type:StumbleUpon|network_name:|input_action:social_sharing|profile_link:|smile_adv_share_opt:0|input_share:|input_share_count:",
		"border" 			      => "br_all:3|br_tl:3|br_tr:3|br_br:3|br_bl:3|style:none|color:rgb(255,255,255)|bw_all:1|bw_t:1|bw_l:1|bw_r:1|bw_b:1",

	);
	foreach( $social_fly_in_default as $option => $value ){
		smile_update_default( "Smile_Slide_Ins", "social_fly_in", $option, $value );
	}

	//	floating_social_bar
	$floating_social_bar_default = array(
		//"slidein_title1"		  => "STAY IN TOUCH",
		"overlay_effect" 		  => "smile-slideInLeft",
		"exit_animation"    	  => "smile-slideOutLeft",
		"slidein_bg_color"     	  => "rgba(239,239,239,0.01)",
		"cp_slidein_width"		  => 80,
		"cp_social_icon_column"   => "1",
		"social_container_border" => "0",
		"cp_social_icon_shape" 	  => "square",
		"cp_social_icon_effect"   => "gradient",
		"close_slidein" 		  => "do_not_close",
		"content_padding"		  => true,
		"cp_social_share_count"   => false,
		"cp_display_nw_name" 	  => false,
		"slidein_position" 		  => "center-left",
		"cp_social_icon_style" 	  => "cp-icon-style-top",
		"cp_social_remove_icon_spacing" => true,
		"box_shadow"			  =>"type:none|horizontal:0|vertical:0|blur:5|spread:0|color:rgba(86,86,131,0.6)",
		"cp_social_icon" 		  => "order:0|input_type:Facebook|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:1|input_type:Google|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:2|input_type:Twitter|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:3|input_type:Blogger|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:4|input_type:Pinterest|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:nput_action:social_sharing|profile_link:|smile_adv_share_opt:0|input_share:|input_share_count:",
		"border" 			      => "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgb(255,255,255)|bw_all:1|bw_t:1|bw_l:1|bw_r:1|bw_b:1",

	);
	foreach( $floating_social_bar_default as $option => $value ){
		smile_update_default( "Smile_Slide_Ins", "floating_social_bar", $option, $value );
	}

	//	social_widget_box Widget
	$social_widget_box_default = array(
		"slidein_short_desc1"    	=> 'Share this with your friends now.',
		"slidein_title1"		 	=> "STAY IN TOUCH",
		"close_slidein"          	=> "do_not_close",
		"slidein_confidential"   	=> "Give it a try, you can unsubscribe anytime.",
		"slidein_bg_color"     	 	=> "#fafafa",
		"border" 			     	=> "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgb(186,186,186)|bw_all:1|bw_t:1|bw_l:1|bw_r:1|bw_b:1",
		"close_position"         	=> "adj_slidein",
		"slidein_title_color"    	=> "rgb(0, 0, 0)",
		"slidein_desc_color" 	 	=> "rgb(0, 0, 0)",
		"tip_color"              	=> "rgb(0, 0, 0)",
		"overlay_effect" 		 	=> "smile-slideInUp",
		"exit_animation"     	    => "smile-slideOutDown",
		"optin_border_color"		=> "rgba(153,153,153,0.46)",
		"cp_slidein_width"		 	=> 320,
		"cp_display_nw_name" 		=> false,
		"cp_social_icon_effect" 	=> "flat",
		"cp_social_icon_align"  	=> "center",
		"cp_social_icon" 			=> "order:0|input_type:Facebook|network_name:|input_action:social_sharing|profile_link:|smile_adv_share_opt:0|input_share:|input_share_count:;order:1|input_type:Google|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:2|input_type:Blogger|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:3|input_type:LinkedIn|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:",
	);
	foreach( $social_widget_box_default as $option => $value ){
		smile_update_default( "Smile_Slide_Ins", "social_widget_box", $option, $value );
	}

	//free_widget
	$free_widget_default = array(
		"form_fields" 				=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			 	=> "cp-form-layout-4",
		"form_input_align"		 	=> "left",
		"form_submit_align" 	 	=> "cp-submit-wrap-full",
		"form_grid_structure"		=> "cp-form-grid-structure-2",
		"form_lable_font_size"		=> 14,
		"form_input_font_size"		=> 14,
		"submit_button_tb_padding" 	=> 13,
		"submit_button_lr_padding" 	=> 20,
		"form_input_padding_tb"		=> 11,
		"form_input_padding_lr"		=> 20,
		"slidein_short_desc1"  		=> '<span style="">This style is purely built for customization and supports text, images, shortcodes, HTML etc. </span>',
		"slidein_title1"		 	=> "Download all the latest Freebies!",
		"slidein_confidential"   	=> "You can unsubscribe anytime.",
		"slidein_bg_color"     		=> "#ffffff",
		"button_title"       		=> "SUBSCRIBE HERE",
		"button_bg_color"    		=> "#ef5350",
		"button_border_color"		=> "#ef5350",
		"cp_slidein_width"     		=> "450",
		"cp_close_image_width" 		=> 26,
		"border" 			 		=> "br_all:5|br_tl:5|br_tr:5|br_br:5|br_bl:5|style:solid|color:#ff8201|bw_type:1|bw_all:0|bw_t:0|bw_l:0|bw_r:0|bw_b:0",
		"btn_disp_next_line" 		=> false,
		"close_position"     		=> "adj_slidein",
		"slidein_title_color" 		=> "#30414f",
		"slidein_desc_color" 		=> "#30414f",
		"tip_color"          		=> "#30414f",
		"placeholder_text"   		=> "Enter Your Email Here",
		"name_text"  		 		=> "Enter Your Name",
		"placeholder_font"   		=> "Raleway",
		"overlay_effect" 	 		=> "smile-slideInUp",
		"exit_animation"     		=> "smile-slideOutDown",
		"close_slidein" 	 		=> "close_img",
		"close_text_color"	 		=> "#898989",
		"slide_button_bg_color"     => "#ef5350",
		"toggle_btn"				=> true,
		"btn_border_radius"			=> 5,
		"image_size"  				=> 100,
		"image_position"			=> false,
		"slidein_image" 			=> plugins_url('config/img/icon.png', __FILE__ ),

	);
	foreach( $free_widget_default as $option => $value ){
		smile_update_default( "Smile_Slide_Ins", "free_widget", $option, $value );
	}
}

//Remove option
if( function_exists( "smile_remove_option" ) ){
	//	Blank
	smile_remove_option( "Smile_Slide_Ins", "blank", array( 'input_bg_color' ) );

	// floating_social_bar
	smile_remove_option( "Smile_Slide_Ins", "floating_social_bar", array('box_shadow','border', 'border_sub_title','box_shadow_sub_title', 'cp_social_icon_effect','cp_display_nw_name' , 'cp_social_icon_column' ,'cp_social_icon_style','content_padding' ,'cp_slidein_width') );

	// optin widget
	smile_remove_option( "Smile_Slide_Ins", "optin_widget", array( 'btn_disp_next_line' , 'hide_animation_width' ,'disable_overlay_effect' , 'exit_animation' ,'overlay_effect','content_padding') );

	// social_widget_box
	smile_remove_option( "Smile_Slide_Ins", "social_widget_box", array( 'btn_disp_next_line' , 'hide_animation_width' ,'disable_overlay_effect' , 'exit_animation' ,'overlay_effect','content_padding') );

}

/**
 * Partial Refresh - Update values
 */
if( function_exists('smile_update_partial') ) {


	// social_widget
	$social_widget_box_partial = array(
	    'optin_border_color' => array(
	        'css_selector' => '.cp-social-widget .cp-slidein-head',
	        'css_property' => 'border-bottom-color',
	    ),
	    'optin_border_width' => array(
	        'css_selector' => '.cp-social-widget .cp-slidein-head',
	        'css_property' => 'border-bottom-width',
	    ),
	);
	foreach( $social_widget_box_partial as $option => $parse_array ){
	    smile_update_partial( "Smile_Slide_Ins", "social_widget_box", $option, $parse_array );
	}

}
