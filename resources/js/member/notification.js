jQuery(function($){
	$('#new-notification-form').submit(function(){
		var form = $(this);
		$.post(form.attr('action'), form.serialize(), function(data){
			data = eval("(" + data+ ")");
			if (!data.state)
			{
				return $('#response-message').html(data.message);
			}
			form.fadeOut('fast', function(){
				$('#response-message').html(data.message);
			});
		});
		return false;
	});
});