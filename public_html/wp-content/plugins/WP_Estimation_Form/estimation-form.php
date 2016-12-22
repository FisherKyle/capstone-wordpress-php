<?php

/*
 * Plugin Name: WP Cost Estimation & Payment Forms Builder 
 * Version: 9.526
 *
 * Plugin URI: http://codecanyon.net/item/wp-cost-estimation-payment-forms-builder/7818230
 * Description: This plugin allows you to create easily flat visual forms of paypal payment/cost estimation.
 * Author: Biscay Charly (loopus)
 * Author URI: http://www.loopus-plugins.com/
 * Requires at least: 3.8
 * Tested up to: 4.6.1
 *
 * @package WordPress
 * @author Biscay Charly (loopus)
 * @since 1.0.0
 */

if (!defined('ABSPATH'))
    exit;

register_activation_hook(__FILE__, 'lfb_install');
register_uninstall_hook(__FILE__, 'lfb_uninstall');

global $jal_db_version;
$jal_db_version = "1.1";

if(!class_exists("Mailchimp",false)){
    require_once('includes/Mailchimp.php');    
}
require_once('includes/MailPoetList.php');
require_once('includes/GetResponseAPI.class.php');    
require_once('includes/lfb-core.php');
require_once('includes/lfb-admin.php');

function Estimation_Form() {
    update_option("lfb_themeMode",false);
    $version = 9.526;
    lfb_checkDBUpdates($version);
    $instance = LFB_Core::instance(__FILE__, $version);
    if (is_null($instance->menu)) {
        $instance->menu = LFB_admin::instance($instance);
    }

    return $instance;
}

/**
 * Installation. Runs on activation.
 * @access  public
 * @since   1.0.0
 * @return  void
 */
