var validTitle = false;

jQuery(document).ready(function($){
    $("#addBookmarksCategoryForm").submit(function(){
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

    function validateForm (request) {
        checkTitle($('#title').val(), request);
        return validTitle;
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
        }
    })

    validateForm(true);
});