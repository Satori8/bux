<?php
if (!DEFINED("ADVERTISE")) {die ("Hacking attempt!");}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_hit' AND `howmany`='1'");
$tests_cena_hit = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_comis_del' AND `howmany`='1'");
$tests_comis_del = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_nacenka' AND `howmany`='1'");
$tests_nacenka = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_min_pay' AND `howmany`='1'");
$tests_min_pay = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_quest' AND `howmany`='1'");
$tests_cena_quest = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_color' AND `howmany`='1'");
$tests_cena_color = number_format(mysql_result($sql,0,0), 4, ".", "");

for($i=1; $i<=4; $i++) {
	$tests_cena_revisit[0] = 0;
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_revisit' AND `howmany`='$i'");
	$tests_cena_revisit[$i] = number_format(mysql_result($sql,0,0), 4, ".", "");
}

for($i=1; $i<=2; $i++) {
	$tests_cena_unic_ip[0] = 0;
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_unic_ip' AND `howmany`='$i'");
	$tests_cena_unic_ip[$i] = number_format(mysql_result($sql,0,0), 4, ".", "");
}
?>

<script type="text/javascript" src="js/jquery.simpletip-1.3.1.pack.js"></script>

<script type="text/javascript" language="JavaScript">

$(document).ready(function(){
	$("#hint1").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>��������� �����</b> - �������� 55 ��������.<br>��������� ������ ���� �������� � ��������. ���������� ����������. ��������� ��������� �������� �����������.<br>�� ������ �� ���������� �������, �� ������� ��������� ���������� ������ ����: !!!!!! � �.�. ����� ������� ��������� ������� ���� �������.'
	});
	$("#hint2").simpletip({
		fixed: true, position: ["-622", "-45"], focus: false,
		content: '<b>���������� ��� ������������</b> - �������� 2000 ��������.<br>��������, ��� ���������� ����������� ������ ��� ��������� ������������, �������� ����������� ��� ���� ��� ��������� ������. ���������� ����������. ��������� ��������� �������� �����������. �� ������ �� ���������� �������, �� ������� ��������� ���������� ������ ����: !!!!!! � �.�. ����� ������� ��������� ������� ���� �������. �������� ���������!'
	});
	$("#hint3").simpletip({
		fixed: true, position: ["-622", "-30"], focus: false,
		content: '<b>���� ��������������� ��� ������� � ����� ���������� ������ ������</b>.<br>������ ������� ������ ������ ���� ����������. �� �������������� ����� ����� ������������ ��� ��� �������������� �������. ������������ ���������� �������� � ������� - 300, � ������ - 30.'
	});
	$("#hint4").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>���������� ������������</b> - ���������� <b>����</b> �������� �������������� ������������ �������������:<br><b>�������� ���� ������ 24 ����</b> - ��� ������, ��� ���� ������������ ������ ������ ��� ���� ���� ��� � �����.<br><b>�������� ���� ������ 3 ���</b> - ��� ������, ��� ���� ������������ ������ ������ ��� ���� ���� ��� � 3 ���.<br><b>�������� ���� ������ ������</b> - ��� ������, ��� ���� ������������ ������ ������ ��� ���� ���� ��� � ������.<br><b>�������� ���� ������ 2 ������</b> - ��� ������, ��� ���� ������������ ������ ������ ��� ���� ���� ��� � 2 ������.<br><b>�������� ���� ������ �����</b> - ��� ������, ��� ���� ������������ ������ ������ ��� ���� ���� ��� � �����.'
	});
	$("#hint5").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>�������� ����</b> - ��� ���� ����� � ������� ����� ������ � <b style="color:red;">������� ������� ������</b> �� �������������� �����.'
	});
	$("#hint6").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>���������� IP</b> - �� ������ ���������� ���������� ������ ����� ��� ������ ���������� IP ��� �� ����� �� 2 ����� (255.255.X.X)'
	});
	$("#hint7").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>�� ���� �����������</b> - �� ������ ���������� ����� ����� �������, ��������, �������� ���������� �� ������ ��������, ������� ������������������ �� ������� 7-�� ���� �����.'
	});
	$("#hint8").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>�� �������� ��������</b> - �� ������ ���������� ����� ����� ������� �� �������� �������� �������������, �������� ����� ����� ������� ������ ������������� �������� ���� �������� ����.'
	});
	$("#hint9").simpletip({
		fixed: true, position: ["-622", "-45"], focus: false,
		content: '<b>������������ �� �������</b> - ���������� ������ ����� �������� ������������� �� ��������� �����, ��� �� �� ������ ��������� ���� ��� ��������� �����'
	});
	$("#hint10").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>������ ������</b> - �������� �������� ���������� ��� ��� ������ ������ ������.'
	});
	$("#hint11").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>����� ����������</b> - ������� �����, ������� �� ������ ������ � ������ ��������� ��������.'
	});
	$("#hint12").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>��������������</b> - ������ ������������ �� �������� ����������� �����.'
	});
	$("#hint13").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>���� ������ �����</b> - ��������� �� ���� ���������� �����.'
	});
	$("#hint14").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>���������� ����������</b> - ���������� �������� ����������� �����, ������� ������� ��������� ��������.'
	});
	$("#hinturl").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>URL-����� �����</b> - ������ ���������� � http:// ��� https:// � ��������� �� ����� 300 ��������.<br>�� ����������� HTML-���� � Java-�������. �� ������� ������ �������, ��������� - �������� ��������.'
	});
})

