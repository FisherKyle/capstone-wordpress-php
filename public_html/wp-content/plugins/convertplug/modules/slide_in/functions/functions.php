<?php
/**
 * Global Settings - Modal
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_modal_global_settings_init" ) ) {
	function cp_modal_global_settings_init( $closed_cookie, $conversion_cookie, $style_id ) {
		$op  = ' data-closed-cookie-time="'.$closed_cookie.'"';
		$op .= ' data-conversion-cookie-time="'.$conversion_cookie.'" ';
		$op .= ' data-modal-id="'.$style_id.'" ';
		$op .= ' data-modal-style="'.$style_id.'" ';
		$op .= ' data-option="smile_modal_styles" ';
		return $op;
	}
}
add_filter( 'cp_modal_global_settings', 'cp_modal_global_settings_init');

if( !function_exists( "slidein_generate_style_css" ) ) {
	function slidein_generate_style_css( $a ) {

		$style_class = ( isset( $a['style_class'] ) ) ? $a['style_class'] : '';

		/** = Submit Button - CSS
		 *-----------------------------------------------------------*/
	$shadow = $style = '';

		$style .= isset( $a['custom_css'] ) ? $a['custom_css'] : '';
		/* CP - SlideIn Styling */
		echo '<style type="text/css" id="">'.$style.'</style>';

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
		if($result['br_type'] == 1){
			$cssCode1 .= $result['br_tl'] . 'px ' . $result['br_tr'] . 'px ' . $result['br_br'] . 'px ';
			$cssCode1 .= $result['br_bl'] . 'px';
		}else{
			$cssCode1 .= $result['br_all'] . 'px';
		}

		$result['border_width']=' ';
		$text = '';
		$text .= 'border-radius: ' . $cssCode1 .';';
		$text .= '-moz-border-radius: ' . $cssCode1 .';';
		$text .= '-webkit-border-radius: ' . $cssCode1 .';';
		$text .= 'border-style: ' . $result['style'] . ';';
		$text .= 'border-color: ' . $result['color'] . ';';
		$text .= 'border-width: ' . $result['border_width'] . 'px;';

		if( $result['bw_type'] == 1 ) {
			$text .= 'border-top-width:'. $result['bw_t'] .'px;';
		    $text .= 'border-left-width:'. $result['bw_l'] .'px;';
		    $text .= 'border-right-width:'. $result['bw_r'] .'px;';
		    $text .= 'border-bottom-width:'. $result['bw_b'] .'px;';
		} else {
			$text .= 'border-width:'. $result['bw_all'] .'px;';
		}

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
 *	Add Custom CSS for
 *
 * @since 0.1.5
 */
add_filter( 'cp_custom_css','cp_custom_css_filter',99,2);
if( !function_exists( "cp_custom_css_filter" ) ) {
	function cp_custom_css_filter($style_id, $css){
		if( $css !== "" ) {
			echo '<style type="text/css" id="custom-css-'.$style_id.'">'.$css.'</style>';
		}
	}
}

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

/**
 *	Check schedule of slidein
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_is_slidein_scheduled" ) ){
	function cp_is_slidein_scheduled($schedule, $live) {
		$op = '';
		if( is_array( $schedule ) && $live=='2' ) {
			$op = ' data-scheduled="true" data-start="'.$schedule['start'].'" data-end="'.$schedule['end'].'" ';
		} else {
			$op = ' data-scheduled="false" ';
		}
		return $op;
	}
}

/**
 * Generate CSS from dev input
 *
 * @param string 		- $prop
 * @param alphanumeric	- $val
 * @param string		- $suffix
 * @return string 		- Generate & return CSS (e.g. font-size: 16px;)
 * @since 0.1.5
 */
if( !function_exists( "cp_add_css" ) ){
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
 *	Get SlideIn Image URL
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_get_slidein_image_url_init" ) ){
	function cp_get_slidein_image_url_init( $a = '' ) {
		if( $a['slidein_img_src'] == 'custom_url' ) {
			$slidein_image = $a['slidein_img_custom_url'];
		} else if( $a['slidein_img_src'] == 'upload_img' ) {
			if ( strpos($a['slidein_image'],'http') !== false ) {
				$slidein_image = explode( '|', $a['slidein_image'] );
				$slidein_image = $slidein_image[0];
			} else {
				$slidein_image = apply_filters('cp_get_wp_image_url', $a['slidein_image'] );
		   	}
		} else {
			$slidein_image = '';
		}
	   	return $slidein_image;
	}
}
add_filter( 'cp_get_slidein_image_url', 'cp_get_slidein_image_url_init' );

/**
 *	Get SlideIn Image URL
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_get_slidein_image_alt_init" ) ){
	function cp_get_slidein_image_alt_init( $a = '' ) {
		$alt = '';

		if( $a['slidein_img_src'] == 'upload_img' ) {
			if ( strpos($a['slidein_image'],'http') !== false ) {
			} else {
				$slidein_image = apply_filters('cp_get_wp_image_url', $a['slidein_image'] );
				$slidein_image_alt = explode( '|', $a['slidein_image'] );
              		if( sizeof($slidein_image_alt) > 2 ){
				 $alt = "alt='".$slidein_image_alt[2]."'";
				}
		   	}
		}
	   	return $alt;
	}
}
add_filter( 'cp_get_slidein_image_alt', 'cp_get_slidein_image_alt_init' );

/**
 *	Get WordPress attachment url
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_get_wp_image_url_init" ) ){
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
 *	Set custom class for slidein
 *
 * @since 0.1.5
 */
