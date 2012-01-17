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
			
			event.find('a.del').click(function(e){
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
		});
	};
	
	$('#event').initEventPage();
});