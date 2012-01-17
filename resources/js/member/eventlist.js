$(function(){
	$.fn.initEventList = function(){
		$(this).each(function(){
			var event_list = $(this);
			
			event_list.find('a.delete').click(function(e){
				e.preventDefault();
				var link = $(this);
				$._confirm('Are you sure?', function(confirmed){
					if (confirmed) {
						$.post(link.attr('href'), function(){
							link.parents('tr').fadeOut('fast', function(){
								$(this).remove();
							});
						});
					}
				});
			})
			
//			event.find('a.delete').click(function(e){
//				e.preventDefault();
//				var link = $(this);
//				$._confirm('Are you sure?', function(confirmed){
//					if (confirmed) {
//						$.post(_member_url + '/album/delete/' + link.attr('rel'), function(){
//							link.parents('li').fadeOut('fast', function(){
//								$(this).remove();
//							})
//						});
//					}
//				});
//			});
		});
	};
	
	$('#event-list').initEventList();
});