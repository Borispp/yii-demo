$(function(){
	$('#search_toggler').click(function(){
		$('#search_form').toggle();
	}).css({cursor:'pointer'});
	
	$('button#reset').click(function(){
		document.location.href = window.location.pathname;
		return false;
	});
	
	$('#search_form.hidden').hide();
});