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
              <label for="x_content_dock_entries_include">
                <strong><?php _e( 'Include', '__x__' ); ?></strong>
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