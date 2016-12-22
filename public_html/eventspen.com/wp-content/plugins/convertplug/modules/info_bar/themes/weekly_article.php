<?php

if( !function_exists( "info_bar_theme_weekly_article" ) ) {
	function info_bar_theme_weekly_article( $atts, $content = null ){
		$style_id = $settings_encoded = $load_on_refresh = '';
		extract(shortcode_atts(array(
			'style_id'			=> '',
			'settings_encoded'	=> '',
	    ), $atts));

		$settings = base64_decode( $settings_encoded );
		$style_settings = unserialize( $settings );

		foreach($style_settings as $key => $setting){
			$style_settings[$key] = apply_filters('smile_render_setting',$setting);
		}

		unset($style_settings['style_id']);

		//	Generate UID
		$uid 		= uniqid();
		$uid_class	= 'content-'.$uid;

		//	Individual style variables
		$individual_vars = array(
			'uid'				=> $uid,
			'uid_class'			=> $uid_class,
			'style_class' 		=> 'cp-weekly-article'
		);

		global $cp_form_vars;

		/**
		 * Merge short code variables arrays
		 *
		 * @array 	$individual_vars		Individual style EXTRA short code variables
		 * @array 	$style_settings			Individual style short code variables
		 * @array 	$cp_form_vars			CP Form global short code variables
		 */
		$all = array_merge(
			$individual_vars,
			$style_settings,
			$cp_form_vars,
			$atts
		);

		//	Extract short code variables
		$a = shortcode_atts( $all, $style_settings );

		/** = Before filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array( 'cp_ib_global_before', array( $a ) );

		$el_class = $info_bar_size_style = $close_class = '';

		if( $a['on_success'] == "redirect" ){
			$on_success_action = $a['redirect_url'];
		} elseif( $a['on_success'] == "message" ) {
			$on_success_action = $a['success_message'] ;
		} else {
			$on_success_action = "Close";
		}

		$button_css = "background:".$a['button_bg_color'].";";

		//info bar image
		$info_bar_image 		= apply_filters( 'cp_get_info_bar_image_url', $a );

		//info bar image alt text
		$info_bar_alt 		= apply_filters( 'cp_get_info_bar_image_alt', $a );

		$imageStyle		    = cp_add_css( 'max-width', $a['image_size'], 'px');
		$imageStyle		   .= cp_add_css( 'width', $a['image_size'], 'px');

		$img_class ='';
		if( $a['image_displayon_mobile'] ){
			$img_class .= 'cp_ifb_hide_img';
		}

		//	Merge arrays - 'shortcode atts' & 'style options'
		$a = array_merge( $a, $atts );

		//ob_start();
		?>

			<div class="cp-image-container">
				<img style="<?php echo esc_attr($imageStyle); ?>" src="<?php echo esc_attr( $info_bar_image ); ?>" class="cp-image <?php echo esc_attr( $img_class );?>" <?php echo $info_bar_alt ;?> >
	        </div>
	        <div class="cp-msg-container <?php echo ( trim( $a['infobar_title'] ) == "" ? "cp-empty" : '' );  ?>">
	            <span class="cp-info-bar-msg"><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['infobar_title'] ) ) ); ?></span>
	        </div>
	        <div class="cp-flex cp-sub-container">
	            <div class="cp-form-container">
		    		<?php
		         		/**
						 * Embed CP Form
						 */
						apply_filters_ref_array('cp_get_form', array( $a ) );
					?>
				</div>
				<div class="cp-flex cp-info-bar-desc-container <?php echo ( trim( $a['infobar_description'] ) == "" ? "cp-empty" : '' );  ?>">
				    <div class="cp-info-bar-desc"><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['infobar_description'] ) ) ); ?></div>
				</div>
			</div>
<?php

	/** = After filter
	  *-----------------------------------------------------------*/
		apply_filters_ref_array( 'cp_ib_global_after', array( $a ) );

		return ob_get_clean();
	}
}
