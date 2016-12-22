<?php
$variant_style = $_GET['variant-style'];
$style = $_GET['style'];
if(!isset($variant_style) && $variant_style !== ''){
	header('?page=smile-info_bar-designer&style-view=variant&variant-style='.$style.'&style='.$style);
}
?>
<div class="edit-screen-overlay" style="overflow: hidden;background: #FCFCFC;position: fixed;width: 100%;height: 100%;top: 0;left: 0;z-index: 9999999;">
    <div class="smile-absolute-loader" style="visibility: visible;overflow: hidden;">
      <div class="smile-loader">
        <div class="smile-loading-bar"></div>
        <div class="smile-loading-bar"></div>
        <div class="smile-loading-bar"></div>
        <div class="smile-loading-bar"></div>
      </div>
    </div>
</div><!-- .edit-screen-overlay -->
<div class="wrap">
	<h2> <?php _e( "Edit Variant Style", "smile" ); ?>
		<a class="add-new-h2" href="?page=smile-info_bar-designer&style-view=variant&variant-style=<?php echo $variant_style; ?>&style=<?php echo $style; ?>" title="<?php _e( "Back to Variant Tests", "smile" ); ?>"><?php _e( "Back to Variant Tests", "smile" ); ?></a>
    </h2>
    <div class="message"></div>
    <div class="smile-style-wrapper">
        <div id="smile-default-styles">
            <div class="smile-default-styles theme-browser rendered">
            	<div class="themes">
				<?php
                if(function_exists('Smile_Style_Dashboard')){
                    Smile_Style_Dashboard('Smile_Info_Bars','info_bar_variant_tests','info_bar');
                }
                ?>
                </div>
            </div>
        </div>
    </div>
</div>
