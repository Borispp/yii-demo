$(function(){
	$.fn.initClientPage = function(){
		$(this).each(function(){
			var client = $(this);
			
			var client_id = client.data('clientid');
			
			$('#description-state').change(function(){
				var select = $(this);
				$.post(_member_url + '/client/toggle/clientId/' + client_id, {state:select.val()}, function(data){
					if (data.success) {
						select.parents('.description').effect("highlight", {}, 500);
					} else {
						$._alert(data.msg)
					}
				},'json');
			});
		});
	};
	$('#client').initClientPage();
});
