<?php
/**
* Class to handle input types registration, activation and callbacks
* @Since 1.0
*/
if(!class_exists("Smile_Framework_Mapper")){
	class Smile_Framework_Mapper{
		/**
		* @var array - store custom input types
		*/
		protected static $params = array();

		/**
		* function to add new input field into $params array
		*
		* @Since 1.0
		* @static
		* @param $type					- input type name
		* @param $input_field_callback	- callback function for the input field
		* @return bool
		*/
		public static function addInputType( $type, $input_field_callback) {

			$result = false;
			if ( ! empty( $type ) && ! empty( $input_field_callback ) ) {
				self::$params[$type] = array(
					'callback' => $input_field_callback
				);
				$result = true;
			}
			return $result;
		}

		/**
		 * Calls hook for attribute type
		 *
		 * @Since 1.0
		 * @static
		 * @param $type           		- input type name
		 * @param $input_type_settings	- input type settings from shortcode
		 * @param $input_value    		- input type value
		 * @return mixed|string 		- returns html which will be render in hook
		 */
		public static function renderInputType( $name, $type, $input_type_settings, $input_value, $default_value = null ) {
			if ( isset( self::$params[$type]['callback'] ) ) {
				return call_user_func( self::$params[$type]['callback'], $name, $input_type_settings, $input_value, $default_value );
			}
			return '';
		}
	}// end class
} // end class check

/**
* Helper function to register options and their respective settings
*
* @param $name - option name to be stored and retrived
* @param $settings - extra settings for option
* @Since 1.0
*/
function smile_framework_add_options($class, $name, $settings){
	Smile_Framework::smile_store_data($class, $name, $settings);
}

/**
 * Helper function to register new input type hook.
 *
 * @param $type                	- input type name
 * @param $input_field_callback - hook, will be called when framework interface is loaded
 * @return bool
 * @Since 1.0
 */
function smile_add_input_type( $type, $input_field_callback ) {
	return Smile_Framework_Mapper::addInputType( $type, $input_field_callback );
}

/**
 * Call hook for input type html.
 *
 * @param $type           		- input type name
 * @param $input_type_settings	- input type settings from mapper
 * @param $input_value    		- input type value
 * @return mixed|string 		- returns html which will be render in hook
 * @Since 1.0
 */
function do_input_type_settings_field( $name, $type, $input_type_settings, $input_value, $default_value = null ) {
	return Smile_Framework_Mapper::renderInputType( $name, $type, $input_type_settings, $input_value, $default_value );
}

/**
 * Call hook for update existing styles options
 *
 * @param $class           		- module class name
 * @param $name					- style name to update options
 * @param $settings    			- options array to be updated into the style
 * @Since 1.0
 */
function smile_update_options($class, $name, $options){
	Smile_Framework::smile_update_data($class, $name, $options);
}

/**
 * Call hook for update default value for a setting
 *
 * @param $class           		- module class name
 * @param $style				- style name, where the option is located
 * @param $name    				- setting name to update default option
 * @param $value    			- new default value to be set for the $name setting
 * @Since 1.0
 */
function smile_update_default( $class, $style, $name, $value ){
	Smile_Framework::smile_update_value( $class, $style, $name, $value );
}

/**
 * Call hook for removing option from settings
 *
 * @param $class           		- module class name
 * @param $style				- style name, where the option is located
 * @param $name    				- array of setting names to be removed
 * @Since 1.0
 */
function smile_remove_option( $class, $style, $name ){
	Smile_Framework::smile_remove_setting( $class, $style, $name );
}

/**
 * Call hook for update partial value for a setting
 *
 * @param $class           		- module class name
 * @param $style				- style name, where the option is located
 * @param $name    				- setting name to update default option
 * @param $parse_array    		- new parse array to be set for the $name setting
 * @Since 1.0
 */
function smile_update_partial( $class, $style, $name, $parse_array ){
	Smile_Framework::smile_update_partial_refresh( $class, $style, $name, $parse_array );
}


/**
 * Call hook for adding mailer addon
 *
 * @param $slug           		- mailer slug
 * @param $setting				- mailer other settings
 * @Since 1.0
 */
function cp_register_addon( $slug, $setting ){
	Smile_Framework::smile_add_mailer( $slug, $setting );
}