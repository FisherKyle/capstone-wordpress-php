<?php
if( !function_exists( "slide_in_theme_optin" ) ) {
	function slide_in_theme_optin( $atts, $content = null ){

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
			'style_class' 	=> 'cp-optin'
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

		//	Merge arrays - 'shortcode atts' & 'style options'
		$a = array_merge( $a, $atts );

		/** = Style - individual options
		 *-----------------------------------------------------------*/
		//	Variables
		$imgclass 			= '';
		$on_success_action  = '';

		if( isset( $a['on_success'] ) && $a['on_success'] == "redirect" ) {
			$on_success_action =  isset( $a['redirect_url'] ) ? $a['redirect_url'] : '';
		} else {
			$on_success_action  = isset( $a['success_message'] ) ? $a['success_message'] : '';
		}


		/** = Before filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array('cp_slidein_global_before', array( $a ) );

?>
	  	<div class="cp-row">
	    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cp-text-container <?php echo esc_attr($imgclass); ?>" >

	      		<div class="cp-title-container <?php if( trim( $a['slidein_title1'] ) == '' ) { echo 'cp-empty'; } ?>">
	       			<h2 class="cp-title cp_responsive"><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['slidein_title1'] ) ) ); ?></h2>
	      		</div>
	      		<div class="cp-desc-container <?php if( trim( $a['slidein_short_desc1'] ) == '' ) { echo 'cp-empty'; } ?>">
	        		<div class="cp-description cp_responsive" ><?php echo do_shortcode( html_entity_decode(  stripcslashes( $a['slidein_short_desc1'] ) ) ); ?></div>
	      		</div>
	        </div><!-- end of text container-->

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
