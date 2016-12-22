// from php : lfb_data
var lfb_isLinking = false;
var lfb_links = new Array();
var lfb_linkCurrentIndex = -1;
var lfb_canvasTimer;
var lfb_mouseX, lfb_mouseY;
var lfb_linkGradientIndex = 1;
var lfb_itemWinTimer;
var lfb_currentDomElement = false;
var lfb_currentStep = false;
var lfb_currentStepID = 0;
var lfb_lock = false;
var lfb_defaultStep = false;
var lfb_steps = false;
var lfb_params;
var lfb_currentLinkIndex = 0;
var lfb_settings;
var lfb_formfield;
var lfb_currentFormID = 0;
var lfb_actTimer;
var lfb_currentForm = false;
var lfb_currentItemID = 0;
var lfb_canSaveLink = true;
var lfb_canDuplicate = true;
var lfb_openChartsAuto = false;
var lfb_currentCharts = false, lfb_currentChartsOptions, lfb_currentChartsData;
var lfb_currentRedirEdit = 0;
var lfb_distanceModeQt = false;

lfb_data = lfb_data[0];

jQuery(document).ready(function () {
    jQuery('#lfb_loader').remove();
    jQuery('#wpcontent').append('<div id="lfb_loader"><div class="lfb_spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>');
    jQuery('#lfb_loader .lfb_spinner').css({
        top: jQuery(window).height() / 2 - jQuery('#wpadminbar').height() / 2
    });
    jQuery(window).resize(function () {
        jQuery('#lfb_loader .lfb_spinner').css({
            top: jQuery(window).height() / 2 - jQuery('#wpadminbar').height() / 2
        });
        jQuery('#lfb_bootstraped,#estimation_popup').css({
            minHeight: jQuery('#wpcontent').height()
        });
    });
    jQuery('#lfb_bootstraped,#estimation_popup').css({
        minHeight: jQuery('#wpcontent').height()
    });
    jQuery('#lfb_stepsContainer').droppable({
        drop: function (event, ui) {
            var $object = jQuery(ui.draggable[0]);
            jQuery.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    action: 'lfb_saveStepPosition',
                    stepID: $object.attr('data-stepid'),
                    posX: parseInt($object.css('left')),
                    posY: parseInt($object.css('top'))
                }
            });
        }
    });

    jQuery('.imageBtn').click(function () {
        lfb_formfield = jQuery(this).prev('input');
        tb_show('', 'media-upload.php?TB_iframe=true');
        return false;
    });
    window.old_tb_remove = window.tb_remove;
    window.tb_remove = function () {
        window.old_tb_remove();
        lfb_formfield = null;
    };
    window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function (html) {
        if (lfb_formfield) {
            var alt = jQuery('img', html).attr('alt');
            fileurl = jQuery('img', html).attr('src');
            if (jQuery('img', html).length == 0) {
                fileurl = jQuery(html).attr('src');
                alt = jQuery(html).attr('alt');
            }
            lfb_formfield.val(fileurl);
            lfb_formfield.trigger('keyup');
            jQuery('#lfb_itemTabGeneral [name="imageDes"]').val(alt);
            tb_remove();
        } else {
            window.original_send_to_editor(html);
        }
    };
    jQuery('#wpwrap').css({
        height: jQuery('#lfb_bootstraped').height() + 48
    });
    setInterval(function () {
        if (jQuery('#lfb_winStep').css('display') == 'block') {
            jQuery('#wpwrap').css({
                height: jQuery('#lfb_winStep').height() + 48
            });
 
        } else {
            jQuery('#wpwrap').css({
                height: jQuery('#lfb_bootstraped').height() + 48
            });

        }
    }, 1000);

    lfb_canvasTimer = setInterval(lfb_updateStepCanvas, 30);
    jQuery(document).mousemove(function (e) {
        if (lfb_isLinking) {
            lfb_mouseX = e.pageX - jQuery('#lfb_stepsContainer').offset().left;
            lfb_mouseY = e.pageY - jQuery('#lfb_stepsContainer').offset().top;
        }
    });
    jQuery(window).resize(lfb_updateStepsDesign);
    lfb_itemWinTimer = setInterval(lfb_updateWinItemPosition, 30);
    jQuery('#lfb_actionSelect').change(function () {
        lfb_changeActionBubble(jQuery('#lfb_actionSelect').val());
    });
    jQuery('#lfb_interactionSelect').change(function () {
        lfb_changeInteractionBubble(jQuery('#lfb_interactionSelect').val());
    });

    jQuery('#lfb_interactionBubble,#lfb_actionBubble,#lfb_linkBubble,#lfb_fieldBubble,#lfb_calculationValueBubble,#lfb_emailValueBubble,#lfb_distanceValueBubble').hover(function (e) {
        jQuery(this).addClass('lfb_hover');
    }, function (e) {
        jQuery(this).removeClass('lfb_hover');
    });
    jQuery('#lfb_interactionBubble,#lfb_actionBubble,#lfb_linkBubble,#lfb_fieldBubble,#lfb_calculationValueBubble,#lfb_emailValueBubble,#lfb_distanceValueBubble').find('select').focus(function () {
        jQuery(this).addClass('lfb_hover');
    }).blur(function () {
        jQuery(this).removeClass('lfb_hover');
    });
    jQuery('body').click(function () {
        if (!jQuery('#lfb_interactionBubble').is('.lfb_hover')) {
            jQuery('#lfb_interactionBubble').fadeOut();
        }
        if (!jQuery('#lfb_actionBubble').is('.lfb_hover') && !jQuery('#lfb_websiteFrame').is('.lfb_hover') && !jQuery('.lfb_selectElementPanel').is('.lfb_hover')) {
            jQuery('#lfb_actionBubble').fadeOut();
        }
        if (!jQuery('#lfb_linkBubble').is('.lfb_hover')) {
            jQuery('#lfb_linkBubble').fadeOut();
        }
        if (!jQuery('#lfb_calculationValueBubble').is('.lfb_hover') && jQuery('#lfb_calculationValueBubble').find('.lfb_hover').length == 0) {
            jQuery('#lfb_calculationValueBubble').fadeOut();
        }
        if (!jQuery('#lfb_emailValueBubble').is('.lfb_hover') && jQuery('#lfb_emailValueBubble').find('.lfb_hover').length == 0) {
            jQuery('#lfb_emailValueBubble').fadeOut();
        }
        
        if (!jQuery('#lfb_fieldBubble').is('.lfb_hover') && jQuery('#lfb_fieldBubble').find('.lfb_hover').length == 0) {
            jQuery('#lfb_fieldBubble').fadeOut();
        }
        if (!jQuery('#lfb_distanceValueBubble').is('.lfb_hover') && jQuery('#lfb_distanceValueBubble').find('.lfb_hover').length == 0) {
            jQuery('#lfb_distanceValueBubble').fadeOut();
        }
    });
    if (jQuery('#lfb_winActivation').is('[data-show="true"]') && document.referrer.indexOf('admin.php?page=lfb_menu') < 0) {
        jQuery('#lfb_winActivation .modal-dialog').hover(function () {
            jQuery(this).addClass('lfb_hover');
        }, function () {
            jQuery(this).removeClass('lfb_hover');
        });
        lfb_lock = true;
        jQuery('#lfb_closeWinActivationBtn').click(function () {
            if (!lfb_lock) {
                jQuery('#lfb_winActivation').modal('hide');
            }
        });
        jQuery('#lfb_closeWinActivationBtn .lfb_text').data('num', 10).html('Wait 10 seconds');
        lfb_actTimer = setInterval(function () {
            var num = jQuery('#lfb_closeWinActivationBtn .lfb_text').data('num');
            num--;
            if (num > 0) {
                jQuery('#lfb_closeWinActivationBtn .lfb_text').data('num', num).html('Wait ' + num + ' seconds');
            } else {
                jQuery('#lfb_closeWinActivationBtn').removeClass('disabled');
                lfb_lock = false;
                jQuery('#lfb_closeWinActivationBtn .lfb_text').data('num', '').html('Close');
            }
        }, 1000);
    } else {
        jQuery('#lfb_winActivation').attr('data-show', 'false');
    }
    jQuery('#lfb_winActivation').on('hide.bs.modal', function (e) {
        if (lfb_lock && !jQuery('#lfb_winActivation .modal-dialog').is('.lfb_hover')) {
            e.preventDefault();
        }
    });
    jQuery(document).mousedown(function (e) {
        if (e.button == 2 && lfb_isLinking) {
            lfb_isLinking = false;
        }
    });

    jQuery('.form-group').each(function () {
        var self = this;
        if (jQuery(self).find('small').length > 0 && jQuery(self).find('.form-control').length > 0) {
            jQuery(this).find('.form-control').tooltip({
                title: jQuery(self).find('small').html()
            });
        }
    });

    jQuery("#lfb_bootstraped.lfb_bootstraped [data-toggle='switch']").wrap('<div class="switch" data-on-label="' + lfb_data.texts['Yes'] + '" data-off-label="' + lfb_data.texts['No'] + '" />').parent().bootstrapSwitch({onLabel: lfb_data.texts['Yes'], offLabel: lfb_data.texts['No']});

    jQuery('#lfb_tabLastStep table tbody').sortable({
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index)
            {
                // Set helper cell sizes to match the original sizes
                jQuery(this).width($originals.eq(index).width());
            });
            return $helper;
        },
        stop: function (event, ui) {
            var fields = '';
            jQuery('#lfb_tabLastStep table tbody tr[data-fieldid]').each(function (i) {
                fields += jQuery(this).attr('data-fieldid') + ',';
            });
            if (fields.length > 0) {
                fields = fields.substr(0, fields.length - 1);
            }
            jQuery.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    action: 'lfb_changeLastFieldsOrders',
                    fields: fields
                }
            });
        }
    });

    lfb_initCharts();
    jQuery('#lfb_winActivation').modal();
    jQuery('[data-toggle="tooltip"]').tooltip();
    lfb_loadSettings();
    lfb_initFormsBackend();
})
        ;

function lfb_initFormsBackend() {
    jQuery('#lfb_formFields [name="use_paypal"]').on('change', lfb_formPaypalChange);
    jQuery('#lfb_formFields [name="use_stripe"]').on('change', lfb_formStripeChange);
    jQuery('#lfb_formFields [name="isSubscription"]').on('change', lfb_formIsSubscriptionChange);
    jQuery('#lfb_formFields [name="gravityFormID"]').on('change', lfb_formGravityChange);
    jQuery('#lfb_formFields [name="save_to_cart"]').on('change', lfb_formWooChange);
    jQuery('#lfb_formFields [name="email_toUser"]').on('change', lfb_formEmailUserChange);
    jQuery('#lfb_formFields [name="legalNoticeEnable"]').on('change', lfb_formLegalNoticeChange);
    jQuery('#lfb_formFields [name="useSummary"]').on('change', lfb_formUseSummaryChange);
    jQuery('#lfb_formFields [name="intro_enabled"]').on('change', lfb_formUseIntroChange);
    jQuery('#lfb_formFields [name="paypal_useIpn"]').on('change', lfb_formIpnChange);
    jQuery('#lfb_formFields [name="useCoupons"]').on('change', lfb_formUseCouponsChange);
    jQuery('#lfb_formFields [name="useRedirectionConditions"]').on('change', lfb_changeUseRedirs);
    jQuery('#lfb_formFields [name="useMailchimp"]').on('change', lfb_changeMailchimp);
    jQuery('#lfb_formFields [name="useMailpoet"]').on('change', lfb_changeMailpoet);
    jQuery('#lfb_formFields [name="useGetResponse"]').on('change', lfb_changeGetResponse);
    jQuery('#lfb_formFields [name="useGoogleFont"]').on('change', lfb_useGoogleFontChange);
    jQuery('#lfb_formFields [name="getResponseKey"]').focusout(lfb_changeGetResponseList);
    jQuery('#lfb_formFields [name="mailchimpKey"]').focusout(lfb_changeMailchimpList);
    jQuery('#lfb_chartsTypeSelect').on('change',lfb_chartsTypeChange);
    jQuery('#lfb_chartsMonth').on('change',lfb_chartsMonthChange);
    jQuery('#lfb_chartsYear').on('change',lfb_chartsYearChange);
    
    lfb_formGravityChange();
    lfb_formWooChange();
    lfb_formLegalNoticeChange();
    lfb_formEmailUserChange();
    lfb_formUseSummaryChange();
    lfb_formPaypalChange();
    lfb_formStripeChange();
    lfb_formUseIntroChange();
    lfb_formUseCouponsChange();
    lfb_changeUseRedirs();
    lfb_changeMailchimp();
    lfb_changeMailpoet();
    lfb_changeGetResponse();
    lfb_changeGetResponseList();
    lfb_changeMailchimpList();
    lfb_useGoogleFontChange();
    lfb_chartsTypeChange();

    jQuery('#lfb_calculationValueBubble select[name="itemID"]').on('change', lfb_updateCalculationsValueElements);
    jQuery('#lfb_emailValueBubble select[name="itemID"]').on('change', lfb_updateEmailValueElements);
    
}
function lfb_formDistanceAsQtChange(){
     if (jQuery('#lfb_winItem [name="useDistanceAsQt"]').is(':checked')) {
        jQuery('#lfb_winItem #lfb_distanceQtContainer').slideDown();
    } else {
        jQuery('#lfb_winItem #lfb_distanceQtContainer').slideUp();        
    }
    
}
function lfb_changeUseRedirs(){
     if (jQuery('#lfb_formFields [name="useRedirectionConditions"]').is(':checked')) {
        jQuery('#lfb_formFields #lfb_redirConditionsContainer').slideDown();
    } else {
        jQuery('#lfb_formFields #lfb_redirConditionsContainer').slideUp();
    }
}
function lfb_useGoogleFontChange() {
    if (jQuery('#lfb_formFields [name="useGoogleFont"]').is(':checked')) {
        jQuery('#lfb_formFields [name="googleFontName"]').closest('.form-group').slideDown();
    } else {
        jQuery('#lfb_formFields [name="googleFontName"]').closest('.form-group').slideUp();
    }
}
function lfb_changeMailchimp() {
    if (jQuery('#lfb_formFields [name="useMailchimp"]').is(':checked')) {
        jQuery('#lfb_formFields [name="mailchimpKey"]').closest('.form-group').slideDown();
        jQuery('#lfb_formFields [name="mailchimpList"]').closest('.form-group').slideDown();
        jQuery('#lfb_formFields [name="mailchimpOptin"]').closest('.form-group').slideDown();

        lfb_changeMailchimpList();
    } else {
        jQuery('#lfb_formFields [name="mailchimpKey"]').closest('.form-group').slideUp();
        jQuery('#lfb_formFields [name="mailchimpList"]').closest('.form-group').slideUp();
        jQuery('#lfb_formFields [name="mailchimpOptin"]').closest('.form-group').slideUp();
    }
}
function lfb_changeMailpoet() {
    if (jQuery('#lfb_formFields [name="useMailpoet"]').is(':checked')) {
        jQuery('#lfb_formFields [name="mailPoetList"]').closest('.form-group').slideDown();
        lfb_changeMailpoetList();
    } else {
        jQuery('#lfb_formFields [name="mailPoetList"]').closest('.form-group').slideUp();
    }
}
function lfb_changeGetResponse() {
    if (jQuery('#lfb_formFields [name="useGetResponse"]').is(':checked')) {
        jQuery('#lfb_formFields [name="getResponseKey"]').closest('.form-group').slideDown();
        jQuery('#lfb_formFields [name="getResponseList"]').closest('.form-group').slideDown();
        lfb_changeGetResponseList();
    } else {
        jQuery('#lfb_formFields [name="getResponseKey"]').closest('.form-group').slideUp();
        jQuery('#lfb_formFields [name="getResponseList"]').closest('.form-group').slideUp();
    }
}
function lfb_changeMailchimpList() {
    jQuery('#lfb_formFields [name="mailchimpList"] option').remove();
    var apiKey = jQuery('#lfb_formFields [name="mailchimpKey"]').val();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_getMailchimpLists',
            apiKey: apiKey
        },
        success: function (rep) {
            jQuery('#lfb_formFields [name="mailchimpList"]').html(rep);
            if (jQuery('#lfb_formFields [name="mailchimpList"] option[value="' + jQuery('#lfb_tabSettings [name="mailchimpList"]').attr('data-initial') + '"]').length > 0) {
                jQuery('#lfb_formFields [name="mailchimpList"]').val(jQuery('#lfb_tabSettings [name="mailchimpList"]').attr('data-initial'));
            }
            if(lfb_currentForm != false){
                jQuery('#lfb_formFields [name="mailchimpList"]').val(lfb_currentForm.form.mailchimpList);
            }
        }
    });
}
function lfb_changeMailpoetList() {
    jQuery('#lfb_formFields [name="mailPoetList"] option').remove();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_getMailpoetLists'
        },
        success: function (rep) {
            jQuery('#lfb_formFields [name="mailPoetList"]').html(rep);
            if (jQuery('#lfb_formFields [name="mailPoetList"] option[value="' + jQuery('#lfb_tabSettings [name="mailPoetList"]').attr('data-initial') + '"]').length > 0) {
                jQuery('#lfb_formFields [name="mailPoetList"]').val(jQuery('#lfb_tabSettings [name="mailPoetList"]').attr('data-initial'));
            }            
            if(lfb_currentForm != false){
                jQuery('#lfb_formFields [name="mailPoetList"]').val(lfb_currentForm.form.mailPoetList);
            }
        }
    });
}
function lfb_changeGetResponseList() {
    jQuery('#lfb_tabSettings [name="getResponseList"] option').remove();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_getGetResponseLists'
        },
        success: function (rep) {
            jQuery('#lfb_tabSettings [name="getResponseList"]').html(rep);
            if (jQuery('#lfb_tabSettings [name="getResponseList"] option[value="' + jQuery('#lfb_tabSettings [name="getResponseList"]').attr('data-initial') + '"]').length > 0) {
                jQuery('#lfb_tabSettings [name="getResponseList"]').val(jQuery('#lfb_tabSettings [name="getResponseList"]').attr('data-initial'));
            }     
            if(lfb_currentForm != false){
                jQuery('#lfb_formFields [name="getResponseList"]').val(lfb_currentForm.form.getResponseList);
            }
        }
    });
}
function lfb_formUseCouponsChange() {
    if (jQuery('#lfb_formFields [name="useCoupons"]').is(':checked')) {
        jQuery('#lfb_formFields .lfb_couponsContainer').slideDown();
    } else {
        jQuery('#lfb_formFields .lfb_couponsContainer').slideUp();
    }
}
function lfb_formUseIntroChange() {
    if (jQuery('#lfb_formFields [name="intro_enabled"]').is(':checked')) {
        jQuery('#lfb_formFields [name="intro_title"]').closest('.form-group').slideDown();
        jQuery('#lfb_formFields [name="intro_text"]').closest('.form-group').slideDown();
        jQuery('#lfb_formFields [name="intro_btn"]').closest('.form-group').slideDown();
    } else {
        jQuery('#lfb_formFields [name="intro_title"]').closest('.form-group').slideUp();
        jQuery('#lfb_formFields [name="intro_text"]').closest('.form-group').slideUp();
        jQuery('#lfb_formFields [name="intro_btn"]').closest('.form-group').slideUp();

    }
}
function lfb_formIsSubscriptionChange() {
    if (jQuery('#lfb_formFields [name="isSubscription"]').is(':checked')) {
        jQuery('#lfb_formFields [name="subscription_text"]').parent().slideDown();
        if (jQuery('#lfb_formFields [name="use_paypal"]').is(':checked')) {
            jQuery('#lfb_formFields [name="paypal_subsFrequency"]').parent().slideDown();
            jQuery('#lfb_formFields [name="paypal_subsMaxPayments"]').parent().slideDown();
            jQuery('#lfb_formFields [name="percentToPay"]').parent().slideUp();
        }
        if (jQuery('#lfb_formFields [name="use_stripe"]').is(':checked')) {
            jQuery('#lfb_formFields [name="stripe_subsFrequencyType"]').parent().slideDown();
        }
        jQuery('#lfb_winItem').find('[name="isSinglePrice"]').closest('.form-group').slideDown();
    } else {
        jQuery('#lfb_formFields [name="subscription_text"]').parent().slideUp();
        jQuery('#lfb_formFields [name="paypal_subsFrequency"]').parent().slideUp();
        jQuery('#lfb_formFields [name="paypal_subsMaxPayments"]').parent().slideUp();
        jQuery('#lfb_formFields [name="stripe_subsFrequencyType"]').parent().slideUp();
        if (jQuery('#lfb_formFields [name="use_paypal"]').is(':checked')) {
            jQuery('#lfb_formFields [name="percentToPay"]').parent().slideDown();
        }
        jQuery('#lfb_winItem').find('[name="isSinglePrice"]').parent().bootstrapSwitch('setState', false);
        jQuery('#lfb_winItem').find('[name="isSinglePrice"]').closest('.form-group').slideUp();
    }
}
function lfb_formUseSummaryChange() {
    if (jQuery('#lfb_formFields [name="useSummary"]').is(':checked')) {
        jQuery('#lfb_formFields [name="summary_title"]').parent().slideDown();
    } else {
        jQuery('#lfb_formFields [name="summary_title"]').parent().slideUp();
    }
}

