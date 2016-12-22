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
        <p class="cf"><input id="submit" class="button button-primary" type="submit" name="x_content_dock_submit" value="Update"></p>
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

          <dt class="toggle"><?php _e( 'Content Output', '__x__' ); ?></dt>
          <dd class="panel">
            <div class="panel-inner">
              <p><?php _e( 'Upon activating the Content Dock, a new widget area will be added to your WordPress admin under <b>Appearance</b> &gt; <b>Widgets</b> labeled <b>Content Dock</b>. This is where you will be able to add anything that you want output to your site.', '__x__' ); ?></p>
              <p><?php _e( 'You will most likely want to use a <b>Text</b> widget, which will allow you to take advantage of shortcodes.', '__x__' ); ?></p>
            </div>
          </dd>

          <dt class="toggle"><?php _e( 'Display (%)', '__x__' ); ?></dt>
          <dd class="panel">
            <div class="panel-inner">
              <p><?php _e( 'The <b>Display (%)</b> setting determines when the Content Dock will appear on the page and is set as a percentage of the page height.', '__x__' ); ?></p>
              <p><?php _e( 'For example, if you want your Content Dock to appear once a visitor scrolls halfway down the page then you would set the field to <strong>50</strong>.', '__x__' ); ?></p>
            </div>
          </dd>

          <dt class="toggle"><?php _e( 'Include', '__x__' ); ?></dt>
          <dd class="panel">
            <div class="panel-inner">
              <p><?php _e( 'To select multiple nonconsecutive pages or posts, hold down <b>CTRL</b> (Windows) or <b>⌘ CMD</b> (Mac), and then click each item you want to select. To cancel the selection of individual items, hold down <b>CTRL</b> or <b>⌘ CMD</b>, and then click the items that you don\'t want to include.', '__x__' ); ?></p>
            </div>
          </dd>

          <dt class="toggle"><?php _e( 'Support', '__x__' ); ?></dt>
          <dd class="panel">
            <div class="panel-inner">
              <p><?php _e( 'For questions, please visit our <a href="//theme.co/x/member/kb/extension-content-dock/" target="_blank">Knowledge Base tutorial</a> for this plugin.', '__x__' ); ?></p>
            </div>
          </dd>

        </dl>
      </div>
    </div>

  </div>
</div>