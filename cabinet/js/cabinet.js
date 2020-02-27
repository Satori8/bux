var LoadCabInfo = false;

function gebi(id){
	return document.getElementById(id)
}

function str_replace(search, replace, subject) {
	return subject.split(search).join(replace);
}

function focus_bg(id){
	gebi(id).style.background = "#FFDBDB";
	gebi(id).focus();
	return false;
}

function number_format(number, decimals, dec_point, thousands_sep) {
	var minus = "";
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	if(number < 0){
		minus = "-";
		number = number*-1;
	}
	var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',

	toFixedFix = function(n, prec) {
		var k = Math.pow(10, prec);
		return '' + (Math.round(n * k) / k).toFixed(prec);
	};

	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');

	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}

	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return minus + s.join(dec);
}

function alert_nostart() {
	alert("Для запуска, необходимо пополнить рекламный бюджет");
	return false;
}

function alert_nostart_edit() {
	alert("Для редактирования необходимо преостановить показ рекламной площадки!");
	return false;
}

function alert_nostart_rc() {
	alert("Для запуска, необходимо оплатить рекламную площадку");
	return false;
}

function alert_nopause() {
	alert("Приостановка этой рекламной площадки не предусмотрена");
	return false;
}

function alert_nolimit() {
	alert("На сегодня показ приостановлен, так как вы установили ограничение показов в сутки (или в час). Просмотр запуститься автоматически");
	return false;
}

function alert_bezlimit() {
	alert("Приостановка этой рекламной площадки не предусмотрена (заказан безлимит)");
	return false;
}

function play_pause(id, type) {
	$.ajax({
		type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: { 'op' : 'play_pause', 'type' : type, 'id' : id }, 
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) { 
			$("#loading").slideToggle();

			if (data == "ERRORNOID") {
				alert("У Вас нет рекламной площадки с ID - " + id);
			} else if (data == "BEZLIMIT") {
				alert_bezlimit();
			} else if (data == "NOLIMIT") {
				alert_nolimit();
			} else if (data == "ERROR") {
				alert("Ошибка! Не удалось обработать запрос!");
			} else if (data == "0") {
				alert_nostart();
			} else if (data == "") {
				alert("Ошибка! Не удалось обработать запрос!");
			} else {
				$("#playpauseimg"+id).html(data); 
			}
			return false;
		}
	});
}

function alert_delete(id, type) {
	if (confirm("Вы уверены что хотите удалить рекламну площадку ID: "+id+" ?")) {
		$.ajax({
			type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: { 'op' : 'delete', 'type' : type, 'id' : id }, 
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) { 
				$("#loading").slideToggle();
				if (data == "OK") { $("#adv_dell"+id).hide(); $("#adv_addmoney"+id).hide(); }
				else { alert(data); }
			}
		});
	}
}

function clear_stat(id, type, count) {
	if (count == 0) {
		alert("Счётчик этой площадки уже равен 0");
	} else if (confirm("Обнулить счетчик просмотров рекламной площадки ID: "+id+" ?")) {
		$.ajax({
			type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: { 'op' : 'clear_stat', 'type' : type, 'id' : id }, 
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) { 
				$("#loading").slideToggle();
				if (data == "OK") {
					$("#c_stat"+id).html('0');
				} else {
					alert(data);
				}
			}
		});
	}
}

function PlayPause(id, type) {
	$.ajax({
		type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: { 'op' : 'play_pause', 'type' : type, 'id' : id }, 
		dataType: 'json', 
		beforeSend: function() { $("#loading").slideToggle(); }, 
		error: function() { $("#loading").slideToggle(); alert("Ошибка обработки данных AJAX. Сообщите Администрации сайта!"); return false; }, 
		success: function(data) { 
			$("#loading").slideToggle();
			if (data.result == "OK") { 
				$("#playpauseimg"+id).html(data.status);
				if(data.message) { alert(data.message); }
			} else { 
				if(data.message) { alert(data.message); }
				else { alert("Ошибка обработки данных!"); return false; }
			}
			return false;
		}
	});
}

function DelAds(id, type) {
	if (confirm("Вы уверены что хотите удалить рекламну площадку ID: "+id+" ?")) {
		$.ajax({
			type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: { 'op' : 'delete', 'type' : type, 'id' : id }, 
			dataType: 'json', 
			beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() { $("#loading").slideToggle(); alert("Ошибка обработки данных AJAX. Сообщите Администрации сайта!"); return false; }, 
			success: function(data) { 
				$("#loading").slideToggle();
				if (data.result == "OK") {
					$("#adv_dell"+id).hide(); $("#load-info"+id).hide(); $("#mess-info"+id).html("");
					if(data.message) { alert(data.message); }
				} else {
					if(data.message) { alert(data.message); }
					else { alert("Ошибка обработки данных!"); return false; }
				}
			}
		});
	}
}

