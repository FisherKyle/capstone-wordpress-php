<?php

/**
 * ConvertPlug Form
 *
 * 	Module 	- Count Down
 *
 * 1. 	count_down Array Setup
 * 2.	Global array for shortcode variables
 * 3.	Generate Output by 'cp_get_count_down' filter
 * 4.	Generate & Append CSS
 *
 * 	Use same names for variables & array
 * 	For '$your_options_name' use '$your_options_name_VARS'
 *
 * 	E.g. 	$cp_count_down
 *     		$cp_count_down_vars
 *
 * @since  1.1.1
 */
global $cp_count_down;
global $cp_count_down_vars;

/**
 * 1.	count_down Array Setup
 */

$cp_count_down = array(
		array(
			"type" 		=> "switch",
			"class" 	=> "",
			"name" 		=> "disable_datepicker",
			"opts"		=> array(
				"title" 	=> __( "Enable Countdown Timer", "smile" ),
				"value" 	=> true,
				"on" 		=> __( "YES", "smile" ),
				"off"		=> __( "NO", "smile" ),
			),
			"section" 	 => "Design",
			"panel" => "Countdown Timer",
			"section_icon" => "connects-icon-image",
		),
 		array(
				"type" 		=> "datetimepicker",
				"class" 	=> "",
				"name" 		=> "date_time_picker",
				"opts"		=> array(
					"title" 		=> __( "Countdown Timer", "smile" ),
					"value" 		=> "",
				),
			"dependency"	=> array("name" => "disable_datepicker", "operator" => "==", "value" => "true"),
			"section" 	 => "Design",
			"panel" => "Countdown Timer",
			"section_icon" => "connects-icon-image",
			),
 		array(
			"type" 		=> "dropdown",
			"class" 	=> "",
			"name" 		=> "datepicker_advance_option",
			"opts" 		=> array(
				"title" 	=> __( "Countdown Timer Style","smile"),
				"value" 	=> "style_1",
				"options" 	=> array(
						__( "Style 1", "smile" ) 			=> "style_1",
						__( "Style 2", "smile" ) 			=> "style_2",
					)
				),
			"dependency"	=> array("name" => "disable_datepicker", "operator" => "==", "value" => "true"),
			"section" 	 => "Design",
			"panel" => "Countdown Timer",
			"section_icon" => "connects-icon-image",
		), 		
 		array(
		    "type"         => "google_fonts",
		    "name"         => "counter_font",
		    "opts"         => array(
		        "title"     => __( "Counter Font", "smile" ),
		        "value"     => "Raleway",
		        "use_in"      => "panel",
		    ),
		    "dependency"	=> array("name" => "disable_datepicker", "operator" => "==", "value" => "true"),
			"section" 	 => "Design",
			"panel" => "Countdown Timer",
			"section_icon" => "connects-icon-image",
		),
 		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "counter_bg_color",
			"opts"		=> array(
				"title" 		=> __( "Countdown Background Color", "smile" ),
				"value" 		=> "#1bce7c",
			),
			"dependency"	=> array("name" => "datepicker_advance_option", "operator" => "==", "value" => "style_2"),
			"section" 	 => "Design",
			"panel" => "Countdown Timer",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "counter_digit_text_color",
			"opts"		=> array(
				"title" 		=> __( "Digit Color", "smile" ),
				"value" 		=> "rgb(255, 255, 255)",
				'css_selector' => '#cp_defaultCountdown , #cp_defaultCountdown .cp_countdown-amount',
            	'css_property' => 'color',	
            	'css_preview' => true,
			),
			"dependency"	=> array("name" => "disable_datepicker", "operator" => "==", "value" => "true"),
			"section" 	 => "Design",
			"panel" => "Countdown Timer",
			"section_icon" => "connects-icon-image",
		),
		
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "counter_digit_border_color",
			"opts"		=> array(
				"title" 		=> __( "Digit Border Color", "smile" ),
				"value" 		=> "#1bce7c",
				'css_selector' => '#cp_defaultCountdown .cp_countdown-amount',
            	'css_property' => 'border-color',
            	'css_preview' => true,
			),
			"dependency"	=> array("name" => "datepicker_advance_option", "operator" => "!==", "value" => "style_1"),
			"section" 	 => "Design",
			"panel" => "Countdown Timer",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "slider",
			"class" 	=> "",
			"name" 		=> "counter_digit_text_size",
			"opts"			=> array(
				"title" 		=> __( "Digit Font Size", "smile" ),
				"value" 		=> 15,
				"min" 			=> 10,
				"max" 			=> 100,
				"step" 			=> 1,
				"suffix" 		=> "px",
				'css_selector' => '.cp-count-down #cp_defaultCountdown , .cp-count-down #cp_defaultCountdown .cp_countdown-amount',
            	'css_property' => 'font-size',
            	'css_preview' => true,
			),
			"dependency"	=> array("name" => "disable_datepicker", "operator" => "==", "value" => "true"),
			"section" 	 => "Design",
			"panel" => "Countdown Timer",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "colorpicker",
			"class" 	=> "",
			"name" 		=> "counter_timer_text_color",
			"opts"		=> array(
				"title" 		=> __( "Time Unit Color", "smile" ),
				"value" 		=> "#fff",
				'css_selector' => '#cp_defaultCountdown .cp_countdown-period',
            	'css_property' => 'color',
            	'css_preview' => true,
			),
			"dependency"	=> array("name" => "datepicker_advance_option", "operator" => "!==", "value" => "style_1"),
			"section" 	 => "Design",
			"panel" => "Countdown Timer",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "slider",
			"class" 	=> "",
			"name" 		=> "counter_timer_text_size",
			"opts"			=> array(
				"title" 		=> __( "Time Unit Font Size", "smile" ),
				"value" 		=> 15,
				"min" 			=> 10,
				"max" 			=> 40,				
				"step" 			=> 1,
				"suffix" 		=> "px",
				'css_selector' => '#cp_defaultCountdown .cp_countdown-period',
            	'css_property' => 'font-size',
            	'css_preview' => true,
			),
			"dependency"	=> array("name" => "datepicker_advance_option", "operator" => "!==", "value" => "style_1"),			"section" 	 => "Design",
			"panel" => "Countdown Timer",
			"section_icon" => "connects-icon-image",
		),
		array(
			"type" 		=> "checkbox",
			"class" 	=> "",
			"name" 		=> "counter_option",
			"opts" 		=> array(
				"title" 	=> __( "Select Time Units To Display In Countdown Timer","smile"),
				"value" 	=> "D|H|M|S",
				"options" 	=> array(
						__( "Years", "smile" ) 		=> "Y",
						__( "Months", "smile" ) 	=> "O",
						__( "Weeks", "smile" ) 		=> "W",
						__( "Days", "smile" ) 		=> "D",
						__( "Hours", "smile" ) 		=> "H",
						__( "Minutes", "smile" ) 	=> "M",
						__( "Seconds", "smile" ) 	=> "S",
					)
				),
			"dependency"	=> array("name" => "disable_datepicker", "operator" => "==", "value" => "true"),
			"section" 	 => "Design",
			"panel" => "Countdown Timer",
			"section_icon" => "connects-icon-image",
		),
		
	);

