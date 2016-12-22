<?php

// =============================================================================
// VIEWS/SITE/CONTENT-DOCK.PHP
// -----------------------------------------------------------------------------
// Plugin site output.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Require Options
//   02. Output
// =============================================================================

// Require Options
// =============================================================================

require( X_CONTENT_DOCK_PATH . '/functions/options.php' );

GLOBAL $post;

// Output
// =============================================================================

$display = false;

// Active for all pages

if ( is_page() && isset( $x_content_dock_all_pages_active ) && $x_content_dock_all_pages_active ) {
  $display = true;
}

// Page is on the list

if ( is_page( $x_content_dock_entries_include ) ) {
  $display = true;
}

// Active for all posts

if ( is_single() && isset( $x_content_dock_all_posts_active ) && $x_content_dock_all_posts_active && $post->post_type !== 'product' ) {
  $display = true;
}

// Post is on the list

if ( is_single() && in_array( $post->ID, $x_content_dock_posts_include ) ) {
  $display = true;
}

// Active for all WooCommerce products

if ( is_single() && isset( $x_content_dock_woo_products_include ) && $x_content_dock_woo_products_include && $post->post_type == 'product') {
  $display = true;
}

// WooCommerce product is on the list

if ( is_single() && in_array( $post->ID, $x_content_dock_woo_products_include ) ) {
  $display = true;
}

// If x_content_dock_do_not_show cookie is set, ignore content-dock
if( isset( $_COOKIE['x_content_dock_do_not_show'] ) ) {
  $display = false;
}

// Render conditionally

if ( $display ) :

?>

  <div class="x-content-dock <?php echo $x_content_dock_position ?> x-content-dock-off" style="width: <?php echo $x_content_dock_width; ?>px; <?php echo $x_content_dock_position ?>: <?php echo -$x_content_dock_width - 50; ?>px;">
    <?php if ( isset( $x_content_dock_image_override_enable ) && $x_content_dock_image_override_enable ) : ?>
      <a href="<?php echo $x_content_dock_image_override_url ?>"><img src="<?php echo $x_content_dock_image_override_image ?>" alt="x-content-dock-image" /></a>
    <?php else : ?>
      <?php dynamic_sidebar( 'content-dock' ); ?>
    <?php endif; ?>

    <a href="#" class="x-close-content-dock">
      <span>&#x2716;</span>
      <span class="visually-hidden">Close the Content Dock</span>
    </a>
    <?php if ( isset( $x_content_dock_cookie_timeout ) && $x_content_dock_cookie_timeout > 0 ) : ?>
      <input type="checkbox" id="x_content_dock_do_not_show" value="1" /> <?php _e( 'Do not show again', '__e__' ); ?>
    <?php endif; ?>
  </div>

  <script id="x-content-dock-js">

    jQuery(document).ready(function($) {

      $.fn.scrollBottom = function() {
        return $(document).height() - this.scrollTop() - this.height();
      };
      var executed             = false;
      var windowObj            = $(window);
      var body                 = $('body');
      var bodyOffsetBottom     = windowObj.scrollBottom();
      var bodyHeightAdjustment = body.height() - bodyOffsetBottom;
      var bodyHeightAdjusted   = body.height() - bodyHeightAdjustment;
      var contentDock          = $('.x-content-dock');

      function sizingUpdate() {
        var bodyOffsetTop = windowObj.scrollTop();
        if ( bodyOffsetTop > ( bodyHeightAdjusted * <?php echo $x_content_dock_display / 100; ?> ) ) {
          if ( ! executed ) {
            executed = true;
            contentDock.toggleClass('x-content-dock-off').toggleClass('x-content-dock-on').css('<?php echo $x_content_dock_position ?>', '20px');
          }
        }
        $('.x-close-content-dock').click( function( e ) {
          e.preventDefault();
          contentDock.toggleClass('x-content-dock-off').toggleClass('x-content-dock-on').css('<?php echo $x_content_dock_position ?>', '<?php echo -$x_content_dock_width - 100; ?>px');
        });

        <?php if ( isset( $x_content_dock_cookie_timeout ) && $x_content_dock_cookie_timeout > 0 ) : ?>
        $('#x_content_dock_do_not_show').change( function( e ) {
          e.preventDefault();
          contentDock.toggleClass('x-content-dock-off').toggleClass('x-content-dock-on').css('<?php echo $x_content_dock_position ?>', '<?php echo -$x_content_dock_width - 100; ?>px');
          var data = {
          		'action': 'x_content_dock_do_not_show',
          		'x_content_dock_do_not_show': 1
          	};
          	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
          	$.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', data, function( response ) {
          		// done silently
          	});
        });
        <?php endif; ?>

      }

      <?php if ( isset( $x_content_dock_trigger_timeout ) && $x_content_dock_trigger_timeout > 0 ) : ?>
        window.setTimeout( function() {
          if ( ! executed ) {
            executed = true;
            contentDock.toggleClass('x-content-dock-off').toggleClass('x-content-dock-on').css('<?php echo $x_content_dock_position ?>', '20px');
          }
        }, <?php echo (int) $x_content_dock_trigger_timeout * 1000; ?>);
      <?php endif; ?>

      windowObj.bind('scroll', sizingUpdate).resize(sizingUpdate);
      sizingUpdate();

    });

  </script>

<?php endif;
