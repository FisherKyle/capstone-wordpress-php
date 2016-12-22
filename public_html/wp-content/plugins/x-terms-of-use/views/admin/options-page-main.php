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
              <label for="x_terms_of_use_enable">
                <strong><?php _e( 'Enable Terms of Use', '__x__' ); ?></strong>
                <span><?php _e( 'Select to enable the plugin and display options below.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text"><span>input type="checkbox"</span></legend>
                <input type="checkbox" class="checkbox" name="x_terms_of_use_enable" id="x_terms_of_use_enable" value="1" <?php echo ( isset( $x_terms_of_use_enable ) && checked( $x_terms_of_use_enable, '1', false ) ) ? checked( $x_terms_of_use_enable, '1', false ) : ''; ?>>
              </fieldset>
            </td>
          </tr>

        </table>
      </div>
    </div>

    <!--
    SETTINGS
    -->

    <div id="meta-box-settings" class="postbox" style="display: <?php echo ( isset( $x_terms_of_use_enable ) && $x_terms_of_use_enable == 1 ) ? 'block' : 'none'; ?>;">
      <div class="handlediv" title="<?php _e( 'Click to toggle', '__x__' ); ?>"><br></div>
      <h3 class="hndle"><span><?php _e( 'Settings', '__x__' ); ?></span></h3>
      <div class="inside">
        <p><?php _e( 'Select your plugin settings below.', '__x__' ); ?></p>
        <table class="form-table">

          <tr>
            <th>
              <label for="x_terms_of_use_entry_include">
                <strong><?php _e( 'Terms of Use Page', '__x__' ); ?></strong>
                <span><?php _e( 'Select the page where your site\'s terms of use are located.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <select name="x_terms_of_use_entry_include" id="x_terms_of_use_entry_include">
                <?php
                foreach ( $x_terms_of_use_list_entries_master as $key => $value ) {
                  if ( isset( $x_terms_of_use_entry_include ) && selected( $x_terms_of_use_entry_include, $key, false ) ) {
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