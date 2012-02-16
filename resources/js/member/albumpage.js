$(function(){
	$.fn.initAlbumPage = function()
	{
		$(this).each(function(){		
			var album = $(this);
			var album_id = album.data('albumid');
			
			var album_photos_container = $('#album-photos');
			
			album_photos_container.sortable({
				placeholder: "place",
				handle: 'a.move',
				opacity: 0.5,
				update:function(){
					$.post(_member_url + '/photo/sort/album/' + album_id, album_photos_container.sortable('serialize'));
				}
			});
			album_photos_container.disableSelection();
			
			$('#description-state').change(function(){
				var select = $(this);
				$.post(_member_url + '/album/toggle/albumId/' + album_id, {state:select.val()}, function(data){
					if (data.success) {
						
						select.parents('.description').effect("highlight", {}, 500);
						
						
					} else {
						$._alert(data.msg)
					}
				},'json');
			});
			
			album.find('a.del').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				$._confirm('Are you sure?', function(confirmed){
					if (confirmed) {
						$.post(link.attr('href'), function(){
							link.parents('li').fadeOut('fast', function(){
								$(this).remove();
							})
						});
					}
				});
			});
			
			album.find('a.move').live('click', function(e){
				e.preventDefault();
			});
			
			album.find('a.setcover').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				$.post(_member_url + '/album/setCover/' + album_id, {
					photo:link.attr('rel')
				}, function(data){
					if (data.success) {
						link.parents('li').addClass('cover').siblings('.cover').removeClass('cover');
					}
				}, 'json');
			});
			
			function _init_uploader()
			{
				$('#photo-upload-container').pluploadQueue({
					runtimes : 'gears,html5,html4,browserplus',
					url:_member_url + '/photo/upload/album/' + album_id,
					max_file_size : '10mb',
					filters : [
						{title : "Image files", extensions : "jpg,gif,png"},
					],
					init : {
						FileUploaded : function(up, file, response){
							var data = $.parseJSON(response.response);

							if (data.success) {
								album_photos_container.append(data.html);

								if(this.total.queued == 0) {
									$.fancybox.close();
									
									$._flash('All Files Uploaded.', {type:'success'});
								}

							} else {
								$._alert(data.msg);
							}
						}
					}
				});
			}
			
			function _reset_uploader()
			{
				var container = $('#photo-upload-container')
				container.pluploadQueue().destroy();
				container.html('');
			}
			
			$('#album-upload-photos-button').fancybox({
				beforeLoad:function() {
					_init_uploader();
				},
				afterClose:function(){
					_reset_uploader();
				}
			});
			
			$('#album-smugmug-import-button').fancybox({
				fitToView:true,
				autoSize:false,
				minWidth:720,
				minHeight:400,
				margin:40,
				padding:0
			});
			
			$('#album-zenfolio-import-button').fancybox({
				fitToView:true,
				autoSize:false,
				minWidth:720,
				minHeight:400,
				margin:40,
				padding:0
			});
			
			$('#album-pass-import-button').fancybox({
				fitToView:true,
				autoSize:false,
				minWidth:720,
				minHeight:400,
				margin:40,
				padding:0
			});
			
			$('#album-slideshow-button').click(function(e){
				e.preventDefault();
				var images = [];
				
				$('#album-photos').find('figure').each(function(){
					images.push({
						href:$(this).data('src')
					});
				});
				$.fancybox.open(images, {
					type:'image'
				});
			});
			
			// smugmug
			var smugmug_container = $('#photo-import-smugmug-container');
			
			var smugmug_data = smugmug_container.find('.data');
			var smugmug_loading = smugmug_container.find('.loading');
			var smugmug_import = smugmug_container.find('.import');
			
			smugmug_container.find('.smugmug-import-selected').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				var chain = new Array;
				
				var buttons = smugmug_container.find('.buttons');
				var importing = smugmug_container.find('.importing');
				
				buttons.hide();
				importing.show();
				
				link.parents('form').find('input[type=checkbox]:checked').each(function(){
					var checkbox = $(this);
					chain.push($.post(_member_url + '/smugmug/importPhoto', {
						smugmug:checkbox.val(),
						album_id:album_id
					}, function(data){
						checkbox.attr('checked', false);
						if (data.success) {
							album_photos_container.append(data.html);
						} else {
							$._flash(data.msg, {type:'error'});
						}
					}, 'json'));
				});
				
				$.when.apply(this, chain).done(function(){
					$.fancybox.close();
					buttons.show();
					importing.hide();
					smugmug_import.html('');
					smugmug_container.find('select').val('');
					$.uniform.update(); 
					$._flash('Photos have been successfully imported.', {type:'success'});
				});
			});
			
			smugmug_container.find('.smugmug-check-all, .smugmug-check-none, .smugmug-check-invert').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				var checkboxes = link.parents('form').find('input:checkbox');
				if (link.hasClass('smugmug-check-all') || link.hasClass('smugmug-check-none')) {
					checkboxes.attr('checked', link.hasClass('smugmug-check-all'));
				} else if (link.hasClass('smugmug-check-invert')) {
					checkboxes.each(function(){
						$(this).attr('checked', !$(this).attr('checked'));
					})
				}
				$.uniform.update(); 
			});
			
			smugmug_container.find('input:button').click(function(e){
				e.preventDefault();
				var smugmug = smugmug_container.find('select').val();
				if (!smugmug) {
					return;
				}
				smugmug_data.hide();
				smugmug_loading.show();
				smugmug_import.html('');
				$.post(_member_url + '/smugmug/album/', {smugmug:smugmug}, function(data){
					smugmug_loading.hide();
					smugmug_data.show();
					if (data.success) {
						smugmug_import.html(data.html);
						smugmug_import.find('input:checkbox').uniform();
					} else {
						$._alert(data.msg);
					}
				}, 'json');
			});
			
			// zenfolio
			var zenfolio_container = $('#photo-import-zenfolio-container');

			var zenfolio_data = zenfolio_container.find('.data');
			var zenfolio_loading = zenfolio_container.find('.loading');
			var zenfolio_import = zenfolio_container.find('.import');
			
			zenfolio_container.find('input:button').click(function(e){
				e.preventDefault();
				var album_id = zenfolio_container.find('select').val();
				if (!album_id) {
					return;
				}
				zenfolio_data.hide();
				zenfolio_loading.show();
				zenfolio_import.html('');
				$.post(_member_url + '/zenfolio/album/', {id:album_id}, function(data){
					zenfolio_loading.hide();
					zenfolio_data.show();
					if (data.success) {
						zenfolio_import.html(data.html);
						zenfolio_import.find('input:checkbox').uniform();
					} else {
						$._alert(data.msg);
					}
				}, 'json');
			});
			
			zenfolio_container.find('.zenfolio-check-all, .zenfolio-check-none, .zenfolio-check-invert').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				var checkboxes = link.parents('form').find('input:checkbox');
				if (link.hasClass('zenfolio-check-all') || link.hasClass('zenfolio-check-none')) {
					checkboxes.attr('checked', link.hasClass('zenfolio-check-all'));
				} else if (link.hasClass('zenfolio-check-invert')) {
					checkboxes.each(function(){
						$(this).attr('checked', !$(this).attr('checked'));
					})
				}
				$.uniform.update(); 
			});
			
			zenfolio_container.find('.zenfolio-import-selected').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				var chain = new Array;
				
				var buttons = zenfolio_container.find('.buttons');
				var importing = zenfolio_container.find('.importing');
				
				buttons.hide();
				importing.show();
				
				link.parents('form').find('input[type=checkbox]:checked').each(function(){
					var checkbox = $(this);
					chain.push($.post(_member_url + '/zenfolio/importPhoto', {
						id:checkbox.val(),
						album_id:album_id
					}, function(data){
						checkbox.attr('checked', false);
						if (data.success) {
							album_photos_container.append(data.html);
						} else {
							$._flash(data.msg, {type:'error'});
						}
					}, 'json'));
				});
				
				$.when.apply(this, chain).done(function(){
					$.fancybox.close();
					buttons.show();
					importing.hide();
					smugmug_import.html('');
					smugmug_container.find('select').val('');
					$.uniform.update(); 
					$._flash('Photos were successfully imported.', {type:'success'});
				});
			});
			
			// pass
			var pass_container = $('#photo-import-pass-container');
			
			var pass_data = pass_container.find('.data');
			var pass_loading = pass_container.find('.loading');
			var pass_import = pass_container.find('.import');
			
			pass_container.find('.pass-import-selected').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				var chain = new Array;
				
				var buttons = pass_container.find('.buttons');
				var importing = pass_container.find('.importing');
				
				buttons.hide();
				importing.show();
				
				link.parents('form').find('input[type=checkbox]:checked').each(function(){
					var checkbox = $(this);
					chain.push($.post(_member_url + '/pass/importPhoto', {
						pass:checkbox.val(),
						album_id:album_id
					}, function(data){
						checkbox.attr('checked', false);
						if (data.success) {
							album_photos_container.append(data.html);
						} else {
							$._flash(data.msg, {type:'error'});
						}
					}, 'json'));
				});
				
				$.when.apply(this, chain).done(function(){
					$.fancybox.close();
					buttons.show();
					importing.hide();
					pass_import.html('');
					pass_container.find('select').val('');
					$.uniform.update(); 
					$._flash('Photos have been successfully imported.', {type:'success'});
				});
			});
			
			pass_container.find('.pass-check-all, .pass-check-none, .pass-check-invert').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				var checkboxes = link.parents('form').find('input:checkbox');
				if (link.hasClass('pass-check-all') || link.hasClass('pass-check-none')) {
					checkboxes.attr('checked', link.hasClass('pass-check-all'));
				} else if (link.hasClass('pass-check-invert')) {
					checkboxes.each(function(){
						$(this).attr('checked', !$(this).attr('checked'));
					})
				}
				$.uniform.update(); 
			});
			
			pass_container.find('input:button').click(function(e){
				e.preventDefault();
				var pass = pass_container.find('select').val();
				if (!pass) {
					return;
				}
				pass_data.hide();
				pass_loading.show();
				pass_import.html('');
				$.post(_member_url + '/pass/album/', {pass:pass}, function(data){
					pass_loading.hide();
					pass_data.show();
					if (data.success) {
						pass_import.html(data.html);
						pass_import.find('input:checkbox').uniform();
					} else {
						$._alert(data.msg);
					}
				}, 'json');
			});
			
			$('#album-order-availability form').ajaxForm({
				beforeSubmit:function(items, frm){
					frm.find('input:submit').btnLoading();
				},
				success:function(data, success, response, frm){
					frm.find('input:submit').btnLoaded();
				}
			});
		});
	}
	
	$('#album').initAlbumPage();
	
})