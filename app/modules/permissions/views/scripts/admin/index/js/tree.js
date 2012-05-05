var role_param = '';
var script_role_id = '<?= $this->currentRole ?>'
$(function () {
    if(script_role_id){
        role_param = '/role_id/'+script_role_id;
    };

    $("#modules_tree").jstree({
        "plugins" : [ "themes", "html_data", "ui", "crrm", "types"],
        "ui" : {
            "select_limit" : 1
        },
        "html_data" : {
            "ajax" : {
                "url" : '/admin/module/permissions/index/fetch',
                "data" : function (n) {
                    return {
                        role_id : script_role_id
                    };
                }
            }
        }
    }).bind("load_node.jstree", function (e, data) {
        $('input.check_access').click(function(event){
            var url = '/admin/module/permissions/index/module/role_id/' + script_role_id + '/action_id/' + $(this).val() + '/permit/' + $(this).attr('checked') + '/format/json/';
            $(this).attr("disabled","disabled");
            $.getJSON(
                url,
                function(response){
                    $('input#check-' + response.actionId).removeAttr("disabled");
                    if(response.code == 1){
                        showNotifyMessagesPool(response.messages);
                    } else {
                        event.preventDefault();
                        showErrorMessagesPool(response.messages);
                    }
                }
            )
        });
    })


    $('select#rolesSelect').change(function(event){
        script_role_id = $(this).val();
        role_param = '/role_id/'+$(this).val();
        $("#modules_tree").jstree('refresh', -1);
     });


});