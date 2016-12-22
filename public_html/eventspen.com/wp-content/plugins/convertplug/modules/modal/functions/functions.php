<?php
/*
* Global functions for modal
*/

if( !function_exists( "smile_get_live_styles" )){
	function smile_get_live_styles(){
		$styles = get_option( 'smile_modal_styles' );
		$smile_variant_tests = get_option( 'modal_variant_tests' );
		$live_array = array();
		if( !empty( $styles ) ) {
			foreach( $styles as $key => $style ){
				$settings = unserialize( $style[ 'style_settings' ] );

				$split_tests = isset( $smile_variant_tests[$style['style_id']] ) ? $smile_variant_tests[$style['style_id']] : '';
				if( is_array( $split_tests ) && !empty( $split_tests ) ) {
					$split_array = array();
					$live = isset( $settings[ 'live' ] ) ? (int)$settings[ 'live' ] : false;
					if( $live ){
						array_push( $split_array, $styles[ $key ] );
					}
					foreach( $split_tests as $key => $test ) {
						$settings = unserialize( $test[ 'style_settings' ] );
						$live = isset( $settings[ 'live' ] ) ? (int)$settings[ 'live' ] : false;
						if( $live ){
							array_push( $split_array, $test );
						}
					}
					if( !empty( $split_array ) ) {
						$key 	= array_rand( $split_array, 1 );
						$array 	= $split_array[$key];
						array_push( $live_array, $array );
					}
				} else {
					$live = isset( $settings[ 'live' ] ) ? (int)$settings[ 'live' ] : false;
					if( $live ){
						array_push( $live_array, $styles[ $key ] );
					}
				}
			}
		}

		return $live_array;
	}
}


