<?php
/**
 * Template Name: WP Estimation & Payment Forms Preview
 *
 * @package WordPress
 * @subpackage WP Estimation & Payment Forms
 */
$lfb= LFB_Core::instance(__FILE__, '1.0');
$formID = $_GET['form'];
$form = $lfb->getFormDatas($formID);
wp_register_style($lfb->_token . '-reset', esc_url($lfb->assets_url) . 'css/reset.css', array(), $lfb->_version);
wp_register_style($lfb->_token . '-bootstrap', esc_url($lfb->assets_url) . 'css/bootstrap.min.css', array(), $lfb->_version);
wp_register_style($lfb->_token . '-flat-ui', esc_url($lfb->assets_url) . 'css/flat-ui_frontend.css', array(), $lfb->_version);
wp_register_style($lfb->_token . '-colpick', esc_url($lfb->assets_url) . 'css/colpick.min.css', array(), $lfb->_version);
wp_register_style($lfb->_token . '-dropzone', esc_url($lfb->assets_url) . 'css/dropzone.min.css', array(), $lfb->_version);
wp_register_style($lfb->_token . '-estimationpopup', esc_url($lfb->assets_url) . 'css/lfb_forms.min.css', array(), $lfb->_version);
wp_enqueue_style($lfb->_token . '-reset');
wp_enqueue_style($lfb->_token . '-dropzone');
wp_enqueue_style($lfb->_token . '-colpick');
wp_enqueue_style($lfb->_token . '-bootstrap');
wp_enqueue_style($lfb->_token . '-flat-ui');
wp_enqueue_style($lfb->_token . '-estimationpopup');

// scripts
wp_register_script($lfb->_token . '-bootstrap-switch', esc_url($lfb->assets_url) . 'js/bootstrap-switch.js', array($lfb->_token . '-bootstrap'), $lfb->_version);
 wp_register_script($lfb->_token . '-touch-punch', esc_url($lfb->assets_url) . 'js/jquery.ui.touch-punch.min.js', array("jquery-ui-core", "jquery-ui-slider", "jquery-ui-position", "jquery-ui-datepicker",), $lfb->_version);
wp_enqueue_script($lfb->_token . '-touch-punch');
wp_register_script($lfb->_token . '-bootstrap', esc_url($lfb->assets_url) . 'js/bootstrap.min.js', array($lfb->_token . '-touch-punch'), $lfb->_version, true);
wp_enqueue_script($lfb->_token . '-bootstrap');
wp_enqueue_script($lfb->_token . '-bootstrap-switch');
wp_register_script($lfb->_token . '-dropzone', esc_url($lfb->assets_url) . 'js/dropzone.min.js', array('jquery'), $lfb->_version);
wp_enqueue_script($lfb->_token . '-dropzone');
wp_register_script($lfb->_token . '-colpick', esc_url($lfb->assets_url) . 'js/colpick.min.js', array('jquery'), $lfb->_version);
wp_enqueue_script($lfb->_token . '-colpick');
if($form->use_stripe){
    wp_enqueue_script($lfb->_token . '-stripe', 'https://js.stripe.com/v2/', true, 3);
}
  wp_register_script($lfb->_token . '-uidatepickerlang', esc_url($lfb->assets_url) . 'js/jquery-ui-i18n.min.js', array($lfb->_token . '-bootstrap-switch'), $lfb->_version);
  wp_enqueue_script($lfb->_token . '-uidatepickerlang');
  wp_register_script($lfb->_token . '-estimationpopup', esc_url($lfb->assets_url) . 'js/lfb_form.min.js', array($lfb->_token . '-uidatepickerlang'), $lfb->_version);
wp_enqueue_script($lfb->_token . '-estimationpopup');

$lfb->currentForms[] = $formID;
add_action('wp_head', array($lfb, 'options_custom_styles'));
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
$js_data = array();

