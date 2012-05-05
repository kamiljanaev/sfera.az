var validTitle = false;
var validUrl = true;

jQuery(document).ready(function($){
    $("#addBookmarksForm").submit(function(){
        if (validateForm(false)) {
            return true;
        } else {
            return false;
        }
    });

    function checkTitle(value) {
        if (value.length > 0) {
            validTitle = true;
            $("#titleCorrectValue").hide();
        } else {
            validTitle = false;
            $("#titleCorrectValue").show();
        }
    }

    function checkUrl(value) {
        if (value.length > 0) {
            validUrl = true;
            $("#urlCorrectValue").hide();
        } else {
            validUrl = false;
            $("#urlCorrectValue").show();
        }
    }

    function validateForm (request) {
        checkTitle($('#title').val(), request);
        checkUrl($('#url').val(), request);
        return validTitle && validUrl;
    }

    $('.formItems').blur(function(event) {
        var
            targetItem = $(event.target),
            targetId = targetItem.attr('id'),
            targetValue = targetItem.val();
        console.log(targetId);
        switch (targetId) {
            case 'title': {
                checkTitle(targetValue, true);
                break;
            }
            case 'url': {
                checkUrl(targetValue);
                break;
            }
        }
    })

    validateForm(true);
});