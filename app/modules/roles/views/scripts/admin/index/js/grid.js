var GridBaseUrl = '<?= $this->msgGridBaseUrl ?>'
$(function(){
	var rowNumCookieKey = 'roles-row-num';
	var gridRowNum = $.cookie(rowNumCookieKey);
	if(gridRowNum == null){
		gridRowNum = 10;
	}
	var dataGrid = $("#jqgridtable").jqGrid({
		height: '400px',
	   	url: GridBaseUrl + 'list',
		datatype: "json",
	   	colNames:['<?= $this->msgGridColumnName ?>', '', '<?= $this->msgGridColumnAction ?>'],
	   	colModel:[
	   		{name:'name',index:'name', width:240},
			{name:'canDelete',index:'canDelete', width:0, sortable:false, search: false, hidedlg:true, hidden:true},		
			{name:'act',index:'act', width:150, sortable:false, search: false, hidedlg:true}		
	   	],
	   	shrinkToFit: false,
	   	autowidth: true,
	   	rowNum: gridRowNum,
	   	rowList:[10,20,30],
	   	imgpath: '/css/jqgrid/images',
	   	pager: $('#jqgridpager'),
	   	sortname: 'name',
	    viewrecords: true,
	    sortorder: "asc",
		loadComplete: function(){
			var ids = $("#jqgridtable").getDataIDs();
			for(var i=0;i<ids.length;i++){
				var id = ids[i];
				edit = '<span class="button"><a name="' + id + '" href="' + GridBaseUrl + 'edit/id/' + id + '" id="edit-' + id + '" class="badge edit" title="<?= $this->msgLabelEdit ?>"><span><?= $this->msgLabelEdit ?></span></a></span> ';
				if (Number($("#jqgridtable").getRowData(id).canDelete) == 0)
					del = '<span class="button"><a name="' + id + '" href="' + GridBaseUrl + 'delete/id/' + id + '/format/json" id="delete-' + id + '" class="badge delete" title="<?= $this->msgLabelDelete ?>"><span><?= $this->msgLabelDelete ?></span></a></span>';
				else
					del = '<span class="alreadyUsed"><?= $this->msgRoleIsUsed ?></span>';
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
                        jsonSuccessResponse
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

    function jsonSuccessResponse(response) {
        if(response.code == 1){
            showNotifyMessagesPool(response.messages);
            $("#jqgridtable").trigger("reloadGrid");
        } else {
            showErrorMessagesPool(response.messages);
        }
    }
});
function getAvailableSpaceForGrid(){
    return  myLayout.panes.center.height() + myLayout.panes.center.offset().top - $("#jqgridtable").parent().parent().offset().top + $('#add-row:visible').height() + $('#import-row:visible').height() - $('.ui-jqgrid-pager').height() - 20;
}
