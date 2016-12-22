var lfb_lastStepID = 0;
var lfb_lastSteps = new Array();
var lfb_plannedSteps;
var lfb_gmapService = false;
jQuery(document).ready(function () {
    if (jQuery('#estimation_popup').length > 0) {
        window.Dropzone.autoDiscover = false;
        initFlatUI();
        wpe_initForms();
    }
});
jQuery(window).load(function () {
    if (jQuery('#estimation_popup').length > 0) {
        jQuery.each(wpe_forms, function () {
            var form = this;
            wpe_checkItems(form.formID);
            wpe_initListeners(form.formID);
        });
        lfb_onResize();
        jQuery(window).resize(lfb_onResize);

        jQuery(window).on("popstate", function (e) {
            if (e.originalEvent.state !== null) {
                e.preventDefault();
                wpe_previousStep(wpe_forms[0].formID);
            }
        });
    }
    jQuery('#ajax-loading-screen').fadeOut();
    jQuery('#ajax-content-wrap >.container-wrap').css({
        opacity: 1,
        display: 'block'
    });
});

function wpe_getForm(formID) {
    var rep = false;
    jQuery.each(wpe_forms, function () {
        if (this.formID == formID) {
            rep = this;
        }
    });
    return rep;
}
function lfb_onResize() {
    jQuery('#estimation_popup.wpe_fullscreen').css({
        minHeight: jQuery(window).height()
    });
    if (jQuery(window).width() <= 768) {

        jQuery('#estimation_popup .lfb_stepDescription').each(function () {
            jQuery(this).css({
                top: jQuery(this).closest('.genSlide').find('.stepTitle').height() + 60
            });
        });
        jQuery('#estimation_popup .genContent ').each(function () {
            jQuery(this).css({
                paddingTop: jQuery(this).closest('.genSlide').find('.stepTitle').height() + jQuery(this).closest('.genSlide').find('.lfb_stepDescription').height() + 70
            });
        });
    } else {
        jQuery('#estimation_popup .lfb_stepDescription').each(function () {
            jQuery(this).css({
                top: jQuery(this).closest('.genSlide').find('.stepTitle').height() + 60
            });
        });
        jQuery('#estimation_popup .genContent ').each(function () {
            jQuery(this).css({
                paddingTop: jQuery(this).closest('.genSlide').find('.stepTitle').height() + jQuery(this).closest('.genSlide').find('.lfb_stepDescription').height() + 70
            });
        });
    }
}

function wpe_updatePlannedSteps(formID) {
    var startStepID = parseInt(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]  #mainPanel .genSlide[data-start="1"]').attr('data-stepid'));
    lfb_plannedSteps = new Array();
    lfb_plannedSteps.push(startStepID);
    lfb_plannedSteps = wpe_scanPlannedSteps(startStepID, formID);
}
function wpe_scanPlannedSteps(stepID, formID) {
    var plannedSteps = new Array();
    var potentialSteps = wpe_findPotentialsSteps(stepID, formID);
    if (potentialSteps.length > 0 && potentialSteps[0] != 'final') {
        lfb_plannedSteps.push(potentialSteps[0]);
        wpe_scanPlannedSteps(potentialSteps[0], formID);
    } else {
        return lfb_plannedSteps;
    }
    return lfb_plannedSteps;
}

function wpe_getTotalQuantities(formID) {
    var form = wpe_getForm(formID);
    var formContent = wpe_getFormContent(formID, true);
    var items = formContent[2];
    var quantities = 0;

    jQuery.each(items, function () {
        var item = this;
        if (isNaN(item.quantity)) {
            quantities++;
        } else {
            quantities += item.quantity;
        }
    });

    return quantities;
}

function wpe_itemClick($item, action, formID) {
    var form = wpe_getForm(formID);
    var chkGrpReq = false;
    var $this = $item;
    var isChecked = false;

    if (action) {
        jQuery('#estimation_popup[data-form="' + form.formID + '"] .quantityBtns').removeClass('open');
        jQuery('#estimation_popup[data-form="' + form.formID + '"] .quantityBtns').fadeOut(250);
        var deviceAgent = navigator.userAgent.toLowerCase();
        var agentID = deviceAgent.match(/(iPad|iPhone|iPod)/i);
        if (agentID) {
            jQuery('body :not(.ui-slider-handle) > .tooltip').remove();
            jQuery('body > .tooltip').remove();
        }

    }
    if (action) {
        $this.addClass('action');
    }
    if ((action) || (!$this.is('.action'))) {
        $this.find('span.icon_select').animate({
            bottom: 160,
            opacity: 0
        }, 200);
        if ($this.is('.checked')) {
            if ((action) && ($this.data('required'))) {
            } else {
                $this.delay(220).removeClass('checked');
                $this.delay(220).find('span.icon_select').removeClass('fui-check').addClass('fui-cross');
            }
            $this.find('.icon_quantity').delay(300).fadeOut(200);

        } else {
            if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                if (!$item.is('[data-type="slider"]')) {
                    $item.tooltip('show');
                }
            }
            isChecked = true;
            $this.delay(220).addClass('checked');
            $this.delay(220).find('span.icon_select').removeClass('fui-cross').addClass('fui-check');
            if ($this.find('.quantityBtns').length > 0 && !$this.is('[data-distanceqt]')) {
                $this.find('.icon_quantity').delay(300).fadeIn(200);
                $this.find('.quantityBtns').delay(500).addClass('open');
                $this.find('.quantityBtns').delay(500).fadeIn('200');
            }
            if ($this.data('urltarget') && $this.data('urltarget') != "") {
                var win = window.open($this.data('urltarget'), '_blank');
                win.focus();
            }
        }
        $this.find('span.icon_select').delay(300).animate({
            bottom: 0,
            opacity: 1
        }, 200);

        if ((action) && ($this.data('group'))) {
            $this.closest('.genSlide').find('div.selectable.checked[data-group="' + $this.data('group') + '"]').each(function () {
                wpe_itemClick(jQuery(this));
            });
            $this.closest('.genSlide').find('input[type=checkbox][data-group="' + $this.data('group') + '"]:checked').trigger('click.auto');

            if (form.groupAutoClick == '1' && $this.is('.checked') && $this.closest('.genSlide').is('[data-required=true]')) {
                if ($this.closest('.genSlide').find('[data-itemid]').not('[data-group="' + $this.data('group') + '"]').length == 0 &&
                        $this.closest('.genSlide').find('.quantityBtns').length == 0 && $this.closest('.genSlide').find('.wpe_qtfield').length == 0) {
                    setTimeout(function () {
                        wpe_nextStep(form.formID);
                    }, 250);
                }
            }
        }

        setTimeout(function () {
            wpe_updatePrice(formID);
            $this.removeClass('action');
        }, 220);
    }
    
        setTimeout(function () {
            if($this.is('[data-usedistance="true"]')){
                lfb_removeDistanceError($this.attr('data-itemid'),formID);
            }
        },200);
}

function wpe_nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function wpe_initForms() {
    jQuery.each(wpe_forms, function () {
        var form = this;
        form.price = 0;
        form.priceSingle = 0;
        form.priceMax = 0;
        form.step = 0;
        form.gFormDesignCheck = 0;
        form.timer_gFormSubmit = null;
        form.timer_gFormDesign = null;
        form.animationsSpeed *= 1000;
        form.reductionResult = 0;
        form.reduction = 0;
        form.discountCode = "";
        form.discountCodeDisplayed = false;
        form.initialPrice = parseFloat(form.initialPrice);
        form.contactSent = 0;
        form.gravitySent = false;
        form.subscriptionText = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalPrice span:eq(1)').html();
        var formID = form.formID;
        if (form.save_to_cart == 1) {
            form.save_to_cart = true;
        } else {
            form.save_to_cart = false;
        }
        if (form.gravityFormID > 0) {
            jQuery.ajax({
                url: form.ajaxurl,
                type: 'post',
                data: {
                    action: 'get_currentRef',
                    formID: formID
                },
                success: function (currentRef) {
                    form.current_ref = currentRef;
                }
            });
        }

        if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').is('.wpe_popup') && !jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').closest('#lfb_bootstraped').parent().is('body')) {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').closest('#lfb_bootstraped').detach().appendTo('body');
        }
        if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm').length > 0) {
            Stripe.setPublishableKey(form.stripePubKey);
        }
        if (jQuery('#estimation_popup[data-form="' + form.formID + '"]').is('.wpe_fullscreen')) {
            jQuery('html,body').css('overflow-y', 'hidden');
        }
        if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm').length > 0) {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm').attr('target', '_self');
        }
        if (form.intro_enabled == '0') {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #btnStart,#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #startInfos').hide();
            if (form.showSteps != '2') {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice').fadeIn(500);
            } else {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice').hide();
            }
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel').fadeIn(form.animationsSpeed, function () {
                wpe_nextStep(form.formID);
            });
        }

        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] input:not([type=checkbox])').change(function () {
            wpe_updatePrice(formID);
        });

        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_dropzone').each(function () {
            var maxSize = null;
            if (jQuery(this).attr('data-maxfiles') > 0) {
                maxSize = jQuery(this).attr('data-maxfiles');
            }
            var dropzone = jQuery(this);
            jQuery(this).dropzone({
                url: form.ajaxurl,
                paramName: 'file',
                maxFilesize: 25,
                maxFiles: maxSize,
                addRemoveLinks: true,
                dictRemoveFile: '',
                dictCancelUpload: '',
                acceptedFiles: jQuery(this).attr('data-allowedfiles'),
                dictDefaultMessage: form.filesUpload_text,
                dictFileTooBig: form.filesUploadSize_text,
                dictInvalidFileType: form.filesUploadType_text,
                dictMaxFilesExceeded: form.filesUploadLimit_text,
                init: function () {
                    this.on("thumbnail", function (file, dataUrl) {
                        var thumb = jQuery(file.previewElement);
                        thumb.attr('data-file', file);
                    });
                    this.on("sending", function (file, xhr, formData) {
                        dropzone.closest('.genSlide').find('.btn-next').fadeOut();
                        formData.append("action", 'lfb_upload_form');
                        formData.append("formSession", jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').attr('data-formsession'));
                    }),
                            this.on("complete", function (file, xhr) {
                                if (dropzone.find('.dz-preview:not(.dz-complete)').length == 0 || dropzone.find('.dz-preview').length == 0) {
                                    dropzone.closest('.genSlide').find('.btn-next').fadeIn();
                                }
                                wpe_updatePrice(form.formID);
                            });
                    this.on("success", function (file, xhr) {
                        var thumb = jQuery(file.previewElement);
                        thumb.attr('data-file', file.name);
                    });
                    this.on("removedfile", function (file, xhr) {
                        wpe_updatePrice(form.formID);
                    });
                }
            });
        });


        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] [data-itemid][data-type="slider"]').each(function () {
            var min = parseInt(jQuery(this).attr('data-min'));
            if (min == 0) {
                min = 0;
            }
            var max = parseInt(jQuery(this).attr('data-max'));
            if (max == 0) {
                max = 30;
            }
            var tooltip = jQuery('<div class="tooltip top" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + min + '</div></div>').css({
                position: 'absolute',
                top: -55,
                left: -20,
                opacity: 1
            }).hide();
            jQuery(this).slider({
                min: min,
                max: max,
                value: min,
                orientation: "horizontal",
                range: "min",
                change: function (event, ui) {
                    tooltip.find('.tooltip-inner').html(ui.value);
                    wpe_updatePrice(formID);
                },
                slide: function (event, ui) {
                    tooltip.find('.tooltip-inner').html(ui.value);
                    wpe_updatePrice(formID);
                    tooltip.show();
                },
                stop: function (event, ui) {
                    tooltip.find('.tooltip-inner').html(ui.value);
                    wpe_updatePrice(formID);
                    tooltip.hide();
                }

            }).find(".ui-slider-handle").append(tooltip).hover(function () {
                tooltip.show();
            }, function () {
                tooltip.hide();
            });
        });

        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_colorpicker').each(function () {
            var $this = jQuery(this);
            jQuery(this).prev('.lfb_colorPreview').click(function () {
                jQuery(this).next('.lfb_colorpicker').trigger('click');
            });
            jQuery(this).prev('.lfb_colorPreview').css({
                backgroundColor: form.colorA
            });
            jQuery(this).colpick({
                color: form.colorA,
                layout: 'hex',
                onSubmit: function () {
                    jQuery('body > .colpick').fadeOut();
                },
                onChange: function (hsb, hex, rgb, el, bySetColor) {
                    jQuery(el).val('#' + hex);
                    jQuery(el).prev('.lfb_colorPreview').css({
                        backgroundColor: '#' + hex
                    });
                }
            });
        });
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').find('.lfb_colorPreview[data-urltarget],input[type="text"][data-itemid]:not(.lfb_colorpicker)[data-urltarget],textarea[data-itemid][data-urltarget],select[data-itemid][data-urltarget]').each(function () {
            jQuery(this).click(function () {
                var win = window.open(jQuery(this).attr('data-urltarget'), '_blank');
                win.focus();
            });
        });


        var dateFormat = wpe_forms[0].dateFormat;
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]  #mainPanel .genSlide .lfb_datepicker').datepicker({
            dateFormat: dateFormat,
            beforeShow: function (textbox, instance) {
                jQuery(this).blur();
                jQuery('#ui-datepicker-div').addClass('lfb_datepickerContainer');
            },
            minDate: 0
        });
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]  #mainPanel .genSlide .lfb_datepicker').keypress(function (e) {
            e.preventDefault();
        });
        if (wpe_forms[0].datepickerLang == "") {
            wpe_forms[0].datepickerLang = "en";
        }
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]  #mainPanel .genSlide .lfb_datepicker').datepicker("option",
                jQuery.datepicker.regional[wpe_forms[0].datePickerLanguage]);

        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]  #mainPanel .genSlide [data-group]').each(function () {
            var $this = jQuery(this);
            if (form.groupAutoClick == '1' && $this.prop('data-group') != "" && $this.closest('.genSlide').is('[data-required=true]')) {
                if ($this.closest('.genSlide').find('[data-itemid]').not('[data-group="' + $this.data('group') + '"]').length == 0 &&
                        $this.closest('.genSlide').find('.quantityBtns').length == 0 && $this.closest('.genSlide').find('.wpe_qtfield').length == 0) {
                    $this.closest('.genSlide').find('.btn-next').addClass('lfb-hidden');
                }
            }

        });

        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]  #mainPanel .genSlide div.selectable.prechecked,#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel .genSlide input[type=checkbox][data-price].prechecked').each(function () {
            wpe_itemClick(jQuery(this), false, formID);
        });
        wpe_initPrice(formID);
        wpe_updatePrice(formID);
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #btnStart').click(function () {
            wpe_openGenerator(formID);
        });
        wpe_initGform(formID);
        if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm').length > 0) {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm').attr('target', '_self');
        }
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .quantityBtns > a').click(function () {
            if (jQuery(this).attr('data-btn') == 'less') {
                wpe_quantity_less(this, formID);
            } else {
                wpe_quantity_more(this, formID);
            }
        });
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .linkPrevious').click(function () {
            wpe_previousStep(formID);
        });
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .btn-next').click(function () {
            wpe_nextStep(formID);
        });
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #btnOrderPaypal').click(function () {
            wpe_order(formID);
        });
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide [data-toggle="switch"]').change(function () {
            var fieldID = jQuery(this).attr('data-fieldid');
            wpe_toggleField(fieldID, formID);
        });
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm').submit(function (event) {
            var error = false;
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm [data-stripe="number"]').closest('.form-group').removeClass('has-error');
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm [data-stripe="exp_month"]').closest('.form-group').removeClass('has-error');
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm [data-stripe="exp_year"]').closest('.form-group').removeClass('has-error');
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm [data-stripe="cvc"]').closest('.form-group').removeClass('has-error');

            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm [data-stripe="number"]').val().length < 5) {
                error = true;
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm [data-stripe="number"]').closest('.form-group').addClass('has-error');
            }
            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm [data-stripe="exp_month"]').val().length < 2) {
                error = true;
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm [data-stripe="exp_month"]').closest('.form-group').addClass('has-error');
            }
            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm [data-stripe="exp_year"]').val().length < 2) {
                error = true;
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm [data-stripe="exp_year"]').closest('.form-group').addClass('has-error');
            }
            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm [data-stripe="cvc"]').val().length < 2) {
                error = true;
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm [data-stripe="cvc"]').closest('.form-group').addClass('has-error');
            }
            if (!error) {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm #wpe_btnOrderStripe').prop('disabled', true);
                Stripe.card.createToken(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm'), function (status, response) {
                    lfb_stripeResponse(status, response, form.formID);
                });
            }
            return false;
        });

        jQuery('.gform_wrapper').each(function () {
            var gravID = jQuery(this).attr('id').substr(jQuery(this).attr('id').lastIndexOf('_') + 1, jQuery(this).attr('id').length);
            if (gravID == form.gravityFormID) {
                jQuery(this).detach().insertAfter('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalPrice');
            }
        });

        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .wpe_qtfield').attr('type', 'number');
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .wpe_qtfield').keyup(function () {
            if(jQuery(this).val().indexOf('-')>-1  && (!jQuery(this).is('[min]')||jQuery(this).attr('min').indexOf('-')<0)){
                jQuery(this).val(parseInt(jQuery(this).attr('min')));
            }
            if (parseFloat(jQuery(this).val()) < parseInt(jQuery(this).attr('min'))) {
                jQuery(this).val(jQuery(this).attr('min'));
            }
            if (parseFloat(jQuery(this).val()) > parseInt(jQuery(this).attr('max'))) {
                jQuery(this).val(jQuery(this).attr('max'));
            }
            wpe_updatePrice(form.formID);
            wpe_updateSummary(form.formID);
        });

        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] input[type="number"][data-itemid]').focusout(function () {
             if(jQuery(this).val().indexOf('-')>-1 && (!jQuery(this).is('[min]')||jQuery(this).attr('min').indexOf('-')<0)){
                jQuery(this).val(parseInt(jQuery(this).attr('min')));
            }
            if (parseFloat(jQuery(this).val()) < parseFloat(jQuery(this).attr('min'))) {
                jQuery(this).val(jQuery(this).attr('min'));
            }
            if (parseFloat(jQuery(this).val()) > parseFloat(jQuery(this).attr('max'))) {
                jQuery(this).val(jQuery(this).attr('max'));
            }
            if (jQuery(this).val() != '') {
                jQuery(this).addClass('checked');
            }
            wpe_updatePrice(form.formID);
            wpe_updateSummary(form.formID);
        });



        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .wpe_sliderQt').each(function () {
            var min = parseInt(jQuery(this).closest('.quantityBtns').attr('data-min'));
            if (min == 0) {
                min = 1;
            }
            var tooltip = jQuery('<div class="tooltip top" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner">0</div></div>').css({
                position: 'absolute',
                top: -55,
                left: -20,
                opacity: 1
            }).hide();
            jQuery(this).slider({
                min: min,
                max: parseInt(jQuery(this).closest('.quantityBtns').attr('data-max')),
                value: parseInt(jQuery(this).closest('.quantityBtns').next('.icon_quantity').html()),
                orientation: "horizontal",
                range: "min",
                change: function (event, ui) {
                    tooltip.find('.tooltip-inner').html(ui.value);
                    jQuery(this).closest('.quantityBtns').next('.icon_quantity').html(ui.value);
                    wpe_updatePrice(formID);
                },
                slide: function (event, ui) {
                    jQuery(this).closest('.quantityBtns').next('.icon_quantity').html(ui.value);
                    tooltip.find('.tooltip-inner').html(ui.value);
                    wpe_updatePrice(formID);
                }
            }).find(".ui-slider-handle").append(tooltip).hover(function () {
                tooltip.show();
            }, function () {
                tooltip.hide();
            });
        });

        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]  #lfb_loader').fadeOut();

    });
}
function lfb_stripeResponse(status, response, formID) {
    var $form = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_stripeForm');
    if (response.error) {
        $form.find('.payment-errors').text(response.error.message);
        $form.find('.btn').prop('disabled', false); 
    } else {
        var token = response.id;
        if ($form.find('[name="stripeToken"]').length == 0) {
            $form.append(jQuery('<input type="hidden" name="stripeToken">').val(token));
            Stripe.card.createToken(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_stripeForm'), function (statusB, responseB) {
                lfb_stripeResponse(statusB, responseB, formID);
            });
        } else if ($form.find('[name="stripeTokenB"]').length == 0) {
            $form.append(jQuery('<input type="hidden" name="stripeTokenB">').val(token));
            wpe_order(formID);
        }
    }
}
function lfb_replaceAllBackSlash(targetStr) {
    var index = targetStr.indexOf("\\");
    while (index >= 0) {
        targetStr = targetStr.replace("\\", "");
        index = targetStr.indexOf("\\");
    }
    return targetStr;
}
function lfb_updateShowSteps(formID) {
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] .genSlide[data-useshowconditions]').each(function () {
        var conditions = lfb_replaceAllBackSlash(jQuery(this).attr('data-showconditions'));
        conditions = conditions.replace(/'/g, '"');
        if (conditions != '') {
            try {
                conditions = JSON.parse(conditions);
                var errors = lfb_checkConditions(conditions, formID);
                var operator = jQuery(this).attr('data-showconditionsoperator');
                if ((operator == 'OR' && !errors.errorOR) || (operator != 'OR' && !errors.error)) {
                    if (jQuery(this).is('.lfb_disabled')) {
                        jQuery(this).css({
                            opacity: 0
                        });
                        jQuery(this).removeClass('lfb_disabled');

                    }
                } else {
                    if (!jQuery(this).is('.lfb_disabled')) {
                        jQuery(this).addClass('lfb_disabled');
                    }
                }
            } catch (e) {
            }
        }
    });
}
function lfb_updateShowItems(formID) {
    var form = wpe_getForm(formID);
    var lastAndCurrentSteps = lfb_lastSteps.slice();
    var pricePreviousStep = 0;
    var singlePricePreviousStep = 0;

    if (form.step != 'final' && jQuery.inArray(parseInt(form.step), lastAndCurrentSteps) == -1) {
        lastAndCurrentSteps.push(parseInt(form.step));
    }
    jQuery.each(lastAndCurrentSteps, function () {
        
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] .genSlide[data-stepid="'+this+'"] [data-useshowconditions]').each(function () {
        var conditions = lfb_replaceAllBackSlash(jQuery(this).attr('data-showconditions'));
        conditions = conditions.replace(/'/g, '"');
        if (conditions != '') {
            try {
                conditions = JSON.parse(conditions);
                var errors = lfb_checkConditions(conditions, formID);
                var operator = jQuery(this).attr('data-showconditionsoperator');
                if ((operator == 'OR' && !errors.errorOR) || (operator != 'OR' && !errors.error)) {
                    if (jQuery(this).is('.lfb_disabled')) {
                        jQuery(this).css({
                            opacity: 0
                        });
                        jQuery(this).removeClass('lfb_disabled');
                        jQuery(this).closest('.itemBloc').removeClass('lfb_disabled');
                        jQuery(this).animate({
                            opacity: 1
                        }, 300);
                        if (jQuery(this).is('input.prechecked') && !jQuery(this).is(':checked')) {
                            wpe_itemClick(jQuery(this), false, formID);
                        }
                        if (jQuery(this).is('.selectable.prechecked') && !jQuery(this).is('.checked')) {
                            wpe_itemClick(jQuery(this), false, formID);
                        }
                        jQuery(this).closest('.itemBloc').fadeIn(300);
                    }
                } else {
                    if (!jQuery(this).is('.lfb_disabled')) {
                        jQuery(this).css({
                            opacity: 1
                        });
                        if (jQuery(this).is(':checked')) {
                            wpe_itemClick(jQuery(this), false, formID);
                        }
                        if (jQuery(this).is('.checked')) {
                            wpe_itemClick(jQuery(this), false, formID);
                        }
                        if (jQuery(this).is('input[type="text"]') || jQuery(this).is('textarea')) {
                            jQuery(this).val('');
                        }
                        if (jQuery(this).is('.lfb_dropzone')) {
                            jQuery(this).find('.dz-preview').remove();
                        }

                        jQuery(this).addClass('lfb_disabled');
                        jQuery(this).closest('.itemBloc').addClass('lfb_disabled');
                        jQuery(this).animate({
                            opacity: 0
                        }, 300);
                        jQuery(this).closest('.itemBloc').fadeOut(300);
                    }
                }
            } catch (e) {
            }
        }
    });
    });
}

