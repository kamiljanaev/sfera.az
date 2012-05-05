var validLogin = false;
var validServerLogin = false;
var validPassword = false;
var validRetypePassword = false;
var validEmail = false;
var validServerEmail = false;
var validPhone = false;
var validCaptcha = false;

jQuery(document).ready(function($){
    $("#password").password_strength();
    $("#password").showPassword();
    $("#retypepassword").showPassword();
    $("input[name=password-clone]").attr('id', 'password-clone');
    $("#registerForm").submit(function(){
        if (validateForm(false)) {
            return true;
        } else {
            return false;
        }
    });

    $('#showpassword').click(function(e){
        if ($(this).is(':checked')) {
            $('.retypepwdcontainer').hide();
        } else {
            $('.retypepwdcontainer').show();
            validPassword = false;
            validRetypePassword = false;
        }
    })

    function checkLogin(value, request) {
        var re = /(\w+)/;
        if (value.match(re)) {
            validLogin = true;
            $("#loginCorrectValue").hide();
        } else {
            validLogin = false;
            $("#loginCorrectValue").show();
        }
        if (value.length&&request) {
            $.ajax({
                url: "/profile/check/login",
                type: "post",
                cache: false,
                data: {
                    value: value
                },
                success: function(resp) {
                    if (resp == "true") {
                        validServerLogin = true;
                        $("#loginInCorrectMessage").hide();
                        $("#loginCorrectMessage").show();
                    } else {
                        validServerLogin = false;
                        $("#loginCorrectMessage").hide();
                        $("#loginInCorrectMessage").show();
                    }
                }
            })
        }
    }

    function checkEmail(value, request) {
        var re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        if (value.match(re)) {
            validEmail = true;
            $("#emailCorrectValue").hide();
        } else {
            validEmail = false;
            $("#emailCorrectValue").show();
        }
        if (value.length&&request&&validEmail) {
            $.ajax({
                url: "/profile/check/email",
                type: "post",
                cache: false,
                data: {
                    value: value
                },
                success: function(resp) {
                    if (resp == "true") {
                        validServerEmail = true;
                        $("#emailInCorrectMessage").hide();
                        $("#emailCorrectMessage").show();
                    } else {
                        validServerEmail = false;
                        $("#emailInCorrectMessage").show();
                        $("#emailCorrectMessage").hide();
                    }
                }
            })
        }
    }

    function checkPassword(value) {
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
    }

    function checkVisiblePassword(value) {
            if (value.length >= 8) {
                validPassword = true;
                $("#pwdCorrectValue").hide();
            } else {
                validPassword = false;
                $("#pwdCorrectValue").show();
            }
            validRetypePassword = true;
    }

    function checkRePassword(value) {
        if (value == $('#password').val()) {
            validRetypePassword = true;
            $("#rePwdCorrectValue").hide();
        } else {
            validRetypePassword = false;
            $("#rePwdCorrectValue").show();
        }
    }

    function checkPhone(value) {
        if (value.length >= 5) {
            validPhone = true;
            $("#phoneCorrectValue").hide();
        } else {
            validPhone = false;
            $("#phoneCorrectValue").show();
        }
    }

    function checkCaptcha(value) {
        if (value.length > 0) {
            validCaptcha = true;
            $("#captchaInCorrectValue").hide();
            $("#captchaCorrectValue").show();
        } else {
            validCaptcha = false;
            $("#captchaCorrectValue").hide();
            $("#captchaInCorrectValue").show();
        }
    }


    function validateForm (request) {
        checkLogin($('#login').val(), request);
        if ($('#showpassword').is(':checked')) {
            checkVisiblePassword($('#password-clone').val());
        } else {
            checkPassword($('#password').val());
            checkRePassword($('#retypepassword').val());
        }
        checkEmail($('#email').val(), request);
        checkPhone($('#phone').val());
        checkCaptcha($('#captcha').val());
        return validLogin && validServerLogin && validPassword && validRetypePassword && validEmail && validServerEmail && validPhone && validCaptcha;
    }

    $('input.formItems').blur(function(event) {
        var
            targetItem = $(event.target),
            targetId = targetItem.attr('id'),
            targetValue = targetItem.val();
        switch (targetId) {
            case 'login': {
                checkLogin(targetValue, true);
                break;
            }
            case 'password': {
                checkPassword(targetValue);
                break;
            }
            case 'password-clone': {
                checkVisiblePassword(targetValue);
                break;
            }
            case 'retypepassword': {
                checkRePassword(targetValue);
                break;
            }
            case 'email': {
                checkEmail(targetValue, true);
                break;
            }
            case 'phone': {
                checkPhone(targetValue);
                break;
            }
            case 'captcha': {
                checkCaptcha(targetValue);
                break;
            }
        }
    })

    validateForm(true);
});