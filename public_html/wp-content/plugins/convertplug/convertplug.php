<?php
/*
Plugin Name: ConvertPlug
Plugin URI: http://convertplug.com/
Author: Brainstorm Force
Author URI: https://www.brainstormforce.com
Version: 2.3.1
Description: Welcome to ConvertPlug - the easiest WordPress plugin to convert website traffic into leads. ConvertPlug will help you build email lists, drive traffic, promote videos, offer coupons and much more!
Text Domain: smile
*/
if( !defined( 'CP_VERSION' ) ) {
	define( 'CP_VERSION', '2.3.1');
}

if( !defined( 'CP_BASE_DIR' ) ) {
	define( 'CP_BASE_DIR', plugin_dir_path( __FILE__ ));
}

if( !defined( 'CP_BASE_URL' ) ) {
	define( 'CP_BASE_URL', plugin_dir_url( __FILE__ ));
}

if( !defined( 'CP_DIR_NAME' ) ){
	define( 'CP_DIR_NAME', plugin_basename( dirname( __FILE__ ) ) );
}

register_activation_hook( __FILE__, 'on_cp_activate' );
/*
* Function for activation hook
*
* @Since 1.0
*/
function on_cp_activate() {

	update_option( 'convert_plug_redirect', true );
	update_site_option( 'bsf_force_check_extensions', true );
	update_option( "dismiss-cp-update-notice", false );

	$cp_previous_version = get_option( 'cp_previous_version' );

	if( !$cp_previous_version ) {
		update_option( 'cp_is_new_user', true );
	} else {
		update_option( 'cp_is_new_user', false );
	}

	// save previous version of plugin in option
	update_option( "cp_previous_version", CP_VERSION );

	global $wp_version;
	$wp = '3.5';
	$php = '5.3.2';
    if ( version_compare( PHP_VERSION, $php, '<' ) )
        $flag = 'PHP';
    elseif
        ( version_compare( $wp_version, $wp, '<' ) )
        $flag = 'WordPress';
    else
        return;
    $version = 'PHP' == $flag ? $php : $wp;
    deactivate_plugins( basename( __FILE__ ) );
    wp_die('<p><strong>ConvertPlug </strong> requires <strong>'.$flag.'</strong> version <strong>'.$version.'</strong> or greater. Please contact your host.</p>','Plugin Activation Error',  array( 'response'=>200, 'back_link'=> TRUE ) );

}

