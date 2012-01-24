jQuery(function($){
	var eventsInput = $('#events-input');
	function getArrayFromString(value)
	{
		var valueArray = [];
		if (value)
		{
			if (value.search(',') == -1)
				valueArray.push(value);
			else
			{
				var valueTempArray = value.split(',');
				for(x in valueTempArray)
				{
					valueArray.push(valueTempArray[x]);
				}
			}
		}
		return valueArray;
	}
	function removeFromInput(value, obInput)
	{
		var currentValues = getArrayFromString(obInput.val())
		if ($.inArray(value, currentValues) == -1)
			return;
		var x;
		var newValues = [];
		for(x in currentValues)
		{
			if (value == currentValues[x])
				continue;
			newValues.push(currentValues[x]);
		}
		obInput.val(newValues.join(','));
	}

	function addToInput(value, obInput)
	{
		var currentValues = getArrayFromString(obInput.val())
		if ($.inArray(value,currentValues) != -1)
			return;
		currentValues.push(value);
		obInput.val(currentValues.join(','));
	}

	$( "#events" ).sortable({
		connectWith: ".connectedSortable"
	}).disableSelection();
	$( "#user-events" ).sortable({
		connectWith: ".connectedSortable",
		receive: function(event, ui)
		{
			addToInput(ui.item.attr('id').replace('event-', ''), eventsInput);
		},
		remove: function(event, ui)
		{
			removeFromInput(ui.item.attr('id').replace('event-', ''), eventsInput);
		}
	}).disableSelection();
});