function lfb_formLegalNoticeChange() {
    if (jQuery('#lfb_formFields [name="legalNoticeEnable"]').is(':checked')) {
        jQuery('#lfb_formFields [name="legalNoticeTitle"]').parent().slideDown();
        jQuery('#lfb_formFields #lfb_legalNoticeContent').closest('.form-group').slideDown();
    } else {
        jQuery('#lfb_formFields [name="legalNoticeTitle"]').parent().slideUp();
        jQuery('#lfb_formFields #lfb_legalNoticeContent').closest('.form-group').slideUp();
    }
}
function lfb_formPaypalChange() {
    if (jQuery('#lfb_formFields [name="use_paypal"]').is(':checked')) {
        jQuery('#lfb_formPaypal').slideDown();
        jQuery('#lfb_formFields [name="use_stripe"]').parent().bootstrapSwitch('setState', false);
        jQuery('#lfb_formFields [name="save_to_cart"]').parent().bootstrapSwitch('setState', false);
        if (jQuery('#lfb_formFields select[name="gravityFormID"]').val() > 0) {
            jQuery('#lfb_formFields select[name="gravityFormID"]').val('0');
        }
        jQuery('#lfb_formFields .lfb_wooOption').slideUp();
        lfb_formIpnChange();
    } else {
        jQuery('#lfb_formPaypal').slideUp();
        if (!jQuery('#lfb_formFields [name="use_stripe"]').is(':checked')) {
            jQuery('#lfb_formFields .lfb_wooOption').slideDown();
        }

    }
    lfb_formIsSubscriptionChange();
}
function lfb_formStripeChange() {
    if (jQuery('#lfb_formFields [name="use_stripe"]').is(':checked')) {
        jQuery('#lfb_formFields [name="use_paypal"]').parent().bootstrapSwitch('setState', false);
        jQuery('.lfb_stripeField').slideDown();
        jQuery('#lfb_formFields [name="save_to_cart"]').parent().bootstrapSwitch('setState', false);
        if (jQuery('#lfb_formFields select[name="gravityFormID"]').val() > 0) {
            jQuery('#lfb_formFields select[name="gravityFormID"]').val('0');
        }

        jQuery('#lfb_formFields .lfb_wooOption').slideUp();
    } else {
        jQuery('.lfb_stripeField').slideUp();
        if (!jQuery('#lfb_formFields [name="use_paypal"]').is(':checked')) {
            jQuery('#lfb_formFields .lfb_wooOption').slideDown();
        }
    }
    lfb_formIsSubscriptionChange();
}
function lfb_chartsTypeChange(){
    if(jQuery('#lfb_chartsTypeSelect').val() == 'month'){
        jQuery('#lfb_panelCharts #lfb_chartsMonth').slideDown();
        jQuery('#lfb_panelCharts #lfb_chartsYear').slideUp();
    } else if (jQuery('#lfb_chartsTypeSelect').val() == 'year'){
        jQuery('#lfb_panelCharts #lfb_chartsMonth').slideUp();
        jQuery('#lfb_panelCharts #lfb_chartsYear').slideDown();           
    } else { 
        jQuery('#lfb_panelCharts #lfb_chartsMonth').slideUp();
        jQuery('#lfb_panelCharts #lfb_chartsYear').slideUp();          
    }
    if(jQuery('#lfb_panelCharts').css('display') == 'block'){
        lfb_loadCharts(jQuery('#lfb_panelCharts').attr('data-formid'));
    }
}
function lfb_chartsYearChange(){
    lfb_loadCharts(jQuery('#lfb_panelCharts').attr('data-formid'));
}
function lfb_chartsMonthChange(){
    lfb_loadCharts(jQuery('#lfb_panelCharts').attr('data-formid'));
}
function lfb_showShortcodeWin(formID) {
    if (!formID) {
        formID = lfb_currentFormID;
    }
    jQuery('#lfb_shortcode_1').val('[estimation_form form_id="' + formID + '"]');
    jQuery('#lfb_shortcode_2').val('[estimation_form form_id="' + formID + '" fullscreen="true"]');
    jQuery('#lfb_shortcode_3').val('[estimation_form form_id="' + formID + '" popup="true"]');
    jQuery('#lfb_shortcode_4').val('<a href="#" class="open-estimation-form form-' + formID + '">Open Form</a>');
    jQuery('#lfb_winShortcode').find('span[data-displayid]').html(formID);
    jQuery('#lfb_winShortcode').modal('show');
}
function lfb_formGravityChange() {
    if (jQuery('#lfb_formFields select[name="gravityFormID"]').val() > 0) {
        jQuery('#lfb_formFields [name="save_to_cart"]').parent().bootstrapSwitch('setState', false);
        jQuery('#lfb_formFields .nav-tabs > li:eq(2)').slideUp();

        jQuery('#lfb_finalStepFields').slideUp();
        jQuery('#lfb_formFields [name="use_paypal"]').parent().bootstrapSwitch('setState', false);
        jQuery('#lfb_formFields [name="use_stripe"]').parent().bootstrapSwitch('setState', false);
        jQuery('#lfb_formFields [name="isSubscription"]').parent().bootstrapSwitch('setState', false);

    } else {
        jQuery('#lfb_finalStepFields').slideDown();
        jQuery('#lfb_formFields .nav-tabs > li:eq(2)').slideDown();
    }
}
function lfb_formEmailUserChange() {
    if (jQuery('#lfb_formFields [name="email_toUser"]').is(':checked')) {
        jQuery('#lfb_formEmailUser').slideDown();
    } else {
        jQuery('#lfb_formEmailUser').slideUp();
    }
}
function lfb_formWooChange() {
    if (jQuery('#lfb_formFields [name="save_to_cart"]').is(':checked')) {
        jQuery('#lfb_formFields .lfb_paymentOption').slideUp();
        jQuery('#lfb_formFields [name="use_paypal"]').parent().bootstrapSwitch('setState', false);
        jQuery('#lfb_formFields [name="isSubscription"]').parent().bootstrapSwitch('setState', false);
        if (jQuery('#lfb_formFields select[name="gravityFormID"]').val() > 0) {
            jQuery('#lfb_formFields select[name="gravityFormID"]').val('0');
        }
    } else {
        jQuery('#lfb_formFields .lfb_paymentOption').slideDown();
    }
}
function lfb_formIpnChange() {
    if (jQuery('#lfb_formFields [name="paypal_useIpn"]').is(':checked')) {
        jQuery('#lfb_infoIpn').slideDown();
    } else {
        jQuery('#lfb_infoIpn').slideUp();
    }
}
function lfb_getStepByID(stepID) {
    var rep = false;
    jQuery.each(lfb_steps, function (i) {
        if (this.id == stepID) {
            rep = this;
        }
    });
    return rep;
}
function lfb_showLoader() {
    jQuery('body').animate({scrollTop: 0}, 250);
    jQuery('#lfb_loader').fadeIn();
}
function lfb_addStep(step) {
    var title = '';
    var startStep = 0;
    if (!step.content) {
        title = step;
    } else {
        title = step.title;

    }
    var newStep = jQuery('<div class="lfb_stepBloc palette palette-clouds"><div class="lfb_stepBlocWrapper"><h4>' + title + '</h4></div>' +
            '<a href="javascript:" class="lfb_btnEdit" title="' + lfb_data.texts['tip_editStep'] + '"><span class="glyphicon glyphicon-pencil"></span></a>' +
            '<a href="javascript:" class="lfb_btnSup" title="' + lfb_data.texts['tip_delStep'] + '"><span class="glyphicon glyphicon-trash"></span></a>' +
            '<a href="javascript:" class="lfb_btnDup" title="' + lfb_data.texts['tip_duplicateStep'] + '"><span class="glyphicon glyphicon-duplicate"></span></a>' +
            '<a href="javascript:" class="lfb_btnLink" title="' + lfb_data.texts['tip_linkStep'] + '"><span class="glyphicon glyphicon-link"></span></a>' +
            '<a href="javascript:" class="lfb_btnStart" title="' + lfb_data.texts['tip_flagStep'] + '"><span class="glyphicon glyphicon-flag"></span></a></div>');
    if (step.content && step.content.start == 1) {
        newStep.find('.lfb_btnStart').addClass('lfb_selected');
        newStep.addClass('lfb_selected');
    }
    if (step.elementID) {
        newStep.attr('id', step.elementID);

    } else {
        newStep.uniqueId();
    }

    newStep.children('a.lfb_btnEdit').click(function () {
        lfb_openWinStep(jQuery(this).parent().attr('data-stepid'));
    });
    newStep.children('a.lfb_btnLink').click(function () {
        lfb_startLink(jQuery(this).parent().attr('id'));
    });
    newStep.children('a.lfb_btnSup').click(function () {
        lfb_removeStep(jQuery(this).parent().attr('data-stepid'));
    });
    newStep.children('a.lfb_btnDup').click(function () {
        lfb_duplicateStep(jQuery(this).parent().attr('data-stepid'));
    });
    newStep.children('a.lfb_btnStart').click(function () {
        lfb_showLoader();
        jQuery('.lfb_stepBloc[data-stepid]').find('.lfb_btnStart').removeClass('lfb_selected');
        jQuery('.lfb_stepBloc[data-stepid]').find('.lfb_btnStart').closest('.lfb_stepBloc').removeClass('lfb_selected');
        jQuery.each(lfb_steps, function () {
            var step = this;
            if (step.id != jQuery(this).parent().attr('data-stepid') && step.content.start == 1) {
                step.content.start = 0;
                jQuery.ajax({
                    url: ajaxurl,
                    type: 'post',
                    data: {
                        action: 'lfb_saveStep',
                        id: step.id,
                        start: 0,
                        formID: lfb_currentFormID,
                        content: JSON.stringify(step.content)
                    }
                });
            }
        });

        jQuery(this).addClass('lfb_selected');
        jQuery(this).closest('.lfb_stepBloc').addClass('lfb_selected');
        var currentStep = lfb_getStepByID(jQuery(this).parent().attr('data-stepid'));
        currentStep.content.start = 1;
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'lfb_saveStep',
                id: step.id,
                start: 1,
                formID: lfb_currentFormID,
                content: JSON.stringify(currentStep.content)
            },
            success: function () {
                lfb_loadForm(lfb_currentFormID);
            }
        });
    });


    newStep.draggable({
        containment: "parent",
        handle: ".lfb_stepBlocWrapper"
    });
    newStep.children('.lfb_stepBlocWrapper').click(function () {
        if (lfb_isLinking) {
            lfb_stopLink(newStep);
        }
    });
    var posX = 10, posY = 10;
    if (step.content && step.content.previewPosX) {
        posX = step.content.previewPosX;
        posY = step.content.previewPosY;
    } else {
        posX = jQuery('#lfb_stepsOverflow').scrollLeft() + jQuery('#lfb_stepsOverflow').width() / 2 - 64;
        posY = jQuery('#lfb_stepsOverflow').scrollTop() + jQuery('#lfb_stepsOverflow').height() / 2 - 64;
    }
    newStep.hide();
    jQuery('#lfb_stepsContainer').append(newStep);
    newStep.css({
        left: (posX) + 'px',
        top: posY + 'px'
    });

    newStep.fadeIn();
    setTimeout(lfb_updateStepsDesign, 250);
    // lfb_updateStepsDesign();
    jQuery('.lfb_btnWinClose').parent().click(function () {
        lfb_closeWin(jQuery(this).parents('.lfb_window'));
    });
    if (jQuery('#lfb_stepsContainer .lfb_stepBloc').length == 0) {
        startStep = 1;
    }
    if (step.id) {
        newStep.attr('data-stepid', step.id);
    } else {
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'lfb_addStep',
                elementID: newStep.attr('id'),
                formID: lfb_currentFormID,
                previewPosX: posX,
                previewPosY: posY,
                start: startStep
            },
            success: function (step) {
                step = jQuery.parseJSON(step);
                newStep.attr('data-stepid', step.id);
                if (step.start == 1) {
                    newStep.find('.lfb_btnStart').addClass('lfb_selected');
                    newStep.addClass('lfb_selected');
                }
                lfb_steps.push({
                    content: step
                });
            }
        });
    }
}

function lfb_removeStep(stepID) {
    var i = 0;

    jQuery('.lfb_stepBloc[data-stepid="' + stepID + '"]').remove();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_removeStep',
            stepID: stepID
        },
        success: function () {
        }
    });
}
function lfb_updateStepsDesign() {
    jQuery('#wpwrap').css({
        height: jQuery('#lfb_bootstraped').height() + 48
    });
    jQuery('#lfb_stepsCanvas').attr('width', jQuery('#lfb_stepsContainer').outerWidth());
    jQuery('#lfb_stepsCanvas').attr('height', jQuery('#lfb_stepsContainer').outerHeight());
    jQuery('#lfb_stepsCanvas').css({
        width: jQuery('#lfb_stepsContainer').outerWidth(),
        height: jQuery('#lfb_stepsContainer').outerHeight()
    });
    jQuery('.lfb_stepBloc > .lfb_stepBlocWrapper > h4').each(function () {
        jQuery(this).css('margin-top', 0 - jQuery(this).height() / 2);
    });
}

function lfb_repositionLinkPoint(linkIndex) {
    var link = lfb_links[linkIndex];
    var originLeft = (jQuery('#' + link.originID).offset().left - jQuery('#lfb_stepsContainer').offset().left) + jQuery('#' + link.originID).width() / 2;
    var originTop = (jQuery('#' + link.originID).offset().top - jQuery('#lfb_stepsContainer').offset().top) + jQuery('#' + link.originID).height() / 2;
    var destinationLeft = (jQuery('#' + link.destinationID).offset().left - jQuery('#lfb_stepsContainer').offset().left) + jQuery('#' + link.destinationID).width() / 2;
    var destinationTop = (jQuery('#' + link.destinationID).offset().top - jQuery('#lfb_stepsContainer').offset().top) + jQuery('#' + link.destinationID).height() / 2;
    var posX = originLeft + (destinationLeft - originLeft) / 2;
    var posY = originTop + (destinationTop - originTop) / 2;

    jQuery.each(lfb_links, function (i) {
        if (this.originID == link.destinationID && this.destinationID == link.originID && i < linkIndex) {

            posX += 15;
            posY += 15;
        }
    });
    jQuery('.lfb_linkPoint[data-linkindex="' + linkIndex + '"]').css({
        left: posX + 'px',
        top: posY + 'px'
    });
}
function lfb_loadSettings() {
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_loadSettings'
        },
        success: function (settings) {
            settings = jQuery.parseJSON(settings);
            lfb_settings = settings;


            jQuery('#lfb_loader').fadeOut();
        }
    });
}

function lfb_closeSettings() {
    lfb_showLoader();
    document.location.href = document.location.href;
}

function lfb_duplicateStep(stepID) {
    if(lfb_canDuplicate){
    lfb_showLoader();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_duplicateStep',
            stepID: stepID
        },
        success: function (newStepID) {
            lfb_canDuplicate = true;
            lfb_loadForm(lfb_currentFormID);
        }
    });
}
}

function lfb_updateStepCanvas() {
    lfb_linkGradientIndex++;
    if (lfb_linkGradientIndex >= 30) {
        lfb_linkGradientIndex = 1;
    }
    var ctx = jQuery('#lfb_stepsCanvas').get(0).getContext('2d');
    ctx.clearRect(0, 0, jQuery('#lfb_stepsCanvas').attr('width'), jQuery('#lfb_stepsCanvas').attr('height'));
    jQuery.each(lfb_links, function (index) {
        var link = this;
        if (link.destinationID && jQuery('#' + link.originID).length > 0 && jQuery('#' + link.destinationID).length > 0) {
            var posX = parseInt(jQuery('#' + link.originID).css('left')) + jQuery('#' + link.originID).outerWidth() / 2 + 22;
            var posY = parseInt(jQuery('#' + link.originID).css('top')) + jQuery('#' + link.originID).outerHeight() / 2 + 22;
            var posX2 = parseInt(jQuery('#' + link.destinationID).css('left')) + jQuery('#' + link.destinationID).outerWidth() / 2 + 22;
            var posY2 = parseInt(jQuery('#' + link.destinationID).css('top')) + jQuery('#' + link.destinationID).outerHeight() / 2 + 22;
            var grd = ctx.createLinearGradient(posX, posY, posX2, posY2);

            var chkBack = false;
            var lfb_linkGradientIndexA = lfb_linkGradientIndex / 30;
            var gradPos1 = lfb_linkGradientIndexA;
            var gradPos2 = lfb_linkGradientIndexA + 0.1;
            var gradPos3 = lfb_linkGradientIndexA + 0.2;
            ctx.lineWidth = 4;
            if (gradPos2 > 1) {
                gradPos2 = 0;
                gradPos3 = 0.2;
            }
            if (gradPos3 > 1) {
                gradPos3 = 0;
            }

            grd.addColorStop(gradPos1, "#bdc3c7");
            grd.addColorStop(gradPos2, "#1ABC9C");
            grd.addColorStop(gradPos3, "#bdc3c7");
            ctx.strokeStyle = grd;
            ctx.beginPath();
            ctx.moveTo(posX, posY);
            ctx.lineTo(posX2, posY2);
            ctx.stroke();

            if (jQuery('.lfb_linkPoint[data-linkindex="' + index + '"]').length == 0) {
                var $point = jQuery('<a href="javascript:" data-linkindex="' + index + '" class="lfb_linkPoint"><span class="glyphicon glyphicon-pencil"></span></a>');
                jQuery('#lfb_stepsContainer').append($point);
                $point.click(function () {
                    lfb_openWinLink(jQuery(this));
                });
            }
            lfb_repositionLinkPoint(index);

        } else {
            jQuery('.lfb_linkPoint[data-linkindex="' + index + '"]').remove();
        }
    });
    if (lfb_isLinking) {
        var step = jQuery('#' + lfb_links[lfb_linkCurrentIndex].originID);
        var posX = step.position().left + jQuery('#lfb_stepsOverflow').scrollLeft() + step.outerWidth() / 2;
        var posY = step.position().top + jQuery('#lfb_stepsOverflow').scrollTop() + step.outerHeight() / 2;
        ctx.strokeStyle = "#bdc3c7";
        ctx.lineWidth = 4;
        ctx.beginPath();
        ctx.moveTo(posX, posY);
        ctx.lineTo(lfb_mouseX, lfb_mouseY);
        ctx.stroke();
    }
}
function lfb_removeItem(itemID) {
    lfb_showLoader();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_removeItem',
            itemID: itemID,
            stepID: lfb_currentStepID,
            formID: lfb_currentFormID
        },
        success: function () {
            lfb_loadForm(lfb_currentFormID);
            lfb_openWinStep(lfb_currentStepID);
        }
    });
}
function lfb_editItem(itemID) {
    lfb_currentItemID = itemID;
    jQuery('#lfb_winItem').find('input,textarea').val('');
    jQuery('#lfb_winItem').find('select option').removeAttr('selected');
    jQuery('#lfb_winItem').find('select option:eq(0)').attr('selected', 'selected');
    jQuery('#lfb_winItem').find('.switch [data-switch="switch"]').bootstrapSwitch('destroy');
    jQuery('#lfb_winItem').find('.switch > div > :not([data-switch="switch"])').remove();
    jQuery('#lfb_winItem').find('.switch [data-switch="switch"]').unwrap().unwrap();
    jQuery('#lfb_winItem').find('#lfb_itemPricesGrid tbody tr').not('.static').remove();
    jQuery('#lfb_winItem').find('#lfb_itemOptionsValues tbody tr').not('.static').remove();
   
    if (itemID > 0) {
        jQuery.each(lfb_currentStep.items, function () {
            var item = this;

            if (item.id == itemID) {
                jQuery('#lfb_winItem').find('input,select,textarea').each(function () {
                    if (jQuery(this).is('[data-switch="switch"]')) {
                        var value = false;
                        //  jQuery(this).attr('checked','checked');

                        eval('if(item.' + jQuery(this).attr('name') + ' == 1){jQuery(this).attr(\'checked\',\'checked\');} else {jQuery(this).attr(\'checked\',false);}');
                        jQuery(this).wrap('<div class="switch" data-on-label="' + lfb_data.texts['Yes'] + '" data-off-label="' + lfb_data.texts['No'] + '" />').parent().bootstrapSwitch();

                    } else {
                        eval('jQuery(this).val(item.' + jQuery(this).attr('name') + ');');
                    }

                });
                jQuery('#lfb_winItem #lfb_itemRichText').code(this.richtext);
                var reducs = item.reducsQt.split('*');
                jQuery.each(reducs, function () {
                    var reduc = this.split('|');
                    if (reduc[0] && reduc[0] > 0) {
                        jQuery('#lfb_itemPricesGrid tbody').prepend('<tr><td>' + reduc[0] + '</td><td>' + parseFloat(reduc[1]).toFixed(2) + '</td><td><a href="javascript:" onclick="lfb_del_reduc(this);" class="btn btn-danger  btn-circle "><span class="glyphicon glyphicon-trash"></span></a></td></tr>');
                    }
                });
                var optionsV = item.optionsValues.split('|');
                jQuery.each(optionsV, function () {
                    var value = this;
                    var price = 0;
                    if (this.indexOf(';;') > 0) {
                        value = this.substr(0, this.indexOf(';;'));
                        price = this.substr(this.indexOf(';;') + 2, this.length);
                    }
                    if (this != "") {
                        jQuery('#lfb_itemOptionsValues #option_new_value').closest('tr').before('<tr><td>' + value + '</td><td>' + price + '</td><td><a href="javascript:" onclick="lfb_del_option(this);" class="btn btn-danger  btn-circle "><span class="glyphicon glyphicon-trash"></span></a></td></tr>');
                    }
                });
                jQuery('#lfb_itemOptionsValues tbody').sortable({
                    items: "tr:not(.static)",
                    helper: function (e, tr) {
                        var $originals = tr.children();
                        var $helper = tr.clone();
                        $helper.children().each(function (index)
                        {
                            jQuery(this).width($originals.eq(index).width());
                        });
                        return $helper;
                    }
                });


                jQuery('#lfb_winItem').find('[name="wooProductID"]').val(item.wooProductID);
                if (item.wooProductID > 0 && item.wooVariation > 0) {
                    jQuery('#lfb_winItem').find('[name="wooProductID"]').find('option[value="' + item.wooProductID + '"]').each(function () {
                        if (jQuery(this).attr('data-woovariation') == item.wooVariation) {
                            jQuery(this).attr('selected', 'selected');
                        }
                    });
                }
            }
        });
    } else {
        jQuery('#lfb_winItem').find('input[name="operation"]').val('+');
        jQuery('#lfb_winItem').find('input[name="ordersort"]').val(0);
        jQuery('#lfb_winItem').find('input[name="quantity_max"]').val(5);
        jQuery('#lfb_winItem').find('[name="reduc_enabled"]').prop('checked', false);
        jQuery('#lfb_winItem').find('[name="quantity_enabled"]').prop('checked', false);
        jQuery('#lfb_winItem').find('[name="ischecked"]').prop('checked', false);
        jQuery('#lfb_winItem').find('select[name="type"]').val('picture');
        jQuery('#lfb_winItem').find('[name="showInSummary"]').prop('checked', true);
        jQuery('#lfb_winItem').find('[name="allowedFiles"]').val('.png,.jpg,.jpeg,.gif,.zip,.rar');
        jQuery('#lfb_winItem').find('[name="maxFiles"]').val('4');

        jQuery('#lfb_winItem').find('[data-switch="switch"]').wrap('<div class="switch" data-on-label="' + lfb_data.texts['Yes'] + '" data-off-label="' + lfb_data.texts['No'] + '" />').parent().bootstrapSwitch({onLabel: lfb_data.texts['Yes'], offLabel: lfb_data.texts['No']});

    }
    jQuery('#lfb_winItem').find('input[type="checkbox"]').each(function () {
        if (jQuery(this).is('[data-switch="switch"]')) {
            if (jQuery(this).closest('.form-group').find('small').length > 0) {
                jQuery(this).closest('.has-switch').tooltip({
                    title: jQuery(this).closest('.form-group').find('small').html()
                });
            }
        }
    });
     if(jQuery('#lfb_formFields [name="gmap_key"]').val().length <3){
        jQuery('#lfb_winItem #lfb_addDistanceBtn').attr('disabled','disabled');    
        jQuery('#lfb_winItem [name="useDistanceAsQt"]').parent().bootstrapSwitch('setState', false);
        jQuery('#lfb_winItem [name="useDistanceAsQt"]').closest('.switch.has-switch').addClass('deactivate');    
    }  else {
        jQuery('#lfb_winItem #lfb_addDistanceBtn').removeAttr('disabled');
        jQuery('#lfb_winItem [name="useDistanceAsQt"]').closest('.switch.has-switch').removeClass('deactivate');   
    }
    jQuery('#lfb_winItem').find('[name="quantity_enabled"]').on('change', lfb_changeQuantityEnabled);
    lfb_changeQuantityEnabled();
    jQuery('#lfb_winItem').find('[name="reduc_enabled"]').on('change', lfb_changeReducEnabled);
    lfb_changeReducEnabled();
    jQuery('#lfb_winItem').find('[name="quantityUpdated"]').change(lfb_changeQuantity);
    lfb_changeQuantity();
    jQuery('#lfb_winItem').find('[name="wooProductID"]').change(lfb_changeWoo);
    lfb_changeWoo();
    jQuery('#lfb_winItem').find('[name="operation"]').change(lpf_changeOperation);
    lpf_changeOperation();
    jQuery('#lfb_winItem').find('[name="useCalculation"]').change(lfb_changeUseCalculation);
    lfb_changeUseCalculation();
    jQuery('#lfb_winItem').find('[name="type"]').change(lfb_changeItemType);
    lfb_changeItemType();
    jQuery('#lfb_winItem').find('[name="useShowConditions"]').change(lfb_changeUseShowConditions);
    lfb_changeUseShowConditions();
    jQuery('#lfb_winItem').find('[name="showInSummary"]').on('change', lfb_showSummaryItemChange);
    lfb_showSummaryItemChange();
    
    jQuery('#lfb_winItem').find('[name="useDistanceAsQt"]').on('change', lfb_formDistanceAsQtChange);
    lfb_formDistanceAsQtChange();
    jQuery('#lfb_winItem').fadeIn();
    jQuery('html,body').scrollTop(0);
}