if(!class_exists( 'Convert_Plug' )){
	// include Smile_Framework class
	require_once( 'framework/Smile_Framework.php' );

	class Convert_Plug extends Smile_Framework{
		public static $options = array();
		var $paths = array();
		function __construct(){

			//	Fall back support for multi fields
			add_action( 'init', array( $this,'fallback_support_for_multifield' ) );
			add_action( 'wp_loaded', array( $this,'cp_access_capabilities' ), 1 );
			add_action( 'wp_loaded', array( $this,'cp_set_options' ), 1 );

			$this->paths = wp_upload_dir();
			$this->paths['fonts'] 	= 'smile_fonts';
			$this->paths['fonturl'] = set_url_scheme( trailingslashit( $this->paths['baseurl'] ).$this->paths['fonts'] );

			add_action( 'admin_menu', array( $this,'add_admin_menu' ), 99 );
			add_action( 'admin_menu', array( $this,'add_admin_menu_rename' ), 9999 );
			add_filter( 'custom_menu_order', array($this,'cp_submenu_order') );
			add_action( 'wp_enqueue_scripts', array( $this,'enqueue_front_scripts' ), 10);
			add_action( 'admin_print_scripts', array( $this, 'cp_admin_css' ) );
			add_action( 'admin_enqueue_scripts', array( $this,'cp_admin_scripts' ), 100);
			add_filter( 'bsf_core_style_screens', array( $this, 'cp_add_core_styles' ));
			add_action( 'admin_head', array( $this, 'cp_custom_css' ));
			add_action( 'admin_init', array($this,'cp_redirect_on_activation'));
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'cp_action_links' ), 10, 5);
			add_action( 'wp_ajax_cp_display_preview_modal', array( $this, 'cp_display_preview_modal' ) );
			add_action( 'wp_ajax_cp_display_preview_info_bar', array( $this, 'cp_display_preview_info_bar' ) );
			add_action( 'wp_ajax_cp_display_preview_slide_in', array( $this, 'cp_display_preview_slide_in' ) );
			add_action( 'plugins_loaded', array( $this, 'cp_load_textdomain' ) );
			add_filter( 'the_content', array( $this, 'cp_add_content' ) );

			// filter hook to add frosty script
			add_filter( 'bsf_core_frosty_screens', array( $this, 'load_frosty_scripts_from_core' ) );

			// de register scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'cp_dergister_scripts' ), 100 );

			require_once( 'admin/ajax-actions.php' );
			require_once( 'framework/cp-widgets.php' );
			add_action( 'widgets_init', 'Load_Convertplug_Widget' );

			if( get_option("dismiss-cp-update-notice") == false ) {
				//add_action( 'admin_notices', 'cp_update_admin_notice' );
			}

			$checked = get_option( "cp_image_compatibility_check" );

			if( !$checked ) {
				cp_back_compatiblity_image();
			}

			// minimum requirement for PHP version
			$php = '5.4';

			// If current version is less than minimum requirement, display admin notice
			if ( version_compare( PHP_VERSION, $php, '<' ) ) {
				add_action( 'admin_notices', 'cp_php_version_notice' );
			}

			$data = get_option( 'convert_plug_debug' );

			$display_debug_info = isset($data['cp-display-debug-info']) ? $data['cp-display-debug-info'] : 0;

			if( $display_debug_info ) {
 				add_action( 'admin_footer', array( $this, 'cp_add_debug_info' ) );
 			}

		}

		/*
		* Set options on load of WordPress
		* Since 2.3.1
		*/
		function cp_set_options() {
			update_option( 'cp_is_displayed_debug_info', false );
		}

		/*
		* Add ConvertPlug access capabilities to user roles
		* Since 2.2.0
		*/
		function cp_access_capabilities() {

			if ( is_user_logged_in() ) {
				if ( current_user_can( 'manage_options' ) ) {

					global $wp_roles;
	 				$wp_roles_data = $wp_roles->get_names();
	 				$roles = false;

					$cp_settings = get_option( 'convert_plug_settings' );

					if( isset($cp_settings['cp-access-role']) ) {
						$roles = explode( ",", $cp_settings['cp-access-role'] );
					}

	 				if(!$roles) {
	 					$roles = array();
	 				}

	 				// give access to administrator
	 				$roles[] = 'administrator';

	 				foreach ( $wp_roles_data as $key => $value ) {
	 					$role = get_role( $key );

	 					if ( in_array( $key, $roles ) ) {
	 						$role->add_cap( 'access_cp' );
	 					} else {
	 						$role->remove_cap( 'access_cp' );
	 					}
	 				}
 				}
			}
		}

		function fallback_support_for_multifield() {
			$op = get_option('cp_multifield_support', 'no');
			if( $op == 'no' ) {
				$this->update_modules( 'smile_modal_styles' );
				$this->update_modules( 'smile_info_bar_styles' );
				$this->update_modules( 'smile_slide_in_styles' );
				update_option( 'cp_multifield_support', 'yes' );
			}
		}
		function update_modules( $module ) {

			$all_modules = get_option( $module );
			add_option( $module . '_backup', $all_modules );	//	Take a backup of current module

			$updated = '';

			if(is_array($all_modules) && !empty($all_modules)){
				foreach( $all_modules as $key => $style ){

				    //  Unserialize
				    $s = unserialize($style['style_settings']);

					//  Add only name field or Email & name
					$email_placeholder  = ( isset($s['placeholder_text']) && $s['placeholder_text'] != '' ) ? $s['placeholder_text'] : 'Enter Your Email Address';
					$name_placeholder 	= ( isset($s['name_text']) && $s['name_text'] != '' ) ? $s['name_text'] : 'Enter Your Name';

					$with_name = 	'order->0|input_type->textfield|input_label->Name|input_name->name|input_placeholder->'.$name_placeholder.'|input_require->true;order->1|input_type->email|input_label->Email|input_name->email|input_placeholder->'.$email_placeholder.'|input_require->true';
				    $only_email = 	'order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->'.$email_placeholder.'|input_require->true';

				    //	Get module slug
				  	$slug = $s['style'];
				    switch( $slug ) {

				    	//	modal
						case 'every_design':
    					case 'special_offer':
						case 'flat_discount':
						case 'instant_coupon':
						case 'locked_content':
						case 'optin_to_win':
						case 'webinar':
						case 'YouTube':
						case 'direct_download':
						case 'first_order':
						case 'first_order_2':
						case 'free_ebook':
												$s['form_input_font_size'] = 15;
												$s['form_input_padding_tb'] = 10;
												$s['form_input_padding_lr'] = 15;
												$s['submit_button_tb_padding'] = 10;
												$s['submit_button_lr_padding'] = 15;
							break;

						//	slide in
						case 'optin':
												$s['form_input_font_size'] = 16;
												$s['form_input_padding_tb'] = 10;
												$s['form_input_padding_lr'] = 15;
												$s['submit_button_tb_padding'] = 12;
												$s['submit_button_lr_padding'] = 15;
							break;
    					case 'optin_widget':
												$s['form_input_font_size'] = 13;
												$s['form_input_padding_tb'] = 6;
												$s['form_input_padding_lr'] = 10;
												$s['submit_button_tb_padding'] = 6;
												$s['submit_button_lr_padding'] = 15;
							break;

						//	info bar
						case 'free_trial':
					    case 'get_this_deal':
					    case 'image_preview':
					    case 'newsletter':
					    case 'weekly_article':
												$s['form_input_font_size'] = 13;
												$s['form_input_padding_tb'] = 6;
												$s['form_input_padding_lr'] = 10;
												$s['submit_button_tb_padding'] = 6;
												$s['submit_button_lr_padding'] = 15;
							break;
			    	}

			    	/**
			    	 * Button Width & Input Alignments
			    	 */
			    	//  Add fields -  form_input_align, form_submit_align, form_fields if not exist
			    	$s['form_input_align']  = "left";
				    $s['form_submit_align'] = "cp-submit-wrap-full";
				    $s['form_grid_structure'] = "cp-form-grid-structure-2";

			    	switch( $slug ) {

				    	//	modal
						case 'direct_download':
													$s['form_submit_align'] = "cp-submit-wrap-left";
									break;
						case 'instant_coupon':
						case 'locked_content':
    					case 'special_offer':
						case 'flat_discount':		$s['form_input_align']  = "center";
									break;

						//	slide in
    					case 'optin_widget':		$s['form_input_align']  = "center";
							break;

						//	info bar
					    case 'get_this_deal':
					    							$s['form_input_align']  = "center";
					    	break;

						case 'free_trial':
					    case 'image_preview':
					    case 'newsletter':
					    case 'weekly_article':		$s['form_submit_align'] = "cp-submit-wrap-left";
							break;
			    	}

				    //  Update if OLD exist & !NEW exist
				    $s['form_input_color']        = ( isset( $s['placeholder_color'] ) ) ? $s['placeholder_color'] : '';
				    $s['form_input_font']         = ( isset( $s['placeholder_font'] ) ) ? $s['placeholder_font'] : '';
				    $s['form_input_bg_color']     = ( isset( $s['input_bg_color'] ) ) ? $s['input_bg_color'] : '';
				    $s['form_input_border_color'] = ( isset( $s['input_border_color'] ) ) ? $s['input_border_color'] : '';

				    //  layout 3 - If Name is enable
				    if( isset($s['namefield']) && $s['namefield'] == 1 ) {

				       	//	Add name field in multi form
				    	$s['form_fields'] = $with_name;

				    	switch( $slug ) {

					    	//	modal
							case 'every_design':
        					case 'special_offer':
				        								$s['form_layout'] = 'cp-form-layout-1';
								break;

							case 'flat_discount':
							case 'instant_coupon':
							case 'locked_content':
							case 'optin_to_win':
														$s['form_layout'] = 'cp-form-layout-2';
								break;

							//	modal
							case 'webinar':
							case 'YouTube':
														$s['form_layout'] = 'cp-form-layout-3';
								break;

							case 'direct_download':
							case 'first_order':
							case 'first_order_2':
							case 'free_ebook':
														$s['form_layout'] = 'cp-form-layout-4';
								break;

							//	slide in
        					case 'optin_widget':
				        								$s['form_layout'] = 'cp-form-layout-1';
								break;
							case 'optin':
														$s['form_layout'] = 'cp-form-layout-3';
								break;

							//	info bar
							case 'free_trial':
						    case 'newsletter':
						    case 'weekly_article':
														$s['form_layout'] = 'cp-form-layout-3';
								break;
				    	}


				    } else if( isset($s['btn_disp_next_line']) && $s['btn_disp_next_line'] == 1 ) {

				    	//	Modal
				    	switch( $slug ) {

				    		//	modal
							case 'every_design':
							case 'flat_discount':
							case 'instant_coupon':
							case 'locked_content':
							case 'optin_to_win':
							case 'special_offer':
							case 'webinar':
							case 'YouTube':			$s['form_layout'] = 'cp-form-layout-1';
											    	$s['form_fields'] = $only_email;
								break;

							case 'direct_download':
							case 'first_order':
							case 'first_order_2':
							case 'free_ebook':
													$s['form_layout'] = 'cp-form-layout-4';
													$s['form_fields'] = '';
								break;

							//	slide in
							case 'optin_widget':
							case 'optin':			$s['form_layout'] = 'cp-form-layout-1';
											    	$s['form_fields'] = $only_email;
								break;

							//	info bar
							case 'free_trial':
						    case 'newsletter':
						    case 'weekly_article':
													$s['form_layout'] = 'cp-form-layout-3';
													$s['form_fields'] = $only_email;
								break;
				    	}

				    } else {

				    	switch( $slug ) {

				    		//	modal
							case 'every_design':
							case 'flat_discount':
							case 'instant_coupon':
							case 'locked_content':
							case 'optin_to_win':
        					case 'special_offer':
							case 'webinar':
							case 'YouTube':
														$s['form_layout'] = 'cp-form-layout-3';
														$s['form_fields'] = $only_email;
								break;
							case 'direct_download':
							case 'first_order':
							case 'first_order_2':
							case 'free_ebook':
														$s['form_layout'] = 'cp-form-layout-4';
								break;

							//	slide in
							case 'optin_widget':
														$s['form_layout'] = 'cp-form-layout-1';
														$s['form_fields'] = $only_email;
								break;
							case 'optin':
														$s['form_layout'] = 'cp-form-layout-3';
														$s['form_fields'] = $only_email;
								break;

							//	info bar
							case 'free_trial':
						    case 'newsletter':
						    case 'weekly_article':
														$s['form_layout'] = 'cp-form-layout-3';
														$s['form_fields'] = $only_email;
								break;
						    case 'get_this_deal':
						    case 'image_preview':
						    							$s['form_layout'] = 'cp-form-layout-4';
								break;
				    	}
				    }

				    /**
			    	 * Specially for - YouTube
			    	 */
			    	if( $s['style'] == 'YouTube' ) {

    					$s['form_input_align'] = 'left';
    					$s['form_input_color'] = 'rgb(153, 153, 153)';
    					$s['form_input_bg_color'] = 'rgb(255, 255, 255)';
    					$s['form_input_border_color'] = 'rgb(191, 190, 190)';
    					$s['form_input_font_size'] = '15';
    					$s['form_input_padding_tb'] = '10';
    					$s['form_input_padding_lr'] = '15';
    					$s['submit_button_tb_padding'] = '12';
    					$s['submit_button_lr_padding'] = '42';

			    		if( isset($s['cta_type']) && $s['cta_type'] != '' ) {

			    			switch( $s['cta_type'] ) {
								case 'button': 		$s['form_layout'] = 'cp-form-layout-4';
							    					$s['form_submit_align'] = 'cp-submit-wrap-center';
							    					$s['form_fields'] = '';
									break;
								case 'form': 		$s['form_lable_color'] = 'rgb(153, 153, 153)';
													$s['form_lable_font_size'] = '15';
									break;
							}
			    		}
			    	}

				    /*  update values 	*/
				    $style['style_settings'] = serialize($s);

				    /*  store the new options 	*/
				    $updated[] = $style;
				}

				//  Update the style
				update_option( $module, $updated );
			}
		}

		/**
		 * Add a class at the end of the post for after content trigger
		 *
		 * @since 1.0.3
		 */
		function cp_add_content( $content ) {
			if( is_single() || is_page() ){
				$content_str_array = cp_display_style_inline();
				$content .= '<span class="cp-load-after-post"></span>';
				$content = $content_str_array[0].$content;
				$content .= $content_str_array[1];
			}
			return $content;
		}

		/**
		 * Load plugin text domain.
		 *
		 * @since 1.0.0
		 */
		function cp_load_textdomain() {
		  load_plugin_textdomain( 'smile', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );
		}

		/**
		 * Handle style preview ajax request for modal
		 *
		 * @since 1.0.0
		 */
		function cp_display_preview_modal(){
			require_once( 'modules/modal/style-preview-ajax.php' );
			die();
		}

		/**
		 * Handle style preview ajax request for info bar
		 *
		 * @since 1.0.0
		 */
		function cp_display_preview_info_bar(){
			require_once( 'modules/info_bar/style-preview-ajax.php' );
			die();
		}

		/**
		 * Ajax Callback for slide in style preview
		 *
		 * @since 1.0.0
		 */
		function cp_display_preview_slide_in(){
			require_once( 'modules/slide_in/style-preview-ajax.php' );
			die();
		}

		/**
		 * Adds settings link in plugins action
		 * @param  array $actions
		 * @Since 1.0
		 * @return array
		 */
		function cp_action_links( $actions, $plugin_file ) {
		    static $plugin;

			if ( !isset($plugin) )
				$plugin = plugin_basename(__FILE__);
			if ( $plugin == $plugin_file ) {
				$settings = array('settings' => '<a href="' . admin_url( 'admin.php?page=convertplug&view=settings' ) . '">Settings</a>');
				$actions = array_merge($settings, $actions);
			}
			return $actions;
		}

		/*
		* Enqueue scripts and styles for insert shortcode popup
		* @Since 1.0
		*/
		function cp_admin_scripts($hook) {

			//	Store all global CSS variables
			wp_enqueue_script( 'cp-css-generator', plugins_url( 'framework/assets/js/css-generator.js', __FILE__ ), array( 'jquery') );

			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );

			$data  =  get_option( 'convert_plug_debug' );

			if ( strpos( $hook , 'convertplug' ) !== false ) {
				wp_enqueue_style( 'cp-connects-icon', plugins_url('modules/assets/css/connects-icon.css',__FILE__) );
			}

			if( isset( $_GET['hidemenubar'] ) ) {

				//	Common File for ConvertPlug
				wp_enqueue_script( 'cp-ckeditor', plugins_url( 'modules/assets/js/ckeditor/ckeditor.js', __FILE__) );
				wp_enqueue_script( 'cp-contact-form', plugins_url( 'modules/assets/js/convertplug.js', __FILE__ ), array( 'jquery', 'cp-ckeditor' ) );

				if( !is_user_logged_in() || ( defined( "LOGGED_IN_COOKIE" ) && empty( $_COOKIE[LOGGED_IN_COOKIE] ) ) ){
					wp_clear_auth_cookie();
					wp_logout();
					auth_redirect();
				}
				wp_enqueue_style( 'cp-perfect-scroll-style', plugins_url('admin/assets/css/perfect-scrollbar.min.css',__FILE__) );
				wp_enqueue_script( 'cp-perfect-scroll-js', plugins_url( 'admin/assets/js/perfect-scrollbar.jquery.js', __FILE__ ), array( "jquery" ) );
			}

			if( isset( $_GET['style-view'] ) && ( $_GET['style-view'] == "edit" || $_GET['style-view'] == 'variant' ) ) {

				wp_enqueue_script( 'cp-perfect-scroll-js', plugins_url( 'admin/assets/js/perfect-scrollbar.jquery.js', __FILE__ ), array( "jquery" ) );
				wp_enqueue_style( 'cp-perfect-scroll-style', plugins_url('admin/assets/css/perfect-scrollbar.min.css',__FILE__) );
				wp_enqueue_style( 'cp-animate', plugins_url( 'modules/assets/css/animate.css', __FILE__ ) );

				// ace editor files
				if( !isset( $_GET['hidemenubar'] ) ) {
					wp_enqueue_script( 'cp-ace', plugins_url( 'admin/assets/js/ace.js', __FILE__ ) , array( "jquery" ) );
					wp_enqueue_script( 'cp-ace-mode-css', plugins_url( 'admin/assets/js/mode-css.js', __FILE__ ) , array( "jquery" ) );
					wp_enqueue_script( 'cp-ace-mode-xml', plugins_url( 'admin/assets/js/mode-xml.js', __FILE__ ) , array( "jquery" ) );
					wp_enqueue_script( 'cp-ace-worker-css', plugins_url( 'admin/assets/js/worker-css.js', __FILE__ ) , array( "jquery" ) );
					wp_enqueue_script( 'cp-ace-worker-xml', plugins_url( 'admin/assets/js/worker-xml.js', __FILE__ ) , array( "jquery" ) );
				}
			}

			if( $hook == 'convertplug_page_contact-manager' ) {
				wp_enqueue_style( 'cp-contacts', plugins_url('admin/contacts/css/cp-contacts.css',__FILE__) );
				if( isset($_GET['view']) && $_GET['view'] == 'analytics' ) {

					wp_enqueue_script( 'bsf-charts-js', plugins_url('admin/assets/js/chart.js',__FILE__), false, false, true );
					wp_enqueue_script( 'bsf-charts-bar-js', plugins_url('admin/assets/js/chart.bar.js',__FILE__), false, false, true );
					wp_enqueue_script( 'bsf-charts-donut-js', plugins_url('admin/assets/js/chart.donuts.js',__FILE__), false, false, true );
					wp_enqueue_script( 'bsf-charts-line-js', plugins_url('admin/assets/js/Chart.Line.js',__FILE__), false, false, true );
					wp_enqueue_script( 'bsf-charts-polararea-js', plugins_url('admin/assets/js/Chart.PolarArea.js',__FILE__), false, false, true );
					wp_enqueue_script( 'bsf-charts-scripts', plugins_url('admin/contacts/js/connect-analytics.js',__FILE__), false, false, true );
				}

				wp_enqueue_style( 'css-select2', plugins_url('admin/assets/select2/select2.min.css',__FILE__) );
				wp_enqueue_script( 'convert-select2', plugins_url('admin/assets/select2/select2.min.js',__FILE__), false, '2.4.0.3', true );

				// sweet alert
				wp_enqueue_script( 'cp-swal-js', plugins_url('admin/assets/js/sweetalert.min.js',__FILE__), false, false, true );
				wp_enqueue_style( 'cp-swal-style', plugins_url('admin/assets/css/sweetalert.css',__FILE__) );
			}

			if( !isset( $_GET['hidemenubar'] ) && strpos( $hook , 'convertplug' ) !== false ) {

				if( ( isset( $_GET['variant-test'] ) && $_GET['variant-test'] !== 'edit' )
					|| ( isset( $_GET['style-view'] ) && $_GET['style-view'] !== 'edit' )
					|| ( isset( $_GET['style-view'] ) && $_GET['style-view'] == 'edit' && isset( $_GET['theme'] ) && $_GET['theme'] == 'countdown' )
					|| !isset( $_GET['style-view'] ) )
				{

					wp_enqueue_style( 'smile-bootstrap-datetimepicker', plugins_url('modules/assets/css/bootstrap-datetimepicker.min.css',__FILE__) );

					wp_enqueue_script( 'smile-moment-with-locales', plugins_url( 'modules/assets/js/moment-with-locales.js', __FILE__), false, false, true );

					if( isset( $data['cp-dev-mode'] ) && $data['cp-dev-mode'] == '1' ) {
						wp_enqueue_script( 'smile-bootstrap-datetimepicker', plugins_url('modules/assets/js/bootstrap-datetimepicker.js',__FILE__), false, false, true );

					} else {
						wp_enqueue_script( 'smile-bootstrap-datetimepicker', plugins_url('modules/assets/js/bootstrap-datetimepicker.min.js',__FILE__), false, false, true );
					}
				}

				// sweet alert
				wp_enqueue_script( 'cp-swal-js', plugins_url('admin/assets/js/sweetalert.min.js',__FILE__), false, false, true );
				wp_enqueue_style( 'cp-swal-style', plugins_url('admin/assets/css/sweetalert.css',__FILE__) );

			}

			// count down style scripts
			if( isset($_GET['theme']) && $_GET['theme'] == 'countdown' ) {
				wp_register_style( 'cp-countdown-style', plugins_url('modules/assets/css/jquery.countdown.css',__FILE__) );
				wp_register_script( 'cp-counter-plugin-js', plugins_url( 'modules/assets/js/jquery.plugin.min.js', __FILE__), array( 'jquery' ), null, null, true );
				wp_register_script( 'cp-countdown-js', plugins_url( 'modules/assets/js/jquery.countdown.js', __FILE__), array( 'jquery' ), null, null, true );
				wp_register_script( 'cp-countdown-script', plugins_url( 'modules/assets/js/jquery.countdown.script.js', __FILE__), array( 'jquery' ), null, null, true );
			}

			if ( strpos( $hook , 'convertplug' ) !== false ) {
				// developer mode
				if( isset( $data['cp-dev-mode'] ) && $data['cp-dev-mode'] == '1' ) {
					wp_enqueue_style( 'convert-admin', plugins_url('admin/assets/css/admin.css',__FILE__) );
					wp_enqueue_style( 'convert-about', plugins_url('admin/assets/css/about.css',__FILE__) );
					wp_enqueue_style( 'convert-preview-style', plugins_url('admin/assets/css/preview-style.css',__FILE__) );
					wp_enqueue_style( 'jquery-ui-accordion', plugins_url('admin/assets/css/accordion.css',__FILE__) );
					wp_enqueue_style( 'css-select2', plugins_url('admin/assets/select2/select2.min.css',__FILE__) );
					wp_enqueue_style( 'cp-contacts', plugins_url('admin/contacts/css/cp-contacts.css',__FILE__) );
					wp_enqueue_style( 'cp-swal-style', plugins_url('admin/assets/css/sweetalert.css',__FILE__) );
				} else {
					wp_enqueue_style( 'convert-admin-css', plugins_url('admin/assets/css/admin.min.css',__FILE__));
				}
			}

		}

		/*
		* Enqueue font style
		* @Since 1.0
		*/
		function cp_admin_css(){
			wp_enqueue_style( 'cp-admin-css', plugins_url( 'admin/assets/css/font.css', __FILE__ ) );
		}

		/*
		* Enqueue scripts and styles on frontend
		* @Since 1.0
		*/
		function enqueue_front_scripts(){

			if( isset( $_GET['hidemenubar'] ) ) {

				//	Common File for ConvertPlug
				wp_enqueue_script( 'cp-ckeditor', plugins_url( 'modules/assets/js/ckeditor/ckeditor.js', __FILE__) );
				wp_enqueue_script( 'cp-contact-form', plugins_url( 'modules/assets/js/convetplug.js', __FILE__ ), array( 'jquery', 'cp-ckeditor', 'smile-customizer-js' ) );

				if( !is_user_logged_in() || ( defined( "LOGGED_IN_COOKIE" ) && empty( $_COOKIE[LOGGED_IN_COOKIE] ) ) ){
					wp_clear_auth_cookie();
					wp_logout();
					auth_redirect();
				}

				wp_enqueue_script( 'cp-perfect-scroll-js', plugins_url( 'admin/assets/js/perfect-scrollbar.jquery.js', __FILE__ ), array( "jquery" ) );
			}

			wp_register_script( 'cp-detect-device', plugins_url( 'modules/assets/js/mdetect.js', __FILE__), array( 'jquery' ), null, null, true );
			wp_register_script( 'cp-ideal-timer-script', plugins_url( 'modules/assets/js/idle-timer.min.js', __FILE__), array( 'jquery' ), null, null, true );
		}
		/*
		* Add main manu for ConvertPlug
		* @Since 1.0
		*/
		function add_admin_menu(){
			$page = add_menu_page( 'ConvertPlug Dashboard', 'ConvertPlug', 'access_cp', 'convertplug', array($this,'admin_dashboard'), 'div' );
			add_action( 'admin_print_scripts-' . $page, array($this,'convert_admin_scripts'));
			add_action( 'admin_footer-'. $page, array($this,'cp_admin_footer') );

			if(defined('BSF_MENU_POS'))
				$required_place = BSF_MENU_POS;
			else
				$required_place = 200;

			if(function_exists('bsf_get_free_menu_position'))
				$place = bsf_get_free_menu_position($required_place,1);
			else
				$place = null;

			if( !defined ( 'BSF_MENU_POS' ) ) {
				define('BSF_MENU_POS', $place);
			}
			global $menu;
			$menuExist = false;
			foreach($menu as $item) {
				if(strtolower($item[0]) == strtolower('Brainstorm')) {
					$menuExist = true;
				}
			}

			$contacts = add_submenu_page(
				"convertplug",
				__("Connects","smile"),
				__("Connects","smile"),
				"access_cp",
				"contact-manager",
				array($this, 'contacts_manager')
			);
			add_action( 'admin_footer-'. $contacts, array($this,'cp_admin_footer') );

			$resources_page = add_submenu_page(
				"convertplug",
				__("Resources","contacts_manager"),
				__("Resources","contacts_manager"),
				"access_cp",
				"cp-resources",
				array($this, 'cp_resources')
			);
			add_action( 'admin_footer-'. $resources_page, array($this,'cp_admin_footer') );

			$cust_page = add_submenu_page(
			        'contacts_manager',
			        'Hidden!',
			        'Hidden!',
			        'access_cp',
			        'cp_customizer',
			        array($this, 'cp_customizer_render_hidden_page')
			    );

			add_action( 'admin_footer-'. $cust_page, array($this,'cp_customizer_render_hidden_page') );

			// section wise menu
			global $bsf_section_menu;
			$section_menu = array(
				'menu' => 'cp-resources',
				'is_down_arrow' => true
			);
			$bsf_section_menu[] = $section_menu;

			$google_manager = add_submenu_page(
				"convertplug",
				__("Google Font Manager","smile"),
				__("Google Fonts","smile"),
				"access_cp",
				"bsf-google-font-manager",
				array($this, 'cp_font_manager')
			);

			add_submenu_page(
				"convertplug",
				__("Knowledge Base","smile"),
				__("Knowledge Base","smile"),
				"access_cp",
				"knowledge-base",
				array($this, 'cp_redirect_to_kb' )
			);

			$Ultimate_Google_Font_Manager = new Ultimate_Google_Font_Manager;
			add_action( 'admin_print_scripts-' . $google_manager, array($Ultimate_Google_Font_Manager,'admin_google_font_scripts'));
            add_action( 'admin_footer-'. $google_manager, array($this,'cp_admin_footer') );
		}

		function cp_customizer_render_hidden_page() {
			require_once( plugin_dir_path(__FILE__).'preview.php' );
		}

		function cp_font_manager() {
			$Ultimate_Google_Font_Manager = new Ultimate_Google_Font_Manager;
			$Ultimate_Google_Font_Manager->ultimate_font_manager_dashboard();
		}
		function add_admin_menu_rename(){
			global $menu, $submenu;
			if( isset( $submenu['convertplug'][0][0] ) ) {
			    $submenu['convertplug'][0][0] = 'Dashboard';
			}
		}

		function cp_resources() {
			$icon_manager = false;
			require_once(plugin_dir_path(__FILE__).'admin/resources.php');
		}

		function cp_submenu_order($menu_ord) {
			global $submenu;

		    if(!isset($submenu['convertplug']))
		    	return false;

		    $temp_resource = $temp_connect = $temp_google_font_manager = $temp_font_icon_manager = $temp_in_sync = $temp_knowledge_base = array();
		    foreach ($submenu['convertplug'] as $key => $cp_submenu) {
		    	if($cp_submenu[2] === 'cp-resources') {
		    		$temp_resource = $submenu['convertplug'][$key];
		    		unset($submenu['convertplug'][$key]);
		    	}
		    	if($cp_submenu[2] === 'contact-manager') {
		    		$temp_connect = $submenu['convertplug'][$key];
		    		unset($submenu['convertplug'][$key]);
		    	}
		    	if($cp_submenu[2] === 'bsf-font-icon-manager') {
		    		$temp_font_icon_manager = $submenu['convertplug'][$key];
		    		unset($submenu['convertplug'][$key]);
		    	}
		    	if($cp_submenu[2] === 'bsf-extensions-14058953') {
		    		$temp_addons = $submenu['convertplug'][$key];
		    		unset($submenu['convertplug'][$key]);
		    	}
		    	if($cp_submenu[2] === 'bsf-google-font-manager') {
		    		$temp_google_font_manager = $submenu['convertplug'][$key];
		    		unset($submenu['convertplug'][$key]);
		    	}
		    	if($cp_submenu[2] === 'knowledge-base') {
		    		$temp_knowledge_base= $submenu['convertplug'][$key];
		    		unset($submenu['convertplug'][$key]);
		    	}
		    	if($cp_submenu[2] === 'cp-wp-comment-form') {
		    		$temp_wp_comment_form = $submenu['convertplug'][$key];
		    		unset($submenu['convertplug'][$key]);
		    	}
		    	if($cp_submenu[2] === 'cp-wp-registration-form') {
		    		$temp_wp_registration_form = $submenu['convertplug'][$key];
		    		unset($submenu['convertplug'][$key]);
		    	}
		    	if($cp_submenu[2] === 'cp-woocheckout-form') {
		    		$temp_woocheckout_form = $submenu['convertplug'][$key];
		    		unset($submenu['convertplug'][$key]);
		    	}
		    	if($cp_submenu[2] === 'cp-contact-form7') {
		    		$temp_contact_form7 = $submenu['convertplug'][$key];
		    		unset($submenu['convertplug'][$key]);
		    	}

		    }

		    array_filter($submenu['convertplug']);

	    	if(!empty($temp_resource)) {
	    		array_push($submenu['convertplug'], $temp_resource);
	    	}
	    	if(!empty($temp_connect)) {
	    		array_push($submenu['convertplug'], $temp_connect);
	    	}
	    	if(!empty($temp_addons)) {
	    		array_push($submenu['convertplug'], $temp_addons);
	    	}
	    	if(!empty($temp_google_font_manager)) {
	    		array_push($submenu['convertplug'], $temp_google_font_manager);
	    	}
	    	if(!empty($temp_knowledge_base)) {
	    		array_push($submenu['convertplug'], $temp_knowledge_base);
	    	}
	    	if(!empty($temp_font_icon_manager)) {
	    		array_push($submenu['convertplug'], $temp_font_icon_manager);
	    	}
	    	if(!empty($temp_wp_comment_form)) {
	    		array_push($submenu['convertplug'], $temp_wp_comment_form);
	    	}
	    	if(!empty($temp_wp_registration_form)) {
	    		array_push($submenu['convertplug'], $temp_wp_registration_form);
	    	}
	    	if(!empty($temp_woocheckout_form)) {
	    		array_push($submenu['convertplug'], $temp_woocheckout_form);
	    	}
	    	if(!empty($temp_contact_form7)) {
	    		array_push($submenu['convertplug'], $temp_contact_form7);
	    	}

		    return $menu_ord;
		}

		/*
		* Load scripts and styles on admin area of convertPlug
		* @Since 1.0
		*/
		function convert_admin_scripts() {

			wp_enqueue_script( 'jQuery' );
			wp_enqueue_style( 'thickbox' );

			$data  =  get_option( 'convert_plug_debug' );

			// developer mode
			if( isset( $data['cp-dev-mode'] ) && $data['cp-dev-mode'] == '1' ) {

				// accordion
				wp_enqueue_script( 'convert-accordion-widget', plugins_url('admin/assets/js/jquery.widget.min.js',__FILE__) );
				wp_enqueue_script( 'convert-accordion', plugins_url('admin/assets/js/accordion.js',__FILE__));

				wp_enqueue_script( 'convert-admin', plugins_url('admin/assets/js/admin.js',__FILE__));

				// shuffle js scripts
				wp_enqueue_script( 'smile-jquery-modernizer', plugins_url('modules/assets/js/jquery.shuffle.modernizr.js',__FILE__),'','',true);
				wp_enqueue_script( 'smile-jquery-shuffle', plugins_url('modules/assets/js/jquery.shuffle.min.js',__FILE__),'','',true);
				wp_enqueue_script( 'smile-jquery-shuffle-custom', plugins_url('modules/assets/js/shuffle-script.js',__FILE__),'','',true);

				// sweet alert
				wp_enqueue_script( 'cp-swal-js', plugins_url('admin/assets/js/sweetalert.min.js',__FILE__), false, false, true );

			} else {
				wp_enqueue_script( 'convert-admin-js', plugins_url('admin/assets/js/admin.min.js',__FILE__),'','',true);
			}

			if( ( isset( $_GET['style-view'] ) && ( $_GET['style-view'] == "edit" || $_GET['style-view'] == "variant" ) ) || !isset( $_GET['style-view'] ) ) {

				wp_enqueue_script( 'convert-select2', plugins_url('admin/assets/select2/select2.min.js',__FILE__), false, '2.4.0.1');

			}

			// REMOVE WP EMOJI
			remove_action('wp_head', 'print_emoji_detection_script', 7);
			remove_action('wp_print_styles', 'print_emoji_styles');

			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );

		}

		/*
		*Add footer link for dashboar
		*Since 1.0.1
		*/
		function cp_admin_footer() {
			echo'<div id="wpfooter" role="contentinfo" class="cp_admin_footer">
				        <p id="footer-left" class="alignleft">
				        <span id="footer-thankyou">Thank you for using <a href="https://www.convertplug.com/" target="_blank" >ConvertPlug</a>.</span>   </p>
				    <p id="footer-upgrade" class="alignright">';
				       _e( "Version", "smile" ); echo ' '.CP_VERSION;
				        ;echo  '</p>
				    <div class="clear"></div>
				</div>';
		}

		/*
		* Load convertPlug dashboard
		* @Since 1.0
		*/
		function admin_dashboard(){
			require_once('admin/admin.php');
		}

		/*
		* Load convertPlug contacts manager
		* @Since 1.0
		*/
		function contacts_manager(){
			require_once('admin/contacts/admin.php');
		}

		function cp_add_core_styles($hooks) {

		    $contactsPage_hook = 'convertplug_page_contact-manager';
		    $cpmainPage_hook = 'toplevel_page_convertplug';
		    array_push($hooks,$contactsPage_hook,$cpmainPage_hook);
		    return $hooks;
		}


		/**
		* Redirects to the premium version of MailChimp for WordPress (uses JS)
		*/
		function cp_redirect_to_kb() {

			?><script type="text/javascript">window.location.replace('<?php echo admin_url(); ?>admin.php?page=convertplug&view=knowledge_base'); </script><?php
		}

		/*
		* Load frosty scripts from bsf core
		* @Since 2.1.0
		*/
		function load_frosty_scripts_from_core($hooks) {

			// page hooks array where we need frosty scripts to load
			$array = array(
				'toplevel_page_convertplug',
				'convertplug_page_smile-modal-designer',
				'convertplug_page_smile-info_bar-designer',
				'convertplug_page_smile-slide_in-designer',
				'convertplug_page_contact-manager',
				'convertplug_page_role-manager',
				'admin_page_cp_customizer',
				'convertplug_page_cp-wp-registration-form'
			);
			foreach ($array as $hook) {
				array_push($hooks, $hook);
			}
			return $hooks;
		}

		/*
		* Retrieve and store modules into the static variable $modules
		* @accepts    ->  array of modules in form of "Module Name" => "Module Main File"
		* @Since 1.0
		*/
		public static function convert_plug_store_module($modules_array){
			$result = false;
			if(!empty($modules_array)){
				self::$modules = $modules_array;
				$result = true;
			}
			return $result;
		}

		/*
		* Created default campaign on activation
		*
		* @Since 1.0
		*/
		function create_default_campaign(){

			// create default campaign
			$smile_lists = get_option('smile_lists');
			if(!$smile_lists) {
				$data = array();
				$list = array(
					"date"           => date("d-m-Y"),
					"list-name"      => "First",
					"list-provider"  => "Convert Plug",
					"list"           => "",
					"provider_list"  => ""
					);

				$data[] = $list;
				update_option('smile_lists',$data);
			}
		}

		/*
		* Redirect on activation hook
		*
		* @Since 1.0
		*/
		function cp_redirect_on_activation(){

			if( get_option('convert_plug_redirect') == true ) {
				update_option('convert_plug_redirect',false);
				$this->create_default_campaign();
				if(!is_multisite()) :
					wp_redirect(admin_url('admin.php?page=convertplug'));
				endif;
			}
		}

		/*
		* Add custom css for customizer admin page
		*
		* @Since 2.0.1
		*/
		function cp_custom_css($hook) {
			if( isset( $_GET['page'] ) && $_GET['page'] == 'cp_customizer' ) {

			  echo '<style>
			    #adminmenuwrap,
			    #adminmenuback,
			    #wpadminbar,
			    #wpfooter,
			    .media-upload-form .notice,
			    .media-upload-form div.error,
			    .update-nag,
			    .updated,
			    .wrap .notice,
			    .wrap div.error,
			    .wrap div.updated,
			    .notice-warning,
			    #wpbody-content .error,
			    #wpbody-content .notice {
			  		display: none !important;
				}
			  </style>';

			   //Remove WooCommerce's annoying update message
			   remove_action( 'admin_notices', 'woothemes_updater_notice' );

			   //Remove admin notices
			   remove_action( 'admin_notices', 'update_nag', 3 );
			}
		}

		/*
		* Deregister scripts on customizer page
		*
		* @Since 2.3.1
		*/
		function cp_dergister_scripts($hook) {

			$data    =  get_option( 'convert_plug_settings' );
			$psval   = isset( $data['cp-plugin-support'] ) ? $data['cp-plugin-support'] : 1;

			if( $psval ) {

	 			$page_hooks = array(
					'convertplug_page_smile-modal-designer',
					'convertplug_page_smile-info_bar-designer',
					'convertplug_page_smile-slide_in-designer',
					'admin_page_cp_customizer'
				);

				if( in_array( $hook, $page_hooks ) ) {

					if( ( isset( $_GET['style-view'] ) && ( $_GET['style-view'] == 'edit' || $_GET['style-view'] == 'variant'  ) )  || isset( $_GET['hidemenubar'] ) )  {

						global $wp_scripts;
			        	$scripts = $wp_scripts->registered;
			        	$deregistered_scripts = array();

			        	if( is_array($scripts) ) {
				        	foreach ($scripts as $key => $script) {

				        		$source = $script->src;

				        		// if script is registered by plugin other than ConvertPlg OR by Theme
				        		if( ( strpos( $source, "wp-content/plugins" ) && !strpos( $source, "wp-content/plugins/". CP_DIR_NAME ) ) || strpos( $source, "wp-content/themes" ) ) {

				        			if( isset( $script->handle ) ) {
					        			$handle = $script->handle;
					        			$source = $script->src;

					        			$deregistered_scripts[$source] = $handle;

					        			// deregister script handle
					        			wp_deregister_script( $handle );
					        		}
				        		}

				        	}
				        }

				        if( !empty($deregistered_scripts) ) {
				        	update_option( 'cp_scripts_debug_info', $deregistered_scripts );
				        }

				    }
				}
			}
		}

		/*
		* Display debug info for excluded scripts
		*
		* @Since 2.3.1
		*/
		function cp_add_debug_info() {

			$is_displayed_info = get_option( 'cp_is_displayed_debug_info' );

			// if debug info is not already displayed
			if( !$is_displayed_info ) {

				$screen = get_current_screen();

				$current_page_hook = $screen->base;

				$page_hooks = array(
					'convertplug_page_smile-modal-designer',
					'convertplug_page_smile-info_bar-designer',
					'convertplug_page_smile-slide_in-designer'
				);

				if( in_array( $current_page_hook, $page_hooks ) && !isset($_GET['hidemenubar']) ) {

					update_option( "cp_is_displayed_debug_info", true );

					$debug_info = get_option( 'cp_scripts_debug_info' );

					$debug_info_html = "<!-- CP Debug Information - List of the JS disabled on customizer screen ----------- \n";

					if( is_array($debug_info) ) {
						foreach ($debug_info as $src => $handle) {
							$string = $handle . " :- " . $src;
							$debug_info_html .= $string ."\n";
						}
					}

					$debug_info_html .= "<!-- End - CP Debug Information -->";

					echo $debug_info_html;
				}
			}
		}

	}


	/*
	* Public Function to search style from multidimentional array
	* @accepts		-> array of styles and style name to be searched
	* @return		-> array key if style is found in the given array
	* @Since 1.0
	*/
	function search_style($array, $style)
	{
		if( is_array($array) ) {
			foreach ( $array as $key => $data )
			{
				$data_style = isset($data['style_id']) ? $data['style_id'] : '';
				if ($data_style == $style)
					return $key;
			}
		}
	}
	/*
	* Public function for accepting requests for adding new module in the convert plug
	* @accepts    ->  array of modules in form of "Module Name" => "Module Main File"
	* @Since 1.0
	*/
	function convert_plug_add_module($modules_array){
		return Convert_Plug::convert_plug_store_module($modules_array);
	}

	function cp_editor_styles() {
    	add_editor_style( plugins_url('admin/assets/css/cp-editor.css',__FILE__) );
	}

	// load modules
	require_once('modules/config.php');

}
new Smile_Framework;
new Convert_Plug;

