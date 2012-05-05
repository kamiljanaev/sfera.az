$(function(){
	var options_form = {
		dataType: 'json',
		success: addFormResponse,
		error: function (data, status, e){
			showErrorMessagesPool(e);
		}
	};
	
	var validator = $("#add-form").validate({
		onkeyup: false,
		onclick: false,
		rules: {
		  	email: {
				required: true,
                email: true
			},
			password: {
				required: true,
				rangelength: [8, 255]
			},
			password_conf: {
				equalTo: "#password"
			}
		},
		messages: {
			email: {
                required: '<?= $this->msgValidationEmptyValue ?>',
                email: '<?= $this->msgValidationIncorrectEmail ?>'
			},
			password: {
				required: '<?= $this->msgValidationEmptyPwd ?>',
				rangelength: '<?= $this->msgValidationShortPwd ?>'
			},								
			password_conf: {
				equalTo: '<?= $this->msgValidationPwdConfirmError ?>'
			}								
		}
	});

 	$('#add-form').submit(function(){
 		if(($(this).valid())){
			stateButtons(false);
 			$('#add-form').ajaxSubmit(options_form);
 		}
 		return false;
 	});

 	$('.add-action').click(function(event){
 		event.preventDefault();
 		$('#add-form').submit();
 	});

 	$('.button .cancel-action').click(function(event){
 		event.preventDefault();
        $('span#add-button').show();
        $('span#cancel-button').hide();
        $('span#submit-button').hide();
        $('#add-row').hide();
		$('#add-form').resetForm();	
 	});

	$('.button .add-button').click(function(event){
		event.preventDefault();
        $('span#add-button').hide();
        $('span#cancel-button').show();
        $('span#submit-button').show();
        $('#add-row').show();
 	});

 	function stateButtons(flag) {
		if (flag) {
			$('.button .add-action').removeAttr('disabled');
			$('.button .cancel-action').removeAttr('disabled');
		} else {
			$('.button .add-action').attr('disabled', 'true');
			$('.button .cancel-action').attr('disabled', 'true');
		}																																
 	}
 	
 	function addFormResponse(data) {
 		stateButtons(true);
		if(data.code == 1){
	 		$("#jqgridtable").trigger("reloadGrid");
			$('#add-row').hide();
            $('span#add-button').show();
            $('span#cancel-button').hide();
            $('span#submit-button').hide();
			$('#add-form').resetForm();	
			showNotifyMessagesPool(data.messages);
		} else {
			data.messages.push({"error":'<?= $this->msgUserNotAdded ?>'});
			showErrorMessagesPool(data.messages);
			validator.showErrors(data.response);
		}
 	}

	$('#add-form').resetForm();	

    $('span#cancel-button').hide();
    $('span#submit-button').hide();

	$('#add-row').hide();
})
