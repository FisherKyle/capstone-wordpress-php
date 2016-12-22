<div class="wrap bsf-page-wrapper ultimate-about">
	<div class="wrap-container">
		<div class="heading-section">
			<div class="bsf-pr-header bsf-left-header" style="margin-bottom: 55px;">
				<h2><?php echo __('Resources!','bsf'); ?></h2>
		    	<div class="bsf-pr-decription"><?php  //_e('Resources used to improve your site with Google Fonts  etc.','bsf'); ?></div>
		    </div>

		    <div class="right-logo-section">
				<div class="bsf-company-logo">
				</div><!--company-logo-->
			</div><!--right-logo-section-->
		</div>	<!--heading section-->

		<div class="inside bsf-wrap">
			<div class="container">
				<?php if(
					(isset($connects) && ($connects === true || $connects == 'true')) ||
					(!isset($connects))
				) : ?>
					<div class="col-sm-3 col-lg-3 resource-block-section">
						<a class="resource-block-link" href="<?php echo admin_url('admin.php?page=contact-manager') ?>">
							<div class="resource-block-icon">
								<span class="dashicons dashicons-share"></span>
							</div>
							<div class="resource-block-content">
								<?php echo __('Connects', 'bsf') ?>
							</div>
						</a>
					</div><!--col-sm-3-->
				<?php endif; ?>

				<div class="col-sm-3 col-lg-3 resource-block-section">
						<a class="resource-block-link" href="<?php echo bsf_exension_installer_url('14058953'); ?>">
							<div class="resource-block-icon">
								<span class="dashicons dashicons-admin-plugins"></span>
							</div>
							<div class="resource-block-content">
								<?php echo __('Addons', 'bsf') ?>
							</div>
						</a>
					</div><!--col-sm-3-->

				<?php if(
					(isset($icon_manager) && ($icon_manager === true || $icon_manager == 'true')) ||
					(!isset($icon_manager))
				) : ?>
					<div class="col-sm-3 col-lg-3 resource-block-section">
						<a class="resource-block-link" href="<?php echo admin_url('admin.php?page=bsf-font-icon-manager') ?>">
							<div class="resource-block-icon">
								<span class="dashicons dashicons-awards"></span>
							</div>
							<div class="resource-block-content">
								<?php echo __('Font Icon Manager', 'bsf') ?>
							</div>
						</a>
					</div><!--col-sm-3-->
				<?php endif; ?>

				<?php if(
					(isset($google_fonts) && ($google_fonts === true || $google_fonts == 'true')) ||
					(!isset($google_fonts))
				) : ?>
					<div class="col-sm-3 col-lg-3 resource-block-section">
						<a class="resource-block-link" href="<?php echo admin_url('admin.php?page=bsf-google-font-manager') ?>">
							<div class="resource-block-icon">
								<span class="dashicons dashicons-edit"></span>
							</div>
							<div class="resource-block-content">
								<?php echo __('Google Fonts Manager', 'bsf') ?>
							</div>
						</a>
					</div><!--col-sm-3-->
				<?php endif; ?>

				<?php if( class_exists('CP_Wp_Comment_Form') ) : ?>
					<div class="col-sm-3 col-lg-3 resource-block-section">
						<a class="resource-block-link" href="<?php echo admin_url('admin.php?page=cp-wp-comment-form') ?>">
							<div class="resource-block-icon">
								<span class="dashicons dashicons-testimonial"></span>
							</div>
							<div class="resource-block-content">
								<?php echo __('WP Comment Form', 'bsf') ?>
							</div>
						</a>
					</div><!--col-sm-3-->
				<?php endif; ?>

				<?php if( class_exists('CP_Wp_Registration_Form') ) : ?>
					<div class="col-sm-3 col-lg-3 resource-block-section">
						<a class="resource-block-link" href="<?php echo admin_url('admin.php?page=cp-wp-registration-form') ?>">
							<div class="resource-block-icon">
								<span class="dashicons dashicons-welcome-write-blog"></span>
							</div>
							<div class="resource-block-content">
								<?php echo __('WP Registration Form', 'bsf') ?>
							</div>
						</a>
					</div><!--col-sm-3-->
				<?php endif; ?>

				<?php if( class_exists('CP_Woocommerce_Checkout_Form') && class_exists( 'WooCommerce' ) ) : ?>
					<div class="col-sm-3 col-lg-3 resource-block-section">
						<a class="resource-block-link" href="<?php echo admin_url('admin.php?page=cp-woocheckout-form') ?>">
							<div class="resource-block-icon">
								<span class="dashicons dashicons-cart"></span>
							</div>
							<div class="resource-block-content">
								<?php echo __('WooCommerce Checkout Form', 'bsf') ?>
							</div>
						</a>
					</div><!--col-sm-3-->
				<?php endif; ?>

				<?php if( class_exists('CP_Contact_Form7') && class_exists( 'WPCF7' ) ) : ?>
					<div class="col-sm-3 col-lg-3">
						<a class="resource-block-link" href="<?php echo admin_url('admin.php?page=cp-contact-form7') ?>">
							<div class="resource-block-icon">
								<span class="dashicons dashicons-clipboard"></span>
							</div>
							<div class="resource-block-content">
								<?php echo __('Contact Form 7', 'bsf') ?>
							</div>
						</a>
					</div><!--col-sm-3-->
				<?php endif; ?>
			</div><!--container-->

		</div><!--bsf-wrap-->
	</div><!--wrap-container-->
</div><!--wrap-->