// load google fonts class
require_once('framework/Ultimate_Font_Manager.php');

/// set global variables
global $cp_analytics_start_time,$cp_analytics_end_time,$colorPallet,$cp_default_dateformat;

$colorPallet = array (
		    		'rgba(26, 188, 156,1.0)',
		    		'rgba(46, 204, 113,1.0)',
		    		'rgba(52, 152, 219,1.0)',
		    		'rgba(155, 89, 182,1.0)',
		    		'rgba(52, 73, 94,1.0)',
		    		'rgba(241, 196, 15,1.0)',
		    		'rgba(230, 126, 34,1.0)',
		    		'rgba(231, 76, 60,1.0)',
					'rgba(236, 240, 241,1.0)',
					'rgba(149, 165, 166,1.0)'
);

$cp_analytics_end_time = current_time( 'd-m-Y');
$date = date_create($cp_analytics_end_time);
date_sub($date, date_interval_create_from_date_string('9 days'));
$cp_analytics_start_time = date_format($date, 'd-m-Y');

if ( get_magic_quotes_gpc() ) {
    $_POST      = array_map( 'stripslashes_deep', $_POST );
    $_GET       = array_map( 'stripslashes_deep', $_GET );
    $_COOKIE    = array_map( 'stripslashes_deep', $_COOKIE );
    $_REQUEST   = array_map( 'stripslashes_deep', $_REQUEST );
}
// bsf core
$bsf_core_version_file = realpath(dirname(__FILE__).'/admin/bsf-core/version.yml');
if(is_file($bsf_core_version_file)) {
	global $bsf_core_version, $bsf_core_path;
	$bsf_core_dir = realpath(dirname(__FILE__).'/admin/bsf-core/');
	$version = file_get_contents($bsf_core_version_file);
	if(version_compare($version, $bsf_core_version, '>')) {
		$bsf_core_version = $version;
		$bsf_core_path = $bsf_core_dir;
	}
}
add_action('init', 'bsf_core_load', 999);
if(!function_exists('bsf_core_load')) {
	function bsf_core_load() {
		global $bsf_core_version, $bsf_core_path;
		if(is_file(realpath($bsf_core_path.'/index.php'))) {
			include_once realpath($bsf_core_path.'/index.php');
		}
	}
}
add_filter('bsf_core_style_screens', 'cp_bsf_core_style_hooks');
function cp_bsf_core_style_hooks($hooks) {
	$resources_page_hook = 'convertplug_page_cp-resources';
	array_push($hooks, $resources_page_hook);
	return $hooks;
}

