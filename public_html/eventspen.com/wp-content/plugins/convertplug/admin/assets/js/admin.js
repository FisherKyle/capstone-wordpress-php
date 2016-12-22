var smile_panel = '',
	data_id = '';

jQuery.extend({
  cpcpGetUrlVars: function(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
      hash = hashes[i].split('=');
      vars.push(hash[0]);
      vars[hash[0]] = hash[1];
    }
    return vars;
  },
  cpGetUrlVar: function(name){
    return jQuery.cpcpGetUrlVars()[name];
  }
});

jQuery.fn.bgColorFade = function(userOptions) {
    // starting color, ending color, duration in ms
    var options = $.extend({
        start: "#fff79f",
        end: "#fff",
        time: 2000
    }, userOptions || {});
    $(this).css({
        backgroundColor: options.start
    }).animate({
        backgroundColor: options.end
    }, options.time);
    return this;
};

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function hideLoading(ID){
	jQuery(document).trigger("iframe_load",[ID]);
	var smile_panel = jQuery(".customize").data('style');
	setTimeout(function(){
		jQuery(".edit-screen-overlay").fadeOut();
		jQuery("#smile_design_iframe").css("visibility","visible");
		jQuery(".design-area-loading").hide();
	},500);
}

// Sets cookies.
window.createCookie = function(name, value, days){

	// If we have a days value, set it in the expiry of the cookie.
	if ( days ) {
		var date = new Date();
		date.setTime(date.getTime() + (days*24*60*60*1000));
		var expires = '; expires=' + date.toGMTString();
	} else {
		var expires = '';
	}

	// Write the cookie.
	document.cookie = name + '=' + value + expires + '; path=/';
}

// Retrieves cookies.
window.getCookie = function(name){
	var nameEQ = name + '=';
	var ca = document.cookie.split(';');
	for ( var i = 0; i < ca.length; i++ ) {
		var c = ca[i];
		while ( c.charAt(0) == ' ' ) {
			c = c.substring(1, c.length);
		}
		if ( c.indexOf(nameEQ) == 0 ) {
			return c.substring(nameEQ.length, c.length);
		}
	}

	return null;
}

// Removes cookies.
window.removeCookie = function(name){
	createCookie(name, false , -1);
}

jQuery(document).on('iframe_load',function(e,data){
	var smile_panel = jQuery(".customize").data('style');
	jQuery('#button-save-'+smile_panel+' > span').trigger('click');
	jQuery('a[data-section-id="responsive-sect"]').trigger('click');
	setTimeout( function() {
		jQuery('#button-save-'+smile_panel).trigger('click');
		jQuery(".cp-section").first().trigger('click');
	},1000);
});

function smileHandleDependencies(){
	var container = jQuery(".active-customizer").find(".smile-element-container");
	jQuery.each(container,function(index,element){
		var $this 		= jQuery(this);
		var el_name 	= $this.data('name');
		var el_operator = $this.data('operator');
		var el_value 	= $this.data('value');
		var element 	= $this.data('element');
			element		= jQuery(this).parents(".content").find("#smile_"+element);

		if(typeof el_name !== 'undefined'){
			var el_id = jQuery(this).parents(".content").find("#smile_"+el_name);
			var value = el_id.val();
			var displayProp = el_id.closest('.smile-element-container').css('display');
			$this.hide();

			//	We check the #smile_EL_NAME value for dependency
			//	In [Radio Buttons] it does not works, Because It has different ID's
			//	So, We change the selector for radio button
			if(typeof value === 'undefined'){
				var el_id = jQuery(this).parents(".content").find("input[type='radio'][name='"+el_name+"']:checked");
				var value = el_id.val();
				var displayProp = el_id.closest('.smile-element-container').css('display');
				$this.hide();
			}

			switch(el_operator){
				case '=':
					if( value = el_value && displayProp == 'block' ){
						$this.show();
					} else {
						$this.hide();
					}
					break;
				case '>':
					if(value > el_value  && displayProp == 'block'){
						$this.show();
					} else {
						$this.hide();
					}
					break;
				case '>=':
					if(value >= el_value  && displayProp == 'block'){
						$this.show();
					} else {
						$this.hide();
					}
					break;
				case '<':
					if(value < el_value  && displayProp == 'block'){
						$this.show();
					} else {
						$this.hide();
					}
					break;
				case '<=':
					if(value <= el_value  && displayProp == 'block'){
						$this.show();
					} else {
						$this.hide();
					}
					break;
				case '==':
					if(value == el_value  && displayProp == 'block') {
						$this.show();
					} else {
						$this.hide();
					}
					break;
				case '!=':
					if(value != el_value  && displayProp == 'block'){
						$this.show();
					} else {
						$this.hide();
					}
					break;
				case '!==':
					if(value !== el_value  && displayProp == 'block'){
						$this.show();
					} else {
						$this.hide();
					}
					break;
				case 'is_contain':
					if( value.indexOf(el_value) >= 0 && displayProp == 'block' ){
						$this.show();
					} else {
						$this.hide();
					}
					break;
			}
			if( $this.hasClass("hide-for-default" ) ) {
				$this.hide();
			}
		}
	});
}