add_filter( 'cp_get_custom_class', 'cp_get_custom_class_init' );
if( !function_exists( "cp_get_custom_class_init" ) ) {
	function cp_get_custom_class_init( $enable_custom_class = 0, $custom_class, $style_id ) {

		$custom_class = $custom_class;
		$custom_class  = str_replace( " ", "", trim( $custom_class ) );
		$custom_class  = str_replace( ",", " ", trim( $custom_class ) );
		$custom_class .= ' cp-'.$style_id;
		$custom_class = trim( $custom_class );
		return $custom_class;
	}
}

/**
 *	Set custom class for modal
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
 * Check slidein has redirection
 *
 * @since 0.1.5
 *
 * @param bullion - $on_success
 * @param string - 	$redirect_url
 * @param string -  $redirect_data
 * @return string - Data Attribute
 */
if( !function_exists( "cp_has_redirect_init" ) ){
	function cp_has_redirect_init($on_success, $redirect_url, $redirect_data) {
		$op = '';
		if($on_success == 'redirect' && $redirect_url != '' && $redirect_data == 1){
			$op = ' data-redirect-lead-data="'.$redirect_data.'" ';
		}
		if( $on_success == 'redirect' && $redirect_url != '' && $on_redirect !== '' ) {
			$op .= ' data-redirect-to ="'.$on_redirect.'" ';
			/*if( $on_redirect == 'download' && $download_url !=='' ){
				$op .= ' data-download-url = "'.$download_url.'" ';
			}*/
		}
		return $op;
	}
}
add_filter( 'cp_has_redirect', 'cp_has_redirect_init' );

