$(function(){
	$.fn.initPhotoPage = function(){
		$(this).each(function(){
			var photo = $(this);
			$('#photo-availability-form, #photo-size-form').ajaxForm();
		});
	};
	
	$('#photo').initPhotoPage();
});