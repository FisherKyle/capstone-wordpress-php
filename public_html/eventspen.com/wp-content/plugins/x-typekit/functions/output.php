<?php

// =============================================================================
// FUNCTIONS/OUTPUT.PHP
// -----------------------------------------------------------------------------
// Plugin output.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Kit Data
//   02. Filter Kit Data Into X
//   03. Typekit Embed
//   04. Output
// =============================================================================

// Filter Kit Data Into X
// =============================================================================

function x_typekit_filter_kit_data_into_x( $data ) {

  require( X_TYPEKIT_PATH . '/functions/options.php' );

  if ( isset( $x_typekit_enable ) && $x_typekit_enable == 1 ) {
    $output = array_merge( $x_typekit_request, $data );
  } else {
    $output = $data;
  }

  return $output;

}

add_filter( 'x_fonts_data', 'x_typekit_filter_kit_data_into_x' );



// Typekit Embed
// =============================================================================

function x_typekit_embed() {

  require( X_TYPEKIT_PATH . '/functions/options.php' );

  ?>

    <script>
      (function(d) {
        var config = {
          kitId         : '<?php echo $x_typekit_kit_id; ?>',
          scriptTimeout : 3000,
          async         : true
        },
        h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
      })(document);
    </script>

  <?php

}



// Output
// =============================================================================

require( X_TYPEKIT_PATH . '/functions/options.php' );

if ( isset( $x_typekit_enable ) && $x_typekit_enable == 1 ) {

  add_action( 'wp_head', 'x_typekit_embed' );

}