function gebi(id){
	return document.getElementById(id)
}

function ClearForm() {
	gebi("title").value = "";
	gebi("description").value = "";
	gebi("url").value = "";
	gebi("quest1").value = "";
	gebi("quest2").value = "";
	gebi("quest3").value = "";
	gebi("quest4").value = "";
	gebi("quest5").value = "";
	gebi("answ11").value = "";
	gebi("answ12").value = "";
	gebi("answ13").value = "";
	gebi("answ21").value = "";
	gebi("answ22").value = "";
	gebi("answ23").value = "";
	gebi("answ31").value = "";
	gebi("answ32").value = "";
	gebi("answ33").value = "";
	gebi("answ41").value = "";
	gebi("answ42").value = "";
	gebi("answ43").value = "";
	gebi("answ51").value = "";
	gebi("answ52").value = "";
	gebi("answ53").value = "";
	gebi("block_quest4").style.display = 'none';
	gebi("block_quest5").style.display = 'none';
	gebi("money_add").value = 100;
	gebi("revisit").value = 0;
	gebi("color").value = 0;
	gebi("unic_ip_user").value = 0;
	gebi("date_reg_user").value = 0;
	gebi("sex_user").value = 0;
	gebi("method_pay").value = 1;
	SetChecked();
	PlanChange();
}

