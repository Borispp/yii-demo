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
	
	$.ucfirst = function(str,force) {
          str=force ? str.toLowerCase() : str;
          return str.replace(/(\b)([a-zA-Z])/,
                   function(firstLetter){
                      return   firstLetter.toUpperCase();
                   });
	}
	
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
	
	// flash types: info, notice, error, success
	$._flash = function(msg, _settings) {
		var settings = $.extend({
			'type':'notice',
			'clear_notifications':true,
			'hide_timeout':5000
		}, _settings);
		var flash = $('<div class="flash ' + settings.type + '">' + msg + '</div>').hide();
		var notifications = $('#notifications');
		if (settings.clear_notifications) {
			notifications.html('');
		}
		notifications.append(flash);
		flash.fadeInSlide(400, function(){
			setTimeout(function(){
				flash.trigger('click');
			}, settings.hide_timeout)
		});
	}
	
	// flashing notices, errors & success messages
	$('#notifications div.flash').live('click', function(){
		$(this).fadeOutSlide(400, function(){
			$(this).remove();
		});
	});
	
	$("a[rel*=external]").each(function(){
		$(this).attr('target', '_blank');
	});
	
	// add ajax loader to page on any ajax call
	var ajax_loader = $('#ajax-loader');
	if (ajax_loader.length) {
		$.preload(ajax_loader.backgroundImageUrl());
		$(document).ajaxStart(function(){
			ajax_loader.show();
		}).ajaxComplete(function(){
			ajax_loader.hide();
		});
	}

	$.fn.fadeInSlide = function (speed, callback) {
		if ($.isFunction(speed)) callback = speed;
		if (!speed) speed = 200;
		if (!callback) callback = function () {};
		this.each(function () {
			var $this = $(this);
			$this.fadeTo(speed / 2, 1).slideDown(speed / 2, function () {
				callback();
			});
		});
		return this;
	};
	
	$.fn.fadeOutSlide = function (speed, callback) {
		if ($.isFunction(speed)) callback = speed;
		if (!speed) speed = 200;
		if (!callback) callback = function () {};
		this.each(function () {
			var $this = $(this);
			$this.fadeTo(speed / 2, 0).slideUp(speed / 2, function () {
				$this.remove();
				callback();
			});
		});
		return this;
	};
	
	$.fn.equalHeights = function(px) {
		$(this).each(function(){
			var currentTallest = 0;
			$(this).children().each(function(i){
				if ($(this).height() > currentTallest) {currentTallest = $(this).height();}
			});
			if ($.browser.msie && $.browser.version == 6.0) {$(this).children().css({'height': currentTallest});}
			$(this).children().css({'min-height': currentTallest}); 
		});
		return this;
	};

	// just in case you need it...
	$.fn.equalWidths = function(px) {
		$(this).each(function(){
			var currentWidest = 0;
			$(this).children().each(function(i){
					if($(this).width() > currentWidest) {currentWidest = $(this).width();}
			});
			if ($.browser.msie && $.browser.version == 6.0) {$(this).children().css({'width': currentWidest});}
			$(this).children().css({'min-width': currentWidest}); 
		});
		return this;
	};
	
	$.fn.toEm = function(settings){
		settings = jQuery.extend({
			scope: 'body'
		}, settings);
		var that = parseInt(this[0],10),
			scopeTest = jQuery('<div style="display: none; font-size: 1em; margin: 0; padding:0; height: auto; line-height: 1; border:0;">&nbsp;</div>').appendTo(settings.scope),
			scopeVal = scopeTest.height();
		scopeTest.remove();
		return (that / scopeVal).toFixed(8) + 'em';
	};


	$.fn.toPx = function(settings){
		settings = jQuery.extend({
			scope: 'body'
		}, settings);
		var that = parseFloat(this[0]),
			scopeTest = jQuery('<div style="display: none; font-size: 1em; margin: 0; padding:0; height: auto; line-height: 1; border:0;">&nbsp;</div>').appendTo(settings.scope),
			scopeVal = scopeTest.height();
		scopeTest.remove();
		return Math.round(that * scopeVal) + 'px';
	};
	
	$.fn.btnLoading = function()
	{
		this.val(this.data('loading')).addClass('disabled');
	}
	$.fn.btnLoaded = function()
	{
		this.val(this.data('value')).removeClass('disabled');
	}
	
    $.fn.animateHighlight = function (highlightColor, duration) {
        var highlightBg = highlightColor || "#FFFF9C";
        var animateMs = duration || "fast"; // edit is here
        var originalBg = this.css("background-color");

        if (!originalBg || originalBg == highlightBg)
            originalBg = "#FFFFFF"; // default to white

        jQuery(this)
            .css("backgroundColor", highlightBg)
            .animate({ backgroundColor: originalBg }, animateMs, null, function () {
                jQuery(this).css("backgroundColor", originalBg); 
            });
    };
	
	$.fn.initNav = function(){
		$(this).each(function(){
			var nav = $(this);
			nav.find('>ul>li:has(ul)').each(function(){
				var li = $(this);
				var sub = li.find('ul');
				sub.css('left', -(sub.outerWidth() - li.outerWidth())/2);
			}).hover(function(){
				$(this).find('ul').stop().fadeIn('fast');
			}, function(){
				$(this).find('ul').hide();				
			});
		});
	}
	$('header nav').initNav();
	
	Modernizr.load({
		test: Modernizr.input.placeholder,
		nope: _polyfill_url + '/placeholder.js'		
	});
});