function lfb_showSummaryItemChange(){
     if (jQuery("#lfb_winItem").find('[name="showInSummary"]').is(":checked")) {
        jQuery("#lfb_winItem").find('[name="hideQtSummary"]').closest(".form-group").slideDown();
    } else {
        jQuery("#lfb_winItem").find('[name="hideQtSummary"]').closest(".form-group").slideUp();
    }
}
var lfb_isWoo = false;
function lfb_changeWoo() {
    if (jQuery('#lfb_winItem').find('[name="wooProductID"]').val() != '0') {
        if (!lfb_isWoo) {
            jQuery('#lfb_winItem').find('[name="quantity_enabled"]').prop('checked', true);
            jQuery('.quantity_max_tr').show();
        }
        lfb_isWoo = true;
        jQuery('.wooMasked').fadeOut(250);
        if (jQuery('#lfb_winItem').find('[name="title"]').val() == "") {
            jQuery('#lfb_winItem').find('[name="title"]').val(jQuery('#lfb_winItem').find('[name="wooProductID"] option:selected').data('title'));
        }
        if (jQuery('#lfb_winItem').find('[name="wooProductID"] option:selected').data('max')) {
            jQuery('#lfb_winItem').find('[name="quantity_max"]').val(jQuery('#lfb_winItem').find('[name="wooProductID"] option:selected').data('max'));
        }
        if (jQuery('#lfb_winItem').find('[name="wooProductID"] option:selected').data('img')) {
            jQuery('#lfb_winItem').find('[name="image"]').val(jQuery('#lfb_winItem').find('[name="wooProductID"] option:selected').data('img'));
        }

        jQuery('#lfb_winItem').find('[name="useCalculation"]').parent().bootstrapSwitch('setState', false);
        jQuery('#lfb_winItem').find('[name="useCalculation"]').closest('.form-group').slideUp();
        jQuery('#lfb_winItem').find('[name="calculation"]').closest('.form-group').slideUp();
        jQuery('#lfb_winItem').find('[name="price"]').closest('.form-group').slideUp();
    } else {
        lfb_isWoo = false;
        jQuery('.wooMasked').fadeIn(250);
        lfb_changeUseCalculation();
    }

}
function lpf_changeOperation() {
    if (jQuery('#lfb_winItem').find('[name="operation"]').val() == 'x' || jQuery('#lfb_winItem').find('[name="operation"]').val() == '/') {
        jQuery('#lfb_winItem').find('[name="price"]').parent().find('label:eq(1)').slideDown();
        jQuery('#lfb_winItem').find('[name="price"]').parent().find('label:eq(0)').slideUp();
    } else {
        jQuery('#lfb_winItem').find('[name="price"]').parent().find('label:eq(1)').slideUp();
        jQuery('#lfb_winItem').find('[name="price"]').parent().find('label:eq(0)').slideDown();
    }
    if (jQuery('#lfb_winItem').find('[name="operation"]').val() != '+') {
        jQuery('#lfb_winItem').find('[name="reduc_enabled"]').closest('.form-group').slideUp();
        jQuery('#lfb_winItem').find('[name="reduc_enabled"]').prop('checked', false);
        jQuery('#lfb_itemPricesGrid').slideUp();
        jQuery('#lfb_winItem').find('#lfb_itemPricesGrid tbody tr').not('.static').remove();
    } else if (jQuery('#lfb_winItem').find('[name="quantity_enabled"]').is(':checked')) {
        jQuery('#lfb_winItem').find('[name="reduc_enabled"]').closest('.form-group').slideDown();
    }
}

function lfb_changeUseShowConditions() {
    if (jQuery("#lfb_winItem").find('[name="useShowConditions"]').is(":checked")) {
        jQuery("#lfb_winItem").find('[name="showConditions"]').closest(".form-group").slideDown();
    } else {
        jQuery("#lfb_winItem").find('[name="showConditions"]').closest(".form-group").slideUp();
    }
}
function lfb_changeUseCalculation() {
    if (jQuery('#lfb_winItem').find('[name="type"]').val() == 'picture' || jQuery('#lfb_winItem').find('[name="type"]').val() == 'checkbox' || jQuery('#lfb_winItem').find('[name="type"]').val() == 'slider') {
        if (jQuery('#lfb_winItem').find('[name="wooProductID"]').val() == '0') {
            jQuery('#lfb_winItem').find('[name="useCalculation"]').closest('.form-group').slideDown();
            if (jQuery('#lfb_winItem').find('[name="useCalculation"]').is(':checked')) {
                jQuery('#lfb_winItem').find('[name="price"]').closest('.form-group').slideUp();
                jQuery('#lfb_winItem').find('[name="calculation"]').closest('.form-group').slideDown();
            } else {
                jQuery('#lfb_winItem').find('[name="price"]').closest('.form-group').slideDown();
                jQuery('#lfb_winItem').find('[name="calculation"]').closest('.form-group').slideUp();
            }
        }
    }
}

