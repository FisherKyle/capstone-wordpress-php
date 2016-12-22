<?php
$variant_style = $_GET['variant-style'];
$parent_style = $_GET['style'];
$theme = $_GET['theme'];

$analyticsData = get_option('smile_style_analytics' );
$variant_tests = get_option('slide_in_variant_tests');
$prev_styles = get_option('smile_slide_in_styles');

$variants = array();
$analyticsLink = '#';
$multivariant = false;
if( $variant_tests ) {
  if(isset($variant_tests[$variant_style])) {
    foreach ( $variant_tests[$variant_style] as $value ) {
      $variants[] = $value['style_id'];
    }
  }
}

if($prev_styles) {
  foreach($prev_styles as $key => $style){
    if( $style['style_id'] == $variant_style ) {
      if(isset($style['multivariant']))
        $multivariant = true;
    }
  }
}

if(!$multivariant) {
  $variants[] = $variant_style;
}

$styleForAnalytics = implode("||",$variants);

if( count($variants) > 1 )
  $compFactor = 'imp';
else
  $compFactor = 'impVsconv';

if( count($variants) > 0 ) {
  $analyticsLink = "?page=smile-slide_in-designer&style-view=analytics&compFactor=".$compFactor."&style=".urlencode( $styleForAnalytics );
}
?>

