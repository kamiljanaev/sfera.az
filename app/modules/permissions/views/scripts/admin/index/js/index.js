var backUrl = '<?= $this->msgBackUrl ?>'

$(function(){
	$('.install-button').click(function(event){
		event.preventDefault();
		installModules();
 	});

    $('.cancel-action').click(function(event){
		event.preventDefault();
		document.location = backUrl;
	});

    $('.clear-cache-action').click(function(event){
		event.preventDefault();
		clearCache();
	});


	function installModules(){
        $.getJSON(
            baseUrl + '/admin/install/index/format/json',
			function ( response ) {
   				if(response.code == 1){
   					showNotifyMessagesPool(response.messages);
                    $("#modules_tree").jstree('refresh', -1);
   				} else {
   					showErrorMessagesPool(response.messages);
   				}
			}
 		);
	}

	function clearCache(){
        $.getJSON(
            baseUrl + '/admin/module/permissions/index/clear-cache',
			function ( response ) {
   				if(response.code == 1){
   					showNotifyMessagesPool(response.messages);
					window.location.reload();
   				} else {
   					showErrorMessagesPool(response.messages);
   				}
			}
 		);
	}

})