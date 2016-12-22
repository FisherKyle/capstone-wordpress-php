<?php

if (!defined('ABSPATH'))
    exit;

class LFB_admin {

    /**
     * The single instance
     * @var    object
     * @access  private
     * @since    1.0.0
     */
    private static $_instance = null;

    /**
     * The main plugin object.
     * @var    object
     * @access  public
     * @since    1.0.0
     */
    public $parent = null;

    /**
     * Prefix for plugin settings.
     * @var     string
     * @access  publicexport
     * Delete
     * @since   1.0.0
     */
    public $base = '';

    /**
     * Available settings for plugin.
     * @var     array
     * @access  public
     * @since   1.0.0
     */
    public $settings = array();

    /**
     * Is WooCommerce activated ?
     * @var     array
     * @access  public
     * @since   1.5.0
     */
    public $isWooEnabled = false;

    public function __construct($parent) {
        $this->parent = $parent;
        $this->base = 'wpt_';
        $this->dir = dirname($this->parent->file);
        add_action('admin_menu', array($this, 'add_menu_item'));
        add_action('admin_print_scripts', array($this, 'admin_scripts'));
        add_action('admin_print_styles', array($this, 'admin_styles'));
        add_action('wp_ajax_nopriv_lfb_saveStep', array($this, 'saveStep'));
        add_action('wp_ajax_lfb_saveStep', array($this, 'saveStep'));
        add_action('wp_ajax_nopriv_lfb_addStep', array($this, 'addStep'));
        add_action('wp_ajax_lfb_addStep', array($this, 'addStep'));
        add_action('wp_ajax_nopriv_lfb_loadStep', array($this, 'loadStep'));
        add_action('wp_ajax_lfb_loadStep', array($this, 'loadStep'));
        add_action('wp_ajax_nopriv_lfb_duplicateStep', array($this, 'duplicateStep'));
        add_action('wp_ajax_lfb_duplicateStep', array($this, 'duplicateStep'));
        add_action('wp_ajax_nopriv_lfb_removeStep', array($this, 'removeStep'));
        add_action('wp_ajax_lfb_removeStep', array($this, 'removeStep'));
        add_action('wp_ajax_nopriv_lfb_saveStepPosition', array($this, 'saveStepPosition'));
        add_action('wp_ajax_lfb_saveStepPosition', array($this, 'saveStepPosition'));
        add_action('wp_ajax_nopriv_lfb_newLink', array($this, 'newLink'));
        add_action('wp_ajax_lfb_newLink', array($this, 'newLink'));
        add_action('wp_ajax_nopriv_lfb_changePreviewHeight', array($this, 'changePreviewHeight'));
        add_action('wp_ajax_lfb_changePreviewHeight', array($this, 'changePreviewHeight'));
        add_action('wp_ajax_nopriv_lfb_saveLinks', array($this, 'saveLinks'));
        add_action('wp_ajax_lfb_saveLinks', array($this, 'saveLinks'));
        add_action('wp_ajax_nopriv_lfb_saveSettings', array($this, 'saveSettings'));
        add_action('wp_ajax_lfb_saveSettings', array($this, 'saveSettings'));
        add_action('wp_ajax_nopriv_lfb_loadSettings', array($this, 'loadSettings'));
        add_action('wp_ajax_lfb_loadSettings', array($this, 'loadSettings'));
        add_action('wp_ajax_nopriv_lfb_removeAllSteps', array($this, 'removeAllSteps'));
        add_action('wp_ajax_lfb_removeAllSteps', array($this, 'removeAllSteps'));
        add_action('wp_ajax_nopriv_lfb_addForm', array($this, 'addForm'));
        add_action('wp_ajax_lfb_addForm', array($this, 'addForm'));
        add_action('wp_ajax_nopriv_lfb_loadForm', array($this, 'loadForm'));
        add_action('wp_ajax_lfb_loadForm', array($this, 'loadForm'));
        add_action('wp_ajax_nopriv_lfb_saveForm', array($this, 'saveForm'));
        add_action('wp_ajax_lfb_saveForm', array($this, 'saveForm'));
        add_action('wp_ajax_nopriv_lfb_removeForm', array($this, 'removeForm'));
        add_action('wp_ajax_lfb_removeForm', array($this, 'removeForm'));
        add_action('wp_ajax_nopriv_lfb_loadFields', array($this, 'loadFields'));
        add_action('wp_ajax_lfb_loadFields', array($this, 'loadFields'));
        
        add_action('wp_ajax_nopriv_lfb_removeRedirection', array($this, 'removeRedirection'));
        add_action('wp_ajax_lfb_removeRedirection', array($this, 'removeRedirection'));
        add_action('wp_ajax_nopriv_lfb_saveRedirection', array($this, 'saveRedirection'));
        add_action('wp_ajax_lfb_saveRedirection', array($this, 'saveRedirection'));
        add_action('wp_ajax_nopriv_lfb_saveField', array($this, 'saveField'));
        add_action('wp_ajax_lfb_saveField', array($this, 'saveField'));
        add_action('wp_ajax_nopriv_lfb_saveItem', array($this, 'saveItem'));
        add_action('wp_ajax_lfb_saveItem', array($this, 'saveItem'));
        add_action('wp_ajax_nopriv_lfb_removeItem', array($this, 'removeItem'));
        add_action('wp_ajax_lfb_removeItem', array($this, 'removeItem'));
        add_action('wp_ajax_nopriv_lfb_exportForms', array($this, 'exportForms'));
        add_action('wp_ajax_lfb_exportForms', array($this, 'exportForms'));
        add_action('wp_ajax_nopriv_lfb_importForms', array($this, 'importForms'));
        add_action('wp_ajax_lfb_importForms', array($this, 'importForms'));
        add_action('wp_ajax_nopriv_lfb_checkLicense', array($this, 'checkLicense'));
        add_action('wp_ajax_lfb_checkLicense', array($this, 'checkLicense'));
        add_action('wp_ajax_nopriv_lfb_duplicateForm', array($this, 'duplicateForm'));
        add_action('wp_ajax_lfb_duplicateForm', array($this, 'duplicateForm'));
        add_action('wp_ajax_nopriv_lfb_duplicateItem', array($this, 'duplicateItem'));
        add_action('wp_ajax_lfb_duplicateItem', array($this, 'duplicateItem'));
        add_action('wp_ajax_nopriv_lfb_removeField', array($this, 'removeField'));
        add_action('wp_ajax_lfb_removeField', array($this, 'removeField'));
        add_action('wp_ajax_nopriv_lfb_loadLogs', array($this, 'loadLogs'));
        add_action('wp_ajax_lfb_loadLogs', array($this, 'loadLogs'));
        add_action('wp_ajax_nopriv_lfb_removeLog', array($this, 'removeLog'));
        add_action('wp_ajax_lfb_removeLog', array($this, 'removeLog'));
        add_action('wp_ajax_nopriv_lfb_loadLog', array($this, 'loadLog'));
        add_action('wp_ajax_lfb_loadLog', array($this, 'loadLog'));
        add_action('wp_ajax_nopriv_lfb_removeCoupon', array($this, 'removeCoupon'));
        add_action('wp_ajax_lfb_removeCoupon', array($this, 'removeCoupon'));
        add_action('wp_ajax_nopriv_lfb_removeAllCoupons', array($this, 'removeAllCoupons'));
        add_action('wp_ajax_lfb_removeAllCoupons', array($this, 'removeAllCoupons'));
        add_action('wp_ajax_nopriv_lfb_saveCoupon', array($this, 'saveCoupon'));
        add_action('wp_ajax_lfb_saveCoupon', array($this, 'saveCoupon'));
        add_action('wp_ajax_nopriv_lfb_getMailchimpLists', array($this, 'getMailchimpLists'));
        add_action('wp_ajax_lfb_getMailchimpLists', array($this, 'getMailchimpLists'));
        add_action('wp_ajax_nopriv_lfb_getMailpoetLists', array($this, 'getMailpoetLists'));
        add_action('wp_ajax_lfb_getMailpoetLists', array($this, 'getMailpoetLists'));
        add_action('wp_ajax_nopriv_lfb_getGetResponseLists', array($this, 'getGetResponseLists'));
        add_action('wp_ajax_lfb_getGetResponseLists', array($this, 'getGetResponseLists'));
        add_action('wp_ajax_nopriv_lfb_exportLogs', array($this, 'exportLogs'));
        add_action('wp_ajax_lfb_exportLogs', array($this, 'exportLogs'));
        add_action('wp_ajax_nopriv_lfb_changeItemsOrders', array($this, 'changeItemsOrders'));
        add_action('wp_ajax_lfb_changeItemsOrders', array($this, 'changeItemsOrders'));
        add_action('wp_ajax_nopriv_lfb_changeLastFieldsOrders', array($this, 'changeLastFieldsOrders'));
        add_action('wp_ajax_lfb_changeLastFieldsOrders', array($this, 'changeLastFieldsOrders'));
        add_action('wp_ajax_nopriv_lfb_loadCharts', array($this, 'loadCharts'));
        add_action('wp_ajax_lfb_loadCharts', array($this, 'loadCharts'));
        add_action('admin_init', array($this, 'checkAutomaticUpdates'));
        add_action('vc_before_init', array($this, 'init_vc'));
    }

    /*
     *  Add shortcode to VisualComposer
     */
    public function init_vc() {
        if (defined('WPB_VC_VERSION')) {
            global $wpdb;
            $formsValues = array();
            $table_name = $wpdb->prefix . "wpefc_forms";
            $forms = $wpdb->get_results("SELECT id,title FROM $table_name ORDER BY id ASC");
            foreach ($forms as $form) {
                $formsValues[] = $form->id;
            }

            vc_map(array(
                "name" => __('Estimation Form', 'lfb'),
                "base" => "estimation_form",
                "category" => 'Content',
                "icon" => 'icon_lfb_form',
                "params" => array(
                    array(
                        "type" => "dropdown",
                        "holder" => "div",
                        "class" => "",
                        "heading" => __("Form ID", 'lfb'),
                        "param_name" => "form_id",
                        "value" => $formsValues,
                        "std" => "1",
                        "description" => __("Select a form", "lfb")
                    ),
                    array(
                        "type" => "dropdown",
                        "holder" => "div",
                        "class" => "",
                        "heading" => __("Popup", 'lfb'),
                        "param_name" => "popup",
                        "value" => array('false', 'true'),
                        "std" => "false",
                        "description" => __("To use as popup", "lfb")
                    ),
                    array(
                        "type" => "dropdown",
                        "holder" => "div",
                        "class" => "",
                        "heading" => __("Fullscreen", 'lfb'),
                        "param_name" => "fullscreen",
                        "value" => array('false', 'true'),
                        "std" => "false",
                        "description" => __("To use in fullscreen", "lfb")
                    )
                )
            ));
        }
    }

    /**
     * Add menu to admin
     * @return void
     */
    public function add_menu_item() {
        add_menu_page(__("E&P Form Builder", 'lfb'), __("E&P Form Builder", 'lfb'), 'manage_options', 'lfb_menu', array($this, 'view_edit_lfb'), 'dashicons-format-aside');
        $menuSlag = 'lfb_menu';
    }

    public function getSettings() {
        global $wpdb;
        $table_name = $wpdb->prefix . "wpefc_settings";
        $settings = $wpdb->get_results("SELECT * FROM $table_name WHERE id=1 LIMIT 1");
        $settings = $settings[0];
        return $settings;
    }

    public function getMailchimpLists() {
        $apiKey = sanitize_text_field($_POST['apiKey']);
        if ($apiKey != "") {
            $MailChimp = new Mailchimp($apiKey);
            $result = $MailChimp->lists->getList();
            foreach ($result['data'] as $list) {
                echo '<option value="' . $list['id'] . '">' . $list['name'] . '</option>';
            }
        }
        die();
    }

    public function getMailpoetLists() {
        $MailPoet = new MailPoetListEP();
        $result = $MailPoet->all();
        foreach ($result as $list) {
            echo '<option value="' . $list['list_id'] . '">' . $list['name'] . '</option>';
        }

        die();
    }

    public function getGetResponseLists() {
        $apiKey = sanitize_text_field($_POST['apiKey']);
        if ($apiKey != "") {
            $GetResponse = new GetResponse($apiKey);
            $result = $GetResponse->getCampaigns();
            foreach ($result as $list => $value) {
                echo '<option value="' . $list . '">' . $value->name . '</option>';
            }
        }
    }

    /*
     * Main view
     */

