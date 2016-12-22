<?php
if( !function_exists( "info_bar_theme_get_this_deal" ) ) {
	function info_bar_theme_get_this_deal( $atts, $content = null ){
		$style_id = $settings_encoded = $load_on_refresh = '';
		extract(shortcode_atts(array(
			'style_id'			=> '',
			'settings_encoded'		=> '',
	    ), $atts));

		$settings = base64_decode( $settings_encoded );
		$style_settings = unserialize( $settings );

		foreach($style_settings as $key => $setting){
			$style_settings[$key] = apply_filters('smile_render_setting',$setting);;
		}

		unset($style_settings['style_id']); 

		//	Generate UID
		$uid 		= uniqid();
		$uid_class	= 'content-'.$uid;

		//	Individual style variables
		$individual_vars = array(
			'uid'			=> $uid,
			'uid_class'		=> $uid_class,
			'style_class' 	=> 'cp-get-this-deal',
		);

		global $cp_form_vars;

		//	1. Individual Style
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
		?>
        <div class="cp-msg-container <?php echo ( trim( $a['infobar_title'] ) == "" ? "cp-empty" : '' );  ?> ">
            <span class="cp-info-bar-msg cp_responsive"><?php echo do_shortcode( stripslashes( html_entity_decode( $a['infobar_title'] ) ) ); ?></span>
        </div>
		<div class="cp-button-field ib-form-container">
	        <div class="cp-form-container">
	    		<?php
	         		/**
					 * Embed CP Form
					 */
					apply_filters_ref_array('cp_get_form', array( $a ) );
				?>
			</div>
        </div>
<?php
       	/** = After filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array( 'cp_ib_global_after', array( $a ) );
		return ob_get_clean();
	}
}