jQuery(document).ready(function(){
	var theme			= jQuery.cpGetUrlVar('theme');
	var btn 			= jQuery('.customize');
	var collapse 		= jQuery('.customizer-collapse');
	var cls 			= jQuery('.close-button');
	var copy_btn 		= jQuery('.copy-style-icon');
	var delete_btn 		= jQuery('.trash-style-icon');
	var rename_btn 		= jQuery('.update-title');
	var calcel_btn 		= jQuery('.cancel-title');
	var changeStatus	= jQuery('a.change-status');

	jQuery('body').on('keyup', '#style-title', function(e) {
		jQuery(this).removeAttr('style');
	});

	// custom html editor

	var htmltextarea = jQuery("#smile_custom_html_form");
	var mode = 'xml';
    var editDiv = jQuery('<div>', {
        position: 'absolute',
        width: 300,
        height: 300,
        'class': htmltextarea.attr('class')
    }).insertBefore(htmltextarea);
    if( htmltextarea.length > 0 )  {
	    htmltextarea.css('visibility', 'hidden');
	    var htmlEditor = ace.edit(editDiv[0]);
	    htmlEditor.renderer.setShowGutter(true);

	    htmlEditor.getSession().setValue(htmltextarea.val());
	    htmlEditor.getSession().setMode("ace/mode/" + mode);
	    htmlEditor.getSession().setUseWrapMode(true);

	    htmlEditor.on('change', function() {
	        htmltextarea.val(htmlEditor.getSession().getValue());
	    });
	}

    // custom css editor

	var textarea = jQuery("#smile_custom_css");
	var mode = 'css';
	var editDiv = jQuery('<div>', {
		position: 'absolute',
		width: 300,
		height: 300,
		'class': htmltextarea.attr('class')
    	}).insertBefore(textarea);

 	if( textarea.length > 0 )  {
		editDiv.attr('id','editor');
		textarea.css('visibility', 'hidden');
		var cssEditor = ace.edit(editDiv[0]);
		cssEditor.renderer.setShowGutter(true);
		cssEditor.getSession().setValue(textarea.val());
		cssEditor.getSession().setMode("ace/mode/" + mode);
		cssEditor.getSession().setUseWrapMode(true);

		cssEditor.on('change', function() {
			textarea.val(cssEditor.getSession().getValue());
		});
	}

	jQuery("body").on("click",".style-title",function(e){
		e.preventDefault();
		var $this = jQuery(this);
		jQuery(".style-title").unbind("click");
		var wrapper 	= $this.parent('.smile-slug');
		var update 		= $this.parents("td").find(".button");
		var tr 			= $this.parents('tbody').find('tr');
		var num			= $this.parents('tr').index();
		var value 		= $this.text();
		value 			= jQuery.trim(value);
		var edit = false;
		jQuery.each(tr,function(index,val){
			if(index !== num){
				jQuery(this).addClass('inline-editing');
			}
		});
		var cls = jQuery(this).parents('tr').attr('class');
		if(cls.indexOf('inline-editing') > 0){
			edit = false;
		} else {
			edit = true;
		}
		if(edit){
			wrapper.html('<input type="text" id="rename-title" value="'+value+'"/>');
			update.show();
		}
	});

	jQuery(".cp-website-link, .cp-dashboard-link, .close-button").click(function(e){
		e.preventDefault();
		var styleName = jQuery("#cp_style_title").val();
		var target = jQuery(this).attr('target');
		var cookie = getCookie("cp-unsaved-changes");
		var redirectURL = jQuery(this).data('redirect');
		var smile_panel = jQuery(".customize").data('style');
		var $this = jQuery(this);
		var closeOncancel = false;
		var showLoaderOnConfirm = true;

		var live 	 = jQuery("#smile_live").val();
		var module = jQuery(".customize").data("module");
		if( live == "" || live == "0" || live == 0 ) {
			var closeOncancel = false;
			var title = "Publish & Set This "+module+" Live?";
			var confirmText = "Yes, Let's Publish It.";
			var cancelButtonText = 'No, Keep it Pause!';
			var cancleMessage = 'The '+module+' is pause at present. It will not be visible on site until you make it live.';
			var showLoaderOnConfirm = false;
		} else if(cookie) {
			var title = "Some changes are not saved yet!";
			var confirmText = "Save it!";
			var cancelButtonText = "Close without saving changes";
			var cancleMessage = 'Changes are saved.';
		} else {
			removeCookie('cp-unsaved-changes');
			if( target == '_blank' )
				window.open(redirectURL);
			else
				window.location = redirectURL;
		}

		swal({
			title: title,
			text: '<span class="cp-discard-popup" style="position: absolute;top: 0;right: 0;"><i class="connects-icon-cross"></i></span>',
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: confirmText,
			cancelButtonText: cancelButtonText,
			closeOnConfirm: false,
			closeOnCancel: closeOncancel,
			showLoaderOnConfirm: showLoaderOnConfirm,
			customClass: 'cp-switch-theme',
			html: true,
		},
		function(isConfirm){
			if (isConfirm) {
				if( live == "" || live == "0" || live == 0 ) {
					jQuery("#smile_live").attr('value',1);
					var section = jQuery('.cp-section.active');
					jQuery('#button-save-'+smile_panel+' > span').trigger('click');
					jQuery('#button-save-'+smile_panel).trigger('click');
					var module = jQuery(".customize").data("module");
					section.trigger('click');
					if(cookie) {
						swal("Saved & Published!", "", "success");
					} else {
						swal("Saved & Published!", "Your "+ module+" is live now.", "success");
					}
					setTimeout( function(){
						removeCookie('cp-unsaved-changes');
						if( target == '_blank' )
							window.open(redirectURL);
						else
							window.location = redirectURL;
					}, 500 );
				} else if(cookie) {
					var section = jQuery('.cp-section.active');
					jQuery('#button-save-'+smile_panel+' > span').trigger('click');
					jQuery('#button-save-'+smile_panel).trigger('click');
					section.trigger('click');
					setTimeout( function(){
						removeCookie('cp-unsaved-changes');
						if( target == '_blank' )
							window.open(redirectURL);
						else
							window.location = redirectURL;
					}, 500 );
				}
			} else {
				if(cookie) {
					if( cancelButtonText == "No, Keep it Pause!"  ){
						swal({
							title: "Some changes are not saved yet!",
							text: '<span class="cp-discard-popup" style="position: absolute;top: 0;right: 0;"><i class="connects-icon-cross"></i></span>',
							type: "warning",
							showCancelButton: true,
							confirmButtonColor: "#DD6B55",
							confirmButtonText: "Save it!",
							cancelButtonText: "Close without saving changes",
							closeOnConfirm: false,
							closeOnCancel: false,
							showLoaderOnConfirm: false,
							customClass: 'cp-switch-theme',
							html: true,
						},
						function(isConfirm){
							if( isConfirm ){
								var section = jQuery('.cp-section.active');
								jQuery('#button-save-'+smile_panel+' > span').trigger('click');
								jQuery('#button-save-'+smile_panel).trigger('click');
								section.trigger('click');
								setTimeout( function(){
									removeCookie('cp-unsaved-changes');
									jQuery(".cp-discard-popup").trigger("click");
									if( target == '_blank' )
										window.open(redirectURL);
									else
										window.location = redirectURL;
								}, 500 );
							} else {
								removeCookie('cp-unsaved-changes');
								jQuery(".cp-discard-popup").trigger("click");
								if( target == '_blank' )
									window.open(redirectURL);
								else
									window.location = redirectURL;
							}
						});
					} else {
						removeCookie('cp-unsaved-changes');
						jQuery(".cp-discard-popup").trigger("click");
						if( target == '_blank' )
							window.open(redirectURL);
						else
							window.location = redirectURL;
					}
				} else {

					jQuery(".cp-discard-popup").trigger("click");
					removeCookie('cp-unsaved-changes');
					if( target == '_blank' ){
						window.open(redirectURL);
					}else{
						window.location = redirectURL;
					}
				}
			}

		});

		jQuery(".cp-switch-theme").prev().css( "background-color", "rgba(0,0,0,.9)" );
		jQuery("body").on("click", ".cp-switch-theme .cp-discard-popup", function(e){
			e.preventDefault();
			jQuery(".sweet-overlay, .sweet-alert").fadeOut('slow').remove();
		});

		e.preventDefault();
		return false;
	});


	collapse.click(function(e){
		e.preventDefault();
		e.stopPropagation();
		var wrapper 		= jQuery(this).parents('.customizer-wrapper');
		var design_area 	= wrapper.find( ".design-form" );
		var content_area 	= wrapper.find( ".design-content" );
		var footer_actions	= wrapper.find( ".customize-footer-actions" );
		var section = jQuery('.cp-section.active');
		wrapper.toggleClass( "collapsed" );
		footer_actions.toggleClass( "collapsed" );
		if( !footer_actions.hasClass('collapsed') ){
			section.trigger('click');
		}
	});

	rename_btn.click(function(e){
		e.preventDefault();
		var wrapper 		= jQuery(this).parents('td').find('.smile-slug');
		var style_name 		= jQuery("#rename-title").val();
		var style_id 		= jQuery(this).data('style');
		var option			= jQuery(this).data('option');
		var action 			= 'smile_rename_style';
		var data 			= {action:action,style_name:style_name,style_id:style_id,option:option};
		var msg 			= jQuery('.message');
		var update 			= jQuery(this).parents("td").find(".button");
		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'JSON',
			data: data,
			success:function(result){
				if(result.message == 'renamed'){
					update.hide();
					wrapper.html('<span class="style-title" href="#" title="Click to rename">'+style_name+'</span>');
					swal("Renamed!", "Style has been renamed.", "success");
					setTimeout(function(){
						window.location = window.location;
					},500);
				}
			}
		});
	});
	calcel_btn.click(function(e){
		var wrapper 		= jQuery(this).parents('td').find('.smile-slug');
		var style_name 		= jQuery(this).data('style');
		var update 			= jQuery(this).parents("td").find(".button");
		wrapper.html('<span class="style-title" href="#" title="Click to rename">'+style_name+'</span>');
		update.hide();
		jQuery.each(jQuery(this).parents('tbody').find('tr'),function(){jQuery(this).removeClass('inline-editing')});
	});
	copy_btn.click(function(e){
		e.preventDefault();
		var style_id 	= jQuery(this).data('style');
		var action 		= 'smile_duplicate_style';
		var option		= jQuery(this).data('option');
		var module		= jQuery(this).data('module');
		var variant_id	= jQuery(this).data('variant-style');
		var stylescreen = jQuery(this).data('stylescreen');
		var data 		= {action:action,module:module,style_id:style_id,variant_id:variant_id,option:option,stylescreen: stylescreen};
		var msg 		= jQuery('.message');
		var module 		= jQuery(this).find(".action-tooltip").html().replace("Duplicate","");
		module = jQuery.trim(module);
		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'JSON',
			data: data,
			success:function(result){
				if(result.message == 'copied'){
					swal({
						title: "Duplicated!",
						text: module + " you have selected has been duplicated.",
						type: "success",
						timer: 2000,
						showConfirmButton: false
					});
					setTimeout(function(){
						window.location = window.location;
					},500);
				}
			}
		});
	});

	delete_btn.click(function(e){
		e.preventDefault();
		var $this = jQuery(this);
		var module = jQuery(this).find(".action-tooltip").html().replace("Delete","");
		module = jQuery.trim(module);
		swal({
			title: "Are you sure?",
			text: "You will not be able to recover this "+module+"!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			cancelButtonText: "No, cancel it!",
			closeOnConfirm: false,
			closeOnCancel: true,
			showLoaderOnConfirm: true
		},
		function(isConfirm){
			if (isConfirm) {
				jQuery(document).trigger('deleteStyle',[$this,true]);
			}
		});
	});

	jQuery(document).on("deleteStyle", function(e,$this,$reload){
		var do_delete = true;
		if(do_delete){
			var style_id 	= $this.data('style');
			var action 		= 'smile_delete_style';
			var option		= $this.data('option');
			var variant_option = $this.data('variantoption');
			var deleteMethod = $this.data('delete');
			var data 		= {action:action,style_id:style_id,option:option,variant_option:variant_option,deleteMethod:deleteMethod };
			var msg 		= jQuery('.message');
			jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType: 'JSON',
				data: data,
				success:function(result){
					if(result.message == 'Deleted'){
						swal({
							title: "Deleted!",
							text: "Style you have selected has been deleted.",
							type: "success",
							timer: 2000,
							showConfirmButton: false
						});
						if( $reload ) {
							setTimeout(function(){
								window.location = window.location;
							},500);
						}
					}
				}
			});
		}
	});

	btn.each(function(index,value){
		var style = jQuery(this).data('style');
		jQuery( "#accordion-"+style ).accordion({
			closeAny: true
		});
	});

	btn.click(function(e){

		e.preventDefault();
		e.stopPropagation();
		var view = jQuery(this).data('view');
		var style_name = jQuery("#style-title").val();
		if( view == "new" ){
			var url = jQuery(this).attr('href');
			if( style_name !== "" ){
				var variantStyle = jQuery.cpGetUrlVar('variant-style');
				if( typeof variantStyle !== 'undefined' ) {
					url += '&style-name='+style_name;
				}
				window.location = url;
			} else {
				jQuery("#style-title").addClass('smile-new-list-required');
				jQuery("#style-title").focus();
			}
			return false;
		} else {
			e.preventDefault();
		}

		if( style_name !== "" ){
			var style = jQuery(this).data('style');
			smile_panel = style;

			jQuery(".theme").removeClass('active');
			jQuery(this).parents(".theme").addClass('active');

			var ID 			= jQuery(this).data('id');
			data_id 		= jQuery(this).data('id');
			var style_id 	= jQuery('#style-title');
			var new_style 	= jQuery('#form-'+ID+' #new_style');
			var $val = style_id.val();
			new_style.attr('value',$val);
			var container 	= jQuery('.'+style);
			var frame 		= container.find('.design-content');
			var design_area	= frame.find('.live-design-area');
			var frame_url 	= frame.data('iframe-url');
			var demo_id 	= frame.data('demo-id');
			var module		= frame.data('module');
			var cls			= frame.data('class');
			var js_url 		= frame.data('js-url');
			container.fadeIn('fast');
			container.addClass('active-customizer');
			jQuery('html').css('overflow','hidden');
			var x = '';
			var data = '';
			var	data = {action:'framework_update_preview_data',demo_id:demo_id,module:module,cls:cls};
			var save_btn = jQuery('#button-save-'+smile_panel);

			jQuery.ajax({
				url: ajaxurl,
				data: data,
				type: 'POST',
				dataType: 'HTML',
				data: data,
				success:function(result){

					var iframe = '<div class="design-area-loading"><div class="smile-absolute-loader" style="visibility: visible;"> <div class="smile-loader"><div class="smile-loading-bar"></div><div class="smile-loading-bar"></div><div class="smile-loading-bar"></div><div class="smile-loading-bar"></div></div></div></div><iframe id="smile_design_iframe" src="'+frame_url+'" data-js="'+js_url+'" onload="hideLoading(data_id);">'+result+'</iframe>';
					design_area.html(iframe);
					jQuery(document).trigger("smile_iframe_after_load");
				}
			});

			jQuery(document).trigger("smile_panel_loaded",[smile_panel,ID]);

			//	Handling dependencies
			smileHandleDependencies();
		} else {
			jQuery("#style-title").addClass('smile-new-list-required');
			jQuery("#style-title").focus();
		}
	});

	changeStatus.unbind().click(function(e){
		e.preventDefault();
		var $this		= jQuery(this);
		var style_id	= $this.attr('data-style-id');
		var status		= $this.attr('data-live');
		var option		= $this.attr('data-option');
		var variant		= $this.attr('data-variant');
		var action		= 'smile_update_status';
		var sch_container = jQuery(".cp-schedular-overlay");
		var sch			= $this.attr('data-schedule');
		var obj 		= jQuery(this).parents("td").find('span.cp-status');
		if( typeof sch !== "undefined" && sch == 1 ){
			sch_container.fadeIn();
			e.stopPropagation();
			jQuery(".cp-schedule-btn").unbind().click(function(event){
				event.preventDefault();
				var cp_start = jQuery(".cp_start").val();
				var cp_end = jQuery(".cp_end").val();
				if( cp_start !== "" && cp_end !== ""){
					var data = {action:action,status:status,style_id:style_id,option:option,variant:variant,cp_start:cp_start,cp_end:cp_end};
					jQuery(document).trigger("changeStatus",[$this,data,obj,status]);
				} else {
					if( cp_start == "" )
						jQuery(".cp_start").focus();
					else
						jQuery(".cp_end").focus();
				}
			});
		} else {
			var data		= {action:action,status:status,style_id:style_id,option:option,variant:variant};
			var obj = jQuery(this).parents("td").find('span.cp-status');
			jQuery(document).trigger("changeStatus",[$this,data,obj,status]);
		}
	});

	jQuery(document).on("changeStatus",function(e,$this,data,obj,status){
		var msg 		= jQuery('.message');
		var old_status	= obj.attr('data-live');
		jQuery(obj).addClass('cp-status-loader');

		jQuery.ajax({
			url: ajaxurl,
			data: data,
			type: 'POST',
			dataType: 'JSON',
			success: function(result){
				if( result.message == "status changed"){
					jQuery(document).trigger("dismissPopup");
					jQuery(obj).removeClass('cp-status-loader');
					jQuery(obj).css('color','rgb(46, 204, 113)');
					if( status == 0 ){
						jQuery(obj).html('<i class="connects-icon-pause"></i><span>Pause</span>');
						jQuery(obj).attr('data-live',0);
					} else if( status == 1 ) {
						jQuery(obj).html('<i class="connects-icon-play"></i><span>Live</span>');
						jQuery(obj).attr('data-live',1);
					} else {
						jQuery(obj).html('<i class="connects-icon-clock"></i><span>Scheduled</span>');
						jQuery(obj).attr('data-live',2);
					}

					if( old_status == 0 ) {
						$this.html('<i class="connects-icon-pause"></i><span>Pause</span>');
						$this.attr('data-live',0);
						$this.removeAttr('data-schedule');
					} else if( old_status == 1 ) {
						$this.html('<i class="connects-icon-play"></i><span>Live</span>');
						$this.attr('data-live',1);
						$this.removeAttr('data-schedule');
					} else {
						$this.html('<i class="connects-icon-clock"></i><span>Scheduled</span>');
						$this.attr('data-live',2);
						$this.attr('data-schedule',1);
					}

					setTimeout(function(){
						jQuery(obj).removeAttr('style');
					},2000);
				}
				return false;
			}
		});
	});

	jQuery("span.change-status").click(function(e){
		e.stopPropagation();
		e.preventDefault();
	});

	jQuery(".cp-scheduler-close").on("click",function(e){
		jQuery(document).trigger("dismissPopup");
	});

	jQuery(".cp-scheduler-popup").on("click", function(e){
		e.preventDefault();
		e.stopPropagation();
	});

	jQuery(document).on("dismissPopup",function(e){
		var sch_container = jQuery(".cp-schedular-overlay");
		jQuery('span.cp-status').removeClass('cp-status-loader');
		sch_container.fadeOut();
	});

	jQuery(".cp-resp-bar-icon").click(function(){
		jQuery(".cp-resp-bar-icon").removeClass('cp-resp-active');
		jQuery(this).addClass('cp-resp-active');
		var cls = jQuery(this).data('res-class');
		jQuery('.live-design-area').attr('class','live-design-area '+cls);
	});

	jQuery(".cp-section").first().trigger('click');
	jQuery(".cp-section").click(function(e){
		e.preventDefault();
		var collapse 		= jQuery('.customizer-collapse');
		var wrapper 		= collapse.parents('.customizer-wrapper');
		var design_area 	= wrapper.find( ".design-form" );
		var content_area 	= wrapper.find( ".design-content" );
		var footer_actions	= wrapper.find( ".customize-footer-actions" );
		var target = jQuery(this).data('section-id');
		if( wrapper.hasClass( "collapsed" ) ) {
			wrapper.toggleClass( "collapsed" );
			footer_actions.toggleClass( "collapsed" );
		}

		var c = jQuery("#"+target).find(".accordion-frame").length;
		var title_area = jQuery("span.theme-name.site-title");
		var theme_title = jQuery(this).find('.has-tip').data('original-title');
		title_area.html(theme_title);
		if( c == 1 ){
			var target_content = jQuery("#"+target).find(".accordion-frame .content");
			var target_link = jQuery("#"+target).find(".accordion-frame > a");
			target_content.show();
			target_link.remove();
		}
	});

	//close all panel on click of cp-section
	 var countclick = 0;
	jQuery(".cp-section").click(function(e){
		e.preventDefault();
		countclick++;
		jQuery( ".accordion-frame" ).find("a.heading").each(function( index ) {
		  if(!jQuery( this ).hasClass('collapsed')){
		  	if(countclick>1){
			  	jQuery(this).addClass('collapsed');
			  	jQuery(this).parents('.accordion-frame').find(".content").css({"display" :"none"});
			  	jQuery(".ps-scrollbar-y-rail ").find(".ps-scrollbar-y").css({"top":"0px" ,"height" :"0px"});
			  	jQuery(".ps-scrollbar-y-rail ").css({"top":"0px" ,"height" :"0px"});
		  	}
		  }
		});

	});

	jQuery(".cp-customize-section").click( function(e) {
		var id = jQuery(this).data('section-id');
		jQuery("#"+id+" .content").show();
	});

	jQuery(".accordion-frame a.heading").click( function(){
		var $this = jQuery(this);//.parent(".accordion-frame");
		setTimeout( function() {
			var top = $this.position().top;
			jQuery(".design-form").animate({
				scrollTop: top
			}, 1000);
			jQuery(".ps-scrollbar-y-rail ").find(".ps-scrollbar-y").css({"top":"0px" ,"height" :"0px"});
			jQuery(".ps-scrollbar-y-rail ").css({"top":"0px" ,"height" :"0px"});
		},400);
	});

	jQuery(window).bind('keydown', function(event) {
		var section = jQuery('.cp-section.active');
		var smile_panel = jQuery(".customize").data('style');
		if (event.which == 83 && (event.ctrlKey||event.metaKey)|| (event.which == 18)) {
			switch (String.fromCharCode(event.which).toLowerCase()) {
				case 's':
					event.preventDefault();
					countclick = 0;
					jQuery('#button-save-'+smile_panel+' > span').trigger('click');
					section.trigger('click');
				break;
			}
		}
		return true;
	});

});

