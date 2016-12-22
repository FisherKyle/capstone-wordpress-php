<?php $cp_addon_list = Smile_Framework::$addon_list; ?>
<div class="wrap about-wrap bsf-connect bsf-connect-new-list bend">
  <div class="wrap-container">
    <div class="bend-heading-section bsf-connect-header bsf-cnlist-header">
      <h1><?php _e( "Create New Campaign", "smile" ); ?></h1>
    </div>
    <!-- bend-heading section -->

    <div class="msg"></div>

    <div class="bend-content-wrap">
    	<div class="smile-absolute-loader">
    		<div class="smile-loader" style="transform: none !important;top: 120px !important;">
				<div class="smile-loading-bar"></div>
				<div class="smile-loading-bar"></div>
				<div class="smile-loading-bar"></div>
				<div class="smile-loading-bar"></div>
			</div>
		</div>
      <hr class="bsf-extensions-lists-separator" style="margin: -20px 0px 45px 0px;">
      </hr>
      <div class="container bsf-cnlist-content">
        <div class="bsf-cnlist-form col-sm-6 col-sm-offset-3">

			<div class="cp-wizard-progress">
				<div class="cp-wizard-progress-bar"></div>
			</div>

            <form id="bsf-cnlist-contact-form">
            	<div class="container">
            		<div class="col-sm-12">
	            		<div class="bsf-cnlist-form-row">
							<input type="hidden" name="action" value="smile_add_list" />
							<input type="hidden" name="date" value="<?php echo esc_attr( date("j-n-Y") ); ?>" />
						</div>
		            	<div class="step-1 bsf-cnlist-form-wizard in active">
		            		<div class="steps-section">
								<div class="bsf-cnlist-form-row bsf-cnlist-list-name" >
									<label for="bsf-cnlist-list-name" >
									  <?php _e( "Campaign Name", "smile" ); ?>
									</label>
									<input type="text" id="bsf-cnlist-list-name" name="list-name" autofocus="autofocus"/>
									<span class="cp-validation-error"></span>
								</div>

								<?php
								if( !empty( $cp_addon_list ) ) {
								?>
								<!-- ********************************************************** -->
								<div class="bsf-cnlist-form-row bsf-cnlist-list-provider" >
										<label for="bsf-cnlist-list-provider" >
										  <?php _e( "Do you want to sync connects with any third party software?", "smile" ); ?>
										</label>
										<select id="bsf-cnlist-list-provider" class="bsf-cnlist-select" name="list-provider">
										  	<option value="Convert Plug">No</option>
											<?php
												foreach( $cp_addon_list as $slug => $setting ){
													echo '<option value="' . $slug . '">' . $setting['name'] . '</option>';
												}
											?>
										</select>
										<div class="bsf-cnlist-list-provider-spinner"></div>
								</div>
								<!-- ********************************************************** -->
								<?php
								}
								?>

					            <div class="bsf-cnlist-form-row short-description" >
					              <p class="description">
					                <?php _e( 'Your connects can be synced to CRM & Mailer softwares like HubSpot, MailChimp, etc.<br><br><strong>Important Note</strong> - If you need to integrate with third party CRM & Mailer software like MailChimp, Infusionsoft, etc. please install the respective addon from <a href="'. bsf_exension_installer_url('14058953') .'">here</a>.', 'smile' ); ?>
					              </p>
					            </div>
					        </div><!-- .steps-section -->
						</div>
						<!-- .step-1    -->

						<div class="step-2 bsf-cnlist-form-wizard" >
							<div class="steps-section">
								<div class="col-sm-12">
						            <div class="bsf-cnlist-form-row bsf-cnlist-mailer-data" style="display:none;"></div>
						            <div class="bsf-cnlist-mailer-help">
						            	<a href="http://documentation.dev/mailer/" target="_blank"><?php _e( "Where to find this?", "smile" ); ?></a>
						            </div><!-- .bsf-cnlist-mailer-help -->
					            </div>
					        </div><!-- .steps-section -->
			            </div>
		            	<!-- .step-2    -->
	            	</div>
	        	</div>

	            <div class="container bsf-new-list-wizard">
	            	<div class="col-sm-6">
	            		<button class="wizard-prev button button-primary disabled" type="button">
	            			<?php _e( "Previous", "smile" ); ?>
	            		</button>
	        		</div>
	        		<div class="col-sm-6">
	        			<div class="bsf-cnlist-save-btn" >
							<button id="save-btn" class="wizard-save button button-primary" data-provider="">
								<?php _e( "Create Campaign", "smile" ); ?>
							</button>
				        </div>
				        <div class="bsf-cnlist-next-btn" style="display:none;">
	            			<button class="wizard-next button button-primary" type="button" style="display: inline-block;">
	            				<?php _e( "Next", "smile" ); ?>
	            			</button>
	            		</div>
	            	</div>
	            </div><!-- .bsf-new-list-wizard -->
            </form>
        </div>
        <!-- .bsf-cnlist-form -->
      </div>
      <!-- .container -->
    </div>
    <!-- .bend-content-wrap -->
  </div>
  <!-- .wrap-container -->
