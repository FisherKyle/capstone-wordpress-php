<?php

// =============================================================================
// VIEWS/ADMIN/OPTIONS-PAGE-SIDEBAR.PHP
// -----------------------------------------------------------------------------
// Plugin options page sidebar.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Sidebar
// =============================================================================

// Sidebar
// =============================================================================

?>

<div id="postbox-container-1" class="postbox-container">
  <div class="meta-box-sortables">

    <!--
    SAVE
    -->

    <div class="postbox">
      <div class="handlediv" title="<?php _e( 'Click to toggle', '__x__' ); ?>"><br></div>
      <h3 class="hndle"><span><?php _e( 'Save', '__x__' ); ?></span></h3>
      <div class="inside">
        <p><?php _e( 'Once you are satisfied with your settings, click the button below to save them.', '__x__' ); ?></p>
        <p class="cf"><input id="submit" class="button button-primary" type="submit" name="x_custom_404_submit" value="Update"></p>
      </div>
    </div>

    <!--
    ABOUT
    -->

    <div class="postbox">
      <div class="handlediv" title="<?php _e( 'Click to toggle', '__x__' ); ?>"><br></div>
      <h3 class="hndle"><span><?php _e( 'About', '__x__' ); ?></span></h3>
      <div class="inside">
        <dl class="accordion">

          <dt class="toggle"><?php _e( '404 Page', '__x__' ); ?></dt>
          <dd class="panel">
            <div class="panel-inner">
              <p><?php _e( 'The page selected for this option will be used in place of your site\'s standard 404 page.', '__x__' ); ?></p>
            </div>
          </dd>

          <dt class="toggle"><?php _e( 'Support', '__x__' ); ?></dt>
          <dd class="panel">
            <div class="panel-inner">
              <p><?php _e( 'For questions, please visit our <a href="//theme.co/x/member/kb/extension-custom-404/" target="_blank">Knowledge Base tutorial</a> for this plugin.', '__x__' ); ?></p>
            </div>
          </dd>

        </dl>
      </div>
    </div>

  </div>
</div>