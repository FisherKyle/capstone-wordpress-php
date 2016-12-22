<?php
	$is_cp_status = ( function_exists( "bsf_product_status" ) ) ? bsf_product_status('14058953') : '';
	$reg_menu_hide = ( (defined( 'BSF_UNREG_MENU' ) && ( BSF_UNREG_MENU === true || BSF_UNREG_MENU === 'true' )) ||
	(defined( 'BSF_REMOVE_14058953_FROM_REGISTRATION' ) && ( BSF_REMOVE_14058953_FROM_REGISTRATION === true || BSF_REMOVE_14058953_FROM_REGISTRATION === 'true' )) ) ? true : false;
	if($reg_menu_hide !== true) {
		if($is_cp_status)
			$reg_menu_hide = true;
	}

	// if reset template is set
	if( isset($_GET['reset_templates']) && $_GET['reset_templates'] == true ) {

		$module = isset($_GET['module']) ? $_GET['module'] : '';
		if( $module !== '' ) {

			$templates = get_option( "cp_".$module."_preset_templates" );

			if ( is_array($templates) && !empty($templates) ) {

				// Reset all template settings
				foreach ( $templates as $slug => $template ) {
					delete_option( 'cp_' . $module . '_' . $slug );
				}

				// remove all screen-shot images
				delete_option( 'cp_screenshots_images' );

				// remove imported images
				delete_option( 'cp_import_images' );

				// remove preset template list
				delete_option( "cp_".$module."_preset_templates" );

				echo "<div id='success'>All templates for ".$module." has been reset.</div>";

			}

		}
	}

?>
<style type="text/css">
.about-cp .wp-badge:before {
	content: "\e600";
	font-family: 'ConvertPlug';
	speak: none;
	font-style: normal;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	line-height: 1;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	font-size: 72px;
	top: calc( 50% - 54px );
	position: absolute;
	left: calc( 50% - 33px );
	color: #FFF;
}

#CPStylesImport .button-import-styles{
	margin-left: 15px !important;
}

#CPStylesImport select{
	height: 45px;
	margin-top: 0px;
	padding-left: 10px;
	padding-right: 10px;
}

#CPStylesImport .smile-loader{
	display: inline-block;
		position: relative;
		margin-left: 20px;
		vertical-align: middle;
		left: 0;
		transform: none;
		visibility: hidden;
}

.progress-bar {
	float: left;
	width: 0;
	height: 100%;
	font-size: 12px;
	line-height: 20px;
	color: #fff;
	text-align: center;
	background-color: #337ab7;
	-webkit-box-shadow: inset 0 -1px 0 rgba(0,0,0,.15);
	box-shadow: inset 0 -1px 0 rgba(0,0,0,.15);
	-webkit-transition: width .6s ease;
	-o-transition: width .6s ease;
	transition: width .6s ease;
}

.progress-bar-success {
		background-color: #5cb85c;
}

.progress {
	height: 20px;
	margin-bottom: 20px;
	overflow: hidden;
	background-color: #f5f5f5;
	border-radius: 4px;
	-webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
	box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
	-webkit-transition: width .6s ease;
	-o-transition: width .6s ease;
	transition: width .6s ease;
}

@-webkit-keyframes progress-bar-stripes {
	from {
		background-position: 40px 0;
	}
	to {
		background-position: 0 0;
	}
}
@-o-keyframes progress-bar-stripes {
	from {
		background-position: 40px 0;
	}
	to {
		background-position: 0 0;
	}
}
@keyframes progress-bar-stripes {
	from {
		background-position: 40px 0;
	}
	to {
		background-position: 0 0;
	}
}

.progress-bar.active,
.progress.active .progress-bar {
	-webkit-animation: progress-bar-stripes 2s linear infinite;
	-o-animation: progress-bar-stripes 2s linear infinite;
	animation: progress-bar-stripes 2s linear infinite;
}

.progress-bar-striped,
.progress-striped,
.progress-bar {
	background-image: -webkit-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);
	background-image: -o-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);
	background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);
	-webkit-background-size: 40px 40px;
	background-size: 40px 40px;
}

.cp-upload-progress{
	margin-top: 20px;
	visibility: hidden;
}



