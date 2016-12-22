<?php
/*
* Preview Style
*/
require_once('functions/functions.options.php');

$style = $_GET['style'];
$settings_method = $_GET['method'];
$template_name = $_GET['temp_name'];

$options = Smile_Slide_Ins::$options;
$style_options = $options[$style]['options'];

$settings_encoded = cp_get_live_preview_settings( 'slide_in', $settings_method, $style_options, $template_name );

echo do_shortcode('[smile_slide_in style="'.$style.'" settings_encoded="' . $settings_encoded . ' "][/smile_slide_in]');
?>
<script type="text/javascript">
jQuery(document).ready(function(e) {
    jQuery(".slidein-overlay").addClass("si-open");
	jQuery("body").on("click",".slidein-overlay", function(){
		jQuery(this).removeClass("si-open");
		jQuery("#TB_ajaxContent").remove();
		jQuery("#TB_window").remove();
		jQuery("#TB_overlay").trigger("click");
		jQuery("body").removeClass("modal-open");
		jQuery("#TB_overlay").remove();
	});
	jQuery("body").on("click",".cp-slidein-content",function(e){
		e.preventDefault();
		e.stopPropagation();
	});
});
</script>
