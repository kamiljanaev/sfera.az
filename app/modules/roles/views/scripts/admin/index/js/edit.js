var backUrl = '<?= $this->msgBackUrl ?>'
$(function(){
	var validator = $("#add-form").validate({
		onkeyup: false,
		onclick: false,
		rules: {
		  	name: {
				required: true,
				rangelength: [3, 255]
			}
		},
		messages: {
			name: {
				required: '<?= $this->msgValidationEmptyValue ?>',
				rangelength: '<?= $this->msgValidationShortValue ?>'
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