function lfb_changeItemType() {
    if (jQuery('#lfb_winItem').find('[name="type"]').val() == 'picture' || jQuery('#lfb_winItem').find('[name="type"]').val() == 'qtfield') {
        jQuery('.picOnly').slideDown();
        jQuery('#lfb_winItem').find('[name="quantity_enabled"]').closest('.form-group').slideDown();
        jQuery('#lfb_winItem').find('[name="quantity_max"]').closest('.form-group').slideDown();
        jQuery('#lfb_winItem').find('[name="quantity_min"]').closest('.form-group').slideDown();
        jQuery('#lfb_itemRichText').next('.note-editor').slideUp();
        jQuery('#lfb_winItem').find('.lfb_textOnly').slideUp();
        jQuery('#lfb_winItem').find('[name="useRow"]').closest('.form-group').slideDown();
        jQuery('#lfb_winItem').find('[name="urlTarget"]').closest('.form-group').slideDown();
        jQuery('#lfb_winItem').find('[name="showInSummary"]').closest('.form-group').slideDown();
        jQuery('#lfb_winItem').find('[name="description"]').closest('.form-group').slideDown();
        jQuery('#lfb_winItem').find('[name="maxFiles"]').closest('.form-group').slideUp();
        jQuery('#lfb_winItem').find('[name="allowedFiles"]').closest('.form-group').slideUp();
        jQuery('#lfb_winItem').find('[name="minSize"]').closest('.form-group').slideUp();
        jQuery('#lfb_winItem').find('[name="maxSize"]').closest('.form-group').slideUp();
        jQuery('#lfb_winItem').find('[name="defaultValue"]').closest('.form-group').slideUp();  

        if (!jQuery('#lfb_winItem').find('[name="useCalculation"]').is(':checked')) {
            jQuery('#lfb_winItem').find('[name="price"]').closest('.form-group').slideDown();
        } else {
            jQuery('#lfb_winItem').find('[name="price"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="calculation"]').closest('.form-group').slideDown();
        }
        jQuery('#lfb_winItem').find('[name="useCalculation"]').closest('.form-group').slideDown();

        jQuery('#lfb_winItem').find('#lfb_itemOptionsValuesPanel').slideUp();
        jQuery('#lfb_winItem').find('[name="operation"]').closest('.form-group').slideDown();
        jQuery('#lfb_winItem').find('[name="showPrice"]').closest('.form-group').slideDown();
        jQuery('#lfb_winItem').find('[name="groupitems"]').closest('.form-group').slideDown();
        jQuery('#lfb_winItem').find('[name="quantity_max"]').closest('.form-group').slideDown();
        jQuery('#lfb_winItem').find('[name="reduc_enabled"]').closest('.form-group').slideDown();
        if (jQuery('#lfb_winItem').find('[name="type"]').val() == 'qtfield') {
              jQuery('#lfb_winItem [name="useDistanceAsQt"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="isRequired"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="isSelected"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="groupitems"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="imageTint"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="quantity_enabled"]').prop('checked', true);
            jQuery('#lfb_winItem').find('[name="quantity_enabled"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="image"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="ischecked"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="urlTarget"]').closest('.form-group').slideUp();

        } else {
            jQuery('#lfb_winItem').find('[name="isRequired"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="ischecked"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('#lfb_itemOptionsValuesPanel').slideUp();
            jQuery('#lfb_winItem').find('[name="wooProductID"]').closest('.form-group').slideDown();

            jQuery('#lfb_winItem').find('[name="urlTarget"]').closest('.form-group').slideDown();
            lfb_changeReducEnabled();
            lfb_changeUseCalculation();
        }
        jQuery('#lfb_winItem').find('[name="usePaypalIfChecked"]').closest('.form-group').slideUp();
    } else {
        jQuery('.picOnly').slideUp();
        jQuery('#lfb_winItem').find('[name="quantity_max"]').closest('.form-group').slideUp();
        jQuery('#lfb_winItem').find('[name="quantity_min"]').closest('.form-group').slideUp();
        if (jQuery('#lfb_winItem').find('[name="type"]').val() != 'slider') {
            jQuery('#lfb_winItem [name="quantity_enabled"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_itemPricesGrid').slideUp();
            jQuery('#lfb_winItem [name="reduc_enabled"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="reduc_enabled"]').closest('.form-group').slideUp();
        }
        jQuery('#lfb_winItem').find('[name="showInSummary"]').closest('.form-group').slideDown();
        if (jQuery('#lfb_winItem').find('[name="type"]').val() == 'textfield') {
            jQuery('#lfb_winItem').find('.lfb_textOnly').slideDown();
        } else {
            jQuery('#lfb_winItem').find('.lfb_textOnly').slideUp();
        }



        if (jQuery('#lfb_winItem').find('[name="type"]').val() == 'textfield' || jQuery('#lfb_winItem').find('[name="type"]').val() == 'numberfield' || jQuery('#lfb_winItem').find('[name="type"]').val() == 'datepicker' || jQuery('#lfb_winItem').find('[name="type"]').val() == 'textarea') {
            jQuery('#lfb_winItem').find('[name="operation"]').parent().slideUp();
             jQuery('#lfb_winItem [name="useDistanceAsQt"]').parent().bootstrapSwitch('setState', false);
             
            jQuery('#lfb_winItem').find('[name="price"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="calculation"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="useCalculation"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="useCalculation"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem [name="showPrice"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="showPrice"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('#lfb_itemOptionsValuesPanel').slideUp();
            jQuery('#lfb_winItem').find('[name="groupitems"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="wooProductID"]').parent().slideUp();
             jQuery('#lfb_winItem').find('[name="useRow"]').closest('.form-group').slideDown();
            if (jQuery('#lfb_winItem').find('[name="type"]').val() == 'numberfield') {
                jQuery('#lfb_winItem').find('[name="minSize"]').closest('.form-group').slideDown();
                jQuery('#lfb_winItem').find('[name="maxSize"]').closest('.form-group').slideDown();
            } else {
                jQuery('#lfb_winItem').find('[name="minSize"]').closest('.form-group').slideUp();
                jQuery('#lfb_winItem').find('[name="maxSize"]').closest('.form-group').slideUp();
            }
            
            if (jQuery('#lfb_winItem').find('[name="type"]').val() != 'datepicker') {
                jQuery('#lfb_winItem').find('[name="defaultValue"]').closest('.form-group').slideDown();                
            }else {
                jQuery('#lfb_winItem').find('[name="defaultValue"]').closest('.form-group').slideUp();                
            }
            jQuery('#lfb_winItem').find('[name="wooProductID"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="ischecked"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="ischecked"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="quantity_enabled"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('input[name="showPrice"]').val(0);
            jQuery('#lfb_itemRichText').next('.note-editor').slideUp();
            jQuery('#lfb_winItem').find('[name="isRequired"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="maxFiles"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="allowedFiles"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="isSinglePrice"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="isSinglePrice"]').closest('.form-group').slideUp(); 
            jQuery('#lfb_winItem').find('[name="description"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="usePaypalIfChecked"]').closest('.form-group').slideUp();
        } else if (jQuery('#lfb_winItem').find('[name="type"]').val() == 'slider') {
            jQuery('#lfb_winItem').find('[name="wooProductID"]').parent().slideDown();
            jQuery('#lfb_winItem').find('[name="operation"]').parent().slideDown();
                jQuery('#lfb_winItem').find('[name="defaultValue"]').closest('.form-group').slideUp();
            if (!jQuery('#lfb_winItem').find('[name="useCalculation"]').is(':checked')) {
                jQuery('#lfb_winItem').find('[name="price"]').closest('.form-group').slideDown();
            } else {
                jQuery('#lfb_winItem').find('[name="price"]').closest('.form-group').slideUp();
                jQuery('#lfb_winItem').find('[name="calculation"]').closest('.form-group').slideDown();
            }
        jQuery('#lfb_winItem').find('[name="useRow"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="quantity_enabled"]').parent().bootstrapSwitch('setState', true);
            jQuery('#lfb_winItem').find('[name="quantity_enabled"]').closest('.form-group').slideUp();
             jQuery('#lfb_winItem [name="useDistanceAsQt"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="useDistanceAsQt"]').closest('.form-group').slideUp();

            if (!jQuery('#lfb_winItem').find('[name="useCalculation"]').is(':checked')) {
                jQuery('#lfb_winItem').find('[name="price"]').closest('.form-group').slideDown();
            } else {
                jQuery('#lfb_winItem').find('[name="price"]').closest('.form-group').slideUp();
                jQuery('#lfb_winItem').find('[name="calculation"]').closest('.form-group').slideDown();
            }
            jQuery('#lfb_winItem').find('[name="useCalculation"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="minSize"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="maxSize"]').closest('.form-group').slideDown();     
            jQuery('#lfb_winItem').find('[name="showPrice"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('#lfb_itemOptionsValuesPanel').slideUp();
            jQuery('#lfb_winItem').find('[name="groupitems"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="ischecked"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="ischecked"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="quantity_enabled"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('input[name="showPrice"]').val(0);
            jQuery('#lfb_itemRichText').next('.note-editor').slideUp();
            jQuery('#lfb_winItem').find('[name="isRequired"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="maxFiles"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="allowedFiles"]').closest('.form-group').slideUp();
            if (jQuery('#lfb_formFields [name="isSubscription"]').is(':checked')) {
                jQuery('#lfb_winItem').find('[name="isSinglePrice"]').closest('.form-group').slideDown();
            } else {                
                jQuery('#lfb_winItem').find('[name="isSinglePrice"]').parent().bootstrapSwitch('setState', false);
                jQuery('#lfb_winItem').find('[name="isSinglePrice"]').closest('.form-group').slideUp();
            }
            jQuery('#lfb_winItem').find('[name="description"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="usePaypalIfChecked"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="urlTarget"]').closest('.form-group').slideUp();

        } else if (jQuery('#lfb_winItem').find('[name="type"]').val() == 'select') {
            jQuery('#lfb_winItem').find('input[name="showPrice"]').val(0);
            jQuery('#lfb_winItem').find('[name="operation"]').parent().slideDown();
                jQuery('#lfb_winItem').find('[name="defaultValue"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="wooProductID"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="price"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="calculation"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="useCalculation"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="useCalculation"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem [name="showPrice"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="showPrice"]').closest('.form-group').slideUp();
        jQuery('#lfb_winItem').find('[name="useRow"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="minSize"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="maxSize"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="groupitems"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="wooProductID"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="ischecked"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="ischecked"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="quantity_enabled"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('#lfb_itemOptionsValuesPanel').slideDown();
            jQuery('#lfb_winItem').find('[name="urlTarget"]').closest('.form-group').slideUp();
            jQuery('#lfb_itemRichText').next('.note-editor').slideUp();
            jQuery('#lfb_winItem').find('[name="isRequired"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="maxFiles"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="allowedFiles"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('.lfb_textOnly').slideUp();
            if (jQuery('#lfb_formFields [name="isSubscription"]').is(':checked')) {
                jQuery('#lfb_winItem').find('[name="isSinglePrice"]').closest('.form-group').slideDown();
            } else {
                jQuery('#lfb_winItem').find('[name="isSinglePrice"]').parent().bootstrapSwitch('setState', false);
                jQuery('#lfb_winItem').find('[name="isSinglePrice"]').closest('.form-group').slideUp();
            }
            jQuery('#lfb_winItem').find('[name="description"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="usePaypalIfChecked"]').closest('.form-group').slideUp();

        } else if (jQuery('#lfb_winItem').find('[name="type"]').val() == 'filefield') {
            jQuery('#lfb_winItem').find('[name="operation"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="price"]').parent().slideUp();
                jQuery('#lfb_winItem').find('[name="defaultValue"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="wooProductID"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="minSize"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="maxSize"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="calculation"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="useCalculation"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="useCalculation"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('#lfb_itemOptionsValuesPanel').slideUp();
            jQuery('#lfb_winItem [name="showPrice"]').parent().bootstrapSwitch('setState', false);
        jQuery('#lfb_winItem').find('[name="useRow"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="showPrice"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="ischecked"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="groupitems"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="wooProductID"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="urlTarget"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="ischecked"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="quantity_enabled"]').closest('.form-group').slideUp();
            jQuery('#lfb_itemRichText').next('.note-editor').slideUp();
            jQuery('#lfb_winItem').find('[name="isRequired"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="maxFiles"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="allowedFiles"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="isSinglePrice"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="isSinglePrice"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="description"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('.lfb_textOnly').slideUp();
            jQuery('#lfb_winItem').find('[name="usePaypalIfChecked"]').closest('.form-group').slideUp();
        } else if (jQuery('#lfb_winItem').find('[name="type"]').val() == 'richtext') {
            jQuery('#lfb_winItem').find('[name="operation"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="price"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="wooProductID"]').parent().slideUp();
                jQuery('#lfb_winItem').find('[name="defaultValue"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="minSize"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="maxSize"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="calculation"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="useCalculation"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="useCalculation"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('#lfb_itemOptionsValuesPanel').slideUp();

            jQuery('#lfb_winItem [name="showPrice"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="showPrice"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="ischecked"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="groupitems"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="wooProductID"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="ischecked"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="quantity_enabled"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="isRequired"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="showInSummary"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="urlTarget"]').val('');
            jQuery('#lfb_winItem').find('[name="urlTarget"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="useRow"]').val('row');
            jQuery('#lfb_winItem').find('[name="useRow"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="description"]').closest('.form-group').slideUp();
            jQuery('#lfb_itemRichText').next('.note-editor').slideDown();
            jQuery('#lfb_winItem').find('[name="isRequired"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="maxFiles"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="allowedFiles"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="isSinglePrice"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="isSinglePrice"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('.lfb_textOnly').slideUp();
            jQuery('#lfb_winItem').find('[name="usePaypalIfChecked"]').closest('.form-group').slideUp();

        } else if (jQuery('#lfb_winItem').find('[name="type"]').val() == 'colorpicker') {
            jQuery('#lfb_winItem').find('[name="operation"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="price"]').parent().slideUp();
            jQuery('#lfb_winItem').find('[name="wooProductID"]').parent().slideUp();
                jQuery('#lfb_winItem').find('[name="defaultValue"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="calculation"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="useCalculation"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="useCalculation"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('#lfb_itemOptionsValuesPanel').slideUp();
            jQuery('#lfb_winItem').find('[name="minSize"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="maxSize"]').closest('.form-group').slideUp();

            jQuery('#lfb_winItem [name="showPrice"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="showPrice"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="groupitems"]').parent().slideUp();
            jQuery('#lfb_winItem').find('#lfb_itemOptionsValuesPanel').slideUp();
            jQuery('#lfb_winItem').find('[name="wooProductID"]').parent().slideUp();
        jQuery('#lfb_winItem').find('[name="useRow"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="ischecked"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="quantity_enabled"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="quantity_max"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="reduc_enabled"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="urlTarget"]').closest('.form-group').slideUp();
            jQuery('#lfb_itemRichText').next('.note-editor').slideUp();
            jQuery('#lfb_winItem').find('.lfb_textOnly').slideUp();
            jQuery('#lfb_winItem').find('[name="isRequired"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="description"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="maxFiles"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="allowedFiles"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="isSinglePrice"]').parent().bootstrapSwitch('setState', false);
            jQuery('#lfb_winItem').find('[name="isSinglePrice"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="usePaypalIfChecked"]').closest('.form-group').slideUp();
        } else {
            if (!jQuery('#lfb_winItem').find('[name="useCalculation"]').is(':checked')) {
                jQuery('#lfb_winItem').find('[name="price"]').closest('.form-group').slideDown();
            } else {
                jQuery('#lfb_winItem').find('[name="price"]').closest('.form-group').slideUp();
                jQuery('#lfb_winItem').find('[name="calculation"]').closest('.form-group').slideDown();
            }
            jQuery('#lfb_winItem').find('[name="useCalculation"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="showPrice"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="operation"]').parent().slideDown();
            jQuery('#lfb_winItem').find('[name="wooProductID"]').parent().slideDown();
            jQuery('#lfb_winItem').find('[name="groupitems"]').parent().slideDown();
                jQuery('#lfb_winItem').find('[name="defaultValue"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="minSize"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="maxSize"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('#lfb_itemOptionsValuesPanel').slideUp();
            jQuery('#lfb_winItem').find('[name="ischecked"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="quantity_enabled"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="useRow"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="quantity_max"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="reduc_enabled"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="description"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="urlTarget"]').closest('.form-group').slideDown();
            jQuery('#lfb_winItem').find('[name="maxFiles"]').closest('.form-group').slideUp();
            jQuery('#lfb_winItem').find('[name="allowedFiles"]').closest('.form-group').slideUp();

            if (jQuery('#lfb_formFields [name="isSubscription"]').is(':checked')) {
                jQuery('#lfb_winItem').find('[name="isSinglePrice"]').closest('.form-group').slideDown();
            } else {
                jQuery('#lfb_winItem').find('[name="isSinglePrice"]').parent().bootstrapSwitch('setState', false);
                jQuery('#lfb_winItem').find('[name="isSinglePrice"]').closest('.form-group').slideUp();
            }
            if (jQuery('#lfb_formFields [name="use_paypal"]').is(':checked')) {
                jQuery('#lfb_winItem').find('[name="usePaypalIfChecked"]').closest('.form-group').slideDown();
            } else {
                jQuery('#lfb_winItem').find('[name="usePaypalIfChecked"]').closest('.form-group').slideUp();
            }
            jQuery('#lfb_itemRichText').next('.note-editor').slideUp();
            jQuery('#lfb_winItem').find('[name="isRequired"]').closest('.form-group').slideDown();
        }
    }
}
function lfb_changeQuantityEnabled() {
    if (jQuery('#lfb_winItem').find('[name="quantity_enabled"]').is(':checked')) {
        jQuery('#efp_itemQuantity').slideDown();
    } else {
        jQuery('#lfb_winItem').find('[name="reduc_enabled"]').prop('checked', false);
        jQuery('#efp_itemQuantity').slideUp();
         jQuery('#lfb_winItem').find('[name="useDistanceAsQt"]').closest('.form-group').slideDown();
    }
}
function lfb_changeReducEnabled() {
    if (jQuery('#lfb_winItem').find('[name="reduc_enabled"]').is(':checked')) {
        jQuery('#lfb_itemPricesGrid').slideDown(250);
    } else {
        jQuery('#lfb_itemPricesGrid').slideUp(250);
    }
}
function lfb_changeQuantity() {
    if (jQuery('#lfb_winItem').find('input[name="quantityUpdated"]').val() < 1) {
        jQuery('#lfb_winItem').find('input[name="quantityUpdated"]').val('3');
    }
}
function lfb_getReducs() {
    var reducsTab = new Array();
    jQuery('#lfb_itemPricesGrid tbody tr').not('.static').each(function () {
        var qt = jQuery(this).find('td:eq(0)').html();
        var price = jQuery(this).find('td:eq(1)').html();
        reducsTab.push(new Array(qt, price));
    });
    reducsTab.sort(function (a, b) {
        return a[0] - b[0];
    });
    return reducsTab;
}
function lfb_getOptions() {
    var optionsTab = new Array();
    jQuery('#lfb_itemOptionsValues tbody tr').not('.static').each(function () {
        optionsTab.push(jQuery(this).find('td:eq(0)').html() + ';;' + jQuery(this).find('td:eq(1)').html());
    });
    return optionsTab;
}
function lfb_add_option() {
    var newValue = jQuery('#lfb_itemOptionsValues #option_new_value').val();
    var newPrice = parseFloat(jQuery('#lfb_itemOptionsValues #option_new_price').val());
    if (isNaN(newPrice)) {
        newPrice = 0;
    }
    if (newValue != "") {
        jQuery('#lfb_itemOptionsValues #option_new_value').closest('tr').before('<tr><td>' + newValue + '</td><td>' + newPrice + '</td><td><a href="javascript:" onclick="lfb_del_option(this);" class="btn btn-danger btn-circle "><span class="glyphicon glyphicon-trash"></span></a></td></tr>');
        jQuery('#lfb_itemOptionsValues #option_new_value').val('');
    }
    jQuery('#lfb_itemOptionsValues #option_new_price').val('');
    jQuery('#lfb_itemOptionsValues tbody').sortable({
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index)
            {
                jQuery(this).width($originals.eq(index).width());
            });
            return $helper;
        }
    });
}
function lfb_del_option(btn) {
    jQuery(btn).parent().parent().remove();
}

function lfb_add_reduc() {
    var qt = parseInt(jQuery('#reduc_new_qt').val());
    var price = parseFloat(jQuery('#reduc_new_price').val());

    if (!isNaN(qt) && qt > 0 && !isNaN(price)) {

        var reducsTab = lfb_getReducs();
        reducsTab.push(new Array(qt, price));
        reducsTab.sort(function (a, b) {
            return b[0] - a[0];
        });
        jQuery('#lfb_itemPricesGrid tbody tr').not('.static').remove();
        jQuery.each(reducsTab, function () {
            jQuery('#lfb_itemPricesGrid tbody').prepend('<tr><td>' + this[0] + '</td><td>' + parseFloat(this[1]).toFixed(2) + '</td><td><a href="javascript:" onclick="lfb_del_reduc(this);" class="btn btn-danger btn-circle "><span class="glyphicon glyphicon-trash"></span></a></td></tr>');
        });
        jQuery('#reduc_new_qt').val('');
        jQuery('#reduc_new_price').val('');
    }
}
function lfb_del_reduc(btn) {
    jQuery(btn).parent().parent().remove();
}

function lfb_saveItem() {
    var reducs = '';
    var optionsValues = '';
    var wooVariation = 0;
    var error = false;
    jQuery('#lfb_winItem').find('input[name="title"]').parent().removeClass('has-error');
    jQuery('#lfb_winItem').find('input[name="image"]').parent().removeClass('has-error');
    jQuery('#lfb_winItem').find('input[name="quantity_max"]').parent().removeClass('has-error');

    if (jQuery('#lfb_winItem').find('input[name="title"]').val() < 1) {
        error = true;
        jQuery('#lfb_winItem').find('input[name="title"]').parent().addClass('has-error');
    }
    if (jQuery('#lfb_winItem').find('input[name="type"]').val() == 'picture' && jQuery('#lfb_winItem').find('input[name="image"]').val().length < 4) {
        error = true;
        jQuery('#lfb_winItem').find('input[name="image"]').parent().addClass('has-error');
    }
    if (jQuery('#lfb_winItem').find('[name="quantity_enabled"]').val() == '1' && jQuery('#lfb_winItem').find('input[name="quantity_max"]').val() == "") {
        error = true;
        jQuery('#lfb_winItem').find('input[name="quantity_max"]').parent().addClass('has-error');
    }
    var optionStab = lfb_getOptions();
    jQuery.each(optionStab, function () {
        optionsValues += this + '|';
    });

    if (jQuery('#lfb_winItem').find('[name="reduc_enabled"]').is(':checked')) {
        var reducsTab = lfb_getReducs();
        jQuery.each(reducsTab, function () {
            reducs += this[0] + '|' + parseFloat(this[1]).toFixed(2) + '*';
        });
        reducs = reducs.substr(0, reducs.length - 1);
    }
    if (jQuery('#lfb_winItem').find('[name="wooProductID"] option:selected').data('woovariation') && jQuery('#lfb_winItem').find('[name="wooProductID"] option:selected').data('woovariation') > 0) {
        wooVariation = jQuery('#lfb_winItem').find('[name="wooProductID"] option:selected').data('woovariation');
    }


    var itemData = {};
    jQuery('#lfb_winItem').find('input,select,textarea').each(function () {
        if (jQuery(this).closest('#lfb_itemPricesGrid').length == 0 && jQuery(this).closest('#lfb_itemOptionsValues').length == 0 &&
                jQuery(this).closest('#lfb_calculationValueBubble').length == 0) {
            if (!jQuery(this).is('[data-switch="switch"]')) {
                eval('itemData.' + jQuery(this).attr('name') + ' = jQuery(this).val();');
            } else {
                var value = 0;
                if (jQuery(this).is(':checked')) {
                    value = 1;
                }
                eval('itemData.' + jQuery(this).attr('name') + ' = value;');
            }
        }
    });
    itemData.action = 'lfb_saveItem';
    itemData.formID = lfb_currentFormID;
    itemData.stepID = lfb_currentStepID;
    itemData.id = lfb_currentItemID;
    itemData.wooVariation = wooVariation;
    itemData.reducsQt = reducs;
    itemData.optionsValues = optionsValues;
    
    
    if(jQuery('#lfb_itemRichText').next('.note-editor').find('.note-toolbar .note-view [data-name="codeview"]').is('.active')){
       jQuery('#lfb_itemRichText').next('.note-editor').find('.note-toolbar .note-view [data-name="codeview"]').trigger('click');
    }
    
    itemData.richtext = jQuery('#lfb_itemRichText').code();
    if (!error) {

        lfb_showLoader();
        jQuery('#lfb_winItem').fadeOut();
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: itemData,
            success: function (itemID) {
                lfb_loadForm(lfb_currentFormID);
                lfb_openWinStep(lfb_currentStepID);
            }
        });
    }
}

function lfb_checkLicense() {
    var error = false;
    var $field = jQuery('#lfb_winActivation input[name="purchaseCode"]');
    if ($field.val().length < 9) {
        $field.parent().addClass('has-error');
    } else {
        lfb_showLoader();
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {action: 'lfb_checkLicense', code: $field.val()},
            success: function (rep) {
                jQuery('#lfb_loader').fadeOut();
                if (rep.length == "1") {
                    $field.parent().addClass('has-error');
                } else {
                    lfb_lock = false;
                    jQuery('#lfb_winActivation').modal('hide');
                }
            }
        });
    }
}

function lfb_duplicateForm(formID) {
    lfb_showLoader();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {action: 'lfb_duplicateForm', formID: formID},
        success: function (rep) {
            document.location.href = document.location.href;
        }
    });
}
function lfb_duplicateItem(itemID) {
    lfb_showLoader();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {action: 'lfb_duplicateItem', itemID: itemID},
        success: function (rep) {
            lfb_openWinStep(lfb_currentStepID);
        }
    });
}

function lfb_startPreview() {

}
function lfb_openWinStep(stepID) {
    lfb_currentStepID = stepID;
    lfb_showLoader();

    jQuery('#lfb_winStep').find('.switch [data-switch="switch"]').bootstrapSwitch('destroy');
    jQuery('#lfb_winStep').find('.switch > div > :not([data-switch="switch"])').remove();
    jQuery('#lfb_winStep').find('.switch [data-switch="switch"]').unwrap().unwrap();

    jQuery('#lfb_itemsTable tbody').html('');
    if (lfb_currentStepID == 0) {
        jQuery('#lfb_itemsList').hide();
    } else {
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'lfb_loadStep',
                stepID: stepID
            },
            success: function (rep) {
                rep = jQuery.parseJSON(rep);
                step = rep.step;
                lfb_currentStep = rep;

                jQuery('#lfb_stepTabGeneral').find('input,select,textarea').each(function () {
                    // eval('jQuery(this).val(step.' + jQuery(this).attr('name') + ');');
                    if (jQuery(this).is('[data-switch="switch"]')) {
                        var value = false;
                        eval('if(step.' + jQuery(this).attr('name') + ' == 1){jQuery(this).attr(\'checked\',\'checked\');} else {jQuery(this).attr(\'checked\',false);}');
                        jQuery(this).wrap('<div class="switch" data-on-label="' + lfb_data.texts['Yes'] + '" data-off-label="' + lfb_data.texts['No'] + '" />').parent().bootstrapSwitch();

                    } else {
                        eval('jQuery(this).val(step.' + jQuery(this).attr('name') + ');');
                    }
                });


                jQuery.each(rep.items, function () {
                    var item = this;
                    var $tr = jQuery('<tr data-itemid="' + item.id + '"></tr>');
                    $tr.append('<td><a href="javascript:"  onclick="lfb_editItem(' + item.id + ');">' + item.title + '</a></td>');
                    $tr.append('<td>' + item.groupitems + '</td>');
                    $tr.append('<td><a href="javascript:" onclick="lfb_editItem(' + item.id + ');" class="btn btn-primary btn-circle"><span class="glyphicon glyphicon-pencil"></span></a>' +
                            '<a href="javascript:" onclick="lfb_duplicateItem(' + item.id + ');" class="btn btn-default btn-circle"><span class="glyphicon glyphicon-duplicate"></span></a>' +
                            '<a href="javascript:" onclick="lfb_removeItem(' + item.id + ');" class="btn btn-danger btn-circle"><span class="glyphicon glyphicon-trash"></span></a></td>');
                    jQuery('#lfb_itemsTable tbody').append($tr);

                });
                jQuery('#lfb_itemsTable tbody').sortable({
                    helper: function (e, tr) {
                        var $originals = tr.children();
                        var $helper = tr.clone();
                        $helper.children().each(function (index)
                        {
                            // Set helper cell sizes to match the original sizes
                            jQuery(this).width($originals.eq(index).width());
                        });
                        return $helper;
                    },
                    stop: function (event, ui) {
                        var items = '';
                        jQuery('#lfb_itemsTable tbody tr[data-itemid]').each(function (i) {
                            items += jQuery(this).attr('data-itemid') + ',';
                        });
                        if (items.length > 0) {
                            items = items.substr(0, items.length - 1);
                        }
                        jQuery.ajax({
                            url: ajaxurl,
                            type: 'post',
                            data: {
                                action: 'lfb_changeItemsOrders',
                                items: items
                            }
                        });
                    }
                });
                jQuery('#lfb_itemsList').show();

                jQuery('#lfb_btns').html('');
                jQuery('#lfb_winStep').show();
                jQuery('#lfb_stepsContainer').slideUp();
                jQuery('#lfb_loader').fadeOut();
                jQuery('#lfb_winStep').find('[name="useShowConditions"]').change(lfb_changeUseShowStepConditions);
                lfb_changeUseShowStepConditions();

                jQuery('#wpwrap').css({
                    height: jQuery('#lfb_winStep').height() + 48
                });
                jQuery('#lfb_winStep').find('input[type="checkbox"]').each(function () {
                    if (jQuery(this).is('[data-switch="switch"]')) {
                        if (jQuery(this).closest('.form-group').find('small').length > 0) {
                            jQuery(this).closest('.has-switch').tooltip({
                                title: jQuery(this).closest('.form-group').find('small').html()
                            });
                        }
                    }
                });

            }
        });
    }

}

function lfb_changeUseShowStepConditions() {
    if (jQuery("#lfb_winStep").find('[name="useShowConditions"]').is(":checked")) {
        jQuery("#showConditionsStepBtn").slideDown();
    } else {
        jQuery("#showConditionsStepBtn").slideUp();
    }
}

function lfb_saveStep() {
    lfb_showLoader();
    var stepData = {};
    jQuery('#lfb_stepTabGeneral').find('input,select,textarea').each(function () {
        if (!jQuery(this).is('[data-switch="switch"]')) {
            eval('stepData.' + jQuery(this).attr('name') + ' = jQuery(this).val();');
        } else {
            var value = 0;
            if (jQuery(this).is(':checked')) {
                value = 1;
            }
            eval('stepData.' + jQuery(this).attr('name') + ' = value;');
        }
    });
    stepData.action = 'lfb_saveStep';
    stepData.formID = lfb_currentFormID;
    stepData.id = lfb_currentStepID;
    jQuery('.lfb_stepBloc[data-stepid="' + lfb_currentStepID + '"] h4').html(stepData.title);
    lfb_updateStepsDesign();

    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: stepData,
        success: function (stepID) {
            lfb_openWinStep(stepID);
        }
    });
}

function lfb_closeWin(win) {
    win.fadeOut();
    jQuery('#lfb_stepsContainer').slideDown();

    setTimeout(function () {
        lfb_updateStepsDesign();
    }, 250);
}

function lfb_startLink(stepID) {
    lfb_isLinking = true;
    lfb_linkCurrentIndex = lfb_links.length;
    lfb_links.push({
        originID: stepID,
        destinationID: null
    });

}

function lfb_stopLink(newStep) {
    lfb_isLinking = false;
    var chkLink = false;
    jQuery.each(lfb_links, function () {
        if (this.originID == lfb_links[lfb_linkCurrentIndex].originID && this.destinationID == newStep.attr('id')) {
            chkLink = this;
        }
    });
    if (!chkLink) {
        lfb_showLoader();
        lfb_links[lfb_linkCurrentIndex].destinationID = newStep.attr('id');
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'lfb_newLink',
                formID: lfb_currentFormID,
                originStepID: jQuery('#' + lfb_links[lfb_linkCurrentIndex].originID).attr('data-stepid'),
                destinationStepID: jQuery('#' + lfb_links[lfb_linkCurrentIndex].destinationID).attr('data-stepid')
            },
            success: function (linkID) {
                lfb_links[lfb_linkCurrentIndex].id = linkID;
                lfb_loadForm(lfb_currentFormID);
            }
        });
    } else {
        jQuery.grep(lfb_links, function (value) {
            return value != chkLink;
        });
    }
}

function lfb_itemsCheckRows(item) {
    var clear = jQuery(item).parent().children('.clearfix');
    clear.detach();
    jQuery(item).parent().append(clear);
}


function lfb_getUniqueTime() {
    var time = new Date().getTime();
    while (time == new Date().getTime())
        ;
    return new Date().getTime();
}

function lfb_changeInteractionBubble(action) {
    jQuery('#lfb_interactionBubble').data('type', action);
    jQuery('#lfb_interactionBubble #lfb_interactionContent > div').slideUp();
    if (action != "") {
        jQuery('#lfb_interactionBubble #lfb_interactionContent > [data-type="' + action + '"]').slideDown();
    }
    if (action == 'select') {
        var nbSel = jQuery('#lfb_interactionContent > [data-type="' + action + '"]').find('.form-group:not(.default)').length;

        if (nbSel == 0 || jQuery('#lfb_interactionContent > [data-type="' + action + '"]').find('.form-group:not(.default):last-child').find('input').val() == '') {
            lfb_interactionAddSelect(action);
        }
    }
}

function lfb_interactionAddSelect(action) {
    var nbSel = jQuery('#lfb_interactionContent > [data-type="' + action + '"]').find('.form-group').length;
    var $field = jQuery('<div class="form-group"><label>' + lfb_data.txt_option + '</label><input type="text" placeholder="' + lfb_data.txt_option + '" class="form-control" name="s_' + nbSel + '_value"></div>');
    $field.find('input').keyup(function () {
        if (jQuery(this).val() == '') {
            if (jQuery(this).closest('.form-group:not(.default)').index() > 0) {
                jQuery(this).closest('.form-group:not(.default)').remove();
            }
        } else {
            if (jQuery(this).closest('.form-group:not(.default)').next('.form-group:not(.default)').length == 0) {
                lfb_interactionAddSelect(action)
            }
        }
    });
    jQuery('#lfb_interactionContent > [data-type="' + action + '"]').append($field);
    return $field;
}

