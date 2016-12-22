<?php

/**
 * ConvertPlug Form
 *
 * 	Module 	- social_media
 *
 * 1. 	Social Array Setup
 * 2.	Global array for shortcode variables
 * 3.	Generate Output by 'cp_get_social' filter
 * 4.	Generate & Append CSS
 *
 * 	Use same names for variables & array
 * 	For '$your_options_name' use '$your_options_name_VARS'
 *
 * 	E.g. 	$cp_form
 *     		$cp_form_vars
 *
 * @since  1.1.1
 */
global $cp_social;
global $cp_social_vars;

/*
 * 1.	Social Array Setup
 */
$option_array ='';
if( isset($_GET['theme']) && $_GET['theme'] =='floating_social_bar' ){
	$option_array = array(
			__( "Normal", "smile" )  => "normal",
			__( "Border", "smile" )  => "border",
			__( "Flip", "smile" ) 	 => "flip",
			__( "Grow", "smile" ) 	 => "grow",
		);
}else{
	$option_array = array(
			__( "Normal", "smile" )  => "normal",
			//__( "Border", "smile" )  => "border",
			//__( "Flip", "smile" ) 	 => "flip",
			__( "Slide", "smile" ) 	 => "slide",
		);
}

$cp_social = array(
	array(
		"type" 		=> "section",
		"class" 	=> "",
		"name" 		=> "social_media_section",
		"opts"		=> array(
			"title"  => "Essential Configurations",
			"link" => "",
			"value"  => "",
		),
		"panel" => "Social Networks",
		"section" => "Design",
		"section_icon" => "connects-icon-disc",
	),
	array(
		"type" 		=> "social_media",
		"class" 	=> "",
		"name" 		=> "cp_social_icon",
		"opts"		=> array(
			"title" => "",
			"value" => "",
		),
		"panel" => "Social Networks",
		"section" => "Design",
		"section_icon" => "connects-icon-disc",
	),
	array(
		"type" 		=> "section",
		"class" 	=> "",
		"name" 		=> "social_media_layout",
		"opts"		=> array(
			"title"  => "Layout",
			"link" => "",
			"value"  => "",
		),
		"panel" => "Social Networks",
		"section" => "Design",
		"section_icon" => "connects-icon-disc",
	),
	array(
		"type" 		=> "radio-image",
		"class" 	=> "",
		"name" 		=> "cp_social_icon_style",
		"opts" 		=> array(
			"title" 	=> __( "Layout", "smile"),
			"value" 	=> "cp-icon-style-left",
			"options" 	=> array(
				__( "cp-icon-style-left", "smile" ) => CP_BASE_URL . '/modules/assets/images/icon_with_left.png',
				__( "cp-icon-style-right", "smile" ) => CP_BASE_URL . '/modules/assets/images/icon_with_right.png',
				__( "cp-icon-style-simple", "smile" ) => CP_BASE_URL . '/modules/assets/images/simple_icon.png',
				__( "cp-icon-style-rectangle", "smile" ) => CP_BASE_URL . '/modules/assets/images/icon_with_square.png'
			),
			"width"		=> "125px",
			"imagetitle" => array(
				__( "title-0", "smile" ) 	=> "Icon At Left",
				__( "title-1", "smile" ) 	=> "Icon At Right",
				__( "title-2", "smile" ) 	=> "Icon At Left Without Background",
				__( "title-3", "smile" ) 	=> "Icon At Top"
			),
		),
		"panel" => "Social Networks",
		"section" => "Design",
		"section_icon" => "connects-icon-disc",
	),
	array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "cp_social_icon_column",
			"opts" 		=> array(
				"title" 	=> __( "Number of Columns","smile"),
				"value" 	=> "auto",
				"options" 	=> array(
						__( "Auto Width", "smile" ) => "auto",
						__( "1", "smile" ) 	    	=> "1",
						__( "2", "smile" ) 			=> "2",
						__( "3", "smile" ) 			=> "3",
						__( "4", "smile" ) 			=> "4",
						__( "5", "smile" ) 			=> "5",
						__( "6", "smile" ) 			=> "6",
					),
				"description"   => __( "Select grid to display social icons", "smile" ),
				),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "cp_social_icon_align",
			"opts" 		=> array(
				"title" 	=> __( " Icon Container Alignment","smile"),
				"value" 	=> "auto",
				"options" 	=> array(
						__( "Center", "smile" ) => "center",
						__( "Left", "smile" ) 	    	=> "left",
						__( "Right", "smile" ) 			=> "right",
					),
				"description"   => __( "Select alignment for icon container", "smile" ),
				),
			"dependency" => array('name' => 'cp_social_icon_column', 'operator' => '==', 'value' => 'auto'),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "cp_social_remove_icon_spacing",
			"opts" 		=> array(
				"title" 	=> __( "Remove Icon Spacing", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description"   => __( "Remove gap / spacing between two social icons", "smile" ),
				),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	array(
		"type" 		=> "section",
		"class" 	=> "",
		"name" 		=> "social_media_styling",
		"opts"		=> array(
			"title"  => "Styling",
			"link" => "",
			"value"  => "",
		),
		"panel" => "Social Networks",
		"section" => "Design",
		"section_icon" => "connects-icon-disc",
	),
	array(
			"type" 		=> "slider",
			"class" 	=> "",
			"name" 		=> "social_container_border",
			"opts"			=> array(
				"title" 		=> __( "Icon Container Border Radius", "smile" ),
				"value" 		=> 5,
				"min" 			=> 0,
				"max" 			=> 50,
				"step" 			=> 1,
				"suffix" 		=> "px",
				"css_property" 	=> "border-radius",
				"css_selector" 	=> ".cp_social_networks.cp_social_left li",
				"css_preview" 	=> true,
				"description" 	=> __( "Apply border radius to icon container.", "smile" ),
			),
			"dependency" => array('name' => 'cp_social_icon_style', 'operator' => '!==', 'value' => 'cp-icon-style-simple'),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "cp_social_icon_shape",
			"opts" 		=> array(
				"title" 	=> __( "Icon Shape","smile"),
				"value" 	=> "Normal",
				"options" 	=> array(
						__( "Normal", "smile" ) 	=> "normal",
						__( "Square", "smile" ) 	=> "square",
						__( "Circle", "smile" ) 	=> "circle",
						__( "Custom", "smile" ) 	=> "border_radius",
					),
				"description" 	=> __( "Provide shape to your icon.", "smile" ),
				),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	array(
			"type" 		=> "slider",
			"class" 	=> "",
			"name" 		=> "social_icon_border",
			"opts"			=> array(
				"title" 		=> __( "Icon Border Radius", "smile" ),
				"value" 		=> 5,
				"min" 			=> 0,
				"max" 			=> 50,
				"step" 			=> 1,
				"suffix" 		=> "px",
				"css_property" 	=> "border-radius",
				"css_selector" 	=> ".cp-border_radius .cp_social_icon ,.cp-slidein .cp-icon-style-top.cp-border_radius li",
				"css_preview" 	=> true,
				"description" 	=> __( "Apply border radius to actual icon.", "smile" ),
			),
			"dependency" => array('name' => 'cp_social_icon_shape', 'operator' => '==', 'value' => 'border_radius'),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "cp_social_icon_effect",
			"opts" 		=> array(
				"title" 	=> __( "Icon Effect","smile"),
				"value" 	=> "gradient",
				"options" 	=> array(
						__( "Flat", "smile" ) 		=> "flat",
						__( "3D", "smile" ) 	    => "3D",
						__( "Overlay", "smile" ) 	=> "gradient",
					),
				"description" 	=> __( "Style your icon container with nice effects.", "smile" ),
				),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "cp_social_icon_hover_effect",
			"opts" 		=> array(
				"title" 	=> __( "Icon Hover Effect","smile"),
				"value" 	=> "Slide",
				"options" 	=> $option_array,
				"description" 	=> __( "Apply slide / normal hover effect to icon.", "smile" ),
				),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),

	array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "cp_social_enable_icon_color",
			"opts" 		=> array(
				"title" 	=> __( "Use Custom Colors", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "Style your icons with custom colors.", "smile" ),
				),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),

	array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "cp_social_icon_color",
			"opts"		=> array(
				"title" 		=> __( "Icon Color", "smile" ),
				"value" 		=> "rgb(255, 255, 255)",
				"css_property" => "color",
				"css_selector" => ".cp-custom-sc-color i.cp_social_icon , .cp-icon-style-top.cp-normal.cp-custom-sc-color i.cp_social_icon ",
				"css_preview"  => true,
			),
			"dependency" => array('name' => 'cp_social_enable_icon_color', 'operator' => '==', 'value' => 'true'),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "cp_social_text_color",
			"opts"		=> array(
				"title" 		=> __( "Text Color", "smile" ),
				"value" 		=> "rgb(255, 255, 255)",
				//"css_property" => "color",
				//"css_selector" => ".cp-custom-sc-color i.cp_social_icon",
			),
			"dependency" => array('name' => 'cp_social_enable_icon_color', 'operator' => '==', 'value' => 'true'),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "cp_social_icon_bgcolor",
			"opts"		=> array(
				"title" 		=> __( "Background Color", "smile" ),
				"value" 		=> "#107fc9",
				"css_property" => "background",
				"css_selector" => ".cp_social_networks.cp-custom-sc-color li ,.cp_social_networks.cp-custom-sc-color.cp_social_simple li .cp_social_icon ,.cp_social_networks.cp-custom-sc-color.cp_social_circle li .cp_social_icon",
				"css_preview"  => true,
			),
			"dependency" => array('name' => 'cp_social_enable_icon_color', 'operator' => '==', 'value' => 'true'),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "cp_social_icon_hover",
			"opts"		=> array(
				"title" 		=> __( "Icon Hover Color", "smile" ),
				"value" 		=> "rgb(255, 255, 255)",
				"css_property" => "color",
				"css_selector" => ".cp-custom-sc-color i.cp_social_icon:hover ,.cp-custom-sc-color li:hover i.cp_social_icon",
				"css_preview"  => true,
			),
			"dependency" => array('name' => 'cp_social_enable_icon_color', 'operator' => '==', 'value' => 'true'),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "cp_social_text_hover_color",
			"opts"		=> array(
				"title" 		=> __( "Text Hover Color", "smile" ),
				"value" 		=> "rgb(255, 255, 255)",
			),
			"dependency" => array('name' => 'cp_social_enable_icon_color', 'operator' => '==', 'value' => 'true'),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "cp_social_icon_bghover",
			"opts"		=> array(
				"title" 		=> __( "Background Hover Color", "smile" ),
				"value" 		=> "#0e72b4",
				"css_property" => "background",
				"css_selector" => ".cp_social_networks.cp-custom-sc-color li:hover ,.cp_social_networks.cp-custom-sc-color.cp_social_simple li:hover .cp_social_icon,.cp_social_networks.cp-custom-sc-color.cp_social_circle li:hover .cp_social_icon ",
				"css_preview"  => true,
			),
			"dependency" => array('name' => 'cp_social_enable_icon_color', 'operator' => '==', 'value' => 'true'),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	array(
		"type" 		=> "section",
		"class" 	=> "",
		"name" 		=> "social_media_Advanced",
		"opts"		=> array(
			"title"  => "Advanced",
			"link" => "",
			"value"  => "",
		),
		"panel" => "Social Networks",
		"section" => "Design",
		"section_icon" => "connects-icon-disc",
	),
	array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "cp_display_nw_name",
			"opts" 		=> array(
				"title" 	=> __( "Display Network Names", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "Show / hide social network name.", "smile" ),
				),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),

	array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "cp_social_share_count",
			"opts" 		=> array(
				"title" 	=> __( "Display Share Counts", "smile" ),
				"value" 	=> false,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
				"description" 	=> __( "Show / hide share counts.", "smile" ),
				),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),
	array(
			"type" 		=> "slider",
			"class" 	=> "",
			"name" 		=> "social_min_count",
			"opts"			=> array(
				"title" 		=> __( "Minimum Count Display", "smile" ),
				"value" 		=> 50,
				"min" 			=> 0,
				"max" 			=> 1000,
				"step" 			=> 1,
				//"suffix" 		=> "",
				"description" 	=> __( "Display minimum share count number until actual count increases.", "smile" ),
			),
			"dependency" => array('name' => 'cp_social_share_count', 'operator' => '==', 'value' => 'true'),
			"panel" => "Social Networks",
			"section" => "Design",
			"section_icon" => "connects-icon-disc"
		),

	//	store button darken on hover
	array(
	    "type"         => "textfield",
	    "name"         => "social_darken",
	    "opts"         => array(
	        "title"     => __( "Button BG Hover Color", "smile" ),
	        "value"     => "",
	    ),
	    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
		"panel" => "Social Networks",
		"section" => "Design",
		"section_icon" => "connects-icon-disc"
	),
	//	store button lighten gradient
	array(
	    "type"         => "textfield",
	    "name"         => "social_lighten",
	    "opts"         => array(
	        "title"     => __( "Button Gradient Color", "smile" ),
	        "value"     => "",
	    ),
	    "dependency" => array('name' => 'hidden', 'operator' => '==', 'value' => 'hide'),
		"panel" => "Social Networks",
		"section" => "Design",
		"section_icon" => "connects-icon-disc"
	),

);


