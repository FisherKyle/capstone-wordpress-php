<?php
if( !function_exists( "modal_theme_blank" ) ) {
	function modal_theme_blank( $atts, $content = null ){

		$style_id = $settings_encoded = $load_on_refresh = '';
		extract(shortcode_atts(array(
			'style_id'			=> '',
			'settings_encoded'		=> '',
	    ), $atts));

		$settings = base64_decode( $settings_encoded );
		$style_settings = unserialize( $settings );

		foreach($style_settings as $key => $setting){
			$style_settings[$key] = apply_filters('smile_render_setting',$setting);
		}

		unset($style_settings['style_id']); 

		//	Generate UID
		$uid		= uniqid();
		$uid_class	= 'content-'.$uid;

		//	Individual style variables
		$individual_vars = array(
			"uid"          => $uid,
			"uid_class"    => $uid_class,
			'style_class'  => 'cp-blank'
		);

		/**
		 * Merge short code variables arrays
		 *
		 * @array 	$individual_vars		Individual style EXTRA shortcode variables
		 * @array 	$style_settings			Individual style shortcode variables
		 * @array 	$cp_form_vars			CP Form global shortcode variables
		 */
		$all = array_merge(
			$individual_vars,
			$style_settings,
			$atts
		);

		//	Extract short code variables
		$a = shortcode_atts( $all , array() );

		$cp_row_Class = $cp_row_eq_Class = '';
		if( isset( $a['cp_custom_height'] ) && $a['cp_custom_height'] == '1' ) {
			$cp_row_Class = 'cp-row-center';
			$cp_row_eq_Class = 'cp-row-equalized-center';
		}

		/** = Before filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array( 'cp_modal_global_before', array( $a ) );

?>
		<!-- BEFORE CONTENTS -->
        <div class="cp-row <?php echo esc_attr( $cp_row_Class );?>">
        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php echo esc_attr( $cp_row_eq_Class );?> cp_responsive">
				<?php
                $content = html_entity_decode( $a['modal_title1'] );
               	$content = htmlspecialchars_decode( $content );
               	$content = htmlspecialchars($content);
               	$content = html_entity_decode( $content );
               	echo do_shortcode( stripslashes( $content ) );
                ?>
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