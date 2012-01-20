var _plupload_settings = {
	runtimes : 'gears,html5,html4,browserplus',
	browse_button : 'photo-upload-browse',
	container : 'photo-upload-container',
	max_file_size : '10mb',
	filters : [
		{title : "Image files", extensions : "jpg,gif,png"},
	]
}

$(function() {

	// init color pickers 
	$('input.colors').miniColors();
	// init datepickers
	$('input.date').datepicker();
	$('input.datetime').datetimepicker({
		ampm: true,
		timeFormat: 'hh:mm:ss',
		showSecond: true
	});
	// styling form elements
	$("select, input:checkbox, input:radio, input:file").uniform();
	// init widgets in memeber area
	$('#member-area').initWidgets();
	// init tips for icons
	$('a.icon[title]').tipTip({
		'defaultPosition':'top'
	});
	$('a.btn.disabled,button.disabled,input:submit.disabled,input:button.disabled').live('click', function(e){
		e.preventDefault();
		return false;
	});
});