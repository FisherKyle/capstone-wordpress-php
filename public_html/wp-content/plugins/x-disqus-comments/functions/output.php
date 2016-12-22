<?php

// =============================================================================
// FUNCTIONS/OUTPUT.PHP
// -----------------------------------------------------------------------------
// Plugin output.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Disqus Embed
//   02. Disqus Count
//   03. Template
//   04. Comments Link
//   05. Output
// =============================================================================

// Disqus Embed
// =============================================================================

function x_disqus_comments_embed() {

  require( X_DISQUS_COMMENTS_PATH . '/functions/options.php' );

  if ( is_singular() && comments_open() ) { ?>

  <script id="x-disqus-comments-embed-js" type="text/javascript">
    var disqus_shortname = '<?php echo $x_disqus_comments_shortname; ?>';
    (function() {
      var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
      dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
      (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
  </script>

  <?php }

}



// Disqus Count
// =============================================================================

function x_disqus_comments_count() {

  require( X_DISQUS_COMMENTS_PATH . '/functions/options.php' ); ?>

  <script id="x-disqus-comments-count-js" type="text/javascript">
    var disqus_shortname = '<?php echo $x_disqus_comments_shortname; ?>';
    (function () {
      var s = document.createElement('script'); s.async = true;
      s.type = 'text/javascript';
      s.src = '//' + disqus_shortname + '.disqus.com/count.js';
      (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
    }());
  </script>

<?php }



// Template
// =============================================================================

function x_disqus_comments_template() {

  $template = X_DISQUS_COMMENTS_PATH . '/views/site/disqus-comments.php';

  return $template;

}



// Comments Link
// =============================================================================

function x_disqus_comments_link() {

  $link = get_permalink() . '#disqus_thread';

  return $link;

}



// Output
// =============================================================================

require( X_DISQUS_COMMENTS_PATH . '/functions/options.php' );

if ( isset( $x_disqus_comments_enable ) && $x_disqus_comments_enable == 1 ) {

  add_action( 'wp_footer', 'x_disqus_comments_embed' );
  add_action( 'wp_footer', 'x_disqus_comments_count' );
  add_filter( 'x_entry_meta_comments_link', 'x_disqus_comments_link' );
  add_filter( 'comments_template', 'x_disqus_comments_template' );

}