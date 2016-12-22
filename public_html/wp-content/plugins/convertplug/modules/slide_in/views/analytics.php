<?php
global $cp_analytics_start_time,$cp_analytics_end_time;
$styles = get_option('smile_slide_in_styles');
if(isset($_GET['style']) && !empty($_GET['style'])) {
  $style_id = $_GET['style'];
  $styleArray = explode("||", $style_id);
} else {
  $styleArray = array('all');
}

$smile_variant_tests = get_option('slide_in_variant_tests'); 


$sDate = ( isset($_GET['sd']) && !empty($_GET['sd']) ) ? $_GET['sd'] : $cp_analytics_start_time;
$eDate = ( isset($_GET['ed']) && !empty($_GET['ed']) ) ? $_GET['ed'] : $cp_analytics_end_time;
$chartType = ( isset($_GET['cType']) && !empty($_GET['cType']) ) ? $_GET['cType'] : 'line';
$compFactor = ( isset($_GET['compFactor']) && !empty($_GET['compFactor']) ) ? $_GET['compFactor'] : 'imp';
?>

<div class="wrap about-wrap bsf-connect bsf-connect-list bend">
  <div class="wrap-container">
    <div class="bend-heading-section">
      <h1><?php echo __( "Slide In Analytics", "smile" ); ?> <a class="add-new-h2" href="?page=smile-slide_in-designer"><?php _e('Back to Slide In List', 'smile'); ?></a></h1>
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
      <hr class="bsf-extensions-lists-separator" style="margin: 22px 0px 30px 0px;">
      </hr>
      <input type="hidden" id="cp-module" value="slide_in" >
      <div class="row cp-analytics-filter-section" style="display:none">
        <div class="container form-container analytics-form">
          <div class="col-sm-2">
            <label class="analytics-form-label"><?php _e('Select Slide In','smile'); ?></label>
            <select id="style-dropdown" multiple>
            <option value="all" <?php if( in_array('all',$styleArray)) echo "selected = 'selected'"; ?>>All Slide Ins</option>
            <?php foreach ($styles as $key => $value) { ?>
              <?php $styleName = urldecode($value['style_name']); ?>
              <?php if(!isset($value['multivariant'])) { ?>
                  <option value="<?php echo $value['style_id']; ?>" <?php if(in_array($value['style_id'],$styleArray)) echo "selected = 'selected'"; ?>><?php echo $styleName; ?></option>
              <?php } ?>
              <?php
              if(isset($value['style_id'])) {
                    if( is_array($smile_variant_tests)) { 
                      foreach($smile_variant_tests[$value['style_id']] as $key => $variant_test )  {
                           $style_name = $variant_test['style_name'];
                           $style_id = $variant_test['style_id']; ?>

                           <option data-variant='true' value="<?php echo  $style_id; ?>" <?php if(in_array($style_id,$styleArray)) echo "selected = 'selected'"; ?>><?php echo urldecode(stripslashes($style_name)); ?></option>";
                      <?php }
                    }
              } ?>
            <?php } ?>
            </select>
          </div>
          <div class="col-sm-2">
            <label class="analytics-form-label"><?php _e('Start Date <span class="cp-analatics-italic">(dd-mm-yyyy)</span>','smile'); ?></label>
            <input type="text" placeholder="Start Date" id="cp-startDate" name="sDate" value="<?php echo esc_attr($sDate); ?>"/>
          </div>
          <div class="col-sm-2">
            <label class="analytics-form-label"><?php _e('End Date <span class="cp-analatics-italic">(dd-mm-yyyy)</span>','smile'); ?></label>
            <input type="text" placeholder="End Date" id="cp-endDate" name="eDate" value="<?php echo esc_attr($eDate); ?>"/>
          </div>
          <div class="col-sm-2">
            <label class="analytics-form-label"><?php _e('Graph Type','smile'); ?></label>
            <select id="cp-chart-type">
              <option value="line" <?php if($chartType == 'line') echo "selected='selected'"; ?> >Line</option>
              <option value="bar" <?php if($chartType == 'bar') echo "selected='selected'"; ?> >Bar</option>
              <option value="donut" <?php if($chartType == 'donut') echo "selected='selected'"; ?> >Donut</option>
              <option value="polararea" <?php if($chartType == 'polararea') echo "selected='selected'"; ?> >Polar Area</option>
            </select>
          </div>
          <div class="col-sm-2">
            <label class="analytics-form-label"><?php _e('Comparison Factor','smile'); ?></label>
            <select id="cp-chart-comp-type">
              <?php if($compFactor == 'impVsconv') { ?>
              <option value="impVsconv"  selected='selected' ><?php _e( "Impression Vs Conversion", "smile" ); ?></option>
              <?php } ?>
              <option value="imp" <?php if($compFactor == 'imp') echo "selected='selected'"; ?> ><?php _e( "Impression", "smile" ); ?> </option>
              <option value="conv" <?php if($compFactor == 'conv') echo "selected='selected'"; ?> ><?php _e( "Conversion", "smile" ); ?></option>
              <option value="convRate" <?php if($compFactor == 'convRate') echo "selected='selected'"; ?> ><?php _e( "Conversion Rate", "smile" ); ?></option>
            </select>
          </div>
          <div class="col-sm-2">
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