function lfb_openWinLink($item) {
    lfb_currentLinkIndex = $item.attr('data-linkindex');
    jQuery('#lfb_winLink').attr('data-linkindex', $item.attr('data-linkindex'));
    jQuery('.lfb_conditionItem').remove();
    var stepID = jQuery('#' + lfb_links[$item.attr('data-linkindex')].originID).attr('data-stepid');
    var step = lfb_getStepByID(stepID);
    var destID = jQuery('#' + lfb_links[$item.attr('data-linkindex')].destinationID).attr('data-stepid');
    var destination = lfb_getStepByID(destID);

    jQuery('#lfb_linkInteractions').show();
    jQuery('#lfb_linkOriginTitle').html(step.title);
    jQuery('#lfb_linkDestinationTitle').html(destination.title);

    jQuery.each(lfb_links[lfb_currentLinkIndex].conditions, function () {
        lfb_addLinkInteraction(this);
    });
    jQuery('#lfb_linkOperator').val(lfb_links[lfb_currentLinkIndex].operator);
    jQuery('#lfb_winLink').fadeIn(250);

    setTimeout(lfb_updateStepsDesign, 255);
    setTimeout(function () {
        jQuery('#wpwrap').css({
            height: jQuery('#lfb_bootstraped').height() + 48
        });
    }, 300);

}



function lfb_addShowStepInteraction(data) {
    var $item = jQuery('<tr class="lfb_conditionItem"></tr>');
    var $select = jQuery('<select class="lfb_conditionSelect form-control"></select>');
    jQuery.each(lfb_steps, function () {
        var step = this;
        jQuery.each(step.items, function () {
            var item = this;
            if (item.type != 'richtext' && item.type != 'colorpicker') {
                var itemID = step.id + '_' + item.id;
                $select.append('<option value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
            }
        });
    });

    $select.append('<option value="_total" data-static="1" data-type="totalPrice" data-variable="pricefield">' + lfb_data.texts['totalPrice'] + '</option>');
    $select.append('<option value="_total_qt" data-static="1" data-type="totalQt" data-variable="numberfield">' + lfb_data.texts['totalQuantity'] + '</option>');

    var $operator = jQuery('<select class="lfb_conditionoperatorSelect form-control"></select>');
    $select.change(function () {
        var stepID = $select.val().substr(0, $select.val().indexOf('_'));
        var itemID = $select.val().substr($select.val().indexOf('_') + 1, $select.val().length);
        var item = false;
        jQuery.each(lfb_steps, function () {
            var step = this;
            if (step.id == stepID) {
                jQuery.each(step.items, function () {
                    if (this.id == itemID) {
                        item = this;
                    }
                });
            }
        });
        var operator = jQuery(this).parent().parent().find('.lfb_conditionoperatorSelect');
        operator.find('option').remove();
        if ($select.find('option:selected').is('[data-static]')) {
            var options = lfb_conditionGetOperators({
                type: $select.find('option:selected').attr('data-type')
            }, $select);
        } else {
            var options = lfb_conditionGetOperators(item, $select);
        }
        jQuery.each(options, function () {
            operator.append('<option value="' + this.value + '"  data-variable="' + this.hasVariable + '">' + this.text + '</option>');
        });
        $operator.change();
    });
    if (data) {
        $select.val(data.interaction);
    }
    if ($select.find('option:selected').is('[data-static]')) {
        var options = lfb_conditionGetOperators({
            type: $select.find('option:selected').attr('data-type')
        }, $select);
    } else {
        var stepID = $select.val().substr(0, $select.val().indexOf('_'));
        var itemID = $select.val().substr($select.val().indexOf('_') + 1, $select.val().length);
        var item = false;
        jQuery.each(lfb_steps, function () {
            var step = this;
            if (step.id == stepID) {
                jQuery.each(step.items, function () {
                    if (this.id == itemID) {
                        item = this;
                    }
                });
            }
        });
        var options = lfb_conditionGetOperators(item, $select);
    }
    jQuery.each(options, function () {
        $operator.append('<option value="' + this.value + '" data-variable="' + this.hasVariable + '">' + this.text + '</option>');
    });

    $operator.change(function () {
        lfb_linksUpdateFields(jQuery(this));
    });
    setTimeout(function(){
        $select.change();        
    },250);
    var $col1 = jQuery('<td></td>');
    $col1.append($select);
    $item.append($col1);
    var $col2 = jQuery('<td></td>');
    $col2.append($operator);
    $item.append($col2);
    $item.append('<td></td><td><a href="javascript:" class="lfb_conditionDelBtn" onclick="lfb_conditionRemove(this);"><span class="glyphicon glyphicon-remove"></span></a> </td>');
    if (data) {
        $operator.val(data.action);
        $operator.change();
        if (data.value) {
            $operator.closest('.lfb_conditionItem').find('.lfb_conditionValue').val(data.value);
        }
        setTimeout(function () {
            lfb_linksUpdateFields($operator, data);
            if (data.value) {
                $operator.closest('.lfb_conditionItem').find('.lfb_conditionValue').val(data.value);
            }
        }, 500);
    }
    jQuery('#lfb_showStepConditionsTable tbody').append($item);
}

function lfb_addRedirInteraction(data) {
    var $item = jQuery('<tr class="lfb_conditionItem"></tr>');
    var $select = jQuery('<select class="lfb_conditionSelect form-control"></select>');
    jQuery.each(lfb_steps, function () {
        var step = this;
        jQuery.each(step.items, function () {
            var item = this;
            if (item.type != 'richtext' && item.type != 'colorpicker') {
                var itemID = step.id + '_' + item.id;
                $select.append('<option value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
            }
        });
    });

    $select.append('<option value="_total" data-static="1" data-type="totalPrice" data-variable="pricefield">' + lfb_data.texts['totalPrice'] + '</option>');
    $select.append('<option value="_total_qt" data-static="1" data-type="totalQt" data-variable="numberfield">' + lfb_data.texts['totalQuantity'] + '</option>');

    var $operator = jQuery('<select class="lfb_conditionoperatorSelect form-control"></select>');
    $select.change(function () {
        var stepID = $select.val().substr(0, $select.val().indexOf('_'));
        var itemID = $select.val().substr($select.val().indexOf('_') + 1, $select.val().length);
        var item = false;
        jQuery.each(lfb_steps, function () {
            var step = this;
            if (step.id == stepID) {
                jQuery.each(step.items, function () {
                    if (this.id == itemID) {
                        item = this;
                    }
                });
            }
        });
        var operator = jQuery(this).parent().parent().find('.lfb_conditionoperatorSelect');
        operator.find('option').remove();
        if ($select.find('option:selected').is('[data-static]')) {
            var options = lfb_conditionGetOperators({
                type: $select.find('option:selected').attr('data-type')
            }, $select);
        } else {
            var options = lfb_conditionGetOperators(item, $select);
        }
        jQuery.each(options, function () {
            operator.append('<option value="' + this.value + '"  data-variable="' + this.hasVariable + '">' + this.text + '</option>');
        });
        $operator.change();
    });
    if (data) {
        $select.val(data.interaction);
    }
    if ($select.find('option:selected').is('[data-static]')) {
        var options = lfb_conditionGetOperators({
            type: $select.find('option:selected').attr('data-type')
        }, $select);
    } else {
        var stepID = $select.val().substr(0, $select.val().indexOf('_'));
        var itemID = $select.val().substr($select.val().indexOf('_') + 1, $select.val().length);
        var item = false;
        jQuery.each(lfb_steps, function () {
            var step = this;
            if (step.id == stepID) {
                jQuery.each(step.items, function () {
                    if (this.id == itemID) {
                        item = this;
                    }
                });
            }
        });
        var options = lfb_conditionGetOperators(item, $select);
    }
    jQuery.each(options, function () {
        $operator.append('<option value="' + this.value + '" data-variable="' + this.hasVariable + '">' + this.text + '</option>');
    });

    $operator.change(function () {
        lfb_linksUpdateFields(jQuery(this));
    });
    setTimeout(function(){
        $select.change();        
    },250);
    var $col1 = jQuery('<td></td>');
    $col1.append($select);
    $item.append($col1);
    var $col2 = jQuery('<td></td>');
    $col2.append($operator);
    $item.append($col2);
    $item.append('<td></td><td><a href="javascript:" class="lfb_conditionDelBtn" onclick="lfb_conditionRemove(this);"><span class="glyphicon glyphicon-remove"></span></a> </td>');
    if (data) {
        $operator.val(data.action);
        $operator.change();
        if (data.value) {
            $operator.closest('.lfb_conditionItem').find('.lfb_conditionValue').val(data.value);
        }
        setTimeout(function () {
            lfb_linksUpdateFields($operator, data);
            if (data.value) {
                $operator.closest('.lfb_conditionItem').find('.lfb_conditionValue').val(data.value);
            }
        }, 500);
    }
    jQuery('#lfb_redirConditionsTable tbody').append($item);
}


function lfb_addShowInteraction(data) {
    var $item = jQuery('<tr class="lfb_conditionItem"></tr>');
    var $select = jQuery('<select class="lfb_conditionSelect form-control"></select>');
    jQuery.each(lfb_steps, function () {
        var step = this;
        jQuery.each(step.items, function () {
            var item = this;
            if (item.type != 'richtext' && item.type != 'colorpicker') {
                var itemID = step.id + '_' + item.id;
                $select.append('<option value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
            }
        });
    });

    $select.append('<option value="_total" data-static="1" data-type="totalPrice" data-variable="pricefield">' + lfb_data.texts['totalPrice'] + '</option>');
    $select.append('<option value="_total_qt" data-static="1" data-type="totalQt" data-variable="numberfield">' + lfb_data.texts['totalQuantity'] + '</option>');

    var $operator = jQuery('<select class="lfb_conditionoperatorSelect form-control"></select>');
    $select.change(function () {
        var stepID = $select.val().substr(0, $select.val().indexOf('_'));
        var itemID = $select.val().substr($select.val().indexOf('_') + 1, $select.val().length);
        var item = false;
        jQuery.each(lfb_steps, function () {
            var step = this;
            if (step.id == stepID) {
                jQuery.each(step.items, function () {
                    if (this.id == itemID) {
                        item = this;
                    }
                });
            }
        });
        var operator = jQuery(this).parent().parent().find('.lfb_conditionoperatorSelect');
        operator.find('option').remove();
        if ($select.find('option:selected').is('[data-static]')) {
            var options = lfb_conditionGetOperators({
                type: $select.find('option:selected').attr('data-type')
            }, $select);
        } else {
            var options = lfb_conditionGetOperators(item, $select);
        }
        jQuery.each(options, function () {
            operator.append('<option value="' + this.value + '"  data-variable="' + this.hasVariable + '">' + this.text + '</option>');
        });
        $operator.change();
    });
    if (data) {
        $select.val(data.interaction);
    }
    if ($select.find('option:selected').is('[data-static]')) {
        var options = lfb_conditionGetOperators({
            type: $select.find('option:selected').attr('data-type')
        }, $select);
    } else {
        var stepID = $select.val().substr(0, $select.val().indexOf('_'));
        var itemID = $select.val().substr($select.val().indexOf('_') + 1, $select.val().length);
        var item = false;
        jQuery.each(lfb_steps, function () {
            var step = this;
            if (step.id == stepID) {
                jQuery.each(step.items, function () {
                    if (this.id == itemID) {
                        item = this;
                    }
                });
            }
        });
        var options = lfb_conditionGetOperators(item, $select);
    }
    jQuery.each(options, function () {
        $operator.append('<option value="' + this.value + '" data-variable="' + this.hasVariable + '">' + this.text + '</option>');
    });

    $operator.change(function () {
        lfb_linksUpdateFields(jQuery(this));
    });
    setTimeout(function(){
      //  $select.change();        
    },250);
    var $col1 = jQuery('<td></td>');
    $col1.append($select);
    $item.append($col1);
    var $col2 = jQuery('<td></td>');
    $col2.append($operator);
    $item.append($col2);
    $item.append('<td></td><td><a href="javascript:" class="lfb_conditionDelBtn" onclick="lfb_conditionRemove(this);"><span class="glyphicon glyphicon-remove"></span></a> </td>');
    if (data) {
        $operator.val(data.action);
        $operator.change();
        if (data.value) {
            $operator.closest('.lfb_conditionItem').find('.lfb_conditionValue').val(data.value);
        }
        setTimeout(function () {
            lfb_linksUpdateFields($operator, data);
            if (data.value) {
                $operator.closest('.lfb_conditionItem').find('.lfb_conditionValue').val(data.value);
            }
        }, 500);
    }
    jQuery('#lfb_showConditionsTable tbody').append($item);
}

function lfb_addCalcInteraction(data) {
    var $item = jQuery('<tr class="lfb_conditionItem"></tr>');
    var $select = jQuery('<select class="lfb_conditionSelect form-control"></select>');
    jQuery.each(lfb_steps, function () {
        var step = this;
        jQuery.each(step.items, function () {
            var item = this;
            if (item.type != 'richtext' && item.type != 'colorpicker') {
                var itemID = step.id + '_' + item.id;
                $select.append('<option value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
            }
        });
    });

    $select.append('<option value="_total" data-static="1" data-type="totalPrice" data-variable="pricefield">' + lfb_data.texts['totalPrice'] + '</option>');
    $select.append('<option value="_total_qt" data-static="1" data-type="totalQt" data-variable="numberfield">' + lfb_data.texts['totalQuantity'] + '</option>');

    var $operator = jQuery('<select class="lfb_conditionoperatorSelect form-control"></select>');
    $select.change(function () {
        var stepID = $select.val().substr(0, $select.val().indexOf('_'));
        var itemID = $select.val().substr($select.val().indexOf('_') + 1, $select.val().length);
        var item = false;
        jQuery.each(lfb_steps, function () {
            var step = this;
            if (step.id == stepID) {
                jQuery.each(step.items, function () {
                    if (this.id == itemID) {
                        item = this;
                    }
                });
            }
        });
        var operator = jQuery(this).parent().parent().find('.lfb_conditionoperatorSelect');
        operator.find('option').remove();
        if ($select.find('option:selected').is('[data-static]')) {
            var options = lfb_conditionGetOperators({
                type: $select.find('option:selected').attr('data-type')
            }, $select);
        } else {
            var options = lfb_conditionGetOperators(item, $select);
        }
        jQuery.each(options, function () {
            operator.append('<option value="' + this.value + '"  data-variable="' + this.hasVariable + '">' + this.text + '</option>');
        });
        $operator.change();
    });
    if (data) {
        $select.val(data.interaction);
    }
    setTimeout(function(){
       // $select.change();        
    },250);
    if ($select.find('option:selected').is('[data-static]')) {
        var options = lfb_conditionGetOperators({
            type: $select.find('option:selected').attr('data-type')
        }, $select);
    } else {
        var stepID = $select.val().substr(0, $select.val().indexOf('_'));
        var itemID = $select.val().substr($select.val().indexOf('_') + 1, $select.val().length);
        var item = false;
        jQuery.each(lfb_steps, function () {
            var step = this;
            if (step.id == stepID) {
                jQuery.each(step.items, function () {
                    if (this.id == itemID) {
                        item = this;
                    }
                });
            }
        });
        var options = lfb_conditionGetOperators(item, $select);
    }
    jQuery.each(options, function () {
        $operator.append('<option value="' + this.value + '" data-variable="' + this.hasVariable + '">' + this.text + '</option>');
    });

    $operator.change(function () {
        lfb_linksUpdateFields(jQuery(this));
    });
    var $col1 = jQuery('<td></td>');
    $col1.append($select);
    $item.append($col1);
    var $col2 = jQuery('<td></td>');
    $col2.append($operator);
    $item.append($col2);
    $item.append('<td></td><td><a href="javascript:" class="lfb_conditionDelBtn" onclick="lfb_conditionRemove(this);"><span class="glyphicon glyphicon-remove"></span></a> </td>');
    if (data) {
        $operator.val(data.action);
        $operator.change();
        if (data.value) {
            $operator.closest('.lfb_conditionItem').find('.lfb_conditionValue').val(data.value);
        }
        setTimeout(function () {
            lfb_linksUpdateFields($operator, data);
            if (data.value) {
                $operator.closest('.lfb_conditionItem').find('.lfb_conditionValue').val(data.value);
            }
        }, 500);
    }
    jQuery('#lfb_calcConditionsTable tbody').append($item);
}
function lfb_addLinkInteraction(data) {
    var $item = jQuery('<tr class="lfb_conditionItem"></tr>');
    var $select = jQuery('<select class="lfb_conditionSelect form-control"></select>');
    jQuery.each(lfb_steps, function () {
        var step = this;
        jQuery.each(step.items, function () {
            var item = this;
            if (item.type != 'richtext' && item.type != 'colorpicker') {
                var itemID = step.id + '_' + item.id;
                $select.append('<option value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
            }
        });
    });
    $select.append('<option value="_total" data-static="1" data-type="totalPrice" data-variable="pricefield">' + lfb_data.texts['totalPrice'] + '</option>');
    $select.append('<option value="_total_qt" data-static="1" data-type="totalQt" data-variable="numberfield">' + lfb_data.texts['totalQuantity'] + '</option>');
    var $operator = jQuery('<select class="lfb_conditionoperatorSelect form-control"></select>');
    $select.change(function () {
        var stepID = $select.val().substr(0, $select.val().indexOf('_'));
        var itemID = $select.val().substr($select.val().indexOf('_') + 1, $select.val().length);
        var item = false;
        jQuery.each(lfb_steps, function () {
            var step = this;
            if (step.id == stepID) {
                jQuery.each(step.items, function () {
                    if (this.id == itemID) {
                        item = this;
                    }
                });
            }
        });
        var operator = jQuery(this).parent().parent().find('.lfb_conditionoperatorSelect');
        operator.find('option').remove();
        if ($select.find('option:selected').is('[data-static]')) {
            var options = lfb_conditionGetOperators({
                type: $select.find('option:selected').attr('data-type')
            }, $select);
        } else {
            var options = lfb_conditionGetOperators(item, $select);
        }
        jQuery.each(options, function () {
            operator.append('<option value="' + this.value + '"  data-variable="' + this.hasVariable + '">' + this.text + '</option>');
        });
        $operator.change();
    });
    if (data) {
        $select.val(data.interaction);
    }
    if ($select.find('option:selected').is('[data-static]')) {
        var options = lfb_conditionGetOperators({
            type: $select.find('option:selected').attr('data-type')
        }, $select);
    } else {
        var stepID = $select.val().substr(0, $select.val().indexOf('_'));
        var itemID = $select.val().substr($select.val().indexOf('_') + 1, $select.val().length);
        var item = false;
        jQuery.each(lfb_steps, function () {
            var step = this;
            if (step.id == stepID) {
                jQuery.each(step.items, function () {
                    if (this.id == itemID) {
                        item = this;
                    }
                });
            }
        });
        var options = lfb_conditionGetOperators(item, $select);
    }
    jQuery.each(options, function () {
        $operator.append('<option value="' + this.value + '" data-variable="' + this.hasVariable + '">' + this.text + '</option>');
    });

    $operator.change(function () {
        lfb_linksUpdateFields(jQuery(this));
    });
    var $col1 = jQuery('<td></td>');
    $col1.append($select);
    $item.append($col1);
    var $col2 = jQuery('<td></td>');
    $col2.append($operator);
    $item.append($col2);
    $item.append('<td></td><td><a href="javascript:" class="lfb_conditionDelBtn" onclick="lfb_conditionRemove(this);"><span class="glyphicon glyphicon-remove"></span></a> </td>');
    if (data) {
        $operator.val(data.action);
        $operator.change();
        if (data.value) {
            $operator.closest('.lfb_conditionItem').find('.lfb_conditionValue').val(data.value);
        }
        setTimeout(function () {
            lfb_linksUpdateFields($operator, data);
            if (data.value) {
                $operator.closest('.lfb_conditionItem').find('.lfb_conditionValue').val(data.value);
            }
        }, 500);
    }
    jQuery('#lfb_conditionsTable tbody').append($item);
}

function lfb_linksUpdateFields($operatorSelect, data) {

    $operatorSelect.closest('.lfb_conditionItem').find('.lfb_conditionValue').parent().remove();
    if ($operatorSelect.closest('.lfb_conditionItem').find('.lfb_conditionoperatorSelect option:selected').attr('data-variable') == "textfield") {
        if ($operatorSelect.closest('.lfb_conditionItem').find('.lfb_conditionValue').length == 0) {
            $operatorSelect.closest('.lfb_conditionItem').children('td:eq(2)').html('<div><input type="text" placeholder="http://..." class="lfb_conditionValue form-control" /> </div>');
        }
    }

    if ($operatorSelect.closest('.lfb_conditionItem').find('.lfb_conditionoperatorSelect option:selected').attr('data-variable') == "numberfield") {
        if ($operatorSelect.closest('.lfb_conditionItem').find('.lfb_conditionValue').length == 0) {
            $operatorSelect.closest('.lfb_conditionItem').children('td:eq(2)').html('<div><input type="number" class="lfb_conditionValue form-control" /> </div>');
        }
    }
    if ($operatorSelect.closest('.lfb_conditionItem').find('.lfb_conditionoperatorSelect option:selected').attr('data-variable') == "pricefield") {
        if ($operatorSelect.closest('.lfb_conditionItem').find('.lfb_conditionValue').length == 0) {
            $operatorSelect.closest('.lfb_conditionItem').children('td:eq(2)').html('<div><input type="number" step="any" class="lfb_conditionValue form-control" /> </div>');
        }
    }

    if ($operatorSelect.closest('.lfb_conditionItem').find('.lfb_conditionoperatorSelect option:selected').attr('data-variable') == "datefield") {
        if ($operatorSelect.closest('.lfb_conditionItem').find('.lfb_conditionValue').length == 0) {
            $operatorSelect.closest('.lfb_conditionItem').children('td:eq(2)').html('<div><input type="text" step="any" class="lfb_conditionValue form-control"/> </div>');
            $operatorSelect.closest('.lfb_conditionItem').find('.lfb_conditionValue').datepicker({
                dateFormat: 'yy-mm-dd'
            });
        }
    }
    if ($operatorSelect.closest('.lfb_conditionItem').find('.lfb_conditionoperatorSelect option:selected').attr('data-variable') == "select") {
        var optionsSelect = '';
        var $select = $operatorSelect.closest('.lfb_conditionItem').find('.lfb_conditionSelect');
        var stepID = $select.val().substr(0, $select.val().indexOf('_'));
        var itemID = $select.val().substr($select.val().indexOf('_') + 1, $select.val().length);

        var optionsString = '';
        jQuery.each(lfb_currentForm.steps, function () {
            if (this.id == stepID) {
                jQuery.each(this.items, function () {
                    if (this.id == itemID) {
                        optionsString = this.optionsValues;
                    }
                });
            }
        });
        var optionsArray = optionsString.split('|');
        jQuery.each(optionsArray, function () {
            var value = this;
            if (value.indexOf(';;') > 0) {
                var valueArray = value.split(';;');
                value = valueArray[0];
            }
            if (value.length > 0) {
                optionsString += '<option value="' + value + '">' + value + '</option>';
            }
        });

        if ($operatorSelect.closest('.lfb_conditionItem').find('.lfb_conditionValue').length == 0) {
            $operatorSelect.closest('.lfb_conditionItem').children('td:eq(2)').html('<div><select class="lfb_conditionValue form-control">' + optionsString + '</select> </div>');
        }
    }

    if (data && data.value) {
        $operatorSelect.closest('.lfb_conditionItem').find('.lfb_conditionValue').val(data.value);
    }
}

function lfb_conditionRemove(btn) {
    var $btn = jQuery(btn);
    $btn.closest('.lfb_conditionItem').remove();
}

function lfb_linkSave() {
    if (lfb_canSaveLink) {
        lfb_canSaveLink = false;
        lfb_showLoader();
        lfb_links[lfb_currentLinkIndex].conditions = new Array();
        jQuery('.lfb_conditionItem').each(function () {
            lfb_links[lfb_currentLinkIndex].conditions.push({
                interaction: jQuery(this).find('.lfb_conditionSelect').val(),
                action: jQuery(this).find('.lfb_conditionoperatorSelect').val(),
                value: jQuery(this).find('.lfb_conditionValue').val()
            });
        });
        lfb_links[lfb_currentLinkIndex].operator = jQuery('#lfb_linkOperator').val();

        var cloneLinks = lfb_links.slice();
        jQuery.each(cloneLinks, function () {
            this.originID = jQuery('#' + this.originID).attr('data-stepid');
            this.destinationID = jQuery('#' + this.destinationID).attr('data-stepid');
        });
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'lfb_saveLinks',
                formID: lfb_currentFormID,
                links: JSON.stringify(cloneLinks)
            },
            success: function () {
                lfb_closeWin(jQuery('#lfb_winLink'));
                lfb_loadForm(lfb_currentFormID);
                lfb_canSaveLink = true;
            }
        });
    }

}

function lfb_linkDel() {
    if (lfb_canSaveLink) {
        lfb_canSaveLink = false;
        setTimeout(function () {
            lfb_canSaveLink = true;
        }, 1500);
        lfb_links.splice(jQuery.inArray(lfb_links[lfb_currentLinkIndex], lfb_links), 1);
        var cloneLinks = lfb_links.slice();
        jQuery.each(cloneLinks, function () {
            this.originID = jQuery('#' + this.originID).attr('data-stepid');
            this.destinationID = jQuery('#' + this.destinationID).attr('data-stepid');
        });
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'lfb_saveLinks',
                formID: lfb_currentFormID,
                links: JSON.stringify(cloneLinks)
            },
            success: function () {
                lfb_closeWin(jQuery('#lfb_winLink'));
                lfb_loadForm(lfb_currentFormID);
            }
        });
    }
}

