<div class="wrap about-wrap about-cp bend">
  <div class="wrap-container">
    <div class="bend-heading-section cp-about-header">
      <h1><?php _e( "ConvertPlug &mdash; Knowledge Base", "smile" ); ?></h1>
      <h3><?php _e( " We are here to help you solve all your doubts, queries and issues you might face while using ConvertPlug. In case of a problem, you can peep into our knowledge base and find a quick solution for it.", "smile" ); ?></h3>
      <div class="bend-head-logo">
        <div class="bend-product-ver">
          <?php _e( "Version", "smile" ); echo ' '.CP_VERSION; ?>
        </div>
      </div>
    </div><!-- bend-heading section -->

    <div class="bend-content-wrap">
      <div class="smile-settings-wrapper">
        <h2 class="nav-tab-wrapper">
            <a class="nav-tab" href="?page=convertplug" title="<?php _e( "About", "smile"); ?>"><?php echo __("About", "smile" ); ?></a>
            <a class="nav-tab" href="?page=convertplug&view=modules" title="<?php _e( "Modules", "smile" ); ?>"><?php echo __( "Modules", "smile" ); ?></a>

            <a class="nav-tab" href="?page=convertplug&view=registration" title="<?php _e( "Registration", "smile"); ?>"><?php echo __("Registration", "smile" ); ?></a>

            <a class="nav-tab nav-tab-active" href="?page=convertplug&view=knowledge_base" title="<?php _e( "knowledge Base", "smile"); ?>"><?php echo __("Knowledge Base", "smile" ); ?></a>

            <?php if( isset( $_GET['author'] ) ){ ?>
            <a class="nav-tab" href="?page=convertplug&view=debug&author=true" title="<?php _e( "Debug", "smile" ); ?>"><?php echo __( "Debug", "smile" ); ?></a>
            <?php } ?>

        </h2>
      </div><!-- smile-settings-wrapper -->
      </hr>
      <div class="container" style="padding: 50px 0 0 0;">
        <div class="col-md-12 text-center" style="overflow:hidden;">
              <a style="max-width:330px;" class="button-primary cp-started-footer-button" href="https://docs.brainstormforce.com/category/convertplug/" target="_blank">Click Here For Knowledge Base</a>
          </div>
      </div><!-- container -->

    </div><!-- bend-content-wrap -->
  </div><!-- .wrap-container -->
</div><!-- .bend -->
