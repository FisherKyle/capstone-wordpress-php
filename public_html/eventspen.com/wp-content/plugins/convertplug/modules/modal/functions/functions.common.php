<?php
if( function_exists( "smile_update_settings" ) ){
	//get style id
	$style_id_for_customcss ='';
	if( isset( $_GET['variant-style'] ) ) {
		$style_id_for_customcss = $_GET['variant-style'];
		$style = $_GET['variant-style'];

	} else {
		if( isset( $_GET['style'] ) ) {
	    	$style_id_for_customcss = $_GET['style'];
		}
	}

	/* Get ConvertPlug Form Option Array */
	global $cp_form;
	global $cp_social;
	global $cp_count_down;

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
			"name" 		=> "modal_title1",
			"opts"		=> array(
				"title" 		=> __( "Main Title", "smile" ),
				"value" 		=> __( "LEARN HOW TO ACQUIRE 15,000 NEW VISITORS EVERY MONTH", "smile" ),
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
			"name" 		=> "modal_short_desc1",
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
			"name" 		=> "modal_short_desc",
			"opts"		=> array(
				"title" 		=> __( "Short Description", "smile" ),
				"value" 		=> __( "",  "smile" ),
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
			"name" 		=> "modal_confidential",
			"opts"		=> array(
				"title" 		=> __( "Notice / Tip Under Form", "smile" ),
				"value" 		=> __( "Written by John Doe, well versed writer at Brainstorm Publication.", "smile" ),
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
			"name" 		=> "count_down_title",
			"opts"		=> array(
				"title" 		=> __( "Count Down Title", "smile" ),
				"value" 		=> __( "Written by John Doe, well versed writer at Brainstorm Publication.", "smile" ),
			),
			"panel" 	 => "Name",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
		array(
			"type" 		=> "textarea",
			"class" 	=> "",
			"name" 		=> "modal_content",
			"opts"		=> array(
				"title" 		=> __( "Modal Content", "smile" ),
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
			"name" 		=> "modal_sec_title",
			"opts"		=> array(
				"title" 		=> __( "Secondary Title", "smile" ),
				"value" 		=> __( "[Revised and Updated for November 2015]", "smile" ),
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
			"name" 		=> "modal_sec_title_color",
			"opts"		=> array(
				"title" 		=> __( "Modal Secondary Title Color", "smile" ),
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
			"name" 		=> "modal_title_color",
			"opts"		=> array(
				"title" 		=> __( "Modal Title Color", "smile" ),
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
			"name" 		=> "modal_desc_color",
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
			"name" 		=> "modal_bg_color",
			"opts"		=> array(
				"title" 		=> __( "Background Color", "smile" ),
				"value" 		=> "rgb(249, 249, 249)",
				"description" 	=> __( "Choose the background color for modal box area.", "smile" ),
				"css_property" => "background-color",
				"css_selector" => ".cp-modal-body-overlay",

			),
			"panel" 	=> "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "modal_overlay_bg_color",
			"opts"		=> array(
				"title" 		=> __( "Overlay Color", "smile" ),
				"value" 		=> "rgba(0, 0, 0, 0.71)",
				"css_property" 	=> "background",
				"css_selector" 	=> ".cp-overlay",
				"description" 	=> __( "Provide the overlay color that appears behind modal box area.", "smile" ),
			),
			"panel" 	=> "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "modal_bg_image_src",
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
			"name" 		=> "modal_bg_image_custom_url",
			"opts"		=> array(
				"title" 		=> __( "Custom URL", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "Enter custom URL for your image.", "smile" ),
			),
			"panel" 	=> "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'modal_bg_image_src', 'operator' => '==', 'value' => 'custom_url')
		),
		array(
			"type" 		=> "media",
			"class" 	=> "",
			"name" 		=> "modal_bg_image",
			"opts"		=> array(
				"title" 		=> __( "Background Image", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "You can provide an image that would be appear behind the content in the modal box area. For this setting to work, the background color you've chosen must be transparent.", "smile" ),
			),
			"panel" 	 => "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'modal_bg_image_src', 'operator' => '==', 'value' => 'upload_img')
		),
		array(
			"type" 		=> "background",
			"class" 	=> "",
			"name" 		=> "opt_bg",
			"opts"		=> array(
				"title" 		=> "",
				"value" 		=> "no-repeat|center|cover",
			),
			"panel" => "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			// "dependency" => array('name' => 'modal_bg_image_src', 'operator' => '!==', 'value' => 'none')
		)
	);

	/*** Array contains modal image options ***/
	$modal_img = array(

		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "modal_img_src",
			"opts" 		=> array(
				"title" 	=> __( "Image source","smile"),
				"value" 	=> "upload_img",
				"options" 	=> array(
						__( "Custom URL", "smile" ) 	 => "custom_url",
						__( "Upload Image", "smile" ) 	 => "upload_img",
						__( "None", "smile" ) 	 		 => "none",
					)
				),
			"panel" => "Modal Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "modal_img_custom_url",
			"opts"		=> array(
				"title" 		=> __( "Custom URL", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "Enter custom URL for your image.", "smile" ),
			),
			"panel" 	=> "Modal Image",
			"dependency" => array('name' => 'modal_img_src', 'operator' => '==', 'value' => 'custom_url'),
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "media",
			"class" 	=> "",
			"name" 		=> "modal_image",
			"opts"		=> array(
				"title" 		=> __( "Upload Image", "smile" ),
				"value" 		=> plugins_url('config/img/default-image.png', __FILE__ ),
				"description" 	=> __( "Upload an image that will be displayed inside the content area.Image size will not bigger than its container.", "smile" ),
			),
			"panel" 	 => "Modal Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'modal_img_src', 'operator' => '==', 'value' => 'upload_img')
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
			"panel" 	 => "Modal Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'modal_img_src', 'operator' => '!=', 'value' => 'none')
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
			"panel" 	 => "Modal Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'modal_img_src', 'operator' => '!=', 'value' => 'none')
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
			"panel" 	 => "Modal Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'modal_img_src', 'operator' => '!=', 'value' => 'none')
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
			"panel" 	 => "Modal Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'modal_img_src', 'operator' => '!=', 'value' => 'none')
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
			"panel" 	 => "Modal Image",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'modal_img_src', 'operator' => '!=', 'value' => 'none')
		),
	);

	/*** Array contains close link options ***/
	$close_link = array(
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "close_modal",
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
			"name" 		=> "close_image_src",
			"opts" 		=> array(
				"title" 	=> __( "Image source","smile"),
				"value" 	=> "upload_img",
				"options" 	=> array(
						__( "Custom URL", "smile" ) 	  => "custom_url",
						__( "Upload Image", "smile" ) 	  => "upload_img",
						__( "Predefined Icons", "smile" ) => "pre_icons",
						//__( "None", "smile" ) 	 		  => "none",
					)
				),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'close_modal', 'operator' => '==', 'value' => 'close_img'),
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
            "dependency" => array('name' => 'close_image_src', 'operator' => '==', 'value' => 'pre_icons')
        ),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "modal_close_img_custom_url",
			"opts"		=> array(
				"title" 		=> __( "Custom URL", "smile" ),
				"value" 		=> "",
			),
			"panel" 	=> "Close Link",
			"dependency" => array('name' => 'close_image_src', 'operator' => '==', 'value' => 'custom_url'),
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
			"dependency" => array('name' => 'close_modal', 'operator' => '==', 'value' => 'close_txt'),
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
			"dependency" => array('name' => 'close_modal', 'operator' => '==', 'value' => 'close_txt'),
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
				"css_property" => 'src',
				"css_selector" => '.cp-default-close',
			),
			"panel" 	=> "Close Link",
			"dependency" => array('name' => 'close_image_src', 'operator' => '==', 'value' => 'upload_img'),
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "cp_close_image_width",
			"opts"			=> array(
				"title" 		=> __( "Close Image Width", "smile" ),
				"css_selector" => ".cp-overlay-close",
				"css_property" => "width",
				"value" 		=> 32,
				"min" 			=> 15,
				"max" 			=> 128,
				"step" 			=> 1,
				"suffix" 		=> "px",
			),
			"panel" 		=> "Close Link",
			"dependency" => array('name' => 'close_modal', 'operator' => '==', 'value' => 'close_img' , 'name' => 'close_image_src','operator' => '!=', 'value' => 'none'),
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "close_position",
			"opts" 		=> array(
				"title" 	=> __( "Close Image Area","smile"),
				"value" 	=> "adj_modal",
				"options" 	=> array(
						__( "Outside Modal", "smile" ) 	=> "out_modal",
						__( "On Modal Edge", "smile" ) 	=> "adj_modal",
						__( "Inside Modal", "smile" )   => "inside_modal"
					),
				"description" 	=> __( "Select the close button placement area.", "smile" ),
				),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'close_modal', 'operator' => '==', 'value' => 'close_img'),
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
			"dependency" => array('name' => 'close_modal', 'operator' => '!=', 'value' => 'do_not_close'),
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "close_modal_on",
			"opts" 		=> array(
				"title" 	=> __( "Close Modal By Clicking On -", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "Close Button & Overlay", "smile" ),
				"off"		=> __( "Close Button Only", "smile" ),
				),
			"dependency" => array('name' => 'close_modal', 'operator' => '!=', 'value' => 'do_not_close'),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "close_modal_tooltip",
			"opts" 		=> array(
				"title" 	=> __( "Display Tooltip On Hover","smile"),
				"value" 	=> false,
				"on" 		=> __( "Yes", "smile" ),
				"off"		=> __( "No", "smile" ),
				),
			"dependency" => array('name' => 'close_modal', 'operator' => '!=', 'value' => 'do_not_close'),
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
				"value" 		=> __( "Note: Modals are displayed only once!", "smile" ),
			),
			"dependency" => array('name' => 'close_modal_tooltip', 'operator' => '==', 'value' => 'true'),
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
			"dependency" => array('name' => 'close_modal_tooltip', 'operator' => '==', 'value' => 'true'),
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
			"dependency" => array('name' => 'close_modal_tooltip', 'operator' => '==', 'value' => 'true'),
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
			"dependency" => array('name' => 'close_modal', 'operator' => '!=', 'value' => 'do_not_close'),
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
				"description" 	=> __( "How long the close image / text to be displayed after Modal is loaded? (value in seconds).", "smile" ),
			),
			"dependency" => array('name' => 'display_close_on_duration', 'operator' => '==', 'value' => 'true'),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		//	Modal Auto close Options
 		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "modal_auto_close_section",
			"opts"		=> array(
				"title" 		=> __( "Auto Close Module", "smile" ),
				"value" 		=> "",
			),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'close_modal', 'operator' => '!=', 'value' => 'do_not_close'),
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
				"description" 	=> __( "If enabled, Modal will close automatically after 'x' seconds when user is inactive on webpage.", "smile" ),
			),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'close_modal', 'operator' => '!=', 'value' => 'do_not_close'),
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
				"description" 	=> __( " How long the Modal should take to be close after its loaded? (value in seconds).", "smile" ),
			),
			"dependency" => array('name' => 'autoclose_on_duration', 'operator' => '==', 'value' => '1'),
			"panel" => "Close Link",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),

	);

	/*** Array contains animation options ***/
	$animations = array (
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "overlay_effect",
			"opts" 		=> array(
				"title" 	=> __( "Entry Animation Effect","smile" ),
				"value" 	=> "smile-3DRotateBottom",
				"description"=> __( "Animation effect while the modal appears.", "smile" ),
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
			"panel" => "Modal Animation",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type"		=> "dropdown",
			"class"		=> "",
			"name"		=> "exit_animation",
			"opts"		=> array(
				"title"		=> __( "Exit Animation Effect", "smile" ),
				"value"		=> "smile-bounceOutDown",
				"description"	=> __( "Animation effect while the modal disappears.", "smile" ),
				"options"		=> array(
					"No Effect"				=> 'cp-overlay-none',
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
			"panel" => "Modal Animation",
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
			"panel" => "Modal Animation",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "slider",
			"class" 	=> "",
			"name" 		=> "hide_animation_width",
			"opts"			=> array(
				"title" 		=> __( "Disable When Browser Width Is Below -", "smile" ),
				"value" 		=> 768,
				"min" 			=> 240,
				"max" 			=> 1200,
				"step" 			=> 1,
				"description" 	=> __( "When width of the browser is below provided value, the modal animation will disable.", "smile" ),
			),
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"panel" => "Modal Animation",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
	);

	/*** Array contains advance design options ***/
	$adv_design_options = array (
		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "modal_size",
			"opts" 		=> array(
				"title" 	=> __( "Modal Size","smile"),
				"value" 	=> "cp-modal-custom-size",
				"description"=> __( "Provide custom size of your choice or make this modal cover entire screen area.", "smile" ),
				"options" 	=> array(
						__( "Full Screen", "smile" )	=> 'cp-modal-window-size',
						__( "Custom Width", "smile" )	=> 'cp-modal-custom-size'
					)
				),
			"panel" => "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "cp_modal_width",
			"opts"			=> array(
				"title" 		=> __( "Modal Width", "smile" ),
				"css_property"  => "max-width",
				"css_selector" => ".cp-modal, .cp-modal-body",
				"value" 		=> 894,
				"min" 			=> 100,
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
				"description"=> __( "Remove the default padding between content area and modal box edges.", "smile" ),
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "border_sec_title",
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
				"title" 		=> "",
				"css_selector" => ".cp-modal-content",
				"css_property" => "border",
				// "css_preview" => true,
				"value" 		=> 'br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:rgb(255,255, 255)|bw_all:5|bw_t:5|bw_l:5|bw_r:5|bw_b:5',
				"description" 	=> __( "Using very customizable settings below, you can apply a border around the modal box.", "smile" ),
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "box_shadow_sec_title",
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
				"css_selector" => ".cp-modal-body-overlay",
				"css_property" => "box-shadow",
				// "css_preview" => true,
				"value" 	=> 'type:outset|horizontal:0|vertical:0|blur:5|spread:0|color:rgba(86,86,131,0.6)',
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "affiliate_sec_title",
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
		array(
			"type" 		=> "section",
			"class" 	=> "",
			"name" 		=> "custom_code_sec_title",
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
				"description" 	=> __( "Enter your custom css code for this modal here.", "smile" ),
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
				"link" 		=> __( "Add custom CSS to your style. Write custom css statement with prefixed the following unique class : <br><br/><span style='color:#444;font-size:18px;font-family: monospace;' ><b>.".$style_id_for_customcss."</span> </b> ".__("", "smile" )."", "smile" ),
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
			"panel" 	=> "Call to Action",
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
			"panel" 	=> "Call to Action",
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
			"panel" 	=> "Call to Action",
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
			"panel" 	=> "Call to Action",
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
			"panel" 	=> "Call to Action",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "placeholder_color",
			"opts"		=> array(
				"title" 		=> __( "Placeholder Text Color", "smile" ),
				"value" 		=> "rgb(153, 153, 153)",
			),
			"panel" 	=> "Call to Action",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
		array(
		    "type"         => "google_fonts",
		    "name"         => "placeholder_font",
		    "opts"         => array(
		        "title"     => __( "Placeholder Font", "smile" ),
		        "value"     => "",
		        "use_in"      => "panel"
		    ),
			"panel" 	=> "Call to Action",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "input_bg_color",
			"opts"		=> array(
				"title" 		=> __( "Input Box Background Color", "smile" ),
				"value" 		=> "rgb(255, 255, 255)",
			),
			"panel" 	=> "Call to Action",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "input_border_color",
			"opts"		=> array(
				"title" 		=> __( "Input Box Border Color", "smile" ),
				"value" 		=> "rgb(191, 190, 190)",
			),
			"panel" 	=> "Call to Action",
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
			"panel" 	=> "Call to Action",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	);

	/*** Array contains behavior options ***/
	$behavior = array(
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "modal_exit_intent",
			"opts"		=> array(
				"title" 	=> __( "Before User Leaves / Exit Intent", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "If enabled, modal will load right before user is about to leave your website.", "smile" ),
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
				"description" 	=> __( "If enabled, modal will load automatically after few seconds.", "smile" ),
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
				"description" 	=> __( "How long the modal should take to be displayed after the page is loaded? (value in seconds).", "smile" ),
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
				"description" 	=> __( "If enabled, modal will load as user scrolls down on the page.", "smile" ),
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
				"description" 	=> __( "How much should the user scroll the page to display the modal? (value in %).", "smile" ),
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
				"description" => __( "If enabled, a modal will be displayed to visitor if he is idle on page for certain time.", "smile" )
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
				"link" 		=> __( "Modal will trigger after `".$user_inactivity."  ".__("Seconds", "smile" )."` of user inactivity. If you would like, you can change the time <a target=\"_blank\" href=\"".admin_url('admin.php?page=convertplug&view=settings#user_inactivity')."\">here</a>", "smile" ),
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
				"description" 	=> __( "Modal will be triggered when user scrolls to the end of post.", "smile" ),
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
				"description" 	=> __( "Modal will be triggered when user scrolls to certain css class or id.", "smile" ),
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
				"description" 	=> __( "Modal can be triggered on click of any UI element. Just provide the unique CSS class of that element here and modal will be trigger when you click on that element.", "smile" ),
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
				"description" 	=> __( "<br>Modal can be triggered on click of any UI element. Just provide the unique CSS class of that element here and modal will be trigger when you click on that element.<br> If you have multiple classes, separate them with comma. Example - widget-title, site-description<br>", "smile" ),
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
				"link" 		=> '[cp_modal id="'.$style.'"]' . __( "Your Content", "smile" ) . '[/cp_modal]',
				"class" 	=> "cp-shortcode",
				"value" 		=> "",
				"title" 		=> __( "Launch With Shortcode", "smile" ),
				"description" 	=> __( "Place your text, image or HTML in-between the provided shortcode to launch the modal.", "smile" ),
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
				"link" 			=> '[cp_modal display="inline" id="'.$style.'"][/cp_modal]',
				"class" 		=> "cp-shortcode",
				"value" 		=> "",
				"title" 		=> __( "Display Inline", "smile" ),
				"description" 	=> __( "Use this shortcode to display modal popup inline as a part of page content / Widget.", "smile" ),
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
				"description"=> __( "ConvertPlug can check user history and limit repeat occurrence of modal when cookies are enabled. No more annoying modals!", "smile" ),
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
				"description" 	=> __( "How many days this modal should not be displayed after user submits the form?", "smile" ),
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
				"value" 		=> 30,
				"min" 			=> 0,
				"max" 			=> 365,
				"step" 			=> 1,
				"suffix" 		=> "days",
				"description" 	=> __( "How many days this modal should not be displayed after user closes the modal?", "smile" ),
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
				"description" 	=> __( "If set YES, code of this modal will be added throughout the website so it can function anywhere. If set NO - select the specific areas where you want the modal to function and code will be automatically embedded there.", "smile" ),
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
				"description" => __("Enable module on selected pages, posts, custom posts, special pages.", "smile" ),
				"value" 	=> '',
			),
			"panel" 	=> "Target Pages",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
			"dependency" => array('name' => 'global', 'operator' => '==', 'value' => '0'),
		),
		array(
			"type" 		=> "post-types",
			"class" 	=> "",
			"name" 		=> "exclusive_post_type",
			"opts"		=> array(
				"title" 	=> __( "", "smile" ),
				"description" => __("Enable module on all single posts of particular custom post types, taxonomies.", "smile" ),
				"value" 	=> '',
			),
			"panel" 	=> "Target Pages",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
			"dependency" => array('name' => 'global', 'operator' => '==', 'value' => '0'),
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "inactivity_link",
			"opts"		=> array(
				"link" 		=> __( "You can select the exceptional areas, where you want this modal to function.", "smile" ),
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
				"description" => __( 'Exceptionally disable module on selected pages, posts, custom posts, special pages.', 'smile' ),
				"value" 	=> '',
			),
			"panel" 	=> "Target Pages",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
			"dependency" => array('name' => 'global', 'operator' => '==', 'value' => '1'),
		),
		array(
			"type" 		=> "post-types",
			"class" 	=> "",
			"name" 		=> "exclude_post_type",
			"opts"		=> array(
				"title" 	=> __( "", "smile" ),
				"description" => __("Exceptionally disable module on all single posts of particular custom post types, taxonomies.", "smile" ),
				"value" 	=> '',
			),
			"panel" 	=> "Target Pages",
			"section" => "Behavior",
			"section_icon" => "connects-icon-cog",
			"dependency" => array('name' => 'global', 'operator' => '==', 'value' => '1'),
		),

		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "inactivity_link",
			"opts"		=> array(
				"link" 		=> __( "You can select the areas, where you do not want this modal to function.", "smile" ),
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
				"on" 		=> __( "SHOW", "smile" ),
				"off"		=> __( "HIDE",  "smile" ),
				"description"=> __( "If your website has login functionality, should the modal be visible to logged users?", "smile" ),
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
				"on" 		=> __( "SHOW", "smile" ),
				"off"		=> __( "HIDE",  "smile" ),
				"description"=> __( "When user visits your site for the first time, should modal be visible?", "smile" ),
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
			"section_icon" => "connects-icon-cog",
		),
		array(
			"type" 		=> "txt-link",
			"class" 	=> "",
			"name" 		=> "inactivity_link",
			"opts"		=> array(
				"link" 		=> __( "By default, this modal will be effective for all. However using controls above, you can hide it for certain visitors.", "smile" ),
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
				"description" 	=> __( "Modal can be displayed when the user is came from a website you would like to track. Eg. If you set to track google.com, all users coming from google will see this popup.", "smile" ),
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
				"title" 	=> __( "Enable Modal On Site", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "LIVE", "smile" ),
				"off"		=> __( "PAUSE",  "smile" ),
				"description" 	=> __( "When modal set as pause, it won't be effective on your website.", "smile" ),
			),
			"panel" 	=> "Modal Status",
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
				"value" 		=> __( 'Thank you.', 'smile' ),
				"description" 	=> __( "Enter the message you would like to display the user after successfully added to the list.<br/>This input field supports HTML too.", "smile" ),
			),
			"panel" 	=> "Form Setup",
			"dependency" => array('name' => 'on_success', 'operator' => '==', 'value' => 'message'),
			"section" => "Submission",
			"section_icon" => "connects-icon-disc",
		),
		//Modal close After form submission Options
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
				"description" 	=> __( "Select how your Modal behaves after successful form submission.", "smile" ),
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
				"title" 		=> __( "Hide Modal After 'x' Seconds of Submission", "smile" ),
				"value" 		=> 1,
				"min" 			=> 0,
				"max" 			=> 100,
				"step" 			=> 1,
				"suffix" 		=> "s",
				"description" 	=> __( "Hide Modal after successful form submission.", "smile" ),
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

//form color and form border array
$form_bg_color = array (
			array(
				"type" 		=> "section",
				"class" 	=> "",
				"name" 		=> "form_bg_option",
				"opts"		=> array(
					"title" 		=> __( "Form Background Styling", "smile" ),
					"value" 		=> "",
				),
				"panel" => "Form Designer",
				"section" => "Design",
				"section_icon" => "connects-icon-disc",
			),
			array(
				"type" 		=> "colorpicker",
				"class" 	=> "",
				"name" 		=> "form_bg_color",
				"opts"		=> array(
					"title" 		=> __( "Form Background Color", "smile" ),
					"value" 		=> "rgba(46, 46, 46, 0.41)",
					"css_property" => "background-color",
					"css_selector" => ".cp-form-container",
				),
				"panel" => "Form Designer",
				"section" => "Design",
				"section_icon" => "connects-icon-disc",
			),
			array(
				"type" 		=> "colorpicker",
				"class" 	=> "",
				"name" 		=> "form_border_color",
				"opts"		=> array(
					"title" 		=> __( "Form Border Color", "smile" ),
					"value" 		=> "#fff",
					"css_property" => "border-color",
					"css_selector" => ".cp-form-container ,.cp-locked-content .cp-form-container",
					"css_preview" 	=> true,
				),
				"panel" => "Form Designer",
				"section" => "Design",
				"section_icon" => "connects-icon-disc",
			),
	);

 //for special offer title background color option
	$title_bg_color = array(
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "modal_title_bg_color",
			"opts"		=> array(
				"title" 		=> __( "Title Background Color", "smile" ),
				"css_selector" => ".cp-instant-coupon .cp-description",
				"css_property" => "background-color",
				"value" 		=> "rgb(225, 225, 225)",
				"description" 	=> __( "Choose the background color for modal title area.", "smile" ),
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
			"name" 		=> "modal_desc_bg_color",
			"opts"		=> array(
				"title" 		=> __( "Description Background Color", "smile" ),
				"value" 		=> "rgba(230, 145, 56, 0.4)",
				"css_selector" => ".cp-free-ebook .cp-short-desc-container, .cp-free-ebook-container .cp-all-inputs-wrap",
				"css_property" => "background-color",
				"description" 	=> __( "Choose the background color for modal description area.", "smile" ),
			),
			"panel" 	=> "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
	);

	// YouTube CTA
	$video = array(
		//	Design - Video
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "video_id",
			"opts"		=> array(
				"title" 		=> __( "Video ID", "smile" ),
				"value" 		=> "YE7VzlLtp-4",
				"description" 	=> __( "Enter YouTube video ID you will find in the url. e.g. If video url is <em>youtube.com/watch?v=P5yHEKqx86U</em> then 'P5yHEKqx86U' is the video ID.", "smile" ),
			),
			"panel" 	=> "Video",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "player_autoplay",
			"opts"		=> array(
				"title" 	=> __( "Autoplay YouTube video.", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description"=> __( "This will Autoplay the video on modal show.", "smile" ),
			),
			"panel" 	=> "Video",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "video_start",
			"opts"			=> array(
				"title" 		=> __( "Video Start Time", "smile" ),
				"description" 	=> __( "You will change the start time of YouTube video.", "smile" ),
				"value" 		=> 0,
				"min" 			=> 0,
				"max" 			=> 5000,
				"step" 			=> 1,
				"suffix" 		=> "s",
			),
			"panel" 	=> "Video",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "player_actions",
			"opts"		=> array(
				"title" 	=> __( "Show Video Title And Player Actions", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description"=> __( "This will display / hide the player title bar and actions.", "smile" ),
			),
			"panel" 	=> "Video",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "player_controls",
			"opts"		=> array(
				"title" 	=> __( "Show Player Controls", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description"=> __( "Would you like to display player controls in the video?.", "smile" ),
			),
			"panel" 	=> "Video",
			"section" => "Design",
			"section_icon" => "connects-icon-image"
		),
	);

	//	'CTA' for YouTube
	$cta = array(
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "cta_switch",
			"opts"		=> array(
				"title" 	=> __( "Enable Form", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
			),
			"panel" => "Form Designer",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "cta_delay",
			"opts"			=> array(
				"title" 		=> __( "CTA Delay", "smile" ),
				"description" 	=> __( "Amount of time to pass (in seconds) after CTA appears.", "smile" ),
				"value" 		=> 0,
				"min" 			=> 0,
				"max" 			=> 1200,
				"step" 			=> 1,
				"suffix" 		=> "s"
			),
			"panel" => "Form Designer",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
			"dependency" => array('name' => 'cta_switch', 'operator' => '==', 'value' => true ),
		),

	);

	$style_height = array(
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "cp_custom_height",
			"opts"		=> array(
				"title" 	=> __( "Modal Height", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "CUSTOM", "smile" ),
				"off"		=> __( "AUTO", "smile" ),
				"description"=> __( "Provide custom height of your choice to this modal .", "smile" ),
			),
			"panel" 	=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 			=> "slider",
			"class" 		=> "",
			"name" 			=> "cp_modal_height",
			"opts"			=> array(
				"title" 		=> __( "Change Height", "smile" ),
				"value" 		=> 300,
				"min" 			=> 50,
				"max" 			=> 1200,
				"step" 			=> 1,
				"suffix" 		=> "px",
			),
			"panel" 		=> "Advance Design Options",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'cp_custom_height', 'operator' => '==', 'value' => 'true'),

		),
	);


 //background color for countdown
 $cont_down_bg = array(
		array(
				"type" 		=> "colorpicker",
				"class" 	=> "",
				"name" 		=> "counter_container_bg_color",
				"opts"		=> array(
					"title" 		=> __( "Description Background Color", "smile" ),
					"value" 		=> "#24859C",
					"css_property" => "background-color",
					"css_selector" => ".counter-overlay",
				),
				"panel" 	=> "Background",
				"section" => "Design",
				"section_icon" => "connects-icon-image"
			)
	);


$modal_layout = array(
        array(
            "type"  =>  "radio-image",
            "name"  =>  "modal_layout",
            "opts"  =>  array(
                "title" => __( "", "smile" ),
                "value" => 'form_left',
                "width" => '80px',
                "options" => array(
                    "form_left"             => CP_BASE_URL . 'modules/modal/assets/demos/jugaad/img/modal-layout01.jpg',
                    "form_right"            => CP_BASE_URL . 'modules/modal/assets/demos/jugaad/img/modal-layout02.jpg',
                    "form_left_img_top"     => CP_BASE_URL . 'modules/modal/assets/demos/jugaad/img/modal-layout03.jpg',
                    "form_right_img_top"    => CP_BASE_URL . 'modules/modal/assets/demos/jugaad/img/modal-layout04.jpg',
                    "img_left_form_bottom"  => CP_BASE_URL . 'modules/modal/assets/demos/jugaad/img/modal-layout05.jpg',
                    "img_right_form_bottom" => CP_BASE_URL . 'modules/modal/assets/demos/jugaad/img/modal-layout06.jpg',
                    "form_bottom_img_top"   => CP_BASE_URL . 'modules/modal/assets/demos/jugaad/img/modal-layout07.jpg',
                    "form_bottom"           => CP_BASE_URL . 'modules/modal/assets/demos/jugaad/img/modal-layout08.jpg',
                ),
				"imagetitle" => array(
					__( "title-0", "smile" ) 	=> "Form At Left Without Image",
					__( "title-1", "smile" ) 	=> "Form At Right Without Image",
					__( "title-2", "smile" ) 	=> "Form At Left With Image At Right",
					__( "title-3", "smile" ) 	=> "Form At Right With Image At Left",
					__( "title-4", "smile" ) 	=> "Form At Bottom With Image At Left",
					__( "title-5", "smile" ) 	=> "Form At Bottom With Image At Right",
					__( "title-6", "smile" ) 	=> "Form At Bottom With Image At top",
					__( "title-7", "smile" ) 	=> "Form At Bottom Without Image"
				),
            ),
            "panel" => "Modal Layout",
            "section" => "Design",
            "section_icon" => "connects-icon-disc",
        ),
		array(
            "type"      => "switch",
            "class"     => "",
            "name"      => "modal_col_width",
            "opts"      => array(
                "title"     => __( "Form Column Width", "smile" ),
                "value"     => true,
                "on"        => __( "1/3", "smile" ),
                "off"       => __( "1/2", "smile" ),
                "description"   => __( "Select column width", "smile" ),
            ),
            "panel"      => "Modal Layout",
            "section" => "Design",
            "section_icon" => "connects-icon-disc"
        ),
        array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "modal_img_src",
			"opts" 		=> array(
				"title" 	=> __( "Image source","smile"),
				"value" 	=> "upload_img",
				"options" 	=> array(
						__( "Custom URL", "smile" ) 	 => "custom_url",
						__( "Upload Image", "smile" ) 	 => "upload_img",
						__( "None", "smile" ) 	 		 => "none",
					)
				),
			"panel" => "Modal Layout",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "modal_img_custom_url",
			"opts"		=> array(
				"title" 		=> __( "Custom URL", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "Enter custom URL for your image.", "smile" ),
			),
			"panel" 	=> "Modal Layout",
			//"dependency" => array('name' => 'modal_img_src', 'operator' => '==', 'value' => 'custom_url'),
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
		array(
			"type" 		=> "media",
			"class" 	=> "",
			"name" 		=> "modal_image",
			"opts"		=> array(
				"title" 		=> __( "Upload Image", "smile" ),
				"value" 		=> plugins_url('config/img/default-image.png', __FILE__ ),
				"description" 	=> __( "Upload an image that will be displayed inside the content area.Image size will not bigger than its container.", "smile" ),
			),
			"panel" 	 => "Modal Layout",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
			// "dependency" => array('name' => 'modal_img_src', 'operator' => '==', 'value' => 'upload_img')
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
			"panel" 	 => "Modal Layout",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
			// "dependency" => array('name' => 'modal_img_src', 'operator' => '!=', 'value' => 'none')
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
			"panel" 	 => "Modal Layout",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
			// "dependency" => array('name' => 'modal_img_src', 'operator' => '!=', 'value' => 'none')
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
			"panel" 	 => "Modal Layout",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
			// "dependency" => array('name' => 'modal_img_src', 'operator' => '!=', 'value' => 'none')
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
			"panel" 	 => "Modal Layout",
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
			// "dependency" => array('name' => 'modal_img_src', 'operator' => '!=', 'value' => 'none')
		),
        array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "modal_short_title",
			"opts"		=> array(
				"title" 		=> __( "Short Title", "smile" ),
				"value" 		=> __( "Subscribe", "smile" ),
				"description" 	=> __( "Enter the main short title.", "smile" ),
			),
			"panel" 	 => "Name",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "modal_note_1",
			"opts"		=> array(
				"title" 		=> __( "Short note", "smile" ),
				"value" 		=> __( "We respect your privacy.", "smile" ),
				"description" 	=> __( "Enter the note.", "smile" ),
			),
			"panel" 	 => "Name",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
		array(
			"type" 		=> "textfield",
			"class" 	=> "",
			"name" 		=> "modal_note_2",
			"opts"		=> array(
				"title" 		=> __( "Short note 2", "smile" ),
				"value" 		=> __( "<super>*</super>Terms and conditions apply.", "smile" ),
				"description" 	=> __( "Enter the note 2.", "smile" ),
			),
			"panel" 	 => "Name",
			"dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
			"section" => "Design",
			"section_icon" => "connects-icon-disc",
		),
    );

    $form_separator = array(
        array(
            "type"      => "dropdown",
            "class"     => "",
            "name"      => "form_separator",
            "opts"      => array(
                "title"     => __( "Form Separator","smile"),
                "value"     => "none",
                "options"   => array(
                        __( "None", "smile" )               => "none",
                        __( "Triangle", "smile" )           => "triangle",
                        __( "Clouds", "smile" )             => "clouds",
                        __( "Round Split", "smile" )        => "round_split"
                    )
                ),
            "panel" => "Form Separator",
            "section" => "Design",
            "section_icon" => "connects-icon-disc"
        ),
        array(
            "type"      => "switch",
            "class"     => "",
            "name"      => "form_sep_part_of",
            "opts"      => array(
                "title"     => __( "Part Of", "smile" ),
                "value"     => true,
                "on"        => "FORM",
                "off"       => "CONTENT",
            ),
            "dependency" => array('name' => 'form_separator', 'operator' => '!==', 'value' => 'none'),
            "panel"      => "Form Separator",
            "section" => "Design",
            "section_icon" => "connects-icon-image",
        )
    );

    $jugaad_background = array(
        array(
            "type"      => "colorpicker",
            "class"     => "",
            "name"      => "modal_title_color",
            "opts"      => array(
                "title"         => __( "Modal Title Color", "smile" ),
                "value"         => "#333333",
                "description"   => __( "Select the title text color.", "smile" ),
            ),
            "panel"     => "Background",
            "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
            "section" => "Design",
            "section_icon" => "connects-icon-image"
        ),
        array(
            "type"      => "colorpicker",
            "class"     => "",
            "name"      => "modal_desc_color",
            "opts"      => array(
                "title"         => __( "Description Color", "smile" ),
                "value"         => "#333333",
                "description"   => __( "Select the description text color.", "smile" ),
            ),
            "panel"     => "Background",
            "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
            "section" => "Design",
            "section_icon" => "connects-icon-image"
        ),
        array(
            "type"      => "colorpicker",
            "class"     => "",
            "name"      => "tip_color",
            "opts"      => array(
                "title"         => __( "Notice / Tip Color", "smile" ),
                "value"         => "rgb(250,250,250)",
                "description"   => __( "Select the text color for Notice / Tip under the form.", "smile" ),
            ),
            "panel"     => "Background",
            "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
            "section" => "Design",
            "section_icon" => "connects-icon-image"
        ),
        array(
            "type"      => "colorpicker",
            "class"     => "",
            "name"      => "modal_overlay_bg_color",
            "opts"      => array(
                "title"         => __( "Overlay Color", "smile" ),
                "css_property" 	=> "background",
				"css_selector" 	=> ".cp-overlay",
                "value"         => "rgba(0, 0, 0, 0.71)",
                "description"   => __( "Provide the overlay color that appears behind modal box area.", "smile" ),
            ),
            "panel"     => "Background",
            "section" => "Design",
            "section_icon" => "connects-icon-image"
        ),
        array(
            "type"      => "section",
            "class"     => "",
            "name"      => "form_background_section_title",
            "opts"      => array(
                "title"         => __( "Form Background", "smile" ),
                "value"         => ""
            ),
            "panel"     => "Background",
            "section" => "Design",
            "section_icon" => "connects-icon-image"
        ),
        array(
            "type"      => "colorpicker",
            "class"     => "",
            "name"      => "form_bg_color",
            "opts"      => array(
                "title"         => __( "Background Color", "smile" ),
                "value"         => "rgb(225, 225, 225)",
                "description"   => __( "Choose the background color for form", "smile" ),
                "css_property" => "background-color",
				"css_selector" => ".cp-form-section-overlay",
            ),
            "panel"     => "Background",
            "section" => "Design",
            "section_icon" => "connects-icon-image"
        ),
        array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "form_bg_image_src",
			"opts" 		=> array(
				"title" 	=> __( "Image source","smile"),
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
			"name" 		=> "form_bg_image_custom_url",
			"opts"		=> array(
				"title" 		=> __( "Custom URL", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "Enter custom URL for your image.", "smile" ),
			),
			"panel" 	=> "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'form_bg_image_src', 'operator' => '==', 'value' => 'custom_url')
		),
        array(
            "type"      => "media",
            "class"     => "",
            "name"      => "form_bg_image",
            "opts"      => array(
                "title"         => __( "Background Image", "smile" ),
                "value"         => "",
                "description"   => __( "You can provide an image that would be appear behind the form in the modal box area. For this setting to work, the background color you've chosen must be transparent.", "smile" ),
            ),
            "panel"      => "Background",
            "section" => "Design",
            "section_icon" => "connects-icon-image",
            "dependency" => array('name' => 'form_bg_image_src', 'operator' => '==', 'value' => 'upload_img')
        ),
        array(
            "type"      => "background",
            "class"     => "",
            "name"      => "form_opt_bg",
            "opts"      => array(
                "title"         => "",
                "value"         => "no-repeat|center|cover"
            ),
            "panel" => "Background",
            "section" => "Design",
            "section_icon" => "connects-icon-image",
            "dependency" => array('name' => 'form_bg_image', 'operator' => '!==', 'value' => '', 'type' => 'media'),
        ),
        array(
            "type"      => "section",
            "class"     => "",
            "name"      => "content_background_section_title",
            "opts"      => array(
                "title"         => __( "Content Background", "smile" ),
                "value"         => "",
            ),
            "panel"     => "Background",
            "section" => "Design",
            "section_icon" => "connects-icon-image"
        ),
        array(
            "type"      => "colorpicker",
            "class"     => "",
            "name"      => "content_bg_color",
            "opts"      => array(
                "title"         => __( "Background Color", "smile" ),
                "value"         => "rgb(255, 255, 255)",
                "description"   => __( "Choose the background color for content", "smile" ),
                "css_property" => "background-color",
				"css_selector" => ".cp-content-section-overlay",
            ),
            "panel"     => "Background",
            "section" => "Design",
            "section_icon" => "connects-icon-image"
        ),
        array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "content_bg_image_src",
			"opts" 		=> array(
				"title" 	=> __( "Image source","smile"),
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
			"name" 		=> "content_bg_image_custom_url",
			"opts"		=> array(
				"title" 		=> __( "Custom URL", "smile" ),
				"value" 		=> "",
				"description" 	=> __( "Enter custom URL for your image.", "smile" ),
			),
			"panel" 	=> "Background",
			"section" => "Design",
			"section_icon" => "connects-icon-image",
			"dependency" => array('name' => 'content_bg_image_src', 'operator' => '==', 'value' => 'custom_url')
		),
        array(
            "type"      => "media",
            "class"     => "",
            "name"      => "content_bg_image",
            "opts"      => array(
                "title"         => __( "Background Image", "smile" ),
                "value"         => "",
                "description"   => __( "You can provide an image that would be appear behind the content in the modal box area. For this setting to work, the background color you've chosen must be transparent.", "smile" ),
            ),
            "panel"      => "Background",
            "section" => "Design",
            "section_icon" => "connects-icon-image",
            "dependency" => array('name' => 'content_bg_image_src', 'operator' => '==', 'value' => 'upload_img')
        ),
        array(
            "type"      => "background",
            "class"     => "",
            "name"      => "content_opt_bg",
            "opts"      => array(
                "title"         => "",
                "value"         => "no-repeat|center|cover"
            ),
            "panel" => "Background",
            "section" => "Design",
            "section_icon" => "connects-icon-image",
            "dependency" => array('name' => 'content_bg_image', 'operator' => '!==', 'value' => '', 'type' => 'media'),
        )
    );

	/**
	 * Setup the option array for `customizer` for individual modal popup
	 *
	 * @since 0.2.1
	 */

	// blank theme
	smile_update_options( "Smile_Modals", "blank",
		array_merge(
			$name,
			$secondary_title,
			$background,
			$close_link,
			$animations,
			$style_height,
			$adv_design_options,
			$behavior
		)
	);

	// optin to win
	smile_update_options( "Smile_Modals", "optin_to_win",
		array_merge(
			$name,
			$secondary_title,
			$background,
			$cp_form,
			$modal_img,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);

	// webinar
	smile_update_options( "Smile_Modals", "webinar",
		array_merge(
			$name,
			$background,
			$cp_form,
			$form_bg_color,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);

	//	YouTube
	smile_update_options( "Smile_Modals", "YouTube",
		array_merge(
			$name,
			$video,
			$background,
			$cta,
			$cp_form,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);

	// Every design
	smile_update_options( "Smile_Modals", "every_design",
		array_merge(
			$name,
			$background,
			$cp_form,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);

	//	Flat Discount
	smile_update_options( "Smile_Modals", "flat_discount",
		array_merge(
			$name,
			$background,
			$cp_form,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);

	//special offer
	smile_update_options( "Smile_Modals", "special_offer",
		array_merge(
			$name,
			$title_bg_color,
			$background,
			$cp_form,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);

	//first_order
	smile_update_options( "Smile_Modals", "first_order",
		array_merge(
			$name,
			$background,
			$cp_form,
			$modal_img,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);

	//exclusive offer
	smile_update_options( "Smile_Modals", "locked_content",
		array_merge(
			$name,
			$background,
			$cp_form,
			$modal_img,
			$form_bg_color,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);

	//first_order with text only
	smile_update_options( "Smile_Modals", "first_order_2",
		array_merge(
			$name,
			$background,
			$cp_form,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);

	// direct download
	smile_update_options( "Smile_Modals", "direct_download",
		array_merge(
			$name,
			$background,
			$cp_form,
			$modal_img,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);

	//instant coupon
	smile_update_options( "Smile_Modals", "instant_coupon",
		array_merge(
			$name,
			$title_bg_color,
			$background,
			$cp_form,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);

	// free ebook
	smile_update_options( "Smile_Modals", "free_ebook",
		array_merge(
			$name,
			$background,
			$cp_form,
			$desc_bg_color,
			$modal_img,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);


	// countdown
	smile_update_options( "Smile_Modals", "countdown",
		array_merge(
			$name,
			$background,
			$cont_down_bg,
			$cp_count_down,
			$form_bg_color,
			$cp_form,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);

	// social media theme
	smile_update_options( "Smile_Modals", "social_media",
		array_merge(
			$name,
			$secondary_title,
			$background,
			$cp_social,
			$close_link,
			$animations,
			$style_height,
			$adv_design_options,
			$behavior
		)
	);

	smile_update_options( "Smile_Modals", "jugaad",
            array_merge(
                $name,
                $modal_layout,
                $jugaad_background,
                $cp_form,
                $form_separator,
                $close_link,
                $animations,
                $adv_design_options,
                $behavior,
                $submission
            )
    );


    // social Inline Share theme
	smile_update_options( "Smile_Modals", "social_inline_share",
		array_merge(
			$name,
			$secondary_title,
			$background,
			$cp_social,
			$close_link,
			$animations,
			$style_height,
			$adv_design_options,
			$behavior
		)
	);

	// Social Widget Box
	smile_update_options( "Smile_Modals", "social_widget_box",
		array_merge(
			$name,
			$secondary_title,
			$background,
			$cp_social,
			$close_link,
			$animations,
			$style_height,
			$adv_design_options,
			$behavior
		)
	);


	// social_article
	smile_update_options( "Smile_Modals", "social_article",
		array_merge(
			$name,
			$secondary_title,
			$background,
			$cp_social,
			$close_link,
			$animations,
			$style_height,
			$adv_design_options,
			$behavior
		)
	);

	// social_media_with_form
	smile_update_options( "Smile_Modals", "social_media_with_form",
		array_merge(
			$name,
			$background,
			$cp_form,
			$cp_social,
			//$modal_img,
			$form_bg_color,
			$close_link,
			$animations,
			$adv_design_options,
			$behavior,
			$submission
		)
	);
}


/**
 * Update the option array of all individual modal popup
 *
 * @since 0.2.1
 */

if( function_exists( "smile_update_default" ) ){

	// Blank
	$blank_default = array(
		'box_shadow' 		=> '',
		'modal_title1'     	=> __( "BLANK style is purely built for customization. This style supports text, images, shortcodes, HTML etc. Use Source button from Rich Text Editor toolbar & customize your Modal effectively." , "smile" ),
		'border'			=> "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgb(0,0,0)|bw_all:5|bw_t:5|bw_l:5|bw_r:5|bw_b:5",
		"cp_modal_width"    => "700",
		"modal_bg_color"    => "rgb(255, 255, 255)",
	);
	foreach( $blank_default as $option => $value ) {
		smile_update_default( "Smile_Modals", "blank", $option, $value );
	}

	// YouTube
	$optin_default = array(
		"form_fields" 			=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			=> "cp-form-layout-3",
		"form_input_align"		=> "left",
		"form_submit_align" 	 => "cp-submit-wrap-full",
		'cp_modal_width' 		=> '700',
		'button_title'      	=> 'Sign Up',
		'placeholder_text' 		=> __( "Enter Your Email Address", "smile" ),
		'namefield'				=> false,
		'btn_disp_next_line'	=> false,
		'box_shadow'			=> 'type:outset|horizontal:0|vertical:0|blur:5|spread:0|color:rgba(86,86,131,0)',
	);
	foreach( $optin_default as $option => $value ){
		smile_update_default( "Smile_Modals", "YouTube", $option, $value );
	}

	//	Optin to Win
	$optin_to_win_defaults = array(
		"form_fields" 			=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			=> "cp-form-layout-3",
		"form_input_align"		=> "left",
		"form_submit_align" 	 => "cp-submit-wrap-full",
		"form_grid_structure"	=> "cp-form-grid-structure-2",
		"submit_button_tb_padding" => 12,
		'btn_disp_next_line'	=> false,
		'image_size'            => 220,
		'cp_modal_width'        => '750',
		'border'                => 'br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgb(255,255, 255)|bw_all:5|bw_t:5|bw_l:5|bw_r:5|bw_b:5'
	);
	foreach( $optin_to_win_defaults as $option => $value ){
		smile_update_default( "Smile_Modals", "optin_to_win", $option, $value );
	}

	// Webinar
	$webinar_default = array(
		"form_fields" 				=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"				=> "cp-form-layout-3",
		"form_input_align"			=> "left",
		"submit_button_tb_padding" 	=> 10,
		"submit_button_lr_padding" 	=> 15,
		"form_submit_align" 		=> "cp-submit-wrap-full",
		"form_grid_structure"		=> "cp-form-grid-structure-2",
		"overlay_effect" 	 		=> "smile-3DRotateBottom",
		"modal_short_desc1"  		=> '<span style=" font-style:italic">'.__( "Register for a FREE Webinar!" , "smile" ).'</span>',
		"modal_title1"		 		=> __( "Learn Art of Conversion Optimization and Science Behind It.", "smile" ),
		"modal_confidential" 		=> "",
		"modal_content" 	 		=> __( "TODAY – 8.00 PM to 8.35 PM (EST)", "smile" ),
		"modal_bg_color"     		=> "rgb(52, 73, 94)",
		"modal_overlay_bg_color" 	=> "rgba(0, 0, 0, 0.7)",
		"button_title"       		=> __( "JOIN US TONIGHT!", "smile" ),
		"button_bg_color"    		=> "rgb(243, 156, 18)",
		"button_border_color"		=> "rgba(243, 156, 18, 0)",
		"cp_modal_width"     		=> "670",
		"border" 			 		=> "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:rgb(255,255,255)|bw_all:1|bw_t:1|bw_l:1|bw_r:1|bw_b:1",
		"btn_disp_next_line" 		=> false,
		"close_position"     		=> "out_modal",
		"modal_title_color"  		=> "rgb(250, 250, 255)",
		"modal_desc_color" 	 		=> "rgb(250, 250, 250)",
		"tip_color"          		=> "rgb(250, 250, 250)",
		"placeholder_text"   		=> __( "Enter Your Email Here", "smile" ),
		"name_text"  		 		=> __( "Enter Your Name", "smile" ),
		"placeholder_font"   		=> "Lato",
	);
	foreach( $webinar_default as $option => $value ){
		smile_update_default( "Smile_Modals", "webinar", $option, $value );
	}

	//	Every Design
	$every_design_default = array(
		"form_fields" 			=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			=> "cp-form-layout-3",
		"form_input_align"		=> "left",
		"form_submit_align" 	=> "cp-submit-wrap-full",
		"form_grid_structure"	=> "cp-form-grid-structure-2",
		"modal_title1"   		=> __( "Subscribe To Newsletter", "smile" ),
		"modal_content"		=> __( "DON&rsquo;T MISS OUT!", "smile" ),
		"modal_confidential" 	=> __( "Give it a try, you can unsubscribe anytime.", "smile" ),
		"modal_short_desc1" 	=> '<span style="font-style:italic">'.__( "Be the first to get latest updates and exclusive content straight to your email inbox.",  "smile" ).'</span>',
		"modal_bg_color" 		=> "rgb(255, 255, 255)",
		"button_title"			=> __( "Stay Updated", "smile" ),
		"button_bg_color" 		=> "rgb(79, 197, 166)",
		"button_border_color"	=> "rgb(52, 152, 219)",
		"cp_modal_width" 		=> "670",
		"border" 				=> "br_all:10|br_tl:10|br_tr:10|br_br:10|br_bl:10|style:none|color:rgb(0, 0, 0)|bw_all:3|bw_t:3|bw_l:3|bw_r:3|bw_b:3",
		"overlay_effect" 		=> "smile-3DRotateBottom",
		"modal_overlay_bg_color"=> "rgba(0, 0, 0, 0.71)",
		"modal_title_color" 	=> "rgb(104, 104, 104)",
		"modal_desc_color" 		=> "rgb(103, 103, 103)",
		"tip_color" 			=> "rgb(68, 68, 68)",
		"placeholder_text"   	=> __( "Enter your email address", "smile" ),
		"name_text"  		 	=> __( "Enter Your Name",  "smile" ),
		"placeholder_font"   	=> "Palatino Linotype",
		"btn_border_radius"		=> 4,
		"btn_disp_next_line"    => false,
		"form_input_font" 		=> 'Lato',

	);
	foreach( $every_design_default as $option => $value ){
		smile_update_default( "Smile_Modals", "every_design", $option, $value );
	}

	// Flat Discount
	$flat_discount_default = array(
		"form_fields" 			=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			=> "cp-form-layout-3",
		"form_input_align"		=> "left",
		"form_submit_align" 	=> "cp-submit-wrap-full",
		"form_grid_structure"	=> "cp-form-grid-structure-2",
		"modal_bg_color"   		=> "rgb(255, 255, 255)",
		"modal_title1"      	=> __( "Flat 20% Off Today!", "smile" ),
		"modal_confidential" 	=> __( "Hurry up! Offer valid till stocks last.", "smile" ),
		"modal_short_desc1" 	=> __( "Dramatically maintain clicks-and-mortar solutions without functional errors.", "smile" ),
		"border"           		=> "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:dashed|color:rgb(44, 62, 80)|bw_all:3|bw_t:3|bw_l:3|bw_r:3|bw_b:3",
		"button_title"     		=> '<span data-font-size="18px" class="cp_responsive cp_font" style="font-size:18px;">'.__( "AVAIL NOW",  "smile" ).'</span>',
		"placeholder_text"      => __( "Enter Your Email", "smile" ),
		"placeholder_font"      => "Roboto",
		"button_bg_color"  		=> "rgb(3, 177, 133)",
		"button_border_color" 	=> "rgb(3, 177, 133)",
		"modal_title_color" 	=> "#2B3D4F",
		"modal_desc_color"  	=> "#3F4E5C",
		"cp_modal_width"        => "625",
		"tip_color"             => "#78828C",
		"placeholder_color"     => "rgb(153, 153, 153)",
		"input_border_color"    => "rgb(102, 102, 102)",
		"btn_disp_next_line"    => false

	);
	foreach( $flat_discount_default as $option => $value ){
		smile_update_default( "Smile_Modals", "flat_discount", $option, $value );
	}

	// Special Offer
	$special_offer_default = array(
		"form_fields" 			=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			=> "cp-form-layout-1",
		"form_input_align"		=> "center",
		"form_submit_align" 	=> "cp-submit-wrap-full",
		"modal_short_desc1"  	 => __( "We&rsquo;ve a Special Offer today!", "smile" ),
		"modal_content" 	 	 => __( "on all electronic & mobile devices.", "smile" ),
		"modal_confidential" 	 => __( "Hurry up! Offer valid till stock last.", "smile" ),
		"modal_title1"  	 	 => __( "FLAT 50% OFF", "smile" ),
		"modal_bg_color"     	 => "rgb(255, 255, 255)",
		"button_title"       	 => __( "GET COUPON", "smile" ),
		"button_bg_color"    	 => "rgb(237, 107, 12)",
		"button_border_color" 	 => "rgb(237, 107, 12)",
		"modal_overlay_bg_color" 	 => "rgba(0, 0, 0, 0.7)",
		"affiliate_title"	 	 => __( "Powered by ConvertPlug<sup>&trade;</sup>", "smile" ),
		"cp_modal_width"     	 => "750",
		"border"             	 => "br_type:0|br_all:10|br_tl:10|br_tr:10|br_br:10|br_bl:10|style:none|color:rgb(255, 255, 255)|bw_type:0|bw_all:3|bw_t:3|bw_l:3|bw_r:3|bw_b:3",
		"overlay_effect"     	 => "smile-3DRotateBottom",
		"close_text_color" 	 	 => "rgb(68, 68, 68)",
		"input_border_color" 	 => "rgb(0, 0, 0)",
		"modal_title_color"  	 => "rgb(51, 51, 51)",
		"modal_desc_color" 	 	 => "rgb(51, 51, 51)",
		"tip_color"          	 => "rgb(51, 51, 51)",
		"font_family"		 	 => "Roboto",
		"placeholder_text"   	 => __( "Enter Your Email Address", "smile" ),
		"btn_style" 		 	 => "cp-btn-gradient",
		"btn_border_radius"  	 => 10,

	);
	foreach( $special_offer_default as $option => $value ){
		smile_update_default( "Smile_Modals", "special_offer", $option, $value );
	}

	// First Order
	$first_order_default = array(
		"form_fields" 			=> "order->0|input_type->textfield|input_label->Name|input_name->name|input_placeholder->Enter Your Name|input_require->true;order->1|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			=> "cp-form-layout-4",
		"form_input_align"		=> "left",
		"form_submit_align" 	=> "cp-submit-wrap-full",
		"submit_button_tb_padding" => 10,
		"submit_button_lr_padding" => 15,
		"form_input_align"		 => "left",
		"form_submit_align" 	 => "cp-submit-wrap-full",
		"modal_title1"  	 => __( "FLAT 30% OFF", "smile" ),
		"modal_content" 	 => __( "Coupon Code - GET30", "smile" ),
		"modal_confidential" => __( "* Terms & Conditions Apply", "smile" ),
		"modal_short_desc1"  => __( "FLAT 30% OFF", "smile" ),
		"modal_bg_color"     => "rgb(255, 255, 255)",
		"button_title"       => __( "SHOP NOW", "smile" ),
		"button_bg_color"    => "rgb(219, 109, 44)",
		"button_border_color" => "rgb(219, 109, 44)",
		"cp_modal_width"     => "450",
		"border"             => "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgb(0,0, 0)|bw_all:5|bw_t:5|bw_l:5|bw_r:5|bw_b:5",
		"overlay_effect"     => "smile-pulse",
		"modal_title_color"  => "rgb(68, 68, 68)",
		"modal_desc_color" 	 => "rgb(68, 68, 68)",
		"tip_color"          => "rgb(68, 68, 68)",
		"modal_image"		 => plugins_url('config/img/fisrt_logo.png', __FILE__ ),
		"image_size"		 => "205",
		"image_displayon_mobile" => false,

	);
	foreach( $first_order_default as $option => $value ){
		smile_update_default( "Smile_Modals", "first_order", $option, $value );
	}

	// Locked Content
	$locked_content_default = array(
		"form_fields" 			=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			=> "cp-form-layout-1",
		"form_input_align"		=> "center",
		"submit_button_tb_padding" => 10,
		"submit_button_lr_padding" => 10,
		"form_submit_align" 	=> "cp-submit-wrap-full",
		"modal_title1"  	 	 => __( "Premium Content Locked!", "smile" ),
		"modal_confidential" 	 => "",
		"modal_short_desc1"  	 => __( "Enter Your Email Below to Unlock Your Exclusive Content", "smile" ),
		"modal_bg_color"     	 => "rgb(250, 250, 250)",
		"button_title"       	 => __( "Get Instant Access", "smile" ),
		"button_bg_color"    	 => "rgb(102, 0, 0)",
		"button_border_color" 	 => "rgb(106, 0, 0)",
		"modal_overlay_bg_color" => "rgba(0, 0, 0, 0.9)",
		"cp_modal_width"     	 => "700",
		"border"                 => "br_all:5|br_tl:5|br_tr:5|br_br:5|br_bl:5|style:none|color:rgb(0,0, 0)|bw_all:2|bw_t:2|bw_l:2|bw_r:2|bw_b:2",
		"overlay_effect"     	 => "smile-swing",
		"close_text_color" 	 	 => "rgb(68, 68, 68)",
		"modal_title_color"  	 => "rgb(106, 0, 0)",
		"modal_desc_color" 	 	 => "rgb(68, 68, 68)",
		"tip_color"           	 => "rgb(106, 0, 0)",
		'form_border_color'  	 => 'rgb(204, 204, 204)',
		'form_bg_color' 	 	 => 'rgb(238, 238, 238)',
		"btn_border_radius"	 	 => 3,
		"font_family"		 	 => "Bitter",
		"close_modal" 		 	 => "do_not_close",
		"modal_image"		 	 =>  plugins_url('config/img/content_locker.png', __FILE__ ),
		"image_size"		 	 => "100",
		"image_displayon_mobile" => false,
		"placeholder_text"       => __( "Enter Your Email Here", "smile" ),
		"btn_style" 		 	 => "cp-btn-gradient",
		"btn_shadow" 			 => true,
		"input_shadow"			 => true,
	);
	foreach( $locked_content_default as $option => $value ){
		smile_update_default( "Smile_Modals", "locked_content", $option, $value );
	}

	// First Order 2
	$first_order_2_default = array(
		"form_fields" 			=> "order->0|input_type->textfield|input_label->Name|input_name->name|input_placeholder->Enter Your Name|input_require->true;order->1|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			=> "cp-form-layout-4",
		"form_input_align"		=> "left",
		"form_submit_align" 	=> "cp-submit-wrap-full",
		"submit_button_tb_padding" => 15,
		"submit_button_lr_padding" => 15,
		"modal_short_desc1"   => __( "Independence Day Sale", "smile" ),
		"modal_content" 	  => __( "Coupon Code - GET30", "smile" ),
		"modal_confidential"  => __( "* Terms & Conditions Apply", "smile" ),
		"modal_title1"  	  => __( "FLAT 30% OFF","smile" ),
		"modal_bg_color"      => "rgb(255, 255, 255)",
		"button_title"        => __( "SHOP NOW", "smile" ),
		"button_bg_color"     => "rgb(228, 68, 129)",
		"button_border_color" => "rgba(195, 0, 73, 0.5)",
		"cp_modal_width"      => "450",
		"border"              => "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgb(0,0, 0)|bw_all:5|bw_t:5|bw_l:5|bw_r:5|bw_b:5",
		"overlay_effect"      => "smile-pulse",
		"modal_title_color"   => "rgb(68, 68, 68)",
		"modal_desc_color" 	  => "rgb(68, 68, 68)",
		"tip_color"           => "rgb(68, 68, 68)",

	);
	foreach( $first_order_2_default as $option => $value ){
		smile_update_default( "Smile_Modals", "first_order_2", $option, $value );
	}

	// Direct Download
	$direct_download_default = array(
		"form_fields" 				=> "order->0|input_type->textfield|input_label->Name|input_name->name|input_placeholder->Enter Your Name|input_require->true;order->1|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			 	=> "cp-form-layout-4",
		"form_input_align"		 	=> "left",
		"form_submit_align" 	 	=> "cp-submit-wrap-left",
		"modal_title1"  	  		=> __( "How to Convert Traffic into Leads with ConvertPlug", "smile" ),
		"modal_content" 	  		=> __( "Seperate CRO facts from fiction & stop wasting your time on outdated strategies that do not work.", "smile" ),
		"modal_confidential"  		=> "",
		"modal_short_desc1"   		=> __( "FEATURED DOWNLOAD -", "smile" ),
		"modal_bg_color"      		=> "rgb(255, 255, 255)",
		"button_title"        		=> __( "DOWNLOAD NOW", "smile" ),
		"button_bg_color"     		=> "rgb(44, 62, 80)",
		"button_border_color" 		=> "rgb(255, 255, 255)",
		"cp_modal_width"      		=> "750",
		"border"              		=> "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgb(0,0, 0)|bw_all:10|bw_t:10|bw_l:10|bw_r:10|bw_b:10",
		"overlay_effect"      		=> "smile-3DRotateBottom",
		"modal_title_color"   		=> "rgb(44, 62, 80)",
		"modal_desc_color" 	  		=> "rgb(44, 62, 80)",
		"tip_color"           		=> "rgb(44, 62, 80)",
		"modal_image"		  		=> plugins_url('config/img/CP_Product_Box_Mockup.png', __FILE__ ),
		"image_position" 	  		=> false,
		"image_size"		  		=> "210",
		"btn_style" 		  		=> "cp-btn-outline",
	);
	foreach( $direct_download_default as $option => $value ){
		smile_update_default( "Smile_Modals", "direct_download", $option, $value );
	}

	//	Instant Coupon
	$instant_coupon_default = array(
		"form_fields" 			 => "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			 => "cp-form-layout-1",
		"form_input_align"		 => "center",
		"form_submit_align" 	 => "cp-submit-wrap-full",
		"submit_button_tb_padding" => 10,
		"submit_button_lr_padding" => 15,
		"overlay_effect"         => "smile-3DRotateBottom",
		"modal_short_desc1"      => __( "Last Minute Vacations Package","smile" ),
		"modal_content" 	     => __( "Enter Your Email To Find Discount On Your Dream Vacations!", "smile" ),
		"modal_confidential"     => __( "Hurry up, Limited Deals Available!", "smile" ),
		"modal_title1" 		     => __( "SAVE UP TO 80%","smile" ),
		"modal_bg_color"         => "rgb(255, 255, 255)",
		"button_title"           =>  __( "GET MY DISCOUNT OFFER","smile" ),
		"button_bg_color"        => "rgb(108, 148, 30)",
		"button_border_color"    => "rgb(255, 255, 255)",
		"modal_overlay_bg_color" => "rgba(0, 0, 0, 0.7)",
		"cp_modal_width"     	 => "750",
		"border"             	 => "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgb(112, 111, 116)|bw_all:6|bw_t:6|bw_l:6|bw_r:6|bw_b:6",
		"close_text_color"   	 => "rgb(68, 68, 68)",
		"tip_color" 		 	 => "rgb(51, 51, 51)",
		"modal_title_bg_color"   => "rgb(153, 0, 0)",
		"namefield"  		 	 => false,
		"name_text"   		 	 => __( "Enter your name.","smile" ),
		"placeholder_text"   	 => __( "Whats your email address?","smile" ),
		"btn_style" 		 	 => "cp-btn-3d",
		"placeholder_font"   	 => "Roboto",
		"modal_desc_color"   	 => "rgb(255, 255, 255)",
		"modal_title_color"  	 => "rgb(16, 16, 16)",
		"input_shadow"			 => true,
	);
	foreach( $instant_coupon_default as $option => $value ){
		smile_update_default( "Smile_Modals", "instant_coupon", $option, $value );
	}

	// Free EBook
	$free_ebook_default = array(
		"form_fields" 			=> "order->0|input_type->textfield|input_label->Name|input_name->name|input_placeholder->Enter Your Name|input_require->true;order->1|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"			 => "cp-form-layout-4",
		"form_input_align"		 => "left",
		"form_submit_align" 	 => "cp-submit-wrap-center",
		"modal_title1"  	   => '<span style="color: #85C624;">'.__( "Convert", "smile" ).'</span>'.__( "Traffic", "smile" ),
		"modal_content" 	   => __( "1. How to add powerful call to actions<br>2. Secrets of industry experts<br>3. A/B Testing & Optimization Methods", "smile" ),
		"modal_confidential"   => "",
		"modal_short_desc1"    => __( "Download this free eBook and learn:","smile" ),
		"modal_bg_color"       => "rgb(255, 255, 255)",
		"button_title"         => __( "DOWNLOAD YOUR FREE COPY", "smile" ),
		"button_bg_color"      => "rgb(133, 198, 34)",
		"button_border_color"  => "rgb(220, 59, 64)",
		"cp_modal_width"       => "750",
		"border"               => "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgba(255, 255, 255, 0.9)|bw_all:1|bw_t:1|bw_l:1|bw_r:1|bw_b:1",
		"overlay_effect"       => "smile-3DRotateBottom",
		"modal_title_color"    => "rgb(51, 51, 51)",
		"modal_desc_color" 	   => "rgb(111, 114, 119)",
		"tip_color"            => "rgb(51, 51, 51)",
		"modal_image"		   => plugins_url('config/img/free_ebook.png', __FILE__ ),
		"image_position" 	   => true,
		"image_size"		   => "195",
		"modal_desc_bg_color"  => "rgb(40, 53, 62)",

	);
	foreach( $free_ebook_default as $option => $value ){
		smile_update_default( "Smile_Modals", "free_ebook", $option, $value );
	}

	// countdown
	$count_down_default = array(
		"modal_title1"  	  => __( "SPECIAL OFFER!", "smile" ),
		"modal_content" 	  => __( "Apply Coupon Code BOGO & Upto 50% Off!", "smile" ),
		"modal_confidential"  => "",
		"count_down_title"	  => __( "Offer Expires In:", "smile" ),
		"modal_short_desc1"   => __( "This Offer is Limited! Grab your Discount!", "smile" ),
		"modal_bg_color"      => "#1bce7c",
		"button_title"        => __( "GET MY OFFER!", "smile" ),
		"button_bg_color"     => "#f25b3b",
		"button_border_color" => "#f25b3b",
		"cp_modal_width"      => "600",
		"border"              => "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgb(0,0, 0)|bw_all:10|bw_t:10|bw_l:10|bw_r:10|bw_b:10",
		"overlay_effect"      => "smile-3DRotateBottom",
		"modal_title_color"   => "rgb(255, 255, 255)",
		"modal_desc_color" 	  => "rgb(255, 255, 255)",
		"tip_color"           => "rgb(44, 62, 80)",
		"btn_style" 		  => "cp-btn-flat",
		"form_bg_color"	 	  => "rgb(255, 255, 255)",
		"form_fields" 		  => "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"		  => "cp-form-layout-1",
		"counter_container_bg_color" => "#312f37",
	);
	foreach( $count_down_default as $option => $value ){
		smile_update_default( "Smile_Modals", "countdown", $option, $value );
	}

	// social_media
	$cp_social_default = array(
		'box_shadow' 			  => '',
		"modal_short_desc1"       => __( "Share this post with your friends", "smile" ),
		'modal_title1'     		  => __( "Sharing is Awesome, Do It!" , "smile" ),
		'border'				  => "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgb(0,0,0)|bw_all:5|bw_t:5|bw_l:5|bw_r:5|bw_b:5",
		"cp_modal_width"    	  => "650",
		"modal_bg_color"    	  => "rgb(255, 255, 255)",
		"modal_title_color"    	  => "rgb(0, 0, 0)",
		"modal_desc_color" 	  	  => "rgb(0, 0, 0)",
		"cp_modal_height" 		  =>"310",
		"cp_custom_height" 		  =>false,
		"cp_social_icon_column"   => "2",
		"social_container_border" => "5",
		"cp_social_icon_shape" 	  => "circle",
		"cp_social_icon_effect"   => "flat",
		"cp_social_icon" 		  => "order:0|input_type:Facebook|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:1|input_type:Google|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:2|input_type:Twitter|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:3|input_type:Blogger|network_name:|input_action:social_sharing|profile_link:|smile_adv_share_opt:0|input_share:|input_share_count:",
		//"close_img" 			  => plugins_url('config/img/cross-new.png', __FILE__ ),
		//"cp_close_image_width"	  => "32",
		"close_image_src" 		  => "pre_icons",
	);
	foreach( $cp_social_default as $option => $value ) {
		smile_update_default( "Smile_Modals", "social_media", $option, $value );
	}

	//	Jugaad
	$jugaad_default = array(
		"form_fields" 		  => "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"		  => "cp-form-layout-1",
		"form_input_align"	  => "left",
		"button_title"        => "SUBSCRIBE NOW",
		"form_submit_align"   => "cp-submit-wrap-center",
		'modal_image'         => plugins_url('config/img/newsletter-icon.png', __FILE__ ),
		'cp_modal_width'      => '700',
		'image_size'          => '150',
		'form_bg_color'       => '#25394A',
		'content_bg_color'    => '#F6F7F1',
		"button_bg_color" 	  => "#EA773D",
		"form_input_padding_tb" => 10,
		"submit_button_tb_padding" => 5,
		"modal_title1"        => "Join Our Newsletter Today On The Writers Cookbook",
		"modal_short_desc1"   => "Stay updated with all latest updates,upcoming events & much more.",
		"form_submit_align"   => "cp-submit-wrap-full",
        'border'              => 'br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgb(255,255, 255)|bw_all:0|bw_t:0|bw_l:0|bw_r:0|bw_b:0'
    );
    foreach( $jugaad_default as $option => $value ){
        smile_update_default( "Smile_Modals", "jugaad", $option, $value );
    }

    // social_inline_share
	$cp_social_inline_share_default = array(
		'box_shadow' 			  => '',
		'border'				  => "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgb(0,0,0)|bw_all:5|bw_t:5|bw_l:5|bw_r:5|bw_b:5",
		"cp_modal_width"    	  => "620",
		"modal_bg_color"    	  => "#ffffff",
		"modal_title_color"    	  => "rgb(0, 0, 0)",
		"modal_desc_color" 	  	  => "rgb(0, 0, 0)",
		"cp_modal_height" 		  =>"310",
		"cp_custom_height" 		  =>false,
		"cp_social_icon_column"   => "3",
		"social_container_border" => "0",
		"cp_social_icon_shape" 	  => "normal",
		"cp_social_icon_effect"   => "gradient",
		"cp_social_icon" 		  => "order:0|input_type:Facebook|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:1|input_type:Google|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:2|input_type:Twitter|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:",
		//"close_img" 			  => plugins_url('config/img/cross-new.png', __FILE__ ),
		//"cp_close_image_width"	  => "32",
		"content_padding" 		  => true,
		"close_modal" 			  => "do_not_close",
		"affiliate_setting"		  => false,
		"close_image_src" 		  => "pre_icons",
	);
	foreach( $cp_social_inline_share_default as $option => $value ) {
		smile_update_default( "Smile_Modals", "social_inline_share", $option, $value );
	}

	// social_widget_box
	$social_widget_box_default = array(
		'box_shadow' 			  => '',
		'modal_title1'     		  => __( "STAY IN TOUCH" ),
		'border'				  => "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgb(0,0,0)|bw_all:5|bw_t:5|bw_l:5|bw_r:5|bw_b:5",
		"cp_modal_width"    	  => "350",
		"modal_bg_color"    	  => "#fafafa",
		"modal_title_color"    	  => "#37474f",
		"cp_modal_height" 		  => "130",
		"cp_custom_height" 		  => true,
		"cp_display_nw_name" 	  => false,
		"cp_social_icon_style" 	  => "cp-icon-style-left",
		"cp_social_icon_effect"   => "flat",
		"cp_social_icon_align"    => "center",
		"cp_social_icon" 		  => "order:0|input_type:Facebook|network_name:|input_action:social_sharing|profile_link:|smile_adv_share_opt:0|input_share:|input_share_count:;order:1|input_type:StumbleUpon|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:2|input_type:Google|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:3|input_type:Blogger|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:",
		//"close_img" 			  => plugins_url('config/img/cross-new.png', __FILE__ ),
		//"cp_close_image_width"	  => "32",
		"close_image_src" 		  => "pre_icons",
	);
	foreach( $social_widget_box_default as $option => $value ) {
		smile_update_default( "Smile_Modals", "social_widget_box", $option, $value );
	}


	// social_article
	$social_article_default = array(		
		'modal_title1'     		  => __( "Enjoyed this article? Please spread the word." ),
		'border'				  => "br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:none|color:rgb(0,0,0)|bw_all:5|bw_t:5|bw_l:5|bw_r:5|bw_b:5",
		"cp_modal_width"    	  => "550",
		"modal_bg_color"    	  => "#edf7f7",
		"modal_title_color"    	  => "#37474F",
		"cp_modal_height" 		  => "160",
		"cp_custom_height" 		  => true,
		"cp_social_icon_hover_effect" => "slide",
		"cp_display_nw_name" 	  => true,
		"cp_social_share_count"	  => true,
		"social_min_count"		  => 180,
		"cp_social_icon_style" 	  => "cp-icon-style-simple",
		"cp_social_icon_effect"   => "flat",
		"cp_social_icon_align"    => "center",
		"cp_social_icon_shape" 	  => "border_radius",
		"cp_social_icon" 		  => "order:0|input_type:Facebook|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:1|input_type:Google|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:2|input_type:Twitter|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:",
		//"close_img" 			  => plugins_url('config/img/cross-new.png', __FILE__ ),
		//"modal_overlay_bg_color"  => "rgba(255,255,255,0.89)",
		"close_image_src" 		  => "pre_icons",
		"content_padding"		  => true,
		"box_shadow"			  => "type:outset|horizontal:4|vertical:4|blur:4|spread:0|color:#cbcbcb"
	);
	foreach( $social_article_default as $option => $value ) {
		smile_update_default( "Smile_Modals", "social_article", $option, $value );
	}

	// social_media_with_form
	$social_media_with_form_default = array(
		"form_fields" 				=> "order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true",
		"form_layout"				=> "cp-form-layout-4",
		"form_input_align"			=> "left",
		"submit_button_tb_padding" 	=> 10,
		"submit_button_lr_padding" 	=> 15,
		'form_bg_color'      		=> '#fafafa',
		"form_submit_align" 		=> "cp-submit-wrap-full",
		"form_grid_structure"		=> "cp-form-grid-structure-2",
		"overlay_effect" 	 		=> "smile-3DRotateBottom",
		"modal_short_desc1"  		=> '<span style=" font-style:italic">'.__( "Join over 4000 of Your Peers!" , "smile" ).'</span>',
		"modal_title1"		 		=> __( "Signup For Access To Free WordPress Tips & Resources.", "smile" ),
		"modal_confidential" 		=> "",
		"modal_content" 	 		=> __( "TODAY – 8.00 PM to 8.35 PM (EST)", "smile" ),
		"modal_bg_color"     		=> "rgba(0,0,0,0.24)",
		"modal_overlay_bg_color" 	=> "rgba(0, 0, 0, 0.7)",
		"button_title"       		=> __( "GET IT NOW!", "smile" ),
		"button_bg_color"    		=> "#193d59",
		"button_border_color"		=> "#193d59",
		"btn_style" 				=> "cp-btn-3d",
		"cp_modal_width"     		=> "650",
		"border" 			 		=> "br_all:4|br_tl:4|br_tr:4|br_br:4|br_bl:4|style:none|color:rgb(255,255,255)|bw_all:1|bw_t:1|bw_l:1|bw_r:1|bw_b:1",
		"btn_disp_next_line" 		=> false,
		"close_position"     		=> "out_modal",
		"modal_title_color"  		=> "rgb(255, 255, 255)",
		"modal_desc_color" 	 		=> "rgb(255, 255, 255)",
		"tip_color"          		=> "rgb(255, 255, 255)",
		"placeholder_text"   		=> __( "Enter Your Email Here", "smile" ),
		"name_text"  		 		=> __( "Enter Your Name", "smile" ),
		"placeholder_font"   		=> "Lato",
		"modal_bg_image"			=> plugins_url('config/img/free_widget.jpg', __FILE__ ),
		"cp_display_nw_name" 	  	=> false,
		"social_container_border" 	=> "20",
		"cp_social_icon_style" 	 	=> "cp-icon-style-left",
		"cp_social_icon_effect"   	=> "flat",
		"cp_social_icon_align"    	=> "center",
		"cp_social_icon" 		  	=> "order:0|input_type:Facebook|network_name:|input_action:social_sharing|profile_link:|smile_adv_share_opt:0|input_share:|input_share_count:;order:1|input_type:Twitter|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:2|input_type:Google|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:3|input_type:Blogger|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:4|input_type:Pinterest|network_name:|input_action:social_sharing|profile_link:|smile_adv_share_opt:0|input_share:|input_share_count:",
	);

	foreach( $social_media_with_form_default as $option => $value ){
		smile_update_default( "Smile_Modals", "social_media_with_form", $option, $value );
	}

}

/**
 * Remove the options of individual modal popup
 *
 * @since 0.2.1
 */

// Remove any option from style
if( function_exists( "smile_remove_option" ) ){

	//	YouTube
	smile_remove_option( "Smile_Modals", "YouTube", array(
		'content_padding',
		'modal_bg_image',
		'opt_bg',
		'modal_bg_image_src')
	);

	//	First Order
	smile_remove_option( "Smile_Modals", "first_order", array(
		'input_bg_color',
		'placeholder_color' ,
		'placeholder_text' ,
		'name_text' ,
		'namefield' ,
		'input_bg_color' ,
		'input_border_color' ,
		'form_options_title' ,
		'placeholder_font' ,
		'image_position' )
	);

	//	Locked Content
	smile_remove_option( "Smile_Modals", "locked_content", array(
		'image_position' )
	);

	//	First Order 2
	smile_remove_option( "Smile_Modals", "first_order_2", array(
		'input_bg_color',
		'placeholder_color' ,
		'placeholder_text' ,
		'name_text' ,
		'namefield' ,
		'input_bg_color' ,
		'input_border_color' ,
		'form_options_title' ,
		'placeholder_font' ,
		'image_position' )
	);

	//	Direct Download
	smile_remove_option( "Smile_Modals", "direct_download", array(
		'input_bg_color',
		'placeholder_color' ,
		'placeholder_text' ,
		'name_text' ,
		'namefield' ,
		'input_bg_color' ,
		'input_border_color' ,
		'form_options_title' ,
		'placeholder_font' )
	);

	//	Free EBook
	smile_remove_option( "Smile_Modals", "free_ebook", array(
		'input_bg_color',
		'placeholder_color' ,
		'placeholder_text' ,
		'name_text' ,
		'namefield' ,
		'input_bg_color' ,
		'input_border_color' ,
		'form_options_title',
		'placeholder_font' ,
		'btn_attached_email',
		)
	);

	//countdown
	smile_remove_option( "Smile_Modals", "countdown", array(
		'input_bg_color',
		'placeholder_color' ,
		'placeholder_text' ,
		'name_text' ,
		'namefield' ,
		'input_bg_color' ,
		'input_border_color' ,
		'form_options_title' ,
		'placeholder_font',
		'form_border_color' )
	);

    smile_remove_option( "Smile_Modals", "jugaad", array(
        'content_padding',
        'modal_size'
         )
    );

    //	social_widget_box
	/*smile_remove_option( "Smile_Modals", "social_widget_box", array(
		'content_padding',
		 )
	);*/


}

/**
 * Partial Refresh - Update values
 */
if( function_exists('smile_update_partial') ) {

	// Webinar
	$flat_discount_partial = array(
	    'border' => array(
	        'css_selector' => '.cp-modal-body-inner',
	        'css_property' => 'border-color',
	    ),
	);
	foreach( $flat_discount_partial as $option => $parse_array ){
	    smile_update_partial( "Smile_Modals", "flat_discount", $option, $parse_array );
	}

	// Webinar
	$webinar_partial = array(
	    'form_bg_color' => array(
	        'css_selector' => '.cp-row.cp-webinar-form',
	        'css_property' => 'background-color',
	    ),
	    'form_border_color' => array(
	        'css_selector' => '.cp-row.cp-webinar-form',
	        'css_property' => 'border-color',
	    ),
	);
	foreach( $webinar_partial as $option => $parse_array ){
	    smile_update_partial( "Smile_Modals", "webinar", $option, $parse_array );
	}

	// Special Offer
	$special_offer_partial = array(
	    'modal_title_bg_color' => array(
	        'css_selector' => '.cp-special-offer .cp-description',
	        'css_property' => 'background-color',
	    ),
	);
	foreach( $special_offer_partial as $option => $parse_array ){
	    smile_update_partial( "Smile_Modals", "special_offer", $option, $parse_array );
	}

	// YouTube
	$youtube_partial = array(
	    'modal_bg_color' => array(
	        'css_selector' => '.cp-modal-body-overlay, .cp-form-container ,.cp-modal-window-size .cp-form-container',
	        'css_property' => 'background-color',
	    ),
	);
	foreach( $youtube_partial as $option => $parse_array ){
	    smile_update_partial( "Smile_Modals", "YouTube", $option, $parse_array );
	}

	// CountDown
	$count_down_partial = array(
	    'form_bg_color' => array(
	        'css_selector' => '.counter-desc-overlay',
	        'css_property' => 'background-color',
	    ),
	    'modal_bg_color' =>array(
	    	'css_selector' => '.cp-counter-container , .cp-modal-body-overlay.cp_fs_overlay',
	        'css_property' => 'background-color',
	   ),
	);
	foreach( $count_down_partial as $option => $parse_array ){
	    smile_update_partial( "Smile_Modals", "countdown", $option, $parse_array );
	}

	// Webinar
	$social_media_with_form_partial = array(
	    'form_bg_color' => array(
	        'css_selector' => '.cp-row.cp-social-form-form',
	        'css_property' => 'background-color',
	    ),
	    'form_border_color' => array(
	        'css_selector' => '.cp-row.cp-social-form-form',
	        'css_property' => 'border-color',
	    ),
	);
	foreach( $social_media_with_form_partial as $option => $parse_array ){
	    smile_update_partial( "Smile_Modals", "social_media_with_form", $option, $parse_array );
	}

}
