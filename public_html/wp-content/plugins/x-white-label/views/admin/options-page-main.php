<?php

// =============================================================================
// VIEWS/ADMIN/OPTIONS-PAGE-MAIN.PHP
// -----------------------------------------------------------------------------
// Plugin options page main content.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Main Content
// =============================================================================

// Main Content
// =============================================================================

?>

<div id="post-body-content">
  <div class="meta-box-sortables ui-sortable">

    <!--
    ENABLE
    -->

    <div id="meta-box-enable" class="postbox">
      <div class="handlediv" title="<?php _e( 'Click to toggle', '__x__' ); ?>"><br></div>
      <h3 class="hndle"><span><?php _e( 'Enable', '__x__' ); ?></span></h3>
      <div class="inside">
        <p><?php _e( 'Select the checkbox below to enable the plugin.', '__x__' ); ?></p>
        <table class="form-table">

          <tr>
            <th>
              <label for="x_white_label_enable">
                <strong><?php _e( 'Enable White Label', '__x__' ); ?></strong>
                <span><?php _e( 'Select to enable the plugin and display options below.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text"><span>input type="checkbox"</span></legend>
                <input type="checkbox" class="checkbox" name="x_white_label_enable" id="x_white_label_enable" value="1" <?php checked( ! isset( $x_white_label_enable ) ? '0' : $x_white_label_enable, '1', true ); ?>>
              </fieldset>
            </td>
          </tr>

        </table>
      </div>
    </div>

    <!--
    SETTINGS
    -->

    <div id="meta-box-settings" class="postbox" style="display: <?php echo ( isset( $x_white_label_enable ) && $x_white_label_enable == 1 ) ? 'block' : 'none'; ?>;">
      <div class="handlediv" title="<?php _e( 'Click to toggle', '__x__' ); ?>"><br></div>
      <h3 class="hndle"><span><?php _e( 'Settings', '__x__' ); ?></span></h3>
      <div class="inside">
        <p><?php _e( 'Select your plugin settings below.', '__x__' ); ?></p>
        <table class="form-table">

          <tr>
            <th>
              <label for="x_white_label_login_image">
                <strong><?php _e( 'Login Image', '__x__' ); ?></strong>
                <span><?php _e( 'Enter the URL to an image that you would like to use in place of the standard WordPress login image (must be less than 320px wide).', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <input type="text" class="file large-text" name="x_white_label_login_image" id="x_white_label_login_image" value="<?php echo ( isset( $x_white_label_login_image ) ) ? $x_white_label_login_image : ''; ?>">
              <input type="button" id="_x_white_label_login_image_image_upload_btn" data-id="x_white_label_login_image" class="button-secondary x-upload-btn-wl" value="Upload Image">
              <div class="x-meta-box-img-thumb-wrap" id="_x_white_label_login_image_thumb">
                  <?php if ( isset( $x_white_label_login_image ) && ! empty( $x_white_label_login_image ) ) : ?>
                     <div class="x-uploader-image"><img src="<?php echo $x_white_label_login_image ?>" alt="" /></div>
                  <?php endif ?>
              </div>
            </td>
          </tr>

          <tr>
            <th>
              <label for="x_white_label_retina_enabled">
                <strong><?php _e( 'Retina support for logo', '__x__' ); ?></strong>
                <span><?php _e( 'Enable retina support for logo. Size will be divided by 2 in non-retina devices.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text"><span>input type="checkbox"</span></legend>
                <input type="checkbox" class="checkbox" name="x_white_label_retina_enabled" id="x_white_label_retina_enabled" value="1" <?php checked( ! isset( $x_white_label_retina_enabled ) ? '0' : $x_white_label_retina_enabled, '1', true ); ?>>
              </fieldset>
            </td>
          </tr>

          <tr>
	          <th>
		          <label for="x_white_label_login_bg_image">
		          	<strong><?php _e( 'Login Background Image', '__x__' ); ?></strong>
		          	<span><?php _e( 'Enter the URL to an image that you would like to use as a background image on the WordPress login screen,', '__x__' ); ?></span>
		          </label>
	          </th>
	          <td>
              <input type="text" class="file large-text" name="x_white_label_login_bg_image" id="x_white_label_login_bg_image" value="<?php echo ( isset( $x_white_label_login_bg_image ) ) ? $x_white_label_login_bg_image : ''; ?>">
              <input type="button" id="_x_white_label_login_bg_image_image_upload_btn" data-id="x_white_label_login_bg_image" class="button-secondary x-upload-btn-wl" value="Upload Image">
              <div class="x-meta-box-img-thumb-wrap" id="_x_white_label_login_bg_image_thumb">
                  <?php if ( isset( $x_white_label_login_bg_image ) && ! empty( $x_white_label_login_bg_image ) ) : ?>
                     <div class="x-uploader-image"><img src="<?php echo $x_white_label_login_bg_image ?>" alt="" /></div>
                  <?php endif ?>
              </div>
            </td>
          </tr>

          <tr>
            <th>
              <label for="x_white_label_addons_home_heading">
                <strong><?php _e( 'Addons Home Heading', '__x__' ); ?></strong>
                <span><?php _e( 'Enter a heading for the box that will be output to the Addons Home page.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_white_label_addons_home_heading" id="x_white_label_addons_home_heading" type="text" value="<?php echo ( isset( $x_white_label_addons_home_heading ) ) ? $x_white_label_addons_home_heading : ''; ?>" class="large-text"></td>
          </tr>

          <tr>
            <th>
              <label for="x_white_label_addons_home_content">
                <strong><?php _e( 'Addons Home Content', '__x__' ); ?></strong>
                <span><?php _e( 'Enter some content for the box that will be output to the Addons Home page.', '__x__' ); ?></span>
              </label>
            </th>
            <td><textarea name="x_white_label_addons_home_content" id="x_white_label_addons_home_content" class="code"><?php echo ( isset( $x_white_label_addons_home_content ) ) ? esc_textarea( $x_white_label_addons_home_content ) : ''; ?></textarea>
            </td>
          </tr>

          <tr>
            <th>
              <label for="x_white_label_addons_home_position">
                <strong><?php _e( 'Addons Home Output Position', '__x__' ); ?></strong>
                <span><?php _e( 'Select where you would like the box to be positioned on the Addons Home page within the main content.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text"><span>input type="radio"</span></legend>
                <label class="radio-label"><input type="radio" class="radio" name="x_white_label_addons_home_position" value="x_addons_main_content_start" <?php echo ( isset( $x_white_label_addons_home_position ) && checked( $x_white_label_addons_home_position, 'x_addons_main_content_start', false ) ) ? checked( $x_white_label_addons_home_position, 'x_addons_main_content_start', false ) : 'checked="checked"'; ?>> <span><?php _e( 'Start', '__x__' ); ?></span></label><br>
                <label class="radio-label"><input type="radio" class="radio" name="x_white_label_addons_home_position" value="x_addons_main_content_end" <?php echo ( isset( $x_white_label_addons_home_position ) && checked( $x_white_label_addons_home_position, 'x_addons_main_content_end', false ) ) ? checked( $x_white_label_addons_home_position, 'x_addons_main_content_end', false ) : ''; ?>> <span><?php _e( 'End', '__x__' ); ?></span></label>
              </fieldset>
            </td>
          </tr>

        </table>
      </div>
    </div>

  </div>
</div>
