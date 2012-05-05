(function($){

var passwordStrength = new function()
{
    this.countRegexp = function(val, rex)
    {
        var match = val.match(rex);
        return match ? match.length : 0;
    };
    
    this.getStrength = function(val, minLength)
    {   
        var len = val.length;
        
        // too short =(
        if (len < minLength)
        {
            return 0;
        }
        
        var nums = this.countRegexp(val, /\d/g),
            lowers = this.countRegexp(val, /[a-z]/g),
            uppers = this.countRegexp(val, /[A-Z]/g),
            specials = len - nums - lowers - uppers;
        
        // just one type of characters =(
        if (nums == len || lowers == len || uppers == len || specials == len)
        {
            return 1;
        }
        
        var strength = 0;
        if (nums)   { strength+= 2; }
        if (lowers) { strength+= uppers? 4 : 3; }
        if (uppers) { strength+= lowers? 4 : 3; }
        if (specials) { strength+= 5; }
        if (len > 10) { strength+= 1; }
        
        return strength;
    };
    
    this.getStrengthLevel = function(val, minLength)
    {
        var strength = this.getStrength(val, minLength);

        val = 1;
        if (strength <= 4) {
            val = 1;
        } else if (strength > 4 && strength <= 8) {
            val = 2;
        } else if (strength > 8) {
            val = 3;
        }

        return val;
    };
};

$.fn.password_strength = function(options)
{
    var settings = $.extend({
        'container' : null,
        'bar': null, // thanks codemonkeyking
        'minLength' : 6,
        'texts' : {
            1 : 'Too weak',
            2 : 'Weak password',
            3 : 'Normal strength',
            4 : 'Strong password',
            5 : 'Very strong password'
        },
        'onCheck': null
    }, options);
    
    return this.each(function()
    {
        var container = null, $bar = null;
        if (settings.container)
        {
            container = $(settings.container);
        }
        else
        {
            container = $('<span/>').attr('class', 'password_strength');
            $(this).after(container);
        }

        if (settings.bar)
        {
            $bar = $(settings.bar);
        }
        
        $(this).bind('keyup.password_strength', function()
        {
            var val = $(this).val(),
                    level = passwordStrength.getStrengthLevel(val, settings.minLength);

            if (val.length > 0)
            {
                $('.password-strength').hide();
                var item = 'pwdStrenghtLevel'+level;
                $('#'+item).show();
/*                var _class = 'password_strength_' + level,
                        _barClass = 'password_bar_' + level;
                
                if (!container.hasClass(_class) && level in settings.texts)
                {
                    container.text(settings.texts[level]).attr('class', 'password_strength ' + _class);
                }
                if ($bar && !$bar.hasClass(_barClass))
                {
                    $bar.attr('class', 'password_bar ' + _barClass);
                }*/
            }
            else
            {
                $('.password-strength').hide();
/*                container.text('').attr('class', 'password_strength');
                if ($bar) {
                    $bar.attr('class', 'password_bar');
                }*/
            }
            if (settings.onCheck) {
                settings.onCheck.call(this, level);
            }
        });

        if ($(this).val() != '') {
                $(this).trigger('keyup.password_strength');
        }
    });
};

})(jQuery);
