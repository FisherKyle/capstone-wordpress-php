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
      <h3 class="hndle"><span>Enable</span></h3>
      <div class="inside">
        <p><?php _e( 'Select the checkbox below to enable the plugin.', '__x__' ); ?></p>
        <table class="form-table">

          <tr>
            <th>
              <label for="x_custom_404_enable">
                <strong><?php _e( 'Enable Custom 404', '__x__' ); ?></strong>
                <span><?php _e( 'Select to enable the plugin and display options below.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text"><span>input type="checkbox"</span></legend>
                <input type="checkbox" class="checkbox" name="x_custom_404_enable" id="x_custom_404_enable" value="1" <?php echo ( isset( $x_custom_404_enable ) && checked( $x_custom_404_enable, '1', false ) ) ? checked( $x_custom_404_enable, '1', false ) : ''; ?>>
              </fieldset>
            </td>
          </tr>

        </table>
      </div>
    </div>

    <!--
    SETTINGS
    -->

    <div id="meta-box-settings" class="postbox" style="display: <?php echo ( isset( $x_custom_404_enable ) && $x_custom_404_enable == 1 ) ? 'block' : 'none'; ?>;">
      <div class="handlediv" title="<?php _e( 'Click to toggle', '__x__' ); ?>"><br></div>
      <h3 class="hndle"><span><?php _e( 'Settings', '__x__' ); ?></span></h3>
      <div class="inside">
        <p><?php _e( 'Select your plugin settings below.', '__x__' ); ?></p>
        <table class="form-table">

          <tr>
            <th>
              <label for="x_custom_404_entry_include">
                <strong><?php _e( '404 Page', '__x__' ); ?></strong>
                <span><?php _e( 'Select the page to be used in place of your site\'s standard 404 page.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <select name="x_custom_404_entry_include" id="x_custom_404_entry_include">
                <?php
                foreach ( $x_custom_404_list_entries_master as $key => $value ) {
                  if ( isset( $x_custom_404_entry_include ) && selected( $x_custom_404_entry_include, $key, false ) ) {
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

        </table>
      </div>
    </div>

  </div>
</div>