/**
 * Check slidein overlay settings
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_has_overaly_setting_init" ) ){
	function cp_has_overaly_setting_init( $overlay_effect, $disable_overlay_effect, $hide_animation_width ) {
		$op = ' data-overlay-animation = "'.$overlay_effect.'" ';
		if( $disable_overlay_effect == 1 ) {
			$op .= ' data-disable-animationwidth="'.$hide_animation_width.'" ';
		}
		return $op;
	}
}
add_filter( 'cp_has_overaly_setting', 'cp_has_overaly_setting_init' );


/**
 * Set value Enabled or Disabled. - Default 'enabled'
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_has_enabled_or_disabled_init" ) ){
	function cp_has_enabled_or_disabled_init( $slidein_exit_intent ) {
		$op = ( $slidein_exit_intent != '' && $slidein_exit_intent != '0' ) ? 'enabled' : 'disabled';
		return $op;
	}
}
add_filter( 'cp_has_enabled_or_disabled', 'cp_has_enabled_or_disabled_init' );


/**
 * Visibility on Browser, Devices & OS
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_slidein_visibility_on_devices_browser_os_init" ) ) {
	function cp_slidein_visibility_on_devices_browser_os_init( $hide_on_device = '', $hide_on_os = '', $hide_on_browser = '' ) {
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
add_filter( 'cp_slidein_visibility', 'cp_slidein_visibility_on_devices_browser_os_init');

/**
 * Affiliate - Link
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_get_affiliate_link_init" ) ){
	function cp_get_affiliate_link_init( $affiliate_setting, $affiliate_username ) {
		$op = '';
		if($affiliate_setting == 1){
			if($affiliate_username ==''){
				$affiliate_username = 'BrainstormForce';
				$op = "https://www.convertplug.com/buy?ref=BrainstormForce";
			} else {
				$op = "https://www.convertplug.com/buy?ref=".$affiliate_username."";
			}
			return $op;
		}
	}
}
add_filter( 'cp_get_affiliate_link', 'cp_get_affiliate_link_init');

/**
 * Affiliate - Class
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_get_affiliate_class_init" ) ){
	function cp_get_affiliate_class_init( $affiliate_setting, $slidein_size ) {
		$op = '';
		if($affiliate_setting == 1 &&  $slidein_size == "cp-slidein-custom-size" ){
			$op .= "cp-affilate";
		}
		return $op;
	}
}
add_filter( 'cp_get_affiliate_class', 'cp_get_affiliate_class_init');

/**
 * Affiliate - Setting
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_get_affiliate_setting_init" ) ){
	function cp_get_affiliate_setting_init( $affiliate_setting ) {
		$op =  ($affiliate_setting == 1) ? 'data-affiliate_setting='.$affiliate_setting : 'data-affiliate_setting ="0"' ;
		return $op;
	}
}
add_filter( 'cp_get_affiliate_setting', 'cp_get_affiliate_setting_init');


/**
 * Hide Image - On Mobile
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_hide_image_on_mobile_init" ) ){
	function cp_hide_image_on_mobile_init($image_displayon_mobile, $image_resp_width){
		$hide_image = '';
		if( $image_displayon_mobile == 1 ){
			$hide_image =' data-hide-img-on-mobile='.$image_resp_width;
		}
		return $hide_image;
	}
}
add_filter( 'cp_hide_image_on_mobile', 'cp_hide_image_on_mobile_init');


/**
 * Global Settings - SlideIn
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_slidein_global_settings_init" ) ){
	function cp_slidein_global_settings_init( $closed_cookie, $conversion_cookie, $style_id ) {
		$op  = ' data-closed-cookie-time="'.$closed_cookie.'"';
		$op .= ' data-conversion-cookie-time="'.$conversion_cookie.'" ';
		$op .= ' data-slidein-id="'.$style_id.'" ';
		$op .= ' data-slidein-style="'.$style_id.'" ';
		$op .= ' data-option="smile_slide_in_styles" ';
		return $op;
	}
}
add_filter( 'cp_slidein_global_settings', 'cp_slidein_global_settings_init');

/**
 * SlideIn Before
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_slidein_global_before_init" ) ){
	function cp_slidein_global_before_init( $a ) {

		//	Print CSS of the style
		slidein_generate_style_css( $a );

		$a['image_resp_width'] = '768';

		//	Enqueue detect device
		if( isset( $a['hide_on_device'] ) && $a['hide_on_device'] ) {
			cp_enqueue_detect_device( $a['hide_on_device'] );
		}

		$hide_from = isset( $a['hide_from'] ) ? $a['hide_from'] : '';

		// check referrer detection
		$referrer_check = ( isset( $a['enable_referrer'] ) && (int)$a['enable_referrer'] ) ? 'display' : 'hide';
		$referrer_domain = ( $referrer_check == 'display' ) ? $a['display_to'] : $hide_from;

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
		// check if inline display is set
		$isInline = ( isset( $a['display'] ) && $a['display'] == "inline" ) ? true : false;

		if( $autoclose_on_duration !== '' && !$isInline && ( isset( $a['toggle_btn'] ) && $a['toggle_btn'] !== '1' ) && (isset( $a['close_slidein']) && $a['close_slidein']!=='do_not_close' ) ){
			$autoclose_data = 'data-close-after="'.$close_module_duration.'"';
		}

		//	Enqueue Google Fonts\
		if( isset( $a['cp_google_fonts'] ) ){
			cp_enqueue_google_fonts( $a['cp_google_fonts'] );
		}

		$bg_repeat = $bg_pos = $bg_size = $bg_setting = "";
		if( isset($a['opt_bg']) && strpos( $a['opt_bg'], "|" ) !== false ){
			$opt_bg      = explode( "|", $a['opt_bg'] );
			$bg_repeat   = $opt_bg[0];
			$bg_pos      = $opt_bg[1];
			$bg_size     = $opt_bg[2];
			$bg_setting .= 'background-repeat: '.$bg_repeat.';';
			$bg_setting .= 'background-position: '.$bg_pos.';';
			$bg_setting .= 'background-size: '.$bg_size.';';
		}

		//	Time Zone
		$timezone = '';
		$timezoneformat = 'none';
		$timezone_settings = get_option('convert_plug_settings');
		$timezone_name = $timezone_settings['cp-timezone'];
		if( $timezone_name != '' && $timezone_name != 'system' ){
		$timezone = get_option('timezone_string');
			if( $timezone == '' ){
				$toffset = get_option('gmt_offset');
				$timezone = "".$toffset."";
				$timezoneformat = 'offset';
			}
		} else {
			$timezone = get_option('timezone_string');
			if( $timezone == '' ){
				$toffset = get_option('gmt_offset');
				$timezone = "".$toffset."";
				$timezoneformat = 'offset';
			}
		}

		//	SlideIn - Padding
		$el_class = '';
		if( isset($a['style'])&& $a['style'] =='floating_social_bar' ){
			$a['content_padding'] = 1 ;
			if( $a['slidein_position'] == 'center-left' ){
				$a['overlay_effect'] = 'smile-slideInLeft';
				$a['exit_animation'] = 'smile-slideOutLeft';
			}else{
				$a['overlay_effect'] = 'smile-slideInRight';
				$a['exit_animation'] = 'smile-slideOutRight';
			}
		}

		if( isset( $a['content_padding'] ) && !empty( $a['content_padding'] ) ) {
			$el_class .= ' cp-no-padding ';
		}

		//	SlideIn - Background Image & Background Color
		$slide_in_bg_image = $customcss  = $windowcss = $inset = $css_style = '';
		$slidein_bg_color = ( isset( $a['slidein_bg_color'] ) ) ? $a['slidein_bg_color'] : '';

		if( !isset( $a['slide_in_bg_image_src']) ) {
			$a['slide_in_bg_image_src'] = 'upload_img';
		}

		if( $a['slide_in_bg_image_src'] == 'upload_img' ) {
			if( isset( $a['slide_in_bg_image'] ) && !empty( $a['slide_in_bg_image'] ) ) {
				$slide_in_bg_image = apply_filters('cp_get_wp_image_url', $a['slide_in_bg_image'] );
			}
		} else if( $a['slide_in_bg_image_src'] == 'custom_url' ) {
			$slide_in_bg_image = $a['slide_in_bg_image_custom_url'];
		}

		//	Variables
		$uid = ( isset($a['uid']) && '' != $a['uid'] ) ? $a['uid'] : '';

		/**
		 * 	Background - (Background Color / Gradient)
		 *
		 */
		$slide_bg_style = '';
		$slidein_bg_color = isset( $a['slidein_bg_color'] ) ? $a['slidein_bg_color'] : '';
		if( isset( $a['slidein_bg_gradient'] ) && $a['slidein_bg_gradient'] != '' && $a['slidein_bg_gradient'] == '1' ) {
			$slide_bg_style .= '.slidein-overlay.content-'.$uid.' .cp-slidein-body-overlay {
						     background: -webkit-linear-gradient(' . $a['slidein_bg_gradient_lighten'] . ', ' . $slidein_bg_color . ');
						     background: -o-linear-gradient(' . $a['slidein_bg_gradient_lighten'] . ', ' . $slidein_bg_color . ');
						     background: -moz-linear-gradient(' . $a['slidein_bg_gradient_lighten'] . ', ' . $slidein_bg_color . ');
						     background: linear-gradient(' . $a['slidein_bg_gradient_lighten'] . ', ' . $slidein_bg_color . ');
						}';
		} else {
			if(isset($a['slidein_bg_color'] )){
			$slide_bg_style .= '.slidein-overlay.content-'.$uid.' .cp-slidein-body-overlay {
							background: ' . $slidein_bg_color . ';
						}';
			}
		}

		echo '<style class="cp-slidebg-color" type="text/css">'.$slide_bg_style.'</style>';


		$windowcss .= 'background-image:url(' . $slide_in_bg_image . ');' .$bg_setting .';';

		if( $slide_in_bg_image !== '' ){
			$customcss .= 'background-image:url(' . $slide_in_bg_image . ');' .$bg_setting .';';
			$windowcss .= 'background-image:url(' . $slide_in_bg_image . ');' .$bg_setting .';';
		}

		//	SlideIn - Box Shadow
		if( isset($a['box_shadow']) && $a['box_shadow'] !='' ) {
			$box_shadow_str = generateBoxShadow($a['box_shadow']);
			if ( strpos( $box_shadow_str,'inset' ) !== false ) {
				$inset 	.= $box_shadow_str.';';
				$inset 	.= "opacity:1";
			} else {
				$css_style 	.= $box_shadow_str;
			}
		}
		$close_html = $slidein_size_style = $close_class = '';

		//	Check 'has_content_border' is set for that style and add border to slidein content (optional)
		//	This option is style dependent - Developer will disable it by adding this variable
		if( !isset( $a['has_content_border'] ) || ( isset( $a['has_content_border'] ) && $a['has_content_border'] ) ) {
			if( isset($a['border']) && $a['border'] !=''){
				$css_style .= generateBorderCss($a['border']);
			}
		}

		$slide_in_ht = isset( $a['cp_slidein_height'] ) ? $a['cp_slidein_height'] : '';
		$slidein_size_style .= cp_add_css('height', $slide_in_ht );

		if( isset($a['cp_slidein_width']) ){
			$slidein_size_style .= cp_add_css('max-width', $a['cp_slidein_width'], 'px');
		}
		//$slidein_size_style .= cp_add_css('width', '100', '%');

		$windowcss = '';

		//	{START} - SAME FOR BEFORE & AFTER NEED TO CREATE FUNCTION IT's TEMP
		$close_img_class = $close_img = '';
		$close_img_prop = cp_si_close_image_setup( $a );

		$close_img = $close_img_prop['close_img'];
		$close_img_class = $close_img_prop['close_img_class'];

		if( isset($a['close_slidein']) && $a['close_slidein'] == "close_txt" ) {
			$close_html = '<span style="color:'.$a['close_text_color'].'">'.$a['close_txt'].'</span>';
		} elseif( isset($a['close_slidein']) && $a['close_slidein'] == "close_img" ){
			$close_html = '<img class="'.$close_img_class.'" src="'.$close_img.'" />';
		} else {
			$close_class = ' do_not_close ';
		}
		//	{END} - SAME FOR BEFORE & AFTER NEED TO CREATE FUNCTION IT's TEMP

		$load_after_scroll = '';
		if( $a['autoload_on_scroll'] ) {
			$load_after_scroll = $a['load_after_scroll'];
		}

		$load_on_duration = '';
		if( $a['autoload_on_duration'] ) {
			$load_on_duration = $a['load_on_duration'];
		}

		$close_btn_on_duration = '';
		if( isset( $a['display_close_on_duration'] ) && $a['display_close_on_duration'] && $a['close_slidein']!=='do_not_close' ) {
			$close_btn_on_duration  .= "data-close-btnonload-delay=".$a['close_btn_duration'];
		}

		$dev_mode = 'disabled';
		if( !$a['developer_mode'] ){
			$a['closed_cookie'] = $a['conversion_cookie'] = 0;
			$dev_mode = 'enabled';
		}

		$cp_settings = get_option('convert_plug_settings');
		$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '3000';
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
				$scroll_data 	= 'data-scroll-class="'.$scroll_class.'"';
			}
		}

		//	Variables
		$global_class 			= ' global_slidein_container ';
		//	Functions

		$schedule 				= isset($a['schedule']) ? $a['schedule'] : '';
		$isScheduled 			= cp_is_slidein_scheduled( $schedule, $a['live'] );
		//	Filters & Actions
		$data_redirect = '';
		if( isset($a['on_success']) && isset($a['redirect_url']) && isset($a['redirect_data']) && isset($a['on_redirect']) ) {
			$download_url = '';

			$data_redirect	 	= cp_has_redirect_init( $a['on_success'], $a['redirect_url'], $a['redirect_data'] , $a['on_redirect'] ,$download_url );
		}
		$overlay_effect = '';
		if( isset($a['overlay_effect']) ) {
			$overlay_effect = $a['overlay_effect'];
		}

		$hide_image = '';
		if( isset( $a['image_displayon_mobile'] ) && isset( $a['image_resp_width'] ) ) {
			$hide_image 	 	= cp_hide_image_on_mobile_init( $a['image_displayon_mobile'], $a['image_resp_width'] );
		}

		$disable_overlay_effect = isset( $a['disable_overlay_effect'] ) ? $a['disable_overlay_effect'] : '';
		$hide_animation_width   = isset( $a['hide_animation_width'] ) ? $a['hide_animation_width'] : '';

		$overaly_setting 		= cp_has_overaly_setting_init( $overlay_effect , $disable_overlay_effect, $hide_animation_width );
		$style_id 				= ( isset( $a['style_id'] ) ) ? $a['style_id'] : '';
		$style_class 			= ( isset( $a['style_class'] ) ) ? $a['style_class'] : '';
		$placeholder_font 		= '';

		//	Filters
		$custom_class 			 = cp_get_custom_class_init( $a['enable_custom_class'], $a['custom_class'], $style_id );

		$slidein_exit_intent 	 = apply_filters( 'cp_has_enabled_or_disabled', $a['slidein_exit_intent'] );
		$load_on_refresh 		 = apply_filters( 'cp_has_enabled_or_disabled', $a['display_on_first_load'] );
		$global_slidein_settings = cp_slidein_global_settings_init( $a['closed_cookie'], $a['conversion_cookie'], $style_id );
		$cp_slidein_visibility 	 = apply_filters( 'cp_slidein_visibility', $a['hide_on_device'] ); 		//	Visibility on Browser, Devices & OS

		$placeholder_color 		 = ( isset( $a['placeholder_color'] ) ) ? $a['placeholder_color'] : '';
		if ( isset( $a['placeholder_font'] ) ) {
			if( $a['placeholder_font'] == '' )
				$placeholder_font = 'inherit';
			else
				$placeholder_font = $a['placeholder_font'];
		}

		$image_position			= ( isset( $a['image_position'] ) ) ? $a['image_position'] : '';
		$exit_animation			= isset( $a['exit_animation'] ) ? $a['exit_animation'] : 'slidein-overlay-none';

		//Slide In button css
			//	Apply box shadow to submit button - If its set & equals to - 1
			$slideshadow = $slideradius = '';
			if( isset($a['side_btn_shadow']) && $a['side_btn_shadow'] != '' ) {
				$slideshadow .= 'box-shadow: 1px 1px 2px 0px rgba(66, 66, 66, 0.6);';
			}
			//	Add - border-radius
			if( isset( $a['side_btn_border_radius'] ) && $a['side_btn_border_radius'] != '' ) {
				$slideradius .= 'border-radius: ' . $a['side_btn_border_radius'] .'px;';
			}
			//slide_btn_gradient
			$side_btn_style = $slidelight = $slidebutton_class = '';

			if( isset( $a['slide_button_bg_color'] ) && $a['slide_button_bg_color'] !== '' ) {
				$slidec_normal 	= $a['slide_button_bg_color'];
				$slidec_hover  	= $a['side_button_bg_hover_color'];
				$slidelight 	= $a['side_button_bg_gradient_color'];

				$slidetext_color = $a['slide_button_text_color'];
				if(isset($a['side_btn_gradient'])){
					$a['slide_btn_gradient'] = $a['side_btn_gradient'];
				}

				$a['side_btn_style'] = '';

				if( isset( $a['slide_btn_gradient'] ) && $a['slide_btn_gradient'] == '1' ) {
					$a['side_btn_style'] = 'cp-btn-gradient';
				}else{
					$a['side_btn_style'] = 'cp-btn-flat';
				}

				switch( $a['side_btn_style'] ) {
					case 'cp-btn-flat':
							$side_btn_style	    .= '.slidein-overlay.content-'.$uid.' .' . $a['side_btn_style'] . '.cp-slide-edit-btn{ background: '.$slidec_normal.'!important;' .$slideshadow .';'. $slideradius . '; color:'.$slidetext_color.'; } '
													.'.slidein-overlay.content-'.$uid.'  .'.$a['side_btn_style'] . '.cp-slide-edit-btn:hover { background: '.$slidec_hover.'!important; } ';
						break;

					case 'cp-btn-gradient': 	//	Apply box $shadow to submit button - If its set & equals to - 1
							$side_btn_style  .= '.slidein-overlay.content-'.$uid.' .'. $a['side_btn_style'] . '.cp-slide-edit-btn {'
														. '     border: none ;'
														. 		$slideshadow . $slideradius
														. '     background: -webkit-linear-gradient(' . $slidelight . ', ' . $slidec_normal . ') !important;'
														. '     background: -o-linear-gradient(' . $slidelight . ', ' . $slidec_normal . ') !important;'
														. '     background: -moz-linear-gradient(' . $slidelight . ', ' . $slidec_normal . ') !important;'
														. '     background: linear-gradient(' . $slidelight . ', ' . $slidec_normal . ') !important;'
														. '     color:'.$slidetext_color.'; }'
														. '.slidein-overlay.content-'.$uid.' .' . $side_btn_style . 'cp-slide-edit-btn:hover {'
														. '     background: ' . $slidec_normal . ' !important;'
														. '}';
						break;
				}

		}

		//	Append - Slide In - Toggle CSS
		$font = 'sans-serif';
		if( isset( $a['toggle_button_font'] ) ) {
			$font = $a['toggle_button_font'] . ',' . $font;
		}
		$side_btn_style .=  '.cp-slide-edit-btn {
								font-family: ' . $font . ';
							}';

		echo '<style class="cp-slidebtn-submit" type="text/css">'.$side_btn_style.'</style>';

		// check if inline display is set
		$isInline = ( isset( $a['display'] ) && $a['display'] == "inline" ) ? true : false;

		//toggle button setting
		$toggleclass = $slide_toggle_class = '';
		if( ( isset( $a['toggle_btn'] ) && $a['toggle_btn'] == 1 ) && $a['close_slidein'] != 'do_not_close' &&  $a['toggle_btn_visible'] == '1' && !$isInline ) {
			$toggleclass = 'cp-hide-slide';
		}

		if( ( isset( $a['toggle_btn'] ) && $a['toggle_btn'] == 0 ) && ( isset( $a['close_slidein'] )  && $a['close_slidein'] != 'do_not_close' ) ) {
			$slide_toggle_class = 'cp-slide-without-toggle';
		}

		if( $isInline ){
			$custom_class .= ' cp-slidein-inline';
		} else {
			$custom_class .= ' cp-slidein-global';
		}

		// check if modal should be triggered after post
		$enable_after_post = (int) ( isset( $a['enable_after_post'] ) ? $a['enable_after_post'] : 0 );
		if( $enable_after_post ) {
			$custom_class .= ' si-after-post';
		}

		$cp_settings = get_option('convert_plug_debug');
		$after_content_scroll = isset( $cp_settings['after_content_scroll'] ) ? $cp_settings['after_content_scroll'] : '50';
		$after_content_data = 'data-after-content-value="'. $after_content_scroll .'"';

		if ( isset( $a['manual'] ) && $a['manual'] == 'true' )
        	$si_onload = '';
        else
        	$si_onload = 'si-onload';

        $alwaysVisible = ( ( isset($a['toggle_btn']) && $a['toggle_btn'] == '1' ) && ( isset($a['toggle_btn_visible']) && $a['toggle_btn_visible']  == '1' ) ) ? 'data-toggle-visible=true' : '';

        $slide_position ='';
        if( !$isInline ) {
        	$slide_position ='slidein-'.$a['slidein_position'];
        }
        $cp_close_body ='';
        $close_adjacent_position = ( isset( $a['adjacent_close_position'] ) ? $a['adjacent_close_position'] : 'cp-adjacent-right' );
        if( $close_adjacent_position =='top_left' && !$isInline ){
        	$cp_close_body ='cp-top-img';
        }

        $minimize_widget = isset( $a['minimize_widget'] ) ? $a['minimize_widget'] : '';
        if( !$isInline && $minimize_widget == '1' ){
        	$minimize_widget = 'minimize-widget';
        }

        $optin_widgetclass ='';
        if( isset($a['developer_mode'] ) && $a['developer_mode'] && ( $a['style'] =='optin_widget' || $a['style'] =='social_widget_box' ) ){
        	$minimize_widget .=' always-minimize-widget';
        }

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

		ob_start();
		if( !$isInline ){
	?>
<div data-class-id="content-<?php echo $uid; ?>" <?php echo $referrer_data; ?> <?php echo $after_content_data; ?> class="<?php echo $si_onload; ?> overlay-show <?php echo esc_attr( $custom_class ); ?>" data-overlay-class="overlay-zoomin" data-onload-delay="<?php echo esc_attr( $load_on_duration ); ?>" data-onscroll-value="<?php echo esc_attr( $load_after_scroll ); ?>" data-exit-intent="<?php echo esc_attr($slidein_exit_intent); ?>" <?php echo $global_slidein_settings; ?> data-custom-class="<?php echo esc_attr( $custom_class ); ?>" data-load-on-refresh="<?php echo esc_attr($load_on_refresh); ?>" data-dev-mode="<?php echo esc_attr( $dev_mode ); ?>" <?php echo $inactive_data; ?> <?php echo $cp_slidein_visibility; ?> <?php echo $alwaysVisible; ?> <?php echo $scroll_data; ?> ></div>
<?php } ?>
		<div class="cp-slidein-popup-container <?php echo esc_attr( $style_id ); ?> <?php echo $style_class. '-container'; ?>">
			<div class="slidein-overlay <?php echo $global_class;?> <?php echo ( $isInline ) ? "cp-slidein-inline  " : "" ; ?><?php echo esc_attr( $slide_toggle_class ); echo ' content-'.$uid;?> <?php echo ' ' . $close_class ; ?> <?php echo $minimize_widget;?>" data-placeholder-font="<?php echo $placeholder_font; ?>" data-class="content-<?php echo $uid; ?>" <?php echo $global_slidein_settings; ?> data-custom-class="<?php echo esc_attr( $custom_class ); ?>" data-load-on-refresh="<?php echo esc_attr($load_on_refresh); ?>" <?php echo $isScheduled; ?> data-timezone="<?php echo esc_attr($timezone); ?>" data-timezonename="<?php echo esc_attr( $timezone_name );?>" data-timezoneformat="<?php echo esc_attr($timezoneformat);?>" data-placeholder-color="<?php echo $placeholder_color; ?>" data-image-position="<?php echo $image_position ;?>" <?php echo $hide_image; ?>  <?php echo $overaly_setting;?> <?php echo $data_redirect;?> <?php echo esc_attr( $close_btn_on_duration ); ?> <?php echo $autoclose_data;?> <?php echo esc_attr( $form_data_onsubmit );?> >
				<div class="cp-slidein <?php echo $slide_position; ?>" style="<?php echo esc_attr( $slidein_size_style ); ?>">
					<div class="cp-animate-container <?php echo esc_attr( $toggleclass );?>" <?php echo $overaly_setting;?> data-exit-animation="<?php echo esc_attr( $exit_animation ); ?>">
						<div class="cp-slidein-content" id="slide-in-animate-<?php echo esc_attr( $style_id ); ?>" style="<?php echo esc_attr( $css_style ); ?>;<?php echo esc_attr( $windowcss );?>">
							<div class="cp-slidein-body <?php echo $style_class . ' ' . esc_attr( $el_class ); ?> <?php echo esc_attr( $cp_close_body );?>" style="<?php echo esc_attr( $customcss );?>">
							  <div class="cp-slidein-body-overlay cp_cs_overlay" style="<?php echo esc_attr( $inset ) ?>;"></div>
	<?php
	}
}
add_filter( 'cp_slidein_global_before', 'cp_slidein_global_before_init' );