// A function to handle sending messages.
function smileSendMessage(e) {

	// Prevent any default browser behavior.
	e.preventDefault();
	e.stopPropagation();

	var save_btn = jQuery('#button-save-'+smile_panel);
	save_btn.addClass('cp-save-loader');

	var form_id 		= 'form-'+jQuery(this).data('style');
	var url_string 		= jQuery("#"+form_id).serialize().replace(/\+/g,'%20');
	var action			= jQuery("#"+form_id).data('action');
	var data 			= JSON.stringify(url_string);
	var container 		= jQuery(this).parents('.customizer-wrapper');
	var frame 			= container.find('.design-content');
	var frame_url 		= frame.data('iframe-url');
	var new_frame_url 	= frame_url+'&'+url_string;
	var receiver 		= document.getElementById('smile_design_iframe').contentWindow;

	// Send the data
	receiver.postMessage(data, frame_url);

	jQuery.ajax({
		url:ajaxurl,
		data:{action:action,style_settings:url_string},
		type:'POST',
		dataType:'HTML',
		success:function(result){
			var new_style 	= jQuery('#new_style');
			var style_id 	= jQuery('#style-title');
			var value 		= style_id.val();
			if(value == ""){
				new_style.val(result);
				style_id.val(result);
				style_id.attr('disabled','true');
			}
			save_btn.removeClass('cp-save-loader');
			var cookie = getCookie("cp-unsaved-changes");
			if(cookie) {
				swal({
					title: "Settings Saved!",
					text: "",
					type: "success",
					timer: 2000,
					showConfirmButton: false
				});
			}
			// remove unsaved cookie
			removeCookie('cp-unsaved-changes');
		},
		error:function(err){
			console.log(err);
		}
	});

}

