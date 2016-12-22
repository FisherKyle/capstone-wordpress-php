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
      <h1><?php _e( "ConvertPlug &mdash; Settings", "smile" ); ?></h1>
      <h3><?php _e( "Below are some global settings that are applied to the elements designed with ConvertPlug. If you're just getting started, you probably don't need to do anything here right now.
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
		    <a class="nav-tab nav-tab-active" href="?page=convertplug&view=settings" title="<?php _e( "Settings", "smile" ); ?>"><?php echo __("Settings", "smile" ); ?></a>

        <!-- <a class="nav-tab" href="?page=convertplug&view=cp_import" title="<?php _e( "Import", "smile" ); ?>"><?php echo __( "Import", "smile" ); ?></a> -->

        <?php if($reg_menu_hide !== true) : ?>
        <a class="nav-tab" href="?page=convertplug&view=registration" title="<?php _e( "Registration", "smile"); ?>"><?php echo __("Registration", "smile" ); ?></a>
        <?php endif; ?>

        <a class="nav-tab" href="?page=convertplug&view=knowledge_base" title="<?php _e( "knowledge Base", "smile"); ?>"><?php echo __("Knowledge Base", "smile" ); ?></a>

        <?php if( isset( $_GET['author'] ) ){ ?>
        <a class="nav-tab" href="?page=convertplug&view=debug&author=true" title="<?php _e( "Debug", "smile" ); ?>"><?php echo __( "Debug", "smile" ); ?></a>
        <?php } ?>
      </h2>
    <div id="smile-settings">
      <div class="container cp-started-content">
        <form id="convert_plug_settings" class="cp-options-list">
            <input type="hidden" name="action" value="smile_update_settings" />

            <!-- MX Record Validation For Email -->

          <div class="debug-section">
            <?php
              $data         =  get_option( 'convert_plug_settings' );
              $gfval        = isset($data['cp-enable-mx-record']) ? $data['cp-enable-mx-record'] : 0;
              $is_checked   = ( $gfval ) ? ' checked="checked" ' : '';
              $uniq         =  uniqid();
            ?>
            <p>
              <label for="hide-options" style="width:340px; display: inline-block;"><strong><?php _e( "MX Record Validation For Email", "smile" ); ?></strong>
                <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "Enable / disable MX lookup email validation method.", "smile" ); ?>">
                  <i class="dashicons dashicons-editor-help"></i>
                </span>
              </label>
              <label class="switch-wrapper" style="display: inline-block;margin: 0;height: 20px;">
                <input type="text"  id="cp-enable-mx-record" class="form-control smile-input smile-switch-input"  name="cp-enable-mx-record" value="<?php echo $gfval; ?>" />
                <input type="checkbox" <?php echo $is_checked; ?> id="smile_cp-enable-mx-record_btn_<?php echo $uniq; ?>"  class="ios-toggle smile-input smile-switch-input switch-checkbox smile-switch " value="<?php echo $gfval; ?>" >
                <label class="smile-switch-btn checkbox-label" data-on="ON"  data-off="OFF" data-id="cp-enable-mx-record" for="smile_cp-enable-mx-record_btn_<?php echo $uniq; ?>"></label>
              </label>
            </p><!-- MX Record Validation For Email -->
          </div><!-- .debug-section -->

          <div class="debug-section">
            <!-- Subscription Messages -->
            <h4>Response Message - When User Is Already Subscribed:</h4>
            <!-- Show default messages -->
            <?php
              $data         =  get_option( 'convert_plug_settings' );
              $gfval        = isset($data['cp-default-messages']) ? $data['cp-default-messages'] : 1;
              $is_checked   = ( $gfval ) ? ' checked="checked" ' : '';
              $uniq         =  uniqid();
            ?>
            <p>
              <label for="hide-options" style="width:340px; display: inline-block;"><strong><?php _e( "Display Your Customized Error Message", "smile" ); ?></strong>
                <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "If turned OFF, third party mailer error message will be displayed.", "smile" ); ?>">
                  <i class="dashicons dashicons-editor-help"></i>
                </span>
              </label>
              <label class="switch-wrapper" style="display: inline-block;margin: 0;height: 20px;">
                <input type="text"  id="cp-default-messages" class="form-control smile-input smile-switch-input"  name="cp-default-messages" value="<?php echo $gfval; ?>" />
                <input type="checkbox" <?php echo $is_checked; ?> id="smile_cp-default-messages_btn_<?php echo $uniq; ?>"  class="ios-toggle smile-input smile-switch-input switch-checkbox smile-switch " value="<?php echo $gfval; ?>" >
                <label class="smile-switch-btn checkbox-label" data-on="ON"  data-off="OFF" data-id="cp-default-messages" for="smile_cp-default-messages_btn_<?php echo $uniq; ?>"></label>
              </label>
            </p><!-- Show default messages -->
            <?php
              $data   = get_option( 'convert_plug_settings' );
              $msg    = isset($data['cp-already-subscribed']) ? $data['cp-already-subscribed'] : __( 'Already Subscribed...!', 'smile' );
            ?>
            <p <?php if($msg == 1 ) { echo "style='display:none;'"; } ?> >
              <label for="hide-options" style="width:340px; vertical-align: top; display: inline-block;"><strong><?php _e( "Enter Custom Message", "smile" ); ?></strong>
                <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "Enter your custom message to display when user is already subscribed.", "smile" ); ?>">
                  <i class="dashicons dashicons-editor-help"></i>
                </span>
              </label>
              <textarea id="cp-already-subscribed" name="cp-already-subscribed" cols="40" rows="5"><?php echo stripslashes( $msg ); ?></textarea>
            </p><!-- Subscription Messages -->
            </div><!-- .debug-section -->

            <!-- Google Fonts -->
            <div class="debug-section">
               <!-- Turn On/Off double optin -->
            <?php
            $data         =  get_option( 'convert_plug_settings' );
            $d_optin        = isset($data['cp-double-optin']) ? $data['cp-double-optin'] : 1;
            $optin_checked   = ( $d_optin ) ? ' checked="checked" ' : '';
          ?>
          <p>
            <label for="hide-options" style="width:340px; display: inline-block;"><strong><?php _e( "Double Optin Enable", "smile" ); ?></strong>
              <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "Enable double optin for MailChimp, Benchmark, MyMail.", "smile" ); ?>">
                <i class="dashicons dashicons-editor-help"></i>
              </span>
            </label>
            <label class="switch-wrapper" style="display: inline-block;margin: 0;height: 20px;">
              <input type="text"  id="cp-double-optin" class="form-control smile-input smile-switch-input"  name="cp-double-optin" value="<?php echo $d_optin; ?>" />
              <input type="checkbox" <?php echo $optin_checked; ?> id="smile_cp-double-optin_btn_<?php echo $uniq; ?>"  class="ios-toggle smile-input smile-switch-input switch-checkbox smile-switch " value="<?php echo $d_optin; ?>" >
              <label class="smile-switch-btn checkbox-label" data-on="ON"  data-off="OFF" data-id="cp-double-optin" for="smile_cp-double-optin_btn_<?php echo $uniq; ?>"></label>
            </label>
          </p><!-- end of double optin -->
          </div><!-- .debug-section -->

            <!-- Google Fonts -->
            <div class="debug-section">
              <?php
                $data         =  get_option( 'convert_plug_settings' );
                $gfval        = isset($data['cp-google-fonts']) ? $data['cp-google-fonts'] : 1;
                $is_checked   = ( $gfval ) ? ' checked="checked" ' : '';
                $uniq         =  uniqid();
              ?>
              <p>
                <label for="hide-options" style="width:340px; display: inline-block;"><strong><?php _e( "Google Fonts", "smile" ); ?></strong>
                  <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "Load Google Fonts at front end.", "smile" ); ?>">
                    <i class="dashicons dashicons-editor-help"></i>
                  </span>
                </label>
                <label class="switch-wrapper" style="display: inline-block;margin: 0;height: 20px;">
                  <input type="text"  id="cp-google-fonts" class="form-control smile-input smile-switch-input"  name="cp-google-fonts" value="<?php echo $gfval; ?>" />
                  <input type="checkbox" <?php echo $is_checked; ?> id="smile_cp-google-fonts_btn_<?php echo $uniq; ?>"  class="ios-toggle smile-input smile-switch-input switch-checkbox smile-switch " value="<?php echo $gfval; ?>" >
                  <label class="smile-switch-btn checkbox-label" data-on="ON"  data-off="OFF" data-id="cp-google-fonts" for="smile_cp-google-fonts_btn_<?php echo $uniq; ?>"></label>
                </label>
              </p><!-- Google Fonts -->
            </div>

            <div class="debug-section">
              <p>
                <?php

                  $cp_settings = get_option('convert_plug_settings');

                  $selected = $wselected = $loggedinuser = '';
                  $loggedinuser = explode(",",$cp_settings['cp-user-role']);
                  $timezone = $cp_settings['cp-timezone'];
  		            $user_inactivity = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '60';
                  if( $timezone == 'system' ){
                    $selected = 'selected';
                  }
                  if( $timezone == 'wordpress' ) {
                   $wselected = 'selected';
                  }
                ?>
                <label for="global-timezone" style="width:340px; display: inline-block;"><strong><?php _e( "Set Timezone", "smile" ); ?></strong>
                  <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "Depending on your selection, input will be taken for timer based features in ConvertPlug.", "smile" ); ?>">
                    <i class="dashicons dashicons-editor-help"></i>
                  </span>
                </label>
                <select id="global-timezone" name="cp-timezone">
                  <option value="wordpress" <?php _e( $wselected ); ?> ><?php _e( "WordPress Timezone", "smile" ); ?></option>
                  <option value="system" <?php _e( $selected ); ?> ><?php _e( "System Default Time", "smile" ); ?></option>
                </select>
              </p>
            </div>


            <div class="debug-section">
              <p>
                  <label for="user_inactivity" style="width:340px; display: inline-block;"><strong><?php _e( "User Inactivity Time", "smile" ); ?></strong>
                    <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "Module can be trigger for idle user on your website. This setting helps you control that idle time.", "smile" ); ?>">
                      <i class="dashicons dashicons-editor-help"></i>
                    </span>
                  </label>
                  <input type="number" id="user_inactivity" name="user_inactivity" min="1" max="10000" value="<?php echo $user_inactivity; ?>"/> <span class="description"><?php _e( " Seconds", "smile" ); ?></span>
              </p>
            </div>

            <div class="debug-section">
              <p>
                <?php

                $psval        = isset($data['cp-edit-style-link']) ? $data['cp-edit-style-link'] : 0;
                $is_checked   = ( $psval ) ? ' checked="checked" ' : '';
                $uniq         =  uniqid();

                ?>
                <label for="edit-style-link" style="width:340px; display: inline-block;"><strong><?php _e( "Display Style Edit Link On Front End", "smile" ); ?></strong>
                  <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "Enable style edit link on frontend at bottom right corner of the module, so a user can easily navigate to edit style window. This link will be visible to users who have access to ConvertPlug backend.", "smile" ); ?>">
                    <i class="dashicons dashicons-editor-help"></i>
                  </span>
                </label>
                <label class="switch-wrapper" style="display: inline-block;margin: 0;height: 20px;">
                  <input type="text"  id="cp-edit-style-link" class="form-control smile-input smile-switch-input"  name="cp-edit-style-link" value="<?php echo $psval; ?>" />
                  <input type="checkbox" <?php echo $is_checked; ?> id="smile_cp-edit-style-link_btn_<?php echo $uniq; ?>"  class="ios-toggle smile-input smile-switch-input switch-checkbox smile-switch " value="<?php echo $gfval; ?>" >
                  <label class="smile-switch-btn checkbox-label" data-on="ON"  data-off="OFF" data-id="cp-edit-style-link" for="smile_cp-edit-style-link_btn_<?php echo $uniq; ?>"></label>
                </label>
              </p>
            </div>

            <div class="debug-section">
              <p>
                <?php

                $psval        = isset($data['cp-plugin-support']) ? $data['cp-plugin-support'] : 1;
                $is_checked   = ( $psval ) ? ' checked="checked" ' : '';
                $uniq         =  uniqid();

                ?>
                <label for="plugin-support" style="width:340px; display: inline-block;"><strong><?php _e( "Third Party Plugin Support", "smile" ); ?></strong>
                  <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "Enable this option if you are facing any issues to access ConvertPlug customizer ( edit module screen ). After enabling this option, ConvertPlug will try to resolve all possible JS errors automatically.", "smile" ); ?>">
                    <i class="dashicons dashicons-editor-help"></i>
                  </span>
                </label>
                <label class="switch-wrapper" style="display: inline-block;margin: 0;height: 20px;">
                  <input type="text"  id="cp-plugin-support" class="form-control smile-input smile-switch-input"  name="cp-plugin-support" value="<?php echo $psval; ?>" />
                  <input type="checkbox" <?php echo $is_checked; ?> id="smile_cp-plugin-support_btn_<?php echo $uniq; ?>"  class="ios-toggle smile-input smile-switch-input switch-checkbox smile-switch " value="<?php echo $gfval; ?>" >
                  <label class="smile-switch-btn checkbox-label" data-on="ON"  data-off="OFF" data-id="cp-plugin-support" for="smile_cp-plugin-support_btn_<?php echo $uniq; ?>"></label>
                </label>
              </p>
            </div>

            <div class="debug-section">
              <p>
                <table>
                	<tr>
                    <td style="vertical-align: top;padding-top: 20px;">
                    	<label style="width:340px; display: inline-block;"><strong><?php _e( "Disable Modal Impression Count For", "smile" ); ?></strong>
                        <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "This setting is used while generating analytics data. For selected WordPress user roles, impressions will not be counted.", "smile" ); ?>">
                          <i class="dashicons dashicons-editor-help"></i>
                        </span>
                      </label>
                    </td>
                    <td>
                      <ul class="checkbox-grid">
                      <?php
                           global $wp_roles;
                           $roles = $wp_roles->get_names();

                              foreach ($roles as $rkey => $rvalue) {
                                if( !empty($cp_settings) ) {
                                            if(in_array($rkey, $loggedinuser)){
                                                       echo'<li><input type="checkbox" name="cp-user-role" id="cp-user-role" value="'.$rkey.'"  checked >'.$rvalue.'</li>';
                                                     }else{
                                                       echo'<li><input type="checkbox" name="cp-user-role" id="cp-user-role" value="'.$rkey.'" >'.$rvalue.'</li>';
                                                     }
                                 } else {
                                  if( $rkey == 'administrator' ){

                                       echo'<li><input type="checkbox" name="cp-user-role" id="cp-user-role" value="'.$rkey.'"  checked >'.$rvalue.'</li>';

                                     }else{
                                        echo'<li><input type="checkbox" name="cp-user-role" id="cp-user-role" value="'.$rkey.'" >'.$rvalue.'</li>';
                                     }
                                }
                              }

                        ?>
                      </ul>
                    </td>
                  </tr>
                </table>
              </p>
            </div>

            <?php
              if ( current_user_can( 'manage_options' ) ) {
            ?>
              <div class="debug-section cp-access-roles">
                <p>
                  <table>
                    <tr>
                      <td style="vertical-align: top;padding-top: 20px;">
                        <label for="cp-access-user-role" style="width:340px; display: inline-block;"><strong><?php _e( "Allow ConvertPlug Dashboard Access For", "smile" ); ?></strong>
                          <span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( "ConvertPlug dashboard access will be provided to selected user roles. By default, Administrator user role has complete access of ConvertPlug & it can not be changed.", "smile" ); ?>">
                            <i class="dashicons dashicons-editor-help"></i>
                          </span>
                        </label>
                      </td>
                      <td>
                        <ul class="checkbox-grid">
                          <?php

                            $access_roles = explode(",",$cp_settings['cp-access-role']);
                            global $wp_roles;
                            $roles = $wp_roles->get_names();

                            unset($roles['administrator']);
                          ?>
                          <?php foreach($roles as $key => $role) { ?>
                              <li>
                                  <input type="checkbox" name="cp_access_role" <?php if( in_array($key, $access_roles) ) { echo "checked='checked';";  } ?> value="<?php echo $key; ?>" />
                                  <?php echo $role; ?>
                              </li>
                          <?php } ?>
                        </ul>
                      </td>
                    </tr>
                  </table>
                </p>
              </div>
            <?php } ?>

        </form>
        <button type="button" class="button button-primary button-update-settings"><?php _e("Save Settings", "smile"); ?></button>
    </div>
