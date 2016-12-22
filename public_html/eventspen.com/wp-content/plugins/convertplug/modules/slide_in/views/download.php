<?php
$wp_content = explode( "wp-content" , __FILE__ );
require_once( $wp_content[0] . "wp-load.php" );
require_once( $wp_content[0] . '/wp-admin/admin.php' );

$path = plugin_dir_path( __FILE__ );
$prev_styles = get_option('smile_slide_in_styles');
$variant_tests = get_option('slide_in_variant_tests');

$data_style = isset( $_GET['style_id'] ) ? $_GET['style_id'] : '';
$data_style_name = '';
if(isset( $_GET['style_name'] )){
	$data_style_name = strtolower( stripcslashes( $_GET['style_name'] )) ;
	$data_style_name = str_replace(' ', '_', $data_style_name);
}
$data_style_name = $data_style_name .'_'. $data_style;

if( $data_style !== "" ) {
	if(is_array($prev_styles) && !empty($prev_styles)){
		foreach($prev_styles as $key => $style){

			$hasVariants = false;
			$style_name = $style['style_name'];
			$style_id = $style['style_id'];

			if( $data_style == $style_id ) {

			    if( $variant_tests ) {
			        if ( array_key_exists($data_style,$variant_tests) && !empty($variant_tests[$data_style]) ) {
			            $hasVariants = true;
			        }
			    }

				$style_settings = unserialize($style['style_settings']);
				$exp_settings = array();
				foreach( $style_settings as $title => $value ){
				    if( !is_array( $value ) ){
				     	$value = urldecode($value);
						$exp_settings[$title] = htmlentities(stripslashes(utf8_encode($value)), ENT_QUOTES, "UTF-8" );
					} else {
						$val = array();
						foreach( $value as $ex_title => $ex_val ) {
						    $val[$ex_title] = $ex_val;
						}
						$exp_settings[$title] =str_replace('"','&quot;',$val);
					}
				}
				$export = $style;
				$export['style_settings'] = $exp_settings;

				$close_image = isset( $style_settings['close_img'] ) ? $style_settings['close_img'] : '' ;
				$bg_image = isset( $style_settings['slide_in_bg_image'] ) ? $style_settings['slide_in_bg_image'] : '';
				$slidein_image = isset( $style_settings['slidein_image'] ) ? $style_settings['slidein_image'] : '';

				if( $hasVariants ) {
					foreach($variant_tests[$data_style] as $variant) {
						$export['variants'][] = $variant;
					}
				}
			}
		}
	}

	$dir = 'slide_in_'.$data_style_name;
	if( !is_dir( $dir ) ) {
		mkdir( $dir, 0777 );
	}

	// Get images attached to the style through settings, copy them in export directory and store them in media array
	$media = array();

	if( $close_image !== "" )
	{
		if ( ( isset( $style_settings['close_si_image_src'] ) && $style_settings['close_si_image_src'] == 'upload_img'  )
			|| !isset( $style_settings['close_si_image_src'] ) )  {

			$close_image = str_replace( "%7C", "|", $close_image );
			if (strpos($close_image,'http') !== false) {
				$close_image = explode( '|', $close_image );
				$close_image = $close_image[0];
				$close_image = urldecode( $close_image );
			} else {
				$close_image = explode("|", $close_image);
				$close_image = wp_get_attachment_image_src($close_image[0],$close_image[1]);
				$close_image = $close_image[0];
			}

			$close_image_name = basename( $close_image );
			copy( $close_image, $dir.'/'.$close_image_name );

			$media['close_image'] = $dir.'/'.$close_image_name;
		}
	}

	if( $bg_image !== "" )
	{
		if ( ( isset( $style_settings['slide_in_bg_image_src'] ) && $style_settings['slide_in_bg_image_src'] == 'upload_img'  )
			|| !isset( $style_settings['slide_in_bg_image_src'] ) )  {

			$bg_image = str_replace( "%7C", "|", $bg_image );
			if (strpos($bg_image,'http') !== false) {
				$bg_image = explode( '|', $bg_image );
				$bg_image = $bg_image[0];
				$bg_image = urldecode( $bg_image );
			} else {
				$bg_image = explode("|", $bg_image);
				$bg_image = wp_get_attachment_image_src($bg_image[0],$bg_image[1]);
				$bg_image = $bg_image[0];
			}

			$bg_image_name = basename( $bg_image );
			copy( $bg_image, $dir.'/'.$bg_image_name );

			$media['slide_in_bg_image'] = $dir.'/'.$bg_image_name;
		}

	}

	if( $slidein_image !== "" )
	{
		if ( ( isset( $style_settings['slidein_image_src'] ) && $style_settings['slidein_image_src'] == 'upload_img'  )
			|| !isset( $style_settings['slidein_image_src'] ) )  {

			$slidein_image = str_replace( "%7C", "|", $slidein_image );
			if (strpos($slidein_image,'http') !== false) {
				$slidein_image = explode( '|', $slidein_image );
				$slidein_image = $slidein_image[0];
				$slidein_image = urldecode( $slidein_image );
			} else {
				$slidein_image = explode("|", $slidein_image);
				$slidein_image = wp_get_attachment_image_src($slidein_image[0],$slidein_image[1]);
				$slidein_image = $slidein_image[0];
			}

			$slidein_image_name = basename( $slidein_image );
			copy( $slidein_image, $dir.'/'.$slidein_image_name );

			$media['slidein_image'] = $dir.'/'.$slidein_image_name;
		}

	}

	if( !empty( $media ) ){
		$export['media'] = $media;
	}

	$export['module'] = 'slide_in';

	$export_data = json_encode( $export );

	$content = $export_data;

	$file_name = $path.'/'.$dir.'/slide_in_'.$data_style_name.'.txt';
	$file_url = plugins_url($dir.'/slide_in_'.$data_style_name.'.txt', __FILE__ );
	$handle = fopen($file_name, "w");
	fwrite($handle, $content);
	fclose($handle);

	$files = glob( "{$dir}/*" );
	$export_file = $dir.'.zip';

	$result = smile_create_export_zip( $files, $export_file , true );

	header('Pragma: public'); 	// required
	header('Expires: 0');		// no cache
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime( $export_file ) ).' GMT');
	header('Cache-Control: private',false);
	header('Content-Type: application/zip');
	header('Content-Disposition: attachment; filename="'.basename( $export_file ).'"');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: '.filesize( $export_file ) );	// provide file size
	header('Connection: close');
	readfile( $export_file );

	// Remove exported directory and its content

	foreach( glob( "{$dir}/*" ) as $file)
    {
		unlink( $file );
    }
	unlink( $export_file );
	rmdir( $dir );
	exit();
} else {
	echo '<script type="text/javascript">window.close();</script>';
}


/* creates a compressed zip file */
function smile_create_export_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if ( file_exists( $destination ) ) {
		 	$zipCreate =  $zip->open($destination, ZipArchive::OVERWRITE);
		} else {
			$zipCreate = $zip->open($destination, ZipArchive::CREATE);
		}

		if ( TRUE !== $zipCreate ) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}

		//close the zip -- done!
		$zip->close();

		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}