/**
 * SlideIn After
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_slidein_global_after_init" ) ){
	function cp_slidein_global_after_init( $a ) {

		$edit_link = '';
		if( is_user_logged_in() ) {
			// if user has access to ConvertPlug, then only display edit style link
			if( current_user_can( 'access_cp' ) ) {
				if( isset( $a['style_id'] ) ) {
					if( isset( $a['style_id'] ) ) {
						$edit_link = cp_get_edit_link( $a['style_id'], 'slide_in', $a['style'] );
					}
				}
			}
		}

		$style_id 	= ( isset( $a['style_id'] ) ) ? $a['style_id'] : '';

		if( isset( $a['close_slidein'] ) && $a['close_slidein'] !== 'close_txt' )
			$cp_close_image_width = $a['cp_close_image_width']."px";
		else
			$cp_close_image_width = 'auto';

		//	{START} - SAME FOR BEFORE & AFTER NEED TO CREATE FUNCTION IT's TEMP
		$close_img_class = $close_alt = '';

		$close_img_prop = cp_si_close_image_setup( $a );

		$close_img = $close_img_prop['close_img'];
		$close_img_class = $close_img_prop['close_img_class'];
		$close_alt = $close_img_prop['close_alt'];
		if($close_alt!==''){
			$close_alt = 'alt="'.$close_alt .'"';
		}

		$close_html = $el_class = $slidein_size_style = $close_class = '';

		if( isset( $a['content_padding'] ) && $a['content_padding'] ) {
			$el_class .= 'cp-no-padding ';
		}
		$close_tooltip= $close_tooltip_end ='';
		if( isset( $a['close_slidein'] ) && $a['close_slidein'] == "close_txt" ) {
			$close_class .= 'cp-text-close';
			if( $a['close_slidein_tooltip'] == 1 ) {
				$close_tooltip ='<span class=" cp-close-tooltip cp-tooltip-icon has-tip cp-tipcontent-'.$a['style_id'].'data-classes="close-tip-content-'.$a['style_id'].'" data-position="left"  title="'. $a['tooltip_title'].'"  data-color="'.$a['tooltip_title_color'] .'" data-bgcolor="'.$a['tooltip_background'].'" data-closeid ="cp-tipcontent-'.$a['style_id'].'" data-position="left" >';
				$close_tooltip_end ='</span>';
			}
			$close_html = '<span style="color:'.$a['close_text_color'].'">'.$a['close_txt'].'</span>';
		} else if( isset( $a['close_slidein'] ) && $a['close_slidein'] == "close_img" ){
			$close_class .= 'cp-image-close';
			$close_html = '<img class="'.$close_img_class.'" src="'.$close_img.'" '.$close_alt.'/>';
		} else {
			$close_class = 'do_not_close';
		}

		if( isset( $a['display_close_on_duration'] ) && $a['display_close_on_duration'] && $a['close_slidein']!=='do_not_close' ) {

			if( $a['toggle_btn_visible']!=='1' ){
				$close_class  .= ' cp-hide-close';
			}else{
				if(isset( $a['toggle_btn'] ) && $a['toggle_btn'] == '0' && $a['toggle_btn_visible'] =='1' ){
					$close_class  .= ' cp-hide-close';
				}
			}

		}

		//	{END} - SAME FOR BEFORE & AFTER NEED TO CREATE FUNCTION IT's TEMP

		/*-- tool tip -----*/
		$tooltip_position = 'left';

		$close_adjacent_position = ( isset( $a['adjacent_close_position'] ) ? $a['adjacent_close_position'] : 'cp-adjacent-right' );
	    if($close_adjacent_position!=''){
		    switch( $close_adjacent_position ){
				case 'top_left':  $tooltip_position = 'right';
					break;
				case 'top_right': $tooltip_position = 'left';
					break;
			}
		}

		$tooltip_class = $tooltip_style = '';
		if( isset( $a['close_slidein_tooltip'] ) && $a['close_slidein_tooltip'] == 1 ) {
			$tooltip_class .= 'cp_closewith_tooltip';
			$tooltip_style .= 'color:'.$a['tooltip_title_color'].';background-color:'.$a['tooltip_background'].';border-top-color: '.$a['tooltip_background'].';';
		}

		/// Generate border radius for form processing
		$cssCode1 = '';	$formProcessCss = '';
		if(isset( $a['border']) &&  $a['border']!=''){
			$pairs = explode( '|', $a['border'] );
			$result = array();
			foreach( $pairs as $pair ){
				$pair = explode( ':', $pair );
				$result[ $pair[0] ] = $pair[1];
			}


			$cssCode1 .= $result['br_tl'] . 'px ' . $result['br_tr'] . 'px ' . $result['br_br'] . 'px ';
			$cssCode1 .= $result['br_bl'] . 'px';
			$result['border_width'] = ' ';
			$formProcessCss .= 'border-radius: ' . $cssCode1 .';';
			$formProcessCss .= '-moz-border-radius: ' . $cssCode1 .';';
			$formProcessCss .= '-webkit-border-radius: ' . $cssCode1 .';';
		}

		// check if inline display is set
		$isInline = ( isset( $a['display'] ) && $a['display'] == "inline" ) ? true : false;

		if( isset( $a['toggle_btn'] ) && $a['toggle_btn'] == '1' &&  $a['toggle_btn_visible'] == '1' && !$isInline ) {
			$slide_in_btn_class = '';
		} else {
			$slide_in_btn_class = 'cp-slide-hide-btn';
		}

		?>
							</div><!-- .cp-slidein-body -->
							</div><!-- .cp-slidein-content -->

							<?php if( isset( $a['form_layout'] ) &&  $a['form_layout'] != 'cp-form-layout-4' ) { ?>
								<div class="cp-form-processing-wrap" style="<?php echo esc_attr($formProcessCss); ?>;">
									<div class="cp-form-after-submit">
										<div class ="cp-form-processing" style="">
											<div class="smile-absolute-loader" style="visibility: visible;">
												<div class="smile-loader">
													<div class="smile-loading-bar"></div>
													<div class="smile-loading-bar"></div>
													<div class="smile-loading-bar"></div>
													<div class="smile-loading-bar"></div>
												</div>
											</div>
										</div>
										<div class ="cp-msg-on-submit"></div>
									</div>
								</div>
							<?php } ?>

							<?php
							$close_overlay_class = 'cp-inside-close';
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

							$close_overlay_class .= $close_adj_class;

							if( !$isInline ){
							?>
							<div class="slidein-overlay-close <?php echo esc_attr( $close_class ).' '.esc_attr( $close_overlay_class ); ?>" style="width: <?php echo esc_attr( $cp_close_image_width ); ?>">
								<?php if( isset( $a['close_slidein_tooltip'] ) &&  $a['close_slidein_tooltip'] == 1 ) { ?>
								<span class=" cp-tooltip-icon cp-inside-tip has-tip cp-tipcontent-<?php echo $a['style_id']; ?>" data-offset="20" data-classes="close-tip-content-<?php echo $a['style_id']; ?>" data-position="<?php echo esc_attr( $tooltip_position );?>"  title="<?php echo esc_attr( $a['tooltip_title'] );?>"  data-color="<?php echo esc_attr( $a['tooltip_title_color'] );?>" data-bgcolor="<?php echo esc_attr( $a['tooltip_background'] );?>" data-closeid ="cp-tipcontent-<?php echo $a['style_id']; ?>">
								<?php } ?>
								<?php echo $close_html; ?>
								<?php if( isset( $a['close_slidein_tooltip'] ) && $a['close_slidein_tooltip'] == 1 ){ ?></span><?php } ?>
							</div>
							<?php } ?>
							</div><!-- .cp-animate-container -->
					<?php
					if ( $edit_link !== '' ) {

						$edit_link_text = 'Edit With ConvertPlug';

						$edit_link_txt = apply_filters( 'cp_style_edit_link_text', $edit_link_text );

					 	echo "<div class='cp_edit_link'><a target='_blank' href=".$edit_link.">".$edit_link_txt."<a></div>";
					}
					?>
					</div><!-- .cp-slidein -->



					 <?php if( $isInline ) { ?>
						<span class="cp-slide_in-inline-end" data-style="<?php echo $style_id; ?>"></span>
					<?php } ?>


					<?php if( isset( $a['toggle_btn'] ) && $a['toggle_btn'] == 1 && $a['close_slidein']!=='do_not_close' ) {
						if( $a['slide_btn_gradient'] == '1' ) {
							$slidebutton_class = 'cp-btn-gradient';
						} else {
							$slidebutton_class = 'cp-btn-flat';
						}

						$slide_btn_animation = '';

						if( $a['slidein_position'] == 'center-left' ||  $a['slidein_position'] == 'top-left' ||  $a['slidein_position'] == 'top-center' ||  $a['slidein_position'] == 'top-right' ){
							$slide_btn_animation = 'smile-slideInDown';
						}
						if( $a['slidein_position'] == 'center-right' || $a['slidein_position'] == 'bottom-left' ||  $a['slidein_position'] == 'bottom-center' ||  $a['slidein_position'] == 'bottom-right' ){
							$slide_btn_animation = 'smile-slideInUp';
						}

						$a['side_btn_style'] = '';
						if( $a['slide_btn_gradient'] == '1') {
							$a['side_btn_style'] = 'cp-btn-gradient';
						} else {
							$a['side_btn_style'] = 'cp-btn-flat';
						}

						?>
						<div class="cp-toggle-container <?php echo esc_attr( $slidebutton_class ); ?> slidein-<?php echo esc_attr( $a['slidein_position'] ); ?> <?php echo $slide_in_btn_class; ?>">
							<div class="<?php echo esc_attr( $a['side_btn_style'] ) ?> cp-slide-edit-btn smile-animated  <?php echo esc_attr( $slide_btn_animation ); ?> ;" ><?php echo  html_entity_decode( $a['slide_button_title'] ) ; ?></div>
						</div>
					<?php  } ?>

		</div><!-- .slidein-overlay -->
	</div><!-- .cp-slidein-popup-container -->
	<?php
	}
}
add_filter( 'cp_slidein_global_after', 'cp_slidein_global_after_init' );

