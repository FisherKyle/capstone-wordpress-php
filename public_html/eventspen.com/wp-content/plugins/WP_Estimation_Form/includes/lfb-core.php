<?php

if (!defined('ABSPATH'))
    exit;

class LFB_Core {

    /**
     * The single instance
     * @var    object
     * @access  private
     * @since    1.0.0
     */
    private static $_instance = null;

    /**
     * Settings class object
     * @var     object
     * @access  public
     * @since   1.0.0
     */
    public $settings = null;

    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_version;

    /**
     * The token.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_token;

    /**
     * The main plugin file.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $file;

    /**
     * The main plugin directory.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $dir;

    /**
     * The plugin assets directory.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_dir;

    /**
     * The plugin assets URL.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_url;

    /**
     * Suffix for Javascripts.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $templates_url;

    /**
     * Suffix for Javascripts.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $script_suffix;

    /**
     * For menu instance
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $menu;

    /**
     * For template
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $plugin_slug;

    /*
     *  Current forms on page
     */
    public $currentForms;
    
    /*
     *  Is analytics loaded ?
     */
    public $checkAnalytics = false;
    
    /*
     *  Analytics ID
     */
    public $analyticsID = '';

    /*
     * Must load or not the js files ?
     */
    private $add_script;

    /**
     * Constructor function.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function __construct($file = '', $version = '1.6.0') {
        $this->_version = $version;
        $this->_token = 'lfb';
        $this->plugin_slug = 'lfb';
        $this->currentForms = array();
        $this->checkedSc = false;

        $this->file = $file;
        $this->dir = dirname($this->file);
        $this->assets_dir = trailingslashit($this->dir) . 'assets';
        $this->assets_url = esc_url(trailingslashit(plugins_url('/assets/', $this->file)));
        $this->templates_url = esc_url(trailingslashit(plugins_url('/templates/', $this->file)));
        $upload_dir = wp_upload_dir();        
        if(!is_dir($upload_dir['basedir'].'/CostEstimationPayment')){
             mkdir($upload_dir['basedir'].'/CostEstimationPayment');
            chmod($upload_dir['basedir'].'/CostEstimationPayment', 0747);
        }
        $this->uploads_dir = $upload_dir['basedir'].'/CostEstimationPayment/';
        $this->uploads_url = $upload_dir['baseurl'].'/CostEstimationPayment/';   
        
        add_shortcode('estimation_form', array($this, 'wpt_shortcode'));
        add_action('wp_ajax_nopriv_lfb_cart_save', array($this, 'cart_save'));
        add_action('wp_ajax_lfb_cart_save', array($this, 'cart_save'));
        add_action('wp_ajax_nopriv_send_email', array($this, 'send_email'));
        add_action('wp_ajax_send_email', array($this, 'send_email'));
        add_action('wp_ajax_nopriv_get_currentRef', array($this, 'get_currentRef'));
        add_action('wp_ajax_get_currentRef', array($this, 'get_currentRef'));
        add_action('wp_ajax_nopriv_lfb_upload_form', array($this, 'uploadFormFiles'));
        add_action('wp_ajax_lfb_upload_form', array($this, 'uploadFormFiles'));
        add_action('wp_ajax_nopriv_lfb_removeFile', array($this, 'removeFile'));
        add_action('wp_ajax_lfb_removeFile', array($this, 'removeFile'));
        add_action('wp_ajax_nopriv_lfb_sendCt', array($this, 'sendContact'));
        add_action('wp_ajax_lfb_sendCt', array($this, 'sendContact'));
        add_action('wp_ajax_nopriv_lfb_applyCouponCode', array($this, 'applyCouponCode'));
        add_action('wp_ajax_lfb_applyCouponCode', array($this, 'applyCouponCode'));        

        add_action('wp_enqueue_scripts', array($this, 'frontend_enqueue_scripts'), 10, 1);        
        add_filter('the_posts', array($this, 'conditionally_add_scripts_and_styles'));
        if (isset($_GET['lfb_action']) && $_GET['lfb_action'] == 'preview') {
            add_filter('template_include', array($this, 'load_lfb_template'));
        }
        add_action('plugins_loaded', array($this, 'init_localization'));
        add_filter('query_vars', array($this, 'lfb_query_vars'));
        add_action('generate_rewrite_rules', array($this, 'lfb_rewrite_rules'));
        add_action('parse_request', array($this, 'lfb_parse_request'));
    }

    // adds plugin variable to allowed url variables
    public function lfb_query_vars($vars) {
        $new_vars = array('EPFormsBuilder');
        $vars = $new_vars + $vars;
        return $vars;
    }

    // execute url variables
    public function lfb_parse_request($wp) {
        if (array_key_exists('EPFormsBuilder', $wp->query_vars) && $wp->query_vars['EPFormsBuilder'] == 'paypal') {
            $this->cbb_proccess_paypal_ipn($wp);
        }
    }
    public function cbb_proccess_paypal_ipn($wp) {
        global $wpdb;
        require_once ('IpnListener.php');
        if(isset($_POST['item_number'])){
            $item_number = sanitize_text_field($_POST['item_number']);
            $table_name = $wpdb->prefix . "wpefc_logs";
            $logReq = $wpdb->get_results("SELECT * FROM $table_name WHERE ref='$item_number' LIMIT 1");
            if (count($logReq) > 0) {
                $log = $logReq[0];
            
                $table_name = $wpdb->prefix . "wpefc_forms";
                $formReq = $wpdb->get_results("SELECT * FROM $table_name WHERE id=".$log->formID." LIMIT 1");
                $form = $formReq[0];   
                $listener = new IpnListener();
                if ($form->paypal_useSandbox) {
                    $listener->use_sandbox = true;           
                }
                if($verified = $listener->processIpn()){} else {
                    
                    $transactionData = $listener->getPostData(); 
                    if($_POST['payment_status'] == 'Completed'){
                        if(!$log->checked){
                            $this->sendOrderEmail($item_number,$log->formID);    
                        }
                    }
                }                     
                
            }
        }
    }

   
    public function lfb_rewrite_rules($wp_rewrite) {
        $new_rules = array('EPFormsBuilder/paypal' => 'index.php?EPFormsBuilder=paypal');
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }

    /**
     * Load popup template.
     * @access  public
     * @since   1.0.0
     * @return void
     */
    public function load_lfb_template($template) {
        $file = plugin_dir_path(__FILE__) . '../templates/lfb-preview.php';
        if (file_exists($file)) {
            return $file;
        }
    }

    /*
     * Plugin init localization
     */

    public function init_localization() {
        $moFiles = scandir(trailingslashit($this->dir) . 'languages/');
        foreach ($moFiles as $moFile) {
            if (strlen($moFile) > 3 && substr($moFile, -3) == '.mo' && strpos($moFile, get_locale()) > -1) {
                load_textdomain('lfb', trailingslashit($this->dir) . 'languages/' . $moFile);
            }
        }
    }

    public function frontend_enqueue_styles($hook = '') {
        $settings = $this->getSettings();
        if ($settings->enabled || (isset($_GET['lfb_action']) && $_GET['lfb_action'] == 'preview')) {
            global $wp_styles;
            wp_register_style($this->_token . '-lfb-reset', esc_url($this->assets_url) . 'css/lfb_frontend-reset.css', array(), $this->_version);
            wp_enqueue_style($this->_token . '-lfb-reset');
        }
    }