/**
 * Register Comvertplug Addons installer menu
 */
if ( ! function_exists( 'cp_bsf_extensions_menu' ) ) {

	function cp_bsf_extensions_menu( $reg_menu ) {

		$reg_menu = get_site_option( 'bsf_installer_menu', $reg_menu );

		$_dir = CP_BASE_DIR;

		$bsf_cp_id = bsf_extract_product_id( $_dir );

		$reg_menu['ConvertPlug'] = array(
			'parent_slug'	=>	'convertplug',
			'page_title'	=>	__('Addons','smile'),
			'menu_title' 	=>	__('Addons','smile'),
			'product_id' 	=>	$bsf_cp_id,
		);

		update_site_option( 'bsf_installer_menu', $reg_menu );


		return $reg_menu;
	}

}

add_filter( 'bsf_installer_menu', 'cp_bsf_extensions_menu' );
if ( is_multisite() ) {
	add_action( 'admin_head', 'cp_bsf_extensions_menu' );
}

/**
 * Heading for the extensions installer screen
 *
 * @return String: Heading to which will appear on Extensions installer page
 */
function cp_bsf_extensioninstaller_heading() {
	return 'ConvertPlug Addons';
}

add_filter( 'bsf_extinstaller_heading_14058953', 'cp_bsf_extensioninstaller_heading' );

