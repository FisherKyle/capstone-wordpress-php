<?php
/* 
* Smile Theme Framework
* @Version: 1.0
*/
// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
/**
 * Framework Root
 */
if ( ! defined( 'SMILE_FRAMEWORK_DIR' ) ) define( 'SMILE_FRAMEWORK_DIR', dirname(__FILE__) );

/**
 * Framework URI
 */
if ( ! defined( 'SMILE_FRAMEWORK_URI' ) ) define( 'SMILE_FRAMEWORK_URI', plugins_url('',__FILE__) );

/* 
* Framework Starts from here. 
*/
if(!class_exists("Smile_Framework")){
	class Smile_Framework{
		public static $options = array();
		public static $modules = array();
		public static $addon_list = array();
		public $fields_dir;
		/*
		* Constructor function that initializes required actions and hooks
		* @Since 1.0
		*/
		function __construct(){
			$this->fields_dir = SMILE_FRAMEWORK_DIR.'/lib/fields/';
			// Load options
			add_action( 'init', array( $this, 'load_framework_functions'));
			add_action('admin_head',array($this,'load_compatible_scripts'));
		}
		
		function load_compatible_scripts(){
			if(isset($_GET['hidemenubar'])){
				wp_register_script('cp-helper-js',plugins_url('assets/js/cp-helper.js',__FILE__));
				wp_enqueue_script('cp-helper-js');
				wp_register_script('smile-customizer-js',plugins_url('assets/js/customizer.js',__FILE__), array('cp-helper-js') );
				wp_enqueue_script('smile-customizer-js');
			}
		}
		
		/*
		* Load and initialize
		* @Since 1.0
		*/
		function load_framework_functions(){
			// load framework mapper class
			require_once('classes/class.framework-mapper.php');
			
			// load style framework loader
			require_once('classes/class.style-framework.php');

			// load style framework loader
			require_once('classes/class.cpImport.php');

			// load required admin fuctions
			require_once('functions/functions.php');
						
			// load default input types from the directory "lib/fields"
			foreach(glob($this->fields_dir."/*/*.php") as $module)
			{
				require_once($module);
			}
		}
		
		/*
		* Retrieve and store data into the static variable $options		
		* @Since 1.0		
		*/
		public static function smile_store_data($class, $name, $settings){
			$result = false;
			if($name !== "" && !empty($settings)){
				$class::$options[$name] = $settings;
				$result = true;
			}
			return $result;
		}
		
		
		/*
		* Retrieve and update stored data into the static variable $options		
		* @Since 1.0		
		*/
		public static function smile_update_data( $class, $name, $settings ){
			$result = false;
			if($name !== "" && !empty($settings)){
				$prev_settings = $class::$options[$name]['options'];
				foreach( $settings as $key => $setting ) {
					array_push( $prev_settings, $setting );
				}
				$class::$options[$name]['options'] = $prev_settings;
				$result = true;
			}
			return $result;
		}
		
		/*
		* Retrieve and update default value in stored data into the static variable $options		
		* @Since 1.0		
		*/
		public static function smile_update_value( $class, $style, $name, $value ){
			$result = false;
			$new_settings = '';
			if($name !== "" ){
				$settings = $class::$options[$style]['options'];
				foreach( $settings as $key => $setting ) {
					$opt_name = $setting['name'];
					if( $opt_name == $name ) {
						$settings[$key]['opts']['value'] = $value;
					}
				}
				$class::$options[$style]['options'] = $settings;
				$result = true;
			}
			return $result;
		}

		/*
		* Retrieve settings array and remove option provided from settings
		* @Since 1.0		
		*/
		public static function smile_remove_setting( $class, $style, $name ){
			$result = false;
			if( !empty( $name ) ){
				$settings = $class::$options[$style]['options'];
				foreach( $settings as $key => $setting ) {
					$opt_name = $setting['name'];
					if( in_array( $opt_name, $name ) ) {
						unset( $settings[ $key ] );
					}
				}
				$class::$options[$style]['options'] = $settings;
				$result = true;
			}
			return $result;
		}

		/*
		* Retrieve and update default value in stored data into the static variable $options		
		* @Since 1.0		
		*/
		public static function smile_update_partial_refresh( $class, $style, $name, $parse_array ){
			$result = false;
			$new_settings = '';
			if($name !== "" ){
				$settings = $class::$options[$style]['options'];
				foreach( $settings as $key => $setting ) {
					$opt_name = $setting['name'];
					if( $opt_name == $name && !empty( $parse_array ) ) {
						if( isset( $parse_array['css_selector'] ) ) {
							$settings[$key]['opts']['css_selector'] = $parse_array['css_selector'];
						}
						if( isset( $parse_array['css_property'] ) ) {
							$settings[$key]['opts']['css_property'] = $parse_array['css_property'];
						}
					}
				}
				$class::$options[$style]['options'] = $settings;
				$result = true;
			}
			return $result;
		}

		/*
		* Add mailer addon to convertplug
		* @Since 1.0		
		*/
		public static function smile_add_mailer( $slug, $setting ){
			$result = false;
			if( $slug != '' ) {
				Smile_Framework::$addon_list[$slug] = $setting;
				$result = true;
			}
			return $result;
		}
	}
}