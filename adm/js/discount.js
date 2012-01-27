jQuery(function($){
	
	var toggle = function(e){
	
		var i = $('#'+e.data('inputid'));
		e.attr('checked') ? i.removeAttr('disabled') : i.attr('disabled','disabled');
	
	};
	
	var allCheckboxes = $("input.membership:enabled");
	var notChecked = allCheckboxes.not(':checked');
	allCheckboxes.each(function(e){
		toggle($(this));
	});
	notChecked.each(function(e){
		toggle($(this));
	});
	
	$('input.membership').change(function(){
		toggle($(this));
	});
	
});