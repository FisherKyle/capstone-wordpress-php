<?php

if( !function_exists( "info_bar_theme_newsletter" ) ) {
	function info_bar_theme_newsletter( $atts, $content = null ){
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
			'style_class' 		=> 'cp-newsletter'
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

		//ob_start();
		?>
        <div class="cp-msg-container <?php echo ( trim( $a['infobar_title'] ) == "" ? "cp-empty" : '' );  ?>">
            <span class="cp-info-bar-msg"><?php echo do_shortcode( stripslashes( html_entity_decode( $a['infobar_title'] ) ) ); ?></span>
        </div>

        <?php if( $a['mailer'] == "custom-form" ) { ?>
            <div class="custom-html-form">
		  		<?php echo do_shortcode( stripslashes( $a['custom_html_form'] ) ); ?>
		  	</div>
  		<?php } else { ?>

			<div class="cp-flex ib-form-container">
            	<div class="cp-form-container">
		    		<?php
		         		/**
						 * Embed CP Form
						 */
						apply_filters_ref_array('cp_get_form', array( $a ) );
					?>
				</div>                
			</div>

		<?php }

	        /** = After filter
			 *-----------------------------------------------------------*/
			apply_filters_ref_array( 'cp_ib_global_after', array( $a ) );
			?>
       <?php
		return ob_get_clean();
	}
}