function lfb_removeFile(formID, file) {
    var form = wpe_getForm(formID);
    jQuery.ajax({
        url: form.ajaxurl,
        type: 'post',
        data: {
            action: 'lfb_removeFile',
            formSession: jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').attr('data-formsession'),
            file: file
        }
    });
}
function wpe_disablesThemeScripts() {
    var scriptsCheck = false;
    jQuery('script').each(function () {
        if ((scriptsCheck)) {
            if (jQuery(this).attr("src") && jQuery(this).attr("src").indexOf("WP_Helper_Creator") > 0) {
            } else if (jQuery(this).attr("src") && jQuery(this).attr("src").indexOf("VisitorsTracker") > 0) {
            } else if (jQuery(this).attr("src") && jQuery(this).attr("src").indexOf("WP_Visual_Chat") > 0) {
            } else if (jQuery(this).attr("src") && jQuery(this).attr("src").indexOf("gravityforms") > 0) {
            } else {
                var scriptCt = this.innerText || this.textContent;
                if (scriptCt.indexOf('analytics') < 0 && jQuery(this).parents('.gform_wrapper').length == 0) {
                    jQuery(this).attr("disabled", "disabled");
                }
            }
        }
        if (jQuery(this).attr("src") && jQuery(this).attr("src").indexOf("estimation_popup") > 0) {
            scriptsCheck = true;
        }

    });
}


function wpe_initGform(formID) {
    var form = wpe_getForm(formID);
    if (form.gravityFormID > 0) {
        form.gravitySent = false;
        form.gFormDesignCheck++;
        if (form.timer_gFormDesign) {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide').delay(100).animate({
                opacity: 1
            }, 1000);
        }
        jQuery('#gform_wrapper_' + form.gravityFormID + ' input[type=radio]').not('[data-toggle="radio"]').attr('data-toggle', 'radio');
        jQuery('#gform_wrapper_' + form.gravityFormID + '  .ginput_container input,#gform_wrapper_' + form.gravityFormID + '  .ginput_container select,#gform_wrapper_' + form.gravityFormID + ' .ginput_container textarea').attr('title', 'control');
        jQuery('#gform_wrapper_' + form.gravityFormID + '  .ginput_container input,#gform_wrapper_' + form.gravityFormID + '  .ginput_container textarea, #gform_wrapper_' + form.gravityFormID + ' .ginput_container select').not('[type=checkbox]').not('[type=radio]').not('[type=submit]').addClass('form-control');
        jQuery('#gform_wrapper_' + form.gravityFormID + '  .ginput_container').addClass('form-group');
        jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_button').attr('class', 'btn btn-wide btn-primary');
        jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper input[type="radio"]:not(.ready)').each(function () {
            jQuery(this).addClass('ready');
            var label = jQuery('#gform_wrapper_' + form.gravityFormID + ' .gform_wrapper label[for="' + jQuery(this).attr('id') + '"]').html();
            jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper label[for="' + jQuery(this).attr('id') + '"]').parent('li').css('display', 'inline-block');
            jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper label[for="' + jQuery(this).attr('id') + '"]').append(jQuery(this));
            jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper label[for="' + jQuery(this).attr('id') + '"]').addClass('radio');
            jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper label[for="' + jQuery(this).attr('id') + '"]').prepend('<span class="icons"><span class="first-icon fui-radio-unchecked"></span><span class="second-icon fui-radio-checked"></span></span>');

            if (!jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper label[for="' + jQuery(this).attr('id') + '"]').parent('li').next().is('br')) {
                jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper label[for="' + jQuery(this).attr('id') + '"]').parent('li').after('<br/>');
            }
            if (jQuery(this).is(':checked')) {
                jQuery(this).trigger('click');
            }
        });
        jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper input[type="checkbox"]:not(.ready)').each(function () {
            jQuery(this).addClass('ready');
            var label = jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper label[for="' + jQuery(this).attr('id') + '"]').html();
            jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper label[for="' + jQuery(this).attr('id') + '"]').parent('li').css('display', 'inline-block');
            jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper label[for="' + jQuery(this).attr('id') + '"]').append(jQuery(this));
            jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper label[for="' + jQuery(this).attr('id') + '"]').addClass('checkbox');
            jQuery(this).before('<span class="icons"><span class="first-icon fui-checkbox-unchecked"></span><span class="second-icon fui-checkbox-checked"></span></span>');
            if (!jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper label[for="' + jQuery(this).attr('id') + '"]').parent('li').next().is('br')) {
                jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper label[for="' + jQuery(this).attr('id') + '"]').parent('li').after('<br/>');
            }

        });
        jQuery('#gform_wrapper_' + form.gravityFormID + '  .gform_wrapper label.checkbox').each(function () {
            if (jQuery(this).find('[type=checkbox]').length > 0) {
                jQuery(this).find('[type=checkbox]').eq(1).remove();
            }
            if (jQuery(this).find('[type=checkbox]').is(':checked')) {
                jQuery(this).find('[type=checkbox]').trigger('click');
            }
        });
        if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm').length > 0) {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm  #btnOrderPaypal').hide();
        }
        jQuery(' #gform_submit_button_' + form.gravityFormID).click(function (e) {
            e.preventDefault();
            if (!form.gravitySent) {
                form.gravitySent = true;
                jQuery(this).addClass('anim');
                form.gFormDesignCheck = 0;
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide').delay(1000).animate({
                    opacity: 0
                }, 1000);
                var $this = jQuery(this);
                setTimeout(function () {
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide .gform_wrapper form').submit();
                    form.timer_gFormDesign = setTimeout(function () {
                        wpe_initGform(formID);
                    }, 2000);
                }, 1000);
            }
        });
    }
}


function wpe_initPrice(formID) {
    var form = wpe_getForm(formID);

    if (form.max_price > 0) {
        form.priceMax = form.max_price;
    } else {
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel .genSlide [data-price]').each(function () {
            if (jQuery(this).data('price') && jQuery(this).data('price') > 0) {
                if (jQuery(this).find('.icon_quantity').length > 0) {
                    var max = parseFloat(jQuery(this).find('.icon_quantity').html());
                    if (max > 10 && parseFloat(jQuery(this).data('price')) > 100) {
                        max = 10;
                    } else if (max > 30) {
                        max = 30;
                    } else {
                        max = parseFloat(jQuery(this).find('.quantityBtns').data('max'));
                    }
                    if (jQuery(this).data('operation') == '-' || jQuery(this).data('operation') == '/') {
                    } else {
                        form.priceMax += parseFloat(jQuery(this).data('price')) * max;
                    }
                } else if (jQuery(this).find('.wpe_qtfield').length > 0) {
                    var max = parseFloat(jQuery(this).find('.wpe_qtfield').val());
                    if (max > 10 && parseFloat(jQuery(this).data('price')) > 100) {
                        max = 10;
                    } else if (max > 30) {
                        max = 30;
                    } else {
                        if (parseFloat(jQuery(this).find('.wpe_qtfield').attr('max').length > 0)) {
                            max = parseFloat(jQuery(this).find('.wpe_qtfield').attr('max'));
                        } else {
                            max = 30;
                        }
                    }
                    if (jQuery(this).data('operation') == '-' || jQuery(this).data('operation') == '/') {
                    } else {
                        form.priceMax += parseFloat(jQuery(this).data('price')) * max;
                    }
                } else {
                    if (jQuery(this).data('operation') == '+') {
                        form.priceMax += parseFloat(jQuery(this).data('price'));
                    }
                }
            }
        });
        form.priceMax += form.initialPrice;

        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel .genSlide [data-price][data-operation="x"]').each(function () {
            if (jQuery(this).find('.icon_quantity').length > 0) {
                for (var i = 0; i < parseFloat(jQuery(this).find('.icon_quantity').html()); i++) {
                    form.priceMax = form.priceMax + (form.priceMax * parseFloat(jQuery(this).data('price')) / 100);
                }
            } else {
                form.priceMax = form.priceMax + (form.priceMax * parseFloat(jQuery(this).data('price')) / 100);
            }
        });
    }
}


function initFlatUI() {
    jQuery('#estimation_popup.wpe_bootstraped .input-group').on('focus', '.form-control', function () {
        jQuery(this).closest('.input-group, .form-group').addClass('focus');
    }).on('blur', '.form-control', function () {
        jQuery(this).closest('.input-group, .form-group').removeClass('focus');
    });
    jQuery("#estimation_popup.wpe_bootstraped .pagination").on('click', "a", function () {
        jQuery(this).parent().siblings("li").removeClass("active").end().addClass("active");
    });
    jQuery("#estimation_popup.wpe_bootstraped .btn-group").on('click', "a", function () {
        jQuery(this).siblings().removeClass("active").end().addClass("active");
    });
    jQuery("#estimation_popup.wpe_bootstraped [data-toggle='switch']").wrap('<div class="switch"  data-on-label="<i class=\'fui-check\'></i>" data-off-label="<i class=\'fui-cross\'></i>" />').parent().bootstrapSwitch();
    jQuery("#estimation_popup.wpe_bootstraped input[type=checkbox][data-urltarget]").each(function () {
        jQuery(this).closest('.switch.has-switch').click(function () {
            if (jQuery(this).find('.switch-on').length > 0) {
                var win = window.open(jQuery(this).find('input[type=checkbox][data-urltarget]').attr('data-urltarget'), '_blank');
                win.focus();
            }
        })
    });
}


