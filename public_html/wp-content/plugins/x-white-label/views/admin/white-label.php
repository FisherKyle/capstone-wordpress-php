<?php

// =============================================================================
// VIEWS/ADMIN/WHITE-LABEL.PHP
// -----------------------------------------------------------------------------
// Plugin admin output.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Output
// =============================================================================

// Require Options
// =============================================================================

require( X_WHITE_LABEL_PATH . '/functions/options.php' );



// Output
// =============================================================================

?>

<div class="tco-row">
  <div class="tco-column">
    <div class="tco-box tco-box-white-label">
      <header class="tco-box-header">
        <h2 class="tco-box-title"><?php echo $x_white_label_addons_home_heading; ?></h2>
      </header>
      <div class="tco-box-content">
        <?php echo $x_white_label_addons_home_content; ?>
      </div>
    </div>
  </div>
</div>