function ShowHideBlock(id) {
	if (gebi("adv-title"+id).className == 'adv-title-open') {
		gebi("adv-title"+id).className = 'adv-title-close';
	} else {
		gebi("adv-title"+id).className = 'adv-title-open';
	}
	$("#adv-block"+id).slideToggle("fast");
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

function add_quest() {
	if (gebi("block_quest4").style.display == 'none') {
		$("#block_quest4").fadeIn("fast", function(){
			gebi("quest4").value = '';
			gebi("answ41").value = '';
			gebi("answ42").value = '';
			gebi("answ43").value = '';
			if (gebi("block_quest5").style.display == '') gebi("block_add_quest").style.display = 'none';
			gebi("block_quest4").style.display = '';
			PlanChange();
		});
	} else if (gebi("block_quest5").style.display == 'none') {
		$("#block_quest5").fadeIn("fast", function(){
			gebi("quest5").value = '';
			gebi("answ51").value = '';
			gebi("answ52").value = '';
			gebi("answ53").value = '';
			if (gebi("block_quest4").style.display == '') gebi("block_add_quest").style.display = 'none';
			gebi("block_quest5").style.display = '';
			PlanChange();
		});
	}
}

function del_quest() {
	if (gebi("block_quest5").style.display == '') {
		$("#block_quest5").fadeOut("fast", function(){
			gebi("quest5").value = '';
			gebi("answ51").value = '';
			gebi("answ52").value = '';
			gebi("answ53").value = '';
			gebi("block_quest5").style.display = 'none';
			gebi("block_add_quest").style.display = '';
			PlanChange();
		});
	} else if (gebi("block_quest4").style.display == '') {
		$("#block_quest4").fadeOut("fast", function(){
			gebi("quest4").value = '';
			gebi("answ41").value = '';
			gebi("answ42").value = '';
			gebi("answ43").value = '';
			gebi("block_quest4").style.display = 'none';
			gebi("block_add_quest").style.display = '';
			PlanChange();
		});
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

function descchange(id, elem, count_s) {
	if (elem.value.length > count_s) { elem.value = elem.value.substr(0,count_s); }
	$("#count"+id).html("�������� ��������: " +(count_s-elem.value.length));
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

function str_replace(search, replace, subject) {
	return subject.split(search).join(replace);
}

function PlanChange(){
	var revisit = $.trim($("#revisit").val());
	var color = $.trim($("#color").val());
	var unic_ip_user = $.trim($("#unic_ip_user").val());
	var money_add = $.trim($("#money_add").val());

	money_add = str_replace(",", ".", money_add);
	money_add = money_add.match(/(\d+(\.)?(\d){0,2})?/);
	money_add = money_add[0] ? money_add[0] : '';

	$("#money_add").val(money_add);
	money_add = number_format(money_add, 2, ".", "");

	var uprice = <?=number_format(($tests_cena_hit/(1 + $tests_nacenka/100)), 4, ".", "");?>;
	var rprice = <?=$tests_cena_hit;?>;

	if (gebi("block_quest4").style.display == '') {
		uprice += <?=number_format(($tests_cena_quest/(1 + $tests_nacenka/100)), 5, ".", "");?>;
		rprice += <?=$tests_cena_quest;?>;
	}
	if (gebi("block_quest5").style.display == '') {
		uprice += <?=number_format(($tests_cena_quest/(1 + $tests_nacenka/100)), 5, ".", "");?>;
		rprice += <?=$tests_cena_quest;?>;
	}

	if (color == 1) rprice += <?=$tests_cena_color;?>;

	if (revisit == 1) {
		rprice += <?=$tests_cena_revisit[1];?>;
	} else if (revisit == 2) {
		rprice += <?=$tests_cena_revisit[2];?>;
	} else if (revisit == 3) {
		rprice += <?=$tests_cena_revisit[3];?>;
	} else if (revisit == 4) {
		rprice += <?=$tests_cena_revisit[4];?>;
	}
	if (unic_ip_user == 1) {
		rprice += <?=$tests_cena_unic_ip[1];?>;
	} else if (unic_ip_user == 2) {
		rprice += <?=$tests_cena_unic_ip[2];?>;
	}

	count_test = parseFloat((money_add*10000)/(rprice*10000));

	$("#price_user").html('<span style="color:#228B22;">' + number_format(uprice, 4, ".", " ") + ' ���.</span>');
	$("#price_one").html('<span style="color:#0000FF;">' + number_format(rprice, 4, ".", " ") + ' ���.</span>');
	$("#count_test").html('<span style="color:#FF0000;">' + Math.floor(count_test) + '</span>');
}

function SaveAds(id, type) {
	var title = $.trim($("#title").val());
	var description = $.trim($("#description").val());
	var url = $.trim($("#url").val());
	var revisit = $.trim($("#revisit").val());
	var color = $.trim($("#color").val());
	var unic_ip_user = $.trim($("#unic_ip_user").val());
	var date_reg_user = $.trim($("#date_reg_user").val());
	var sex_user = $.trim($("#sex_user").val());
	var country = $('input[id="country[]"]:checked').map(function(){return $(this).val();}).get();
	var method_pay = $.trim($("#method_pay").val());
	var money_add = $.trim($("#money_add").val());

	money_add = str_replace(",", ".", money_add);
	money_add = money_add.match(/[+]?(\d+(\.)?(\d){0,2})?/);
	money_add = money_add[0] ? money_add[0] : '';
	money_add = number_format(money_add, 2, ".", "");

	var quest1 = $.trim($("#quest1").val());
	var answ11 = $.trim($("#answ11").val());
	var answ12 = $.trim($("#answ12").val());
	var answ13 = $.trim($("#answ13").val());

	var quest2 = $.trim($("#quest2").val());
	var answ21 = $.trim($("#answ21").val());
	var answ22 = $.trim($("#answ22").val());
	var answ23 = $.trim($("#answ23").val());

	var quest3 = $.trim($("#quest3").val());
	var answ31 = $.trim($("#answ31").val());
	var answ32 = $.trim($("#answ32").val());
	var answ33 = $.trim($("#answ33").val());

	var quest4 = $.trim($("#quest4").val());
	var answ41 = $.trim($("#answ41").val());
	var answ42 = $.trim($("#answ42").val());
	var answ43 = $.trim($("#answ43").val());

	var quest5 = $.trim($("#quest5").val());
	var answ51 = $.trim($("#answ51").val());
	var answ52 = $.trim($("#answ52").val());
	var answ53 = $.trim($("#answ53").val());

	if (title == "") {
		gebi("info-msg-cab").style.display = "block";
		gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� ��������� �����!</span>';
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (description == "") {
		gebi("info-msg-cab").style.display = "";
		gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� ���������� � ���������� �����!</span>';
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if ((url == '') | (url == 'http://') | (url == 'https://')) {
		gebi("info-msg-cab").style.display = "";
		gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� URL-����� �����!</span>';
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (quest1 == "") {
		gebi("info-msg-cab").style.display = "";
		gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� ������ ������!</span>';
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (answ11 == "" | answ12 == "" | answ13 == "") {
		gebi("info-msg-cab").style.display = "";
		gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� �������� ������ �� ������ ������!</span>';
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (quest2 == "") {
		gebi("info-msg-cab").style.display = "";
		gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� ������ ������!</span>';
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (answ21 == "" | answ22 == "" | answ23 == "") {
		gebi("info-msg-cab").style.display = "";
		gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� �������� ������ �� ������ ������!</span>';
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (quest3 == "") {
		gebi("info-msg-cab").style.display = "";
		gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� ������ ������!</span>';
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (answ31 == "" | answ32 == "" | answ33 == "") {
		gebi("info-msg-cab").style.display = "";
		gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� �������� ������ �� ������ ������!</span>';
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (money_add < <?=$tests_min_pay;?>) {
		gebi("info-msg-cab").style.display = "";
		gebi("info-msg-cab").innerHTML = '<span class="msg-error">����������� ����� ���������� - <?=$tests_min_pay;?> ���.</span>';
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else {
		$.ajax({
			type: "POST", url: "/advertise/ajax/ajax_adv_add.php?rnd="+Math.random(), 
			data: {
				'op':'add', 
				'type':type, 
				'title':title, 
				'description':description, 
				'url':url, 
				'revisit':revisit, 
				'color':color, 
				'unic_ip_user':unic_ip_user, 
				'date_reg_user':date_reg_user, 
				'sex_user':sex_user, 
				'country[]':country, 
				'money_add':money_add, 
				'method_pay':method_pay, 
				'quest1':quest1, 
				'answ11':answ11, 
				'answ12':answ12, 
				'answ13':answ13, 
				'quest2':quest2, 
				'answ21':answ21, 
				'answ22':answ22, 
				'answ23':answ23, 
				'quest3':quest3, 
				'answ31':answ31, 
				'answ32':answ32, 
				'answ33':answ33, 
				'quest4':quest4, 
				'answ41':answ41, 
				'answ42':answ42, 
				'answ43':answ43, 
				'quest5':quest5, 
				'answ51':answ51, 
				'answ52':answ52, 
				'answ53':answ53

			}, 
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				$("#loading").slideToggle();
				gebi("info-msg-cab").innerHTML = "";
				var status_ok = data.substr(0,2);
				var status_er = data.substr(0,5);

				if(status_ok == "OK") var data = data.substr(2);
				if(status_er == "ERROR") var data = data.substr(5);

				if (status_ok == "OK") {
					$("#info-msg-cab").show();
					$("#OrderForm").html(data);
					$("#BlockForm").slideToggle("slow");
					$("#OrderForm").slideToggle("slow");
					$("#InfoAds").slideToggle("slow");

					//window.history.pushState(null, null, "/advertise.php?ads=<?=$ads;?>");
					$("html, body").animate({scrollTop: $("#ScrollID").offset().top-10}, 700);
					return false;
				} else if (status_er == "ERROR") {
					gebi("info-msg-cab").style.display = "";
					gebi("info-msg-cab").innerHTML = '<span class="msg-error">' + data + '</span>';
					setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 5000); clearTimeout();
					return false;
				} else {
					gebi("info-msg-cab").style.display = "";
					gebi("info-msg-cab").innerHTML = '<span class="msg-error">' + data + '</span>';
					setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 5000); clearTimeout();
					return false;
				}
			}
		});
	}
}

function DeleteAds(id) {
	$.ajax({
		type: "POST", url: "/advertise/ajax/ajax_adv_add.php?rnd="+Math.random(), 
		data: {'op':'del', 'type':'tests', 'id':id}, 
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) {
			$("#loading").slideToggle();
			if (data == "OK") {
				$("html, body").animate({scrollTop:0}, 700);
				$("#BlockForm").slideToggle("slow");
				$("#OrderForm").slideToggle("slow");
				$("#InfoAds").slideToggle("slow");
				ClearForm();
			}else{
				alert("������! �������� ���� ��� ��� ������.");
			}
		}
	});
}

function ChangeAds() {
	$("#info-msg-cab").hide();
	$("#loading").slideToggle();
	$("#BlockForm").slideToggle("slow");
	$("#OrderForm").slideToggle("slow");
	$("#InfoAds").slideToggle("slow");
	//window.history.pushState(null, null, "/advertise.php?ads=<?=$ads;?>");
	$("html, body").animate({scrollTop: $("#ScrollID").offset().top-10}, 700);
	$("#loading").slideToggle();
	return false;
}

function CtrlEnter(event) {
	if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD))) {
		gebi("Save").click();
	}
}

</script>

<?php
echo '<div id="ScrollID"></div>';

echo '<div id="InfoAds" style="display:block; align:justify; margin-bottom:6px;">';
	echo '<span id="adv-title-info" class="adv-title-open" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">������������ ����� - ��� ���?</span>';
	echo '<div id="adv-block-info" style="display:block; padding:5px 7px 10px 7px; text-align:justify; background-color:#FFFFFF;">';
		echo '����� �� <b style="color:#3A5FCD">'.strtoupper($_SERVER["HTTP_HOST"]).'</b> &mdash; ��� ���������� ������, ����������� ���������� �������� �� ��� ��������-������, ����������� ��� ��� ���������� �����-���� ��������, ����� ���� �������� �� 3-5 ����������� ��������. ';
		echo '��� �������� �������, ���� �����, ����� ������� ���-���� ������, �������� �� ������ ��������� ����� ��� ������ �� ������� Google ��� ������ �� ��������� �������� ������.';
		echo '<br>';
	echo '</div>';

	echo '<span id="adv-title-rules" class="adv-title-open" onclick="ShowHideBlock(\'-rules\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">�������</span>';
	echo '<div id="adv-block-rules" style="display:block; padding:5px 7px 10px 7px; text-align:center; background-color:#FFFFFF;">';
	echo '<div style="margin:7px auto; margin-bottom:50px; background:#F0F8FF; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:center; color:#E32636;">';
		//echo '<span class="warning-info" style="margin-bottom:0; font-weight:normal;">';
                        echo '���� ������ ���� ������������� �����, �� ���������� �������� ����������� � ��������.<br>';
                        echo '����� �� ������ ������ ���������� �� ����� 3-� (�������) �������� ����� �������� �� ������ �� ����.<br>';
                        echo '<b>���� �� ���������� ���� �� ����������� �����, �� ������ ���� �� ������ 10 �� ���� ����! <br>�����, ��� ����� ����� ������ 10 - ����� ���������!</b><br>';
                        echo '� ������ ��������� ����� �� �������, ��������� �� ����� 3-� ��������� �� �������.<br>';
			echo '��������� ���������� ����� � ����������� ����������� ���� �����������.<br>';
			echo '��� ����������� � ����������� ����������� <b>�������</b>.<br><br>';
			echo '<b>����� ����� ������� ��� �������� ������� � ������:</b><br>';
			echo '1. ���������� ������ ����������� � �����������.<br>';
			echo '2. ����� � �������������� ������� MoneyCaptcha, adf.ly.<br>';
			echo '3. ���������� ������.<br>';
			echo '<b>����� ���������, ���� ����� �������� ��������������� ��� ����������� �������.</b><br>';
//			echo '��� ��������� ������, ��������� �������� ����� ��������� ��� �������� �������!';
		//echo '</span>';
	echo '</div>';
echo '</div>';
echo '</div>';

echo '<div id="BlockForm" style="display:block;">';
echo '<div id="newform" onkeypress="CtrlEnter(event);">';
	echo '<table class="tables" style="border:none; margin:0; padding:0; width:100%;">';
	echo '<thead><tr>';
		echo '<th align="center" class="top">��������</th>';
		echo '<th align="center" class="top" colspan="2">��������</th>';
	echo '</thead></tr>';
	echo '<tr>';
		echo '<td align="left" width="220"><b>��������� �����</b></td>';
		echo '<td align="left"><input type="text" id="title" maxlength="60" value="" class="ok"></td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint1" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" colspan="3"><b>���������� ��� ������������ &darr;</b></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" colspan="2">';
			echo '<span class="bbc-bold" style="float:left;" title="�������� ������" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'description\'); return false;">�</span>';
			echo '<span class="bbc-italic" style="float:left;" title="�������� ��������" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'description\'); return false;">�</span>';
			echo '<span class="bbc-uline" style="float:left;" title="�������� ��������������" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'description\'); return false;">�</span>';
			echo '<span class="bbc-tline" style="float:left;" title="������������� �����" onClick="javascript:InsertTags(\'[s]\',\'[/s]\', \'description\'); return false;">ST</span>';
			echo '<span class="bbc-left" style="float:left;" title="��������� �� ������ ����" onClick="javascript:InsertTags(\'[left]\',\'[/left]\', \'description\'); return false;"></span>';
			echo '<span class="bbc-center" style="float:left;" title="��������� �� ������" onClick="javascript:InsertTags(\'[center]\',\'[/center]\', \'description\'); return false;"></span>';
			echo '<span class="bbc-right" style="float:left;" title="��������� �� ������� ����" onClick="javascript:InsertTags(\'[right]\',\'[/right]\', \'description\'); return false;"></span>';
			echo '<span class="bbc-justify" style="float:left;" title="��������� �� ������" onClick="javascript:InsertTags(\'[justify]\',\'[/justify]\', \'description\'); return false;"></span>';
			echo '<span class="bbc-url" style="float:left;" title="�������� URL" onClick="javascript:InsertTags(\'[url]\',\'[/url]\', \'description\'); return false;">URL</span>';
			echo '<span id="count1" style="display: block; float:right; color:#696969; margin-top:2px; margin-right:3px;">�������� ��������: 2000</span>';
			echo '<br>';
			echo '<div style="display: block; clear:both; padding-top:4px">';
				echo '<textarea id="description" class="ok" style="height:120px; width:99%;" onKeyup="descchange(\'1\', this, \'2000\');" onKeydown="descchange(\'1\', this, \'2000\');" onClick="descchange(\'1\', this, \'2000\');"></textarea>';
			echo '</div>';
		echo '</td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint2" class="hint-quest"></span></td>';
	echo '</tr>';

	echo '<tr>';
		echo '<td align="left"><b>URL �����</b> (������� http://)</td>';
		echo '<td align="left"><input type="text" id="url" maxlength="300" value="" class="ok"></td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hinturl" class="hint-quest"></span></td>';
	echo '</tr>';

	for($i=1; $i<=3; $i++){
		echo '<tr><td align="center" colspan="3" style="background: #DCE7EA; padding:6px 10px; color: #00649E; font:13px Tahoma, Arial, sans-serif; font-weight:bold; text-shadow:1px 1px 1px #FFF;">������ �'.$i.'</td></tr>';
		echo '<tr align="left">';
			echo '<td align="left" width="220"><b>���������� �������</b></td>';
			echo '<td align="left"><input type="text" id="quest'.$i.'" maxlength="300" value="" class="ok"></td>';
			if($i==1) {
				echo '<td align="center" width="16" rowspan="4" style="background: #EDEDED;"><span id="hint3" class="hint-quest"></span></td>';
			}else{
				echo '<td align="center" width="16" rowspan="4" style="background: #EDEDED;"></td>';
			}
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">������� ������ <span style="color: #009125;">(����������)</span></td>';
			echo '<td align="left"><input type="text" id="answ'.$i.'1" maxlength="30" value="" class="ok" style="color: #009125;"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">������� ������ <span style="color: #FF0000;">(������)</span></td>';
			echo '<td align="left"><input type="text" id="answ'.$i.'2" maxlength="30" value="" class="ok" style="color: #FF0000;"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">������� ������ <span style="color: #FF0000;">(������)</span></td>';
			echo '<td align="left"><input type="text" id="answ'.$i.'3" maxlength="30" value="" class="ok" style="color: #FF0000;"></td>';
		echo '</tr>';
	}
	echo '</table>';

	for($i=4; $i<=5; $i++){
		echo '<table class="tables" id="block_quest'.$i.'" style="display: none; margin:0;">';
		echo '<tr><td align="center" colspan="3" style="background: #DCE7EA; padding:6px 10px; color: #00649E; font:13px Tahoma, Arial, sans-serif; font-weight:bold; text-shadow:1px 1px 1px #FFF;">�������������� ������</td></tr>';
		echo '<tr>';
			echo '<td align="left" width="220"><b>���������� �������</b></td>';
			echo '<td align="left"><input type="text" id="quest'.$i.'" maxlength="300" value="" class="ok"></td>';
			echo '<td align="center" width="16" rowspan="4" style="background: #EDEDED;"><img src="/img/error2.gif" onClick="del_quest();" style="float: none; width:14px; cursor:pointer; margin:0; padding:0" title="������� ������"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">������� ������ <span style="color: #009125;">(����������)</span></td>';
			echo '<td align="left"><input type="text" id="answ'.$i.'1" maxlength="30" value="" class="ok" style="color: #009125;"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">������� ������ <span style="color: #FF0000;">(������)</span></td>';
			echo '<td align="left"><input type="text" id="answ'.$i.'2" maxlength="30" value="" class="ok" style="color: #FF0000;"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">������� ������ <span style="color: #FF0000;">(������)</span></td>';
			echo '<td align="left"><input type="text" id="answ'.$i.'3" maxlength="30" value="" class="ok" style="color: #FF0000;"></td>';
		echo '</tr>';
		echo '</table>';
	}

	echo '<table class="tables" id="block_add_quest" style="margin:0;">';
	echo '<tr><td align="center" style="padding: 8px 0;">';
		echo '<span class="sub-click" onClick="add_quest();">�������� ��� ������ '.($tests_cena_quest>0 ? "(+ ".p_floor($tests_cena_quest, 4)." ���.)" : false).'</span>';
	echo '</td></tr>';
	echo '</table>';

	echo '<span id="adv-title1" class="adv-title-close" onclick="ShowHideBlock(1);">�������������� ���������</span>';
	echo '<div id="adv-block1" style="display:none;">';
	echo '<table class="tables">';
	echo '<tr>';
		echo '<td align="left" width="220">���������� ������������</td>';
		echo '<td align="left">';
			echo '<select id="revisit" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
				echo '<option value="0" selected="selected">�������� ���� ������ 24 ����</option>';
				echo '<option value="1">�������� ���� ������ 3 ��� '.($tests_cena_revisit[1]>0 ? "(+ ".p_floor($tests_cena_revisit[1], 4)." ���.)" : false).'</option>';
				echo '<option value="2">�������� ���� ������ ������ '.($tests_cena_revisit[2]>0 ? "(+ ".p_floor($tests_cena_revisit[2], 4)." ���.)" : false).'</option>';
				echo '<option value="3">�������� ���� ������ 2 ������ '.($tests_cena_revisit[3]>0 ? "(+ ".p_floor($tests_cena_revisit[3], 4)." ���.)" : false).'</option>';
				echo '<option value="4">�������� ���� ������ ����� '.($tests_cena_revisit[4]>0 ? "(+ ".p_floor($tests_cena_revisit[4], 4)." ���.)" : false).'</option>';
			echo '</select>';
		echo '</td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint4" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">�������� ����</td>';
		echo '<td align="left">';
			echo '<select id="color" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
				echo '<option value="0">���</option>';
				echo '<option value="1">�� '.($tests_cena_color>0 ? "(+ ".p_floor($tests_cena_color, 4)." ���.)" : false).'</option>';
			echo '</select>';
		echo '</td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint5" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">���������� IP</td>';
		echo '<td align="left">';
			echo '<select id="unic_ip_user" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
				echo '<option value="0">���</option>';
				echo '<option value="1">��, 100% ���������� '.($tests_cena_unic_ip[1]>0 ? "(+ ".p_floor($tests_cena_unic_ip[1], 4)." ���.)" : false).'</option>';
				echo '<option value="2">��������� �� ����� �� 2 '.($tests_cena_unic_ip[2]>0 ? "(+ ".p_floor($tests_cena_unic_ip[2], 4)." ���.)" : false).'</option>';
			echo '</select>';
		echo '</td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint6" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">�� ���� �����������</td>';
		echo '<td align="left">';
			echo '<select id="date_reg_user" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
				echo '<option value="0">��� ������������ �������</option>';
				echo '<option value="1">�� 7 ���� � ������� �����������</option>';
				echo '<option value="2">�� 7 ���� � ������� �����������</option>';
				echo '<option value="3">�� 30 ���� � ������� �����������</option>';
				echo '<option value="4">�� 90 ���� � ������� �����������</option>';
			echo '</select>';
		echo '</td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint7" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">�� �������� ��������</td>';
		echo '<td align="left">';
			echo '<select id="sex_user" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
				echo '<option value="0">��� ������������ �������</option>';
				echo '<option value="1">������ �������</option>';
				echo '<option value="2">������ �������</option>';
			echo '</select>';
		echo '</td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint8" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';

	echo '<span id="adv-title2" class="adv-title-close" onclick="ShowHideBlock(2);">��������� �������������</span>';
	echo '<div id="adv-block2" style="display:none;">';
	echo '<table class="tables">';
	echo '<tr>';
		echo '<td colspan="2" align="center" style="border-right:none;"><a onclick="SetChecked(\'paste\');" style="width:100%; color:#008B00; font-weight:bold; cursor:pointer;"><center>�������� ���</center></a></td>';
		echo '<td colspan="2" align="center" style="border-left:none;"><a onclick="SetChecked();" style="width:100%; color:#FF0000; font-weight:bold; cursor:pointer;"><center>����� ���</center></a></td>';
		echo '<td align="center" width="16" rowspan="10" style="background: #EDEDED;"><span id="hint9" class="hint-quest"></span></td>';
	echo '</tr>';
	include(DOC_ROOT."/advertise/func_geotarg.php");
	echo '</table>';
	echo '</div>';

	echo '<table class="tables">';
	echo '<tr><td align="center" colspan="3" style="background: #DCE7EA; padding:6px 10px; color: #00649E; font:13px Tahoma, Arial, sans-serif; font-weight:bold; text-shadow:1px 1px 1px #FFF;">����� � ������ ������</td></tr>';
	echo '<tr>';
		echo '<td align="left" width="220"><b>������ ������</b></td>';
		echo '<td align="left">';
			echo '<select id="method_pay" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
				require_once("".DOC_ROOT."/method_pay/method_pay_form.php");
			echo '</select>';
		echo '</td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint10" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>����� ����������</b></td>';
		echo '<td align="left"><input type="text" id="money_add" maxlength="7" value="100" class="ok12"  onChange="PlanChange();" onKeydowm="PlanChange();" onKeyup="PlanChange();">&nbsp;&nbsp;(������� - '.$tests_min_pay.' ���.)</td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint11" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" height="23px"><b>��������������</b></td>';
		echo '<td align="left" id="price_user"></td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint12" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" height="23px"><b>���� ������ �����</b></td>';
		echo '<td align="left" id="price_one"></td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint13" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" height="23px"><b>���������� ����������</b></td>';
		echo '<td align="left" id="count_test"></td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint14" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '</table>';
echo '</div>';

echo '<br>';
echo '<div id="info-msg-cab" style="display:none;"></div>';
echo '<div align="center"><span id="Save" onClick="SaveAds(0, \'tests\');" class="proc-btn" style="float:none; width:160px;">�������� �����</span></div>';

echo '</div>'; ### END BlockForm ###

echo '<div id="OrderForm" style="display:none;"></div>';
echo '<div id="info-msg-pay" style="display:none;"></div>';

?>
<script language="JavaScript">ClearForm();</script>