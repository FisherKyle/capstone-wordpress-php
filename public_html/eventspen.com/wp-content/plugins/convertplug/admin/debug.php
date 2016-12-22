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

.debug-section {
    background: #FAFAFA;
    padding: 10px 30px;
    border: 1px solid #efefef;
    margin-bottom: 15px;
}
</style>
<div class="wrap about-wrap about-cp bend">
  <div class="wrap-container">
    <div class="bend-heading-section cp-about-header">
      <h1><?php _e( "ConvertPlug &mdash; Debugging", "smile" ); ?></h1>
      <h3><?php _e( "Below are some settings that will help you to debug the js and css and some extra functionality.
", "smile" ); ?></h3>
      <div class="bend-head-logo">
        <div class="bend-product-ver">
          <?php _e( "Version", "smile" ); echo ' '.CP_VERSION; ?>
        </div>
      </div>
    </div><!-- bend-heading section -->
    <div class="msg"></div>
    <div class="bend-content-wrap smile-settings-wrapper">
      <h2 class="nav-tab-wrapper">
        <a class="nav-tab" href="?page=convertplug" title="<?php _e( "About", "smile"); ?>"><?php echo __("About", "smile" ); ?></a>
        <a class="nav-tab" href="?page=convertplug&view=modules" title="<?php _e( "Modules", "smile" ); ?>"><?php echo __( "Modules", "smile" ); ?></a>
        <?php if($reg_menu_hide !== true) : ?>
        <a class="nav-tab" href="?page=convertplug&view=registration" title="<?php _e( "Registration", "smile"); ?>"><?php echo __("Registration", "smile" ); ?></a>
        <?php endif; ?>

        <a class="nav-tab nav-tab-active" href="?page=convertplug&view=knowledge_base" title="<?php _e( "knowledge Base", "smile"); ?>"><?php echo __("Knowledge Base", "smile" ); ?></a>

        <?php if( isset( $_GET['author'] ) ){ ?>
        <a class="nav-tab" href="?page=convertplug&view=debug&author=true" title="<?php _e( "Debug", "smile" ); ?>"><?php echo __( "Debug", "smile" ); ?></a>
        <?php } ?>
      </h2>
    <div id="smile-settings">
      <div class="container cp-started-content">
        <form id="convert_plug_debug" class="cp-options-list">
          <input type="hidden" name="action" value="smile_update_debug" />

           <div class="debug-section">
              <?php
                $data         =  get_option( 'convert_plug_debug' );
                $dmval        = isset($data['cp-dev-mode']) ? $data['cp-dev-mode'] : 0;
                $is_checked   = ( $dmval ) ? ' checked="checked" ' : '';
                $uniq         =  uniqid();
              ?>
              <p>
                <label for="hide-options" style="width:320px; display: inline-block;"><strong><?php _e( "Developer Mode", "smile" ); ?></strong>
                  <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "Enable developer mode to load all beautified CSS and JS.", "smile" ); ?>">
                    <i class="dashicons dashicons-editor-help"></i>
                  </span>
                </label>
                <label class="switch-wrapper" style="display: inline-block;margin: 0;height: 20px;">
                  <input type="text"  id="cp-dev-mode" class="form-control smile-input smile-switch-input" name="cp-dev-mode" value="<?php echo $dmval; ?>" />
                  <input type="checkbox" <?php echo $is_checked; ?> id="smile_cp-dev-mode_btn_<?php echo $uniq; ?>"  class="ios-toggle smile-input smile-switch-input switch-checkbox smile-switch " value="<?php echo $dmval; ?>" >
                  <label class="smile-switch-btn checkbox-label" data-on="ON"  data-off="OFF" data-id="cp-dev-mode" for="smile_cp-dev-mode_btn_<?php echo $uniq; ?>"></label>
                </label>
              </p><!-- Contact Form 7 - Styles -->
            </div><!-- .debug-section -->

          <div class="debug-section">
            <h4>Page Push Down Support:</h4>
            <p>
            <?php
            $data = get_option( 'convert_plug_debug' );
            $push_page_input = isset($data['push-page-input']) ? $data['push-page-input'] : '';
            $top_offset_container = isset($data['top-offset-container']) ? $data['top-offset-container'] : '';
            ?>
              <label for="hide-options" style="width:320px; display: inline-block;"><strong><?php _e( "Fixed Header Class / ID", "smile" ); ?></strong>
                <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "For effective execution of push page down functionality of Info Bar, please enter class / ID of fixed header of your theme. e.g. #ID, .class", "smile" ); ?>">
                  <i class="dashicons dashicons-editor-help"></i>
                </span>
              </label>
              <input type="text" name="push-page-input" value="<?php echo $push_page_input; ?>">
            </p>
            <p>
              <label for="hide-options" style="width:320px; display: inline-block;"><strong><?php _e( "Top Offset Class / ID", "smile" ); ?></strong>
                <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "For effective execution of push page down functionality of Info Bar, please enter class / ID of Top offset container of your theme. e.g. #ID, .class", "smile" ); ?>">
                  <i class="dashicons dashicons-editor-help"></i>
                </span>
              </label>
              <input type="text" name="top-offset-container" value="<?php echo $top_offset_container; ?>">
            </p>
          </div><!-- .debug-section -->

          <!-- Contact Form 7 - Styles -->

            <div class="debug-section">
              <?php
                $data         =  get_option( 'convert_plug_debug' );
                $gfval        = isset($data['cp-cf7-styles']) ? $data['cp-cf7-styles'] : 1;
                $is_checked   = ( $gfval ) ? ' checked="checked" ' : '';
                $uniq         =  uniqid();
              ?>
              <p>
                <label for="hide-options" style="width:320px; display: inline-block;"><strong><?php _e( "Predefine Contact Form 7 Style", "smile" ); ?></strong>
                  <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "Enable Predefined Style to your Contact Form 7.", "smile" ); ?>">
                    <i class="dashicons dashicons-editor-help"></i>
                  </span>
                </label>
                <label class="switch-wrapper" style="display: inline-block;margin: 0;height: 20px;">
                  <input type="text"  id="cp-cf7-styles" class="form-control smile-input smile-switch-input"  name="cp-cf7-styles" value="<?php echo $gfval; ?>" />
                  <input type="checkbox" <?php echo $is_checked; ?> id="smile_cp-cf7-styles_btn_<?php echo $uniq; ?>"  class="ios-toggle smile-input smile-switch-input switch-checkbox smile-switch " value="<?php echo $gfval; ?>" >
                  <label class="smile-switch-btn checkbox-label" data-on="ON"  data-off="OFF" data-id="cp-cf7-styles" for="smile_cp-cf7-styles_btn_<?php echo $uniq; ?>"></label>
                </label>
              </p><!-- Contact Form 7 - Styles -->
            </div><!-- .debug-section -->

            <div class="debug-section">
              <?php
                $data = get_option( 'convert_plug_debug' );
                $hide_admin_bar = $data['cp-hide-bar'];
                $selected_wp = ( $hide_admin_bar == "wordpress" ) ? "selected" : "";
                $selected_css = ( $hide_admin_bar == "css" ) ? "selected" : "";
                $after_content_scroll = isset( $data['after_content_scroll'] ) ? $data['after_content_scroll'] : '50';
              ?>
              <p>
                  <label for="after_content_scroll" style="width:320px; display: inline-block;"><strong><?php _e( "After Content Scroll %", "smile" ); ?></strong>
                    <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "Page scroll % to trigger the modal after content.", "smile" ); ?>">
                      <i class="dashicons dashicons-editor-help"></i>
                    </span>
                  </label>
                  <input type="number" id="after_content_scroll" name="after_content_scroll" min="1" max="10000" value="<?php echo $after_content_scroll; ?>"/> <span class="description"><?php _e( " %", "smile" ); ?></span>
              </p>
            </div><!-- .debug-section -->

            <div class="debug-section">
              <p>
                <label for="hide-options" style="width:320px; display: inline-block;"><strong><?php _e( "Hide Admin Bar Using", "smile" ); ?></strong>
                  <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "Depending on your selection, the WordPress admin bar will be hidden for you in customizer.", "smile" ); ?>">
                    <i class="dashicons dashicons-editor-help"></i>
                  </span>
                </label>
                <select id="hide-options" name="cp-hide-bar">
                  <option value="css" <?php echo esc_attr( $selected_css ); ?>><?php _e( "CSS", "smile" ); ?></option>
                  <option value="wordpress" <?php echo esc_attr( $selected_wp ); ?>><?php _e( "WordPress Filter", "smile" ); ?></option>
                </select>
              </p>
            </div><!-- .debug-section -->
            <?php
                $sub_def_action = isset( $data['cp-post-sub-action'] ) ? $data['cp-post-sub-action'] : 'process_success';
                $selected_already_sbuscribed = ( $sub_def_action == "already_sub_msg" ) ? "selected" : "";
                $selected_msg_success = ( $sub_def_action == "process_success" ) ? "selected" : "";

            ?>
             <div class="debug-section">
              <p>
                <label for="post-sub-action" style="width:320px; display: inline-block;"><strong><?php _e( "Default Action - when user is already subscribed", "smile" ); ?></strong>
                  <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "Depending on your selection, action will be taken if user is already subscribed.", "smile" ); ?>">
                    <i class="dashicons dashicons-editor-help"></i>
                  </span>
                </label>
                <select id="post-sub-action" name="cp-post-sub-action">
                  <option value="already_sub_msg" <?php echo esc_attr( $selected_already_sbuscribed ); ?>><?php _e( "Show message as already subscribed", "smile" ); ?></option>
                  <option value="process_success" <?php echo esc_attr( $selected_msg_success ); ?>><?php _e( "Update and process as success", "smile" ); ?></option>
                </select>
              </p>
            </div><!-- .debug-section -->

            <div class="debug-section">
              <?php
                $dival        = isset($data['cp-display-debug-info']) ? $data['cp-display-debug-info'] : 0;
                $is_checked   = ( $dival ) ? ' checked="checked" ' : '';
                $uniq         =  uniqid();
              ?>
              <p>
                <label for="hide-options" style="width:320px; display: inline-block;"><strong><?php _e( "Display Debug Info", "smile" ); ?></strong>
                  <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "Enable this option to display debug info in HTML comments.", "smile" ); ?>">
                    <i class="dashicons dashicons-editor-help"></i>
                  </span>
                </label>
                <label class="switch-wrapper" style="display: inline-block;margin: 0;height: 20px;">
                  <input type="text"  id="cp-display-debug-info" class="form-control smile-input smile-switch-input" name="cp-display-debug-info" value="<?php echo $dival; ?>" />
                  <input type="checkbox" <?php echo $is_checked; ?> id="smile_cp-display-debug-info_btn_<?php echo $uniq; ?>"  class="ios-toggle smile-input smile-switch-input switch-checkbox smile-switch " value="<?php echo $dival; ?>" >
                  <label class="smile-switch-btn checkbox-label" data-on="ON"  data-off="OFF" data-id="cp-display-debug-info" for="smile_cp-display-debug-info_btn_<?php echo $uniq; ?>"></label>
                </label>
              </p><!-- Contact Form 7 - Styles -->
            </div><!-- .debug-section -->

        </form>
        <button type="button" class="button button-primary button-update-settings"><?php _e("Save Settings", "smile"); ?></button>
    </div>
</div>
</div>
</div>
<script type="text/javascript">

jQuery(document).ready(function($){


  jQuery('.has-tip').frosty();
  var form = jQuery("#convert_plug_debug");
  var btn = jQuery(".button-update-settings");
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
        if( result.message == "Settings Updated!" ) {
          swal("<?php _e( "Updated!", "smile" ); ?>", result.message, "success");
          setTimeout(function(){
            window.location = window.location;
          },500);
        } else {
          swal("<?php _e( "Error!", "smile" ); ?>", result.message, "error");
        }
      }
    });
  });
});


</script>