function wpe_getFormContent(formID, useCurrent) {
    var form = wpe_getForm(formID);
    var content = "";
    var contentGform = "";
    var totalTxt = "";
    var items = new Array();

    contentGform += "<p>Ref : " + form.current_ref + " </p>";
    var lastStepTitle = "";
    var cloneSteps = lfb_lastSteps.slice();
    if (useCurrent) {
        if (form.step != 'final') {
            cloneSteps.push(form.step);
        }
    }
    var checkedItems = new Array();
    jQuery.each(cloneSteps, function () {

        $panel = jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + this + '"]');

        if (jQuery.inArray(parseInt($panel.attr('data-stepid')), lfb_plannedSteps) >= 0 && !jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-title][data-stepid="' + this + '"]').is('.lfb_disabled')) {

            content += "<br/><p><u><b>" + $panel.data("title") + " :</b></u></p><br/>";
            contentGform += "\n\n---------" + $panel.data("title") + " :---------\n";

            $panel.find('.itemBloc:not(.lfb_disabled)').each(function () {
                var $itembloc = jQuery(this);
                var showSummary = true;

                if ($itembloc.find('div.selectable.checked').length > 0) {
                    var itemSelf = $itembloc.find('div.selectable.checked').get(0);
                    var quantityText = '';
                    if (jQuery(itemSelf).is('[data-resqt]')) {
                        jQuery(itemSelf).data('resqt', jQuery(itemSelf).attr('data-resqt'));
                    }
                    if (jQuery(itemSelf).is('[data-resprice]')) {
                        jQuery(itemSelf).data('resprice', jQuery(itemSelf).attr('data-resprice'));
                    }

                    if ($panel.is('[data-showstepsum="0"]') || !jQuery(itemSelf).is('[data-showinsummary="true"]')) {
                        showSummary = false;
                    }
                    var quantity = parseFloat(jQuery(itemSelf).data('resqt'));
                    var priceItem = parseFloat(jQuery(itemSelf).data('resprice'));
                    if (quantity == 0) {
                        quantity = 1;
                    }
                    if (quantity > 1) {
                        quantityText = quantity + 'X ';
                    }
                    if (jQuery(itemSelf).data('price')) {
                        if (jQuery(itemSelf).data('operation') == "+") {
                            if (form.currencyPosition == 'left') {
                                priceItem = form.currency + priceItem;
                            } else {
                                priceItem += form.currency;
                            }
                        } else if (jQuery(itemSelf).data('operation') == "-") {
                            if (form.currencyPosition == 'left') {
                                priceItem = '-' + form.currency + priceItem;
                            } else {
                                priceItem += '-' + form.currency;
                            }
                        } else if (jQuery(itemSelf).data('operation') == "/") {
                            priceItem = '-' + jQuery(itemSelf).data('price') + '%';
                        } else {
                            priceItem = '+' + jQuery(itemSelf).data('price') + '%';
                        }
                        if (showSummary) {
                            content += '    - ' + quantityText + jQuery(itemSelf).data("originaltitle") + ' : ' + priceItem + '<br/>';
                            contentGform += ' - ' + quantityText + jQuery(itemSelf).data("originaltitle") + ' : ' + priceItem + '\n';
                        }
                    } else {
                        if (showSummary) {
                            content += '    - ' + quantityText + jQuery(itemSelf).data("originaltitle") + '<br/>';
                            contentGform += ' - ' + quantityText + jQuery(itemSelf).data("originaltitle") + '\n';
                        }
                    }
                    var itemPriceS = parseFloat(jQuery(itemSelf).data('resprice'));
                    if (isNaN(itemPriceS)) {
                        itemPriceS = parseFloat(jQuery(itemSelf).data('price'));
                    }
                    var isSinglePrice = false;
                    if (jQuery(itemSelf).is('[data-singleprice="true"]')) {
                        isSinglePrice = true;
                    }
                    if (jQuery.inArray(jQuery(itemSelf).attr('data-itemid'), checkedItems) == -1) {
                        checkedItems.push(jQuery(itemSelf).attr('data-itemid'));
                        items.push({
                            label: jQuery(itemSelf).attr('data-title'),
                            itemid: jQuery(itemSelf).attr('data-itemid'),
                            price: itemPriceS,
                            quantity: quantity,
                            step: $panel.attr('data-title'),
                            stepid: parseInt($panel.attr('data-stepid')),
                            showInSummary: showSummary,
                            isSinglePrice: isSinglePrice
                        });
                    }
                }

                if ($itembloc.find('input[type=checkbox]:checked').length > 0) {
                    var itemSelf = $itembloc.find('input[type=checkbox]:checked').get(0);
                    var priceItem = parseFloat(jQuery(itemSelf).data('price'));

                    if ($panel.is('[data-showstepsum="0"]') || !jQuery(itemSelf).is('[data-showinsummary="true"]')) {
                        showSummary = false;
                    }
                    if (jQuery(itemSelf).data('price')) {

                        if (jQuery(itemSelf).data('operation') == "+") {
                            if (form.currencyPosition == 'left') {
                                priceItem = form.currency + priceItem;
                            } else {
                                priceItem += form.currency;
                            }
                        } else if (jQuery(itemSelf).data('operation') == "-") {
                            if (form.currencyPosition == 'left') {
                                priceItem = '-' + form.currency + priceItem;
                            } else {
                                priceItem += '-' + form.currency;
                            }
                        } else if (jQuery(itemSelf).data('operation') == "/") {
                            priceItem = '-' + priceItem + '%';
                        } else {
                            priceItem = '+' + priceItem + '%';
                        }
                        var title = jQuery(itemSelf).attr('data-originaltitle');
                        if (jQuery(itemSelf).is('select')) {
                            title += ' (' + jQuery(itemSelf).val() + ')';
                        }
                        if (showSummary) {
                            content += '    - ' + title + ' : ' + priceItem + '<br/>';
                            contentGform += ' - ' + title + ' : ' + priceItem + '\n';
                        }
                    } else {
                        var title = jQuery(itemSelf).attr('data-originaltitle');
                        if (jQuery(itemSelf).is('select')) {
                            title += ' : ' + jQuery(itemSelf).val() + '';
                        }
                        if (showSummary) {
                            content += '    - ' + title + '<br/>';
                            contentGform += ' - ' + title + '\n';
                        }
                    }
                    var label = jQuery(itemSelf).attr('data-title');
                    if (jQuery(itemSelf).is('select')) {
                        label += ' : ' + jQuery(itemSelf).val();
                    }

                    var isSinglePrice = false;
                    if (jQuery(itemSelf).is('[data-singleprice="true"]')) {
                        isSinglePrice = true;
                    }
                    if (jQuery.inArray(jQuery(itemSelf).attr('data-itemid'), checkedItems) == -1) {
                        checkedItems.push(jQuery(itemSelf).attr('data-itemid'));
                        items.push({
                            label: label,
                            itemid: jQuery(itemSelf).attr('data-itemid'),
                            price: parseFloat(jQuery(itemSelf).data('resprice')),
                            quantity: 1,
                            step: $panel.attr('data-title'),
                            stepid: parseInt($panel.attr('data-stepid')),
                            showInSummary: showSummary,
                            isSinglePrice: isSinglePrice
                        });
                    }
                }


                if ($itembloc.find('div[data-type="slider"]').length > 0) {
                    var itemSelf = $itembloc.find('div[data-type="slider"]').get(0);
                    var priceItem = parseFloat(jQuery(itemSelf).data('price'));
                    if ($panel.is('[data-showstepsum="0"]') || !jQuery(itemSelf).is('[data-showinsummary="true"]')) {
                        showSummary = false;
                    }
                    if (jQuery(itemSelf).data('price')) {
                        if (jQuery(itemSelf).data('operation') == "+") {
                            if (form.currencyPosition == 'left') {
                                priceItem = form.currency + priceItem;
                            } else {
                                priceItem += form.currency;
                            }
                        } else if (jQuery(itemSelf).data('operation') == "-") {
                            if (form.currencyPosition == 'left') {
                                priceItem = '-' + form.currency + priceItem;
                            } else {
                                priceItem += '-' + form.currency;
                            }
                        } else if (jQuery(itemSelf).data('operation') == "/") {
                            priceItem = '-' + priceItem + '%';
                        } else {
                            priceItem = '+' + priceItem + '%';
                        }
                        var title = jQuery(itemSelf).attr('data-originaltitle');
                        if (jQuery(itemSelf).is('select')) {
                            title += ' (' + jQuery(itemSelf).val() + ')';
                        }

                        if (showSummary) {
                            content += '    - ' + title + ' : ' + priceItem + '<br/>';
                            contentGform += ' - ' + title + ' : ' + priceItem + '\n';
                        }
                    } else {
                        var title = jQuery(itemSelf).attr('data-originaltitle');
                        if (jQuery(itemSelf).is('select')) {
                            title += ' : ' + jQuery(itemSelf).val() + '';
                        }
                        if (showSummary) {
                            content += '    - ' + title + '<br/>';
                            contentGform += ' - ' + title + '\n';
                        }
                    }
                    var label = jQuery(itemSelf).attr('data-title');
                    if (jQuery(itemSelf).is('select')) {
                        label += ' : ' + jQuery(itemSelf).val();
                    }

                    var isSinglePrice = false;
                    if (jQuery(itemSelf).is('[data-singleprice="true"]')) {
                        isSinglePrice = true;
                    }
                    if (jQuery.inArray(jQuery(itemSelf).attr('data-itemid'), checkedItems) == -1) {
                        checkedItems.push(jQuery(itemSelf).attr('data-itemid'));
                        items.push({
                            label: label,
                            itemid: jQuery(itemSelf).attr('data-itemid'),
                            price: parseFloat(jQuery(itemSelf).data('resprice')),
                            quantity: parseFloat(jQuery(itemSelf).slider('value')),
                            step: $panel.attr('data-title'),
                            stepid: parseInt($panel.attr('data-stepid')),
                            showInSummary: showSummary,
                            isSinglePrice: isSinglePrice
                        });
                    }
                }


                if ($itembloc.find('select[data-itemid]').length > 0) {
                    var itemSelf = $itembloc.find('select[data-itemid]').get(0);
                    var priceItem = parseFloat(jQuery(itemSelf).data('price'));
                    if ($panel.is('[data-showstepsum="0"]') || !jQuery(itemSelf).is('[data-showinsummary="true"]')) {
                        showSummary = false;
                    }
                    if (jQuery(itemSelf).data('price')) {
                        if (jQuery(itemSelf).data('operation') == "+") {
                            if (form.currencyPosition == 'left') {
                                priceItem = form.currency + priceItem;
                            } else {
                                priceItem += form.currency;
                            }
                        } else if (jQuery(itemSelf).data('operation') == "-") {
                            if (form.currencyPosition == 'left') {
                                priceItem = '-' + form.currency + priceItem;
                            } else {
                                priceItem += '-' + form.currency;
                            }
                        } else if (jQuery(itemSelf).data('operation') == "/") {
                            priceItem = '-' + priceItem + '%';
                        } else {
                            priceItem = '+' + priceItem + '%';
                        }
                        var title = jQuery(itemSelf).attr('data-originaltitle');
                        if (jQuery(itemSelf).is('select')) {
                            title += ' (' + jQuery(itemSelf).val() + ')';
                        }
                        if (showSummary) {
                            content += '    - ' + title + ' : ' + priceItem + '<br/>';
                            contentGform += ' - ' + title + ' : ' + priceItem + '\n';
                        }
                    } else {
                        var title = jQuery(itemSelf).attr('data-originaltitle');
                        if (jQuery(itemSelf).is('select')) {
                            title += ' : ' + jQuery(itemSelf).val() + '';
                        }
                        if (showSummary) {
                            content += '    - ' + title + '<br/>';
                            contentGform += ' - ' + title + '\n';
                        }
                    }
                    var label = jQuery(itemSelf).attr('data-title');

                    var isSinglePrice = false;
                    if (jQuery(itemSelf).is('[data-singleprice="true"]')) {
                        isSinglePrice = true;
                    }
                    if (jQuery.inArray(jQuery(itemSelf).attr('data-itemid'), checkedItems) == -1) {
                        checkedItems.push(jQuery(itemSelf).attr('data-itemid'));
                        items.push({
                            label: label,
                            itemid: jQuery(itemSelf).attr('data-itemid'),
                            price: parseFloat(jQuery(itemSelf).data('resprice')),
                            value: jQuery(itemSelf).val(),
                            quantity: 1,
                            step: $panel.attr('data-title'),
                            stepid: parseInt($panel.attr('data-stepid')),
                            showInSummary: showSummary,
                            isSinglePrice: isSinglePrice
                        });
                    }
                }

                if ($itembloc.find('input[type=file]').length > 0) {
                    var itemSelf = $itembloc.find('input[type=file]').get(0);
                    if ($panel.is('[data-showstepsum="0"]') || !jQuery(itemSelf).is('[data-showinsummary="true"]')) {
                        showSummary = false;
                    }
                    if (showSummary) {
                        content += '    - <span style="text-decoration: underline;" class="lfb_file">' + jQuery(itemSelf).data("originaltitle") + ' : ' + jQuery(itemSelf).val() + '</span><br/>';
                        contentGform += ' - ' + jQuery(itemSelf).data("originaltitle") + ' : ' + jQuery(itemSelf).val() + '\n';
                    }
                    if ($panel.is('[data-showstepsum="0"]') || !jQuery(itemSelf).is('[data-showinsummary="true"]')) {
                        showSummary = false;
                    }
                    if (jQuery.inArray(jQuery(itemSelf).attr('data-itemid'), checkedItems) == -1) {
                        checkedItems.push(jQuery(itemSelf).attr('data-itemid'));
                        items.push({
                            label: jQuery(itemSelf).data("title"),
                            itemid: jQuery(itemSelf).attr('data-itemid'),
                            value: jQuery(itemSelf).val(),
                            step: $panel.attr('data-title'),
                            stepid: parseInt($panel.attr('data-stepid')),
                            showInSummary: showSummary,
                            isFile: true
                        });
                    }

                }
                if ($itembloc.find('.lfb_dropzone').length > 0) {
                    var itemSelf = $itembloc.find('.lfb_dropzone').get(0);
                    if ($panel.is('[data-showstepsum="0"]') || !jQuery(itemSelf).is('[data-showinsummary="true"]')) {
                        showSummary = false;
                    }
                    var filesValue = '';
                    var filesValueG = '';
                    jQuery(itemSelf).find('.dz-preview[data-file]').each(function () {
                        filesValue += ' - <span class="lfb_file">' + jQuery(this).attr('data-file') + '</span>' + "<br/>";
                        filesValueG += ' - ' + jQuery(this).attr('data-file') + '\n';
                    });

                    if (showSummary) {
                        content += '    - <span class="lfb_file">' + jQuery(itemSelf).data("originaltitle") + ' : ' + filesValue + '</span><br/>';
                        contentGform += ' - ' + jQuery(itemSelf).data("originaltitle") + ' : ' + filesValueG + '\n\n';
                    }
                    if (jQuery.inArray(jQuery(itemSelf).attr('data-itemid'), checkedItems) == -1) {
                        checkedItems.push(jQuery(itemSelf).attr('data-itemid'));
                        items.push({
                            label: jQuery(itemSelf).data("title"),
                            itemid: jQuery(itemSelf).attr('data-itemid'),
                            value: filesValue,
                            step: $panel.attr('data-title'),
                            stepid: parseInt($panel.attr('data-stepid')),
                            showInSummary: showSummary,
                            isFile: true
                        });
                    }

                }

                if ($itembloc.find('input[type=text]:not(.lfb_colorpicker)').length > 0) {
                    var itemSelf = $itembloc.find('input[type=text]:not(.lfb_colorpicker)').get(0);
                    if ($panel.is('[data-showstepsum="0"]') || !jQuery(itemSelf).is('[data-showinsummary="true"]')) {
                        showSummary = false;
                    }

                    if (showSummary) {
                        content += '    - ' + jQuery(itemSelf).data("title") + ' : <b>' + jQuery(itemSelf).val() + '</b><br/>';
                        contentGform += ' - ' + jQuery(itemSelf).data("title") + ' : ' + jQuery(itemSelf).val() + ' \n';
                    }
                    if (jQuery.inArray(jQuery(itemSelf).attr('data-itemid'), checkedItems) == -1) {
                        checkedItems.push(jQuery(itemSelf).attr('data-itemid'));
                        items.push({
                            label: jQuery(itemSelf).data("title"),
                            itemid: jQuery(itemSelf).attr('data-itemid'),
                            value: jQuery(itemSelf).val(),
                            step: $panel.attr('data-title'),
                            stepid: parseInt($panel.attr('data-stepid')),
                            showInSummary: showSummary
                        });
                    }
                }
                if ($itembloc.find('input[type=number]:not(.lfb_colorpicker)').length > 0) {
                    var itemSelf = $itembloc.find('input[type=number]:not(.lfb_colorpicker)').get(0);
                    if ($panel.is('[data-showstepsum="0"]') || !jQuery(itemSelf).is('[data-showinsummary="true"]')) {
                        showSummary = false;
                    }
                    if (showSummary) {
                        content += '    - ' + jQuery(itemSelf).data("title") + ' : <b>' + jQuery(itemSelf).val() + '</b><br/>';
                        contentGform += ' - ' + jQuery(itemSelf).data("title") + ' : ' + jQuery(itemSelf).val() + ' \n';
                    }
                    if (jQuery.inArray(jQuery(itemSelf).attr('data-itemid'), checkedItems) == -1) {
                        checkedItems.push(jQuery(itemSelf).attr('data-itemid'));
                        items.push({
                            label: jQuery(itemSelf).data("title"),
                            itemid: jQuery(itemSelf).attr('data-itemid'),
                            value: jQuery(itemSelf).val(),
                            step: $panel.attr('data-title'),
                            stepid: parseInt($panel.attr('data-stepid')),
                            showInSummary: showSummary
                        });
                    }
                }
                if ($itembloc.find('.lfb_colorPreview').length > 0) {
                    var itemSelf = $itembloc.find('.lfb_colorPreview').get(0);

                    if ($panel.is('[data-showstepsum="0"]') || !jQuery(itemSelf).is('[data-showinsummary="true"]')) {
                        showSummary = false;
                    }
                    if (showSummary) {
                        content += '    - ' + jQuery(itemSelf).data("title") + ' : <b>' + jQuery(itemSelf).next('.lfb_colorpicker').val() + '</b><br/>';
                        contentGform += ' - ' + jQuery(itemSelf).data("title") + ' : ' + jQuery(itemSelf).next('.lfb_colorpicker').val() + ' \n';
                    }
                    if (jQuery.inArray(jQuery(itemSelf).attr('data-itemid'), checkedItems) == -1) {
                        checkedItems.push(jQuery(itemSelf).attr('data-itemid'));
                        items.push({
                            label: jQuery(itemSelf).data("title"),
                            itemid: jQuery(itemSelf).attr('data-itemid'),
                            value: jQuery(itemSelf).next('.lfb_colorpicker').val(),
                            step: $panel.attr('data-title'),
                            stepid: parseInt($panel.attr('data-stepid')),
                            showInSummary: showSummary
                        });
                    }
                }
                if ($itembloc.find('textarea').length > 0) {
                    var itemSelf = $itembloc.find('textarea').get(0);
                    if ($panel.is('[data-showstepsum="0"]') || !jQuery(itemSelf).is('[data-showinsummary="true"]')) {
                        showSummary = false;
                    }
                    if (showSummary) {
                        content += '    - ' + jQuery(itemSelf).data("title") + ' : <b>' + jQuery(itemSelf).val() + '</b><br/>';
                        contentGform += ' - ' + jQuery(itemSelf).data("title") + ' : ' + jQuery(itemSelf).val() + ' \n';
                    }
                    if (jQuery.inArray(jQuery(itemSelf).attr('data-itemid'), checkedItems) == -1) {
                        checkedItems.push(jQuery(itemSelf).attr('data-itemid'));
                        items.push({
                            label: jQuery(itemSelf).data("title"),
                            itemid: jQuery(itemSelf).attr('data-itemid'),
                            value: wpe_nl2br(jQuery(itemSelf).val()),
                            step: $panel.attr('data-title'),
                            stepid: parseInt($panel.attr('data-stepid')),
                            showInSummary: showSummary
                        });
                    }
                }

            });

        }
    });

    if (!form.price || form.price < 0) {
        form.price = 0;
    }
    var pattern = /^\d+(\.\d{2})?$/;
    if (!pattern.test(form.price)) {
        form.price = parseFloat(form.price).toFixed(2);
    }
    if (form.currencyPosition == 'left') {
        totalTxt += form.currency + form.price;
        contentGform += '\n\nTotal : ' + form.currency + form.price;
    } else {
        totalTxt += form.price + form.currency;
        contentGform += '\n\nTotal : ' + form.price + form.currency;
    }
    return new Array(content, totalTxt, items, contentGform);
}

function wpe_check_gform_response(formID) {
    var form = wpe_getForm(formID);
    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #gforms_confirmation_message').length > 0) {
        clearInterval(form.timer_gFormSubmit);
        if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm').length > 0 && form.price > 0) { 

            var payPrice = form.price;
            payPrice = parseFloat(payPrice) * (parseFloat(form.percentToPay) / 100);
            payPrice = parseFloat(payPrice).toFixed(2);
            if (form.priceSingle > 0) {
                var payPriceSingle = parseFloat(form.priceSingle) * (parseFloat(form.percentToPay) / 100);
                if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=a1]').length == 0) {
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm').append('<input type="hidden" name="a1" value="0">');
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm').append('<input type="hidden" name="p1" value="1">');
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm').append('<input type="hidden" name="t1" value="M">');
                }
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=a1]').val(payPriceSingle);
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=p1]').val(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=p3]').val());
            } else {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=a1]').remove();
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=t1]').remove();
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=p1]').remove();
            }
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=amount]').val(payPrice);
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=a3]').val(payPrice);
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=item_number]').val(form.current_ref);
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=item_name]').val(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=item_name]').val() + ' - ' + form.current_ref);
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [type="submit"]').trigger('click');


        } else {
            jQuery('#finalText').html(jQuery('#gform_wrapper_' + form.gravityFormID + ' .gforms_confirmation_message').html());
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide #gforms_confirmation_message').fadeIn();
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide #lfb_summary').fadeOut();
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide #lfb_couponContainer').fadeOut();
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide #lfb_legalNoticeContent').fadeOut();
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide .linkPrevious').fadeOut();
            wpe_finalStep(formID);
        }
    }
}

function wpe_quantity_less(btn, formID) {
    var $target = jQuery(btn).parent().parent().find('.icon_quantity');
    var min = parseFloat(jQuery(btn).parent().data('min'));
    var quantity = parseInt($target.html());
    if (quantity > 1 && quantity > min) {
        quantity--;
        $target.html(quantity);
        wpe_updatePrice(formID);
    }
}

function wpe_quantity_more(btn, formID) {
    var $target = jQuery(btn).parent().parent().find('.icon_quantity');
    var max = parseFloat(jQuery(btn).parent().data('max'));
    var quantity = parseFloat($target.html());
    if (quantity < max || max == 0) {
        quantity++;
        $target.html(quantity);
        wpe_updatePrice(formID);
    }
}


function wpe_checkEmail(email) {
    if (email.indexOf("@") != "-1" && email.indexOf(".") != "-1" && email != "")
        return true;
    return false;
}

