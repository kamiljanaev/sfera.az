var currentId = null;
$(function(){
	var validator = $("#add-form").validate({
		onkeyup: false,
		onclick: false,
		rules: {
		  	title: {
				required: true,
				rangelength: [1, 255]
			}
		},
		messages: {
		  	title: {
				required: 'Поле не может быть пустым',
				rangelength: 'Значение слишком короткое'
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