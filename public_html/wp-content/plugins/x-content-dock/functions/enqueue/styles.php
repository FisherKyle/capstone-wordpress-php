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

function x_content_dock_output_site_styles() {

  require( X_CONTENT_DOCK_PATH . '/functions/options.php' );

  if ( isset( $x_content_dock_enable ) && $x_content_dock_enable == 1 ) :

    if ( is_page( $x_content_dock_entries_include ) || is_single( $x_content_dock_entries_include ) ) :

    ?>

      /*
      // Base styles.
      */

      .x-content-dock {
        position: fixed;
        bottom: 0;
        border: 1px solid <?php echo $x_content_dock_border_color; ?>;
        border-bottom: 0;
        padding: 30px;
        background-color: <?php echo $x_content_dock_background_color; ?>;
        z-index: 1050;
        -webkit-transition: all 0.5s ease;
                transition: all 0.5s ease;
        -webkit-transform: translate3d(0, 0, 0);
            -ms-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
        <?php if ( $x_content_dock_box_shadow == 1 ) { ?>
          box-shadow: 0 0.085em 0.5em 0 rgba(0, 0, 0, 0.165);
        <?php } ?>
      }


      /*
      // Headings.
      */

      .x-content-dock h1,
      .x-content-dock h2,
      .x-content-dock h3,
      .x-content-dock h4,
      .x-content-dock h5,
      .x-content-dock h6 {
        color: <?php echo $x_content_dock_headings_color; ?> !important;
      }


      /*
      // Links.
      */

      .x-content-dock a:not(.x-btn):not(.x-recent-posts a) {
        color: <?php echo $x_content_dock_link_color; ?> !important;
      }

      .x-content-dock a:not(.x-btn):not(.x-recent-posts a):hover {
        color: <?php echo $x_content_dock_link_hover_color; ?> !important;
      }


      /*
      // Widget styles.
      */

      .x-content-dock .widget {
        text-shadow: none;
        color: <?php echo $x_content_dock_text_color; ?> !important;
      }

      .x-content-dock .widget:before {
        display: none;
      }

      .x-content-dock .h-widget {
        margin: 0 0 0.5em;
        font-size: 1.65em;
        line-height: 1.2;
      }


      /*
      // Close.
      */

      .x-close-content-dock {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 12px;
        line-height: 1;
        text-decoration: none;
      }

      .x-close-content-dock span {
        color: <?php echo $x_content_dock_close_button_color; ?> !important;
        -webkit-transition: color 0.3s ease;
                transition: color 0.3s ease;
      }

      .x-close-content-dock:hover span {
        color: <?php echo $x_content_dock_close_button_hover_color; ?> !important;
      }


      /*
      // Responsive.
      */

      @media (max-width: 767px) {
        .x-content-dock {
          display: none;
        }
      }

    <?php endif;

  endif;

}

add_action( 'x_head_css', 'x_content_dock_output_site_styles' );



// Enqueue Admin Styles
// =============================================================================

function x_content_dock_enqueue_admin_styles( $hook ) {

  if ( $hook == 'addons_page_x-extensions-content-dock' ) {

    wp_enqueue_style( 'x-content-dock-admin-css', X_CONTENT_DOCK_URL . '/css/admin/style.css', NULL, NULL, 'all' );

  }

}

add_action( 'admin_enqueue_scripts', 'x_content_dock_enqueue_admin_styles' );