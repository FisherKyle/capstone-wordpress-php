<?php

/**
 *	Get info bar Image URL
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_get_info_bar_image_url_init" ) ) {
	function cp_get_info_bar_image_url_init( $a = '' ) {

		if( !isset($a['info_bar_img_src']) ) {
			$a['info_bar_img_src'] = 'upload_img';
		}
		if( $a['info_bar_img_src'] == 'custom_url' ) {
			$image = $a['info_bar_img_custom_url'];
		} else if( $a['info_bar_img_src'] == 'upload_img' ) {
			if ( strpos($a['info_bar_image'],'http') !== false ) {
				$image = explode( '|', $a['info_bar_image'] );
				$image = $image[0];
			} else {
				$image = apply_filters('cp_get_wp_image_url', $a['info_bar_image'] );
		   	}
		} else {
			$image = '';
		}
	   	return $image;
	}
}
add_filter( 'cp_get_info_bar_image_url', 'cp_get_info_bar_image_url_init' );

/**
 *	Get info bar Image ALt text
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_get_info_bar_image_alt_init" ) ) {
	function cp_get_info_bar_image_alt_init( $a = '' ) {

		$alt = '';
		if( !isset($a['info_bar_img_src']) ) {
			$a['info_bar_img_src'] = 'upload_img';
		}
		if( $a['info_bar_img_src'] == 'upload_img' ) {
			if ( strpos($a['info_bar_image'],'http') !== false ) {
			} else {
				$image_alt = explode( '|', $a['info_bar_image'] );
				if( sizeof($image_alt) > 2){
				 $alt = "alt='".$image_alt[2]."'";
				}
		   	}
		}
	   	return $alt;
	}
}
add_filter( 'cp_get_info_bar_image_alt', 'cp_get_info_bar_image_alt_init' );


/**
 *	Get WordPress attachment url
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_get_wp_image_url_init" ) ) {
	function cp_get_wp_image_url_init( $wp_image = '') {
		if( cp_is_not_empty($wp_image) ){
			$wp_image = explode("|", $wp_image);
			$wp_image = wp_get_attachment_image_src($wp_image[0],$wp_image[1]);
			$wp_image = $wp_image[0];
		}
		return $wp_image;
	}
}
add_filter( 'cp_get_wp_image_url', 'cp_get_wp_image_url_init' );

/**
 * Generate CSS from dev input
 *
 * @param string 		- $prop
 * @param alphanumeric	- $val
 * @param string		- $suffix
 * @return string 		- Generate & return CSS (e.g. font-size: 16px;)
 * @since 0.1.5
 */
if( !function_exists( "cp_add_css" ) ) {
	function cp_add_css($prop, $val, $suffix = '') {
		$op = '';
		if( $val != '') {
			if( $suffix != '' ) {
				$op = $prop. ':' .esc_attr( $val ) . $suffix. ';';
			} else {
				$op = $prop. ':' .esc_attr( $val ). ';';
			}
		}
		return $op;
	}
}

/**
 *	= Enqueue mobile detection js
 *
 * @param string
 * @return string
 * @since 0.1.0
 *-----------------------------------------------------------*/
 if( !function_exists( "cp_enqueue_detect_device" ) ){
	function cp_enqueue_detect_device( $devices ) {
		 if (wp_script_is( 'cp-detect-device', 'enqueued' )) {
	       return;
	     } else {
			wp_enqueue_script('cp-detect-device' );
		}

	}
}

if( !function_exists( 'generateBorderCss' ) ){
	function generateBorderCss($string){
		$pairs = explode( '|', $string );
		$result = array();
		foreach( $pairs as $pair ){
			$pair = explode( ':', $pair );
			$result[ $pair[0] ] = $pair[1];
		}

		$cssCode1 = '';
		$cssCode1 .= $result['br_tl'] . 'px ' . $result['br_tr'] . 'px ' . $result['br_br'] . 'px ';
		$cssCode1 .= $result['br_bl'] . 'px';
		$result['border_width']=' ';
		$text = '';
		$text .= 'border-radius: ' . $cssCode1 .';';
		$text .= '-moz-border-radius: ' . $cssCode1 .';';
		$text .= '-webkit-border-radius: ' . $cssCode1 .';';
		$text .= 'border-style: ' . $result['style'] . ';';
		$text .= 'border-color: ' . $result['color'] . ';';
		$text .= 'border-width: ' . $result['border_width'] . 'px;';
		$text .= 'border-top-width:'. $result['bw_t'] .'px;';
	    $text .= 'border-left-width:'. $result['bw_l'] .'px;';
	    $text .= 'border-right-width:'. $result['bw_r'] .'px;';
	    $text .= 'border-bottom-width:'. $result['bw_b'] .'px;';

		return $text;
	}
}

if( !function_exists( 'generateBoxShadow' )) {
	function generateBoxShadow($string){
		$pairs = explode( '|', $string );
		$result = array();
		foreach( $pairs as $pair ) {
			$pair = explode( ':', $pair );
			$result[$pair[0]] = $pair[1];
		}

		$res = '';
		if ( isset( $result['type'] ) && $result['type'] !== 'outset' )
			$res .= $result['type'] . ' ';

		$res .= $result['horizontal'] . 'px ';
		$res .= $result['vertical'] . 'px ';
		$res .= $result['blur'] . 'px ';
		$res .= $result['spread'] . 'px ';
		$res .= $result['color'];

		$style = 'box-shadow:'.$res.';';
		$style .= '-webkit-box-shadow:'.$res.';';
		$style .= '-moz-box-shadow:'.$res.';';

		if( $result['type'] == 'none' ) {
			$style = '';
		}

		return $style;
	}
}

