<?php
/**
 * Update preview page data before customizing the element
 *
 * @request $_POST with Options array to update data
 * @since 1.0.0
 * @return void
 */
function framework_update_preview_data(){
	$preview_page = get_option('smile-preview-page');
	if(isset($_POST['demo_id'])){
		$demo_id = $_POST['demo_id'];
		$class = $_POST['cls'];

		$module = $_POST['module'];
		require_once( CP_BASE_DIR.'/modules/'.$module.'/functions/functions.options.php' );

		$demo_html = $customizer_js = '';
		$settings = $class::$options;
		foreach( $settings as $style => $options ){
			if( $style == $demo_id ){
				$demo_html = $options['demo_url'];
				$demo_dir = $options['demo_dir'];
				$customizer_js = $options['customizer_js'];
			}
		}

		$handle = fopen($demo_dir, "r");
		$post_content = fread($handle, filesize($demo_dir));

		print_r($post_content);
		die();

	} else {
		echo 'Not Ok';
	}
	die();
}
/**
 * Save options to the database after processing them
 *
 * @param $data Options array to save
 * @since 1.0.0
 * @uses update_option()
 * @return void
 */

function framework_update_options($data) {
    if (empty($data))
        return;
	if ($key != null) { // Update one specific value
		set_theme_mod($key, $data);
	} else { // Update all values in $data
		foreach ( $data as $k=>$v ) {
			if (!isset($smof_data[$k]) || $smof_data[$k] != $v) { // Only write to the DB when we need to
				set_theme_mod($k, $v);
			}
	  	}
	}
	die();
}

if(!function_exists('smile_framework_create_dependency')){
	function smile_framework_create_dependency($name,$array){
		if(is_array($array)){
			$dependency = '';
			$element = $array['name'];
			$operator = $array['operator'];
			$value = $array['value'];
			$type = isset( $array['type'] ) ? $array['type'] : '';

			if( $type == "media" ){
				$uid = $_SESSION[$element];
				$element = $element."_".$uid;
			}

			$dependency = 'data-name="'.$element.'" data-element="'.$name.'" data-operator="'.$operator.'" data-value="'.$value.'"';

			return $dependency;
		} else {
			return false;
		}
	}
}

if(!function_exists('smile_framework_get_styles')){
	function smile_framework_get_styles($option){
		$prev_styles = get_option($option);
		$styles = array();
		if(is_array($prev_styles) && !empty($prev_styles)){
			foreach($prev_styles as $key => $style){
				$style_id = isset($style['style_id']) ? $style['style_id'] : '';
				$style_name = isset($style['style_name']) ? $style['style_name'] : '';
				$styles[$style_id] = $style_name;
			}
		}
		return $styles;
	}
}

add_filter('smile_render_setting','smile_render_setting',1);
function smile_render_setting($setting){
	if( !is_array( $setting ) ) {
		return urldecode($setting);
	} else {
		return $setting;
	}
}

if( !function_exists( "cp_import_upload_prefilter" ) ){
	add_filter( 'wp_handle_upload_prefilter', 'cp_import_upload_prefilter' );
	function cp_import_upload_prefilter( $file )
	{
		$page = isset( $_POST['admin_page'] ) ? $_POST['admin_page'] : '';

		if( isset( $page ) && $page == "import" ) {

			$ext = pathinfo( $file['name'], PATHINFO_EXTENSION );

			if ( $ext !== "zip" ) {
				$file['error'] = "The uploaded ". $ext ." file is not supported. Please upload the exported text file. e.g. .zip";
			}
		}

		return $file;
	}
}


/*
* creates a folder for the theme framework
*/
if(!function_exists('smile_backend_create_folder'))
{
	function smile_backend_create_folder(&$folder, $addindex = true)
	{
		if(is_dir($folder) && $addindex == false)
			return true;
		$created = wp_mkdir_p( trailingslashit( $folder ) );
		@chmod( $folder, 0777 );
		if($addindex == false) return $created;
		$index_file = trailingslashit( $folder ) . 'index.php';
		if ( file_exists( $index_file ) )
			return $created;
		$handle = @fopen( $index_file, 'w' );
		if ($handle)
		{
			fwrite( $handle, "<?php\r\necho 'Sorry, browsing the directory is not allowed!';\r\n?>
" );
			fclose( $handle );
		}
		return $created;
	}
}