    public function view_edit_lfb() {
        global $wpdb;
        $this->checkFields();
        $settings = $this->getSettings();
        wp_enqueue_style('thickbox');
        wp_enqueue_script('thickbox');


        echo '<div id="lfb_loader"></div>';
        echo '<div id="lfb_bootstraped" class="lfb_bootstraped lfb_panel">';
        echo '<div id="estimation_popup" class="wpe_bootstraped">';

        echo '<div id="lfb_formWrapper" >';
        echo '<div class="lfb_winHeader col-md-12 palette palette-turquoise">
               <span class="glyphicon  glyphicon-list-alt" style="opacity: 0;"></span><span class="lfb_iconLogo"></span>' . __('Estimation & Payment Forms', 'lfb') . '';
        echo '<div class="btn-toolbar">';
        echo '<div class="btn-group">';
        echo '<a class="btn btn-primary" href="javascript:" onclick="lfb_closeSettings();" data-toggle="tooltip" title="' . __('Return to the forms list', 'lfb') . '" data-placement="left"><span class="glyphicon glyphicon-list"></span></a>';
        echo '</div>';
        echo '</div>'; // eof toolbar
        echo '</div>'; // eof lfb_winHeader
        echo '<div class="clearfix"></div>';


        echo '<div id="lfb_panelSettings">';
        echo '<div class="container-fluid lfb_container" style="max-width: 90%;margin: 0 auto;margin-top: 18px;">';
        echo '</div>'; // eof container
        echo '</div>'; // eof lfb_panelSettings

        echo '<div id="lfb_panelLogs">';
        echo '<div class="container-fluid lfb_container" style="max-width: 90%;margin: 0 auto;margin-top: 18px;">';
        echo '<div class="col-md-12">';

        echo '<p style="float: right; margin-bottom:0px;">'
        . '<a href="javascript:" onclick="lfb_exportLogs();" class="btn btn-default" style="margin-right: 12px;"><span class="glyphicon glyphicon-cloud-download"></span>' . __('Export as CSV', 'lfb') . '</a>'
        . '<a href="javascript:" onclick="lfb_showLoader();lfb_openCharts(jQuery(\'#lfb_panelLogs\').attr(\'data-formid\'));"  style="margin-right: 12px;"  class="btn btn-default"><span class="glyphicon glyphicon-stats"></span>' . __('View statistics', 'lfb') . '</a>'
        . '<a href="javascript:" onclick="lfb_loadForm(jQuery(\'#lfb_panelLogs\').attr(\'data-formid\'));" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span>' . __('Return to the form', 'lfb') . '</a>'
        . '</p>';
        echo '<div role="tabpanel">';
        echo '<ul class="nav nav-tabs" role="tablist" >
                <li role="presentation" class="active" ><a href="#wpefc_formsTabGeneral" aria-controls="general" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-th-list" ></span > ' . __('Orders List', 'lfb') . ' </a ></li >
                </ul >';
        echo '<div class="tab-content" >';
        echo '<div role="tabpanel" class="tab-pane active" id="wpefc_formsTabGeneral" >';
        echo '<table id="lfb_logsTable" class="table">';
        echo '<thead>';
        echo '<th>' . __('Date', 'lfb') . '</th>';
        echo '<th>' . __('Reference', 'lfb') . '</th>';
        echo '<th>' . __('Email', 'lfb') . '</th>';
        echo '<th>' . __('Actions', 'lfb') . '</th>';
        echo '</thead>';
        echo '<tbody>';
        echo '</tbody>';
        echo '</table>';

        echo '</div>'; // eof tab-content
        echo '</div>'; // eof wpefc_formsTabGeneral
        echo '</div>'; // eof tabpanel

        echo '</div>'; // eof col-md-12"
        echo '</div>'; // eof lfb_container

        echo '</div>'; // eof lfb_panelLogs



        echo '<div id="lfb_panelCharts">';
        echo '<div class="container-fluid lfb_container" style="max-width: 90%;margin: 0 auto;margin-top: 18px;">';
        echo '<div class="col-md-12">';
        echo '<p style="float: right; margin-bottom:0px;">'
        . '<a href="javascript:"  onclick="lfb_loadLogs(jQuery(\'#lfb_panelCharts\').attr(\'data-formid\'));"  style="margin-right: 12px;"  class="btn btn-default"><span class="glyphicon glyphicon-list-alt"></span>' . __('View orders', 'lfb') . '</a>'
        . '<a href="javascript:" onclick="lfb_closeCharts();" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span>' . __('Return to the form', 'lfb') . '</a>'
        . '</p>';
        echo '<div role="tabpanel">';
        echo '<ul class="nav nav-tabs" role="tablist" >
                <li role="presentation" class="active" ><a href="#lfb_chartsTab" aria-controls="general" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-th-list" ></span > ' . __('Statistics', 'lfb') . ' </a ></li >
                </ul >';
        echo '<div class="tab-content" >';
        echo '<div role="tabpanel" class="tab-pane active" id="lfb_chartsTab" >';
        echo '<div id="lfb_chartsMenu">';
        echo '<div class="form-group">';
        echo '<label>' . __('Type of chart', 'lfb') . '</label>';
        echo '<select id="lfb_chartsTypeSelect" class="form-control">';
        echo '<option value="month">' . __('Month', 'lfb') . '</option>';
        echo '<option value="year" selected>' . __('Year', 'lfb') . '</option>';
        echo '<option value="all">' . __('All years', 'lfb') . '</option>';
        echo '</select>';
        echo '<select id="lfb_chartsMonth" class="form-control">';

        $table_name = $wpdb->prefix . "wpefc_logs";
        $logs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY dateLog ASC LIMIT 1");
        $yearMin = date('Y');
        $monthMin = 1;
        $currentYear = date('Y');
        if (count($logs) > 0) {
            $log = $logs[0];
            $yearMin = substr($log->dateLog, 0, 4);
            $monthMin = substr($log->dateLog, 6, 2);
        }
        for ($a = $yearMin; $a <= $currentYear; $a++) {
            for ($i = 1; $i <= 12; $i++) {
                $month = $i;
                if ($month < 10) {
                    $month = '0' . $month;
                }
                $sel = '';
                if ($month == date('m')) {
                    $sel = 'selected';
                }
                echo '<option value="' . $a . '-' . $month . '" ' . $sel . '>' . $a . '-' . $month . '</option>';
            }
            $monthMin = 1;
        }
        echo '</select>';
        echo '<select id="lfb_chartsYear" class="form-control">';


        $table_name = $wpdb->prefix . "wpefc_logs";
        $logs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY dateLog ASC LIMIT 1");
        $yearMin = date('Y');
        $currentYear = date('Y');
        if (count($logs) > 0) {
            $log = $logs[0];
            $yearMin = substr($log->dateLog, 0, 4);
        }
        for ($i = $yearMin; $i <= $currentYear; $i++) {
            $sel = '';
            if ($i == $currentYear) {
                $sel = 'selected';
            }
            echo '<option value="' . $i . '" ' . $sel . '>' . $i . '</option>';
        }
        echo '</select>';
        echo '</div>';

        echo '</div>'; // eof lfb_chartsMenu
        echo '<div id="lfb_charts"></div>';

        echo '</div>'; // eof tab-content
        echo '</div>'; // eof wpefc_formsTabGeneral
        echo '</div>'; // eof tabpanel

        echo '</div>'; // eof col-md-12"
        echo '</div>'; // eof lfb_container
        echo '</div>'; // eof lfb_panelCharts


        echo '<div class="clearfix"></div>';

        echo '<div id="lfb_panelFormsList">';
        echo '<div class="container-fluid lfb_container" style="max-width: 90%;margin: 0 auto;margin-top: 18px;">';
        echo '<div class="col-md-12">';
        echo '<div role="tabpanel">';
        echo '<ul class="nav nav-tabs" role="tablist" >
                <li role="presentation" class="active" ><a href="#wpefc_formsTabGeneral" aria-controls="general" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-th-list" ></span > ' . __('Forms List', 'lfb') . ' </a ></li >
                </ul >';
        echo '<div class="tab-content" >';
        echo '<div role="tabpanel" class="tab-pane active" id="wpefc_formsTabGeneral" >';

        echo '<p style="text-align: right; margin-top: 18px;">
            <a href="javascript:" style="margin-right: 12px;" onclick="lfb_addForm();" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>' . __('Add a new Form', 'lfb') . ' </a>
            <a href="javascript:" style="margin-right: 12px;" onclick=" jQuery(\'#lfb_winImport\').modal(\'show\');" class="btn btn-warning"><span class="glyphicon glyphicon-import"></span>' . __('Import forms', 'lfb') . ' </a>
            <a href="javascript:" onclick="lfb_exportForms();" class="btn btn-default"><span class="glyphicon glyphicon-export"></span>' . __('Export all forms', 'lfb') . ' </a>
         </p>';
        echo '<table class="table">';
        echo '<thead>';
        echo '<th>' . __('Form title', 'lfb') . '</th>';
        echo '<th>' . __('Shortcode', 'lfb') . '</th>';
        echo '<th>' . __('Actions', 'lfb') . '</th>';
        echo '</thead>';
        echo '<tbody>';
        $table_name = $wpdb->prefix . "wpefc_forms";
        $forms = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id ASC");
        foreach ($forms as $form) {
            echo '<tr>';
            echo '<td><a href="javascript:" onclick="lfb_loadForm(' . $form->id . ');">' . $form->title . '</a></td>';
            echo '<td><a href="javascript:" onclick="lfb_showShortcodeWin(' . $form->id . ');" class="btn btn-info btn-circle "><span class="glyphicon glyphicon-info-sign"></span></a><code>[estimation_form form_id="' . $form->id . '"]</code></td>';
            echo '<td>';
            echo '<a href="javascript:" onclick="lfb_loadForm(' . $form->id . ');" class="btn btn-primary btn-circle " data-toggle="tooltip" title="' . __('Edit this form', 'lfb') . '" data-placement="bottom"><span class="glyphicon glyphicon-pencil"></span></a>';
            echo '<a href="' . get_home_url() . '?lfb_action=preview&form=' . $form->id . '" target="_blank"  class="btn btn-default btn-circle " data-toggle="tooltip" title="' . __('Preview this form', 'lfb') . '" data-placement="bottom"><span class="glyphicon glyphicon-eye-open"></span></a>';
            echo '<a href="javascript:" onclick="lfb_loadLogs(' . $form->id . ');" class="btn btn-default btn-circle " data-toggle="tooltip" title="' . __('View orders', 'lfb') . '" data-placement="bottom"><span class="glyphicon glyphicon-list-alt"></span></a>';
            echo '<a href="javascript:"  onclick="lfb_openCharts(' . $form->id . ');"  class="btn btn-default btn-circle " data-toggle="tooltip" title="' . __('View statistics', 'lfb') . '" data-placement="bottom"><span class="glyphicon glyphicon-stats"></span></a>';

            echo '<a href="javascript:" onclick="lfb_duplicateForm(' . $form->id . ');" class="btn btn-default btn-circle " data-toggle="tooltip" title="' . __('Duplicate this form', 'lfb') . '" data-placement="bottom"><span class="glyphicon glyphicon-duplicate"></span></a>';
            echo '<a href="javascript:" onclick="lfb_removeForm(' . $form->id . ');" class="btn btn-danger btn-circle " data-toggle="tooltip" title="' . __('Delete this form', 'lfb') . '" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';

        echo '</div>'; // eof tab-content
        echo '</div>'; // eof wpefc_formsTabGeneral
        echo '</div>'; // eof tabpanel


        echo '</div>'; // eof col-md-12
        echo '</div>'; // eof container
        echo '</div>'; // eof lfb_panelFormsList


        echo '<div id="lfb_panelPreview">';
        echo '<div class="clearfix"></div>';
        echo '<div style="max-width: 90%;margin: 0 auto;margin-top: 18px;" id="lfb_formTopbtns">
                <p class="text-right" style="float:right; margin-bottom:0px;">
                 <a href="javascript:" style="margin-right: 12px;" onclick="lfb_addStep( \'' . __('My Step', 'lfb') . '\');" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>' . __("Add a step", 'lfb') . '</a>
                <a href="javascript:" id="lfb_btnPreview" target="_blank" style="margin-right: 12px;"  class="btn btn-default"><span class="glyphicon glyphicon-eye-open"></span>' . __("View the form", 'lfb') . '</a>
                <a href="javascript:" onclick="lfb_showShortcodeWin();" style="margin-right: 12px;"  class="btn btn-default"><span class="glyphicon glyphicon-info-sign"></span>' . __('Shortcode', 'lfb') . '</a>
                <a href="javascript:" id="lfb_logsBtn" data-formid="0" onclick="lfb_loadLogs(jQuery(this).attr(\'data-formid\'));"  style="margin-right: 12px;"  class="btn btn-default"><span class="glyphicon glyphicon-list-alt"></span>' . __('View orders', 'lfb') . '</a>
                <a href="javascript:" id="lfb_chartsBtn" data-formid="0" onclick="lfb_showLoader();lfb_loadCharts(jQuery(this).attr(\'data-formid\'));"  style="margin-right: 12px;"  class="btn btn-default"><span class="glyphicon glyphicon-stats"></span>' . __('View statistics', 'lfb') . '</a>
                <a href="javascript:" data-toggle="modal" data-target="#modal_removeAllSteps" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>' . __("Remove all steps", 'lfb') . '</a>
                </p>
                <h3 id="lfb_stepsManagerTitle">' . __('Steps manager', 'lfb') . '</h3>

                <div class="clearfix"></div>
            </div>
        ';

        echo '
        <!-- Modal -->
        <div class="modal fade" id="modal_removeAllSteps" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                ' . __('Are you sure you want to delete all steps ?', 'lfb') . '
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"  onclick="lfb_removeAllSteps();" >' . __('Yes', 'lfb') . '</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" >' . __('No', 'lfb') . '</button>
              </div>
            </div>
          </div>
        </div>';

        echo '<div id="lfb_stepsOverflow">';
        echo '<div id="lfb_stepsContainer">';
        echo '<canvas id="lfb_stepsCanvas"></canvas>';
        echo '</div>';
        echo '</div>';


        echo '<div id="lfb_formFields" style="max-width: 90%;margin: 0 auto;margin-top: 18px;" >
                <h3>' . __('Form settings', 'lfb') . '</h3>
            <div role="tabpanel" >

              <!--Nav tabs-->
              <ul class="nav nav-tabs" role="tablist" >
                <li role="presentation" class="active" ><a href="#lfb_tabGeneral" aria-controls="general" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-cog" ></span > ' . __('General', 'lfb') . ' </a ></li >
                <li role="presentation" ><a href="#lfb_tabTexts" aria-controls="texts" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-edit" ></span > ' . __('Texts', 'lfb') . ' </a ></li >
                <li role="presentation" ><a href="#lfb_tabEmail" aria-controls="email" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-envelope" ></span > ' . __('Email', 'lfb') . ' </a ></li >
                <li role="presentation" ><a href="#lfb_tabLastStep" aria-controls="last step" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-list" ></span > ' . __('Last Step', 'lfb') . ' </a ></li >
                <li role="presentation" ><a href="#lfb_tabDesign" aria-controls="design" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-tint" ></span > ' . __('Design', 'lfb') . ' </a ></li >
                <li role="presentation" ><a href="#lfb_tabCoupons" aria-controls="design" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-gift" ></span > ' . __('Discount coupons', 'lfb') . ' </a ></li >
               </ul >

              <!--Tab panes-->
              <div class="tab-content" >
                <div role="tabpanel" class="tab-pane active" id="lfb_tabGeneral" >
                    <div class="row-fluid" >
                        <div class="col-md-6" >
                         <div class="form-group" >
                                <label > ' . __('Title', 'lfb') . ' </label >
                                <input type="text" name="title" class="form-control" />
                                <small> ' . __('The form title', 'lfb') . ' </small>
                            </div>
                        <div class="form-group" >
                                <label > ' . __('Order reference prefix', 'lfb') . ' </label >
                                <input type="text" name="ref_root" class="form-control" />
                                <small> ' . __('Enter a prefix for the order reference', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Google Analytics ID', 'lfb') . ' </label >
                                <input type="text" name="analyticsID" class="form-control" />
                                <small> ' . __('By filling this field, you can track user actions in your form', 'lfb') . ' </small>
                                <a href="https://support.google.com/analytics/answer/1032385?hl=en" target="_blank" style="margin-left: 8px;" class="btn btn-info btn-circle"><span class="glyphicon glyphicon-info-sign"></span></a>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Google Maps browser key', 'lfb') . ' </label >
                                <input type="text" name="gmap_key" class="form-control" />
                                <small> ' . __('By filling this field, you can use distance calculations', 'lfb') . ' </small>
                                <a href="https://developers.google.com/maps/documentation/javascript/get-api-key?hl=en" target="_blank" style="margin-left: 8px;" class="btn btn-info btn-circle"><span class="glyphicon glyphicon-info-sign"></span></a>
                            </div>
                           
                            <div class="form-group" >
                                <label > ' . __('Progress bar shows', 'lfb') . ' </label >
                                <select  name="showSteps" class="form-control" />
                                    <option value="0" > ' . __('Price', 'lfb') . ' </option >
                                    <option value="1" > ' . __('Step', 'lfb') . ' </option >
                                    <option value="2" > ' . __('No progress bar', 'lfb') . ' </option >
                                </select >
                                <small> ' . __('The progress bar can show the price or step number', 'lfb') . ' </small>
                            </div>                            
                            
                            <div class="form-group" >
                                <label > ' . __('Show the total price at bottom ?', 'lfb') . ' </label >
                                <input type="checkbox"  name="showTotalBottom" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '"class=""   />
                                <small> ' . __('Display or hide the total price at bottom of each step', 'lfb') . ' </small>
                            </div>
                            
                            <div class="form-group" >
                                <label > ' . __('Currency', 'lfb') . ' </label >
                                <input type="text"  name="currency" class="form-control" />
                                <small> ' . __('$, € , £ ...', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Currency Position', 'lfb') . ' </label >
                                <select  name="currencyPosition" class="form-control" />
                                    <option value="right" > ' . __('Right', 'lfb') . ' </option >
                                    <option value="left" > ' . __('Left', 'lfb') . ' </option >
                                </select >
                                <small> ' . __('Sets the currency position in the price', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Quantity selection style', 'lfb') . ' </label >
                                <select  name="qtType" class="form-control" />
                                    <option value="0" > ' . __('Buttons', 'lfb') . ' </option >
                                    <option value="1" > ' . __('Field', 'lfb') . ' </option >
                                    <option value="2" > ' . __('Slider', 'lfb') . ' </option >
                                </select >
                                <small> ' . __('If "field", tooltip will be positionned on top', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Custom JS', 'lfb') . ' </label >
                                <textarea name="customJS" class="form-control" ></textarea>
                                <small> ' . __('You can paste your own js code here', 'lfb') . ' </small>
                            </div>
                        </div>
                        <div class="col-md-6" >
                             <div class="form-group" >
                                <label > ' . __('Initial price', 'lfb') . ' </label >
                                <input type="number" step="any" name="initial_price" class="form-control" />
                                <small> ' . __('Starting price', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Maximum price', 'lfb') . ' </label >
                                <input type="number" step="any"  name="max_price" class="form-control" />
                                <small> ' . __('Leave blank for automatic calculation', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Hide initial price in the progress bar ? ', 'lfb') . ' </label >
                                <input type="checkbox"  name="show_initialPrice" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '"class=""   />
                                <small> ' . __('Display or hide the initial price from progress bar', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Hide tooltips on touch devices ?', 'lfb') . ' </label >
                                <input type="checkbox"  name="disableTipMobile" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" class=""   />
                                <small> ' . __('Hide tooltips on touch devices ?', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Automatic next step', 'lfb') . ' </label >
                                <input type="checkbox"  name="groupAutoClick" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" class=""   />
                                <small> ' . __('Automatically go to the next step when selecting if only one product is selectable and step is required', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Ajax navigation support', 'lfb') . ' </label >
                                <input type="checkbox"  name="loadAllPages" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" class=""   />
                                <small> ' . __('Activate this option if your theme uses ajax navigation to display pages', 'lfb') . ' </small>
                            </div>
                            
                            
                            <div class="form-group" >
                                <label > ' . __('Datepicker language', 'lfb') . ' </label >
                                <select  name="datepickerLang" class="form-control" />
                                    <option value="">en</option >
                                    <option value="af">af</option >
                                    <option value="ar-DZ">ar-DZ</option >
                                    <option value="ar">ar</option >
                                    <option value="az">az</option >
                                    <option value="be">be</option >
                                    <option value="bg">bg</option >
                                    <option value="bs">bs</option >
                                    <option value="ca">ca</option >
                                    <option value="cs">cs</option >
                                    <option value="cy-GB">cy-GB</option >
                                    <option value="da">da</option >
                                    <option value="de">de</option >
                                    <option value="el">el</option >
                                    <option value="en-AU">en-AU</option >
                                    <option value="en-NZ">en-NZ</option >
                                    <option value="eo">eo</option >
                                    <option value="es">es</option >
                                    <option value="et">et</option >
                                    <option value="eu">eu</option >
                                    <option value="fa">fa</option >
                                    <option value="fi">fi</option >
                                    <option value="fo">fo</option >
                                    <option value="fr-CA">fr-CA</option >
                                    <option value="fr-CH">fr-CH</option >
                                    <option value="fr">fr</option >
                                    <option value="gl">gl</option >
                                    <option value="he">he</option >
                                    <option value="hi">hi</option >
                                    <option value="hr">hr</option >
                                    <option value="hu">hu</option >
                                    <option value="hy">hy</option >
                                    <option value="id">id</option >
                                    <option value="is">is</option >
                                    <option value="hr">hr</option >
                                    <option value="hu">hu</option >
                                    <option value="hy">hy</option >
                                    <option value="id">id</option >
                                    <option value="is">is</option >
                                    <option value="it-CH">it-CH</option >
                                    <option value="it">it</option >
                                    <option value="ja">ja</option >
                                    <option value="ka">ka</option >
                                    <option value="kk">kk</option >
                                    <option value="km">km</option >
                                    <option value="ko">ko</option >
                                    <option value="ky">ky</option >
                                    <option value="lb">lb</option >
                                    <option value="lt">lt</option >
                                    <option value="lv">lv</option >
                                    <option value="mk">mk</option >
                                    <option value="ml">ml</option >
                                    <option value="ms">ms</option >
                                    <option value="nb">nb</option >
                                    <option value="nl-BE">nl-BE</option >
                                    <option value="nl">nl</option >
                                    <option value="nn">nn</option >
                                    <option value="no">no</option >
                                    <option value="pl">pl</option >
                                    <option value="pt-BR">pt-BR</option >
                                    <option value="pt">pt</option >
                                    <option value="rm">rm</option >
                                    <option value="ro">ro</option >
                                    <option value="ru">ru</option >
                                    <option value="sk">sk</option >
                                    <option value="sl">sl</option >
                                    <option value="sg">sg</option >
                                    <option value="sr-SR">sr-SR</option >
                                    <option value="sr">sr</option >
                                    <option value="sv">sv</option >
                                    <option value="ta">ta</option >
                                    <option value="th">th</option >
                                    <option value="tj">tj</option >
                                    <option value="tr">tr</option >
                                    <option value="uk">bg</option >
                                    <option value="vi">vi</option >
                                    <option value="zh-CN">zh-CN</option >
                                    <option value="zh-HK">zh-HK</option >
                                    <option value="zh-TW">zh-TW</option >
                                </select >
                                <small> ' . __('Select your language code', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Decimals separator', 'lfb') . ' </label >
                                <input type="text"  name="decimalsSeparator" class="form-control" />
                                <small> ' . __('Enter a separator or leave empty', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Thousands separator', 'lfb') . ' </label >
                                <input type="text"  name="thousandsSeparator" class="form-control" />
                                <small> ' . __('Enter a separator or leave empty', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Millions separator', 'lfb') . ' </label >
                                <input type="text"  name="millionSeparator" class="form-control" />
                                <small> ' . __('Enter a separator or leave empty', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Animations speed', 'lfb') . ' </label >
                                <input type="number" step="0.1"  name="animationsSpeed" class="form-control" />
                                <small> ' . __('Sets the animations speed, in seconds(default : 0.5)', 'lfb') . ' </small>
                            </div>
                            

                        </div>
                    </div>
                    <div class="clearfix" ></div>
                </div>

                <div role="tabpanel" class="tab-pane" id="lfb_tabTexts" >
                    <div class="row-fluid" >
                        <div class="col-md-6" >
                            <h4 > ' . __('General', 'lfb') . ' </h4 >                           
                            <div class="form-group" >
                                <label > ' . __('Selection required', 'lfb') . ' </label >
                                <input type="text" name="errorMessage" class="form-control" />
                                <small> ' . __('Something like "You need to select an item to continue"', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Button "next step"', 'lfb') . ' </label >
                                <input type="text" name="btn_step" class="form-control" />
                                <small> ' . __('Something like "NEXT STEP"', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Link "previous step"', 'lfb') . ' </label >
                                <input type="text" name="previous_step" class="form-control" />
                                <small> ' . __('Something like "return to previous step"', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Label "Description"', 'lfb') . ' </label >
                                <input type="text" name="summary_description" class="form-control" />
                                <small> ' . __('Something like "Description"', 'lfb') . ' </small>
                            </div>                             
                            <div class="form-group" >
                                <label > ' . __('Label "Quantity"', 'lfb') . ' </label >
                                <input type="text" name="summary_quantity" class="form-control" />
                                <small> ' . __('Something like "Quantity"', 'lfb') . ' </small>
                            </div>                             
                            <div class="form-group" >
                                <label > ' . __('Label "Information"', 'lfb') . ' </label >
                                <input type="text" name="summary_value" class="form-control" />
                                <small> ' . __('Something like "Information"', 'lfb') . ' </small>
                            </div>                                   
                            <div class="form-group" >
                                <label > ' . __('Label "Price"', 'lfb') . ' </label >
                                <input type="text" name="summary_price" class="form-control" />
                                <small> ' . __('Something like "Price"', 'lfb') . ' </small>
                            </div>                  
                            <div class="form-group" >
                                <label > ' . __('Label "Total"', 'lfb') . ' </label >
                                <input type="text" name="summary_total" class="form-control" />
                                <small> ' . __('Something like "Total :"', 'lfb') . ' </small>
                            </div>        
                            <div class="form-group" >
                                <label > ' . __('Label "Discount"', 'lfb') . ' </label >
                                <input type="text" name="summary_discount" class="form-control" />
                                <small> ' . __('Something like "Discount :"', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Label of files fields', 'lfb') . ' </label >
                                <input type="text" name="filesUpload_text" class="form-control" />
                                <small> ' . __('Something like "Drop files here to upload"', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Size error for files fields', 'lfb') . ' </label >
                                <input type="text" name="filesUploadSize_text" class="form-control" />
                                <small> ' . __('Something like "File is too big (max size: {{maxFilesize}}MB)"', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('File type error for files fields', 'lfb') . ' </label >
                                <input type="text" name="filesUploadType_text" class="form-control" />
                                <small> ' . __('Something like "Invalid file type"', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Limit error for files fields', 'lfb') . ' </label >
                                <input type="text" name="filesUploadLimit_text" class="form-control" />
                                <small> ' . __('Something like "You can not upload any more files"', 'lfb') . ' </small>
                            </div>   
                            <div class="form-group" >
                                <label > ' . __('Distance calculation error', 'lfb') . ' </label >
                                <input type="text" name="txtDistanceError" class="form-control" />
                                <small> ' . __('Something like "Calculating the distance could not be performed, please verify the input addresses"', 'lfb') . ' </small>
                            </div>   
                            
                            
                        </div>
                        <div class="col-md-6" >
                         <h4 > ' . __('Introduction', 'lfb') . ' </h4 >
                            <div class="form-group" >
                                <label> ' . __('Enable Introduction ? ') . ' </label >
                                <input type="checkbox"  name="intro_enabled" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                                <small> ' . __('Is Introduction enabled ? ', 'lfb') . ' </small>
                            </div>
                             <div class="form-group" >
                                <label > ' . __('Introduction title', 'lfb') . ' </label >
                                <input type="text" name="intro_title" class="form-control" />
                                <small> ' . __('Something like "HOW MUCH TO MAKE MY WEBSITE ?"', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Introduction text', 'lfb') . ' </label >
                                <input type="text" name="intro_text" class="form-control" />
                                <small> ' . __('Something like "Estimate the cost of a website easily using this awesome tool."', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Introduction button', 'lfb') . ' </label >
                                <input type="text" name="intro_btn" class="form-control" />
                                <small> ' . __('Something like "GET STARTED"', 'lfb') . ' </small>
                            </div>
                            <h4> ' . __('Last Step', 'lfb') . ' </h4>
                             <div class="form-group" >
                                <label > ' . __('Last step title', 'lfb') . ' </label >
                                <input type="text" name="last_title" class="form-control" />
                                <small> ' . __('Something like "Final cost", "Result" ...', 'lfb') . ' </small>
                            </div>
                             <div class="form-group" >
                                <label > ' . __('Last step text', 'lfb') . ' </label >
                                <input type="text" name="last_text" class="form-control" />
                                <small> ' . __('Something like "The final estimated price is :"', 'lfb') . ' </small>
                            </div>
                             <div class="form-group" >
                                <label > ' . __('Last step button', 'lfb') . ' </label >
                                <input type="text" name="last_btn" class="form-control" />
                                <small> ' . __('Something like "ORDER MY WEBSITE"', 'lfb') . ' </small>
                            </div>
                             <div class="form-group" >
                                <label > ' . __('Succeed text', 'lfb') . ' </label >
                                <input type="text" name="succeed_text" class="form-control" />
                                <small> ' . __('Something like "Thanks, we will contact you soon"', 'lfb') . ' </small>
                            </div> 
                            <h4> ' . __('Stripe payment', 'lfb') . ' </h4>                                   
                             <div class="form-group" >
                                <label > ' . __('Label "Credit Card number"', 'lfb') . ' </label >
                                <input type="text" name="stripe_label_creditCard" class="form-control" />
                                <small> ' . __('Something like "Credit Card number"', 'lfb') . ' </small>
                            </div>
                             <div class="form-group" >
                                <label > ' . __('Label "CVC"', 'lfb') . ' </label >
                                <input type="text" name="stripe_label_cvc" class="form-control" />
                                <small> ' . __('Something like "CVC"', 'lfb') . ' </small>
                            </div>
                             <div class="form-group" >
                                <label > ' . __('Label "Expiration date"', 'lfb') . ' </label >
                                <input type="text" name="stripe_label_expiration" class="form-control" />
                                <small> ' . __('Something like "Expiration date"', 'lfb') . ' </small>
                            </div> 
                        </div>
                        
                    </div>
                    <div class="clearfix" ></div>
                </div>

                <div role="tabpanel" class="tab-pane" id="lfb_tabEmail" >
                    <div class="row-fluid" >
                        <div class="col-md-6" >
                            <h4 > ' . __('Admin email', 'lfb') . ' </h4 >
                            <div class="form-group" >
                                <label > ' . __('Admin email', 'lfb') . ' </label >
                                <input type="text" name="email" class="form-control" />
                                <small> ' . __('Email that will receive requests', 'lfb') . ' </small>
                            </div>
                             <div class="form-group" >
                                <label > ' . __('Admin email subject', 'lfb') . ' </label >
                                <input type="text" name="email_subject" class="form-control" />
                                <small> ' . __('Something like "New order from your website"', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Send the order as pdf', 'lfb') . ' </label >
                                <input type="checkbox"  name="sendPdfAdmin" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" class=""   />
                                <small> ' . __('A pdf file will be generated and sent as attachment', 'lfb') . ' </small>
                            </div>

                            <div class="form-group" >
                               <!-- <label> ' . __('Admin email content', 'lfb') . ' </label> -->
                                    <p><strong> ' . __('Variables', 'lfb') . ' :</strong></p >
                                <div class="palette palette-turquoise" >
                                    <p>
                                      <strong>[project_content]</strong> : ' . __('Selected items list', 'lfb') . ' <br/>
                                        <strong>[information_content]</strong> : ' . __('Last step form values', 'lfb') . ' <br/>
                                        <strong>[total_price]</strong> : ' . __('Total price', 'lfb') . ' <br/>
                                        <strong>[ref]</strong> : ' . __('Order reference', 'lfb') . ' <br/>
                                    </p>
                                    <a href="javascript:" id="lfb_btnAddEmailValue" onclick="lfb_addEmailValue(false);" class="btn btn-default" style="margin-bottom: 8px;"><span class="glyphicon glyphicon-plus"></span>' . __('Get the value of a field', 'lfb') . '</a>

                                </div>
                                <div id="email_adminContent_editor" >
                                <div id="email_adminContent"></div>';

