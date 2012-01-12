$(function(){
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
			var widget = $(this);
//			var widget = $(this),
//				widget_handle = widget.find(settings.handle),
//				widget_content = widget.find('div').eq(0),
//				widget_container = widget.parent();
				
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
})