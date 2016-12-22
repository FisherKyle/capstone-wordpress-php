<?php

/**
 * Class = cpImport
 */
if ( ! class_exists( 'cpImport' ) ) {

	class cpImport {

		/**
		 * Absolute path to Presets directory
		 */
		private $CP_PRESETS_DIR = '';

		/**
		 * @var $module - name of the module for which the presets are to be imported.
		 */
		private $module = '';

		public $presets_list;

		/**
		 * Constructor
		 */
		function __construct( $module = '', $preset = '' ) {

			// module to be imported
			$this->module = strtolower( $module );
			$this->preset = $preset;

			$this->cp_presets_list( $this->module, $this->preset );

			$this->cp_import_preset_frontend( $this->module, $this->preset );

		}

		/**
		 * CP Presets Importer
		 *
		 * @param String $module - modal | info bar |slide in
		 */
		public function cp_import_preset_frontend( $module, $preset ) {

			$this->CP_PRESETS_DIR = CP_BASE_DIR . 'modules/' . $module . '/presets/';

			$option = 'cp_'.$module.'_preset_templates';

			$this->presets_list = get_option( $option );

			foreach ( $this->presets_list as $current_slug => $name ) {

				if( $preset !== '' && $current_slug != $preset ) {
					continue;
				}

				$preset_atts = file_get_contents( $this->CP_PRESETS_DIR . $current_slug . '.txt' );

				if ( $preset_atts == null ) {
					wp_send_json_error();
				} else {
					$preset_atts = json_decode( $preset_atts, true );
				}

				// Generate list of images to be downloaded
				$images = $this->cp_list_images( $preset_atts, $current_slug );
				$imagePresent = 0;

				foreach ( $images as $img_url => $image_atts ) {
					if ( in_array( $current_slug, $images[ $img_url ]['preset_slug'] ) ) {
						$imagePresent++;
					}
				}

				if ( $imagePresent > 0 ) {
					$this->cp_import_preset( $images, $preset_atts, $current_slug );
				} else {
					update_option( 'cp_' . $module . '_' . $current_slug, $preset_atts );
				}

			}

			$result = array(
				"success" => true
			);

			echo json_encode( $result );
			die();

		}

		function cp_import_preset( $images, $preset_atts, $current_slug ) {

			if ( ! empty( $images ) ) {

				ini_set( 'max_execution_time', 0 );

				foreach ( $images as $img_url => $image_atts ) {

					if ( ! isset( $image_atts['id'] ) || get_post_status( $image_atts['id'] ) === false ) {
						$img_id                   = $this->download_image( $img_url );
						$images[ $img_url ]['id'] = $img_id;
					}

					$id   = $images[ $img_url ]['id'];
					$attr = $images[ $img_url ]['attr_name'];

					if ( isset( $images[ $img_url ]['preset_slug'] ) ) {
						if ( in_array( $current_slug, $images[ $img_url ]['preset_slug'] ) ) {
							$this->cp_create_preset( $preset_atts, $current_slug, $id, $attr );
						}
					}

				}

				update_option( 'cp_import_images', $images );
			}

		}


		function cp_create_preset( $preset_atts, $current_slug, $id, $attr ) {

			$cp_preset = get_option( 'cp_' . $this->module . '_' . $current_slug, array() );

			if ( empty( $cp_preset ) ) {
			 	$cp_preset = $preset_atts;
			}

			$url = wp_get_attachment_image_url( $id, 'full' );
			$url = preg_replace( "(^https?:)", "", $url );

			$cp_preset['style_settings'][ $attr ] = $url;

			update_option( 'cp_' . $this->module . '_' . $current_slug, $cp_preset );

		}


		/**
		 * Populates array $this->import_images with the list of URLs of images to be imported
		 *
		 * @param Array Style atts of a preset.
		 */
		function cp_list_images( $preset_atts, $current_slug ) {

			$image_list = get_option( 'cp_import_images', array() );

			// _bg_image_custom_url
			if ( isset( $preset_atts['style_settings'][ $this->module . '_bg_image_custom_url' ] ) && $preset_atts['style_settings'][ $this->module . '_bg_image_custom_url' ] !== '' ) {

				if ( ! isset( $image_list[ $preset_atts['style_settings'][ $this->module . '_bg_image_custom_url' ] ]['id'] ) ) {

					$image_list[ $preset_atts['style_settings'][ $this->module . '_bg_image_custom_url' ] ] = array(
						'attr_name' => $this->module . '_bg_image_custom_url',
						'preset_slug' => array( strtolower( $current_slug ) )
					);

				} else {

					$slug = $image_list[ $preset_atts['style_settings'][ $this->module . '_bg_image_custom_url' ] ]['preset_slug'];
					$ID = $image_list[ $preset_atts['style_settings'][ $this->module . '_bg_image_custom_url' ] ]['id'];

					$slug[] = strtolower( $current_slug );

					$image_list[ $preset_atts['style_settings'][ $this->module . '_bg_image_custom_url' ] ] = array(
						'id'          => $ID,
						'attr_name'   => $this->module . '_bg_image_custom_url',
						'preset_slug' => array_unique($slug)
					);

				}

			}

			// _img_custom_url
			if ( isset( $preset_atts['style_settings'][ $this->module . '_img_custom_url' ] ) && $preset_atts['style_settings'][ $this->module . '_img_custom_url' ] !== '' ) {

				if ( ! isset( $image_list[ $preset_atts['style_settings'][ $this->module . '_img_custom_url' ] ]['id'] ) ) {

					$image_list[ $preset_atts['style_settings'][ $this->module . '_img_custom_url' ] ] = array(
						'attr_name' => $this->module . '_img_custom_url',
						'preset_slug' => array( strtolower( $current_slug ) )
					);

				} else {

					$slug = $image_list[ $preset_atts['style_settings'][ $this->module . '_img_custom_url' ] ]['preset_slug'];
					$ID = $image_list[ $preset_atts['style_settings'][ $this->module . '_img_custom_url' ] ]['id'];

					$slug[] = strtolower( $current_slug );

					$image_list[ $preset_atts['style_settings'][ $this->module . '_img_custom_url' ] ] = array(
						'id'    => $ID,
						'attr_name' => $this->module . '_img_custom_url',
						'preset_slug' => array_unique($slug)
					);

				}

			}

			// close_img_custom_url
			if ( isset( $preset_atts['style_settings'][ $this->module .'_close_img_custom_url'] ) && $preset_atts['style_settings'][ $this->module .'_close_img_custom_url'] !== '' ) {

				if ( ! isset( $image_list[ $preset_atts['style_settings'][ $this->module .'_close_img_custom_url'] ]['id'] ) ) {

					$image_list[ $preset_atts['style_settings'][ $this->module .'_close_img_custom_url'] ] = array(
						'attr_name' => $this->module .'_close_img_custom_url',
						'preset_slug' => array( strtolower( $current_slug ) )
					);

				} else {

					$slug = $image_list[ $preset_atts['style_settings'][ $this->module . '_close_img_custom_url' ] ]['preset_slug'];
					$ID = $image_list[ $preset_atts['style_settings'][ $this->module . '_close_img_custom_url' ] ]['id'];

					$slug[] = strtolower( $current_slug );

					$image_list[ $preset_atts['style_settings'][ $this->module . '_close_img_custom_url' ] ] = array(
						'id'          => $ID,
						'attr_name'   => $this->module . '_close_img_custom_url',
						'preset_slug' => array_unique($slug)
					);

				}

			}

			return $image_list;

		}

		function download_image( $image_path ) {

			require_once( ABSPATH . 'wp-admin/includes/image.php' );

			$uploaded_file  = wp_upload_bits( basename( $image_path ), null, file_get_contents( $image_path ) );
			$wp_upload_dir  = wp_upload_dir();
			$file_path      = $wp_upload_dir['basedir'] . str_replace( $wp_upload_dir['baseurl'], '', $uploaded_file['url'] );
			$parent_post_id = 0;
			$filetype       = wp_check_filetype( basename( $file_path ), null );
			$file_data      = array(
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $file_path ),
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_path ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			$file_id       = wp_insert_attachment( $file_data, $file_path, $parent_post_id );
			$file_metadata = wp_generate_attachment_metadata( $file_id, $file_path );
			wp_update_attachment_metadata( $file_id, $file_metadata );

			return (string) $file_id;
		}

		function cp_presets_list( $module, $preset ) {

			$styles = array();
			$fun = 'cp_add_'.$module.'_template';

			// Get preset array list
			$styles = $fun( $styles , '', $module );

			$option = 'cp_'.$module.'_preset_templates';

			$existing_templates = get_option( $option );

			if( is_array($existing_templates) ) {
				foreach ($existing_templates as $key => $value) {
					if(  isset( $styles[$key] ) ) {
						unset( $styles[$key] );
					}
				}

				$styles = array_merge( $existing_templates, $styles );
			}


			// get screen shot images
			$screenshot_images = get_option( 'cp_screenshots_images', array() );

			// upload screen shot URL to uploads directory
			foreach( $styles as $key => $style ) {

				if( $preset !== '' && $key != $preset ) {
					continue;
				}

				$screenshot_url = $style[3];

				// if screen shot URL is not present
				if ( !isset( $screenshot_images[ $screenshot_url ] ) ) {

					$source = $screenshot_url;
					$wp_upload_dir  = wp_upload_dir();
					$dir = $wp_upload_dir['basedir'] . '/cp_preset_screenshots';
					$upload_url = $wp_upload_dir['baseurl'];
					$ext = pathinfo( $source, PATHINFO_EXTENSION );
					$fileName = "cp_" . $style[7] . "_screenshot.".$ext;

					// upload image to WP upload directory
					$result = $this->cp_upload_image( $dir, $source, $fileName  );

					if( $result == 'success' || $result = 'Already Present' ) {

						$url = $upload_url . "/cp_preset_screenshots/". $fileName;

						$screenshot_images[$screenshot_url] = array (
							'preset_slug' => $style[7]
						);

					} else {
						wp_send_json_error();
					}

				} else {

					$url = $screenshot_url;

				}

				// replace URL in array
				$styles[$key][3] = $url;

			}

			$option = 'cp_'.$module.'_preset_templates';

			// save all screen shot URLs to option
			update_option( "cp_screenshots_images", $screenshot_images );

			// Save array to option
			update_option( $option, $styles );

			$this->presets_list = $styles;

		}

		function cp_upload_image( $dir, $source, $fileName  ) {

			$result = "success";
			$file_content = file_get_contents($source);

			$file = array(
				'base' 		=> $dir,
				'file' 		=> $fileName,
				'content' 	=> $file_content
			);

			if ( wp_mkdir_p( $file['base'] ) && !file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
					if( fwrite( $file_handle, $file['content'] ) ) {
					   $result = "success";
					} else {
						$result = "failure";
					}

					fclose( $file_handle );
				}
			} else {
				$result = "Already Present";
			}

			return $result;

		}

	}


}

