var _plupload_settings = {
	runtimes : 'gears,html5,html4,browserplus',
	browse_button : 'photo-upload-browse',
	container : 'photo-upload-container',
	max_file_size : '10mb',
	filters : [
		{title : "Image files", extensions : "jpg,gif,png"},
	]
}
function _plupload_error_handler(up, err) {
	$('#photo-filelist').append("<div>Error: " + err.code +
		", Message: " + err.message +
		(err.file ? ", File: " + err.file.name : "") +
		"</div>"
	);
	up.refresh();
}
function _plupload_upload_progress(up, file) {
	$('#' + file.id + " b").html(file.percent + "%");
}
function _plupload_init(up, params) {
	$('#photo-filelist').html("<div>Current runtime: " + params.runtime + "</div>");
}
function _plupload_files_added(up, files) {
	$.each(files, function(i, file) {
		$('#photo-filelist').append(
			'<div id="' + file.id + '">' +
			file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
		'</div>');
	});

	up.refresh();
}


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
				if (!confirm('Are you sure?')) {
					return false;
				}
				var link = $(this);
				$.post(_member_url + '/photo/delete/' + link.attr('rel'), function(){
					link.parents('li').fadeOut('fast', function(){
						$(this).remove();
					})
				});
			});
			
			// init uploader
			var uploader = new plupload.Uploader($.extend(_plupload_settings, {url:_member_url + '/photo/upload/album/' + album_id}));
			
			uploader.bind('Init', _plupload_init);
			uploader.init();
			uploader.bind('FilesAdded', _plupload_files_added);
			uploader.bind('UploadProgress', _plupload_upload_progress);
			uploader.bind('Error', _plupload_error_handler);
			uploader.bind('FileUploaded', function(up, file, response) {
				$('#' + file.id + " b").html("100%");
				var data = $.parseJSON(response.response);
				if (data.success) {
					album_photos_container.append(data.html);
				} else {
					alert(data.msg);
				}
			});
			
			$('#photo-upload-submit').click(function(e) {
				e.preventDefault();
				uploader.start();
			});
			
			
			
			var smugmug_container = $('#photo-import-smugmug-container');
			
			var smugmug_data = smugmug_container.find('.data');
			var smugmug_loading = smugmug_container.find('.loading');
			var smugmug_import = smugmug_container.find('.import');
			
			function smugmug_import_photo(checkbox)
			{
				$.post(_member_url + '/photo/smugmugImportPhoto', {
					smugmug:checkbox.val(),
					album_id:album_id
				}, function(data){
					checkbox.attr('checked', false);
					if (data.success) {
						album_photos_container.append(data.html);
					}
				}, 'json');
			}
			
			smugmug_container.find('.smugmug-import-selected').live('click', function(e){
				e.preventDefault();
				var link = $(this);
				
				link.parents('form').find('input[type=checkbox]:checked').each(function(){
					smugmug_import_photo($(this));
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
						alert(data.msg);
					}
				}, 'json');
				
//				$.post(_member_url + '/photo/smugmugImport/album/' + album_id, {
//					smugmug:smugmug
//				}, function(data){
//					
//					console.log(data);
//					
//					smugmug_loading.hide();
//					
//					
//					smugmug_import.html(data.html)
//					
//					smugmug_data.show();
//					
//				});
			})
			
			
		});
	}
	
	$('#album').initAlbumPage();
	
	$.fn.initEventPage = function()
	{
		$(this).each(function(){
			var event = $(this);
			
			var event_id = event.attr('eventid');
			
			var event_albums_container = $('#event-albums');
			
			event_albums_container.sortable({
				placeholder: "ui-state-highlight",
				opacity: 0.6,
				update:function(){
					$.post(_member_url + '/album/sort/event/' + event_id, event_albums_container.sortable('serialize'), function(){
						
					});
				}
			});
			
			event.find('a.delete').click(function(e){
				e.preventDefault();
				if (!confirm('Are you sure?')) {
					return false;
				}
				var link = $(this);
				$.post(_member_url + '/album/delete/' + link.attr('rel'), function(){
					link.parents('li').fadeOut('fast', function(){
						$(this).remove();
					})
				});
			});
		});
	};
	
	$('#event').initEventPage();
	
	
	
	
	
	
	$.fn.initStudioPage = function()
	{
		$(this).each(function(){
			
		});
	}
	
	$('#studio').initStudioPage();
	
	
	
	
	
	
	
	
	
	
	
	$.fn.initPortfolioAlbumPage = function()
	{
		$(this).each(function(){
			
			var album = $(this);
			var album_id = album.attr('albumid');
			
			var album_photos_container = $('#portfolio-album-photos');
			
			album_photos_container.sortable({
				placeholder: "ui-state-highlight",
				opacity: 0.6,
				update:function(){
					$.post(_member_url + '/portfolioPhoto/sort/album/' + album_id, album_photos_container.sortable('serialize'), function(){
						
					});
				}
			});
			album_photos_container.disableSelection();
			
			album.find('a.delete').live('click', function(e){
				e.preventDefault();
				if (!confirm('Are you sure?')) {
					return false;
				}
				var link = $(this);
				$.post(_member_url + '/portfolioPhoto/delete/' + link.attr('rel'), function(){
					link.parents('li').fadeOut('fast', function(){
						$(this).remove();
					});
				});
			});
			
			// init uploader
			var uploader = new plupload.Uploader($.extend(_plupload_settings, {url:_member_url + '/portfolioPhoto/upload/album/' + album_id}));
			
			uploader.bind('Init', _plupload_init);
			uploader.init();
			uploader.bind('FilesAdded', _plupload_files_added);
			uploader.bind('UploadProgress', _plupload_upload_progress);
			uploader.bind('Error', _plupload_error_handler);
			uploader.bind('FileUploaded', function(up, file, response) {
				$('#' + file.id + " b").html("100%");
				var data = $.parseJSON(response.response);
				if (data.success) {
					album_photos_container.append(data.html);
				} else {
					alert(data.msg);
				}
			});
			
			$('#photo-upload-submit').click(function(e) {
				e.preventDefault();
				uploader.start();
			});
		});
	}
	
	$('#portfolio-album').initPortfolioAlbumPage();
	
	
	
	
	$.fn.initPortfolioPage = function()
	{
		$(this).each(function(){
			var portfolio = $(this);
			
			var portfolio_albums_container = $('#portfolio-albums');
			
			portfolio_albums_container.sortable({
				placeholder: "ui-state-highlight",
				opacity: 0.6,
				update:function(){
					$.post(_member_url + '/portfolioAlbum/sort/', portfolio_albums_container.sortable('serialize'), function(){
						
					});
				}
			});
			
			portfolio.find('a.delete').click(function(e){
				e.preventDefault();
				if (!confirm('Are you sure?')) {
					return false;
				}
				var link = $(this);
				$.post(_member_url + '/portfolioAlbum/delete/' + link.attr('rel'), function(){
					link.parents('li').fadeOut('fast', function(){
						$(this).remove();
					})
				});
			});
		});
	};
	
	$('#portfolio').initPortfolioPage();
});