/**
 * 2.	Global array for shortcode variables
 */

$cp_social_vars = generate_global_shortcode_vars( $cp_social );

/**
 * 3.	Generate Output by 'cp_get_social' filter
 */
add_filter( 'cp_get_social', 'cp_get_social_init' );

if( !function_exists('cp_get_social_init') ) {
	function cp_get_social_init( $a ) {
		if( !empty( $a['cp_social_icon'] ) ){

		  $cp_social_icon_column 		 = $a['cp_social_icon_column'];
		  $cp_social_icon_style  		 = $a['cp_social_icon_style'];
		  $cp_display_nw_name 	 		 = $a['cp_display_nw_name'];
		  $cp_social_icon_shape 		 = $a['cp_social_icon_shape'];
		  $cp_social_icon_effect  		 = $a['cp_social_icon_effect'];
		  $cp_social_enable_icon_color   = $a['cp_social_enable_icon_color'];
		  $cp_social_icon_color    		 = $a['cp_social_icon_color'];
		  $cp_social_icon_bgcolor  		 = $a['cp_social_icon_bgcolor'];
		  $cp_social_icon_bghover  		 = $a['cp_social_icon_bghover'];
		  $cp_social_share_count  		 = $a['cp_social_share_count'];
		  $social_min_count 		 	 = $a['social_min_count'];
		  $cp_social_remove_icon_spacing = $a['cp_social_remove_icon_spacing'];
		  $cp_social_icon_hover_effect   = $a['cp_social_icon_hover_effect'];

		  //	apply social styles
		  apply_filters_ref_array('cp_social_css', array( $a ) );

		  $social_arr = explode(';', $a['cp_social_icon'] );
		  $array = array();
		  	foreach ($social_arr as $key => $value) {
				$single = explode('|', $value );
				$ItemArray = array();
				foreach ($single as $key1 => $value1) {
					$s = explode(':', $value1 );
					$ItemArray[$s[0]] = $s[1];
				}
				array_push($array ,$ItemArray);
			}

		/**
         * Build HTML structure for Social_icon
         */

           if($cp_social_icon_style =='' || $cp_social_icon_style =='undefined'){
              $cp_social_icon_style = 'cp-icon-style-top';
           }
           if($cp_display_nw_name =='' ||  $cp_display_nw_name =='undefined'){
              $cp_display_nw_name = false;
           }
           if($cp_social_icon_column =='' ||  $cp_social_icon_column =='undefined'){
              $cp_social_icon_column = '1';
           }

           if($cp_social_icon_effect =='' ||  $cp_social_icon_effect =='undefined'){
              $cp_social_icon_effect = 'none';
           }


		 //apply no of column to container
        if( $cp_social_icon_column == 'auto' ) {
            $cp_social_icon_column_class = 'autowidth';
        } else {
            $cp_social_icon_column_class = 'col_'.$cp_social_icon_column;
        }

        //if count and nw name is not present
        $no_count ='';
        if( $cp_social_icon_style == 'cp-icon-style-rectangle' && $cp_social_icon_effect =='gradient' && $cp_display_nw_name !=='1' && $cp_social_share_count !== '1' ){
        	$no_count .='cp-no-count-no-share';
        }

        //style class
        $class_icon_hover_effect = '';
        if( $cp_social_icon_hover_effect == 'slide' ) {
            switch( $cp_social_icon_style ) {
                case 'cp-icon-style-simple':
                            $class_icon_hover_effect = 'cp_social_slide';
                    break;

                case 'cp-icon-style-rectangle':
                            $class_icon_hover_effect = 'cp_social_slide';
                    break;

                case 'cp-icon-style-right':
                            $class_icon_hover_effect = 'cp_social_flip';
                    break;

                case 'cp-icon-style-left':
                            $class_icon_hover_effect = 'cp_social_flip';
                    break;
            }
        }


        //apply style to icon
        $class_list ='';
         if( $cp_social_icon_style == 'cp-icon-style-simple' ){
           $class_list .= 'cp_social_simple'.' '.$class_icon_hover_effect;
         } else {
         	 if( $cp_social_icon_style == 'cp-icon-style-rectangle' ){
         	 	$class_list .= ' '.$class_icon_hover_effect;
         	 } else {
         	 	$class_list .= ' '.$class_icon_hover_effect;
         	 }
         }

        //icon shape
        if( $cp_social_icon_shape == 'circle' ){
            //$class_list .= 'cp_social_circle';
        }

        //spacing
	    if( $cp_social_remove_icon_spacing == 1 ) {
	       $class_list .= ' cp-no-spacing';
	    }

	    if( $cp_social_icon_style == 'cp-icon-style-top' ){
	    	$cp_social_icon_column_class .=' cp-hover-'.$cp_social_icon_hover_effect ;
	    	if( $cp_social_share_count == 0 ){
                $cp_social_icon_column_class .=' cp-network-without-count';
            }
	    }

	    if($cp_social_enable_icon_color == 1 ){
	    	$class_list .=' cp-custom-color';
	    }

		$social_html = '';
        $social_html .= '<div class="cp_social_networks cp_social_'.$cp_social_icon_column_class.' cp_social_left cp_social_withcounts cp_social_withnetworknames '.$cp_social_icon_style .' '.$class_list.' cp-'.$cp_social_icon_shape.' cp_'.$cp_social_icon_effect.' '.$no_count.'" data-column-no ="cp_social_'.$cp_social_icon_column_class.'">';

        $social_html .= ' <ul class="cp_social_icons_container">';

			foreach ($array as $key => $value) {
				$input_type = strtolower( $value['input_type'] );
            	$network_name = $value['input_type'];
            	$newnw 	 	= $value['network_name'];
            	if($newnw !==''){
            		$network_name = $newnw;
            	}

            	$profile_link_name = 'javascript:void(0)';
            	$current_page = '';

            	if( isset( $value['profile_link'] ) && $value['profile_link'] !=='' ){
            		$profile_link_name = urldecode( $value['profile_link'] );
            	}

            	if( isset( $value['smile_adv_share_opt'] ) ){
            		if($value['smile_adv_share_opt'] == '1' ){
            			$current_page = urldecode( $value['input_share'] );
            		}else{
            			$current_page = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            		}
            	}

            	$input_action = strtolower( $value['input_action'] );

				$url = '';
				if( $input_action == 'profile_link' ){

					$url = $profile_link_name;
				} else {

	            	switch( $input_type ) {
	            		case 'facebook':
	            						 $url = "http://www.facebook.com/sharer.php?u=".$current_page;
	            			  break;

	            		case 'twitter':
	            						 $url = "https://twitter.com/share?url=".$current_page;
	            			  break;

	            		case 'google':
	            						 $url = "https://plus.google.com/share?url=".$current_page;
	            			  break;

	            		case 'pinterest':
	            						 $url = "https://pinterest.com/pin/create/bookmarklet/?url=".$current_page;
	            			  break;
	            		case 'linkedin':
	            						 $url = "http://www.linkedin.com/shareArticle?url=".$current_page;
	            			  break;

	            		case 'digg':
	            						 $url = "http://digg.com/submit?url=".$current_page;
	            			  break;

	            		case 'blogger':
	            						 $url = "https://www.blogger.com/blog_this.pyra?t&amp;u=".$current_page;
	            			  break;

	            		case 'reddit':
	            						 $url = "http://reddit.com/submit?url=".$current_page;
	            			  break;

	            		case 'stumbleupon':
	            						 $url = "http://www.stumbleupon.com/submit?url=".$current_page;
	            			  break;

	            		case 'tumblr':
	            						 $url = "https://www.tumblr.com/widgets/share/tool?canonicalUrl=".$current_page;
	            			  break;

            			case 'myspace':
            						 $url = "https://myspace.com/post?u=".$current_page;
            			 	 break;

            		}
            	}

            	$social_html .= '<li class="cp_social_'.$input_type.'">';

            	if($input_action == 'profile_link' )
            	{
            		$social_html .= "<a href = ".$url." class='cp_social_share cp_social_display_count'  target='_blank' >";
            	}else{
            		$social_html .= '<a href="'.$url.'" class="cp_social_share cp_social_display_count" onclick="window.open(this.href,\'mywin\',\'left=20,top=20,width=500,height=500,toolbar=1,resizable=0\');return false">';
	          	}

				$social_html .= '<i class="cp_social_icon cp_social_icon_'.$input_type.'"></i>';
				//display label
	            if( $cp_display_nw_name == '1' || $cp_social_share_count == '1' ){
	                $social_html .= '<div class="cp_social_network_label">';
	            }

	           	//display network name
	            if( $cp_display_nw_name == '1' ){
	               $social_html .= '<div class="cp_social_networkname">'.$network_name.'</div>';
	            }

	            //display share count
	            if( $cp_social_share_count == '1' ){
	            	if($social_min_count !==''){
	               		$social_html .= '<div class="cp_social_count"><span>'.$social_min_count.'</span></div>';
	               }
	            }

	            //close label div
	            if( $cp_display_nw_name == '1' || $cp_social_share_count == '1' ){
	                $social_html .= '</div>';
	            }

	            if($cp_social_icon_effect == 'gradient'){
	            	$social_html .= '<div class="cp_social_overlay"></div>';
	            }

	            $social_html .= '</a>'
	                         . ' </li>';

			}

		 $social_html .= '</ul>';   /*--end of cp_social_icons_container --*/
         $social_html .= '</div>';/*--end of cp_social_networks--*/
         echo $social_html ;

		}/*--end of empty --*/

	}
}