//        ' . wp_editor('<p>Ref: <strong>[ref]</strong></p><h2 style="color: #008080;">Information</h2><hr/><span style="font-weight: 600; color: #444444;">[information_content]</span><span style="color: #444444;"> </span><hr/><h2 style="color: #008080;">Project</h2><hr/>[project_content]<hr/><h4>Total: <strong><span style="color: #444444;">[total_price]</span></strong></h4>', 'email_adminContent', array('tinymce' => array('height' => 300))) . '
        echo '</div>
                            </div>
                        </div>
                             <div class="col-md-6" >
                            <h4> ' . __('Customer email', 'lfb') . ' </h4>
                             <div class="form-group" >
                                <label > ' . __('Send email to the customer ? ', 'lfb') . ' </label >
                                <input type="checkbox"  name="email_toUser" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                                <small> ' . __('If true, the user will receive a confirmation email', 'lfb') . ' </small>
                            </div>
                            <div id="lfb_formEmailUser" >
                             <div class="form-group" >
                                <label > ' . __('Customer email subject', 'lfb') . ' </label >
                                <input type="text" name="email_userSubject" class="form-control" />
                                <small> ' . __('Something like "Order confirmation"', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Send the order as pdf', 'lfb') . ' </label >
                                <input type="checkbox"  name="sendPdfCustomer" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" class=""   />
                                <small> ' . __('A pdf file will be generated and sent as attachment', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                    <p><strong > ' . __('Variables', 'lfb') . ' :</strong ></p >
                                <div class="palette palette-turquoise" >
                                    <p>
                                        <strong>[project_content]</strong> : ' . __('Selected items list', 'lfb') . ' <br/>
                                        <strong>[information_content]</strong> : ' . __('Last step form values', 'lfb') . ' <br/>
                                        <strong>[total_price]</strong> : ' . __('Total price', 'lfb') . ' <br/>
                                        <strong>[ref]</strong> : ' . __('Order reference', 'lfb') . ' <br/>
                                    </p>
                                    <a href="javascript:" id="lfb_btnAddEmailValueCustomer" onclick="lfb_addEmailValue(true);" class="btn btn-default" style="margin-bottom: 8px;"><span class="glyphicon glyphicon-plus"></span>' . __('Get the value of a field', 'lfb') . '</a>
                                </div>';

       
        
        echo'  <div id="email_userContent_editor" >
                                <div id="email_userContent"></div>';
        echo '</div>
                            </div>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                    <div class="row-fluid">
                        <div class="col-md-6">
                            <h4>' . __('Mailing list', 'lfb') . '</h4>
                        </div>
                        <div class="col-md-6"></div>
                    <div class="clearfix"></div>
                        <div class="col-md-6">';
        echo '<div class="form-group">'
        . '<label>' . __('Send contact to Mailchimp ?', 'lfb') . '</label>'
        . '<input type="checkbox" data-switch="switch"  name="useMailchimp"/>'
        . '</div>';
        echo '<div class="form-group">'
        . '<label>' . __('Mailchimp API key', 'lfb') . ' :</label>'
        . '<input type="text" class="form-control" name="mailchimpKey"/>'
        . '<a href="http://kb.mailchimp.com/accounts/management/about-api-keys" target="_blank" style="margin-left: 8px;" class="btn btn-info btn-circle"><span class="glyphicon glyphicon-info-sign"></span></a>'
        . '</div>';
        echo '<div class="form-group">'
        . '<label>' . __('Mailchimp list', 'lfb') . ' :</label>'
        . '<select class="form-control" name="mailchimpList"></select>'
        . '</div>';
        echo '<div class="form-group">'
        . '<label>' . __('Confirmation by email required ?', 'lfb') . '</label>'
        . '<input type="checkbox" data-switch="switch"  name="mailchimpOptin"/>'
        . '</div>';
        echo '<div class="form-group">'
        . '<label>' . __('Send contact to MailPoet ?', 'lfb') . '</label>'
        . '<input type="checkbox" data-switch="switch"  name="useMailpoet"/>'
        . '</div>';
        echo '<div class="form-group">'
        . '<label>' . __('Mailpoet list', 'lfb') . ' :</label>'
        . '<select class="form-control" name="mailPoetList"></select>'
        . '</div>';
        echo '</div>';
        echo '<div class="col-md-6">';


        echo '<div class="form-group">'
        . '<label>' . __('Send contact to GetResponse ?', 'lfb') . '</label>'
        . '<input type="checkbox" data-switch="switch"  name="useGetResponse"/>'
        . '</div>';
        echo '<div class="form-group">'
        . '<label>' . __('GetResponse API key', 'lfb') . ' :</label>'
        . '<input type="text" class="form-control" name="getResponseKey"/>'
        . '<a href="https://support.getresponse.com/faq/where-i-find-api-key" target="_blank" style="margin-left: 8px;" class="btn btn-info btn-circle"><span class="glyphicon glyphicon-info-sign"></span></a>'
        . '</div>';
        echo '<div class="form-group">'
        . '<label>' . __('GetResponse list', 'lfb') . ' :</label>'
        . '<select class="form-control" name="getResponseList"></select>'
        . '</div>';
        echo '<div class="form-group">'
        . '<label>' . __('Send contact as soon the email field is filled ?', 'lfb') . '</label>'
        . '<input type="checkbox" data-switch="switch"  name="sendContactASAP"/>'
        . '<small> ' . __('If checked, the contact will be send at end of the step containing the email field', 'lfb') . ' </small>'
        . '</div>';
        echo '</div>
                    </div>
                    <div class="clearfix" ></div>
                </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="lfb_tabLastStep" >
                    <div class="row-fluid" >
                        <div class="col-md-6" >
                            <div class="form-group" >
                                <label > ' . __('Call an url on close', 'lfb') . ' </label >
                                <input type="text" name="close_url" class="form-control" />
                                <small> ' . __('Complete this field if you want to call a specific url on close . Otherwise leave it empty.', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Conditions on redirection ?', 'lfb') . ' </label >
                                <input  type="checkbox"  name="useRedirectionConditions" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '"/>
                                <small> ' . __('Activate it to create different possible redirections', 'lfb') . ' </small>
                            </div>
                            
                            <div id="lfb_redirConditionsContainer">
                            <p style="text-align: right;"><a href="javascript:" id="lfb_addRedirBtn" onclick="lfb_editRedirection(0);" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> '.__('Add a redirection', 'lfb').'</a></p>
                            <table id="lfb_redirsTable" class="table">
                            <thead>
                                <tr>
                                    <th>' . __('URL', 'lfb') . '</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                          </table>
                          </div>
                          
                            <div class="form-group" >
                                <label > ' . __('Delay before the redirection', 'lfb') . ' </label >
                                <input type="numberfield" name="redirectionDelay" class="form-control" />
                                <small> ' . __('Enter the wanted delay in seconds', 'lfb') . ' </small>
                            </div>
                            
                                <div class="form-group" >
                                    <label > ' . __('Hide the final price ?', 'lfb') . ' </label >
                                    <input  type="checkbox"  name="hideFinalPrice" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '"/>
                                    <small> ' . __('Set on true to hide the price on the last step.', 'lfb') . ' </small>
                                </div>
                                                       
                            
                          <h4 > ' . __('Summary', 'lfb') . ' </h4 >
                            <div class="form-group" >
                                <label > ' . __('Show a summary ?', 'lfb') . ' </label >
                                <input  type="checkbox"  name="useSummary" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '"/>
                                <small> ' . __('Do you want to show a summary on last step ?', 'lfb') . ' </small>
                            </div>                                
                            <div class="form-group" >
                                <label > ' . __('Summary title', 'lfb') . ' </label >
                                <input type="text" name="summary_title" class="form-control" />
                                <small> ' . __('Something like "Summary"', 'lfb') . ' </small>
                            </div>      
                            <div class="form-group" >
                                <label > ' . __('Hide quantity column', 'lfb') . ' </label >
                                <input  type="checkbox"  name="summary_hideQt" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '"/>
                                <small> ' . __('Do you want to hide the column of quantities ?', 'lfb') . ' </small>
                            </div>   
                            <div class="form-group" >
                                <label > ' . __('Hide zero prices', 'lfb') . ' </label >
                                <input  type="checkbox"  name="summary_hideZero" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '"/>
                                <small> ' . __('Do you want to hide zero prices ?', 'lfb') . ' </small>
                            </div>    
                            <div class="form-group" >
                                <label > ' . __('Hide all prices', 'lfb') . ' </label >
                                <input  type="checkbox"  name="summary_hidePrices" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '"/>
                                <small> ' . __('Do you want to hide all prices ?', 'lfb') . ' </small>
                            </div> 
                            
                            <h4 > ' . __('Legal notice', 'lfb') . ' </h4 >
                          <div>
                               <div class="form-group" >
                                   <label > ' . __('Enable legal notice ?', 'lfb') . ' </label >
                                   <input type="checkbox"  name="legalNoticeEnable" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                                   <small> ' . __('If true, the user must accept the notice before submitting the form', 'lfb') . ' </small>
                               </div>
                               <div class="form-group" >
                                  <label > ' . __('Sentence of acceptance', 'lfb') . ' </label >
                                  <input type="text" name="legalNoticeTitle" class="form-control" />
                                  <small> ' . __('Something like "I certify I completely read and I accept the legal notice by validating this form"', 'lfb') . ' </small>
                              </div>
                              <div class="form-group" >
                                 <label > ' . __('Content of the legal notice', 'lfb') . ' </label >
                                  <div id="lfb_legalNoticeContent"></div>
                                 <small> ' . __('Write your legal notice here', 'lfb') . ' </small>
                             </div>
                        </div><div class="clearfix" ></div>
                        </div>
                            
                            
                        </div>
                        <div class="col-md-6" >
                        <div class="lfb_paymentOption">
                            <h4> ' . __('Payment', 'lfb') . ' </h4 >
                            <div class="form-group " >
                                <label > ' . __('Is subscription ?', 'lfb') . ' </label >
                                <input type="checkbox"  name="isSubscription" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                                <small> ' . __('Does the price corresponds to a subscription ?', 'lfb') . ' </small>                            
                            </div>                 
                            <div class="form-group" >
                                <label > ' . __('Text after price', 'lfb') . ' </label >
                                <input type="text" name="subscription_text" class="form-control" maxlength="11" />
                                <small> ' . __('Something like "/month"', 'lfb') . ' </small>
                            </div>
                            
                            <div class="form-group" >
                                <label > ' . __('Use paypal payment', 'lfb') . ' </label >
                                <input type="checkbox"  name="use_paypal" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                                <small> ' . __('If true, the user will be redirected to the payment page', 'lfb') . ' </small>                            
                            </div>
                            
                            <div id="lfb_formPaypal" >
                             <div class="form-group" >
                                <label > ' . __('Paypal email', 'lfb') . ' </label >
                                <input type="text" name="paypal_email" class="form-control" />
                                <small> ' . __('Enter your paypal email', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Frequency of subscription') . ' </label >
                                <select name="paypal_subsFrequency" class="form-control" style="margin-left: 8px; width: 80px;" />
                                    <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option>
                                </select>
                                <select name="paypal_subsFrequencyType" class="form-control" style="display: inline-block; margin-left: 8px; width: 120px;" />
                                    <option value="D">' . __('day(s)', 'lfb') . '</option>
                                    <option value="W">' . __('week(s)', 'lfb') . '</option>
                                    <option value="M">' . __('month(s)', 'lfb') . '</option>
                                    <option value="Y">' . __('year(s)', 'lfb') . '</option>
                                </select>
                                <small> ' . __('Payment will be renewed every ... ?"', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('How many payments ?') . ' </label >
                                <select name="paypal_subsMaxPayments" class="form-control" />
                                    <option value="0">' . __('Illimited', 'lfb') . '</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option>
                                </select>
                                <small> ' . __('The subscription ends after how many payments ?', 'lfb') . ' </small>
                            </div>                    
                            <div class="form-group" >
                                <label > ' . __('Percentage of the total price to pay', 'lfb') . ' </label >
                                <input type="number" step="0.10" name="percentToPay" class="form-control" />
                                <small> ' . __('Only this percentage will be paid by paypal', 'lfb') . ' </small>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Paypal currency', 'lfb') . ' </label >
                                <select name="paypal_currency" class="form-control" />
                                    <option value="AUD" > AUD</option >
                                    <option value="CAD" > CAD</option >
                                    <option value="CZK" > CZK</option >
                                    <option value="DKK" > DKK</option >
                                    <option value="EUR" > EUR</option >
                                    <option value="HKD" > HKD</option >
                                    <option value="HUF" > HUF</option >
                                    <option value="JPY" > JPY</option >
                                    <option value="NOK" > NOK</option >
                                    <option value="MXN" > MXN </option >
                                    <option value="NZD" > NZD</option >
                                    <option value="PLN" > PLN</option >
                                    <option value="GBP" > GBP</option >
                                    <option value="SGD" > SGD</option >
                                    <option value="SEK" > SEK</option >
                                    <option value="CHF" > CHF</option >
                                    <option value="USD" > USD</option >
                                    <option value="RUB" > RUB</option >
                                    <option value="PHP" > PHP</option >
                                    <option value="ILS" > ILS</option >
                                    <option value="BRL" > BRL</option >
                                    <option value="MYR" > MYR</option >                                    
                                </select >
                                <small> ' . __('Enter your paypal currency', 'lfb') . ' </small>
                            </div>
                            
                            <div class="form-group" >
                                <label > ' . __('Use paypal IPN', 'lfb') . ' </label >
                                <input type="checkbox"  name="paypal_useIpn" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                                <small> ' . __('Email will be send only if the payment has been done and verified', 'lfb') . ' </small> 
                                <p id="lfb_infoIpn" class="alert alert-info" style="margin-top: 18px; display:none;">
                                    ' . sprintf(__('IPN requires a PayPal Business or Premier account and IPN must be configured on that account.<br/>See the <a %1$s>PayPal IPN Integration Guide</a> to learn how to set up IPN.<br/>The IPN listener URL you will need is : %2$s', 'lfb'), 'href="https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNSetup/" target="_blank"', '<br/><strong>' . get_site_url() . '/?EPFormsBuilder=paypal</strong>') . '
                                </p>
                            </div>
                            <div class="form-group" >
                                <label > ' . __('Use paypal Sandbox', 'lfb') . ' </label >
                                <input type="checkbox"  name="paypal_useSandbox" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                                <small> ' . __('Enable Sandbox only to test with fake payments', 'lfb') . ' </small> 
                            </div>
                            </div> ';

        echo '<div class="form-group" >
                                <label > ' . __('Use stripe payment', 'lfb') . ' </label >
                                <input type="checkbox"  name="use_stripe" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                                <small> ' . __('If true, the user will be redirected to the payment page', 'lfb') . ' </small>                            
                            </div>
                            <div class="form-group lfb_stripeField" >
                                <label > ' . __('Stripe secret key', 'lfb') . ' </label >
                                <input type="text" name="stripe_secretKey" class="form-control" />
                                <small> ' . __('Enter your stripe secret key', 'lfb') . ' </small>
                            </div>
                            <div class="form-group lfb_stripeField" >
                                <label > ' . __('Stripe publishable key', 'lfb') . ' </label >
                                <input type="text" name="stripe_publishKey" class="form-control" />
                                <small> ' . __('Enter your stripe publishable key', 'lfb') . ' </small>
                            </div>
                             <div class="form-group" >
                                <label > ' . __('Frequency of subscription') . ' </label >
                                <select name="stripe_subsFrequencyType" class="form-control" />
                                    <option value="day">' . __('day(s)', 'lfb') . '</option>
                                    <option value="week">' . __('week(s)', 'lfb') . '</option>
                                    <option value="month">' . __('month(s)', 'lfb') . '</option>
                                    <option value="year">' . __('year(s)', 'lfb') . '</option>
                                </select>
                                <small> ' . __('Payment will be renewed every ... ?"', 'lfb') . ' </small>
                            </div>
                            
                             <div class="form-group lfb_stripeField" >
                                <label > ' . __('Stripe currency', 'lfb') . ' </label >
                                <select name="stripe_currency" class="form-control" />
                                    <option value="AED">United Arab Emirates Dirham
                                    </option>
                                    <option value="ALL">Albanian Lek
                                    </option>
                                    <option value="ANG">Netherlands Antillean Gulden
                                    </option>
                                    <option value="ARS">Argentine Peso
                                    </option>
                                    <option value="AUD">Australian Dollar
                                    </option>
                                    <option value="AWG">Aruban Florin
                                    </option>
                                    <option value="BBD">Barbadian Dollar
                                    </option>
                                    <option value="BDT">Bangladeshi Taka
                                    </option>
                                    <option value="BIF">Burundian Franc
                                    </option>
                                    <option value="BMD">Bermudian Dollar
                                    </option>
                                    <option value="BND">Brunei Dollar
                                    </option>
                                    <option value="BOB">Bolivian Boliviano
                                    </option>
                                    <option value="BRL">Brazilian Real
                                    </option>
                                    <option value="BSD">Bahamian Dollar
                                    </option>
                                    <option value="BWP">Botswana Pula
                                    </option>
                                    <option value="BZD">Belize Dollar
                                    </option>
                                    <option value="CAD">Canadian Dollar
                                    </option>
                                    <option value="CHF">Swiss Franc
                                    </option>
                                    <option value="CLP">Chilean Peso
                                    </option>
                                    <option value="CNY">Chinese Renminbi Yuan
                                    </option>
                                    <option value="COP">Colombian Peso
                                    </option>
                                    <option value="CRC">Costa Rican Colón
                                    </option>
                                    <option value="CVE">Cape Verdean Escudo
                                    </option>
                                    <option value="CZK">Czech Koruna
                                    </option>
                                    <option value="DJF">Djiboutian Franc
                                    </option>
                                    <option value="DKK">Danish Krone
                                    </option>
                                    <option value="DOP">Dominican Peso
                                    </option>
                                    <option value="DZD">Algerian Dinar
                                    </option>
                                    <option value="EGP">Egyptian Pound
                                    </option>
                                    <option value="ETB">Ethiopian Birr
                                    </option>
                                    <option value="EUR">Euro
                                    </option>
                                    <option value="FJD">Fijian Dollar
                                    </option>
                                    <option value="FKP">Falkland Islands Pound
                                    </option>
                                    <option value="GBP">British Pound
                                    </option>
                                    <option value="GIP">Gibraltar Pound
                                    </option>
                                    <option value="GMD">Gambian Dalasi
                                    </option>
                                    <option value="GNF">Guinean Franc
                                    </option>
                                    <option value="GTQ">Guatemalan Quetzal
                                    </option>
                                    <option value="GYD">Guyanese Dollar
                                    </option>
                                    <option value="HKD">Hong Kong Dollar
                                    </option>
                                    <option value="HNL">Honduran Lempira
                                    </option>
                                    <option value="HRK">Croatian Kuna
                                    </option>
                                    <option value="HTG">Haitian Gourde
                                    </option>
                                    <option value="HUF">Hungarian Forint
                                    </option>
                                    <option value="IDR">Indonesian Rupiah
                                    </option>
                                    <option value="ILS">Israeli New Sheqel
                                    </option>
                                    <option value="INR">Indian Rupee
                                    </option>
                                    <option value="ISK">Icelandic Króna
                                    </option>
                                    <option value="JMD">Jamaican Dollar
                                    </option>
                                    <option value="JPY">Japanese Yen
                                    </option>
                                    <option value="KES">Kenyan Shilling
                                    </option>
                                    <option value="KHR">Cambodian Riel
                                    </option>
                                    <option value="KMF">Comorian Franc
                                    </option>
                                    <option value="KRW">South Korean Won
                                    </option>
                                    <option value="KYD">Cayman Islands Dollar
                                    </option>
                                    <option value="KZT">Kazakhstani Tenge
                                    </option>
                                    <option value="LAK">Lao Kip
                                    </option>
                                    <option value="LBP">Lebanese Pound
                                    </option>
                                    <option value="LKR">Sri Lankan Rupee
                                    </option>
                                    <option value="LRD">Liberian Dollar
                                    </option>
                                    <option value="MAD">Moroccan Dirham
                                    </option>
                                    <option value="MDL">Moldovan Leu
                                    </option>
                                    <option value="MNT">Mongolian Tögrög
                                    </option>
                                    <option value="MOP">Macanese Pataca
                                    </option>
                                    <option value="MRO">Mauritanian Ouguiya
                                    </option>
                                    <option value="MUR">Mauritian Rupee
                                    </option>
                                    <option value="MVR">Maldivian Rufiyaa
                                    </option>
                                    <option value="MWK">Malawian Kwacha
                                    </option>
                                    <option value="MXN">Mexican Peso
                                    </option>
                                    <option value="MYR">Malaysian Ringgit
                                    </option>
                                    <option value="NAD">Namibian Dollar
                                    </option>
                                    <option value="NGN">Nigerian Naira
                                    </option>
                                    <option value="NIO">Nicaraguan Córdoba
                                    </option>
                                    <option value="NOK">Norwegian Krone
                                    </option>
                                    <option value="NPR">Nepalese Rupee
                                    </option>
                                    <option value="NZD">New Zealand Dollar
                                    </option>
                                    <option value="PAB">Panamanian Balboa
                                    </option>
                                    <option value="PEN">Peruvian Nuevo Sol
                                    </option>
                                    <option value="PGK">Papua New Guinean Kina
                                    </option>
                                    <option value="PHP">Philippine Peso
                                    </option>
                                    <option value="PKR">Pakistani Rupee
                                    </option>
                                    <option value="PLN">Polish Złoty
                                    </option>
                                    <option value="PYG">Paraguayan Guaraní
                                    </option>
                                    <option value="QAR">Qatari Riyal
                                    </option>
                                    <option value="RUB">Russian Ruble
                                    </option>
                                    <option value="SAR">Saudi Riyal
                                    </option>
                                    <option value="SBD">Solomon Islands Dollar
                                    </option>
                                    <option value="SCR">Seychellois Rupee
                                    </option>
                                    <option value="SEK">Swedish Krona
                                    </option>
                                    <option value="SGD">Singapore Dollar
                                    </option>
                                    <option value="SHP">Saint Helenian Pound
                                    </option>
                                    <option value="SLL">Sierra Leonean Leone
                                    </option>
                                    <option value="SOS">Somali Shilling
                                    </option>
                                    <option value="STD">São Tomé and Príncipe Dobra
                                    </option>
                                    <option value="SVC">Salvadoran Colón
                                    </option>
                                    <option value="SZL">Swazi Lilangeni
                                    </option>
                                    <option value="THB">Thai Baht
                                    </option>
                                    <option value="TOP">Tongan Paʻanga
                                    </option>
                                    <option value="TTD">Trinidad and Tobago Dollar
                                    </option>
                                    <option value="TWD">New Taiwan Dollar
                                    </option>
                                    <option value="TZS">Tanzanian Shilling
                                    </option>
                                    <option value="UAH">Ukrainian Hryvnia
                                    </option>
                                    <option value="UGX">Ugandan Shilling
                                    </option>
                                    <option value="USD">United States Dollar
                                    </option>
                                    <option value="UYU">Uruguayan Peso
                                    </option>
                                    <option value="UZS">Uzbekistani Som
                                    </option>
                                    <option value="VND">Vietnamese Đồng
                                    </option>
                                    <option value="VUV">Vanuatu Vatu
                                    </option>
                                    <option value="WST">Samoan Tala
                                    </option>
                                    <option value="XAF">Central African Cfa Franc
                                    </option>
                                    <option value="XOF">West African Cfa Franc
                                    </option>
                                    <option value="XPF">Cfp Franc
                                    </option>
                                    <option value="YER">Yemeni Rial
                                    </option>
                                    <option value="ZAR">South African Rand
                                </select >
                                <small> ' . __('Enter your stripe currency', 'lfb') . ' </small>
                            </div>
                          
                        </div> ';

        if (is_plugin_active('gravityforms/gravityforms.php')) {
            echo ' <h4>' . __('Gravity Form', 'lfb') . ' </h4>
                                 <div class="form-group" >
                                <label> ' . __('Assign a Gravity Form to the last step', 'lfb') . ' </label>
                                <select name="gravityFormID" class="form-control" />
                                    <option value="0" > ' . __('None', 'lfb') . ' </option> ';
            $formsG = RGFormsModel::get_forms(null, "title");
            foreach ($formsG as $formG) {
                echo '<option value="' . $formG->id . '" > ' . $formG->title . '</option > ';
            }
            echo '
                                </select>
                                <small> ' . __('If true, the user will be redirected on the payment page', 'lfb') . ' </small>
                            </div>
    ';
        }
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            $disp = '';
        } else {
            $disp = 'style="display:none;"';
        }
        echo ' <div ' . $disp . ' ><h4 class="lfb_wooOption" > ' . __('Woo Commerce', 'lfb') . ' </h4 >
                            <div class="form-group lfb_wooOption"  >
                                    <label > ' . __('Add selected items to cart', 'lfb') . ' </label >
                                    <input type="checkbox"  name="save_to_cart" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                                    <small> ' . __('If true, all items with price must beings products of the woo catalog', 'lfb') . ' </small>
                                </div>
                        </div>

                        <div class="col-md-12" id="lfb_finalStepFields" >
                            <h4 > ' . __('Fields of the final step', 'lfb') . ' </h4 >
                            <p style="text-align: left;" ><a href="javascript:" id="lfb_addFieldBtn" onclick="lfb_editField(0);" class="btn btn-primary" ><span class="glyphicon glyphicon-plus" ></span > ' . __('Add a field', 'lfb') . ' </a ></p >
                            <table class="table table-striped table-bordered" >
                                <thead >
                                    <tr >
                                        <th > ' . __('Label', 'lfb') . ' </th >
                                        <th > ' . __('Type', 'lfb') . ' </th >
                                        <th > ' . __('Actions', 'lfb') . ' </th >
                                    </tr >
                                </thead >
                                <tbody >
                                </tbody >
                            </table >

                        </div>
                    <div class="clearfix" ></div>
    ';


        echo ' </div><div class="clearfix"></div></div>
                  <!--    <div class="clearfix" ></div>
               </div> -->
                <div role="tabpanel" class="tab-pane" id="lfb_tabDesign" >
                    <div class="row-fluid" >
                            <div class="col-md-12" >
                                <h4 > ' . __('Design', 'lfb') . ' </h4 >
                            </div>                            
                            <div class="col-md-4">
                             
                                <div class="form-group">
                                    <label>' . __('Use Google font ?', 'lfb') . '</label>
                                    <input type="checkbox"  name="useGoogleFont" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />   
                                    <small>' . __('If disabled, the default theme font will be used', 'lfb') . '</small>
                                </div>
                                <div class="form-group" >
                                       <label > ' . __('Google font name', 'lfb') . ' </label >
                                       <input type="text" name="googleFontName" class="form-control" style="width: 100%;" />
                                       <small> ' . __('ex : Lato', 'lfb') . '</small>
                                   <label></label>
                                   <a href="https://www.google.com/fonts" style="margin-top: 8px;" target="_blank" class="btn btn-default"><span class="glyphicon glyphicon-list"></span>' . __('See available Google fonts', 'lfb') . '</a>        
                               </div>
                                <div class="form-group" >
                                    <label > ' . __('Pictures size', 'lfb') . ' </label >
                                    <input type="number" name="item_pictures_size" class="form-control" />
                                    <small> ' . __('Enter a size in pixels(ex : 64)', 'lfb') . ' </small>
                                </div>        
                                <div class="form-group">
                                    <label>' . __('Inverse gray effect', 'lfb') . '</label>
                                    <input type="checkbox"  name="inverseGrayFx" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />   
                                    <small>' . __('Apply the gray effect on unselected items ?', 'lfb') . '</small>
                                </div>
                            </div>
                            <div class="col-md-4" >
                                <div class="form-group" >
                                    <label > ' . __('Main color', 'lfb') . ' </label >
                                    <input type="text" name="colorA" class="form-control colorpick" />
                                    <small> ' . __('ex : #1abc9c', 'lfb') . '</small>
                                </div>
                                <div class="form-group" >
                                    <label > ' . __('Secondary  color', 'lfb') . ' </label >
                                    <input type="text" name="colorB" class="form-control colorpick" />
                                    <small> ' . __('ex : #34495e', 'lfb') . '</small>
                                </div>
                                <div class="form-group" >
                                    <label > ' . __('Scroll margin', 'lfb') . ' </label >
                                    <input type="number" name="scrollTopMargin" class="form-control" />
                                    <small> ' . __('Increase this value if your theme uses a fixed header', 'lfb') . '</small>
                                </div>
                                  
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" >
                                      <label > ' . __('Texts color', 'lfb') . ' </label >
                                      <input type="text" name="colorC" class="form-control colorpick" />
                                      <small> ' . __('ex : #bdc3c7', 'lfb') . '</small>
                                  </div>
                                    <div class="form-group" >
                                        <label > ' . __('Background color', 'lfb') . ' </label >
                                        <input type="text" name="colorBg" class="form-control colorpick" />
                                        <small> ' . __('ex : #ecf0f1', 'lfb') . '</small>
                                    </div>
                            </div>
                            <div class="col-md-12">

                            <div class="form-group" >
                                <label style="margin-bottom: 18px;"> ' . __('Custom CSS rules', 'lfb') . ' </label >
                                <textarea name="customCss" class="form-control" style=" width: 100%; max-width: inherit; height: 120px;}"></textarea>
                                <small> ' . __('Enter your custom css code here', 'lfb') . '</small>
                            </div>
                            </div>
                    </div>
                    <div class="clearfix" ></div>

                </div>
                
                <div role="tabpanel" class="tab-pane" id="lfb_tabCoupons" >
                    <div class="row-fluid" >
                        <div class="col-md-12" style="padding-top: 14px;">
                            <h4 style="margin-top: 18px;"> ' . __('Discount coupons', 'lfb') . ' </h4>
                         </div>
                        <div class="col-md-6" >
                            <div class="form-group">
                                <label>' . __('Use discount coupons', 'lfb') . '</label>
                                <input type="checkbox"  name="useCoupons" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />   
                                <small>' . __('If you enable this option, a discount coupon field will be displayed at end of the form', 'lfb') . '</small>
                            </div>
                        </div>
                        <div class="col-md-6" >
                            <div class="lfb_couponsContainer">
                                <div class="form-group">
                                   <label>' . __('Label of the coupon field', 'lfb') . '</label>
                                   <input type="text"  name="couponText" class="form-control" />   
                               </div>
                            </div>
                        </div>
                        <div class="col-md-12" >
                            <div class="lfb_couponsContainer">
                                <p id="lfb_couponsTableBtns">
                                    <a href="javascript:" onclick="lfb_editCoupon(0);" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>' . __('Add a new coupon', 'lfb') . '</a>
                                    <a href="javascript:" style="margin-left: 8px;" onclick="lfb_removeAllCoupons();" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>' . __('Remove all coupons', 'lfb') . '</a>
                                </p>
                                <table id="lfb_couponsTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>' . __('Coupon code', 'lfb') . '</th>
                                            <th>' . __('Max uses', 'lfb') . '</th>
                                            <th>' . __('Number of uses', 'lfb') . '</th>                                                
                                            <th>' . __('Reduction', 'lfb') . '</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix" ></div>

                </div>


		<p style="text-align: center; padding-top: 18px;" ><a href="javascript:" onclick="lfb_saveForm();" class="btn btn-lg btn-primary" ><span class="glyphicon glyphicon-floppy-disk" ></span > ' . __('Save', 'lfb') . ' </a ></p >
              </div>

            </div> ';
        echo '<div class="clearfix" ></div>';


        echo '</div> ';


        echo '</div> ';
         echo ' <div id="lfb_emailValueBubble" class="container-fluid" >
                <div>
                <div class="col-md-12" >
                    <div class="form-group" >
                        <label > ' . __('Select an item', 'lfb') . ' </label >
                        <select name="itemID" class="form-control" />
                        </select >
                    </div>
                    <div class="form-group" style="display: none;" >
                        <label > ' . __('Select an attribute', 'lfb') . ' </label >
                        <select name="element" class="form-control" />
                            <option value="">' . __('Price', 'lfb') . '</option>
                            <option value="quantity">' . __('Quantity', 'lfb') . '</option>
                            <option value="value">' . __('Value', 'lfb') . '</option>
                        </select >
                    </div>
                    <p style="text-align: center;">
                        <a href="javascript:" class="btn btn-primary"  onclick="lfb_saveEmailValue();"><span class="glyphicon glyphicon-disk"></span>' . __('Insert', 'lfb') . '</a>
                    </p>
                </div>
                </div> ';
        echo '</div>'; // eof win lfb_emailValueBubble
        echo ' <div id="lfb_fieldBubble" class="container-fluid" >
                <div >
                    <input type="hidden" name="id" class="form-control" />
                <div class="col-md-12" >
                <div class="form-group" >
                    <label > ' . __('Label', 'lfb') . ' </label >
                    <input type="text" name="label" class="form-control" />
                    <small> ' . __('This is the field label', 'lfb') . ' </small>
                </div>
                <!--<div class="form-group" >
                    <label > ' . __('Order', 'lfb') . ' </label >
                    <input type="number" name="ordersort" class="form-control" />
                    <small> ' . __('Fields take place according to the order', 'lfb') . ' </small>
                </div>-->
                <div class="form-group" >
                    <label > ' . __('Type of field', 'lfb') . ' </label >
                    <select name="typefield" class="form-control" />
                        <option value="input" selected="" selected > Input</option >
                        <option value="textarea" > Textarea</option >
                    </select >
                    <small> ' . __('Choose a type', 'lfb') . ' </small>
                </div>
                <div class="form-group" >
                    <label > ' . __('Validation', 'lfb') . ' </label >
                    <select name="validation" class="form-control" />
                        <option value="" selected > None</option >
                        <option value="fill" > Must be filled </option >
                        <option value="email" > Email</option >
                    </select >
                    <small> ' . __('Select a validation method', 'lfb') . ' </small>
                </div>
                <div class="form-group" >
                    <label> ' . __('Type of information', 'lfb') . ' </label >
                    <select name="fieldType" class="form-control">
                        <option value="">' . __('Other', 'lfb') . '</option>    
                        <option value="address">' . __('Address', 'lfb') . '</option>    
                        <option value="city">' . __('City', 'lfb') . '</option>       
                        <option value="country">' . __('Country', 'lfb') . '</option>      
                        <option value="email">' . __('Email', 'lfb') . '</option>      
                        <option value="firstName">' . __('First name', 'lfb') . '</option>  
                        <option value="lastName">' . __('Last name', 'lfb') . '</option>  
                        <option value="phone">' . __('Phone', 'lfb') . '</option>    
                        <option value="state">' . __('State', 'lfb') . '</option>     
                        <option value="zip">' . __('Zip code', 'lfb') . '</option>                           
                    </select>
                    <small> ' . __('It will allow the plugin to recover this information', 'lfb') . ' </small>
                </div>
                <div class="form-group" >
                    <label > ' . __('Toggle or displayed ? ', 'lfb') . ' </label >
                    <select name="visibility" class="form-control" />
                        <option value="display" selected > Displayed</option >
                        <option value="toggle" > Toggle</option >
                    </select >
                </div>
                <div class="form-group" >
                    <label ></label >
                    <a href="javascript:" onclick="lfb_saveField();" style="display: inline-block; width: 190px;" class="btn btn-primary btn-block" ><span class="glyphicon glyphicon-floppy-disk"></span>'.__('Insert','lfb').'</a>
                </div>

                </div>
                </div>
            </div> ';

        echo '<div id="lfb_winLink" class="lfb_window container-fluid"> ';
        echo '<div class="lfb_winHeader col-md-12 palette palette-turquoise" ><span class="glyphicon glyphicon-pencil" ></span > ' . __('Edit a link', 'lfb');

        echo ' <div class="btn-toolbar"> ';
        echo '<div class="btn-group" > ';
        echo '<a class="btn btn-primary" href="javascript:" ><span class="glyphicon glyphicon-remove lfb_btnWinClose" ></span ></a > ';
        echo '</div> ';
        echo '</div> '; // eof toolbar
        echo '</div> '; // eof header

        echo '<div class="clearfix"></div><div class="container-fluid lfb_container"   style="max-width: 90%;margin: 0 auto;margin-top: 18px;"> ';
        echo '<div role="tabpanel">';
        echo '<ul class="nav nav-tabs" role="tablist" >
                <li role="presentation" class="active" ><a href="#lfb_linkTabGeneral" aria-controls="general" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-cog" ></span > ' . __('Link conditions', 'lfb') . ' </a ></li >
                </ul >';
        echo '<div class="tab-content" >';
        echo '<div role="tabpanel" class="tab-pane active" id="lfb_linkTabGeneral" >';

        echo '<div id="lfb_linkInteractions" > ';
        echo '<div id="lfb_linkStepsPreview">
                <div id="lfb_linkOriginStep" class="lfb_stepBloc "><div class="lfb_stepBlocWrapper"><h4 id="lfb_linkOriginTitle"></h4></div> </div>
                <div id="lfb_linkStepArrow"></div>
                <div id="lfb_linkDestinationStep" class="lfb_stepBloc  "><div class="lfb_stepBlocWrapper"><h4 id="lfb_linkDestinationTitle"></h4></div></div>
              </div>';
        echo '<p>'
        . '<select id="lfb_linkOperator" class="form-control">'
        . '<option value="">' . __('All conditions must be filled', 'lfb') . '</option>'
        . '<option value="OR">' . __('One of the conditions must be filled', 'lfb') . '</option>'
        . '</select>'
        . '<a href="javascript:" class="btn btn-primary" onclick="lfb_addLinkInteraction();" ><span class="glyphicon glyphicon-plus" ></span > ' . __('Add a condition', 'lfb') . ' </a></p> ';
        echo '<table id="lfb_conditionsTable" class="table">
                <thead>
                    <tr>
                        <th>' . __('Element', 'lfb') . '</th>
                        <th>' . __('Condition', 'lfb') . '</th>
                        <th>' . __('Value', 'lfb') . '</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
              </table>';

        echo '<div class="row" ><div class="col-md-12" ><p style="padding-left: 16px;padding-right: 16px; text-align: center;">'
        . '   <a href="javascript:" onclick="lfb_linkSave();" class="btn btn-primary" style="margin-top: 24px; margin-right: 8px;" ><span class="glyphicon glyphicon-ok" ></span > ' . __('Save', 'lfb') . ' </a >
              <a href="javascript:" onclick="lfb_linkDel();" class="btn btn-danger" style="margin-top: 24px;" ><span class="glyphicon glyphicon-trash" ></span > ' . __('Delete', 'lfb') . ' </a ></p ></div></div> ';

        echo '<div class="clearfix"></div>';
        echo '</div> '; // eof row
        echo '</div> '; // eof lfb_linkInteractions
        echo '</div> '; // eof tabpanel
        echo '</div> '; // eof tab-content
        echo '</div> '; // eof lfb_container

        echo '</div> '; //eof lfb_winLink
        // echo '</div> ';
        //  echo '</div> ';
        // echo '</div> ';// eof lfb_winLink
        
        
        
        echo '<div id="lfb_winRedirection" class="lfb_window container-fluid"> ';
        echo '<div class="lfb_winHeader col-md-12 palette palette-turquoise" ><span class="glyphicon glyphicon-pencil" ></span > ' . __('Edit a redirection', 'lfb');

        echo ' <div class="btn-toolbar"> ';
        echo '<div class="btn-group" > ';
        echo '<a class="btn btn-primary" href="javascript:" ><span class="glyphicon glyphicon-remove lfb_btnWinClose" ></span ></a > ';
        echo '</div> ';
        echo '</div> '; // eof toolbar
        echo '</div> '; // eof header

        echo '<div class="clearfix"></div><div class="container-fluid lfb_container"   style="max-width: 90%;margin: 0 auto;margin-top: 18px;"> ';
        echo '<div role="tabpanel">';
        echo '<ul class="nav nav-tabs" role="tablist" >
                <li role="presentation" class="active" ><a href="#lfb_redirTabGeneral" aria-controls="general" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-cog" ></span > ' . __('Link conditions', 'lfb') . ' </a ></li >
                </ul >';
        echo '<div class="tab-content" >';
        echo '<div role="tabpanel" class="tab-pane active" id="lfb_redirTabGeneral" >';

        echo '<div id="lfb_redirInteractions" > ';
        echo '<div id="lfb_redirStepsPreview">
                <div id="lfb_showIcon"></div>
              </div>';
         echo '<p>'
        . '<div class="form-group">'
                 . '<label>'.__('URL','lfb').' : </label>'
                 . '<input type="text" id="lfb_redirUrl" class="form-control"/>'
                 . '</div>'
        . '</p>';
        echo '<p>'
        . '<select id="lfb_redirOperator" class="form-control">'
        . '<option value="">' . __('All conditions must be filled', 'lfb') . '</option>'
        . '<option value="OR">' . __('One of the conditions must be filled', 'lfb') . '</option>'
        . '</select>'
        . '<a href="javascript:" class="btn btn-primary" onclick="lfb_addRedirInteraction();" ><span class="glyphicon glyphicon-plus" ></span > ' . __('Add a condition', 'lfb') . ' </a></p> ';
        echo '<table id="lfb_redirConditionsTable" class="table">
                <thead>
                    <tr>
                        <th>' . __('Element', 'lfb') . '</th>
                        <th>' . __('Condition', 'lfb') . '</th>
                        <th>' . __('Value', 'lfb') . '</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
              </table>';

        echo '<div class="row" ><div class="col-md-12" ><p style="padding-left: 16px;padding-right: 16px; text-align: center;">'
        . '   <a href="javascript:" onclick="lfb_redirSave();" class="btn btn-primary" style="margin-top: 24px; margin-right: 8px;" ><span class="glyphicon glyphicon-ok" ></span > ' . __('Save', 'lfb') . ' </a ></p ></div></div> ';

        echo '<div class="clearfix"></div>';
        echo '</div> '; // eof row
        echo '</div> '; // eof lfb_linkInteractions
        echo '</div> '; // eof tabpanel
        echo '</div> '; // eof tab-content
        echo '</div> '; // eof lfb_container

        echo '</div> '; //eof lfb_winRedirection


        echo '<div id="lfb_winCalculationConditions" class="lfb_window container-fluid"> ';
        echo '<div class="lfb_winHeader col-md-12 palette palette-turquoise" ><span class="glyphicon glyphicon-pencil" ></span > ' . __('Add a condition', 'lfb');

        echo ' <div class="btn-toolbar"> ';
        echo '<div class="btn-group" > ';
        echo '<a class="btn btn-primary" href="javascript:" ><span class="glyphicon glyphicon-remove lfb_btnWinClose" ></span ></a > ';
        echo '</div> ';
        echo '</div> '; // eof toolbar
        echo '</div> '; // eof header

        echo '<div class="clearfix"></div><div class="container-fluid lfb_container"   style="max-width: 90%;margin: 0 auto;margin-top: 18px;"> ';
        //echo '<div role="tabpanel">';
        echo '<ul class="nav nav-tabs" role="tablist" >
                <li role="presentation" class="active" ><a href="#lfb_calcTabGeneral" aria-controls="general" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-cog" ></span > ' . __('Conditions', 'lfb') . ' </a ></li >
                </ul >';
        echo '<div class="tab-content" >';
        echo '<div role="tabpanel" class="tab-pane active" id="lfb_calcTabGeneral" >';

        echo '<div id="#lfb_calcInteractions" > ';
        echo '<div id="lfb_calcStepsPreview">
                <div id="lfb_calcIcon"></div>
              </div>';
        echo '<p>'
        . '<select id="lfb_calcOperator" class="form-control">'
        . '<option value="">' . __('All conditions must be filled', 'lfb') . '</option>'
        . '<option value="OR">' . __('One of the conditions must be filled', 'lfb') . '</option>'
        . '</select>'
        . '<a href="javascript:" class="btn btn-primary" onclick="lfb_addCalcInteraction();" ><span class="glyphicon glyphicon-plus" ></span > ' . __('Add a condition', 'lfb') . ' </a></p> ';
        echo '<table id="lfb_calcConditionsTable" class="table">
                <thead>
                    <tr>
                        <th>' . __('Element', 'lfb') . '</th>
                        <th>' . __('Condition', 'lfb') . '</th>
                        <th>' . __('Value', 'lfb') . '</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
              </table>';

        echo '<div class="row" ><div class="col-md-12" ><p style="padding-left: 16px;padding-right: 16px; text-align: center;">'
        . '   <a href="javascript:" onclick="lfb_calcConditionSave();" class="btn btn-primary" style="margin-top: 24px; margin-right: 8px; margin-top: 18px;" ><span class="glyphicon glyphicon-ok" ></span > ' . __('Save', 'lfb') . ' </a>';
        echo '<div class="clearfix"></div>';
        echo '</div> '; // eof row
        echo '<div class="clearfix"></div>';
        echo '</div> '; // eof lfb_calcInteractions
        echo '</div> '; // eof lfb_calcTabGeneral
        echo '</div> '; // eof tabpanel
        echo '</div> '; // eof tab-content
        echo '</div> '; // eof lfb_container
        echo '</div> '; // eof lfb_winCalculationConditions



        echo '<div id="lfb_winShowConditions" class="lfb_window container-fluid"> ';
        echo '<div class="lfb_winHeader col-md-12 palette palette-turquoise" ><span class="glyphicon glyphicon-pencil" ></span > ' . __('Add a condition', 'lfb');

        echo ' <div class="btn-toolbar"> ';
        echo '<div class="btn-group" > ';
        echo '<a class="btn btn-primary" href="javascript:" ><span class="glyphicon glyphicon-remove lfb_btnWinClose" ></span ></a > ';
        echo '</div> ';
        echo '</div> '; // eof toolbar
        echo '</div> '; // eof header

        echo '<div class="clearfix"></div><div class="container-fluid lfb_container"   style="max-width: 90%;margin: 0 auto;margin-top: 18px;"> ';
        //echo '<div role="tabpanel">';
        echo '<ul class="nav nav-tabs" role="tablist" >
                <li role="presentation" class="active" ><a href="#lfb_showTabGeneral" aria-controls="general" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-cog" ></span > ' . __('Conditions', 'lfb') . ' </a ></li >
                </ul >';
        echo '<div class="tab-content" >';
        echo '<div role="tabpanel" class="tab-pane active" id="lfb_showTabGeneral" >';

        echo '<div id="#lfb_showInteractions" > ';
        echo '<div id="lfb_showStepsPreview">
                <div id="lfb_showIcon"></div>
              </div>';
        echo '<p>'
        . '<select id="lfb_showOperator" class="form-control">'
        . '<option value="">' . __('All conditions must be filled', 'lfb') . '</option>'
        . '<option value="OR">' . __('One of the conditions must be filled', 'lfb') . '</option>'
        . '</select>'
        . '<a href="javascript:" class="btn btn-primary" onclick="lfb_addShowInteraction();" ><span class="glyphicon glyphicon-plus" ></span > ' . __('Add a condition', 'lfb') . ' </a></p> ';
        echo '<table id="lfb_showConditionsTable" class="table">
                <thead>
                    <tr>
                        <th>' . __('Element', 'lfb') . '</th>
                        <th>' . __('Condition', 'lfb') . '</th>
                        <th>' . __('Value', 'lfb') . '</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
              </table>';

        echo '<div class="row" ><div class="col-md-12" ><p style="padding-left: 16px;padding-right: 16px; text-align: center;">'
        . '   <a href="javascript:" onclick="lfb_showConditionSave();" class="btn btn-primary" style="margin-top: 24px; margin-right: 8px;" ><span class="glyphicon glyphicon-ok" ></span > ' . __('Save', 'lfb') . ' </a >';
        echo '<div class="clearfix"></div>';
        echo '</div> '; // eof row
        echo '</div> '; // eof lfb_calcInteractions
        echo '</div> '; // eof lfb_calcTabGeneral
        echo '</div> '; // eof tabpanel
        echo '</div> '; // eof tab-content
        echo '</div> '; // eof lfb_container
        echo '</div> '; // eof lfb_winShowConditions


        echo '<div id="lfb_winShowStepConditions" class="lfb_window container-fluid"> ';
        echo '<div class="lfb_winHeader col-md-12 palette palette-turquoise" ><span class="glyphicon glyphicon-pencil" ></span > ' . __('Add a condition', 'lfb');

        echo ' <div class="btn-toolbar"> ';
        echo '<div class="btn-group" > ';
        echo '<a class="btn btn-primary" href="javascript:" ><span class="glyphicon glyphicon-remove lfb_btnWinClose" ></span ></a > ';
        echo '</div> ';
        echo '</div> '; // eof toolbar
        echo '</div> '; // eof header

        echo '<div class="clearfix"></div><div class="container-fluid lfb_container"   style="max-width: 90%;margin: 0 auto;margin-top: 18px;"> ';
        //echo '<div role="tabpanel">';
        echo '<ul class="nav nav-tabs" role="tablist" >
                <li role="presentation" class="active" ><a href="#lfb_showStepTabGeneral" aria-controls="general" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-cog" ></span > ' . __('Conditions', 'lfb') . ' </a ></li >
                </ul >';
        echo '<div class="tab-content" >';
        echo '<div role="tabpanel" class="tab-pane active" id="lfb_showStepTabGeneral" >';

        echo '<div id="#lfb_showStepInteractions" > ';
        echo '<div id="lfb_showStepStepsPreview">
                <div id="lfb_showIcon"></div>
              </div>';
        echo '<p>'
        . '<select id="lfb_showStepOperator" class="form-control">'
        . '<option value="">' . __('All conditions must be filled', 'lfb') . '</option>'
        . '<option value="OR">' . __('One of the conditions must be filled', 'lfb') . '</option>'
        . '</select>'
        . '<a href="javascript:" class="btn btn-primary" onclick="lfb_addShowStepInteraction();" ><span class="glyphicon glyphicon-plus" ></span > ' . __('Add a condition', 'lfb') . ' </a></p> ';
        echo '<table id="lfb_showStepConditionsTable" class="table">
                <thead>
                    <tr>
                        <th>' . __('Element', 'lfb') . '</th>
                        <th>' . __('Condition', 'lfb') . '</th>
                        <th>' . __('Value', 'lfb') . '</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
              </table>';

        echo '<div class="row" ><div class="col-md-12" ><p style="padding-left: 16px;padding-right: 16px; text-align: center;">'
        . '   <a href="javascript:" onclick="lfb_showStepConditionSave();" class="btn btn-primary" style="margin-top: 24px; margin-right: 8px;" ><span class="glyphicon glyphicon-ok" ></span > ' . __('Save', 'lfb') . ' </a >';
        echo '<div class="clearfix"></div>';
        echo '</div> '; // eof row
        echo '</div> '; // eof lfb_calcInteractions
        echo '</div> '; // eof lfb_calcTabGeneral
        echo '</div> '; // eof tabpanel
        echo '</div> '; // eof tab-content
        echo '</div> '; // eof lfb_container
        echo '</div> '; // eof lfb_winShowConditions




        echo '<div id="lfb_winStep" class="lfb_window container-fluid">';
        echo '<div class="lfb_winHeader col-md-12 palette palette-turquoise"><span class="glyphicon glyphicon-pencil"></span>' . __('Edit a step', 'lfb');

        echo '<div class="btn-toolbar">';
        echo '<div class="btn-group">';
        echo '<a class="btn btn-primary" href="javascript:"><span class="glyphicon glyphicon-remove lfb_btnWinClose"></span></a>';
        echo '</div>';
        echo '</div>'; // eof toolbar
        echo '</div>'; // eof header
        echo '<div class="clearfix"></div>';
        echo '<div class="container-fluid  lfb_container"  style="max-width: 90%;margin: 0 auto;margin-top: 18px;">';
        echo '<div role="tabpanel">';
        echo '<ul class="nav nav-tabs" role="tablist" >
                <li role="presentation" class="active" ><a href="#lfb_stepTabGeneral" aria-controls="general" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-cog" ></span > ' . __('Step', 'lfb') . ' </a ></li >
                </ul >';
        echo '<div class="tab-content" >';
        echo '<div role="tabpanel" class="tab-pane active" id="lfb_stepTabGeneral" >';
        echo '<h4 style="padding-left: 14px; padding-right: 14px;">' . __('Step options', 'lfb') . ' </h4>';
        echo '<div class="col-md-3">';
        echo '<div class="form-group" >
                    <label> ' . __('Title', 'lfb') . ' </label >
                    <input type="text" name="title" class="form-control" />
                    <small> ' . __('This is the step name', 'lfb') . ' </small>
                </div>';
        echo '<div class="form-group" >
                    <label> ' . __('Description', 'lfb') . ' </label >
                    <input type="text" name="description" class="form-control" />
                    <small> ' . __('A facultative description', 'lfb') . ' </small>
                </div>';

        echo '</div>'; // eof col-md-4
        echo '<div class="col-md-3">';

        echo '
                <div class="form-group" >
                    <label> ' . __('Max items per row', 'lfb') . ' </label >
                     <input type="number" name="itemsPerRow" class="form-control" min="0" />
                    <small> ' . __('Leave 0 to fill the full width', 'lfb') . ' </small>
                </div>
                ';
        echo '<div class="">
                    <label></label >
                    <textarea name="showConditions" style="display: none;"></textarea>
                    <input type="hidden" name="showConditionsOperator" style="display: none;"/>
                </div>';
        echo '<div class="form-group" style="height: 86px; margin-bottom: 0px; top: -18px;">
                    <label> ' . __('Selection required', 'lfb') . ' </label ><br/>
                    <input type="checkbox"  name="itemRequired" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    
                    <!-- <select name="itemRequired" class="form-control" />
                        <option value="0" > ' . __('No', 'lfb') . ' </option >
                        <option value="1" > ' . __('Yes', 'lfb') . ' </option >
                    </select>-->
                    <small> ' . __('If true, the user must select at least one item to continue', 'lfb') . ' </small>
                </div>';

        echo '</div>'; // eof col-md-4
        echo '<div class="col-md-3">';
        echo '<div class="form-group" style="height: 86px; margin-bottom: 34px;" >
                    <label> ' . __('Show it depending on conditions ?', 'lfb') . ' </label ><br/>
                    <input type="checkbox"  name="useShowConditions" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    
                    <a href="javascript:" id="showConditionsStepBtn" onclick="lfb_editShowStepConditions();" class="btn btn-primary" style="margin-left: 18px;"><span class="glyphicon glyphicon-question-sign"></span> ' . __('Edit conditions', 'lfb') . '</a>
                    <small> ' . __('This step will be displayed only if the conditions are filled', 'lfb') . ' </small>
                </div>
                <div class="form-group" style="height: 86px; margin-bottom: 0px;  top: -18px;" >
                    <label> ' . __('Show in email/summary ?', 'lfb') . ' </label ><br/>
                    <input type="checkbox"  name="showInSummary" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />

                    <!-- <select name="showInSummary" class="form-control" >
                        <option value="0" > ' . __('No', 'lfb') . ' </option >
                        <option value="1" > ' . __('Yes', 'lfb') . ' </option >
                    </select>-->
                    <small> ' . __('This step will be displayed in the summary', 'lfb') . ' </small>
                </div>';


        echo '</div>'; // eof col-md-3
        echo '<div class="col-md-3">';
         echo '<div class="form-group" style="height: 86px; margin-bottom: 34px;" >
                    <label> ' . __('Hide the next step button ?', 'lfb') . ' </label ><br/>
                    <input type="checkbox"  name="hideNextStepBtn" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    
                </div>';
        echo '</div>'; // eof col-md-3
        echo '<div class="col-md-12" style="padding-left: 14px; padding-right: 14px;">';
        echo '<p style="text-align:center;"><a href="javascript:" class="btn btn-primary" onclick="lfb_saveStep();"><span class="glyphicon glyphicon-floppy-disk"></span>' . __('Save', 'lfb') . '</a></p>';
        echo '</div>'; // eof col-md-12
        echo '<div class="clearfix"></div>';


        echo '<div role="tabpanel" id="lfb_itemsList" style="margin-top: 24px;padding-left: 14px; padding-right: 14px;">';
        echo '<h4>' . __('Items List', 'lfb') . ' </h4>';
        echo '<div id="lfb_itemTab" >';
        echo '<div class="col-md-12">';
        echo '<p style="padding-top: 24px;"><a href="javascript:" onclick="lfb_editItem(0);" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span>' . __('Add a new Item', 'lfb') . '</a></p>';
        echo '<table id="lfb_itemsTable" class="table">';
        echo '<thead>
                <th>' . __('Title', 'lfb') . '</th>
                <th>' . __('Group', 'lfb') . '</th>
                <th>' . __('Actions', 'lfb') . '</th>
            </thead>';
        echo '<tbody>';
        echo '</tbody>';
        echo '</table>';
        echo '</div>'; // eof col-md-12
        echo '<div class="clearfix"></div>';
        echo '</div>'; // eof lfb_itemTab
        echo '</div>'; // eof tabpanel

        echo '</div>'; // eof lfb_stepTabGeneral
        echo '</div>'; // eof tab-content
        echo '</div>'; // eof tabpanel

        echo '</div>'; // eof lfb_container
        echo '</div>'; // eof win step


        echo '<div id="lfb_winItem" class="lfb_window container-fluid">';
        echo '<div class="lfb_winHeader col-md-12 palette palette-turquoise"><span class="glyphicon glyphicon-pencil"></span>' . __('Edit an item', 'lfb');

        echo '<div class="btn-toolbar">';
        echo '<div class="btn-group">';
        echo '<a class="btn btn-primary" href="javascript:"><span class="glyphicon glyphicon-remove lfb_btnWinClose"></span></a>';
        echo '</div>';
        echo '</div>'; // eof toolbar
        echo '</div>'; // eof header
        echo '<div class="clearfix"></div>';
        echo '<div class="container-fluid  lfb_container"  style="max-width: 90%;margin: 0 auto;margin-top: 18px;">';
        echo '<div role="tabpanel">';
        echo '<ul class="nav nav-tabs" role="tablist" >
                <li role="presentation" class="active" ><a href="#lfb_itemTabGeneral" aria-controls="general" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-cog" ></span > ' . __('Item options', 'lfb') . ' </a ></li >
                </ul >';
        echo '<div class="tab-content" >';
        echo '<div role="tabpanel" class="tab-pane active" id="lfb_itemTabGeneral" >';
        echo '<div class="col-md-6">';
        echo '<div class="form-group" >
                    <label> ' . __('Title', 'lfb') . ' </label >
                    <input type="text" name="title" class="form-control" />
                    <small> ' . __('This is the item name', 'lfb') . ' </small>
                </div>';
        echo '<div class="form-group" >
                    <label> ' . __('Small description', 'lfb') . ' </label >
                    <textarea name="description" class="form-control" style="height: 42px;" ></textarea>
                    <small> ' . __('Item small description. You can leave it empty.', 'lfb') . ' </small>
                </div>';
        echo '<div class="form-group" >
                    <label> ' . __('Group name', 'lfb') . ' </label >
                    <input type="text" name="groupitems" class="form-control" />
                    <small> ' . __('Only one of the items of a same group can be selected', 'lfb') . ' </small>
                </div>';

        echo '<div class="form-group" >
                    <label> ' . __('Type', 'lfb') . ' </label >
                    <select name="type" class="form-control">
                        <option value="picture">' . __('Picture', 'lfb') . '</option>
                        <option value="checkbox">' . __('Checkbox', 'lfb') . '</option>
                        <option value="textfield">' . __('Text field', 'lfb') . '</option>
                        <option value="numberfield">' . __('Number field', 'lfb') . '</option>
                        <option value="textarea">' . __('Text area', 'lfb') . '</option>
                        <option value="select">' . __('Select field', 'lfb') . '</option>
                        <option value="datepicker">' . __('Date picker', 'lfb') . '</option>
                        <option value="filefield">' . __('File field', 'lfb') . '</option>
                        <option value="colorpicker" >' . __('Color picker', 'lfb') . '</option>
                        <option value="richtext">' . __('Rich Text', 'lfb') . '</option>
                        <option value="slider">' . __('Slider', 'lfb') . '</option>
                    </select>
                    <small> ' . __('Select a type of item', 'lfb') . ' </small>
                </div>';

        echo '<div class="form-group lfb_textOnly" >
                    <label> ' . __('Type of information', 'lfb') . ' </label >
                    <select name="fieldType" class="form-control">
                        <option value="">' . __('Other', 'lfb') . '</option>    
                        <option value="address">' . __('Address', 'lfb') . '</option>    
                        <option value="city">' . __('City', 'lfb') . '</option>       
                        <option value="country">' . __('Country', 'lfb') . '</option>      
                        <option value="email">' . __('Email', 'lfb') . '</option>      
                        <option value="firstName">' . __('First name', 'lfb') . '</option>  
                        <option value="lastName">' . __('Last name', 'lfb') . '</option>  
                        <option value="phone">' . __('Phone', 'lfb') . '</option>    
                        <option value="state">' . __('State', 'lfb') . '</option>     
                        <option value="zip">' . __('Zip code', 'lfb') . '</option>                           
                    </select>
                    <small> ' . __('It will allow the plugin to recover this information', 'lfb') . ' </small>
                </div>';

        echo '<div class="form-group " >
                    <label> ' . __('Min size', 'lfb') . ' </label >
                    <input type="number" name="minSize" class="form-control" />
                    <small> ' . __('Fill this field to limit the the minimum number of characters', 'lfb') . ' </small>
                </div>';
        echo '<div class="form-group " >
                    <label> ' . __('Max size', 'lfb') . ' </label >
                    <input type="number" name="maxSize" class="form-control" />
                    <small> ' . __('Fill this field to limit the the maximum number of characters', 'lfb') . ' </small>
                </div>';

        echo '<div id="lfb_itemOptionsValuesPanel"><table id="lfb_itemOptionsValues" class="table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th colspan="3">' . __('Options of select field', 'lfb') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr class="static">';
        echo '<td><div class="form-group" style="top: 10px;"><input type="text" id="option_new_value" class="form-control" value="" placeholder="' . __('Option value', 'lfb') . '"></div></td>'
        . '<td><div class="form-group" style="top: 10px;"><input type="number" id="option_new_price" step="any" class="form-control" value="0" placeholder="' . __('Option price', 'lfb') . '"></div></td>';
        echo '<td style="width: 200px;"><a href="javascript:" onclick="lfb_add_option();" class="btn btn-default"><span class="glyphicon glyphicon-plus" style="margin-right:8px;"></span>' . __('Add a new option', 'lfb') . '</a></td>';
        echo '</tr>';
        echo '</tbody>';
        echo '</table></div>';

        echo '<div class="form-group picOnly" >
                    <label > ' . __('Picture', 'lfb') . ' </label >
                    <input type="text" name="image" class="form-control " style="max-width: 140px; margin-right: 10px;display: inline-block;" />
                    <a class="btn btn-default imageBtn" style=" display: inline-block;">' . __('Upload Image', 'lfb') . '</a>
                    <small display: block;> ' . __('Select a picture', 'lfb') . ' </small>
                </div>';
        echo '<input type="hidden" name="imageDes"/>';
        echo '<div class="form-group picOnly" >
                    <label> ' . __('Tint image ?', 'lfb') . ' </label >
                    <input type="checkbox"  name="imageTint" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    <small> ' . __('Automatically fill the picture with the main color', 'lfb') . ' </small>
                </div>';

        echo '<div class="form-group " >
                    <label> ' . __('Open url on click ?', 'lfb') . ' </label >
                    <input type="text"  name="urlTarget" class="form-control" placeholder="http://..."  />
                    <small> ' . __('If you fill an url, it will be opened in a new tab on selection', 'lfb') . ' </small>
                </div>';

        echo '<div class="form-group" >
                    <label> ' . __('Display price in title ?', 'lfb') . ' </label >
                    <input type="checkbox"  name="showPrice" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    <small> ' . __('Shows the price in the item title', 'lfb') . ' </small>
                </div>';
        echo '<div class="form-group" >
                    <label> ' . __('Use column or row ?', 'lfb') . ' </label >
                    <select name="useRow" class="form-control">
                        <option value="0">' . __('Column', 'lfb') . '</option>
                        <option value="1">' . __('Row', 'lfb') . '</option>
                    </select>
                    <small> ' . __('The item will be displayed as column or full row', 'lfb') . ' </small>
                </div>';


        echo '<div class="form-group" >
                    <label> ' . __('Show it depending on conditions ?', 'lfb') . ' </label >
                    <input type="checkbox"  name="useShowConditions" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    <small> ' . __('This item will be displayed only if the conditions are filled', 'lfb') . ' </small>
                </div>';

        echo '<div class="form-group" >
                    <label></label >
                    <textarea name="showConditions" style="display: none;"></textarea>
                    <input type="hidden" name="showConditionsOperator" style="display: none;"/>
                    <a href="javascript:" onclick="lfb_editShowConditions();" class="btn btn-primary"><span class="glyphicon glyphicon-question-sign"></span> ' . __('Edit conditions', 'lfb') . '</a>
                </div>';

        echo '</div>'; // eof col-md-6
        echo '<div class="col-md-6">';
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            $disp = '';
        } else {
            $disp = 'style="display:none;"';
        }
        echo '<div class="form-group" ' . $disp . '>
                    <label> ' . __('Woocommerce product', 'lfb') . ' </label>
                   <select name="wooProductID" class="form-control">
                        ';
        echo '<option value="0"> ' . __('None', 'lfb') . '</option>';
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            $args = array('post_type' => 'product', 'posts_per_page' => -1, 'orderby' => 'category', 'order' => 'ASC');
            $products = get_posts($args);
            foreach ($products as $productI) {
                $product = get_product($productI->ID);
                $cat = '';
                $cats = $product->get_categories(',');
                $cats = explode(',', $cats);
                foreach ($cats as $catI) {
                    $cat = $cat . $catI . ' > ';
                }
                $sel = '';
                $dataMax = '';
                $dataImg = '';
                if ($product->is_type('simple')) {
                    if ($product->get_stock_quantity() && $product->get_stock_quantity() > 0) {
                        if ($product->get_stock_quantity() > 5) {
                            $dataMax = 'data-max="5"';
                        } else {
                            $dataMax = 'data-max="' . $product->get_stock_quantity() . '"';
                        }
                    }
                    // check image
                    $argsI = array('post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $productI->ID);
                    $attachments = get_posts($argsI);
                    if ($attachments[0]) {
                        $imgDom = wp_get_attachment_image($attachments[count($attachments) - 1]->ID, 'thumbnail');
                        $img = substr($imgDom, strpos($imgDom, 'src="') + 5, strpos($imgDom, '"', stripos($imgDom, 'src="') + 6) - (strpos($imgDom, 'src="') + 5));

                        $dataImg = 'data-img="' . $img . '"';
                    }

                    echo '<option ' . $sel . ' ' . $dataImg . ' ' . $dataMax . ' value="' . $productI->ID . '" data-title="' . $productI->post_title . '">' . $cat . $productI->post_title . '</option>';
                } else if ($product->is_type('variable')) {
                    $available_variations = $product->get_available_variations();
                    foreach ($available_variations as $variation) {
                        $variable_product = new WC_Product_Variation($variation['variation_id']);
                        if ($variable_product->get_stock_quantity() && $variable_product->get_stock_quantity() > 0) {
                            if ($variable_product->get_stock_quantity() > 5) {
                                $dataMax = 'data-max="5"';
                            } else {
                                $dataMax = 'data-max="' . $variable_product->get_stock_quantity() . '"';
                            }
                        }
                        // check image
                        $argsI = array('post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $productI->ID);
                        $attachments = get_posts($argsI);
                        if ($attachments[0]) {
                            $imgDom = wp_get_attachment_image($attachments[count($attachments) - 1]->ID, 'thumbnail');
                            $img = substr($imgDom, strpos($imgDom, 'src="') + 5, strpos($imgDom, '"', stripos($imgDom, 'src="') + 6) - (strpos($imgDom, 'src="') + 5));

                            $dataImg = 'data-img="' . $img . '"';
                        }
                        echo '<option ' . $sel . ' ' . $dataImg . ' ' . $dataMax . ' value="' . $productI->ID . '" data-woovariation="' . $variation['variation_id'] . '" data-title="' . $productI->post_title . ' - ' . $variation['sku'] . '">' . $cat . $productI->post_title . ' - ' . $variation['sku'] . '</option>';
                    }
                }
            }
        }
        echo '    </select>
                    <small> ' . __('You can select a product from your catalog', 'lfb') . ' </small>
                </div>';
        echo '<div class="form-group wooMasked" >
                    <label> ' . __('Price', 'lfb') . ' </label><label style="display: none;">' . __('Percentage', 'lfb') . '</label>
                    <input type="number" name="price" step="any" class="form-control" />
                    <small> ' . __('Sets the item price', 'lfb') . ' </small>
                </div>';
        echo '<div class="form-group" >
                   <label> ' . __('Use calculation ?', 'lfb') . ' </label >
                   <input type="checkbox"  name="useCalculation" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    <small> ' . __('If checked, the price will be replaced by a calculation', 'lfb') . ' </small>
                </div>';

        echo '<div class="form-group" >
                    <label> ' . __('Calculation', 'lfb') . ' </label >
                    <a href="javascript:" onclick="lfb_addCalculationValue();" class="btn btn-default" style="margin:4px;margin-bottom: 8px;"><span class="glyphicon glyphicon-plus"></span>' . __('Add a value', 'lfb') . '</a>
                    <a href="javascript:" onclick="lfb_addCalculationCondition();" class="btn btn-default" style="margin:4px;margin-bottom: 8px; margin-left:0px;"><span class="glyphicon glyphicon-plus"></span>' . __('Add a condition', 'lfb') . '</a>
                    <a href="javascript:" id="lfb_addDistanceBtn" onclick="lfb_editDistanceValue(false);" class="btn btn-default" style="margin:4px;margin-bottom: 8px;"><span class="glyphicon glyphicon-plus"></span>' . __('Add a distance', 'lfb') . '</a><br/>

                    <textarea name="calculation" class="form-control" style="max-width: 100%; width: 100%;" ></textarea>
                    <small> ' . __('Use the buttons to easily create your calculation', 'lfb') . ' </small>
                    <div class="alert alert-info" style="margin-top: 18px;">
                        <p>' . __('Example of calculation', 'lfb') . ' :</p>
                        <pre>10
if(([item-3_quantity] >5) ) {
	([item-3_price]/2)*([item-1_quantity])
} </pre>
                    <p style="font-size: 12px;">' . __('Here, the default price of the item will be $10. If the item #3 is selected, the price of the current item will be the half of the item #3 calculated price multiplied by the selected quantity of the item #1.', 'lfb') . '</p>
                    </div>
                </div>';


        echo '<div class="form-group" >
                    <label> ' . __('Operator', 'lfb') . ' </label >
                    <select name="operation" class="form-control">
                        <option value="+">' . __('+', 'lfb') . '</option>
                        <option value="-">' . __('-', 'lfb') . '</option>
                        <option value="x">' . __('x', 'lfb') . '</option>
                        <option value="/">' . __('/', 'lfb') . '</option>
                    </select>
                    <small> ' . __('+ and - allow you to add or remove the price of the total price, * and / allow you to add or remove a percentage from the total price', 'lfb') . ' </small>
                </div>';



        echo '<div class="form-group" >
                   <label> ' . __('Is selected ?', 'lfb') . ' </label >
                   <input type="checkbox"  name="ischecked" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    <small> ' . __('Is the item selected by default ?', 'lfb') . ' </small>
                </div>';
        echo '<div class="form-group" >
                   <label> ' . __('Is hidden ?', 'lfb') . ' </label >
                   <input type="checkbox"  name="isHidden" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    <small> ' . __('Item will be used in the calculation, but will not be displayed', 'lfb') . ' </small>
                </div>';
        echo '<div class="form-group" >
                    <label> ' . __('Is required ?', 'lfb') . ' </label >
                    <input type="checkbox"  name="isRequired" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    <small> ' . __('Is the item required to continue ?', 'lfb') . ' </small>
                </div>';
        echo '<div class="form-group" >
                    <label> ' . __('Use payment only if selected', 'lfb') . ' </label >
                    <input type="checkbox"  name="usePaypalIfChecked" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    <small> ' . __('Payment will be used only if this item is selected', 'lfb') . ' </small>
                </div>';

        echo '<div class="form-group" >
                    <label> ' . __('Show in email/summary ?', 'lfb') . ' </label >
                    <input type="checkbox"  name="showInSummary" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    <small> ' . __('This item will be displayed in the summary if the user selects it', 'lfb') . ' </small>
                </div>';
        
        echo '<div class="form-group" >
                    <label> ' . __('Hide quantity in summary ?', 'lfb') . ' </label >
                    <input type="checkbox"  name="hideQtSummary" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    <small> ' . __('The quantity of this item will be hidden in the summary', 'lfb') . ' </small>
                </div>';
        

        echo '<div class="form-group" >
                    <label> ' . __('Default value', 'lfb') . ' </label >
                    <input type="text" name="defaultValue" class="form-control" />
                    <small> ' . __('Defines the default value of this field', 'lfb') . ' </small>
                </div>';

        echo '<div class="form-group" >
                    <label> ' . __('Max files', 'lfb') . ' </label >
                    <input type="number" name="maxFiles" class="form-control" />
                    <small> ' . __('Maximum number of files the user can upload', 'lfb') . ' </small>
                </div>';
        echo '<div class="form-group" >
                    <label> ' . __('Allowed files', 'lfb') . ' </label >
                    <textarea name="allowedFiles" class="form-control" ></textarea>
                    <small> ' . __('Enter the allowed extensions separated by commas', 'lfb') . ' </small>
                </div>';

        echo '<div class="form-group" >
                    <label> ' . __("Isn't a part of the subscription ?", 'lfb') . ' </label >
                    <input type="checkbox"  name="isSinglePrice" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    <small> ' . __('If checked, the item price will not be a part of the subscription price', 'lfb') . ' </small>
                </div>';

        echo '<div class="form-group" >
                    <label> ' . __('Enable quantity choice ?', 'lfb') . ' </label >
                    <input type="checkbox"  name="quantity_enabled" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    <small> ' . __('Can the user select a quantity for this item ?', 'lfb') . ' </small>
                </div>';
        echo '<div id="efp_itemQuantity">';
        echo '<div class="form-group" >
                    <label> ' . __('Min quantity', 'lfb') . ' </label >
                    <input type="number" name="quantity_min" class="form-control" />
                    <small> ' . __('Sets the minimum quantity that can be selected', 'lfb') . ' </small>
                </div>';
        echo '<div class="form-group" >
                    <label> ' . __('Max quantity', 'lfb') . ' </label >
                    <input type="number" name="quantity_max" class="form-control" />
                    <small> ' . __('Sets the maximum quantity that can be selected', 'lfb') . ' </small>
                </div>';
            echo '<div class="form-group" >
                    <label> ' . __('Apply reductions on quantities ?', 'lfb') . ' </label >
                    <input type="checkbox"  name="reduc_enabled" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    <small> ' . __('Apply reductions on quantities ?', 'lfb') . ' </small>
                </div>';
            echo '<div class="form-group" >
                    <label> ' . __('Use distance as quantity ?', 'lfb') . ' </label >
                    <input type="checkbox"  name="useDistanceAsQt" data-switch="switch" data-on-label="' . __('Yes', 'lfb') . '" data-off-label="' . __('No', 'lfb') . '" />
                    <small> ' . __('Use distance as quantity ?', 'lfb') . ' </small>
                </div>
                <input type="hidden" name="distanceQt"/>
                <div id="lfb_distanceQtContainer" class="form-group" >
                    <label></label >
                    <a href="javascript:" onclick="lfb_editDistanceValue(true);" class="btn btn-default"> ' . __('Configure the distance', 'lfb') . ' </a>
                </div>
                
                ';
        echo '<table id="lfb_itemPricesGrid" class="table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . __('If quantity >= than', 'lfb') . '</th>';
        echo '<th>' . __('Item price becomes', 'lfb') . '</th>';
        echo '<th></th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr class="static">';
        echo '<td><input type="number" style="width: 100%;" class="form-control" id="reduc_new_qt" value="" placeholder="' . __('Quantity', 'lfb') . '"></td>';
        echo '<td><input type="number"  style="width: 100%;" class="form-control"  id="reduc_new_price" value="" placeholder="' . __('Price', 'lfb') . '"></td>';
        echo '<td><a href="javascript:" onclick="lfb_add_reduc();" class="btn btn-default"><span class="glyphicon glyphicon-plus" style="margin-right:8px;"></span>' . __('Add a new reduction', 'lfb') . '</a></td>';
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
        echo '</div>'; // eof efp_itemQuantity


        echo '</div>'; // eof col-md-6
        echo '<div class="col-md-12">';
        echo '<div id="lfb_itemRichText"></div>';
        echo '<p style="padding-left: 14px; padding-right: 14px;text-align:center;"><a href="javascript:" class="btn btn-primary" onclick="lfb_saveItem();"><span class="glyphicon glyphicon-floppy-disk"></span>' . __('Save', 'lfb') . '</a></p>';
        echo '</div>'; // eof col-md-12
        echo '<div class="clearfix"></div>';

        echo '</div>'; // eof lfb_stepTabGeneral
        echo '</div>'; // eof tab-content
        echo '</div>'; // eof tabpanel

        echo '</div>'; // eof lfb_container
        echo '</div>'; // eof lfb_winItem



        echo ' <div id="lfb_calculationValueBubble" class="container-fluid" >
                <div>
                <div class="col-md-12" >
                    <div class="form-group" >
                        <label > ' . __('Select an item', 'lfb') . ' </label >
                        <select name="itemID" class="form-control" />
                        </select >
                    </div>
                    <div class="form-group" >
                        <label > ' . __('Select an attribute', 'lfb') . ' </label >
                        <select name="element" class="form-control" />
                            <option value="">' . __('Price', 'lfb') . '</option>
                            <option value="quantity">' . __('Quantity', 'lfb') . '</option>
                            <option value="value">' . __('Value', 'lfb') . '</option>
                        </select >
                    </div>
                    <p style="text-align: center;">
                        <a href="javascript:" class="btn btn-primary"  onclick="lfb_saveCalculationValue();"><span class="glyphicon glyphicon-disk"></span>' . __('Insert', 'lfb') . '</a>
                    </p>
                </div>
                </div> ';
        echo '</div>'; // eof win lfb_calculationValueBubble
        
        
         echo '<div id="lfb_winDistance" class="lfb_window container-fluid"> ';
        echo '<div class="lfb_winHeader col-md-12 palette palette-turquoise" ><span class="glyphicon glyphicon-pencil" ></span > ' . __('Distance calculation', 'lfb');

        echo ' <div class="btn-toolbar"> ';
        echo '<div class="btn-group" > ';
        echo '<a class="btn btn-primary" href="javascript:" ><span class="glyphicon glyphicon-remove lfb_btnWinClose" ></span ></a > ';
        echo '</div> ';
        echo '</div> '; // eof toolbar
        echo '</div> '; // eof header

        echo '<div class="clearfix"></div><div class="container-fluid lfb_container"   style="max-width: 90%;margin: 0 auto;margin-top: 18px;"> ';
        echo '<div role="tabpanel">';
        echo '<ul class="nav nav-tabs" role="tablist" >
                <li role="presentation" class="active" ><a href="#lfb_distanceTabGeneral" aria-controls="general" role="tab" data-toggle="tab" ><span class="glyphicon glyphicon-cog" ></span > ' . __('Distance calculation', 'lfb') . ' </a ></li>
                </ul >';
        echo '<div class="tab-content" >';
        echo '<div role="tabpanel" class="tab-pane active" id="lfb_distanceTabGeneral" >';
        
            echo '<div id="lfb_calcStepsPreview">
                    <div id="lfb_mapIcon"></div>
                  </div>';
            echo '<div class="col-md-6" >
                    <h4>'.__('Departure address','lfb').'</h4>
                    <table id="lfb_departTable" class="table table-striped">
                    <thead>
                        <th>'.__('Type','lfb').'</th>
                        <th>'.__('Item','lfb').'</th>
                    </thead>
                    <tbody>
                        <tr>
                        <td>'.__('Address','lfb').'</td>
                        <td>
                            <select id="lfb_departAdressItem" class="form-control">
                            </select>
                        </td>
                        </tr>
                        <tr>
                        <td>'.__('City','lfb').'</td>
                        <td>
                            <select id="lfb_departCityItem" class="form-control">
                            </select>
                        </td>
                        </tr>
                        <tr>
                        <td>'.__('Zip code','lfb').'</td>
                        <td>
                            <select id="lfb_departZipItem" class="form-control">
                            </select>
                        </td>
                        </tr>
                        <tr>
                        <td>'.__('Country','lfb').'</td>
                        <td>
                            <select id="lfb_departCountryItem" class="form-control">
                            </select>
                        </td>
                        </tr>
                    </tbody>
                    </table>
                    </div>
                    <div class="col-md-6" >
                    <h4>'.__('Arrival address','lfb').'</h4>
                        <table id="lfb_arrivalTable" class="table table-striped">
                    <thead>
                        <th>'.__('Type','lfb').'</th>
                        <th>'.__('Item','lfb').'</th>
                    </thead>
                    <tbody>
                        <tr>
                        <td>'.__('Address','lfb').'</td>
                        <td>
                            <select id="lfb_arrivalAdressItem" class="form-control">
                            </select>
                        </td>
                        </tr>
                        <tr>
                        <td>'.__('City','lfb').'</td>
                        <td>
                            <select id="lfb_arrivalCityItem" class="form-control">
                            </select>
                        </td>
                        </tr>
                        <tr>
                        <td>'.__('Zip code','lfb').'</td>
                        <td>
                            <select id="lfb_arrivalZipItem" class="form-control">
                            </select>
                        </td>
                        </tr>
                        <tr>
                        <td>'.__('Country','lfb').'</td>
                        <td>
                            <select id="lfb_arrivalCountryItem" class="form-control">
                            </select>
                        </td>
                        </tr>
                    </tbody>
                    </table>
                    </div>
                    <div class="clearfix"></div>
                    <p style="text-align: center;">
                        '.__('The result will be the distance between the two addresses in','lfb').'
                         <select class="form-control" id="lfb_distanceType" style="max-width: 280px;display: inline-block;margin-left: 8px;">
                            <option value="km">'.__('km','lfb').'</option>
                            <option value="miles">'.__('miles','lfb').'</option>
                         </select>
                    </p>
                    <p style="text-align: center;">
                        <a href="javascript:" class="btn btn-primary" style="margin-top:18px;"  onclick="lfb_saveDistanceValue();"><span class="glyphicon glyphicon-floppy-disk"></span>' . __('Insert', 'lfb') . '</a>
                    </p>
                ';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>'; // eof lfb_winRedirection
        
        

          echo ' <div id="lfb_distanceValueBubble" class="container-fluid" >
                
                </div> ';// eof win lfb_distanceValueBubble

        echo '<div id="lfb_winEditCoupon" class="modal fade ">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">' . __('Edit a coupon', 'lfb') . '</h4>
                    </div>
                    <div class="modal-body" style="padding-bottom:0px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>' . __('Coupon code', 'lfb') . '</label>
                                <input type="text" class="form-control" name="couponCode"/>
                            </div>
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>' . __('Reduction type', 'lfb') . '</label>
                                <select class="form-control" name="reductionType">
                                    <option value="">' . __('Price', 'lfb') . '</option>
                                    <option value="percentage">' . __('Percentage', 'lfb') . '</option>
                                </select>
                            </div>
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>' . __('Reduction', 'lfb') . '</label>
                                <input type="number" step="any" class="form-control" name="reduction"/>
                            </div>
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>' . __('Max uses', 'lfb') . '</label>
                                <input type="number" class="form-control" name="useMax" min="0" /><br/>
                                <small>' . __('Set 0 for an infinite use', 'lfb') . '</small>
                            </div> 
                        </div>
                    <div class="clearfix" ></div>
                    </div>
                    <div class="modal-footer" style="text-align: center;">
                        <a href="javascript:" class="btn btn-primary"  onclick="lfb_saveCoupon();"><span class="glyphicon glyphicon-floppy-disk"></span>' . __('Save', 'lfb') . '</a>
                    </div><!-- /.modal-footer -->
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
              </div><!-- /.modal -->';

        echo '<div id="lfb_winLog" class="modal fade ">
                         <div class="modal-dialog">
                           <div class="modal-content">
                             <div class="modal-body">
                             </div>
                             <div class="modal-footer" style="text-align: center;">
                                 <a href="javascript:" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>' . __('Close', 'lfb') . '</a>
                             </div><!-- /.modal-footer -->
                           </div><!-- /.modal-content -->
                         </div><!-- /.modal-dialog -->
                       </div><!-- /.modal -->';

        echo '<div id="lfb_winShortcode" class="modal fade ">
                         <div class="modal-dialog">
                           <div class="modal-content">
                             <div class="modal-header">
                               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                               <h4 class="modal-title">' . __('Shortcode', 'lfb') . '</h4>
                             </div>
                             <div class="modal-body">
                                <p style="margin-bottom: 0px;"><strong>' . __('Integrate form in a page', 'lfb') . ':</strong></p>
                                <input id="lfb_shortcode_1" readonly class="lfb_shortcodeInput" onclick="lfb_selectPre(this);" value="[estimation_form form_id=' . "&quot;" . '1' . "&quot;" . ']"/>
                                <p style="margin-bottom: 0px;"><strong>' . __('To use in fullscreen', 'lfb') . ':</strong></p>
                                <input id="lfb_shortcode_2" readonly class="lfb_shortcodeInput" onclick="lfb_selectPre(this);" value="[estimation_form form_id=' . "&quot;" . '1' . "&quot;" . ' fullscreen=&quot;true&quot;]"/>
                                <p style="margin-bottom: 0px;"><strong>' . __('To use as popup', 'lfb') . ':</strong></p>
                                <input id="lfb_shortcode_3" readonly class="lfb_shortcodeInput" onclick="lfb_selectPre(this);" value="[estimation_form form_id=' . "&quot;" . '1' . "&quot;" . ' popup=' . "&quot;" . 'true' . "&quot;" . ']" />
                                <p style="margin-bottom: 0px;">To open the popup, simply use the css class "<b>open-estimation-form form-<span data-displayid="1" style="font-weight: bold;">1</span></b>".</p>
                                <input id="lfb_shortcode_4" readonly class="lfb_shortcodeInput" onclick="lfb_selectPre(this);" value="&lt;a href=' . "&quot;" . '#' . "&quot;" . ' class=' . "&quot;" . 'open-estimation-form form-1' . "&quot;" . '&gt;Open Form&lt;/a&gt;">
                             </div>
                           </div><!-- /.modal-content -->
                         </div><!-- /.modal-dialog -->
                       </div><!-- /.modal -->';

        $dispS = '';
        if ($settings->purchaseCode == "" && !get_option('lfb_themeMode')) {
            $dispS = 'true';
        }
        echo '<div id="lfb_winActivation" class="modal fade " data-show="' . $dispS . '" >
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">The license must be verified</h4>
                              </div>
                              <div class="modal-body">
                                <div id="lfb_iconLock"></div>
                                <p style="margin-bottom: 14px;">
                                	The license of this plugin isn\'t verified.<br/>Please fill the field below with your purchase code :
                                </p>
                                <div class="form-group">
                                	<input type="text" class="form-control" style="display:inline-block; width: 312px; margin-bottom: 4px" name="purchaseCode" placeholder="Enter your puchase code here"/>
                                	<a href="javascript:" onclick="lfb_checkLicense();" class="btn btn-primary"><span class="glyphicon glyphicon-check"></span>Verify</a>
                                	<br/>
                                	<span style="font-size:12px;"><a href="' . $this->parent->assets_url . 'img/purchaseCode.gif" target="_blank">Where I can find my purchase code ?</a></span>
                                </div>
                                <div class="alert alert-danger" style="font-size:12px;  margin-bottom: 0px;" >
                                	<span class="glyphicon glyphicon-warning-sign" style="margin-right: 12px;float: left;font-size: 22px;margin-top: 10px;margin-bottom: 10px;"></span>
                                  Each website using this plugin needs a legal license (1 license = 1 website). <br/>
                                  You can find more information on envato licenses <a href="http://codecanyon.net/licenses/standard" target="_blank">clicking here</a>.<br/>
                                     If you need to buy a new license of this plugin, <a href="http://codecanyon.net/item/wp-flat-estimation-payment-forms-/7818230?ref=loopus" target="_blank">click here</a>.
                                </div>
                              </div>
                              <div class="modal-footer" style="text-align: center;">
              								<a href="javascript:"  id="lfb_closeWinActivationBtn" class="btn btn-default disabled"><span class="glyphicon glyphicon-remove"></span><span class="lfb_text">Close</span></a>
              							  </div><!-- /.modal-footer -->
                            </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->';

        echo '<div id="lfb_winImport" class="modal fade">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">' . __('Import data', 'lfb') . '</h4>
                              </div>
                              <div class="modal-body">
                               <div class="alert alert-danger"><p>' . __('Be carreful : all existing forms and steps will be erased importing new data.', 'lfb') . '</p></div>
                                   <form id="lfb_winImportForm" method="post" enctype="multipart/form-data">
                                       <div class="form-group">
                                        <input type="hidden" name="action" value="lfb_importForms"/>
                                        <label>' . __('Select the .zip data file', 'lfb') . '</label><input name="importFile" type="file" class="" />
                                       </div>
                                  </form>
                              </div>
                              <div class="modal-footer">
                                <a href="javascript:" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>' . __('Cancel', 'lfb') . '</a>
                                <a href="javascript:" class="btn btn-primary" onclick="lfb_importForms();"><span class="glyphicon glyphicon-floppy-disk"></span>' . __('Import', 'lfb') . '</a>
                            </div>
                            </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->';


        echo '<div id="lfb_winExport" class="modal fade">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">' . __('Export data', 'lfb') . '</h4>
                      </div>
                      <div class="modal-body">
                        <p style="text-align: center;"><a href="' . $this->parent->assets_url . '../tmp/export_estimation_form.zip" target="_blank" onclick="jQuery(\'#lfb_winExport\').modal(\'hide\');" class="btn btn-primary btn-lg" id="lfb_exportLink"><span class="glyphicon glyphicon-floppy-disk"></span>' . __('Download the exported data', 'lfb') . '</a></p>
                      </div>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->';

        echo ' </div><!-- /wpe_bootstraped -->';
    }

    /* Load Logs */

    function loadLogs() {
        global $wpdb;
        $formID = sanitize_text_field($_POST['formID']);
        $rep = "";
        $table_name = $wpdb->prefix . "wpefc_logs";
        $logs = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID . " AND checked=1 ORDER BY id DESC");
        foreach ($logs as $log) {
            $formTitle = "";
            $rep .= '<tr>
                 <td>' . date(get_option('date_format'), strtotime($log->dateLog)) . '</td>
                <td><a href="javascript:" onlick="lfb_loadLog(' . $log->id . ');">' . $log->ref . '</a></td>
                    <td>' . $log->email . '</td>
                    <td><a href="javascript:" onclick="lfb_loadLog(' . $log->id . ');" class="btn btn-primary btn-circle" data-toggle="tooltip" title="' . __('View this order', 'lfb') . '" data-placement="bottom"><span class="glyphicon glyphicon-search"></span></a>
                    <a href="javascript:" onclick="lfb_removeLog(' . $log->id . ',' . $formID . ');" class="btn btn-danger btn-circle" data-toggle="tooltip" title="' . __('Delete this order', 'lfb') . '" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a></td>
          </tr>';
        }
        echo $rep;
        die();
    }

    /* Load Log */

    function loadLog() {
        global $wpdb;
        $logID = sanitize_text_field($_POST['logID']);
        $rep = "";
        $table_name = $wpdb->prefix . "wpefc_logs";
        $log = $wpdb->get_results("SELECT * FROM $table_name WHERE id=" . $logID);
        if (count($log) > 0) {
            $log = $log[0];
            $rep = $log->content;
        }
        echo $rep;
        die();
    }

    /* Remove Log */

    function removeLog() {
        global $wpdb;
        $logID = sanitize_text_field($_POST['logID']);
        $table_name = $wpdb->prefix . "wpefc_logs";
        $wpdb->delete($table_name, array('id' => $logID));
        die();
    }

    /*
     * Load admin styles
     */

    function admin_styles() {
        if (isset($_GET['page']) && strpos($_GET['page'], 'lfb') !== false) {
            wp_register_style($this->parent->_token . '-reset', esc_url($this->parent->assets_url) . 'css/reset.css', array(), $this->parent->_version);
            //wp_enqueue_style('jquery-ui-datepicker-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
            wp_register_style($this->parent->_token . '-jqueryui', esc_url($this->parent->assets_url) . 'css/jquery-ui-theme/jquery-ui.min.css', array(), $this->parent->_version);
            wp_register_style($this->parent->_token . '-bootstrap', esc_url($this->parent->assets_url) . 'css/bootstrap.min.css', array(), $this->parent->_version);
            wp_register_style($this->parent->_token . '-flat-ui', esc_url($this->parent->assets_url) . 'css/flat-ui_frontend.css', array(), $this->parent->_version);
            wp_register_style($this->parent->_token . '-colpick', esc_url($this->parent->assets_url) . 'css/colpick.css', array(), $this->parent->_version);
            wp_register_style($this->parent->_token . '-lfb-admin', esc_url($this->parent->assets_url) . 'css/lfb_admin.min.css', array(), $this->parent->_version);
            wp_register_style($this->parent->_token . '-fontawesome', esc_url($this->parent->assets_url) . 'css/font-awesome.min.css', array(), $this->parent->_version);
            wp_register_style($this->parent->_token . '-editor', esc_url($this->parent->assets_url) . 'css/summernote.min.css', array(), $this->parent->_version);
            wp_register_style($this->parent->_token . '-editorB3', esc_url($this->parent->assets_url) . 'css/summernote-bs3.css', array(), $this->parent->_version);

            wp_enqueue_style($this->parent->_token . '-reset');
            wp_enqueue_style($this->parent->_token . '-jqueryui');
            wp_enqueue_style($this->parent->_token . '-bootstrap');
            wp_enqueue_style($this->parent->_token . '-flat-ui');
            wp_enqueue_style($this->parent->_token . '-colpick');
            wp_enqueue_style($this->parent->_token . '-fontawesome');
            wp_enqueue_style($this->parent->_token . '-editor');
            wp_enqueue_style($this->parent->_token . '-editorB3');
            wp_enqueue_style($this->parent->_token . '-lfb-admin');
            wp_enqueue_style($this->parent->_token . '-core-components');
        }
        wp_register_style($this->parent->_token . '-lfb-adminGlobal', esc_url($this->parent->assets_url) . 'css/lfb_admin_global.css', array(), $this->parent->_version);
        wp_enqueue_style($this->parent->_token . '-lfb-adminGlobal');
    }

    /*
     * Load admin scripts
     */

    function admin_scripts() {
        if (isset($_GET['page']) && strpos($_GET['page'], 'lfb') !== false) {
            wp_register_script($this->parent->_token . '-bootstrap', esc_url($this->parent->assets_url) . 'js/bootstrap.min.js', array('jquery', "jquery-ui-core"), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-bootstrap');
            wp_register_script($this->parent->_token . '-bootstrap-switch', esc_url($this->parent->assets_url) . 'js/bootstrap-switch.js', array('jquery', "jquery-ui-core"), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-bootstrap-switch');
            wp_register_script($this->parent->_token . '-colpick', esc_url($this->parent->assets_url) . 'js/colpick.js', array('jquery'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-colpick');
            wp_register_script($this->parent->_token . '-editor', esc_url($this->parent->assets_url) . 'js/summernote.min.js', array('jquery', "jquery-ui-core", $this->parent->_token . '-bootstrap'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-editor');
            // wp_enqueue_script('tiny_mce');
            wp_register_script($this->parent->_token . '-googleCharts', 'https://www.gstatic.com/charts/loader.js', array('jquery'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-googleCharts');

            wp_register_script($this->parent->_token . '-lfb-admin', esc_url($this->parent->assets_url) . 'js/lfb_admin.min.js', array("jquery-ui-draggable", "jquery-ui-droppable", "jquery-ui-resizable", "jquery-ui-sortable", "jquery-ui-datepicker", $this->parent->_token . '-bootstrap-switch', $this->parent->_token . '-editor'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-lfb-admin');

            $js_data[] = array(
                'assetsUrl' => esc_url($this->parent->assets_url),
                'websiteUrl' => esc_url(get_home_url()),
                'texts' => array(
                    'tip_flagStep' => __('Click the flag icon to set this step at first step', 'lfb'),
                    'tip_linkStep' => __('Start a link to another step', 'lfb'),
                    'tip_delStep' => __('Remove this step', 'lfb'),
                    'tip_duplicateStep' => __('Duplicate this step', 'lfb'),
                    'tip_editStep' => __('Edit this step', 'lfb'),
                    'tip_editLink' => __('Edit a link', 'lfb'),
                    'isSelected' => __('Is selected', 'lfb'),
                    'isUnselected' => __('Is unselected', 'lfb'),
                    'isPriceSuperior' => __('Is price superior to', 'lfb'),
                    'isPriceInferior' => __('Is price inferior to', 'lfb'),
                    'isPriceEqual' => __('Is price equal to', 'lfb'),
                    'isSuperior' => __('Is superior to', 'lfb'),
                    'isInferior' => __('Is inferior to', 'lfb'),
                    'isEqual' => __('Is equal to', 'lfb'),
                    'isQuantitySuperior' => __('Quantity selected is superior to', 'lfb'),
                    'isQuantityInferior' => __('Quantity selected is inferior to', 'lfb'),
                    'isQuantityEqual' => __('Quantity is equal to', 'lfb'),
                    'totalPrice' => __('Total price', 'lfb'),
                    'totalQuantity' => __('Total quantity', 'lfb'),
                    'isFilled' => __('Is Filled', 'lfb'),
                    'errorExport' => __('An error occurred during the exportation. Please verify that your server supports the ZipArchive php library ', 'lfb'),
                    'errorImport' => __('An error occurred during the importation. Please verify that your server supports the ZipArchive php library ', 'lfb'),
                    'Yes' => __('Yes', 'lfb'),
                    'No' => __('No', 'lfb'),
                    'days' => __('Days', 'lfb'),
                    'months' => __('Months', 'lfb'),
                    'years' => __('Years', 'lfb'),
                    'amountOrders' => __('Amount of orders', 'lfb'),
                    'oneTimePayment' => __('One time payments or estimates', 'lfb'),
                    'subscriptions' => __('Subscriptions', 'lfb'),
                    'lastStep' => __('Last Step', 'lfb'),   
                    'Nothing' => __('Nothing', 'lfb'),               
                )
            );
            wp_localize_script($this->parent->_token . '-lfb-admin', 'lfb_data', $js_data);
        }
    }

    private function jsonRemoveUnicodeSequences($struct) {
        return json_encode($struct);
    }

    public function loadCharts() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $formID = sanitize_text_field($_POST['formID']);
            $mode = sanitize_text_field($_POST['mode']);
            $rep = '';
            $conditionChecked = '';
            $table_name = $wpdb->prefix . "wpefc_forms";
            $form = $wpdb->get_results("SELECT * FROM $table_name WHERE id=" . $formID . " LIMIT 1");
            if (count($form) > 0) {
                if ($mode == 'all') {
                    $table_name = $wpdb->prefix . "wpefc_logs";
                    $logs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY dateLog ASC LIMIT 1");
                    $yearMin = date('Y');
                    $currentYear = date('Y');
                    if (count($logs) > 0) {
                        $log = $logs[0];
                        $yearMin = substr($log->dateLog, 0, 4);
                    }
                    $rep.= ($yearMin - 1) . ';0;0|';
                    for ($a = $yearMin; $a <= $currentYear; $a++) {
                        $table_name = $wpdb->prefix . "wpefc_logs";
                        $logs = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=$formID AND dateLog LIKE '" . $a . "-%' ORDER BY dateLog ASC");
                        $valuePrice = 0;
                        $valueSubs = 0;
                        foreach ($logs as $log) {
                            $valuePrice += $log->totalPrice;
                            $valueSubs += $log->totalSubscription;
                        }
                        $rep.= $a . ';' . $valuePrice . ';' . $valueSubs . '|';
                    }
                } else if ($mode == 'month') {
                    $yearMonth = sanitize_text_field($_POST['yearMonth']);
                    $year = substr($yearMonth, 0, 4);
                    $month = substr($yearMonth, 6, 2);
                    $nbDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                    for ($i = 1; $i <= $nbDays; $i++) {
                        $table_name = $wpdb->prefix . "wpefc_logs";
                        $logs = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=$formID AND dateLog LIKE '" . $yearMonth . '-' . $i . "' ORDER BY dateLog ASC");
                        $valuePrice = 0;
                        $valueSubs = 0;
                        foreach ($logs as $log) {
                            $valuePrice += $log->totalPrice;
                            $valueSubs += $log->totalSubscription;
                        }
                        $rep.= $i . ';' . $valuePrice . ';' . $valueSubs . '|';
                    }
                } else {
                    $year = sanitize_text_field($_POST['year']);
                    for ($i = 1; $i <= 12; $i++) {
                        $month = $i;
                        if ($month < 10) {
                            $month = '0' . $month;
                        }
                        $yearMonth = $year . '-' . $month;

                        $table_name = $wpdb->prefix . "wpefc_logs";
                        $logs = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=$formID AND dateLog LIKE '" . $yearMonth . "%' ORDER BY dateLog ASC");
                        $valuePrice = 0;
                        $valueSubs = 0;
                        foreach ($logs as $log) {
                            $valuePrice += $log->totalPrice;
                            $valueSubs += $log->totalSubscription;
                        }
                        $rep.= $month . ';' . $valuePrice . ';' . $valueSubs . '|';
                    }
                    if (strlen($rep) > 0) {
                        $rep = substr($rep, 0, -1);
                    } else {
                        $rep = '0;0;0|';
                    }
                }
            }
            echo $rep;
            die();
        }
    }

    public function addForm() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $table_name = $wpdb->prefix . "wpefc_forms";
            $wpdb->insert($table_name, array('title' => 'My new Form', 'btn_step' => "NEXT STEP", 'previous_step' => "return to previous step", 'intro_title' => "HOW MUCH TO MAKE MY WEBSITE ?", 'intro_text' => "Estimate the cost of a website easily using this awesome tool.", 'intro_btn' => "GET STARTED", 'last_title' => "Final cost", 'last_text' => "The final estimated price is : ", 'last_btn' => "ORDER MY WEBSITE", 'last_msg_label' => "Do you want to write a message ? ", 'succeed_text' => "Thanks, we will contact you soon", 'initial_price' => 0, 'email' => 'your@email.com', 'email_subject' => 'New order from your website', 'currency' => '$', 'currencyPosition' => 'left', 'errorMessage' => 'You need to select an item to continue', 'intro_enabled' => 1, 'email_userSubject' => 'Order confirmation',
                'email_adminContent' => '<p style="text-align:right;">Ref: <strong>[ref]</strong></p><h2 style="color: #008080;">Information</h2><hr/><span style="color: #444444;">[information_content]</span><span style="color: #444444;"> </span><hr/><h2 style="color: #008080;">Project</h2><hr/>[project_content]',
                'email_userContent' => '<p style="text-align:right;">Ref: <strong>[ref]</strong></p><h2 style="color: #008080;">Information</h2><hr/><span style="color: #444444;">[information_content]</span><span style="color: #444444;"> </span><hr/><h2 style="color: #008080;">Project</h2><hr/>[project_content]<hr/><p><span style="font-style:italic;">Thank you for your confidence.</span></p>',
                'colorA' => '#1abc9c', 'colorB' => '#34495e', 'colorC' => '#bdc3c7', 'item_pictures_size' => 64, 'colorBg' => '#ecf0f1', 'summary_title' => 'Summary', 'summary_description' => 'Description', 'summary_quantity' => 'Quantity', 'summary_price' => 'Price', 'summary_value' => 'Information', 'summary_total' => 'Total :', 'legalNoticeTitle' => 'I certify I completely read and I accept the legal notice by validating this form',
                'legalNoticeContent' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam faucibus lectus ac massa dictum, rhoncus bibendum mauris volutpat. Aenean venenatis mi porta gravida dignissim. Mauris eu ipsum convallis, semper massa sed, bibendum justo. Pellentesque porta suscipit aliquet. Integer quis odio tempus nibh cursus sollicitudin. Vivamus at rutrum dui. Proin sit amet porta neque, ac hendrerit purus.',
                'decimalsSeparator' => '.', 'thousandsSeparator' => ',', 'stripe_label_creditCard' => 'Credit card number', 'stripe_label_cvc' => 'CVC',
                'stripe_label_expiration' => 'Expiration date', 'stripe_currency' => 'USD', 'stripe_subsFrequencyType' => 'month',
                'redirectionDelay'=>5,'useRedirectionConditions'=>0,'txtDistanceError'=>'Calculating the distance could not be performed, please verify the input addresses'));

            $formID = $wpdb->insert_id;

            $table_name = $wpdb->prefix . "wpefc_fields";
            $wpdb->insert($table_name, array('formID' => $formID, 'label' => "Enter your email", 'isRequired' => 1, 'typefield' => 'input', 'visibility' => 'display', 'validation' => 'email'));
            $wpdb->insert($table_name, array('formID' => $formID, 'label' => "Do you want to write a message ?", 'isRequired' => 0, 'typefield' => 'textarea', 'visibility' => 'toggle'));

            echo $formID;
            die();
        }
    }

    public function duplicateStep() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $table_name = $wpdb->prefix . "wpefc_steps";
            $stepID = sanitize_text_field($_POST['stepID']);
            $steps = $wpdb->get_results("SELECT * FROM $table_name WHERE id=" . $stepID);
            $step = $steps[0];
            $step->title = $step->title . ' (1)';
            $step->start = 0;
            unset($step->id);

            $content = json_decode($step->content);
            $content->previewPosX += 40;
            $content->previewPosY += 40;
            $content->start = 0;
            $step->content = stripslashes($this->jsonRemoveUnicodeSequences($content));

            //$wpdb->insert($table_name, array('content' => $this->jsonRemoveUnicodeSequences($content), 'start' => 0,'title'=>$step->title,'itemRequired'=>$step->itemRequired ));
            $wpdb->insert($table_name, (array) $step);
            $newID = $wpdb->insert_id;

            $table_name = $wpdb->prefix . "wpefc_items";
            $items = $wpdb->get_results("SELECT * FROM $table_name WHERE stepID=$stepID");
            foreach ($items as $item) {
                $item->stepID = $newID;
                unset($item->id);
                $wpdb->insert($table_name, (array) $item);
            }
            die();
        }
    }

    public function duplicateItem() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $table_name = $wpdb->prefix . "wpefc_items";
            $itemID = sanitize_text_field($_POST['itemID']);
            $items = $wpdb->get_results("SELECT * FROM $table_name WHERE id=" . $itemID);
            $item = $items[0];
            $item->title = $item->title . ' (1)';
            unset($item->id);
            $wpdb->insert($table_name, (array) $item);
        }
        die();
    }

    public function changeItemsOrders() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $items = sanitize_text_field($_POST['items']);
            $items = explode(',', $items);
            $table_name = $wpdb->prefix . "wpefc_items";
            foreach ($items as $key => $value) {
                $wpdb->update($table_name, array('ordersort' => $key), array('id' => $value));
            }
        }
        die();
    }

    public function changeLastFieldsOrders() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $fields = sanitize_text_field($_POST['fields']);
            $fields = explode(',', $fields);
            $table_name = $wpdb->prefix . "wpefc_fields";
            foreach ($fields as $key => $value) {
                $wpdb->update($table_name, array('ordersort' => $key), array('id' => $value));
            }
        }
        die();
    }

    /*
     * Check for  updates
     */
    function checkAutomaticUpdates() {

        if (current_user_can('manage_options')) {
            $settings = $this->getSettings();
            if ($settings && $settings->purchaseCode != "" && !get_option('lfb_themeMode')) {
                require_once('plugin_update_check.php');
                $updateChecker = new PluginUpdateChecker_2_0(
                        'https://kernl.us/api/v1/updates/56af639d99c6c1732b9284ce/', $this->parent->file, 'lfb', 1
                );
                $updateChecker->purchaseCode = $settings->purchaseCode;
            }
        }
    }

    public function duplicateForm() {
        global $wpdb;
        if (current_user_can('manage_options')) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $formID = sanitize_text_field($_POST['formID']);

        $table_forms = $wpdb->prefix . "wpefc_forms";
        $table_steps = $wpdb->prefix . "wpefc_steps";
        $table_items = $wpdb->prefix . "wpefc_items";
        $table_fields = $wpdb->prefix . "wpefc_fields";
        $table_links = $wpdb->prefix . "wpefc_links";
        $forms = $wpdb->get_results("SELECT * FROM $table_forms WHERE id=$formID LIMIT 1");
        $form = $forms[0];
        unset($form->id);
        $form->title = $form->title . ' (1)';
        $form->current_ref = 1;
        $wpdb->insert($table_forms, (array) $form);
        $newFormID = $wpdb->insert_id;
        $fields = $wpdb->get_results("SELECT * FROM $table_fields WHERE formID=$formID");
        foreach ($fields as $field) {
            unset($field->id);
            $field->formID = $newFormID;
            $wpdb->insert($table_fields, (array) $field);
        }
        $stepsReplacement = array();
        $itemsReplacement = array();

        $steps = $wpdb->get_results("SELECT * FROM $table_steps WHERE formID=$formID");
        foreach ($steps as $step) {
            $step->formID = $newFormID;
            $stepID = $step->id;
            unset($step->id);

            $wpdb->insert($table_steps, (array) $step);
            $newStepID = $wpdb->insert_id;
            $stepsReplacement[$stepID] = $newStepID;

            $items = $wpdb->get_results("SELECT * FROM $table_items WHERE stepID=$stepID");
            foreach ($items as $item) {
                $itemID = $item->id;
                unset($item->id);
                $item->stepID = $newStepID;
                $item->formID = $newFormID;
                $wpdb->insert($table_items, (array) $item);
                $newItemID = $wpdb->insert_id;

                $itemsReplacement[$itemID] = $newItemID;
            }
        }
        $stepsNew = $wpdb->get_results("SELECT * FROM $table_steps WHERE formID=$newFormID");
        foreach ($stepsNew as $step) {
            if ($step->showConditions != "") {
                $conditions = json_decode($step->showConditions);
                foreach ($conditions as $condition) {
                    $oldStep = substr($condition->interaction, 0, strpos($condition->interaction, '_'));
                    $oldItem = substr($condition->interaction, strpos($condition->interaction, '_') + 1);
                    $condition->interaction = $stepsReplacement[$oldStep] . '_' . $itemsReplacement[$oldItem];
                }
                $wpdb->update($table_steps, array('showConditions' => $this->jsonRemoveUnicodeSequences($conditions)), array('id' => $step->id));
            }
        }
        $itemsNew = $wpdb->get_results("SELECT * FROM $table_items WHERE formID=$newFormID");
        foreach ($itemsNew as $item) {
            if ($item->showConditions != "") {
                $conditions = json_decode($item->showConditions);
                foreach ($conditions as $condition) {
                    $oldStep = substr($condition->interaction, 0, strpos($condition->interaction, '_'));
                    $oldItem = substr($condition->interaction, strpos($condition->interaction, '_') + 1);
                    $condition->interaction = $stepsReplacement[$oldStep] . '_' . $itemsReplacement[$oldItem];
                }
                $wpdb->update($table_items, array('showConditions' => $this->jsonRemoveUnicodeSequences($conditions)), array('id' => $item->id));
            }
            if ($item->calculation != "") {
                $lastPos = 0;
                echo $item->title;
                $toReplace = array();
                $replaceBy = array();
                while (($lastPos = strpos($item->calculation, 'item-', $lastPos)) !== false) {
                    $oldItem = substr($item->calculation, $lastPos + 5, (strpos($item->calculation, '_', $lastPos) - ($lastPos + 5)));                
                    $toReplace[] = $oldItem;
                    $replaceBy[] = $itemsReplacement[$oldItem];    
                    $lastPos = $lastPos + 5;
                }
                
                $i = 0;
                $newCalculation = $item->calculation;
                $currentIndex = 0;
                foreach ($replaceBy as $value) {
                    $newCalculation = str_replace($toReplace[$i], $replaceBy[$i], $newCalculation);                    
                    $i++;
                }                
                $wpdb->update($table_items, array('calculation' => $newCalculation), array('id' => $item->id));
            }
        }

        $links = $wpdb->get_results("SELECT * FROM $table_links WHERE formID=$formID");
        foreach ($links as $link) {
            unset($link->id);
            $link->originID = $stepsReplacement[$link->originID];
            $link->destinationID = $stepsReplacement[$link->destinationID];
            $link->formID = $newFormID;

            $conditions = json_decode($link->conditions);
            foreach ($conditions as $condition) {
                $oldStep = substr($condition->interaction, 0, strpos($condition->interaction, '_'));
                $oldItem = substr($condition->interaction, strpos($condition->interaction, '_') + 1);
                $condition->interaction = $stepsReplacement[$oldStep] . '_' . $itemsReplacement[$oldItem];
            }
            $wpdb->insert($table_links, array('operator' => $link->operator, 'conditions' => $this->jsonRemoveUnicodeSequences($conditions), 'originID' => $link->originID, 'destinationID' => $link->destinationID, 'formID' => $newFormID));
        }
        }

        die();
    }

    public function saveForm() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $table_name = $wpdb->prefix . "wpefc_forms";
            $formID = sanitize_text_field($_POST['formID']);
            $sqlDatas = array();
            foreach ($_POST as $key => $value) {
                if ($key != 'action' && $key != 'id' && $key != 'pll_ajax_backend' && $key != "undefined" && $key != "formID" && $key != "files" && $key != "client_action") {
                    if ($key == 'email_adminContent') {
                        $value = str_replace("../wp-content/", get_home_url() . '/wp-content/', $value);
                        $value = str_replace("../", get_home_url() . '/', $value);
                    }
                    if ($key == 'email_userContent') {
                        $value = str_replace("../wp-content/", get_home_url() . '/wp-content/', $value);
                        $value = str_replace("../", get_home_url() . '/', $value);
                    }
                    if ($key == 'percentToPay' && ($value == 0 || $value > 100)) {
                        $value = 100;
                    }

                    $sqlDatas[$key] = (stripslashes($value));
                }
            }
            if ($formID > 0) {
                $wpdb->update($table_name, $sqlDatas, array('id' => $formID));
                $response = $formID;
            } else {
                if (isset($_POST['title'])) {
                    $wpdb->insert($table_name, $sqlDatas);
                    $lastid = $wpdb->insert_id;
                    $response = $lastid;
                }
            }
            echo $response;
        }
        die();
    }

    public function removeForm() {
        global $wpdb;
        if (current_user_can('manage_options')) {
        $formID = sanitize_text_field($_POST['formID']);
        $table_name = $wpdb->prefix . "wpefc_forms";
        $wpdb->delete($table_name, array('id' => $formID));
        $table_name = $wpdb->prefix . "wpefc_steps";
        $wpdb->delete($table_name, array('formID' => $formID));
        $table_name = $wpdb->prefix . "wpefc_fields";
        $wpdb->delete($table_name, array('formID' => $formID));
        $table_name = $wpdb->prefix . "wpefc_items";
        $wpdb->delete($table_name, array('formID' => $formID));
        $table_name = $wpdb->prefix . "wpefc_coupons";
        $wpdb->delete($table_name, array('formID' => $formID));
        $table_name = $wpdb->prefix . "wpefc_logs";
        $wpdb->delete($table_name, array('formID' => $formID));
        }
        die();
    }

    public function checkFields() {
        global $wpdb;
        if (current_user_can('manage_options')) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $forms = $wpdb->get_results("SELECT * FROM $table_name");
        foreach ($forms as $form) {
            $table_nameF = $wpdb->prefix . "wpefc_fields";
            $fields = $wpdb->get_results("SELECT * FROM $table_nameF WHERE formID=" . $form->id);
            $table_nameI = $wpdb->prefix . "wpefc_items";
            $items = $wpdb->get_results("SELECT * FROM $table_nameI WHERE formID=" . $form->id . ' AND type="textfield"');
            $chk = false;
            $chkF = false;
            foreach ($fields as $field) {
                if ($field->typefield == 'input' && $field->validation == "email") {
                    $chk = true;
                }
            }
            foreach ($items as $item) {
                if ($item->fieldType == "email") {
                    $chkF = true;
                }
            }
            if (!$chk && !$chkF && !$form->save_to_cart) {
                $wpdb->insert($table_nameF, array('formID' => $form->id, 'validation' => "email", 'typefield' => "input", 'label' => "Email", 'isRequired' => 1));
            }
        }
        }
    }

    public function checkLicense() {
        global $wpdb;
        if (current_user_can('manage_options')) {
        try {
            $url = 'http://www.loopus-plugins.com/updates/update.php?checkCode=7818230&code=' . sanitize_text_field($_POST['code']);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $rep = curl_exec($ch);
            if ($rep != '0410') {
                $table_name = $wpdb->prefix . "wpefc_settings";
                $wpdb->update($table_name, array('purchaseCode' => sanitize_text_field($_POST['code'])), array('id' => 1));
            } else {
                echo '1';
            }
        } catch (Throwable $t) {
            $table_name = $wpdb->prefix . "wpefc_settings";
            $wpdb->update($table_name, array('purchaseCode' => sanitize_text_field($_POST['code'])), array('id' => 1));
        } catch (Exception $e) {
            $table_name = $wpdb->prefix . "wpefc_settings";
            $wpdb->update($table_name, array('purchaseCode' => sanitize_text_field($_POST['code'])), array('id' => 1));
        }
        }
        die();
    }

    public function loadSettings() {
        global $wpdb;
        if (current_user_can('manage_options')) {
        $table_name = $wpdb->prefix . "wpefc_settings";
        $settings = $wpdb->get_results("SELECT * FROM $table_name WHERE id=1 LIMIT 1");
        $settings = $settings[0];
        echo json_encode($settings);
        }
        die();
    }

    public function saveStepPosition() {
        global $wpdb;
        if (current_user_can('manage_options')) {
        $stepID = sanitize_text_field($_POST['stepID']);
        $posX = sanitize_text_field($_POST['posX']);
        $posY = sanitize_text_field($_POST['posY']);
        $table_name = $wpdb->prefix . "wpefc_steps";
        $step = $wpdb->get_results("SELECT * FROM $table_name WHERE id=" . $stepID . ' LIMIT 1');
        $step = $step[0];
        $content = json_decode($step->content);
        $content->previewPosX = $posX;
        $content->previewPosY = $posY;

        $wpdb->update($table_name, array('content' => stripslashes($this->jsonRemoveUnicodeSequences($content))), array('id' => $stepID));
        }
        die();
    }

    public function newLink() {
        global $wpdb;
        if (current_user_can('manage_options')) {
        $formID = sanitize_text_field($_POST['formID']);
        $originID = sanitize_text_field($_POST['originStepID']);
        $destinationID = sanitize_text_field($_POST['destinationStepID']);
        $table_name = $wpdb->prefix . "wpefc_links";
        $wpdb->insert($table_name, array('originID' => $originID, 'destinationID' => $destinationID, 'conditions' => '[]', 'formID' => $formID));
        echo $wpdb->insert_id;
        }
        die();
    }

    public function loadForm() {
        global $wpdb;
        if (current_user_can('manage_options')) {
        $formID = sanitize_text_field($_POST['formID']);
        $rep = new stdClass();
        $rep->steps = array();

        $table_name = $wpdb->prefix . "wpefc_forms";
        $forms = $wpdb->get_results("SELECT * FROM $table_name WHERE id=" . $formID);
        $rep->form = $forms[0];
        if (!$rep->form->colorBg || $rep->form->colorBg == "") {
            $rep->form->colorBg = "#ecf0f1";
        }

        $table_name = $wpdb->prefix . "wpefc_settings";
        $params = $wpdb->get_results("SELECT * FROM $table_name");
        $rep->params = $params[0];

        $table_name = $wpdb->prefix . "wpefc_steps";
        $steps = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID);
        foreach ($steps as $step) {
            $table_name = $wpdb->prefix . "wpefc_items";
            $items = $wpdb->get_results("SELECT * FROM $table_name WHERE stepID=" . $step->id . " ORDER BY ordersort ASC");
            $step->items = $items;
            $rep->steps[] = $step;
        }

        $table_name = $wpdb->prefix . "wpefc_links";
        $links = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID);
        $rep->links = $links;

        $table_name = $wpdb->prefix . "wpefc_fields";
        $fields = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID);
        $rep->fields = $fields;

        $table_name = $wpdb->prefix . "wpefc_coupons";
        $coupons = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID);
        $rep->coupons = $coupons;
        
        $table_name = $wpdb->prefix . "wpefc_redirConditions";
        $redirections = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID);
        $rep->redirections = $redirections;

        echo($this->jsonRemoveUnicodeSequences($rep));
        }
        die();
    }

    public function loadFields() {
        global $wpdb;
        if (current_user_can('manage_options')) {
        $formID = sanitize_text_field($_POST['formID']);
        $table_name = $wpdb->prefix . "wpefc_fields";
        $fields = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID . " ORDER BY ordersort ASC");
        echo($this->jsonRemoveUnicodeSequences($fields));
        }
        die();
    }

    public function removeField() {
        global $wpdb;
        if (current_user_can('manage_options')) {
        $table_name = $wpdb->prefix . "wpefc_fields";
        $fieldID = sanitize_text_field($_POST['fieldID']);
        $wpdb->delete($table_name, array('id' => $fieldID));
        }
        die();
    }

    public function saveField() {
        global $wpdb;
        if (current_user_can('manage_options')) {
        $table_name = $wpdb->prefix . "wpefc_fields";
        $fieldID = sanitize_text_field($_POST['id']);
        $formID = sanitize_text_field($_POST['formID']);
        $sqlDatas = array();
        foreach ($_POST as $key => $value) {
            if ($key != 'action' && $key != 'id' && $key != 'pll_ajax_backend') {
                $sqlDatas[$key] = sanitize_text_field(stripslashes($value));
            }
        }
        if ($fieldID > 0) {
            $wpdb->update($table_name, $sqlDatas, array('id' => $fieldID));
            $response = $_POST['id'];
        } else {
            $sqlDatas['formID'] = $formID;
            $wpdb->insert($table_name, $sqlDatas);
            $lastid = $wpdb->insert_id;
            $response = $lastid;
        }
        echo $response;
        }
        die();
    }
    
    public function saveRedirection(){
         global $wpdb;
        if (current_user_can('manage_options')) {
            $table_redirs = $wpdb->prefix . "wpefc_redirConditions";
            $id = sanitize_text_field($_POST['id']);
            $formID = sanitize_text_field($_POST['formID']);
            $conditions = sanitize_text_field($_POST['conditions']);
            $url = sanitize_text_field($_POST['url']);
            $conditionsOperator = sanitize_text_field($_POST['operator']);
            $table_name = $wpdb->prefix . "wpefc_redirections";
            
            $data = array('formID'=>$formID,'conditions'=>$conditions,'conditionsOperator'=>$conditionsOperator,'url'=>$url);
            if($id>0){
                $wpdb->update($table_redirs, $data, array('id' => $id));                
            }else {
                $wpdb->insert($table_redirs, $data);
                echo $wpdb->insert_id;
            }
        }
        die();
        
    }
    public function removeRedirection(){
     global $wpdb;
    if (current_user_can('manage_options')) {
        $table_redirs = $wpdb->prefix . "wpefc_redirConditions";
        $id = sanitize_text_field($_POST['id']);
        $wpdb->delete($table_redirs, array('id' => $id));
        
    }
    die();       
    
    }

    public function removeAllSteps() {
        global $wpdb;
        
        if (current_user_can('manage_options')) {
        $formID = sanitize_text_field($_POST['formID']);

        $table_name = $wpdb->prefix . "wpefc_steps";
        $steps = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID);
        foreach ($steps as $step) {
            $table_nameL = $wpdb->prefix . "wpefc_links";
            $wpdb->delete($table_nameL, array('originID' => $step->id));
            $wpdb->delete($table_nameL, array('destinationID' => $step->id));
        }

        $wpdb->delete($table_name, array('formID' => $formID));
        }
        die();
    }

    public function removeItem() {
        global $wpdb;
        
        if (current_user_can('manage_options')) {
        $formID = sanitize_text_field($_POST['formID']);
        $stepID = sanitize_text_field($_POST['stepID']);
        $itemID = sanitize_text_field($_POST['itemID']);

        $table_name = $wpdb->prefix . "wpefc_items";
        $wpdb->delete($table_name, array('id' => $itemID));


        $table_links = $wpdb->prefix . "wpefc_links";
        $links = $wpdb->get_results("SELECT * FROM $table_links WHERE formID=$formID");
        foreach ($links as $link) {
            // unset($link->id);

            $conditions = json_decode($link->conditions);
            $newConditions = array();

            foreach ($conditions as $condition) {
                $oldStep = substr($condition->interaction, 0, strpos($condition->interaction, '_'));
                $oldItem = substr($condition->interaction, strpos($condition->interaction, '_') + 1);
                if ($oldStep == $stepID && $oldItem == $itemID) {
                    
                } else {
                    $newConditions[] = $condition;
                }
            }
            $wpdb->update($table_links, array('conditions' => $this->jsonRemoveUnicodeSequences($newConditions)), array('id' => $link->id));
        }
        }
        die();
    }

    public function removeStep() {
        global $wpdb;
        if (current_user_can('manage_options')) {
        $table_name = $wpdb->prefix . "wpefc_steps";

        $wpdb->delete($table_name, array('id' => sanitize_text_field($_POST['stepID'])));
        $table_name = $wpdb->prefix . "wpefc_links";
        $wpdb->delete($table_name, array('originID' => sanitize_text_field($_POST['stepID'])));
        $wpdb->delete($table_name, array('destinationID' => sanitize_text_field($_POST['stepID'])));
        }
        die();
    }

    public function addStep() {
        global $wpdb;
        if (current_user_can('manage_options')) {
        $table_name = $wpdb->prefix . "wpefc_steps";
        $formID = sanitize_text_field($_POST['formID']);

        $data = new stdClass();
        $data->start = sanitize_text_field($_POST['start']);

        $stepsStart = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID . " AND start=1");
        if (count($stepsStart) == 0) {
            $data->start = 1;
        }

        if ($data->start == 1) {
            $steps = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID . " AND start=1");
            foreach ($steps as $step) {
                $dataContent = json_decode($step->content);
                $dataContent->start = 0;
                $wpdb->update($table_name, array('content' => $this->jsonRemoveUnicodeSequences($dataContent), 'start' => 0), array('id' => $data->id));
            }
        }
        $data->previewPosX = sanitize_text_field($_POST['previewPosX']);
        $data->previewPosY = sanitize_text_field($_POST['previewPosY']);
        $data->actions = array();



        $wpdb->insert($table_name, array('content' => $this->jsonRemoveUnicodeSequences($data), 'title' => __('My Step', 'lfb'), 'formID' => $formID, 'start' => $data->start));
        $data->id = $wpdb->insert_id;
        $wpdb->update($table_name, array('content' => $this->jsonRemoveUnicodeSequences($data), 'formID' => $formID), array('id' => $data->id));
        echo json_encode((array) $data);
        }
        die();
    }

    public function loadStep() {
        global $wpdb;
        if (current_user_can('manage_options')) {
        $rep = new stdClass();
        $table_name = $wpdb->prefix . "wpefc_steps";
        $step = $wpdb->get_results("SELECT * FROM $table_name WHERE id='" . sanitize_text_field($_POST['stepID']) . "' LIMIT 1");
        $rep->step = $step[0];
        $table_name = $wpdb->prefix . "wpefc_items";
        $items = $wpdb->get_results("SELECT * FROM $table_name WHERE stepID='" . sanitize_text_field($_POST['stepID']) . "' ORDER BY ordersort ASC");
        $rep->items = $items;
        echo $this->jsonRemoveUnicodeSequences((array) $rep);
        }
        die();
    }

    public function saveItem() {
        global $wpdb;
        if (current_user_can('manage_options')) {
            $formID = sanitize_text_field($_POST['formID']);
            $stepID = sanitize_text_field($_POST['stepID']);
            $itemID = sanitize_text_field($_POST['id']);

            $table_name = $wpdb->prefix . "wpefc_items";

            $sqlDatas = array();
            foreach ($_POST as $key => $value) {
                if ($key != 'action' && $key != 'id' && $key != 'pll_ajax_backend' && $key != "undefined" && $key != 'files') {
                    $sqlDatas[$key] = stripslashes($value);
                }
            }
            if ($itemID > 0) {
                $wpdb->update($table_name, $sqlDatas, array('id' => $itemID));
                $response = $_POST['id'];
            } else {
                $sqlDatas['formID'] = $formID;
                $sqlDatas['stepID'] = $stepID;
                $wpdb->insert($table_name, $sqlDatas);
                $itemID = $wpdb->insert_id;
            }
            echo $itemID;
            
        }
        die();
    }

    public function saveStep() {
        global $wpdb;
        if (current_user_can('manage_options')) {
            $formID = sanitize_text_field($_POST['formID']);
            $stepID = sanitize_text_field($_POST['id']);
            $table_name = $wpdb->prefix . "wpefc_steps";

            $sqlDatas = array();
            foreach ($_POST as $key => $value) {
                if ($key != 'action' && $key != 'id' && $key != 'pll_ajax_backend') {
                    $sqlDatas[$key] = (stripslashes($value));
                }
            }

            if ($stepID > 0) {
                $wpdb->update($table_name, $sqlDatas, array('id' => $stepID));
                $response = sanitize_text_field($_POST['id']);
            } else {
                $sqlDatas['formID'] = $formID;
                $wpdb->insert($table_name, $sqlDatas);
                $stepID = $wpdb->insert_id;
            }
            echo $stepID;
        }
        die();
    }

    public function exportLogs() {
        global $wpdb;
        if (current_user_can('manage_options')) {
            $formID = sanitize_text_field($_POST['formID']);
            $table_name = $wpdb->prefix . "wpefc_logs";
            $logs = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID . " ORDER BY id ASC");
            if (!is_dir(plugin_dir_path(__FILE__) . '../tmp')) {
                mkdir(plugin_dir_path(__FILE__) . '../tmp');
                chmod(plugin_dir_path(__FILE__) . '../tmp', 0747);
            }
            $random = rand(1000, 100000);
            $filename = 'export_csv_' . $random . '.csv';
            $target_path = plugin_dir_path(__FILE__) . '../tmp/' . $filename;
            $file = fopen($target_path, "w");

            $content = __('Date', 'lfb') . ';' .
                    __('Form', 'lfb') . ';' .
                    __('Total price', 'lfb') . ';' .
                    __('Total Subscription', 'lfb') . ';' .
                    __('Frequency of subscription', 'lfb') . ';' .
                    __('Reference', 'lfb') . ';' .
                    __('Order', 'lfb') . ';' .
                    __('Email', 'lfb') . ';' .
                    __('First name', 'lfb') . ';' .
                    __('Last name', 'lfb') . ';' .
                    __('Country', 'lfb') . ';' .
                    __('State', 'lfb') . ';' .
                    __('City', 'lfb') . ';' .
                    __('Zip code', 'lfb') . ';' .
                    __('Address', 'lfb') . ';';

            fwrite($file, $content . "\n");

            foreach ($logs as $log) {
                $verifiedPayment = __('No', 'lfb');
                if ($log->checked) {
                    $verifiedPayment = __('Yes', 'lfb');
                }
                $contentTxt = str_replace('[n]', "\r\n", $log->contentTxt);
                $contentTxt = "\"$contentTxt\"";
                $content = $log->dateLog . ';' . $log->formTitle . ';' . number_format($log->totalPrice, 2) . ';' . number_format($log->totalSubscription, 2) . ';' . $log->subscriptionFrequency . ';' .
                        $log->ref . ';' .
                        $contentTxt . ';' .
                        $log->email . ';' .
                        $log->firstName . ';' .
                        $log->lastName . ';' .
                        $log->country . ';' .
                        $log->state . ';' .
                        $log->city . ';' .
                        $log->zip . ';' .
                        $log->address . ';';
                fwrite($file, $content . "\n");
            }
            fclose($file);
            echo $this->parent->assets_url . '../tmp/' . $filename;
            die();
        }
    }

    public function changePreviewHeight() {
        global $wpdb;
        $height = sanitize_text_field($_POST['height']);
        $table_name = $wpdb->prefix . "wpefc_settings";
        $wpdb->update($table_name, array('previewHeight' => $height), array('id' => 1));
        die();
    }

    public function saveLinks() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $formID = sanitize_text_field($_POST['formID']);
            $table_name = $wpdb->prefix . "wpefc_links";
            if (substr(sanitize_text_field($_POST['links']), 0, 1) == '[' && $formID != "") {
                $links = json_decode(stripslashes($_POST['links']));

                $existingLinks = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $formID);
                if (count($existingLinks) > 1 && count($links) == 0) {
                    
                } else {
                    $wpdb->query("DELETE FROM $table_name WHERE formID=" . $formID . " AND id>0");

                    foreach ($links as $link) {
                        if (isset($link->destinationID) && $link->destinationID > 0) {
                            $wpdb->insert($table_name, array('formID' => $formID, 'operator' => $link->operator, 'originID' => $link->originID, 'destinationID' => $link->destinationID, 'conditions' => $this->jsonRemoveUnicodeSequences($link->conditions)));
                        }
                    }
                }
            }
            echo '1';
            die();
        }
    }

    public function importForms() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $displayForm = true;
            $settings = $this->getSettings();
            $code = $settings->purchaseCode;
            if (isset($_FILES['importFile'])) {
                $error = false;
                if (!is_dir(plugin_dir_path(__FILE__) . '../tmp')) {
                    mkdir(plugin_dir_path(__FILE__) . '../tmp');
                    chmod(plugin_dir_path(__FILE__) . '../tmp', 0747);
                }
                $target_path = plugin_dir_path(__FILE__) . '../tmp/export_estimation_form.zip';
                if (@move_uploaded_file($_FILES['importFile']['tmp_name'], $target_path)) {


                    $upload_dir = wp_upload_dir();
                    if (!is_dir($upload_dir['path'])) {
                        mkdir($upload_dir['path']);
                    }

                    $zip = new ZipArchive;
                    $res = $zip->open($target_path);
                    if ($res === TRUE) {
                        $zip->extractTo(plugin_dir_path(__FILE__) . '../tmp/');
                        $zip->close();

                        $formsData = array();

                        $jsonfilename = 'export_estimation_form.json';
                        if (!file_exists(plugin_dir_path(__FILE__) . '../tmp/export_estimation_form.json')) {
                            $jsonfilename = 'export_estimation_form';
                        }

                        $file = file_get_contents(plugin_dir_path(__FILE__) . '../tmp/' . $jsonfilename);
                        $dataJson = json_decode($file, true);

                        $table_name = $wpdb->prefix . "wpefc_settings";
                        $wpdb->query("TRUNCATE TABLE $table_name");
                        $value = $dataJson['settings'][0];
                        if (array_key_exists('intro_title', $value)) {
                            foreach ($value as $keyV => $valueV) {
                                if ($keyV != 'colorA' && $keyV != 'colorB' && $keyV != 'colorC' && $keyV != 'item_pictures_size') {
                                    $formsData[$keyV] = $valueV;
                                }
                            }
                        }
                        if (!array_key_exists('colorC', $value)) {
                            $value['colorC'] = '#bdc3c7';
                        }
                        if (!array_key_exists('useRedirectionConditions', $value)) {
                            $value['useRedirectionConditions'] = 0;
                        }
                        if (!array_key_exists('redirectionDelay', $value)) {
                            $value['redirectionDelay'] = 5;
                        }
                        
                        $previewHeight = 300;
                        if (isset($value['previewHeight']) && $value['previewHeight'] > 0) {
                            $previewHeight = $value['previewHeight'];
                        }

                        $wpdb->insert($table_name, array('previewHeight' => $previewHeight, 'purchaseCode' => $code));

                        $table_name = $wpdb->prefix . "wpefc_forms";
                        $wpdb->query("TRUNCATE TABLE $table_name");
                        if (array_key_exists('forms', $dataJson)) {
                            foreach ($dataJson['forms'] as $key => $value) {
                                if (!array_key_exists('email_adminContent', $value)) {
                                    $value['email_adminContent'] = '<p>Ref: <strong>[ref]</strong></p><h2 style="color: #008080;">Information</h2><hr/><span style="font-weight: 600; color: #444444;">[information_content]</span><span style="color: #444444;"> </span><hr/><h2 style="color: #008080;">Project</h2><hr/>[project_content]<hr/><h4>Total: <strong><span style="color: #444444;">[total_price]</span></strong></h4>';
                                    $value['email_userContent'] = '<p>Ref: <strong>[ref]</strong></p><h2 style="color: #008080;">Information</h2><hr/><span style="font-weight: 600; color: #444444;">[information_content]</span><span style="color: #444444;"> </span><hr/><h2 style="color: #008080;">Project</h2><hr/>[project_content]<hr/><h4>Total: <strong><span style="color: #444444;">[total_price]</span></strong></h4>';
                                }
                                if($value['summary_hideQt'] == null){
                                    $value['summary_hideQt'] = 0;
                                }
                                if($value['summary_hideZero'] == null){
                                    $value['summary_hideZero'] = 0;
                                }
                                if($value['summary_hidePrices'] == null){
                                    $value['summary_hidePrices'] = 0;
                                }
                                if($value['groupAutoClick'] == null){
                                    $value['groupAutoClick'] = 0;
                                }
                                
                                if (array_key_exists('form_page_id', $value)) {
                                    unset($value['form_page_id']);
                                }
                                
                                $wpdb->insert($table_name, $value);
                            }
                        }


                        $table_name = $wpdb->prefix . "wpefc_steps";
                        $wpdb->query("TRUNCATE TABLE $table_name");
                        $prevPosX = 40;
                        $firstStep = false;
                        foreach ($dataJson['steps'] as $key => $value) {
                            if (!array_key_exists('formID', $value)) {
                                $value['formID'] = 1;
                            }
                            if (!array_key_exists('showInSummary', $value)) {
                                $value['showInSummary'] = 1;
                            }
                            if (!array_key_exists('content', $value)) {
                                $start = 0;
                                if (!$firstStep && $value['ordersort'] == 0) {
                                    $start = 1;
                                    $value['start'] = 1;
                                    $firstStep = true;
                                }
                                $value['content'] = '{"start":"' . $start . '","previewPosX":"' . $prevPosX . '","previewPosY":"140","actions":[],"id":' . $value['id'] . '}';
                                $prevPosX += 200;
                            }
                            $wpdb->insert($table_name, $value);
                        }

                        $table_name = $wpdb->prefix . "wpefc_fields";
                        $wpdb->query("TRUNCATE TABLE $table_name");
                        if (array_key_exists('fields', $dataJson)) {
                            foreach ($dataJson['fields'] as $key => $value) {
                                if (!array_key_exists('validation', $value) && $value['id'] == '1') {
                                    $value['validation'] = 'email';
                                }
                                if (array_key_exists('height', $value)) {
                                    unset($value['height']);
                                }
                                $wpdb->insert($table_name, $value);
                            }
                        }

                        $table_name = $wpdb->prefix . "wpefc_links";
                        $wpdb->query("TRUNCATE TABLE $table_name");
                        if (array_key_exists('links', $dataJson)) {
                            foreach ($dataJson['links'] as $key => $value) {
                                $wpdb->insert($table_name, $value);
                            }
                        }

                        $table_name = $wpdb->prefix . "wpefc_logs";
                        $wpdb->query("TRUNCATE TABLE $table_name");
                        if (array_key_exists('logs', $dataJson)) {
                            foreach ($dataJson['logs'] as $key => $value) {
                                $wpdb->insert($table_name, $value);
                            }
                        }


                        $table_name = $wpdb->prefix . "wpefc_coupons";
                        $wpdb->query("TRUNCATE TABLE $table_name");
                        if (array_key_exists('coupons', $dataJson)) {
                            foreach ($dataJson['coupons'] as $key => $value) {
                                $wpdb->insert($table_name, $value);
                            }
                        }
                        
                        $table_name = $wpdb->prefix . "wpefc_redirConditions";
                        $wpdb->query("TRUNCATE TABLE $table_name");
                        if (array_key_exists('redirections', $dataJson)) {
                            foreach ($dataJson['redirections'] as $key => $value) {
                                $wpdb->insert($table_name, $value);
                            }
                        }
                        
                        

                        // Check links
                        $table_name = $wpdb->prefix . "wpefc_forms";
                        $forms = $wpdb->get_results("SELECT * FROM $table_name");
                        foreach ($forms as $form) {
                            $table_name = $wpdb->prefix . "wpefc_links";
                            $links = $wpdb->get_results("SELECT * FROM $table_name WHERE formID=" . $form->id);
                            if (count($links) == 0) {

                                $stepStartID = 0;
                                $stepStart = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "wpefc_steps WHERE start=1 AND formID=" . $form->id);
                                if (count($stepStart) > 0) {
                                    $stepStart = $stepStart[0];
                                    $stepStartID = $stepStart->id;
                                }
                                $steps = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "wpefc_steps WHERE formID=" . $form->id . " AND start=0 ORDER BY ordersort ASC, id ASC");
                                $i = 0;
                                $prevStepID = 0;
                                foreach ($steps as $step) {
                                    if ($i == 0 && $stepStartID > 0) {
                                        $wpdb->insert($wpdb->prefix . "wpefc_links", array('originID' => $stepStartID, 'destinationID' => $step->id, 'formID' => $form->id, 'conditions' => '[]'));
                                    } else if ($i > 0 && $prevStepID > 0) {
                                        $wpdb->insert($wpdb->prefix . "wpefc_links", array('originID' => $prevStepID, 'destinationID' => $step->id, 'formID' => $form->id, 'conditions' => '[]'));
                                    }
                                    $prevStepID = $step->id;
                                    $i++;
                                }
                            }
                        }



                        $table_name = $wpdb->prefix . "wpefc_items";
                        $wpdb->query("TRUNCATE TABLE $table_name");
                        foreach ($dataJson['items'] as $key => $value) {

                            if ($value['image'] && $value['image'] != "") {
                                $img_name = substr($value['image'], strrpos($value['image'], '/') + 1);
                                $imagePath = substr($value['image'], 0, strrpos($value['image'], '/'));
                                if (!file_exists(site_url() . '/' . $value['image'])) {
                                    if (!is_dir($imagePath)) {
                                        $imagePath = wp_upload_dir();
                                        // mkdir($imagePath, 0747, true);
                                    }
                                    if (strrpos($value['image'], "uploads") === false) {
                                        $value['image'] = 'uploads' . $value['image'];
                                    }
                                    if (is_file(plugin_dir_path(__FILE__) . '../tmp/' . $img_name)) {
                                        copy(plugin_dir_path(__FILE__) . '../tmp/' . $img_name, $imagePath['basedir'] . $imagePath['subdir'] . '/' . $img_name);
                                    }
                                }
                                $value['image'] = $imagePath['url'] . '/' . $img_name;
                            }
                            if (array_key_exists('reduc_qt', $value)) {
                                unset($value['reduc_qt']);
                                unset($value['reduc_value']);
                            }

                            $wpdb->insert($table_name, $value);
                        }


                        // check if form exists
                        $table_name = $wpdb->prefix . "wpefc_forms";
                        $forms = $wpdb->get_results("SELECT * FROM $table_name LIMIT 1");
                        if (!$forms || count($forms) == 0) {
                            $formsData['title'] = 'My Estimation Form';
                            $wpdb->insert($table_name, $formsData);
                        }


                        $files = glob(plugin_dir_path(__FILE__) . '../tmp/*');
                        foreach ($files as $file) {
                            if (is_file($file))
                                unlink($file);
                        }
                    } else {
                        $error = true;
                    }
                } else {
                    $error = true;
                }
                if ($error) {
                    echo __('An error occurred during the transfer', 'lfb');
                    die();
                } else {
                    $displayForm = false;
                    echo 1;
                    die();
                }
            }
        }
    }

    public function exportForms() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            if (!is_dir(plugin_dir_path(__FILE__) . '../tmp')) {
                mkdir(plugin_dir_path(__FILE__) . '../tmp');
                chmod(plugin_dir_path(__FILE__) . '../tmp', 0747);
            }

            $destination = plugin_dir_path(__FILE__) . '../tmp/export_estimation_form.zip';
            if (file_exists($destination)) {
                unlink($destination);
            }
            $zip = new ZipArchive();
            if ($zip->open($destination, ZipArchive::CREATE) !== true) {
                return false;
            }

            $jsonExport = array();
            $table_name = $wpdb->prefix . "wpefc_settings";
            $settings = $this->getSettings();
            $settings->purchaseCode = "";
            $jsonExport['settings'] = array();
            $jsonExport['settings'][] = $settings;


            $table_name = $wpdb->prefix . "wpefc_forms";
            $forms = array();
            foreach ($wpdb->get_results("SELECT * FROM $table_name") as $key => $row) {
                $row->analyticsID = '';
                $forms[] = $row;
            }
            $jsonExport['forms'] = $forms;

            $table_name = $wpdb->prefix . "wpefc_logs";
            $logs = array();
            foreach ($wpdb->get_results("SELECT * FROM $table_name") as $key => $row) {
                $logs[] = $row;
            }
            $jsonExport['logs'] = $logs;

            $table_name = $wpdb->prefix . "wpefc_coupons";
            $coupons = array();
            foreach ($wpdb->get_results("SELECT * FROM $table_name") as $key => $row) {
                $coupons[] = $row;
            }
            $jsonExport['coupons'] = $coupons;

            $table_name = $wpdb->prefix . "wpefc_steps";
            $steps = array();
            foreach ($wpdb->get_results("SELECT * FROM $table_name") as $key => $row) {
                $steps[] = $row;
            }
            $jsonExport['steps'] = $steps;

            $table_name = $wpdb->prefix . "wpefc_fields";
            $steps = array();
            foreach ($wpdb->get_results("SELECT * FROM $table_name") as $key => $row) {
                $steps[] = $row;
            }
            $jsonExport['fields'] = $steps;

            $table_name = $wpdb->prefix . "wpefc_links";
            $steps = array();
            foreach ($wpdb->get_results("SELECT * FROM $table_name") as $key => $row) {
                $steps[] = $row;
            }
            $jsonExport['links'] = $steps;
            
            $table_name = $wpdb->prefix . "wpefc_redirConditions";
            $redirs = array();
            foreach ($wpdb->get_results("SELECT * FROM $table_name") as $key => $row) {
                $steps[] = $row;
            }
            $jsonExport['redirections'] = $redirs;
            
            


            $table_name = $wpdb->prefix . "wpefc_items";
            $items = array();
            foreach ($wpdb->get_results("SELECT * FROM $table_name") as $key => $row) {
                $items[] = $row;
                if ($row->image != "") {
                    $original_image = $row->image;
                    $upload_dir = wp_upload_dir();
                    $pos1 = strrpos($original_image, '/');
                    $pos2 = strrpos($row->image, '/', 0 - (strlen($row->image) - $pos1) - 1);
                    $pos3 = strrpos($row->image, '/', 0 - (strlen($row->image) - $pos2) - 1);
                    $row->image = substr($row->image, strlen(site_url()) + 1);
                    if (strrpos($row->image, "wp-content") > -1) {
                        $row->image = substr($row->image, strrpos($row->image, "wp-content") + 11);
                    }
                    if (substr($row->image, 0, 17) == '/uploads/uploads/') {
                        $row->image = substr($row->image, 9);
                    }
                    $zip->addfile($this->dir . "/../../" . $row->image, substr($original_image, $pos1 + 1));
                }
            }

            $jsonExport['items'] = $items;
            $fp = fopen(plugin_dir_path(__FILE__) . '../tmp/export_estimation_form.json', 'w');
            fwrite($fp, json_encode($jsonExport));
            fclose($fp);

            $zip->addfile(plugin_dir_path(__FILE__) . '../tmp/export_estimation_form.json', 'export_estimation_form.json');
            $zip->close();
            echo '1';
            die();
        }
    }

    public function removeAllCoupons() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $formID = sanitize_text_field($_POST['formID']);
            $table_name = $wpdb->prefix . "wpefc_coupons";
            $wpdb->delete($table_name, array('formID' => $formID));
        }
        die();
    }

    public function removeCoupon() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $couponID = sanitize_text_field($_POST['couponID']);
            $formID = sanitize_text_field($_POST['formID']);
            $table_name = $wpdb->prefix . "wpefc_coupons";
            $wpdb->delete($table_name, array('id' => $couponID));
        }
        die();
    }

    public function saveCoupon() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $table_name = $wpdb->prefix . "wpefc_coupons";
            $couponID = sanitize_text_field($_POST['couponID']);
            $formID = sanitize_text_field($_POST['formID']);
            $couponCode = sanitize_text_field($_POST['couponCode']);
            $useMax = sanitize_text_field($_POST['useMax']);
            $reduction = sanitize_text_field($_POST['reduction']);
            $reductionType = sanitize_text_field($_POST['reductionType']);

            if ($couponID > 0) {
                $wpdb->update($table_name, array('couponCode' => $couponCode, 'useMax' => $useMax, 'reduction' => $reduction, 'reductionType' => $reductionType), array('id' => $couponID));
                echo $couponID;
            } else {
                $wpdb->insert($table_name, array('couponCode' => $couponCode, 'useMax' => $useMax, 'reduction' => $reduction, 'reductionType' => $reductionType, 'formID' => $formID));
                echo $wpdb->insert_id;
            }
        }
        die();
    }
    
   
    /**
     * Main Instance
     *
     *
     * @since 1.0.0
     * @static
     * @return Main instance
     */
    public
    static function instance($parent) {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($parent);
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
        _doing_it_wrong(__FUNCTION__, __(''), $this->parent->_version);
    }

// End __clone()

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup() {
        _doing_it_wrong(__FUNCTION__, __(''), $this->parent->_version);
    }

// End __wakeup()
}