function lfb_install() {
    global $wpdb;
    global $jal_db_version;
    require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

    add_option("jal_db_version", $jal_db_version);

    $db_table_name = $wpdb->prefix . "wpefc_forms";
    if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

        $sql = "CREATE TABLE $db_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		title VARCHAR(120) NOT NULL,
                errorMessage VARCHAR(240) NOT NULL,
                intro_enabled BOOL,
                save_to_cart BOOL,
                use_paypal BOOL NOT NULL,
                paypal_email VARCHAR(250) NULL,
                paypal_currency VARCHAR(3) NOT NULL DEFAULT 'USD',
                paypal_useIpn BOOL,
                paypal_useSandbox BOOL,
                paypal_subsFrequency SMALLINT(5) NOT NULL DEFAULT 1,
                paypal_subsFrequencyType VARCHAR(1) NOT NULL DEFAULT 'M',
                paypal_subsMaxPayments SMALLINT(5) NOT NULL DEFAULT 0,
                use_stripe BOOL,
                stripe_useSandbox BOOL,
                stripe_secretKey VARCHAR(250) NOT NULL,
                stripe_publishKey VARCHAR(250) NOT NULL,
                stripe_currency VARCHAR(6) NOT NULL,
                stripe_subsFrequencyType VARCHAR(16) NOT NULL DEFAULT 'month',                
                isSubscription BOOL,
                subscription_text VARCHAR(250) NOT NULL DEFAULT '/month',
                close_url VARCHAR(250) NOT NULL DEFAULT '#',
                btn_step VARCHAR(120) NOT NULL,
                previous_step VARCHAR(120) NOT NULL,
                intro_title VARCHAR(120) NOT NULL,
                intro_text TEXT NOT NULL,
                intro_btn VARCHAR(120) NOT NULL,
                last_title VARCHAR(120) NOT NULL,
                last_text TEXT NOT NULL,
                last_btn VARCHAR(120) NOT NULL,
                last_msg_label VARCHAR(240) NOT NULL,
                initial_price FLOAT NOT NULL,
                max_price FLOAT NOT NULL,
                succeed_text TEXT NOT NULL,
                email VARCHAR(250) NOT NULL,
                email_adminContent TEXT NOT NULL,
                email_subject VARCHAR(250) NOT NULL,
                email_toUser BOOL NOT NULL,
                email_userSubject VARCHAR(250) NOT NULL,
                email_userContent TEXT NOT NULL,
                currency VARCHAR (32) NOT NULL,
                currencyPosition VARCHAR (32) NOT NULL,
                gravityFormID INT(9) NOT NULL,
                animationsSpeed FLOAT NOT NULL DEFAULT 0.5,
                showSteps SMALLINT(5) NOT NULL,
                qtType SMALLINT(9) NOT NULL,
                show_initialPrice BOOL NOT NULL,
                ref_root VARCHAR(16) NOT NULL DEFAULT 'A000',
                current_ref INT(9) NOT NULL DEFAULT 1,
                colorA VARCHAR(16) NOT NULL,
                colorB VARCHAR(16) NOT NULL,
                colorC VARCHAR(16) NOT NULL,
                colorBg VARCHAR(16) NOT NULL,
                item_pictures_size SMALLINT(9) NOT NULL,
                hideFinalPrice BOOL NOT NULL DEFAULT 0,
                priceFontSize SMALLINT NOT NULL DEFAULT 18,
                customCss TEXT NOT NULL,
                disableTipMobile BOOL NOT NULL,
                legalNoticeContent TEXT NOT NULL,
                legalNoticeTitle TEXT NOT NULL,
                legalNoticeEnable BOOL NOT NULL,
                datepickerLang VARCHAR(16)  NOT NULL,
         	percentToPay FLOAT DEFAULT 100,
                thousandsSeparator VARCHAR(4) NOT NULL,
                decimalsSeparator VARCHAR(4) NOT NULL,
                millionSeparator VARCHAR(4) NOT NULL,
                useSummary BOOL NOT NULL,
                summary_title VARCHAR(240) NOT NULL,
                summary_description VARCHAR(240) NOT NULL,
                summary_quantity VARCHAR(240) NOT NULL,
                summary_price VARCHAR(240) NOT NULL,
                summary_total VARCHAR(240) NOT NULL,
                summary_value VARCHAR(240) NOT NULL,
                summary_discount VARCHAR(240) NOT NULL DEFAULT 'Discount :',
                summary_hideQt BOOL,
                summary_hideZero BOOL,
                summary_hidePrices BOOL,
                groupAutoClick BOOL,
                useCoupons BOOL NOT NULL,
                inverseGrayFx BOOL NOT NULL,                
                couponText VARCHAR(250) NOT NULL DEFAULT 'Discount coupon code',
                useMailchimp BOOL NOT NULL,
                mailchimpKey VARCHAR(250) NOT NULL,
                mailchimpList VARCHAR(250) NOT NULL,
                mailchimpOptin BOOL NOT NULL,
                useMailpoet BOOL NOT NULL,
                mailPoetList VARCHAR(250) NOT NULL,
                useGetResponse BOOL NOT NULL,
                getResponseKey VARCHAR(250) NOT NULL,
                getResponseList VARCHAR(250) NOT NULL,
                loadAllPages BOOL NOT NULL,
                filesUpload_text VARCHAR(250) NOT NULL DEFAULT 'Drop files here to upload', 
                filesUploadSize_text VARCHAR(250) NOT NULL DEFAULT 'File is too big (max size: {{maxFilesize}}MB)', 
                filesUploadType_text VARCHAR(250) NOT NULL DEFAULT 'Invalid file type',          
                filesUploadLimit_text VARCHAR(250) NOT NULL DEFAULT 'You can not upload any more files',
                useGoogleFont BOOL NOT NULL DEFAULT 1,
                googleFontName VARCHAR(250) NOT NULL DEFAULT 'Lato',
                analyticsID VARCHAR(250) NOT NULL,
                sendPdfCustomer BOOL NOT NULL, 
                sendPdfAdmin BOOL NOT NULL, 
                sendContactASAP BOOL NOT NULL,
                showTotalBottom BOOL NOT NULL,
                stripe_label_creditCard VARCHAR(250) NOT NULL,
                stripe_label_cvc VARCHAR(250) NOT NULL,
                stripe_label_expiration VARCHAR(250) NOT NULL,    
                scrollTopMargin INT(9) NOT NULL,
                redirectionDelay INT(9) NOT NULL DEFAULT 5,
                useRedirectionConditions BOOL NOT NULL DEFAULT 0,
                gmap_key VARCHAR(250) NOT NULL,
                txtDistanceError TEXT NOT NULL,
                customJS TEXT NOT NULL,
                
		UNIQUE KEY id (id)
		) $charset_collate;";
        dbDelta($sql);
    }
    
    $db_table_name = $wpdb->prefix . "wpefc_steps";
    if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

        $sql = "CREATE TABLE $db_table_name (
    		id mediumint(9) NOT NULL AUTO_INCREMENT,
    		formID mediumint (9) NOT NULL,
    		start BOOL  NOT NULL DEFAULT 0,
    		title VARCHAR(120) NOT NULL,
    		content TEXT NOT NULL,
    		ordersort mediumint(9) NOT NULL,
    		itemRequired BOOL  NOT NULL DEFAULT 0,
    		itemDepend SMALLINT(5) NOT NULL,
    		interactions TEXT NOT NULL,
    		description TEXT NOT NULL,
    		showInSummary BOOL  NOT NULL DEFAULT 1,
                itemsPerRow TINYINT(2) NOT NULL,
                useShowConditions BOOL NOT NULL,
                showConditions TEXT NOT NULL,
                showConditionsOperator VARCHAR(8) NOT NULL,
                hideNextStepBtn  BOOL NOT NULL,
    		UNIQUE KEY id (id)
    		) $charset_collate;";
        dbDelta($sql);
    }

    $db_table_name = $wpdb->prefix . "wpefc_logs";
    if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

        $sql = "CREATE TABLE $db_table_name (
    		id mediumint(9) NOT NULL AUTO_INCREMENT,
    		formID mediumint (9) NOT NULL,
    		ref VARCHAR(120) NOT NULL,
    		email VARCHAR(250) NOT NULL,
    		content MEDIUMTEXT NOT NULL,
                contentUser MEDIUMTEXT NOT NULL,
                contentTxt MEDIUMTEXT NOT NULL,
                dateLog VARCHAR(64) NOT NULL,
                sendToUser BOOL,
                checked BOOL,
                phone VARCHAR(120) NOT NULL,
                firstName VARCHAR(250) NOT NULL,
                lastName VARCHAR(250) NOT NULL,
                address TEXT NOT NULL,
                city VARCHAR(250) NOT NULL,
                country VARCHAR(250) NOT NULL,
                state VARCHAR(250) NOT NULL,
                zip VARCHAR(128) NOT NULL,
                totalPrice FLOAT NOT NULL,
                totalSubscription FLOAT NOT NULL,
                subscriptionFrequency VARCHAR(64) NOT NULL,
                formTitle VARCHAR(250) NOT NULL,
    		UNIQUE KEY id (id)
    		) $charset_collate;";
        dbDelta($sql);
    }

    $db_table_name = $wpdb->prefix . "wpefc_items";
    if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

        $sql = "CREATE TABLE $db_table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                title VARCHAR(120) NOT NULL,
                 description TEXT NOT NULL,
                ordersort mediumint(9) NOT NULL,
                image VARCHAR(250) NOT NULL,
                imageDes VARCHAR(250) NOT NULL,
                groupitems VARCHAR(120) NOT NULL,
                type VARCHAR(120) NOT NULL,
                stepID mediumint(9) NOT NULL,
                formID mediumint(9) NOT NULL,
                 price FLOAT NOT NULL,
                 operation VARCHAR(1) NOT NULL DEFAULT '+',
                 ischecked BOOL,
                 isRequired BOOL,
                 quantity_enabled BOOL,
                 quantity_max INT(11)  NOT NULL,
                 quantity_min INT(11)  NOT NULL,
                 reduc_enabled BOOL NOT NULL,
                 reduc_qt SMALLINT(5) NOT NULL,
                 reduc_value FLOAT NOT NULL,
                 reducsQt LONGTEXT NOT NULL,
                 isWooLinked BOOL,
                 wooProductID SMALLINT(5)  NOT NULL,
                 wooVariation SMALLINT(9)  NOT NULL,
                 imageTint BOOL,
                 showPrice BOOL,
                 useRow BOOL NOT NULL,
                 optionsValues TEXT NOT NULL,
                 urlTarget VARCHAR(250) NOT NULL,
                 showInSummary BOOL DEFAULT 1,
                 richtext TEXT NOT NULL,
                 isHidden BOOL NOT NULL,
                 minSize INT(11) NOT NULL,
                 maxSize INT(11) NOT NULL,
                 isNumeric BOOL NOT NULL,
                 isSinglePrice BOOL NOT NULL,
                 maxFiles SMALLINT(9) NOT NULL,
                 allowedFiles VARCHAR(250) NOT NULL DEFAULT '.png,.jpg,.jpeg,.gif,.zip,.rar',
                 useCalculation BOOL NOT NULL,
                 calculation TEXT NOT NULL,
                 fieldType VARCHAR(64) NOT NULL,
                 useShowConditions BOOL NOT NULL,
                 showConditions TEXT NOT NULL,
                 showConditionsOperator VARCHAR(8) NOT NULL,
                 usePaypalIfChecked BOOL NOT NULL,
                 useDistanceAsQt BOOL NOT NULL,
                 distanceQt VARCHAR(250) NOT NULL,
                 hideQtSummary BOOL NOT NULL,
                 defaultValue TEXT NOT NULL,
  		UNIQUE KEY id (id)
		) $charset_collate;";
        dbDelta($sql);
    }

    $db_table_name = $wpdb->prefix . "wpefc_links";
    if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

        $sql = "CREATE TABLE $db_table_name (
    		id mediumint(9) NOT NULL AUTO_INCREMENT,
    		formID mediumint (9) NOT NULL,
    		originID INT(9) NOT NULL,
    		destinationID INT(9) NOT NULL,
    		conditions TEXT NOT NULL,
                operator VARCHAR(8) NOT NULL,
    		UNIQUE KEY id (id)
    		) $charset_collate;";
        dbDelta($sql);
    }


    $db_table_name = $wpdb->prefix . "wpefc_fields";
    if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

        $sql = "CREATE TABLE $db_table_name (
    		    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    formID SMALLINT(5) NOT NULL,
    		    label VARCHAR(120) NOT NULL,
    		    ordersort mediumint(9) NOT NULL,
    		    isRequired BOOL,
    		    typefield VARCHAR(32) NOT NULL,
    		    visibility VARCHAR(32) NOT NULL,
                    validation VARCHAR(64) NOT NULL,
                     fieldType VARCHAR(64) NOT NULL,
    		UNIQUE KEY id (id)
    		) $charset_collate;";
        dbDelta($sql);
    }

    $db_table_name = $wpdb->prefix . "wpefc_settings";
    if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

        $sql = "CREATE TABLE $db_table_name (
  		id mediumint(9) NOT NULL AUTO_INCREMENT,
  		purchaseCode VARCHAR(250) NOT NULL,
  		previewHeight SMALLINT(5) NOT NULL DEFAULT 300,
  		UNIQUE KEY id (id)
  		) $charset_collate;";
        dbDelta($sql);
        $rows_affected = $wpdb->insert($db_table_name, array('previewHeight' => 300));
    }
    
    $db_table_name = $wpdb->prefix . "wpefc_coupons";
    if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

        $sql = "CREATE TABLE $db_table_name (
  		id mediumint(9) NOT NULL AUTO_INCREMENT,
                formID mediumint(9) NOT NULL,
  		couponCode VARCHAR(250) NOT NULL,
  		reduction FLOAT NOT NULL,
                reductionType VARCHAR(64) NOT NULL,
                useMax SMALLINT(5) NOT NULL DEFAULT 1,
                currentUses SMALLINT(5) NOT NULL,
  		UNIQUE KEY id (id)
  		) $charset_collate;";
        dbDelta($sql);
    }
    
    $db_table_name = $wpdb->prefix . "wpefc_redirConditions";
    if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

        $sql = "CREATE TABLE $db_table_name (
    		id mediumint(9) NOT NULL AUTO_INCREMENT,
    		formID mediumint (9) NOT NULL,    		
    		conditions TEXT NOT NULL,
                conditionsOperator VARCHAR(4) NOT NULL DEFAULT '+',
                url VARCHAR(250) NOT NULL,
    		UNIQUE KEY id (id)
    		) $charset_collate;";
        dbDelta($sql);
    }
    
    

    global $isInstalled;
    $isInstalled = true;
}
// End install()

