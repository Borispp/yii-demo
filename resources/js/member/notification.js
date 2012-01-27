jQuery(function($){
	$('#new-notification-form').submit(function(){
		var form = $(this);
		$.post(form.attr('action'), form.serialize(), function(data){
			data = eval("(" + data+ ")");
			if (!data.state)
			{
				return $('#response-message').fadeIn().html(data.message);
			}
			form.fadeOut('fast', function(){
				$.fancybox.close();
				$._flash(data.message, {type:'success'});
			});
		});
		return false;
	});
});