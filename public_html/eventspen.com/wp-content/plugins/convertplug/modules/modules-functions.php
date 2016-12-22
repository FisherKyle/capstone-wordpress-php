<?php
if( !function_exists( 'cp_get_form_hidden_fields' ) ) {
	function cp_get_form_hidden_fields( $a ){
		/** = Form options
		 *	Mailer - We will also optimize this by filter. If in any style we need the form then apply filter otherwise nope.
		 *-----------------------------------------------------------*/

		$mailer 		= explode( ":",$a['mailer'] );
		$on_success_action = $on_success = $on_redirect = '';
		$mailer_id = $list_id = $data_option = '';

		if( $a['mailer'] !== '' && $a['mailer'] != "custom-form" ) {
		    $smile_lists = get_option('smile_lists');

		    $list = ( isset( $smile_lists[$a['mailer']] ) ) ? $smile_lists[$a['mailer']] : '';
		    $mailer = ( $list != '' ) ? $list['list-provider'] : '';
		    $listName = ( $list != '' ) ? str_replace(" ","_",strtolower( trim( $list['list-name'] ) ) ) : '';

		    if( $mailer == 'Convert Plug' ) {
		        $mailer_id = 'cp';
		        $list_id = $a['mailer'];
		        $data_option = "cp_connects_".$listName;
		    } else {
		        $mailer_id = strtolower($mailer);
		        $list_id = ( $list != '' ) ? $list['list'] : '';
		        $data_option = "cp_".$mailer_id."_".$listName;
		    }

		    $on_success = ( isset($a['on_success']) ) ? $a['on_success'] : '';
		    if( isset($on_success) && $on_success == "redirect" )  {
		    	$on_success_action = $a['redirect_url'];
		    	if( isset($a['on_redirect']) && $a['on_redirect'] !== '' ){
		    		$on_redirect .= '<input type="hidden" name="redirect_to" value="'.$a['on_redirect'].'" />';
		    		if( $a['on_redirect'] == 'download' && isset($a['download_url']) && $a['download_url']!=='' ){
		    		$on_redirect .= '<input type="hidden" name="download_url" value="'.$a['download_url'].'" />';
		    		}

		    	}
		    } else if( isset( $a['success_message'] ) ) {
		    	//$on_success_action = $a['success_message'] ;
		    	$on_success_action =  do_shortcode( html_entity_decode( stripcslashes( $a['success_message'] ) ) );

		    }
		}
		ob_start();
		$uid = md5(uniqid(rand(), true));

		global $wp;
		$current_url = home_url(add_query_arg(array(),$wp->request));
		?>

		<input type="hidden" name="cp-page-url" value="<?php echo $current_url; ?>" />
		<input type="hidden" name="param[user_id]" value="cp-uid-<?php echo $uid; ?>" />
        <input type="hidden" name="param[date]" value="<?php echo esc_attr( date("j-n-Y") ); ?>" />
		<input type="hidden" name="list_parent_index" value="<?php echo isset( $a['mailer'] ) ? $a['mailer'] : ''; ?>" />
        <input type="hidden" name="option" value="<?php echo $data_option; ?>" />
		<input type="hidden" name="action" value="<?php echo $mailer_id; ?>_add_subscriber" />
        <input type="hidden" name="list_id" value="<?php echo $list_id; ?>" />
        <input type="hidden" name="style_id" value="<?php echo ( isset( $a['style_id'] ) ) ? $a['style_id'] : ''; ?>" />
        <input type="hidden" name="msg_wrong_email" value='<?php echo isset( $a['msg_wrong_email'] ) ? do_shortcode( html_entity_decode( stripcslashes( $a['msg_wrong_email'] ) ) ) : ''; ?>' />
        <input type="hidden" name="<?php echo $on_success; ?>" value='<?php echo $on_success_action; ?>' />
        <?php
        //echo $on_redirect;
        ?>
        <?php
        $html = ob_get_clean();
        echo $html;
	}
}

add_filter( 'cp_form_hidden_fields', 'cp_get_form_hidden_fields', 10, 1 );

/**
 *	Filter 'cp_valid_mx_email' for MX - Email validation
 *
 * @since 1.0
 */
