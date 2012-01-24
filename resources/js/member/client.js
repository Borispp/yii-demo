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

//
//jQuery(function($){
//	var clientsInput = $('#ApplicationNotification_clients');
//	var eventsInput = $('#ApplicationNotification_events');
//
//	function getArrayFromString(value)
//	{
//		var valueArray = [];
//		if (value)
//		{
//			if (value.search(',') == -1)
//				valueArray.push(value);
//			else
//			{
//				var valueTempArray = value.split(',');
//				for(x in valueTempArray)
//				{
//					valueArray.push(valueTempArray[x]);
//				}
//			}
//		}
//		return valueArray;
//	}
//
//	function removeFromInput(value, obInput)
//	{
//		var currentValues = getArrayFromString(obInput.val())
//		if ($.inArray(value, currentValues) == -1)
//			return;
//		var x;
//		var newValues = [];
//		for(x in currentValues)
//		{
//			if (value == currentValues[x])
//				continue;
//			newValues.push(currentValues[x]);
//		}
//		obInput.val(newValues.join(','));
//	}
//
//	function addToInput(value, obInput)
//	{
//		var currentValues = getArrayFromString(obInput.val())
//		if ($.inArray(value,currentValues) != -1)
//			return;
//		currentValues.push(value);
//		obInput.val(currentValues.join(','));
//	}
//
//	$('.event, .client').draggable();
//	$('#notification-events').droppable({
//		drop: function( event, ui ) {
//			if (ui.draggable.hasClass('event'))
//				addToInput(ui.draggable.attr('id').replace('event-', ''), eventsInput);
//		},
//		out:  function(event, ui)
//		{
//			if (ui.draggable.hasClass('event'))
//				removeFromInput(ui.draggable.attr('id').replace('event-', ''), eventsInput);
//		}
//	});
//	$('#notification-clients').droppable({
//		drop: function( event, ui ) {
//			if (ui.draggable.hasClass('client'))
//				addToInput(ui.draggable.attr('id').replace('client-', ''), clientsInput);
//		},
//		out:  function(event, ui)
//		{
//			if (ui.draggable.hasClass('client'))
//				removeFromInput(ui.draggable.attr('id').replace('client-', ''), clientsInput);
//		}
//	});
//});