function ShowForm() {
	document.getElementById('add_form_kop').style.display = 'block';
	document.getElementById('sub_kop').style.display = 'none';
}

function hidemsg() {
	$('#info-msg-kop').fadeOut('slow');
	if(tm) clearTimeout(tm);
}

function str_replace(search, replace, subject) {
	return subject.split(search).join(replace);
}

function go_url(url) {
	document.location.href = url;
	return false;
}

function money_plus_us() {
	$.ajax({
		type: "POST", url: "/kopilka/ajax_kopilka.php", data: { 'op':'money_plus_us' }, 
		beforeSend: function() { $('#loading').show(); }, 
		success: function(data) { 
			$('#loading').hide();
			if(data=="OK") {
				ModalStart("����������", StatusMsg("OK", "�� ������� �������� �����!"), 500, true, false, false);
				setTimeout(function(){if($("div").is(".box-modal")) {$("#LoadModal").modalpopup("close"); window.location = '';}}, 1500);
			}else{
				ModalStart("����������", StatusMsg("ERROR", data), 500, true, false, false);
				setTimeout(function(){if($("div").is(".box-modal")) $("#LoadModal").modalpopup("close");}, 10000);
			}
		}
	});
}

function money_add_kopilka() {
	var moneyadd = str_replace(',','.', document.forms['kopilka_form'].moneyadd.value);
	var comment = document.forms['kopilka_form'].comment.value;
	var radio1 = $('input[id="radio1"]:checked').val();
	var radio2 = $('input[id="radio2"]:checked').val();

	if(radio1==1) radio1 = 1; else radio1 = 0;
	if(radio2==1) radio2 = 1; else radio2 = 0;

	if(radio1 == 1) {
		var methodadd = 1;
	} else if(radio2 == 1) {
		var methodadd = 2;
	} else {
		var methodadd = '';
	}

	if(moneyadd == '') {
		document.getElementById("info-msg-kop").innerHTML = "<span class='msg-error'>�� ������� �����</span>";
		document.getElementById('info-msg-kop').style.display = 'block';
		tm = setTimeout(function() {
			hidemsg()
		}, 1000);
	} else if(isNaN(moneyadd)) {
		document.getElementById("info-msg-kop").innerHTML = "<span class='msg-error'>������� ������� �����</span>";
		document.getElementById('info-msg-kop').style.display = 'block';
		tm = setTimeout(function() {
			hidemsg()
		}, 1000);
	} else if(moneyadd < 0.01) {
		document.getElementById("info-msg-kop").innerHTML = "<span class='msg-error'>����������� ����� 0.01 ���.</span>";
		document.getElementById('info-msg-kop').style.display = 'block';
		tm = setTimeout(function() {
			hidemsg()
		}, 1000);
	} else if( (methodadd != '1') && (methodadd != '2') ) {
		document.getElementById("info-msg-kop").innerHTML = "<span class='msg-error'>�� ������ ��� �����</span>";
		document.getElementById('info-msg-kop').style.display = 'block';
		tm = setTimeout(function() {
			hidemsg()
		}, 1000);
	} else {
		var myReq = getXMLHTTPRequest();
		var params = "op=money_plus&moneyadd="+moneyadd+"&comment="+comment+"&methodadd="+methodadd;

		function setstate() {
			if((myReq.readyState == 4)&&(myReq.status == 200)) {
				var resvalue = myReq.responseText;
				//$('#info-msg-kop').fadeOut('fast');
				if(resvalue > 0) {
					document.getElementById("kopilka_summa_in").innerHTML = resvalue;
					document.getElementById('add_form_kop').style.display = 'none';
					document.getElementById('sub_kop').style.display = 'none';
				} else if(resvalue == 'ERRORLOG') {
				        window.location = '/';
				} else {
					document.getElementById('info-msg-kop').innerHTML = "<span class='msg-error'>"+resvalue+"</span>";
					document.getElementById('info-msg-kop').style.display = 'block';
					tm = setTimeout(function() {hidemsg()}, 2000);
				}
			} else {
				document.getElementById('info-msg-kop').innerHTML = "<span id='loading' title='��������� ����������...'></span>";
				document.getElementById('info-msg-kop').style.display = 'block';
			}
			return true;
		} 

		myReq.open("POST", "kopilka/ajax_kopilka.php?rnd="+Math.random(), true);
		myReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		myReq.setRequestHeader("Accept-Language", "ru");
		myReq.setRequestHeader("Accept-Charset", "windows-1251");
		myReq.setRequestHeader("Content-lenght", params.length);
		myReq.setRequestHeader("Connection", "close");
		myReq.onreadystatechange = setstate;
		myReq.send(params);

		document.getElementById('info-msg-kop').style.display = 'block';
		document.getElementById('info-msg-kop').innerHTML = "<span id='loading' title='��������� ����������...'></span>";
	}
	return false;
}

function ViewMoneyIn() {
	$.ajax({
		type: "POST", url: "/kopilka/ajax_kopilka.php?rnd="+Math.random(), 
		data: { 'op': 'ViewMoneyIn' }, 
		success: function(data) {
			ModalStart("��������� �������", data, 700, true, false, false);
		}
	});
}

function InfoKop() {
	$.ajax({
		type: "POST", url: "/kopilka/ajax_kopilka.php?rnd="+Math.random(), 
		data: { 'op': 'InfoKop' }, 
		success: function(data) {
			ModalStart("������� ������ �������", data, 600, true, false, false);
		}
	});
}

function kop_dll_coment(id) {
	$.ajax({
		type: "POST", url: "/kopilka/ajax_kopilka.php", data: { 'op':'kop_dll_coment', 'id':id }, 
		beforeSend: function() { $('#loading').show(); }, 
		success: function(data) { 
			$('#loading').hide();
			if(data=="OK") {
				$('#coment'+id).html("� ������� �� ��������"); 
			}else{
				alert(data);
			}
		}
	});
}