function wpe_isIframe() {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
}
function wpe_cloneSummary(mode, formID) {
    var $summaryClone = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary').clone();

    if (mode) {
      //  $summaryClone.find('tr.lfb-hidden:not(.lfb_hidePrice)').removeClass('lfb-hidden');
    }
    $summaryClone.addClass('lfb-hidden');
    $summaryClone.uniqueId();
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary').after($summaryClone);
    $summaryClone.children('h4').remove();
    $summaryClone.find('*').each(function () {
        jQuery(this).css({
            fontSize: jQuery(this).css('font-size'),
            padding: jQuery(this).css('padding'),
            textAlign: jQuery(this).css('text-align'),
            lineHeight: jQuery(this).css('line-height')
        });
    });
    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table thead > tr > th:eq(1)').is('.lfb-hidden')) {
        $summaryClone.find('table thead > tr > th:eq(1)').remove();
        $summaryClone.find('table tbody td.lfb_valueTd').remove();
    }
    $summaryClone.find('th.sfb_summaryStep').css({fontSize: 22});
    $summaryClone.find('th.sfb_summaryStep').attr('align', 'center');
    $summaryClone.find('table').attr('width', '90%');
    $summaryClone.find('table').css('width', '90%');
    $summaryClone.find('table').attr('border', '1');
    $summaryClone.find('table').attr('bordercolor', lfb_rgb2hex($summaryClone.find('table').css('border-color')));
    $summaryClone.find('table').attr('style', 'width:90%;margin:0 auto;');
    $summaryClone.find('thead td, thead th').each(function () {
        jQuery(this).attr('bgcolor', lfb_rgb2hex($summaryClone.find('thead').css('background-color')));
        jQuery(this).css('background', lfb_rgb2hex($summaryClone.find('thead').css('background-color')));

    });
    $summaryClone.find('td,th').each(function () {
        var width = (jQuery(this).width() * 100) / $summaryClone.find('table').width();
        jQuery(this).css('width', parseInt(width) + '%');
    });
    $summaryClone.find('th.sfb_summaryStep').each(function () {
        jQuery(this).attr('bgcolor', lfb_rgb2hex(jQuery(this).css('background-color')));
    });
    $summaryClone.find('td,th').each(function () {
        jQuery(this).attr('color', lfb_rgb2hex(jQuery(this).css('color')));
        jQuery(this).html('<span style="color: ' + lfb_rgb2hex(jQuery(this).css('color')) + '; font-size: ' + jQuery(this).css('font-size') + '">' + jQuery(this).html() + '</span>');
    });


    $summaryClone.find('table .lfb-hidden').remove();
    $summaryClone.find('table').attr('cellspacing', '0');
    $summaryClone.find('table').attr('cellpadding', '8');
    $summaryClone.find('table').attr('bgcolor', '#FFFFFF');
    $summaryClone.find('td,th').each(function () {
        jQuery(this).attr('align', jQuery(this).css('text-align'));
        jQuery(this).css('padding', jQuery(this).css('padding'));
    });
    $summaryClone.find('thead > tr th:eq(0)').css({
        minWidth: 500
    });
    $summaryClone.find('*').each(function () {
        var color = lfb_rgb2hex(jQuery(this).css('color'));
        jQuery(this).css({
            color: ''
        });
        jQuery(this).attr('style', jQuery(this).attr('style') + ';color:' + color);
        if (jQuery(this).attr('style').length > 0) {
            jQuery(this).attr('style', jQuery(this).attr('style') + ';color:' + color);
        } else {
            jQuery(this).attr('style', 'color:' + color);
        }

    });
    $summaryClone.find('.lfb_file').each(function () {
        jQuery(this).removeAttr('style');
    });
    $summaryClone.html('<div style="padding: 24px; text-align: center;">' + $summaryClone.html() + '</div>');
    return $summaryClone;
}
function wpe_getContactInformations(formID) {
    var rep = new Array();
    rep['email'] = '';
    rep['phone'] = '';
    rep['firstName'] = '';
    rep['lastName'] = '';
    rep['address'] = '';
    rep['city'] = '';
    rep['state'] = '';
    rep['zip'] = '';
    rep['country'] = '';

    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] [data-fieldtype="email"]').each(function () {
        if (jQuery(this).val().length > 0 && wpe_checkEmail(jQuery(this).val())) {
            rep['email'] = jQuery(this).val();
        }
    });
    var phone = '';
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] [data-fieldtype="phone"]').each(function () {
        if (jQuery(this).val().length > 3) {
            rep['phone'] = jQuery(this).val();
        }
    });
    var firstName = '';
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] [data-fieldtype="firstName"]').each(function () {
        if (jQuery(this).val().length > 0) {
            rep['firstName'] = jQuery(this).val();
        }
    });
    var lastName = '';
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] [data-fieldtype="lastName"]').each(function () {
        if (jQuery(this).val().length > 0) {
            rep['lastName'] = jQuery(this).val();
        }
    });
    var address = '';
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] [data-fieldtype="address"]').each(function () {
        if (jQuery(this).val().length > 0) {
            rep['address'] = jQuery(this).val();
        }
    });
    var city = '';
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] [data-fieldtype="city"]').each(function () {
        if (jQuery(this).val().length > 0) {
            rep['city'] = jQuery(this).val();
        }
    });
    var state = '';
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] [data-fieldtype="state"]').each(function () {
        if (jQuery(this).val().length > 0) {
            rep['state'] = jQuery(this).val();
        }
    });
    var zip = '';
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] [data-fieldtype="zip"]').each(function () {
        if (jQuery(this).val().length > 0) {
            rep['zip'] = jQuery(this).val();
        }
    });
    var country = '';
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] [data-fieldtype="country"]').each(function () {
        if (jQuery(this).val().length > 0) {
            rep['country'] = jQuery(this).val();
        }
    });

    return rep;

}
function wpe_orderSend(formID, informations, email, fields) {

    var form = wpe_getForm(formID);
    var contentForm = wpe_getFormContent(formID);
    var content = contentForm[0];
    content = content.replace(/<br\/>/g, '[n]');
    var totalTxt = contentForm[1];
    var items = contentForm[2];
    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]').is('[data-subs]')) {
        totalTxt += jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]').attr('data-subs');
    }
    var usePaypalIpn = 0;
    var activatePaypal = true;
    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] [data-activatePaypal="true"]:not(:checked)').length > 0) {
        activatePaypal = false;
    }
    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #wtmt_paypalForm').is('[data-useipn="1"]')) {
        usePaypalIpn = 1;
    }
    var $summaryClone = wpe_cloneSummary(false, formID);
    var $summaryCloneA = wpe_cloneSummary(true, formID);
    var summaryData = $summaryClone.html();
    var summaryAData = $summaryCloneA.html();
    $summaryClone.remove();
    $summaryCloneA.remove();

    var infosCt = wpe_getContactInformations(formID);
    email = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] .emailField').val();
    if (wpe_checkEmail(infosCt['email'])) {
        email = infosCt['email'];
    }

    var total = parseFloat(form.price);
    var totalSub = 0;
    var subFrequency = '';
    var formTitle = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').attr('data-formtitle');
    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').is('[data-isSubs="true"]')) {
        total = parseFloat(form.priceSingle);
        totalSub = parseFloat(form.price);
        subFrequency = form.subscriptionText;
    }
    var stripeToken = '';
    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] [name="stripeToken"]').length > 0) {
        stripeToken = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] [name="stripeToken"]').val();
    }
    var stripeTokenB = '';
    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] [name="stripeTokenB"]').length > 0) {
        stripeTokenB = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] [name="stripeTokenB"]').val();
    }

    var fieldsLast = new Array();
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide input[type=text],#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide input[type=email], #estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide textarea').each(function () {
        if (jQuery(this).closest('#lfb_stripeForm').length == 0 && jQuery(this).closest('#wtmt_paypalForm').length == 0) {
            fieldsLast.push({
                fieldID: jQuery(this).prop('id').substr(6, 9),
                value: wpe_nl2br(jQuery(this).val())
            });
        }
    });

    jQuery.ajax({
        url: form.ajaxurl,
        type: 'post',
        data: {
            action: 'send_email',
            formID: form.formID,
            informations: informations,
            email: email,
            lastName: infosCt['lastName'],
            firstName: infosCt['firstName'],
            phone: infosCt['phone'],
            country: infosCt['country'],
            zip: infosCt['zip'],
            state: infosCt['state'],
            city: infosCt['city'],
            address: infosCt['address'],
            summary: summaryData,
            stripeToken: stripeToken,
            stripeTokenB: stripeTokenB,
            summaryA: summaryAData,
            totalTxt: totalTxt,
            email_toUser: form.email_toUser,
            usePaypalIpn: usePaypalIpn,
            discountCode: form.discountCode,
            formSession: jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_formSession').val(),
            total: total,
            totalSub: totalSub,
            subFrequency: subFrequency,
            formTitle: formTitle,
            contactSent: form.contactSent,
            contentTxt: content,
            items: items,
            fieldsLast: fieldsLast
        },
        success: function (current_ref) {
            $summaryClone.remove();
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_loader').delay(400).fadeOut();
            if (activatePaypal && jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm').length > 0 && (form.price > 0 || form.priceSingle > 0)) { 
                var payPrice = form.price;
                payPrice = parseFloat(payPrice) * (parseFloat(form.percentToPay) / 100);
                payPrice = parseFloat(payPrice).toFixed(2);
                if (form.priceSingle > 0) {
                    var payPriceSingle = parseFloat(form.priceSingle) * (parseFloat(form.percentToPay) / 100);
                    payPriceSingle = parseFloat(payPriceSingle).toFixed(2);
                    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=a1]').length == 0) {
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm').append('<input type="hidden" name="a1" value="0"/>');
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm').append('<input type="hidden" name="p1" value="1"/>');
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm').append('<input type="hidden" name="t1" value="M"/>');
                    }
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=a1]').val(payPriceSingle);
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=p1]').val(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=p3]').val());

                    if (payPrice <= 0) {
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=cmd]').val('_xclick');
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=a3]').remove();
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=t3]').remove();
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=p3]').remove();
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=bn]').remove();
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=no_note]').remove();
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=src]').remove();
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=a1]').remove();
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=t1]').remove();
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=p1]').remove();
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm').append('<input type="hidden" name="amount" value="' + payPriceSingle + '"/>');
                    }
                } else {
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=a1]').remove();
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=t1]').remove();
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=p1]').remove();
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=amount]').val(payPrice);
                }
                
                
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=return]').val(lfb_getRedirectionURL(formID));
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=a3]').val(payPrice);
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=custom]').val(content + '<hr/>' + informations);
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=item_number]').val(current_ref);
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=item_name]').val(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [name=item_name]').val() + ' - ' + current_ref);
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wtmt_paypalForm [type="submit"]').trigger('click');
            } else if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_stripeForm').length > 0 && form.price > 0) {
                var payPrice = form.price;
                payPrice = parseFloat(payPrice) * (parseFloat(form.percentToPay) / 100);
                payPrice = payPrice.toFixed(2);
                payPrice = payPrice.replace('.', '');
                wpe_finalStep(formID);

            } else if (!form.save_to_cart) {
                wpe_finalStep(formID);
            }
        }
    });

    if (form.save_to_cart) {
        var products = new Array();
        jQuery.each(lfb_lastSteps, function () {
            $panel = jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + this + '"]');
            $panel.find('div.selectable.checked:not(.lfb_disabled),input[type=checkbox]:checked:not(.lfb_disabled),[data-type="slider"]:not(.lfb_disabled)').each(function () {
                var quantity = 1;
                if (parseInt(jQuery(this).data('resqt')) > 0) {
                    quantity = parseInt(jQuery(this).data('resqt'));
                }
                if (jQuery(this).is('[data-type="slider"]')) {
                    quantity = parseInt(jQuery(this).slider('value'));
                    if (!isNaN(parseInt(jQuery(this).find('.tooltip-inner').html()))) {
                        quantity = parseInt(jQuery(this).find('.tooltip-inner').html());
                    }
                }
                if (parseInt(jQuery(this).data('prodid')) > 0) {
                    products.push({
                        quantity: quantity,
                        product_id: parseInt(jQuery(this).data('prodid')),
                        variation: parseInt(jQuery(this).attr('data-woovar'))
                    });
                }
            });
        });
        jQuery.ajax({
            url: form.ajaxurl,
            type: 'post',
            data: {
                action: 'lfb_cart_save',
                products: products
            },
            success: function () {
                wpe_finalStep(formID);
            }
        });
    }
}
function wpe_order(formID) {
    var form = wpe_getForm(formID);
    var isOK = true;
    var informations = '';
    var email = '';

    var fields = new Array();


    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide .form-group').removeClass('has-error');
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide input[type=text],#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide input[type=email], #estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide textarea').each(function () {
        if (jQuery(this).closest('#lfb_stripeForm').length == 0 && jQuery(this).closest('#wtmt_paypalForm').length == 0) {
            if (jQuery(this).is('.lfb_disabled')) {
                isOK = true;
            } else {
                if (jQuery(this).attr('data-required') && jQuery(this).attr('data-required') == 'true' && jQuery(this).val().length < 1) {
                    isOK = false;
                    jQuery(this).parent().addClass('has-error');
                    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').is('.wpe_fullscreen')) {
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').animate({
                            scrollTop: jQuery(this).parent().offset().top - (80 + parseInt(form.scrollTopMargin))
                        }, form.animationsSpeed * 2);
                    } else {
                        jQuery('body,html').animate({
                            scrollTop: jQuery(this).parent().offset().top - (80 + parseInt(form.scrollTopMargin))
                        }, form.animationsSpeed * 2);
                    }
                }
                if (jQuery(this).is('.emailField')) {
                    email = jQuery(this).val();
                }
                if (jQuery(this).is('.emailField') && !wpe_checkEmail(jQuery(this).val())) {
                    isOK = false;
                    jQuery(this).parent().addClass('has-error');
                    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').is('.wpe_fullscreen')) {
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').animate({
                            scrollTop: jQuery(this).parent().offset().top - (80 + parseInt(form.scrollTopMargin))
                        }, form.animationsSpeed * 2);
                    } else {
                        jQuery('body,html').animate({
                            scrollTop: jQuery(this).parent().offset().top - (80 + parseInt(form.scrollTopMargin))
                        }, form.animationsSpeed * 2);
                    }
                }
                if (jQuery(this).is('.toggle') && !jQuery(this).is('.opened')) {
                } else if (jQuery(this).is('#lfb_couponField')) {
                } else {
                    var dbpoints = ':';
                    if (jQuery('label[for="' + jQuery(this).attr('id') + '"]').html().lastIndexOf(':') == jQuery(this).attr('id').length - 1) {
                        dbpoints = '';
                    }
                    informations += '<p>' + jQuery('label[for="' + jQuery(this).attr('id') + '"]').html() + ' ' + dbpoints + ' <b>' + jQuery(this).val() + '</b></p>';
                    fields.push({
                        label: jQuery('label[for="' + jQuery(this).attr('id') + '"]').html(),
                        value: jQuery(this).val()
                    });
                }
            }
        }
    });
    if (form.legalNoticeEnable == 1) {
        if (!jQuery('#lfb_legalCheckbox').is(':checked')) {
            jQuery('#lfb_legalCheckbox').closest('.form-group').addClass('has-error');
            isOK = false;
        }
    }

    if (isOK == true) {
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide').find('#wpe_btnOrder,.linkPrevious').fadeOut(250);
        wpe_uploadFiles(formID, informations, email, fields);
    } else {
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_stripeForm .btn').prop('disabled', false);
    }
}

function wpe_previousStep(formID) {
    var form = wpe_getForm(formID);
    var deviceAgent = navigator.userAgent.toLowerCase();
    var agentID = deviceAgent.match(/(iPad|iPhone|iPod)/i);
    if (agentID) {
        jQuery('#estimation_popup :not(.ui-slider-handle) > .tooltip').remove();
        jQuery('body > .tooltip').remove();
    }
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .errorMsg').hide();

    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('div.selectable.checked:not(.prechecked)').each(function () {
        wpe_itemClick(jQuery(this), false, formID);
    });
    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('input[data-toggle="switch"]:checked:not(.prechecked)').each(function () {
        jQuery(this).trigger('click.auto');
    });

    var chkCurrentStep = false;
    var lastStepID = 0;
    var lastStepIndex = 0;
    jQuery.each(lfb_lastSteps, function (i) {
        var stepID = this;
        if (parseInt(stepID) == parseInt(form.step)) {
            chkCurrentStep = true;
        }
        if (!chkCurrentStep) {
            if ((jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] .lfb_item:not(.lfb-hidden)').length > 0 ||
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] .lfb_distanceError').length >0)
                    && !jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"]').is('.lfb_disabled')) {
                lastStepID = stepID;
                lastStepIndex = i;
            }
        }
    });
    lfb_lastSteps = jQuery.grep(lfb_lastSteps, function (value, i) {
        if (i <= lastStepIndex)
            return (value);
    });

    wpe_changeStep(lastStepID, formID);
}

function wpe_uploadFiles(formID, informations, email, fields) {
    var form = wpe_getForm(formID);
    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]').is('.wpe_fullscreen')) {
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]').css('overflow-y', 'hidden');
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]').animate({
            scrollTop: 0
        }, form.animationsSpeed * 2);
    } else {
        jQuery('body,html').animate({
            scrollTop: jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]').offset().top - (80 + parseInt(form.scrollTopMargin))
        }, form.animationsSpeed * 2);
    }

    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_loader').fadeIn(form.animationsSpeed * 2);
    setTimeout(function () {
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel').fadeOut(form.animationsSpeed * 2);
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #startInfos').fadeOut(form.animationsSpeed * 2);
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #genPrice').fadeOut(form.animationsSpeed * 2);
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide').fadeOut(form.animationsSpeed * 2);
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .btn-next').fadeOut(form.animationsSpeed);
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #finalText').css({
            opacity: 0,
            display: 'block'
        });
        setTimeout(function () {
            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]').is('.wpe_fullscreen')) {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]').animate({
                    scrollTop: 0
                }, form.animationsSpeed * 2);
            } else {
                jQuery('body,html').animate({
                    scrollTop: jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]').offset().top - (80 + parseInt(form.scrollTopMargin))
                }, form.animationsSpeed * 2);
            }
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #finalText').animate({opacity: 1}, form.animationsSpeed * 2);
        }, form.animationsSpeed * 4 + 50);
    }, form.animationsSpeed * 2 + 50);

    wpe_orderSend(formID, informations, email, fields);

}

function wpe_isAnyParentFixed($el, rep) {
    if (!rep) {
        var rep = false;
    }
    try {
        if ($el.parent().length > 0 && $el.parent().css('position') == "fixed") {
            rep = true;
        }
    } catch (e) {
    }
    if (!rep && $el.parent().length > 0) {
        rep = wpe_isAnyParentFixed($el.parent(), rep);
    }
    return rep;
}

function wpe_is_touch_device() {
    return (('ontouchstart' in window)
            || (navigator.MaxTouchPoints > 0)
            || (navigator.msMaxTouchPoints > 0));
}

function wpe_updateSummary(formID) {
    var form = wpe_getForm(formID);
    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary').length > 0) {
        var formContent = wpe_getFormContent(formID);
        var items = formContent[2];
        var step = -1;
        var hasValues = false;
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table tbody tr:not(.lfb_static)').remove();
        var priceClass = '';
        if (form.summary_hidePrices == 1) {
            priceClass = 'lfb-hidden lfb_hidePrice';
        }
        jQuery.each(items, function () {
            var item = this;
            if (item.label != undefined && item.label != "" && item.label != "undefined") {
                if (item.stepid != step) {
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table tbody #lfb_summaryDiscountTr').before('<tr><th colspan="4" class="sfb_summaryStep" data-step="' + item.stepid + '"><strong>' + item.step + '</strong></th>');
                }
                step = item.stepid;

                if (isNaN(item.quantity)) {
                    item.quantity = 1;
                }
                if (isNaN(item.price)) {
                    item.price = 0;
                }
                var value = item.value;
                if (item.value === undefined) {
                    value = "";
                }
                if (value != "") {
                    hasValues = true;
                }
                var itemPrice = item.price;
                var itemQt = item.quantity;
                if (value != "" && itemPrice == 0 && itemQt == 1) {
                    itemQt = '';
                    itemPrice = '';
                } else {
                    var isNegative = false;
                    if (parseFloat(itemPrice) < 0) {
                        isNegative = true;
                        itemPrice *= -1;
                    }
                    if (form.currencyPosition == 'left') {
                        itemPrice = form.currency + '' + wpe_formatPrice(parseFloat(itemPrice).toFixed(2), formID);
                    } else {
                        itemPrice = wpe_formatPrice(parseFloat(itemPrice).toFixed(2), formID) + '' + form.currency;
                    }
                    if (isNegative) {
                        itemPrice = '- ' + itemPrice;
                    }
                }
                var classIsFile = '';
                if (item.isFile) {
                }
                var cssQt = '';
                if (form.summary_hideQt == 1) {
                    cssQt = 'lfb-hidden';
                }
                if (form.summary_hideZero == 1 && item.price == 0) {
                    itemPrice = '';
                }
                var hideClass = '';
                if (!item.showInSummary) {
                    hideClass = 'lfb-hidden';
                }
                if(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] [data-itemid="'+item.itemid+'"]').is('[data-hideqtsum="true"]')){
                    itemQt = '';
                }
                if (form.reductionResult > 0) {
                    var reduction = parseFloat(form.reductionResult).toFixed(2) + form.currency;
                    if (form.currencyPosition == 'left') {
                        reduction = form.currency + parseFloat(form.reductionResult).toFixed(2);
                    }
                    reduction = '-' + reduction;
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary #lfb_summaryDiscount>span').html(reduction);
                    if (!form.discountCodeDisplayed) {
                        var discLabel = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary #lfb_summaryDiscountTr th[colspan]').html();
                        if (discLabel.indexOf('<i>') < 0) {
                            if (discLabel.substr(discLabel.length - 1, 1) == ':') {
                                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary #lfb_summaryDiscountTr th[colspan]').html(discLabel.substr(0, discLabel.length - 1) + ' <i>(' + form.discountCode + ')</i> :');
                            } else {
                                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary #lfb_summaryDiscountTr th[colspan]').html(discLabel + ' <i>(' + form.discountCode + ')</i>');
                            }
                        }
                    }
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary #lfb_summaryDiscountTr').slideDown();
                }
                if (form.priceSingle > 0 && !item.isSinglePrice && (itemPrice != "" || itemPrice > 0)) {
                    itemPrice += ' ' + form.subscriptionText;
                }
                if (form.price <= 0) {
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary .lfb_subTxt').hide();
                } else {
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary .lfb_subTxt').show();
                }
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table tbody #lfb_summaryDiscountTr').before('<tr data-itemstep="' + item.stepid + '" class="' + hideClass + '"><td>' + item.label + '</td><td class="lfb_valueTd ' + classIsFile + '">' + value + '</td><td class="' + cssQt + '">' + itemQt + '</td><td class="' + priceClass + '">' + itemPrice + '</td></tr>');
            }
        });

        if (!form.price || form.price < 0) {
            form.price = 0;
        }
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table tr th.sfb_summaryStep').each(function () {
            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table tr[data-itemstep="' + jQuery(this).attr('data-step') + '"]:not(.lfb-hidden)').length == 0) {
                jQuery(this).parent().addClass('lfb-hidden');
            }
        });
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table #lfb_summaryTotal>span:eq(0)').html(parseFloat(form.price).toFixed(2));
        var colspan = 4;
        if (form.summary_hideQt == 1) {
            colspan -= 1;
        }
        if (form.summary_hidePrices == 1) {
            colspan -= 1;
        }
        if (!hasValues) {
            colspan -= 1;
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table td.lfb_valueTd').hide();
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table thead th:eq(1)').hide().addClass('lfb-hidden');
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table tbody tr#sfb_summaryTotalTr th[colspan]').attr('colspan', colspan - 1);
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table tbody tr#lfb_summaryDiscountTr th[colspan]').attr('colspan', colspan - 1);
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table tbody tr th.sfb_summaryStep').attr('colspan', colspan);
        } else {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table thead th:eq(1)').show().removeClass('lfb-hidden');
            
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table tbody tr:eq(1)').show();
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table tbody td.lfb_valueTd').show();
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table tbody tr#sfb_summaryTotalTr th[colspan]').attr('colspan', colspan - 1);
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table tbody tr#lfb_summaryDiscountTr th[colspan]').attr('colspan', colspan - 1);
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table tbody tr th.sfb_summaryStep').attr('colspan', colspan);
        }
    }
}