/**
 * 2.	Global array for shortcode variables
 */
$cp_count_down_vars = generate_global_shortcode_vars( $cp_count_down );


/**
 * 3.	Generate Output by 'cp_get_count_down' filter
 */
add_filter( 'cp_get_count_down', 'cp_get_count_down_init' );

if( !function_exists('cp_get_count_down_init') ) {
		function cp_get_count_down_init( $a ) {

		//	apply count down styles
		apply_filters_ref_array('cp_count_down_css', array( $a ) );

		$show_datepicker = $countdown_option ='';
		$advance_dtpicker = '';
	    $show_datepicker = 'show';
	    if( $a['datepicker_advance_option'] == 'style_2'){
	      	$advance_dtpicker = $a['datepicker_advance_option'];
	      } else{
	      	$advance_dtpicker = 0 ;
	      }

	     $countdown_option .='data-advnce-countdown='.$advance_dtpicker;
		 $countdown_option .=' data-showcounter='.$show_datepicker ;	

		//build HTML structure for count down	
		 if( $a['disable_datepicker']){ 
		 	echo '<span id="cp_defaultCountdown" class="cp_count_down_main" data-timeformat = "'.$a['counter_option'].'" data-date="'.$a['date_time_picker'].'" '.$countdown_option.'></span>';
		  } 

	}
}

