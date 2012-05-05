jQuery(document).ready(function($) {

    $('.fav-link').click(function(e) {
        e.preventDefault();
        var currentUrl = $(this);
        $.ajax({
            url: currentUrl.attr('href'),
            data: {id: currentUrl.attr('item-id'), type: currentUrl.attr('item-type')},
            method: 'post',
            dataType: 'json',
            success: function(resp) {
                if (resp.result) {
                    if (currentUrl.hasClass('favorites')) {
                        var parentNode = currentUrl.parents().get(0);
                        currentUrl.remove();
                        parentNode.innerHTML = 'In favorites';
                    }
                }
            }
        })
    });

    $('.ajax_follow').click(function(e) {
        e.preventDefault();
        var currentUrl = $(this);
        var url = currentUrl.attr('href');
        $.ajax({
            url: url,
            dataType: 'json',
            success: function(resp) {
                if (resp.result) {
                    if (currentUrl.attr('data-type') == 'follow') {
                        $('a.ajax_follow[data-id="' + currentUrl.attr('data-id') + '"]').each(function() {
                            $(this).text('Unfollow');
                            $(this).attr('href', url.replace(/add/, 'remove'));
                            $(this).attr('data-type', 'unfollow');
                        })
                        currentUrl.text('Unfollow');
                        currentUrl.attr('href', url.replace(/add/, 'remove'));
                        currentUrl.attr('data-type', 'unfollow');
                    } else {
                        $('a.ajax_follow[data-id="' + currentUrl.attr('data-id') + '"]').each(function() {
                            $(this).text('Follow');
                            $(this).attr('href', url.replace(/remove/, 'add'));
                            $(this).attr('data-type', 'follow');
                        })
                        currentUrl.text('Follow');
                        currentUrl.attr('href', url.replace(/remove/, 'add'));
                        currentUrl.attr('data-type', 'follow');
                    }
                }
            }
        })
    });

    $('.ajax_follow_tag').click(function(e) {
        e.preventDefault();
        var currentUrl = $(this);
        var url = currentUrl.attr('href');
        var linkText = '';
        $.ajax({
            url: url,
            data: {id: currentUrl.attr('item-id'), type: currentUrl.attr('item-type')},
            method: 'post',
            dataType: 'json',
            success: function(resp) {
                if (resp.result) {
                    if (currentUrl.attr('item-type') == 'follow') {
                        $('a.ajax_follow_tag[item-id="' + currentUrl.attr('item-id') + '"]').each(function() {
                            linkText = $(this).text();
                            $(this).text(linkText.replace(/Follow/, 'Unfollow'));
                            $(this).attr('item-type', 'unfollow');
                        })
                    } else {
                        $('a.ajax_follow_tag[item-id="' + currentUrl.attr('item-id') + '"]').each(function() {
                            linkText = $(this).text();
                            $(this).text(linkText.replace(/Unfollow/, 'Follow'));
                            $(this).attr('item-type', 'follow');
                        })
                    }
                }
            }
        })
    });

    $('#showAllProfiles').click(function(e) {
        e.preventDefault();
        $('.offlineuser').show();
        $(this).parents('li:first').addClass('state-active');
        $('#showOnlineProfiles').parents('li:first').removeClass('state-active');
    });

    $('#showOnlineProfiles').click(function(e) {
        e.preventDefault();
        $('.offlineuser').hide();
        $(this).parents('li:first').addClass('state-active');
        $('#showAllProfiles').parents('li:first').removeClass('state-active');
    });

    $('#showOnlineProfiles').text('Online (' + $('.onlineuser').length + ')');

    $('.ratingtrigger').click(function(e) {
        e.preventDefault();
        var currentUrl = $(this);
        $.ajax({
            url: currentUrl.attr('href'),
            method: 'get',
            dataType: 'json',
            success: function(resp) {
                $('.do-rating span.value').text(resp.result);
            }
        })
    });

    $('.reply').click(function(e) {
        e.preventDefault();
        var currentUrl = $(this);
        $("#addCommentForm").append('<input type="hidden" name="parent_id" id="comment_parent_id" value="' + currentUrl.attr('parent-id') + '">');
        $(".add-comment h4").text('Reply to comment');
        $(".add-comment .head").append('<a href="#" id="resetReply">Reset reply</a>');
        $('#resetReply').click(function(e) {
            e.preventDefault();
            $("#comment_parent_id").remove();
            $(".add-comment h4").text('Add comment');
            $("#resetReply").remove();
        })
    });

    $('#toggle-tags-block').click(function(e) {
        e.preventDefault();
        $('#other-tags-link-block').slideToggle();
        if ($('#other-tags-link-block').is(':visible')) {
            $(this).text('Hide all tags');
        } else {
            $(this).text('Show all tags');
        }
    });

    $('.tag-switch a').click(function(e) {
        e.preventDefault();
        $('.tags-list-sorted-content').hide();
        $('#' + $(this).attr('relation')).show();
        $('.tag-switch li').removeClass('state-active');
        $(this).parent().addClass('state-active');
    });

    $(".selectBox").selectBox();


    $("#complain-response").dialog({
        autoOpen:false,
        buttons: {
            "Close": function() {
                $(this).dialog("close");
            }
        }
    });

    $("#complain-error").dialog({
        autoOpen:false,
        buttons: {
            "Close": function() {
                $(this).dialog("close");
            }
        }
    });

    $(".do-complain").click(function(e) {
        e.preventDefault();
        var currentUrl = $(this);
        var complainDialog = $("#complain").dialog({
            autoOpen:true,
            buttons: {
                "Complain":function() {
                    $.ajax({
                        url: currentUrl.attr('href'),
                        data: {item_id: currentUrl.attr('item-id'), type_id: currentUrl.attr('item-type'), complain: $('#complainValue').val()},
                        method: 'post',
                        dataType: 'json',
                        success: function(resp) {
                            complainDialog.dialog("close");
                            if (resp.result) {
                                $("#complain-response").dialog("open");
                            } else {
                                $("#complain-error").dialog("open");
                            }
                        }
                    })
                },
                "Cancel":function() {
                    complainDialog.dialog("close");
                }
            }
        });
//        $("#complain").dialog("open");
    });
});