<div class="wrap about-wrap bend cp-slidein-main">
  <div class="wrap-container">
    <div class="bend-heading-section">
      <h1 style="font-size: 38px;">
        <?php _e( "Variants for", "smile" ); ?>
        <?php
        $rand = substr(md5(uniqid()),rand(0,26),5);
        $dynamic_style_name = 'cp_id_'.$rand;
        ?>
        <span class="cp-strip-text" style="max-width: 460px;top: 10px;" title="<?php echo stripslashes(urldecode( $parent_style )); ?>"><?php echo stripslashes(urldecode( $parent_style )); ?> </span>
        <a class="add-new-h2" href="?page=smile-slide_in-designer&style-view=variant&variant-test=edit&action=new&style_id=<?php echo $variant_style; ?>&variant-style=<?php echo $dynamic_style_name; ?>&style=<?php echo urlencode(stripslashes($parent_style) ); ?>&theme=<?php echo $theme; ?>" title="Add New Variant">
            <?php _e( "Create New Variant", "smile" ); ?>
        </a>
        <a class="add-new-h2" href="?page=smile-slide_in-designer"><?php _e('Back to Slide In List', 'smile'); ?></a>
      </h1>
      <a href="?page=smile-slide_in-designer&style-view=variant&variant-test=edit&action=new&style_id=<?php echo $variant_style; ?>&variant-style=<?php echo $dynamic_style_name; ?>&style=<?php echo $parent_style; ?>&theme=<?php echo $theme; ?>" title="Create New Variant" class="bsf-connect-download-csv" style="margin-right: 25px !important;"><i class="connects-icon-square-plus" style="line-height: 30px;font-size: 22px;"></i>
        <?php _e( "Create New Variant", "smile" ); ?>
      </a>
      <a href="<?php echo $analyticsLink; ?>"  style="margin-right: 25px !important;" class="bsf-connect-download-csv"><i class="connects-icon-bar-graph-2" style="line-height: 30px;"></i>
        <?php _e( "Analytics", "smile" ); ?>
      </a>
      <a href="?page=smile-slide_in-designer" style="margin-right: 25px !important;" class="bsf-connect-download-csv"><i class="connects-icon-reply" style="line-height: 30px;font-size: 22px;"></i>
        <?php _e( "Back to Slide In List", "smile" ); ?>
      </a>

      <div class="message"></div>
    </div>
    <!-- bend-heading section -->

    <div class="bend-content-wrap" style="margin-top: 40px;">
      <hr class="bsf-extensions-lists-separator" style="margin: 22px 0px 30px 0px;">
      </hr>
      <div class="container">
        <div id="smile-stored-styles">
          <table class="wp-list-table widefat fixed cp-list-optins cp-slidein-list-optins cp-variants-list">
            <thead>
              <tr>
                <th scope="col" id="style-name" class="manage-column column-style"><span class="connects-icon-ribbon"></span>
                  <?php _e( "Variant Name", "smile" ); ?></th>
                <th scope="col" id="impressions" class="manage-column column-impressions"><span class="connects-icon-disc"></span>
                  <?php _e( "Impressions", "smile" ); ?></th>
                <th scope="col" id="status" class="manage-column column-status"><span class="connects-icon-toggle"></span>
                  <?php _e( "Status", "smile" ); ?></th>
                <th scope="col" id="actions" class="manage-column column-actions" style="min-width: 300px;"><span class="connects-icon-cog"></span>
                  <?php _e( "Actions", "smile" ); ?></th>
              </tr>
            </thead>
            <tbody id="the-list" class="smile-style-data smile-style-slidein-variant">
            <?php
              $variant_style = $_GET['variant-style'];

              if(is_array($prev_styles) && !empty($prev_styles)){
                foreach($prev_styles as $key => $style){
                  $style_name = $style['style_name'];
                  $style_id = $style['style_id'];
                  if( $style_id == $variant_style ) {

                    $impressions = 0;

                    if(isset($analyticsData[$style_id])) {
                      foreach ($analyticsData[$style_id] as $key => $value) {
                         $impressions = $impressions + $value['impressions'];
                      }
                    }

                    $style_settings = unserialize($style['style_settings']);
                    $theme = $style_settings['style'];
                    $live = (int)$style_settings['live'];
                    $status = '';
                    if( $live == 1) {
                      $status .= '<span class="change-status"><span data-live="1" class="cp-status"><i class="connects-icon-play"></i><span>'.__( "Live", "smile" ).'</span></span>';
                    } elseif( $live == 0 ){
                      $status .= '<span class="change-status"><span data-live="0" class="cp-status"><i class="connects-icon-pause"></i><span>'.__( "Pause", "smile" ).'</span></span>';
                    } else {
                      $status .= '<span class="change-status"><span data-live="2" class="cp-status"><i class="connects-icon-clock"></i><span>'.__( "Scheduled", "smile" ).'</span></span>';
                    }
                    $status .= '<ul class="manage-column-menu">';
          			    if( $live !== 1 && $live !== "1" ) {
          				    $status .= '<li><a href="#" class="change-status" data-style-id="'.$style_id.'" data-live="1" data-option="smile_slide_in_styles"><i class="connects-icon-play"></i><span>'.__( "Live", "smile" ).'</span></a></li>';
          			    }
          			    if( $live !== 0 && $live !== "" && $live !== "0" ) {
          				    $status .= '<li><a href="#" class="change-status" data-style-id="'.$style_id.'" data-live="0" data-option="smile_slide_in_styles"><i class="connects-icon-pause"></i><span>'.__( "Pause", "smile" ).'</span></a></li>';
          			    }
          			    if( $live !== 2 && $live !== "2" ) {
          				    $status .= '<li><a href="#" class="change-status" data-style-id="'.$style_id.'" data-live="2" data-option="smile_slide_in_styles" data-schedule="1"><i class="connects-icon-clock"></i><span>'.__( "Schedule", "smile" ).'</span></a></li>';
          			    }
          			    $status .= '</ul>';
                    $status .= '</span>';
                    ?>
                    <?php if( !isset($style['multivariant']) ) { ?>
                        <tr id="<?php echo $key; ?>" class="ui-sortable-handle">
                          <td class="name column-name"><a target="_blank" href="?page=smile-slide_in-designer&style-view=edit&style=<?php echo urlencode( $style_id ); ?>&theme=<?php echo urlencode( $theme ); ?>"> <?php echo urldecode($style_name); ?> </a></td>
                          <td class="column-impressions" style="vertical-align: inherit;"><?php echo $impressions; ?></td>
                          <td class="column-status" style="vertical-align: inherit;"><?php echo $status; ?></td>
                          <td class="actions column-actions" style="vertical-align: inherit;">
                            <a class="action-list copy-style-icon" data-style="<?php echo $style_id; ?>" data-variant-style="<?php echo esc_attr( $variant_style ); ?>" data-module="slide_in"  data-stylescreen="multivariant" data-option="slide_in_variant_tests" href="#"><i class="connects-icon-paper-stack" style="font-size: 20px;"></i><span class="action-tooltip">
                            <?php _e( "Duplicate Slide In", "smile" ); ?>
                            </span></a>
                            <a class="action-list" style="margin-left: 25px;" data-style="<?php echo urlencode( $style_id ); ?>" data-option="smile_slide_in_styles" href="?page=smile-slide_in-designer&compFactor=impVsconv&style-view=analytics&style=<?php echo urlencode( $style_id ); ?>"><i class="connects-icon-bar-graph-2"></i><span class="action-tooltip">
                            <?php _e( "View Analytics", "smile" ); ?>
                            </span></a>
                            <?php echo apply_filters( 'cp_before_delete_action', $style_settings, 'slide_in' ); ?>
                            <a class="action-list trash-style-icon" data-delete="soft" data-variantoption="slide_in_variant_tests" data-style="<?php echo $style_id; ?>" data-option="smile_slide_in_styles" style="margin-left: 25px;" href="#"><i class="connects-icon-trash"></i><span class="action-tooltip">
                            <?php _e( "Delete Slide In", "smile" ); ?>
                            </span></a>
                          </td>
                        </tr>
                    <?php } ?>
                    <?php
                  }
                }
              }
              ?>
              <?php
        $variant_tests = isset($variant_tests[$variant_style]) ? $variant_tests[$variant_style] : '';

        if(is_array($variant_tests) && !empty($variant_tests)){
          $variant_tests = array_reverse($variant_tests);
          foreach($variant_tests as $key => $variant_test){
          $style_name = $variant_test['style_name'];
          $style_id = $variant_test['style_id'];
          $impressions = 0;

          if(isset($analyticsData[$style_id])) {
            foreach ($analyticsData[$style_id] as $key => $value) {
              $impressions = $impressions + $value['impressions'];
            }
          }

          $style_settings = unserialize($variant_test['style_settings']);
          $theme = $style_settings['style'];
          $live = $style_settings['live'];
          $status = '';
          if( $live == 1) {
				    $status .= '<span class="change-status"><span data-live="1" class="cp-status"><i class="connects-icon-play"></i><span>'.__( "Live", "smile" ).'</span></span>';
			    } elseif( $live == 0 ){
  				  $status .= '<span class="change-status"><span data-live="0" class="cp-status"><i class="connects-icon-pause"></i><span>'.__( "Pause", "smile" ).'</span></span>';
			    } else {
				    $status .= '<span class="change-status"><span data-live="2" class="cp-status"><i class="connects-icon-clock"></i><span>'.__( "Scheduled", "smile" ).'</span></span>';
    			}
          $status .= '<ul class="manage-column-menu">';
    		  if( $live !== 1 && $live !== "1" ) {
    			   $status .= '<li><a href="#" class="change-status" data-style-id="'.$style_id.'" data-variant="slide_in_variant_tests" data-live="1" data-option="slide_in_variant_tests"><i class="connects-icon-play"></i><span>'.__( "Live", "smile" ).'</span></a></li>';
    		  }
    		  if( $live !== 0 && $live !== "" && $live !== "0" ) {
    			   $status .= '<li><a href="#" class="change-status" data-style-id="'.$style_id.'" data-variant="slide_in_variant_tests" data-live="0" data-option="slide_in_variant_tests"><i class="connects-icon-pause"></i><span>'.__( "Pause", "smile" ).'</span></a></li>';
    		  }
    		  if( $live !== 2 && $live !== "2" ) {
    			   $status .= '<li><a href="#" class="change-status" data-style-id="'.$style_id.'" data-variant="slide_in_variant_tests" data-live="2" data-option="slide_in_variant_tests" data-schedule="1"><i class="connects-icon-clock"></i><span>'.__( "Schedule", "smile" ).'</span></a></li>';
    		  }
    		  $status .= '</ul>';
          $status .= '</span>';
          ?>
              <tr id="<?php echo $key; ?>" class="ui-sortable-handle">
                <td class="name column-name"><a target="_blank" href="?page=smile-slide_in-designer&style-view=variant&variant-test=edit&variant-style=<?php echo $style_id; ?>&style=<?php echo stripslashes($style_name); ?>&parent-style=<?php echo urlencode( stripslashes($_GET['style']) ); ?>&style_id=<?php echo $variant_style; ?>&theme=<?php echo esc_attr( $theme ); ?>"> <?php echo urldecode(stripslashes($style_name)); ?> </a></td>
                <td class="column-impressions" style="vertical-align: inherit;"><?php echo $impressions; ?></td>
                <td class="column-status" style="vertical-align: inherit;"><?php echo $status; ?></td>
                <td class="actions column-actions" style="vertical-align: inherit;">
                  <a class="action-list copy-style-icon" data-style="<?php echo $style_id; ?>" data-variant-style="<?php echo esc_attr( $variant_style ); ?>" data-module="slide_in"  data-option="slide_in_variant_tests" data-stylescreen="multivariant" href="#"><i class="connects-icon-paper-stack" style="font-size: 20px;"></i><span class="action-tooltip">
                  <?php _e( "Duplicate Slide In", "smile" ); ?>
                  </span></a>
                  <a class="action-list" data-style="<?php echo urlencode( $style_id ); ?>" data-option="smile_slide_in_styles" style="margin-left: 25px;" href="?page=smile-slide_in-designer&style-view=analytics&compFactor=impVsconv&style=<?php echo urlencode( $style_id ); ?>"><i class="connects-icon-bar-graph-2"></i><span class="action-tooltip">
                  <?php _e( "View Analytics", "smile" ); ?>
                  </span></a>
                  <?php echo apply_filters( 'cp_before_delete_action', $style_settings, 'slide_in' ); ?>
                  <a class="action-list trash-style-icon" data-delete="hard" data-variantoption="slide_in_variant_tests" data-style="<?php echo $style_id; ?>" data-option="slide_in_variant_tests" style="margin-left: 25px;" href="#"><i class="connects-icon-trash"></i><span class="action-tooltip">
                  <?php _e( "Delete Slide In", "smile" ); ?>
                  </span></a>
                </td>
              </tr>
              <?php
          }
        } else {
          ?>
              <tr>
                <th class="cp-list-empty cp-empty-graphic" colspan="4"><?php echo __('FIRST TIME BEING HERE?','smile'); ?><br/><a class="add-new-h2" href="?page=smile-slide_in-designer&style-view=variant&variant-test=edit&action=new&style_id=<?php echo $variant_style; ?>&variant-style=<?php echo $dynamic_style_name; ?>&theme=<?php echo $theme; ?>" title="<?php _e( "Create new variant Test", "smile" ); ?>">
                  <?php _e( "Awesome! Let's start with your first variant", "smile" ); ?>
                  </a></th>
              </tr>
              <?php
        }
          ?>
            </tbody>
          </table>
          <a class="button-primary cp-add-new-style-bottom" href="?page=smile-slide_in-designer&style-view=variant&variant-test=edit&action=new&style_id=<?php echo $variant_style; ?>&variant-style=<?php echo $dynamic_style_name; ?>&theme=<?php echo $theme; ?>" title="<?php _e( "Create New variant", "smile" ); ?>">
          <?php _e( "Create New Variant", "smile" ); ?>
          </a> </div>
        <!-- smile-stored-styles -->
      </div>
      <!-- .container -->
    </div>
    <!-- .bend-content-wrap -->

    <!-- scheduler popup -->
    <div class="cp-schedular-overlay">
      <div class="cp-scheduler-popup">
        <div class="cp-scheduler-close"> <span class="connects-icon-cross"></span> </div>
        <div class="cp-row">
          <div class="schedular-title">
            <h3>
              <?php _e( "Schedule This Slide In", "smile" ); ?>
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
                    <input type="text" id="cp_start_time" class="form-control cp_start" />
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
                    <input type="text" id="cp_end_time" class="form-control cp_end" />
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
              <?php _e( "Schedule Slide In", "smile" ); ?>
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
  <!-- .wrap-container -->
</div>
<!-- .wrap -->
<script type="text/javascript">
jQuery(document).ready(function(){

  var colImpressions = jQuery('.column-impressions').outerHeight();

  jQuery("span.change-status").css({
    'height' : colImpressions+"px",
    'line-height' : colImpressions+"px"
  });

  jQuery('#cp_start_time').datetimepicker({
    sideBySide: true,
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
  jQuery('#cp_end_time').datetimepicker({
    sideBySide: true,
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
});
</script>
