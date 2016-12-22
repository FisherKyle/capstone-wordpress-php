<?php
if( !function_exists( "info_bar_theme_blank" ) ) {
	function info_bar_theme_blank( $atts, $content = null ){
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
			'uid'				=> $uid,
			'uid_class'			=> $uid_class,	
			'style_class' 		=> 'cp-blank-info-bar'
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

		//	Merge arrays - 'shortcode atts' & 'style options'
		$a = array_merge( $a, $atts );

		/** = Before filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array( 'cp_ib_global_before', array( $a ) );
		?>

        <div class="cp-content-container">
                <?php
                $content = html_entity_decode( $a['infobar_title'] );
                $content = htmlspecialchars_decode( $content );
                $content = htmlspecialchars($content);
                $content = html_entity_decode( $content );
                echo do_shortcode( stripslashes( $content ) );
                ?>
        </div>
       <?php

       	/** = After filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array( 'cp_ib_global_after', array( $a ) );

		return ob_get_clean();
	}
}