add_filter( 'cp_valid_mx_email', 'cp_valid_mx_email_init' );
if( !function_exists( "cp_valid_mx_email_init" ) ){
	function cp_valid_mx_email_init($email) {
		//	Proceed If global check box enabled for MX Record from @author tab
		if( apply_filters( 'cp_enabled_mx_record', $email ) ) {
			if( cp_is_valid_mx_email($email) ) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
}
if( !function_exists( "cp_is_valid_mx_email" ) ){
	function cp_is_valid_mx_email($email,$record = 'MX') {
		list($user,$domain) = explode('@',$email);
		return checkdnsrr($domain,$record);
	}
}
/**
 * 	Check MX record globally enabled or not [Setting found in @author tab]
 */
add_filter( 'cp_enabled_mx_record', 'cp_enabled_mx_record_init' );
function cp_enabled_mx_record_init() {
	$data = get_option( 'convert_plug_settings' );
	$is_enable_mx_records = isset($data['cp-enable-mx-record']) ? $data['cp-enable-mx-record'] : 0;
	if( $is_enable_mx_records ) {
		return true;
	} else {
		return false;
	}
}

/**
 * 	Check if style is visible here or not
 * @Since 2.1.0
 */
function cp_is_style_visible($settings) {

	global $post;
	$post_id = ( !is_404() && !is_search() && !is_archive() && !is_home() ) ? @$post->ID : '';
	$category = get_queried_object_id();

	$cat_ids = wp_get_post_categories( $post_id );

	$post_type = get_post_type( $post );
	$taxonomies = get_post_taxonomies( $post );

	$global_display		= isset($settings['global']) ? apply_filters('smile_render_setting', $settings['global']) : '';

	$exclude_from 		= isset($settings['exclude_from']) ? apply_filters('smile_render_setting', $settings['exclude_from']) : '';
	$exclude_from		= str_replace( "post-", "", $exclude_from );
	$exclude_from		= str_replace( "tax-", "", $exclude_from );
	$exclude_from		= str_replace( "special-", "", $exclude_from );
	$exclude_from 		= ( !$exclude_from == "" ) ? explode( ",", $exclude_from ) : '';

	$exclusive_on 		= isset($settings[ 'exclusive_on' ]) ? apply_filters('smile_render_setting', $settings[ 'exclusive_on' ]) : '';
	$exclusive_on		= str_replace( "post-", "", $exclusive_on );
	$exclusive_on		= str_replace( "tax-", "", $exclusive_on );
	$exclusive_on		= str_replace( "special-", "", $exclusive_on );
	$exclusive_on 		= ( !$exclusive_on == "" ) ? explode( ",", $exclusive_on ) : '';

	// exclude post type
	$exclude_cpt 		= isset($settings[ 'exclude_post_type' ]) ? apply_filters('smile_render_setting', $settings[ 'exclude_post_type' ]) : '';
	$exclude_cpt		= str_replace( "post-", "", $exclude_cpt );
	$exclude_cpt		= str_replace( "tax-", "", $exclude_cpt );
	$exclude_cpt		= str_replace( "special-", "", $exclude_cpt );
	$exclude_cpt 		= ( !$exclude_cpt == "" ) ? explode( ",", $exclude_cpt ) : '';

	// exclusive taxonomy
	$exclusive_tax 		= isset($settings[ 'exclusive_post_type' ]) ? apply_filters('smile_render_setting', $settings[ 'exclusive_post_type' ]) : '';

	$exclusive_tax		= str_replace( "post-", "", $exclusive_tax );
	$exclusive_tax		= str_replace( "tax-", "", $exclusive_tax );
	$exclusive_tax		= str_replace( "special-", "", $exclusive_tax );
	$exclusive_tax 		= ( !$exclusive_tax == "" ) ? explode( ",", $exclusive_tax ) : '';

	if( !$global_display ){
		if( !$settings['enable_custom_class'] ) {
			$settings['custom_class'] = 'priority_modal';
			$settings['enable_custom_class'] = true;
		} else {
			$settings['custom_class'] = $settings['custom_class'].',priority_modal';
		}
	}

	$show_for_logged_in = isset($settings['show_for_logged_in'] ) ? $settings['show_for_logged_in'] : '';

	$all_users = isset($settings['all_users'] ) ? $settings['all_users'] : '';

	if( $all_users ){
		$show_for_logged_in = 0;
	}

	if( $global_display ) {
		$display = true;
		if( is_404() ){
			if( is_array( $exclude_from ) && in_array( '404', $exclude_from ) ){
				$display = false;
			}
		}
		if( is_search() ){
			if( is_array( $exclude_from ) && in_array( 'search', $exclude_from ) ){
				$display = false;
			}
		}
		if( is_front_page() ){
			if( is_array( $exclude_from ) && in_array( 'front_page', $exclude_from ) ){
				$display = false;
			}
		}
		if( is_home() ){
			if( is_array( $exclude_from ) && in_array( 'blog', $exclude_from ) ){
				$display = false;
			}
		}
		if( is_author() ){
			if( is_array( $exclude_from ) && in_array( 'author', $exclude_from ) ){
				$display = false;
			}
		}
		if( is_archive() ){
			$term_id = '';
			$obj = get_queried_object();
			if( $obj !=='' && $obj !== null ){
				if( isset($obj->term_id) ) {
					$term_id = $obj->term_id;
				}
			}
			if( is_array( $exclude_from ) && in_array( $term_id, $exclude_from ) ){
				$display = false;
			} elseif( is_array( $exclude_from ) && in_array( 'archive', $exclude_from ) ){
				$display = false;
			}
		}
		if( $post_id ) {
			if( is_array( $exclude_from ) && in_array( $post_id, $exclude_from ) ){
				$display = false;
			}
		}
		if( !empty( $cat_ids ) ) {
			foreach( $cat_ids as $cat_id ){
				if( is_array( $exclude_from ) && in_array( $cat_id, $exclude_from ) ){
					$display = false;
				}
			}
		}

		if( !empty( $exclude_cpt ) && is_array( $exclude_cpt ) ){
			foreach( $exclude_cpt as $taxonomy ) {
				$taxonomy = str_replace( "cp-", "", $taxonomy );

				if( is_singular($taxonomy) ) {
					$display = false;
				}

				if( is_category($taxonomy) ){
					$display = false;
				}

				if( is_tag($taxonomy) ){
					$display = false;
				}

				if( is_tax($taxonomy) ){
					$display = false;
				}
			}
		}

	} else {
		$display = false;

		if( is_array( $exclusive_on ) && !empty( $exclusive_on ) ){
			foreach( $exclusive_on as $page ){
				if( is_page( $page ) ){
					$display = true;
				}
			}
		}
		if( is_404() ){
			if( is_array( $exclusive_on ) && in_array( '404', $exclusive_on ) ){
				$display = true;
			}
		}
		if( is_search() ){
			if( is_array( $exclusive_on ) && in_array( 'search', $exclusive_on ) ){
				$display = true;
			}
		}
		if( is_front_page() ){
			if( is_array( $exclusive_on ) && in_array( 'front_page', $exclusive_on ) ){
				$display = true;
			}
		}
		if( is_home() ){
			if( is_array( $exclusive_on ) && in_array( 'blog', $exclusive_on ) ){
				$display = true;
			}
		}
		if( is_author() ){
			if( is_array( $exclusive_on ) && in_array( 'author', $exclusive_on ) ){
				$display = true;
			}
		}
		if( is_archive() ){
			$obj = get_queried_object();
			$term_id ='';
			if( $obj !=='' &&  $obj !== null){
				$term_id = $obj->term_id;
			}

			if( is_array( $exclusive_on ) && in_array( $term_id, $exclusive_on ) ){
				$display = true;
			} elseif( is_array( $exclusive_on ) && in_array( 'archive', $exclusive_on ) ){
				$display = true;
			}
		}
		if( $post_id ) {
			if( is_array( $exclusive_on ) && in_array( $post_id, $exclusive_on ) ){
				$display = true;
			}
		}
		if( !empty( $cat_ids ) ) {
			foreach( $cat_ids as $cat_id ){
				if( is_array( $exclusive_on ) && in_array( $cat_id, $exclusive_on ) ){
					$display = true;
				}
			}
		}

		if( !empty( $exclusive_tax ) ){
			foreach( $exclusive_tax as $taxonomy ) {
				$taxonomy = str_replace( "cp-", "", $taxonomy );

				if( is_singular($taxonomy) ) {
					$display = true;
				}

				if( is_category($taxonomy) ){
					$display = true;
				}

				if( is_tag($taxonomy) ){
					$display = true;
				}

				if( is_tax($taxonomy) ){
					$display = true;
				}
			}
		}
	}

	if( !$show_for_logged_in ){
		if( is_user_logged_in() )
			$display = false;
	}

	$style_id = $settings['style_id'];

	// filter target page settings
	$display = apply_filters( 'cp_target_page_settings', $display, $style_id );

	return $display;
}


/**
 * 	display style inline
 * @Since 2.1.0
 */
function cp_display_style_inline() {

	$before_content_string = '';
	$after_content_string  = '';

	$cp_modules = get_option('convert_plug_modules');

	if( is_array($cp_modules) ) {

		foreach( $cp_modules as $module ) {

			$module = strtolower( str_replace( "_Popup", "" , $module) );
			$style_arrays = cp_get_live_styles($module);

			if( is_array($style_arrays) ) {

				foreach( $style_arrays as $key => $style_array ){

					$display = false;
					$display_inline = false;
					$settings_encoded = '';
					$style_settings = array();
					$settings_array = unserialize($style_array[ 'style_settings' ]);
					foreach($settings_array as $key => $setting){
						$style_settings[$key] = apply_filters( 'smile_render_setting',$setting );
					}

					$style_id = $style_array[ 'style_id' ];
					$modal_style = $style_settings[ 'style' ];

					if( is_array($style_settings) && !empty($style_settings) ){
						$settings = unserialize( $style_array[ 'style_settings' ] );

						if( isset( $settings['enable_display_inline'] ) && $settings['enable_display_inline'] == '1' ) {
							$display_inline = true;
							$inline_position = $settings['inline_position'];
						}

						$css = isset( $settings['custom_css'] ) ? urldecode($settings['custom_css']) : '';
						$display = cp_is_style_visible($settings);
						$settings = serialize( $settings );
						$settings_encoded 	= base64_encode( $settings );
					}

					if( $display && $display_inline ) {

						ob_start();

						echo do_shortcode( '[smile_'.$module.' display="inline" style_id = '.$style_id.' style="'.$modal_style.'" settings_encoded="' . $settings_encoded . ' "][/smile_'.$module.']' );
						apply_filters('cp_custom_css',$style_id, $css);

						switch($inline_position) {
							case "before_post":
								$before_content_string .= ob_get_contents();
							break;
							case "after_post":
								$after_content_string .= ob_get_contents();
							break;
							case "both":
								$after_content_string .= ob_get_contents();
								$before_content_string .= ob_get_contents();
							break;
						}

						ob_end_clean();
					}
				}
			}
		}
	}

	$output_string = array($before_content_string, $after_content_string);
	return $output_string;
}


/**
 * 	Get live styles list for particular module
 * @Since 2.1.0
 */
function cp_get_live_styles($module) {

	$styles = get_option( 'smile_'.$module.'_styles' );
	$style_variant_tests = get_option( $module.'_variant_tests' );
	$live_array = array();
	if( !empty( $styles ) ) {
		foreach( $styles as $key => $style ){
			$settings = unserialize( $style[ 'style_settings' ] );

			$split_tests = isset( $style_variant_tests[$style['style_id']] ) ? $style_variant_tests[$style['style_id']] : '';
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


/**
 * 	Notify form submission errors to admin
 * @Since 2.3.0
 */
if( !function_exists('cp_notify_error_to_admin') ) {
	function cp_notify_error_to_admin($page_url) {

		// prepare content for email
		$subject  = 'Issue with the ConvertPlug configuration';

		$body = "Hello there, <p>There appears to be an issue with the ConvertPlug configuration on your website. Someone tried to fill out ConvertPlug form on ".$page_url." and regretfully, it didn't go through.</p>";

		$body .= "Please try filling out the form yourself or read more why this could happen here.";

		$body .= "<br>---<p>This e-mail was sent from ConvertPlug on ". get_bloginfo('name') ." (". site_url() . ")</p>";

		// get admin email
		$to = get_option( 'admin_email' );

		$admin_notifi_time = get_option( 'cp_notified_admin_time' );

		if( !$admin_notifi_time ) {
			cp_send_mail( $to, $subject, $body );
			update_option( 'cp_notified_admin_time', date('Y-m-d H:i:s') );
		} else {
			// getting previously saved notification time
			$saved_timestamp = strtotime($admin_notifi_time);

			// getting current date
			$cDate = strtotime(date('Y-m-d H:i:s'));

			// Getting the value of current date - 24 hours
			$oldDate = $cDate - 86400; // 86400 seconds in 24 hrs

			// if last email was sent time is greater than 24 hours, sent one more notification email
			if ( $oldDate > $saved_timestamp ) {
				cp_send_mail( $to, $subject, $body );
				update_option( 'cp_notified_admin_time', date('Y-m-d H:i:s') );
			}
		}
	}
}

/**
 * Sends an email
 * @Since 2.3.0
 */
if( !function_exists('cp_send_mail') ) {
	function cp_send_mail($to,$subject,$body) {

		// set headers for email
		$headers = array('Content-Type: text/html; charset=UTF-8');

		if( wp_mail( $to, $subject, $body, $headers ) ) {
			$msg = "success";
		} else {
			$msg = "error";
		}
		return $msg;
	}
}


function cp_generate_scheduled_info($style_settings) {

	$scheduleData = unserialize($style_settings);
	$title = '';

    if( isset($scheduleData['schedule']) ) {
        $scheduledArray = $scheduleData['schedule'];
        if( is_array($scheduledArray) ) {
            $startDate = date("j M Y ",strtotime($scheduledArray['start']));
            $endDate = date("j M Y ",strtotime($scheduledArray['end']));
            $first = date('j-M-Y (h:i A)', strtotime($scheduledArray['start']));
            $second = date('j-M-Y (h:i A)', strtotime($scheduledArray['end']));
            $title = "Scheduled From ".$first." To ".$second;
        }
    }

    $status = '<span class="change-status"><span data-live="2" class="cp-status"><i class="connects-icon-clock"></i><span class="scheduled-info" title="'.$title.'">'.__( "Scheduled", "smile" ).'</span></span>';

   	return $status;
}

if( !function_exists( 'cp_get_live_preview_settings' ) ) {
	function cp_get_live_preview_settings( $module, $settings_method, $style_options, $template_name) {

		$settings = array();
		if ( $settings_method == 'internal' ) {

			foreach( $style_options as $key => $value ) {
				$settings[$value['name']] = $value['opts']['value'];
			}

			$settings['affiliate_setting'] = false;
			$settings['style'] = 'preview';
			$settings_encoded = base64_encode( serialize( $settings ) );

		} else {

			$settings = get_option( 'cp_'.$module.'_' . $template_name , '' );

			if( is_array($settings) ) {

				$settings = get_option( 'cp_'.$module.'_' . $template_name , '' );

				$style_setting_arr = $settings['style_settings'];
				$style_setting_arr['style'] = 'preview';

			} else {
				$demo_dir = CP_BASE_DIR . 'modules/'.$module.'/presets/'.$template_name.'.txt';

				$handle = fopen($demo_dir, "r");

				$settings = fread($handle, filesize($demo_dir));

				$settings = json_decode($settings, TRUE);

				$style_setting_arr = $settings['style_settings'];

				$style_setting_arr['style'] = 'preview';
			}

			$style_setting_arr['cp_image_link_url'] = 'external';

			$import_style = array();
			foreach( $style_setting_arr as $title => $value ){
				if( !is_array( $value ) ){
					$value = htmlspecialchars_decode($value);
					$import_style[$title] = $value;
				} else {
					foreach( $value as $ex_title => $ex_val ) {
							$val[$ex_title] = htmlspecialchars_decode($ex_val);
					}
					$import_style[$title] = $val;
				}
			}

			$settings_encoded =  base64_encode( serialize ( $import_style ) );
		}

		return $settings_encoded;

	}
}


if( !function_exists('cp_is_connected') )  {
	function cp_is_connected() {

	    $is_conn = false;
        $response = wp_remote_get( 'http://downloads.brainstormforce.com' );

        $response_code = wp_remote_retrieve_response_code( $response );

        if ( $response_code == 200 ){
    		$is_conn = true; //action when connected
      	} else {
       		$is_conn = false; //action in connection failure
      	}

	    return $is_conn;
	}

}

if( !function_exists('cp_get_edit_link') ) {
	function cp_get_edit_link( $style_id, $module, $theme ) {

		$url = '';

		$data   =  get_option( 'convert_plug_settings' );
		$esval  =  isset($data['cp-edit-style-link']) ? $data['cp-edit-style-link'] : 0;

		if( $esval ) {

			// get module styles
			$styles = get_option("smile_".$module."_styles");

			// get variant style for module
			$variant_styles = get_option( $module."_variant_tests" );

			$parent_style = false;
			$variant_style = false;
			$variant_style_id = '';

			if( is_array($styles) ) {
				foreach ($styles as $style) {

					// check if it is parent style
					if( $style['style_id'] == $style_id ) {
						$parent_style = true;
						break;
					}

					if( is_array($variant_styles) ) {
						if( isset( $variant_styles[$style['style_id']] ) ) {
							foreach ($variant_styles[$style['style_id']] as $child_style) {

								// check if it is child/ variant style
								if( $child_style['style_id'] == $style_id ) {
									$variant_style = true;
									$variant_style_id = $style['style_id'];
									break;
								}
							}
						}
					}
				}
			}

			if( $parent_style ) {
				$baseurl = "admin.php?page=smile-".$module."-designer&style-view=edit&style=".$style_id."&theme=".$theme;
				$url = admin_url($baseurl);
			} else {
				$baseurl = "admin.php?page=smile-".$module."-designer&style-view=variant&variant-test=edit&variant-style=".$style_id."&style=".$theme."&parent-style=".$theme."&style_id=".$variant_style_id."&theme=".$theme;
				$url = admin_url($baseurl);
			}
		}

		return $url;

	}
}

