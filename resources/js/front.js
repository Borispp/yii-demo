$(function(){

	$.fn.initFaq = function() {
		$(this).each(function(){
			var faq = $(this);
			var answers = faq.find('.answers');
			var questions = faq.find('.questions');
			faq.find('.questions ul li a').click(function(e){
				e.preventDefault();
				var link = $(this);
				var id = link.data('id');
				link.addClass('active').parent().siblings().find('a').removeClass('active');
				answers.find('.answer:visible').hide();
				$('#faq-answer-' + id).fadeIn('fast');
			}).filter(':first').trigger('click');
			
			
			faq.equalHeights(true);
			
		});
	}
	$('#faq').initFaq();



	$.fn.initNewsletter = function() {
		$(this).each(function(){
			var newsletter = $(this);
			var form = newsletter.find('form');
			
			
			form.validator({
				position: 'center right', 
				offset: [0, -500],
				message: '<div><em/></div>' // em element is the arrow
			});
			
			
			form.submit(function(e){
				e.preventDefault();
				if (!form.data('validator').checkValidity()) {
					return;
				}
				$.post(form.attr('action'), form.serialize(), function(data){
					if (data.success) {
						var success = $('<div id="newsletter-subscribe-success"></div>').hide().html(data.msg);
						newsletter.append(success);
						form.fadeOut('fast', function(){
							$(this).remove();
							success.fadeIn('fast');
						})
					} else {
						$('#newsletter-subscribe-form-errors').html(data.msg);
					}
				}, 'json');
			});
//			form.html5form({
//				allBrowsers : true,
//				messages: 'en',
//				responseDiv : '#newsletter-subscribe-form-errors',
//				onSuccess: function(data){
//					data = $.parseJSON(data);
//					if (data.success) {
//						var success = $('<div id="newsletter-subscribe-success"></div>').hide().html(data.msg);
//						newsletter.append(success);
//						form.fadeOut('fast', function(){
//							$(this).remove();
//							success.fadeIn('fast');
//						})
//					} else {
//						$('#newsletter-subscribe-form-errors').html(data.msg);
//					}
//				}
//			});  
		});
	}
	$('#newsletter-subscribe').initNewsletter();
	
	
	$.fn.initLoginWindow = function()
	{
		$(this).each(function(){
			var login = $(this);
			var login_visible = 0;
			var login_link = $('#navigation-login-link');
			
			login_link.find('a').click(function(e){
				e.preventDefault();
				if (login_visible) {
					login.hide();
					login_visible = 0;
					login_link.removeClass('login-visible');
				} else {
					login.show();
					login_visible = 1;
					login_link.addClass('login-visible');
				}
			});
			
//			$(document).click(function(){
//				if (login_visible) {
//					login.fadeOut(400, function(){
//						login_visible = 0;
//					});
//				}
//			});
		});
	}
	$('#login-window').initLoginWindow();
})