function wpe_changeStep(stepID, formID) {
    var form = wpe_getForm(formID);
    
    jQuery('#estimation_popup :not(.ui-slider-handle) > .tooltip').remove();
    jQuery('body > .tooltip').remove();
    
    if (form.intro_enabled > 0 || form.step > 0) {
        var posTop = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]  #genPrice').offset().top - 100;

        if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]').is('.wpe_fullscreen')) {
            posTop = 0;
            if (form.intro_enabled > 0) {
                posTop = 200;
            }
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]').animate({
                scrollTop: posTop
            }, 250);
        } else {
            if (jQuery('header').length > 0 && wpe_isAnyParentFixed(jQuery('header'))) {
                posTop -= jQuery('header').height();
            }
            posTop -= (48 + parseInt(form.scrollTopMargin));
            jQuery('body,html').animate({
                scrollTop: posTop
            }, 250);
        }
    }
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] .quantityBtns').removeClass('open');
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] .quantityBtns').fadeOut(form.animationsSpeed / 4);
    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide').fadeOut(form.animationsSpeed * 2);
    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .btn-next').fadeOut(form.animationsSpeed / 2);
    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .linkPrevious').fadeOut(form.animationsSpeed / 2);
    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .lfb_distanceError').fadeOut(form.animationsSpeed / 2);
     


    if (stepID == 'final') {
        wpe_updateSummary(formID);
    }

    var $title = jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"]').find('h2.stepTitle');
    var $des = jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"]').find('p.lfb_stepDescription');
    var $content = jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"]').find('.genContent');
    var totalBottom = jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"]').find('.lfb_totalBottomContainer');
    $content.find('.genContentSlide').removeClass('active');
    $content.find('.genContentSlide').eq(0).addClass('active');
    
    
    $content.animate({
        opacity: 0
    }, form.animationsSpeed);
    $des.animate({
        opacity: 0
    }, form.animationsSpeed);
    $title.removeClass('positioned');
    $title.css({
        "-webkit-transition": "none",
        "transition": "none"
    });
    totalBottom.animate({
        opacity: 0
    }, form.animationsSpeed);

    if (stepID > 0 && typeof ga !== 'undefined') {
        try {
            ga('set', 'page', location.pathname + "#" + $title.html());
            ga('send', 'pageview');
        } catch (e) {
        }
    }

    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"]').css('opacity', 0).show();
    var titleHeight = jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] h2.stepTitle').height();
    var heightP = jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"]').outerHeight() + 160 + titleHeight;

    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"]').hide().css('opacity', 1);
    var animSpeed = form.animationsSpeed * 4.5;

    if (form.step == 1) {
        wpe_initPanelResize(formID);
        animSpeed = form.animationsSpeed * 2.5;
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"]').fadeIn(form.animationsSpeed * 2);
    } else {
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"]').delay(form.animationsSpeed * 2).fadeIn(form.animationsSpeed * 2);
    }

    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #finalSlide .estimation_project').length > 0) {
        var contentForm = wpe_getFormContent(formID);
        var content = contentForm[3];
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #finalSlide .estimation_project textarea').val(content);
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #finalSlide .estimation_total input').val(form.price);

    }
    setTimeout(function () {
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel').css('min-height', heightP);
        $title.css({
            "-webkit-transition": "all 0.3s ease-out",
            "transition": "all 0.3s ease-out"
        }).addClass('positioned');
        $content.css({
            paddingTop: $des.height() + $title.height() + 70
        });
        $content.delay(form.animationsSpeed * 2).animate({
            opacity: 1
        }, form.animationsSpeed);
        $des.delay(form.animationsSpeed).animate({
            opacity: 1,
            top: $title.height() + 60
        }, form.animationsSpeed);

        totalBottom.delay(form.animationsSpeed * 2).animate({
            opacity: 1
        }, form.animationsSpeed);
        if(!jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] .btn-next').is('.lfb_disabledBtn')){
            jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] .btn-next').css('display', 'inline-block').hide();
            setTimeout(function(){
                if(!jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] .btn-next').is('.lfb_disabledBtn')){
                    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] .btn-next').fadeIn(500);
                setTimeout(function(){
                   if(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] .btn-next').is('.lfb_disabledBtn')){
                    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] .btn-next').hide();
                    }
                },550);
                }
            },form.animationsSpeed * 2);
        } else {
             jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] .btn-next').hide();
        }
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] .linkPrevious').delay(form.animationsSpeed * 3).fadeIn(500);

        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] .lfb_distanceError').delay(form.animationsSpeed * 3).fadeIn(500);

        if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
            var deviceAgent = navigator.userAgent.toLowerCase();
            var agentID = deviceAgent.match(/(iPad|iPhone|iPod)/i);
            if (agentID) {
                $content.delay(750).find('[data-toggle="tooltip"]').tooltip({
                    html: true,
                    container: '#estimation_popup',
                    trigger: 'manual'
                });
            } else {
                $content.delay(750).find('[data-toggle="tooltip"]').tooltip({
                    html: true,
                    container: '#estimation_popup'
                });
            }
            $content.on('enter', function () {
                if (this.options.trigger == 'hover' && 'ontouchstart' in document.documentElement) {
                    return;
                }
            });
        }
        setTimeout(function () {
            $content.find('.wpe_itemQtField').each(function () {
                if (jQuery(this).parent().next().is('.itemDes')) {
                    jQuery(this).css({
                        marginTop: 20 + jQuery(this).parent().next().outerHeight()
                    });
                }
            });
            form.step = stepID;
            setTimeout(function () {
            
             jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"]').find('[data-usedistance="true"]').each(function(){
                lfb_removeDistanceError(jQuery(this).attr('data-itemid'),formID);
            });
        },(form.animationsSpeed * 2)+550);
            wpe_updatePrice(formID);
            if(form.backFomFinal){
                form.backFomFinal = false;
            }
        }, 300);


    }, animSpeed);

        jQuery('#estimation_popup :not(.ui-slider-handle) > .tooltip').remove();
        jQuery('body > .tooltip').remove();
    
    wpe_updatePrice(formID);
}

