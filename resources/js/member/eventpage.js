$(function(){
	$.fn.initEventPage = function(){
		$(this).each(function(){
			var event = $(this);
			
			var event_id = event.data('eventid');
			
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
});