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
		  	number: {
				required: true,
				rangelength: [3, 255]
			},
            amount: {
                required: true,
                digits: true
            }
		},
		messages: {
			number: {
				required: '<?= $this->msgValidationEmptyValue ?>',
				rangelength: '<?= $this->msgValidationShortValue ?>'
			},
            amount: {
                required: '<?= $this->msgValidationEmptyValue ?>',
                digits: '<?= $this->msgValidationIncorrectDigits ?>'
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

 	$('.cancel-action').click(function(event){
 		event.preventDefault();
        $('span#add-button').show();
        $('span#cancel-button').hide();
        $('span#submit-button').hide();
        $('#add-row').hide();
		$('#add-form').resetForm();	
 	});

	$('.add-button').click(function(event){
		event.preventDefault();
        $('span#add-button').hide();
        $('span#cancel-button').show();
        $('span#submit-button').show();
        $('#add-row').show();
 	});
 	
 	function stateButtons(flag) {
		if (flag) {
			$('.add-action').removeAttr('disabled');
			$('.cancel-action').removeAttr('disabled');
		} else {
			$('.add-action').attr('disabled', 'true');
			$('.cancel-action').attr('disabled', 'true');
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
			validator.showErrors(data.messages[0]);
			showErrorMessagesPool(data.messages);
		}
 	}

    $('span#cancel-button').hide();
    $('span#submit-button').hide();

	$('#add-row').hide();

})