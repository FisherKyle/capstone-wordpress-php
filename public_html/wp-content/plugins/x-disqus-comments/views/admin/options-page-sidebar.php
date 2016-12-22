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
        <p class="cf"><input id="submit" class="button button-primary" type="submit" name="x_disqus_comments_submit" value="Update"></p>
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

          <dt class="toggle"><?php _e( 'Shortname', '__x__' ); ?></dt>
          <dd class="panel">
            <div class="panel-inner">
              <p><?php _e( 'Your Disqus <b>shortname</b> is a unique identifier assigned to your Disqus site. All the comments posted to a site are referenced with the shortname. The shortname tells Disqus to load only your site\'s comments, as well as the settings specified in your Disqus admin.', '__x__' ); ?></p>
              <p><?php _e( 'Your shortname is acquired by <a href="http://disqus.com/register" target="_blank">registering your site</a>, which will allow you to manage your comments and settings as well.', '__x__' ); ?></p>
            </div>
          </dd>

          <dt class="toggle"><?php _e( 'Support', '__x__' ); ?></dt>
          <dd class="panel">
            <div class="panel-inner">
              <p><?php _e( 'For questions, please visit our <a href="//theme.co/x/member/kb/extension-disqus-comments/" target="_blank">Knowledge Base tutorial</a> for this plugin.', '__x__' ); ?></p>
            </div>
          </dd>

        </dl>
      </div>
    </div>

  </div>
</div>