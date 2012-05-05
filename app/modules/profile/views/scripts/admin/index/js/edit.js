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
            public_date: {
                required: true
            },
            short_content: {
                required: true
            },
            content: {
                required: true
            }
		},
		messages: {
		  	title: {
				required: '<?= $this->msgValidationEmptyValue ?>',
				rangelength: '<?= $this->msgValidationShortValue ?>'
            },
            public_date: {
                required: '<?= $this->msgValidationEmptyValue ?>'
			},
            short_content: {
              required: '<?= $this->msgValidationEmptyValue ?>'
            },
            content: {
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
//	$('textarea#content').elrte(opts);

	var url = '/admin/module/content/utils/get-alias';
	if (currentId) {
		url = url + '/id/' + currentId;
	}
//	$('input#title').generateAlias({source: 'title', target: 'alias', button: 'regenerate', url: url});

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
					$('input#avatar').val(url);
				}
			})
	})
})