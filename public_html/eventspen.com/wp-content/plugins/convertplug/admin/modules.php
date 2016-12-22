<?php
  $is_cp_status = ( function_exists( "bsf_product_status" ) ) ? bsf_product_status('14058953') : '';
  $reg_menu_hide = ( (defined( 'BSF_UNREG_MENU' ) && ( BSF_UNREG_MENU === true || BSF_UNREG_MENU === 'true' )) ||
  (defined( 'BSF_REMOVE_14058953_FROM_REGISTRATION' ) && ( BSF_REMOVE_14058953_FROM_REGISTRATION === true || BSF_REMOVE_14058953_FROM_REGISTRATION === 'true' )) ) ? true : false;
  if($reg_menu_hide !== true) {
    if($is_cp_status)
      $reg_menu_hide = true;
  }
?>
<style type="text/css">
.about-cp .wp-badge:before {
  content: "\e600";
  font-family: 'ConvertPlug';
  speak: none;
  font-style: normal;
  font-weight: normal;
  font-variant: normal;
  text-transform: none;
  line-height: 1;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  font-size: 72px;
  top: calc( 50% - 54px );
  position: absolute;
  left: calc( 50% - 33px );
  color: #FFF;
}
</style>
<div class="wrap about-wrap about-cp bend">
  <div class="wrap-container">
    <div class="bend-heading-section cp-about-header">
    <h1><?php _e("Welcome to ConvertPlug!", "smile"); ?></h1>
    <h3><?php _e("Welcome to ConvertPlug - the easiest WordPress plugin to convert website traffic into leads. ConvertPlug will help you build email lists, drive traffic, promote videos, offer coupons and much more!", "smile"); ?></h3>
    <div class="bend-head-logo">
        <div class="bend-product-ver">
          <?php _e( "Version", "smile" ); echo ' '.CP_VERSION; ?>
        </div>
      </div>
	</div><!-- bend-heading section -->
    <div class="msg"></div>
    <div class="bend-content-wrap">
	    <h2 class="nav-tab-wrapper">
            <a class="nav-tab" href="?page=convertplug" title="<?php _e( "About", "smile"); ?>"><?php echo __("About", "smile" ); ?></a>
            <a class="nav-tab nav-tab-active" href="?page=convertplug&view=modules" title="<?php _e( "Modules", "smile" ); ?>"><?php echo __( "Modules", "smile" ); ?></a>

            <!-- <a class="nav-tab" href="?page=convertplug&view=cp_import" title="<?php _e( "Import", "smile" ); ?>"><?php echo __( "Import", "smile" ); ?></a> -->

            <?php if($reg_menu_hide !== true) : ?>
            <a class="nav-tab" href="?page=convertplug&view=registration" title="<?php _e( "Registration", "smile"); ?>"><?php echo __("Registration", "smile" ); ?></a>
            <?php endif; ?>

            <a class="nav-tab nav-tab-active" href="?page=convertplug&view=knowledge_base" title="<?php _e( "knowledge Base", "smile"); ?>"><?php echo __("Knowledge Base", "smile" ); ?></a>

            <?php if( isset( $_GET['author'] ) ){ ?>
            <a class="nav-tab" href="?page=convertplug&view=debug&author=true" title="<?php _e( "Debug", "smile" ); ?>"><?php echo __( "Debug", "smile" ); ?></a>
            <?php } ?>

	      </h2>
    <div id="smile-module-settings">
        <?php
        $modules = Smile_Framework::$modules;
        $stored_modules = get_option('convert_plug_modules');

        ?>
        <form id="convert_plug_modules" class="cp-modules-list">
        <input type="hidden" name="action" value="smile_update_modules" />
        <?php
            $output = '';
			foreach($modules as $module => $opts){
				$file = $opts['file'];
				$module_img = $opts['img'];
				$module_desc = $opts['desc'];
				$module_name = str_replace(' ', '_', $module);
				$checked = is_array( $stored_modules ) && in_array($module_name,$stored_modules) ? 'checked="checked"' : '';
				$output .= '<div class="cp-module-box">';
				$output .= '<div class="cp-module">';
				$output .= "\t".'<div class="cp-module-switch">';
				$uniq = uniqid();
				$output .= "\t\t".'<div class="switch-wrapper">
							<input type="text"  id="smile_'.$module_name.'" class="form-control smile-input smile-switch-input "  value="'.$module.'" />
							<input type="checkbox" '.$checked.' id="smile_'.$module_name.'_btn_'.$uniq.'" name="' . $module_name . '" class="ios-toggle smile-input smile-switch-input switch-checkbox smile-switch " value="'.$module.'" >
							<label class="smile-switch-btn checkbox-label" data-on="ON"  data-off="OFF" data-id="smile_'.$module_name.'" for="smile_'.$module_name.'_btn_'.$uniq.'">
							</label>
						</div>';
				$output .= "\t".'</div>';
				$output .= "\t".'<div class="cp-module-desc">';
				$output .= "\t".'<h3>'.$module.'</h3>';
				$output .= "\t".'<p>'.$module_desc.'</p>';
				$output .= "\t".'</div>';
				$output .= '</div>';
				$output .= '</div>';
			}
			echo $output;
        ?>
        </form>
        <button type="button" class="button button-primary button-hero button-update-modules"><?php _e("Save Modules", "smile"); ?></button>
        <a class="button button-secondary button-hero advance-cp-setting" href="?page=convertplug&view=settings" title="<?php _e( "Advanced Settings", "smile" ); ?>"><?php echo __("Advanced Settings", "smile" ); ?></a>

    </div>
</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
	var form = jQuery("#convert_plug_modules");
	var btn = jQuery(".button-update-modules");
	var msg = jQuery(".msg");
	btn.click(function(){
		var data = form.serialize();
		jQuery.ajax({
			url: ajaxurl,
			data: data,
			dataType: 'JSON',
			type: 'POST',
			success: function(result){
				console.log(result);
				if(result.message == "Modules Updated!"){
					swal("Updated!", result.message, "success");
					setTimeout(function(){
						window.location = window.location;
					},500);
				} else {
					swal("Error!", result.message, "error");
				}
			}
		});
	});
});

</script>
