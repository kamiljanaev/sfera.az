$(function () {
	var treeBaseUrl = "/admin/module/content/category/";
	var options_form = {
		dataType: 'json',
		success: addFormResponse,
		error: function (data, status, e){
			showErrorMessagesPool(e);
		}
	};

	$("#category_tree").jstree({
        "plugins" : [ "themes", "json_data", "ui", "crrm", "dnd", "search", "types", "hotkeys", "cookies" ],
			"ui" : {
				"select_limit" : 1
			},
			"json_data" : {
				"ajax" : {
					"url" : treeBaseUrl + 'fetch',
					"data" : function (n) {
						return {
							"parent_id" : n.attr ? n.attr("id").replace("node_","") : 0
						};
					}
				}
			}
		})
//		.bind("load_node.jstree", function (e, data) {
//			alert('loaded');
//		})
		.bind("select_node.jstree", function (e, data) {
			startLoading();
			$('#category_data').load(
				treeBaseUrl + 'load/id/' + data.rslt.obj.attr('id'),
				{},
				function(){
					bindFormActions()
				}
			);
		})
		.bind("create.jstree", function (e, data) {
			$.post(
				treeBaseUrl + 'add',
				{
					"parent_id" : data.rslt.parent.attr("id").replace("node_",""),
					"order" : data.rslt.position,
					"title" : data.rslt.name
				},
				function (r) {
					if(r.status) {
						$(data.rslt.obj).attr("id", r.answer.id);
						$(data.rslt.obj).attr("href", treeBaseUrl + 'edit/id/'+r.answer.id);
						$("#category_tree").jstree('select_node', data.rslt.obj, true);
					}
					else {
						$.jstree.rollback(data.rlbk);
					}
				}
			);
		})
		.bind("remove.jstree", function (e, data) {
			data.rslt.obj.each(function () {
				$.ajax({
					async : false,
					type: 'POST',
					url: treeBaseUrl + 'delete',
					data : {
						"id" : this.id.replace("node_","")
					},
					success : function (r) {
						if(!r.status) {
							data.inst.refresh();
						}
					}
				});
			});
		})
		.bind("move_node.jstree", function (e, data) {
			$.post(
				"/admin/module/content/category/move",
				{
					"id" : data.rslt.o.attr("id").replace("node_",""),
					"pid" : data.rslt.np.attr("id").replace("node_",""),
					"pos" : data.rslt.cp
				},
				function (r) {
					if(!r.code) {
						$.jstree.rollback(data.rlbk);
						showErrorMessagesPool(r.messages);
					} else {
						showNotifyMessagesPool(r.messages);
					}
				}
			);
		})
		.bind("rename.jstree", function (e, data) {
			$.post(
				"/admin/module/content/category/edit",
				{
					"id" : data.rslt.obj.attr("id").replace("node_",""),
					"title" : data.rslt.new_name
				},
				function (r) {
					if(!r.status) {
						$.jstree.rollback(data.rlbk);
					}
				}
			);
		});

	function bindFormActions() {
		$('#add-form').submit(function(){
			$('#add-form').ajaxSubmit(options_form);
			startLoading();
			return false;
		});
		$('.save-action').unbind('click').click(function(event){
			event.preventDefault();
			$('#add-form').submit();
		});
		$('.delete-action').unbind('click').click(function(event){
			event.preventDefault();
			if(confirm('Вы уверены что хотите удалить элемент?')){
				$selected_node = $("#category_tree").jstree('get_selected');
				deleteNode($selected_node.attr('id'));
			}
		});
		$('.submit').show();
		$('.delete').show();
		$('#category_data').removeClass('loading_data');
		$('#urlsArray').change(function(){
			$('input#url').val($(this).val());
		})
		$('input#url').change(function(){
			$('select#urlsArray').val('');
		})
	}

	function startLoading() {
		$('#category_data').text('');
		$('#category_data').addClass('loading_data');
		$('.submit').hide();
		$('.delete').hide();
	}

	$('.submit').hide();
	$('.delete').hide();

	function addFormResponse(data) {
		$('#category_data').removeClass('loading_data');
		$('.submit').show();
		$('.delete').show();
		$("#category_tree").jstree('refresh', -1);
		if(data.code == 1){
			showNotifyMessagesPool(data.messages);
		} else {
			showErrorMessagesPool(data.messages);
		}
 	}

	function deleteNode(nodeId) {
		var url = treeBaseUrl + 'delete/id/' + nodeId;
		$.getJSON(
			url,
			function(response){
				if(response.code == 1){
					$('#category_data').text('');
					$('.submit').hide();
					$('.delete').hide();
					$("#category_tree").jstree('refresh', -1);
					showNotifyMessagesPool(response.messages);
				} else {
					showErrorMessagesPool(response.messages);
				}
			}
		)
	}

	$('a.add-action').click(function (e) {
		e.preventDefault();
        startLoading();
		$('#category_data').load(
			treeBaseUrl + 'add',
			{},
			function(){
				bindFormActions()
			}
		);
	})

});
