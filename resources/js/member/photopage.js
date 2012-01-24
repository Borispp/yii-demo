$(function(){
	$.fn.initPhotoPage = function(){
		$(this).each(function(){
			var photo = $(this);
			
			var photo_id = photo.data('id');
			
			$('#photo-availability-form, #photo-size-form').ajaxForm();
			
			photo.find('figure a').fancybox({
				type:'image'
			});
			
			$('#description-state').change(function(){
				var select = $(this);
				$.post(_member_url + '/photo/toggle/photoId/' + photo_id, {state:select.val()}, function(data){
					if (data.success) {
						select.parents('.description').effect("highlight", {}, 500);
					} else {
						$._alert(data.msg)
					}
				},'json');
			});
		});
	};
	
	$('#photo').initPhotoPage();
});