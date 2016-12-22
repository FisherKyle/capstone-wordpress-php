<div class="wrap about-wrap about-cp bend">
  <div class="wrap-container">
    <div class="bend-heading-section cp-about-header">
      <h1><?php _e( "ConvertPlug &mdash; Registration", "smile" ); ?></h1>
      <h3><?php _e( "When you register your copy of ConvertPlug, instant access to our support portal, one click updates, extensions and many other addon freebies will be unlocked to you. It is a one time process and will take less than a minute!.", "smile" ); ?></h3>
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

            <!-- <a class="nav-tab" href="?page=convertplug&view=cp_import" title="<?php _e( "Import", "smile" ); ?>"><?php echo __( "Import", "smile" ); ?></a> -->

            <a class="nav-tab nav-tab-active" href="?page=convertplug&view=registration" title="<?php _e( "Registration", "smile"); ?>"><?php echo __("Registration", "smile" ); ?></a>

            <a class="nav-tab" href="?page=convertplug&view=knowledge_base" title="<?php _e( "knowledge Base", "smile"); ?>"><?php echo __("Knowledge Base", "smile" ); ?></a>

            <?php if( isset( $_GET['author'] ) ){ ?>
            <a class="nav-tab" href="?page=convertplug&view=debug&author=true" title="<?php _e( "Debug", "smile" ); ?>"><?php echo __( "Debug", "smile" ); ?></a>
            <?php } ?>

        </h2>
      </div><!-- smile-settings-wrapper -->

      </hr>

      <div class="container cp-welcome-content">
        <div class="col-md-4">
          <div class="cp-wrap-content">
            <div class="cp-wrap-left-digit"> <span class="bsf-numbers-uni31"></span></div>
            <div class="cp-wrap-right-content">
              <h3><?php _e( 'Register', "smile" ); ?></h3>
            </div>
            <div class="cp-wrap-bottom-content">
              <p class="cp-wrap-discription"><?php _e( "Register with your email address which instantly creates your account on our support portal.", "smile" ); ?></p>
            </div>
          </div><!--cp wrap content-->
        </div><!--col-md-4-->
        <div class="col-md-4">
          <div class="cp-wrap-content">
            <div class="cp-wrap-left-digit"> <span class="bsf-numbers-uni32"></span></div>
            <div class="cp-wrap-right-content">
              <h3><?php _e( 'Validate Purchase', "smile" ); ?></h3>
            </div><!--cp-wrap-right-content-->
            <div class="cp-wrap-bottom-content">
              <p class="cp-wrap-discription"><?php _e( "Once registered, enter your purchase code and validate your license to get eligible for one click updates.", "smile" ); ?></p>
            </div>
          </div><!--cp wrap content-->
        </div><!--col-md-4-->
        <div class="col-md-4">
          <div class="cp-wrap-content">
            <div class="cp-wrap-left-digit"><span class="bsf-numbers-uni33"></span></div>
            <div class="cp-wrap-right-content">
              <h3><?php _e( "That's All!", "smile" ); ?></h3>
            </div>
            <div class="cp-wrap-bottom-content">
              <p class="cp-wrap-discription"><?php _e( "You will have access to our support portal, one click updates, extensions and many other addon freebies.", "smile" ); ?></p>
            </div>
          </div><!--cp wrap content-->
        </div><!--col-md-4-->
      </div><!-- cp-welcome-content -->

      <hr class="bsf-extensions-lists-separator" style="margin: 22px 0px 30px 0px;"></hr>

      <div class="container cp-welcome-bottom-content">
        <div class="col-md-4 col-md-offset-4">
          <a target="_blank" class="button-primary cp-welcome-started" href="<?php get_admin_url() ?>index.php?page=bsf-registration"><?php _e( "LET'S GET STARTED", "smile" ); ?></a>
        </div>
      </div><!-- cp-welcome-bottom-content -->

      <div class="container">
        <div class="col-md-12 text-center" style="margin-bottom: 50px;">
          <?php _e( "Thank you for choosing ConvertPlug. We are thrilled and committed to make your WordPress experience better.", "smile" ); ?>
        </div>
      </div><!-- container -->

    </div><!-- bend-content-wrap -->
  </div><!-- .wrap-container -->
</div><!-- .bend -->
