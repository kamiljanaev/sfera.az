jQuery.ajaxBase = jQuery.ajax;
jQuery.ajax = function (options)
{
	if (typeof document.ajax == "undefined") document.ajax = {q:{}, r:{}};
	if (typeof document.ajax.requestCounter == "undefined") document.ajax.requestCounter = 0;
	queue = 'request'+ document.ajax.requestCounter % 3;
	document.ajax.requestCounter ++;
	if (typeof document.ajax.q[queue] == "undefined") document.ajax.q[queue] = [];	
	if (typeof document.ajax.r[queue] == "undefined") document.ajax.r[queue] = [];	
	if (typeof options != "undefined") 
	{
		var optionsCopy = {};
		for (var o in options) optionsCopy[o] = options[o];
		options = optionsCopy;
		options.originalCompleteCallback = options.complete;
		options.queue = queue;
		options.complete = function (request, status)
		{
			document.ajax.q[this.queue].shift ();
			document.ajax.r[this.queue] = null;
			if (this.originalCompleteCallback) this.originalCompleteCallback (request, status);
			if (document.ajax.q[this.queue].length > 0) document.ajax.r[this.queue] = jQuery.ajaxBase (document.ajax.q[this.queue][0]);
            if ( jQuery.bindVisualTranslate ) {
    			jQuery.bindVisualTranslate();
            }
		};
		document.ajax.q[queue].push (options);
		if (document.ajax.q[queue].length == 1) document.ajax.r[queue] = jQuery.ajaxBase (options);
	}
	else
	{
		if (document.ajax.r[queue])
		{
			document.ajax.r[queue].abort ();
			document.ajax.r[queue] = null;
		}
		document.ajax.q[queue] = [];
	}
}