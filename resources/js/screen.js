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
});