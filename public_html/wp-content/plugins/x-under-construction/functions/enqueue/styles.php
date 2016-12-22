<?php

// =============================================================================
// FUNCTIONS/ENQUEUE/STYLES.PHP
// -----------------------------------------------------------------------------
// Plugin styles.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Output Site Styles
//   02. Enqueue Admin Styles
// =============================================================================

// Output Site Styles
// =============================================================================

function x_under_construction_output_site_styles() {

  require( X_UNDER_CONSTRUCTION_PATH . '/functions/options.php' );

  if ( isset( $x_under_construction_enable ) && $x_under_construction_enable == 1 && ! is_user_logged_in() ) {

    $admin_bar_is_showing = is_admin_bar_showing();

    ?>

    /*
    // Import font.
    */

    @import url(http://fonts.googleapis.com/css?family=Lato:300,700);


    /*
    // Disable browser scroll.
    */

    html,
    body {
      overflow: hidden !important;
      height: 100% !important;
      background: none;
    }


    /*
    // Base styles.
    */

    body {
      background-color: <?php echo $x_under_construction_background_color; ?>;
      <?php if ( $x_under_construction_background_image != '' ) : ?>
        background-image: url(<?php echo $x_under_construction_background_image; ?>);
        background-position: 50% 50%;
        background-repeat: no-repeat;
        -webkit-background-size: cover;
                background-size: cover;
      <?php endif; ?>
    }

    .x-under-construction-overlay {
      position: fixed;
      top: <?php echo ( $admin_bar_is_showing ) ? '32px' : '0' ; ?>;
      left: 0;
      right: 0;
      bottom: 0;
      overflow-x: hidden;
      overflow-y: auto;
      z-index: 99999;
    }

    .x-under-construction-wrap-outer {
      display: table;
      width: 100%;
      height: 100%;
    }

    .x-under-construction-wrap-inner {
      display: table-cell;
      vertical-align: middle;
      padding: 55px 35px;
    }

    .x-under-construction {
      display: block;
      overflow: auto;
      margin: 0 auto;
      max-width: 600px;
      font-family: Lato, "Helvetica Neue", Helvetica, sans-serif;
      text-align: center;
    }


    /*
    // Components.
    */

    .x-under-construction h1 {
      margin: 0 0 25px;
      font-family: Lato, "Helvetica Neue", Helvetica, sans-serif;
      font-size: 48px;
      font-weight: 300;
      line-height: 1;
      color: <?php echo $x_under_construction_heading_color; ?>;
    }

    .x-under-construction h2 {
      margin: 0;
      font-family: Lato, "Helvetica Neue", Helvetica, sans-serif;
      font-size: 24px;
      font-weight: 300;
      line-height: 1.4;
      color: <?php echo $x_under_construction_subheading_color; ?>;
    }

    .x-under-construction-countdown {
      margin: 25px 0 0;
      font-size: 18px;
      font-weight: 300;
      line-height: 1;
      color: <?php echo $x_under_construction_date_color; ?>;
    }

    .x-under-construction-countdown span {
      margin: 0 3px;
      display: inline-block;
      border: 1px solid;
      padding: 10px 12px;
      line-height: 1;
      border-radius: 4px;
    }

    .x-under-construction-social {
      margin: 30px 0 0;
    }

    .x-under-construction-social a {
      padding: 0 6px;
      font-size: 24px;
      line-height: 1;
      color: <?php echo $x_under_construction_social_color; ?>;
      opacity: 0.25;
      transition: opacity 0.3s ease;
    }

    .x-under-construction-social a:hover {
      opacity: 1;
    }
    
    .backstretch {
      display: none;
    }


    /*
    // Responsive.
    */

    <?php if ( $admin_bar_is_showing ) : ?>

      @media (max-width: 782px) {
        .x-under-construction-overlay {
          top: 46px;
        }
      }

      @media (max-width: 600px) {
        .x-under-construction-overlay {
          top: 0;
        }
      }

    <?php endif; ?>

    @media (max-width: 768px) {
      .x-under-construction h1 {
        font-size: 32px;
      }

      .x-under-construction h2 {
        font-size: 21px;
      }
    }

    @media (max-width: 580px) {
      .x-under-construction-countdown span {
        display: block;
        margin: 10px 0 0;
        width: calc(50% - 5px);
      }

      .x-under-construction-countdown span:nth-child(odd) {
        float: left;
      }

      .x-under-construction-countdown span:nth-child(even) {
        float: right;
      }
    }

  <?php }

}

add_action( 'x_head_css', 'x_under_construction_output_site_styles' );



// Enqueue Admin Styles
// =============================================================================

function x_under_construction_enqueue_admin_styles( $hook ) {

  if ( $hook == 'addons_page_x-extensions-under-construction' ) {

    wp_enqueue_style( 'x-under-construction-admin-css', X_UNDER_CONSTRUCTION_URL . '/css/admin/style.css', NULL, NULL, 'all' );

  }

}

add_action( 'admin_enqueue_scripts', 'x_under_construction_enqueue_admin_styles' );