</div>
</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($){

    //  Toggle Response Messages
    jQuery('#cp-default-messages').siblings('.smile-switch-btn').each(function(index, el) {
        var self = jQuery(el);
        toggle_response_messages( self );
        self.click(function(event) {
          jQuery("#cp-already-subscribed").parent('p').slideToggle();
        });
    });

    jQuery('.has-tip').frosty();
  	var form = jQuery("#convert_plug_settings");
  	var btn = jQuery(".button-update-settings");
  	var inactive = jQuery("#user_inactivity");
  	var msg = jQuery(".msg");
  	btn.click(function() {

        var ser = jQuery("[name]").not("#cp-user-role").serialize();
        var array_values = [];
        var access_role_array = [];
        jQuery("input[name='cp-user-role']").map(function(){
            if(jQuery(this).is(":checked")){
               array_values.push( $(this).val() );
            }
        });

        if ( jQuery(".cp-access-roles.debug-section").length > 0 ) {

            jQuery("input[name='cp_access_role']").map(function(){
                if(jQuery(this).is(":checked")){
                    access_role_array.push( $(this).val() );
                }
            });

            var access_role_array = access_role_array.join(',');
            ser += "&cp-access-role="+access_role_array;
        }

        var arrayValues = array_values.join(',');
        ser += "&cp-user-role="+arrayValues;

      	var inactive_time = inactive.val();
      	ser += "&user_inactivity="+inactive_time;

        var data =ser;
  		jQuery.ajax({
  			url: ajaxurl,
  			data: data,
  			dataType: 'JSON',
  			type: 'POST',
  			success: function(result){
  				if(result.message == "Settings Updated!"){
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

//  Toggle Response Messages
function toggle_response_messages( self ) {
  var id = self.data('id');
  var value = self.parents(".switch-wrapper").find("#"+id).val();

  if( value == 1 || value == '1' ) {
    jQuery("#cp-already-subscribed").parent('p').slideDown();
  } else {
    jQuery("#cp-already-subscribed").parent('p').slideUp();
  }
}

</script>
