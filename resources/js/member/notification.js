jQuery(function($){
	var clientsInput = $('#ApplicationNotification_clients');
	var eventsInput = $('#ApplicationNotification_events');

	function getArrayFromString(value)
	{
		var valueArray = {};
		//if (value)

		value.split(',');
	}
	$('.event, .client').draggable();
	$('#notification-events').droppable({
		drop: function( event, ui ) {
			clientsInput.val()
			if (ui.draggable.hasClass('event'))
				console.log(ui.draggable.attr('id').replace('event-', ''));
		},
		out:  function(event, ui)
		{
			if (ui.draggable.hasClass('event'))
				console.log(ui.draggable.attr('id').replace('event-', ''));
		}
	});
	$('#notification-clients').droppable({
		drop: function( event, ui ) {
			if (ui.draggable.hasClass('client'))
				console.log(ui.draggable.attr('id').replace('client-', ''));
		},
		out:  function(event, ui)
		{
			if (ui.draggable.hasClass('client'))
				console.log(ui.draggable.attr('id').replace('client-', ''));
		}
	});
});