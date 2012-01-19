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
	
	$.fn.backgroundImageUrl = function(options) {
		if (options){
			return this.each(function(){
				$(this).css('backgroundImage','url:('+options+')');
			});
		}else {
			return $(this).css('backgroundImage').replace(/url\(|\)|"|'/g,"");
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
	
	$._flash = function(msg, _settings) {
		var settings = $.extend({
			'type':'notice'
		}, _settings);
		
		var flash = $('<div class="flash ' + settings.type + '">' + msg + '</div>');
		flash.hide();
		$('#notifications').append(flash);
		
		flash.slideDown('fast');
	}
	
	// flashing notices, errors & success messages
	$('div.flash').live('click', function(){
		$(this).slideUp('fast', function(){
			$(this).remove();
		});
	});
	
	
	
	
//	preload
	
	// add ajax loader to page on any ajax call
	var ajax_loader = $('#ajax-loader');
	$.preload(ajax_loader.backgroundImageUrl());
	$(document).ajaxStart(function(){
		ajax_loader.show();
	}).ajaxComplete(function(){
		ajax_loader.hide();
	});
});