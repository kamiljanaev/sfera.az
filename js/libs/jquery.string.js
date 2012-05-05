(function($) {
    $.fn.uppercase = function(options) {
        return this.each(function() {
            $element = $(this);
            $element.keyup(function() {
                var str = $(this).val();
                $(this).val(str.toUpperCase());
            });
        });
    };

    $.fn.lowercase = function(options) {
        return this.each(function() {
            $element = $(this);
            $element.keyup(function() {
                var str = $(this).val();
                $(this).val(str.toLowerCase());
            });
        });
    };

    $.fn.ucfirst = function(options) {
        return this.each(function() {
            $element = $(this);
            $element.keyup(function(){
                var str = $(this).val();
                var resStr = '';
                var isUpper = true;
                for (var i = 0;i<str.length;i=i+1) {
                    if (isUpper) {
                        resStr = resStr+str.charAt(i).toUpperCase();
                        isUpper = false;
                    } else {
                        resStr = resStr+str.charAt(i).toLowerCase();
                    }
                    if (str.charAt(i) == ' ') {
                        isUpper = true;
                    }
                }
                $(this).val(resStr);
            });
        });
    };
})(jQuery);