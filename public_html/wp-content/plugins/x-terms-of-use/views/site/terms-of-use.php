<?php

// =============================================================================
// VIEWS/SITE/TERMS-OF-USE.PHP
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

require( X_TERMS_OF_USE_PATH . '/functions/options.php' );



// Output
// =============================================================================

$permalink = get_permalink( $x_terms_of_use_entry_include );

?>

<p class="terms-of-use" style="margin-bottom: 12px;">
  <label for="agree">
    <input type="checkbox" name="agree" id="agree" value="1">
    <span>I agree to the <a href="<?php echo $permalink; ?>" target="_blank" title="Click to view our terms of use">terms of use</a>.</span>
  </label>
</p>