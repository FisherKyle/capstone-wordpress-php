<?php
global $cp_analytics_start_time,$cp_analytics_end_time;
if(isset($_GET['campaign']) ) {
  $listIDs = $_GET['campaign'];
  $listArray = explode("||", $listIDs);
} else {
  $listArray = array('all');
}

$sDate = ( isset($_GET['sd']) && !empty($_GET['sd']) ) ? $_GET['sd'] : $cp_analytics_start_time;
$eDate = ( isset($_GET['ed']) && !empty($_GET['ed']) ) ? $_GET['ed'] : $cp_analytics_end_time;
$chartType = ( isset($_GET['cType']) && !empty($_GET['cType']) ) ? $_GET['cType'] : 'line';
$smile_lists = get_option('smile_lists');

// to unset deactivated / inactive mailer addons
if( is_array($smile_lists) ) {
  foreach( $smile_lists as $key => $list ){
    $provider = $list['list-provider'];
    if( $provider !== 'Convert Plug' ) {
      if( !isset( Smile_Framework::$addon_list[$provider] ) && !isset( Smile_Framework::$addon_list[strtolower($provider)] ) ) {
        unset( $smile_lists[$key] );
      }
    }
  }
}

?>

<div class="wrap about-wrap bsf-connect bsf-connect-analytics bend">
  <div class="wrap-container">
    <div class="bend-heading-section">
      <h1><?php echo __( "Analytics", "smile" ); ?> <a class="add-new-h2" href="?page=contact-manager"><?php _e( 'Back to Campaigns List', 'smile' ); ?></a></h1>
      <div class="bend-head-logo"></div>
    </div>
    <!-- bend-heading section -->

    <div class="msg"></div>
    <div class="bend-content-wrap" style="position: relative;margin-top: 40px !important;">
     <div class="smile-absolute-loader smile-top-fix-loader" style="visibility: visible;-webkit-transition: visibility 100ms linear, background-color 100ms linear;
  -moz-transition: visibility 100ms linear, background-color 100ms linear;
  transition: visibility 100ms linear, background-color 100ms linear;">
      <div class="smile-loader">
        <div class="smile-loading-bar"></div>
        <div class="smile-loading-bar"></div>
        <div class="smile-loading-bar"></div>
        <div class="smile-loading-bar"></div>
      </div>
     </div>
      <hr class="bsf-extensions-lists-separator" style="margin: 22px 0px 45px 0px;">
      </hr>

      <div class="row cp-analytics-filter-section" style="display:none">
        <div class="container form-container analytics-form">
          <div class="col-sm-2 form-col-5">
            <label class="analytics-form-label"><?php _e('Select Campaign','smile'); ?></label>
            <select id="list-dropdown"  multiple >
              <option value="all" <?php if( in_array('all',$listArray)) echo "selected = 'selected'"; ?>><?php _e( "All Campaigns", "smile" ); ?></option>
              <?php foreach ($smile_lists as $key => $value) { ?>
              <option value="<?php echo $key; ?>" <?php if(in_array((string)$key,$listArray,true)) { echo "selected = 'selected'"; } ?>><?php echo $value['list-name']; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-sm-2 form-col-5">
            <label class="analytics-form-label"><?php _e('Start Date <span class="cp-analatics-italic">(dd-mm-yyyy)</span>','smile'); ?></label>
            <input type="text" placeholder="Start Date" id="cp-startDate" name="sDate" value="<?php echo esc_attr($sDate); ?>"/>
          </div>
          <div class="col-sm-2 form-col-5">
            <label class="analytics-form-label"><?php _e('End Date <span class="cp-analatics-italic">(dd-mm-yyyy)</span>','smile'); ?></label>
            <input type="text" placeholder="End Date" id="cp-endDate" name="eDate" value="<?php echo esc_attr($eDate); ?>"/>
          </div>
          <div class="col-sm-2 form-col-5">
            <label class="analytics-form-label"><?php _e('Graph Type','smile'); ?></label>
            <select id="cp-chart-type">
              <option value="line" <?php if($chartType == 'line') echo "selected='selected'"; ?> >Line</option>
              <option value="bar" <?php if($chartType == 'bar') echo "selected='selected'"; ?> >Bar</option>
              <option value="donut" <?php if($chartType == 'donut') echo "selected='selected'"; ?> >Donut</option>
              <option value="polararea" <?php if($chartType == 'polararea') echo "selected='selected'"; ?> >Polar Area</option>
            </select>
          </div>
          <div class="col-sm-2 form-col-5">
            <button class="button-primary cp-chart-submit" type="submit" id="submit-query">Submit</button>
          </div>
        </div>
        <!-- .form-container -->
      </div>
      <!-- .row -->

      <div class="row" style="padding-left: 15px;padding-right: 15px;">
          <div class="container cp-graph-area cp-hidden">
            <div class="col-lg-12 col-sm-12 cp-graph-width">
              <div id="canvas-holder" class="chart-holder" >
                <canvas id="line-chart" />
              </div>
              <div id="chartjs-tooltip"></div>
            </div>
            <div class="col-lg-12 col-sm-12">
              <div id="chart-legend"></div>
            </div>
        </div>
        <!-- .container -->
      </div>
      <!-- .row -->

    </div>
    <!-- .bend-content-wrap -->
  </div>
  <!-- .wrap-container -->
</div>
<!-- .wrap -->
