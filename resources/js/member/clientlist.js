$(function(){
	$.fn.initClientList = function(){
		$(this).each(function(){
			var client_list = $(this);
			client_list.find('span.member, span.ipad').tipTip({
				'defaultPosition':'top'
			});
			
			client_list.find('a.del').click(function(e){
				e.preventDefault();
				var link = $(this);
				$._confirm('Are you sure?', function(confirmed){
					if (confirmed) {
						$.post(link.attr('href'), function(){
							link.parents('tr').fadeOut('fast', function(){
								$(this).remove();
							})
						});
					}
				});
			});
			
		});
	};
	
	$('#client-list').initClientList();
});