add_filter('cp_custom_css','cp_custom_css_filter',99,2);
if( !function_exists( "cp_custom_css_filter" ) ) {
	function cp_custom_css_filter($style_id, $css){
		if( $css !== "" ) {
			echo '<style type="text/css" id="custom-css-'.$style_id.'">'.$css.'</style>';
		}
	}
}

/**
 *	Get Info bar Image URL
 *
 * @since 0.1.5
 */
function cp_get_ib_image_url( $ib_image = '' ) {
	if ( strpos( $ib_image, 'http' ) !== false ) {
		$ib_image = explode( '|', $ib_image );
		$ib_image = $ib_image[0];
	} else {
		$ib_image = explode( "|", $ib_image );
		$ib_image = wp_get_attachment_image_src( $ib_image[0], $ib_image[1] );
		$ib_image = $ib_image[0];
   	}
   	return $ib_image;
}

/**
 * Visibility on Browser, Devices & OS
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_info_bar_visibility_on_devices_browser_os_init" ) ) {
	function cp_info_bar_visibility_on_devices_browser_os_init( $hide_on_device = '', $hide_on_os = '', $hide_on_browser = '' ) {
		$op = '';
		if( $hide_on_device != '' ){
			$op .= ' data-hide-on-devices="'.$hide_on_device.'" ';
		}
		if( $hide_on_os != '' ){
			$op .= ' data-hide-on-os="'.$hide_on_os.'" ';
		}
		if( $hide_on_browser != '' ){
			$op .= ' data-hide-on-browser="'.$hide_on_browser.'" ';
		}
		return $op;
	}
}
add_filter( 'cp_info_bar_visibility', 'cp_info_bar_visibility_on_devices_browser_os_init');

/**
 *	Set scroll class for modal
 *
 * @since 0.1.5
 */
add_filter( 'cp_get_scroll_class', 'cp_get_scroll_class_init' );

if( !function_exists( "cp_get_scroll_class_init" ) ) {
	function cp_get_scroll_class_init( $scroll_class) {
		$scroll_class = $scroll_class;
		$scroll_class  = str_replace( " ", "", trim( $scroll_class ) );
		$scroll_class  = str_replace( ",", " ", trim( $scroll_class ) );
		//$scroll_class .= ' cp-'.$style_id;
		$scroll_class = trim( $scroll_class );
		return $scroll_class;
	}
}

/**
 * Info Bar Before
 *
 * @since 0.2.3
 */
