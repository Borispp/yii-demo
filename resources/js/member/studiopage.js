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
			uploader.init();
			uploader.bind('Error', function(up, err) {
				specials_loading.hide();
				specials_browse.show();
				$._alert(err.message + ' Please reload the page and try again.');
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
					var container = $('#' + up.settings.container);
					container.parent().html(data.html);
					container.find('figure a.image').fancybox();
				} else {
					$._alert(data.msg);
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
			
			$('#studio-info-form').ajaxForm();
			
			specials.find('figure a.image').fancybox();
			
			specials.find('a.delete').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				$._confirm('Are you sure?', function(confirmed){
					if (confirmed) {
						$.post(link.attr('href'), function(data){
							if (data.success) {
								specials.html(data.html);
								_init_specials_uploader();
							} else {
								$._alert(data.msg);
							}
						}, 'json');
					}
				});
			});
			
			$('#studio-person-add-button').fancybox({
				type:'ajax',
				autoSize:true,
				padding:0,
				beforeShow:function(){
					$('#studio-add-person-form').find('input:file').uniform();
				}
			});
			
			$('#studio-link-add-button').fancybox({
				type:'ajax',
				autoSize:true,
				padding:0
			});
			
			studio.find('a.del').click(function(e){
				e.preventDefault();
				var link = $(this);
				$._confirm('Are you sure?', function(confirmed){
					if (confirmed) {
						$.post(link.attr('href'), function(data){
							if (data.success) {
								link.parents('li').fadeOut('fast', function(){
									$(this).remove();
								});
							} else {
								$._alert(data.msg);
							}
						}, 'json');
					}
				})
			});
			
			var persons_container = studio.find('.persons');
			var links_container = studio.find('.links');
			
			persons_container.find('li a.view').fancybox({
				type:'ajax'
			});
			
			studio.find('a.move').click(function(e){
				e.preventDefault();
			})
			
			persons_container.sortable({
				placeholder: "place",
				handle: 'a.move',
				opacity: 0.5,
				update:function(){
					$.post(_member_url + '/person/sort/', persons_container.sortable('serialize'));
				}
			});
			persons_container.disableSelection();
			
			links_container.sortable({
				placeholder: "place",
				handle: 'a.move',
				opacity: 0.5,
				update:function(){
					$.post(_member_url + '/link/sort/', links_container.sortable('serialize'));
				}
			});
			links_container.disableSelection();
			
		});
	}
	$('#studio').initStudioPage();
})