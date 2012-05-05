var backUrl = '<?= $this->msgBackUrl ?>'
var currentId = null;
<?php if ($this->itemRow->id): ?>
    var currentId = <?= $this->itemRow->id ?>;
<?php endif; ?>
$(function(){
	var validator = $("#add-form").validate({
		onkeyup: false,
		onclick: false,
		rules: {
		  	title: {
				required: true,
				rangelength: [1, 255]
			},
            logo: {
                required: true
            }
		},
		messages: {
		  	title: {
				required: '<?= $this->msgValidationEmptyValue ?>',
				rangelength: '<?= $this->msgValidationShortValue ?>'
            },
            logo: {
                required: '<?= $this->msgValidationEmptyValue ?>'
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

	var opts = {
		lang: 'ru',
		styleWithCss: false,
		height: 200,
		toolbar: 'complete',
		fmAllow: true,
		fmOpen : function(callback) {
			$('<div id="myelfinder" />').elfinder({
				url : '/files/elfinder/connector',
				lang : 'ru',
				dialog : { width : 900, modal : true, title : 'Files' },
				closeOnEditorCallback : true,
				editorCallback : callback
			})
		}
	};


	$('#browse').click(function(e){
		e.preventDefault();
		$('<div id="myelfinder" />').elfinder({
				url : '/files/elfinder/connector',
				lang : 'ru',
				dialog : { width : 900, modal : true, title : 'Files' },
			    places : '',
			    cutURL : '',
				closeOnEditorCallback : true,
				editorCallback : function(url) {
					$('input#logo').val(url);
				}
			})
	})
})