</div>
<!-- .wrap -->
<script type="text/javascript">
var provider = jQuery("#bsf-cnlist-list-provider");
jQuery(document).ready(function(){

	var val = provider.length ? provider.val().toLowerCase() : 'convert plug' ;
	<?php if( !empty( $cp_addon_list ) ) { ?>
	jQuery("#save-btn").attr('data-provider',val);
	provider.change(function(e){
		if( jQuery(this).val() == 'Convert Plug' ) {
			jQuery(".bsf-cnlist-save-btn").show();
			jQuery(".bsf-cnlist-next-btn").hide();
			jQuery("#save-btn").removeAttr('disabled');
		} else {
			jQuery(".bsf-cnlist-save-btn").hide();
			jQuery("#save-btn").attr('disabled', 'disabled');
			jQuery(".bsf-cnlist-next-btn").show();
		}
	});
	<?php } ?>
});

jQuery(document).on( "click", ".update-mailer", function(){
	jQuery('.bsf-cnlist-mailer-data input[type="text"]').val('');
	jQuery(this).replaceWith('<button id="auth-'+jQuery(this).attr('data-mailer')+'" class="button button-secondary auth-button" disabled="true"><?php _e( "Authenticate ' + jQuery(this).attr('data-mailerslug') + '", "smile" ); ?></button><span class="spinner" style="float: none;"></span>');
});

