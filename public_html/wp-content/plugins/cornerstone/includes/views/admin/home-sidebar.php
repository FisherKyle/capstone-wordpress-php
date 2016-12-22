<div class="tco-sidebar">
  <div class="tco-cta">
    <a href="https://theme.co/cornerstone/" target="_blank"><?php cs_tco()->cornerstone_logo( 'tco-cta-logo-product' ); ?></a>
    <hr class="tco-cta-spacing">
    <a href="https://theme.co/" target="_blank"><?php cs_tco()->themeco_logo( 'tco-cta-logo-company' ); ?></a>
    <hr class="tco-cta-spacing">
    <p class="tco-cta-note"><?php _e( '<strong>NOTE:</strong> A separate license is required for each site using Cornerstone.', 'cornerstone' ); ?></p>
    <hr class="tco-cta-spacing">
    <div class="tco-cta-actions">
      <a class="tco-cta-action" href="https://community.theme.co/my-licenses/" target="_blank"><?php _e( 'Manage Licenses', 'cornerstone' ); ?></a>
      <?php if ( ! apply_filters( '_cornerstone_integration_remove_purchase_link', false ) ) : ?>
      <a class="tco-cta-action" href="https://theme.co/go/join-validation-cs.php" target="_blank" data-tco-module="cs-purchase-another-license"><?php _e( 'Purchase Another License', 'cornerstone' ); ?></a>
      <?php endif; ?>
    </div>
    <?php if ( $is_validated ) : ?>
      <hr class="tco-cta-spacing">
      <p class="tco-cta-note" data-tco-module="cs-validation-revoke"><?php _e( 'Your site is validated. <a href="#" data-tco-module-target="revoke">Revoke validation</a>.', 'cornerstone' ); ?></p>
    <?php endif; ?>
  </div>
</div>