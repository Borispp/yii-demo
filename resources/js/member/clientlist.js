$(function(){
	$.fn.initClientList = function(){
		$(this).each(function(){
			var client_list = $(this);
			client_list.find('span.member, span.ipad').tipTip({
				'defaultPosition':'top'
			});
		});
	};
	
	$('#client-list').initClientList();
});