/**
 * Sub Heading for the extensions installer screen
 *
 * @return String: Sub Heading to which will appear on Extensions installer page
 */
function cp_bsf_extensioninstaller_subheading() {
	return 'Add-ons extend the functionality of ConvertPlug. With these addons, you can connect with third party softwares, integrate new features and make ConvertPlug even more powerful.';
}

add_filter( 'bsf_extinstaller_subheading_14058953', 'cp_bsf_extensioninstaller_subheading' );
/**
 * Heading for the extensions installer screen
 *
 * @return String: Heading to which will appear on Extensions installer page
 */
function cp_extensioninstaller_heading() {
	return 'ConvertPlug Addons';
}

add_filter( 'bsf_extinstaller_heading_14058953', 'cp_extensioninstaller_heading' );

/**
 * Sub Heading for the extensions installer screen
 *
 * @return String: Sub Heading to which will appear on Extensions installer page
 */
function cp_extensioninstaller_subheading() {
	return 'Add-ons extend the functionality of ConvertPlug. With these addons, you can connect with third party softwares, integrate new features and make ConvertPlug even more powerful.';
}

add_filter( 'bsf_extinstaller_subheading_14058953', 'cp_extensioninstaller_subheading' );


// BSF CORE commom functions
if(!function_exists('bsf_get_option')) {
	function bsf_get_option($request = false) {
		$bsf_options = get_option('bsf_options');
		if(!$request)
			return $bsf_options;
		else
			return (isset($bsf_options[$request])) ? $bsf_options[$request] : false;
	}
}
if(!function_exists('bsf_update_option')) {
	function bsf_update_option($request, $value) {
		$bsf_options = get_option('bsf_options');
		$bsf_options[$request] = $value;
		return update_option('bsf_options', $bsf_options);
	}
}
add_action( 'wp_ajax_bsf_dismiss_notice', 'bsf_dismiss_notice');
if(!function_exists('bsf_dismiss_notice')) {
	function bsf_dismiss_notice() {
		$notice = $_POST['notice'];
		$x = bsf_update_option($notice, true);
		echo ($x) ? true : false;
		die();
	}
}

