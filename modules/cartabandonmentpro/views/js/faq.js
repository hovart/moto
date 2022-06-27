$(function() {
	$('.faq-item').click(
		function(){
			if($(this).find('.faq-content').is(':visible'))
			{
				$(this).find('.faq-content').slideUp('fast');
				$(this).find('.expand').html('+');
			}
			else
			{
				$('.faq-content').hide('fast');
				$(this).find('.faq-content').slideDown('fast');
				$('.expand').html('+');
				$(this).find('.expand').html('-');
			}
		}
	);
	$('.faq-item a').click(
		function(e){
			e.stopPropagation();
		}
	);
});