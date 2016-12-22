<?php
if( !function_exists( "modal_theme_instant_coupon" ) ) {
	function modal_theme_instant_coupon( $atts, $content = null ){
		/**
		 * Define Variables
		 */
		global $cp_form_vars;

		$style_id = $settings_encoded = '';
		extract(shortcode_atts(array(
			'style_id'			=> '',
			'settings_encoded'	=> '',
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
			"style_class"	=> "cp-instant-coupon"
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
	
		/** = Before filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array('cp_modal_global_before', array( $a ) );
 ?>
      	<div class="cp-row">
        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cp-text-container " >
           		<div class="cp-desc-container <?php if( trim( $a['modal_short_desc1'] ) == '' ) { echo 'cp-empty'; } ?>">
            		<div class="cp-description cp_responsive" style="background-color:<?php echo esc_attr( $a['modal_title_bg_color'] );?> ;border-top-color:<?php echo esc_attr( $a['modal_title_bg_color'] );?> "><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_short_desc1'] ) ) ); ?></div>
          		</div>
           		<div class="cp-title-container <?php if( trim( $a['modal_title1'] ) == '' ) { echo 'cp-empty'; } ?> ">
           			<h2 class="cp-title cp_responsive" ><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_title1'] ) ) ); ?></h2>
          		</div>          		
          		<div class="cp-short-desc-container <?php if( trim( $a['modal_content'] ) == '' ) { echo 'cp-empty'; } ?>">
                    <div class="cp-short-description cp-desc cp_responsive " ><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_content'] ) ) ); ?></div>
                </div>
         		<div class="cp-form-container">
					<?php
	             		/**
						 * Embed CP Form
						 */
						apply_filters_ref_array('cp_get_form', array( $a ) );
					?>
				</div><!-- .cp-form-container -->
        	    <div class="cp-info-container cp_responsive <?php if( trim( $a['modal_confidential'] ) == '' ) { echo 'cp-empty'; } ?>" style="color: <?php echo esc_attr( $a['tip_color'] ); ?>;">
                	<?php echo do_shortcode( html_entity_decode( stripcslashes ( $a['modal_confidential'] ) ) ); ?>
                </div>

        	</div><!-- .col-lg-7 col-md-7 col-sm-7 col-xs-12 cp-text-container -->
		</div>
<?php
		/** = After filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array('cp_modal_global_after', array( $a ) );

	   return ob_get_clean();
	}
}