if( !function_exists( "cp_ib_global_before_init" ) ) {
	function cp_ib_global_before_init( $a ) {

		$style_id = ( isset( $a['style_id'] ) ) ? $a['style_id'] : '';

		$uid = $a['uid'];

		$isIbInline = ( isset( $a['display'] ) && $a['display'] == "inline" ) ? true : false;

		$ib_class_name = '.cp-info-bar';
		if( $isIbInline ){
			$ib_class_name = '.cp-info-bar-inline';
			$uid = ( isset( $a['style_id'] ) ) ? $a['style_id'] : '';
		} else {
			$ib_class_name = '.cp-info-bar';
		}

		// check referrer detection
		$referrer_check = ( isset( $a['enable_referrer'] ) && (int)$a['enable_referrer'] ) ? 'display' : 'hide';
		$referrer_domain = ( $referrer_check == 'display' ) ? $a['display_to'] : $a['hide_from'];

		if( $referrer_check !== '' ){
			$referrer_data = 'data-referrer-domain="'.$referrer_domain.'"';
			$referrer_data .= ' data-referrer-check="'.$referrer_check.'"';
		} else {
			$referrer_data = "";
		}

		// check close after few second
		$autoclose_on_duration  = ( isset( $a['autoclose_on_duration'] ) && (int)$a['autoclose_on_duration'] ) ? $a['autoclose_on_duration'] : '';
		$close_module_duration = ( isset( $a['close_module_duration'] ) && (int)$a['close_module_duration'] ) ? $a['close_module_duration'] : '';
		$autoclose_data = '';
		$isInline = ( isset( $a['display'] ) && $a['display'] == "inline" ) ? true : false;
		if( $autoclose_on_duration !== '' && !$isInline && ( isset( $a['toggle_btn'] ) && $a['toggle_btn'] !== '1' ) && (isset( $a['close_info_bar']) && $a['close_info_bar']!=='do_not_close' ) ){
			$autoclose_data = 'data-close-after="'.$close_module_duration.'"';
		}

		// Enqueue Google Fonts
		cp_enqueue_google_fonts( $a['cp_google_fonts'] );

		//	Enqueue detect device
		if($a['hide_on_device']){
			cp_enqueue_detect_device( $a['hide_on_device'] );
		}

		// push down page only if info bar position is at top
		if( !$isIbInline && $a['infobar_position'] == 'cp-pos-top' && $a['page_down'] ) {
			$page_down = 1;
		} else {
			$page_down = 0;
		}

		$css = '';

		/**
		 * 	Shadow & Border
		 *
		 */
		$cp_info_bar_class = '';

		/* Shadow */
		$cp_info_bar_class .= ( $a['enable_shadow'] != '' && $a['enable_shadow'] == '1' ) ? 'cp-info-bar-shadow' : '';

		/* Border */
		if( $a['enable_border'] != '' && $a['enable_border'] == '1' ) {

			$cp_info_bar_class .= ' cp-info-bar-border';

			// Generate the BORDER COLOR
			if(isset($a['border_darken'])){
				$css .= $ib_class_name.'.content-'.$uid.'.cp-pos-top.cp-info-bar-border {
						     border-bottom: 2px solid '. $a['border_darken']. '
						}
						'.$ib_class_name.'.content-'.$uid.'.cp-pos-bottom.cp-info-bar-border {
						     border-top: 2px solid '. $a['border_darken']. '
						}';
			}
		}

		// Custom CSS
		$css .=  $a['custom_css'];

		/**
		 * 	Toggle Button
		 */
		$font = 'sans-serif';
		if( $a['toggle_button_font'] ) {
			$font = $a['toggle_button_font'] . ',' . $font;
		}

		$css .= '.cp-info-bar.content-'.$uid.' .cp-ifb-toggle-btn {
					font-family: ' . $font . '
				}';

		/**
		 * 	Background - (Background Color / Gradient)
		 *
		 */
		if( $a['bg_gradient'] != '' && $a['bg_gradient'] == '1' ) {

			$css .= $ib_class_name.'.content-'.$uid.' .cp-info-bar-body-overlay {
						     background: -webkit-linear-gradient(' . $a['bg_gradient_lighten'] . ', ' . $a['bg_color'] . ');
						     background: -o-linear-gradient(' . $a['bg_gradient_lighten'] . ', ' . $a['bg_color'] . ');
						     background: -moz-linear-gradient(' . $a['bg_gradient_lighten'] . ', ' . $a['bg_color'] . ');
						     background: linear-gradient(' . $a['bg_gradient_lighten'] . ', ' . $a['bg_color'] . ');
						}';
		} else {

			$css .= $ib_class_name.'.content-'.$uid.' .cp-info-bar-body-overlay {
							background: ' . $a['bg_color'] . ';
						}';
		}

		if( !isset( $a['info_bar_bg_image_src'] ) ) {
			$a['info_bar_bg_image_src'] = 'upload_img';
		}

		if( isset( $a['info_bar_bg_image_src'] ) && !empty( $a['info_bar_bg_image_src'] ) ) {

			if ( $a['info_bar_bg_image_src'] == 'custom_url' ) {
				$info_bar_bg_image = $a['info_bar_bg_image_custom_url'];
			} else if ( $a['info_bar_bg_image_src'] == 'upload_img' ) {
				$info_bar_bg_image = apply_filters( 'cp_get_wp_image_url', $a['info_bar_bg_image'] );
			} else {
				$info_bar_bg_image = '';
			}
		}

		if( $info_bar_bg_image != '' ) {
				$bg_repeat = $bg_pos = $bg_size = $bg_setting = "";
				if( strpos( $a['opt_bg'], "|" ) !== false ){
				    $a['opt_bg']      = explode( "|", $a['opt_bg'] );
				    $bg_repeat   = $a['opt_bg'][0];
				    $bg_pos      = $a['opt_bg'][1];
				    $bg_size     = $a['opt_bg'][2];
			        $bg_setting .= 'background-repeat: '.$bg_repeat.';';
			        $bg_setting .= 'background-position: '.$bg_pos.';';
			        $bg_setting .= 'background-size: '.$bg_size.';';
				}
			$css .= $ib_class_name.'.content-'.$uid.' .cp-info-bar-body {
					    background: url(' . $info_bar_bg_image . ');
					    '.$bg_setting.'
					}';
		} else {
			$css .= $ib_class_name.'.content-'.$uid.' .cp-info-bar-body {
					    background: ' . $a['bg_color']. ';
					}';
		}

		$width = $a["infobar_width"].'px';

		$css .= $ib_class_name.'.content-'.$uid.' .cp-ib-container {
					width: '.$width.';
			}';

		// append css
		echo '<style type="text/css">'.$css.'</style>';

		$ib_custom_class = $ib_custom_id = '';
		if(isset($a['style_id'])){
			$ib_custom_id = 'cp-'.$a['style_id'];
		}

		//enable launch with css
		$a['enable_custom_class'] = 1;
		$enable_custom_class = (int) $a['enable_custom_class'];
		if( $enable_custom_class ){
			$ib_custom_class = $a['custom_class'];
			$ib_custom_class = str_replace( " ", "", trim( $ib_custom_class ) );
			$ib_custom_class = str_replace( ",", " ", trim( $ib_custom_class ) );
			$ib_custom_class = trim( $ib_custom_class );
		}

		if( $enable_custom_class && strpos( $ib_custom_class, 'priority_info_bar' ) !== false ) {
			$priority_cls = 'priority_info_bar';
		} else {
			$priority_cls = '';
		}

		if($enable_custom_class) {
			$ib_custom_class = trim( str_replace( 'priority_info_bar', '', $ib_custom_class ) );
		}

		$ib_custom_class .= " ".$style_id;

		$cp_settings = get_option('convert_plug_settings');
		$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '60';
		$inactive_data = '';
		if( $a['inactivity'] ) {
			$inactive_data = 'data-inactive-time="'.$user_inactivity.'"';
		}

		//scroll up to specific class
		$scroll_data = $scroll_class = '';
		$enable_scroll_class = isset( $a['enable_scroll_class'] ) ? $a['enable_scroll_class'] : '';
		$enable_custom_scroll = isset( $a['enable_custom_scroll'] ) ? $a['enable_custom_scroll'] : '';

		if($enable_custom_scroll){
			if( $enable_scroll_class!='' ){
				$scroll_class 	= cp_get_scroll_class_init( $a['enable_scroll_class'] );
				$scroll_data 	= 'data-scroll-class = "'.$scroll_class.'"';
			}
		}
		$isScheduled = '';
		$schedule = isset( $a['schedule'] ) ? $a['schedule'] : '';

		if( is_array( $schedule ) && $a['live'] == '2' ) {
			$isScheduled = ' data-scheduled="true" data-start="'.$schedule['start'].'" data-end="'.$schedule['end'].'" ';
		} else {
			$isScheduled = ' data-scheduled="false" ';
		}

		$timezone = '';
		$timezone_settings = get_option('convert_plug_settings');
		$timezone_name = $timezone_settings['cp-timezone'];
		if( $timezone_name != '' && $timezone_name != 'system' ){
			$timezone = get_option('timezone_string');
			if( $timezone == '' ){
				$toffset = get_option('gmt_offset');
				$timezone = "".$toffset."";
			}
	    } else {
	    	$timezone = get_option('timezone_string');
			if( $timezone == '' ){
				$toffset = get_option('gmt_offset');
				$timezone = "".$toffset."";
			}
	    }

	    //find out offset

		if( !function_exists( "getOffsetByTimeZone" ) ) {
			function getOffsetByTimeZone($localTimeZone)
				{
				$time = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone($localTimeZone));
				$timezoneOffset = $time->format('P');
				return $timezoneOffset;
				}
		}

		$schedular_tmz_offset = get_option('gmt_offset');
		if($schedular_tmz_offset == ''){
		 	$schedular_tmz_offset = getOffsetByTimeZone(get_option('timezone_string'));
		}

		$el_class = $info_bar_size_style = $close_class = '';

		$ib_exit_intent = apply_filters( 'cp_has_enabled_or_disabled', $a['ib_exit_intent'] );
		$load_on_refresh = apply_filters( 'cp_has_enabled_or_disabled', $a['display_on_first_load'] );

		if( !$a['autoload_on_scroll'] ) {
			$load_after_scroll = '';
		} else {
			$load_after_scroll = $a['load_after_scroll'];
		}

		if( !$a['autoload_on_duration'] ) {
			$load_on_duration = '';
		} else {
			$load_on_duration = $a['load_on_duration'];
		}

		$close_btn_on_duration = '';
		if( isset( $a['display_close_on_duration'] ) && $a['display_close_on_duration'] && $a['close_modal'] !== 'do_not_close' ) {
			$close_btn_on_duration  .= "data-close-btnonload-delay=".$a['close_btn_duration'];
		}

		$dev_mode = 'disabled';
		if( !$a['developer_mode'] ){
			$closed_cookie = $conversion_cookie = 0;
			$dev_mode = 'enabled';
		} else {
			$dev_mode = 'disabled';
			$closed_cookie = $a['closed_cookie'];
			$conversion_cookie = $a['conversion_cookie'];
		}

		$data_redirect = '';
		$on_success = ( isset( $a['on_success']) ? $a['on_success'] : '' );
		$on_redirect = ( isset( $a['on_redirect']) ? $a['on_redirect'] : '' );

		if( $on_success == 'redirect' && $a['redirect_url'] !== '' && (int)$a['redirect_data'] ){
			$data_redirect .= 'data-redirect-lead-data="'.$a['redirect_data'].'"';
		}

		if( $on_success == 'redirect' && $a['redirect_url']  != '' && $on_redirect!== '' ) {
			$data_redirect .= ' data-redirect-to ="'.$on_redirect.'" ';
		}

		$global_info_bar_settings = 'data-closed-cookie-time="'.$closed_cookie.'" data-conversion-cookie-time="'.$conversion_cookie.'" data-info_bar-id="'.$style_id.'" data-info_bar-style="'.$style_id.'" data-entry-animation="'. $a['entry_animation'] .'" data-exit-animation="'. $a['exit_animation'] .'" data-option="smile_info_bar_styles"' . $inactive_data . ' '.$scroll_data;
		$global_class = 'global_info_bar_container';

		if( $a['fix_position'] ){
			$global_class .= ' ib-fixed';
		}

		//	Apply box shadow to submit button - If its set & equals to - 1
		$shadow = $radius = '';
		if( isset($a['btn_shadow']) && $a['btn_shadow'] != '' ) {
			$shadow .= 'box-shadow: 1px 1px 2px 0px rgba(66, 66, 66, 0.6);';
		}

		//	Add - border-radius
		if( isset( $a['btn_border_radius'] ) && $a['btn_border_radius'] != '' ) {
			$radius .= 'border-radius: ' . $a['btn_border_radius'] .'px;';
		}

		//	Disable toggle button if button link is 'do_not_close'
		if( $a['close_info_bar'] == 'do_not_close' ){
			$a['toggle_btn'] = 0;
		}

		//toggle btn css
	    $toggle_normal 		 = $a['toggle_button_bg_color'];
		$toggle_hover  		 = $a['toggle_button_bg_hover_color'];
		$toggle_light 	  	 = $a['toggle_button_bg_gradient_color'];
		$toggle_text_color	 = $a['toggle_button_text_color'];

		$toggle_btn_style = '';
		if( $a['toggle_btn_gradient'] == '1' ) {
			$toggle_btn_style = 'cp-btn-gradient';
		} else {
			$toggle_btn_style = 'cp-btn-flat';
		}

		$ifb_toggle_btn_style = '';
		switch( $toggle_btn_style ) {
			case 'cp-btn-flat':
					$ifb_toggle_btn_style	    .= $ib_class_name.'.content-'.$uid.' .' . $toggle_btn_style . '.cp-ifb-toggle-btn{ background: '.$toggle_normal.'!important; color:'.$toggle_text_color.'; } '
												. $ib_class_name.'.content-'.$uid.'  .'.$toggle_btn_style . '.cp-ifb-toggle-btn:hover { background: '.$toggle_hover.'!important; } ';
				break;

			case 'cp-btn-gradient': 	//	Apply box $shadow to submit button - If its set & equals to - 1
					$ifb_toggle_btn_style  .= $ib_class_name.'.content-'.$uid.' .'. $toggle_btn_style . '.cp-ifb-toggle-btn {'
												. '     border: none ;'
												. '     background: -webkit-linear-gradient(' . $toggle_light . ', ' . $toggle_normal . ') !important;'
												. '     background: -o-linear-gradient(' . $toggle_light . ', ' . $toggle_normal . ') !important;'
												. '     background: -moz-linear-gradient(' . $toggle_light . ', ' . $toggle_normal . ') !important;'
												. '     background: linear-gradient(' . $toggle_light . ', ' . $toggle_normal . ') !important;'
												. '     color:'.$toggle_text_color.'; }'
												. $ib_class_name.'.content-'.$uid.' .' . $toggle_btn_style . '.cp-ifb-toggle-btn:hover {'
												. '     background: ' . $toggle_normal . ' !important;'
												. '}';
				break;
		}
		echo '<style class="cp-toggle-btn" type="text/css">'.$ifb_toggle_btn_style.'</style>';

		//for second button----
		$ifb_ib_style  = '';

		//	Apply box ifb_shadow to submit button - If its set & equals to - 1
		$ifb_shadow = $ifb_radius = '';
		if( isset($a['ifb_btn_shadow']) && $a['ifb_btn_shadow'] != '' ) {
			$ifb_shadow .= 'box-shadow: 1px 1px 2px 0px rgba(66, 66, 66, 0.6);';
		}
		//	Add - border-radius
		if( isset( $a['ifb_btn_border_radius'] ) && $a['ifb_btn_border_radius'] != '' ) {
			$ifb_radius .= 'border-radius: ' . $a['ifb_btn_border_radius'] .'px;';
		}

		$ifb_ib_style = $ifb_light = $ifb_c_normal ='';
		if( isset( $a['ifb_btn_style'] ) && $a['ifb_btn_style'] !== '' ) {
			$ifb_c_normal 	= $a['ifb_button_bg_color'];
			$ifb_c_hover  = $a['ifb_btn_darken'];
			$ifb_light = $a['ifb_btn_gradiant'];

			switch( $a['ifb_btn_style'] ) {
				case 'cp-btn-flat':
						$ifb_ib_style	.= $ib_class_name.'.content-'.$uid.' .' . $a['ifb_btn_style'] . '.cp-second-submit-btn{ background: '.$ifb_c_normal.'!important;' .$ifb_shadow .';'. $ifb_radius . ' } '
													. $ib_class_name.'.content-'.$uid.'  .'.$a['ifb_btn_style'] . '.cp-second-submit-btn:hover { background: '.$ifb_c_hover.'!important; } ';
					break;
				case 'cp-btn-3d':
				 		$ifb_ib_style 	.= $ib_class_name.'.content-'.$uid.' .'. $a['ifb_btn_style'] . '.cp-second-submit-btn {background: '.$ifb_c_normal.'!important; '.$ifb_radius.' position: relative ; box-shadow: 0 6px ' . $ifb_c_hover . ';} '
													. $ib_class_name.'.content-'.$uid.' .'. $a['ifb_btn_style'] . '.cp-second-submit-btn:hover {background: '.$ifb_c_normal.'!important;top: 2px; box-shadow: 0 4px ' . $ifb_c_hover . ';} '
													. $ib_class_name.'.content-'.$uid.' .' . $a['ifb_btn_style'] . '.cp-second-submit-btn:active {background: '.$ifb_c_normal.'!important;top: 6px; box-shadow: 0 0px ' . $ifb_c_hover . ';} ';
					break;
				case 'cp-btn-outline':
						$ifb_ib_style 	.= $ib_class_name.'.content-'.$uid.' .'. $a['ifb_btn_style'] . '.cp-second-submit-btn { background: transparent!important;border: 2px solid ' . $ifb_c_normal . ';color: inherit ;' . $ifb_shadow . $ifb_radius . '}'
													. $ib_class_name.'.content-'.$uid.' .'. $a['ifb_btn_style'] . '.cp-second-submit-btn:hover { background: ' . $ifb_c_hover . '!important;border: 2px solid ' . $ifb_c_hover . ';color: ' . $a['ifb_button_txt_hover_color'] . ' ;' . '}'
													. $ib_class_name.'.content-'.$uid.' .'. $a['ifb_btn_style'] . '.cp-second-submit-btn:hover span { color: inherit !important ; } ';
					break;
				case 'cp-btn-gradient': 	//	Apply box $ifb_shadow to submit button - If its set & equals to - 1
						$ifb_ib_style  .= $ib_class_name.'.content-'.$uid.' .'. $a['ifb_btn_style'] . '.cp-second-submit-btn {'
													. '     border: none ;'
													. 		$ifb_shadow . $ifb_radius
													. '     background: -webkit-linear-gradient(' . $ifb_light . ', ' . $ifb_c_normal . ') !important;'
													. '     background: -o-linear-gradient(' . $ifb_light . ', ' . $ifb_c_normal . ') !important;'
													. '     background: -moz-linear-gradient(' . $ifb_light . ', ' . $ifb_c_normal . ') !important;'
													. '     background: linear-gradient(' . $ifb_light . ', ' . $ifb_c_normal . ') !important;'
													. '}'
													. $ib_class_name.'.content-'.$uid.' .' . $a['ifb_btn_style'] . '.cp-second-submit-btn:hover {'
													. '     background: ' . $ifb_c_normal . ' !important;'
													. '}';
					break;
			}
		}

		echo '<style class="cp-ifb-second_submit" type="text/css">'.$ifb_ib_style.'</style>';

		ob_start();
		$data = get_option( 'convert_plug_debug' );
        $push_page_input = isset($data['push-page-input']) ? $data['push-page-input'] : '';
        $top_offset_container = isset($data['top-offset-container']) ? $data['top-offset-container'] : '';

        //hide on mobile devices
        $cp_info_bar_visibility	= apply_filters( 'cp_info_bar_visibility', $a['hide_on_device'] ); 		//	Visibility on Browser, Devices & OS
        $global_info_bar_settings .= $cp_info_bar_visibility;

        if( $a['close_info_bar_pos'] == 0 )
        	$ib_close_class = 'ib-close-inline';
        else
        	$ib_close_class = 'ib-close-outside';

        // check if info bar should be triggered after post
		$enable_after_post = (int) ( isset( $a['enable_after_post'] ) ? $a['enable_after_post'] : 0 );
		if( $enable_after_post ) {
			 $global_class .= ' ib-after-post';
		}

		// check if inline display is set
		$cp_info_bar_class .= " cp-info-bar";
		$isInline = ( isset( $a['display'] ) && $a['display'] == "inline" ) ? true : false;
		if( $isInline ){
			$cp_info_bar_class .= " cp-info-bar-inline";
			$cp_info_bar_class .= ' content-'.$style_id;
			$a['entry_animation'] = '';
			$a['exit_animation'] = '';
		}

		//	Enable animation initially
        $toggl_class_name  = '';
        $toggle_container_class = '';
        if( !$isInline && $a['toggle_btn'] == 1 ) {
        	 $toggl_class_name = 'cp-ifb-with-toggle';
        }

        if( !$isInline && isset( $a['toggle_btn_visible'] ) && $a['toggle_btn_visible'] == '1' && $a['toggle_btn'] == '1' ) {
        	$cp_info_bar_class .= ' cp-ifb-hide';
        } else {
        	$toggle_container_class = ' smile-animated ' .$a['entry_animation'];
        }

        $toggle_container_class .= " ".$toggl_class_name;

        if ( ( isset( $a['manual'] ) && $a['manual'] == 'true' ) || ( isset( $a['display'] ) && $a['display'] == 'inline' ) )
        	$ib_onload = '';
        else
        	$ib_onload = 'cp-ib-onload';

        //	Is InfoBar InLine?
		$isInline = ( isset( $a['display'] ) && $a['display'] == "inline" ) ? true : false;

        $cp_settings = get_option('convert_plug_debug');
		$after_content_scroll = isset( $cp_settings['after_content_scroll'] ) ? $cp_settings['after_content_scroll'] : '50';
		$after_content_data = 'data-after-content-value="'. $after_content_scroll .'"';

        $alwaysVisible = ( ( isset($a['toggle_btn']) && $a['toggle_btn'] == '1' ) && ( isset($a['toggle_btn_visible']) && $a['toggle_btn_visible']  == '1' ) ) ? 'data-toggle-visible=true' : '';

		//form display/hide after sucessfull submission
		$form_data_onsubmit ='';
		$form_action_onsubmit = isset( $a['form_action_on_submit'] )? $a['form_action_on_submit'] :'';

		if( $form_action_onsubmit == 'reappear' ){
			$form_data_onsubmit .= 'data-form-action = reappear';
			$form_data_onsubmit .= ' data-form-action-time ='.$a['form_reappear_time'];
		}else if( $form_action_onsubmit == 'disappears' ){
			$form_data_onsubmit .= 'data-form-action = disappear';
			$form_data_onsubmit .= ' data-form-action-time ='.$a['form_disappears_time'];
		}

		?>

		<input type="hidden" id="cp-push-down-support" value="<?php echo $push_page_input; ?>">
		<input type="hidden" id="cp-top-offset-container" value="<?php echo $top_offset_container; ?>">

        <div id="<?php echo esc_attr( $ib_custom_id ); ?>" <?php echo $referrer_data; ?> <?php echo $after_content_data; ?> class="<?php echo esc_attr( $cp_info_bar_class ); ?> <?php echo esc_attr($a['style_class']); ?> cp-info-bar-container <?php echo $ib_onload; ?> cp-clear <?php echo esc_attr( $a['infobar_position'] ); echo ( !$isInline ) ? ' content-'.$uid .' '.$style_id. ' '.$ib_custom_class : "";?> <?php echo $global_class; ?> <?php echo esc_attr( $toggle_container_class ); ?>" style="min-height:<?php echo esc_attr( $a['infobar_height'] ); ?>px;" data-push-down="<?php echo esc_attr( $page_down ); ?>" data-animate-push-page="<?php echo esc_attr( $a['animate_push_page'] ); ?>" data-class="content-<?php echo $uid; ?>" <?php echo $global_info_bar_settings; echo ( !$isInline ) ? ' data-custom-class="'.esc_attr( $ib_custom_class ).'"' : ""; ?> data-load-on-refresh="<?php echo esc_attr($load_on_refresh); ?>" <?php echo $isScheduled; ?> data-timezone="<?php echo esc_attr($timezone); ?>" data-timezonename="<?php echo esc_attr( $timezone_name );?>" <?php echo $data_redirect;?> data-onload-delay="<?php echo esc_attr($load_on_duration); ?>" data-onscroll-value="<?php echo esc_attr($load_after_scroll); ?>" data-exit-intent="<?php echo esc_attr($ib_exit_intent); ?>" data-dev-mode="<?php echo esc_attr( $dev_mode ); ?>" data-tz-offset="<?php echo $schedular_tmz_offset ;?>" data-toggle="<?php echo $a['toggle_btn'] ;?>" <?php echo $alwaysVisible; ?> <?php echo esc_attr( $close_btn_on_duration ); ?> <?php echo $autoclose_data; ?> <?php echo esc_attr( $form_data_onsubmit );?>>

            <div class="cp-info-bar-wrapper cp-clear">
                <div class="cp-info-bar-body-overlay"></div>
                <div class="cp-flex cp-info-bar-body <?php echo esc_attr($ib_close_class); ?>" style="min-height:<?php echo esc_attr( $a['infobar_height'] ); ?>px;" data-height=''>
		    		<div class="cp-flex cp-ib-container">
        <?php
	}
}
add_filter( 'cp_ib_global_before', 'cp_ib_global_before_init' );


