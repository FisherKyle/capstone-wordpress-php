<?php
if( !function_exists( "modal_theme_countdown" ) ) {
	function modal_theme_countdown( $atts, $content = null ){
		/**
		 * Define Variables
		 */
		global $cp_form_vars;

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
		$uid 		= uniqid();
		$uid_class	= 'content-'.$uid;

		//	Individual style variables
		$individual_vars = array(
			'style_class' 		=> 'cp-count-down',
			'uid'				=> $uid,
			'uid_class'			=> $uid_class
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
			$cp_form_vars,
			$style_settings,
			$atts
		);

		//	Extract short code variables
		$a = shortcode_atts( $all, $style_settings );
		
		/**
		 * 	Style - individual options
		 */
		
		/** = Before filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array('cp_modal_global_before', array( $a ) );
?>
		<!-- BEFORE CONTENTS -->
		<div class="cp-row cp-counter-container" style = 'background:<?php echo esc_attr( $a['modal_bg_color'] ); ?>'>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cp-text-container" >
	        	<div class="cp-title-container <?php if( trim( $a['modal_title1'] ) == '' ) { echo 'cp-empty'; } ?>">
	            	<h2 class="cp-title cp_responsive" style="color: <?php echo esc_attr( $a['modal_title_color'] ); ?>;"><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_title1'] ) ) ); ?></h2>
	           	</div>
	            <div class="cp-desc-container <?php if( trim( $a['modal_short_desc1'] ) == '' ) { echo 'cp-empty'; } ?>">
		        	<div class="cp-description cp_responsive" style="color: <?php echo esc_attr( $a['modal_desc_color'] ); ?>;"><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_short_desc1'] ) ) ); ?></div>
				</div>
				<div class="cp-count-down-container cp-clear"  >
					 <div class="counter-overlay" style = 'background:<?php echo esc_attr( $a['counter_container_bg_color'] ); ?>'></div>
					<div class="cp-count-down-desc"><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['count_down_title'] ) ) ); ?></div>
						<?php
		             		/**
							 * Embed count down
							 */
							apply_filters_ref_array('cp_get_count_down', array( $a ) );
						?>
				</div>
				<div class='cp-row cp-form-seperator cp-clear' >		
		           	 <div class="counter-desc-overlay" style = "background:<?php echo esc_attr( $a['form_bg_color'] ); ?>"></div>
		           	<div class="cp-short-desc-container <?php if( trim( $a['modal_content'] ) == '' ) { echo 'cp-empty'; } ?>">
	                    <div class="cp-short-description cp-desc cp_responsive " ><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_content'] ) ) ); ?></div>
	                </div> 
	             <div class="cp-form-container">
                	<div class="cp-submit-container">
						<?php
		             		/**
							 * Embed CP Form
							 */
							apply_filters_ref_array('cp_get_form', array( $a ) );
						?>
					</div>
				</div>
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
