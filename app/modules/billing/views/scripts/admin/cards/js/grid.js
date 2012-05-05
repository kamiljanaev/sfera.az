var GridBaseUrl = '<?= $this->msgGridBaseUrl ?>'
$(function(){
	var rowNumCookieKey = 'cards-row-num';
	var gridRowNum = $.cookie(rowNumCookieKey);
	if(gridRowNum == null){
		gridRowNum = 10;
	}
	var dataGrid = $("#jqgridtable").jqGrid({
		height: '400px',
	   	url: GridBaseUrl + 'list',
		datatype: "json",
	   	colNames:['<?= $this->msgGridColumnNumber ?>', '<?= $this->msgGridColumnAmount ?>', '<?= $this->msgGridColumnIsUsed ?>', '<?= $this->msgGridColumnAction ?>'],
	   	colModel:[
               {name:'number',index:'number', width:240},
               {name:'amount',index:'amount', width:240},
               {name:'is_used',index:'is_used', width:240},
			{name:'act',index:'act', width:150, sortable:false, search: false, hidedlg:true}
	   	],
	   	shrinkToFit: false,
	   	autowidth: true,
	   	rowNum: gridRowNum,
	   	rowList:[10,20,30],
	   	imgpath: '/css/jqgrid/images',
	   	pager: $('#jqgridpager'),
	   	sortname: 'number',
	    viewrecords: true,
	    sortorder: "asc",
		loadComplete: function(){
			var ids = $("#jqgridtable").getDataIDs();
			for(var i=0;i<ids.length;i++){
				var id = ids[i];
				edit = '<span class="button"><a name="' + id + '" href="' + GridBaseUrl + 'edit/id/' + id + '" id="edit-' + id + '" class="badge edit" title="<?= $this->msgLabelEdit ?>"><span><?= $this->msgLabelEdit ?></span></a></span> ';
    			del = '<span class="button"><a name="' + id + '" href="' + GridBaseUrl + 'delete/id/' + id + '/format/json" id="delete-' + id + '" class="badge delete" title="<?= $this->msgLabelDelete ?>"><span><?= $this->msgLabelDelete ?></span></a></span>';
				$("#jqgridtable").setRowData(ids[i],{act:edit+del});

				$('a#delete-' + id).click(function(event) {
					event.preventDefault();
					if(!confirm('<?= $this->msgConfirmDeleteItem ?>')){
						return false;
					}
					var id = $(this).attr('name');
					var url = GridBaseUrl + 'delete/id/' + id + '/format/json/';
					$.getJSON(
						url,
						function(response){
							if(response.code == 1){
								showNotifyMessagesPool(response.messages);
								$("#jqgridtable").trigger("reloadGrid");
							} else {
								showErrorMessagesPool(response.messages);
							}
						}
					)
				});
			}
		},
	    caption:'<?= $this->msgGridListTitle ?>'
	}).navGrid('#jqgridpager',{edit:false,add:false,del:false,search:false});
	dataGrid.filterToolbar(); 
	$('select[role=listbox]').change(function(){
		$.cookie(rowNumCookieKey, $(this).val());
	});
	$("#jqgridtable").jqGrid('setGridHeight', getAvailableSpaceForGrid());
});
function getAvailableSpaceForGrid(){
    return  myLayout.panes.center.height() + myLayout.panes.center.offset().top - $("#jqgridtable").parent().parent().offset().top + $('#add-row:visible').height() + $('#import-row:visible').height() - $('.ui-jqgrid-pager').height() - 20;
}
