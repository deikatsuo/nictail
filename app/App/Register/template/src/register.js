$(document).ready(function(){
	$('#reg_form').submit(function(e){
		e.preventDefault();
		var url=$(this).attr('action');
		var send=$.post(url, $(this).serialize(),'json');
		send.done(function(data){
			data=$.parseJSON(data);
			$('#reg_form').find('*').removeClass('uk-form-danger');
			if(!data.success) {
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