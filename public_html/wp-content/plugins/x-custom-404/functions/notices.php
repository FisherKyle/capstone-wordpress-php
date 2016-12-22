<?php

// =============================================================================
// FUNCTIONS/NOTICES.PHP
// -----------------------------------------------------------------------------
// Plugin notices.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Notices
// =============================================================================

// Notices
// =============================================================================

function x_custom_404_admin_notices() { ?>

  <?php if ( isset( $_POST['x_custom_404_form_submitted'] ) ) : ?>
    <?php if ( strip_tags( $_POST['x_custom_404_form_submitted'] ) == 'submitted' && current_user_can( 'manage_options' ) ) : ?>

      <div class="updated">
        <p><?php _e( '<strong>Huzzah!</strong> All settings have been successfully saved.', '__x__' ); ?></p>
      </div>

    <?php endif; ?>
  <?php endif; ?>

<?php }

add_action( 'admin_notices', 'x_custom_404_admin_notices' );