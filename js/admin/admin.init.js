var baseUrl = '';
var CKFField = '';
var myLayout = false;

function init(url) {
    baseUrl = url;
}

jQuery.fn.log = function (msg) {
	if(typeof(window['console']) == 'object'){
		console.log("%s: %o", msg, this);
	}
	return this;
};

$(function() {
	var west_panel_state=false, east_panel_state=false;
	myLayout = $('#layouts').layout({
		west__showOverflowOnHover: true,
		west: {
			size:250,
			maxSize:250,
			spacing_closed:22,
			togglerLength_closed:140,
			togglerAlign_closed:"top",
			togglerContent_closed:$(this).find('.ui-layout-west .ui-closeTitle').html(),
			togglerTip_closed:"Open & Pin Contents",
			sliderTip:"Slide Open Contents",
			slideTrigger_open:"mouseover",
			initClosed:west_panel_state,
			onopen:function(){
				$.cookie('west_panel_state', '1', { path: '/', expires: 10 });
			},
			onclose:function(){
				$.cookie('west_panel_state', '0', { path: '/', expires: 10 });
			}
		},
		east: {
			size:280,
			maxSize:280,
			spacing_closed:22,
			togglerLength_closed:140,
			togglerAlign_closed:"top",
			togglerContent_closed:$(this).find('.ui-layout-east .ui-closeTitle').html(),
			togglerTip_closed:"Open & Pin Contents",
			sliderTip:"Slide Open Contents",
			slideTrigger_open:"mouseover",
			initClosed:east_panel_state,
			onopen:function(){
				$.cookie('east_panel_state', '1', { path: '/', expires: 10 });
			},
			onclose:function(){
				$.cookie('east_panel_state', '0', { path: '/', expires: 10 });
			}
		},
		center: {
			onresize: function(){
				if(myLayout.state.west.isClosed){
					var west_size = myLayout.state.west.minSize;
				}else{
					var west_size = myLayout.state.west.size;
				}
				if(myLayout.state.east.isClosed){
					var east_size = myLayout.state.east.minSize+5;
				}else{
					var east_size = myLayout.state.east.size;
				}
				var container_size = myLayout.state.container.innerWidth;
				var new_width = container_size-west_size-east_size-50;
				var old_width = $("#jqgridtable").width();
				if(old_width != new_width){
					$("#jqgridtable").setGridWidth(new_width);
				}
				$("#jqgridtable").jqGrid('setGridHeight', getAvailableSpaceForGrid());
			}
		}
	});

	$('#mainmenu li').hover(
		function(){$(this).addClass('hover');},
		function(){$(this).removeClass('hover');}
	);

	doDateFields();
	doMoneyFields();

	checked_unchecked_checkboxes();

	setRequiredLabels();
	setMaskedInputs();
});

function doDateFields() {
	if (($.mask) && ($.datepick)) {
		var today = new Date();
		today = new Date(today.getFullYear(), today.getMonth(), today.getDate());
		$.datepick.regional['ru'] = {
//			yearRange: '-1:1',
			defaultDate: today,
			showTrigger: '<img src="/js/libs/datepick/calendar-blue.gif" class="trigger">',
			showOnFocus: false,
			'dateFormat': 'dd.mm.yyyy'
		}
		$.datepick.setDefaults($.datepick.regional['ru']);
		$('.date-field').not('.hasMask').mask('99.99.9999').addClass("hasMask").datepick();
	}
}

function doMoneyFields() {
	if ($.maskMoney) {
		alert('!!');
	}
}

function checked_unchecked_checkboxes() {
    $("input[name^=check_uncheck]").click(
         function() {
            var checked_status = this.checked;
         	$("input:checkbox[class^=check_uncheck]").each(function() {
         		this.checked = checked_status;
         	});
         }
    );
}

function setRequiredLabels() {
	$('label.required').each(function() {
		$(this).html($(this).html()+'<span style="color: red;">*</span>');
	})
}

function setMaskedInputs() {
	if ($.fn.mask) {
		$('input.phone_local').mask('99 99 99 99 99');
		$('input.postalcode_local').mask('99999');
	}
	if ($.fn.uppercase) {
		$('input.city_local').uppercase();
		$('input.lastname_local').uppercase();
	}
	if ($.fn.ucfirst) {
		$('input.firstname_local').ucfirst();
		$('input.address1_local').ucfirst();
		$('input.address2_local').ucfirst();
	}
}

function _initjGrowl(theme, sticky) {
	var Theme = theme ? theme : 'notify';
	var isSticky = sticky ? true : false;
	var messageHeader = 'Ошибка';
	if (Theme == 'notify') {
		messageHeader = 'Уведомление';
	}
	var settings = {life: 5000, sticky: isSticky, glue: 'before', header: messageHeader, theme: Theme};
	$.jGrowl.defaults.closerTemplate = '<div>[закрыть все]</div>';
	return settings;
}

function _showMessages(messages, settings) {
	if (typeof(messages) == 'string') {
		$.jGrowl(messages, settings);
	} else {
		for (var mi in messages) {
			if (typeof(messages[mi]) == 'string') {
				$.jGrowl(messages[mi], settings);
			} else {
				for (var i in messages[mi]) {
					$.jGrowl(messages[mi][i], settings);
				}
			}
		}
	}
}

function showErrorMessage(msg) {
	_showMessages(msg, _initjGrowl('error', true));
}

function showErrorMessagesPool(msgs) {
	_showMessages(msgs, _initjGrowl('error', true));
}

function showNotifyMessage(msg) {
	_showMessages(msg, _initjGrowl('notify'));
}

function showNotifyMessagesPool(msgs) {
	_showMessages(msgs, _initjGrowl('notify'));
}

function showGrowlMessage(msg, options) {
	var settings = {life: 5000, glue: 'before'};
	$.extend(settings, options);
	$.jGrowl.defaults.closerTemplate = '<div>[закрыть все]</div>';
	$.jGrowl(msg, settings);
}

function ieFix() {
	$('#layouts').remove();
}
window.onbeforeunload = ieFix;

