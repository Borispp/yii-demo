$(function(){
	$.fn.initAlbumPage = function()
	{
		$(this).each(function(){
			
			var album = $(this);
			var album_id = album.attr('albumid');
			
			var album_photos_container = $('#album-photos');
			
			album_photos_container.sortable({
				placeholder: "ui-state-highlight",
				opacity: 0.6,
				update:function(){
					$.post(_member_url + '/photo/sort/album/' + album_id, album_photos_container.sortable('serialize'), function(){
						
					});
				}
			});
			album_photos_container.disableSelection();
			
			album.find('a.delete').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				$._confirm('Are you sure?', function(confirmed){
					if (confirmed) {
						$.post(_member_url + '/photo/delete/' + link.attr('rel'), function(){
							link.parents('li').fadeOut('fast', function(){
								$(this).remove();
							})
						});
					}
				});
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
			
			$('#photo-upload-container').plupload({
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
						} else {
							$._alert(data.msg);
						}
					}
				}
			});
			
			// init uploader
//			var uploader = new plupload.Uploader($.extend(_plupload_settings, {url:_member_url + '/photo/upload/album/' + album_id}));
//			uploader.init();
//			uploader.bind('FilesAdded', _plupload_files_added);
//			uploader.bind('UploadProgress', _plupload_upload_progress);
//			uploader.bind('Error', _plupload_error_handler);
//			uploader.bind('FileUploaded', function(up, file, response) {
//				$('#' + file.id + " b").html("100%");
//				var data = $.parseJSON(response.response);
//				if (data.success) {
//					album_photos_container.append(data.html);
//				} else {
//					_alert(data.msg);
//				}
//			});
//			
//			$('#photo-upload-submit').click(function(e) {
//				e.preventDefault();
//				uploader.start();
//			});
			
			
			var smugmug_container = $('#photo-import-smugmug-container');
			
			var smugmug_data = smugmug_container.find('.data');
			var smugmug_loading = smugmug_container.find('.loading');
			var smugmug_import = smugmug_container.find('.import');
			
			smugmug_container.find('.smugmug-import-selected').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				
				var chain = new Array;
				link.parents('form').find('input[type=checkbox]:checked').each(function(){
					var checkbox = $(this);
					chain.push($.post(_member_url + '/photo/smugmugImportPhoto', {
						smugmug:checkbox.val(),
						album_id:album_id
					}, function(data){
						checkbox.attr('checked', false);
						if (data.success) {
							album_photos_container.append(data.html);
						}
					}, 'json'));
				});
				
				$.when.apply(this, chain).done(function(){
					$._alert('Photos were successfully imported.')
				});
			});
			
			smugmug_container.find('.smugmug-check-all, .smugmug-check-none').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				link.parents('form').find('input[type=checkbox]').attr('checked', link.hasClass('smugmug-check-all'))
			});
			
			smugmug_container.find('input[type=button]').click(function(e){
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
					} else {
						$._alert(data.msg);
					}
				}, 'json');
			});		
			
			
			$('#album-order-sizes form').ajaxForm();
			$('#album-order-availability form').ajaxForm();
		});
	}
	
	$('#album').initAlbumPage();
	
})