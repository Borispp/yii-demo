$(function(){
	$('#search_toggler').click(function(){
		$('section.search-form').toggle();
	}).css({cursor:'pointer'});
	
	$('button#reset').click(function(){
		document.location.href = window.location.pathname;
		return false;
	});
});