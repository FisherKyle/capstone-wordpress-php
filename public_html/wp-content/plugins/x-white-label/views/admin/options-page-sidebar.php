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
        <p class="cf"><input id="submit" class="button button-primary" type="submit" name="x_white_label_submit" value="Update"></p>
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

          <dt class="toggle"><?php _e( 'Addons Home Content', '__x__' ); ?></dt>
          <dd class="panel">
            <div class="panel-inner">
              <p><?php _e( 'Only a select list of HTML tags are allowed in <b>Addons Home Content</b>. The list of allowed tags with their corresponding allowed attributes are as follows:', '__x__' ); ?></p>
              <p><?php _e( '<b>&lt;div&gt;</b>, <b>&lt;p&gt;</b>, <b>&lt;h1&gt;</b>, <b>&lt;h2&gt;</b>, <b>&lt;h3&gt;</b>, <b>&lt;h4&gt;</b>, <b>&lt;h5&gt;</b>, <b>&lt;h6&gt;</b>, <b>&lt;a&gt;</b>, <b>&lt;img&gt;</b>, <b>&lt;span&gt;</b>, <b>&lt;em&gt;</b>, <b>&lt;strong&gt;</b>, and <b>&lt;style&gt;</b>', '__x__' ); ?></p>
              <p><?php _e( 'Out of these tags, all are allowed to use the <b>class</b> attribute. Additionally, <b>&lt;a&gt;</b> can use the <b>href</b> and <b>target</b> attributes and <b>&lt;img&gt;</b> can use the <b>src</b> attribute.', '__x__' ); ?></p>
            </div>
          </dd>

          <dt class="toggle"><?php _e( 'Support', '__x__' ); ?></dt>
          <dd class="panel">
            <div class="panel-inner">
              <p><?php _e( 'For questions, please visit our <a href="//theme.co/x/member/kb/extension-white-label/" target="_blank">Knowledge Base tutorial</a> for this plugin.', '__x__' ); ?></p>
            </div>
          </dd>

        </dl>
      </div>
    </div>

  </div>
</div>