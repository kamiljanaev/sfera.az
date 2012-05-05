var validTitle = false;
var validImage = true;
var validShortContent = false;
var validContent = true;

jQuery(document).ready(function($){
    $("#addNewsForm").submit(function(){
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

    function checkImage(value) {
        validImage = true;
    }

    function checkShortContent(value) {
        if (value.length > 0) {
            validShortContent = true;
            $("#shortContentCorrectValue").hide();
        } else {
            validShortContent = false;
            $("#shortContentCorrectValue").show();
        }
    }

    function checkContent(value) {
        if (value.length > 0) {
            validContent = true;
            $("#contentCorrectValue").hide();
        } else {
            validContent = false;
            $("#contentCorrectValue").show();
        }
    }

    function validateForm (request) {
        checkTitle($('#title').val(), request);
        checkImage($('#image').val(), request);
        checkShortContent($('#short_content').val(), request);
//        checkContent($('#content').val(), request);
        return validTitle && validImage && validShortContent && validContent;
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
            case 'image': {
                checkImage(targetValue);
                break;
            }
            case 'short_content': {
                checkShortContent(targetValue);
                break;
            }
            case 'content': {
//                checkContent(targetValue, true);
                break;
            }
        }
    })

    validateForm(true);
});