/**
 * 4.	Generate & Append CSS
 */
add_filter('cp_count_down_css', 'cp_count_down_css_init');

function cp_count_down_css_init( $a ) {
   	 
   	 //counter css
	 	
	 if( isset( $a['disable_datepicker'] ) && $a['disable_datepicker'] == 1 ){
	 	$counter_digit = $timer_digit = '';
		
		$counter_digit .= 'color: ' .  $a['counter_digit_text_color'] . ';';
		if( $a['counter_font'] == '' ){
			$a['counter_font'] ='inherit';
		}
		$uid = $a['uid'];
		$counter_digit .= 'font-family: ' .  $a['counter_font'] . ';';
		$counter_digit .= 'font-size: ' .  $a['counter_digit_text_size']. 'px;';
		$counter_digit .= 'border-color: ' .  $a['counter_digit_border_color'] . ';';

		//timer text css
		$timer_digit .= 'color: ' . $a['counter_timer_text_color'] . ';';
		$timer_digit .= 'font-size: ' . $a['counter_timer_text_size'] . 'px;';
		$timer_digit .= 'font-family: ' . $a['counter_font'] . ';';

		if( $a['datepicker_advance_option'] == 'style_2' ){
			$counter_digit .= 'background: ' . $a['counter_bg_color'] . ';';
			echo '<style class="cp-counter">.content-'.$uid.' .cp-count-down #cp_defaultCountdown  .cp_countdown-amount {  '. $counter_digit .'; }
			.content-'.$uid.' #cp_defaultCountdown  .cp_countdown-period { ' . $timer_digit .'; } 
			.content-'.$uid.' .cp-count-down #cp_defaultCountdown {font-size: ' . $a['counter_digit_text_size'] . 'px;}
		    </style>';
		} else {
			$counter_digit .= 'background: transparent;';
			echo '<style class="cp-counter">.content-'.$uid.' .cp-count-down #cp_defaultCountdown {  '. $counter_digit .'; } </style>';
		}

	    //countdown script
	    if ( !wp_script_is( 'cp-countdown-style', 'enqueued' ) ) {	
	    	wp_register_style( 'cp-countdown-style', plugins_url('../../modules/assets/css/jquery.countdown.css',__FILE__) );
			wp_register_script( 'cp-counter-plugin-js', plugins_url( '../../modules/assets/js/jquery.plugin.min.js', __FILE__), array( 'jquery' ), null, null, true );
			wp_register_script( 'cp-countdown-js', plugins_url( '../../modules/assets/js/jquery.countdown.js', __FILE__), array( 'jquery' ), null, null, true );
			wp_register_script( 'cp-countdown-script', plugins_url( '../../modules/assets/js/jquery.countdown.script.js', __FILE__), array( 'jquery' ), null, null, true );
		     	
	     	wp_enqueue_style( 'cp-countdown-style');
			wp_enqueue_script( 'cp-counter-plugin-js');
			wp_enqueue_script( 'cp-countdown-js');
			wp_enqueue_script( 'cp-countdown-script');	       
	    }  	 
	}
}