jQuery(function($){
	$('.event').draggable();
	$('#user-events').droppable({
		drop: function( event, ui ) {
			$.ajax({
				url: '/member/client/addevent/',
				dataType: 'json',
				type: 'POST',
				data: {
					'event_id':		ui.draggable.attr('id').replace('event-', ''),
					'client_id':	$('#user-events').attr('rel').replace('client-', '')
				}
			}).done(function(data)
			{
				return;
			});
		},
		out:  function(event, ui)
		{
			$.ajax({
				url: '/member/client/removeevent/',
				dataType: 'json',
				type: 'POST',
				data: {
					'event_id':		ui.draggable.attr('id').replace('event-', ''),
					'client_id':	$('#user-events').attr('rel').replace('client-', '')
				}
			}).done(function(data)
			{
				return;
			});
		}
	});
});