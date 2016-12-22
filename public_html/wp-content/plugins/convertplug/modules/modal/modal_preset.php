<?php

// action to add templates
//add_filter('cp_templates_list', 'cp_add_template_action', 10, 3 );
function cp_add_modal_template( $args, $preset, $module ) {

	if( $module == 'modal' ) {

		$modal_temp_array = array (

			"international_conference" =>
				array (
					"locked_content", // theme slug for template
					"International Conference", // template name
					plugins_url('assets/demos/locked_content/locked_content.html',__FILE__), // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshot_international_conf.png", // screen shot url for template
					plugins_url('assets/demos/locked_content/customizer.js',__FILE__), // customizer js for template
					"All,Offers", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"international_conference" // template unique slug
				),

			"how_to_learn" =>
				array (
					"every_design", // theme slug for template
					"How To Learn", // template name
					plugins_url('assets/demos/every_design/every_design.html',__FILE__), // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshot_how_to_learn.png", // screen shot url for template
					plugins_url('assets/demos/every_design/customizer.js',__FILE__), // customizer js for template
					"All,Offers", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"how_to_learn" // template unique slug
				),

			"sharing_is_awesome_do_it" =>
				array (
					"social_media", // theme slug for template
					"Sharing Is Awesome Do It", // template name
					plugins_url('assets/demos/social_media/social_media.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_8cc49.png", // screen shot url for template
					plugins_url('assets/demos/social_media/customizer.js',__FILE__), // customizer js for template
					"All,Social", // categories
					"Shortcode,Canvas,HTML,Custom,Facebook,Twitter,Google,Blogger,Pinterest,LinkedIn", // tags
					"sharing_is_awesome_do_it" // template unique slug
					),
			"sharing_rounded_icons" =>
				array (
					"social_media", // theme slug for template
					"Sharing Rounded Icons", // template name
					plugins_url('assets/demos/social_media/social_media.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_ad474.png", // screen shot url for template
					plugins_url('assets/demos/social_media/customizer.js',__FILE__), // customizer js for template
					"All,Social", // categories
					"Shortcode,Canvas,HTML,Custom,Facebook,Twitter,Google,Pinterest", // tags
					"sharing_rounded_icons" // template unique slug
					),
				"sharing_bar_icons" =>
				array (
					"social_media", // theme slug for template
					"Sharing Bar Icons", // template name
					plugins_url('assets/demos/social_media/social_media.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_5f0a9.png", // screen shot url for template
					plugins_url('assets/demos/social_media/customizer.js',__FILE__), // customizer js for template
					"All,Social", // categories
					"Shortcode,Canvas,HTML,Custom,Facebook,Twitter,Google,Blogger", // tags
					"sharing_bar_icons" // template unique slug
					),
				"first_order_discount" =>
				array (
					"first_order_2", // theme slug for template
					"First Order Discount", // template name
					plugins_url('assets/demos/first_order_2/first_order_2.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_10442.png", // screen shot url for template
					plugins_url('assets/demos/first_order_2/customizer.js',__FILE__), // customizer js for template
					"All,modal popup, Offers", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"first_order_discount" // template unique slug
					),
				"subscribe_to_newsletter" =>
				array (
					"every_design", // theme slug for template
					"Subscribe To Newsletter", // template name
					plugins_url('assets/demos/every_design/every_design.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_3efd1.png", // screen shot url for template
					plugins_url('assets/demos/every_design/customizer.js',__FILE__), // customizer js for template
					"All,modal popup, full screen", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"subscribe_to_newsletter" // template unique slug
					),
				"create_profitable_blog" =>
				array (
					"every_design", // theme slug for template
					"Create Profitable Blog", // template name
					plugins_url('assets/demos/every_design/every_design.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_f7bc0.png", // screen shot url for template
					plugins_url('assets/demos/every_design/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,full screen", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"create_profitable_blog" // template unique slug
					),
				"get_glamorous" =>
				array (
					"first_order_2", // theme slug for template
					"Get Glamorous", // template name
					plugins_url('assets/demos/first_order_2/first_order_2.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_fa4a6.png", // screen shot url for template
					plugins_url('assets/demos/first_order_2/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Offers,Updates,Exit Intent", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"get_glamorous" // template unique slug
					),
				"get_latest_freebies" =>
				array (
					"every_design", // theme slug for template
					"Get Latest Freebies", // template name
					plugins_url('assets/demos/every_design/every_design.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_6dd6c.png", // screen shot url for template
					plugins_url('assets/demos/every_design/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,exit intent,offers,Optins", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"get_latest_freebies" // template unique slug
					),
				"burger_hot_deals" =>
				array (
					"optin_to_win", // theme slug for template
					"Burger Hot Deals", // template name
					plugins_url('assets/demos/optin_to_win/optin_to_win.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_9a260.png", // screen shot url for template
					plugins_url('assets/demos/optin_to_win/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Optins", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"burger_hot_deals" // template unique slug
					),
				"join_greatest_mailing_list" =>
				array (
					"every_design", // theme slug for template
					"Join Greatest Mailing List", // template name
					plugins_url('assets/demos/every_design/every_design.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_cbf7b.png", // screen shot url for template
					plugins_url('assets/demos/every_design/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Optins", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"join_greatest_mailing_list" // template unique slug
					),
				"pet_care" =>
				array (
					"direct_download", // theme slug for template
					"Pet Care", // template name
					plugins_url('assets/demos/direct_download/direct_download.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_e7b09.png", // screen shot url for template
					plugins_url('assets/demos/direct_download/customizer.js',__FILE__), // customizer js for template
					"All,modal popup, Updates", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"pet_care" // template unique slug
					),
				"spicy_hot_deal" =>
				array (
					"direct_download", // theme slug for template
					"Spicy Hot Deal", // template name
					plugins_url('assets/demos/direct_download/direct_download.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_f199e.png", // screen shot url for template
					plugins_url('assets/demos/direct_download/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Offers", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"spicy_hot_deal" // template unique slug
					),

				"blue_social_media_guide" =>
				array (
					"direct_download", // theme slug for template
					"Blue Social Media Guide", // template name
					plugins_url('assets/demos/direct_download/direct_download.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_e8868.png", // screen shot url for template
					plugins_url('assets/demos/direct_download/customizer.js',__FILE__), // customizer js for template
					"All,Exit Intent,modal popup", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"blue_social_media_guide" // template unique slug
					),
				"bricks_popup_subscription_box" =>
				array (
					"every_design", // theme slug for template
					"Brics Popup Subscription", // template name
					plugins_url('assets/demos/every_design/every_design.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_2aa4f.png", // screen shot url for template
					plugins_url('assets/demos/every_design/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Optins,Exit Intent", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"bricks_popup_subscription_box" // template unique slug
					),
				"green_exclusive_blogging_tips" =>
				array (
					"every_design", // theme slug for template
					"Green Exclusive Tips", // template name
					plugins_url('assets/demos/every_design/every_design.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_30739.png", // screen shot url for template
					plugins_url('assets/demos/every_design/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Optins", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"green_exclusive_blogging_tips" // template unique slug
					),
				"get_more_subscribers" =>
				array (
					"webinar", // theme slug for template
					"Get More Subscriber", // template name
					plugins_url('assets/demos/webinar/webinar.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_8eba4.png", // screen shot url for template
					plugins_url('assets/demos/webinar/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Optins", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"get_more_subscribers" // template unique slug
					),
				"business_blog_optin" =>
				array (
					"direct_download", // theme slug for template
					"Bussiness Blog Optin", // template name
					plugins_url('assets/demos/direct_download/direct_download.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_fa93d.png", // screen shot url for template
					plugins_url('assets/demos/direct_download/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Exit Intent", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"business_blog_optin" // template unique slug
					),
				"blogging_guide" =>
				array (
					"direct_download", // theme slug for template
					"Blogging Guide", // template name
					plugins_url('assets/demos/direct_download/direct_download.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_8016c.png", // screen shot url for template
					plugins_url('assets/demos/direct_download/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Exit Intent", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"blogging_guide" // template unique slug
					),
				"black_friday_discount" =>
				array (
					"first_order", // theme slug for template
					"Black Friday Discount", // template name
					plugins_url('assets/demos/first_order/first_order.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/cp_id_8c9f9.png", // screen shot url for template
					plugins_url('assets/demos/first_order/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Updates,Offers", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"black_friday_discount" // template unique slug
					),
				"design_kitchen" =>
				array (
					"locked_content", // theme slug for template
					"Design Kitchen", // template name
					plugins_url('assets/demos/locked_content/locked_content.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshots_design_kitchen.png", // screen shot url for template
					plugins_url('assets/demos/locked_content/customizer.js',__FILE__), // customizer js for template
					"All,modal popup, Updates", // categories
					"Shortcode,Canvas,HTML,Custom,Kitchen,Delights", // tags
					"design_kitchen" // template unique slug
					),
				"exclusive_tips" =>
				array (
					"every_design", // theme slug for template
					"Exclusive Tips", // template name
					plugins_url('assets/demos/every_design/every_design.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshots_excl_tip.png", // screen shot url for template
					plugins_url('assets/demos/every_design/customizer.js',__FILE__), // customizer js for template
					"All,modal popup, Updates", // categories
					"Shortcode,Canvas,HTML,Custom,Tips", // tags
					"exclusive_tips" // template unique slug
					),
				"show_time" =>
				array (
					"optin_to_win", // theme slug for template
					"Show Time", // template name
					plugins_url('assets/demos/optin_to_win/optin_to_win.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshots_show_time.png", // screen shot url for template
					plugins_url('assets/demos/optin_to_win/customizer.js',__FILE__), // customizer js for template
					"All,modal popup, Updates", // categories
					"Shortcode,Canvas,HTML,Custom,Tips,Movie", // tags
					"show_time" // template unique slug
					),
				"design_post" =>
				array (
					"every_design", // theme slug for template
					"Design Post", // template name
					plugins_url('assets/demos/every_design/every_design.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshots_design_post.png", // screen shot url for template
					plugins_url('assets/demos/every_design/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,exit intent,offers,Optins", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"design_post" // template unique slug
					),
				"extended_discount" =>
				array (
					"first_order_2", // theme slug for template
					"Extended Discount", // template name
					plugins_url('assets/demos/first_order_2/first_order_2.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshot_ext_disc.png", // screen shot url for template
					plugins_url('assets/demos/first_order_2/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Offers,Updates,Exit Intent", // categories
					"Shortcode,Canvas,HTML,Custom,Discount", // tags
					"extended_discount" // template unique slug
					),
				"design_tigers" =>
				array (
					"every_design", // theme slug for template
					"Design Tigers", // template name
					plugins_url('assets/demos/every_design/every_design.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshots_super_surviver.png", // screen shot url for template
					plugins_url('assets/demos/every_design/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,exit intent,offers,Optins", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"design_tigers" // template unique slug
					),
				"free_note" =>
				array (
					"first_order", // theme slug for template
					"Free Note", // template name
					plugins_url('assets/demos/first_order/first_order.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshot_note.png", // screen shot url for template
					plugins_url('assets/demos/first_order/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Updates,Offers", // categories
					"Shortcode,Canvas,HTML,Custom,Notes,Enterpreneur", // tags
					"free_note" // template unique slug
					),
				"conversion_hack" =>
				array (
					"direct_download", // theme slug for template
					"Conversion Hack", // template name
					plugins_url('assets/demos/direct_download/direct_download.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshot_conversion_tips.png", // screen shot url for template
					plugins_url('assets/demos/direct_download/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Exit Intent", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"conversion_hack" // template unique slug
					),
				"ecommerce_growth" =>
				array (
					"webinar", // theme slug for template
					"Ecommerce Growth", // template name
					plugins_url('assets/demos/webinar/webinar.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshot_ecommerce.png", // screen shot url for template
					plugins_url('assets/demos/webinar/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Optins", // categories
					"Shortcode,Canvas,HTML,Custom,Ecommerce", // tags
					"ecommerce_growth" // template unique slug
					),
				"pet_care_fullscreen" =>
				array (
					"locked_content", // theme slug for template
					"Pet Care", // template name
					plugins_url('assets/demos/locked_content/locked_content.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshot_full_pet_care.png", // screen shot url for template
					plugins_url('assets/demos/locked_content/customizer.js',__FILE__), // customizer js for template
					"All,modal popup, Updates", // categories
					"Shortcode,Canvas,HTML,Custom,Pet,Care", // tags
					"pet_care_fullscreen" // template unique slug
					),
				"we_care_your_pet" =>
				array (
					"first_order_2", // theme slug for template
					"We Care Your Pet", // template name
					plugins_url('assets/demos/first_order_2/first_order_2.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/preset_we_care_ur_pet.png", // screen shot url for template
					plugins_url('assets/demos/first_order_2/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Offers,Updates,Exit Intent", // categories
					"Shortcode,Canvas,HTML,Custom,Discount,Pet", // tags
					"we_care_your_pet" // template unique slug
					),
				"e_cooking_classes" =>
				array (
					"direct_download", // theme slug for template
					"E Cooking Classes", // template name
					plugins_url('assets/demos/direct_download/direct_download.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshot_cookery_classes.png", // screen shot url for template
					plugins_url('assets/demos/direct_download/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Exit Intent", // categories
					"Shortcode,Canvas,HTML,Custom", // tags
					"e_cooking_classes" // template unique slug
					),
				"huge_discount" =>
				array (
					"first_order", // theme slug for template
					"Huge Discount", // template name
					plugins_url('assets/demos/first_order/first_order.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshot_huge.png", // screen shot url for template
					plugins_url('assets/demos/first_order/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Updates,Offers", // categories
					"Shortcode,Canvas,HTML,Custom,Notes,Enterpreneur", // tags
					"huge_discount" // template unique slug
					),
				"Yello_pet_food_offer" =>
				array (
					"special_offer", // theme slug for template
					"Yello Pet Food Offer", // template name
					plugins_url('assets/demos/special_offer/special_offer.html',__FILE__),	 // HTML file for template
					"http://downloads.brainstormforce.com/convertplug/presets/screenshot_yellow_food_pet.png", // screen shot url for template
					plugins_url('assets/demos/special_offer/customizer.js',__FILE__), // customizer js for template
					"All,modal popup,Updates,Offers", // categories
					"Shortcode,Canvas,HTML,Custom,Pet,Food", // tags
					"Yello_pet_food_offer" // template unique slug
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