/**
 * 4.	Generate & Append CSS
 */

add_filter('cp_social_css', 'cp_social_css_init');

function cp_social_css_init( $a ) {
		  $styleid 					= ( isset( $a['uid_class'] ) ) ? esc_attr( $a['uid_class'] ) : '';
		  $cp_social_icon_column 		= $a['cp_social_icon_column'];
		  $cp_social_icon_style  		= $a['cp_social_icon_style'];
		  $cp_display_nw_name 	 		= $a['cp_display_nw_name'];
		  $cp_social_icon_shape 		= $a['cp_social_icon_shape'];
		  $cp_social_icon_effect  		= $a['cp_social_icon_effect'];
		  $cp_social_enable_icon_color  = $a['cp_social_enable_icon_color'];
		  $icon_color    				= $a['cp_social_icon_color'];
		  $icon_bgcolor  				= $a['cp_social_icon_bgcolor'];
		  $icon_bghover  				= $a['cp_social_icon_bghover'];
		  $icon_hover  					= $a['cp_social_icon_hover'];
		  $social_icon_border           = $a['social_icon_border'];
		  $social_container_border 		= $a['social_container_border'];
		  $cp_social_icon_align 		= $a['cp_social_icon_align'];
		  $cp_social_text_hover_color   = $a['cp_social_text_hover_color'];
		  $cp_social_text_color  		= $a['cp_social_text_color'];
		  $social_style = '';

		 $light    = $a['social_lighten'];
         $c_hover  = $a['social_darken'];

         if($cp_social_icon_style =='' || $cp_social_icon_style =='undefined'){
              $cp_social_icon_style = 'cp-icon-style-top';
           }

		   //to use user defined color for icon
        if($cp_social_enable_icon_color == 1){
             $social_style = '.'.$styleid.' .cp_social_networks li ,'
                          .'.'.$styleid.' .cp_social_networks.cp_social_simple li .cp_social_icon ,'
              			  .'.'.$styleid.' .cp_social_networks.cp_social_circle li .cp_social_icon {'
                          .'    background:'. $icon_bgcolor
                          .' }'
                          .'.'.$styleid.' .cp_social_networks li:hover {'
                          .'    background:'. $icon_bghover
                          .' }'
                          .'.'.$styleid.'  .cp_social_networks li .cp_social_icon ,'
                          .'.'.$styleid.'  .cp_social_networks.cp_social_simple li .cp_social_icon ,'
                          .'.'.$styleid.'  .cp_social_networks.cp_social_circle li .cp_social_icon {'
                          .'     color:'. $icon_color
                          .' }'
                          .'.'.$styleid.' .cp_social_networks li:hover .cp_social_icon{'
                          .'      color: '. $icon_hover
                          .' }'
                          .'.'.$styleid.' .cp_social_networks.cp_social_simple li:hover .cp_social_icon ,'
                          .'.'.$styleid.' .cp_social_networks.cp_social_circle li:hover .cp_social_icon {'
                          .'    background:'. $icon_bghover.'!important'
                          .' }';

           if($cp_social_icon_effect == '3D'){
           		$social_style .='.'.$styleid.' .cp_3D li,'
                          .'.'.$styleid.' .cp_social_networks.cp_social_simple.cp_3D li i ,'
                          .'.'.$styleid.' .cp_social_networks.cp_social_circle.cp_3D li i{'
                          .'    -moz-box-shadow: 0 4px '.$light.'!important;'
                          .'    -webkit-box-shadow: 0 4px '.$light.'!important;'
                          .'    -o-box-shadow: 0 4px '.$light.'!important;'
                          .'    box-shadow: 0 4px '.$light.'!important;'
                          .' }'
                          .'.'.$styleid.' .cp_3D li:hover,'
                          .'.'.$styleid.' .cp_social_networks.cp_social_simple.cp_3D li:hover i ,'
                          .'.'.$styleid.' .cp_social_networks.cp_social_circle.cp_3D li:hover i {'
                          .'    -moz-box-shadow: 0 4px '.$c_hover.'!important;'
                          .'    -webkit-box-shadow: 0 4px '.$c_hover.'!important;'
                          .'    -o-box-shadow: 0 4px '.$c_hover.'!important;'
                          .'    box-shadow: 0 4px '.$c_hover.'!important;'
                          .' }';

                 if( $cp_social_icon_shape == 'square' && $cp_social_icon_style == 'cp-icon-style-simple'){
                 		$social_style .='.'.$styleid.' .cp_3D .cp_social_share {'
                          .'     padding: 5px;'
                          .' }';
                 }


           }

           	//if icon style is normal
           $social_style .='.'.$styleid.' .cp-icon-style-simple.cp-normal i,'
                          .'.'.$styleid.' .cp_social_networks.cp_social_simple.cp-icon-style-simple.cp-normal i {'
                          .'   color:'.$icon_color.'!important;'
                          .'    background-color:transparent!important;'
                          .' }'
                          .'.'.$styleid.' .cp-icon-style-simple.cp-normal li:hover i ,'
                          .'.'.$styleid.' .cp_social_networks.cp_social_simple.cp-icon-style-simple.cp-normal li:hover i {'
                          .'   color:'.$icon_hover.'!important;'
                          .'    background-color:transparent!important;'
                          .' }';

            //apply custom text color
            $social_style .='.'.$styleid.' .cp_social_networks .cp_social_network_label ,'
            				.'.'.$styleid.' .cp_social_networks .cp_social_networkname ,'
            				.'.'.$styleid.' .cp_social_networks .cp_social_count{'
	                         .'   color:'.$cp_social_text_color.'!important;'
	                         .' }'
	                         .'.'.$styleid.' .cp_social_networks li:hover .cp_social_network_label, '
	                         .'.'.$styleid.' .cp_social_networks li:hover .cp_social_networkname,'
	                         .'.'.$styleid.' .cp_social_networks li:hover .cp_social_count,'
	                         .'.'.$styleid.' .cp_social_networks li:hover .cp_social_count span{'
	                         .'   color:'.$cp_social_text_hover_color.'!important;'
	                         .' }';

	          //set visited color none
                $social_style .='.'.$styleid.' .cp_social_networks li a:visited, .cp_social_networks li a:visited * {'
                      .'      color: inherit;'
                      .' }';

         }else{
            if( ($cp_social_icon_effect == '3D' && $cp_social_icon_shape == 'square') && ($cp_social_icon_style == 'cp-icon-style-simple') ){
                   $social_style .='.'.$styleid.' .cp_3D .cp_social_share {'
                          .'     padding: 5px;'
                          .' }';
                 }
            //set visited color none
               /* $social_style .='.'.$styleid.' .cp_social_networks li a:visited, .cp_social_networks li a:visited * {'
                      .'      color: #fff;'
                      .' }';
*/
         }

         //if icon shape is custom
         if($cp_social_icon_shape == 'border_radius'){
            $social_style .='.'.$styleid.' .cp_social_networks i.cp_social_icon {'
                          .'     border-radius: '.$social_icon_border.'px;'
                          .' }';
	        if($cp_social_icon_style =='cp-icon-style-top'){
	            $social_style .='.'.$styleid.' .cp_social_networks li {'
                          .'     border-radius: '.$social_icon_border.'px!important;'
                          .' }';
	        }
         }

         //if apply border-radius to container
         if( $cp_social_icon_style !== 'cp-icon-style-simple' && $cp_social_icon_style !== 'cp-icon-style-top' && $social_container_border !== '' ){
            $social_style .='.'.$styleid.' .cp_social_networks.cp_social_left li {'
                          .'     border-radius: '.$social_container_border.'px;'
                          .' }';
         }

		 //apply no of column to container
        if($cp_social_icon_column == 'auto'){
             $social_style .='.'.$styleid.' .cp_social_networks .cp_social_icons_container {'
                          .'     margin-bottom: -15px!important;'
                          .' }';

            $social_style .='.'.$styleid.' .cp_social_networks.cp_social_autowidth .cp_social_icons_container {'
                          .'     text-align:'.$cp_social_icon_align.';'
                          .' }';
        }

           // 	Append CSS code
		echo '<style type="text/css" class="cp-social-css">'.$social_style.'</style>';
}
