$(function(){
	$('a.btn.disabled,button.disabled,input:submit.disabled,input:button.disabled').live('click', function(e){
		e.preventDefault();
		return false;
	});
	
	$.tools.validator.addEffect("wall", function(errors, event) {
			var wall = $(this.getConf().container).fadeIn();
			wall.find("p").remove();
			$.each(errors, function(index, error) {
				wall.append(
					"<p><strong>" +error.input.attr("name")+ "</strong> " +error.messages[0]+ "</p>"
				);		
			});
		}, function(inputs)  {}
	);

	$.fn.initFaq = function() {
		$(this).each(function(){
			var faq = $(this);
			
			faq.find('.questions a').click(function(e){
				e.preventDefault();
				var link = $(this);
				$.scrollTo($('#faq-answer-' + link.data('id')), 800);
			});
			
			var answers = faq.find('.answers');
			var answers_width = answers.outerWidth();
			
            var totop = faq.find('a.top');
            var totop_height = totop.outerHeight();
			
			var window_height, before_answers_offset, window_top;
			
			function _init_button()
			{
				window_height = $(window).height();
				before_answers_offset = answers.offset();
				window_top = $(this).scrollTop();
				
				if (window_top >= before_answers_offset.top) {
					totop.css({
						left:answers_width + before_answers_offset.left - totop_height / 2,
						top:(window_height-totop_height) / 2
					});
					if (totop.is(':hidden')) {
						totop.fadeIn(200);
					}
				} else {
					if (totop.is(':visible')) {
						totop.fadeOut(200);
					}
				}
			}
			
			$(window).scroll(function(){
				_init_button();
			}).resize(function(){
				_init_button();
			});
			_init_button();
			
			totop.click(function(e){
				e.preventDefault();
				$.scrollTo($('#page-top'), 400);
			});
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
				messageClass:'form-error',
				message: '<div><em/></div>' // em element is the arrow
			}).submit(function(e){
				e.preventDefault();
				if (!form.data('validator').checkValidity()) {
					return;
				}
				var button = form.find('input:submit');
				button.btnLoading();
				$.post(form.attr('action'), form.serialize(), function(data){
					button.btnLoaded();
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
		});
	}
	$('#newsletter-subscribe').initNewsletter();
	
	
	$.fn.initLoginWindow = function()
	{
		$(this).each(function(){
			var login = $(this);
			var login_visible = 0;
			var login_link = $('#navigation-login-link');
			$('html').click(function(e){
                if ('object' == typeof(e.originalEvent) && login_visible) {
                    login_link.find('a').trigger('click');
                }
			});
			login.click(function(e){
				e.stopPropagation();
			});
			login_link.find('a').click(function(e){
				e.preventDefault();
				if (login_visible) {
					login.hide();
					login_visible = 0;
					login_link.removeClass('login-visible');
				} else {
					e.stopPropagation();
					login.show();
					login.find('input:first').focus();
					login_visible = 1;
					login_link.addClass('login-visible');
				}
			});
			
			login.find('form').submit(function(){
				$(this).find('input:submit').btnLoading();
				
			})
		});
	}
	$('#login-window').initLoginWindow();
	
	$.fn.initContact = function()
	{
		$(this).each(function(){
			var contact = $(this);
			var form = contact.find('form');
			form.validator({
				position: 'top center', 
				offset: [10, 0],
				messageClass:'error-top',
				errorClass:'error',
				grouped:true,
				onSuccess:function(e){
					e.preventDefault();
					$.post(form.attr('action'), form.serialize(), function(data){
						if (data.success) {
							var success = $('<div id="contact-success"></div>').hide().html(data.msg);
							contact.find('.form').append(success);
							form.fadeOut('fast', function(){
								$(this).remove();
								success.fadeIn('fast');
							})
						} else {
							$('#newsletter-subscribe-form-errors').html(data.msg);
						}
					}, 'json');
				}
			});
		});
	}
	$('#contact').initContact();
	
	$.fn.initBlog = function() {
		$(this).each(function(){
			//var blog = $(this);
			
		});
	}
	$('#blog').initBlog();
	
	
	
	$.fn.initLoginRegister = function()
	{
		$(this).each(function(){
//			var login_register = $(this);
			
			var login_form = $('#login-form');
			var register_form = $('#registration-form');
			
			var register_simple_submit = false;
			var register_submit = register_form.find('input:submit');
			register_form.validator({
				position: 'top center', 
				offset: [10, 0],
				messageClass:'error-top',
				errorClass:'error',
				grouped:true
			}).submit(function(e) {
				if (register_simple_submit)
					return;
				if (!e.isDefaultPrevented()) {
					register_submit.btnLoading();
					$.post(_base_url + '/auth/checkRegistration', register_form.serialize(), function(data) {
						register_submit.btnLoaded();
						if (data.success)  {
							register_form.data("validator").destroy();
							register_simple_submit = true;
							register_form.submit();
							return true;
						} else {
							register_form.data("validator").invalidate(data.errors);
						}
					}, 'json');
					e.preventDefault();
				}
			});
			
			login_form.submit(function(){
				login_form.find('input:submit').btnLoading();
			})
		});
	}
	$('#login-register').initLoginRegister();
	
	
	
	
	$.fn.initHomepageSlider = function(){
		$(this).each(function(){
			var slider = $(this);
			var slides = slider.find('li');
			var buttons_container = $('<span class="buttons"></span>');
			slides.each(function(k){
				buttons_container.append('<a href="#" data-slide="' + k + '"></a>');
			});
			slider.append(buttons_container);
			var buttons = buttons_container.find('a');
			var scroll_interval;
			var animated = false;
			
			buttons.click(function(e){
				e.preventDefault();
				var link = $(this);
				if (link.hasClass('active')) {
					return;
				}
				if (animated) {
					return;
				}
                if ('object' == typeof(e.originalEvent)) {
                    clearInterval(scroll_interval);
                }
				var current = slides.filter(':visible');
				var show = slides.filter('.slide' + link.data('slide'));
				animated = true;
				
				link.addClass('active').siblings('.active').removeClass('active');
				if (current.length) {
					current.fadeOut(400);
				}
				
				show.css({
					opacity:0,
					display:'block',
					visibility:'visible',
					right:'500px'
				}).animate({
                    opacity:1,
                    right:0
                }, 'swing', function() {
					animated = false;
				});
			}).filter(':first').trigger('click');
			
			
            scroll_interval = setInterval(function(){
                var current = buttons.filter('.active');
                var show = current.next();
                if (!show.length) {
                    show = buttons.filter(':first');
                }
                show.trigger('click');
            }, 5000);
			
			
		});
	}
	$('#homepage-slider').initHomepageSlider();
	
	
	$.fn.initHomepageVideo = function(){
		$(this).each(function(){
			var video = $(this);
			
			video.find('.video a').click(function(e){
				e.preventDefault();
				
				$.fancybox({
					'href' : _base_url + '/site/loadVideo/',
					'type' : 'ajax'
				});
			});
		});
	}
	$('#homepage-video').initHomepageVideo();
	
});