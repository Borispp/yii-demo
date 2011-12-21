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
			
			var swfu = new SWFUpload(
				$.extend(swfupload_options, {
					upload_url: _member_url + '/photo/upload/album/' + album_id,
					upload_success_handler : function(file, data) {
						try {
							data = $.parseJSON(data);
							if (data.success) {
								album_photos_container.append(data.html);
							} else {
								alert(data.msg);
							}
						} catch (ex) {
							this.debug(ex);
						}
					}
				})
			);
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
			var swfu = new SWFUpload(
				$.extend(swfupload_options, {
					upload_url: _member_url + '/portfolioPhoto/upload/album/' + album_id,
					upload_success_handler : function(file, data) {
						try {
							data = $.parseJSON(data);
							if (data.success) {
								album_photos_container.append(data.html);
							} else {
								alert(data.msg);
							}
						} catch (ex) {
							this.debug(ex);
						}
					}
				})
			);
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