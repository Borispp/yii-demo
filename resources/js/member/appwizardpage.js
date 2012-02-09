$(function(){
	$.fn.ysaAccordion = function(_settings){
		
		var settings = $.extend({
			'title':'h3',
			'step':'section.step',
			'content':'div.box-content',
			'activeClass':'active',
			'opened':function(){},
			'closed':function(){},
			'slideSpeed':400,
			'closeOpened':false,
			'openFirst':true
		}, _settings);
		
		var _toggle = function()
		{
			var title = $(this);
			var step = title.parents(settings.step);
			var content = step.find(settings.content);
			if (step.hasClass(settings.activeClass) && settings.closeOpened) {
				content.slideUp(settings.slideSpeed, function(){
					step.trigger('stepClosed');
				});
			} else {
				if (step.hasClass(settings.activeClass)) {
					return;
				}
				step.addClass(settings.activeClass)
					.siblings('.' + settings.activeClass).removeClass(settings.activeClass)
					.find(settings.content).slideUp(settings.slideSpeed, function(){
						var step = $(this).parents(settings.step);
						step.trigger('stepClosed');
					});
				content.slideDown(settings.slideSpeed, function(){
					step.trigger('stepOpened');
				});
			}
		}
		$(this).each(function(){
			var $this = $(this);
			
			var $steps = $this.find(settings.step);
			
			$steps.find(settings.title).click(_toggle);
			
			$steps.bind('stepOpened', settings.opened);
			$steps.bind('stepClosed', settings.closed);
			
			if (settings.openFirst) {
				$steps.filter(':first').find(settings.title).trigger('click');
			}
			
		})
	}
	
	$.fn.initAppWizardPage = function() {
		
		function _init_uploader(img) {
			var container = $('#wzd-' + img + '-upload-container');
			var browse = $('#wzd-' + img + '-upload-browse')
			var loading = container.find('.loading');

			var uploader = new plupload.Uploader($.extend(_plupload_settings, {
				url:_member_url + '/application/upload/image/' + img,
				container:'wzd-' + img + '-upload-container',
				browse_button:'wzd-' + img + '-upload-browse',
				multi_selection:false
			}));
			uploader.init();
			uploader.bind('Error', function(up, err) {
				loading.hide();
				browse.show();
				$._alert(err.message + ' Please reload the page and try again.');
				up.refresh();
			});
			uploader.bind('FilesAdded', function(up){
				loading.show();
				browse.hide();
				up.start();
			});

			uploader.bind('FileUploaded', function(up, file, response) {
				loading.hide();
				browse.show();
				var data = $.parseJSON(response.response);
				if (data.success) {
					var part = $('#' + up.settings.container).parents('section.part');
					part.find('.value-image').html(data.html);
					part.find('a.fancybox').fancybox();
					browse.text('Change');
					
					var imageRadio = part.find('ul.switcher input[value=image]');
					
					if (imageRadio.length) {
						imageRadio.attr('checked', true);
						$.uniform.update(); 
					}
					
					if (part.hasClass('error')) {
						part.removeClass('error');
					}
					
				} else {
					$._alert(data.msg);
				}
			});

			return uploader;
		}
		
		
		$(this).each(function(){
			var page = $(this);

			
			page.find('.upload .container a').each(function(){
				_init_uploader($(this).attr('rel'));
			});
			
			page.find('a.delete').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				$._confirm('Are you sure?', function(confirmed){
					if (confirmed) {
						$.post(_member_url + '/application/delete/image/' + link.attr('rel'), function(data){
							if (data.success) {
								var part = link.parents('section.part');
								part.find('div.value-image').html('');
								part.find('.upload a.btn').text('Upload');
							} else {
								$._alert(data.msg);
							}
						}, 'json');
					}
				});
			});
			
			var accordion = $('#wizard-accordion');
			var breadcrumbs = $('#wizard-breadcrumbs');
			
			accordion.ysaAccordion({
				opened:function(){
					var cl = $(this).attr('id').replace('wizard-content-', '');
					breadcrumbs.find('li.' + cl).addClass('active').siblings('.active').removeClass('active');
				},
				closed:function(){}
			});
			
			
			breadcrumbs.find('li a').click(function(e){
				e.preventDefault();
				var link = $(this);
				if (link.hasClass('active')) {
					return;
				}
				var cl = link.parent().attr('class');
				$('#wizard-content-' + cl).find('h3').trigger('click');
				
			})
			
			page.find('form').ajaxForm({
				type:'post',
				dateType:'json',
				clearForm:false,
				resetForm:false,
				beforeSubmit:function(){
					page.find('section.part.error').removeClass('error');
					page.find('section.step h3.error').removeClass('error');
				},
				success:function(data){
					data = $.parseJSON(data);
					if (data.success) {
						if (data.redirectUrl) {
							window.location.href = data.redirectUrl;
						} else {
							accordion.find('.step.active').next().find('h3').trigger('click');
						}
					} else {
						if (data.msg) {
							$._alert(data.msg)
						}
						if (data.fields) {
							$.each(data.fields, function(opt, label) {
								$('#wizard-box-' + opt).addClass('error')
									.parents('section.step').find('.box-title h3').addClass('error');
							});
						}
					}
				}
			});
			
			page.find('a.fancybox').fancybox({
				
			});
			
			// style selector
			$('#logo-step-form .style a.styl').click(function(e){
				e.preventDefault()
				var link = $(this);
				var input = link.siblings('input[type=hidden]');
				input.val(link.data('style'));
				link.addClass('selected').siblings('a').removeClass('selected');
			});
			
			$('#wizard-templates a').click(function(e){
				e.preventDefault();
				var link = $(this);
				$._confirm('Are you sure you want to load default color template?', function(confirmed){
					if (confirmed) {
						window.location.href = link.attr('href');
						return;
					}
				});
			})
			page.find('a.fancybox').fancybox();
		});
	}
	
	$('#app-wizard').initAppWizardPage();
});