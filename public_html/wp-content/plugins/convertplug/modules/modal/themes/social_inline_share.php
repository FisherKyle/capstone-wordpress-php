<?php
if( !function_exists( "modal_theme_social_inline_share" ) ) {
	function modal_theme_social_inline_share( $atts, $content = null ){

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
			"style_class"	=> "cp-social-inline-share"
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

		$a = shortcode_atts( $all , array() );

		$cp_row_Class = $cp_row_eq_Class = '';
		if(isset( $a['cp_custom_height'] ) && $a['cp_custom_height'] == '1'){
			$cp_row_Class = 'cp-row-center';
			$cp_row_eq_Class = 'cp-row-equalized-center';
		}
	
		/** = Before filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array('cp_modal_global_before', array( $a ) );

		$class = '';
		if( $a['cp_social_remove_icon_spacing'] == 1 ){
			$class .= 'cp-social-no-space';
		}
		if( $a['cp_social_icon_column'] == 'auto' ){
			$class .= ' cp-auto-column';
		}
		
?>
		<!-- BEFORE CONTENTS -->
		<div class="cp-row <?php echo esc_attr( $cp_row_Class );?>">		
			<div class="cp-text-container <?php echo esc_attr( $cp_row_eq_Class );?>" >
	        	<div class="cp_social_media_wrapper <?php echo esc_attr($class);?>">
						<?php
		             		/**
							 * Embed CP Form
							 */
							apply_filters_ref_array('cp_get_social', array( $a ) );
						?>
				 </div>
	        </div><!-- .col-lg-7 col-md-7 col-sm-7 col-xs-12 cp-text-container -->	 
		</div>
		<!-- AFTER CONTENTS -->
<?php
		/** = After filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array('cp_modal_global_after', array( $a ) );

	   return ob_get_clean();
	}
}
