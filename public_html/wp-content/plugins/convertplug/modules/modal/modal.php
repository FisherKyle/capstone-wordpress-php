<?php
require_once('functions/functions.php');
if(!class_exists('Smile_Modals')){
	class Smile_Modals extends Convert_Plug{
		public static $settings = array();
		public static $options = array();
		function __construct(){
			add_action( 'wp_enqueue_scripts',array($this,'enqueue_front_scripts' ), 100);
			add_action( 'admin_enqueue_scripts',array($this,'enqueue_admin_scripts' ) );
			add_action( 'admin_menu',array($this,'add_admin_menu_page' ), 999);
			add_action( 'admin_head',array($this,'load_customizer_scripts' ) );
			add_action( 'wp_footer', array( $this, 'load_modal_globally' ) );
			add_action( 'init', array( $this, 'register_theme_templates') );
			add_filter( 'admin_body_class', array( $this, 'cp_admin_body_class') );
			require_once( 'modal_preset.php' );
		}

		function cp_admin_body_class( $classes ) {

			if( isset( $_GET['style-view']) && $_GET['style-view'] == "new" ){
	        	$classes .= 'cp-add-new-style';
	        }
			return $classes;
		}

		function register_theme_templates(){
			$dir = plugin_dir_path( __FILE__ );
			$themes = glob($dir . 'themes/*.php');
			foreach( $themes as $theme ){
				require_once( $theme );
			}
		}

		function add_admin_menu_page(){
			$page = add_submenu_page(
				'convertplug',
				'Modal Popup Designer',
				'Modal Popup',
				'access_cp',
				'smile-modal-designer',
				array($this,'modal_dashboard') );
			$obj = new parent;
			add_action( 'admin_print_scripts-' . $page, array($obj,'convert_admin_scripts'));
			add_action( 'admin_print_scripts-' . $page, array($this,'modal_admin_scripts'));
            add_action( 'admin_footer-'. $page, array($this,'cp_admin_footer') );

		}

		function modal_admin_scripts(){
			if( ( isset( $_GET['style-view'] ) && ( $_GET['style-view'] == "edit" || $_GET['style-view'] == "variant" ) ) || !isset( $_GET['style-view'] ) ) {

				wp_enqueue_script( 'smile-modal-receiver', 			plugins_url( 'assets/js/receiver.js',__FILE__) );
				wp_enqueue_media();
				wp_enqueue_script( 'smile-modal-importer', 			plugins_url( '../assets/js/admin-media.js',__FILE__),array( 'jquery' ),'',true);
			}

			if( isset($_GET['style-view']) && $_GET['style-view'] == 'analytics' ) {
				wp_enqueue_style( 'css-select2',					plugins_url( '../../admin/assets/select2/select2.min.css', __FILE__ ) );
				wp_enqueue_script( 'convert-select2',				plugins_url( '../../admin/assets/select2/select2.min.js', __FILE__ ) );
				wp_enqueue_script( 'bsf-charts-js',					plugins_url( '../../admin/assets/js/chart.js', __FILE__ ) );
				wp_enqueue_script( 'bsf-charts-bar-js',				plugins_url( '../../admin/assets/js/chart.bar.js', __FILE__ ) );
				wp_enqueue_script( 'bsf-charts-donut-js',			plugins_url( '../../admin/assets/js/chart.donuts.js', __FILE__ ) );
				wp_enqueue_script( 'bsf-charts-line-js',			plugins_url( '../../admin/assets/js/Chart.Line.js', __FILE__ ) );
				wp_enqueue_script( 'bsf-charts-polararea-js',		plugins_url( '../../admin/assets/js/Chart.PolarArea.js', __FILE__ ) );
				wp_enqueue_script( 'bsf-style-analytics-js',		plugins_url( '../assets/js/style-analytics.js', __FILE__ ) );
			}
		}

		function modal_dashboard(){
			$page = isset($_GET['style-view']) ? $_GET['style-view'] : 'main';

			// load default option set
			require_once('functions/functions.options.php');

			switch($page){
				case 'main':
					require_once('views/main.php');
					break;
				case 'new':
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
					$gfonts = implode( ",", $default_google_fonts );
					require_once('functions/functions.php');
					if( function_exists( "cp_enqueue_google_fonts" ) ){
						//cp_enqueue_google_fonts( $gfonts );
					}
					require_once('views/new-style.php');
					break;
				case 'edit':
					require_once('views/edit.php');
					break;
				case 'variant':
					require_once('views/variant.php');
					break;
				case 'analytics':
					require_once('views/analytics.php');
					break;
			}
		}

		function load_modal_globally(){

			if(!isset($_GET['hidemenubar'])){
				?>
	            <script type="text/javascript" id="modal">
				jQuery(window).on( 'load', function(){
					startclock();
				});
				function stopclock (){
				  if(timerRunning) clearTimeout(timerID);
				  timerRunning = false;
				  document.cookie="time=0";
				}
				function showtime () {
				  var now = new Date();
				  var my = now.getTime() ;
				  now = new Date(my-diffms) ;
				  document.cookie="time="+now.toLocaleString();
				  timerID = setTimeout('showtime()',10000);
				  timerRunning = true;
				}
				function startclock () {
				  stopclock();
				  showtime();
				}
				var timerID = null;
				var timerRunning = false;
				var x = new Date() ;
				var now = x.getTime() ;
				var gmt = <?php echo time(); ?> * 1000 ;
				var diffms = (now - gmt) ;
				</script>
	            <?php

				$modal_style = $modal_style_delay = $modal_cookie_delay = '';
				$live_styles = smile_get_live_styles();
				$prev_styles = get_option('smile_modal_styles');
				$smile_variant_tests = get_option('modal_variant_tests');

				if( is_array($live_styles) && !empty( $live_styles ) ) {

		            global $post;
					$modal_arrays = $live_styles;

					foreach( $modal_arrays as $key => $modal_array ){
						$display = false;
						$settings_encoded = '';

						$style_settings = array();
						$global_display = $pages_to_exclude = $cats_to_exclude = $exclusive_pages = $exclusive_cats = $show_for_logged_in = '';
						$settings_array = unserialize($modal_array[ 'style_settings' ]);
						foreach($settings_array as $key => $setting){
							$style_settings[$key] = apply_filters( 'smile_render_setting',$setting );
						}

						$style_id = $modal_array[ 'style_id' ];
						$modal_style = $style_settings[ 'style' ];

						if( is_array($style_settings) && !empty($style_settings) ){

							$settings = unserialize( $modal_array[ 'style_settings' ] );
							$css = isset( $settings['custom_css'] ) ? urldecode($settings['custom_css']) : '';

							$display = cp_is_style_visible($settings);

							// remove back slashes from settings
							$settings = stripslashes_deep( $settings );

							$settings = serialize( $settings );
							$settings_encoded 	= base64_encode( $settings );
						}

						if( $display ) {

							$data  =  get_option( 'convert_plug_debug' );

							// developer mode
							if( isset( $data['cp-dev-mode'] ) && $data['cp-dev-mode'] == '1' ) {

								$script_handlers = array(
									'smile-modal-common',
									'smile-modal-script',
									'cp-ideal-timer-script',
									'cp-modal-mailer-script',
									'bsf-core-frosty'
								);

	   							$list = 'enqueued';

	   							foreach( $script_handlers as $handler ) {
	   								if ( !wp_script_is( $handler, $list ) ) {
								       wp_enqueue_script( $handler );
								    }
	   							}

	   						} else {

	   							if ( !wp_script_is( 'bsf-core-frosty', 'enqueued' ) ) {
								    wp_enqueue_script( 'bsf-core-frosty' );
								}

								if ( !wp_script_is( 'cp-ideal-timer-script', 'enqueued' ) ) {
								    wp_enqueue_script( 'cp-ideal-timer-script' );
								}

								if ( !wp_style_is( 'bsf-core-frosty-style', 'enqueued' ) ) {
								    wp_enqueue_style( 'bsf-core-frosty-style' );
								}

								if ( !wp_script_is( 'smile-modal-script', 'enqueued' ) ) {
								    wp_enqueue_script( 'smile-modal-script' );
								}
	   						}

							//	Generate style ID
							$id = $modal_style . '-' . $style_id;

							//	Individual Style Path
							$file_name = '/assets/demos/'. strtolower($modal_style) . '/' . strtolower($modal_style) . '.min.css';
							$url = plugins_url( $file_name , __FILE__ );

							require_once( 'functions/functions.options.php' );
							$demo_html = $customizer_js = '';
							$settings = $this::$options;
							foreach( $settings as $style => $options ){
								if( $style == $modal_style ){
									$customizer_js = $options['customizer_js'];
									$url = str_replace( "customizer.js", strtolower( $modal_style ) .".min.css", $customizer_js);
								}
							}

							if( $customizer_js !== "" ) {
								//	Check file exist or not - and append to the head
								wp_enqueue_style( $id, $url );
							}

							echo do_shortcode('[smile_modal style_id = '.$style_id.' style="'.$modal_style.'" settings_encoded="' . $settings_encoded . ' "][/smile_modal]');
							apply_filters('cp_custom_css',$style_id, $css);
						}
					}
				}
			}
		}

		function load_customizer_scripts(){
			if( isset( $_GET['hidemenubar'] ) && isset( $_GET['module'] ) && $_GET['module'] == "modal" ){

				//countdown js
				wp_enqueue_style( 'cp-countdown-style');
				wp_enqueue_script( 'cp-counter-plugin-js');
				wp_enqueue_script( 'cp-countdown-js');

				//	Enqueue - CKEditor script
				wp_enqueue_style( 'cp-perfect-scroll-style', plugins_url('../../admin/assets/css/perfect-scrollbar.min.css',__FILE__) );
				wp_enqueue_script( 'cp-common-functions-js' );
				wp_enqueue_script( 'cp-admin-customizer-js', plugins_url( '../assets/js/admin.customizer.js', __FILE__ ) );
				require_once( 'functions/functions.options.php' );
				$demo_html = $customizer_js = '';
				$settings = $this::$options;
				foreach( $settings as $style => $options ){
					if( $style == $_GET['theme'] ){
						$customizer_js = $options['customizer_js'];
					}
				}
				if( $customizer_js !== "" ){
					wp_enqueue_script( 'cp-style-customizer-js', $customizer_js , "", true );
				}
			}
		}

		function enqueue_admin_scripts($hook) {

			if( ( isset( $_GET['hidemenubar'] ) && $_GET['module'] == 'modal' )
				 || ( isset($_GET['style-view']) && $_GET['style-view'] == 'new' && $hook == 'convertplug_page_smile-modal-designer' ) ) {

				wp_enqueue_style( 'smile-modal', plugins_url( 'assets/css/modal.min.css', __FILE__ ) );
				wp_register_script( 'cp-frosty-script', plugins_url( '../../admin/assets/js/frosty.js', __FILE__), array( 'jquery' ), null, null, true );
            	wp_register_script( 'smile-modal-common', plugins_url( 'assets/js/modal.common.js', __FILE__), array( 'cp-style-customizer-js' ), null, true );
            	wp_register_script( 'cp-common-functions-js', plugins_url( 'assets/js/functions-common.js', __FILE__ ), array('smile-modal-common'), null, true );

				wp_enqueue_script( 'smile-modal');
				wp_enqueue_script( 'smile-modal-common' );
				wp_localize_script( 'smile-modal-common', 'cp', array(
														'demo_dir' => plugins_url('/assets/demos', __FILE__ ) ,
														'module' => 'modal',
														"module_img_dir" => plugins_url( "../assets/images", __FILE__)
														) );

				//	Add 'Theme Name' as a class to <html> tag
				//	To provide theme compatibility
				$theme_name = wp_get_theme();
				$theme_name = $theme_name->get( "Name" );
				$theme_name = strtolower( preg_replace("/[\s_]/", "-", $theme_name ) );

				wp_localize_script( 'jquery', 'cp_active_theme', array( 'slug' => $theme_name ) );
				wp_localize_script( 'cp-common-functions-js', 'smile_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );

          	}
		}

		function enqueue_front_scripts(){

            $live_styles = cp_get_live_styles('modal');

			// if any style is live or modal is in live preview mode then only enqueue scripts and styles
			if( $live_styles && count($live_styles) > 0 ) {

					$data  =  get_option( 'convert_plug_debug' );

					if( isset( $data['cp-dev-mode'] ) && $data['cp-dev-mode'] == '1' ) {

						// register styles
						wp_enqueue_style( 'smile-modal-style', plugins_url( 'assets/css/modal.css', __FILE__ ) );
						wp_enqueue_style( 'smile-modal-grid-style', plugins_url( 'assets/css/modal-grid.css', __FILE__ ) );
						wp_enqueue_style( 'cp-animate-style', plugins_url( '../assets/css/animate.css', __FILE__ ) );
						wp_enqueue_style( 'cp-social-media-style', plugins_url( '../assets/css/cp-social-media-style.css', __FILE__ ) );
						wp_enqueue_style( 'cp-social-icon-style', plugins_url( '../assets/css/social-icon-css.css', __FILE__ ) );
						wp_enqueue_style( 'convertplug-style', plugins_url( '../assets/css/convertplug.css', __FILE__ ) );

						// register scripts
						wp_register_script( 'smile-modal-common', plugins_url( 'assets/js/modal.common.js', __FILE__), null , null, true );
						wp_register_script( 'smile-modal-script', plugins_url( 'assets/js/modal.js', __FILE__), array( 'jquery' ), null, null, true );
						wp_register_script( 'cp-ideal-timer-script', plugins_url( 'assets/js/idle-timer.min.js', __FILE__), array( 'jquery' ), null, null, true );
						wp_register_script( 'cp-modal-mailer-script', plugins_url( 'assets/js/mailer.js', __FILE__), array( 'jquery' ), null,
								null, true );
						wp_register_script( 'cp-frosty-script', plugins_url( '../../admin/assets/js/frosty.js', __FILE__), array( 'jquery' ), null, null, true );

					} else {
						wp_register_script( 'smile-modal-script', plugins_url( 'assets/js/modal.min.js', __FILE__), array( 'jquery' ), null, null, true );
						wp_enqueue_style( 'smile-modal-style', plugins_url( 'assets/css/modal.min.css', __FILE__) );
					}

				wp_localize_script( 'smile-modal-script', 'smile_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
			}
		}

	}
	$Smile_Modals = new Smile_Modals;
}

if (!function_exists('smile_modal_popup')) {
	function smile_modal_popup( $atts, $content = null ) {
		$style = '';
		extract(shortcode_atts(array(
			'style' 				=> '',
			'style_name'			=> '',
		), $atts));
		$output = '';
		$func = 'modal_theme_'.$style;

		$settings = base64_decode( $atts['settings_encoded'] );
		$style_settings = unserialize( $settings );

		// remove back slashes from settings
		$settings = stripslashes_deep( $style_settings );

		$settings = serialize( $settings );
		$settings_encoded 	= base64_encode( $settings );
		$atts['settings_encoded'] = $settings_encoded;

		if( function_exists( $func ) ) {
			$output = $func( $atts );
		}
		echo $output;
	}
	add_shortcode('smile_modal', 'smile_modal_popup');
}


if (!function_exists('cp_modal_custom')) {
	function cp_modal_custom( $atts, $content = null ) {
		ob_start();
		$id = $display = '';
		extract(shortcode_atts(array(
			'id' 				=> '',
			'display'			=> '',
		), $atts));
		$live_styles = smile_get_live_styles();
		$live_array = $settings = '';
		foreach( $live_styles as $key => $modal_array ){
			 $style_id = $modal_array[ 'style_id' ];

			$settings = unserialize( $modal_array[ 'style_settings' ] );
			if(isset($settings['variant_style_id']) && $id == $settings['style_id']){
				$id = $settings['variant_style_id'];
			}

			if( $id == $style_id )
			{
				$live_array = $modal_array;
				$settings = unserialize( $modal_array[ 'style_settings' ] );
				$settings_array = unserialize($modal_array[ 'style_settings' ]);
				foreach($settings_array as $key => $setting){
					$style_settings[$key] = apply_filters( 'smile_render_setting',$setting );
				}
				$modal_style = $style_settings[ 'style' ];
				$global = $style_settings[ 'global' ];

				$style_settings[ 'display' ] = $display;
				$style_settings['custom_class'] .= isset( $style_settings['custom_class']) ? $style_settings['custom_class'].',cp-trigger-'.$style_id : 'cp-trigger-'.$style_id;
				$display = cp_is_style_visible($style_settings);

				// remove back slashes from settings
				$settings = stripslashes_deep( $settings );

				$encode_settings = serialize( $style_settings );
				$settings_encoded = base64_encode( $encode_settings );

				echo '<span class="cp-trigger-shortcode cp-trigger-'.$style_id.' cp-'.$style_id.'">'.do_shortcode( $content ).'</span>';

				if( $display ){
					//	Generate style ID
					$id = $modal_style . '-' . $style_id;

					//	Individual Style Path
					$file_name = '/assets/demos/'. strtolower($modal_style) . '/' . strtolower($modal_style) . '.min.css';
					$url = plugins_url( $file_name , __FILE__ );

					//	Check file exist or not - and append to the head
					echo '<link rel="stylesheet" id="'.$id.'" href="' . $url .'" type="text/css" media="all" />';
					echo do_shortcode('[smile_modal manual="true" style_id = '.$style_id.' style="'.$modal_style.'" settings_encoded="' . $settings_encoded . ' "][/smile_modal]');
					$css = isset( $settings['custom_css'] ) ? urldecode($settings['custom_css']) : '';
					apply_filters('cp_custom_css',$style_id, $css);
				}
				break;
			}
		}
		return ob_get_clean();
	}
	add_shortcode('cp_modal', 'cp_modal_custom');
}
add_filter('widget_text', 'do_shortcode');
