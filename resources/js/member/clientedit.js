$(function(){
	$.fn.initClientEdit = function(){
		$(this).each(function(){
			var client = $(this);
			client.find('select.multiselect').multiSelect({
				selectableHeader : '<h3>Available Events</h3>',
				selectedHeader : '<h3>Selected Events</h3>'
			});
		});
	};
	
	$('#client-edit').initClientEdit();	
});