if ($form) {
    // check gmap
    if($form->gmap_key != ""){
     $chkMap = false;
        $table_name = $wpdb->prefix . "wpefc_items";
        $itemsQt = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=$formID AND useDistanceAsQt=1 ORDER BY id ASC");
        if(count($itemsQt)>0){
            $chkMap = true;
        }
        if(!$chkMap){
        $itemsCalcul = $wpdb->get_results("SELECT * FROM $table_name WHERE useCalculation=1 AND formID=$formID ORDER BY id ASC");
        foreach ($itemsCalcul as $itemCalcul) {
              $lastPos = 0;
                while (($lastPos = strpos($itemCalcul->calculation, 'distance_', $lastPos)) !== false) {
                    $chkMap = true;
                 $lastPos += 9;
                }
         }
        }
        if($chkMap){
            wp_register_script($lfb->_token . '-gmap', 'http://maps.googleapis.com/maps/api/js?key='.$form->gmap_key,array());
            wp_enqueue_script($lfb->_token . '-gmap');
        }
    }
                            
    if (is_plugin_active('gravityforms/gravityforms.php') && $form->gravityFormID > 0) {
        gravity_form_enqueue_scripts($form->gravityFormID, true);
        if (is_plugin_active('gravityformssignature/signature.php')) {
            wp_register_script('gforms_signature', esc_url($lfb->assets_url) . '../../gravityformssignature/super_signature/ss.js', array("gform_gravityforms"), $lfb->_version);
            wp_enqueue_script('gforms_signature');
        }
    } 
    if (!$form->colorA || $form->colorA == "") {
        $form->colorA = $settings->colorA;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . "wpefc_links";
    $links = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID);
        
    $table_name = $wpdb->prefix . "wpefc_redirConditions";
    $redirections = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID);                            
                            
    if($form->decimalsSeparator == ""){
        $form->decimalsSeparator = '.';
    }
    $usePdf = 0;
    if($form->sendPdfCustomer || $form->sendPdfAdmin){
        $usePdf = 1;
    }

    $js_data[] = array(
    'currentRef' => 0,
    'ajaxurl' => admin_url('admin-ajax.php'),
    'initialPrice' => $form->initial_price,
    'max_price' => $form->max_price,
    'currency' => $form->currency,
    'percentToPay'=>$form->percentToPay,
    'currencyPosition' => $form->currencyPosition,
    'intro_enabled' => $form->intro_enabled,
    'save_to_cart' => $form->save_to_cart,
    'colorA' => $form->colorA,
    'close_url' => $form->close_url,
    'animationsSpeed' => $form->animationsSpeed,
    'email_toUser' => $form->email_toUser,
    'showSteps' => $form->showSteps,
    'formID' => $form->id,
    'gravityFormID' => $form->gravityFormID,
    'showInitialPrice' => $form->show_initialPrice,
    'disableTipMobile' => $form->disableTipMobile,
    'legalNoticeEnable'=>$form->legalNoticeEnable,
    'links'=>$links,
    'redirections'=>$redirections,
    'useRedirectionConditions'=>$form->useRedirectionConditions,
    'usePdf'=>$usePdf,
    'txt_yes' => __('Yes', 'lfb'),
    'txt_no' => __('No', 'lfb'),
    'txt_lastBtn'=>$form->last_btn,
    'txt_btnStep'=>$form->btn_step,
    'dateFormat'=>$lfb->dateFormatToDatePickerFormat(get_option('date_format')),
    'datePickerLanguage'=>$form->datepickerLang,
    'thousandsSeparator' => $form->thousandsSeparator,
    'decimalsSeparator' => $form->decimalsSeparator,
    'millionSeparator'=>$form->millionSeparator,
    'summary_hideQt'=>$form->summary_hideQt,
    'summary_hideZero'=>$form->summary_hideZero,
    'summary_hidePrices'=>$form->summary_hidePrices,
    'groupAutoClick'=>$form->groupAutoClick,
    'filesUpload_text'=>$form->filesUpload_text,
    'filesUploadSize_text'=>$form->filesUploadSize_text,
    'filesUploadType_text'=>$form->filesUploadType_text,
    'filesUploadLimit_text'=>$form->filesUploadLimit_text,
    'sendContactASAP'=>$form->sendContactASAP,
    'showTotalBottom'=>$form->showTotalBottom,
    'stripePubKey' => $form->stripe_publishKey,
    'scrollTopMargin'=>$form->scrollTopMargin,
    'redirectionDelay'=>$form->redirectionDelay,
    'gmap_key'=>$form->gmap_key,
    'txtDistanceError'=>$form->txtDistanceError
  );
}
wp_localize_script($lfb->_token . '-estimationpopup', 'wpe_forms', $js_data);
add_action('wp_head', array($lfb, 'options_custom_styles'));

get_header();
function lfb_content($content) {
  $content = '[estimation_form form_id="'.$_GET['form'].'" fullscreen="true"]';
  return do_shortcode( $content );
}
add_filter( 'the_content', 'lfb_content', 20 );
echo '<div id="lfb_preview">';
the_content();
echo '</div>';
 wp_footer();
?>
