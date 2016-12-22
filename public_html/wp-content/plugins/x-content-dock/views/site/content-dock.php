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



// Output
// =============================================================================

if ( is_page( $x_content_dock_entries_include ) || is_single( $x_content_dock_entries_include ) ) :

?>

  <div class="x-content-dock <?php echo $x_content_dock_position ?> x-content-dock-off" style="width: <?php echo $x_content_dock_width; ?>px; <?php echo $x_content_dock_position ?>: <?php echo -$x_content_dock_width - 50; ?>px;">
    <?php dynamic_sidebar( 'content-dock' ); ?>
    <a href="#" class="x-close-content-dock">
      <span>&#x2716;</span>
      <span class="visually-hidden">Close the Content Dock</span>
    </a>
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
        $('.x-close-content-dock').click(function(e) {
          e.preventDefault();
          contentDock.toggleClass('x-content-dock-off').toggleClass('x-content-dock-on').css('<?php echo $x_content_dock_position ?>', '<?php echo -$x_content_dock_width - 100; ?>px');
        });
      }

      windowObj.bind('scroll', sizingUpdate).resize(sizingUpdate);
      sizingUpdate();

    });

  </script>

<?php endif;