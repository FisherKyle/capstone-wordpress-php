<?php
if( !function_exists( "slide_in_theme_social_fly_in" ) ) {
	function slide_in_theme_social_fly_in( $atts, $content = null ){
		$style_id = $settings_encoded = '';
		extract(shortcode_atts(array(
			'style_id'			=> '',
			'settings_encoded'		=> '',
	    ), $atts));

		/**
		 * Define Variables
		 */
		global $cp_social_vars;

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
			'style_class' 	=> 'cp-social-flyin'
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
			$cp_social_vars,
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

		/** = Before filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array( 'cp_slidein_global_before', array( $a ) );
?>
		<!-- BEFORE CONTENTS -->
        <div class="cp-row">
        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
        		<div class="cp-title-container <?php if( trim( $a['slidein_title1'] ) == '' ) { echo 'cp-empty'; } ?> ">
					<h1 class="cp-title cp_responsive">
						<?php  
		               	echo do_shortcode( html_entity_decode( stripcslashes( $a['slidein_title1'] ) ) );
		                ?>
		            </h1>
            	</div>
            	<div class="cp-desc-container <?php if( trim( $a['slidein_short_desc1'] ) == '' ) { echo 'cp-empty'; } ?>">
	        		<div class="cp-description cp_responsive" ><?php echo do_shortcode( html_entity_decode(  stripcslashes( $a['slidein_short_desc1'] ) ) ); ?></div>
	      		</div>
                <div class="cp_social_media_wrapper">
					<?php
	             		/**
						 * Embed CP social media
						 */
						apply_filters_ref_array('cp_get_social', array( $a ) );
					?>
				 </div>
			</div>
		</div>
		<!-- AFTER CONTENTS -->
<?php
		/** = After filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array('cp_slidein_global_after', array( $a ) );

	   	return ob_get_clean();
	}
}
