$(document).ready(function(){
	$('#reg_form').submit(function(e){
		e.preventDefault();
		if($('#g-recaptcha-response').val() == '') {
			$('.g-recaptcha-c').addClass('uk-form-danger');
			$('.g-recaptcha-c i').css('display','block');
			return false;
		}
		$('.g-recaptcha-c i').css('display','none');
		var url=$(this).attr('action');
		var send=$.post(url, $(this).serialize(),'json');
		send.done(function(data){
			data=$.parseJSON(data);
			$('#reg_form').find('*').removeClass('uk-form-danger');
			if(!data.success) {
				grecaptcha.reset();
				var error=data.error;
				for(var keys in error) {
					$('#'+keys).addClass('uk-form-danger');
				}
			}
			else {
				$('#top_notification').addClass('uk-alert-success').show('fast');
				$('#top_notification_message').html(data.successmessage);
				$('body').animate({scrollTop:0},'fast');
			}
		});
	});
});