function lfb_conditionGetOperators(item, $select) {
    var options = new Array();
    switch (item.type) {
        case "totalPrice":
            options.push({
                value: 'superior',
                text: lfb_data.texts['isSuperior'],
                hasVariable: 'pricefield'
            });
            options.push({
                value: 'inferior',
                text: lfb_data.texts['isInferior'],
                hasVariable: 'pricefield'
            });
            options.push({
                value: 'equal',
                text: lfb_data.texts['isEqual'],
                hasVariable: 'pricefield'
            });
            break;
        case "totalQt":
            options.push({
                value: 'superior',
                text: lfb_data.texts['isSuperior'],
                hasVariable: 'numberfield'
            });
            options.push({
                value: 'inferior',
                text: lfb_data.texts['isInferior'],
                hasVariable: 'numberfield'
            });
            options.push({
                value: 'equal',
                text: lfb_data.texts['isEqual'],
                hasVariable: 'numberfield'
            });
            break;

        case "picture":
            options.push({
                value: 'clicked',
                text: lfb_data.texts['isSelected']
            });
            options.push({
                value: 'unclicked',
                text: lfb_data.texts['isUnselected']
            });
            options.push({
                value: 'PriceSuperior',
                text: lfb_data.texts['isPriceSuperior'],
                hasVariable: 'numberfield'
            });
            options.push({
                value: 'PriceInferior',
                text: lfb_data.texts['isPriceInferior'],
                hasVariable: 'numberfield'
            });
            options.push({
                value: 'PriceEqual',
                text: lfb_data.texts['isPriceEqual'],
                hasVariable: 'numberfield'
            });
            if (item.quantity_enabled == "1") {
                options.push({
                    value: 'QtSuperior',
                    text: lfb_data.texts['isQuantitySuperior'],
                    hasVariable: 'numberfield'
                });
                options.push({
                    value: 'QtInferior',
                    text: lfb_data.texts['isQuantityInferior'],
                    hasVariable: 'numberfield'
                });
                options.push({
                    value: 'QtEqual',
                    text: lfb_data.texts['isQuantityEqual'],
                    hasVariable: 'numberfield'
                });
            }
            break;
        case "slider":
            options.push({
                value: 'PriceSuperior',
                text: lfb_data.texts['isPriceSuperior'],
                hasVariable: 'numberfield'
            });
            options.push({
                value: 'PriceInferior',
                text: lfb_data.texts['isPriceInferior'],
                hasVariable: 'numberfield'
            });
            options.push({
                value: 'PriceEqual',
                text: lfb_data.texts['isPriceEqual'],
                hasVariable: 'numberfield'
            });
            options.push({
                value: 'QtSuperior',
                text: lfb_data.texts['isQuantitySuperior'],
                hasVariable: 'numberfield'
            });
            options.push({
                value: 'QtInferior',
                text: lfb_data.texts['isQuantityInferior'],
                hasVariable: 'numberfield'
            });
            options.push({
                value: 'QtEqual',
                text: lfb_data.texts['isQuantityEqual'],
                hasVariable: 'numberfield'
            });
            break;

        case "textfield":
            options.push({
                value: 'filled',
                text: lfb_data.texts['isFilled']
            });
            break;
        case "numberfield":
            options.push({
                value: 'filled',
                text: lfb_data.texts['isFilled']
            });
            options.push({
                value: 'superior',
                text: lfb_data.texts['isSuperior'],
                hasVariable: 'numberfield'
            });
            options.push({
                value: 'inferior',
                text: lfb_data.texts['isInferior'],
                hasVariable: 'numberfield'
            });
            options.push({
                value: 'equal',
                text: lfb_data.texts['isEqual'],
                hasVariable: 'numberfield'
            });
            break;
        case "textarea":
            options.push({
                value: 'filled',
                text: lfb_data.texts['isFilled']
            });
            break;
        case "datepicker":
            options.push({
                value: 'filled',
                text: lfb_data.texts['isFilled']
            });
            options.push({
                value: 'superior',
                text: lfb_data.texts['isSuperior'],
                hasVariable: 'datefield'
            });
            options.push({
                value: 'inferior',
                text: lfb_data.texts['isInferior'],
                hasVariable: 'datefield'
            });
            options.push({
                value: 'equal',
                text: lfb_data.texts['isEqual'],
                hasVariable: 'datefield'
            });
            break;
        case "select":
            options.push({
                value: 'equal',
                text: lfb_data.texts['isEqual'],
                hasVariable: 'select'
            });
            break;
        case "filefield":
            options.push({
                value: 'filled',
                text: lfb_data.texts['isFilled']
            });
            break;
        case "checkbox":
            options.push({
                value: 'clicked',
                text: lfb_data.texts['isSelected']
            });
            options.push({
                value: 'unclicked',
                text: lfb_data.texts['isUnselected']
            });
            options.push({
                value: 'PriceSuperior',
                text: lfb_data.texts['isPriceSuperior'],
                hasVariable: 'numberfield'
            });
            options.push({
                value: 'PriceInferior',
                text: lfb_data.texts['isPriceInferior'],
                hasVariable: 'numberfield'
            });
            options.push({
                value: 'PriceEqual',
                text: lfb_data.texts['isPriceEqual'],
                hasVariable: 'numberfield'
            });
            break;
        case "datefield":
            options.push({
                value: 'filled',
                text: lfb_data.txt_filled
            });
            options.push({
                value: 'superior',
                text: lfb_data.txt_superiorTo
            });
            options.push({
                value: 'inferior',
                text: lfb_data.txt_inferiorTo
            });
            options.push({
                value: 'equal',
                text: lfb_data.txt_equalTo
            });
            break;
        case "date":
            options.push({
                value: 'superior',
                text: lfb_data.txt_superiorTo
            });
            options.push({
                value: 'inferior',
                text: lfb_data.txt_inferiorTo
            });
            options.push({
                value: 'equal',
                text: lfb_data.txt_equalTo
            });
            break;
    }
    return options;
}


function lfb_updateWinItemPosition() {
    if (jQuery('#lfb_winStep').css('display') != 'none') {
        var $item = jQuery('#' + jQuery('#lfb_itemWindow').attr('data-item'));
        if ($item.length > 0) {
            jQuery('#lfb_itemWindow').css({
                top: $item.offset().top - jQuery('#lfb_bootstraped.lfb_bootstraped').offset().top + $item.outerHeight() + 12,
                left: $item.offset().left - jQuery('#lfb_bootstraped.lfb_bootstraped').offset().left
            });
        } else {
            jQuery('#lfb_itemWindow').fadeOut();
        }
    } else {
        jQuery('#lfb_itemWindow').fadeOut();
    }
}

function lfb_checkEmail(emailToTest) {
    if (emailToTest.indexOf("@") != "-1" && emailToTest.indexOf(".") != "-1" && emailToTest != "")
        return true;
    return false;
}


function lfb_existInDefaultStep(itemID) {
    var rep = false;
    jQuery.each(lfb_defaultStep.interactions, function () {
        var interaction = this;
        if (interaction.itemID == itemID) {
            rep = true;
        }
    });
    return rep;
}

function lfb_removeAllSteps() {
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_removeAllSteps',
            formID: lfb_currentFormID
        },
        success: function () {
            lfb_loadForm(lfb_currentFormID);
        }
    });
}

function lfb_addForm() {
    lfb_showLoader();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_addForm'
        },
        success: function (formID) {
            lfb_loadForm(formID);
        }
    });
}
function lfb_removeForm(formID) {
    lfb_showLoader();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_removeForm',
            formID: formID
        },
        success: function () {
            lfb_closeSettings();
        }
    });

}
function lfb_saveForm() {
    lfb_showLoader();
    var formData = {};
    jQuery('#lfb_formFields').find('input,select,textarea').each(function () {
        if (jQuery(this).closest('#lfb_fieldBubble').length == 0 && jQuery(this).closest('#lfb_couponsTable').length == 0 && jQuery(this).closest('#lfb_distanceValueBubble').length == 0) {
            if (!jQuery(this).is('[data-switch="switch"]')) {
                if (jQuery(this).is('[name="percentToPay"]')) {
                    if (jQuery(this).val() == 0 || jQuery(this).val() > 100) {
                        jQuery(this).val('100');
                    }
                }
                eval('formData.' + jQuery(this).attr('name') + ' = jQuery(this).val();');
            } else {
                var value = 0;
                if (jQuery(this).is(':checked')) {
                    value = 1;
                }
                eval('formData.' + jQuery(this).attr('name') + ' = value;');
            }
        }
    });

    formData.email_adminContent = jQuery('#email_adminContent').code();
    formData.email_userContent = jQuery('#email_userContent').code();
    formData.legalNoticeContent = jQuery('#lfb_legalNoticeContent').code();

    formData.action = 'lfb_saveForm';
    formData.formID = lfb_currentFormID;
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: formData,
        success: function () {
            jQuery('#lfb_loader').fadeOut();
        }
    });
}
function lfb_editField(fieldID) {
    jQuery('#lfb_fieldBubble').find('input,textarea').val('');
    jQuery('#lfb_fieldBubble').find('select option').removeAttr('selected');
    jQuery('#lfb_fieldBubble').find('select option:eq(0)').attr('selected', 'selected');
    if (fieldID > 0) {
        jQuery.each(lfb_currentForm.fields, function () {
            var field = this;
            if (field.id == fieldID) {
                jQuery('#lfb_fieldBubble').find('input,select,textarea').each(function () {
                    eval('jQuery(this).val(field.' + jQuery(this).attr('name') + ');');
                });
            }
        });
        jQuery('#lfb_fieldBubble').css({
            left: jQuery('#lfb_finalStepFields tr[data-fieldid="' + fieldID + '"] td:eq(0) a').offset().left,
            top: jQuery('#lfb_finalStepFields tr[data-fieldid="' + fieldID + '"] td:eq(0) a').offset().top
        });
    } else {
        jQuery('#lfb_fieldBubble').find('input[name="id"]').val(0);
        jQuery('#lfb_fieldBubble').css({
            left: jQuery('#lfb_addFieldBtn').offset().left,
            top: jQuery('#lfb_addFieldBtn').offset().top + 18
        });
    }
    jQuery('#lfb_fieldBubble').fadeIn();
    jQuery('#lfb_fieldBubble').addClass('lfb_hover');
    setTimeout(function () {
        jQuery('#lfb_fieldBubble').removeClass('lfb_hover');
    }, 50);

}
function lfb_saveField() {
    lfb_showLoader();
    jQuery('#lfb_fieldBubble').fadeOut();
    var fieldData = {};
    jQuery('#lfb_fieldBubble').find('input,select,textarea').each(function () {
        eval('fieldData.' + jQuery(this).attr('name') + ' = jQuery(this).val();');
    });
    fieldData.action = 'lfb_saveField';
    fieldData.formID = lfb_currentFormID;
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: fieldData,
        success: function () {
            lfb_loadFields();
        }
    });
}
function lfb_loadFields() {
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_loadFields',
            formID: lfb_currentFormID
        },
        success: function (fields) {
            jQuery('#lfb_finalStepFields table tbody').html('');
            if (fields != "[]") {
                fields = JSON.parse(fields);
                lfb_currentForm.fields = fields;
                jQuery.each(fields, function () {
                    var field = this;
                    var $tr = jQuery('<tr data-fieldid="' + field.id + '"></tr>');
                    $tr.append('<td><a href="javascript:" onclick="lfb_editField(' + field.id + ');">' + field.label + '</a></td>');
                    $tr.append('<td>' + field.typefield + '</td>');
                    $tr.append('<td>' +
                            '<a href="javascript:" onclick="lfb_editField(' + field.id + ');" class="btn btn-primary btn-circle"><span class="glyphicon glyphicon-pencil"></span></a>' +
                            '<a href="javascript:" onclick="lfb_removeField(' + field.id + ');" class="btn btn-danger btn-circle"><span class="glyphicon glyphicon-trash"></span></a>' +
                            '</td>');
                    jQuery('#lfb_finalStepFields table tbody').append($tr);
                    jQuery('#lfb_loader').fadeOut();

                });
            }
        }
    });
}
function lfb_removeField(fieldID) {
    jQuery('#lfb_finalStepFields table tr[data-fieldid="' + fieldID + '"]').slideUp();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_removeField',
            fieldID: fieldID
        }
    });
}
function lfb_loadForm(formID) {
    lfb_currentFormID = formID;
    jQuery('#lfb_btnLogsForm').attr('data-formid', formID);
    lfb_showLoader();
    jQuery('#lfb_stepsContainer .lfb_stepBloc,.lfb_loadSteps,.lfb_linkPoint').remove();
    jQuery('#lfb_formFields').find('.switch [data-switch="switch"]').bootstrapSwitch('destroy');
    jQuery('#lfb_formFields').find('.switch > div > :not([data-switch="switch"])').remove();
    jQuery('#lfb_formFields').find('.switch [data-switch="switch"]').unwrap().unwrap();
    jQuery('#lfb_formFields').find('#lfb_itemPricesGrid tbody tr').not('.static').remove();
    lfb_loadFields();
    jQuery('#lfb_logsBtn').attr('data-formid', formID);
    jQuery('#lfb_chartsBtn').attr('data-formid', formID);
    
    jQuery('#lfb_btnPreview').attr('href', lfb_data.websiteUrl + '?lfb_action=preview&form=' + formID);
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_loadForm',
            formID: formID
        },
        success: function (rep) {

            rep = JSON.parse(rep);
            lfb_currentForm = rep;
            lfb_params = rep.params;
            lfb_steps = rep.steps;
            jQuery('#lfb_formFields').find('input,select,textarea').each(function () {
                if (jQuery(this).is('[data-switch="switch"]')) {
                    var value = false;
                    eval('if(rep.form.' + jQuery(this).attr('name') + ' == 1){jQuery(this).attr(\'checked\',\'checked\');} else {jQuery(this).attr(\'checked\',false);}');
                    jQuery(this).wrap('<div class="switch" data-on-label="' + lfb_data.texts['Yes'] + '" data-off-label="' + lfb_data.texts['No'] + '" />').parent().bootstrapSwitch({onLabel: lfb_data.texts['Yes'], offLabel: lfb_data.texts['No']});
                    var self = this;
                    if (jQuery(self).closest('.form-group').find('small').length > 0) {
                        jQuery(self).closest('.has-switch').tooltip({
                            title: jQuery(self).closest('.form-group').find('small').html()
                        });
                    }
                } else {
                    eval('jQuery(this).val(rep.form.' + jQuery(this).attr('name') + ');');
                }
            });

            lfb_initFormsBackend();

            jQuery('#lfb_itemRichText').summernote({
                height: 300,
                minHeight: null,
                maxHeight: null,
            });
            jQuery('#lfb_tabEmail').show();
            jQuery('#email_adminContent').summernote({
                height: 300,
                minHeight: null,
                maxHeight: null,
            });
            jQuery('#email_adminContent').code(rep.form.email_adminContent);
            jQuery('#lfb_formEmailUser').show();
            jQuery('#email_userContent').summernote({
                height: 300,
                minHeight: null,
                maxHeight: null,
            });
            jQuery('#email_userContent').code(rep.form.email_userContent);

            jQuery('#lfb_legalNoticeContent').summernote({
                height: 300,
                minHeight: null,
                maxHeight: null,
            });
            jQuery('#lfb_legalNoticeContent').code(rep.form.legalNoticeContent);

            jQuery('.imageBtn').click(function () {
                lfb_formfield = jQuery(this).prev('input');
                tb_show('', 'media-upload.php?TB_iframe=true');
                return false;
            });

            if (!jQuery('#lfb_formFields [name="email_toUser"]').is(':checked')) {
                jQuery('#lfb_formEmailUser').hide();
            }
            jQuery('#lfb_tabEmail').attr('style', '');
            jQuery('#lfb_tabEmail').prop('style', '');

            jQuery('.colorpick').each(function () {
                var $this = jQuery(this);
                if (jQuery(this).prev('.lfb_colorPreview').length == 0) {
                    jQuery(this).before('<div class="lfb_colorPreview" style="background-color:#' + $this.val().substr(1, 7) + '"></div>');
                }
                jQuery(this).prev('.lfb_colorPreview').click(function () {
                    jQuery(this).next('.colorpick').trigger('click');
                });
                jQuery(this).colpick({
                    color: $this.val().substr(1, 7),
                    onChange: function (hsb, hex, rgb, el, bySetColor) {
                        jQuery(el).val('#' + hex);
                        jQuery(el).prev('.lfb_colorPreview').css({
                            backgroundColor: '#' + hex
                        });
                    }
                });
            });

            jQuery.each(rep.steps, function (index) {
                var step = this;
                step.content = JSON.parse(step.content);
                lfb_addStep(step);
            });
            jQuery.each(rep.links, function (index) {
                var link = this;
                link.originID = jQuery('.lfb_stepBloc[data-stepid="' + link.originID + '"]').attr('id');
                link.destinationID = jQuery('.lfb_stepBloc[data-stepid="' + link.destinationID + '"]').attr('id');
                link.conditions = JSON.parse(link.conditions);
                lfb_links[index] = link;
            });
            
            jQuery.each(rep.redirections, function (index) {
                var tr = jQuery('<tr data-id="'+this.id+'"></tr>');
                tr.append('<td>'+this.url+'</td>');
                tr.append('<td><a href="javascript:" onclick="lfb_editRedirection(' + this.id + ');" class="btn btn-primary btn-circle"><span class="glyphicon glyphicon-pencil"></span></a><a href="javascript:" onclick="lfb_removeRedirection(' + this.id + ');" class="btn btn-danger btn-circle"><span class="glyphicon glyphicon-trash"></span></a></td>');

                jQuery('#lfb_redirsTable tbody').append(tr);
            });
           // lfb_redirConditionsTable
            
            jQuery('#lfb_panelPreview').show();
            jQuery('#lfb_panelFormsList').hide();
            jQuery('#lfb_panelLogs').hide();
            jQuery('#lfb_panelSettings').hide();

            jQuery('#lfb_couponsTable tbody').html('');
            jQuery.each(rep.coupons, function () {
                var coupon = this;

                if (coupon.reductionType == 'percentage') {
                    coupon.reduction = '-' + coupon.reduction + '%';
                } else {
                    coupon.reduction = '-' + parseFloat(coupon.reduction).toFixed(2);
                }

                var tdAction = '<td style="text-align:right;">' +
                        '<a href="javascript:" onclick="lfb_editCoupon(' + coupon.id + ');" class="btn btn-primary btn-circle"><span class="glyphicon glyphicon-pencil"></span></a>' +
                        '<a href="javascript:" onclick="lfb_removeCoupon(' + coupon.id + ');" class="btn btn-danger btn-circle"><span class="glyphicon glyphicon-trash"></span></a>' +
                        '</td>';
                jQuery('#lfb_couponsTable tbody').append('<tr data-couponid="' + coupon.id + '"><td>' + coupon.couponCode + '</td><td>' + coupon.useMax + '</td><td>' + coupon.currentUses + '</td><td>' + coupon.reduction + '</td>' + tdAction + '</tr>');
            });

            lfb_updateStepsDesign();
            
            if(lfb_openChartsAuto){
                lfb_openChartsAuto = false;
                lfb_loadCharts(formID);
            } else {
                jQuery('#lfb_loader').delay(1000).fadeOut();
            }
            setTimeout(function () {
                lfb_updateStepsDesign();
            }, 250);
        }
    });
}
function lfb_initCharts() {
    google.charts.load('current', {'packages': ['corechart']});
}
function lfb_openCharts(formID){
    lfb_openChartsAuto = true;
    lfb_loadForm(formID);    
}
function lfb_closeCharts(){    
    lfb_showLoader();
    jQuery('#lfb_panelPreview').show();
    jQuery('#lfb_panelCharts').hide(); 
    lfb_loadForm(jQuery('#lfb_panelCharts').attr('data-formid'));
}
function lfb_loadCharts(formID) {
    jQuery('#lfb_panelCharts').attr('data-formid', formID);
    jQuery('#lfb_panelPreview').hide();
    jQuery('#lfb_panelFormsList').hide();
            jQuery('#lfb_panelLogs').hide();
    
    var mode = jQuery('#lfb_chartsTypeSelect').val();
    var year = jQuery('#lfb_chartsYear').val();
    var month = jQuery('#lfb_chartsMonth').val();

    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_loadCharts',
            formID: formID,
            mode: mode,
            year: year,
            yearMonth: month
        },
        success: function (rep) {    
            jQuery('#lfb_panelCharts').show();        
                var rowsPrice = [];
                rep = rep.split('|');
                jQuery.each(rep, function () {
                    if(this.indexOf(';')>-1){
                        var row = this.split(';');
                        if (row[2] > 0) {
                            chkSubs = true;
                        }
                        rowsPrice.push([row[0].toString(), parseFloat(row[1]), parseFloat(row[2])]);
                    }
                });                

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(function () {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'X');
                data.addColumn('number', lfb_data.texts['oneTimePayment']);
                data.addColumn('number', lfb_data.texts['subscriptions']);
                
                var prefixCurrency = '';
                var suffixCurrency = '';
                if(jQuery('#lfb_formFields [name="currencyPosition"]').val() == 'right'){
                    suffixCurrency = jQuery('#lfb_formFields [name="currency"]').val();
                }else {
                    prefixCurrency = jQuery('#lfb_formFields [name="currency"]').val();                    
                }
                var decimalSymbol = jQuery('#lfb_formFields [name="decimalsSeparator"]').val();
                var thousandsSeparator = jQuery('#lfb_formFields [name="thousandsSeparator"]').val();
                var millionSeparator = jQuery('#lfb_formFields [name="millionSeparator"]').val();
                var columnFormat = prefixCurrency+'###'+millionSeparator+'###'+thousandsSeparator+'###'+decimalSymbol+'00'+suffixCurrency;
                    
                var formatter = new google.visualization.NumberFormat({
                    prefix: prefixCurrency,
                    suffix: suffixCurrency,
                });

                var options = {
                    hAxis: {
                        title: lfb_data.texts['months'],
                        
                    },
                    vAxis: {
                        title: lfb_data.texts['amountOrders'],
                        format: columnFormat,
                        viewWindow:{
                        min:0
                      }
                    },
                    legend: { position: 'bottom' },
                    backgroundColor: '#FFFFFF',
                    colors: ['#16a085', '#e67e22', '#95a5a6', '#34495e'],
                    width: jQuery('#lfb_charts').parent().width(),
                    height: 550
                };
                data.addRows(rowsPrice);
                
                formatter.format(data, 1);
                formatter.format(data, 2);
                lfb_currentChartsOptions = options;
                lfb_currentChartsData = data;

                var chart = new google.visualization.LineChart(document.getElementById('lfb_charts'));
                lfb_currentCharts = chart;
                chart.draw(data, options);
                
                jQuery(window).resize(function(){
                    var data = lfb_currentChartsData;
                    var options = lfb_currentChartsOptions;
                    options.width = jQuery('#lfb_charts').parent().width();                    
                    lfb_currentCharts.draw(data, options);                    
                });
                jQuery('#lfb_loader').fadeOut();

       // }
        
        });
    }
    });

}
function lfb_loadLogs(formID) {
    lfb_showLoader();
    jQuery('#lfb_panelLogs').attr('data-formid', formID);
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_loadLogs',
            formID: formID
        },
        success: function (rep) {
            jQuery('#lfb_logsTable tbody').html(rep);
            jQuery('#lfb_panelPreview').hide();
            jQuery('#lfb_panelFormsList').hide();
            jQuery('#lfb_panelCharts').hide();  
            jQuery('#lfb_panelLogs').show();
            jQuery('#lfb_logsTable tbody [data-toggle="tooltip"]').tooltip();
            jQuery('#lfb_loader').fadeOut();
        }
    });
}
function lfb_loadLog(logID) {
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_loadLog',
            logID: logID
        },
        success: function (rep) {
            jQuery('#lfb_winLog').find('.modal-body').html(rep);
            jQuery('#lfb_winLog').find('.modal-body [bgcolor]').each(function () {
                jQuery(this).css({
                    backgroundColor: jQuery(this).attr('bgcolor')
                });
            });
            jQuery('#lfb_winLog').modal('show');
        }
    });
}
function lfb_removeLog(logID, formID) {
    lfb_showLoader();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_removeLog',
            logID: logID
        },
        success: function () {
            lfb_loadLogs(formID);
        }
    });
}
function lfb_exportForms() {
    lfb_showLoader();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_exportForms'
        },
        success: function (rep) {
            jQuery('#lfb_loader').fadeOut();
            if (rep == '1') {
                jQuery('#lfb_winExport').modal('show');
            } else {
                alert(lfb_data.texts['errorExport']);
            }
        }
    });

}
function lfb_importForms() {
    lfb_showLoader();
    jQuery('#lfb_winImport').modal('hide');
    var formData = new FormData(jQuery('#lfb_winImportForm')[0]);

    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        xhr: function () {
            var myXhr = jQuery.ajaxSettings.xhr();
            return myXhr;
        },
        success: function (rep) {
            if (rep != '1') {
                jQuery('#lfb_loader').fadeOut();
                alert(lfb_data.texts['errorImport']);
            } else {
                document.location.href = document.location.href;
            }
        },
        data: formData,
        cache: false,
        contentType: false,
        processData: false
    });
}

