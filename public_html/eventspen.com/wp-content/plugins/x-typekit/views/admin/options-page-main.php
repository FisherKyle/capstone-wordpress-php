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
              <label for="x_typekit_enable">
                <strong><?php _e( 'Enable Typekit', '__x__' ); ?></strong>
                <span><?php _e( 'Select to enable the plugin and display options below.', '__x__' ); ?></span>
              </label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text"><span>input type="checkbox"</span></legend>
                <input type="checkbox" class="checkbox" name="x_typekit_enable" id="x_typekit_enable" value="1" <?php echo ( isset( $x_typekit_enable ) && checked( $x_typekit_enable, '1', false ) ) ? checked( $x_typekit_enable, '1', false ) : ''; ?>>
              </fieldset>
            </td>
          </tr>

        </table>
      </div>
    </div>

    <!--
    SETTINGS
    -->

    <div id="meta-box-settings" class="postbox" style="display: <?php echo ( isset( $x_typekit_enable ) && $x_typekit_enable == 1 ) ? 'block' : 'none'; ?>;">
      <div class="handlediv" title="<?php _e( 'Click to toggle', '__x__' ); ?>"><br></div>
      <h3 class="hndle"><span><?php _e( 'Settings', '__x__' ); ?></span></h3>
      <div class="inside">
        <p><?php _e( 'Select your plugin settings below.', '__x__' ); ?></p>
        <table class="form-table">

          <tr>
            <th>
              <label for="x_typekit_kit_id">
                <strong><?php _e( 'Kit ID', '__x__' ); ?></strong>
                <span><?php _e( 'Enter in the ID for your kit here. Only published data is accessible, so make sure that any changes you make to your kit are updated. Once published, your Typekit fonts will show up in the Customizer. If the ID you\'ve entered is invalid, you will not see any kit information below.', '__x__' ); ?></span>
              </label>
            </th>
            <td><input name="x_typekit_kit_id" id="x_typekit_kit_id" type="text" value="<?php echo ( isset( $x_typekit_kit_id ) ) ? $x_typekit_kit_id : ''; ?>" class="large-text"></td>
          </tr>

          <?php if ( ! empty( $x_typekit_request ) ) : ?>

            <tr>
              <th>
                <label for="x_typekit_kit_information">
                  <strong><?php _e( 'Kit Information', '__x__' ); ?></strong>
                  <span><?php _e( 'Only published information is displayed here, so make sure that you have published your kit on Typekit\'s website. If you alter your kit, remember to refresh this form (or save the plugin settings again) so that your updated information will be available.', '__x__' ); ?></span>
                </label>
              </th>
              <td>
                <table class="info-table">
                  <tr>
                    <th><?php _e( 'Fonts', '__x__' ); ?></th>
                    <th><?php _e( 'Weights', '__x__' ); ?></th>
                  </tr>

                  <?php

                  foreach ( $x_typekit_request as $font ) :

                    echo '<tr>';
                      echo '<td>' . $font['family'] . '</td>';
                      echo '<td>';

                      foreach ( $font['weights'] as $weight ) :

                        echo str_replace( 'italic', ' Italic', $weight );

                        if ( $weight != end( $font['weights'] ) ) {
                          echo ', ';
                        }

                      endforeach;

                      echo '</td>';
                    echo '</tr>';

                  endforeach;

                  ?>

                </table>
                <br>
                <button id="refresh" class="button"><?php _e( 'Refresh', '__x__' ); ?></button>
              </td>
            </tr>

          <?php endif; ?>

        </table>
      </div>
    </div>

  </div>
</div>