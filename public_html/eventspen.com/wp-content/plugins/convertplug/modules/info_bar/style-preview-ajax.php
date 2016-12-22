<?php
/*
* Preview Style
*/
require_once('functions/functions.options.php');

$style = $_GET['style'];
$settings_method = $_GET['method'];
$template_name = $_GET['temp_name'];

$options = Smile_Info_Bars::$options;
$style_options = $options[$style]['options'];

$settings_encoded = cp_get_live_preview_settings( 'info_bar', $settings_method, $style_options, $template_name );

echo '<style type="text/css">
	.cp-overlay {
		background: rgb(0, 0, 0);
		opacity: 0.2;
		filter: alpha(opacity=70);
		position: fixed;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		z-index: 100050;
	}
</style>';
echo '<div class="cp-overlay"></div>';

echo do_shortcode('[smile_info_bar style="'.$style.'" settings_encoded="' . $settings_encoded . ' "][/smile_info_bar]');
?>
<script type="text/javascript">
jQuery(document).ready(function(e) {
	jQuery(".cp-info-bar").addClass("ib-display");
	jQuery("#TB_ajaxContent").appendTo("body");
    jQuery(".cp-info-bar-container").css({"position":"fixed","z-index":9999999});
	jQuery("body").on("click",".ib-close, .cp-overlay", function(){
		jQuery(".cp-info-bar").removeClass("ib-display");
		jQuery("#TB_ajaxContent").remove();
		jQuery("#TB_overlay").trigger("click");
		jQuery("body").removeClass("modal-open");
	});
	jQuery("body").on("click",".cp-info_bar-content",function(e){
		e.preventDefault();
		e.stopPropagation();
	});
});
jQuery(document).ready(function(){
	jQuery(document).bind('keydown', function(e) {
		if (e.which == 27) {
			var cp_overlay = jQuery(".ib-display");
			var info_bar = cp_overlay;
			info_bar.fadeOut('slow').remove();
			jQuery("#TB_ajaxContent").remove();
			jQuery("#TB_window").remove();
			jQuery("#TB_overlay").remove();
		}
	});
});

</script>
