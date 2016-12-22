<?php
  $is_cp_status = ( function_exists( "bsf_product_status" ) ) ? bsf_product_status('14058953') : '';
  $reg_menu_hide = ( (defined( 'BSF_UNREG_MENU' ) && ( BSF_UNREG_MENU === true || BSF_UNREG_MENU === 'true' )) ||
  (defined( 'BSF_REMOVE_14058953_FROM_REGISTRATION' ) && ( BSF_REMOVE_14058953_FROM_REGISTRATION === true || BSF_REMOVE_14058953_FROM_REGISTRATION === 'true' )) ) ? true : false;
  if($reg_menu_hide !== true) {
    if($is_cp_status)
      $reg_menu_hide = true;
  }
?>
<div class="wrap about-wrap about-cp bend">
  <div class="wrap-container">
    <div class="bend-heading-section cp-about-header">
      <h1><?php _e( "Welcome to ConvertPlug!", "smile" ); ?></h1>
      <h3><?php _e( "Welcome to ConvertPlug - the easiest WordPress plugin to convert website traffic into leads. ConvertPlug will help you build email lists, drive traffic, promote videos, offer coupons and much more!", "smile" ); ?></h3>
      <div class="bend-head-logo">
        <div class="bend-product-ver">
          <?php _e( "Version", "smile" ); echo ' '.CP_VERSION; ?>
        </div>
      </div>
    </div><!-- bend-heading section -->

    <div class="bend-content-wrap">
      <div class="smile-settings-wrapper">
        <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active" href="?page=convertplug" title="<?php _e( "About", "smile"); ?>"><?php echo __("About", "smile" ); ?></a>
            <a class="nav-tab" href="?page=convertplug&view=modules" title="<?php _e( "Modules", "smile" ); ?>"><?php echo __( "Modules", "smile" ); ?></a>

            <!-- <a class="nav-tab" href="?page=convertplug&view=cp_import" title="<?php _e( "Import", "smile" ); ?>"><?php echo __( "Import", "smile" ); ?></a> -->

            <?php if($reg_menu_hide !== true) : ?>
            <a class="nav-tab" href="?page=convertplug&view=registration" title="<?php _e( "Registration", "smile"); ?>"><?php echo __("Registration", "smile" ); ?></a>
            <?php endif; ?>

            <a class="nav-tab" href="?page=convertplug&view=knowledge_base" title="<?php _e( "knowledge Base", "smile"); ?>"><?php echo __("Knowledge Base", "smile" ); ?></a>

            <?php if( isset( $_GET['author'] ) ){ ?>
            <a class="nav-tab" href="?page=convertplug&view=debug&author=true" title="<?php _e( "Debug", "smile" ); ?>"><?php echo __( "Debug", "smile" ); ?></a>
            <?php } ?>
        </h2>
      </div><!-- smile-settings-wrapper -->

      </hr>

      <div class="container cp-started-content">
        <div class="container">
          <div class="col-md-6">
            <div class="cp-started-section">

              <h3 class="cp-started-title"><?php _e( 'Same traffic, but more conversions!', 'smile' ); ?></h3>
              <p class="cp-started-desc"><?php _e( "Let's see how ConvertPlug works and some use cases -", "smile" ); ?></p>

              <div class="cp-started-main-content">

                <ul class="cp-started-content-list">

                  <li data-id="img1" class="cp-started-li-act">
                    <i class="cp-started-content-icon connects-icon-mail"></i>
                    <h5 class="cp-started-content-data"><?php _e( 'Build Email List', 'smile' ); ?></h5>
                  </li>

                  <li data-id="img2">
                    <i class="cp-started-content-icon connects-icon-video"></i>
                    <h5 class="cp-started-content-data"><?php _e( 'Promote Videos', 'smile' ); ?></h5>
                  </li>

                  <li data-id="img3">
                    <i class="cp-started-content-icon connects-icon-bar-graph"></i>
                    <h5 class="cp-started-content-data"><?php _e( 'Analytics', 'smile' ); ?></h5>
                  </li>

                  <li data-id="img4">
                    <i class="cp-started-content-icon connects-icon-location-2"></i>
                    <h5 class="cp-started-content-data"><?php _e( 'Drive Traffic', 'smile' ); ?></h5>
                  </li>

                  <li data-id="img5">
                    <i class="cp-started-content-icon connects-icon-tag"></i>
                    <h5 class="cp-started-content-data"><?php _e( 'Offer Coupons', 'smile' ); ?></h5>
                  </li>

                  <li data-id="img6">
                    <i class="cp-started-content-icon connects-icon-users"></i>
                    <h5 class="cp-started-content-data"><?php _e( 'Share Updates', 'smile' ); ?></h5>
                  </li>

                </ul>

              </div><!-- .cp-started-main-content -->

            </div><!--cp started section-->
          </div><!--col-md-6-->

          <div class="col-md-6">
            <div class="cp-started-section">
              <div class="cp-started-screenshot">
                <div class="imgtarget img1 active"><img src="<?php echo plugins_url( 'assets/img/getting-started/1.png', __FILE__ ); ?>" /> </div>
                <div class="imgtarget img2"><img src="<?php echo plugins_url( 'assets/img/getting-started/2.png', __FILE__ ); ?>" /> </div>
                <div class="imgtarget img3"><img src="<?php echo plugins_url( 'assets/img/getting-started/3.png', __FILE__ ); ?>" /> </div>
                <div class="imgtarget img4"><img src="<?php echo plugins_url( 'assets/img/getting-started/4.png', __FILE__ ); ?>" /> </div>
                <div class="imgtarget img5"><img src="<?php echo plugins_url( 'assets/img/getting-started/5.png', __FILE__ ); ?>" /> </div>
                <div class="imgtarget img6"><img src="<?php echo plugins_url( 'assets/img/getting-started/6.png', __FILE__ ); ?>" /> </div>
              </div>
            </div><!-- cp-started-section -->
          </div><!--col-md-6-->

        </div><!-- .continer -->

        <div class="container cp-started-bottom-content">
          <div class="col-md-4">
          <?php

		  $stored_modules = get_option('convert_plug_modules');

		  $get_started_url = get_admin_url();
		  if( $stored_modules[0] == "Modal_Popup" ){
			  $get_started_url .= "admin.php?page=smile-modal-designer&style-view=new";
		  }else if( $stored_modules[0] == "Slide_In_Popup" ){
         $get_started_url .= "admin.php?page=smile-slide_in-designer&style-view=new";
      }else {
			  $get_started_url .= "admin.php?page=smile-info_bar-designer&style-view=new";
		  }
		  ?>
            <a class="button-primary cp-started-footer-button" href="<?php echo esc_url( $get_started_url ); ?>"><?php _e( "LET'S GET STARTED", "smile" ); ?></a>
          </div>
        </div><!-- cp-started-bottom-content -->

      </div><!-- cp-started-content -->
    </div><!-- bend-content-wrap -->
  </div><!-- .wrap-container -->
</div><!-- .bend -->
<style type="text/css">
.imgtarget {
  position: absolute;
  opacity: 0;
  -webkit-transition: opacity 0.5s ease-in-out;
     -moz-transition: opacity 0.5s ease-in-out;
      -ms-transition: opacity 0.5s ease-in-out;
       -o-transition: opacity 0.5s ease-in-out;
          transition: opacity 0.5s ease-in-out;
}
.active {
  opacity: 1;
}

</style>
<script type="text/javascript">
  jQuery(document).ready(function($) {
    jQuery('.cp-started-content-list li').each(function(index, el) {
      jQuery(el).hover(function() {
        jQuery(el).siblings().removeClass('cp-started-li-act');
        jQuery(el).addClass('cp-started-li-act');

        var imgId = jQuery(el).attr('data-id');
        if( imgId ) {
          jQuery('.'+imgId).siblings().removeClass('active');
          jQuery('.'+imgId).addClass('active');
        }
      });
    });
  });
</script>