function lfb_editCoupon(couponID) {
    var couponCode = '';
    var useMax = 1;
    var reduction = 0;
    if (couponID > 0) {
        couponCode = jQuery('#lfb_couponsTable tbody tr[data-couponid="' + couponID + '"] td:eq(0)').html();
        useMax = jQuery('#lfb_couponsTable tbody tr[data-couponid="' + couponID + '"] td:eq(1)').html();
        reduction = jQuery('#lfb_couponsTable tbody tr[data-couponid="' + couponID + '"] td:eq(3)').html();
        reduction = reduction.substr(1, reduction.length);
        if (reduction.substr(reduction.length - 1, 1) == '%') {
            reduction = reduction.substr(0, reduction.length - 1);
        }
    }

    jQuery('#lfb_winEditCoupon .form-group').removeClass('has-error');
    jQuery('#lfb_winEditCoupon').attr('data-couponid', couponID);
    jQuery('#lfb_winEditCoupon [name="couponCode"]').val(couponCode);
    jQuery('#lfb_winEditCoupon [name="useMax"]').val(useMax);
    jQuery('#lfb_winEditCoupon [name="reduction"]').val(reduction);
    jQuery('#lfb_winEditCoupon').modal('show');
}

function lfb_removeCoupon(couponID) {
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_removeCoupon',
            formID: lfb_currentFormID,
            couponID: couponID
        },
        success: function () {
            jQuery('#lfb_couponsTable tbody tr[data-couponid="' + couponID + '"]').slideUp();
            setTimeout(function () {
                jQuery('#lfb_couponsTable tbody tr[data-couponid="' + couponID + '"]').remove();
            }, 300);
        }
    });
}
function lfb_removeAllCoupons() {
    jQuery('#lfb_couponsTable tbody').html('');
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_removeAllCoupons',
            formID: lfb_currentFormID
        },
        success: function () {

        }
    });
}

function lfb_saveCoupon() {
    var couponID = jQuery('#lfb_winEditCoupon').attr('data-couponid');
    jQuery('#lfb_winEditCoupon .form-group').removeClass('has-error');

    var error = false;
    if (jQuery('#lfb_winEditCoupon [name="couponCode"]').val().length < 3) {
        error = true;
        jQuery('#lfb_winEditCoupon [name="couponCode"]').closest('.form-group').addClass('has-error');
    }
    if (!error) {
        var couponCode = jQuery('#lfb_winEditCoupon [name="couponCode"]').val();
        var useMax = jQuery('#lfb_winEditCoupon [name="useMax"]').val();
        var reduction = jQuery('#lfb_winEditCoupon [name="reduction"]').val();
        var reductionType = jQuery('#lfb_winEditCoupon [name="reductionType"]').val();
        if (reduction == "" || isNaN(reduction)) {
            reduction = 0;
        }
        if (reduction < 0) {
            reduction *= -1;
        }

        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'lfb_saveCoupon',
                formID: lfb_currentFormID,
                couponID: couponID,
                couponCode: jQuery('#lfb_winEditCoupon [name="couponCode"]').val(),
                useMax: jQuery('#lfb_winEditCoupon [name="useMax"]').val(),
                reduction: jQuery('#lfb_winEditCoupon [name="reduction"]').val(),
                reductionType: jQuery('#lfb_winEditCoupon [name="reductionType"]').val()
            },
            success: function (rep) {
                jQuery('#lfb_winEditCoupon').modal('hide');

                if (reductionType == 'percentage') {
                    reduction = '-' + reduction + '%';
                } else {
                    reduction = '-' + parseFloat(reduction).toFixed(2);
                }

                if (couponID == 0) {
                    var tdAction = '<td>' +
                            '<a href="javascript:" onclick="lfb_editCoupon(' + rep + ');" class="btn btn-primary btn-circle"><span class="glyphicon glyphicon-pencil"></span></a>' +
                            '<a href="javascript:" onclick="lfb_removeCoupon(' + rep + ');" class="btn btn-danger btn-circle"><span class="glyphicon glyphicon-trash"></span></a>' +
                            '</td>';
                    jQuery('#lfb_couponsTable tbody').append('<tr data-couponid="' + rep + '"><td>' + couponCode + '</td><td>' + useMax + '</td><td>0</td><td>' + reduction + '</td>' + tdAction + '</tr>');

                } else {
                    jQuery('#lfb_couponsTable tbody tr[data-couponid="' + couponID + '"] td:eq(0)').html(couponCode);
                    jQuery('#lfb_couponsTable tbody tr[data-couponid="' + couponID + '"] td:eq(1)').html(useMax);
                    jQuery('#lfb_couponsTable tbody tr[data-couponid="' + couponID + '"] td:eq(3)').html(reduction);
                }

            }
        });
    }
}
function lfb_addCalculationValue() {
    jQuery('#lfb_calculationValueBubble').find('select,textarea,input').val('');
    lfb_updateCalculationsValueItems();
    jQuery('#lfb_calculationValueBubble').css({
        left: jQuery('#lfb_winItem').find('[name="calculation"]').closest('.form-group').offset().left,
        top: jQuery('#lfb_winItem').find('[name="calculation"]').closest('.form-group').offset().top + 18
    });
    jQuery('#lfb_calculationValueBubble').fadeIn();
    jQuery('#lfb_calculationValueBubble').addClass('lfb_hover');
    lfb_updateCalculationsValueElements();
}
function lfb_updateCalculationsValueItems() {
    var $selectItem = jQuery('#lfb_calculationValueBubble select[name="itemID"]');
    $selectItem.html('');
    jQuery.each(lfb_steps, function () {
        var step = this;
        jQuery.each(step.items, function () {
            var item = this;
            if (item.type == 'picture' || item.type == 'checkbox' || item.type == 'numberfield' || item.type == 'select' || item.type == 'slider') {
                var itemID = item.id;
                $selectItem.append('<option value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
            }
        });
    });
    $selectItem.append('<option value="_total" data-type="totalPrice">' + lfb_data.texts['totalPrice'] + '</option>');
    $selectItem.append('<option value="_total_qt" data-type="totalQt">' + lfb_data.texts['totalQuantity'] + '</option>');
}
function lfb_updateCalculationsValueElements() {
    var $selectItem = jQuery('#lfb_calculationValueBubble select[name="itemID"]');
    var $selectElement = jQuery('#lfb_calculationValueBubble select[name="element"]');
    $selectElement.val('');
    $selectElement.find('option[value="quantity"]').hide();
    $selectElement.find('option[value=""]').show();
    if ($selectItem.val() != "") {
        var selectedItemID = $selectItem.val();
        jQuery.each(lfb_currentForm.steps, function () {
            jQuery.each(this.items, function () {
                if (this.id == selectedItemID) {
                    if (this.quantity_enabled == 1 || this.type == 'slider') {
                        $selectElement.find('option[value="quantity"]').show();
                    } else {
                        $selectElement.find('option[value="quantity"]').hide();
                    }
                    if (this.type == 'numberfield') {
                        $selectElement.find('option[value="value"]').show();
                        $selectElement.find('option[value=""]').hide();
                        $selectElement.val('value');
                    } else {
                        $selectElement.find('option[value="value"]').hide();
                        $selectElement.find('option[value=""]').show();
                    }
                }
            });
        });
        if ($selectItem.val() == "_total_qt") {
            $selectElement.find('option[value="quantity"]').show();
            $selectElement.find('option[value=""]').hide();
            $selectElement.val('quantity');

        }
    }
}

function lfb_addDistanceCondition() {
    
    jQuery('#lfb_winDistances').fadeIn();
}

function lfb_saveCalculationValue() {
    var $selectItem = jQuery('#lfb_calculationValueBubble select[name="itemID"]');
    var $selectElement = jQuery('#lfb_calculationValueBubble select[name="element"]');
    var attribute = 'price';
    if ($selectElement.val() != "") {
        attribute = $selectElement.val();
    }
    var itemTag = '[item-' + $selectItem.val() + '_' + attribute + ']';
    if ($selectItem.val() == '_total') {
        itemTag = '[total]';
    }
    if ($selectItem.val() == '_total_qt') {
        itemTag = '[total_quantity]';
    }
    var posCar = jQuery('#lfb_winItem').find('[name="calculation"]').prop("selectionStart");
    var value = jQuery('#lfb_winItem').find('[name="calculation"]').val();
    if (isNaN(posCar)) {
        posCar == value.length;
    }
    var newValue = value.substr(0, posCar) + ' ' + itemTag + ' ' + value.substr(posCar, value.length);

    jQuery('#lfb_winItem').find('[name="calculation"]').val(newValue);
    jQuery('#lfb_calculationValueBubble').fadeOut();
}

function lfb_addCalculationCondition() {
    jQuery('#lfb_winCalculationConditions #lfb_calcConditionsTable tbody').html('');    
    jQuery("body,html").animate({
        scrollTop: 0
    }, 200);
    jQuery('#lfb_winCalculationConditions').fadeIn();
}
function lfb_calcConditionSave() {
    var conditionString = 'if(';
    if (jQuery('#lfb_winItem').find('[name="calculation"]').val().length > 0) {
        conditionString = "\n" + conditionString;
    }
    var operator = '&&';
    if (jQuery('#lfb_calcOperator').val() == 'OR') {
        operator = '||';
    }
    jQuery('#lfb_winCalculationConditions #lfb_calcConditionsTable tbody tr.lfb_conditionItem').each(function () {
        var tr = this;
        var itemID = jQuery(tr).find('.lfb_conditionSelect').val();
        if (itemID != '_total' && itemID != '_total_qt') {
            itemID = 'item-' + itemID.substr(itemID.indexOf('_') + 1, itemID.length);
        }
        if (jQuery(tr).find('.lfb_conditionoperatorSelect ').val().substr(0, 2) == 'Qt') {
            conditionString += '([' + itemID + '_quantity]';
            if (jQuery(tr).find('.lfb_conditionoperatorSelect ').val() == 'QtSuperior') {
                conditionString += ' >';
            } else if (jQuery(tr).find('.lfb_conditionoperatorSelect ').val() == 'QtInferior') {
                conditionString += ' <';
            } else {
                conditionString += ' ==';
            }
            conditionString += jQuery(tr).find('.lfb_conditionValue').val();
        } else if (jQuery(tr).find('.lfb_conditionoperatorSelect ').val().substr(0, 5) == 'Price') {
            conditionString += '([' + itemID + '_price]';
            if (jQuery(tr).find('.lfb_conditionoperatorSelect ').val() == 'PriceSuperior') {
                conditionString += ' >';
            } else if (jQuery(tr).find('.lfb_conditionoperatorSelect ').val() == 'PriceInferior') {
                conditionString += ' <';
            } else {
                conditionString += ' ==';
            }
            conditionString += jQuery(tr).find('.lfb_conditionValue').val();
        } else if (jQuery(tr).find('.lfb_conditionoperatorSelect ').val() == 'clicked') {
            conditionString += '([' + itemID + '_isChecked]';
        } else if (jQuery(tr).find('.lfb_conditionoperatorSelect ').val() == 'unclicked') {
            conditionString += '([' + itemID + '_isUnchecked]';
        } else if (jQuery(tr).find('.lfb_conditionoperatorSelect ').val() == 'superior') {
            if (itemID == '_total') {
                conditionString += '([total]';
                conditionString += ' >';
                conditionString += jQuery(tr).find('.lfb_conditionValue').val();
            } else if (itemID == '_total_qt') {
                conditionString += '([total_quantity]';
                conditionString += ' >';
                conditionString += jQuery(tr).find('.lfb_conditionValue').val();
            } else if (jQuery(tr).find('.lfb_conditionSelect option[value="' + jQuery(tr).find('.lfb_conditionSelect').val() + '"]').attr('data-type') == 'select') {
                conditionString += '([' + itemID + '_value]';
                conditionString += ' >';
                conditionString += '\'' + jQuery(tr).find('.lfb_conditionValue').val() + '\'';
            } else if (jQuery(tr).find('.lfb_conditionSelect option[value="' + jQuery(tr).find('.lfb_conditionSelect').val() + '"]').attr('data-type') == 'numberfield') {
             conditionString += '([' + itemID + '_value]';
                conditionString += ' >';
                conditionString += jQuery(tr).find('.lfb_conditionValue').val();
            } else {
                conditionString += '([' + itemID + '_date]';
                conditionString += ' >';
                conditionString += "'" + jQuery(tr).find('.lfb_conditionValue').val() + "'";
            }
        } else if (jQuery(tr).find('.lfb_conditionoperatorSelect').val() == 'inferior') {
            if (itemID == '_total') {
                conditionString += '([total]';
                conditionString += ' <';
                conditionString += jQuery(tr).find('.lfb_conditionValue').val();
            } else if (itemID == '_total_qt') {
                conditionString += '([total_quantity]';
                conditionString += ' <';
                conditionString += jQuery(tr).find('.lfb_conditionValue').val();
            }  else if (jQuery(tr).find('.lfb_conditionSelect option[value="' + jQuery(tr).find('.lfb_conditionSelect').val() + '"]').attr('data-type') == 'select') {
                conditionString += '([' + itemID + '_value]';
                conditionString += ' <';
                conditionString += '\'' + jQuery(tr).find('.lfb_conditionValue').val() + '\'';
            } else if (jQuery(tr).find('.lfb_conditionSelect option[value="' + jQuery(tr).find('.lfb_conditionSelect').val() + '"]').attr('data-type') == 'numberfield') {
                 conditionString += '([' + itemID + '_value]';
                conditionString += ' <';
                conditionString += jQuery(tr).find('.lfb_conditionValue').val();
            }else {
                conditionString += '([' + itemID + '_date]';
                conditionString += ' <';
                conditionString += '"' + jQuery(tr).find('.lfb_conditionValue').val() + '"';
            }
        } else if (jQuery(tr).find('.lfb_conditionoperatorSelect').val() == 'equal') {
            if (itemID == '_total') {
                conditionString += '([total]';
                conditionString += ' ==';
                conditionString += jQuery(tr).find('.lfb_conditionValue').val();
            } else if (itemID == '_total_qt') {
                conditionString += '([total_quantity]';
                conditionString += ' ==';
                conditionString += jQuery(tr).find('.lfb_conditionValue').val();
            } else if (jQuery(tr).find('.lfb_conditionSelect option[value="' + jQuery(tr).find('.lfb_conditionSelect').val() + '"]').attr('data-type') == 'select') {
                conditionString += '([' + itemID + '_value]';
                conditionString += ' ==';
                conditionString += '\'' + jQuery(tr).find('.lfb_conditionValue').val() + '\'';
            } else if (jQuery(tr).find('.lfb_conditionSelect option[value="' + jQuery(tr).find('.lfb_conditionSelect').val() + '"]').attr('data-type') == 'numberfield') {
             conditionString += '([' + itemID + '_value]';
                conditionString += ' ==';
                conditionString += jQuery(tr).find('.lfb_conditionValue').val();
            } else {
                conditionString += '([' + itemID + '_date]';
                conditionString += ' ==';
                conditionString += jQuery(tr).find('.lfb_conditionValue').val();
            }
        }
        else if (jQuery(tr).find('.lfb_conditionoperatorSelect').val() == 'filled') {
            conditionString += '([' + itemID + '_isFilled]';
        }
        conditionString += ') ' + operator;
    });
    conditionString = conditionString.substr(0, conditionString.length - 2);
    conditionString += ') {' + "\n" + "\n" + '}';
    var posCar = jQuery('#lfb_winItem').find('[name="calculation"]').prop("selectionStart");
    var value = jQuery('#lfb_winItem').find('[name="calculation"]').val();
    if (isNaN(posCar)) {
        posCar == value.length;
    }
    var newValue = value.substr(0, posCar) + ' ' + conditionString + ' ' + value.substr(posCar, value.length);

    jQuery('#lfb_winItem').find('[name="calculation"]').val(newValue);
    jQuery('#lfb_winCalculationConditions').fadeOut();
}


function lfb_addEmailValue(customerMode){    
    jQuery('#lfb_emailValueBubble').find('select,textarea,input').val('');
    lfb_updateEmailValueItems();
    var target = '#lfb_btnAddEmailValue';
    if(customerMode){
        jQuery('#lfb_emailValueBubble').attr('data-customermode','1');
        target = '#lfb_btnAddEmailValueCustomer';
    } else {        
        jQuery('#lfb_emailValueBubble').attr('data-customermode','0');
    }
    jQuery('#lfb_emailValueBubble').css({
        left: jQuery(target).offset().left-80,
        top: jQuery(target).offset().top + 18
    });
    jQuery('#lfb_emailValueBubble').fadeIn();
    jQuery('#lfb_emailValueBubble').addClass('lfb_hover');
    lfb_updateEmailValueElements();
}
function lfb_updateEmailValueElements(){
     var $selectItem = jQuery('#lfb_emailValueBubble select[name="itemID"]');
    var $selectElement = jQuery('#lfb_emailValueBubble select[name="element"]');
    $selectElement.val('');
    $selectElement.find('option[value="quantity"]').hide();
    $selectElement.find('option[value=""]').show();
    if ($selectItem.val() != "") {
        var selectedItemID = $selectItem.val();
        jQuery.each(lfb_currentForm.steps, function () {
            jQuery.each(this.items, function () {
                if (this.id == selectedItemID) {
                    if (this.quantity_enabled == 1 || this.type == 'slider') {
                        $selectElement.find('option[value="quantity"]').show();
                    } else {
                        $selectElement.find('option[value="quantity"]').hide();
                    }
                    if (this.type == 'numberfield'||this.type == 'textfield' || this.type == 'textarea' || this.type == 'select'|| this.type == 'colorpicker'|| this.type == 'datepicker') {
                        $selectElement.find('option[value="value"]').show();
                        $selectElement.find('option[value=""]').hide();
                        $selectElement.val('value');
                    } else {
                        $selectElement.find('option[value="value"]').hide();
                        $selectElement.find('option[value=""]').show();
                    }
                }
            });
        });
    }
}
function lfb_updateEmailValueItems() {
    var $selectItem = jQuery('#lfb_emailValueBubble select[name="itemID"]');
    $selectItem.html('');
    jQuery.each(lfb_steps, function () {
        var step = this;
        jQuery.each(step.items, function () {
            var item = this;
            if (this.type == 'numberfield'||this.type == 'textfield' || this.type == 'textarea' || this.type == 'select'|| this.type == 'colorpicker'|| this.type == 'datepicker') {
                var itemID = item.id;
                $selectItem.append('<option value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
            }
        });        
    });
    jQuery.each(lfb_currentForm.fields,function(){
        $selectItem.append('<option value="f' + this.id + '" data-type="textfield">' + lfb_data.texts['lastStep'] + ' : " ' + this.label + ' "</option>');            
    });
}

function lfb_saveEmailValue() {
    var $selectItem = jQuery('#lfb_emailValueBubble select[name="itemID"]');
    var $selectElement = jQuery('#lfb_emailValueBubble select[name="element"]');
    var target = '#email_adminContent';
    if(jQuery('#lfb_emailValueBubble').attr('data-customermode') == '1'){
        target = '#email_userContent';
    }
    var attribute = 'value';
    if ($selectElement.val() != "") {
        attribute = $selectElement.val();
    }
    var itemTag = '[item-' + $selectItem.val() + '_' + attribute + ']';
    
    jQuery(target).summernote('editor.focus');
    jQuery(target).summernote('editor.insertText', itemTag);

  //  jQuery('#lfb_winItem').find('[name="calculation"]').val(newValue);
    jQuery('#lfb_emailValueBubble').fadeOut();
}

function lfb_exportLogs() {
    var logID = jQuery('#lfb_panelLogs').attr('data-formid');
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_exportLogs',
            formID: logID
        },
        success: function (rep) {
            if (rep != 'error') {
                window.open(
                        rep,
                        '_blank'
                        );
            }
        }
    });
}
function lfb_editShowStepConditions() {
    jQuery("#lfb_winShowStepConditions #lfb_showStepOperator").val(jQuery("#lfb_winStep").find('[name="showConditionsOperator"]').val());
    jQuery("#lfb_winShowStepConditions #lfb_showStepConditionsTable tbody").html("");
    if (jQuery("#lfb_winStep").find('[name="showConditions"]').val() != '') {
        try {
            var conditions = JSON.parse(jQuery("#lfb_winStep").find('[name="showConditions"]').val());
            jQuery.each(conditions, function () {
                lfb_addShowStepInteraction(this);
            });
        } catch (e) {
        }
    }
    jQuery("#lfb_winShowStepConditions").fadeIn();
    setTimeout(function () {
        jQuery("#wpwrap").css({
            height: jQuery("#lfb_bootstraped").height() + 48
        });
    }, 300);
    jQuery("body,html").animate({
        scrollTop: 0
    }, 200);
}

