$(function(){
	$.fn.initPhotoPage = function(){
		$(this).each(function(){
			var photo = $(this);
			
			var photo_id = photo.data('id');
			var photo_url = photo.find('figure a');
			var photo_image = photo_url.find('img');
			
//			$('#photo-availability-form, #photo-size-form').ajaxForm();
			
			photo_url.fancybox({
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
			
			photo.find('ul.edit li a').click(function(e){
				e.preventDefault();
				var link = $(this);
				
				photo_image.fadeTo(200, .5);
				
				$.post(link.attr('href'), function(data){
					if (data.success) {
						photo_url.attr('href', data.url);
						photo_image.fadeOut(200, function(){
							$(this).attr('src', data.url)
								.fadeTo(400, 1);
						});
						

					} else {
						$._alert(data.msg)
					}
				},'json');
			})
			
		});
	};
	
	$('#photo').initPhotoPage();
});