function ClearStat(id, type, count1, count2) {
	if (count1 == 0 && count2 == 0) {
		alert("Счётчик этой площадки уже равен 0");
	} else if (confirm("Обнулить счетчик просмотров рекламной площадки ID: "+id+" ?")) {
		$.ajax({
			type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: { 'op' : 'clear_stat', 'type' : type, 'id' : id }, 
			dataType: 'json', 
			beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() { $("#loading").slideToggle(); alert("Ошибка обработки данных AJAX. Сообщите Администрации сайта!"); return false; }, 
			success: function(data) { 
				$("#loading").slideToggle();
				if (data.result == "OK") {
					$("#g_stat"+id).html('0');
					$("#b_stat"+id).html('0');
				} else {
					if(data.message) { alert(data.message); }
					else { alert("Ошибка обработки данных!"); return false; }
				}
			}
		});
	}
}

function show_money_add(id) {
	if (gebi(id).style.display == 'none') {
		gebi(id).style.display = '';
	} else {
		gebi(id).style.display = 'none';
	}
}

function show_up_list(id) {
	if (gebi(id).style.display == 'none') {
		gebi(id).style.display = '';
	} else {
		gebi(id).style.display = 'none';
	}
}

function ShowHideBlock(id) {
	if (gebi("adv-title"+id).className == 'adv-title-open') {
		gebi("adv-title"+id).className = 'adv-title-close';
	} else {
		gebi("adv-title"+id).className = 'adv-title-open';
	}
	$("#adv-block"+id).slideToggle('fast');
}

function SetChecked(type){
	var nodes = document.getElementsByTagName("input");
	for (var i = 0; i < nodes.length; i++) {
		if (nodes[i].name == "country[]") {
			if(type == "paste") nodes[i].checked = true;
			else  nodes[i].checked = false;
		}
	}
}

function InsertTags(text1, text2, descId) {
	var textarea = gebi(descId);
	if (typeof(textarea.caretPos) != "undefined" && textarea.createTextRange) {
		var caretPos = textarea.caretPos, temp_length = caretPos.text.length;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text1 + caretPos.text + text2 + ' ' : text1 + caretPos.text + text2;

		if (temp_length == 0) {
			caretPos.moveStart("character", -text2.length);
			caretPos.moveEnd("character", -text2.length);
			caretPos.select();
		} else {
			textarea.focus(caretPos);
		}
	} else if (typeof(textarea.selectionStart) != "undefined") {
		var begin = textarea.value.substr(0, textarea.selectionStart);
		var selection = textarea.value.substr(textarea.selectionStart, textarea.selectionEnd - textarea.selectionStart);
		var end = textarea.value.substr(textarea.selectionEnd);
		var newCursorPos = textarea.selectionStart;
		var scrollPos = textarea.scrollTop;
		textarea.value = begin + text1 + selection + text2 + end;

		if (textarea.setSelectionRange) {
			if (selection.length == 0) {
				textarea.setSelectionRange(newCursorPos + text1.length, newCursorPos + text1.length);
			} else {
				textarea.setSelectionRange(newCursorPos, newCursorPos + text1.length + selection.length + text2.length);
			}
			textarea.focus();
		}
		textarea.scrollTop = scrollPos;
	} else {
		textarea.value += text1 + text2;
		textarea.focus(textarea.value.length - 1);
	}
}


function cab_link(ads) {
	var expires = new Date();
	expires.setTime(expires.getTime() + (365 * 24 * 60 * 60 * 1000));
	document.cookie="cab_link=" + ads + "; path=/; expires=" + expires.toUTCString(); location.replace("/cabinet_ads?ads=" + ads);
}

function InfoCab() {
	if(!LoadCabInfo) {
		$.ajax({
			type: "GET", cache: false, url: "/cabinet/cab_info.php", dataType: "html", 
			beforeSend: function() {LoadCabInfo = true; $("#loading").slideToggle(); $("input, textarea, select").blur(); }, 
			statusCode: {404: function() {LoadCabInfo = false; $("#loading").slideToggle(); ModalStart("Ошибка 404", '<span class="msg-error" style="margin:0;">Возможно эта страница была удалена, переименована, или она временно недоступна.</span>', 600, false, false, 5);}},
			success: function(data) { 
				LoadCabInfo = false; $("#loading").slideToggle();
				ModalStart("Накопительные скидки для рекламодателей", data, 550, true, true, 0);
			}
		});
	}
	return false;
}
