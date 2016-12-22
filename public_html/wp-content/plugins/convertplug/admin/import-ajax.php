<?php
add_action( 'wp_ajax_cp_import_slide_in', 'cp_import_slide_in' );
add_action( 'wp_ajax_cp_import_info_bar', 'cp_import_info_bar' );
add_action( 'wp_ajax_cp_import_modal', 'cp_import_modal' );

// cp-import-modal
if( !function_exists( "cp_import_slide_in" ) ){
	function cp_import_slide_in(){
		$data = $_POST;
		$file = $data['file'];
		$title = $file['title'];
		$filename = $file['filename'];
		$file = realpath(get_attached_file($file['id']));

		// Get the name of the directory inside the exported zip
		$zip = zip_open( $file );

		if ( is_resource($zip) ) {
			while ( $zip_entry = zip_read( $zip ) ) {
				$title = dirname( zip_entry_name( $zip_entry ) );
			}
			zip_close($zip);
		} else {
			echo __( "Failed to Open. Error Code: " . $zip, "smile" );
			die();
		}

		// Set the path variable for extracting the zip
		$paths = array();
		$paths			= wp_upload_dir();
		$paths['export']  = 'cp_export';
		$paths['tempdir'] = trailingslashit($paths['basedir']).'cp_modal';
		$paths['temp']  	= trailingslashit($paths['basedir']).'cp_modal/'.$title;
		$paths['tempurl'] = trailingslashit($paths['baseurl']).'cp_modal/';
		$paths['basepath'] = $paths['basedir'].'/cp_modal/';
		$folderPath = $paths['basedir'] .'/cp_modal/'.$title;

		// Create the respective directory inside wp-uploads directory
		if( !is_dir( $paths['temp'] ) ){
			$tempdir = smile_backend_create_folder($paths['temp'], false);
		}

		WP_Filesystem();
		$destination_path = $paths['tempdir'];

		// Extract the zip to our newly created directory
		$unzipfile = unzip_file( $file, $destination_path);


		if ( !$unzipfile ) {
	      	die(__( 'Unable to extract the file.', 'smile' ));
	   	}

		// sanitize folder name
	   	$newFolderName = sanitize_file_name( $title );

	   	// grant permission
	   	chmod( $folderPath, 0755);

	   	$newFolderPath =  $paths['basepath'] . $newFolderName;

	   	// rename folder
	    rename( $folderPath, $newFolderPath );

	    // rename settings file
	    rename( $newFolderPath .'/'. $title . '.txt' , $newFolderPath .'/'.$newFolderName .'.txt' );

		// Set the json file file url to get the settings for the style
		$json_file = $paths['tempurl'].$newFolderName.'/'.$newFolderName.'.txt';

		$module = $data['module'];
		$data_option = 'smile_slide_in_styles';
		$variant_option = 'slide_in_variant_tests';

		// Read the text file containing the json formatted settings of style and decode it
		$content = wp_remote_get($json_file);

		$json = $content['body'];
		$obj = json_decode($json,true);
		$import_style = array();
		$new_style_id = $obj['style_id'];
		$cp_module = $obj['module'];

		if( $cp_module !== "slide_in" ){

			print_r(json_encode(
				array(
					'status' 		=> 'error',
					'description' 	=> __( "Seems that the file have uploaded the wrong file. This file can be imported for ". str_replace("_", " ", $cp_module ), "smile" )
				)
			));

			die();
		}

		if( !isset( $obj['style_id'] ) ){
			print_r(json_encode(
				array(
					'status' 		=> 'error',
					'description' 	=> __( "Seems that the file is different from the exported modal zip. Please try with another zip file.", "smile" )
				)
			));
			die();
		}

		$style_settings = (array)$obj['style_settings'];

		if( isset( $style_settings['cp_google_fonts'] ) ) {
			$google_fonts =  explode( ",", $style_settings['cp_google_fonts'] );
			cp_import_google_fonts($google_fonts);
		}

		if( isset($obj['media']['slidein_bg_image']) ) {
			$old_image = $obj['media']['slidein_bg_image'];
			unset($obj['media']['slidein_bg_image']);
			$obj['media']['slide_in_bg_image'] =  $old_image;
		}

		if( isset($obj['media']) ) {
			$media = (array)$obj['media'];
			$media_ids = array();

			if ( isset($media) && is_array($media) ) {
				// Import media if any
				foreach( $media as $option => $value ) {

					$value = str_replace( $title , $newFolderName, $value);

					// $filename should be the path to a file in the upload directory.
					$filename =  $paths['tempdir'].'/'.$value;

					// Check the type of file. We'll use this as the 'post_mime_type'.
					$filetype = wp_check_filetype( basename( $filename ), null );

					// Get the path to the upload directory.
					$wp_upload_dir = wp_upload_dir();

					// Prepare an array of post data for the attachment.
					$attachment = array(
						'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
						'post_mime_type' => $filetype['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					);

					// Insert the attachment.
					$option = ( $option == "close_image" ) ? "close_img" : $option;
					$media_ids[ $option ] = wp_insert_attachment( $attachment, $filename );

					// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
					require_once( ABSPATH . 'wp-admin/includes/image.php' );

					// Generate the metadata for the attachment, and update the database record.
					$attach_data = wp_generate_attachment_metadata( $media_ids[ $option ], $filename );
					wp_update_attachment_metadata( $media_ids[ $option ], $attach_data );

					// Get the attachment id and update the setting for media in style
					if( isset( $style_settings[ $option ] ) ){
						$media_image = $style_settings[ $option ];
						$media_image = str_replace( "%7C", "|", $media_image );
						if (strpos($media_image,'http') !== false) {
							$media_image = explode( '|', $media_image );
							$media_image = $media_image[1];
						} else {
							$media_image = explode("|", $media_image);
							$media_image = $media_image[1];
						}
						$media_image = $media_ids[ $option ]."|".$media_image;
						$style_settings[ $option ] = $media_image;
					}
				}
			}

		}

		$prev_styles = get_option( $data_option );
		$variant_tests = get_option(  $variant_option  );

		$prev_styles = empty( $prev_styles ) ? array() : $prev_styles;
		$update = false;

		foreach( $style_settings as $title => $value ){

			if( $title == 'slidein_bg_image' ) {
				$title = 'slide_in_bg_image';
			}

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


		$import = $obj;
		$import['style_settings'] = serialize( $import_style );

		if(isset($import['variants']) ){
			unset($import['variants']);
		}

		if( !empty( $prev_styles ) ){
			foreach( $prev_styles as $key => $style ) {
				$style_id = $style['style_id'];
				if( $new_style_id == $style_id ) {
					$update = false;
					print_r(json_encode(
						array(
							'status' 		=> 'error',
							'description' 	=> __( "Style Already Exists! Please try importing another style.", "smile" )
						)
					));
					die();
				} else {
					$update = true;
				}
			}
		} else {
			$update = true;
		}

		if( $update ) {
			array_push($prev_styles,$import);
			$status = update_option( $data_option, $prev_styles );

 		/* import variants  */
			if( isset($obj['variants']) ) {
				$variant_tests[$new_style_id] = $obj['variants'];
				$status = update_option(  $variant_option , $variant_tests );
			}

		} else {
			$status = false;
		}

		// Check the status of import and return the object accordingly
		if( $status ) {
			print_r(json_encode(
				array(
					'status' 		=> 'success',
					'description' 	=> ucwords( str_replace("_", " ", $module ) )." ".__( "imported successfully!", "smile" )
				)
			));
		} else {
			print_r(json_encode(
				array(
					'status' 		=> 'error',
					'description' 	=> __( "Something went wrong! Please try again with different file.", "smile" )
				)
			));
		}
		die();
	}
}

// cp-import-style
if( !function_exists( "cp_import_info_bar" ) ){
	function cp_import_info_bar(){
		$data = $_POST;
		$file = $data['file'];
		$title = $file['title'];
		$filename = $file['filename'];
		$file = realpath(get_attached_file($file['id']));

		// Get the name of the directory inside the exported zip
		$zip = zip_open( $file );

		if ( is_resource($zip) ) {
			while ( $zip_entry = zip_read( $zip ) ) {
				$title = dirname( zip_entry_name( $zip_entry ) );
			}
			zip_close($zip);
		} else {
			echo __( "Failed to Open. Error Code: " . $zip, "smile" );
			die();
		}

		// Set the path variable for extracting the zip
		$paths = array();
		$paths			= wp_upload_dir();
		$paths['export']  = 'cp_export';
		$paths['tempdir'] = trailingslashit($paths['basedir']).'cp_modal';
		$paths['temp']  	= trailingslashit($paths['basedir']).'cp_modal/'.$title;
		$paths['tempurl'] = trailingslashit($paths['baseurl']).'cp_modal/';
		$paths['basepath'] = $paths['basedir'].'/cp_modal/';
		$folderPath = $paths['basedir'] .'/cp_modal/'.$title;

		// Create the respective directory inside wp-uploads directory
		if( !is_dir( $paths['temp'] ) ){
			$tempdir = smile_backend_create_folder($paths['temp'], false);
		}

		WP_Filesystem();
		$destination_path = $paths['tempdir'];

		// Extract the zip to our newly created directory
		$unzipfile = unzip_file( $file, $destination_path);


		if ( !$unzipfile ) {
	      	die(__( 'Unable to extract the file.', 'smile' ));
	   	}

		// sanitize folder name
	   	$newFolderName = sanitize_file_name( $title );

	   	// grant permission
	   	chmod( $folderPath, 0755 );

	   	$newFolderPath =  $paths['basepath'] . $newFolderName;

	   	// rename folder
	    rename( $folderPath, $newFolderPath );

	    // rename settings file
	    rename( $newFolderPath .'/'. $title . '.txt' , $newFolderPath .'/'.$newFolderName .'.txt' );

		// Set the json file file url to get the settings for the style
		$json_file = $paths['tempurl'].$newFolderName.'/'.$newFolderName.'.txt';

		$module = $data['module'];
		$data_option = 'smile_info_bar_styles';
		$variant_option = 'info_bar_variant_tests';

		// Read the text file containing the json formatted settings of style and decode it
		$content = wp_remote_get($json_file);

		$json = $content['body'];

		$obj = json_decode($json,true);
		$import_style = array();
		$new_style_id = $obj['style_id'];

		$cp_module = $obj['module'];

		if( $cp_module !== "info_bar" ){

			print_r(json_encode(
				array(
					'status' 		=> 'error',
					'description' 	=> __( "Seems that the file have uploaded the wrong file. This file can be imported for ". str_replace("_", " ", $cp_module ), "smile" )
				)
			));

			die();
		}

		if( !isset( $obj['style_id'] ) ){
			print_r(json_encode(
				array(
					'status' 		=> 'error',
					'description' 	=> __( "Seems that the file is different from the exported info bar zip. Please try with another zip file.", "smile" )
				)
			));
			die();
		}

		$style_settings = (array)$obj['style_settings'];

		if( isset($obj['media']['infobar_image']) ) {
			$old_ib_image = $obj['media']['infobar_image'];
			unset($obj['media']['infobar_image']);
			$obj['media']['info_bar_image'] =  $old_ib_image;
		}

		if( isset($obj['media']['infobar_bg_image']) ) {
			$old_ib_bg_image = $obj['media']['infobar_bg_image'];
			unset($obj['media']['infobar_bg_image']);
			$obj['media']['info_bar_bg_image'] =  $old_ib_bg_image;
		}

		if( isset($obj['media']) ) {
			$media = (array)$obj['media'];
		}

		if( isset( $style_settings['cp_google_fonts'] ) ) {
			$google_fonts =  explode( ",", $style_settings['cp_google_fonts'] );
			cp_import_google_fonts($google_fonts);
		}

		$media_ids = array();


		if ( isset($media) && is_array($media) ) {

			// Import media if any
			foreach( $media as $option => $value ) {

				$value = str_replace( $title , $newFolderName, $value);

				// $filename should be the path to a file in the upload directory.
				$filename =  $paths['tempdir'].'/'.$value;

				// Check the type of file. We'll use this as the 'post_mime_type'.
				$filetype = wp_check_filetype( basename( $filename ), null );

				// Get the path to the upload directory.
				$wp_upload_dir = wp_upload_dir();

				// Prepare an array of post data for the attachment.
				$attachment = array(
					'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
					'post_mime_type' => $filetype['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				// Insert the attachment.
				$option = ( $option == "close_image" ) ? "close_img" : $option;
				$media_ids[ $option ] = wp_insert_attachment( $attachment, $filename );

				// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
				require_once( ABSPATH . 'wp-admin/includes/image.php' );

				// Generate the metadata for the attachment, and update the database record.
				$attach_data = wp_generate_attachment_metadata( $media_ids[ $option ], $filename );
				wp_update_attachment_metadata( $media_ids[ $option ], $attach_data );

				// Get the attachment id and update the setting for media in style
				if( isset( $style_settings[ $option ] ) ){
					$media_image = $style_settings[ $option ];
					$media_image = str_replace( "%7C", "|", $media_image );
					if (strpos($media_image,'http') !== false) {
						$media_image = explode( '|', $media_image );
						$media_image = $media_image[1];
					} else {
						$media_image = explode("|", $media_image);
						$media_image = $media_image[1];
					}
					$media_image = $media_ids[ $option ]."|".$media_image;
					$style_settings[ $option ] = $media_image;
				}
			}
		}

		$prev_styles = get_option( $data_option );
		$variant_tests = get_option(  $variant_option  );

		$prev_styles = empty( $prev_styles ) ? array() : $prev_styles;
		$update = false;

		foreach( $style_settings as $title => $value ){

			if( $title == 'infobar_bg_image' ) {
				$title = 'info_bar_bg_image';
			}

			if( $title == 'infobar_image' ) {
				$title = 'info_bar_image';
			}

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
		$import = $obj;
		$import['style_settings'] = serialize( $import_style );

		if(isset($import['variants']) ){
			unset($import['variants']);
		}

		if( !empty( $prev_styles ) ){
			foreach( $prev_styles as $key => $style ) {
				$style_id = $style['style_id'];
				if( $new_style_id == $style_id ) {
					$update = false;
					print_r(json_encode(
						array(
							'status' 		=> 'error',
							'description' 	=> __( "Style Already Exists! Please try importing another style.", "smile" )
						)
					));
					die();
				} else {
					$update = true;
				}
			}
		} else {
			$update = true;
		}

		if( $update ) {
			array_push($prev_styles,$import);
			$status = update_option( $data_option, $prev_styles );

 		/* import variants  */
			if( isset($obj['variants']) ) {
				$variant_tests[$new_style_id] = $obj['variants'];
				$status = update_option(  $variant_option , $variant_tests );
			}

		} else {
			$status = false;
		}

		// Check the status of import and return the object accordingly
		if( $status ) {
			print_r(json_encode(
				array(
					'status' 		=> 'success',
					'description' 	=> ucwords( str_replace("_", " ", $module ) )." ".__( "imported successfully!", "smile" )
				)
			));
		} else {
			print_r(json_encode(
				array(
					'status' 		=> 'error',
					'description' 	=> __( "Something went wrong! Please try again with different file.", "smile" )
				)
			));
		}
		die();
	}
}

// cp-import-modal
if( !function_exists( "cp_import_modal" ) ){
	function cp_import_modal(){
		$data = $_POST;
		$file = $data['file'];

		$title = $file['title'];
		$filename = $file['filename'];
		$file = realpath(get_attached_file($file['id']));

		// Get the name of the directory inside the exported zip
		$zip = zip_open( $file );

		// valid zip file
		if ( is_resource($zip) ) {
			while ( $zip_entry = zip_read( $zip ) ) {
				$title = dirname( zip_entry_name( $zip_entry ) );
			}
			zip_close($zip);
		} else {
			echo __( "Failed to Open. Error Code: " . $zip, "smile" );
			die();
		}

		// Set the path variable for extracting the zip
		$paths = array();
		$paths			  = wp_upload_dir();
		$paths['export']  = 'cp_export';
		$paths['tempdir'] = trailingslashit($paths['basedir']).'cp_modal';
		$paths['temp']    = trailingslashit($paths['basedir']).'cp_modal/'.$title;
		$paths['tempurl'] = trailingslashit($paths['baseurl']).'cp_modal/';
		$paths['basepath'] = $paths['basedir'].'/cp_modal/';
		$folderPath = $paths['basedir'] .'/cp_modal/'.$title;

		// Create the respective directory inside wp-uploads directory
		if( !is_dir( $paths['temp'] ) ) {
			$tempdir = smile_backend_create_folder($paths['temp'], false);
		}

		WP_Filesystem();
		$destination_path = $paths['tempdir'];

		// Extract the zip to our newly created directory
		$unzipfile = unzip_file( $file, $destination_path);

		if ( !$unzipfile ) {
	      	die(__( 'Unable to extract the file.', 'smile' ));
	   	}

	   	// sanitize folder name
	   	$newFolderName = sanitize_file_name( $title );

	   	// grant permission
	   	chmod( $folderPath, 0755 );

	   	$newFolderPath =  $paths['basepath'] . $newFolderName;

	   	// rename folder
	    rename( $folderPath, $newFolderPath );

	    // rename settings file
	    rename( $newFolderPath .'/'. $title . '.txt' , $newFolderPath .'/'.$newFolderName .'.txt' );

		// Set the json file file url to get the settings for the style
		$json_file = $paths['tempurl'].$newFolderName.'/'.$newFolderName.'.txt';

		$module = $data['module'];
		$data_option = 'smile_modal_styles';
		$variant_option = 'modal_variant_tests';

		// Read the text file containing the json formatted settings of style and decode it
		$content = wp_remote_get( $json_file );

		$json = $content['body'];

		$obj = json_decode($json,true);
		$import_style = array();
		$new_style_id = $obj['style_id'];
		$cp_module = $obj['module'];

		if( $cp_module !== "modal" ){

			print_r(json_encode(
				array(
					'status' 		=> 'error',
					'description' 	=> __( "Seems that the file have uploaded the wrong file. This file can be imported for ". str_replace("_", " ", $cp_module ), "smile" )
				)
			));

			die();
		}

		if( !isset( $obj['style_id'] ) ){
			print_r(json_encode(
				array(
					'status' 		=> 'error',
					'description' 	=> __( "Seems that the file is different from the exported modal zip. Please try with another zip file.", "smile" )
				)
			));
			die();
		}
		$style_settings = (array)$obj['style_settings'];

		if( isset($obj['media']) ) {
			$media = (array)$obj['media'];
		}

		if( isset( $style_settings['cp_google_fonts'] ) ) {
			$google_fonts =  explode( ",", $style_settings['cp_google_fonts'] );
			cp_import_google_fonts($google_fonts);
		}

		$media_ids = array();

		if ( isset($media) && is_array($media) ) {
			// Import media if any
			foreach( $media as $option => $value ) {

				$value = str_replace( $title , $newFolderName, $value);

				// $filename should be the path to a file in the upload directory.
				$filename =  $paths['tempdir'].'/'.$value;

				// Check the type of file. We'll use this as the 'post_mime_type'.
				$filetype = wp_check_filetype( basename( $filename ), null );

				// Get the path to the upload directory.
				$wp_upload_dir = wp_upload_dir();

				// Prepare an array of post data for the attachment.
				$attachment = array(
					'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
					'post_mime_type' => $filetype['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				// Insert the attachment.
				$option = ( $option == "close_image" ) ? "close_img" : $option;
				$media_ids[ $option ] = wp_insert_attachment( $attachment, $filename );

				// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
				require_once( ABSPATH . 'wp-admin/includes/image.php' );

				// Generate the metadata for the attachment, and update the database record.
				$attach_data = wp_generate_attachment_metadata( $media_ids[ $option ], $filename );
				wp_update_attachment_metadata( $media_ids[ $option ], $attach_data );

				// Get the attachment id and update the setting for media in style
				if( isset( $style_settings[ $option ] ) ){
					$media_image = $style_settings[ $option ];
					$media_image = str_replace( "%7C", "|", $media_image );
					if (strpos($media_image,'http') !== false) {
						$media_image = explode( '|', $media_image );
						$media_image = $media_image[1];
					} else {
						$media_image = explode("|", $media_image);
						$media_image = $media_image[1];
					}
					$media_image = $media_ids[ $option ]."|".$media_image;
					$style_settings[ $option ] = $media_image;
				}
			}
		}

		$prev_styles = get_option( $data_option );
		$variant_tests = get_option(  $variant_option  );

		$prev_styles = empty( $prev_styles ) ? array() : $prev_styles;
		$update = false;

		foreach( $style_settings as $title => $value ){
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
		$import = $obj;
		$import['style_settings'] = serialize( $import_style );

		if(isset($import['variants']) ){
			unset($import['variants']);
		}

		if( !empty( $prev_styles ) ){
			foreach( $prev_styles as $key => $style ) {
				$style_id = $style['style_id'];
				if( $new_style_id == $style_id ) {
					$update = false;
					print_r(json_encode(
						array(
							'status' 		=> 'error',
							'description' 	=> __( "Style Already Exists! Please try importing another style.", "smile" )
						)
					));
					die();
				} else {
					$update = true;
				}
			}
		} else {
			$update = true;
		}

		if( $update ) {
			array_push($prev_styles,$import);
			$status = update_option( $data_option, $prev_styles );

 		/* import variants  */
			if( isset($obj['variants']) ) {
				$variant_tests[$new_style_id] = $obj['variants'];
				$status = update_option(  $variant_option , $variant_tests );
			}

		} else {
			$status = false;
		}

		// Check the status of import and return the object accordingly
		if( $status ) {
			print_r(json_encode(
				array(
					'status' 		=> 'success',
					'description' 	=> ucwords( str_replace("_", " ", $module ) )." ".__( "imported successfully!", "smile" )
				)
			));
		} else {
			print_r(json_encode(
				array(
					'status' 		=> 'error',
					'description' 	=> __( "Something went wrong! Please try again with different file.", "smile" )
				)
			));
		}
		die();
	}
}

/*
* Add Google fonts while importing
* since 2.2.0
*/
if( !function_exists( 'cp_import_google_fonts' ) ) {
	function cp_import_google_fonts($google_fonts) {

		$basicFonts = array (
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
					"Verdana",
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

		$saved_fonts = get_option('ultimate_selected_google_fonts');

		$gfonts = array();

		if( is_array($saved_fonts) ) {
			foreach ( $saved_fonts as $font ) {
				if( isset($font['font_family']) ) {
	 				$gfonts[] = $font['font_family'];
	 			}
			}
		}

		$fonts_to_exclude = array_merge( $basicFonts, $default_google_fonts, $gfonts );

		foreach ( $google_fonts as $font ) {
			// make sure font is not already present in array
			if( !in_array( $font, $fonts_to_exclude ) ) {

					$fontArr = array (
						'font_family' => $font,
						'font_name'   => $font,
						'variants'    => NUll,
						'subsets'     => NUll
					);

	             	$saved_fonts[]  = $fontArr;
			}
		}

		// update google font option
		update_option( 'ultimate_selected_google_fonts', $saved_fonts );
	}
}