    private function jsonRemoveUnicodeSequences($struct) {
        return preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($struct));
    }

  

    public function conditionally_add_scripts_and_styles($posts) {
        if (empty($posts))
            return $posts;
        global $wpdb;
        if(!$this->checkedSc){
            $shortcode_found = false;
            $form_id = 0;
            $this->currentForms[] = array();
            
            if(!isset($_GET['cornerstone_preview'])){
                foreach ($posts as $post) {
                    $lastPos = 0;
                    while (($lastPos = strpos($post->post_content, '[estimation_form', $lastPos)) !== false) {
                        $shortcode_found = true;
                        $this->checkedSc = true;
                        $pos_start = strpos($post->post_content, 'form_id="', $lastPos + 16) + 9;
                        // $pos_end=strpos($post->post_content, '"', strpos($post->post_content, 'form_id="', strpos($post->post_content, '[estimation_form') + 16) + 10)-1;
                        $pos_end = strpos($post->post_content, '"', $pos_start);
                        $form_id = substr($post->post_content, $pos_start, $pos_end - $pos_start);
                        if ($form_id && $form_id > 0 && !is_array($form_id)) {
                            $this->currentForms[] = $form_id;
                        } else {
                            $table_name = $wpdb->prefix . "wpefc_forms";
                            $formReq = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id ASC LIMIT 1");
                            if (count($formReq) > 0) {
                                $form = $formReq[0];
                                if(!in_array($form->id,$this->currentForms)){
                                    $this->currentForms[] = $form->id;       
                                }
                            }
                        }
                        $lastPos = $lastPos + 16;
                    }
                }
            }
            if(isset($_GET['cornerstone_preview'])){
		wp_register_style($this->_token . '-reset', esc_url($this->assets_url) . 'css/reset.min.css', array(), $this->_version);
                wp_register_style($this->_token . '-bootstrap', esc_url($this->assets_url) . 'css/bootstrap.min.css', array(), $this->_version);
                wp_register_style($this->_token . '-flat-ui', esc_url($this->assets_url) . 'css/flat-ui_frontend.min.css', array(), $this->_version);
                wp_register_style($this->_token . '-dropzone', esc_url($this->assets_url) . 'css/dropzone.min.css', array(), $this->_version);
                wp_register_style($this->_token . '-colpick', esc_url($this->assets_url) . 'css/colpick.min.css', array(), $this->_version);

                wp_register_style($this->_token . '-estimationpopup', esc_url($this->assets_url) . 'css/lfb_forms.min.css', array(), $this->_version);
                wp_enqueue_style($this->_token . '-reset');
                wp_enqueue_style($this->_token . '-bootstrap');
                wp_enqueue_style($this->_token . '-flat-ui');
                wp_enqueue_style($this->_token . '-dropzone');
                wp_enqueue_style($this->_token . '-colpick');
                wp_enqueue_style($this->_token . '-estimationpopup');
            } else if(!$shortcode_found && defined('CNR_DEV')){
                $table_name = $wpdb->prefix . "wpefc_forms";
                $formReq = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id ASC");     
                if(count($formReq)>0) {
                    $shortcode_found = true;
                    $this->checkedSc = true;
                    foreach ($formReq as $form) {
                        if(!in_array($form->id,$this->currentForms)){
                            $this->currentForms[] = $form->id;       
                        }
                    }                
                }           
            }
            
            //loadAllPages
            $table_name = $wpdb->prefix . "wpefc_forms";
            $formReq = $wpdb->get_results("SELECT * FROM $table_name WHERE loadAllPages=1 ORDER BY id ASC");     
            if(count($formReq)>0) {
                $shortcode_found = true;
                $this->checkedSc = true;
                foreach ($formReq as $form) {
                    if(!in_array($form->id,$this->currentForms)){
                        $this->currentForms[] = $form->id;                          
                    }              
                }                
            }           

            if ($shortcode_found && count($this->currentForms) > 0) {
                $settings = $this->getSettings();
                

                // styles
                wp_register_style($this->_token . '-reset', esc_url($this->assets_url) . 'css/reset.min.css', array(), $this->_version);
                wp_register_style($this->_token . '-bootstrap', esc_url($this->assets_url) . 'css/bootstrap.min.css', array(), $this->_version);
                wp_register_style($this->_token . '-flat-ui', esc_url($this->assets_url) . 'css/flat-ui_frontend.min.css', array(), $this->_version);
                wp_register_style($this->_token . '-colpick', esc_url($this->assets_url) . 'css/colpick.min.css', array(), $this->_version);
                wp_register_style($this->_token . '-dropzone', esc_url($this->assets_url) . 'css/dropzone.min.css', array(), $this->_version);
                wp_register_style($this->_token . '-estimationpopup', esc_url($this->assets_url) . 'css/lfb_forms.min.css', array(), $this->_version);
                wp_enqueue_style($this->_token . '-reset');
                wp_enqueue_style($this->_token . '-bootstrap');
                wp_enqueue_style($this->_token . '-flat-ui');
                wp_enqueue_style($this->_token . '-colpick');
                wp_enqueue_style($this->_token . '-dropzone');
                wp_enqueue_style($this->_token . '-estimationpopup');

                // scripts
                wp_register_script($this->_token . '-bootstrap-switch', esc_url($this->assets_url) . 'js/bootstrap-switch.min.js', array($this->_token . '-bootstrap'), $this->_version, true);
                wp_register_script($this->_token . '-touch-punch', esc_url($this->assets_url) . 'js/jquery.ui.touch-punch.min.js', array("jquery-ui-core", "jquery-ui-slider", "jquery-ui-position", "jquery-ui-datepicker"), $this->_version);
                wp_enqueue_script($this->_token . '-touch-punch');
                wp_register_script($this->_token . '-bootstrap', esc_url($this->assets_url) . 'js/bootstrap.min.js', array($this->_token . '-touch-punch'), $this->_version, true);
                wp_enqueue_script($this->_token . '-bootstrap');
                wp_enqueue_script($this->_token . '-bootstrap-switch');
                wp_register_script($this->_token . '-colpick', esc_url($this->assets_url) . 'js/colpick.min.js', array('jquery'), $this->_version);
                wp_enqueue_script($this->_token . '-colpick');
                wp_register_script($this->_token . '-dropzone', esc_url($this->assets_url) . 'js/dropzone.min.js', array('jquery'), $this->_version);
                wp_enqueue_script($this->_token . '-dropzone');
               
                wp_register_script($this->_token . '-uidatepickerlang', esc_url($this->assets_url) . 'js/jquery-ui-i18n.min.js', array($this->_token . '-bootstrap-switch'), $this->_version, true);
                wp_enqueue_script($this->_token . '-uidatepickerlang');
                wp_register_script($this->_token . '-estimationpopup', esc_url($this->assets_url) . 'js/lfb_form.min.js', array($this->_token . '-uidatepickerlang'), $this->_version, true);
                wp_enqueue_script($this->_token . '-estimationpopup');

                include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                $js_data = array();
                $formsDone = array();
                
                foreach ($this->currentForms as $formID) {

                    if ($formID > 0 && !is_array($formID)) {
                        if(!in_array($formID,$formsDone)){
                            $formsDone[] = $formID;
                        $form = $this->getFormDatas($formID);

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
                                    wp_register_script($this->_token . '-gmap', 'http://maps.googleapis.com/maps/api/js?key='.$form->gmap_key,array());
                                    wp_enqueue_script($this->_token . '-gmap');
                                }
                            }
                            
                            
                            if($form->analyticsID != ""){
                                $this->analyticsID = $form->analyticsID;
                                add_action('wp_footer', array($this, 'add_googleanalytics'));
                            }
                            if (is_plugin_active('gravityforms/gravityforms.php') && $form->gravityFormID > 0 && !$this->is_enqueued_script($this->_token . '-estimationpopup')) {
                                gravity_form_enqueue_scripts($form->gravityFormID, true);
                                if (is_plugin_active('gravityformssignature/signature.php')) {
                                    wp_register_script('gforms_signature', esc_url($this->assets_url) . '../../gravityformssignature/super_signature/ss.js', array("gform_gravityforms"), $this->_version);
                                    wp_enqueue_script('gforms_signature');
                                }
                            }
                            if (!$form->colorA || $form->colorA == "") {
                                $form->colorA = $settings->colorA;
                            } 
                            if($form->use_stripe){
                                wp_enqueue_script($this->_token . '-stripe', 'https://js.stripe.com/v2/', true, 3);
                            }

                            $table_name = $wpdb->prefix . "wpefc_links";
                            $links = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID);

                            $table_name = $wpdb->prefix . "wpefc_redirConditions";
                            $redirections = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID);
                            
                            if ($form->decimalsSeparator == "") {
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
                                'percentToPay' => $form->percentToPay,
                                'currency' => $form->currency,
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
                                'legalNoticeEnable' => $form->legalNoticeEnable,
                                'links' => $links,
                                'redirections'=>$redirections,
                                'useRedirectionConditions'=>$form->useRedirectionConditions,
                                'usePdf'=>$usePdf,
                                'txt_yes' => __('Yes', 'lfb'),
                                'txt_no' => __('No', 'lfb'),
                                'txt_lastBtn' => $form->last_btn,
                                'txt_btnStep' => $form->btn_step,
                                'dateFormat' => $this->dateFormatToDatePickerFormat(get_option('date_format')),
                                'datePickerLanguage' => $form->datepickerLang,
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
                        }
                    }
                }
                wp_localize_script($this->_token . '-estimationpopup', 'wpe_forms', $js_data);

                add_action('wp_head', array($this, 'options_custom_styles'));
            }
        
        }

        return $posts;
    }
    private function is_enqueued_script($script)
    {
        return isset( $GLOBALS['wp_scripts']->registered[ $script ] );
    }


    public function dateFormatToDatePickerFormat($dateFormat) {
        $chars = array(
            'd' => 'dd', 'j' => 'd', 'l' => 'DD', 'D' => 'D',
            'm' => 'mm', 'n' => 'm', 'F' => 'MM', 'M' => 'M',
            'Y' => 'yy', 'y' => 'y',
        );
        return strtr((string) $dateFormat, $chars);
    }
    
    public function add_googleanalytics(){
        if(!$this->checkAnalytics){
            $this->checkAnalytics = true;
            echo "<script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', '".$this->analyticsID."', 'auto');
            ga('send', 'pageview');
          </script>";
        }
    }

    /*
     * Shortcode to integrate a form in a page
     */
    public function wpt_shortcode($attributes, $content = null) {
        global $wpdb;
        $response = "";
        $popup = false;
        $fullscreen = false;
        extract(shortcode_atts(array(
            'form' => 0,
            'height' => 1000,
            'popup' => false,
            'fullscreen' => false,
            'form_id' => 0 ), $attributes));
        if (is_numeric($height)) {
            $height .= 'px';
        }
        if ($form_id == 0) {
            $table_name = $wpdb->prefix . "wpefc_forms";
            $formReq = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id ASC LIMIT 1");
            $form = $formReq[0];
            $form_id = $form->id;
        }
        if ($form_id != "" && $form_id > 0 && !is_array($form_id)) {
            $table_name = $wpdb->prefix . "wpefc_forms";
            $forms = array();
            $formReq = $wpdb->get_results("SELECT * FROM $table_name WHERE id=" . $form_id . " LIMIT 1");
            if(count($formReq)>0){
            $form = $formReq[0];
            //$form=$formReq->form_page_id;
            $settings = $this->getSettings();
            $fields = $this->getFieldDatas($form->id);
            $steps = $this->getStepsData($form->id);
            $items = $this->getItemsData($form->id);

            if (!$form->save_to_cart) {
                $form->save_to_cart = '0';
            }
            $popupCss = '';
            $fullscreenCss = '';
            if ($popup) {
                $popupCss = 'wpe_popup';
            }
            if ($fullscreen) {
                $fullscreenCss = 'wpe_fullscreen';
            }
            $formSession = uniqid();
            $priceSubs = '';
            $priceSubsClass = '';
            $dataSubs = '';
            $dataIsSubs = '';
            if ($form->isSubscription){
                $dataIsSubs = 'data-isSubs="true"';
            }
            if ($form->isSubscription && $form->showSteps==0) {
                $priceSubsClass = 'lfb_subsPrice';
                $priceSubs = '<span>' . $form->subscription_text . '</span>';
                $dataSubs = $form->subscription_text;
            }
            if ($form->isSubscription){
            $priceSubBottom = '<span>' . $form->subscription_text . '</span>';
            }
            $dispIntro = '';
            if(!$form->intro_enabled){
                $dispIntro = 'display:none !important;';                
            }
            $progressBarHide = '';
            if($form->showSteps == 2){
                $progressBarHide = 'style="display: none !important;"';
            }

            $response .= '<div id="lfb_bootstraped" class="lfb_bootstraped"><div id="estimation_popup" '.$dataIsSubs.' data-formtitle="'.$form->title.'" data-formsession="'.$formSession.'" data-autoclick="'.$form->groupAutoClick.'"  data-subs="' . $dataSubs . '" data-form="' . $form_id . '" class="wpe_bootstraped ' . $popupCss . ' ' . $fullscreenCss . '">
                <div id="lfb_loader"><div class="lfb_spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>
                <a id="wpe_close_btn" href="javascript:"><span class="fui-cross"></span></a>
                <div id="wpe_panel">
                <div class="container-fluid">
                    <div class="row">
                        <div class="" >
                            <div id="startInfos" style="'.$dispIntro.'">
                                <h1>' . $form->intro_title . '</h1>
                                <p>' . $form->intro_text . '</p>
                            </div>
                            <p style="'.$dispIntro.'">
                                <a href="javascript:" onclick="lfb_startFormIntro('.$form->id.');" class="btn btn-large btn-primary" id="btnStart">' . $form->intro_btn . '</a>
                            </p>

                            <div id="genPrice" class="genPrice" '.$progressBarHide.'>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 0%;">
                                        <div class="progress-bar-price ' . $priceSubsClass . '">
                                            <span>0$</span>
                                            ' . $priceSubs . '
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- /genPrice -->
                            <h2 id="finalText" class="stepTitle">' . $form->succeed_text . '</h2>
                        </div>
                        <!-- /col -->
                    </div>
                    <!-- /row -->
                <div id="mainPanel" class="palette-clouds" data-savecart="' . $form->save_to_cart . '">
                <input type="hidden" name="action" value="lfb_upload_form"/>
                <input type="hidden" id="lfb_formSession" name="formSession" value="' . $formSession . '"/>';
            $i = 0;

            foreach ($steps as $dataSlide) {
                if ($dataSlide->formID == $form->id) {
                    $dataContent = json_decode($dataSlide->content);

                    $required = '';
                    if ($dataSlide->itemRequired > 0) {
                        $required = 'data-required="true"';
                    }
                     $useShowStepConditions = '';
                    $showStepConditionsOperator = '';
                    $showStepConditions = '';
                    if ($dataSlide->useShowConditions) {
                        $useShowStepConditions = 'data-useshowconditions="true"';
                        $dataSlide->showConditions = str_replace('"', "'", $dataSlide->showConditions);
                        $showStepConditions = 'data-showconditions="'.addslashes($dataSlide->showConditions).'"';
                        $showStepConditionsOperator = 'data-showconditionsoperator="'.$dataSlide->showConditionsOperator.'"';
                    }
                    
                    $response .= '<div class="genSlide" data-start="' . $dataContent->start . '" '.$useShowStepConditions.' '.$showStepConditions.' '.$showStepConditionsOperator.' data-showstepsum="'.$dataSlide->showInSummary.'" data-stepid="' . $dataSlide->id . '" data-title="' . $dataSlide->title . '" ' . $required . ' data-dependitem="' . $dataSlide->itemDepend . '">';
                    $response .= '	<h2 class="stepTitle">' . $dataSlide->title . '</h2>';
                    $contentNoDes = 'lfb_noDes';
                    if($dataSlide->description != ""){
                        $response .= '	<p class="lfb_stepDescription">' . $dataSlide->description . '</p>';
                        $contentNoDes = '';
                    }
                    $response .= '	<div class="genContent container-fluid '.$contentNoDes.'">';
                    $response .= '		<div class="row">';
                    $itemIndex = 0;
                    foreach ($items as $dataItem) {

                        if ($dataItem->stepID == $dataSlide->id) {
                            $chkDisplay = true;
                            $hiddenClass = '';
                            $checked = '';
                            $checkedCb = '';
                            $prodID = 0;
                            $wooVar = $dataItem->wooVariation;
                            $itemRequired = '';
                            $showInSummary = '';
                            $useCalculation = '';
                            $calculation = '';
                            $useShowConditions = '';
                            $showConditionsOperator = '';
                            $showConditions = '';
                            $hideQtSummary = '';
                            $defaultValue = '';
                            
                            if($dataItem->defaultValue != ""){
                                $defaultValue = 'value="'.$dataItem->defaultValue.'"';
                            }
                            
                            if ($dataItem->hideQtSummary) {
                                $hideQtSummary = 'data-hideqtsum="true"';
                            }
                            
                            if ($dataItem->useShowConditions) {
                                $useShowConditions = 'data-useshowconditions="true"';
                                $dataItem->showConditions = str_replace('"', "'", $dataItem->showConditions);
                                $showConditions = 'data-showconditions="'.addslashes($dataItem->showConditions).'"';
                                $showConditionsOperator = 'data-showconditionsoperator="'.$dataItem->showConditionsOperator.'"';
                            }
                            
                            if ($dataItem->useCalculation) {
                                $useCalculation = 'data-usecalculation="true"';
                                $calculation = 'data-calculation="'.addslashes($dataItem->calculation).'"';
                            }
                            
                            if ($dataItem->isRequired) {
                                $itemRequired = 'data-required="true"';
                            }
                            if ($dataItem->ischecked == 1) {
                                $checked = 'prechecked';
                                $checkedCb = 'checked';
                            }
                            if ($dataItem->isHidden == 1) {
                                $hiddenClass = 'lfb-hidden';
                            } 
                            
                            if ($dataItem->showInSummary == 1) {
                                $showInSummary = 'data-showinsummary="true"';
                            }

                            if ($dataItem->wooProductID > 0) {
                                $prodID = $dataItem->wooProductID;
                                $product = new WC_Product($dataItem->wooProductID);
                                if (!$product) {
                                    $chkDisplay = false;
                                } else {
                                    if ($dataItem->wooVariation == 0) {
                                        $dataItem->price = $product->price;
                                        if($dataItem->type =='slider'){
                                            if ($product->get_stock_quantity() && $product->get_stock_quantity() < $dataItem->maxSize) {
                                                $dataItem->maxSize = $product->get_stock_quantity();
                                            }
                                         } else {
                                            if ($product->get_stock_quantity() && $product->get_stock_quantity() < $dataItem->quantity_max) {
                                                $dataItem->quantity_max = $product->get_stock_quantity();

                                            }
                                        }
                                        if ($product->get_stock_quantity() && $product->get_stock_quantity() < 1) {
                                            $chkDisplay = false;
                                        }
                                    } else {
                                        $variable_product = new WC_Product_Variation($dataItem->wooVariation);
                                        $dataItem->price = $variable_product->price;
                                         if($dataItem->type =='slider'){
                                             if ($variable_product->get_stock_quantity() && $variable_product->get_stock_quantity() < $dataItem->maxSize) {
                                                $dataItem->maxSize = $product->get_stock_quantity();
                                             }
                                            } else {
                                        if ($variable_product->get_stock_quantity() && $variable_product->get_stock_quantity() < $dataItem->quantity_max) {
                                            $dataItem->quantity_max = $variable_product->get_stock_quantity();
                                            
                                        }
                                            }
                                        if ($variable_product->get_stock_quantity() && $variable_product->get_stock_quantity() < 1) {
                                            $chkDisplay = false;
                                        }
                                    }
                                }
                            } else if ($form->save_to_cart) {
                                $dataItem->price = 0;
                            }
                            $originalTitle = $dataItem->title;
                            $dataShowPrice = "";
                            if ($dataItem->showPrice) {
                                $dataShowPrice = 'data-showprice="1"';
                                if ($form->currencyPosition == 'right') {
                                    if ($dataItem->operation == "+") {
                                        $dataItem->title = $dataItem->title . " : " . $this->getFormatedPrice($dataItem->price, $form) . $form->currency;
                                    }
                                    if ($dataItem->operation == "-") {
                                        $dataItem->title = $dataItem->title . " : -" . $this->getFormatedPrice($dataItem->price, $form) . $form->currency;
                                    }
                                    if ($dataItem->operation == "x") {
                                        $dataItem->title = $dataItem->title . " : +" . $this->getFormatedPrice($dataItem->price, $form) . '%';
                                    }
                                    if ($dataItem->operation == "/") {
                                        $dataItem->title = $dataItem->title . " : -" . $this->getFormatedPrice($dataItem->price, $form) . '%';
                                    }
                                } else {
                                    if ($dataItem->operation == "+") {
                                        $dataItem->title = $dataItem->title . " : " . $form->currency . $this->getFormatedPrice($dataItem->price, $form);
                                    }
                                    if ($dataItem->operation == "-") {
                                        $dataItem->title = $dataItem->title . " : -" . $form->currency . $this->getFormatedPrice($dataItem->price, $form);
                                    }
                                    if ($dataItem->operation == "x") {
                                        $dataItem->title = $dataItem->title . " : +" . $this->getFormatedPrice($dataItem->price, $form) . '%';
                                    }
                                    if ($dataItem->operation == "/") {
                                        $dataItem->title = $dataItem->title . " : -" . $this->getFormatedPrice($dataItem->price, $form) . '%';
                                    }
                                }
                            }
                            $urlTag = "";
                            if ($dataItem->urlTarget != "") {
                                $urlTag .= 'data-urltarget="' . $dataItem->urlTarget . '"';
                            }
                            $isSinglePrice = '';
                            if ($form->isSubscription && $dataItem->isSinglePrice) {
                                $isSinglePrice = 'data-singleprice="true"';
                            }
                            
                            if ($chkDisplay) {
                                
                                $colClass = 'col-md-2'.' '.$hiddenClass.' lfb_item';
                                if ($dataItem->useRow || $dataItem->type =='richtext') {
                                    $itemIndex = 0;
                                    $colClass = 'col-md-12'.' '.$hiddenClass.' lfb_item';
                                } else {
                                    if ($dataItem->isHidden == 0) {                               
                                        $itemIndex++;
                                    }
                                    if($dataSlide->itemsPerRow > 0 && $itemIndex-1 == $dataSlide->itemsPerRow){
                                        $itemIndex= 1;
                                        $response .='<br/>';
                                    }
                                }
                                $distanceQt = '';
                                if($dataItem->useDistanceAsQt && $dataItem->distanceQt != ""){
                                    $distanceQt = 'data-distanceqt="'.$dataItem->distanceQt .'"';
                                }

                                if ($dataItem->type == 'picture') {
                                    $response .= '<div class="itemBloc ' . $colClass . ' lfb_picRow">';
                                    $group = '';
                                    if ($dataItem->groupitems != "") {
                                        $group = 'data-group="' . $dataItem->groupitems . '"';
                                    }
                                    $tooltipPosition = 'bottom';
                                    if ($form->qtType == 1) {
                                        $tooltipPosition = 'top';
                                    }
                                    $response .= '<div class="selectable ' . $checked . '" '.$itemRequired.' '.$useCalculation.' '.$hideQtSummary.' '.$calculation.' '.$distanceQt.' '.$useShowConditions.' '.$showConditions.' '.$showConditionsOperator.' '.$isSinglePrice.' ' . $dataShowPrice . ' ' . $urlTag . ' ' . $showInSummary . ' data-woovar="' . $wooVar . '" data-reduc="' . $dataItem->reduc_enabled . '" data-reducqt="' . $dataItem->reducsQt . '"  data-operation="' . $dataItem->operation . '" data-itemid="' . $dataItem->id . '"  ' . $group . '  data-prodid="' . $prodID . '" data-title="' . $dataItem->title . '" data-toggle="tooltip" title="' . $dataItem->title . '" data-originaltitle="' . $originalTitle . '" data-placement="' . $tooltipPosition . '" data-price="' . $dataItem->price . '">';
                                    $tint = 'false';
                                    if ($dataItem->imageTint) {
                                        $tint = 'true';
                                    }
                                    $response .= '<img data-tint="' . $tint . '" src="' . $dataItem->image . '" alt="' . $dataItem->imageDes . '" class="img" />';

                                    $response .= '<span class="palette-clouds fui-cross icon_select"></span>';
                                    if ($dataItem->quantity_enabled) {
                                        if ($form->qtType == 1) {
                                            $qtMax = '';
                                            if ($dataItem->quantity_max > 0) {
                                                $qtMax = 'max="' . $dataItem->quantity_max . '"';
                                            } else {
                                                $qtMax = 'max="999999999"';
                                            }
                                            if ($dataItem->quantity_min > 0) {
                                                $qtMin = $dataItem->quantity_min . '"';
                                            } else {
                                                $qtMin = '1';
                                            }
                                            $response .= '<div class="form-group wpe_itemQtField">';
                                            $response .= ' <input class="wpe_qtfield form-control" min="' . $qtMin . '" ' . $qtMax . ' type="number" value="' . $qtMin . '" /> ';

                                            $response .= '</div>';
                                        } else if ($form->qtType == 2) {
                                            $response .= '<div class="quantityBtns wpe_sliderQtContainer" data-max="' . $dataItem->quantity_max . '" data-min="' . $dataItem->quantity_min . '">
                                                     <div class="wpe_sliderQt"></div>
                                                 </div>';
                                            $valMin = 1;
                                            if ($dataItem->quantity_min > 0) {
                                                $valMin = $dataItem->quantity_min;
                                            }
                                            $response .= '<span class="palette-turquoise icon_quantity wpe_hidden">' . $valMin . '</span>';
                                        } else {
                                            $response .= '<div class="quantityBtns" data-max="' . $dataItem->quantity_max . '" data-min="' . $dataItem->quantity_min . '">
                                                <a href="javascript:" data-btn="less">-</a>
                                                <a href="javascript:" data-btn="more">+</a>
                                                </div>';
                                            $valMin = 1;
                                            if ($dataItem->quantity_min > 0) {
                                                $valMin = $dataItem->quantity_min;
                                            }
                                            $response .= '<span class="palette-turquoise icon_quantity">' . $valMin . '</span>';
                                        }
                                    }
                                    $response .= '</div>';
                                    if ($dataItem->description != "") {
                                        $cssWidth = '';
                                        if ($dataItem->useRow) {
                                            $cssWidth = 'max-width: 100%;';
                                        }
                                        $response .= '<p class="itemDes" style="' . $cssWidth . '">' . $dataItem->description . '</p>';
                                    }
                                    $response .= '</div>';
                                } else if ($dataItem->type == 'datepicker') {
                                    $response .= '<div class="itemBloc ' . $colClass . '">';
                                    $response .= '<div class="form-group">';
                                    $response .= '<label>' . $dataItem->title . '</label>
                                                <input type="text" data-itemid="' . $dataItem->id . '"  ' . $showInSummary . '  '.$hideQtSummary.'   '.$useShowConditions.' '.$showConditions.' '.$showConditionsOperator.'  class="form-control lfb_datepicker" ' . $itemRequired . ' data-title="' . $dataItem->title . '" data-originaltitle="' . $originalTitle . '"  ' . $urlTag . '  />
                                              ';

                                    if ($dataItem->description != "") {
                                        $response .= '<p class="itemDes" style="margin: 0 auto; max-width: 90%;">' . $dataItem->description . '</p>';
                                    }
                                    $response .= '</div>';
                                    $response .= '</div>';
                                } else if ($dataItem->type == 'filefield_') {
                                    $response .= '<div class="itemBloc ' . $colClass . '">';
                                    $response .= '<div class="form-group">
                                              <label>' . $dataItem->title . '</label>
                                              <input type="file" ' . $itemRequired . '  ' . $showInSummary . '  '.$hideQtSummary.'   '.$useShowConditions.' '.$showConditions.' '.$showConditionsOperator.' class="lfb_filefield"  name="file_' . $dataItem->id . '" data-itemid="' . $dataItem->id . '" data-title="' . $dataItem->title . '" data-originaltitle="' . $originalTitle . '" ' . $urlTag . '  />
                                              </div>
                                              ';
                                    if ($dataItem->description != "") {
                                        $response .= '<p class="itemDes" style="margin: 0 auto; max-width: 90%;">' . $dataItem->description . '</p>';
                                    }
                                    $response .= '</div>';
                                } else if ($dataItem->type == 'filefield') {
                                    $response .= '<div class="itemBloc ' . $colClass . '" style="margin-top: 18px;">';
                                    $response .= '<label>' . $dataItem->title . '</label>';
                                    $response .= '<div class="lfb_dropzone dropzone" ' . $itemRequired . '  '.$hideQtSummary.'   '.$useShowConditions.' '.$showConditions.' '.$showConditionsOperator.' ' . $showInSummary . ' data-allowedfiles="'.$dataItem->allowedFiles.'" data-maxfiles="'.$dataItem->maxFiles.'" id="lfb_dropzone_'.$dataItem->id.'" data-itemid="' . $dataItem->id . '" data-title="' . $dataItem->title . '" data-originaltitle="' . $originalTitle . '" ></div>';
                                    $response .= '</div>';
                                } else if ($dataItem->type == 'qtfield') {
                                    $response .= '<div class="itemBloc ' . $colClass . '">';
                                    $response .= '<div class="form-group">';
                                    $response .= '<label>' . $dataItem->title . '</label>';
                                    $qtMax = '';
                                    if ($qtMax > 0) {
                                        $qtMax = 'max="' . $dataItem->quantity_max . '"';
                                    }
                                    $response .= ' <input  ' . $urlTag . '  ' . $showInSummary . '  '.$hideQtSummary.'   '.$useShowConditions.' '.$showConditions.'  '.$isSinglePrice.'  class="wpe_qtfield form-control" min="0" ' . $qtMax . ' ' . $dataShowPrice . ' type="number" value="0" data-reduc="' . $dataItem->reduc_enabled . '" data-price="' . $dataItem->price . '" data-reducqt="' . $dataItem->reducsQt . '" data-operation="' . $dataItem->operation . '" data-itemid="' . $dataItem->id . '" class="form-control" data-title="' . $dataItem->title . '" /> ';

                                    if ($dataItem->description != "") {
                                        $response .= '<p class="itemDes" style="margin: 0 auto; max-width: 90%;">' . $dataItem->description . '</p>';
                                    }
                                    $response .= '</div>';
                                    $response .= '</div>';
                                } else if ($dataItem->type == 'textarea') {
                                    $response .= '<div class="itemBloc ' . $colClass . '">';
                                    $response .= '<div class="form-group">';
                                    $response .= '<label>' . $dataItem->title . '</label>
                                              <textarea data-itemid="' . $dataItem->id . '"  '.$useShowConditions.'  '.$hideQtSummary.'  '.$showConditions.' '.$showConditionsOperator.' ' . $showInSummary . ' '.$urlTag.' class="form-control" ' . $itemRequired . ' data-title="' . $dataItem->title . '" data-originaltitle="' . $originalTitle . '">'.$dataItem->defaultValue.'</textarea>';
                                    if ($dataItem->description != "") {
                                        $response .= '<p class="itemDes" style="margin: 0 auto; max-width: 90%;">' . $dataItem->description . '</p>';
                                    }
                                    $response .= '</div>';
                                    $response .= '</div>';
                                } else if ($dataItem->type == 'select') {
                                    $response .= '<div class="itemBloc ' . $colClass . '">';
                                    $response .= '<p>
                                              <label>' . $dataItem->title . '</label>
                                              <br/>
                                              <select class="form-control"  '.$useShowConditions.'  '.$hideQtSummary.'  '.$showConditions.' '.$showConditionsOperator.' ' . $showInSummary . ' '.$isSinglePrice.'  data-operation="' . $dataItem->operation . '"  data-originaltitle="' . $originalTitle . '"  ' . $urlTag . '  data-itemid="' . $dataItem->id . '"  data-title="' . $dataItem->title . '" >';
                                    $optionsArray = explode('|', $dataItem->optionsValues);
                                    foreach ($optionsArray as $option) {
                                        if ($option != "") {
                                            $value = $option;
                                            $price = 0;
                                            if (strpos($option, ";;") > 0) {
                                                $optionArr = explode(";;", $option);
                                                $value = $optionArr[0];
                                                $price = $optionArr[1];
                                            }
                                            $response .= '<option value="' . $value . '" data-price="' . $price . '">' . $value . '</option>';
                                        }
                                    }
                                    $response .= '</select>
                                              </p>
                                              ';

                                    if ($dataItem->description != "") {
                                        $response .= '<p class="itemDes" style="margin: 0 auto; max-width: 90%;">' . $dataItem->description . '</p>';
                                    }
                                    $response .= '</div>';
                                
                                } else if ($dataItem->type == 'richtext') {
                                    $response .= '<div class="lfb_richtext lfb_item" data-title="' . $dataItem->title . '"  '.$useShowConditions.' '.$showConditions.' '.$showConditionsOperator.'>'.$dataItem->richtext.'</div>';
                                    
                                }  else if ($dataItem->type == 'checkbox') {
                                    $activatePaypal  = '';
                                    if($dataItem->usePaypalIfChecked){
                                        $activatePaypal = 'data-activatepaypal="true"';
                                    }
                                    $group = '';
                                    if ($dataItem->groupitems != "") {
                                        $group = 'data-group="' . $dataItem->groupitems . '"';
                                    }
                                    $response .= '<div class="itemBloc ' . $colClass . '">';
                                    $response .= '<p>
                                                <label>' . $dataItem->title . '</label>
                                                <br/>
                                                <input type="checkbox"  '.$hideQtSummary.'  ' . $group . ' '.$useCalculation.' '.$activatePaypal.' '.$calculation.'  '.$useShowConditions.' '.$showConditions.' '.$showConditionsOperator.' ' . $showInSummary . ' '.$isSinglePrice.'  class="' . $checked . '" ' . $urlTag . ' ' . $dataShowPrice . ' data-operation="' . $dataItem->operation . '" data-originaltitle="' . $originalTitle . '" data-itemid="' . $dataItem->id . '" data-prodid="' . $prodID . '"  data-woovar="' . $wooVar . '" ' . $itemRequired . ' data-toggle="switch" ' . $checkedCb . ' data-price="' . $dataItem->price . '" data-title="' . $dataItem->title . '" />
                                                </p>';

                                    if ($dataItem->description != "") {
                                        $response .= '<p class="itemDes" style="margin: 0 auto; max-width: 90%;">' . $dataItem->description . '</p>';
                                    }
                                    $response .= '</div>';
                                } else if ($dataItem->type == 'colorpicker') {
                                    $response .= '<div class="itemBloc ' . $colClass . '">';
                                    $response .= '<div style="background-color: '.$settings->colorA.';"  '.$useShowConditions.'  '.$hideQtSummary.'  '.$showConditions.' '.$showConditionsOperator.' class="lfb_colorPreview checked" data-itemid="' . $dataItem->id . '"  '.$urlTag.' ' . $showInSummary . ' data-toggle="tooltip"  ' . $itemRequired . ' data-placement="bottom" data-title="' . $dataItem->title . '" data-originaltitle="' . $originalTitle . '" ></div>'
                                            . '<input type="text" value="'.$settings->colorA.'" class="lfb_colorpicker" />
                                                ';

                                    if ($dataItem->description != "") {
                                        $response .= '<p class="itemDes" style="margin: 0 auto; max-width: 90%;">' . $dataItem->description . '</p>';
                                    }
                                    $response .= '</div>';
                                    
                                } else if ($dataItem->type == 'numberfield') {
                                    $response .= '<div class="itemBloc ' . $colClass . '">';
                                    $response .= '<div class="form-group">';
                                    $minLength = '';
                                    $maxLength = '';
                                    if($dataItem->minSize > 0){
                                        $minLength = 'min="'.$dataItem->minSize.'"';
                                    }
                                    if($dataItem->maxSize > 0){
                                        $maxLength = 'max="'.$dataItem->maxSize.'"';
                                    }
                                    $response .= '<label>' . $dataItem->title . '</label>
                                                <input type="number" '.$useShowConditions.' '.$showConditions.'  '.$hideQtSummary.'  '.$showConditionsOperator.' data-itemid="' . $dataItem->id . '" '.$minLength.' '.$maxLength.' ' . $showInSummary . ' '.$urlTag.' '.$defaultValue.' class="form-control" ' . $itemRequired . ' data-title="' . $dataItem->title . '" data-originaltitle="' . $originalTitle . '" />
                                                ';

                                    if ($dataItem->description != "") {
                                        $response .= '<p class="itemDes" style="margin: 0 auto; max-width: 90%;">' . $dataItem->description . '</p>';
                                    }
                                    $response .= '</div>';
                                    $response .= '</div>';
                                }else if ($dataItem->type == 'slider') {
                                    $dataShowPrice = '';
                                    if($dataItem->showPrice){
                                        $dataShowPrice = 'data-showprice="1"';
                                    }
                                    $response .= '<div class="itemBloc ' . $colClass . '">';
                                    $minLength = 'data-min="0"';
                                    $maxLength = 'data-max="30"';
                                    if($dataItem->maxSize < $dataItem->minSize){
                                        $dataItem->minSize = $dataItem->maxSize;
                                    }
                                    if($dataItem->minSize > 0){
                                        $minLength = 'data-min="'.$dataItem->minSize.'"';
                                    }
                                    if($dataItem->maxSize > 0){
                                        $maxLength = 'data-max="'.$dataItem->maxSize.'"';
                                    }
                                    $response .= '<label>' . $dataItem->title . '</label>
                                                <div data-type="slider"  '.$distanceQt.'  '.$dataShowPrice.'  '.$hideQtSummary.'  '.$isSinglePrice.'  data-reducqt="' . $dataItem->reducsQt . '" data-operation="' . $dataItem->operation . '" data-reduc="' . $dataItem->reduc_enabled . '" data-price="' . $dataItem->price . '"  '.$useCalculation.' '.$calculation.'  '.$useShowConditions.' '.$showConditions.' '.$showConditionsOperator.' data-itemid="' . $dataItem->id . '" '.$minLength.' '.$maxLength.' ' . $showInSummary . ' class="" data-title="' . $dataItem->title . '" data-originaltitle="' . $originalTitle . '" data-prodid="' . $prodID . '"  data-woovar="' . $wooVar . '"></div>
                                                ';

                                    if ($dataItem->description != "") {
                                        $response .= '<p class="itemDes" style="margin: 0 auto; max-width: 90%;">' . $dataItem->description . '</p>';
                                    }
                                    $response .= '</div>';
                                }else {
                                    $response .= '<div class="itemBloc ' . $colClass . '">';
                                    $response .= '<div class="form-group">';
                                    $minLength = '';
                                    $maxLength = '';
                                    $autocomp = '';
                                    if($dataItem->minSize > 0){
                                        $minLength = 'minlength="'.$dataItem->minSize.'"';
                                    }
                                    if($dataItem->maxSize > 0){
                                        $maxLength = 'maxlength="'.$dataItem->maxSize.'"';
                                    }
                                    if($dataItem->fieldType == 'email'){
                                     $autocomp = 'autocomplete="on" name="email" ';                                        
                                    }
                                    $response .= '<label>' . $dataItem->title . '</label>
                                                <input type="text" data-fieldtype="'.$dataItem->fieldType.'" '.$defaultValue.'  '.$hideQtSummary.'  '.$autocomp.' '.$useShowConditions.' '.$showConditions.' '.$showConditionsOperator.' data-itemid="' . $dataItem->id . '" '.$minLength.' '.$maxLength.' ' . $showInSummary . ' '.$urlTag.' class="form-control" ' . $itemRequired . ' data-title="' . $dataItem->title . '" data-originaltitle="' . $originalTitle . '" />
                                                ';

                                    if ($dataItem->description != "") {
                                        $response .= '<p class="itemDes" style="margin: 0 auto; max-width: 90%;">' . $dataItem->description . '</p>';
                                    }
                                    $response .= '</div>';
                                    $response .= '</div>';
                                }
                            }
                        }
                    }

                    $response .= ' </div>';
                    $response .= ' </div>';
                    if($form->showTotalBottom){
                        $response .= '<div class="lfb_totalBottomContainer '.$priceSubsClass.'"><hr/><h3 class="lfb_totalBottom"> 
                            <span>0$</span>' . $priceSubBottom . '</h3></div>';
                    }
                    $response .= '<div class="errorMsg alert alert-danger">' . $form->errorMessage . '</div>';
                    $response .= '<p style="margin-top: 22px; position: absolute; width: 100%;" class="text-center lfb_btnNextContainer">';
                    $hideNtxStepBtn = '';
                    if($dataSlide->hideNextStepBtn){
                        $hideNtxStepBtn = 'lfb-hidden';
                    }
                    $response .= '<a href="javascript:" id="lfb_btnNext_'.$dataContent->id.'" class="btn btn-wide btn-primary btn-next '.$hideNtxStepBtn.'">' . $form->btn_step . '</a>';
                    
                    if ($dataContent->start == 0) {
                        $response .= '<br/><a href="javascript:"  class="linkPrevious">' . $form->previous_step . '</a>';
                    }
                    $response .= '</p>';

                    $response .= '</div>';
                    $i++;
                }
            }

            $response .= '<div class="genSlide" id="finalSlide" data-stepid="final">
                <h2 class="stepTitle">' . $form->last_title . '</h2>
                <div class="genContent">
                    <div class="genContentSlide active">
                        <p>' . $form->last_text . '</p>';
            $dispFinalPrice = '';
            if ($form->hideFinalPrice == 1) {
                $dispFinalPrice = "display:none;";
            }
            $subTxt = '';
            if ($form->isSubscription == 1) {
                $subTxt = '<span>' . $form->subscription_text . '</span>';
            }
            $response .= '<h3 id="finalPrice" style="' . $dispFinalPrice . '"><span></span>' . $subTxt . '</h3>';
            
            $response .= '<div id="lfb_subTxtValue" style="display: none;">'.$priceSubs.'</div>';

            if ($form->gravityFormID > 0) {
                gravity_form($form->gravityFormID, $display_title = false, $display_description = true, $display_inactive = false, $field_values = null, $ajax = true);
            } else {
                foreach ($fields as $field) {
                    $response .= '<div class="form-group">';
                    $placeholder = "";
                    $disp = '';
                    $dispLabel = 'block';
                    if ($field->visibility == 'toggle') {
                        $disp = 'toggle';
                        $placeholder = "";
                    } else {
                        $dispLabel = 'none';
                        $placeholder = $field->label;
                        if ($field->validation == 'fill') {
                            $req = "true";
                        }
                    }
                    $response .= '<label for="field_' . $field->id . '" style="display: ' . $dispLabel . '">' . $field->label . '</label>';
                    if ($field->visibility == 'toggle') {
                        $response .= '<input id="field_' . $field->id . '_cb" type="checkbox" data-toggle="switch" data-fieldid="' . $field->id . '" /><br/>';
                    }
                    $req = "false";
                    $autocomp = '';
                    $emailField = '';
                    if ($field->validation == 'email') {
                        $emailField = 'emailField';
                        $autocomp = 'autocomplete="on" name="email" ';
                    }
                    if ($field->validation == 'fill') {
                        $req = 'true';
                    }

                    if ($field->typefield == 'textarea') {
                        $response .= '<textarea id="field_' . $field->id . '" data-fieldtype="'.$field->fieldType.'"  data-required="' . $req . '"  class="form-control ' . $disp . ' ' . $emailField . '" placeholder="' . $placeholder . '"></textarea>';
                    } else {
                        $response .= '<input type="text" id="field_' . $field->id . '" '.$autocomp.' data-fieldtype="'.$field->fieldType.'"  data-required="' . $req . '" placeholder="' . $placeholder . '" class="form-control ' . $emailField . ' ' . $disp . '"/>';
                    }
                    $response .= '</div>';
                }

                $response .= '<p style="margin-bottom: 28px;">';
            }
            
            if($form->useCoupons){
                $response .= '<div id="lfb_couponContainer" class="form-group">'
                          .    '<input type="text" placeholder="'.$form->couponText.'" id="lfb_couponField" class="form-control"/>'
                          .    '<a href="javascript:" id="lfb_couponBtn" onclick="lfb_applyCouponCode('.$form->id.');" class="btn btn-primary"><span class="glyphicon glyphicon-check"></span></a>'
                          . '</div>';
            }
            
            $cssSum = '';
            $cssQtCol = '';
            if (!$form->useSummary) {
                $cssSum = 'lfb-hidden';
            }
            if ($form->summary_hideQt) {
                $cssQtCol = 'lfb-hidden';                
            }
            $subTxt = '';
            if ($form->isSubscription == 1) {
                $subTxt = '<span class="lfb_subTxt">' . $form->subscription_text . '</span>';
            }
            $priceHiddenClass = '';
            if($form->summary_hidePrices == 1){
                $priceHiddenClass = 'lfb-hidden lfb_hidePrice';      
            }
            $response .= '
                   <div id="lfb_summary" class="table-responsive ' . $cssSum . '">
                        <h4>' . $form->summary_title . '</h4>
                        <table class="table table-bordered">
                            <thead>
                                <th>' . $form->summary_description . '</th>
                                <th>' . $form->summary_value . '</th>
                                <th class="'.$cssQtCol.'">' . $form->summary_quantity . '</th>
                                <th class="'.$priceHiddenClass.'">' . $form->summary_price . '</th>
                            </thead>
                            <tbody>    
                                <tr id="lfb_summaryDiscountTr" class="lfb_static '.$priceHiddenClass.'"><th colspan="3">' . $form->summary_discount . '</th><th id="lfb_summaryDiscount"><span></span></th></tr>                                  
                                <tr id="sfb_summaryTotalTr" class="lfb_static '.$priceHiddenClass.'"><th colspan="3">' . $form->summary_total . '</th><th id="lfb_summaryTotal"><span></span>' . $subTxt . '</th></tr>                                  
                            </tbody>
                        </table>
                    </div>';


            if ($form->legalNoticeEnable) {
                $response .= '
                    <div id="lfb_legalNoticeContent">' . nl2br($form->legalNoticeContent) . '</div>
                    <div class="form-group" style=" margin-top: 14px;">
                      <label for="lfb_legalCheckbox">' . $form->legalNoticeTitle . '</label>
                      <input type="checkbox" data-toggle="switch" id="lfb_legalCheckbox" class="form-control"/>
                    </div>';
            }
             
            if ($form->use_stripe) {

                $response .= '<form id="lfb_stripeForm" action="" data-title="' . $form->title . '" method="post">';
                
                $response .= '
                    <div class="form-group">
                    <label>
                      <span>'.$form->stripe_label_creditCard.'</span>
                    </label><br/>
                      <input type="text" size="20" data-stripe="number" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>
                      <span>'.$form->stripe_label_expiration.' (MM/YY)</span>
                    </label><br/>
                    <input type="text" size="2" data-stripe="exp_month" class="form-control" style="display: inline-block;margin-right: 8px; width: 60px;">
                    <span style="font-size: 24px;"> / </span>
                    <input type="text" size="2" data-stripe="exp_year" class="form-control" style="display: inline-block;margin-left: 8px; width: 60px;">
                  </div>

                  <div class="form-group">
                    <label>
                      <span>'.$form->stripe_label_cvc.'</span>
                    </label><br/>
                      <input type="text" size="4" data-stripe="cvc"  class="form-control" style="width: 110px;">
                  </div>

                  <span class="payment-errors" style="color:red; font-size: 20px;padding-top: 28px;"></span>
                  <p style="margin-top: 38px; margin-bottom: -28px;"><input type="submit" value="' . $form->last_btn . '"  id="wpe_btnOrderStripe"  class="btn btn-wide btn-primary"></p>';
                
                $response .= '</form>';
            } else if ($form->use_paypal) {
                $useIPN = '';
                if ($form->paypal_useIpn == 1) {
                    $useIPN = 'data-useipn="1"';
                }
                if ($form->paypal_useSandbox == 1) {
                    $response .= '<form id="wtmt_paypalForm" action="https://www.sandbox.paypal.com/cgi-bin/webscr" ' . $useIPN . ' method="post">';
                } else {
                    $response .= '<form id="wtmt_paypalForm" action="https://www.paypal.com/cgi-bin/webscr" ' . $useIPN . ' method="post">';
                }

                $response .= '<a href="javascript:" id="btnOrderPaypal" class="btn btn-wide btn-primary">' . $form->last_btn . '</a>
                            <input type="submit" style="display: none;" name="submit"/>
                            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                            <input type="hidden" name="add" value="1">';
                if ($form->isSubscription == 1) {
                    $response .= '<input type="hidden" name="cmd" value="_xclick-subscriptions">
                            <input type="hidden" name="no_note" value="1">
                            <input type="hidden" name="src" value="1">
                            <input type="hidden" name="a3" value="15.00">
                            <input type="hidden" name="p3" value="' . $form->paypal_subsFrequency . '">
                            <input type="hidden" name="t3" value="' . $form->paypal_subsFrequencyType . '">
                            <input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">';
                } else {
                    $response .= '<input type="hidden" name="cmd" value="_xclick">
                            <input type="hidden" name="amount" value="1">';
                }
                $response .= '<input type="hidden" name="business" value="' . $form->paypal_email . '">
                            <input type="hidden" name="business_cs_email" value="' . $form->paypal_email . '">
                            <input type="hidden" name="item_name" value="' . $form->title . '">
                            <input type="hidden" name="item_number" value="A00001">
                            <input type="hidden" name="charset" value="utf-8">
                            <input type="hidden" name="no_shipping" value="1">
                            <input type="hidden" name="cn" value="Message">
                            <input type="hidden" name="custom" value="Form content">
                            <input type="hidden" name="currency_code" value="' . $form->paypal_currency . '">
                            <input type="hidden" name="return" value="' . $form->close_url . '">
                        </form>';
            } else if ($form->gravityFormID == 0) {
                $response .= ' <a href="javascript:" id="wpe_btnOrder" class="btn btn-wide btn-primary">' . $form->last_btn . '</a>';
            }
            if (count($steps) > 0) {
                $response .= '<div><a href="javascript:" class="linkPrevious">' . $form->previous_step . '</a></div>';
            }
            $response .= '</p>';
        }
        }
        $response .= '</div>';
        $response .= '</div>';
        $response .= '</div>';
        $response .= '</div>';
        $response .= '</div>';


        $response .= '</div>';
        $response .= '</div>';
        $response .= '</div>';
        /* end */


        return $response;
    }

    private function getFormatedPrice($price, $form) {
        $formatedPrice = $price;
        $priceNoDecimals = $formatedPrice;
        $decimals = "";
        if (strpos($formatedPrice, '.') > 0) {
            $formatedPrice = number_format($formatedPrice, 2, ".", "");
            $priceNoDecimals = substr($formatedPrice, 0, strpos($formatedPrice, '.'));
            $decimals = substr($formatedPrice, strpos($formatedPrice, '.') + 1, 2);
            $formatedPrice = str_replace(".", $formatedPrice, $form->decimalsSeparator);
            $decimals.='0';
            if (strlen($decimals) == 1) {
                
            }
            if (strlen($priceNoDecimals) > 3) {
                $formatedPrice = substr($priceNoDecimals, 0, -3) . $form->thousandsSeparator . substr($priceNoDecimals, -3) . $form->decimalsSeparator . $decimals;
            }
        } else {
            if (strlen($priceNoDecimals) > 3) {
                $formatedPrice = substr($priceNoDecimals, 0, -3) . $form->thousandsSeparator . substr($priceNoDecimals, -3);
            }
        }


        return $formatedPrice;
    }

    /*
     * Styles integration
     */

    public function options_custom_styles() {

        $settings = $this->getSettings();
        $output = '';

        foreach ($this->currentForms as $currentForm) {
            if ($currentForm > 0 && !is_array($currentForm)) {
                $form = $this->getFormDatas($currentForm);
                if ($form) {
                    if (!$form->colorA || $form->colorA == "") {
                        $form->colorA = $settings->colorA;
                    }
                    if (!$form->colorB || $form->colorB == "") {
                        $form->colorB = $settings->colorB;
                    }
                    if (!$form->colorC || $form->colorC == "") {
                        $form->colorC = $settings->colorC;
                    }
                    if (!$form->item_pictures_size || $form->item_pictures_size == "") {
                        $form->item_pictures_size = $settings->item_pictures_size;
                    }
                    
                    if($form->useGoogleFont && $form->googleFontName != ""){
                        $fontname = str_replace(' ', '+',$form->googleFontName);
                        $output .= '@import url(https://fonts.googleapis.com/css?family='.$fontname.':400,700);';
                        
                        $output .= 'body:not(.wp-admin) #estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] {';
                        $output .= ' font-family:"' . $form->googleFontName . '"; ';
                        $output .= '}';
                    }

                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel {';
                    $output .= ' background-color:' . $form->colorBg . '; ';
                    $output .= '}';
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #lfb_loader {';
                    $output .= ' background-color:' . $form->colorA . '; ';
                    $output .= '}';
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]  {';
                    $output .= ' color:' . $form->colorB . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] .genSlide .lfb_totalBottomContainer hr  {';
                    $output .= ' border-color:' . $form->colorC . '; ';
                    $output .= '}';
                    $output .= "\n";
                    
                    
                    $fieldsColor = $form->colorC;
                    if(strtolower($fieldsColor) == '#ffffff'){
                        $fieldsColor = '#bdc3c7';
                    }
                    $output .= 'body #estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] .form-control,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel ,'
                            .  '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] p,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #lfb_summary tbody td,'
                            .  '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #lfb_summary tbody #sfb_summaryTotalTr th:not(#lfb_summaryTotal) {';
                    $output .= ' color:' . $fieldsColor . '; ';
                    $output .= '}';
                    $output .= "\n";
                    
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]  .tooltip .tooltip-inner,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   #mainPanel .genSlide .genContent div.selectable span.icon_quantity,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .dropdown-inverse {';
                    $output .= ' background-color:' . $form->colorB . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .tooltip.top .tooltip-arrow {';
                    $output .= ' border-top-color:' . $form->colorB . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .tooltip.bottom .tooltip-arrow {';
                    $output .= ' border-bottom-color:' . $form->colorB . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .btn-primary,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] .gform_button,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .btn-primary:hover,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .btn-primary:active,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]    .genPrice .progress .progress-bar-price,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]    .progress-bar,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .quantityBtns a,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .btn-primary:active,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]    .btn-primary.active,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]    .open .dropdown-toggle.btn-primary,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .dropdown-inverse li.active > a,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]    .dropdown-inverse li.selected > a,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .btn-primary:active,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]
            .btn-primary.active,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .open .dropdown-toggle.btn-primary,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .btn-primary:hover,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]    .btn-primary:focus,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]    .btn-primary:active,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]    .btn-primary.active,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]    .open .dropdown-toggle.btn-primary {';
                    $output .= ' background-color:' . $form->colorA . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] .lfb_dropzone:focus,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] .has-switch > div.switch-on label,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .form-group.focus .form-control,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .form-control:focus {';
                    $output .= ' border-color:' . $form->colorA . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] a:not(.btn),#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   a:not(.btn):hover,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   a:not(.btn):active,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   #mainPanel .genSlide .genContent div.selectable.checked span.icon_select,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   #mainPanel #finalPrice,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]    .ginput_product_price,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .checkbox.checked,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]    .radio.checked,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .checkbox.checked .second-icon,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]    .radio.checked .second-icon {';
                    $output .= ' color:' . $form->colorA . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   #mainPanel .genSlide .genContent div.selectable .img {';
                    $output .= ' max-width:' . $form->item_pictures_size . 'px; ';
                    $output .= ' max-height:' . $form->item_pictures_size . 'px; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   #mainPanel,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .form-control {';
                    $output .= ' color:' . $form->colorC . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]   .form-control,#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] .lfb_dropzone  {';
                    $output .= ' color:' . $form->colorC . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"]  .lfb_dropzone .dz-preview .dz-remove {';
                    $output .= ' color:' . $form->colorC . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] .genPrice .progress .progress-bar-price  {';
                    $output .= ' font-size:' . $form->priceFontSize . 'px; ';
                    $output .= '}';
                    $output .= "\n";
                    $maxWidth = 240;
                    if($form->item_pictures_size > $maxWidth){
                        $maxWidth = $form->item_pictures_size;
                    }
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] .itemDes  {';
                    $output .= ' max-width:' . ($maxWidth) . 'px; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel .genSlide .genContent div.selectable .wpe_itemQtField  {';
                    $output .= ' width:' . ($form->item_pictures_size) . 'px; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel .genSlide .genContent div.selectable .wpe_itemQtField .wpe_qtfield  {';
                    $output .= ' margin-left:' . (0-(100-($form->item_pictures_size))/2) . 'px; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= 'body .lfb_datepickerContainer .ui-datepicker-title { ';
                    $output .= ' background-color:' . $form->colorA . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= 'body .lfb_datepickerContainer td a {';
                    $output .= ' color:' . $form->colorA . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= 'body .lfb_datepickerContainer  td.ui-datepicker-today a {';
                    $output .= ' color:' . $form->colorB . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] .has-switch span.switch-left {';
                    $output .= ' background-color:' . $form->colorA . ' !important; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel #lfb_summary table thead {';
                    $output .= ' background-color:' . $form->colorA . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel #lfb_summary table th.sfb_summaryStep {';
                    $output .= ' background-color:' . $fieldsColor . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel #lfb_summary table #lfb_summaryTotal {';
                    $output .= ' color:' . $form->colorA . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel .wpe_sliderQt {';
                    $output .= ' background-color:' . $form->colorC . '; ';
                    $output .= '}';
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel [data-type="slider"] {';
                    $output .= ' background-color:' . $form->colorC . '; ';
                    $output .= '}';
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel .wpe_sliderQt .ui-slider-range,'
                            . ' #estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel .wpe_sliderQt .ui-slider-handle, '
                            . ' #estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel [data-type="slider"] .ui-slider-range,'
                            . '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel [data-type="slider"] .ui-slider-handle {';
                    $output .= ' background-color:' . $form->colorA . ' !important; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel #finalPrice span:nth-child(2) {';
                    $output .= ' color:' . $form->colorC . '; ';
                    $output .= '}';
                    $output .= "\n";
                    $output .= '#estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] .lfb_colorPreview {';
                    $output .= ' border-color:' . $form->colorC . '; ';
                    $output .= '}';
                    $output .= "\n";
                    
                    
                    if($form->inverseGrayFx){
                        $output .= 'body #estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel .genSlide div.selectable:not(.checked) .img {
                            -webkit-filter: grayscale(100%);
                            -moz-filter: grayscale(100%);
                            -ms-filter: grayscale(100%);
                            -o-filter: grayscale(100%);
                            filter: grayscale(100%);
                            filter: gray;
                        }
                        body #estimation_popup.wpe_bootstraped[data-form="' . $form->id . '"] #mainPanel .genSlide div.selectable.checked .img {
                                -webkit-filter: grayscale(0%);
                            -moz-filter: grayscale(0%);
                            -ms-filter: grayscale(0%);
                            -o-filter: grayscale(0%);
                            filter: grayscale(0%);
                            filter: none;
                        }';
                    }


#estimation_popup.wpe_bootstraped #mainPanel #finalPrice span:nth-child(2) {

                    if ($form->customCss != "") {
                        $output .= $form->customCss;
                        $output .= "\n";
                    }
                }
            }
        }
        if ($output != '') {
            $output = "\n<style >\n" . $output . "</style>\n";
            echo $output;
        }
        if ($form->customJS != "") {
            $output = "\n<script>\n" . $form->customJS . "</script>\n";
            echo $output;
        }
    }

    private function isUpdated() {
        $settings = $this->getSettings();
        if ($settings->updated) {
            return false;
        } else {
            return true;
        }
    }

    public function frontend_enqueue_scripts($hook = '') {

        wp_register_script($this->_token . '-frontend', esc_url($this->assets_url) . 'js/lfb_frontend.min.js', array('jquery'), $this->_version);
        wp_enqueue_script($this->_token . '-frontend');
    }

    /* Ajax : get Current ref */

    public function get_currentRef() {
        $rep = false;
        $settings = $this->getSettings();
        if (isset($_POST['formID']) && !is_array($_POST['formID'])) {
            $formID = sanitize_text_field($_POST['formID']);

            global $wpdb;
            $table_name = $wpdb->prefix . "wpefc_forms";
            $rows = $wpdb->get_results("SELECT * FROM $table_name WHERE id=$formID LIMIT 1");
            $form = $rows[0];
            $current_ref = $form->current_ref + 1;
            $wpdb->update($table_name, array('current_ref' => $current_ref), array('id' => $form->id));
            $rep = $form->ref_root . $current_ref;
        }
        echo $rep;
        die();
    }

    // Send email to admin & customer
    private function sendOrderEmail($orderRef,$formID) {
        global $wpdb;

        $table_name = $wpdb->prefix . "wpefc_logs";
        $rows = $wpdb->get_results("SELECT * FROM $table_name WHERE ref='$orderRef' AND formID='$formID' LIMIT 1");
        if (count($rows) > 0) {
            $order = $rows[0];

            $table_name = $wpdb->prefix . "wpefc_forms";
            $rows = $wpdb->get_results("SELECT * FROM $table_name WHERE id=$order->formID LIMIT 1");
            $form = $rows[0];

            add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
            $headers = "";
            if ($order->email != "") {
                $headers = "Reply-to: " . $order->email."\n";
            }
            if (strpos($form->email, ',') > 0) {
                $emailsArr = explode(',', $form->email);
                $form->email = $emailsArr;
            }
            
	    //$order->content= chunk_split(base64_encode($order->content));
            //$headers .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $attachmentAdmin = array();
            if($form->sendPdfAdmin){
                try {

                   $url = 'http://freehtmltopdf.com';
                    $data = array(  'convert' => '', 
                                    'html' => '<html><head><title>'.$form->title.'</title><meta http-equiv="Content-Type" content="text/html;charset=UTF-8"><style>body{font-family: Helvetica,Arial;}</style></head><body>'.$order->content.'</html>',
                                    'baseurl' => get_bloginfo('url'));

                    $options = array(
                            'http' => array(
                                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                    'method'  => 'POST',
                                    'content' => http_build_query($data),
                            ),
                    );
                    $context  = stream_context_create($options);
                    $result = file_get_contents($url, false, $context);
                    $fileName = $form->title.'-'.$order->ref.'-'.uniqid().'.pdf';
                    chmod($this->dir.'/uploads', 0747);
                    $fp = fopen($this->dir.'/uploads/'.$fileName,'w');
                    fwrite($fp, $result);
                    fclose($fp);
                    $attachmentAdmin[] = $this->dir.'/uploads/'.$fileName;

                } catch (Throwable $t) {
                } catch (Exception $e) {
                }
            }
            if(wp_mail($form->email, $form->email_subject . ' - ' . $order->ref, $order->content, $headers,$attachmentAdmin)){
                if(count($attachmentAdmin)>0){
                    unlink($attachmentAdmin[0]);
                }
            }
                                   
            if ($order->sendToUser && $order->email != '') { 
                 $attachmentCustomer = array();
                if($form->sendPdfCustomer){
                    try {

                       $url = 'http://freehtmltopdf.com';
                        $data = array(  'convert' => '', 
                                        'html' => '<html><head><title>'.$form->title.'</title><meta http-equiv="Content-Type" content="text/html;charset=UTF-8"><style>body{font-family: Helvetica,Arial;}</style></head><body>'.$order->contentUser.'</html>',
                                        'baseurl' => get_bloginfo('url'));

                        $options = array(
                                'http' => array(
                                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                        'method'  => 'POST',
                                        'content' => http_build_query($data),
                                ),
                        );
                        $context  = stream_context_create($options);
                        $result = file_get_contents($url, false, $context);
                         $fileName = $form->title.'-'.$order->ref.'-'.uniqid().'.pdf';
                        chmod($this->dir.'/uploads', 0747);
                        $fp = fopen($this->dir.'/uploads/'.$fileName,'w');
                        fwrite($fp, $result);
                        fclose($fp);
                        $attachmentCustomer[] = $this->dir.'/uploads/'.$fileName;

                    } catch (Throwable $t) {
                    } catch (Exception $e) {
                    }
                }
                $headers = "";  
                add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
                if(wp_mail($order->email, $form->email_userSubject, $order->contentUser,$headers,$attachmentCustomer)){
                     if(count($attachmentCustomer)>0){
                        unlink($attachmentCustomer[0]);
                    }
                }
            }

            $table_name = $wpdb->prefix . "wpefc_logs";
            $wpdb->update($table_name, array('checked' => true), array('id' => $order->id));
        }
    }

    /*
     * Ajax : send email
     */
    public function send_email() {
        global $wpdb;
        $settings = $this->getSettings();
        $formID = sanitize_text_field($_POST['formID']);
        $formSession = sanitize_text_field(($_POST['formSession']));        
        $phone = sanitize_text_field($_POST['phone']);
        $firstName = sanitize_text_field($_POST['firstName']);
        $lastName = sanitize_text_field($_POST['lastName']);
        $address = sanitize_text_field($_POST['address']);
        $city = sanitize_text_field($_POST['city']);
        $country = sanitize_text_field($_POST['country']);
        $state = sanitize_text_field($_POST['state']);
        $zip = sanitize_text_field($_POST['zip']);
        $email = sanitize_text_field($_POST['email']);        
        $contentTxt = sanitize_text_field($_POST['contentTxt']);
        $contactSent = $_POST['contactSent'];
        
        $total = sanitize_text_field($_POST['total']);
        $totalSub = sanitize_text_field($_POST['totalSub']);
        $subFrequency = sanitize_text_field($_POST['subFrequency']);
        $formTitle = sanitize_text_field($_POST['formTitle']);
        $stripeToken = sanitize_text_field($_POST['stripeToken']);  
        $stripeTokenB = sanitize_text_field($_POST['stripeTokenB']);     
        $itemsArray = $_POST['items'];
                
        $usePaypalIpn = false;
        if (isset($_POST['usePaypalIpn']) && $_POST['usePaypalIpn'] == '1') {
            $usePaypalIpn = true;
        }
        $sendUser = 0;
        $discountCode =  sanitize_text_field($_POST['discountCode']);
        if($discountCode != ""){
            $table_name = $wpdb->prefix . "wpefc_coupons";
            $rows = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=$formID AND couponCode='$discountCode' LIMIT 1");
            if(count($rows)>0){
                $coupon = $rows[0];
                $coupon->currentUses ++;
                if($coupon->useMax > 0 && $coupon->currentUses >= $coupon->useMax){
                    $wpdb->delete($table_name, array('id' => $coupon->id));                    
                } else {
                    $wpdb->update($table_name, array('currentUses' => $coupon->currentUses), array('id' => $coupon->id));                    
                }
            }   
        }

        $table_name = $wpdb->prefix . "wpefc_forms";
        $rows = $wpdb->get_results("SELECT * FROM $table_name WHERE id=$formID LIMIT 1");
        $form = $rows[0];

        $summary = ($_POST['summary']);
        $summaryA  = ($_POST['summaryA']);
        //$summaryPdf = $_POST['summaryPdf'];

        add_filter( 'safe_style_css', function( $styles ) {
            $styles[] = 'border-color';
            $styles[] = 'background-color';
            $styles[] = 'font-size';
            $styles[] = 'padding';
            $styles[] = 'color';
            $styles[] = 'text-align';
            $styles[] = 'line-height';
            $styles[] = 'margin';
            return $styles;
        });
        $contentProject = $summary;
        $contentProject = wp_kses($contentProject, array(
            'br' => array(),
            'u' => array(),
            'p' => array(),
            'b' => array(),
            'span' => array('style' => true),
            'strong' => array('style' => true),
            'div' => array('style' => true),
            'td' => array('style' => true, 'align' => true, 'colspan' => true, 'bgcolor' => true, 'color' => true),
            'thead' => array('style' => true, 'bgcolor' => true),
            'th' => array('style' => true, 'align' => true, 'colspan' => true, 'bgcolor' => true, 'color' => true),
            'table' => array('style' => true, 'cellspacing' => true, 'cellpadding' => true, 'border' => true, 'width' => true, 'bordercolor' => true, 'bgcolor' => true),
            'tbody' => array('style' => true),
            'tr' => array('style' => true)
        ));
        $informations = $_POST['informations'];
        $informations = wp_kses($informations, array(
            'br' => array(),
            'u' => array(),
            'p' => array(),
            'b' => array(),
            'strong' => array(),
        ));
        $contentUser = '';

        $current_ref = $form->current_ref + 1;
        $wpdb->update($table_name, array('current_ref' => $current_ref), array('id' => $form->id));
        if (!isset($_POST['gravity']) || $_POST['gravity'] == 0) {

            if ($_POST['email_toUser'] == '1') {
                $sendUser = 1;

                $projectCustomer = stripslashes($contentProject);
                $projectCustomer = str_replace('C:\\fakepath\\', "", $projectCustomer);

                $content = $form->email_userContent;
                $content = str_replace("[customer_email]", sanitize_text_field($_POST['email']), $content);
                $content = str_replace("[project_content]", $projectCustomer, $content);
                $content = str_replace("[information_content]", stripslashes($informations), $content);
                $content = str_replace("[total_price]", sanitize_text_field($_POST['totalTxt']), $content);
                $content = str_replace("[ref]", $form->ref_root . $current_ref, $content);
                
                // recover items values
            $lastPos = 0;            
            while (($lastPos = strpos($content, '[item-', $lastPos)) !== false) {
                $itemID = substr($content, $lastPos + 6, (strpos($content, '_', $lastPos) - ($lastPos + 6)));
                $attribute = substr($content,strpos($content, '_', $lastPos)+1,((strpos($content, ']', $lastPos))-strpos($content, '_', $lastPos))-1);
                $newContent = substr($content, 0, $lastPos);
                $newValue = '';
                $itemFound = false;
                if(substr($itemID,0,1) != 'f'){
                    foreach ($_POST['items'] as $key => $value) {
                        if ($value['itemid'] == $itemID){
                            if($value[$attribute]){
                                $newValue = $value[$attribute];
                                $itemFound = true;
                            }
                        }
                    }
                } else {
                    foreach ($_POST['fieldsLast'] as $key => $value) {
                        if ($value['fieldID'] == substr($itemID,1)){                            
                            $newValue = $value['value'];
                            $itemFound = true;                            
                        }
                    }
                }                
                $newContent .= $newValue;
                $newContent .= substr($content, strpos($content, ']', $lastPos)+1);                
                $content = $newContent;
                
                if($itemFound){
                     $lastPos = $lastPos + strlen($newValue);                       
                }else {
                    $lastPos = $lastPos + strlen('[item-'.$itemID.']');
                }
            }
            
                $contentUser = $content;
            }

            $projectAdmin = stripslashes($summaryA);
            $lastPos = 0;
            $positions = array();

            $projectAdmin = str_replace('C:\\fakepath\\', "", $projectAdmin);
            while (($lastPos = strpos($projectAdmin, 'class="lfb_file">', $lastPos)) !== false) {
                $positions[] = $lastPos;
                $lastPos = $lastPos + 17;
                $fileStartPos = $lastPos;
                // $fileStartPos = strpos($projectAdmin, ':', $lastPos) + 2;
                $lastSpan = strpos($projectAdmin, '</span>', $fileStartPos);
                $file = substr($projectAdmin, $fileStartPos, $lastSpan - $fileStartPos);
                $projectAdmin = str_replace($file, '<a href="' . $this->uploads_url . $formSession .'/'.$file . '">' . $file . '</a>', $projectAdmin);
            }
            add_filter( 'safe_style_css', function( $styles ) {
                $styles[] = 'border-color';
                $styles[] = 'background-color';
                $styles[] = 'font-size';
                $styles[] = 'padding';
                $styles[] = 'color';
                $styles[] = 'text-align';
                $styles[] = 'line-height';
                $styles[] = 'margin';
                return $styles;
            });
            $projectAdmin = wp_kses($projectAdmin, array(
                'br' => array(),
                'u' => array(),
                'p' => array(),
                'b' => array(),
                'a' => array('href' => true),
                'span' => array('style' => true, 'class' => true),
                'strong' => array('style' => true),
                'div' => array('style' => true),
                'td' => array('style' => true, 'align' => true, 'colspan' => true, 'bgcolor' => true, 'color' => true),
                'thead' => array('style' => true, 'bgcolor' => true),
                'th' => array('style' => true, 'align' => true, 'colspan' => true, 'bgcolor' => true, 'color' => true),
                'table' => array('style' => true, 'cellspacing' => true, 'cellpadding' => true, 'border' => true, 'width' => true, 'bordercolor' => true, 'bgcolor' => true),
                'tbody' => array('style' => true),
                'tr' => array('style' => true)
            ));

            
           
            
            $content = $form->email_adminContent;
            $content = str_replace("[customer_email]", $form->ref_root . $current_ref, $content);
            $content = str_replace("[project_content]", $projectAdmin, $content);
            $content = str_replace("[information_content]", stripslashes($informations), $content);
            $content = str_replace("[total_price]", sanitize_text_field($_POST['totalTxt']), $content);
            $content = str_replace("[ref]", $form->ref_root . $current_ref, $content);
            
            // recover items values
            $lastPos = 0;            
            while (($lastPos = strpos($content, '[item-', $lastPos)) !== false) {
                $itemID = substr($content, $lastPos + 6, (strpos($content, '_', $lastPos) - ($lastPos + 6)));
                $attribute = substr($content,strpos($content, '_', $lastPos)+1,((strpos($content, ']', $lastPos))-strpos($content, '_', $lastPos))-1);
                $newContent = substr($content, 0, $lastPos);
                $newValue = '';
                $itemFound = false;
                if(substr($itemID,0,1) != 'f'){
                    foreach ($_POST['items'] as $key => $value) {
                        if ($value['itemid'] == $itemID){
                            if($value[$attribute]){
                                $newValue = $value[$attribute];
                                $itemFound = true;
                            }
                        }
                    }
                } else {
                    foreach ($_POST['fieldsLast'] as $key => $value) {
                        if ($value['fieldID'] == substr($itemID,1)){                            
                            $newValue = $value['value'];
                            $itemFound = true;                            
                        }
                    }
                }                
                $newContent .= nl2br($newValue);
                $newContent .= substr($content, strpos($content, ']', $lastPos)+1);                
                $content = $newContent;
                
                if($itemFound){
                     $lastPos = $lastPos + strlen($newValue);                       
                }else {
                    $lastPos = $lastPos + strlen('[item-'.$itemID.']');
                }
            }
            

            if (isset($_POST['email']) && $contactSent == 0) {
                if($form->useMailchimp && $form->mailchimpList != ""){
                    try{
                    $MailChimp = new Mailchimp($form->mailchimpKey);
                    $merge_vars = array('FNAME'=>$firstName, 'LNAME'=>$lastName,'phone'=>$phone,
                        'address1'=>array('addr1'=>$address, 'city'=>$city, 'state'=>$state, 'zip'=>$zip,'country'=>$country));
                    
                    $MailChimp->lists->subscribe($form->mailchimpList,array('email'=>$email),$merge_vars,'html',$form->mailchimpOptin);
                    } catch (Throwable $t) {
                    } catch (Exception $e) {
                    }
                    
                }
                if($form->useMailpoet){            
                    $MailPoet = new MailPoetListEP();
                    $MailPoet->add_contact($email, $form->mailPoetList,$firstName,$lastName);    
                }
                if($form->useGetResponse){ 
                     $GetResponse = new GetResponseEP($form->getResponseKey);
                     $merge_vars = array('firstName'=>$firstName,'lastName'=>$lastName,'phone'=>$phone,
                         'city'=>$city,'state'=>$state,'zipCode'=>$zip);
                     $GetResponse->addContact($form->getResponseList, $firstName.' '.$lastName, $email,'standard',0,$merge_vars);
                }
            }

            $table_name = $wpdb->prefix . "wpefc_logs";
            $checked = false;
            
                   
            $wpdb->insert($table_name, array('ref' => $form->ref_root . $current_ref, 'email' => $email,'phone'=>$phone,'firstName'=>$firstName,'lastName'=>$lastName,
                'address'=>$address,'city'=>$city,'country'=>$country,'state'=>$state,'zip'=>$zip,
                'formID' => $formID, 'dateLog' => date('Y-m-d'), 'content' => $content, 'contentUser' => $contentUser, 'sendToUser' => $sendUser,
                'totalPrice'=>$total, 'totalSubscription'=>$totalSub,'subscriptionFrequency'=>$subFrequency,'formTitle'=>$formTitle,'contentTxt'=>$contentTxt));
            $orderID = $wpdb->insert_id;
            $chkStripe = false;
            $useStripe = false;
            if($stripeToken != "" && $form->use_stripe){
                $useStripe = true;
                $chkStripe = $this->doStripePayment($orderID,$stripeToken,$stripeTokenB);                
            }
               
            if (!$usePaypalIpn && (!$useStripe || $chkStripe)) {
                $this->sendOrderEmail($form->ref_root . $current_ref,$form->id);
            }
        }


        echo $form->ref_root . $current_ref;
        die();
    }
    
    /*
     * Stripe : new subscription
     */
    public function doStripePayment($orderID,$stripeToken,$stripeTokenB) {
        global $wpdb;          
        $rep = false;
        
        $table_name = $wpdb->prefix . "wpefc_logs";
        $orders = $wpdb->get_results("SELECT * FROM $table_name WHERE id=" . $orderID . " LIMIT 1");
        if(count($orders)>0){
            $order = $orders[0];
            $form = $this->getFormDatas($order->formID);
             require_once( 'stripe/Stripe.php');
             require_once( 'stripe/JsonSerializable.php');
             require_once( 'stripe/ApiRequestor.php');
             require_once( 'stripe/ApiResponse.php');
             require_once( 'stripe/Error/Base.php');
             require_once( 'stripe/Error/InvalidRequest.php');
             require_once( 'stripe/Error/Authentication.php');
             require_once( 'stripe/Util/Util.php');
             require_once( 'stripe/Util/Set.php');
             require_once( 'stripe/HttpClient/ClientInterface.php');
             require_once( 'stripe/HttpClient/CurlClient.php');
             require_once( 'stripe/Util/RequestOptions.php');
             require_once( 'stripe/StripeObject.php');
             require_once( 'stripe/AttachedObject.php');
             require_once( 'stripe/ApiResource.php');
             require_once( 'stripe/Plan.php');
             require_once( 'stripe/ExternalAccount.php');
             require_once( 'stripe/Card.php');
             require_once( 'stripe/Charge.php');
             require_once( 'stripe/Collection.php');
             require_once( 'stripe/Error/Card.php');
             require_once( 'stripe/Customer.php');
             require_once( 'stripe/Subscription.php');
             
                     
            
            if($order->totalPrice>0){
                    $price = number_format((float)$order->totalPrice, 2, '', '');
                   try {
                \Stripe\Stripe::setApiKey($form->stripe_secretKey);    
                    $charge = \Stripe\Charge::create(array(
                    'amount' => $price,
                    "currency" => strtolower($form->stripe_currency),
                    'source' => $stripeToken,
                    'description'=> $form->title.' - '.$order->ref,
                    "metadata"=> array('email'=>$order->email)                     
                  ));  
                    
                $rep = true;
                    
                }catch (Throwable $t) { 
                    echo 'Throwable';
                    echo $t;
                } catch (\Stripe\Error\ApiConnection $e) {
                    echo 'ApiConnection';
                // Network problem, perhaps try again.
                } catch (\Stripe\Error\InvalidRequest $e) {
                    echo 'InvalidRequest';
                    // You screwed up in your programming. Shouldn't happen!
                } catch (\Stripe\Error\Api $e) {
                    echo 'Api';
                    // Stripe's servers are down!
                } catch (\Stripe\Error\Card $e) {
                    echo 'Card';
                    // Card was declined.
                }
            }
            if($order->totalSubscription>0){                  
                // $intervalsStripe = array('D'=>'day','W'=>'week', 'M'=>'month','Y'=>'year');
                 $interval = $form->stripe_subsFrequencyType;
                 $price = $order->totalSubscription;
                // echo $price."\n";
                 $price = number_format((float)$price, 2, '', '');
                 //echo $price;

                  try {
                      $trialDays = 0;
                      if($order->totalPrice>0){
                          $trialDays = 30;
                          if($interval == 'day'){
                              $trialDays = 1;
                          }
                          if($interval == 'week'){
                              $trialDays = 7;
                          }
                          if($interval == 'year'){
                              $trialDays = 365;
                          }
                      }
                 if($order->totalPrice >0){
                     $stripeToken = $stripeTokenB;
                 }
                \Stripe\Stripe::setApiKey($form->stripe_secretKey);   
                 \Stripe\Plan::create(array(
                     "amount" => $price,
                     "interval" => $interval,
                     "name" => $form->title.' - '.$order->ref,
                     "currency" => strtolower($form->stripe_currency),
                     "id" => $order->id,
                     "metadata"=> array('email'=>$order->email,'date'=>$order->dateLog),
                     "trial_period_days"=>$trialDays)
                   );
                 
                 $customer = \Stripe\Customer::create(array(
                    "source" => $stripeToken, 
                    "plan" => $order->id,
                    "email" => $order->email
                  ));
                 
                $rep = true;
                 
                  }catch (Throwable $t) { 
                    echo 'Throwable';
                    echo $t;
                } catch (\Stripe\Error\ApiConnection $e) {
                    echo 'ApiConnection';
                // Network problem, perhaps try again.
                } catch (\Stripe\Error\InvalidRequest $e) {
                    echo 'InvalidRequest';
                    // You screwed up in your programming. Shouldn't happen!
                } catch (\Stripe\Error\Api $e) {
                    echo 'Api';
                    // Stripe's servers are down!
                } catch (\Stripe\Error\Card $e) {
                    echo 'Card';
                    // Card was declined.
                }
            }
        }           
       
        return $rep;        
    }
    
    public function sendContact(){   
        global $wpdb;
        $phone = sanitize_text_field($_POST['phone']);
        $firstName = sanitize_text_field($_POST['firstName']);
        $lastName = sanitize_text_field($_POST['lastName']);
        $address = sanitize_text_field($_POST['address']);
        $city = sanitize_text_field($_POST['city']);
        $country = sanitize_text_field($_POST['country']);
        $state = sanitize_text_field($_POST['state']);
        $zip = sanitize_text_field($_POST['zip']);
        $email = sanitize_text_field($_POST['email']); 
        $formID = sanitize_text_field($_POST['formID']);
        
         $table_name = $wpdb->prefix . "wpefc_forms";
        $rows = $wpdb->get_results("SELECT * FROM $table_name WHERE id=$formID LIMIT 1");
        if(count($rows)>0){
            $form = $rows[0];
            
            if (isset($_POST['email'])) {
                if($form->useMailchimp && $form->mailchimpList != ""){
                    try{
                    $MailChimp = new Mailchimp($form->mailchimpKey);
                    $merge_vars = array('FNAME'=>$firstName, 'LNAME'=>$lastName,'phone'=>$phone,
                        'address1'=>array('addr1'=>$address, 'city'=>$city, 'state'=>$state, 'zip'=>$zip,'country'=>$country));
                    
                    $MailChimp->lists->subscribe($form->mailchimpList,array('email'=>$email),$merge_vars,'html',$form->mailchimpOptin);
                    } catch (Throwable $t) {
                    } catch (Exception $e) {
                    }
                    
                }
                if($form->useMailpoet){            
                    $MailPoet = new MailPoetListEP();
                    $MailPoet->add_contact($email, $form->mailPoetList,$firstName,$lastName);    
                }
                if($form->useGetResponse){ 
                     $GetResponse = new GetResponseEP($form->getResponseKey);
                     $merge_vars = array('firstName'=>$firstName,'lastName'=>$lastName,'phone'=>$phone,
                         'city'=>$city,'state'=>$state,'zipCode'=>$zip);
                     $GetResponse->addContact($form->getResponseList, $firstName.' '.$lastName, $email,'standard',0,$merge_vars);
                }
            }
        }        
        die();
    }
    
    public function applyCouponCode(){
        global $wpdb;
        $rep = '';
        $table_name = $wpdb->prefix . "wpefc_coupons";
        $formID = sanitize_text_field($_POST['formID']);        
        $code = sanitize_text_field($_POST['code']);
        $rows = $wpdb->get_results("SELECT * FROM $table_name  WHERE couponCode='$code' AND formID=$formID LIMIT 1");
        $chk = false;
        if(count($rows)>0){
            $coupon = $rows[0];
            if($coupon->reductionType == 'percentage'){
               $rep =  $coupon->reduction.'%';
            } else {
               $rep =  $coupon->reduction;
            }
        }        
        echo $rep;
        die();
    }

    function custom_wp_mail_from($email) {
        return sanitize_text_field($_POST['email']);
    }

    /**
     * Get  fields datas
     * @since   1.6.0
     * @return object
     */
    public function getFieldsData() {
        global $wpdb;
        $table_name = $wpdb->prefix . "wpefc_fields";
        $rows = $wpdb->get_results("SELECT * FROM $table_name  ORDER BY ordersort ASC");
        return $rows;
    }

    /**
     * Get  fields from specific form
     * @since   1.6.0
     * @return object
     */
    public function getFieldDatas($form_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . "wpefc_fields";
        $rows = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=$form_id ORDER BY ordersort ASC");
        return $rows;
    }

    /**
     * Get  form by pageID
     * @since   1.6.0
     * @return object
     */
    public function getFormByPageID($pageID) {
        global $wpdb;
        $table_name = $wpdb->prefix . "wpefc_forms";
        $rows = $wpdb->get_results("SELECT * FROM $table_name WHERE form_page_id=$pageID LIMIT 1");
        if ($rows) {
            return $rows[0];
        } else {
            return null;
        }
    }

    /**
     * Get Forms datas
     * @return Array
     */
    private function getFormsData() {
        global $wpdb;
        $table_name = $wpdb->prefix . "wpefc_forms";
        $rows = $wpdb->get_results("SELECT * FROM $table_name");
        return $rows;
    }

    /**
     * Get specific Form datas
     * @return object
     */
    public function getFormDatas($form_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . "wpefc_forms";
        $rows = $wpdb->get_results("SELECT * FROM $table_name WHERE id=$form_id LIMIT 1");
        if (count($rows) > 0) {
            return $rows[0];
        } else {
            return null;
        }
    }

    /**
     * Recover uploaded files from the form
     * @access  public
     * @since   1.0.0
     * @return  object
     */
    public function uploadFormFiles() {
        $formSession = sanitize_text_field($_POST['formSession']);
       
        foreach ($_FILES as $key => $value) {
            if ($value["error"] > 0) {
                echo "error";
            } else {
                if (strlen($value["name"]) > 4 &&
                        $value['size'] < 10485760 &&
                        strpos(strtolower($value["name"]), '.php') === false &&
                        strpos(strtolower($value["name"]), '.js') === false &&
                        strpos(strtolower($value["name"]), '.html') === false &&
                        strpos(strtolower($value["name"]), '.phtml') === false &&
                        strpos(strtolower($value["name"]), '.pl') === false &&
                        strpos(strtolower($value["name"]), '.py') === false &&
                        strpos(strtolower($value["name"]), '.jsp') === false &&
                        strpos(strtolower($value["name"]), '.asp') === false &&
                        strpos(strtolower($value["name"]), '.htm') === false &&
                        strpos(strtolower($value["name"]), '.shtml') === false &&
                        strpos(strtolower($value["name"]), '.sh') === false &&
                        strpos(strtolower($value["name"]), '.cgi') === false
                ) {
                    
                    if(!is_dir($this->uploads_dir.$formSession)){
                         mkdir($this->uploads_dir.$formSession);
                        chmod($this->uploads_dir.$formSession, 0747);
                    }
                    move_uploaded_file($value["tmp_name"],$this->uploads_dir.$formSession.'/'.$value["name"]);
                    chmod($this->uploads_dir.$formSession.'/'.$value["name"], 0644);
                }
            }
        }
        die();
    }
    
    public function removeFile(){
        $formSession = sanitize_text_field($_POST['formSession']);
        $file = sanitize_text_field($_POST['file']);
        $fileName = $formSession . '_' . $file;
        if(file_exists($this->uploads_dir .$fileName)){
            unlink($this->uploads_dir .$fileName);
        }
        die();
    }

    /**
     * Return steps data.
     * @access  public
     * @since   1.0.0
     * @return  object
     */
    public function getStepsData($form_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . "wpefc_steps";
        $rows = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=$form_id ORDER BY ordersort");
        return $rows;
    }

    /**
     * Return items data.
     * @access  public
     * @since   1.0.0
     * @return  object
     */
    public function getItemsData($form_id) {
        global $wpdb;
        $results = array();
        $table_name = $wpdb->prefix . "wpefc_steps";
        $steps = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=$form_id ORDER BY ordersort");
        foreach ($steps as $step) {
            $table_name = $wpdb->prefix . "wpefc_items";
            $rows = $wpdb->get_results("SELECT * FROM $table_name WHERE stepID=$step->id ORDER BY ordersort");
            foreach ($rows as $row) {
                $results[] = $row;
            }
        }
        return $results;
    }

    // End getItemsData()

    /**
     * Save form datas to cart (woocommerce only)
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function cart_save() {
        global $woocommerce;
        $products = $_POST['products'];
        foreach ($products as $product) {
            //variation
            $productWoo = new WC_Product($product['product_id']);
            if ($product['variation'] != 0) {
                $productWoo = new WC_Product_Variation($product['variation']);
            }
            $existInCart = false;
            /* foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
              if ($product['variation'] == 0 && $cart_item['product_id'] == $product['product_id']) {
              $cart_item['quantity'] += $product['quantity'];
              }
              } */
            if ($product['variation'] == '0') {
                $woocommerce->cart->add_to_cart($product['product_id'], $product['quantity']);
            } else {
                $variation = new WC_Product_Variation($product['variation']);
                $attributes = $productWoo->get_variation_attributes();
                $woocommerce->cart->add_to_cart($product['product_id'], $product['quantity'], $product['variation'], $attributes);
            }
        }
        die();
    }

    /**
     * Main LFB_Core Instance
     *
     *
     * @since 1.0.0
     * @static
     * @see BSS_Core()
     * @return Main LFB_Core instance
     */
    public static function instance($file = '', $version = '1.0.0') {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($file, $version);
        }
        return self::$_instance;
    }

// End instance()

    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone() {
        
    }

// End __clone()

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup() {
        //  _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->_version);
    }

// End __wakeup()

    /**
     * Return settings.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function getSettings() {
        global $wpdb;
        $table_name = $wpdb->prefix . "wpefc_settings";
        $settings = $wpdb->get_results("SELECT * FROM $table_name WHERE id=1 LIMIT 1");
        return $settings[0];
    }

    // End getSettings()

    /**
     * Log the plugin version number.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    private function _log_version_number() {
        update_option($this->_token . '_version', $this->_version);
    }

}
