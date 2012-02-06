$(function(){
	$.fn.initSubscriptionList = function(){
		$(this).each(function(){
			var subscription_list = $(this);
			subscription_list.find('a.delete').click(function(e){
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
			});
		});
	};
	
	$('#subscription-list').initSubscriptionList();
});