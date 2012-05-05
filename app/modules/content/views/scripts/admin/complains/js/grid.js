var GridBaseUrl = '<?= $this->msgGridBaseUrl ?>'
$(function(){
	var rowNumCookieKey = 'complains-row-num';
	var gridRowNum = $.cookie(rowNumCookieKey);
	if(gridRowNum == null){
		gridRowNum = 10;
	}
	var dataGrid = $("#jqgridtable").jqGrid({
		height: '400px',
	   	url: GridBaseUrl + 'list',
		datatype: "json",
	   	colNames:['<?= $this->msgGridColumnType ?>', '<?= $this->msgGridColumnItem ?>', '<?= $this->msgGridColumnProfile ?>', '<?= $this->msgGridColumnComplain ?>', '<?= $this->msgGridColumnAction ?>'],
	   	colModel:[
	   		{name:'type_id',index:'type_id', width:100, align:"center", stype:'select', editoptions:{value:'<?= $this->typesList ?>'}},
            {name:'item_id',index:'item_id', width:250, align:'center', search: false},
            {name:'profile_id',index:'profile_id', width:250, align:'center', search: false},
            {name:'complain',index:'complain', width:270},
			{name:'act',index:'act', width:100, sortable:false, search: false, hidedlg:true}
	   	],
	   	shrinkToFit: false,
	   	autowidth: true,
	   	rowNum: gridRowNum,
	   	rowList:[10,20,30],
	   	imgpath: '/css/jqgrid/images',
	   	pager: $('#jqgridpager'),
	   	sortname: 'type_id',
		viewrecords: true,
		sortorder: "desc",
		loadComplete: function(){
			var ids = $("#jqgridtable").getDataIDs();
			for(var i=0;i<ids.length;i++){
				var id = ids[i];
                del = '<span class="button"><a name="' + id + '" href="' + GridBaseUrl + 'delete/id/' + id + '/format/json" id="delete-' + id + '" class="badge delete" title="<?= $this->msgLabelDelete ?>"><span><?= $this->msgLabelDelete ?></span></a></span>';
				$("#jqgridtable").setRowData(ids[i],{act:del});
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
	return  myLayout.panes.center.height() - $('.ui-widget-header').height() - $('.ui-jqgrid-hdiv').height() - $('.ui-jqgrid-pager').height() - $('#head').height() - $('#add-button-row').height() - 55;
}
