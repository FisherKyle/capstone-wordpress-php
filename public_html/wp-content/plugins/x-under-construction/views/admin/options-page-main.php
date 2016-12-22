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
              <label for="x_under_construction_enable">
                <strong><?php _e( 'Enable Under Construction', '__x__' ); ?></strong>
                <span><?php _e( 'Select to enable the plugin and display options below.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text"><span>input type="checkbox"</span></legend>
                <input type="checkbox" class="checkbox" name="x_under_construction_enable" id="x_under_construction_enable" value="1" <?php echo ( isset( $x_under_construction_enable ) && checked( $x_under_construction_enable, '1', false ) ) ? checked( $x_under_construction_enable, '1', false ) : ''; ?>>
              </fieldset>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <!--
    SETTINGS
    -->

    <div id="meta-box-settings" class="postbox" style="display: <?php echo ( isset( $x_under_construction_enable ) && $x_under_construction_enable == 1 ) ? 'block' : 'none'; ?>;">
      <div class="handlediv" title="<?php _e( 'Click to toggle', '__x__' ); ?>"><br></div>
      <h3 class="hndle"><span><?php _e( 'Settings', '__x__' ); ?></span></h3>
      <div class="inside">
        <p><?php _e( 'Select your plugin settings below.', '__x__' ); ?></p>
        <table class="form-table">

          <tr>
            <th>
              <label for="x_under_construction_heading">
                <strong><?php _e( 'Heading', '__x__' ); ?></strong>
                <span><?php _e( 'Enter your desired heading.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_under_construction_heading" id="x_under_construction_heading" type="text" value="<?php echo ( isset( $x_under_construction_heading ) ) ? stripslashes( $x_under_construction_heading ) : ''; ?>" class="large-text"></td>
          </tr>

          <tr>
            <th>
              <label for="x_under_construction_subheading">
                <strong><?php _e( 'Subheading', '__x__' ); ?></strong>
                <span><?php _e( 'Enter your desired subheading.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_under_construction_subheading" id="x_under_construction_subheading" type="text" value="<?php echo ( isset( $x_under_construction_subheading ) ) ? stripslashes( $x_under_construction_subheading ) : ''; ?>" class="large-text"></td>
          </tr>

          <tr>
            <th>
              <label for="x_under_construction_date">
                <strong><?php _e( 'Completed By', '__x__' ); ?></strong>
                <span><?php _e( 'Set the date when maintenance is expected to be complete.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_under_construction_date" id="x_under_construction_date" type="text" value="<?php echo ( isset( $x_under_construction_date ) ) ? $x_under_construction_date : ''; ?>" class="large-text datepicker"></td>
          </tr>

          <tr>
            <th>
              <label for="x_under_construction_facebook">
                <strong><?php _e( 'Facebook Profile', '__x__' ); ?></strong>
                <span><?php _e( 'Enter the URL to your Facebook profile.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_under_construction_facebook" id="x_under_construction_facebook" type="text" value="<?php echo ( isset( $x_under_construction_facebook ) ) ? $x_under_construction_facebook : ''; ?>" class="large-text"></td>
          </tr>

          <tr>
            <th>
              <label for="x_under_construction_twitter">
                <strong><?php _e( 'Twitter Profile', '__x__' ); ?></strong>
                <span><?php _e( 'Enter the URL to your Twitter profile.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_under_construction_twitter" id="x_under_construction_twitter" type="text" value="<?php echo ( isset( $x_under_construction_twitter ) ) ? $x_under_construction_twitter : ''; ?>" class="large-text"></td>
          </tr>

          <tr>
            <th>
              <label for="x_under_construction_google_plus">
                <strong><?php _e( 'Google+ Profile', '__x__' ); ?></strong>
                <span><?php _e( 'Enter the URL to your Google+ profile.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_under_construction_google_plus" id="x_under_construction_google_plus" type="text" value="<?php echo ( isset( $x_under_construction_google_plus ) ) ? $x_under_construction_google_plus : ''; ?>" class="large-text"></td>
          </tr>

          <tr>
            <th>
              <label for="x_under_construction_instagram">
                <strong><?php _e( 'Instagram Profile', '__x__' ); ?></strong>
                <span><?php _e( 'Enter the URL to your Instagram profile.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_under_construction_instagram" id="x_under_construction_instagram" type="text" value="<?php echo ( isset( $x_under_construction_instagram ) ) ? $x_under_construction_instagram : ''; ?>" class="large-text"></td>
          </tr>

          <tr>
            <th>
              <label for="x_under_construction_background_image">
                <strong><?php _e( 'Background Image', '__x__' ); ?></strong>
                <span><?php _e( 'Optionally set a background image.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_under_construction_background_image" id="x_under_construction_background_image" type="text" value="<?php echo ( isset( $x_under_construction_background_image ) ) ? $x_under_construction_background_image : ''; ?>" class="large-text"></td>
          </tr>
          <tr>
            <th>
              <label for="x_under_construction_background_color">
                <strong><?php _e( 'Background', '__x__' ); ?></strong>
                <span><?php _e( 'Select your color.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_under_construction_background_color" id="x_under_construction_background_color" type="text" value="<?php echo ( isset( $x_under_construction_background_color ) ) ? $x_under_construction_background_color : '#34495e'; ?>" class="wp-color-picker" data-default-color="#34495e"></td>
          </tr>

          <tr>
            <th>
              <label for="x_under_construction_heading_color">
                <strong><?php _e( 'Headings', '__x__' ); ?></strong>
                <span><?php _e( 'Select your color.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_under_construction_heading_color" id="x_under_construction_heading_color" type="text" value="<?php echo ( isset( $x_under_construction_heading_color ) ) ? $x_under_construction_heading_color : '#ffffff'; ?>" class="wp-color-picker" data-default-color="#ffffff"></td>
          </tr>

          <tr>
            <th>
              <label for="x_under_construction_subheading_color">
                <strong><?php _e( 'Subheading', '__x__' ); ?></strong>
                <span><?php _e( 'Select your color.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_under_construction_subheading_color" id="x_under_construction_subheading_color" type="text" value="<?php echo ( isset( $x_under_construction_subheading_color ) ) ? $x_under_construction_subheading_color : '#ffffff'; ?>" class="wp-color-picker" data-default-color="#ffffff"></td>
          </tr>

          <tr>
            <th>
              <label for="x_under_construction_date_color">
                <strong><?php _e( 'Completed By', '__x__' ); ?></strong>
                <span><?php _e( 'Select your color.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_under_construction_date_color" id="x_under_construction_date_color" type="text" value="<?php echo ( isset( $x_under_construction_date_color ) ) ? $x_under_construction_date_color : '#ffffff'; ?>" class="wp-color-picker" data-default-color="#ffffff"></td>
          </tr>

          <tr>
            <th>
              <label for="x_under_construction_social_color">
                <strong><?php _e( 'Social Profile Links', '__x__' ); ?></strong>
                <span><?php _e( 'Select your color.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_under_construction_social_color" id="x_under_construction_social_color" type="text" value="<?php echo ( isset( $x_under_construction_social_color ) ) ? $x_under_construction_social_color : '#ffffff'; ?>" class="wp-color-picker" data-default-color="#ffffff"></td>
          </tr>

        </table>
      </div>
    </div>

  </div>
</div>