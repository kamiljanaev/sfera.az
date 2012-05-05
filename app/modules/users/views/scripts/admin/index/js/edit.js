$(function(){

	var validator = $("#add-form").validate({
		onkeyup: false,
		onclick: false,
		rules: {
		  	login: {
				required: true
			},
			password: {
				rangelength: [3, 255]
			},
			password_conf: {
				equalTo: "#password"
			}
		},
		messages: {
			login: {
				required: 'Логин не может быть пустым'
			},
			password: {
				rangelength: 'Длина пароля дожна быть не менее 8 символов'
			},
			password_conf: {
				equalTo: 'Подтверждение пароля не совпадает с паролем'
			}
		}
	});

 	$('#add-form').submit(function(){
 		if(($(this).valid())){
 			return true;
 		}
 		return false;
 	});

 	$('.add-action').click(function(event){
 		event.preventDefault();
 		$('#add-form').submit();
 	});

 	$('.cancel-action').click(function(event){
 		event.preventDefault();
 		document.location = backUrl;
 	});

})