//for count down modal
function cp_start_count_timer(){
  //cp_count_down cp-overlay
    jQuery(".cp_count_down_main").each(function(t) {
        var date                = jQuery(this).data('date'),
         countupto              = new Date(date),
         counter_option         = jQuery(this).data('timeformat'),
         format                 = '',
         cp_defaultCountdown    = jQuery(this),
         timezonename           = jQuery(this).closest('.global_modal_container').data('timezonename'),
         show_datepicker        = jQuery(this).data('showcounter'),
         advnce_countdown       = jQuery(this).data('advnce-countdown'),
         layoutopt              = "",
         layoutformat           = "",
         gmt_offset             = jQuery(this).closest('.global_modal_container').data('tz-offset'),
         vw                     = jQuery(window).width(),
         compact                = false,
         labelsname             = ['Year','Month','Weeks','Days','Hours','Minutes','Seconds'];

    if(show_datepicker == 'show'){

        if (counter_option.length > 0) {
         counter_option = counter_option.split("|");
         jQuery.each(counter_option, function(i,v){
              format += v;

          });
        } else{
            format = "YOWDHMS";
            labels = "";
        }

        for (var i = 0, len = format.length; i < len; i++) {
            var  lower = format[i].toLowerCase();
            layoutformat += '{'+lower+'n}';
            if(i+1!== len){
                layoutformat += ' {'+lower+'l}, ';
            }else{
               layoutformat += ' {'+lower+'l} ';
            }
        }

        //enable-disable layouts
        if(advnce_countdown !== 'style_2'){
            layoutopt = layoutformat ;
        }else{
            var lt = format.length ;
            //if counter digit greater than 4 then compress labels
            if(vw <=610 && lt >3 ){
                labelsname = ['Y','M','W','D','H','Mn','S'];
            }
        }

        //destry prev counter
        cp_defaultCountdown.cp_countdown('destroy');

        if(timezonename == 'wordpress'){
            cp_defaultCountdown.cp_countdown({ until: countupto, timezone: gmt_offset , format: format ,layout: layoutopt , labels:labelsname });
        }else{
            cp_defaultCountdown.cp_countdown({ until: countupto, format: format , layout: layoutopt ,labels:labelsname });
        }

         }

    });
}

 jQuery(document).ready(function(){
     cp_start_count_timer();
 });

 jQuery(window).on('resize',function() {
     cp_start_count_timer();
});
