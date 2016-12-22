<?php

// action to add templates
function cp_add_info_bar_template( $args, $preset, $module ) {

	if( $module == 'info_bar' ) {

		$modal_temp_array = array (

			"vector" =>
				array (
					"image_preview", // theme slug for template
					"Free Images", // template name
					plugins_url('assets/demos/image_preview/image_preview.html',__FILE__), // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshot_vector.png", // screen shot
					plugins_url('assets/demos/image_preview/customizer.js',__FILE__), // customizer js for template
					"All,Offers", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"vector" // template unique slug
			),
			"simple_text_notice" =>
				array (
					"blank", // theme slug for template
					"Text Notice", // template name
					plugins_url('assets/demos/blank/blank.html',__FILE__), // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/info-screenshot.png", // screen shot
					plugins_url('assets/demos/blank/customizer.js',__FILE__), // customizer js for template
					"All,info bar", // categories
					"Shortcode,Canvas,HTML,Custom,Notice", // tags
					"simple_text_notice" // template unique slug
			),
			"stickybar_newsletter" =>
				array (
					"newsletter", // theme slug for template
					"Sticky Newsletter", // template name
					plugins_url('assets/demos/newsletter/newsletter.html',__FILE__), // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_d66dc.png", // screen shot
					plugins_url('assets/demos/newsletter/customizer.js',__FILE__), // customizer js for template
					"All,Optins,info bar", // categories
					"Shortcode,Canvas,HTML,Custom,Notice", // tags
					"stickybar_newsletter" // template unique slug
			),
			"social_infobar_circle" =>
				array (
					"social_info_bar", // theme slug for template
					"Social Info Bar Circle", // template name
					plugins_url('assets/demos/social_info_bar/social_info_bar.html',__FILE__), // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/social_site.png", // screen shot
					plugins_url('assets/demos/social_info_bar/customizer.js',__FILE__), // customizer js for template
					"All,Optins,info bar", // categories
					"Shortcode,Canvas,HTML,Custom,Notice", // tags
					"social_infobar_circle" // template unique slug
			),
		);

		if( $preset  !== '' ) {
			$temp_arr = $modal_temp_array[$preset];
			$modal_temp_array = array();
			$modal_temp_array[$preset] = $temp_arr;
			$args = array_merge( $args, $modal_temp_array );
		} else {
			$args = array_merge( $args, $modal_temp_array );
		}
	}

	return $args;
}