function lfb_editShowConditions() {
    jQuery("#lfb_winShowConditions #lfb_showOperator").val(jQuery("#lfb_winItem").find('[name="showConditionsOperator"]').val());
    jQuery("#lfb_winShowConditions #lfb_showConditionsTable tbody").html("");
    if (jQuery("#lfb_winItem").find('[name="showConditions"]').val() != '') {
        try {
            var conditions = JSON.parse(jQuery("#lfb_winItem").find('[name="showConditions"]').val());
            jQuery.each(conditions, function () {
                lfb_addShowInteraction(this);
            });
        } catch (e) {
        }
    }
    jQuery("#lfb_winShowConditions").fadeIn();
    setTimeout(function () {
        jQuery("#wpwrap").css({
            height: jQuery("#lfb_bootstraped").height() + 48
        });
    }, 300);
    jQuery("body,html").animate({
        scrollTop: 0
    }, 200);
}

function lfb_showConditionSave() {
    var conditions = new Array();
    jQuery("#lfb_showConditionsTable .lfb_conditionItem").each(function () {
        var condValue = jQuery(this).find(".lfb_conditionValue").val();
        if(condValue){
            condValue =  condValue.replace(/\'/g, '`');
        }
        conditions.push({
            interaction: jQuery(this).find(".lfb_conditionSelect").val(),
            action: jQuery(this).find(".lfb_conditionoperatorSelect").val(),
            value: condValue
        });
    });
    jQuery("#lfb_winItem").find('[name="showConditionsOperator"]').val(jQuery("#lfb_showOperator").val());
    jQuery("#lfb_winItem").find('[name="showConditions"]').val(JSON.stringify(conditions));
    jQuery("#lfb_winShowConditions").fadeOut();
}
function lfb_showStepConditionSave() {
    var conditions = new Array();
    jQuery("#lfb_showStepConditionsTable .lfb_conditionItem").each(function () {
        var condValue = jQuery(this).find(".lfb_conditionValue").val();
        if(condValue){
            condValue =  condValue.replace(/\'/g, '`');
        }
        conditions.push({
            interaction: jQuery(this).find(".lfb_conditionSelect").val(),
            action: jQuery(this).find(".lfb_conditionoperatorSelect").val(),
            value: condValue
        });
    });
    jQuery("#lfb_winStep").find('[name="showConditionsOperator"]').val(jQuery("#lfb_showStepOperator").val());
    jQuery("#lfb_winStep").find('[name="showConditions"]').val(JSON.stringify(conditions));
    jQuery("#lfb_winShowStepConditions").fadeOut();
}
function lfb_selectPre(input) {
    jQuery(input).select();
}
function lfb_removeRedirection(id){
    jQuery('#lfb_redirsTable tr[data-id="'+id+'"]').remove();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_removeRedirection',
            id: id,
            formID: lfb_currentFormID
        }
    });
}


function lfb_editRedirection(id,mode) {
    lfb_currentRedirEdit = id;
    jQuery("#lfb_winRedirection #lfb_redirOperator").val(jQuery("#lfb_winItem").find('[name="showConditionsOperator"]').val());
    jQuery("#lfb_winRedirection #lfb_redirConditionsTable tbody").html("");
    jQuery("#lfb_winRedirection #lfb_redirUrl").val("");
    
    if(id>0){
        jQuery.each(lfb_currentForm.redirections,function(){
           if(this.id == id){           
              jQuery("#lfb_winRedirection #lfb_redirUrl").val(this.url);
               var conditions =this.conditions.replace(/\\"/g, '"');
               conditions =  JSON.parse(conditions);    
                jQuery.each(conditions, function () {
                    lfb_addRedirInteraction(this);
                });
           } 
        });
    }
    
    jQuery("#lfb_winRedirection").fadeIn();
    setTimeout(function () {
        jQuery("#wpwrap").css({
            height: jQuery("#lfb_bootstraped").height() + 48
        });
    }, 300);
    jQuery("body,html").animate({
        scrollTop: 0
    }, 200);
}

function lfb_redirSave() {
        
    var conditions = new Array();
        jQuery('#lfb_winRedirection #lfb_redirUrl').parent().removeClass('has-error');
    jQuery("#lfb_winRedirection .lfb_conditionItem").each(function () {
          var condValue = jQuery(this).find(".lfb_conditionValue").val();
        if(condValue){
            condValue =  condValue.replace(/\'/g, '`');
        }
        conditions.push({
            interaction: jQuery(this).find(".lfb_conditionSelect").val(),
            action: jQuery(this).find(".lfb_conditionoperatorSelect").val(),
            value: condValue
        });
    });
    var url = jQuery('#lfb_winRedirection #lfb_redirUrl').val();
    if(url.length < 1){
        jQuery('#lfb_winRedirection #lfb_redirUrl').parent().addClass('has-error');
    } else {
        var data = {
                action: 'lfb_saveRedirection',
                id: lfb_currentRedirEdit,
                url: url,
                formID: lfb_currentFormID,
                conditions: JSON.stringify(conditions),
                operator: jQuery("#lfb_redirOperator").val()
            };
            lfb_showLoader();
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: data,
            success: function(rep){            
                if(lfb_currentRedirEdit ==0){
                    data.id = rep;
                    lfb_currentForm.redirections.push(data);
                    var tr = jQuery('<tr data-id="'+this.id+'"></tr>');
                    tr.append('<td>'+data.url+'</td>');
                    tr.append('<td style="text-align:right;"><a href="javascript:" onclick="lfb_editRedirection(' + data.id + ');" class="btn btn-primary btn-circle"><span class="glyphicon glyphicon-pencil"></span></a><a href="javascript:" onclick="lfb_removeRedirection(' + data.id + ');" class="btn btn-danger btn-circle"><span class="glyphicon glyphicon-trash"></span></a></td>');

                    jQuery('#lfb_redirsTable tbody').append(tr);
                } else {
                    jQuery.each(lfb_currentForm.redirections,function(){
                       if(this.id == lfb_currentRedirEdit){
                           this.url = data.url;
                           this.conditions = data.conditions;
                           this.conditionsOperator = data.operator;
                       } 
                    });
                }
                jQuery('#lfb_loader').fadeOut();                
            }
        });
    }
    jQuery("#lfb_winRedirection").fadeOut();
}

function lfb_editDistanceValue(modeQt){
    lfb_distanceModeQt = modeQt;
     var departAdress = -1;
     var departCity = -1;
     var departZip = -1;
     var departCountry = -1;
     var arrivalAdress = -1;
     var arrivalCity = -1;
     var arrivalZip = -1;
     var arrivalCountry = -1;
     var distanceType = 'km';
     
    if(modeQt){
        var distCode = jQuery('#lfb_winItem [name="distanceQt"]').val();
        if(distCode.indexOf('distance_')>-1){
            var i =-1;
            while ((i = distCode.indexOf('distance_', i + 1)) != -1) {
             
         var departAdPosEnd = distCode.indexOf('-', i+9)+1;
             departAdress = distCode.substr(i + 9, distCode.indexOf('-', i) - (i + 9));
         
            var departCityPosEnd = distCode.indexOf('-', departAdPosEnd)+1;
             departCity = distCode.substr(departAdPosEnd, distCode.indexOf('-', departAdPosEnd) - (departAdPosEnd));

            var departZipPosEnd = distCode.indexOf('-', departCityPosEnd)+1;
             departZip = distCode.substr(departCityPosEnd, distCode.indexOf('-', departCityPosEnd) - (departCityPosEnd));

            var departCountryPosEnd = distCode.indexOf('_', departZipPosEnd)+1;
             departCountry= distCode.substr(departZipPosEnd, distCode.indexOf('_', departZipPosEnd) - (departZipPosEnd));         

            var arrivalAdPosEnd = distCode.indexOf('-', departCountryPosEnd)+1;
             arrivalAdress = distCode.substr(departCountryPosEnd, distCode.indexOf('-', departCountryPosEnd) - (departCountryPosEnd));

            var arrivalCityPosEnd = distCode.indexOf('-', arrivalAdPosEnd)+1;
             arrivalCity = distCode.substr(arrivalAdPosEnd, distCode.indexOf('-', arrivalAdPosEnd) - (arrivalAdPosEnd));

            var arrivalZipPosEnd = distCode.indexOf('-', arrivalCityPosEnd)+1;
             arrivalZip = distCode.substr(arrivalCityPosEnd, distCode.indexOf('-', arrivalCityPosEnd) - (arrivalCityPosEnd));

            var arrivalCountryPosEnd = distCode.indexOf('-', arrivalZipPosEnd)+1;
             arrivalCountry = distCode.substr(arrivalZipPosEnd, distCode.indexOf('_', arrivalZipPosEnd) - (arrivalZipPosEnd));
             
            distanceType = distCode.substr(arrivalCountryPosEnd, distCode.indexOf(']', arrivalCountryPosEnd) - (arrivalCountryPosEnd));
         
         }
        }
        
    }
    
    var $selectDepart = jQuery('#lfb_departAdressItem');
    var $selectArrival = jQuery('#lfb_arrivalAdressItem');
    var $selectDepartCity = jQuery('#lfb_departCityItem');
    var $selectArrivalCity = jQuery('#lfb_arrivalCityItem');
    var $selectDepartZip = jQuery('#lfb_departZipItem');
    var $selectArrivalZip = jQuery('#lfb_arrivalZipItem');
    var $selectDepartCountry = jQuery('#lfb_departCountryItem');
    var $selectArrivalCountry = jQuery('#lfb_arrivalCountryItem');
    
    $selectDepart.find('option').remove();
    $selectArrival.find('option').remove();
    $selectDepartCity.find('option').remove();
    $selectArrivalCity.find('option').remove();
    $selectDepartZip.find('option').remove();
    $selectArrivalZip.find('option').remove();
    $selectDepartCountry.find('option').remove();
    $selectArrivalCountry.find('option').remove();
    $selectDepart.append('<option value="" data-type="">'+lfb_data.texts['Nothing']+'</option>');
    $selectArrival.append('<option value="" data-type="">'+lfb_data.texts['Nothing']+'</option>');
    $selectDepartCity.append('<option value="" data-type="">'+lfb_data.texts['Nothing']+'</option>');
    $selectArrivalCity.append('<option value="" data-type="">'+lfb_data.texts['Nothing']+'</option>');
    $selectDepartZip.append('<option value="" data-type="">'+lfb_data.texts['Nothing']+'</option>');
    $selectArrivalZip.append('<option value="" data-type="">'+lfb_data.texts['Nothing']+'</option>');
    $selectDepartCountry.append('<option value="" data-type="">'+lfb_data.texts['Nothing']+'</option>');
    $selectArrivalCountry.append('<option value="" data-type="">'+lfb_data.texts['Nothing']+'</option>');
    
     jQuery.each(lfb_currentForm.steps, function () {
        var step = this;
        jQuery.each(this.items, function () {
             var item = this;
            if (item.type == 'textfield' ||item.type == 'select') {
                var itemID = item.id;
                var selDepAd = '';
                var selDepCity = '';
                var selDepZip = '';
                var selDepCountry = '';
                var selArrAd = '';
                var selArrCity = '';
                var selArrZip = '';
                var selArrCountry = '';
                
                if(item.id == departAdress){ selDepAd = 'selected';}
                if(item.id == departCity){ selDepCity = 'selected';}
                if(item.id == departZip){ selDepZip = 'selected';}
                if(item.id == departCountry){ selDepCountry = 'selected';}
                if(item.id == arrivalAdress){ selArrAd = 'selected';}
                if(item.id == arrivalCity){ selArrCity = 'selected';}
                if(item.id == arrivalZip){ selArrZip = 'selected';}
                if(item.id == arrivalCountry){ selArrCountry = 'selected';}
                
                $selectDepart.append('<option '+selDepAd+' value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
                $selectArrival.append('<option '+selArrAd+' value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
                $selectDepartCity.append('<option '+selDepCity+' value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
                $selectArrivalCity.append('<option '+selArrCity+' value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
                $selectDepartZip.append('<option '+selDepZip+' value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
                $selectArrivalZip.append('<option '+selArrZip+' value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
                $selectDepartCountry.append('<option '+selDepCountry+' value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
                $selectArrivalCountry.append('<option '+selArrCountry+' value="' + itemID + '" data-type="' + item.type + '">' + step.title + ' : " ' + item.title + ' "</option>');
            }
        });
    });
    
    jQuery("body,html").animate({
        scrollTop: 0
    }, 200);
    jQuery('#lfb_winDistance').fadeIn();
}
function lfb_saveDistanceValue(){
    var depAd = '';
    if(jQuery('#lfb_departAdressItem').val() != ""){
        depAd = jQuery('#lfb_departAdressItem').val();
    }
    var depCity = '';
    if(jQuery('#lfb_departCityItem').val() != ""){
        depCity = jQuery('#lfb_departCityItem').val();
    }
    var depCountry = '';
    if(jQuery('#lfb_departCountryItem').val() != ""){
        depCountry = jQuery('#lfb_departCountryItem').val();
    }
    var depZip = '';
    if(jQuery('#lfb_departZipItem').val() != ""){
        depZip = jQuery('#lfb_departZipItem').val();
    }
    
    
    var arrivalAd = '';
    if(jQuery('#lfb_arrivalAdressItem').val() != ""){
        arrivalAd = jQuery('#lfb_arrivalAdressItem').val();
    }
    var arrivalCity = '';
    if(jQuery('#lfb_arrivalCityItem').val() != ""){
        arrivalCity = jQuery('#lfb_arrivalCityItem').val();
    }
    var arrivalCountry = '';
    if(jQuery('#lfb_arrivalCountryItem').val() != ""){
        arrivalCountry = jQuery('#lfb_arrivalCountryItem').val();
    }
    var arrivalZip = '';
    if(jQuery('#lfb_arrivalZipItem').val() != ""){
        arrivalZip = jQuery('#lfb_arrivalZipItem').val();
    }
    var distanceType = jQuery('#lfb_distanceType').val();
    
    var code = '[distance_';
    code += depAd+'-'+depCity+'-'+depZip+'-'+depCountry+'_'+arrivalAd+'-'+arrivalCity+'-'+arrivalZip+'-'+arrivalCountry+'_'+distanceType;
    code += ']';
    
    if(depAd == '' && depCity==''&&depCountry == '' && arrivalAd == '' &&arrivalCity == '' && arrivalCountry == '' && depZip == '' && arrivalZip == '' ){
        code = '';
    }
    
     var posCar = jQuery('#lfb_winItem').find('[name="calculation"]').prop("selectionStart");
    var value = jQuery('#lfb_winItem').find('[name="calculation"]').val();
    if (isNaN(posCar)) {
        posCar == value.length;
    }
    var newValue = value.substr(0, posCar) + ' ' + code + ' ' + value.substr(posCar, value.length);

    if(!lfb_distanceModeQt){
        jQuery('#lfb_winItem').find('[name="calculation"]').val(newValue);
    } else {
        jQuery('#lfb_winItem').find('[name="distanceQt"]').val(newValue);        
    }
        
    jQuery('#lfb_winDistance').fadeOut();    
}