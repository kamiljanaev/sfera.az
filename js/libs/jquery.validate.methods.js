$().ready(function() {
    jQuery.validator.addMethod("checked", function(value, element) {
        var result = false;
        $("input:checked").each(function(){
            if($(this).attr('name') == element.name){
                result = true;
            }
        });
        return result;
    }, "Champ obligatoire.");

    jQuery.validator.addMethod("lettersDigits", function(value, element) {
        return this.optional(element) || ( /^\w+$/i.test(value) && /^[^\_]+$/i.test(value) );
    }, "Ce champ est uniquement alphanumérique.");

    jQuery.validator.addMethod("notDigits", function(value, element) {
        return /^[\D]+$/i.test(value);
    }, "Ce champ est uniquement alpha.");

    jQuery.validator.addMethod("phone", function(value, element) {
        return /^(\d{2}) (\d{2}) (\d{2}) (\d{2}) (\d{2})$/i.test(value);
    }, "Ce champ est uniquement alpha.");

    jQuery.validator.addMethod("notEqualTo", function(value, element) {
        return value !== jQuery(param).val();
    }, "Ce champ doit être différent du précédent.");

    jQuery.validator.addMethod("login", function (value,element) {
        return /^[a-zA-Z0-9\.\-@]+$/.test(value);
    }, 'Cet identifiant contient des caractères invalides.');

	jQuery.validator.addMethod("decimalTwo", function(value, element) {
	    return this.optional(element) || /^(\d{1,5})(\.{0,1}\d{0,2})$/.test(value);
	}, "Must be in US currency format 0.99");

    //Url validation enchanted
    jQuery.validator.addMethod("url",function (value,element) {
        if(value == ''){
            return true;
        }

        if(!/^(https?|ftp):\/\/(.?)+$/i.test(value)){
            value = "http://" + value;
        }
        return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
    });

    //	Default french method messages
jQuery.extend(jQuery.validator.messages, {
	        required: "Это поле необходимо заполнить.",
	        remote: "Пожалуйста, введите правильное значение.",
	        email: "Пожалуйста, введите корретный адрес электронной почты.",
	        url: "Пожалуйста, введите корректный URL.",
	        date: "Пожалуйста, введите корректную дату.",
	        dateISO: "Пожалуйста, введите корректную дату в формате ISO.",
	        number: "Пожалуйста, введите число.",
	        digits: "Пожалуйста, вводите только цифры.",
	        creditcard: "Пожалуйста, введите правильный номер кредитной карты.",
	        equalTo: "Пожалуйста, введите такое же значение ещё раз.",
	        accept: "Пожалуйста, выберите файл с правильным расширением.",
	        maxlength: jQuery.format("Пожалуйста, введите не больше {0} символов."),
	        minlength: jQuery.format("Пожалуйста, введите не меньше {0} символов."),
	        rangelength: jQuery.format("Пожалуйста, введите значение длиной от {0} до {1} символов."),
	        range: jQuery.format("Пожалуйста, введите число от {0} до {1}."),
	        max: jQuery.format("Пожалуйста, введите число, меньшее или равное {0}."),
	        min: jQuery.format("Пожалуйста, введите число, большее или равное {0}.")
});
//	Custom french method messages
    jQuery.validator.messages.lettersDigits = "Seuls les caractères alphanumériques sont autorisés.";
    jQuery.validator.messages.notDigits = "Seuls les caractères alpha sont autorisés.";
    jQuery.validator.messages.phone = "Veuillez indiquer le numéro de téléphone valide.";
    jQuery.validator.messages.notEqualTo = "Indiquez une valeur, différente du champ précédent.";
    jQuery.validator.messages.login = "Le pseudo contient des caractères interdits.";
    jQuery.validator.messages.checked = "Champ obligatoire.";
});