if( !function_exists('cp_si_close_image_setup') ) {

	function cp_si_close_image_setup( $a ) {
		$close_img = $close_img_class = $close_alt ='';

		if ( !isset( $a['close_si_image_src'] ) ) {
			$a['close_si_image_src'] = 'upload_img';
		}

		if ( isset( $a['close_si_image_src'] ) &&  $a['close_si_image_src'] == 'upload_img' ) {

			if( isset($a['close_img'] ) && !empty($a['close_img']) ) {
				if ( strpos($a['close_img'],'http') !== false ) {
				    $close_img = $a['close_img'];
				    if ( strpos($close_img, '|') !== FALSE ) {
						$close_img = explode( '|', $close_img );
						$close_img = $close_img[0];
					}
				    $close_img_class = 'cp-default-close';
				} else {
					$close_img = apply_filters('cp_get_wp_image_url', $a['close_img'] );
					$close_img_alt =  explode( '|', $a['close_img'] );

					if( sizeof($close_img_alt) > 2 ){
						$close_alt = $close_img_alt[2];
					}
				}
			}
		} else if ( isset( $a['close_si_image_src'] ) && $a['close_si_image_src'] == 'custom_url' ) {
			$close_img = $a['slide_in_close_img_custom_url'];
		}else if( $a['close_si_image_src'] == 'pre_icons' ) {
			$icon_url = plugins_url( "../../assets/images",  __FILE__) . "/" .$a['close_icon']. ".png";
			$close_img = $icon_url;
		}


		$close_img_prop = array (
			"close_img" => $close_img,
			"close_img_class" => $close_img_class,
			"close_alt" => $close_alt,
			);

		return $close_img_prop;

	}
}
