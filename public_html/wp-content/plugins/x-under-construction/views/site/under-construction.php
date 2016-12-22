<?php

// =============================================================================
// VIEWS/SITE/UNDER-CONSTRUCTION.PHP
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

require( X_UNDER_CONSTRUCTION_PATH . '/functions/options.php' );



// Output
// =============================================================================

$facebook    = $x_under_construction_facebook;
$twitter     = $x_under_construction_twitter;
$google_plus = $x_under_construction_google_plus;
$instagram   = $x_under_construction_instagram;

?>

<!DOCTYPE html>
<!--[if IE 9]><html class="no-js ie9" <?php language_attributes(); ?>><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->

<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php wp_title(''); ?></title>
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  <?php wp_head(); ?>
</head>

<body>

  <div class="x-under-construction-overlay">
    <div class="x-under-construction-wrap-outer">
      <div class="x-under-construction-wrap-inner">
        <div class="x-under-construction">

          <h1><?php echo stripslashes( $x_under_construction_heading ); ?></h1>
          <h2><?php echo stripslashes( $x_under_construction_subheading ); ?></h2>

          <?php if ( $x_under_construction_date != '' ) : ?>

            <div class="x-under-construction-countdown cf">
              <span class="days">0 Days</span>
              <span class="hours">0 Hours</span>
              <span class="minutes">0 Minutes</span>
              <span class="seconds">0 Seconds</span>
            </div>

            <script type="text/javascript">
              jQuery(document).ready(function($) {
                $('.x-under-construction-countdown').countdown('<?php echo $x_under_construction_date; ?>',
                  function(e) {

                    var $this = $(this);

                    $this.find('.days').text(e.strftime('%-D Days'));
                    $this.find('.hours').text(e.strftime('%-H Hours'));
                    $this.find('.minutes').text(e.strftime('%-M Minutes'));
                    $this.find('.seconds').text(e.strftime('%-S Seconds'));

                  }
                );
              });
            </script>

          <?php endif; ?>

          <?php if ( $facebook || $twitter || $google_plus || $instagram ) : ?>

            <div class="x-under-construction-social">
              <?php if ( $facebook )    : ?><a href="<?php echo $facebook ?>" target="_blank"><i class="x-icon-facebook-square" data-x-icon="&#xf082;"></i></a><?php endif; ?>
              <?php if ( $twitter )     : ?><a href="<?php echo $twitter ?>" target="_blank"><i class="x-icon-twitter-square" data-x-icon="&#xf081;"></i></a><?php endif; ?>
              <?php if ( $google_plus ) : ?><a href="<?php echo $google_plus ?>" target="_blank"><i class="x-icon-google-plus-square" data-x-icon="&#xf0d4;"></i></a><?php endif; ?>
              <?php if ( $instagram )   : ?><a href="<?php echo $instagram ?>" target="_blank"><i class="x-icon-instagram" data-x-icon="&#xf16d;"></i></a><?php endif; ?>
            </div>

          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>

  <?php wp_footer(); ?>

</body>
</html>