/**
 * Info Bar After
 *
 * @since 0.2.3
 */
if( !function_exists( "cp_ib_global_after_init" ) ) {
	function cp_ib_global_after_init( $a ) {

		$toggle_class = '';
		$toggle_btn_style = '';
        $ib_close_html = $ib_close_class = $close_img_class = '';
        $img_src = '';

        $edit_link = '';
		if( is_user_logged_in() ) {
			// if user has access to ConvertPlug, then only display edit style link
			if( current_user_can( 'access_cp' ) ) {
				if( isset( $a['style_id'] ) ) {
					$edit_link = cp_get_edit_link( $a['style_id'], 'info_bar', $a['style'] );
				}
			}
		}

        $style_id = ( isset( $a['style_id'] ) ) ? $a['style_id'] : '';
        $close_alt = $close_img_alt = '';
        if ( !isset( $a['close_ib_image_src'] ) ) {
			$a['close_ib_image_src'] = 'upload_img';
		}

        if( $a['close_info_bar'] == "close_img" ) {

        	if ( $a['close_ib_image_src'] == 'upload_img' ) {

	        	if ( strpos( $a['close_img'], 'http' ) !== false ) {
					$close_img_class = 'ib-img-default';
				}
	            $img_src = cp_get_ib_image_url( $a['close_img'] );
		           $close_img_alt =  explode( '|', $a['close_img'] );
		           $cnt_arr = count($close_img_alt);
		            if ( $cnt_arr > 2 ) {
						if( $close_img_alt[2]!='' ){
							$close_alt = 'alt="'.$close_img_alt[2].'"';
						}
					}

	        } else if ( $a['close_ib_image_src'] == 'custom_url' ) {
				$img_src = $a['info_bar_close_img_custom_url'];
			}else if( $a['close_ib_image_src'] == 'pre_icons' ) {
				$icon_url = plugins_url( "../../assets/images",  __FILE__) . "/" .$a['close_icon']. ".png";
				$img_src = $icon_url;
			}

            $ib_close_html = '<img src="'.$img_src.'" class="'.$close_img_class.'" '.$close_alt.' >';
			$ib_close_class = 'ib-img-close';
			$ib_img_width = "width:" . esc_attr( $a['close_img_width'] ) . "px;";

        } else {
            $ib_close_html = '<span style="color:'.$a['close_text_color'].'">'.$a['close_txt'].'</span>';
			$ib_close_class = 'ib-text-close';
			$ib_img_width = '';
        }

        if( isset( $a['display_close_on_duration'] ) && $a['display_close_on_duration']
        	&& $a['close_info_bar'] !== 'do_not_close' ) {

        	if( $a['toggle_btn_visible'] !== '1' ) {
				$ib_close_class  .= ' cp-hide-close';
			} else {
				if( isset( $a['toggle_btn'] ) && $a['toggle_btn'] == '0' && $a['toggle_btn_visible'] =='1' ) {
					$ib_close_class  .= ' cp-hide-close';
				}
			}
		}

        //toggle settings
        //	Disable toggle button if button link is 'do_not_close'
		if( $a['close_info_bar'] == 'do_not_close' ){
			$a['toggle_btn'] = 0;
		}

		if( isset( $a['toggle_btn_visible'] ) && $a['toggle_btn_visible'] !== '1' ) {
        	$toggle_class = 'cp-ifb-hide';
        } else if( isset( $a['toggle_btn_visible'] ) && $a['toggle_btn_visible'] == '1' && $a['toggle_btn'] == '1' ) {
        	$ifb_toggle_btn_anim ='smile-slideInUp';
        	if( $a['infobar_position'] == 'cp-pos-top'){
        		$ifb_toggle_btn_anim = 'smile-slideInDown';
        	}
        	$toggle_btn_style .= 'cp-ifb-show smile-animated ' .$ifb_toggle_btn_anim;
        }

        if( $a['toggle_btn_gradient'] == '1') {
			$toggle_btn_style .= ' cp-btn-gradient';
		} else {
			$toggle_btn_style .= ' cp-btn-flat';
		}

		//	Is InfoBar InLine?
		$isInline = ( isset( $a['display'] ) && $a['display'] == "inline" ) ? true : false;

		?>
		    </div><!-- cp-ib-container -->
			    <?php
			     $close_adj_class = '';
    			 $close_adjacent_position = ( isset( $a['adjacent_close_position'] ) ? $a['adjacent_close_position'] : 'cp-adjacent-right' );
	      			switch( $close_adjacent_position ){
						case 'top_left':  $close_adj_class .= ' cp-adjacent-left';
							break;
						case 'top_right': $close_adj_class .= ' cp-adjacent-right';
							break;
						case 'bottom_left': $close_adj_class .= ' cp-adjacent-bottom-left';
							break;
						case 'bottom_right': $close_adj_class .= ' cp-adjacent-bottom-right';
							break;
					}

			    if( !$isInline && $a['close_info_bar_pos'] == 0 && $a['close_info_bar'] !== "do_not_close" )  { ?>
					<div class="ib-close <?php echo esc_attr( $ib_close_class ); ?> <?php echo esc_attr( $close_adj_class );?>" style=" <?php echo esc_attr( $ib_img_width ); ?>"><?php echo do_shortcode( $ib_close_html ); ?></div>
				<?php } ?>
			</div><!-- cp-info-bar-body -->

			<?php
			if ( $edit_link !== '' ) {

				$edit_link_text = 'Edit With ConvertPlug';

				$edit_link_txt = apply_filters( 'cp_style_edit_link_text', $edit_link_text );

			 	echo "<div class='cp_edit_link'><a target='_blank' href=".$edit_link.">".$edit_link_txt."<a></div>";
			}
			?>

		</div>
		<!--toggle button-->
			<?php if( !$isInline && $a['toggle_btn'] == '1' ) { ?>
		  	   <div class="cp-ifb-toggle-btn <?php echo esc_attr(  $toggle_class .' '. $toggle_btn_style ); ?> "><?php echo do_shortcode( $a['toggle_button_title'] ); ?></div>
		  	<?php } ?>
			<?php
		    if( !$isInline && $a['close_info_bar_pos'] == 1 && $a['close_info_bar'] !== "do_not_close" )  { ?>
		        <div class="ib-close  <?php echo esc_attr( $ib_close_class ); ?> <?php echo esc_attr( $close_adj_class );?>" style=" <?php echo esc_attr( $ib_img_width ); ?>"><?php echo do_shortcode( $ib_close_html ); ?></div>
		    <?php } ?>

		    <?php if( $isInline ) { ?>
				<span class="cp-info_bar-inline-end" data-style="<?php echo $style_id; ?>"></span>
			<?php }

		    //	Disable loading for ONLY BUTTON
		    if( isset($a['mailer']) && $a['mailer'] !== '' && $a['form_layout'] != 'cp-form-layout-4' ) { ?>
		    <div class="cp-form-processing-wrap" style="position: absolute; display:none; ">
	            <div class="cp-form-after-submit" style="line-height:<?php echo esc_attr( $a['infobar_height'] ); ?>px;">
	                <div class ="cp-form-processing">
	                    <div class="smile-absolute-loader" style="visibility: visible;">
	                        <div class="smile-loader" style="width: 100px;">
	                            <div class="smile-loading-bar"></div>
	                            <div class="smile-loading-bar"></div>
	                            <div class="smile-loading-bar"></div>
	                            <div class="smile-loading-bar"></div>
	                        </div>
	                    </div>
	                </div>
	                <div class ="cp-msg-on-submit" style="color:<?php echo esc_attr( $a['message_color'] ); ?>;"></div>
	            </div>
	        </div>

		    <?php } ?>
	    </div>
	    <?php
	}
}

