
jQuery(document).ready(function($){

    var validEmail = false;
    var validServerEmail = false;
    var validCurrentPassword = false;
    var validPassword = false;
    var validRetypePassword = false;
    var validNickname = false;
    var validFirstname = false;
    var validLastname = false;

        $("#editForm").submit(function(){
            if (validateForm(false)) {
                return true;
            } else {
                return false;
            }
        });

        function checkEmail(value, request) {
            var re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            if (value.match(re)) {
                validEmail = true;
                $("#emailServerInCorrectValue").hide();
                $("#emailInCorrectValue").hide();
            } else {
                validEmail = false;
                $("#emailServerInCorrectValue").hide();
                $("#emailInCorrectValue").show();
            }
            if (value.length&&request) {
                $.ajax({
                    url: "/profile/check/email",
                    type: "post",
                    cache: false,
                    data: {
                        value: value,
                        exclude: currentUserId
                    },
                    success: function(resp) {
                        if (resp == "true") {
                            validServerEmail = true;
                            $("#emailInCorrectValue").hide();
                            $("#emailServerInCorrectValue").hide();
                        } else {
                            validServerEmail = false;
                            $("#emailInCorrectValue").hide();
                            $("#emailServerInCorrectValue").show();
                        }
                    }
                })
            }
        }

        function checkPassword(value) {
            if (value.length > 0) {
                if (value.length >= 8) {
                    validPassword = true;
                    $("#pwdCorrectValue").hide();
                } else {
                    validPassword = false;
                    $("#pwdCorrectValue").show();
                }
                if (value == $('#retypepassword').val()) {
                    validRetypePassword = true;
                    $("#rePwdCorrectValue").hide();
                } else {
                    validRetypePassword = false;
                    $("#rePwdCorrectValue").show();
                }
            } else {
                validPassword = true;
                $("#pwdCorrectValue").hide();
            }
        }

        function checkRePassword(value) {
            if (value == $('#newpassword').val()) {
                validRetypePassword = true;
                $("#rePwdCorrectValue").hide();
            } else {
                validRetypePassword = false;
                $("#rePwdCorrectValue").show();
            }
        }

        function checkCurrentPassword(value) {
            if (($('#newpassword').val() != '')&&!value.length) {
                validCurrentPassword = false;
                $("#currentPwdCorrectValue").hide();
                $("#currentPwdCorrectLength").show();
            } else {
                validCurrentPassword = true;
                $("#currentPwdCorrectValue").hide();
                $("#currentPwdCorrectLength").hide();
            }
        }

        function checkNickname(value) {
            if (value.length > 0) {
                validNickname = true;
                $("#aliasInCorrectValue").hide();
            } else {
                validNickname = false;
                $("#aliasInCorrectValue").show();
            }
        }

        function checkFirstname(value) {
            if (value.length > 0) {
                validFirstname = true;
                $("#firstnameInCorrectValue").hide();
            } else {
                validFirstname = false;
                $("#firstnameInCorrectValue").show();
            }
        }

        function checkLastname(value) {
            if (value.length > 0) {
                validLastname = true;
                $("#lastnameInCorrectValue").hide();
            } else {
                validLastname = false;
                $("#lastnameInCorrectValue").show();
            }
        }

        function validateForm (request) {
            if ($('#email').length) {
                checkEmail($('#email').val(), request);
                checkCurrentPassword($('#currentpassword').val());
                checkPassword($('#newpassword').val());
                checkRePassword($('#retypepassword').val());
                checkNickname($('#alias').val());
                checkFirstname($('#firstname').val());
                checkLastname($('#lastname').val());
            }
            return validCurrentPassword && validPassword && validRetypePassword && validEmail && validNickname && validFirstname && validLastname;
        }


        $('input.formItems').blur(function(event) {
            var
                targetItem = $(event.target),
                targetId = targetItem.attr('id'),
                targetValue = targetItem.val();
            switch (targetId) {
                case 'email': {
                    checkEmail(targetValue, true);
                    break;
                }
                case 'alias': {
                    checkNickname(targetValue, true);
                    break;
                }
                case 'newpassword': {
                    checkPassword(targetValue);
                    break;
                }
                case 'currentpassword': {
                    checkPassword(targetValue);
                    break;
                }
                case 'retypepassword': {
                    checkRePassword(targetValue);
                    break;
                }
                case 'firstname': {
                    checkFirstname(targetValue);
                    break;
                }
                case 'lastname': {
                    checkLastname(targetValue);
                    break;
                }
            }
        })
});