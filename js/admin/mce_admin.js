$().ready(function() {
    $('textarea.ck_editor_area').tinymce({
        script_url : '/js/libs/tinymce/tiny_mce.js',
        // General options
        theme : "advanced",
        plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

        // Theme options
        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        content_css : "/css/site.css",
        file_browser_callback: function(field_name, url, type, win) {
            aFieldName = field_name,aWin = win;

            if ($('#elfinder').length == 0) {
                $('body').append($('<div/>').attr('id', 'elfinder'));
                $('#elfinder').elfinder({
                    url : '/files/elfinder/connector',
                    dialog : { width: 900, modal: true, title: 'Files', zIndex: 400001 }, // open in dialog window
                    editorCallback: function(url) {
                        aWin.document.forms[0].elements[aFieldName].value = url;
                    },
                    closeOnEditorCallback: true
                });
            } else {
                $('#elfinder').elfinder('open');
            }
        }
    });
});
