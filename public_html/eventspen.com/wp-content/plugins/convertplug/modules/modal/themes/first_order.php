<?php
if( !function_exists( "modal_theme_first_order" ) ) {
	function modal_theme_first_order( $atts, $content = null ){
		/**
		 * Define Variables
		 */
		global $cp_form_vars;

		$style_id = $settings_encoded = '';
		extract(shortcode_atts(array(
			'style_id'			=> '',
			'settings_encoded'	=> ''
	    ), $atts));

		$settings = base64_decode( $settings_encoded );
		$style_settings = unserialize( $settings );

		foreach( $style_settings as $key => $setting ) {
			$style_settings[$key] = apply_filters('smile_render_setting',$setting);;
		}

		unset($style_settings['style_id']);

		//	Generate UID
		$uid		= uniqid();
		$uid_class	= 'content-'.$uid;

		$individual_vars = array(
			"uid"       	=> $uid,
			"uid_class" 	=> $uid_class,
			"style_class"	=> "cp-first-order"
		);

		/**
		 * Merge short code variables arrays
		 *
		 * @array 	$individual_vars		Individual style EXTRA short-code variables
		 * @array 	$cp_form_vars			CP Form global short-code variables
		 * @array 	$style_settings			Individual style short-code variables
		 * @array 	$atts					short-code attributes
		 */

		$all = array_merge(
			$individual_vars,
			$style_settings,
			$cp_form_vars,
			$atts
		);

		//	Merge arrays - 'short code atts' & 'style options'
		$a = shortcode_atts( $all, $style_settings );


		/** = Style - individual options
		 *-----------------------------------------------------------*/
		$on_success_action 	= ( $a['on_success'] == "redirect" ) ? $a['redirect_url'] : $a['success_message'] ;
		$imageStyle		 	= cp_add_css( 'left', $a['image_horizontal_position'], 'px');
		$imageStyle		   .= cp_add_css( 'top', $a['image_vertical_position'], 'px');
		$imageStyle		   .= cp_add_css( 'max-width', $a['image_size'], 'px');


		//	Filters & Actions
		$modal_image 		= apply_filters( 'cp_get_modal_image_url', $a );

		$modal_image_alt 		= apply_filters( 'cp_get_modal_image_alt', $a );

		/** = Before filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array('cp_modal_global_before', array( $a ) );

 ?>
        <div class="cp-row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cp-text-container">
           		<?php if ( isset($a['modal_img_src']) && $a['modal_img_src'] != 'none'  ) {?>
           		<div class="">
	              <div class="cp-image-container">
	              	<img style="<?php echo esc_attr($imageStyle); ?>" src="<?php echo esc_attr( $modal_image ); ?>" class="cp-image" <?php echo $modal_image_alt;?> >
	              </div>
	            </div>
	            <?php } ?>
	            <div class="cp-title-container <?php if( trim( $a['modal_title1'] ) == '' ) { echo 'cp-empty'; } ?>">
           			<h2 class="cp-title cp_responsive" ><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_title1'] ) ) ); ?></h2>
           		</div>
          		<div class="cp-short-desc-container cp-clear  <?php if( trim( $a['modal_content'] ) == '' ) { echo 'cp-empty'; } ?>">
          			<div class="cp-short-description cp_responsive cp-clear " ><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_content'] ) ) ); ?></div>
             	</div>
             	<div class="cp-form-container">
					<?php
	             		/**
						 * Embed CP Form
						 */
						apply_filters_ref_array('cp_get_form', array( $a ) );
					?>
				</div>
	            <div class="cp-info-container cp_responsive <?php if( trim( $a['modal_confidential'] ) == '' ) { echo 'cp-empty'; } ?>" >
	                <?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_confidential'] ) ) ); ?>
	           </div>
            </div>
        </div>
       <!-- AFTER CONTENTS -->
<?php
		/** = After filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array('cp_modal_global_after', array( $a ) );

	   return ob_get_clean();
	}
}