add_action('admin_init', 'bsf_core_check',10);
if(!function_exists('bsf_core_check')) {
	function bsf_core_check() {
		if(!defined('BSF_CORE')) {
			if(!bsf_get_option('hide-bsf-core-notice'))
				add_action( 'admin_notices', 'bsf_core_admin_notice' );
		}
	}
}

if(!function_exists('bsf_core_admin_notice')) {
	function bsf_core_admin_notice() {
		?>
		<script type="text/javascript">
		(function($){
			$(document).ready(function(){
				$(document).on( "click", ".bsf-notice", function() {
					var bsf_notice_name = $(this).attr("data-bsf-notice");
				    $.ajax({
				        url: ajaxurl,
				        method: 'POST',
				        data: {
				            action: "bsf_dismiss_notice",
				            notice: bsf_notice_name
				        },
				        success: function(response) {
				        	console.log(response);
				        }
				    })
				})
			});
		})(jQuery);
		</script>
		<div class="bsf-notice update-nag notice is-dismissible" data-bsf-notice="hide-bsf-core-notice">
            <p><?php _e( 'License registration and extensions are not part of plugin/theme anymore. Kindly download and install "BSF CORE" plugin to manage your licenses and extensins.', 'bsf' ); ?></p>
        </div>
		<?php
	}
}