function wpe_findPotentialsSteps(originStepID, formID) {
    var form = wpe_getForm(formID);
    var potentialSteps = new Array();
    var conditionsArray = new Array();
    var noConditionsSteps = new Array();
    var maxConditions = 0;
    jQuery.each(form.links, function () {
        var link = this;

        if (link.originID == originStepID) {
            var error = false;
            var errorOR = true;
            if (link.conditions && link.conditions != "[]") {
                link.conditionsO = JSON.parse(link.conditions);
                var errors = lfb_checkConditions(link.conditionsO, formID);
                error = errors.error;
                errorOR = errors.errorOR;
            } else {
                noConditionsSteps.push(link.destinationID);
                errorOR = false;
            }
            if ((link.operator == 'OR' && !errorOR) || (link.operator != 'OR' && !error)) {
                link.conditionsO = JSON.parse(link.conditions);
                conditionsArray.push({
                    stepID: parseInt(link.destinationID),
                    nbConditions: link.conditionsO.length
                });
                if (link.conditionsO.length > maxConditions) {
                    maxConditions = link.conditionsO.length;
                }
                potentialSteps.push(parseInt(link.destinationID));

            }
        }
    });
    if (originStepID == 0) {
        potentialSteps.push(parseInt(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]  #mainPanel .genSlide[data-start="1"]').attr('data-stepid')));
    }
    if (potentialSteps.length == 0) {
        potentialSteps.push('final');
    } else if (noConditionsSteps.length > 0 && noConditionsSteps.length < potentialSteps.length) {
        jQuery.each(noConditionsSteps, function () {
            var removeItem = this;
            potentialSteps = jQuery.grep(potentialSteps, function (value) {
                return value != removeItem;
            });
        });
        if (maxConditions > 0) {
            jQuery.each(potentialSteps, function (stepID) {
                jQuery.each(conditionsArray, function (condition) {
                    if (condition.stepID == stepID && condition.nbConditions < maxConditions) {
                        potentialSteps = jQuery.grep(potentialSteps, function (value) {
                            return value != stepID;
                        });
                    }
                });
            });
        }
    }

    return potentialSteps;
}

function lfb_checkConditions(conditions, formID) {
    var error = false;
    var errorOR = true;

    jQuery.each(conditions, function () {
        var condition = this;
        if (condition.interaction.substr(0, 1) != '_') {
            var stepID = condition.interaction.substr(0, condition.interaction.indexOf('_'));
            var itemID = condition.interaction.substr(condition.interaction.indexOf('_') + 1, condition.interaction.length);
            var $item = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] .genContent [data-itemid="' + itemID + '"]');
            if ($item.is('.lfb_disabled') || $item.closest('.genSlide').is('.lfb_disabled')) {
                error = true;
            } else {
                condition.value = condition.value;
                 if(condition.value){ condition.value = condition.value.replace(/\`/g, "'");}
                switch (condition.action) {
                    case "clicked":
                        if ($item.is('[type="checkbox"]')) {
                            if (!$item.is(':checked')) {
                                error = true;
                            }
                            if ($item.is(':checked')) {
                                errorOR = false;
                            }
                        } else {
                            if (!$item.is('.checked') && !$item.is(':checked')) {
                                error = true;
                            }
                            if ($item.is('.checked') || $item.is(':checked')) {
                                errorOR = false;
                            }
                        }

                        break;
                    case "unclicked":
                        if ($item.is(':not([type="checkbox"]).checked') || $item.is(':checked')) {
                            error = true;
                        }
                        if (!$item.is(':not([type="checkbox"]).checked') && !$item.is(':checked')) {
                            errorOR = false;
                        }
                        break;
                    case "filled":
                        if ($item.is('.lfb_dropzone')) {
                            if ($item.find('.dz-preview[data-file].dz-success.dz-complete').length == 0) {
                                error = true;
                            } else {
                                errorOR = false;
                            }
                        } else {
                            if ($item.val().length == 0) {
                                error = true;
                            } else {
                                errorOR = false;
                            }
                        }

                        break;
                    case "equal":
                        if ($item.val() != condition.value) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                        break;
                    case "PriceSuperior":
                        var price = parseFloat($item.data('resprice'));
                        if ($item.is('.selectable:not(.checked)') || $item.is('input[type=checkbox]:not(:checked)') || price <= condition.value) {
                            error = true;
                        }
                        if (($item.is('.checked') || $item.is(':checked')) && price > condition.value) {
                            errorOR = false;
                        }
                        break;
                    case "PriceInferior":
                        var price = parseFloat($item.data('resprice'));
                        if ($item.is('.selectable:not(.checked)') || $item.is('input[type=checkbox]:not(:checked)') || price >= condition.value) {
                            error = true;
                        }
                        if (($item.is('.checked') || $item.is(':checked')) && price < condition.value) {
                            errorOR = false;
                        }
                        break;
                    case "PriceEqual":
                        var price = parseFloat($item.data('resprice'));
                        if ($item.is('.selectable:not(.checked)') || $item.is('input[type=checkbox]:not(:checked)') || price != condition.value) {
                            error = true;
                        }
                        if (($item.is('.checked') || $item.is(':checked')) && price == condition.value) {
                            errorOR = false;
                        }
                        break;
                    case "QtSuperior":
                        if ($item.is('.selectable:not(.checked)') || ($item.find('.icon_quantity').length > 0 && parseInt($item.find('.icon_quantity').html()) <= condition.value) || ($item.find('.wpe_qtfield').length > 0 && parseInt($item.find('.wpe_qtfield').val()) <= condition.value) || ($item.is('[data-type="slider"]') && parseInt($item.slider("value")) <= condition.value)) {
                            error = true;
                        }
                        if ($item.is('.selectable.checked') && ((parseInt($item.find('.icon_quantity').html()) > condition.value) || (parseInt($item.find('.wpe_qtfield').val()) > condition.value) || ($item.is('[data-type="slider"]') && parseInt($item.slider("value")) > condition.value))) {
                            errorOR = false;
                        }
                        if ($item.is('input')) {
                            if (parseInt($item.val()) <= condition.value) {
                                error = true;
                            }
                            if (parseInt($item.val()) > condition.value) {
                                errorOR = false;
                            }
                        }

                        break;
                    case "QtInferior":
                        if ($item.is('.selectable:not(.checked)') || ($item.find('.icon_quantity').length > 0 && parseInt($item.find('.icon_quantity').html()) >= condition.value) || ($item.find('.wpe_qtfield').length > 0 && parseInt($item.find('.wpe_qtfield').val()) >= condition.value) || ($item.is('[data-type="slider"]') && parseInt($item.slider("value")) >= condition.value)) {
                            error = true;
                        }
                        if ($item.is('.selectable.checked') && ((parseInt($item.find('.icon_quantity').html()) < condition.value) || (parseInt($item.find('.wpe_qtfield').val()) < condition.value) || ($item.is('[data-type="slider"]') && parseInt($item.slider("value")) < condition.value))) {
                            errorOR = false;
                        }
                        if ($item.is('input')) {
                            if (parseInt($item.val()) >= condition.value) {
                                error = true;
                            }
                            if (parseInt($item.val()) < condition.value) {
                                errorOR = false;
                            }
                        }
                        break;
                    case "QtEqual":
                        if ($item.is('.selectable:not(.checked)') || ($item.find('.icon_quantity').length > 0 && parseInt($item.find('.icon_quantity').html()) != condition.value) || ($item.find('.wpe_qtfield').length > 0 && parseInt($item.find('.wpe_qtfield').val()) != condition.value) || ($item.is('[data-type="slider"]') && parseInt($item.slider("value")) != condition.value)) {
                            error = true;
                        }
                        if ($item.is('.selectable.checked') && ((parseInt($item.find('.icon_quantity').html()) == condition.value) || (parseInt($item.find('.wpe_qtfield').val()) == condition.value) || ($item.is('[data-type="slider"]') && parseInt($item.slider("value")) == condition.value))) {
                            errorOR = false;
                        }
                        if ($item.is('input')) {
                            if (parseInt($item.val()) != condition.value) {
                                error = true;
                            }
                            if (parseInt($item.val()) == condition.value) {
                                errorOR = false;
                            }
                        }
                        break;
                    case "superior":
                        if ($item.is('.lfb_datepicker')) {
                            if (jQuery.datepicker.formatDate("yy-mm-dd", $item.datepicker("getDate")) <= condition.value) {
                                error = true;
                            }
                            if (jQuery.datepicker.formatDate("yy-mm-dd", $item.datepicker("getDate")) > condition.value) {
                                errorOR = false;
                            }
                        } else if ($item.is('input[type="number"]')) {
                            if (parseFloat($item.val()) <= parseFloat(condition.value)) {
                                error = true;
                            }
                            if (parseFloat($item.val()) > parseFloat(condition.value)) {
                                errorOR = false;
                            }
                        }
                        break;
                    case "inferior":
                        if ($item.is('.lfb_datepicker')) {
                            if (jQuery.datepicker.formatDate("yy-mm-dd", $item.datepicker("getDate")) >= condition.value) {
                                error = true;
                            }
                            if (jQuery.datepicker.formatDate("yy-mm-dd", $item.datepicker("getDate")) < condition.value) {
                                errorOR = false;
                            }
                        } else if ($item.is('input[type="number"]')) {
                            if (parseFloat($item.val()) >= parseFloat(condition.value)) {
                                error = true;
                            }
                            if (parseFloat($item.val()) < parseFloat(condition.value)) {
                                errorOR = false;
                            }
                        }
                        break;
                    case "equal":
                        if ($item.is('.lfb_datepicker')) {
                            if (jQuery.datepicker.formatDate("yy-mm-dd", $item.datepicker("getDate")) != condition.value) {
                                error = true;
                            }
                            if (jQuery.datepicker.formatDate("yy-mm-dd", $item.datepicker("getDate")) == condition.value) {
                                errorOR = false;
                            }
                        } else if ($item.is('input[type="number"]')) {
                            if (parseFloat($item.val()) != parseFloat(condition.value)) {
                                error = true;
                            }
                            if (parseFloat($item.val()) == parseFloat(condition.value)) {
                                errorOR = false;
                            }
                        }
                        break;
                }
            }
        } else {
            if (condition.interaction == "_total") {
                switch (condition.action) {
                    case "superior":
                        if (form.price <= condition.value) {
                            error = true;
                        }
                        if (form.price > condition.value) {
                            errorOR = false;
                        }
                        break;
                    case "inferior":
                        if (form.price >= condition.value) {
                            error = true;
                        }
                        if (form.price < condition.value) {
                            errorOR = false;
                        }
                        break;
                    case "equal":
                        if (form.price != condition.value) {
                            error = true;
                        }
                        if (form.price == condition.value) {
                            errorOR = false;
                        }
                        break;
                }
            } else if (condition.interaction == "_total_qt") {
                var totalQt = wpe_getTotalQuantities(formID);
                switch (condition.action) {
                    case "superior":
                        if (totalQt <= condition.value) {
                            error = true;
                        }
                        if (totalQt > condition.value) {
                            errorOR = false;
                        }
                        break;
                    case "inferior":
                        if (totalQt >= condition.value) {
                            error = true;
                        }
                        if (form.price < condition.value) {
                            errorOR = false;
                        }
                        break;
                    case "equal":
                        if (totalQt != condition.value) {
                            error = true;
                        }
                        if (totalQt == condition.value) {
                            errorOR = false;
                        }
                        break;
                }
            }
        }
    });

    return {
        error: error,
        errorOR: errorOR
    };
}

function wpe_nextStep(formID) {
    var form = wpe_getForm(formID);
    jQuery('#lfb_bootstraped :not(.ui-slider-handle) > .tooltip').remove();
    var deviceAgent = navigator.userAgent.toLowerCase();
    var agentID = deviceAgent.match(/(iPad|iPhone|iPod)/i);
    if (agentID) {
        setTimeout(function () {
            jQuery('#lfb_bootstraped :not(.ui-slider-handle) > .tooltip').remove();
        }, 500);
    }

    lfb_updateShowSteps(formID);
    jQuery('.errorMsg').hide();
    var chkSelection = true;
    var chkSelectionitem = true;
    var maxConditions = 0;

    var potentialSteps = wpe_findPotentialsSteps(form.step, formID);

    if (form.step > 0) {
        if (jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').data('required') == true) {
            chkSelection = false;
            if ((jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('select:not(.lfb_disabled)').length > 0)
                    || (jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('div.selectable.checked:not(.lfb_disabled)').length > 0)
                    || (jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('input[data-toggle="switch"]:checked:not(.lfb_disabled)').length > 0)
                    || (jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('input[type=text][data-title].checked:not(.lfb_disabled)').length > 0)
                    || (jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('input[type=number][data-itemid].checked:not(.lfb_disabled)').length > 0)
                    || (jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('input[type=file].checked:not(.lfb_disabled)').length > 0)
                    || (jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('.lfb_colorPreview:not(.lfb_disabled)').length > 0)
                    || (jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('.dz-preview[data-file].dz-success.dz-complete').length > 0)) {
                chkSelection = true;
            }
        }
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('.has-error').removeClass('has-error');
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('.icon_select.lfb_error').removeClass('lfb_error');
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('input[type=text][data-required="true"]:not(.lfb_disabled)').each(function () {
            if (jQuery(this).val().length < 1) {
                chkSelectionitem = false;
                jQuery(this).parent().addClass('has-error');
            } else if (jQuery(this).is('[data-fieldtype="email"]') && !wpe_checkEmail(jQuery(this).val())) {
                chkSelectionitem = false;
                jQuery(this).parent().addClass('has-error');
            }

        });
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('input[type=text][minlength]:not(.lfb_disabled)').each(function () {
            if (jQuery(this).val().length < jQuery(this).attr('minlength')) {
                chkSelectionitem = false;
                jQuery(this).parent().addClass('has-error');
            }
        });
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('input[type=text][minlength]:not(.lfb_disabled)').each(function () {
            if (jQuery(this).val().length < jQuery(this).attr('minlength')) {
                chkSelectionitem = false;
                jQuery(this).parent().addClass('has-error');
            }
        });
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('input[type=number][data-required="true"]:not(.lfb_disabled)').each(function () {
            if (jQuery(this).val() == '' || jQuery(this).val() == 0) {
                chkSelectionitem = false;
                jQuery(this).parent().addClass('has-error');
            }
        });
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('.lfb_dropzone[data-required="true"]:not(.lfb_disabled)').each(function () {

            if (jQuery(this).find('.dz-preview[data-file].dz-success.dz-complete').length == 0) {
                chkSelectionitem = false;
                jQuery(this).parent().addClass('has-error');
            }
        });
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('textarea[data-required="true"]:not(.lfb_disabled)').each(function () {
            if (jQuery(this).val().length < 1) {
                chkSelectionitem = false;
                jQuery(this).parent().addClass('has-error');
            }
        });
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('input[type=checkbox][data-required="true"]:not(.lfb_disabled)').each(function () {
            if (!jQuery(this).is(':checked')) {
                chkSelectionitem = false;
                jQuery(this).parent().addClass('has-error');
            }
        });
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('.selectable[data-required="true"]:not(.lfb_disabled)').each(function () {
            if (!jQuery(this).is('.checked')) {
                chkSelectionitem = false;
                jQuery(this).find('.icon_select').addClass('lfb_error');
            }
        });
    }

    if (chkSelection && chkSelectionitem) {
        lfb_lastStepID = form.step;
        if ((parseFloat(lfb_lastStepID) == parseInt(lfb_lastStepID)) && !isNaN(lfb_lastStepID)) {
            if (jQuery.inArray(parseInt(form.step), lfb_lastSteps) == -1) {
                lfb_lastSteps.push(parseInt(form.step));
            }
        }
        var title = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + lfb_lastStepID + '"] .stepTitle').html();
        history.pushState({id: lfb_lastStepID}, '', '');
        var nextStepID = potentialSteps[0];
        if (nextStepID != 'final') {
            nextStepID = wpe_getNextEnabledStep(formID, potentialSteps);
            if (nextStepID == -1) {
                nextStepID = 'final';
            }
        }
        if (form.sendContactASAP == 1 && jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"] [data-fieldtype="email"]').length > 0
                && wpe_checkEmail(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"] [data-fieldtype="email"]').val())) {
            form.contactSent = 1;
            var infosCt = wpe_getContactInformations(formID);
            jQuery.ajax({
                url: form.ajaxurl,
                type: 'post',
                data: {
                    action: 'lfb_sendCt',
                    formID: formID,
                    email: infosCt['email'],
                    lastName: infosCt['lastName'],
                    firstName: infosCt['firstName'],
                    phone: infosCt['phone'],
                    country: infosCt['country'],
                    zip: infosCt['zip'],
                    state: infosCt['state'],
                    city: infosCt['city'],
                    address: infosCt['address']
                },
                success: function (rep) {
                }
            });
        }
        wpe_changeStep(nextStepID, formID);
    } else if (!chkSelection) {
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] .errorMsg').slideDown();
    }
}

function wpe_getNextEnabledStep(formID, potentialSteps) {
    var rep = -1;
    var stepID = potentialSteps[0];
    if (stepID != 'final') {
        if (!jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]  #mainPanel .genSlide[data-stepid="' + stepID + '"]').is('.lfb_disabled') &&
                (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] .lfb_item:not(.lfb-hidden)').length > 0
                || jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + stepID + '"] .lfb_distanceError').length >0)) {
            rep = stepID;
        } else {
            lfb_lastSteps.push(parseInt(stepID));
            wpe_updatePrice(formID);
            rep = wpe_getNextEnabledStep(formID, wpe_findPotentialsSteps(parseInt(stepID), formID));
        }
    }

    return rep;
}

function wpe_openGenerator(formID) {
    var form = wpe_getForm(formID);
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]  #startInfos > p').slideDown();

    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #btnStart').parent().fadeOut(form.animationsSpeed, function () {
        if (form.showSteps != '2') {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] .genPrice').fadeIn(form.animationsSpeed);
        }
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel').fadeIn(form.animationsSpeed + form.animationsSpeed / 2, function () {
            wpe_nextStep(formID);
        });
    });
}

function wpe_initListeners(formID) {
    var form = wpe_getForm(formID);
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide div.selectable .img,  #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide div.selectable .icon_select').click(function () {
        wpe_itemClick(jQuery(this).parent(), true, formID);
    });
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel input[type=checkbox][data-price]').change(function () {
        
        if(jQuery(this).is('[data-usedistance="true"]')){
            lfb_removeDistanceError(jQuery(this).attr('data-itemid'),formID);
        }
        wpe_updatePrice(formID);
    });
    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel input[type=checkbox][data-group]').change(function (e) {
        var clickedInput = jQuery(this);
        if (clickedInput.is(':checked')) {
            jQuery(this).closest('.genSlide').find('div.selectable.checked[data-group="' + clickedInput.data('group') + '"]').each(function () {
                wpe_itemClick(jQuery(this), false, formID);
            });
            jQuery(this).closest('.genSlide').find('input[type=checkbox][data-group="' + clickedInput.data('group') + '"]:checked').each(function () {
                if (!jQuery(this).is(clickedInput)) {
                    jQuery(this).trigger('click.auto');
                }
            });

            if (jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"]').is('[data-autoclick="1"]') && clickedInput.closest('.genSlide').find('[data-itemid]').not('[data-group="' + clickedInput.data('group') + '"]').length == 0) {
                var form = wpe_getForm(formID);
                wpe_nextStep(form.formID);
            }
        }

    });
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide select[data-itemid]').change(function () {
        var value = jQuery(this).val();
        var price = 0;
        jQuery(this).find('option').each(function () {
            if (jQuery(this).attr('value') == value) {
                price = jQuery(this).attr('data-price');
            }
        });
        jQuery(this).attr('data-price', price);
        wpe_updatePrice(formID);
    });
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide select[data-itemid]').trigger('change');

    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel input[type=checkbox][data-price]').change(function () {

    });
    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel .genSlide input.wpe_qtfield').change(function () {
        wpe_updatePrice(formID);
    });
    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel .genSlide').find('input[type=text][data-title],textarea[data-title],input[type=file][data-title],input[type=number][data-title]').change(function () {
        if (jQuery(this).val().length > 0) {
            jQuery(this).addClass('checked');
        } else {
            jQuery(this).removeClass('checked');
        }
    });
    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel .genSlide div.selectable .icon_quantity').click(function () {
        jQuery('.quantityBtns').not(jQuery(this).parent().find('.quantityBtns')).removeClass('open');
        jQuery('.quantityBtns').not(jQuery(this).parent().find('.quantityBtns')).fadeOut(250);

        if (!jQuery(this).parent().find('.quantityBtns').is('.open') && jQuery(this).parent().is('.checked')) {
            if (jQuery(this).parent().find('.quantityBtns .tooltip-inner').length > 0) {
                jQuery(this).parent().find('.quantityBtns .tooltip-inner').html(parseInt(jQuery(this).parent().find('.icon_quantity').html()));
            }
            jQuery(this).parent().find('.quantityBtns').addClass('open');
            jQuery(this).parent().find('.quantityBtns').fadeIn(250);
        } else {
            jQuery(this).parent().find('.quantityBtns').removeClass('open');
            jQuery(this).parent().find('.quantityBtns').fadeOut(250);
        }
    });

    jQuery('#wpe_orderMessageCheck').change(function () {
        if (jQuery(this).is(':checked')) {
            jQuery('#wpe_orderMessage').slideDown(250);
        } else {
            jQuery('#wpe_orderMessage').slideUp(250);
        }
    });
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wpe_btnOrder').click(function () {
        wpe_order(formID);
    });

    jQuery('#gform_wrapper_' + form.gravityFormID + ' form').submit(function (e) {
        var $this = jQuery(this);
        if (!jQuery(this).is('.submit')) {
            e.preventDefault();
            jQuery(this).addClass('submit');
            form.timer_gFormSubmit = setInterval(function () {
                wpe_check_gform_response(form.formID);
            }, 300);
            setTimeout(function () {
                $this.submit();
            }, 700);
        } else {
            jQuery(this).removeClass('submit');
        }
    });


}

function wpe_checkItems(formID) {
    var form = wpe_getForm(formID);
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel .genSlide div.selectable img[data-tint="true"]').each(function () {
        jQuery(this).css('opacity', 0);
        jQuery(this).show();
        var $canvas = jQuery('<canvas class="img"></canvas>');
        $canvas.css({
            width: jQuery(this).get(0).width,
            height: jQuery(this).get(0).height
        });
        jQuery(this).hide();
        jQuery(this).after($canvas);
        var ctx = $canvas.get(0).getContext('2d');
        var img = new Image();
        img.onload = function () {
            ctx.fillStyle = form.colorA;
            ctx.fillRect(0, 0, $canvas.get(0).width, $canvas.get(0).height);
            ctx.fill();
            ctx.globalCompositeOperation = 'destination-in';
            ctx.drawImage(img, 0, 0, $canvas.get(0).width, $canvas.get(0).height);
        };
        img.src = jQuery(this).attr('src');
    });
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel .genSlide div.selectable img[data-tint="false"]').each(function () {
        if (jQuery(this).is('[data-src]')) {
            jQuery(this).attr('src', jQuery(this).attr('data-src'));
        }
    });

    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel .genSlide div.selectable.checked , #estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel .genSlide  input[type=checkbox]:checked').hover(function () {
        jQuery(this).addClass('lfb_hover');
    }, function () {
        jQuery(this).removeClass('lfb_hover');
    });
}
function lfb_getDistanceCalc(distanceCode,formID,itemID, depart,arrival,distanceType){
    var rep = 0;
    var distanceMode = google.maps.UnitSystem.METRIC;
    
    lfb_gmapService = new google.maps.DistanceMatrixService();
     lfb_gmapService.getDistanceMatrix({
            origins: [depart], 
            destinations: [arrival], 
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: false,
          }, function(response, status){
              var error = false;
              if(status==google.maps.DistanceMatrixStatus.OK)
              {
                  var distance = 0;
                  if(response.rows[0].elements[0].distance){
                    distance = response.rows[0].elements[0].distance.value;
                    distance = distance/1000;
                    if(distanceType == 'miles' ) {
                        distance = distance*0.62;
                    }
                   } else {
                       error = true;
                   }
                   var $item =   jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] [data-itemid="' + itemID + '"]');
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] [data-itemid="' + itemID + '"]').attr('data-distance',distance);
                    var form = wpe_getForm(formID);
                    wpe_updateSummary(formID);
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table tr[data-itemstep="'+itemID+'"] td:eq(2)').html(distance);
                    wpe_updatePrice(formID);
                     lfb_removeDistanceError(itemID,formID);
              }   else {
                  error = true;
              }    
              if(error){
                  lfb_showDistanceError(itemID,formID);
              }
        });
    
    return rep;
}
function lfb_executeCalculation(calculation, formID,targetID) {
    calculation = calculation.replace(/\\/g, '');
    var form = wpe_getForm(formID);
    var price = 0;
    var i = 0;
    var elementsToReplace = new Array();
    var $target = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + targetID + '"]');
    while ((i = calculation.indexOf('item-', i + 1)) != -1) {
        var itemID = calculation.substr(i + 5, calculation.indexOf('_', i) - (i + 5));
        var action = calculation.substr(calculation.indexOf('_', i) + 1, (calculation.indexOf(']', i) - 1) - (calculation.indexOf('_', i)));
        var value = 0;
        var $item = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]');

        if (action == 'isChecked') {
            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').is('.checked') ||
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').is(':checked')) {
                value = '1==1';
            } else {
                value = '1==0'
            }
        }
        if (action == 'isUnchecked') {
            if (!jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').is('.checked') &&
                    !jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').is(':checked')) {
                value = '1==1';
            } else {
                value = '1==0'
            }
        }
        if (action == 'isFilled') {
            if ($item.is('.lfb_dropzone')) {
                if ($item.find('.dz-preview[data-file].dz-success.dz-complete').length > 0) {
                    value = '1==1';
                } else {
                    value = '1==0';
                }
            } else {
                if ($item.val().length > 0) {
                    value = '1==1';
                } else {
                    value = '1==0';
                }
            }
        }
        if (action == 'price') {
            value = 0;
            if (itemID == 'total') {
                value = form.price;
            } else {
                if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').is('.checked') ||
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').is(':checked') ||
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').is('select') ||
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').is('[data-type="slider"]')) {
                    value = parseFloat(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').data('resprice'));
                    if (isNaN(value)) {
                        value = 0;
                    }
                }
            }
        }
        if (action == 'quantity') {
            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').is('.checked') ||
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').is(':checked')) {
                if ($item.find('.icon_quantity').length > 0) {
                    value = parseFloat($item.find('.icon_quantity').html());
                } else {
                    value = $item.find('.wpe_qtfield').val();
                }
                if (isNaN(value)) {
                    value = 0;
                }
            } else if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').is('[data-type="slider"]')) {
                value = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').slider('value');
            }
        }
        if (action == 'value') {
            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').is('select')) {
                value = '"' + jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').val() + '"';
            } else {
                value = parseFloat(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]').val());
                if (isNaN(value)) {
                    value = 0;
                }
            }
        }
        if (action == 'date') {
            if ($item.is('.lfb_datepicker') && $item.datepicker("getDate") != null) {
                value = "'"+jQuery.datepicker.formatDate("yy-mm-dd", $item.datepicker("getDate"))+"'";
            } else {
                value = "null";
            }
        }
        elementsToReplace.push({
            oldValue: calculation.substr(i - 1, (calculation.indexOf(']', i) + 1) - (i - 1)),
            newValue: value
        });
    }
    if(calculation.indexOf('distance_')>-1){
        $target.attr('data-usedistance','true');
        while ((i = calculation.indexOf('distance_', i + 1)) != -1) {
            
        var distanceType = 'km';
     
         var departAdPosEnd = calculation.indexOf('-', i+9)+1;
         var departAdress = calculation.substr(i + 9, calculation.indexOf('-', i) - (i + 9));
         
         var departCityPosEnd = calculation.indexOf('-', departAdPosEnd)+1;
         var departCity = calculation.substr(departAdPosEnd, calculation.indexOf('-', departAdPosEnd) - (departAdPosEnd));
                 
         var departZipPosEnd = calculation.indexOf('-', departCityPosEnd)+1;
         var departZip = calculation.substr(departCityPosEnd, calculation.indexOf('-', departCityPosEnd) - (departCityPosEnd));
                   
         var departCountryPosEnd = calculation.indexOf('_', departZipPosEnd)+1;
         var departCountry= calculation.substr(departZipPosEnd, calculation.indexOf('_', departZipPosEnd) - (departZipPosEnd));         
         
         var arrivalAdPosEnd = calculation.indexOf('-', departCountryPosEnd)+1;
         var arrivalAdress = calculation.substr(departCountryPosEnd, calculation.indexOf('-', departCountryPosEnd) - (departCountryPosEnd));
         
         var arrivalCityPosEnd = calculation.indexOf('-', arrivalAdPosEnd)+1;
         var arrivalCity = calculation.substr(arrivalAdPosEnd, calculation.indexOf('-', arrivalAdPosEnd) - (arrivalAdPosEnd));
         
         var arrivalZipPosEnd = calculation.indexOf('-', arrivalCityPosEnd)+1;
         var arrivalZip = calculation.substr(arrivalCityPosEnd, calculation.indexOf('-', arrivalCityPosEnd) - (arrivalCityPosEnd));
         
         var arrivalCountryPosEnd = calculation.indexOf('_', arrivalZipPosEnd)+1;
         var arrivalCountry = calculation.substr(arrivalZipPosEnd, calculation.indexOf('_', arrivalZipPosEnd) - (arrivalZipPosEnd));
         
         distanceType = calculation.substr(arrivalCountryPosEnd, calculation.indexOf(']', arrivalCountryPosEnd) - (arrivalCountryPosEnd));
         
         
         if(departAdress != ""){      
            if(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + departAdress + '"]').length>0){
                var $item = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + departAdress + '"]');
                departAdress = $item.val();
            } else {
                departAdress = 0;                
            }
         }
         if(departCity != ""){      
            if(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + departCity + '"]').length>0){
                var $item = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + departCity + '"]');
                departCity = $item.val();
            } else {
                departCity = 0;                
            }
         }
         if(departZip != ""){      
            if(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + departZip + '"]').length>0){
                var $item = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + departZip + '"]');
                departZip = $item.val();
            } else {
                departZip = 0;                
            }
         }
         if(departCountry != ""){      
            if(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + departCountry + '"]').length>0){
                var $item = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + departCountry + '"]');
                departCountry = $item.val();
            } else {
                departCountry = 0;                
            }
         }if(arrivalAdress != ""){      
            if(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + arrivalAdress + '"]').length>0){
                var $item = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + arrivalAdress + '"]');
                arrivalAdress = $item.val();
            } else {
                arrivalAdress = 0;                
            }
         }
         if(arrivalCity != ""){      
            if(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + arrivalCity + '"]').length>0){
                var $item = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + arrivalCity + '"]');
                arrivalCity = $item.val();
            } else {
                arrivalCity = 0;                
            }
         }
         if(arrivalZip != ""){      
            if(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + arrivalZip + '"]').length>0){
                var $item = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + arrivalZip + '"]');
                arrivalZip = $item.val();
            } else {
                arrivalZip = 0;                
            }
         }
         if(arrivalCountry != ""){      
            if(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + arrivalCountry + '"]').length>0){
                var $item = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + arrivalCountry + '"]');
                arrivalCountry = $item.val();
            } else {
                arrivalCountry = 0;                
            }
         }     
         if($target.closest('.genSlide').find('.lfb_distanceError').length>0){
                lfb_removeDistanceError(targetID,formID);
         }
         
         //lfb_showDistanceError(formID,targetID);
         var distanceCode = calculation.substr(i - 1, (calculation.indexOf(']', i) + 1) - (i - 1));
         var distance = 0;
         if($target.attr('data-distance') != ""){
             distance = parseFloat($target.attr('data-distance'));
         }
         if(departAdress == "" && departCity == "" && departCountry == "" && arrivalAdress == "" && arrivalCity == "" && arrivalCountry == "" && departZip == "" && arrivalZip == ""){             
             lfb_showDistanceError(targetID,formID);
          } else {
             if(form.gmap_key == ""){     
                 lfb_showDistanceError(targetID,formID);
                 console.log("invalid gmap api key");
             } else {
                 var depart = departAdress+' '+departZip+' '+departCity+' '+departCountry;
                 var arrival = arrivalAdress+' '+arrivalZip+' '+arrivalCity+' '+arrivalCountry;
                 if($target.attr('data-departure') != depart || arrival != $target.attr('data-arrival')){
                 $target.attr('data-departure',depart);
                 $target.attr('data-arrival',arrival);
                 lfb_getDistanceCalc(distanceCode,formID,targetID,depart,arrival,distanceType);      
              }
             }
         }
        elementsToReplace.push({
            oldValue: calculation.substr(i - 1, (calculation.indexOf(']', i) + 1) - (i - 1)),
            newValue: distance
        });      
            
        }
    } 
    jQuery.each(elementsToReplace, function () {
        calculation = calculation.replace(this.oldValue, this.newValue);
    });
    calculation = calculation.replace(/\[total\]/g, form.price);
    calculation = calculation.replace(/\[total_quantity\]/g, wpe_getTotalQuantities(formID));

    i = 0;
    while ((i = calculation.indexOf('{', i + 1)) != -1) {
        var charsToEnd = calculation.substr(i + 1, (calculation.indexOf('}', i + 1) - (i + 1)));
        if (/\S/.test(charsToEnd)) {
            calculation = calculation.substr(0, i + 1) + ' price =' + calculation.substr(i + 1, calculation.length);
            i += 8;
        }
    }
    if (calculation.indexOf('if') < 0) {
        calculation = 'price = ' + calculation;
    } else {
        var charsToStart = calculation.substr(0, calculation.indexOf('if'));
        if (/\S/.test(charsToStart)) {
            calculation = 'price = ' + calculation;
        }
    }
    try {
        eval(calculation);
    } catch (e) {
        console.log('wrong calculation');
    }

    return parseFloat(price);
}

function lfb_removeDistanceError(itemID,formID){
    var $target = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]');
    
    if($target.closest('.genSlide').find('[data-itemid][data-usedistance="true"]:not([data-distance]):not([type="checkbox"]).checked,[data-itemid][data-usedistance="true"]:not([data-distance]):checked,[data-itemid][data-usedistance="true"]:not([data-distance])[data-type="slider"]').length==0 && 
       $target.closest('.genSlide').find('[data-itemid][data-usedistance="true"][data-distance="0"]:not([type="checkbox"]).checked,[data-itemid][data-usedistance="true"][data-distance="0"]:checked,[data-itemid][data-usedistance="true"][data-distance="0"][data-type="slider"]').length==0 ){
        $target.closest('.genSlide').find('.btn-next').fadeIn();
        $target.closest('.genSlide').find('.btn-next').removeClass('lfb_disabledBtn');
        var errorMsg = $target.closest('.genSlide').find('.lfb_distanceError');
        errorMsg.fadeOut();
        setTimeout(function(){
            errorMsg.remove();
        },300);
    }
}
function lfb_showDistanceError(itemID,formID){
    var form = wpe_getForm(formID);
    var $target = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide [data-itemid="' + itemID + '"]');
    $target.closest('.genSlide').find('.btn-next').addClass('lfb_disabledBtn');
    $target.closest('.genSlide').find('.btn-next').hide();
    if($target.closest('.genSlide').find('.lfb_distanceError').length==0){
        $target.closest('.genSlide').find('.lfb_btnNextContainer').before('<div class="lfb_distanceError alert alert-danger"><p>'+form.txtDistanceError+'</p></div>');
        
    }
    var stepID = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] [data-itemid="' + itemID + '"]').closest('.genSlide').attr('data-stepid');
    if(form.step == 'final' && !form.backFomFinal){  
        form.backFomFinal = true;
        wpe_changeStep(stepID,formID);       
   }
    
    
}
function wpe_updateLabelItem($item, formID) {
    form = wpe_getForm(formID);
    if (!$item.is('.dropzone')) {
        $item.closest('.itemBloc').find("label").each(function () {
            if (jQuery(this).html().indexOf(":") > -1 && jQuery(this).closest('.switch').length == 0) {
                jQuery(this).html($item.attr('data-original-title'));
            }
        });
    }
}

function wpe_updatePrice(formID) {
    var hasSinglePrice = false;
    lfb_updateShowSteps(formID);
    lfb_updateShowItems(formID);
    form = wpe_getForm(formID);
    form.lastPrice = form.price;
    form.price = form.initialPrice;
    form.priceSingle = 0;
    wpe_updatePlannedSteps(formID);
    wpe_updateStep(formID);
    var lastAndCurrentSteps = lfb_lastSteps.slice();
    var pricePreviousStep = 0;
    var singlePricePreviousStep = 0;

    if (form.step != 'final' && jQuery.inArray(parseInt(form.step), lastAndCurrentSteps) == -1) {
        lastAndCurrentSteps.push(parseInt(form.step));
    }
    jQuery.each(lastAndCurrentSteps, function () {
        var step = this;
        if (parseInt(step) != 0 && !jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel .genSlide[data-title][data-stepid="' + step + '"]').is('.lfb_disabled')) {
            $panel = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel .genSlide[data-title][data-stepid="' + step + '"]');
            

            $panel.find('div.selectable.checked, input[type=checkbox]:checked,select[data-itemid][data-price],div[data-type="slider"]').each(function () {
                if (!jQuery(this).is('.lfb_disabled') && jQuery(this).closest('.itemBloc.lfb_disabled').length == 0) {
                    if (jQuery.inArray(parseInt($panel.attr('data-stepid')), lastAndCurrentSteps) >= 0 && !jQuery(this).is('.lfb_disabled')) {
                        if (jQuery(this).is('[data-singleprice="true"]')) {
                            hasSinglePrice = true;
                        }
                        jQuery(this).data('price', jQuery(this).attr('data-price'));
                        if (jQuery(this).is('[data-usecalculation]')) {
                            jQuery(this).data('price', lfb_executeCalculation(jQuery(this).attr('data-calculation'), formID,jQuery(this).attr('data-itemid')));
                        }
                        if ((jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel').data('savecart') == "0") || (jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel').data('savecart') == "1" && jQuery(this).data('prodid') > 0)) {

                            if (jQuery(this).find('.icon_quantity').length > 0 || jQuery(this).find('.wpe_qtfield').length > 0 || jQuery(this).is('[data-type="slider"]')) {
                                var quantityA = '';
                                var min = 0;
                                var max = 999999999;
                                if (jQuery(this).find('.icon_quantity').length > 0) {
                                    quantityA = jQuery(this).find('.icon_quantity').html();
                                       min =  jQuery(this).find('.quantityBtns').attr('data-min');
                                       max =  jQuery(this).find('.quantityBtns').attr('data-max');
                                    
                                    
                                } else if (jQuery(this).find('.wpe_qtfield').length > 0) {
                                    quantityA = jQuery(this).find('.wpe_qtfield').val();
                                       min =  jQuery(this).find('.wpe_qtfield').attr('min');
                                       max =  jQuery(this).find('.wpe_qtfield').attr('max');
                                } else if (jQuery(this).is('[data-type="slider"]')) {
                                    quantityA = parseInt(jQuery(this).slider('value'));
                                    if (!isNaN(parseInt(jQuery(this).find('.tooltip-inner').html()))) {
                                        quantityA = parseInt(jQuery(this).find('.tooltip-inner').html());
                                    }                                    
                                    min =  jQuery(this).attr('data-min');
                                    max =  jQuery(this).attr('data-max');
                                }
                                if(jQuery(this).is('[data-distanceqt]')){
                                   // if(jQuery(this).is('[data-distance]')){
                                        lfb_executeCalculation(jQuery(this).attr('data-distanceqt'),formID,jQuery(this).attr('data-itemid'));
                                        if(jQuery(this).is('[data-distance]')) {
                                            quantityA = parseFloat(jQuery(this).attr('data-distance'));
                                        }
                                       // quantityA = parseFloat(jQuery(this).attr('data-distance'));      
                                        if(quantityA<min){
                                            quantityA = min;
                                        } else if(quantityA>max){
                                            quantityA = max;
                                        }
                                          if (jQuery(this).find('.wpe_qtfield').length > 0) {
                                              jQuery(this).find('.wpe_qtfield').val(quantityA);
                                          } else if  (jQuery(this).find('.wpe_sliderQt').length > 0) {
                                              jQuery(this).find('.wpe_sliderQt').slider('value',quantityA);
                                              jQuery(this).find('.icon_quantity').html(quantityA);
                                          } else if  (jQuery(this).find('.quantityBtns').length > 0) {
                                              jQuery(this).find('.icon_quantity').html(quantityA);
                                          }else if (jQuery(this).is('[data-type="slider"]')) {
                                              jQuery(this).slider('value',quantityA);
                                          }
                                   // }
                                }
                                jQuery(this).attr('data-resqt', quantityA);
                                if (jQuery(this).data('price')) {
                                    if (jQuery(this).data('operation') == '-') {
                                        jQuery(this).data('resprice', 0 - parseFloat(jQuery(this).data('price')) * parseFloat(quantityA));
                                        if (jQuery(this).is('[data-singleprice="true"]')) {
                                            form.priceSingle -= parseFloat(jQuery(this).data('price')) * parseFloat(quantityA);
                                        } else {
                                            form.price -= parseFloat(jQuery(this).data('price')) * parseFloat(quantityA);
                                        }
                                    } else if (jQuery(this).data('operation') == 'x') {
                                        for (var i = 0; i < parseFloat(quantityA); i++) {
                                            if (i == 0) {
                                                form.price = form.price;
                                                jQuery(this).data('resprice', form.price);
                                            } else {
                                                if (jQuery(this).is('[data-singleprice="true"]')) {
                                                    form.priceSingle += ((singlePricePreviousStep * parseFloat(jQuery(this).data('price'))) / 100);
                                                    jQuery(this).data('resprice', (singlePricePreviousStep * parseFloat(jQuery(this).data('price'))) / 100);
                                                } else {
                                                    form.price += ((pricePreviousStep * parseFloat(jQuery(this).data('price'))) / 100);
                                                    jQuery(this).data('resprice', (form.price * parseFloat(jQuery(this).data('price'))) / 100);
                                                }
                                            }
                                        }
                                    } else if (jQuery(this).data('operation') == '/') {
                                        for (var i = 0; i < parseFloat(quantityA); i++) {
                                            jQuery(this).data('resprice', 0 - (form.price * parseFloat(jQuery(this).data('price'))) / 100);
                                            if (jQuery(this).is('[data-singleprice="true"]')) {
                                                form.priceSingle = form.price - (form.price * parseFloat(jQuery(this).data('price'))) / 100;
                                            } else {
                                                form.price = form.price - (form.price * parseFloat(jQuery(this).data('price'))) / 100;
                                            }
                                        }
                                    } else {
                                        var reducIndex = -2;
                                        if (jQuery(this).data('reduc') && jQuery(this).data('reducqt').length > 0) {
                                            var self = this;
                                            var reducsTab = jQuery(this).data('reducqt');
                                            reducsTab = reducsTab.split("*");
                                            var valuesTab = new Array();
                                            var minQtReduc = 0;
                                            jQuery.each(reducsTab, function (i) {
                                                var reduc = reducsTab[i].split('|');
                                                valuesTab.push(reduc[1]);
                                                if (parseFloat(reduc[0]) <= parseFloat(quantityA)) {
                                                    reducIndex = i;
                                                }
                                                if (parseFloat(reduc[0]) < minQtReduc || minQtReduc == 0) {
                                                    minQtReduc = parseFloat(reduc[0]);
                                                }

                                            });
                                        }
                                        if (reducIndex >= 0) {

                                            var calculatedPrice = parseFloat(valuesTab[reducIndex]) * parseFloat(quantityA);
                                            if (jQuery(this).attr('data-price') == "0") {
                                                calculatedPrice = parseFloat(valuesTab[reducIndex]) * (parseFloat(quantityA) - minQtReduc);
                                            }
                                            jQuery(this).data('resprice', calculatedPrice);
                                            if (jQuery(this).is('[data-singleprice="true"]')) {
                                                form.priceSingle += parseFloat(calculatedPrice);
                                            } else {
                                                form.price += parseFloat(calculatedPrice);
                                            }

                                            if (form.currencyPosition == 'left') {
                                                if (jQuery(this).is('[data-showprice="1"]')) {
                                                    if (jQuery(this).data('operation') == "+") {
                                                        jQuery(this).attr('title', jQuery(this).data('originaltitle') + ' : ' + form.currency + (wpe_formatPrice(valuesTab[reducIndex], formID)));
                                                        jQuery(this).attr('data-original-title', jQuery(this).data('originaltitle') + ' : ' + form.currency + (wpe_formatPrice(valuesTab[reducIndex], formID)));
                                                        if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                            if (!jQuery(this).is('[data-type="slider"]')) {
                                                                jQuery(this).tooltip('fixTitle');
                                                            }
                                                        }
                                                        wpe_updateLabelItem(jQuery(this), formID);
                                                    } else if (jQuery(this).data('operation') == "-") {
                                                        jQuery(this).attr('title', jQuery(this).data('originaltitle') + ' : -' + form.currency + (wpe_formatPrice(valuesTab[reducIndex], formID)));
                                                        if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                            if (!jQuery(this).is('[data-type="slider"]')) {
                                                                jQuery(this).tooltip('fixTitle');
                                                            }
                                                        }
                                                        jQuery(this).attr('data-original-title', jQuery(this).data('originaltitle') + ' : -' + form.currency + (wpe_formatPrice(valuesTab[reducIndex], formID)));
                                                        if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                            if (!jQuery(this).is('[data-type="slider"]')) {
                                                                jQuery(this).tooltip('fixTitle');
                                                            }
                                                        }
                                                        wpe_updateLabelItem(jQuery(this), formID);
                                                    } else if (jQuery(this).data('operation') == "x") {
                                                        jQuery(this).attr('title', jQuery(this).data('originaltitle') + ' : +' + (wpe_formatPrice(valuesTab[reducIndex], formID)) + '%');
                                                        if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                            if (!jQuery(this).is('[data-type="slider"]')) {
                                                                jQuery(this).tooltip('fixTitle');
                                                            }
                                                        }
                                                        jQuery(this).attr('data-original-title', jQuery(this).data('originaltitle') + ' : +' + (wpe_formatPrice(valuesTab[reducIndex], formID)) + '%');
                                                        if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                            if (!jQuery(this).is('[data-type="slider"]')) {
                                                                jQuery(this).tooltip('fixTitle');
                                                            }
                                                        }

                                                        wpe_updateLabelItem(jQuery(this), formID);
                                                    } else {
                                                        jQuery(this).attr('title', jQuery(this).data('originaltitle') + ' : -' + (wpe_formatPrice(valuesTab[reducIndex], formID)) + '%');
                                                        if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                            if (!jQuery(this).is('[data-type="slider"]')) {
                                                                jQuery(this).tooltip('fixTitle');
                                                            }
                                                        }
                                                        jQuery(this).attr('data-original-title', jQuery(this).data('originaltitle') + ' : -' + (wpe_formatPrice(valuesTab[reducIndex], formID)) + '%');
                                                        if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                            if (!jQuery(this).is('[data-type="slider"]')) {
                                                                jQuery(this).tooltip('fixTitle');
                                                            }
                                                        }
                                                        wpe_updateLabelItem(jQuery(this), formID);
                                                    }
                                                } else {
                                                    jQuery(this).attr('data-original-title', jQuery(this).data('originaltitle'));
                                                }
                                                if (jQuery(this).find('.quantityBtns').is('.open') || (jQuery(this).is('.checked') && jQuery(this).find('.wpe_itemQtField').length > 0)) {
                                                    if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                        jQuery(this).tooltip('show');
                                                    }
                                                }
                                            } else {
                                                if (jQuery(this).is('[data-showprice="1"]')) {
                                                    if (jQuery(this).attr('data-operation') == "+") {
                                                        jQuery(this).attr('title', jQuery(this).data('originaltitle') + ' : ' + (wpe_formatPrice(valuesTab[reducIndex], formID)) + form.currency);
                                                        jQuery(this).attr('data-original-title', jQuery(this).data('originaltitle') + ' : ' + (wpe_formatPrice(valuesTab[reducIndex], formID)) + form.currency);
                                                        if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                            if (!jQuery(this).is('[data-type="slider"]')) {
                                                                jQuery(this).tooltip('fixTitle');
                                                            }
                                                        }
                                                        wpe_updateLabelItem(jQuery(this), formID);
                                                    } else if (jQuery(this).attr('data-operation') == "-") {
                                                        jQuery(this).attr('title', jQuery(this).data('originaltitle') + ' : -' + +(wpe_formatPrice(valuesTab[reducIndex], formID)));
                                                        jQuery(this).attr('data-original-title', jQuery(this).data('originaltitle') + ' : -' + (wpe_formatPrice(valuesTab[reducIndex], formID)) + form.currency);
                                                        if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                            if (!jQuery(this).is('[data-type="slider"]')) {
                                                                jQuery(this).tooltip('fixTitle');
                                                            }
                                                        }
                                                        wpe_updateLabelItem(jQuery(this), formID);
                                                    } else if (jQuery(this).attr('data-operation') == "x") {
                                                        jQuery(this).attr('title', jQuery(this).data('originaltitle') + ' : +' + (wpe_formatPrice(valuesTab[reducIndex], formID)) + '%');
                                                        if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                            if (!jQuery(this).is('[data-type="slider"]')) {
                                                                jQuery(this).tooltip('fixTitle');
                                                            }
                                                        }
                                                        jQuery(this).attr('data-original-title', jQuery(this).data('originaltitle') + ' : +' + (wpe_formatPrice(valuesTab[reducIndex], formID)) + '%');
                                                        if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                            if (!jQuery(this).is('[data-type="slider"]')) {
                                                                jQuery(this).tooltip('fixTitle');
                                                            }
                                                        }
                                                        wpe_updateLabelItem(jQuery(this), formID);
                                                    } else {
                                                        jQuery(this).attr('title', jQuery(this).data('originaltitle') + ' : -' + (wpe_formatPrice(valuesTab[reducIndex], formID)) + '%');
                                                        if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                            if (!jQuery(this).is('[data-type="slider"]')) {
                                                                jQuery(this).tooltip('fixTitle');
                                                            }
                                                        }
                                                        jQuery(this).attr('data-original-title', jQuery(this).data('originaltitle') + ' : -' + (wpe_formatPrice(valuesTab[reducIndex], formID)) + '%');
                                                        if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                            if (!jQuery(this).is('[data-type="slider"]')) {
                                                                jQuery(this).tooltip('fixTitle');
                                                            }
                                                        }
                                                        wpe_updateLabelItem(jQuery(this), formID);

                                                    }
                                                } else {
                                                    jQuery(this).attr('data-original-title', jQuery(this).data('originaltitle'));
                                                }
                                                if (jQuery(this).find('.quantityBtns').is('.open') || (jQuery(this).is('.checked') && jQuery(this).find('.wpe_itemQtField').length > 0)) {
                                                    if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                        jQuery(this).tooltip('show');
                                                    }
                                                }
                                            }
                                        } else {
                                            jQuery(this).data('resprice', parseFloat(jQuery(this).data('price')) * parseFloat(quantityA));
                                            if (jQuery(this).is('[data-singleprice="true"]')) {
                                                form.priceSingle += parseFloat(jQuery(this).data('price')) * parseFloat(quantityA);
                                            } else {
                                                form.price += parseFloat(jQuery(this).data('price')) * parseFloat(quantityA);
                                            }

                                            wpe_updateItemTitleNoReduc(jQuery(this), form);
                                            if (jQuery(this).find('.quantityBtns').is('.open') || (jQuery(this).is('.checked') && jQuery(this).find('.wpe_itemQtField').length > 0)) {
                                                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                                                    jQuery(this).tooltip('show');
                                                }
                                            }

                                        }
                                    }
                                } else {
                                    jQuery(this).data('resprice', '0');
                                }

                            } else {
                                jQuery(this).data('resqt', '0');
                                if (jQuery(this).data('price')) {
                                    if (jQuery(this).data('operation') == '-') {
                                        jQuery(this).data('resprice', 0 - parseFloat(jQuery(this).data('price')));
                                        if (jQuery(this).is('[data-singleprice="true"]')) {
                                            form.priceSingle -= parseFloat(jQuery(this).data('price'));
                                        } else {
                                            form.price -= parseFloat(jQuery(this).data('price'));
                                        }
                                    } else if (jQuery(this).data('operation') == 'x') {
                                        if (jQuery(this).is('[data-singleprice="true"]')) {
                                            jQuery(this).data('resprice', (form.priceSingle * parseFloat(jQuery(this).data('price'))) / 100);
                                            form.priceSingle = form.priceSingle + (form.priceSingle * parseFloat(jQuery(this).data('price'))) / 100;
                                        } else {
                                            jQuery(this).data('resprice', (form.price * parseFloat(jQuery(this).data('price'))) / 100);
                                            form.price = form.price + (form.price * parseFloat(jQuery(this).data('price'))) / 100;
                                        }
                                    } else if (jQuery(this).attr('data-operation') == '/') {
                                        if (jQuery(this).is('[data-singleprice="true"]')) {
                                            jQuery(this).data('resprice', 0 - (form.priceSingle * parseFloat(jQuery(this).data('price'))) / 100);
                                            form.priceSingle = form.price - (form.priceSingle * parseFloat(jQuery(this).data('price'))) / 100;
                                        } else {
                                            jQuery(this).data('resprice', 0 - (form.price * parseFloat(jQuery(this).data('price'))) / 100);
                                            form.price = form.price - (form.price * parseFloat(jQuery(this).data('price'))) / 100;
                                        }
                                    } else {
                                        jQuery(this).data('resprice', jQuery(this).data('price'));
                                        if (jQuery(this).is('[data-singleprice="true"]')) {
                                            form.priceSingle += parseFloat(jQuery(this).data('price'));
                                        } else {
                                            form.price = parseFloat(form.price) + parseFloat(jQuery(this).data('price'));
                                        }


                                    }
                                    
                                    wpe_updateItemTitleNoReduc(jQuery(this), form);
                                } else {
                                    jQuery(this).data('resprice', '0');
                                }
                            }
                        }
                    }
                }

            });
        }
        pricePreviousStep = form.price;
        singlePricePreviousStep = form.priceSingle;
    });
    if (form.reduction > 0) {
        if (form.reductionType && form.reductionType == '%') {
            form.reductionResult = (form.price * form.reduction) / 100;
        } else {
            form.reductionResult = form.reduction;
        }
        form.reductionResult = parseFloat(form.reductionResult);
        if (form.priceSingle == 0) {
            form.price -= form.reductionResult;
        } else {
            form.priceSingle -= form.reductionResult;
        }

    }
    if (!form.price || form.price < 0) {
        form.price = 0;
    }
    if (!hasSinglePrice || !form.priceSingle || form.priceSingle < 0) {
        form.priceSingle = 0;
    }
    var pattern = /^\d+(\.\d{2})?$/;
    if (!pattern.test(form.price)) {
        form.price = parseFloat(form.price).toFixed(2);
    }
    try {
        if (!pattern.test(form.priceSingle)) {
            form.priceSingle = parseFloat(form.priceSingle).toFixed(2);
        }
    } catch (e) {
    }
    var formatedSinglePrice = form.currency + '' + wpe_formatPrice(parseFloat(form.priceSingle), formID);
    var formatedPrice = form.currency + '' + wpe_formatPrice(parseFloat(form.price), formID);
    if (form.currencyPosition != 'left') {
        formatedPrice = wpe_formatPrice(form.price, formID) + '' + form.currency;
        formatedSinglePrice = wpe_formatPrice(form.priceSingle, formID) + '' + form.currency;
    }
    if (form.showTotalBottom == 1) {
        if (hasSinglePrice && form.priceSingle > 0) {
            if (form.price < 0) {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom').removeClass('lfb_priceSingle');
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom>span.lfb_subPrice').remove();
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom>br').remove();

            } else {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom').addClass('lfb_priceSingle');
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom').each(function () {
                    if (jQuery(this).find('.lfb_subPrice').length == 0) {
                        jQuery(this).find('>span:eq(0)').after('<br/><span class="lfb_subPrice">+ ' + formatedPrice + '</span>');

                    }
                });
            }
        } else {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom').removeClass('lfb_priceSingle');
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom>span.lfb_subPrice').remove();
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom>br').remove();
        }


        if (hasSinglePrice && form.priceSingle > 0) {
            if (form.price <= 0) {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom> span:first-child').html(formatedSinglePrice);
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom> span.lfb_subPrice').hide();
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom> span:eq(2)').hide();
            } else {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom> span.lfb_subPrice').show();
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom> span:eq(2)').show();
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom> span:first-child').html(formatedSinglePrice);
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom> span.lfb_subPrice').html('+ ' + formatedPrice);
            }

        } else {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_totalBottom> span:first-child').html(formatedPrice);

        }
    }
    if (form.showSteps == 0) {
        if (hasSinglePrice && form.priceSingle > 0) {
            if (form.price < 0) {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price').removeClass('lfb_priceSingle');
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price>span.lfb_subPrice').remove();

            } else {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price').addClass('lfb_priceSingle');
                if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span.lfb_subPrice').length == 0) {
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price>span:eq(0)').after('<span class="lfb_subPrice">+ ' + formatedPrice + '</span>');

                }
            }
        } else {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price').removeClass('lfb_priceSingle');
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price>span.lfb_subPrice').remove();
        }


        if (hasSinglePrice && form.priceSingle > 0) {
            if (form.price <= 0) {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span:first-child').css('top', '6px');
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span:first-child').html(formatedSinglePrice);
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span.lfb_subPrice').hide();
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span:eq(2)').hide();
            } else {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span:first-child').css('top', '-5px');
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span.lfb_subPrice').show();
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span:eq(2)').show();
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span:first-child').html(formatedSinglePrice);
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span.lfb_subPrice').html('+ ' + formatedPrice);
            }

        } else {
            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').is('[data-isSubs="true"]')) {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span:first-child').css('position', 'relative').css('top', '6px');
            } else {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span:first-child').css('position', 'relative').css('top', '0px');
            }
            if (form.price > 0) {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span:first-child').css('position', 'relative').css('top', '0px');
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span:eq(1)').show();
            } else {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span:eq(1)').hide();
            }
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span:first-child').html(formatedPrice);

        }
        if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span:first-child').length > 0) {
            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price> span:first-child').html().length > 8) {
                if (parseInt(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price').css('font-size')) >= 16) {
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price').css('font-size', '16px');
                }
            } else {
                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price').css('font-size', '18px');
            }
            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price > span:first-child').html().length > 9) {
                if (parseInt(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price').css('font-size')) >= 14) {
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price').css('font-size', '14px');
                }
            }
            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price > span:first-child').html().length > 10) {
                if (parseInt(jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price').css('font-size')) >= 11) {
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price').css('font-size', '11px');
                }
            }
        }
        var percent = (form.price * 100) / form.priceMax;

        if (form.showInitialPrice == 1) {
            percent = ((form.price - parseFloat(form.initialPrice)) * 100) / form.priceMax;
        }
        if (percent > 100) {
            percent = 100;
        }
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar').css('width', percent + '%');
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .genPrice .progress .progress-bar-price').animate({
            left: percent + '%'
        }, 70);
    }

    var summaryPrice = form.currency + '' + wpe_formatPrice(parseFloat(form.price).toFixed(2), formID);
    var summaryPriceSingle = form.currency + '' + wpe_formatPrice(parseFloat(form.priceSingle).toFixed(2), formID);
    if (form.currencyPosition != 'left') {
        summaryPrice = wpe_formatPrice(parseFloat(form.price).toFixed(2), formID) + '' + form.currency;
        summaryPriceSingle = wpe_formatPrice(parseFloat(form.priceSingle).toFixed(2), formID) + '' + form.currency;
    }

    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table #lfb_summaryTotal>span:eq(0)').html(summaryPrice);

    if (hasSinglePrice && form.priceSingle > 0) {
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalPrice span:eq(0)').html(formatedSinglePrice);
        if (form.price <= 0) {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalPrice span:eq(1)').css('display', 'none');
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalPrice span:eq(2)').css('display', 'none');
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table #lfb_summaryTotal>span:eq(0)').html('<strong>' + summaryPriceSingle + '</strong>');

        } else {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalPrice span:eq(2)').css('display', 'inline-block');
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalPrice span:eq(1)').html('+' + formatedPrice + form.subscriptionText);
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalPrice span:eq(1)').css('display', 'block');
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_summary table #lfb_summaryTotal>span:eq(0)').html('<strong>' + summaryPriceSingle + '</strong> <br/>+' + summaryPrice);

        }
    } else {
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalPrice span:eq(2)').css('display', 'inline-block');
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalPrice span:eq(1)').css('display', 'inline-block');
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalPrice span:eq(0)').html(formatedPrice);
    }



    wpe_updateStep(formID);
}

function wpe_updateItemTitleNoReduc($item, form) {
    var formID = form.formID;
    if (form.currencyPosition == 'left') {
        if ($item.is('[data-showprice="1"]')) {
            if ($item.data('operation') == "+") {
                $item.attr('title', $item.data('originaltitle') + ' : ' + form.currency + (wpe_formatPrice($item.data('price'), formID)));
                $item.attr('data-original-title', $item.data('originaltitle') + ' : ' + form.currency + (wpe_formatPrice($item.data('price'), formID)));
                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                    if (!$item.is('[data-type="slider"]')) {
                        $item.tooltip('fixTitle');
                    }
                }
                wpe_updateLabelItem($item, formID);
            } else if ($item.data('operation') == "-") {
                $item.attr('title', $item.data('originaltitle') + ' : -' + form.currency + (wpe_formatPrice($item.data('price'), formID)));
                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                    if (!$item.is('[data-type="slider"]')) {
                        $item.tooltip('fixTitle');
                    }
                }
                $item.attr('data-original-title', $item.data('originaltitle') + ' : -' + form.currency + (wpe_formatPrice($item.data('price'), formID)));
                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                    if (!$item.is('[data-type="slider"]')) {
                        $item.tooltip('fixTitle');
                    }
                }
                wpe_updateLabelItem($item, formID);
            } else if ($item.data('operation') == "x") {
                $item.attr('title', $item.data('originaltitle') + ' : +' + (wpe_formatPrice($item.data('price'), formID)) + '%');
                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                    if (!$item.is('[data-type="slider"]')) {
                        $item.tooltip('fixTitle');
                    }
                }
                $item.attr('data-original-title', $item.data('originaltitle') + ' : +' + (wpe_formatPrice($item.data('price'), formID)) + '%');
                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                    if (!$item.is('[data-type="slider"]')) {
                        $item.tooltip('fixTitle');
                    }
                }
                wpe_updateLabelItem($item, formID);
            } else {
                $item.attr('title', $item.data('originaltitle') + ' : -' + (wpe_formatPrice($item.data('price'), formID)) + '%');
                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                    if (!$item.is('[data-type="slider"]')) {
                        $item.tooltip('fixTitle');
                    }
                }
                $item.attr('data-original-title', $item.data('originaltitle') + ' : -' + (wpe_formatPrice($item.data('price'), formID)) + '%');
                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                    if (!$item.is('[data-type="slider"]')) {
                        $item.tooltip('fixTitle');
                    }
                }
                wpe_updateLabelItem($item, formID);
            }
        } else {
            $item.attr('data-original-title', $item.data('originaltitle'));
        }
    } else {
        if ($item.is('[data-showprice="1"]')) {
            if ($item.attr('data-operation') == "+") {
                $item.attr('title', $item.data('originaltitle') + ' : ' + (wpe_formatPrice($item.data('price'), formID)) + form.currency);
                $item.attr('data-original-title', $item.data('originaltitle') + ' : ' + (wpe_formatPrice($item.data('price'), formID)) + form.currency);
                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                    if (!$item.is('[data-type="slider"]')) {
                        $item.tooltip('fixTitle');
                    }
                }
                wpe_updateLabelItem($item, formID);
            } else if ($item.attr('data-operation') == "-") {
                $item.attr('title', $item.data('originaltitle') + ' : -' + +(wpe_formatPrice($item.data('price'), formID)));
                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                    if (!$item.is('[data-type="slider"]')) {
                        $item.tooltip('fixTitle');
                    }
                }
                $item.attr('data-original-title', $item.data('originaltitle') + ' : -' + (wpe_formatPrice($item.data('price'), formID)) + form.currency);
                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                    if (!$item.is('[data-type="slider"]')) {
                        $item.tooltip('fixTitle');
                    }
                }
                wpe_updateLabelItem($item, formID);
            } else if ($item.attr('data-operation') == "x") {
                $item.attr('title', $item.data('originaltitle') + ' : +' + (wpe_formatPrice($item.data('price'), formID)) + '%');
                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                    if (!$item.is('[data-type="slider"]')) {
                        $item.tooltip('fixTitle');
                    }
                }
                $item.attr('data-original-title', $item.data('originaltitle') + ' : +' + (wpe_formatPrice($item.data('price'), formID)) + '%');
                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                    if (!$item.is('[data-type="slider"]')) {
                        $item.tooltip('fixTitle');
                    }
                }
                wpe_updateLabelItem($item, formID);
            } else {
                $item.attr('title', $item.data('originaltitle') + ' : -' + (wpe_formatPrice($item.data('price'), formID)) + '%');
                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                    if (!$item.is('[data-type="slider"]')) {
                        $item.tooltip('fixTitle');
                    }
                }
                $item.attr('data-original-title', $item.data('originaltitle') + ' : -' + (wpe_formatPrice($item.data('price'), formID)) + '%');
                if (form.disableTipMobile == 0 || !wpe_is_touch_device()) {
                    if (!$item.is('[data-type="slider"]')) {
                        $item.tooltip('fixTitle');
                    }
                }
                wpe_updateLabelItem($item, formID);
            }
        } else {
            $item.attr('data-original-title', $item.data('originaltitle'));
        }
    }
}

function wpe_isDecimal(n) {
    if (n == "")
        return false;

    var strCheck = "0123456789";
    var i;

    for (i in n) {
        if (strCheck.indexOf(n[i]) == -1)
            return false;
    }
    return true;
}


function wpe_changeContentSlide(dir, formID) {
    var index = jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('.genContent').find('.genContentSlide.active').index();
    if (dir == 'left') {
        if (index > 0) {
            index--;
        } else {
            index = jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('.genContent').find('.genContentSlide').length;
        }
    } else {
        if (index < jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('.genContent').find('.genContentSlide').length - 1) {
            index++;
        } else {
            index = 0;
        }
    }
    jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('.genContent').find('.genContentSlide.active').fadeOut(500, function () {
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('.genContent').find('.genContentSlide.active').removeClass('active');
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('.genContent').find('.genContentSlide').eq(index).delay(200).fadeIn(500, function () {
            jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').find('.genContent').find('.genContentSlide').eq(index).delay(250).addClass('active');
        });
    });
}

function wpe_toggleField(fieldID, formID) {
    var form = wpe_getForm(formID);
    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #field_' + fieldID + '_cb').is(':checked')) {
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #field_' + fieldID).addClass('opened');
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #field_' + fieldID).slideDown(250);
    } else {
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #field_' + fieldID).removeClass('opened');
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #field_' + fieldID).slideUp(250);
    }
    setTimeout(function () {
        var titleHeight = jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"] h2.stepTitle').height();
        var heightP = jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid=' + form.step + ']').outerHeight() + 160 + titleHeight;
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel').css('min-height', heightP);
    }, 300);
}

function wpe_finalStep(formID) {
    var form = wpe_getForm(formID);
    form.step++;
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_loader').delay(800).fadeOut(form.animationsSpeed * 2);
    setTimeout(function () {
        var redirUrl = lfb_getRedirectionURL(formID);
        if (redirUrl != "" && redirUrl != "#" && redirUrl != " ") {
            document.location.href = redirUrl;
        }
       
    }, form.redirectionDelay * 1000);

}


function wpe_updateStep(formID) {
    var form = wpe_getForm(formID);
    if (form.showSteps == 1) {

        var realPlannedSteps = new Array();
        jQuery.each(lfb_plannedSteps, function () {
            if (!jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + this + '"]').is('.lfb_disabled')
                    && (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + this + '"] .lfb_item:not(.lfb-hidden)').length > 0
                    || jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + this + '"] .lfb_distanceError').length >0)) {
                realPlannedSteps.push(this);
            }
        });
        var disp_step = 0;
        jQuery.each(realPlannedSteps, function (i, v) {
            if (parseInt(v) == parseInt(form.step)) {
                disp_step = i;
            }
        });
        disp_step++;
        if (disp_step == 0) {
            disp_step = 1;
        }
        if (form.step == 'final') {
            disp_step = realPlannedSteps.length + 1;
        }
        var totalStep = realPlannedSteps.length + 1;
        if (disp_step > totalStep) {
            disp_step = totalStep;
        }
        var percent = ((disp_step) * 100) / totalStep;
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] .genPrice .progress .progress-bar-price> span:first-child').html((disp_step) + '/' + totalStep);
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] .genPrice .progress .progress-bar').css('width', percent + '%');
    }
}

function wpe_initPanelResize(formID) {
    var form = wpe_getForm(formID);
    jQuery(window).resize(function () {
        var titleHeight = jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"] h2.stepTitle').height();
        var heightP = jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel .genSlide[data-stepid="' + form.step + '"]').outerHeight() + 160 + titleHeight;
        jQuery(' #estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #mainPanel').css('min-height', heightP);
    });
}
function lfb_rgb2hex(rgb) {
    if (rgb.indexOf('rgb') > -1) {
        try {
            rgb = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
            function hex(x) {
                return ("0" + parseInt(x).toString(16)).slice(-2);
            }
            return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
        } catch (e) {
            return rgb;
        }
    } else {
        return rgb;
    }
}

function wpe_formatPrice(price, formID) {
    var formatedPrice = price.toString();
    if (formatedPrice.indexOf('.') > -1) {
        formatedPrice = parseFloat(price).toFixed(2).toString();
    }
    var form = wpe_getForm(formID);
    var decSep = form.decimalsSeparator;
    var thousSep = form.thousandsSeparator;
    var priceNoDecimals = formatedPrice;
    var millionSep = form.millionSeparator;
    var decimals = "";
    if (formatedPrice.indexOf('.') > -1) {
        priceNoDecimals = formatedPrice.substr(0, formatedPrice.indexOf('.'));
        decimals = formatedPrice.substr(formatedPrice.indexOf('.') + 1, 2);
        formatedPrice = formatedPrice.replace('.', form.decimalsSeparator);
        if (decimals.toString().length == 1) {
            decimals = decimals.toString() + '0';
        }
        if (priceNoDecimals.length > 6) {
            formatedPrice = priceNoDecimals.substr(0, priceNoDecimals.length - 6) + millionSep + priceNoDecimals.substr(priceNoDecimals.length - 6, 3) + thousSep + priceNoDecimals.substr(priceNoDecimals.length - 3, priceNoDecimals.length) + form.decimalsSeparator + decimals;
        } else if (priceNoDecimals.length > 3) {
            formatedPrice = priceNoDecimals.substr(0, priceNoDecimals.length - 3) + thousSep + priceNoDecimals.substr(priceNoDecimals.length - 3, priceNoDecimals.length) + form.decimalsSeparator + decimals;
        }


    } else {
        if (priceNoDecimals.length > 6) {
            formatedPrice = priceNoDecimals.substr(0, priceNoDecimals.length - 6) + millionSep + priceNoDecimals.substr(priceNoDecimals.length - 6, 3) + thousSep + priceNoDecimals.substr(priceNoDecimals.length - 3, priceNoDecimals.length);
        } else if (priceNoDecimals.length > 3) {
            formatedPrice = priceNoDecimals.substr(0, priceNoDecimals.length - 3) + thousSep + priceNoDecimals.substr(priceNoDecimals.length - 3, priceNoDecimals.length);
        }
    }
    return formatedPrice;
}
function lfb_applyCouponCode(formID) {
    var form = wpe_getForm(formID);
    var code = jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_couponField').val();
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_couponField').closest('.form-group').removeClass('has-error');
    if (code.length < 3) {
        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_couponField').closest('.form-group').addClass('has-error');
    } else {
        jQuery.ajax({
            url: form.ajaxurl,
            type: 'post',
            data: {
                action: 'lfb_applyCouponCode',
                formID: formID,
                code: code
            },
            success: function (rep) {
                if (rep == '0' || rep == '') {
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_couponField').closest('.form-group').addClass('has-error');
                } else {
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_couponContainer').slideUp();
                    var reduction = rep;
                    if (rep.indexOf('%') > 0) {
                        reduction = rep.substr(0, rep.length - 1);
                        form.reductionType = '%';
                    }
                    form.discountCode = code;
                    form.reduction = reduction;
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #finalSlide .genContent').animate({opacity: 0}, form.animationsSpeed);
                    setTimeout(function () {
                        wpe_updatePrice(formID);
                        wpe_changeStep('final', formID);
                    }, form.animationsSpeed + 100);
                }
            }
        });
    }
}
function lfb_getRedirectionURL(formID){
    var form = wpe_getForm(formID);
    var rep = form.close_url;
    if(form.useRedirectionConditions == 1){
        jQuery.each(form.redirections,function(){
             var conditions = this.conditions.replace(/'/g, '"');
             conditions = conditions.replace(/\\"/g, '"');
            conditions = JSON.parse(conditions);
            var errors = lfb_checkConditions(conditions, formID);
            error = errors.error;
            errorOR = errors.errorOR;
            
            if ((this.conditionsOperator == 'OR' && !errorOR) || (this.conditionsOperator != 'OR' && !error)) {
                rep = this.url;
            }
            
        });
    }     
    return rep;
}
function lfb_startFormIntro(formID) {
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"]  #startInfos > p').slideDown();
}