</style>
<div class="wrap about-wrap about-cp bend">
	<div class="wrap-container">
		<div class="bend-heading-section cp-about-header">
			<h1><?php _e("ConvertPlug - Import!", "smile"); ?></h1>
			<h3><?php _e("Import style templates!", "smile"); ?></h3>
			<div class="bend-head-logo">
				<div class="bend-product-ver">
					<?php _e( "Version", "smile" ); echo ' '.CP_VERSION; ?>
				</div>
			</div>
		</div><!-- bend-heading section -->
		<div class="msg"></div>
		<div class="bend-content-wrap">
			<h2 class="nav-tab-wrapper">
				<a class="nav-tab" href="?page=convertplug" title="<?php _e( "About", "smile"); ?>"><?php echo __("About", "smile" ); ?></a>

				<a class="nav-tab" href="?page=convertplug&view=modules" title="<?php _e( "Modules", "smile" ); ?>"><?php echo __( "Modules", "smile" ); ?></a>

				<a class="nav-tab nav-tab-active" href="?page=convertplug&view=cp_import" title="<?php _e( "Import", "smile" ); ?>"><?php echo __( "Import", "smile" ); ?></a>

				<?php if($reg_menu_hide !== true) : ?>
				<a class="nav-tab" href="?page=convertplug&view=registration" title="<?php _e( "Registration", "smile"); ?>"><?php echo __("Registration", "smile" ); ?></a>
				<?php endif; ?>

				<?php if( isset( $_GET['author'] ) ){ ?>
				<a class="nav-tab" href="?page=convertplug&view=debug&author=true" title="<?php _e( "Debug", "smile" ); ?>"><?php echo __( "Debug", "smile" ); ?></a>
				<?php } ?>

			</h2>
			<?php $selected = ( isset($_GET['module']) && $_GET['module'] !== '' ) ? $_GET['module'] : ''; ?>
		 	<div id="smile-module-settings">
				<form id="CPStylesImport">
					<select name="module" class="cp_import_module">
					 <option value="select-module">Select Module</option>
						<option value="modal" <?php if( $selected == 'modal' ) { echo "selected"; }  ?>>Modal</option>
						<option value="info_bar" <?php if( $selected == 'info_bar' ) { echo "selected"; }  ?>>Info Bar</option>
						<option value="slide_in" <?php if( $selected == 'slide_in' ) { echo "selected"; }  ?>>Slide In</option>
					</select>
					<button type="submit" class="button button-primary button-hero button-import-styles"><?php _e("Import Styles", "smile"); ?></button>

					<div class="cp-upload-progress">
						 <div class="progress">
							<div class="progress-bar progress-bar-striped active" role="progressbar"
							aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
								0%
							</div>
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

jQuery(document).ready(function($) {

	var form = jQuery('#CPStylesImport');
	form.on('submit', function(event) {

		event.preventDefault();
		var data = $( '.cp_import_module' ).val();
		if( data !== 'select-module' ) {

			jQuery(".cp-upload-progress").css( 'visibility', 'visible' );
			jQuery(".button-import-styles").attr( "disabled", 'disabled' );

			setTimeout(function() {
				jQuery(".cp-upload-progress .progress-bar").css("width", "5%");
				jQuery(".cp-upload-progress .progress-bar").html( "5%" );
			}, 1000);

			setTimeout(function() {
				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'cp_import_presets',
						module: data
					},
				})
				.done(function(e) {

					console.log(e);
					var result = JSON.parse(e);

					if( result.success === true ) {

						var progress = result.progress;
						jQuery(".cp-upload-progress .progress-bar").css("width", progress);
						jQuery(".cp-upload-progress .progress-bar").html( progress );

						// step 2
						jQuery.ajax({
							url: ajaxurl,
							type: 'POST',
							data: {
								action: 'cp_import_presets_step2',
								module: data
							},
						})
						.done(function(e) {

							var result = JSON.parse(e);

							if( result.success === true ) {

								var progress = result.progress;

								jQuery(".cp-upload-progress .progress-bar").css( "width", progress);
								jQuery(".cp-upload-progress .progress-bar").html( progress );

								setTimeout(function() {

									jQuery(".cp-upload-progress").css( 'visibility', 'hidden' );
									jQuery(".cp-upload-progress .progress-bar").css("width", "0%");
									jQuery(".cp-upload-progress .progress-bar").html( "0%" );
									swal( "Styles has been imported successfully!", "", "success" );
									jQuery(".button-import-styles").removeAttr("disabled");

								}, 3000);

							} else {

								swal( "Oops...", "Something went wrong!", "error" );
								return false;
							}

						})
						.fail(function(e) {
							console.log(e);
							console.log("error");
						});

					} else {
						swal("Oops...", "Something went wrong!", "error");
						return false;
					}


				})
				.fail(function(e) {
					console.log(e);
					console.log("error");
				});
			}, 1800 );

		} else {
			alert('Please select module to import styles');
		}

	});
});
</script>