jQuery("#save-btn").click(function(e){

	e.preventDefault();

	if( jQuery("#bsf-cnlist-list-name").val() == "" ){
		jQuery('html, body').animate({ scrollTop: jQuery(".bsf-cnlist-list-name").offset().top - 100 }, 500);
		jQuery("#bsf-cnlist-list-name").focus();
		jQuery("#bsf-cnlist-list-name").addClass('connect-new-list-required');
		return false;
	}

	var isCampaignExists = false;
	var campaignName = jQuery("#bsf-cnlist-list-name").val();
	jQuery.ajax({
		url: ajaxurl,
		data: {
			campaign: campaignName,
			action: 'isCampaignExists'
		},
		async: false,
		method: "POST",
		dataType: "JSON",
		success: function(result){
			if( result.status == 'error' ) {
				jQuery(".cp-validation-error").show();
				jQuery(".cp-validation-error").html(result.message);
				isCampaignExists = true;
			} else {
				jQuery(".cp-validation-error").html('');
			}
		},
		error: function(err){
			console.log(err);
		}
	});

	if( isCampaignExists ) {
		return false;
	}

	<?php if( !empty( $cp_addon_list ) ) { ?>
		var data = jQuery("#bsf-cnlist-contact-form").serialize();
	<?php } else{ ?>
		var data = jQuery("#bsf-cnlist-contact-form").serialize() + '&list-provider=Convert+Plug';
	<?php } ?>
	var provider = jQuery(this).data('provider');
	
	if( provider == "madmimi" ) {
		var mailer_list_name = 	jQuery("#"+provider+"-list option:selected").text();
		var mailer_list_id = jQuery("#"+provider+"-list option:selected").text();
		data += "&list="+mailer_list_id+"&provider_list="+mailer_list_name;
	} else if( provider == "sendy" ){
		var mailer_list_name = 	jQuery( '#sendy_list_ids' ).val();
		var mailer_list_id = jQuery( '#sendy_list_ids' ).val();
		data += "&list="+mailer_list_id+"&provider_list="+mailer_list_name;
	} else if( provider == "infusionsoft" ){
		var lists_arr = new Array();
		var mailer_list_id = '';
		var mailer_list_name = '';
		var selected_id = '';
		var name = '';
		if( jQuery( "#"+provider+"-list option:selected" ).text() != '' ) {
			jQuery( "#"+provider+"-list option:selected" ).each(function(){
				selected_id = jQuery(this).val();
	            name = jQuery(this).text();
				lists_arr.push("{\""+selected_id+"\" : \""+name+"\"}");
			});
			
		} else {
			selected_id = -1;
			name = -1;
			lists_arr.push("{\""+selected_id+"\" : \""+name+"\"}");
		}
		mailer_list_id = JSON.stringify(lists_arr);
		mailer_list_name = 	JSON.stringify(lists_arr);
		
		var infusionsoft_action_id = jQuery('#infusionsoft_action_id').val();
		data += "&list="+mailer_list_id+"&provider_list="+mailer_list_name+"&infusionsoft_action_id="+infusionsoft_action_id;
	} else if( provider == "ontraport" ) {
		var mailer_list_id = jQuery("#"+provider+"-list option:selected").val();
		var mailer_list_name = 	jQuery("#"+provider+"-list option:selected").text();
		data += "&list="+mailer_list_id+"&provider_list="+mailer_list_name;
	} else {
		var mailer_list_id = jQuery("#"+provider+"-list ").val();
		var mailer_list_name = 	jQuery("#"+provider+"-list option:selected").text();
		data += "&list="+mailer_list_id+"&provider_list="+mailer_list_name;
	}
	var loading = jQuery(this).next(".spinner");
	var msg = jQuery(".msg");
	loading.css('visibility','visible');
	jQuery.ajax({
		url: ajaxurl,
		data: data,
		method: "POST",
		dataType: "JSON",
		success: function(result){

			if( result.status == 'error' ) {
				jQuery(".cp-validation-error").show();
				jQuery(".cp-validation-error").html(result.message);
				return false;
			} else{
				 jQuery(".cp-validation-error").html('');
			}

			if( result.message == "added" ){
				swal({
					title: "<?php _e( "Added!", "smile" ); ?>",
					text: "<?php _e( "The campaign you just created, is added to the list.", "smile" ); ?>",
					type: "success",
					timer: 2000,
					showConfirmButton: false
				});
			} else {
				swal({
					title: "<?php _e( "Error!", "smile" ); ?>",
					text: "<?php _e( "Error adding the campaign to the list. Please try again.", "smile" ); ?>",
					type: "error",
					timer: 2000,
					showConfirmButton: false
				});
			}
			setTimeout( function(){
				document.location = 'admin.php?page=contact-manager';
			}, 600 );
		},
		error: function(err){
			swal({
				title: "<?php _e( "Error!", "smile" ); ?>",
				text: "<?php _e( "Error adding the campaign to the list. Please try again.", "smile" ); ?>",
				type: "error",
				timer: 2000,
				showConfirmButton: false
			});
		}
	});
});

/************** JQuery change events *************/

