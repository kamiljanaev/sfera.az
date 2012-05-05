var GridBaseUrl = '<?= $this->msgGridBaseUrl ?>'
$(function(){
	var rowNumCookieKey = 'document-row-num';
	var gridRowNum = $.cookie(rowNumCookieKey);
	if(gridRowNum == null){
		gridRowNum = 10;
	}
	var dataGrid = $("#jqgridtable").jqGrid({
		height: '400px',
	   	url: GridBaseUrl + 'list',
		datatype: "json",
	   	colNames:['<?= $this->msgGridColumnUser ?>', '<?= $this->msgGridColumnStatus ?>', '<?= $this->msgGridColumnAction ?>'],
	   	colModel:[
            {name:'user_id',index:'user_id', width:400, align:'center', stype:'select', editoptions:{value:'<?= $this->usersList ?>'}},
            {name:'status',index:'status', width:150, align:"center", stype:'select', editoptions:{value:'<?= $this->newList ?>'}},
			{name:'act',index:'act', width:150, sortable:false, search: false, hidedlg:true}
	   	],
	   	shrinkToFit: false,
	   	autowidth: true,
	   	rowNum: gridRowNum,
	   	rowList:[10,20,30],
	   	imgpath: '/css/jqgrid/images',
	   	pager: $('#jqgridpager'),
	   	sortname: 'user_id',
		viewrecords: true,
		sortorder: "asc",
		loadComplete: function(){
			var ids = $("#jqgridtable").getDataIDs();
			for(var i=0;i<ids.length;i++){
				var id = ids[i];
                view = '<span class="button"><a name="' + id + '" href="' + GridBaseUrl + 'view/id/' + id + '" id="view-' + id + '" class="badge view" title="<?= $this->msgLabelView ?>"><span><?= $this->msgLabelView ?></span></a></span> ';
                del = '<span class="button"><a name="' + id + '" href="' + GridBaseUrl + 'delete/id/' + id + '/format/json" id="delete-' + id + '" class="badge delete" title="<?= $this->msgLabelDelete ?>"><span><?= $this->msgLabelDelete ?></span></a></span>';
				$("#jqgridtable").setRowData(ids[i],{act:view+del});
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
            $('.notActivated').parent('td').css("background-color","#ff9");
            $('.blocked').parent('td').css("background-color","#f99");
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
