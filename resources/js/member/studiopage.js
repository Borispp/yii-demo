$(function(){
	$.fn.initStudioPage = function()
	{
		function _init_specials_uploader()
		{
			var specials_loading = $('#studio-specials-container .loading');
			var specials_browse = $('#studio-specials-browse');
			
			var uploader = new plupload.Uploader($.extend(_plupload_settings, {
				url:_member_url + '/studio/uploadSpecials/',
				container:'studio-specials-container',
				browse_button:'studio-specials-browse',
				multi_selection:false
			}));
			
			uploader.bind('Init', _plupload_init);
			uploader.init();
			uploader.bind('Error', function(up, err) {
				specials_loading.hide();
				specials_browse.show();
				alert(err.message + ' Please reload the page and try again.');
				up.refresh();
			});
			uploader.bind('FilesAdded', function(up){
				specials_loading.show();
				specials_browse.hide();
				up.start();
			});

			uploader.bind('FileUploaded', function(up, file, response) {
				specials_loading.hide();
				specials_browse.show();
				var data = $.parseJSON(response.response);
				if (data.success) {
					$('#' + up.settings.container).parent().html(data.html);
				} else {
					alert(data.msg);
				}
			});
			
			return uploader;
		}
		
		$(this).each(function(){
			var studio = $(this);
			
			var specials = $('#studio-specials');
			
			if ($('#studio-specials-container').length) {
				_init_specials_uploader();
			}
			
			specials.find('a.delete').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				if (!confirm('Are you sure?')) {
					return;
				}
				$.post(link.attr('href'), function(data){
					if (data.success) {
						specials.html(data.html);
						_init_specials_uploader();
					} else {
						alert(data.msg);
					}
				}, 'json');
			});
			
			
			
			
		});
	}
	$('#studio').initStudioPage();
})