if( !function_exists( "cp_generate_style_css" )){
	function cp_generate_style_css( $a ) {

		//custom css
		//$style  = $a['custom_css'];
		$styleID = "content-".$a['uid'];
		$style  = "";
		//custom height only for blank style
		if( isset( $a['cp_custom_height'] ) && isset( $a['cp_modal_height'] ) && $a['cp_custom_height'] == '1' ) {
			$style  .= "";
			$style  .=  "." . $styleID . " .cp-modal-body { "
					. "		min-height:".$a['cp_modal_height']."px;}";
		}

		// 	Append CSS code
		echo '<style type="text/css">'.$style.'</style>';

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
		if( isset( $result['br_type'] ) && $result['br_type'] == 1 ) {
			$cssCode1 .= $result['br_tl'] . 'px ' . $result['br_tr'] . 'px ' . $result['br_br'] . 'px ';
			$cssCode1 .= $result['br_bl'] . 'px';
		} else {
			$cssCode1 .= $result['br_all'] . 'px';
		}

		$result['border_width'] = ' ';
		$text = '';
		$text .= 'border-radius: ' . $cssCode1 .';';
		$text .= '-moz-border-radius: ' . $cssCode1 .';';
		$text .= '-webkit-border-radius: ' . $cssCode1 .';';
		$text .= 'border-style: ' . $result['style'] . ';';
		$text .= 'border-color: ' . $result['color'] . ';';
		$text .= 'border-width: ' . $result['border_width'] . 'px;';

		if( isset( $result['bw_type'] ) && $result['bw_type'] == 1 ) {
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

		$default_google_fonts = array (
			"Lato",
			"Open Sans",
			"Libre Baskerville",
			"Montserrat",
			"Neuton",
			"Raleway",
			"Roboto",
			"Sacramento",
			"Varela Round",
			"Pacifico",
			"Bitter"
		);

		$allFonts = array_merge($default_google_fonts, $basicFonts);

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

			$GFonts .= implode( "|", $default_google_fonts );

		} else {
			$GFonts = implode( "|", $default_google_fonts );
		}

		//	Check the google fonts is enabled from BackEnd.
		$data         = get_option( 'convert_plug_settings' );
		$is_GF_Enable = isset($data['cp-google-fonts']) ? $data['cp-google-fonts'] : 1;

		//	Register & Enqueue selected - Google Fonts
		if( !empty( $GFonts ) && $is_GF_Enable ) {
			echo "<link rel='stylesheet' type='text/css' id='cp-google-fonts' href='https://fonts.googleapis.com/css?family=".$GFonts."'>";
		}
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
 *	Add Custom CSS for
 *
 * @since 0.1.5
 */
add_filter( 'cp_custom_css','cp_custom_css_filter', 99, 2);

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
 *	Check schedule of modal
 *
 * @since 0.1.5
 */

if( !function_exists( "cp_is_modal_scheduled" ) ) {
	function cp_is_modal_scheduled($schedule, $live) {
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
 *	Get Modal Image URL
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_get_modal_image_url_init" ) ) {
	function cp_get_modal_image_url_init( $a = '' ) {

		if( !isset($a['modal_img_src']) ) {
			$a['modal_img_src'] = 'upload_img';
		}

		if( isset( $a['modal_img_src'] ) && $a['modal_img_src'] == 'custom_url' ) {
			$modal_image = $a['modal_img_custom_url'];
		} else if( isset( $a['modal_img_src'] ) && $a['modal_img_src'] == 'upload_img' ) {
			if ( strpos($a['modal_image'],'http') !== false ) {
				$modal_image = explode( '|', $a['modal_image'] );
				$modal_image = $modal_image[0];
			} else {
				$modal_image = apply_filters('cp_get_wp_image_url', $a['modal_image'] );
		   	}
		} else {
			$modal_image = '';
		}
	   	return $modal_image;
	}
}
add_filter( 'cp_get_modal_image_url', 'cp_get_modal_image_url_init' );

if( !function_exists( "cp_get_modal_image_alt_init" ) ) {
	function cp_get_modal_image_alt_init( $a = '' ) {

		if( !isset($a['modal_img_src']) ) {
			$a['modal_img_src'] = 'upload_img';
		}
		$alt = '';
		if( isset( $a['modal_img_src'] ) && $a['modal_img_src'] == 'upload_img' ) {
			if ( strpos($a['modal_image'],'http') !== false ) {
			} else {
				$modal_image_alt = explode( '|', $a['modal_image'] );
              	if( sizeof($modal_image_alt) >2 ){
				 $alt = "alt='".$modal_image_alt[2]."'";
				}
		   	}
		}
	   	return $alt;
	}
}
add_filter( 'cp_get_modal_image_alt', 'cp_get_modal_image_alt_init' );

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
 *	Set custom class for modal
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
 * Check modal has redirection
 *
 * @since 0.1.5
 *
 * @param bullion - $on_success
 * @param string  - $redirect_url
 * @param string  - $redirect_data
 * @param string  - $on_redirect
 * @param string  - $download_url
 * @return string - Data Attribute
 */

if( !function_exists( "cp_has_redirect_init" ) ) {
	function cp_has_redirect_init( $on_success, $redirect_url, $redirect_data , $on_redirect ,$download_url ) {
		$op = '';
		if( $on_success == 'redirect' && $redirect_url != '' && $redirect_data == 1 ) {
			$op .= ' data-redirect-lead-data="'.$redirect_data.'" ';

		}
		if( $on_success == 'redirect' && $redirect_url != '' && $on_redirect !== '' ) {
			$op .= ' data-redirect-to ="'.$on_redirect.'" ';
		}

		return $op;
	}
}
add_filter( 'cp_has_redirect', 'cp_has_redirect_init' );

/**
 * Check modal overlay settings
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_has_overaly_setting_init" ) ) {
	function cp_has_overaly_setting_init( $overlay_effect, $disable_overlay_effect, $hide_animation_width ) {
		$op = ' data-overlay-animation = "'.$overlay_effect.'" ';
		if($disable_overlay_effect == 1){
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
if( !function_exists( "cp_has_enabled_or_disabled_init" ) ) {
	function cp_has_enabled_or_disabled_init( $modal_exit_intent ) {
		$op = ( $modal_exit_intent != '' && $modal_exit_intent != '0' ) ? 'enabled' : 'disabled';
		return $op;
	}
}
add_filter( 'cp_has_enabled_or_disabled', 'cp_has_enabled_or_disabled_init' );


/**
 * Visibility on Browser, Devices & OS
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_modal_visibility_on_devices_browser_os_init" ) ) {
	function cp_modal_visibility_on_devices_browser_os_init( $hide_on_device = '', $hide_on_os = '', $hide_on_browser = '' ) {
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
add_filter( 'cp_modal_visibility', 'cp_modal_visibility_on_devices_browser_os_init');

/**
 * Affiliate - Link
 *
 * @since 0.1.5
 */

if( !function_exists( "cp_get_affiliate_link_init" ) ) {
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
if( !function_exists( "cp_get_affiliate_class_init" ) ) {
	function cp_get_affiliate_class_init( $affiliate_setting, $modal_size ) {
		$op = '';
		if($affiliate_setting == 1 &&  $modal_size == "cp-modal-custom-size" ){
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
if( !function_exists( "cp_get_affiliate_setting_init" ) ) {
	function cp_get_affiliate_setting_init( $affiliate_setting ) {
		$op =  ( $affiliate_setting == 1 ) ? 'data-affiliate_setting='.$affiliate_setting : 'data-affiliate_setting ="0"' ;
		return $op;
	}
}
add_filter( 'cp_get_affiliate_setting', 'cp_get_affiliate_setting_init');


/**
 * Hide Image - On Mobile
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_hide_image_on_mobile_init" ) ) {
	function cp_hide_image_on_mobile_init( $image_displayon_mobile, $image_resp_width ) {
		$hide_image = '';
		if( $image_displayon_mobile == 1 ) {
			$hide_image =' data-hide-img-on-mobile='.$image_resp_width;
		}
		return $hide_image;
	}
}
add_filter( 'cp_hide_image_on_mobile', 'cp_hide_image_on_mobile_init');


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

/**
 * Modal Before
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_modal_global_before_init" ) ) {
function cp_modal_global_before_init( $a ) {

	if ( !isset( $a['modal_size'] ) ) {
		$a['modal_size'] = 'cp-modal-custom-size';
	}

	//	Print CSS of the style
	cp_generate_style_css( $a );

	$a['image_resp_width'] = '768';

	//	Enqueue detect device
	if($a['hide_on_device']){
		cp_enqueue_detect_device( $a['hide_on_device'] );
	}

	// check referrer detection
	$referrer_check  = ( isset( $a['enable_referrer'] ) && (int)$a['enable_referrer'] ) ? 'display' : 'hide';
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
	$isInline = ( isset( $a['display'] ) && $a['display'] == "inline" ) ? true : false;

	$autoclose_data = '';
	if( $autoclose_on_duration !== '' && (!$isInline) && ( isset( $a['close_modal'] ) && $a['close_modal']!=='do_not_close' )){
		$autoclose_data = 'data-close-after = "'.$close_module_duration.'"';
	}
	//	Enqueue Google Fonts
	cp_enqueue_google_fonts( $a['cp_google_fonts'] );

	$bg_repeat = $bg_pos = $bg_size = $bg_setting = "";
	if( isset( $a['opt_bg'] ) && strpos( $a['opt_bg'], "|" ) !== false ){
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
	$timezone_settings = get_option('convert_plug_settings');
	$timezone_name = $timezone_settings['cp-timezone'];
	if( $timezone_name != '' && $timezone_name!='system' ){
	$timezone = get_option('timezone_string');
		if( $timezone == '' ){
			$toffset = get_option('gmt_offset');
			$timezone = "".$toffset."";
		}
	} else {
		$timezone = get_option('timezone_string');
		if($timezone==''){
			$toffset = get_option('gmt_offset');
			$timezone = "".$toffset."";
		}
	}

	//	Modal - Padding
	$el_class = '';
	if( isset( $a['content_padding'] ) && !empty( $a['content_padding'] ) ) {
		$el_class .= ' cp-no-padding ';
	}

	//	Modal - Background Image & Background Color
	$modal_bg_image = $customcss  = $windowcss = $inset = $css_style = '';
	$modal_bg_color = ( isset( $a['modal_bg_color'] ) ) ? $a['modal_bg_color'] : '';
	if( !isset( $a['modal_bg_image_src'] ) ) {
		$a['modal_bg_image_src']  = 'upload_img';
	}
	if( isset( $a['modal_bg_image_src'] ) && !empty( $a['modal_bg_image_src'] ) ) {

		if ( $a['modal_bg_image_src'] == 'custom_url' ) {
			$modal_bg_image = $a['modal_bg_image_custom_url'];
		} else if ( $a['modal_bg_image_src'] == 'upload_img' ) {
			if( isset( $a['modal_bg_image'] ) ) {
				if ( strpos($a['modal_bg_image'],'http') !== false ) {
					$modal_bg_image = explode( '|', $a['modal_bg_image'] );
					$modal_bg_image = $modal_bg_image[0];
				} else {
					$modal_bg_image = apply_filters( 'cp_get_wp_image_url', $a['modal_bg_image'] );
			   	}
			}
		} else {
			$modal_bg_image = '';
		}
	}

	if( $modal_bg_image !== '' ){
		$customcss .= 'background-image:url(' . $modal_bg_image . ');' .$bg_setting .';';
		$windowcss .= 'background-image:url(' . $modal_bg_image . ');' .$bg_setting .';';
	}

	//	Modal - Box Shadow
	if( $a['box_shadow'] !== '' )  {
		$box_shadow_str = generateBoxShadow($a['box_shadow']);
		if ( strpos( $box_shadow_str,'inset' ) !== false ) {
			$inset 	.= $box_shadow_str.';';
			$inset 	.= "opacity:1";
		} else {
			$css_style 	.= $box_shadow_str;
		}
	}

	$close_html = $modal_size_style = $close_class = '';

	//	Check 'has_content_border' is set for that style and add border to modal content (optional)
	//	This option is style dependent - Developer will disable it by adding this variable
	if( !isset( $a['has_content_border'] ) || ( isset( $a['has_content_border'] ) && $a['has_content_border'] ) ) {
		if( isset( $a['border'] ) && $a['border'] !=='' ){
		 $css_style .= generateBorderCss($a['border']);
		}
	}
	if( $a['modal_size'] == "cp-modal-custom-size" ){
		$modal_size_style  = cp_add_css('width', '100', '%');
		$modal_ht = isset( $a['cp_modal_height'] ) ? $a['cp_modal_height'] : 'auto';
		$modal_size_style .= cp_add_css('height', $modal_ht );
		$modal_size_style .= cp_add_css('max-width', $a['cp_modal_width'], 'px');
		$windowcss = '';
	} else {
		$customcss = 'max-width: '.$a['cp_modal_width'].'px';
		$windowcss .= $box_shadow_str;
	}

	//	{START} - SAME FOR BEFORE & AFTER NEED TO CREATE FUNCTION IT's TEMP
	$close_img_class = $close_img = '';
	$close_img_prop = cp_close_image_setup( $a );

	$close_img = $close_img_prop['close_img'];
	$close_img_class = $close_img_prop['close_img_class'];

	if( $a['close_modal'] == "close_txt") {
		$close_html = '<span style="color:'.$a['close_text_color'].'">'.$a['close_txt'].'</span>';
	} else if( $a['close_modal'] == "close_img" ) {
		$close_html = '<img class="'.$close_img_class.'" src="'.$close_img.'"  />';
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
	if( isset( $a['display_close_on_duration'] ) && $a['display_close_on_duration'] && $a['close_modal'] !== 'do_not_close' ) {
		$close_btn_on_duration  .= "data-close-btnonload-delay=".$a['close_btn_duration'];
	}

	$dev_mode = 'disabled';
	if( !$a['developer_mode'] ){
		$a['closed_cookie'] = $a['conversion_cookie'] = 0;
		$dev_mode = 'enabled';
	}

	$close_modal_on = '';
	if( $a['close_modal_on'] )
		$close_modal_on = ' close_btn_nd_overlay';

	$cp_settings = get_option('convert_plug_settings');
	$user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '60';
	$inactive_data = '';
	if( $a['inactivity'] ) {
		$inactive_data = 'data-inactive-time="'.$user_inactivity.'"';
	}

	//scroll up to specific class
	$scroll_data = $scroll_class = '';
	$enable_custom_scroll = isset( $a['enable_custom_scroll'] ) ? $a['enable_custom_scroll'] : '';
	$enable_scroll_class = isset( $a['enable_scroll_class'] ) ? $a['enable_scroll_class'] : '';

	if($enable_custom_scroll){
		if( $enable_scroll_class!='' ){
			$scroll_class 	= cp_get_scroll_class_init( $a['enable_scroll_class'] );
			$scroll_data 	= 'data-scroll-class="'.$scroll_class.'"';
		}
	}

	//	Variables
	$global_class 			= 'global_modal_container';
	$schedule               = isset( $a['schedule'] ) ? $a['schedule'] : '';
	$isScheduled 			= cp_is_modal_scheduled( $schedule, $a['live'] );
	//	Filters & Actions
	$data_redirect = '';
	if( isset($a['on_success']) && isset($a['redirect_url']) && isset($a['redirect_data']) && isset($a['on_redirect']) ) {
		$download_url ='';
		if(isset($a['download_url'])){
			$download_url = $a['download_url'];
		}
		$data_redirect	 	= cp_has_redirect_init( $a['on_success'], $a['redirect_url'], $a['redirect_data'] , $a['on_redirect'] ,$download_url);
	}
	$overlay_effect = '';
	if( isset($a['overlay_effect']) ) {
		$overlay_effect = $a['overlay_effect'];
	}


	$hide_image = '';
	if( isset( $a['image_displayon_mobile'] ) && isset( $a['image_resp_width'] ) ) {
		$hide_image 	 	= cp_hide_image_on_mobile_init( $a['image_displayon_mobile'], $a['image_resp_width'] );
	}

	$overaly_setting 		= cp_has_overaly_setting_init( $overlay_effect , $a['disable_overlay_effect'], $a['hide_animation_width'] );
	$afl_setting 	 		= apply_filters( 'cp_get_affiliate_setting', $a['affiliate_setting'] );
	$style_id 				= ( isset( $a['style_id'] ) ) ? $a['style_id'] : '';
	$style_class 			= ( isset( $a['style_class'] ) ) ? $a['style_class'] : '';
	$placeholder_font 		= '';

	//	Filters
	$custom_class 			= cp_get_custom_class_init( $a['enable_custom_class'], $a['custom_class'], $style_id );

	$modal_exit_intent 		= apply_filters( 'cp_has_enabled_or_disabled', $a['modal_exit_intent'] );
	$load_on_refresh 		= apply_filters( 'cp_has_enabled_or_disabled', $a['display_on_first_load'] );
	$global_modal_settings 	= cp_modal_global_settings_init( $a['closed_cookie'], $a['conversion_cookie'], $style_id );
	$cp_modal_visibility	= apply_filters( 'cp_modal_visibility', $a['hide_on_device'] ); 		//	Visibility on Browser, Devices & OS

	$placeholder_color 		= ( isset( $a['placeholder_color'] ) ) ? $a['placeholder_color'] : '';
	if ( isset( $a['placeholder_font'] ) ) {
		if( $a['placeholder_font'] == '' )
			$placeholder_font = 'inherit';
		else
			$placeholder_font = $a['placeholder_font'];
	}

	$image_position			= ( isset( $a['image_position'] ) ) ? $a['image_position'] : '';
	$exit_animation			= isset( $a['exit_animation'] ) ? $a['exit_animation'] : 'cp-overlay-none';

	//find out offset
	if( !function_exists( "getOffsetByTimeZone" ) ) {
		function getOffsetByTimeZone($localTimeZone) {
			$time = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone($localTimeZone));
			$timezoneOffset = $time->format('P');
			return $timezoneOffset;
		}
	}

	$schedular_tmz_offset = get_option('gmt_offset');
	if( $schedular_tmz_offset == '' ){
		$schedular_tmz_offset = getOffsetByTimeZone(get_option('timezone_string'));
	}

	//  Container Classes
	$cp_modal_content_class = '';
    if( isset( $a['mailer'] ) && ( $a['mailer'] == "custom-form" ) ) {
		$cp_modal_content_class .= ' cp-custom-form-container';

		//  Add - Contact Form 7 Styles
	    $data         			 = get_option( 'convert_plug_debug' );
	    $is_cf7_styles_enable 	 = ( isset( $data['cp-cf7-styles'] ) ) ? $data['cp-cf7-styles'] : 1;
	    $cp_modal_content_class .= ( $is_cf7_styles_enable ) ? ' cp-default-cf7-style1' : '';
    }

	 $schedular_tmz_offset = get_option('gmt_offset');
	 if( $schedular_tmz_offset == '' ){
	 	$schedular_tmz_offset = getOffsetByTimeZone(get_option('timezone_string'));
	}

	//  Container Classes
	$cp_modal_content_class = '';
    if( isset( $a['mailer'] ) && ( $a['mailer'] == "custom-form" ) ) {
		$cp_modal_content_class .= ' cp-custom-form-container';

		//  Add - Contact Form 7 Styles
	    $data         			 = get_option( 'convert_plug_debug' );
	    $is_cf7_styles_enable 	 = ( isset( $data['cp-cf7-styles'] ) ) ? $data['cp-cf7-styles'] : 1;
	    $cp_modal_content_class .= ( $is_cf7_styles_enable ) ? ' cp-default-cf7-style1' : '';
    }

	 // check if modal should be triggered after post
	 $enable_after_post = (int) ( isset( $a['enable_after_post'] ) ? $a['enable_after_post'] : 0 );
	 if( $enable_after_post ) {
		 $custom_class .= ' cp-after-post';
	 }

	 // check if modal should be triggerd if items in the cart
	 $items_in_cart = (int) ( isset( $a['items_in_cart'] ) ? $a['items_in_cart'] : 0 );
	 if( $items_in_cart ) {
		 $custom_class .= ' cp-items-in-cart';
	 }

	// check if inline display is set
	$isInline = ( isset( $a['display'] ) && $a['display'] == "inline" ) ? true : false;
	if( $isInline ){
		$custom_class .= " cp-open";
		$close_class = "do_not_close";
		$a['modal_overlay_bg_color'] = 'rgba( 255,255,255,0 );';
	} else {
		$custom_class .= " cp-modal-global";
	}

	/**
	 * Contact Form - Layouts
	 *
	 */
	$form_layout = ( isset( $a['form_layout'] ) ) ? $a['form_layout'] : '';

	$cp_settings = get_option('convert_plug_debug');
	$after_content_scroll = isset( $cp_settings['after_content_scroll'] ) ? $cp_settings['after_content_scroll'] : '50';
	$after_content_data = 'data-after-content-value="'. $after_content_scroll .'"';

	if ( isset( $a['manual'] ) && $a['manual'] == 'true' )
		$cp_onload = '';
	else
		$cp_onload = 'cp-onload';

	$modal_bg_color = isset( $a['modal_bg_color'] ) ? $a['modal_bg_color'] : '';

	if( $a['modal_size'] =='cp-modal-window-size'){
		$global_class .= ' cp-window-overlay';
	}

	//form display/hide after sucessfull submission
	$form_data_onsubmit ='';
	$form_action_onsubmit = isset( $a['form_action_on_submit'] )? $a['form_action_on_submit'] :'';
	
	if( $form_action_onsubmit == 'reappear' ){
		$form_data_onsubmit = 'data-form-action = reappear';
		$form_data_onsubmit .= ' data-form-action-time ='.$a['form_reappear_time'];
	}else if( $form_action_onsubmit == 'disappears' ){
		$form_data_onsubmit = 'data-form-action = disappear';
		$form_data_onsubmit .= ' data-form-action-time ='.$a['form_disappears_time'];
	}

	ob_start();

?>
<?php if( !$isInline ){ ?>
	<div data-class-id="content-<?php echo $a['uid']; ?>" <?php echo $referrer_data; ?> <?php echo $after_content_data; ?> class="<?php echo $cp_onload; ?> overlay-show <?php echo esc_attr( $custom_class ); ?>" data-overlay-class="overlay-zoomin" data-onload-delay="<?php echo esc_attr( $load_on_duration ); ?>" data-onscroll-value="<?php echo esc_attr( $load_after_scroll ); ?>" data-exit-intent="<?php echo esc_attr($modal_exit_intent); ?>" <?php echo $global_modal_settings; ?> data-custom-class="<?php echo esc_attr( $custom_class ); ?>" data-load-on-refresh="<?php echo esc_attr($load_on_refresh); ?>" data-dev-mode="<?php echo esc_attr( $dev_mode ); ?>" <?php echo $inactive_data; ?> <?php echo $cp_modal_visibility; ?> <?php echo  $scroll_data ;?> ></div>
<?php } ?>
	<div data-form-layout="<?php echo $form_layout; ?>" class="cp-modal-popup-container <?php echo esc_attr( $style_id ); ?> <?php echo $style_class. '-container'; ?><?php echo ( $isInline ) ? " cp-inline-modal-container" : ""; ?>">
		<div class="<?php echo ( $isInline ) ? "cp-modal-inline" : "cp-overlay "; ?><?php echo esc_attr( $close_modal_on ); ?> <?php echo esc_attr( $overlay_effect ); ?> content-<?php echo $a['uid'] . ' ' . $global_class . ' ' . $close_class ; ?>" data-placeholder-font="<?php echo $placeholder_font; ?>" data-class="content-<?php echo $a['uid']; ?>" style=" <?php echo esc_attr( 'background:'.$a['modal_overlay_bg_color'] ); ?>" <?php echo $global_modal_settings; ?> data-custom-class="<?php echo esc_attr( $custom_class ); ?>" data-load-on-refresh="<?php echo esc_attr($load_on_refresh); ?>" <?php echo $isScheduled; ?> data-timezone="<?php echo esc_attr($timezone); ?>" data-timezonename="<?php echo esc_attr( $timezone_name );?>" data-placeholder-color="<?php echo $placeholder_color; ?>" data-image-position="<?php echo $image_position ;?>" <?php echo $hide_image; ?> <?php echo $afl_setting; ?> <?php echo $overaly_setting;?> <?php echo $data_redirect;?> data-tz-offset="<?php echo $schedular_tmz_offset ;?>" <?php echo esc_attr( $close_btn_on_duration ); ?>  <?php echo $autoclose_data; ?>  <?php echo esc_attr( $form_data_onsubmit );?>>
	    	<div class="cp-modal <?php echo esc_attr( $a['modal_size'] ); ?>" style="<?php echo esc_attr( $modal_size_style ); ?>">
	      		<div class="cp-animate-container" <?php echo $overaly_setting;?> data-exit-animation="<?php echo esc_attr( $exit_animation ); ?>">
	      			<div class="cp-modal-content <?php echo $cp_modal_content_class; ?>" style="<?php echo esc_attr( $css_style ); ?>;<?php echo esc_attr( $windowcss );?>">
					<?php if( isset( $a['modal_size'] ) && $a['modal_size'] != "cp-modal-custom-size" ){ ?>
	      				<div class="cp-modal-body-overlay cp_fs_overlay" style="background-color: <?php echo esc_attr( $modal_bg_color ); ?>;<?php echo esc_attr( $inset ); ?>;"></div>
	      			<?php } ?>

	        		<div class="cp-modal-body <?php echo $style_class . ' ' . esc_attr( $el_class ); ?>" style="<?php echo esc_attr( $customcss );?>">
	          		 <?php if( $a['modal_size'] == "cp-modal-custom-size" ) { ?>
	      					<div class="cp-modal-body-overlay cp_cs_overlay" style="background-color: <?php echo esc_attr( $modal_bg_color ); ?>;<?php echo esc_attr( $inset ); ?>;"></div>
	      				<?php } ?>
<?php
 }
}
add_filter( 'cp_modal_global_before', 'cp_modal_global_before_init' );


/**
 * Modal After
 *
 * @since 0.1.5
 */
if( !function_exists( "cp_modal_global_after_init" ) ) {
function cp_modal_global_after_init( $a ) {

	$edit_link = '';
	if( is_user_logged_in() ) {
		// if user has access to ConvertPlug, then only display edit style link
		if( current_user_can( 'access_cp' ) ) {
			if( isset( $a['style_id'] ) ) {
				$edit_link = cp_get_edit_link( $a['style_id'], 'modal', $a['style'] );
			}
		}
	}

	if ( !isset( $a['modal_size'] ) ) {
		$a['modal_size'] = 'cp-modal-custom-size';
	}

	$afilate_link 	= cp_get_affiliate_link_init( $a['affiliate_setting'], $a['affiliate_username'] );
	$afilate_class 	= cp_get_affiliate_class_init( $a['affiliate_setting'], $a['modal_size'] );
	$style_id 				= ( isset( $a['style_id'] ) ) ? $a['style_id'] : '';
	if( $a['close_modal'] !== 'close_txt' )
		$cp_close_image_width = $a['cp_close_image_width']."px";
	else
		$cp_close_image_width = 'auto';

	//	{START} - SAME FOR BEFORE & AFTER NEED TO CREATE FUNCTION IT's TEMP
	$close_img_class = $close_img = $close_alt  = '';

	$close_img_prop = cp_close_image_setup( $a );

	$close_img = $close_img_prop['close_img'];
	$close_img_class = $close_img_prop['close_img_class'];
	$close_alt = $close_img_prop['close_alt'];
	if($close_alt!==''){
		$close_alt = 'alt="'.$close_alt .'"';
	}

	$close_html = $el_class = $modal_size_style = $close_class = '';
	if( isset( $a['content_padding'] ) && $a['content_padding'] ) {
		$el_class .= 'cp-no-padding ';
	}
	$close_tooltip = $close_tooltip_end = '';

	if( $a['close_modal'] == "close_txt" ) {
		$close_class .= 'cp-text-close';
		if( $a['close_modal_tooltip'] == 1 ) {
			$close_tooltip ='<span class="cp-close-tooltip cp-tooltip-icon has-tip cp-tipcontent-'.$a['style_id'].'data-classes="close-tip-content-'.$a['style_id'].'" data-position="left"  title="'. $a['tooltip_title'].'"  data-color="'.$a['tooltip_title_color'] .'" data-bgcolor="'.$a['tooltip_background'].'" data-closeid ="cp-tipcontent-'.$a['style_id'].'">';
			$close_tooltip_end ='</span>';
		}
		$close_html = '<span style="color:'.$a['close_text_color'].'">'.$a['close_txt'].'</span>';
	} else if( $a['close_modal'] == "close_img" ) {
		$close_class .= 'cp-image-close';
		$close_html   = '<img class="'.$close_img_class.'" src="'.$close_img.'" '.$close_alt.' />';
	} else {
		$close_class = 'do_not_close';
	}

	if( isset( $a['display_close_on_duration'] ) && $a['display_close_on_duration'] && $a['close_modal'] !== 'do_not_close' ) {
		$close_class  .= ' cp-hide-close';
	}

	//	{END} - SAME FOR BEFORE & AFTER NEED TO CREATE FUNCTION IT's TEMP

	/* -- tool tip ----- */
	$tooltip_position = '';

	if( $a['modal_size'] == "cp-modal-custom-size" ){
		$tooltip_position = 'top';
	} else {
		$tooltip_position = 'left';
	}

	$close_adjacent_position = ( isset( $a['adjacent_close_position'] ) ? $a['adjacent_close_position'] : 'cp-adjacent-right' );
	$close_position = ( isset($a['close_position']) ? $a['close_position'] :'' );
 	if($close_adjacent_position!=''){
		switch( $close_adjacent_position ){
			case 'top_left':  $tooltip_position = 'right';
				break;
			case 'top_right': $tooltip_position = 'left';
				break;
		}
	}

	if( $close_position =='inside_modal' ){
		//$tooltip_position = 'top';
	}


	$tooltip_class = $tooltip_style = '';
	if( $a['close_modal_tooltip'] == 1 ) {
		$tooltip_class .= 'cp_closewith_tooltip';
		$tooltip_style .= 'color:'.$a['tooltip_title_color'].';background-color:'.$a['tooltip_background'].';border-top-color: '.$a['tooltip_background'].';';
	}
	$affiliate_fullsize = '';
	if( $a['modal_size'] !== "cp-modal-custom-size" ) {
		$affiliate_fullsize ='cp-affiliate-fullsize';
	}

	/// Generate border radius for form processing
	$pairs = explode( '|', $a['border'] );
	$result = array();
	foreach( $pairs as $pair ){
		$pair = explode( ':', $pair );
		$result[ $pair[0] ] = $pair[1];
	}

	$cssCode1 = '';
	if( !isset( $a['has_content_border'] ) || ( isset( $a['has_content_border'] ) && $a['has_content_border'] ) ) {
		$cssCode1 .= generateBorderCss($a['border']);
	}

	$result['border_width']=' ';
	$formProcessCss = '';
	$formProcessCss = $cssCode1 .';';
	$formProcessCss .= 'border-width: 0px;';
	$formProcessCss .= 'box-shadow: 0 0 3px 1px '.$a['modal_overlay_bg_color'].' inset;';

	// check if inline display is set
	$isInline = ( isset( $a['display'] ) && $a['display'] == "inline" ) ? true : false;
	if( $isInline ){
		$a['close_modal'] = "do_not_close";
	}

?>
		</div><!-- .cp-modal-body -->

		<?php
		if ( $edit_link !== '' ) {

			$edit_link_text = 'Edit With ConvertPlug';

			$edit_link_txt = apply_filters( 'cp_style_edit_link_text', $edit_link_text );

		 	echo "<div class='cp_edit_link'><a target='_blank' href=".$edit_link.">".$edit_link_txt."<a></div>";
		}
		?>

			</div><!-- .cp-modal-content -->
            <?php if( isset($a['form_layout']) && $a['form_layout'] != 'cp-form-layout-4' ) { ?>
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

    		if( $a['close_modal'] == 'close_img' && $a['close_position'] != 'out_modal' ) { ?>

	      		<?php
	      		if( $a['close_position'] == 'adj_modal' ){
	      			$close_overlay_class = 'cp-adjacent-close';
	      		}else{
	      			$close_overlay_class = 'cp-inside-close';
	      		}
	      		   $close_overlay_class .= $close_adj_class;

	      		?>
		      	<div class="cp-overlay-close <?php echo esc_attr( $close_class ).' '.esc_attr( $close_overlay_class ); ?>" style="width: <?php echo esc_attr( $cp_close_image_width ); ?>">
					<?php if( $a['close_modal_tooltip'] == 1 ) { ?>
	      			<span class=" cp-tooltip-icon 1 cp-inside-tip has-tip cp-tipcontent-<?php echo $a['style_id']; ?>" data-classes="close-tip-content-<?php echo $a['style_id']; ?>" data-offset="20"  data-position="<?php echo esc_attr( $tooltip_position );?>"  title="<?php echo html_entity_decode(stripslashes(esc_attr( $a['tooltip_title'] ) ));?>"  data-color="<?php echo esc_attr( $a['tooltip_title_color'] );?>" data-bgcolor="<?php echo esc_attr( $a['tooltip_background'] );?>" data-closeid ="cp-tipcontent-<?php echo $a['style_id']; ?>">
	      			<?php } ?>
					<?php echo $close_html; ?>
					<?php if($a['close_modal_tooltip'] == 1){ ?></span><?php } ?>
		      	</div>

		    <?php } ?>
		</div><!-- .cp-animate-container -->

		<?php if( $isInline ) { ?>
			<span class="cp-modal-inline-end" data-style="<?php echo $style_id; ?>"></span>
		<?php } ?>

    </div><!-- .cp-modal -->
		<?php if( $a['affiliate_setting'] == 1  ) { ?>
		        <div class ="cp-affilate-link cp-responsive">
		           <a href="<?php echo $afilate_link ?>" target= "_blank"><?php echo do_shortcode( html_entity_decode( $a['affiliate_title'] ) ); ?></a>
		        </div>
      	<?php } ?><!-- .affiliate link for fullscreen -->

		<?php if( ( $a['close_position'] == 'out_modal' && $a['close_modal'] != 'do_not_close') || $a['close_modal'] == 'close_txt' ) { ?>
		    <div class="cp-overlay-close cp-outside-close <?php echo esc_attr( $close_class ); ?> <?php echo $close_adj_class;?>"  style="width: <?php echo esc_attr( $cp_close_image_width ); ?>">
				 <?php if( $a['close_modal_tooltip'] == 1 ) { ?>
					<span class=" cp-close-tooltip cp-tooltip-icon  has-tip cp-tipcontent-<?php echo $a['style_id']; ?>" data-classes="close-tip-content-<?php echo $a['style_id']; ?>" data-position="<?php echo $tooltip_position;?>"  title="<?php echo html_entity_decode(stripslashes(esc_attr( $a['tooltip_title'] ) ));?>"  data-color="<?php echo esc_attr( $a['tooltip_title_color'] );?>" data-bgcolor="<?php echo esc_attr( $a['tooltip_background'] );?>" data-closeid ="cp-tipcontent-<?php echo $a['style_id']; ?>" data-offset="20">
				<?php } ?>
				<?php echo $close_html; ?><?php if($a['close_modal_tooltip'] == 1){ ?></span><?php } ?>
			 </div>
		<?php } ?>
	</div><!-- .cp-overlay -->
</div><!-- .cp-modal-popup-container -->
<?php
 }
}
add_filter( 'cp_modal_global_after', 'cp_modal_global_after_init' );

if( !function_exists('cp_close_image_setup') ) {

	function cp_close_image_setup( $a ) {
		$close_img = $close_img_class = $close_alt = '';

		if ( !isset( $a['close_image_src'] ) ) {
			$a['close_image_src'] = 'upload_img';
		}

		if ( $a['close_image_src'] == 'upload_img' ) {

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
		} else if ( $a['close_image_src'] == 'custom_url' ) {
			$close_img = $a['modal_close_img_custom_url'];
		} else if( $a['close_image_src'] == 'pre_icons' ) {
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

