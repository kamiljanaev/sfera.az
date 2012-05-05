$().ready(function() {
    $('textarea.tinymce').tinymce({
        script_url : '/js/libs/tinymce/tiny_mce.js',
        theme : "simple",
        content_css : "/css/site.css"
    });
});
