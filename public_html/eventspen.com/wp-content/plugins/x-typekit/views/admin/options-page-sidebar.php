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
        <p class="cf"><input id="submit" class="button button-primary" type="submit" name="x_typekit_submit" value="Update"></p>
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

          <dt class="toggle"><?php _e( 'Kit ID', '__x__' ); ?></dt>
          <dd class="panel">
            <div class="panel-inner">
              <p><?php _e( 'Your collection of kits can be found <a href="https://typekit.com/account/kits" target="_blank">here</a> in your Typekit account. Your <b>Kit ID</b> can be found next to each of your kit names.', '__x__' ); ?></p>
              <p><?php _e( 'If you need guidance in setting up a kit, we recommend that you read through Typekit\'s documentation <a href="http://help.typekit.com/customer/en/portal/articles/6780-adding-fonts-to-your-website" target="_blank">here</a>.', '__x__' ); ?></p>
            </div>
          </dd>

          <dt class="toggle"><?php _e( 'Support', '__x__' ); ?></dt>
          <dd class="panel">
            <div class="panel-inner">
              <p><?php _e( 'For questions, please visit our <a href="//theme.co/x/member/kb/extension-typekit/" target="_blank">Knowledge Base tutorial</a> for this plugin.', '__x__' ); ?></p>
            </div>
          </dd>

        </dl>
      </div>
    </div>

  </div>
</div>