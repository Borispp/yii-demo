$(function(){
	
	$.fn.initAppWizardPage = function()
	{
		$(this).each(function(){			
			var page = $(this);
			function _init_uploader(img)
			{
				var container = $('#wzd-' + img + '-upload-container');
				var browse = $('#wzd-' + img + '-upload-browse')
				var loading = container.find('.loading');
				
				var uploader = new plupload.Uploader($.extend(_plupload_settings, {
					url:_member_url + '/application/upload/image/' + img,
					container:'wzd-' + img + '-upload-container',
					browse_button:'wzd-' + img + '-upload-browse',
					multi_selection:false
				}));
				
				uploader.bind('Init', _plupload_init);
				uploader.init();
				uploader.bind('Error', function(up, err) {
					loading.hide();
					browse.show();
					alert(err.message + ' Please reload the page and try again.');
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
						$('#' + up.settings.container).parent().html(data.html);
					} else {
						alert(data.msg);
					}
				});
				
				return uploader;
			}
			
			page.find('.value-image .container a').each(function(){
				var btn = $(this);
				_init_uploader(btn.attr('rel'));
			})
			
			page.find('a.delete').live('click', function(e){
				e.preventDefault();
				if (!confirm('Are you sure?')) {
					return;
				}
				var link = $(this);
				$.post(_member_url + '/application/delete/image/' + link.attr('rel'), function(data){
					if (data.success) {
						link.parents('div.value').html(data.html);
						_init_uploader(data.image);
						
					} else {
						alert(data.msg);
					}
				}, 'json');
			});
			
			page.find('.group').each(function(){
				var group = $(this);
				var input = group.find('input[type=radio]');
				var image_section = group.find('section.image');
				var color_section = group.find('section.color');
				if (input.filter(':checked').val() == 'image') {
					color_section.hide();
				} else {
					image_section.hide();
				}
				input.change(function(){
					image_section.toggle();
					color_section.toggle();
				});
			});
		});
	}
	
	$('#app-wizard').initAppWizardPage();
});