if(isset($_GET['hide-bsf-core-notice']) && $_GET['hide-bsf-core-notice'] === 're-enable') {
	$x = bsf_update_option('hide-bsf-core-notice', false);
}

/*
 * Function to display admin notice after updating plugin
*/
if( !function_exists( 'cp_update_admin_notice' ) ) {
	function cp_update_admin_notice() {
	    ?>
	    <script type="text/javascript" >
	    	(function($){
				$(document).ready(function(){
					$(document).on( "click", ".cp-update-notice .cp-notice-dismiss", function() {
						var cp_notice_name = $(this).closest('div').attr("data-cp-notice");
					    $.ajax({
					        url: ajaxurl,
					        method: 'POST',
					        data: {
					            action: "cp_dismiss_notice",
					            notice: cp_notice_name
					        },
					        success: function(response) {
					        	console.log(response);
					        	jQuery(".cp-update-notice").remove();
					        }
					    })
					})
				});
			})(jQuery);
	    </script>
	    <div class="notice cp-update-notice notice-success " data-cp-notice="dismiss-cp-update-notice">
	    	<?php
	    	$is_new_user = get_option( 'cp_is_new_user' );
	    	if( $is_new_user ) {
	    	?>
		        <p><?php _e( "You've just installed ConvertPlug 2.1.0 As we've made important changes in this version, you are strongly advised to read the changelog <a target='_blank' href='https://changelog.brainstormforce.com/convertplug/author/brainstormforce/'>here</a>.", 'smile' ); ?><a class="cp-notice-dismiss" style='float:right; padding-right: 10px; color:red; text-decoration: none;' href="javascript:void(0)">Dismiss this notice </a></p>
		    <?php
	    	} else {
	    	?>
	    		<p><?php _e( "You've just updated ConvertPlug 2.1.0 As we've made important changes in this version, you are strongly advised to read the changelog <a target='_blank' href='https://changelog.brainstormforce.com/convertplug/author/brainstormforce/'>here</a>.", 'smile' ); ?><a class="cp-notice-dismiss" style='float:right; padding-right: 10px; color:red; text-decoration: none;' href="javascript:void(0)">Dismiss this notice </a></p>
	    	<?php } ?>
	    </div>
	    <?php
	}
}

