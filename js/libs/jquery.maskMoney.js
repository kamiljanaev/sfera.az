
(function($) {
	$.fn.maskMoney = function(settings) {
		settings = $.extend({
			symbol:'US$',
			decimal:'.',
			precision:2,
			thousands:',',
			allowZero:false,
			showSymbol:false
		}, settings);

		settings.symbol=settings.symbol+' ';

		return this.each(function() {
			var input = $(this);

			function keypressEvent(e) {
				e = e||window.event;
				var k = e.charCode||e.keyCode||e.which;
				if (k == undefined) return; //needed to handle an IE "special" event

				if (k==8) { // tecla backspace
					preventDefault(e);
					var x = input.val().substring(0,input.val().length-1);
					input.val(maskValue(x));
					return false;
				} else if (k==9) { // tecla tab
					return true;
				}
				if (k<48||k>57) {
					preventDefault(e);
					return true;
				} else if (input.val().length==input.attr('maxlength')) {
					return false;
				}

				var key = String.fromCharCode(k);  // Valor para o código da Chave
				preventDefault(e);
				input.val(maskValue(input.val()+key));
			}

			function focusEvent(e) {
				if (input.val()=='') {
					input.val(setSymbol(getDefaultMask()));
				} else {
					input.val(setSymbol(input.val()));
				}
                if (this.createTextRange) {
                    var textRange = this.createTextRange();
                    textRange.collapse(false); // posiciona cursor no final.
                    textRange.select();
                }
			}

			function blurEvent(e) {
                if ($.browser.msie) {
                    keypressEvent(e);
                }

				if (input.val()==setSymbol(getDefaultMask())) {
					if(!settings.allowZero) input.val('');
				} else {
					input.val(input.val().replace(settings.symbol,''));
				}
			}

			function preventDefault(e) {
				if (e.preventDefault) { //standart browsers
					e.preventDefault();
				} else { // internet explorer
					e.returnValue = false
				}
			}

			function maskValue(v) {
				v = v.replace(settings.symbol,'');

				var strCheck = '0123456789';
				var len = v.length;
				var a = '', t = '';

				if (len==0) {
					t = '0.00';
				}

				for (var i = 0; i<len; i++) {
					if ((v.charAt(i)!='0') && (v.charAt(i)!=settings.decimal)) break;
                }

				for (; i<len; i++) {
					if (strCheck.indexOf(v.charAt(i))!=-1) a+= v.charAt(i);
				}

				var n = parseFloat(a);
				n = isNaN(n) ? 0 : n/Math.pow(10,settings.precision);
				t = n.toFixed(settings.precision);

                i = settings.precision == 0 ? 0 : 1;
				var p, d = (t=t.split('.'))[i].substr(0,settings.precision);
				for (p = (t=t[0]).length; (p-=3)>=1;) {
					t = t.substr(0,p)+settings.thousands+t.substr(p);
				}

				return (settings.precision>0)
                    ? setSymbol(t+settings.decimal+d+Array((settings.precision+1)-d.length).join(0))
                    : setSymbol(t);
			}

			function getDefaultMask() {
				var n = parseFloat('0')/Math.pow(10,settings.precision);
				return (n.toFixed(settings.precision)).replace(new RegExp('\\.','g'),settings.decimal);
			}

			function setSymbol(v) {
				if (settings.showSymbol) {
					return settings.symbol+v;
				}
				return v;
			}

			input.bind('keypress',keypressEvent);
			input.bind('blur',blurEvent);
			input.bind('focus',focusEvent);

			input.one('unmaskMoney',function() {
				input.unbind('focus',focusEvent);
				input.unbind('blur',blurEvent);
				input.unbind('keypress',keypressEvent);

				if ($.browser.msie) {
                    this.onpaste= null;
				} else if ($.browser.mozilla) {
                    this.removeEventListener('input',blurEvent,false);
                }
			});
		});
	}

	$.fn.unmaskMoney=function() {
		return this.trigger('unmaskMoney');
	};
})(jQuery);