window.onload = function() {
	var theme		= jQuery.cpGetUrlVar('theme');
	var smile_panel = jQuery(".customize").data('style');
	var btn 		= jQuery('.customize');
	if( typeof theme !== "undefined" ){
		setTimeout(function(){
			if( !btn.hasClass("variant-test") ) {
				btn.trigger('click');
			}
		},500);
	}

	// A function to handle sending messages for live preview.
	function smileLiveData(e) {

		// Handle dependencies
		smileHandleDependencies();

		// Prevent any default browser behavior.
		e.preventDefault();

		var save_btn = jQuery('#button-save-'+smile_panel);

		var el_id 			= jQuery(this).parents('form').attr('id');
		var url_string 		= jQuery("#"+el_id).serialize().replace(/\+/g,'%20');
		var data 			= JSON.stringify(url_string);
		var container 		= jQuery(this).parents('.customizer-wrapper');
		var frame 			= container.find('.design-content');
		var frame_url 		= frame.data('iframe-url');
		var new_frame_url 	= frame_url+'&'+url_string;
		var iframe_container = document.getElementById('smile_design_iframe');
		if( iframe_container ) {

			var iframe 			= iframe_container.contentWindow;
			// Send the data
			iframe.postMessage(data, frame_url);

			// create cookie for unsaved changes
			var cookieName = 'cp-unsaved-changes';
			createCookie(cookieName,true,1);
		}
	}

	jQuery(document).on('smile_panel_loaded',function(e,smile_panel,id){
		// Add an event listener that will execute the sendMessage() function
		// when the send button is clicked.
		var save_btn = document.getElementById('button-save-'+smile_panel);
		var form_id = 'form-'+id;

		var elements = jQuery("#"+form_id+" .smile-input");
		jQuery.each(elements,function(i,v){
			jQuery(this).trigger('change');
			jQuery(this).on('change',smileLiveData);
			jQuery(this).on('keyup',smileLiveData);
		});

		if( save_btn )
			save_btn.addEventListener('click', smileSendMessage);
	});

	jQuery(document).on('click','.cp-vertical-nav a:not(".cp-save")',function(){
		var href = jQuery(this).attr('href');
		jQuery('.cp-vertical-nav a').removeClass('active');
		jQuery(this).addClass('active');

		jQuery('.cp-customizer-tab').hide();
		jQuery(href).fadeIn(300);
	});
}
jQuery(document).ready(function(){

	removeCookie('cp-unsaved-changes');
	var a = jQuery("a.mailer-stage");
	var c = jQuery("div.stage-content");
	var i = jQuery("a.mailer-stage-internal");

	a.click(function(e){
		e.preventDefault();
		var id = jQuery(this).attr('href');
		a.removeClass('active-stage');
		c.removeClass('active-stage');
		jQuery(this).addClass('active-stage');
		jQuery(id).addClass('active-stage');
	});

	i.click(function(e){
		e.preventDefault();
		var id = jQuery(this).attr('href');
		var cls = id.replace("#","");
		a.removeClass('active-stage');
		c.removeClass('active-stage');
		jQuery('.'+cls+' > a').addClass('active-stage');
		jQuery(id).addClass('active-stage');
	});

	jQuery('.has-tip').frosty({
        offset: 10
    });

	jQuery("a[href='#top']").click(function() {
	  jQuery("html, body").animate({ scrollTop: 0 }, "1000");
	  return false;
	});

	jQuery(".cp-action-link.customize").click(function() {
		var redirectLink = jQuery(this).parent().attr('href');
		window.location.href = redirectLink;
	});

	jQuery(".cp-style-import-link").click(function(e){

        e.preventDefault();
        e.stopPropagation();

        var actionLink = jQuery(this).find(".cp-action-link");
        var item_box  = jQuery(this).closest('.cp-style-item-box');
        var item_text = jQuery(this).find('.cp-action-text');

        item_box.addClass('cp-import-processing');

        item_text.html("Importing ...");
        item_text.css( 'color', "#008000" );

        actionLink.addClass('cp-save-loader');

        var module = jQuery(this).data('module');
        var preset = jQuery(this).data('preset');

        var redirect_url = jQuery(this).data('href');

        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'cp_import_presets',
                module: module,
                preset: preset
            },
        })
        .done(function(e) {

            var result = JSON.parse(e);

            if( result.success != true ) {
                swal( "Oops...", "Something went wrong!", "error" );
                return false;
            }

            actionLink.removeClass('cp-save-loader');
            item_box.removeClass('cp-import-processing');

            window.location = redirect_url;

        })
        .fail(function(e) {
            console.log(e);
            console.log("error");
        });

    });

});

