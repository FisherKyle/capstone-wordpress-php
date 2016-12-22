<?php
    require_once CP_BASE_DIR.'admin/contacts/views/cp-paginator.php';

    //  Remove All Styles
    $remove_styles = ( isset( $_GET['remove-styles'] ) ) ? $_GET['remove-styles'] : 'false';
    if( $remove_styles == 'true' ) {
        delete_option('smile_info_bar_styles');
        delete_option('info_bar_variant_tests');
        delete_option('smile_style_analytics');
        echo '<div style="background: #2F9DD2;color: #FFF;padding: 16px;margin-top: 20px;margin-right: 20px;text-align: center;font-size: 16px;border-radius: 4px;">Removed All Styles..!</div>';
    }

    $prev_styles = get_option('smile_info_bar_styles');
    $variant_tests = get_option('info_bar_variant_tests');
    $analyticsData = get_option('smile_style_analytics');

    if(is_array($prev_styles)) {
      foreach($prev_styles as $key => $style){
        $impressions = 0;
        $multivariant = false;
        $hasVariants = false;
        $style_id = $style['style_id'];

        if(isset($style['multivariant'])) {
            $multivariant = true;
        }

        if( $variant_tests ) {
          if ( array_key_exists($style_id,$variant_tests) && !empty($variant_tests[$style_id]) ) {
            $hasVariants = true;
          }
        }

        $variants = array();
        $live = '0';

        if($hasVariants) {
            foreach ($variant_tests[$style_id] as $value) {
              $settings = unserialize($value['style_settings']);
              if( $settings['live'] == '1' )  {
                  $live = '1';
              }
              $variants[] = $value['style_id'];
            }

            foreach ($variants as $value) {
               if( isset($analyticsData[$value]) ) {
                  foreach ($analyticsData[$value] as $value1) {
                    $impressions = $impressions + $value1['impressions'];
                  }
               }
            }
        }

        if(!$multivariant) {
          if(isset($analyticsData[$style_id])) {
            foreach ($analyticsData[$style_id] as $key1 => $value2) {
              $impressions = $impressions + $value2['impressions'];
            }
          }
        }

        $style_settings = unserialize($prev_styles[$key]['style_settings']);
        if( isset( $style_settings['live'] ) && $style_settings['live'] == '1' )
            $live = '1';

        if($hasVariants) {
          $info_barStatus = $live;
        } else {
          $info_barStatus = ( isset( $style_settings['live'] ) ) ? $style_settings['live'] : '';
        }

        if( $info_barStatus == '2' )
          $status = '1';
        else if( $info_barStatus == '1' )
          $status = '2';
        else
          $status = '0';

        $prev_styles[$key]['info_barStatus'] = intval($info_barStatus);
        $prev_styles[$key]['status'] = intval($status);
        $prev_styles[$key]['impressions'] = $impressions;
      }
      $prev_styles = array_reverse( $prev_styles, true );
   }

   $limit      = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 10;
   $page       = ( isset( $_GET['cont-page'] ) ) ? $_GET['cont-page'] : 1;
   $links      = ( isset( $_GET['links'] ) ) ? $_GET['links'] : 1;
   $orderby    = ( isset( $_GET['orderby'] ) ) ? $_GET['orderby'] : false;
   $order      = ( isset( $_GET['order'] ) ) ? $_GET['order'] : false;
   $total      = count($prev_styles);
   $maintainKeys = false;

   if( isset( $_POST['sq'] ) ) {
      $searchKey =  esc_attr($_POST['sq']);
      $redirectString = '?page=smile-info_bar-designer&limit='.$limit.'&sq='.$searchKey.'&cont-page=1';
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

    $sortingStyleNameClass = $sortinglistImpClass =  $sortingStatusClass = "sorting";

    // define sorting class
    if ( isset( $_GET['orderby'] ) ) {
      switch( $_GET['orderby'] ) {
        case "style_name" :
          $sortingStyleNameClass  = 'sorting-'.$_GET['order'];
        break;
        case "impressions" :
          $sortinglistImpClass = 'sorting-'.$_GET['order'];
        break;
        case "status" :
          $sortingStatusClass = 'sorting-'.$_GET['order'];
        break;
      }
    }

   if( isset($_GET['sq']) && !empty($_GET['sq']) )
      $sq = $_GET['sq'];
   else
      $sq = $searchKey;

   if( isset( $_POST['sq'] ) && $_POST['sq'] == '' )
       $sq = '';

   $searchInParams = array('style_name','style_id');

   if ($prev_styles) {
      $Paginator = new Paginator( $prev_styles );
      $result = $Paginator->getData( $limit , $page ,$orderby, $order , $sq, $searchInParams, $maintainKeys );
      $prev_styles = $result->data;
   }

?>
<div class="wrap about-wrap bend cp-info_bar-main">
<div class="wrap-container">
   <div class="bend-heading-section">
      <h1><?php echo __( "Info Bar Designer", "smile" ); ?>
    	<a class="add-new-h2" href="?page=smile-info_bar-designer&style-view=new" title="<?php echo __( "Create New Info Bar", "smile" ); ?>"><?php echo __( "Create New Info Bar", "smile" ); ?></a>
        <span class="cp-loader spinner" style="float: none;"></span>
	   </h1>

      <a href="?page=smile-info_bar-designer&style-view=new" class="bsf-connect-download-csv" style="margin-right: 25px !important;"><i class="connects-icon-square-plus" style="line-height: 30px;font-size: 22px;"></i>
        <?php _e( "Create New Info Bar", "smile" ); ?>
      </a>
      <a href="?page=smile-info_bar-designer&style-view=analytics"  style="margin-right: 25px !important;" class="bsf-connect-download-csv"><i class="connects-icon-bar-graph-2" style="line-height: 30px;"></i>
        <?php _e( "Analytics", "smile" ); ?>
      </a>
      <a href="#" style="margin-right: 25px !important;" class="bsf-connect-download-csv cp-import-style" data-module="info_bar" data-uploader_title="<?php _e( "Upload Your Exported file", "smile" ); ?>" data-uploader_button_text="<?php _e( "Import Style", "smile" ); ?>" onclick_="jQuery('.cp-import-overlay, .cp-style-importer').fadeIn('fast');"><i class="connects-icon-upload" style="line-height: 30px;font-size: 22px;"></i>
        <?php _e( "Import Info Bar", "smile" ); ?>
      </a>
      <?php $searchActiveClass = $sq !== '' ? "bsf-cntlist-top-search-act" : ''; ?>
      <span class="bsf-contact-list-top-search <?php echo $searchActiveClass; ?>"><i class="connects-icon-search" style="line-height: 30px;"></i>
        <form method="post" class="bsf-cntlst-top-search">
          <input class="bsf-cntlst-top-search-input" type="search" id="post-search-input" name="sq" placeholder="<?php _e( "Search", "smile" ); ?>" value="<?php echo esc_attr($sq ); ?>">
          <i class="bsf-cntlst-top-search-submit connects-icon-search"></i>
        </form>
      </span><!-- .bsf-contact-list-top-search -->

      <div class="message"></div>
   </div>
   <!-- bend-heading-section -->

   <div class="bend-content-wrap" style="margin-top: 40px;">
      <hr class="bsf-extensions-lists-separator" style="margin: 22px 0px 30px 0px;">
      </hr>
      <div class="container">
      <div id="smile-stored-styles">
         <table class="wp-list-table widefat fixed cp-list-optins cp-info_bar-list-optins">
            <thead>
               <tr>
                 <th scope="col" id="style-name" class="manage-column column-style <?php echo $sortingStyleNameClass; ?>">
                  <a href="?page=smile-info_bar-designer&orderby=style_name&<?php echo $orderLink; ?>&sq=<?php echo $searchKey; ?>&cont-page=<?php echo $page; ?>">
                 <span class="connects-icon-ribbon"></span>
                   <?php _e( "Info Bar Name", "smile" ); ?></a></th>
                 <th scope="col" id="impressions" class="manage-column column-impressions <?php echo $sortinglistImpClass; ?>">
                  <a href="?page=smile-info_bar-designer&orderby=impressions&<?php echo $orderLink; ?>&sq=<?php echo $searchKey; ?>&cont-page=<?php echo $page; ?>">
                 <span class="connects-icon-disc"></span>
                   <?php _e( "Impressions", "smile" ); ?></a></th>
                 <th scope="col" id="status" class="manage-column column-status <?php echo $sortingStatusClass; ?>"><a href="?page=smile-info_bar-designer&orderby=status&<?php echo $orderLink; ?>&sq=<?php echo $searchKey; ?>&cont-page=<?php echo $page; ?>">
                 <span class="connects-icon-toggle"></span>
                   <?php _e( "Status", "smile" ); ?></a></th>
                 <th scope="col" id="actions" class="manage-column column-actions" style="min-width: 300px;"><span class="connects-icon-cog"></span>
                   <?php _e( "Actions", "smile" ); ?></th>
               </tr>
            </thead>
            <tbody id="the-list" class="smile-style-data">
            <?php

            if(is_array($prev_styles) && !empty($prev_styles)){
               foreach($prev_styles as $key => $style){
                  $style_name = $style['style_name'];
                  $style_id = $style['style_id'];
                  $impressions = $style['impressions'];
                  $variants = array();
                  $hasVariants = false;
                  if( $variant_tests ) {
                     if ( array_key_exists($style_id,$variant_tests) && !empty($variant_tests[$style_id]) ) {
                        $hasVariants = true;
                        foreach ($variant_tests[$style_id] as $value) {
                          $variants[] = $value['style_id'];
                        }
                     }
                  }

                  $style_settings = unserialize($style['style_settings']);
  			          $exp_settings = array();
			            foreach( $style_settings as $title => $value ){
				            if( !is_array( $value ) ){
					            $value = urldecode($value);
    				  	      $exp_settings[$title] = htmlentities(stripslashes(utf8_encode($value)), ENT_QUOTES);
      				      } else {
  					          foreach( $value as $ex_title => $ex_val ) {
  						          $val[$ex_title] = $ex_val;
  					          }
  					          $exp_settings[$title] = str_replace('"','&quot;',$val);
        				   }
      			      }
  			         $export = $style;
  			         $export['style_settings'] = $exp_settings;

                  $theme = $style_settings['style'];
                  $multivariant = isset($style['multivariant']) ? true : false;
                  $live = isset( $style['info_barStatus'] ) ? (int)$style['info_barStatus'] : '';
                  $isScheduled = false;
                  $status = '';

                  if($hasVariants) {
                    $status .= "<a href=?page=smile-info_bar-designer&style-view=variant&variant-style=".urlencode( $style_id )."&style=".urlencode( stripslashes($style_name ) )."&theme=".urlencode( $theme ).">";
                  } else {
                    $status .= '<span class="change-status">';
                  }

                  if( $live == 1) {
                     $status .=  '<span data-live="1" class="cp-status cp-main-variant-status"><i class="connects-icon-play"></i><span>'.__( "Live", "smile" ).'</span></span>';
                  } elseif( $live == 0 ){
                     $status .= '<span data-live="0" class="cp-status cp-main-variant-status"><i class="connects-icon-pause"></i><span>'.__( "Pause", "smile" ).'</span></span>';
                  } else {
                     $scheduleData = unserialize($style['style_settings']);
                     if( isset($scheduleData['schedule']) ) {
                        $scheduledArray = $scheduleData['schedule'];
                        if( is_array($scheduledArray) ) {
                           $startDate = date("j M Y ",strtotime($scheduledArray['start']));
                           $endDate = date("j M Y ",strtotime($scheduledArray['end']));
                           $first = date('j-M-Y (h:i A) ', strtotime($scheduledArray['start']));
                           $second = date('j-M-Y (h:i A) ', strtotime($scheduledArray['end']));
                           $title = "Scheduled From ".$first." To ".$second;
                        }
                     } else {
                        $title = '';
                     }

                     $status .= '<span data-live="2" class="cp-status"><i class="connects-icon-clock"></i><span title="'.$title.'">'.__( "Scheduled", "smile" ).'</span></span>';
                  }

                  if($hasVariants) {
                    $status .= "</a>";
                  }

                  if(!$hasVariants) {
                    $status .= '<ul class="manage-column-menu">';
            				  if( $live !== 1 && $live !== "1" ) {
                        $status .= '<li><a href="#" class="change-status" data-style-id="'.$style_id.'" data-live="1" data-option="smile_info_bar_styles"><i class="connects-icon-play"></i><span>'.__( "Live", "smile" ).'</span></a></li>';
            				  }
            				  if( $live !== 0 && $live !== "0" && $live !== "") {
            				  	$status .= '<li><a href="#" class="change-status" data-style-id="'.$style_id.'" data-live="0" data-option="smile_info_bar_styles"><i class="connects-icon-pause"></i><span>'.__( "Pause", "smile" ).'</span></a></li>';
            				  }
            				  if( $live !== 2 && $live !== "2" ) {
                        $status .= '<li><a href="#" class="change-status" data-style-id="'.$style_id.'" data-live="2" data-option="smile_info_bar_styles" data-schedule="1"><i class="connects-icon-clock"></i><span>'.__( "Schedule", "smile" ).'</span></a></li>';
            				  }
                    $status .= '</ul>';
                  }
                  $status .= '</span>';
            ?>
            <tr id="<?php echo $key; ?>" class="ui-sortable-handle <?php if($hasVariants) { echo 'cp-variant-exist'; } ?>">
               <?php if( $multivariant || $hasVariants ) { ?>
                  <td class="name column-name"><a href="?page=smile-info_bar-designer&style-view=variant&variant-style=<?php echo urlencode( $style_id ); ?>&style=<?php echo urlencode( $style_name ); ?>&theme=<?php echo urlencode( $theme ); ?>"  ><?php echo "Variants of ".urldecode($style_name); ?> </a></td>
               <?php  } else { ?>
                  <td class="name column-name"><a href="?page=smile-info_bar-designer&style-view=edit&style=<?php echo urlencode( $style_id ); ?>&theme=<?php echo urlencode( $theme ); ?>" target ="_blank" ><?php echo urldecode($style_name); ?> </a></td>
                  <?php } ?>
                  <td class="column-impressions"><?php echo $impressions; ?></td>
                  <td class="column-status"><?php echo $status; ?></td>
                  <td class="actions column-actions">
                   <a class="action-list" data-style="<?php echo urlencode( $style_id ); ?>" data-option="smile_info_bar_styles" href="?page=smile-info_bar-designer&style-view=variant&variant-style=<?php echo urlencode( $style_id ); ?>&style=<?php echo urlencode( stripslashes($style_name ) ); ?>&theme=<?php echo urlencode( $theme ); ?>" ><i class="connects-icon-share"></i><span class="action-tooltip">
                   <?php if($hasVariants) { ?>
                      <?php _e( "See Variants", "smile" ); ?>
                   <?php } else { ?>
                      <?php _e( "Create Variant", "smile" ); ?>
                   <?php } ?>
                   </span></a>
                   <?php if(!$hasVariants) { ?>
                     <a class="action-list copy-style-icon" data-style="<?php echo urlencode( $style_id ); ?>" data-module="info_bar" data-option="smile_info_bar_styles" style="margin-left: 25px;" href="#"><i class="connects-icon-paper-stack" style="font-size: 20px;"></i><span class="action-tooltip">
                     <?php _e( "Duplicate Info bar", "smile" ); ?>
                     </span></a>
                   <?php } ?>
                   <?php
                    if($hasVariants)  {
                        $styleForAnalytics = implode("||",$variants);
                        if(!$multivariant) {
                          $styleForAnalytics .= "||".$style_id;
                        }
                        $styleArr = explode("||",$styleForAnalytics);
                        if( count($styleArr) > 1 )
                            $compFactor = 'imp';
                        else
                            $compFactor = 'impVsconv';
                    }  else {
                       $styleForAnalytics = $style_id;
                       $compFactor = 'impVsconv';
                    }
                    ?>
                    <a class="action-list" data-style="<?php echo urlencode( $style_id ); ?>" data-option="smile_info_bar_styles" style="margin-left: 25px;" href="?page=smile-info_bar-designer&style-view=analytics&compFactor=<?php echo $compFactor; ?>&style=<?php echo urlencode( $styleForAnalytics ); ?>"><i class="connects-icon-bar-graph-2"></i><span class="action-tooltip">
                   <?php _e( "View Analytics", "smile" ); ?>
                   </span></a>
                   <a class="action-list cp-export" style="margin-left: 25px;" href="<?php echo plugins_url('download.php?style_id='.urlencode( $style_id ).'&style_name='.urldecode( $style_name ),__FILE__); ?>" target="_top" data="text/txt;charset=utf-8,<?php echo esc_attr( json_encode( $export ) ); ?>" data-download="<?php echo 'cp_info_bar_style-'.esc_attr( $style_name ); ?>.txt"><i class="connects-icon-download"></i><span class="action-tooltip">
                   <?php _e( "Export Settings", "smile" ); ?>
                    <?php
                    if( !$multivariant && !$hasVariants ) {
                        echo apply_filters( 'cp_before_delete_action', $style_settings , 'info_bar' );
                    }
                   ?>
                   </span></a> <a class="action-list trash-style-icon" data-delete="hard" data-variantoption="info_bar_variant_tests" data-style="<?php echo urlencode( $style_id ); ?>" data-option="smile_info_bar_styles" style="margin-left: 25px;" href="#"><i class="connects-icon-trash"></i><span class="action-tooltip">
                   <?php _e( "Delete Info Bar", "smile" ); ?>
                   </span></a>
                   </td>
            </tr>
            <?php

          }
        } else {
          ?>
            <tr>
              <?php if( isset( $_GET['sq'] ) && $_GET['sq'] !== '' ) { ?>
                <th scope="col" colspan="4" class="manage-column cp-list-empty"><?php echo __( 'No results available.', 'smile' ); ?><a class="add-new-h2" href="?page=smile-info_bar-designer" title="<?php _e( "Back to info_bar list", "smile" ); ?>">
                  <?php _e( "Back to Info Bar list", "smile" ); ?>
                  </a>
                </th>
              <?php } else { ?>
                <th scope="col" colspan="4" class="manage-column cp-list-empty cp-empty-graphic"><?php echo __( 'First time being here?', 'smile' ); ?><br> <a class="add-new-h2" href="?page=smile-info_bar-designer&style-view=new" title="<?php _e( "Create New Info Bar", "smile" ); ?>">
                  <?php _e( "Awesome! Let's start with your first info bar", "smile" ); ?>
                  </a>
                </th>
              <?php } ?>
            </tr>
            <?php
        }
          ?>
          </tbody>
        </table>

        <!-- Pagination & Search -->
        <div class="row">
          <div class="container" style="max-width:100% !important;width:100% !important;">
            <div class="col-sm-6">
              <a class="button-primary cp-add-new-style-bottom" href="?page=smile-info_bar-designer&style-view=new" title="<?php _e( "Create New Info Bar", "smile" ); ?>">
                <?php _e( "Create New Info Bar", "smile" ); ?>
              </a>
              <a class="button-primary cp-style-analytics-bottom" href="?page=smile-info_bar-designer&style-view=analytics" title="<?php _e( "Analytics", "smile" ); ?>">
                <?php _e( "Analytics", "smile" ); ?>
              </a>
            </div><!-- .col-sm-6 -->
            <div class="col-sm-6">
              <?php
                  if( $total > $limit ) {
                    $basePageLink = '?page=smile-info_bar-designer';
                    echo $Paginator->createLinks( $links, 'pagination bsf-cnt-pagi', '' , $sq, $basePageLink );
                  }
              ?>
            </div><!-- .col-sm-6 -->
          </div><!-- .container -->
        </div><!-- .row -->

      </div>
      <!-- #smile-stored-styles -->
    </div>
    <!-- .container -->

    <!-- Pagination & Search -->
    <div class="row">
      <div class="container" style="max-width:100% !important;width:100% !important;">
        <div class="col-sm-6">
          <?php if( $total > $limit ) { ?>
          <p class="search-box">
            <form method="post" class="bsf-cntlst-search">
              <label class="screen-reader-text" for="post-search-input"><?php _e( "Search Contacts", "smile" ); ?>:</label>
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

  </div>
  <!-- .bend-content-wrap -->
</div>
<!-- .wrap-container -->
<?php
    $timezone = '';
    $timezone_settings = get_option('convert_plug_settings');

    $timezone_name = $timezone_settings['cp-timezone'];
    if( $timezone_name == 'wordpress' ){
        $timezone = 'wordpress';
    } else if( $timezone_name == 'system' ){
       $timezone = 'system';
    } else {
        $timezone = "wordpress";
    }

    $date = current_time( 'm/d/Y h:i A');
    echo' <input type="hidden" id="cp_timezone_name" class="form-control cp_timezone" value="'.esc_attr($timezone).'" />';
    echo' <input type="hidden" id="cp_currenttime" class="form-control cp_currenttime" value="'.esc_attr($date).'" />';


?>
<!-- scheduler popup -->
<div class="cp-schedular-overlay">
  <div class="cp-scheduler-popup">
    <div class="cp-scheduler-close"> <span class="connects-icon-cross"></span> </div>
    <div class="cp-row">
      <div class="schedular-title">
        <h3>
          <?php _e( "Schedule This Info Bar", "smile" );   ?>
        </h3>
      </div>
    </div>
    <!-- cp-row -->
    <div class="cp-row">
      <div class="scheduler-container">
        <div class="container cp-start-time">
          <div class="col-md-6">
            <h3>
              <?php _e( "Enter Starting Time", "smile" ); ?>
            </h3>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <div class="input-group date">
                <input type="text" id="cp_start_time" class="form-control cp_start" value="" />
                <span class="input-group-addon"><span class="connects-icon-clock"></span></span> </div>
            </div>
          </div>
        </div>
        <div class="container cp-end-time">
          <div class="col-md-6">
            <h3>
              <?php _e( "Enter Ending Time", "smile" ); ?>
            </h3>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <div class="input-group date">
                <input type="text" id="cp_end_time" class="form-control cp_end" value=" "/>
                <span class="input-group-addon"><span class="connects-icon-clock"></span></span> </div>
            </div>
            <!-- form-group -->
          </div>
        </div>
        <!-- cp-end-time -->
      </div>
      <!-- scheduler-container -->
    </div>
    <!-- cp-row -->
    <div class="cp-row">
      <div class="cp-actions">
        <div class="cp-action-buttons">
          <button class="button button-primary cp-schedule-btn">
          <?php _e( "Schedule Info Bar", "smile" ); ?>
          </button>
          <button class="button button-primary cp-schedule-cancel" onclick="jQuery(document).trigger('dismissPopup')">
          <?php _e( "Cancel", "smile" ); ?>
          </button>
        </div>
      </div>
    </div>
    <!-- cp-row -->
  </div>
  <!-- .cp-schedular-popup -->
</div>
<!-- .cp-schedular-overlay -->
</div>
<!-- .wrap -->


<style type="text/css">
.cp-import-overlay {
	background-color: rgba(0, 0, 0, 0.8);
	width: 100%;
	height: 100%;
	position: fixed;
	top: 0;
	left: 0;
	z-index: 99999;
	display:none;
}
.cp-style-importer {
	display:none;
	max-width: 400px;
	background-color: #FFF;
	top: 50%;
	position: absolute;
	left: 50%;
	z-index: 999999;
	padding: 15px;
	margin-left: -200px;
	border-radius: 3px;
}
</style>
<!--  cp style import -->
<div class="cp-import-overlay"></div>
<div class="cp-style-importer">
	<div class="cp-importer-close"> <span class="connects-icon-cross"></span> </div>
	<div class="cp-import-container">
		<div class="cp-import-info_bar">
        	<div class="cp-row">
            	<div class="cp-info_bar-heading">
            		<h3><?php _e( "Import Info Bar", "smile" ); ?></h3>
                </div>
            </div>
        	<div class="cp-row">
            	<div class="cp-import-input">
                <input type="file" id="cp-import" />
                <button class="button button-primary"><?php _e( "Import", "smile" ); ?></button>
                </div>
            </div>
		</div>
	</div>
</div>

<script type="text/javascript">

   jQuery(document).ready(function(){

      var colImpressions = jQuery('.column-impressions').outerHeight();

      jQuery("span.change-status").css({
         'height' : colImpressions+"px",
         'line-height' : colImpressions+"px"
      });

      var timestring = '';
      timestring = jQuery(".cp_timezone").val();

      var currenttime = '';
      if( timestring == 'system' ){
         currenttime = new Date();
      }else {
         currenttime = jQuery(".cp_currenttime").val();
      }
      //convert date to jquery date format
       var date2 = new Date(currenttime);
           date2 = new Date(date2.getTime() + 1*60000);

      jQuery('#cp_start_time').datetimepicker({
             sideBySide: true,
             minDate: currenttime,
             icons: {
               time: 'connects-icon-clock',
               date: 'dashicons dashicons-calendar-alt',
               up: 'dashicons dashicons-arrow-up-alt2',
               down: 'dashicons dashicons-arrow-down-alt2',
               previous: 'dashicons dashicons-arrow-left-alt2',
               next: 'dashicons dashicons-arrow-right-alt2',
               today: 'dashicons dashicons-screenoptions',
               clear: 'dashicons dashicons-trash',
             },
      });
      jQuery("#cp_start_time").on("dp.change", function (e) {
          jQuery('#cp_end_time').data("DateTimePicker").minDate(e.date);
        });

      jQuery('#cp_end_time').datetimepicker({
             sideBySide: true,
             minDate: currenttime,
             icons: {
               time: 'connects-icon-clock',
               date: 'dashicons dashicons-calendar-alt',
               up: 'dashicons dashicons-arrow-up-alt2',
               down: 'dashicons dashicons-arrow-down-alt2',
               previous: 'dashicons dashicons-arrow-left-alt2',
               next: 'dashicons dashicons-arrow-right-alt2',
               today: 'dashicons dashicons-screenoptions',
               clear: 'dashicons dashicons-trash',
             },
      });

      if( jQuery('.bsf-contact-list-top-search').hasClass('bsf-cntlist-top-search-act') )  {
         jQuery('.bsf-cntlst-top-search-input').focus().trigger('click');
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

</script>
