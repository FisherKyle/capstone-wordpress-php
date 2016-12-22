<?php
	$total        = 0;
    $limit        = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 10;
    $page         = ( isset( $_GET['cont-page'] ) ) ? $_GET['cont-page'] : 1;
    $links        = ( isset( $_GET['links'] ) ) ? $_GET['links'] : 1;
    $orderby      = ( isset( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'date';
    $order        = ( isset( $_GET['order'] ) ) ? $_GET['order'] : 'desc';
    $maintainKeys = true;
    $smile_lists = get_option('smile_lists');
    $uninstalled_addons = array();
    // to unset deactivated / inactive mailer addons
    if( is_array($smile_lists) ) {
    	foreach( $smile_lists as $key => $list ){
    		$provider = $list['list-provider'];
    		if( $provider !== 'Convert Plug' ) {
	    		if( !isset( Smile_Framework::$addon_list[$provider] ) && !isset( Smile_Framework::$addon_list[strtolower($provider)] ) ) {

            $uninstalled_addons[] =  $provider;
	    			unset( $smile_lists[$key] );
	    		}
	    	}
    	}
    }

    if ( count($uninstalled_addons) > 0 ) {

      $msg = "It seems you recently upgraded to the 2.0 version. Read the changelog <a target='_blank' href='https://changelog.brainstormforce.com/convertplug/author/brainstormforce/'>here</a>. To see your previous campaigns (".ucfirst(implode(",",$uninstalled_addons))."), you will need to install the free addon from <a target='_blank' href='".admin_url('admin.php?page=bsf-extensions-14058953')."'>this</a> page.";

      echo "<div class='cp-notification'><h4>".$msg."</h4></div>";

    }

    // push contact count to smile_lists array
    if( is_array($smile_lists) ) {
	    foreach( $smile_lists as $key => $list ){
			$provider = $list['list-provider'];
			$listName = str_replace(" ","_",strtolower( trim( $list['list-name'] ) ) );
			$list_id = isset( $list['list'] ) ? $list['list'] : '';
			$mailer = str_replace(" ","_",strtolower( trim( $provider ) ) );
			if( $mailer !== "convert_plug" ){
				$listOption = "cp_".$mailer."_".$listName;
		  		$list_contacts = get_option( $listOption );
			} else {
				$listOption = "cp_connects_".$listName;
				$list_contacts = get_option($listOption);
			}
			$contacts = !empty( $list_contacts ) ? count( $list_contacts ) : 0;
			$smile_lists[$key]['contacts'] = $contacts;
		}
	}

	if( is_array($smile_lists) ) {
		$total      = count($smile_lists);
	}
	require_once 'cp-paginator.php';

	// redirect to first page for search results
    if( isset( $_POST['sq'] ) ) {
      $searchKey =  esc_attr($_POST['sq']);
      $redirectString = '?page=contact-manager&limit='.$limit.'&sq='.$searchKey.'&cont-page=1';
      echo "<script>
      window.location.href= '$redirectString';
      </script>";
    } else {
      $searchKey = '';
    }

    if ( isset( $_GET['order'] )  && $_GET['order']  == 'asc' )
      $orderLink = "order=desc";
    else
      $orderLink = "order=asc";

  	$sortingListClass = $sortinglistNameClass = $sortingProviderClass = $sortingContactsClass = "sorting";

  	// define sorting class
    if ( isset( $_GET['orderby'] ) ) {
      switch( $_GET['orderby'] ) {
        case "list" :
          $sortingListClass  = 'sorting-'.$_GET['order'];
        break;
        case "list-name" :
          $sortinglistNameClass = 'sorting-'.$_GET['order'];
        break;
        case "list-provider" :
          $sortingProviderClass  = 'sorting-'.$_GET['order'];
        break;
        case "contacts":
         	$sortingContactsClass = 'sorting-'.$_GET['order'];
        break;
      }
    }

    if( isset($_GET['sq']) && !empty($_GET['sq']) )
      $sq = $_GET['sq'];
    else
      $sq = $searchKey;

    if( isset( $_POST['sq'] ) && $_POST['sq'] == '' )
       $sq = '';

   	// define parameters for search
    $searchInParams = array('list-name','list-provider','provider_list');

    if ($smile_lists) {
      $Paginator = new Paginator( $smile_lists );
      $result = $Paginator->getData( $limit , $page ,$orderby, $order , $sq, $searchInParams, $maintainKeys );
      $smile_lists = $result->data;
    }
?>

<div class="wrap about-wrap bsf-connect bsf-connect-campaign bend">
  <div class="wrap-container">

    <div class="bend-heading-section bsf-connect-header">
		<h1> <?php echo __("Connects", "smile"); ?> <a class="add-new-h2" href="?page=contact-manager&view=new-list" title="<?php _e( "Create new list", "smile" ); ?>"><?php _e( "Create New Campaign", "smile" ); ?></a> </h1>
		<h3 style="margin-bottom: 30px;"><?php _e( "Connects is a tool to capture, sync, manage & analyze your contacts all in one place. Create campaigns & integrate them with your favorite CRM software. It comes with built-in analytics as well.", "smile" ); ?></h3>
		<a href="?page=contact-manager&view=new-list"  style="margin-right: 25px !important;" class="bsf-connect-download-csv"><i class="connects-icon-square-plus" style="line-height: 30px;font-size: 22px;"></i>
			<?php _e( "Create New Campaign", "smile" ); ?>
		</a>
		<a href="?page=contact-manager&view=analytics"  style="margin-right: 25px !important;" class="bsf-connect-download-csv"><i class="connects-icon-bar-graph-2" style="line-height: 30px;"></i>
			<?php _e( "Analytics", "smile" ); ?>
		</a>
		<?php $searchActiveClass = $sq !== '' ? "bsf-cntlist-top-search-act" : ''; ?>
		<span class="bsf-contact-list-top-search <?php echo $searchActiveClass; ?>"><i class="connects-icon-search" style="line-height: 30px;"></i>
			<form method="post" class="bsf-cntlst-top-search">
				<input class="bsf-cntlst-top-search-input" type="search" id="post-search-input" name="sq" placeholder="<?php _e( "Search", "smile" ); ?>" value="<?php echo esc_attr($sq ); ?>">
				<i class="bsf-cntlst-top-search-submit connects-icon-search"></i>
			</form>
		</span><!-- .bsf-contact-list-top-search -->
      <div class="bend-head-logo">
        <div class="bend-product-ver">
          <?php _e( 'Connects', 'smile' ); ?>
        </div>
      </div>
    </div><!-- bend-heading section -->

    <div class="bend-content-wrap" style="margin-top: 30px;">
      <hr class="bsf-extensions-lists-separator" style="margin: 22px 0px 30px 0px;"></hr>
      <div class="container bsf-connect-content">
        <table class="wp-list-table widefat fixed bsf-connect-optins bsf-connect-optins-campaign">
          <thead>
            <tr>
              <th scope="col" id="provider" class="manage-column column-provider <?php echo $sortingProviderClass; ?>">
              	<a href="?page=contact-manager&orderby=list-provider&<?php echo $orderLink; ?>&sq=<?php echo $searchKey; ?>&cont-page=<?php echo $page; ?>">
              		<span class="connects-icon-share"></span> <?php _e( "Service", "smile" ); ?>
                </a>
              </th>
              <th scope="col" id="list-id" class="manage-column column-id <?php echo $sortinglistNameClass; ?>">
              	 <a href="?page=contact-manager&orderby=list-name&<?php echo $orderLink; ?>&sq=<?php echo $searchKey; ?>&cont-page=<?php echo $page; ?>">
              		<span class="connects-icon-bar-graph-2"></span> <?php _e( "Campaign", "smile" ); ?>
              	 </a>
              </th>
              <th scope="col" class="manage-column column-provider <?php echo $sortingListClass; ?>">
              	<a href="?page=contact-manager&orderby=list&<?php echo $orderLink; ?>&sq=<?php echo $searchKey; ?>&cont-page=<?php echo $page; ?>">
              		<span class="connects-icon-align-justify"></span> <?php _e( "List", "smile" ); ?>
              	</a>
              </th>
              <th scope="col" id="contacts" class="manage-column column-contacts <?php echo $sortingContactsClass; ?>">
              	<a href="?page=contact-manager&orderby=contacts&<?php echo $orderLink; ?>&sq=<?php echo $searchKey; ?>&cont-page=<?php echo $page; ?>">
              		<span class="connects-icon-head"></span> <?php _e( "Contacts", "smile" ); ?>
              	</a>
              </th>
              <th scope="col" id="actions" class="manage-column column-actions sorting"><span class="connects-icon-cog"></span> <?php _e("Actions", "smile"); ?></th>
            </tr>
          </thead>
          <tbody id="the-list" class="smile-style-data">
            <?php

			  if( !empty( $smile_lists ) ){

				  foreach( $smile_lists as $key => $list ){
				  	$provider = $list['list-provider'];
						$list_name = $list['list-name'];
						$list_id = $list['list'];

						$mailer = str_replace(" ","_",strtolower( trim( $provider ) ) );
						$contacts = $list['contacts'];
						$provider_list_name = $list['provider_list'];
						$date = date("j M Y",strtotime($list['date']));
						$campaignDate = date("j M Y", strtotime($list['date']));
						$onclick = '';
						if( $contacts == 0 ){
							$onclick = ' onclick="alert(\''.__( "Contact list is empty.", "smile" ).'\'); return false;" ';
						}
						if( $provider == 'Convert Plug' ) {
							$provider_list_name = 'Default';
							$providerName = 'ConvertPlug';
						} else {
							$providerName = Smile_Framework::$addon_list[strtolower($provider)]['name'];
						}
					  ?>
            <tr>
              <td scope="col" class="manage-column column-provider <?php echo esc_attr( str_replace(" ", "-", strtolower( $provider ) ) ); ?>"><span>
              	<?php if( $contacts > 0 ){ ?>
                <a href="?page=contact-manager&view=contacts&list=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $providerName ); ?></a>
                <?php } else {
					echo esc_attr( $providerName );
			  }?>
                </span>
              </td>
              <td scope="col" class="manage-column column-id">
              <?php if( $contacts > 0 ){ ?>
              <a title="Created on <?php echo $campaignDate; ?>" href="?page=contact-manager&view=contacts&list=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $list_name ); ?></a>
              <?php } else {
					echo esc_attr( $list_name );
			  }?>
              </td>
              <td scope="col" class="manage-column column-list">
              	<?php
              	if( isset( Smile_Framework::$addon_list[strtolower($provider)]['mailer_type'] ) ) {
              		if( Smile_Framework::$addon_list[strtolower($provider)]['mailer_type'] == 'multiple' ) {
                    $str = array();
                    if( count( $provider_list_name ) > 0 && is_array( $provider_list_name ) ) {
                      foreach( $provider_list_name as $list_names ) {
                        $str[] = $list_names;
                      }
                      $first_tag = array_shift( $provider_list_name );
                      $tooltip = implode( ', ', $provider_list_name );
                      $tooltip_html = '<span data-position="top" class="cp-tooltip-icon has-tip" title="' . $tooltip . '"><a style="cursor: help;" href="javascript:void(0);">' . count( $provider_list_name ) . ' More</a></span>';
                      $first_tag = ( $first_tag != -1 ) ? $first_tag : 'No tags associated with this campaign.' ;
                      echo ( count( $provider_list_name ) > 1 ) ? $first_tag . ' & ' . $tooltip_html : $first_tag;
                    } else {
                      if( is_array( $provider_list_name ) ) {
                        echo 'No list.';
                      } else {
                        echo esc_attr( $provider_list_name );
                      }
                    }
              		}

              	} else {
                  if( $provider == 'ontraport' ) {
                    echo ( $list['list'] != '-1' ) ? esc_attr( $provider_list_name ) : 'No tags associated with this campaign.';
                  } else {
                   echo esc_attr( $provider_list_name );
                  }
              	} ?>
              </td>
              <td scope="col" class="manage-column column-contacts"><?php echo esc_attr( $contacts ); ?></td>
              <td class="actions column-actions" style="vertical-align: inherit;">
                <a class="action-list" href="<?php echo plugins_url( 'download.php?list_id='.$key, __FILE__ ); ?>"<?php echo $onclick; ?> target="_top"><i style="font-size: 17px;top: -1px;position: relative;" class="connects-icon-download"></i><span class="action-tooltip"><?php _e( "Export", "smile" ); ?></span></a>
              	<a class="action-list list-analytics" style="margin-left: 6px;" data-list-id="<?php echo $key; ?>"<?php echo $onclick; ?> href="?page=contact-manager&view=analytics&campaign=<?php echo $key; ?>"><i class="connects-icon-bar-graph-2"></i><span class="action-tooltip"><?php _e( "Analytics", "smile" ); ?></span></a>
              	<a class="action-list delete-list" style="margin-left: 6px;" data-list-id="<?php echo $key; ?>" data-list-mailer="<?php echo $mailer; ?>" href="#"><i class="connects-icon-trash"></i><span class="action-tooltip"><?php _e( "Delete", "smile" ); ?></span></a>
              </td>
            </tr>
            <?php
				  }
			  } else {
				  ?>
            <tr>
            	<?php if( isset( $_GET['sq'] ) && $_GET['sq'] !== '' ) { ?>
                	<th scope="col" class="manage-column bsf-connect-column-empty" colspan="5"><?php _e( "No results available. ", "smile" ); ?><a class="add-new-h2" style="position:relative;top:-2px;" href="?page=contact-manager" title="<?php _e( "back to campaign list", "smile" ); ?>"><?php _e( "back to campaign list", "smile" ); ?></a></th>
                <?php } else { ?>
  					<th scope="col" class="manage-column bsf-connect-column-empty cp-empty-graphic" colspan="5"><?php _e( "First time being here?", "smile" ); ?> <br><a class="add-new-h2" href="?page=contact-manager&view=new-list" title="<?php _e( "Create new campaign", "smile" ); ?>"><?php _e( "Awesome! Let's start with your first campaign", "smile" ); ?></a></th>
  				<?php } ?>
            </tr>
            <?php
			  }
		      ?>
          </tbody>
        </table>

        <!-- Start Pagination -->
	      <div class="row">
	        <div class="container" style="max-width:100% !important;width:100% !important;margin-top: 41px !important;">
	          <div class="col-sm-6">
	          	    <a class="button-primary bsf-connect-add-contact-list" href="?page=contact-manager&view=new-list" title="<?php _e( "Create new list", "smile" ); ?>"><?php _e( "Create New Campaign", "smile" ); ?></a>
        			<a class="button-primary bsf-connect-campaign-analytics" href="?page=contact-manager&view=analytics" title="<?php _e( "Analytics", "smile" ); ?>"><?php _e( "Analytics", "smile" ); ?></a>
	          </div><!-- .col-sm-6 -->
	          <div class="col-sm-6">
	            <?php
	            if( $total > $limit ) {
	              $basePageLink = '?page=contact-manager';
	              echo $Paginator->createLinks( $links, 'pagination bsf-cnt-pagi', '' , $sq, $basePageLink );
	            }
	            ?>
	          </div><!-- .col-sm-6 -->
	        </div><!-- .container -->
	      </div><!-- .row -->
	    <!-- End Pagination -->
      </div>
      <!-- bsf-connect-content -->

      	<!-- Start Search -->
	      <div class="row">
	        <div class="container" style="max-width:100% !important;width:100% !important;margin-top: 41px !important;">
	          <div class="col-sm-6">
	          	<?php if( $total > $limit ) { ?>
	            <p class="search-box">
	              <form method="post" class="bsf-cntlst-search">
	                <label class="screen-reader-text" for="post-search-input"><?php _e( "Search Contacts:", "smile" ); ?></label>
	                <input type="search" id="post-search-input" name="sq" value="<?php echo esc_attr($sq ); ?>">
	                <input type="submit" id="search-submit" class="button" value="Search">
	              </form>
	            </p>
	            <?php } ?>
	          </div><!-- .col-sm-6 -->
	          <div class="col-sm-6">

	          </div><!-- .col-sm-6 -->
	        </div><!-- .container -->
	      </div><!-- .row -->
	    <!-- End Search -->

    </div>
    <!-- bend-content-wrap -->
  </div>
  <!-- wrap-container -->
</div>
<!-- bend -->
<script type="text/javascript">

jQuery(".delete-list").click(function(e){
	e.preventDefault();

	var action = 'cp_is_list_assigned';
	var list_id = jQuery(this).data('list-id');
	var data = {
		list_id: list_id,
		action: action
	};
	var $this = jQuery(this);

	jQuery.ajax({
			url: ajaxurl,
			data: data,
			method: "POST",
			dataType: "JSON",
			success: function(result){

				if( result.message == 'no' ) {
					swal({
						title: "<?php _e( "Are you sure?", "smile" ); ?>",
						text: "<?php _e( "You will not be able to recover this list!", "smile" ); ?>",
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "<?php _e( "Yes, delete it!", "smile" ); ?>",
						cancelButtonText: "<?php _e( "No, cancel it!", "smile" ); ?>",
						closeOnConfirm: false,
						closeOnCancel: false,
						showLoaderOnConfirm: true
					},
					function(isConfirm){
						if (isConfirm) {
							jQuery(document).trigger('trashStyle',[$this]);
						} else {
							swal("<?php _e( "Cancelled", "smile" ); ?>", "<?php _e( "Your campaign is safe :)", "smile" ); ?>", "error");
						}
					});
				} else {

					var assigned_to_list = result.assigned_to;
					var styleCount       = result.style_count;
					var ulString = '<ul>';
					jQuery.each( assigned_to_list, function( index, value ) {
						if( index > 2 ) {
							return false;
						}
					  	jQuery.each( value , function( style, link ) {
					  		ulString += "<li><a target='_blank' href='"+link+"'>"+style+"</a></li>";
					  	});
					});

					if( assigned_to_list.length > 3 ) {
						ulString += "<li>& more ...</li>";
					}
					ulString += '</ul>';

					if(styleCount > 1 ) {
						var styleCountStr = styleCount+" Styles -";
					} else {
						var styleCountStr = styleCount+" Style -";
					}

					swal({
						title: "<?php _e( "Error!", "smile" ); ?>",
						html: true,
						text: "<?php _e( "You can not delete this campaign as it is being used in ", "smile" ); ?>"+styleCountStr+ulString+"<?php _e( "Please change submission settings of above and try again.", "smile" ); ?>",
						type: "error",
					});
					return false;
				}

			},
			error: function(error){
				console.log(error);
			}
	});
});

jQuery(document).on("trashStyle", function(e,$this){
	var ok = true;
	if( ok ){
		var action = 'cp_trash_list';
		var list_id = $this.data('list-id');
		var list_mailer = $this.data('list-mailer');
		var data = {action:action, list_id:list_id, mailer: list_mailer};
		var msg = jQuery(".msg");
		jQuery.ajax({
			url: ajaxurl,
			data: data,
			method: "POST",
			dataType: "JSON",
			success: function(result){
				console.log(result);
				if( result.status == "success" ){
					swal({
						title: "<?php _e( "Removed!", "smile" ); ?>",
						text: "<?php _e( "The campaign list you have selected is removed.", "smile" ); ?>",
						type: "success",
						timer: 2000,
						showConfirmButton: false
					});
				} else {
					swal({
						title: "<?php _e( "Error!", "smile" ); ?>",
						text: "<?php _e( "Something went wrong! Please try again.", "smile" ); ?>",
						type: "error",
						timer: 2000,
						showConfirmButton: false
					});
				}
				setTimeout(function(){
						document.location = document.location;
				},800);

			},
			error: function(error){
				console.log(error);
			}
		});
	}
});

jQuery(document).on("focus",'.bsf-cntlst-top-search-input', function(){
    jQuery(".bsf-contact-list-top-search").addClass('bsf-cntlist-top-search-act');
});

jQuery(document).on("focusout",'.bsf-cntlst-top-search-input', function(){
	jQuery(".bsf-contact-list-top-search").removeClass('bsf-cntlist-top-search-act');
});

jQuery(document).on("click",".bsf-cntlst-top-search-submit", function(){
  jQuery('.bsf-cntlst-top-search').submit();
});

jQuery( document ).ready(function() {
	if( jQuery('.bsf-contact-list-top-search').hasClass('bsf-cntlist-top-search-act') )  {
		jQuery('.bsf-cntlst-top-search-input').focus().trigger('click');
	}
	jQuery('.has-tip').frosty();
});
</script>
