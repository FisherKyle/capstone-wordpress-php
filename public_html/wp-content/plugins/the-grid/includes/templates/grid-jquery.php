<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

// Cornerstone live builder mode
if (isset($_SERVER['QUERY_STRING']) && strpos($_SERVER['QUERY_STRING'], 'action=cs_render_element') !== false) {
	$ID = $tg_grid_data['ID'];
	echo '<script type="text/javascript">';
	echo '(function($) {
		"use strict";
		$.TG_media_init();
		$(document).ready(function() {
			$("#'.esc_attr($ID).' .preloader-styles,#'.esc_attr($ID).' .the_grid_styles").removeAttr("scoped");
			$("#'.esc_attr($ID).' .tg-grid-holder").The_Grid();
		});
	})(jQuery);';
	echo '</script>';
}

// Append custom script
if (!empty($tg_grid_data['custom_js'])) {
	echo '<script type="text/javascript">(function($) {"use strict";'.$tg_grid_data['custom_js'].'})(jQuery)</script>';
}