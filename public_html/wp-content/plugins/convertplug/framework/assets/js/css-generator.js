;(function ( $, window, undefined ) {

    //  Global variables
    window.css = {};
    window.css_array = [];
    window.targets = {};
    window.properties = {};
  var flag = 1;
    /**
     *  Initially APPLY CSS
     *
     *  1. Apply INLINE - ( THIS IS IN FILE - /framework/lib/fields/colorpicker/cp-color-picker.min.js)
     *  2. Apply after CSS Generation
     */
    //  2. Apply after CSS Generation
    jQuery(document).on('updated', function(e, css_preview, selector, property, value, unit) {
        saveGlobalCSS( css_preview, selector, property, value, unit );
    });
    function set_css_key_val(selector, property, value, unit) {
        if($.isNumeric(value)) {
            css[selector][property] = value + unit;     //  Add numeric values e.g. font-size, line-height etc
        } else {
            css[selector][property] = value;            //  Add numeric values e.g. font-size, line-height etc
        }
    }
    function set_css_attr_val(selector, property, value, unit) {

        if( '' != value && 'undefined' != value ) {
            value = value.split('|');
            if( value[0] ) {
                value = value[0];
            }
        }

        if($.isNumeric(value)) {
            jQuery("#smile_design_iframe").contents().find('body').find(selector).attr(property, value + unit);     //  Add numeric values e.g. font-size, line-height etc
        } else {
            jQuery("#smile_design_iframe").contents().find('body').find(selector).attr(property, value);            //  Add numeric values e.g. font-size, line-height etc
        }
    }

    //  Store CSS on LOAD
    function saveGlobalCSS(css_preview, selector, property, value, unit) {
        if (!css.hasOwnProperty(selector)) css[selector] = {};

            if( css_preview == 1 && null != css_preview && 'undefined' != css_preview ) {
                switch( property ) {
                    case 'padding-tb':      set_css_key_val( selector, 'padding-top', value, unit );
                                            set_css_key_val( selector, 'padding-bottom', value, unit );
                        break;
                    case 'padding-lr':      set_css_key_val( selector, 'padding-left', value, unit );
                                            set_css_key_val( selector, 'padding-right', value, unit );
                        break;
                    case 'margin-tb':       set_css_key_val( selector, 'margin-top', value, unit );
                                            set_css_key_val( selector, 'margin-bottom', value, unit );
                        break;
                    case 'margin-lr':       set_css_key_val( selector, 'margin-top', value, unit );
                                            set_css_key_val( selector, 'margin-bottom', value, unit );
                        break;
                    case 'background-image':
                    case 'bg-image':        set_css_key_val( selector, property, 'url('+value+')', unit );
                        break;
                    case 'width-max':       set_css_key_val( selector, 'width', value, unit );
                                            set_css_key_val( selector, 'max-width', value, unit );
                        break;
                    case 'src':             set_css_attr_val( selector, property, value, unit );
                        break;
                    default:                set_css_key_val( selector, property, value, unit );
                        break;
                }
            }

            var D_CSS = '';
            if(jQuery.isPlainObject(css) && !jQuery.isEmptyObject(css)) {
                jQuery.each(css, function(property, val) {
                    D_CSS += gen_css(property, val );
                });
            }
            jQuery("#smile_design_iframe").contents().find('body').find('#cp-preview-css').html('<style>'+D_CSS+'</style>');
    };
    function gen_css(property, val ) {
        var CSS =  property + ' { \n';
        if(typeof val != 'undefined' && val != null) {
            jQuery.each(val, function(p, v) {
                CSS += p + ':' + v + ';\n';
            });
        }
        CSS += ' } \n\n';
        return CSS;
    }
    /**
     * iFrame load
     */
    jQuery(document).on('iframe_load',function(e,data){
        //  Init - Generate global CSS
        jQuery('.cp-cust-form .smile-input', window.parent.document ).each(function(index, el) {
            var t           = jQuery(el);

            var css_preview = t.attr('data-css-preview') || '';

            var selector    = t.attr('data-css-selector') || '';

            var property    = t.attr('data-css-property') || '';

            var unit        = t.attr('data-unit') || 'px';

            var value       = t.val() || '';

            //  Set background image
            if( property == 'bg-image' || property == 'background-image' ) {
                value = t.attr('data-css-image-url') || '';
            }

            //  Set image url
            if( property == 'src' ) {
                value = t.attr('data-css-image-url') || '';
            }

            //  TEMPORARY STORE ALL CSS
            //  apply css by - inline
            if( css_preview != 1 || 'null' == css_preview || 'undefined' == css_preview || '' == css_preview ) {
                jQuery( selector ).css( property , value );
            }
            saveGlobalCSS( 1, selector, property, value, unit );

        });

        dual__design_form();
        dual__design_form_submit_align();

        //social media
        if(jQuery('.social-media-wrapper').length > 0){
            dual__design_social_media();
        }


        check_layout_dependancy();

        disable_form_field();

        hide_butn_style();

        //  Label Visibility
        var lable_visible = jQuery('#smile_form_lable_visible', window.parent.document ).val();
        dual__hide_form_labels( lable_visible );

        jQuery('.cp-cust-form .smile-input', window.parent.document ).change(function( event ) {

            /**
             * Get single field Updated Key
             */
            var t = jQuery( this );
            //  FIELD - SWITCH
            if( t.hasClass('smile-switch') ) {
                elm_id = t.siblings('input[type="text"]').attr('id');
                t = t.siblings('input[type="text"]');
            }

            var elm_data = t.val();
            var elm_id = t.attr('id');

            if( 'undefined' != elm_id && null != elm_id ) {
                elm_id = elm_id.split("smile_").pop();

                //  Toggle Form - Show either CP (Default) Form or Custom form via ShortCode.
                if( elm_id === 'form_lable_visible' ) {
                    dual__hide_form_labels( elm_data );
                }
            }
        });
    });

    /**
     * Form Generation
     */
    //  Call form builder only for - form layout & form grid structure
    $(document).on('smile-radio-image-change', function(e, el) {

        var s = jQuery(el);
        if( s.hasClass('form_layout') || s.hasClass('form_grid_structure') ) {
            dual__design_form();

            //
            hide_butn_style();

            //  Label Visibility
            var lable_visible = jQuery('#smile_form_lable_visible', window.parent.document ).val();
            dual__hide_form_labels( lable_visible );
        }

        //social media
        if( s.hasClass('cp_social_icon_style')){
            dual__design_social_media();
        }
         if( s.hasClass('form_layout')) {
            var val = jQuery(el).attr('value');
            if( val == 'cp-form-layout-4' || val == 'cp-form-layout-3' ){
                jQuery('.smile-element-container[data-element="form_lable_visible"]').addClass('hide-label');
            }else{
               jQuery('.smile-element-container[data-element="form_lable_visible"]').removeClass('hide-label');

            }

         }

    });
    $(document).on('smile-select-change', function(e, el) {
        var v = $(el).attr('class');
        var s = jQuery(el);
        if( s.hasClass('form_submit_align') ) {
            dual__design_form_submit_align();
            //dual__design_form();
        }

        //social media
        if( s.hasClass('cp_social_icon_column') || s.hasClass('cp_social_icon_shape') || s.hasClass('cp_social_icon_hover_effect') || s.hasClass('cp_social_icon_effect') ) {
            dual__design_social_media();
        }

       if( s.hasClass('btn_style')){
          hide_butn_style();
        }

    });
    $(document).on('multiBoxUpdated', function( e, new_string, pre_id) {
        dual__design_form();
        hide_butn_style ();
    });

   $(document).on('smile-switch-change', function(e,val) {
         //social media
        if( val == 'smile_cp_social_remove_icon_spacing' ||  val == 'smile_cp_display_nw_name' ||  val == 'smile_cp_social_share_count' || val == 'smile_cp_social_enable_icon_color' ){
            dual__design_social_media();
        }

         if( val == 'smile_cta_switch'){
             disable_form_field();
         }

         if( val == 'smile_btn_attached_email' || val == 'smile_btn_shadow'){
          dual__design_form();
          hide_butn_style();
         }
        
    });

   $(document).on('cp-slider-slide', function(e,el) {
        if( jQuery(el).hasClass('social_min_count') ) {
            dual__design_social_media();
        }

   });


   function disable_form_field(){
     var val = jQuery('#smile_cta_switch').val() || '';
     var parent = jQuery("#smile_cta_switch").parents('.accordion-frame');
     var next = jQuery('.cta_delay').parents('.smile-element-container');
     if(val == 0){
         parent.addClass('cp-hide-accordion');
     }else{
        parent.removeClass('cp-hide-accordion');
     }
   }


    function dual__design_form() {

        var form_submit_align = jQuery('#smile_form_submit_align').val() || '';
        var form_grid_structure = jQuery('[name="form_grid_structure"]:checked').val() || '';
        var form_layout = jQuery('[name="form_layout"]:checked').val() || '';
        var fields = jQuery('.smile-multi_box.form_fields').val() || '';
        var enable_attached_field = jQuery('[name="btn_attached_email"]').val() || '';
        var preview_frame = jQuery("#smile_design_iframe").contents();
        var box_shadow = jQuery('[name="btn_shadow"]').val() || '';

        /**
         *  Hide unwanted things from - INFO_BAR
         * 1. Disable 1 & 2 Layouts
         * 2. Form Label options
         * 3. Submit button alignment
         */
        if( preview_frame.find('html').hasClass('cp-customizer-info_bar') ) {
            jQuery('.form_layout-cp-form-layout-1').closest('.smile-radio-image-holder').addClass('cp-hidden');
            jQuery('.form_layout-cp-form-layout-2').closest('.smile-radio-image-holder').addClass('cp-hidden');
            jQuery('#smile_form_lable_visible').closest('.smile-element-container').addClass('cp-hidden');
            jQuery('#smile_form_lable_color').closest('.smile-element-container').addClass('cp-hidden');
            jQuery('#smile_form_label_font').closest('.smile-element-container').addClass('cp-hidden');
            jQuery('#smile_form_lable_font_size').closest('.smile-element-container').addClass('cp-hidden');
            jQuery('#smile_form_submit_align').closest('.smile-element-container').addClass('cp-hidden');
        }

        //  Set variables
        var cp_submit           = preview_frame.find(".cp-submit"),
            cp_submit_wrap      = preview_frame.find(".cp-submit-wrap"),
            cp_all_inputs_wrap  = preview_frame.find('.cp-all-inputs-wrap'),
            default_form        = preview_frame.find(".default-form");

        //  CP_FORM - Set form layout class

         /**
          * Define Form Layout Classes
          */
        var class_fields = '';
        var class_submit = '';
        var class_cp_all_inputs_wrap = 'col-xs-12';
        var all_fields = fields.split(";");

        default_form.removeClass( ' cp-form-layout-1 cp-form-layout-2 cp-form-layout-3 cp-form-layout-4 ' );
        default_form.addClass( form_layout );
        switch ( form_layout ) {

            case 'cp-form-layout-1':    class_fields = ' col-md-12 col-lg-12 col-sm-12 col-xs-12';
                                        class_submit = ' col-md-12 col-lg-12 col-sm-12 col-xs-12';
                                        preview_frame.find('.cp-all-inputs-wrap').show();                          //  Show Input form
                                        // preview_frame.find('.cp-section[data-section-id="submission"]', window.parent.document).show();
                break;

            case 'cp-form-layout-2':    class_fields = ' col-md-6 col-lg-6 col-sm-6 col-xs-12';
                                        class_submit = ' col-md-12 col-lg-12 col-sm-12 col-xs-12';
                                        preview_frame.find('.cp-all-inputs-wrap').show();                          //  Show Input form
                                        // preview_frame.find('.cp-section[data-section-id="submission"]', window.parent.document).show();
                break;

            case 'cp-form-layout-3':    //  Grid structure for All Input Wrap & Submit
                                        switch( form_grid_structure ) {
                                            case 'cp-form-grid-structure-1':                class_submit    = ' col-md-6 col-lg-6 col-sm-6 col-xs-12 ';
                                                                                class_cp_all_inputs_wrap    = ' col-md-6 col-lg-6 col-sm-6 col-xs-12 ';
                                                break;
                                            case 'cp-form-grid-structure-2':                class_submit    = ' col-md-4 col-lg-4 col-sm-4 col-xs-12 ';
                                                                                class_cp_all_inputs_wrap    = ' col-md-8 col-lg-8 col-sm-8 col-xs-12 ';
                                                break;
                                            case 'cp-form-grid-structure-3':
                                            default:                class_submit    = ' col-md-3 col-lg-3 col-sm-3 col-xs-12 ';
                                                        class_cp_all_inputs_wrap    = ' col-md-9 col-lg-9 col-sm-9 col-xs-12 ';
                                                break;
                                        }

                                        if( all_fields.length > 0 ) {

                                            //  Remove hidden fields from count
                                            var no_of_hiddens = (fields.match(/input_type->hidden/g) || []).length;
                                            var fields_count = all_fields.length;
                                            if( no_of_hiddens != 'NaN' && no_of_hiddens != 'undefined' && no_of_hiddens != null ) {
                                                fields_count = all_fields.length - no_of_hiddens;
                                            }

                                            switch( fields_count ) {
                                                case 1:
                                                            class_fields = 'col-md-12 col-lg-12 col-sm-12 col-xs-12';
                                                    break;
                                                case 2:
                                                            class_fields = 'col-md-6 col-lg-6 col-sm-6 col-xs-12';
                                                    break;
                                                case 3:
                                                            class_fields = 'col-md-4 col-lg-4 col-sm-4 col-xs-12';
                                                    break;
                                                case 4:
                                                case 5:
                                                            class_fields = 'col-md-3 col-lg-3 col-sm-3 col-xs-12';
                                                    break;
                                                case 6:
                                                case 7:
                                                            class_fields = 'col-md-2 col-lg-2 col-sm-2 col-xs-12';
                                                    break;
                                            }
                                        }
                                        preview_frame.find('.cp-all-inputs-wrap').show();                          //  Show Input form
                                        // preview_frame.find('.cp-section[data-section-id="submission"]', window.parent.document).show();

                break;

            case 'cp-form-layout-4':    cp_all_inputs_wrap.removeClass('col-md-9 col-lg-9 col-sm-9');
                                        preview_frame.find('.cp-all-inputs-wrap').hide();                          //  Hide Input form
                                        // preview_frame.find('.cp-section[data-section-id="submission"]', window.parent.document).hide();
                break;

            break;
        }

        //  Remove all classes
        var allColClasses = 'col-lg-1 col-md-1 col-sm-1 col-lg-2 col-md-2 col-sm-2 col-lg-3 col-md-3 col-sm-3 col-lg-4 col-md-4 col-sm-4 col-lg-5 col-md-5 col-sm-5 col-lg-6 col-md-6 col-sm-6 col-lg-7 col-md-7 col-sm-7  col-lg-8 col-md-8 col-sm-8 col-lg-9 col-md-9 col-sm-9 col-lg-10 col-md-10 col-sm-10 col-lg-11 col-md-11 col-sm-11 col-lg-12 col-md-12 col-sm-12';
        preview_frame.find('.cp-form-field').removeClass( allColClasses );
        cp_submit_wrap.removeClass( allColClasses );
        cp_all_inputs_wrap.removeClass( allColClasses );

        //  Add classes for Submit, All Input Wrapper
        cp_submit_wrap.addClass( class_submit );
        cp_all_inputs_wrap.addClass( class_cp_all_inputs_wrap );



        /**
         * Create HTML structures
         *
         * 1+ For Inputs
         * 2+ For CKEditor button only
         */
        var HTML = '';
        var HIDDEN_FIELDS = '';
        var data_value = [];
        var last_order = '';
        //  Extract ALL - field
        var all = fields.split(";");
        $lg = all.length-1;
        if($lg !== 0){
        $.each( all , function( index, val ) {
             //  Extract SINGLE - all
            var single = val.split("|");
            if(single.length!== 0){
                if (single[1].indexOf("hidden") <= 0){
                    data_value.push(single[0]);
                }
            }

        });

        var last_order = data_value[data_value.length-1];
        last_order= last_order.split("->");
        last_order = parseInt(last_order[1]);
        }

        if(typeof last_order =='undefined' || last_order == ''){
         last_order = 0;
        }

        var p = 0;
        $.each( all , function( index, val ) {

            //  Empty Fields
            var name = '';
            var require = '';
            var placeholder = '';
            var label = '';
            var type = '';
            var dropdown_options = '';
            var row_value ='';
            var last_input_class = '';

            //  Extract SINGLE - all
            var single = val.split("|");
            $.each( single , function( i, v ) {
                var s = v.split("->");
                switch( s[0] ) {
                    case 'input_label':         label = s[1];
                        break;
                    case 'input_name':          name = s[1];
                        break;
                    case 'input_placeholder':   placeholder = s[1];
                        break;
                    case 'input_require':       require = ( s[1] === 'true' ) ? ' required ' : '';
                        break;
                    case 'input_type':          type = s[1];
                        break;
                    case 'dropdown_options':    dropdown_options = s[1];
                        break;
                    case 'row_value':    row_value = 'rows="'+s[1]+'"';
                        break;
                }
            });

            //  If last child then add '.col-md-12' for last child
                if( ( form_layout !== 'cp-form-layout-3' ) && ( index === last_order ) && ( p%2 == 0 ) ) {

                    class_fields = ' col-md-12 col-lg-12 col-sm-12 col-xs-12 odd-field-fallback';
                }

            if(( form_layout == 'cp-form-layout-2' ) &&  $lg == 0 ){
                if(( index === last_order ) && ( p%2 == 0 )){
                     class_fields = ' col-md-12 col-lg-12 col-sm-12 col-xs-12 odd-field-fallback';
                 }else{
                    class_fields = ' col-md-12 col-lg-12 col-sm-12 col-xs-12 ';
                }
            }

            if( type !== 'hidden' ) {
                p++;
            }

            flag = last_order;
            last_order =parseInt(last_order);
            if( ( form_layout == 'cp-form-layout-3' ) && last_order == 0 ){
               jQuery(".smile-element-container[ data-element='btn_attached_email']").removeClass('hide_email_attached');
             }else{
               jQuery(".smile-element-container[ data-element='btn_attached_email']").addClass('hide_email_attached');
             }

            if( ( form_layout == 'cp-form-layout-3' ) && ( index === last_order ) && enable_attached_field == 1 ){
                if( last_order == 0 ){
                    //enable_attached_field = 1;
                    last_input_class = 'enable-field-attached';
                    if( box_shadow == '1' ){
                        last_input_class = last_input_class+" cp-enable-box-shadow";
                    }
                }else{
                    //enable_attached_field = 0;
                   // jQuery('[name="btn_attached_email"]').val("0");
                }
            }

            var shadow_input_class = '';
            /* if( box_shadow == '1' ){
                shadow_input_class = " cp-enable-box-shadow";
             }*/

            //  For ONLY hidden field
            if( type == 'hidden' ) {
                //  Hidden
                HIDDEN_FIELDS += '<input class="cp-input cp-' + type + '"'
                    + ' type="' + type + '"'
                    + ' name="' + name + '"'
                    + ' placeholder="' + placeholder + '" ' + require + ' />';
            } else {

                /**
                 * Build HTML structure for inputs
                 */

                var lable_visible = jQuery('#smile_form_lable_visible', window.parent.document ).val();
                var display = 'display:none;';
                if( typeof lable_visible != 'undefined' && lable_visible == '1' ) {
                    display = 'display:block;';
                }

                /*//  If last child then add '.col-md-12' for last child
                if( ( form_layout !== 'cp-form-layout-3' ) && ( index === all.length - 1 ) && ( index%2 == 0 ) ) {
                    class_fields = ' col-md-12 col-lg-12 col-sm-12 col-xs-12 odd-field-fallback';
                }*/

                HTML += '<div class="cp-form-field '+class_fields+' '+last_input_class+' '+ shadow_input_class+'" >';
                HTML += '   <label style="'+display+'">'+label+'</label>';
                HTML += '       <div>';

                switch( type ) {
                    case 'email':
                    case 'textfield':       //  Text
                                            HTML    += '<input class="cp-input cp-' + type + '"'
                                                + ' type="' + type + '"'
                                                + ' name="' + name + '"'
                                                + ' placeholder="' + placeholder + '" ' + require + ' />';
                        break;
                    case 'textarea':        //  Textarea
                                            HTML    += '<textarea class="cp-input cp-' + type + '"' + require
                                                + ' name="' + name + '" placeholder="' + placeholder + '" '+row_value+'></textarea>';
                        break;
                    case 'number':
                                            HTML += '<input type="number" min="" max="" step="" value="" class="cp-' + type + '"'
                                                + ' name="' + name + '"'
                                                + ' placeholder="' + placeholder + '" ' + require + ' />';
                    case 'dropdown':
                                            if( '' != dropdown_options && null != dropdown_options && 'undefined' != dropdown_options ) {
                                                HTML += '<select class="cp-' + type + '"' + ' name="' + name + '"' + require + ' >'
                                                +   get_dropdown_options(dropdown_options)
                                                + '</select>';
                                            }
                        break;
                }

                HTML    += '</div></div><!-- .cp-form-field -->';
            }
        });

        //  ConCat ALL_FILEDS & HIDDEN_FIELDS
        HTML = HTML + HIDDEN_FIELDS;

        //  Append to All Inputs Wrap
        preview_frame.find('.cp-all-inputs-wrap').html( HTML );

        //  Model height
        if( typeof function_name == 'CPModelHeight' ) {
             CPModelHeight();
        }

        if( form_layout == 'cp-form-layout-3' && enable_attached_field == 1 && flag == 0){
            cp_submit_wrap.addClass('enable-field-attached');
           // cp_all_inputs_wrap.find('.cp-form-field:last-child').addClass('enable-field-attached');
        }else{
            cp_submit_wrap.removeClass('enable-field-attached');
            //cp_all_inputs_wrap.find('.cp-form-field:last-child').removeClass('enable-field-attached');
        }


    }
    //  Hide Labels?
    function dual__hide_form_labels( is_visible ) {
        if( typeof is_visible != 'undefined' && is_visible != '1' ) {
            jQuery("#smile_design_iframe").contents().find('.cp-form-container label').hide();
        } else {
            jQuery("#smile_design_iframe").contents().find('.cp-form-container label').show();
        }
    }
    function dual__design_form_submit_align() {
        var v = $('#smile_form_submit_align' ).val();

        if( typeof v != 'undefined' && v != '' ) {
            var submit = jQuery("#smile_design_iframe").contents().find(".cp-submit-wrap")
            submit.removeClass( "cp-submit-wrap-center cp-submit-wrap-left cp-submit-wrap-right cp-submit-wrap-full " );
            //submit.removeClass("enable-field-attached");
            submit.addClass( v );
        }
    }

    /**
     * DropDown - Extract all dropdown options
     */
    function get_dropdown_options( string ) {
        var lines = string.split("\n");
        var all_options = '';
        jQuery.each( lines , function(index, val) {
            if( '' != val && 'undefined' != val && null != val ) {
                var option = val.split('+');
                all_options += '<option value="'+option[0].toLowerCase()+'">' + option[0] + '</option>';
            }
        });
        return all_options;
    }

/*
 *design social media style
 */

  $(document).on('socialMediaUpdated', function( e, new_string, pre_id) {
        dual__design_social_media();
   });

 //function for social media design
  function dual__design_social_media(){

        var preview_frame                   = jQuery("#smile_design_iframe").contents();
            cp_social_icon_style            = jQuery('[name="cp_social_icon_style"]:checked').val() || '';
            cp_social_icon_shape            = jQuery(".cp_social_icon_shape").val(),
            cp_social_icon_effect           = jQuery(".cp_social_icon_effect").val(),
            cp_social_icon_column           = jQuery('#smile_cp_social_icon_column').val(),// data.cp_social_icon_column,
            icon_bgcolor                    = jQuery('#smile_cp_social_icon_bgcolor').val(),
            icon_bghover                    = jQuery('#smile_cp_social_icon_bghover').val(),
            cp_social_share_count           = jQuery('#smile_cp_social_share_count').val(),
            cp_display_nw_name              = jQuery('#smile_cp_display_nw_name').val(),
            cp_social_remove_icon_spacing   = jQuery('#smile_cp_social_remove_icon_spacing').val(),
            cp_social_icon                  = jQuery('.cp_social_icon').val()|| '',
            social_min_count                = jQuery('.social_min_count').val(),
            cp_social_networks              = preview_frame.find(".cp_social_networks"),
            cp_social_media_wrapper         = preview_frame.find(".cp_social_media_wrapper"),
            cp_social_enable_icon_color     = jQuery('#smile_cp_social_enable_icon_color').val(),
            cp_social_icon_hover_effect     = jQuery('#smile_cp_social_icon_hover_effect').val(),
            social_html                     = '',
            cp_social_icon_column_class     = '',
            social_style                    = '',
            icon_style                      = '';


           if(cp_social_icon_style =='' || typeof cp_social_icon_style =='undefined'){
              cp_social_icon_style = 'cp-icon-style-top';
           }
           if(cp_display_nw_name =='' || typeof cp_display_nw_name =='undefined'){
              cp_display_nw_name = false;
           }
           if(cp_social_icon_column =='' || typeof cp_social_icon_column =='undefined'){
              cp_social_icon_column = '1';
           }

           if(cp_social_icon_effect =='' || typeof cp_social_icon_effect =='undefined'){
              cp_social_icon_effect = 'none';
           }

        //remove html structure of wrapper
        if( typeof cp_social_icon !=='undefined' || cp_social_icon !==''){
             cp_social_media_wrapper.empty();
             if( typeof cp_social_icon_column == 'undefined' || cp_social_icon_column == ''){
                cp_social_icon_column ='1';
             }

            //apply no of column to container
            if( cp_social_icon_column == 'auto' ){
                cp_social_icon_column_class = 'autowidth';
            } else {
                cp_social_icon_column_class = 'col_'+cp_social_icon_column;
            }

        /**
         * Build HTML structure for Social_icon
         */

        social_html += '<div class="cp_social_networks cp_social_'+cp_social_icon_column_class+' cp_social_left cp_social_withcounts cp_social_withnetworknames '+ cp_social_icon_style +'" data-column-no="cp_social_'+cp_social_icon_column_class+'">';

        social_html += ' <ul class="cp_social_icons_container">';

        var cp_fileds = cp_social_icon.split(";");

        var array = [];
        $.each( cp_fileds , function( index, val ) {
            var single = val.split("|");
             var ItemArray = [];
            $.each( single , function( i, v ) {
                var s = v.split(":");
                ItemArray[s[0]] = s[1];

            });
            array.push(ItemArray);
        });

        $.each( array , function( index, val ) {

            var input_type = val['input_type'].toLowerCase();
            var network_name = val['input_type'];
            var newnw = val['network_name'];
             if(newnw!==''){
                network_name = newnw;
             }

            social_html += '<li class="cp_social_'+input_type+'">'
                     + '<a href="javascript:void(0)" class="cp_social_share cp_social_display_count">'
                     + '<i class="cp_social_icon cp_social_icon_'+input_type+'"></i>';

            //display label
            if( cp_display_nw_name == 1 || cp_social_share_count == 1 ){
                social_html += '<div class="cp_social_network_label">';
            }
            //display network name
            if( cp_display_nw_name == 1 ){
               social_html += '<div class="cp_social_networkname">'+network_name+'</div>';
            }

            //display share count
            if( cp_social_share_count == 1 ){
                if(social_min_count !== ''){
                    social_html += '<div class="cp_social_count"><span>'+social_min_count+'</span></div>';
                }
            }
            //close label div
            if( cp_display_nw_name == 1 || cp_social_share_count == 1 ){
                social_html += '</div>';
            }

            //icon effect
            if(cp_social_icon_effect == 'gradient' ){
                social_html += '<div class="cp_social_overlay"></div>';
            }

            social_html += '</a>'
                        + ' </li>';
        });

        social_html += '</ul>';   /*--end of cp_social_icons_container --*/
        social_html += '</div>';/*--end of cp_social_networks--*/

        //append html to social media wrapper
        cp_social_media_wrapper.append( social_html );

        //  Model height
        if( typeof function_name == 'CPModelHeight' ) {
             CPModelHeight();
        }

        // Equalize blank style content vertically center
        if( typeof function_name == 'cp_row_equilize' ) {
                cp_row_equilize();
        }

    //style class

        var class_icon_hover_effect = '';

        if( cp_social_icon_hover_effect == 'slide'){
            switch( cp_social_icon_style ) {
                case 'cp-icon-style-simple':
                            class_icon_hover_effect = 'cp_social_slide';
                    break;

                case 'cp-icon-style-rectangle':
                            class_icon_hover_effect = 'cp_social_slide';
                    break;

                case 'cp-icon-style-right':
                            class_icon_hover_effect = 'cp_social_flip';
                    break;

                case 'cp-icon-style-left':
                            class_icon_hover_effect = 'cp_social_flip';
                    break;


            }
        }

        var container_social_nw = jQuery("#smile_design_iframe").contents().find('.cp_social_networks');
        if(cp_social_icon_style == 'cp-icon-style-simple' ){
            container_social_nw.addClass('cp_social_simple');
            container_social_nw.addClass( class_icon_hover_effect );
            container_social_nw.removeClass('cp_social_flip');
        }else {
            if(cp_social_icon_style == 'cp-icon-style-rectangle' ){
                 container_social_nw.addClass( class_icon_hover_effect );
                 container_social_nw.removeClass('cp_social_flip');
            }else{
                 container_social_nw.addClass( class_icon_hover_effect )
                 container_social_nw.removeClass('cp_social_slide ');
            }
             container_social_nw.removeClass('cp_social_simple');
        }

        //for floating sidebar of slidein
        if( cp_social_icon_style == 'cp-icon-style-top'){
            var cpeffectList = ['cp-hover-simple', 'cp-hover-slide', 'cp-hover-grow', 'cp-hover-flip'];
            jQuery.each(cpeffectList, function(i, v){
               container_social_nw.removeClass(v);
            });
            container_social_nw.addClass('cp-hover-'+cp_social_icon_hover_effect);

            if( cp_social_share_count == 1 ){
                container_social_nw.removeClass('cp-network-without-count');
            }else{
                container_social_nw.addClass('cp-network-without-count');
            }
        }else{
            container_social_nw.removeClass('cp-network-without-count');
        }


        //manage spacing when column is 1
        jQuery('#cp-social-icon-space-css').remove();

        //apply css if column width = 1; remove margin for li
        jQuery('head').append('<div id="cp-social-icon-space-css"></div>');
        var icon_space_style ='';
        if(cp_social_icon_column == '1' ){
            icon_space_style += '.cp-modal-body ol, .cp-modal-body ol li, .cp-modal-body ul, .cp-modal-body ul li {margin: 2% 0 0 0;}';
         }
        jQuery('#cp-social-icon-space-css').html( '<style>' + icon_space_style + '</style>');

        //remove class
        var classList = ['cp-circle', 'cp-square', 'cp-border_radius','cp-normal'];
        jQuery.each(classList, function(i, v){
           container_social_nw.removeClass(v);
        });
        container_social_nw.addClass( 'cp-'+cp_social_icon_shape );


        //  Remove all classes for icon effect
        var effectList = ['cp-flat', 'cp-3d', 'cp-gradient'];
        jQuery.each(effectList, function(i, v){
           container_social_nw.removeClass(v);
        });
        container_social_nw.addClass('cp_'+cp_social_icon_effect );

         //if count and nw name is not present
        var no_count ='';
        if( cp_social_icon_style == 'cp-icon-style-rectangle' && cp_social_icon_effect =='gradient' && cp_display_nw_name !== '1' && cp_social_share_count !== '1' ){
            container_social_nw.addClass('cp-no-count-no-share');
        }else{
            container_social_nw.removeClass('cp-no-count-no-share');
        }

        //spacing
        if(cp_social_remove_icon_spacing == 1){
            container_social_nw.addClass('cp-no-spacing');
            cp_social_media_wrapper.addClass('cp-social-no-space');
        }else{
            container_social_nw.removeClass('cp-no-spacing');
            cp_social_media_wrapper.removeClass('cp-social-no-space')
        }

        if( cp_social_icon_column == 'auto' ){
            cp_social_media_wrapper.addClass('cp-auto-column');
        }else{
            cp_social_media_wrapper.removeClass('cp-auto-column');
        }

        if( cp_social_enable_icon_color == 1 ){
            container_social_nw.addClass('cp-custom-sc-color');
        }else{
            container_social_nw.removeClass('cp-custom-sc-color');
        }

    }


}
    function check_layout_dependancy(){
        var layout = jQuery('[name="form_layout"]:checked').val() || '';
        if( layout == 'cp-form-layout-4' || layout == 'cp-form-layout-3' ){
            jQuery('.smile-element-container[data-element="form_lable_visible"]').addClass('hide-label');
        }else{
           jQuery('.smile-element-container[data-element="form_lable_visible"]').removeClass('hide-label');

        }
    }

//hide btn style for enable input field attached
function hide_butn_style(){

    var layout = jQuery('[name="form_layout"]:checked').val() || '';
    var btn_attached_email    = jQuery('[name="btn_attached_email"]').val() || '';
    var option =  jQuery("#smile_btn_style").val();
    var btn_shadow = jQuery(".smile-element-container[data-element='btn_shadow']");

    if( btn_attached_email == '1' && layout == 'cp-form-layout-3' && flag !== 1 ){
        jQuery("#smile_btn_style option[value=cp-btn-3d]").hide();
        jQuery("#smile_btn_style option[value=cp-btn-outline]").hide();
        if( option == 'cp-btn-3d' || option == 'cp-btn-outline' ){
            jQuery('select[name^="btn_style"] option[selected="selected"]').removeAttr("selected");
            jQuery('select[name^="btn_style"] option[value="cp-btn-flat"]').attr("selected","selected");
            btn_shadow.addClass('display-shadow');
        }else{
           jQuery('select[name^="btn_style"] option[selected="selected"]').removeAttr("selected");
            btn_shadow.removeClass('display-shadow');
            jQuery('select[name^="btn_style"] option[value="'+option+'"]').attr("selected","selected");
        }
    }else{
        jQuery('select[name^="btn_style"] option[selected="selected"]').removeAttr("selected");
        btn_shadow.removeClass('display-shadow');
        jQuery('select[name^="btn_style"] option[value="'+option+'"]').attr("selected","selected");
        jQuery("#smile_btn_style option[value=cp-btn-3d]").show();
        jQuery("#smile_btn_style option[value=cp-btn-outline]").show();
    }
}

}(jQuery, window));
