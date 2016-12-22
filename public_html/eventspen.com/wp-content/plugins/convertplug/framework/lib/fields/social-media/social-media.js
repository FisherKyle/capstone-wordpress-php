(function($){
	$(document).ready(function(){

		// on change events
		$(document).on('change','.cp_sm_select_action, .cp_sm_select, .cp_sm_input, .cp_sm_checkbox ', function(){
			refresh_social_media();
		});

		// before update starts
		function refresh_social_media() {

			$('.social-media-wrapper').each(function(i,wrapper){
				var id = $(wrapper).attr('data-id');
				setTimeout(function(){
				 cp_social_social_media(id);
				},100);
			});

		}

		// click new box
		function cp_social_social_media(id) {
			var pre_id = id;
			var id = 'cp-wrapper-'+id;
			var $id = $('#'+id);
			var string = '';

			$id.find('.social-media').each(function(j,box){
				var box_string = '';

				var temp_name = $(box).find('input[name=input_share]').val();
				var temp_label = $(box).find('.cp_sm_select').val();
				var temp_val = (temp_label !== '') ? temp_label : temp_name;
				$(box).find('.accordion-head-label').html(temp_val);

				// order
				box_string += 'order:'+j+'|';

				$(box).find('.cp_sm_select, .cp_sm_input ,.cp_sm_select_action ').each(function(i,input){
					if($(input).hasClass('skip-input')) {
						return;
					}
					var name = $(input).attr('name');
					var value = $(input).val();
					if (value.indexOf(":") >= 0){
						value = encodeURIComponent(value);
					}
					box_string += name+':'+value+'|';
				});

				$(box).find('.cp_sm_checkbox').each(function(i,check){
					if($(check).hasClass('skip-input'))
						return;
					var name = $(check).attr('name');
					if($(check).is(':checked')) {
						var value = 'true';
					}
					else {
						var value = 'false';
					}
					box_string += name+':'+value+'|';
				});
				box_string = box_string.slice(0, -1); // remove | from end of string
				string += box_string+';';
			});
			string = string.slice(0, -1); // remove ; from end of string

			var $input = $('#social-media-input-'+pre_id);
			$input.val(string);
			//$input.trigger('change');
			$(document).trigger('socialMediaUpdated',[string, pre_id]);
		} // cp_social_social_media end

		// click on new box
		$('.social-media-add-new').click(function(){
			var $icon = $(this).find('i');
			$icon.addClass('rotating');
			var box_wrapper = $(this).find('i').parents('.social-media-wrapper:first');
			var uniq = $(box_wrapper).attr('data-id');

			var buildData = {
				'action': 'repeat_social_media',
				'id': uniq
			};

			$.post(ajaxurl, buildData, function(response) {
				console.log(response);
				$icon.removeClass('rotating');
				var result = JSON.parse(response);
				if(result.type === 'undefined') {
					result.log('Incorrect response');
					console.log(response);
					return false;
				}
				if(result.type === 'error') {
					console.log(result.message);
					return false;
				}
				var new_box = $(box_wrapper).find('.social-media-inner').append(result.message);
				$(document).trigger('socialMediaAdded',[new_box]);
				$(document).trigger('refreshSocialDependancy');
			});

		}); // add new click event

		// on click delete box
		$(document).on('click', '.social-media-delete', function(event){
			event.preventDefault();
			var box = $(this).parents('.social-media:first');
			swal({
				title: 'Are you sure?',
				text: 'Do you really want to delete this field?<span class="cp-discard-popup" style="position: absolute;top: 0;right: 0;"><i class="connects-icon-cross"></i></span>',
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: 'Yes, delete it!',
				cancelButtonText: 'No, cancel it!',
				closeOnConfirm: true,
				closeOnCancel: true,
				showLoaderOnConfirm: true,
				customClass: 'cp-confirm-delete-box',
				html: true,
				},
				function(isConfirm){
					if( isConfirm ){
						$(box).slideUp(100);
						setTimeout(function(){
							$(box).remove();
							refresh_social_media();
						},350);
					}
				}
			);
		});

		// on click accordion head toggle
		$(document).on('click', '.toggle-accordion-head', function(){
			var box = $(this).parents('.social-media:first');
			$(box).find('.toggle-accordion-content').slideToggle(250, function() {
				if($(box).hasClass('active')) {
					$(box).removeClass('active');
				}
				else {
					$(box).addClass('active');
				}
			});
		});

		// update dependancy on document ready
		$(document).on('refreshSocialDependancy', function(){
			setTimeout(function(){
				$('select[name=input_type]').each(function(i, select){
					refresh_social_dependancy(select);
				});
				$('select[name=input_action]').each(function(i, select){
					refresh_social_dependancy(select);
				});

				$('input[name=smile_adv_share_opt]').each(function(i, select){
					refresh_social_dependancy(select);
				});

				//	Reinitialize ToolTip
				$('.has-tip').frosty({
			    	offset: 10,
			 	});

				refresh_social_media();

			},150);
		});

		//$(document).trigger('refreshSocialDependancy');

		// update dependancy on input type change
		$(document).on('change', 'select[name=input_type]', function(){
			$(document).trigger('onInputTypeChanged',this);
			refresh_social_dependancy(this);
		});
		$(document).on('change', 'select[name=input_action]', function(){
			$(document).trigger('onInputActionChanged',this);
			refresh_social_dependancy(this);
		});

		$(document).on('change', 'input[name=smile_adv_share_opt]', function(){
			$(document).trigger('onShareUrlChanged',this);
			refresh_social_dependancy(this);
		});

		// custom procedure on input types like hidden, dropdown, placeholder
		function refresh_social_dependancy(select) {

			var html = '';
			var box = $(select).parents('.social-media:first');
			var val = $(select).val();
			$(box).find('.cp_sm_select_action, .cp_sm_select, .cp_sm_input, .cp_sm_checkbox').removeClass('skip-input');

			if(val === 'profile_link') {
				var hidden_dependant_array_to_hide = [
					'input[name=input_share]',
					'input[name=smile_adv_share_opt]'
				];
				var hidden_dependant_array_to_show  = [
					'input[name=profile_link]'
				];

				$.each(hidden_dependant_array_to_hide,function(i,ele){
					$(box).find(ele).parents('.social-media-field').slideUp(100);
					$(box).find(ele).addClass('skip-input'); // skip input value to add to string
				});

				$.each(hidden_dependant_array_to_show,function(i,show_ele){
					$(box).find(show_ele).parents('.social-media-field').slideDown(100);
				});
			}
			else if( val === 'social_sharing' ) {
				var show_url = $(box).find(".smile-switch-input").val();

				var dropdown_dependant_array_to_hide = [
					'input[name=profile_link]',
					'input[name=input_share]'
				];

				if(show_url == 1){
					var dropdown_dependant_array_to_show  = [
					'input[name=input_share]',
					'input[name=smile_adv_share_opt]'
					];
				}else{
					var dropdown_dependant_array_to_show  = [
					'input[name=smile_adv_share_opt]'
					];
				}

				$.each(dropdown_dependant_array_to_hide,function(i,ele){
					$(box).find(ele).parents('.social-media-field').slideUp(100);
					$(box).find(ele).addClass('skip-input'); // skip input value to add to string
				});

				$.each(dropdown_dependant_array_to_show,function(i,show_ele){
					$(box).find(show_ele).parents('.social-media-field').slideDown(100);
				});
			} else if( val == 1 ) {

				var show_url = $(box).find(".smile-switch-input").val();
				var dropdown_dependant_array_to_hide = [
					'input[name=profile_link]'
				];
				var dropdown_dependant_array_to_show  = [
					'input[name=input_share]',
					'input[name=smile_adv_share_opt]'
				];

				$.each(dropdown_dependant_array_to_hide,function(i,ele){
					$(box).find(ele).parents('.social-media-field').slideUp(100);
					$(box).find(ele).addClass('skip-input'); // skip input value to add to string
				});

				$.each(dropdown_dependant_array_to_show,function(i,show_ele){
					$(box).find(show_ele).parents('.social-media-field').slideDown(100);
				});
			} else if( val == 0 ) {

				var show_action = $(box).find(".cp_sm_select_action").val();
				if(show_action == 'profile_link'){
					var dropdown_dependant_array_to_hide = [
					'input[name=input_share]'
				];
				var dropdown_dependant_array_to_show  = [
					'input[name=profile_link]'
				];
				}else{
					var dropdown_dependant_array_to_hide = [
						'input[name=profile_link]',
						'input[name=input_share]'
					];
					var dropdown_dependant_array_to_show  = [
						'input[name=smile_adv_share_opt]'
					];
				}

				$.each(dropdown_dependant_array_to_hide,function(i,ele){
					$(box).find(ele).parents('.social-media-field').slideUp(100);
					$(box).find(ele).addClass('skip-input'); // skip input value to add to string
				});

				$.each(dropdown_dependant_array_to_show,function(i,show_ele){
					$(box).find(show_ele).parents('.social-media-field').slideDown(100);
				});

			}/*else {
				var dependant_array_to_hide = [	];
				var dependant_array_to_show = [
					//'input[name=input_share]',
					//'input[name=profile_link]'
				];


				$.each(dependant_array_to_hide,function(i,ele){
					$(box).find(ele).parents('.social-media-field').slideUp(100);
					$(box).find(ele).addClass('skip-input'); // skip input value to add to string
				});

				$.each(dependant_array_to_show,function(i,show_ele){
					$(box).find(show_ele).parents('.social-media-field').slideDown(100);
				});

			}*/
		} // refresh dependancy

		// sortable script
		$('.social-media-inner').sortable({
			items: '.social-media',
			//handle: '.social-media-handle',
			opacity: 0.5,
			cursor: 'pointer',
			axis: 'y',
			update: function() {
				refresh_social_media();
			}
		}); // sortable script

		$("body").on("click", ".cp-discard-popup", function(e){
			e.preventDefault();
			$(".sweet-overlay, .sweet-alert").fadeOut('slow').remove();
		});

	});
})(jQuery);