jQuery(document).on("customize_loaded", function(){
	var smile_panel = jQuery(".customize").data('style');
	jQuery('#button-save-'+smile_panel).trigger('click');
});


jQuery(document).on( 'click', '.cp-behavior-settings' , function(){
	var setttings = jQuery(this).data('settings');
	swal({
		title: "",
		text: setttings,
		animation: "slide-from-top",
		html: true,
		customClass: 'cp-behavior-alert',
		allowEscapeKey: true,
		allowOutsideClick: true
	});
});

jQuery(document).on( 'click', '.cp-reset-analytics' , function(e){
	e.preventDefault();
	var $this = jQuery(this);
	swal({
		title: "Are you sure?",
		text: "This action will delete impression & conversion count of your style. You will be not able to recover this data.",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes, reset it!",
		cancelButtonText: "No, cancel it!",
		closeOnConfirm: false,
		closeOnCancel: true,
		showLoaderOnConfirm: true
	},
	function(isConfirm){
		if (isConfirm) {
			jQuery(document).trigger('resetAnalytics',[$this,true]);
		}
	});
});


//prevent action on escape key
jQuery(document).keyup(function(e){

     if (e.keyCode == 27) {
     	var styleView = jQuery.cpGetUrlVar('style-view');
     	if( styleView == 'edit' ) {
     		e.preventDefault();
     		var cookieName = 'cp-unsaved-changes';
			var cokkie = createCookie(cookieName,true,1);
     		window.location='#';
     	}
     }
});

