<?php
if( !function_exists( "slide_in_theme_free_widget" ) ) {
	function slide_in_theme_free_widget( $atts, $content = null ){
/**
		 * Define Variables
		 */
		global $cp_form_vars;

		$style_id = $settings_encoded = '';
		extract(shortcode_atts(array(
			'style_id'			=> '',
			'settings_encoded'		=> '',
	    ), $atts));

		$settings = base64_decode( $settings_encoded );
		$style_settings = unserialize( $settings );

		foreach( $style_settings as $key => $setting ) {
			$style_settings[$key] = apply_filters('smile_render_setting',$setting);
		}

		unset($style_settings['style_id']);

		//	Generate UID
		$uid		= uniqid();
		$uid_class	= 'content-'.$uid;

		//	Individual style variables
		$individual_vars = array(
			"uid"       	=> $uid,
			"uid_class" 	=> $uid_class,
			'style_class' 	=> 'cp-free-widget'
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
			$cp_form_vars,
			$style_settings,
			$atts
		);

		 /**
		 *	Extract short-code variables
		 *
		 *	@array 		$all 		 All merged arrays
		 *	@array 		array() 	 Its required as per WP. Merged $style_settings in $all.
		 */

		$a = shortcode_atts( $all , $style_settings );

		/** = Style - individual options
		 *-----------------------------------------------------------*/
		//	Variables
		$imgclass 			= '';
		$on_success_action 	= ( $a['on_success'] == "redirect" ) ? $a['redirect_url'] : $a['success_message'] ;


		/**
		 * 	Style - individual options
		 */
		//	Variables
		$imgclass 			= ( $a['image_position'] == 0 ) ? 'cp-right-contain' : '';
		$imageStyle		 	= cp_add_css( 'left', $a['image_horizontal_position'], 'px');
		$imageStyle		   .= cp_add_css( 'top', $a['image_vertical_position'], 'px');
		$imageStyle		   .= cp_add_css( 'max-width', $a['image_size'], 'px');

		//	Filters & Actions
		$slidein_image 			= apply_filters( 'cp_get_slidein_image_url', $a );

		//	Filters & Actions for image alt
		$slidein_image_alt 			= apply_filters( 'cp_get_slidein_image_alt', $a );

		/** = Before filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array('cp_slidein_global_before', array( $a ) );

?>
	  	<div class="cp-row cp-columns-equalized">
	    	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 cp-text-container <?php echo esc_attr($imgclass); ?> cp-column-equalized-center" >

	      		<div class="cp-title-container <?php if( trim( $a['slidein_title1'] ) == '' ) { echo 'cp-empty'; } ?>">
	       			<h2 class="cp-title cp_responsive"><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['slidein_title1'] ) ) ); ?></h2>
	      		</div>
	      		
	        </div><!-- end of text container-->
	        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 cp-column-equalized-center">
              <?php
              if ( !isset($a['slidein_img_src']) ) {
              	$a['slidein_img_src'] = 'upload_img';
              }

              if ( isset($a['slidein_img_src']) && $a['slidein_img_src'] != 'none' ) {
              ?>
	              <div class="cp-image-container  ">
	              	<img style="<?php echo esc_attr($imageStyle); ?>" src="<?php echo esc_attr( $slidein_image ); ?>" class="cp-image" <?php echo $slidein_image_alt;?> >
	              </div>

              <?php } ?>
            </div><!-- .col-lg-4 col-md-4 col-sm-4 col-xs-12 -->
        </div>
        <div class="cp-row">
			<div class="cp-desc-container <?php if( trim( $a['slidein_short_desc1'] ) == '' ) { echo 'cp-empty'; } ?>">
        		<div class="cp-description cp_responsive" ><?php echo do_shortcode( html_entity_decode(  stripcslashes( $a['slidein_short_desc1'] ) ) ); ?></div>
      		</div>
	    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cp-form-container">
	      		<?php
	         		/**
					 * Embed CP Form
					 */
					apply_filters_ref_array('cp_get_form', array( $a ) );
				?>
			</div>
			<div class="cp-info-container <?php if( trim( $a['slidein_confidential'] ) == '' ) { echo 'cp-empty'; } ?>" >
	        	<?php echo do_shortcode( html_entity_decode( stripcslashes( $a['slidein_confidential'] ) ) ); ?>
	        </div>
	  	</div><!--row-->
<?php
		/** = After filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array('cp_slidein_global_after', array( $a ) );

	   return ob_get_clean();
	}
}