add_filter( 'cp_ib_global_after', 'cp_ib_global_after_init' );

/**
 *	= Enqueue Selected - Google Fonts
 *
 * @param string
 * @return string
 * @since 0.1.0
 *-----------------------------------------------------------*/
 if( !function_exists( "cp_enqueue_google_fonts" ) ){
	function cp_enqueue_google_fonts( $fonts = '' ) {

		$pairs = $GFonts = $ar = '';

		$basicFonts = array(
			"Arial",
			"Arial Black",
			"Comic Sans MS",
			"Courier New",
			"Georgia",
			"Impact",
			"Lucida Sans Unicode",
			"Palatino Linotype",
			"Tahoma",
			"Times New Roman",
			"Trebuchet MS",
			"Verdana"
		);

		if (strpos($fonts, ',') !== FALSE)
			$pairs = explode(',', $fonts);

		//	Extract selected - Google Fonts
		if(!empty($pairs)) {
			foreach ($pairs as $key => $value) {
				if( isset($value) && !empty($value) ) {
					if( !in_array( $value, $basicFonts ) ) {
						$GFonts .= str_replace(' ', '+', $value) .'|';
					}
				}
			}
		} else {
			$GFonts = $fonts;
		}

		//	Check the google fonts is enabled from BackEnd.
		$data         = get_option( 'convert_plug_settings' );
		$is_GF_Enable = isset($data['cp-google-fonts']) ? $data['cp-google-fonts'] : 1;

		//	Register & Enqueue selected - Google Fonts
		if( !empty( $GFonts ) && $is_GF_Enable ) {
			wp_register_style('cp-google-fonts' , 'https://fonts.googleapis.com/css?family='.$GFonts, null, null, null);
			wp_enqueue_style('cp-google-fonts' );
		}
	}
}


/**
 * Set value Enabled or Disabled. - Default 'enabled'
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_has_enabled_or_disabled_init" ) ) {
	function cp_has_enabled_or_disabled_init( $modal_exit_intent ) {
		$op = ( $modal_exit_intent != '' && $modal_exit_intent != '0' ) ? 'enabled' : 'disabled';
		return $op;
	}
}
add_filter( 'cp_has_enabled_or_disabled', 'cp_has_enabled_or_disabled_init' );

/**
 *	Check values are empty or not
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_is_not_empty" ) ) {
	function cp_is_not_empty($vl) {
		if( isset( $vl ) && $vl != '' ) {
			return true;
		} else {
			return false;
		}
	}
}
