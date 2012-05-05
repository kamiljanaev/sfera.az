var GridBaseUrl = '<?= $this->msgGridBaseUrl ?>'
$(function(){
	var rowNumCookieKey = 'news-row-num';
	var gridRowNum = $.cookie(rowNumCookieKey);
	if(gridRowNum == null){
		gridRowNum = 10;
	}
	var dataGrid = $("#jqgridtable").jqGrid({
		height: '400px',
	   	url: GridBaseUrl + 'list',
		datatype: "json",
	   	colNames:['<?= $this->msgGridColumnUser ?>', '<?= $this->msgGridColumnName ?>', '<?= $this->msgGridColumnIsReal ?>', '<?= $this->msgGridColumnIsVip ?>', '<?= $this->msgGridColumnAction ?>'],
	   	colModel:[
            {name:'user_id',index:'user_id', width:200, align:'center', stype:'select', editoptions:{value:'<?= $this->usersList ?>'}},
            {name:'lastname',index:'lastname', width:270},
            {name:'is_real',index:'is_real', width:100, align:"center", stype:'select', editoptions:{value:'<?= $this->realList ?>'}},
            {name:'is_vip',index:'is_vip', width:100, align:"center", stype:'select', editoptions:{value:'<?= $this->vipList ?>'}},
			{name:'act',index:'act', width:250, sortable:false, search: false, hidedlg:true}
	   	],
	   	shrinkToFit: false,
	   	autowidth: true,
	   	rowNum: gridRowNum,
	   	rowList:[10,20,30],
	   	imgpath: '/css/jqgrid/images',
	   	pager: $('#jqgridpager'),
	   	sortname: 'user_id',
		viewrecords: true,
		sortorder: "desc",
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
