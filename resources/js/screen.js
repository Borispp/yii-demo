$(function(){
	// Arguments are image paths relative to the current page.
	$.preload = function() {
		var cache = [],
			args_len = arguments.length;
		for (var i = args_len; i--;) {
			var cacheImage = document.createElement('img');
			cacheImage.src = arguments[i];
			cache.push(cacheImage);
		}
	};
	
	$.leadingZero = function (value) {
		value = parseInt(value, 10);
		if(!isNaN(value)) {
			(value < 10) ? value = '0' + value : value;
		}
		return value;
	};
	
	// confirm wrapper for uprise
	$._confirm = function (msg, callback) {
		apprise(msg, {'confirm':true,animate:true}, callback);
	}
	// alert wrapper for uprise
	$._alert = function(msg, callback) {
		apprise(msg, {animate:true}, callback);
	}
	
	// flashing notices, errors & success messages
	$('div.flash').click(function(){
		$(this).slideUp('fast', function(){
			$(this).remove();
		});
	});
	
	// add ajax loader to page on any ajax call
	var ajax_loader = $('#ajax-loader');
	$(document).ajaxStart(function(){
		ajax_loader.show();
	}).ajaxComplete(function(){
		ajax_loader.hide();
	});
});