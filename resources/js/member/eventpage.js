$(function(){
	$.fn.initEventPage = function(){
		$(this).each(function(){
			var event = $(this);
			
			var event_id = event.data('eventid');
			
			var event_albums_container = $('#event-albums');
			
			event_albums_container.sortable({
				placeholder: "place",
				handle: 'a.move',
				opacity: 0.5,
				update:function(){
					$.post(_member_url + '/album/sort/event/' + event_id, event_albums_container.sortable('serialize'), function(){
						
					});
				}
			});
			
			event.find('a.del').live('click', function(e){
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
			
			$('#description-state').change(function(){
				var select = $(this);
				$.post(_member_url + '/event/toggle/eventId/' + event_id, {state:select.val()}, function(data){
					if (data.success) {

						select.parents('.description').effect("highlight", {}, 500);


					} else {
						$._alert(data.msg)
					}
				},'json');
			});
			
			$('#album-zenfolio-import-button').fancybox({
				fitToView:false,
				autoSize:false,
				minWidth:720,
				height:200,
				margin:40,
				padding:0
			});
			
			var zenfolio_container = $('#album-import-zenfolio-container');
			var zenfolio_data = zenfolio_container.find('.data');
			var zenfolio_loading = zenfolio_container.find('.loading');
			
			zenfolio_container.find('input:button').click(function(e){
				e.preventDefault();
				var album_id = zenfolio_container.find('select').val();
				if (!album_id) {
					return;
				}
				zenfolio_data.hide();
				zenfolio_loading.show();
				$.post(_member_url + '/zenfolio/importAlbum/', {id:album_id,event_id:event_id}, function(data){
					zenfolio_loading.hide();
					zenfolio_data.show();
					if (data.success) {
						$.fancybox.close();
						$.uniform.update();
						$._flash(data.msg, {type:'success'});
						event_albums_container.append(data.html);
					} else {
						$._alert(data.msg);
					}
				}, 'json');
			});
			
			$('#album-smugmug-import-button').fancybox({
				fitToView:false,
				autoSize:false,
				minWidth:720,
				height:200,
				margin:40,
				padding:0
			});
			
			var smugmug_container = $('#album-import-smugmug-container');
			var smugmug_data = smugmug_container.find('.data');
			var smugmug_loading = smugmug_container.find('.loading');
			
			smugmug_container.find('input:button').click(function(e){
				e.preventDefault();
				var album_id = smugmug_container.find('select').val();
				if (!album_id) {
					return;
				}
				smugmug_data.hide();
				smugmug_loading.show();
				$.post(_member_url + '/smugmug/importAlbum/', {id:album_id,event_id:event_id}, function(data){
					smugmug_loading.hide();
					smugmug_data.show();
					if (data.success) {
						$.fancybox.close();
						$.uniform.update();
						$._flash(data.msg, {type:'success'});
						event_albums_container.append(data.html);
					} else {
						$._alert(data.msg);
					}
				}, 'json');
			});
			
		});
	};
	
	$('#event').initEventPage();
});