function lfb_setThemeMode(){
    update_option("lfb_themeMode",true);
}

/**
 * Update database
 * @access  public
 * @since   2.0
 * @return  void
 */
function lfb_checkDBUpdates($version) {
    global $wpdb;
    $installed_ver = get_option("wpecf_version");
    require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

    if (!$installed_ver || $installed_ver < 8.5) {
        $db_table_name = $wpdb->prefix . "lfb_items";
        if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") == $db_table_name) {
            $sql = "RENAME TABLE " . $db_table_name . " TO " . $wpdb->prefix . "wpefc_items;";
            $wpdb->query($sql);
        } else {
            $db_table_name = $wpdb->prefix . "wpefc_items";
            if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
                if (!empty($wpdb->charset))
                    $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
                if (!empty($wpdb->collate))
                    $charset_collate .= " COLLATE $wpdb->collate";

                $sql = "CREATE TABLE $db_table_name (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    title VARCHAR(120) NOT NULL,
                    description TEXT NOT NULL,
                    ordersort mediumint(9) NOT NULL,
                    image VARCHAR(250) NOT NULL,
                    groupitems VARCHAR(120) NOT NULL,
                    type VARCHAR(120) NOT NULL,
                    stepID mediumint(9) NOT NULL,
                    formID mediumint(9) NOT NULL,
                    price FLOAT NOT NULL,
                    operation VARCHAR(1) NOT NULL DEFAULT '+',
                    ischecked BOOL,
                    isRequired BOOL,
                    quantity_enabled BOOL,
                    quantity_max SMALLINT(5)  NOT NULL,
                    reduc_enabled BOOL NOT NULL,
                    reduc_qt SMALLINT(5) NOT NULL,
                    reduc_value FLOAT NOT NULL,
                    reducsQt TEXT NOT NULL,
                    isWooLinked BOOL,
                    wooProductID SMALLINT(5)  NOT NULL,
                    wooVariation SMALLINT(9)  NOT NULL,
                    imageTint BOOL,
                    showPrice BOOL NOT NULL,
                    useRow BOOL NOT NULL,
                    UNIQUE KEY id (id)
                    ) $charset_collate;";
                dbDelta($sql);
            }
        }
    }

    if (!$installed_ver || $installed_ver < 9.11) {
        $db_table_name = $wpdb->prefix . "wpefc_logs";
        if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
            if (!empty($wpdb->charset))
                $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
            if (!empty($wpdb->collate))
                $charset_collate .= " COLLATE $wpdb->collate";

            $sql = "CREATE TABLE $db_table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            formID mediumint (9) NOT NULL,
            ref VARCHAR(120) NOT NULL,
            email VARCHAR(120) NOT NULL,
            content TEXT NOT NULL,
            dateLog VARCHAR(64) NOT NULL,
            UNIQUE KEY id (id)
            ) $charset_collate;";
            dbDelta($sql);
        }
    }

    if (!$installed_ver || $installed_ver < 9.14) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD  hideFinalPrice BOOL DEFAULT 0;";
        $wpdb->query($sql);
    }

    if (!$installed_ver || $installed_ver < 9.15) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD  priceFontSize SMALLINT NOT NULL DEFAULT 18;";
        $wpdb->query($sql);
    }


    if (!$installed_ver || $installed_ver < 9.182) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD  customCss TEXT NOT NULL;";
        $wpdb->query($sql);
        $table_name = $wpdb->prefix . "wpefc_items";
        $sql = "ALTER TABLE " . $table_name . " ADD  optionsValues TEXT NOT NULL;";
        $wpdb->query($sql);
    }

    if (!$installed_ver || $installed_ver < 9.186) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD disableTipMobile BOOL NOT NULL;";
        $wpdb->query($sql);
    }
    if (!$installed_ver || $installed_ver < 9.187) {
        $table_name = $wpdb->prefix . "wpefc_items";
        $sql = "ALTER TABLE " . $table_name . " ADD quantity_min SMALLINT(5)  NOT NULL;";
        $wpdb->query($sql);

        $sql = "ALTER TABLE " . $table_name . " MODIFY COLUMN wooProductID mediumint(9) NOT NULL;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " MODIFY COLUMN wooVariation mediumint(9) NOT NULL;";
        $wpdb->query($sql);
    }
    if (!$installed_ver || $installed_ver < 9.193) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD legalNoticeContent TEXT NOT NULL;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD legalNoticeTitle TEXT NOT NULL;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD legalNoticeEnable BOOL NOT NULL;";
        $wpdb->query($sql);
    }
    if (!$installed_ver || $installed_ver < 9.195) {
        $table_name = $wpdb->prefix . "wpefc_items";
        $sql = "ALTER TABLE " . $table_name . " ADD urlTarget VARCHAR(250)  NOT NULL;";
        $wpdb->query($sql);
    }

    if (!$installed_ver || $installed_ver < 9.21) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD datepickerLang VARCHAR(16)  NOT NULL;";
        $wpdb->query($sql);
    }
    if (!$installed_ver || $installed_ver < 9.24) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD percentToPay FLOAT DEFAULT 100 ;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD  colorBg VARCHAR(16) NOT NULL;";
        $wpdb->query($sql);


        $forms = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");
        foreach ($forms as $form) {
            $wpdb->update($table_name, array('percentToPay' => 100), array('id' => $form->id));
        }
        mkdir('uploads');
        chmod("uploads", 0747);
    }
    if (!$installed_ver || $installed_ver < 9.34) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD thousandsSeparator VARCHAR(4) NOT NULL ;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD decimalsSeparator VARCHAR(4) NOT NULL ;";
        $wpdb->query($sql);
    }

    if (!$installed_ver || $installed_ver < 9.35) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD useSummary BOOL NOT NULL;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD summary_title VARCHAR(240) NOT NULL;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD summary_description VARCHAR(240) NOT NULL;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD summary_quantity VARCHAR(240) NOT NULL;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD summary_price VARCHAR(240) NOT NULL;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD summary_total VARCHAR(240) NOT NULL;";
        $wpdb->query($sql);
    }
    if (!$installed_ver || $installed_ver < 9.370) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_name = '" . $table_name . "' AND column_name = 'qtType'");

        if (empty($row)) {
            $sql = "ALTER TABLE " . $table_name . " MODIFY COLUMN qtType SMALLINT(5) NOT NULL;";
            $wpdb->query($sql);
        }
        $table_name = $wpdb->prefix . "wpefc_steps";
        $sql = "ALTER TABLE " . $table_name . " ADD description TEXT NOT NULL;";
        $wpdb->query($sql);
    }
    if (!$installed_ver || $installed_ver < 9.382) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD summary_value VARCHAR(240) NOT NULL;";
        $wpdb->query($sql);
    }
    if (!$installed_ver || $installed_ver < 9.385) {
        $table_name = $wpdb->prefix . "wpefc_items";
        $sql = "ALTER TABLE " . $table_name . " ADD showInSummary BOOL DEFAULT 1;";
        $wpdb->query($sql);
    }
    if (!$installed_ver || $installed_ver < 9.386) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD paypal_useIpn BOOL DEFAULT 0;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD paypal_useSandbox BOOL DEFAULT 0;";
        $wpdb->query($sql);        
        $sql = "ALTER TABLE " . $table_name . " ADD paypal_subsFrequency SMALLINT(5) NOT NULL DEFAULT 1;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD paypal_subsFrequencyType VARCHAR(1) NOT NULL DEFAULT 'M';";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD paypal_subsMaxPayments SMALLINT(5) NOT NULL DEFAULT 0;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD isSubscription BOOL DEFAULT 0;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD subscription_text VARCHAR(250) NOT NULL DEFAULT '/month';";
        $wpdb->query($sql);
        
        $table_name = $wpdb->prefix . "wpefc_logs";
        $sql = "ALTER TABLE " . $table_name . " ADD sendToUser BOOL;";
        $wpdb->query($sql);        
        $sql = "ALTER TABLE " . $table_name . " ADD contentUser TEXT NOT NULL;";
        $wpdb->query($sql);        
        $sql = "ALTER TABLE " . $table_name . " ADD checked BOOL;";
        $wpdb->query($sql);     
    }
    if (!$installed_ver || $installed_ver < 9.394) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD summary_hideQt BOOL DEFAULT 0;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE " . $table_name . " ADD summary_hideZero BOOL DEFAULT 0;";
        $wpdb->query($sql); 
        $table_name = $wpdb->prefix . "wpefc_items";    
        $sql = "ALTER TABLE " . $table_name . " ADD richtext TEXT NOT NULL;";
        $wpdb->query($sql);    
    }
    if (!$installed_ver || $installed_ver < 9.396) {
        $table_name = $wpdb->prefix . "wpefc_steps";    
        $sql = "ALTER TABLE " . $table_name . " ADD showInSummary BOOL  NOT NULL DEFAULT 1;";
        $wpdb->query($sql);   
        
        $steps = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");
        foreach ($steps as $step) {
            $wpdb->update($table_name, array('showInSummary'=>true), array('id' => $step->id));            
        }
        
        $table_name = $wpdb->prefix . "wpefc_items";    
        $sql = "ALTER TABLE " . $table_name . " ADD imageDes VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);   
    }
    
    if (!$installed_ver || $installed_ver < 9.407) {
        $db_table_name = $wpdb->prefix . "wpefc_coupons";
        if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
            if (!empty($wpdb->charset))
                $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
            if (!empty($wpdb->collate))
                $charset_collate .= " COLLATE $wpdb->collate";

            $sql = "CREATE TABLE $db_table_name (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    formID mediumint(9) NOT NULL,
                    couponCode VARCHAR(250) NOT NULL,
                    reduction FLOAT NOT NULL,
                    reductionType VARCHAR(64) NOT NULL,
                    useMax SMALLINT(5) NOT NULL DEFAULT 1,
                    currentUses SMALLINT(5) NOT NULL,
                    UNIQUE KEY id (id)
                    ) $charset_collate;";
            dbDelta($sql);
        }
        
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD useCoupons BOOL NOT NULL;";
        $wpdb->query($sql);        
        $sql = "ALTER TABLE " . $table_name . " ADD couponText VARCHAR(250) NOT NULL DEFAULT 'Discount coupon code';";
        $wpdb->query($sql);        
        $sql = "ALTER TABLE " . $table_name . " ADD summary_discount VARCHAR(240) NOT NULL DEFAULT 'Discount :';";
        $wpdb->query($sql);
        
        $forms = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");
        foreach ($forms as $form) {
            $wpdb->update($table_name, array('summary_discount' => 'Discount :'), array('id' => $form->id));
        }
                        
    }
    
    if (!$installed_ver || $installed_ver < 9.410) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD summary_hidePrices BOOL NOT NULL;";
        $wpdb->query($sql);   
        $sql = "ALTER TABLE " . $table_name . " ADD groupAutoClick BOOL NOT NULL;";
        $wpdb->query($sql);   
        $sql = "ALTER TABLE " . $table_name . " ADD inverseGrayFx BOOL NOT NULL;";
        $wpdb->query($sql);   
        $forms = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");
        foreach ($forms as $form) {
            $wpdb->update($table_name, array('groupAutoClick' => 1), array('id' => $form->id));
        }
    }
    if (!$installed_ver || $installed_ver < 9.412) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD useMailchimp BOOL NOT NULL;";
        $wpdb->query($sql);   
        $sql = "ALTER TABLE " . $table_name . " ADD mailchimpKey VARCHAR(250) NOT NULL;";
        $wpdb->query($sql); 
        $sql = "ALTER TABLE " . $table_name . " ADD mailchimpList VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);    
        $sql = "ALTER TABLE " . $table_name . " ADD useMailpoet BOOL NOT NULL;";
        $wpdb->query($sql);   
        $sql = "ALTER TABLE " . $table_name . " ADD mailPoetList VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);  
        $sql = "ALTER TABLE " . $table_name . " ADD useGetResponse BOOL NOT NULL;";
        $wpdb->query($sql);   
        $sql = "ALTER TABLE " . $table_name . " ADD getResponseKey VARCHAR(250) NOT NULL;";
        $wpdb->query($sql); 
        $sql = "ALTER TABLE " . $table_name . " ADD getResponseList VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);    
        $sql = "ALTER TABLE " . $table_name . " ADD millionSeparator VARCHAR(4) NOT NULL;";
        $wpdb->query($sql);                    
    }
    if (!$installed_ver || $installed_ver < 9.416) {
        $table_name = $wpdb->prefix . "wpefc_items";
        $sql = "ALTER TABLE " . $table_name . " ADD isHidden BOOL NOT NULL;";
        $wpdb->query($sql);           
    }
    if (!$installed_ver || $installed_ver < 9.417) {
        $table_name = $wpdb->prefix . "wpefc_items";
        $sql = "ALTER TABLE " . $table_name . " ADD minSize SMALLINT(9) NOT NULL;";
        $wpdb->query($sql);         
        $sql = "ALTER TABLE " . $table_name . " ADD maxSize SMALLINT(9) NOT NULL;";
        $wpdb->query($sql);         
        $sql = "ALTER TABLE " . $table_name . " ADD isNumeric BOOL NOT NULL;";
        $wpdb->query($sql);     
    }
    if (!$installed_ver || $installed_ver < 9.420) {      
        $table_name = $wpdb->prefix . "wpefc_items";      
        $sql = "ALTER TABLE " . $table_name . " ADD isSinglePrice BOOL NOT NULL;";
        $wpdb->query($sql);     
        
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD loadAllPages BOOL NOT NULL;";
        $wpdb->query($sql);     
    }
    if (!$installed_ver || $installed_ver < 9.424) {    
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD filesUpload_text VARCHAR(250) NOT NULL DEFAULT 'Drop files here to upload';";
        $wpdb->query($sql);     
        $sql = "ALTER TABLE " . $table_name . " ADD filesUploadSize_text VARCHAR(250) NOT NULL DEFAULT 'File is too big (max size: {{maxFilesize}}MB)';";
        $wpdb->query($sql);  
        $sql = "ALTER TABLE " . $table_name . " ADD filesUploadType_text VARCHAR(250) NOT NULL DEFAULT 'Invalid file type';";
        $wpdb->query($sql);  
        $sql = "ALTER TABLE " . $table_name . " ADD filesUploadLimit_text VARCHAR(250) NOT NULL DEFAULT 'You can not upload any more files';";
        $wpdb->query($sql);  
           
        $table_name = $wpdb->prefix . "wpefc_items";      
        $sql = "ALTER TABLE " . $table_name . " ADD maxFiles SMALLINT(9) NOT NULL;";
        $wpdb->query($sql);          
        $sql = "ALTER TABLE " . $table_name . " ADD allowedFiles VARCHAR(250) NOT NULL DEFAULT '.png,.jpg,.jpeg,.gif,.zip,.rar';";
        $wpdb->query($sql);     
                                                                            
    }
    if (!$installed_ver || $installed_ver < 9.426) {    
        $table_name = $wpdb->prefix . "wpefc_links";      
        $sql = "ALTER TABLE " . $table_name . " ADD operator VARCHAR(8) NOT NULL;";
        $wpdb->query($sql);      
         $table_name = $wpdb->prefix . "wpefc_items";      
        $sql = "ALTER TABLE " . $table_name . " ADD useCalculation BOOL NOT NULL;";
        $wpdb->query($sql);        
        $sql = "ALTER TABLE " . $table_name . " ADD calculation TEXT NOT NULL;";
        $wpdb->query($sql);        
    }
    if (!$installed_ver || $installed_ver < 9.438) {  
         $table_name = $wpdb->prefix . "wpefc_items";      
        $sql = "ALTER TABLE " . $table_name . " ADD fieldType VARCHAR(64) NOT NULL;";
        $wpdb->query($sql);    
                
        $table_name = $wpdb->prefix . "wpefc_logs";
        $sql = "ALTER TABLE " . $table_name . " ADD phone VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);    
        $sql = "ALTER TABLE " . $table_name . " ADD lastName VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);    
        $sql = "ALTER TABLE " . $table_name . " ADD firstName VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);    
        $sql = "ALTER TABLE " . $table_name . " ADD address TEXT NOT NULL;";
        $wpdb->query($sql);    
        $sql = "ALTER TABLE " . $table_name . " ADD city VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);    
        $sql = "ALTER TABLE " . $table_name . " ADD country VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);    
        $sql = "ALTER TABLE " . $table_name . " ADD state VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);   
        $sql = "ALTER TABLE " . $table_name . " ADD zip VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);   
        
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD mailchimpOptin BOOL NOT NULL;";
        $wpdb->query($sql);          
        $sql = "ALTER TABLE " . $table_name . " ADD useGoogleFont BOOL NOT NULL DEFAULT 1;";
        $wpdb->query($sql);     
        $sql = "ALTER TABLE " . $table_name . " ADD googleFontName VARCHAR(250) NOT NULL DEFAULT 'Lato';";
        $wpdb->query($sql);    
                       
    }
    if (!$installed_ver || $installed_ver < 9.439) {         
        $table_name = $wpdb->prefix . "wpefc_steps";    
        $sql = "ALTER TABLE " . $table_name . " ADD itemsPerRow TINYINT(2) NOT NULL;";
        $wpdb->query($sql);   
    }
    if (!$installed_ver || $installed_ver < 9.440) {         
        $table_name = $wpdb->prefix . "wpefc_logs";    
        $sql = "ALTER TABLE " . $table_name . " ADD totalPrice FLOAT NOT NULL;";
        $wpdb->query($sql);   
        $sql = "ALTER TABLE " . $table_name . " ADD totalSubscription FLOAT NOT NULL;";
        $wpdb->query($sql);   
        $sql = "ALTER TABLE " . $table_name . " ADD subscriptionFrequency VARCHAR(64) NOT NULL;";
        $wpdb->query($sql);   
        $sql = "ALTER TABLE " . $table_name . " ADD formTitle VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);   
        $sql = "ALTER TABLE " . $table_name . " ADD contentTxt TEXT NOT NULL;";
        $wpdb->query($sql);   
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD analyticsID VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);   
    }
    
    if (!$installed_ver || $installed_ver < 9.445) {      
         $table_name = $wpdb->prefix . "wpefc_items";      
        $sql = "ALTER TABLE " . $table_name . " ADD useShowConditions BOOL NOT NULL;";
        $wpdb->query($sql);      
        $sql = "ALTER TABLE " . $table_name . " ADD showConditions TEXT NOT NULL;";
        $wpdb->query($sql);     
        $sql = "ALTER TABLE " . $table_name . " ADD showConditionsOperator VARCHAR(8) NOT NULL;";
        $wpdb->query($sql);          
    }
    if (!$installed_ver || $installed_ver < 9.451) {  
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD sendPdfCustomer BOOL NOT NULL;";
        $wpdb->query($sql);           
        $sql = "ALTER TABLE " . $table_name . " ADD sendPdfAdmin BOOL NOT NULL;";
        $wpdb->query($sql);           
    }
    if (!$installed_ver || $installed_ver < 9.458) { 
         $table_name = $wpdb->prefix . "wpefc_items";      
        $sql = "ALTER TABLE " . $table_name . " ADD usePaypalIfChecked BOOL NOT NULL;";
        $wpdb->query($sql);          
    }
    if (!$installed_ver || $installed_ver < 9.464) { 
         $table_name = $wpdb->prefix . "wpefc_fields";      
        $sql = "ALTER TABLE " . $table_name . " ADD fieldType VARCHAR(64) NOT NULL;";
        $wpdb->query($sql);          
    }     
    if (!$installed_ver || $installed_ver < 9.465) { 
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD sendContactASAP BOOL NOT NULL;";
        $wpdb->query($sql);        
        
    }
    if (!$installed_ver || $installed_ver < 9.472) { 
        $table_name = $wpdb->prefix . "wpefc_steps";      
        $sql = "ALTER TABLE " . $table_name . " ADD showConditions TEXT NOT NULL;";
        $wpdb->query($sql);          
        $table_name = $wpdb->prefix . "wpefc_steps";      
        $sql = "ALTER TABLE " . $table_name . " ADD showConditionsOperator VARCHAR(8) NOT NULL;";
        $wpdb->query($sql);    
        $sql = "ALTER TABLE " . $table_name . " ADD useShowConditions BOOL NOT NULL;";
        $wpdb->query($sql);  
        $table_name = $wpdb->prefix . "wpefc_forms"; 
        $sql = "ALTER TABLE " . $table_name . " MODIFY COLUMN showSteps SMALLINT(5) NOT NULL;"; 
        $wpdb->query($sql);  
        $table_name = $wpdb->prefix . "wpefc_forms"; 
        $sql = "ALTER TABLE " . $table_name . " ADD showTotalBottom BOOL NOT NULL;";
        $wpdb->query($sql);  
         $table_name = $wpdb->prefix . "wpefc_items"; 
        $sql = "ALTER TABLE " . $table_name . " MODIFY COLUMN minSize INT(11) NOT NULL;"; 
        $wpdb->query($sql);  
         $table_name = $wpdb->prefix . "wpefc_items"; 
        $sql = "ALTER TABLE " . $table_name . " MODIFY COLUMN maxSize INT(11) NOT NULL;"; 
        $wpdb->query($sql);          
        
    }
    
    if (!$installed_ver || $installed_ver < 9.474) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD use_stripe BOOL;";
        $wpdb->query($sql);     
        $sql = "ALTER TABLE " . $table_name . " ADD stripe_useSandbox BOOL;";
        $wpdb->query($sql);     
        $sql = "ALTER TABLE " . $table_name . " ADD stripe_secretKey VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);     
        $sql = "ALTER TABLE " . $table_name . " ADD stripe_publishKey VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);  
        $sql = "ALTER TABLE " . $table_name . " ADD stripe_label_creditCard VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);  
        $sql = "ALTER TABLE " . $table_name . " ADD stripe_label_cvc VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);  
        $sql = "ALTER TABLE " . $table_name . " ADD stripe_label_expiration VARCHAR(250) NOT NULL;";
        $wpdb->query($sql);  
        $sql = "ALTER TABLE " . $table_name . " ADD stripe_currency VARCHAR(6) NOT NULL;";
        $wpdb->query($sql);  
        $sql = "ALTER TABLE " . $table_name . " ADD stripe_subsFrequencyType VARCHAR(16) NOT NULL DEFAULT 'month';";
        $wpdb->query($sql);  
                
    }
     if (!$installed_ver || $installed_ver < 9.475) {
         $table_name = $wpdb->prefix . "wpefc_items"; 
        $sql = "ALTER TABLE " . $table_name . " MODIFY COLUMN quantity_max INT(11) NOT NULL;"; 
        $wpdb->query($sql);  
         $table_name = $wpdb->prefix . "wpefc_items"; 
        $sql = "ALTER TABLE " . $table_name . " MODIFY COLUMN quantity_min INT(11) NOT NULL;"; 
        $wpdb->query($sql);         
     
     }
     if (!$installed_ver || $installed_ver < 9.476) {
         $table_name = $wpdb->prefix . "wpefc_logs"; 
        $sql = "ALTER TABLE " . $table_name . " MODIFY COLUMN content MEDIUMTEXT NOT NULL;"; 
        $wpdb->query($sql);  
        $sql = "ALTER TABLE " . $table_name . " MODIFY COLUMN contentUser MEDIUMTEXT NOT NULL;"; 
        $wpdb->query($sql);  
        $sql = "ALTER TABLE " . $table_name . " MODIFY COLUMN contentTxt MEDIUMTEXT NOT NULL;"; 
        $wpdb->query($sql);       
     }
     if (!$installed_ver || $installed_ver < 9.496) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD scrollTopMargin INT(9) NOT NULL;";
        $wpdb->query($sql);     
         
     }    
     if (!$installed_ver || $installed_ver < 9.502) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD redirectionDelay INT(9) NOT NULL DEFAULT 5;";
        $wpdb->query($sql);             
           $forms = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");
        foreach ($forms as $form) {
            $wpdb->update($table_name, array('redirectionDelay' => 5), array('id' => $form->id));
        }
     }    
     if (!$installed_ver || $installed_ver < 9.505) {
        $table_name = $wpdb->prefix . "wpefc_items";
        $sql = "ALTER TABLE " . $table_name . " MODIFY COLUMN reducsQt LONGTEXT NOT NULL;"; 
     
     }
     if (!$installed_ver || $installed_ver < 9.514) {
          $db_table_name = $wpdb->prefix . "wpefc_redirConditions";
            if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
                if (!empty($wpdb->charset))
                    $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
                if (!empty($wpdb->collate))
                    $charset_collate .= " COLLATE $wpdb->collate";

                $sql = "CREATE TABLE $db_table_name (
                        id mediumint(9) NOT NULL AUTO_INCREMENT,
                        formID mediumint (9) NOT NULL,    		
                        conditions TEXT NOT NULL,
                        conditionsOperator VARCHAR(4) NOT NULL DEFAULT '+',
                        url VARCHAR(250) NOT NULL,
                        UNIQUE KEY id (id)
                        ) $charset_collate;";
                dbDelta($sql);
            }
    
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD useRedirectionConditions BOOL NOT NULL DEFAULT 0;"; 
        $wpdb->query($sql);             
     
     }
     if (!$installed_ver || $installed_ver < 9.515) {
         
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD gmap_key VARCHAR(250) NOT NULL;"; 
        $wpdb->query($sql);   
        $sql = "ALTER TABLE " . $table_name . " ADD txtDistanceError TEXT NOT NULL;"; 
        $wpdb->query($sql);   
         $forms = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");
        foreach ($forms as $form) {
            $wpdb->update($table_name, array('txtDistanceError' => 'Calculating the distance could not be performed, please verify the input addresses'), array('id' => $form->id));
        }
        $table_name = $wpdb->prefix . "wpefc_items";
        $sql = "ALTER TABLE " . $table_name . " ADD useDistanceAsQt BOOL NOT NULL;"; 
        $wpdb->query($sql);  
        $sql = "ALTER TABLE " . $table_name . " ADD distanceQt VARCHAR(250) NOT NULL;"; 
        $wpdb->query($sql);   
                       
     } 
     if (!$installed_ver || $installed_ver < 9.525) {
        $table_name = $wpdb->prefix . "wpefc_forms";
        $sql = "ALTER TABLE " . $table_name . " ADD customJS TEXT NOT NULL;"; 
        $wpdb->query($sql);  
        $table_name = $wpdb->prefix . "wpefc_items";
        $sql = "ALTER TABLE " . $table_name . " ADD hideQtSummary BOOL NOT NULL;"; 
        $wpdb->query($sql); 
     }   
     if (!$installed_ver || $installed_ver < 9.526) {
        $table_name = $wpdb->prefix . "wpefc_steps";
        $sql = "ALTER TABLE " . $table_name . " ADD hideNextStepBtn BOOL NOT NULL;"; 
        $wpdb->query($sql); 
        $table_name = $wpdb->prefix . "wpefc_items";
        $sql = "ALTER TABLE " . $table_name . " ADD defaultValue TEXT NOT NULL;"; 
        $wpdb->query($sql);      
     }
         
       
    update_option("wpecf_version", $version);
}

/**
 * Uninstallation.
 * @access  public
 * @since   1.0.0
 * @return  void
 */
function lfb_uninstall() {
    global $wpdb;
    global $jal_db_version;
    $table_name = $wpdb->prefix . "wpefc_steps";
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $table_name = $wpdb->prefix . "wpefc_items";
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $table_name = $wpdb->prefix . "wpefc_links";
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $table_name = $wpdb->prefix . "wpefc_settings";
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $table_name = $wpdb->prefix . "wpefc_forms";
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $table_name = $wpdb->prefix . "wpefc_fields";
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $table_name = $wpdb->prefix . "wpefc_logs";
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $table_name = $wpdb->prefix . "wpefc_coupons";
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $table_name = $wpdb->prefix . "wpefc_redirConditions";
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    
}

// End uninstall()

Estimation_Form();