jQuery(document).on("resetAnalytics", function(e,$this,$reload){
	var style_id 	= $this.data('style');
	var action 		= 'cp_reset_analytics_action';
	var data 		= {action:action,style_id:style_id};

	jQuery.ajax({
		url: ajaxurl,
		type: 'POST',
		data: data,
		success:function(result){
			if( result == 'reset' ){
				swal({
					title: "Success!",
					text: "Analytics data for selected style has been reset.",
					type: "success",
					timer: 2000,
					showConfirmButton: false
				});
				if( $reload ) {
					setTimeout(function(){
						window.location = window.location;
					},500);
				}
			}
		}
	});
});

//dependency for jugaad style layout
jQuery(document).on("change_radio_image", function(e,$this){

	var this_class = $this.find('input.smile-radio-image').parents(".panel-jugaad");
	if(this_class.length > 0){
		var modal_image_style = this_class.find(".modal_image").parents(".smile-element-container").css("display");
		if(modal_image_style == 'none'){
			this_class.find(".cp-media-sizes").addClass('hide-cp-media-sizes');
		} else {
			this_class.find(".cp-media-sizes").removeClass('hide-cp-media-sizes');
		}
	}
});

jQuery('a[data-section-id="responsive-sect"]').click(function(e){
		e.preventDefault();
		var $this = jQuery(this);
		jQuery(document).trigger('hide_images_on_mobile',[$this,true]);
});

jQuery('.close_btn_duration ').keydown(function(e){	
        var kCode = (e.which || e.keyCode)
        if(kCode == 190 || kCode == 110) e.preventDefault();
        if(e.which === 86 && (e.ctrlKey || e.metaKey)) e.preventDefault();
    })