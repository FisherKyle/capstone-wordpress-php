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
              <label for="x_content_dock_enable">
                <strong><?php _e( 'Enable Content Dock', '__x__' ); ?></strong>
                <span><?php _e( 'Select to enable the plugin and display options below.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text"><span>input type="checkbox"</span></legend>
                <input type="checkbox" class="checkbox" name="x_content_dock_enable" id="x_content_dock_enable" value="1" <?php echo ( isset( $x_content_dock_enable ) && checked( $x_content_dock_enable, '1', false ) ) ? checked( $x_content_dock_enable, '1', false ) : ''; ?>>
              </fieldset>
            </td>
          </tr>

        </table>
      </div>
    </div>

    <!--
    SETTINGS
    -->

    <div id="meta-box-settings" class="postbox" style="display: <?php echo ( isset( $x_content_dock_enable ) && $x_content_dock_enable == 1 ) ? 'block' : 'none'; ?>;">
      <div class="handlediv" title="<?php _e( 'Click to toggle', '__x__' ); ?>"><br></div>
      <h3 class="hndle"><span><?php _e( 'Settings', '__x__' ); ?></span></h3>
      <div class="inside">
        <p><?php _e( 'Select your plugin settings below.', '__x__' ); ?></p>
        <table class="form-table">

          <tr>
            <th>
              <label for="x_content_dock_position">
                <strong><?php _e( 'Position', '__x__' ); ?></strong>
                <span><?php _e( 'Which side of the screen you want the Content Dock to appear.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text"><span>input type="radio"</span></legend>
                <label class="radio-label"><input type="radio" class="radio" name="x_content_dock_position" value="left" <?php echo ( isset( $x_content_dock_position ) && checked( $x_content_dock_position, 'left', false ) ) ? checked( $x_content_dock_position, 'left', false ) : 'checked="checked"'; ?>> <span><?php _e( 'Left', '__x__' ); ?></span></label><br>
                <label class="radio-label"><input type="radio" class="radio" name="x_content_dock_position" value="right" <?php echo ( isset( $x_content_dock_position ) && checked( $x_content_dock_position, 'right', false ) ) ? checked( $x_content_dock_position, 'right', false ) : ''; ?>> <span><?php _e( 'Right', '__x__' ); ?></span></label>
              </fieldset>
            </td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_width">
                <strong><?php _e( 'Width (px)', '__x__' ); ?></strong>
                <span><?php _e( 'Valid inputs are between 250 and 450 in increments of 10.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_content_dock_width" id="x_content_dock_width" type="number" step="10" min="250" max="450" value="<?php echo ( isset( $x_content_dock_width ) ) ? $x_content_dock_width : 350; ?>" class="small-text"></td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_display">
                <strong><?php _e( 'Display (%)', '__x__' ); ?></strong>
                <span><?php _e( 'Valid inputs are between 5 and 95 in increments of 5.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_content_dock_display" id="x_content_dock_display" type="number" step="5" min="5" max="95" value="<?php echo ( isset( $x_content_dock_display ) ) ? $x_content_dock_display : 50; ?>" class="small-text"></td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_trigger_timeout">
                <strong><?php _e( 'Auto trigger timeout (%)', '__x__' ); ?></strong>
                <span><?php _e( 'Display content dock after "n" seconds if user doesn\'t reach the bottom of the page. "0" means disable this feature.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_content_dock_trigger_timeout" id="x_content_dock_trigger_timeout" type="number" step="1" min="0" max="120" value="<?php echo ( isset( $x_content_dock_trigger_timeout ) ) ? $x_content_dock_trigger_timeout : 0; ?>" class="small-text"> seconds</td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_cookie_timeout">
                <strong><?php _e( '"Do not show again" cookie timeout (%)', '__x__' ); ?></strong>
                <span><?php _e( 'How many days content dock will not appear for the user if he checks "Do not show again" checkbox. "0" means disable this feature.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_content_dock_cookie_timeout" id="x_content_dock_cookie_timeout" type="number" step="1" min="0" max="360" value="<?php echo ( isset( $x_content_dock_cookie_timeout ) ) ? $x_content_dock_cookie_timeout : 0; ?>" class="small-text"> days</td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_all_pages_active">
                <strong><?php _e( 'Active for all pages', '__x__' ); ?></strong>
                <span><?php _e( 'Activate for all pages (including created on the future) without to add one by one to the list below.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text"><span>input type="checkbox"</span></legend>
                <input type="checkbox" class="checkbox" name="x_content_dock_all_pages_active" id="x_content_dock_all_pages_active" value="1" <?php echo ( isset( $x_content_dock_all_pages_active ) && checked( $x_content_dock_all_pages_active, '1', false ) ) ? checked( $x_content_dock_all_pages_active, '1', false ) : ''; ?>>
              </fieldset>
            </td>
          </tr>

          <tr id="x_content_dock_entries_include_row" style="display:none">
            <th>
              <label for="x_content_dock_entries_include">
                <strong><?php _e( 'Include Pages', '__x__' ); ?></strong>
                <span><?php _e( 'Select the pages or posts that you want the Content Dock to appear on.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <select name="x_content_dock_entries_include[]" id="x_content_dock_entries_include" multiple="multiple">
                <?php
                foreach ( $x_content_dock_list_entries_master as $key => $value ) {
                  if ( in_array( $key, $x_content_dock_entries_include ) ) {
                    $selected = ' selected="selected"';
                  } else {
                    $selected = '';
                  }
                  echo '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
                }
                ?>
              </select>
            </td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_all_posts_active">
                <strong><?php _e( 'Active for all posts', '__x__' ); ?></strong>
                <span><?php _e( 'Activate for all posts (including created on the future) without to add one by one to the list below.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text"><span>input type="checkbox"</span></legend>
                <input type="checkbox" class="checkbox" name="x_content_dock_all_posts_active" id="x_content_dock_all_posts_active" value="1" <?php echo ( isset( $x_content_dock_all_posts_active ) && checked( $x_content_dock_all_posts_active, '1', false ) ) ? checked( $x_content_dock_all_posts_active, '1', false ) : ''; ?>>
              </fieldset>
            </td>
          </tr>

          <tr id="x_content_dock_posts_include_row" style="display:none">
            <th>
              <label for="x_content_dock_posts_include">
                <strong><?php _e( 'Include Posts', '__x__' ); ?></strong>
                <span><?php _e( 'Select the posts that you want the Content Dock to appear on.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <select name="x_content_dock_posts_include[]" id="x_content_dock_posts_include" multiple="multiple">
                <?php
                foreach ( $x_content_dock_list_post_entries_master as $key => $value ) {
                  if ( in_array( $key, $x_content_dock_posts_include ) ) {
                    $selected = ' selected="selected"';
                  } else {
                    $selected = '';
                  }
                  echo '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
                }
                ?>
              </select>
            </td>
          </tr>

          <?php if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) : ?>

            <tr>
              <th>
                <label for="x_content_dock_all_woo_products_active">
                  <strong><?php _e( 'Active for all products', '__x__' ); ?></strong>
                  <span><?php _e( 'Activate for all products (including created on the future) without to add one by one to the list below.', '__x__' ); ?></span>
                </label>
              </th>
              <td>
                <fieldset>
                  <legend class="screen-reader-text"><span>input type="checkbox"</span></legend>
                  <input type="checkbox" class="checkbox" name="x_content_dock_all_woo_products_active" id="x_content_dock_all_woo_products_active" value="1" <?php echo ( isset( $x_content_dock_all_woo_products_active ) && checked( $x_content_dock_all_woo_products_active, '1', false ) ) ? checked( $x_content_dock_all_woo_products_active, '1', false ) : ''; ?>>
                </fieldset>
              </td>
            </tr>

            <tr id="x_content_dock_woo_products_include_row" style="display:none">
              <th>
                <label for="x_content_dock_woo_products_include">
                  <strong><?php _e( 'Include WooCommerce Products', '__x__' ); ?></strong>
                  <span><?php _e( 'Select the products that you want the Content Dock to appear on.', '__x__' ); ?></span>
                </label>
              </th>
              <td>
                <select name="x_content_dock_woo_products_include[]" id="x_content_dock_woo_products_include" multiple="multiple">
                  <?php
                  foreach ( $x_content_dock_list_woo_products_master as $key => $value ) {
                    if ( in_array( $key, $x_content_dock_woo_products_include ) ) {
                      $selected = ' selected="selected"';
                    } else {
                      $selected = '';
                    }
                    echo '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
                  }
                  ?>
                </select>
              </td>
            </tr>

          <?php endif; ?>

          <tr>
            <th>
              <label for="x_content_dock_image_override_enable">
                <strong><?php _e( 'Use an override image and URL', '__x__' ); ?></strong>
                <span><?php _e( 'If enabled an image will override content dock with URL as link.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text"><span>input type="checkbox"</span></legend>
                <input type="checkbox" class="checkbox" name="x_content_dock_image_override_enable" id="x_content_dock_image_override_enable" value="1" <?php echo ( isset( $x_content_dock_image_override_enable ) && checked( $x_content_dock_image_override_enable, '1', false ) ) ? checked( $x_content_dock_image_override_enable, '1', false ) : ''; ?>>
              </fieldset>
            </td>
          </tr>

          <tr class="x_content_dock_image_override_image_row" style="display:none">
            <th>
              <label for="x_content_dock_image_override_image">
                <strong><?php _e( 'Override image', '__x__' ); ?></strong>
                <span><?php _e( 'Image to override content dock.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <input type="text" class="file large-text" name="x_content_dock_image_override_image" id="x_content_dock_image_override_image" value="<?php echo ( isset( $x_content_dock_image_override_image ) ) ? $x_content_dock_image_override_image : ''; ?>">
              <input type="button" id="_x_content_dock_image_override_image_image_upload_btn" data-id="x_content_dock_image_override_image" class="button-secondary x-upload-btn-cd" value="Upload Image">
              <div class="x-meta-box-img-thumb-wrap" id="_x_content_dock_image_override_image_thumb">
                  <?php if ( isset( $x_content_dock_image_override_image ) && ! empty( $x_content_dock_image_override_image ) ) : ?>
                     <div class="x-uploader-image"><img src="<?php echo $x_content_dock_image_override_image ?>" alt="" /></div>
                  <?php endif ?>
              </div>
            </td>
          </tr>

          <tr class="x_content_dock_image_override_image_row" style="display:none">
            <th>
              <label for="x_content_dock_image_override_url">
                <strong><?php _e( 'Override URL', '__x__' ); ?></strong>
                <span><?php _e( 'URL for the link to override content dock.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_content_dock_image_override_url" id="x_content_dock_image_override_url" type="text" value="<?php echo ( isset( $x_content_dock_image_override_url ) ) ? $x_content_dock_image_override_url : ''; ?>" class="large-text"></td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_text_color">
                <strong><?php _e( 'Text', '__x__' ); ?></strong>
                <span><?php _e( 'Select your color.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_content_dock_text_color" id="x_content_dock_text_color" type="text" value="<?php echo ( isset( $x_content_dock_text_color ) ) ? $x_content_dock_text_color : '#b5b5b5'; ?>" class="wp-color-picker" data-default-color="#b5b5b5"></td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_headings_color">
                <strong><?php _e( 'Headings', '__x__' ); ?></strong>
                <span><?php _e( 'Select your color.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_content_dock_headings_color" id="x_content_dock_headings_color" type="text" value="<?php echo ( isset( $x_content_dock_headings_color ) ) ? $x_content_dock_headings_color : '#272727'; ?>" class="wp-color-picker" data-default-color="#272727"></td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_link_color">
                <strong><?php _e( 'Link', '__x__' ); ?></strong>
                <span><?php _e( 'Select your color.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_content_dock_link_color" id="x_content_dock_link_color" type="text" value="<?php echo ( isset( $x_content_dock_link_color ) ) ? $x_content_dock_link_color : '#428bca'; ?>" class="wp-color-picker" data-default-color="#428bca"></td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_link_hover_color">
                <strong><?php _e( 'Link Hover', '__x__' ); ?></strong>
                <span><?php _e( 'Select your color.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_content_dock_link_hover_color" id="x_content_dock_link_hover_color" type="text" value="<?php echo ( isset( $x_content_dock_link_hover_color ) ) ? $x_content_dock_link_hover_color : '#2a6496'; ?>" class="wp-color-picker" data-default-color="#2a6496"></td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_close_button_color">
                <strong><?php _e( 'Close Button', '__x__' ); ?></strong>
                <span><?php _e( 'Select your color.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_content_dock_close_button_color" id="x_content_dock_close_button_color" type="text" value="<?php echo ( isset( $x_content_dock_close_button_color ) ) ? $x_content_dock_close_button_color : '#d9d9d9'; ?>" class="wp-color-picker" data-default-color="#d9d9d9"></td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_close_button_hover_color">
                <strong><?php _e( 'Close Button Hover', '__x__' ); ?></strong>
                <span><?php _e( 'Select your color.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_content_dock_close_button_hover_color" id="x_content_dock_close_button_hover_color" type="text" value="<?php echo ( isset( $x_content_dock_close_button_hover_color ) ) ? $x_content_dock_close_button_hover_color : '#428bca'; ?>" class="wp-color-picker" data-default-color="#428bca"></td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_border_color">
                <strong><?php _e( 'Border', '__x__' ); ?></strong>
                <span><?php _e( 'Select your color.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_content_dock_border_color" id="x_content_dock_border_color" type="text" value="<?php echo ( isset( $x_content_dock_border_color ) ) ? $x_content_dock_border_color : '#e5e5e5'; ?>" class="wp-color-picker" data-default-color="#e5e5e5"></td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_background_color">
                <strong><?php _e( 'Background', '__x__' ); ?></strong>
                <span><?php _e( 'Select your color.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_content_dock_background_color" id="x_content_dock_background_color" type="text" value="<?php echo ( isset( $x_content_dock_background_color ) ) ? $x_content_dock_background_color : '#ffffff'; ?>" class="wp-color-picker" data-default-color="#ffffff"></td>
          </tr>

          <tr>
            <th>
              <label for="x_content_dock_box_shadow">
                <strong><?php _e( 'Box Shadow', '__x__' ); ?></strong>
                <span><?php _e( 'Select to enable a shadow around the Content Dock.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text"><span>input type="checkbox"</span></legend>
                <input type="checkbox" class="checkbox" name="x_content_dock_box_shadow" id="x_content_dock_box_shadow" value="1" <?php echo ( isset( $x_content_dock_box_shadow ) && checked( $x_content_dock_box_shadow, '1', false ) ) ? checked( $x_content_dock_box_shadow, '1', false ) : ''; ?>>
              </fieldset>
            </td>
          </tr>

        </table>
      </div>
    </div>

  </div>
</div>
