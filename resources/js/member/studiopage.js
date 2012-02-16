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
				multi_selection:false,
				filters : [
					{title : "Image & PDF files", extensions : "jpg,gif,png,jpeg,pdf"},
				]
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
					container.find('a.image').fancybox();
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
			
			$('#studio-info-form').ajaxForm({
				beforeSubmit:function(items, frm){
					var submit = frm.find('input:submit');
					submit.val(submit.data('loading')).addClass('disabled');
				},
				success:function(data, success, response, frm){
					var submit = frm.find('input:submit');
					submit.val(submit.data('value')).removeClass('disabled');
					$.each(data.attributes, function(field, value){
						$('#Studio_' + field).val(value);
					});
					$._flash(data.msg, {
						'type':data.success ? 'success' : 'error'
					});
				},
				dataType:'json'
			});
			
			specials.find('a.image').fancybox();
			
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
			
			$('#studio-link-bookmark-add-button, #studio-link-custom-add-button').fancybox({
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
			
			
			
			var studio_video = $('#studio-video');
			function _init_video_form()
			{
				$('#studio-video-form').ajaxForm({
					beforeSubmit:function(items, frm){
						var submit = frm.find('input:submit');
						submit.val(submit.data('loading')).addClass('disabled');
					},
					success:function(data, success, response, frm){
						
						if (data.success) {
							studio_video.html(data.html);
						} else {
							var submit = frm.find('input:submit');
							submit.val(submit.data('value')).removeClass('disabled');
							$._alert(data.msg);
						}
					},
					dataType:'json'
				});
			}
			studio_video.find('a.delete').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				$._confirm('Are you sure?', function(confirmed){
					if (confirmed) {
						$.post(link.attr('href'), function(data){
							if (data.success) {
								studio_video.html(data.html);
								_init_video_form();
							} else {
								$._alert(data.msg);
							}
						}, 'json');
					}
				})
			});
			_init_video_form();
			
			var contact_form = $('#studio-contact-form');
			contact_form.find('textarea').maxlength({
				maxCharacters: 100
			}).maxrows();
			contact_form.ajaxForm({
				beforeSubmit:function(items, frm){
					var submit = frm.find('input:submit');
					submit.val(submit.data('loading')).addClass('disabled');
				},
				success:function(data, success, response, frm){
					var submit = frm.find('input:submit');
					submit.val(submit.data('value')).removeClass('disabled');
					$._flash(data.msg, {
						'type':data.success ? 'success' : 'error'
					});
				},
				dataType:'json'
			});
			
			
			var persons_container = studio.find('.persons');
			var links_container = studio.find('.links');
			
			studio.find('a.move').click(function(e){
				e.preventDefault();
			});
			
			persons_container.find('li a.view').fancybox({
				type:'ajax'
			});
			
			persons_container.sortable({
				placeholder: "place",
				handle: 'a.move',
				opacity: 0.5,
				update:function(){
					$.post(_member_url + '/person/sort/', persons_container.sortable('serialize'));
				}
			});
			persons_container.disableSelection();
			
			links_container.each(function(){
				var l_container = $(this);
				
				l_container.sortable({
					placeholder: "place",
					handle: 'a.move',
					opacity: 0.5,
					update:function(){
						$.post(_member_url + '/link/sort' + $.ucfirst(l_container.data('type')), l_container.sortable('serialize'), function(data){
							if (data.success) {
								
							} else {
								$._alert(data.msg);
							}
						}, 'json');
					}
				});
			});
			links_container.disableSelection();
			
			
			studio.find('a.box-help').fancybox();
		});
	}
	$('#studio').initStudioPage();
})