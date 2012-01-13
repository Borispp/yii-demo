var _plupload_settings = {
	runtimes : 'gears,html5,html4,browserplus',
	browse_button : 'photo-upload-browse',
	container : 'photo-upload-container',
	max_file_size : '10mb',
	filters : [
		{title : "Image files", extensions : "jpg,gif,png"},
	]
}
function _plupload_error_handler(up, err) {
	$('#photo-filelist').append("<div>Error: " + err.code +
		", Message: " + err.message +
		(err.file ? ", File: " + err.file.name : "") +
		"</div>"
	);
	up.refresh();
}
function _plupload_upload_progress(up, file) {
	$('#' + file.id + " b").html(file.percent + "%");
}
function _plupload_init(up, params) {
	$('#photo-filelist').html("<div>Current runtime: " + params.runtime + "</div>");
}
function _plupload_files_added(up, files) {
	$.each(files, function(i, file) {
		$('#photo-filelist').append(
			'<div id="' + file.id + '">' +
			file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
		'</div>');
	});

	up.refresh();
}

// confirm wrapper for uprise
function _confirm(msg, callback) {
	apprise(msg, {'confirm':true,animate:true}, callback);
}
// alert wrapper for uprise
function _alert(msg, callback) {
	apprise(msg, {animate:true}, callback);
}

$(function(){
	
	var ajax_loader = $('#ajax-loader');
	$(document).ajaxStart(function(){
		ajax_loader.show();
	}).ajaxComplete(function(){
		ajax_loader.hide();
	});
	
	
	$('div.flash').click(function(){
		$(this).slideUp('fast', function(){
			$(this).remove();
		});
	})
	
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

	
	$.fn.initWidgets = function(_settings) {
		
		var settings = $.extend({
			namespace : 'ysawdgt_',
			handle : 'h3',
			placeholder : 'sortable-placeholder',
			opacity : .8,
			distance : 5
		}, _settings);
		
		var main_container = $(this);
		var widgets_container = main_container.find('div.widgets');
		var widgets = widgets_container.find('div.widget');
		
		main_container.disableSelection();

		widgets.each(function(){
			var widget = $(this),
				widget_handle = widget.find(settings.handle),
				widget_content = widget.find('div').eq(0),
				widget_container = widget.parent();
				
			widget.data(settings.namespace + 'widget', {});
		})
		
		widgets_container.each(function(i){
			var widgets = $.jStorage.get(settings.namespace + 'widgets_' + i),
				widgets_cont = $(this);
			if (!widgets) {
				return false;
			}
			$.each(widgets, function(w, options){
				var widget = $('#' + w);
				//position handling
				if (widget.length && (widget.prevAll('div').length != options.position || widget.parent()[0] !== widgets_cont[0])) {
					var children = widgets_cont.children('div.widget');
					if (children.eq(options.position).length) {
						widget.insertBefore(children.eq(options.position));
					} else if (children.length) {
						widget.insertAfter(children.eq(options.position - 1));
					} else {
						widget.appendTo(widgets_cont);
					}
				}
			});
		});

		widgets_container.each(function(){
			//use jQuery UI sortable plugin for the widget sortable function
			$(this).sortable({
				items: widgets,
				containment: main_container,
				opacity: settings.opacity,
				distance: settings.distance,
				handle: settings.handle,
				connectWith: widgets_container,
				forceHelperSize: true,
				placeholder: settings.placeholder,
				forcePlaceholderSize: true,
				zIndex: 10000,
				start: function (event, ui) {
					
				},
				stop: function (event, ui) {
					widgets_container.each(function(containerid, e){
						var _widgets = {};
						//get info from all widgets from that container
						$(this).find('div.widget').each(function (pos, e) {
							var _t = $(this);
							_widgets[this.id] = {
								position: pos
							};
						});
						
						$.jStorage.set(settings.namespace + 'widgets_' + containerid, _widgets);
					});
				}
			});
		});
	}
	$('#member-area').initWidgets();
	
});