jQuery(document).on('click', '.wizard-next', function(e){

	var cpDesc = jQuery('.bsf-cnlist-provider-description').html();
	if( jQuery("#bsf-cnlist-list-name").val() == '' ) {
		jQuery("#bsf-cnlist-list-name").addClass('connect-new-list-required');
		jQuery("#bsf-cnlist-list-name").focus();
		return false;
	} else {

		var isCampaignExists = false;
		var campaignName = jQuery("#bsf-cnlist-list-name").val();
		jQuery.ajax({
			url: ajaxurl,
			data: {
				campaign: campaignName,
				action: 'isCampaignExists'
			},
			async: false,
			method: "POST",
			dataType: "JSON",
			success: function(result){
				if( result.status == 'error' ) {
					jQuery(".cp-validation-error").show();
					jQuery(".cp-validation-error").html(result.message);
					isCampaignExists = true;
				} else {
					jQuery(".cp-validation-error").html('');
				}
			},
			error: function(err){
				console.log(err);
			}
		});

		if(isCampaignExists) {
			return false;
		}

		jQuery(".smile-absolute-loader").css('visibility','visible');
		jQuery("#bsf-cnlist-list-name").removeClass('has-error');
		jQuery(this).addClass('disabled');
		jQuery(".wizard-prev").removeClass('disabled');
		jQuery(".bsf-cnlist-save-btn").show();
		jQuery(".wizard-next").hide();

		jQuery('.bsf-cnlist-provider-description').fadeOut(300);
		val = jQuery("#bsf-cnlist-list-provider").val().toLowerCase();
		jQuery("#save-btn").attr('data-provider',val);

		jQuery("#save-btn").attr('disabled','true');
		var action = 'get_'+val+'_data';
		var data = 'action='+action;

		jQuery.ajax({
			url: ajaxurl,
			data: data,
			method: "POST",
			dataType: "JSON",
			success: function(result){
				if( result.isconnected )  {
					jQuery(".bsf-cnlist-mailer-help").hide();
				}
				else {
					jQuery(".bsf-cnlist-mailer-help").show();
				}
				jQuery(".bsf-cnlist-mailer-help a").attr('href',result.helplink);
				jQuery('.bsf-cnlist-mailer-data').html(result.data);
				jQuery('.bsf-cnlist-mailer-data').slideDown(300);
				jQuery(".smile-absolute-loader").css('visibility','hidden');

				setTimeout(function(){
					jQuery('.bsf-cnlist-form-wizard.step-1').css('transform','translateX(-100px)');
				}, 800 );

				setTimeout(function(){
					jQuery('.bsf-cnlist-form-wizard.step-1').removeClass('active in');
					jQuery('.bsf-cnlist-form-wizard.step-2').addClass('in active').css( 'transform' ,'translateX(0px)');
				}, 1200 );

				if( jQuery("#"+val+"-list").length > 0 ) {
					jQuery("#save-btn").removeAttr('disabled');
				}
				jQuery(".select2-infusionsoft-list").select2();
			},
			error: function(err){
				console.log(err);
			}
		});
	}
});

jQuery(document).on('click', '.wizard-prev', function(e){

	if( !jQuery(this).hasClass('disabled') ) {

		setTimeout(function(){
			jQuery('.bsf-cnlist-form-wizard.step-2').css('transform','translateX(-100px)');
		}, 200 );

		setTimeout(function(){
			jQuery('.bsf-cnlist-form-wizard.step-2').removeClass('active in');
			jQuery('.bsf-cnlist-form-wizard.step-1').addClass('in active').css( 'transform' ,'translateX(0px)');
			jQuery(".wizard-next").removeClass('disabled');
			jQuery(".wizard-prev").addClass('disabled');
			jQuery(".bsf-cnlist-save-btn").hide();
			jQuery(".wizard-next").show();

		}, 600 );
	}
});

jQuery(document).on('keyup change keydown', '#bsf-cnlist-list-name', function() {
	if(jQuery(this).val() !== '') {
		jQuery(this).removeClass('connect-new-list-required');
	}
});

</script>