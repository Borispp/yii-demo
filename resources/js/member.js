var _plupload_settings = {
	runtimes : 'gears,html5,html4,browserplus',
	browse_button : 'photo-upload-browse',
	container : 'photo-upload-container',
	max_file_size : '10mb',
	filters : [
		{title : "Image files", extensions : "jpg,jpeg,gif,png,jpeg"},
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
	$("select:not(.multiselect), input:checkbox, input:radio, input:file").uniform();
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
	
	
	
	var checkbox_select_all = $('#entries-select-all');
	var data_table = $('table.data');
	checkbox_select_all.change(function(){
		var boxes = checkbox_select_all.parents('table').find('td input:checkbox');
		boxes.attr('checked', checkbox_select_all.is(':checked'));
		$.uniform.update(boxes);
	});
	data_table.find('a.entries-delete-selected').click(function(e){
		e.preventDefault();
		var link = $(this);
		$._confirm('Are you sure you want to permanently delete selected items?', function(confirmed){
			if (confirmed) {
				var ids = [];
				data_table.find('td input[type=checkbox]:checked').each(function(){
					ids.push($(this).val());
				});
				$.post(_member_url + '/' + data_table.data('control') + '/delete', {ids:ids}, function(){
					$.each(ids, function(k, v){
						$('#entry-row-' + v).fadeOut('fast', function(){
							$(this).remove();
						});
						checkbox_select_all.attr('checked', false);
						$.uniform.update(checkbox_select_all);
					});
				});
			}
		});
	});
	
	
});