add_action( 'wp_ajax_cp_dismiss_notice', 'cp_dismiss_notice');
if(!function_exists('cp_dismiss_notice')) {
	function cp_dismiss_notice() {
		$notice = $_POST['notice'];
		$x = update_option($notice, true);
		echo ($x) ? true : false;
		die();
	}
}

/*
 * Function to display admin notice for outdated php version
*/
if( !function_exists( 'cp_php_version_notice' ) ) {
	function cp_php_version_notice() {
	    ?>
	    <div class="notice notice-warning cp-php-warning is-dismissible">
		        <p><?php _e( "Your server seems to be running outdated, unsupported and vulnerable version of PHP. You are advised to contact your host and upgrade to PHP 5.6 or greater.", 'smile' ); ?></p>
	    </div>
	    <?php
	}
}

// end of common functions


function cp_back_compatiblity_image() {

	$modules = array(
		"slide_in",
		"info_bar"
	);

	foreach ( $modules as $module ) {

		$styles = get_option('smile_'.$module.'_styles');

		if( is_array($styles) ) {

			foreach ( $styles as $key => $style ) {

				$style_settings = $style['style_settings'];

				$sett = unserialize($style_settings);

				$old_bg_image_option = str_replace( "_", "", $module ) . '_bg_image';
				$new_bg_image_option = $module . '_bg_image';

				$old_image_option = str_replace( "_", "", $module ) . '_image';
				$new_image_option = $module . '_image';

				// style background image
				if( isset($sett[$old_bg_image_option]) && $sett[$old_bg_image_option] !== '' ) {
					$image = $sett[$old_bg_image_option];
					unset( $sett[$old_bg_image_option] );
					$sett[$new_bg_image_option] = $image;
				}

				/// style image
				if( isset($sett[$old_image_option]) && $sett[$old_image_option] !== '' ) {
					$image = $sett[$old_image_option];
					unset( $sett[$old_image_option] );
					$sett[$new_image_option] = $image;
				}

				$style['style_settings'] =  serialize($sett);

				$styles[$key] = $style;

			}

			update_option( 'smile_'.$module.'_styles' , $styles );

		}
	}

	update_option( 'cp_image_compatibility_check', true );

}
