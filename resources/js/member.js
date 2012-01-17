var _plupload_settings = {
	runtimes : 'gears,html5,html4,browserplus',
	browse_button : 'photo-upload-browse',
	container : 'photo-upload-container',
	max_file_size : '10mb',
	filters : [
		{title : "Image files", extensions : "jpg,gif,png"},
	]
}
//function _plupload_error_handler(up, err) {
//	$('#photo-filelist').append("<div>Error: " + err.code +
//		", Message: " + err.message +
//		(err.file ? ", File: " + err.file.name : "") +
//		"</div>"
//	);
//	up.refresh();
//}
//function _plupload_upload_progress(up, file) {
//	$('#' + file.id + " b").html(file.percent + "%");
//}
//function _plupload_files_added(up, files) {
//	$.each(files, function(i, file) {
//		$('#photo-filelist').append(
//			'<div id="' + file.id + '">' +
//			file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
//		'</div>');
//	});
//
//	up.refresh();
//}



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
	$('a.icon').tipTip({
		'defaultPosition':'top'
	});
});