$(function(){

	$.fn.initFaq = function() {
		$(this).each(function(){
			var faq = $(this);
			var answers = faq.find('.answers');
			faq.find('.questions ul li a').click(function(e){
				e.preventDefault();
				var link = $(this);
				var id = link.data('id');
				link.addClass('active').parent().siblings().find('a').removeClass('active');
				answers.find('.answer:visible').hide();
				$('#faq-answer-' + id).fadeIn('fast');
			}).filter(':first').trigger('click');
		});
	}
	$('#faq').initFaq();



})