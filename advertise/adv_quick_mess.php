<?php
if (!DEFINED("ADVERTISE")) {die ("Hacking attempt!");}
if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">��� ������ ����� ���� ������� ���������� ��������������!</span>';
	include(ROOT_DIR."/footer.php");
	exit();
}

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='quick_mess_�ena' AND `howmany`='1'");
	$quick_mess_cena = number_format(mysql_result($sql,0,0), 2, ".", "");
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='quick_mess_url' AND `howmany`='1'");
	$quick_mess_cena_url = number_format(mysql_result($sql,0,0), 2, ".", "");

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='quick_mess_color' AND `howmany`='1'");
	$quick_mess_cena_color = number_format(mysql_result($sql,0,0), 2, ".", "");
?>

<script type="text/javascript" language="JavaScript">
var tm;

function HideMsg(id, timer) {
        clearTimeout(tm);
	tm = setTimeout(function() {$("#"+id).slideToggle(700);}, timer);
	return false;
}

function ClearForm() {
	$("#url").val("");
	$("#description").val("");
	//$("#method_pay").val("2");
	$("#tosaccept").prop("checked", false);
	return false;
}

function ShowHideBlock(id) {
	if($("#adv-title"+id).attr("class") == "adv-title-open") {
		$("#adv-title"+id).attr("class", "adv-title-close")
	} else {
		$("#adv-title"+id).attr("class", "adv-title-open")
	}
	$("#adv-block"+id).slideToggle("slow");
}

function SaveAds(id, type) {
	var tosaccept = $("#tosaccept").prop("checked") == true ? 1 : 0;
	var url = $.trim($("#url").val());
	var description = $.trim($("#description").val());
	var color = $.trim($("#color").val());
	//var method_pay = $.trim($("#method_pay").val());
	var method_pay = 2;
	//alert (method_pay);
	$("#info-msg-cab").html("").hide();

	if(tosaccept!=1) {
		alert("���� �� ��������� �������, � ���� ���������� �����������!");
		return false;
	}

	$.ajax({
		type: "POST", url: "/advertise/ajax/ajax_advertise.php?rnd="+Math.random(), 
		data: {'op':'add', 'type':type, 'url':url, 'description':description, 'method_pay':method_pay, 'color':color }, 
		dataType: 'json',
		error: function(request, status, errortext) {
			$("#loading").slideToggle();
			var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
			$("#info-msg-cab").show().html('<span class="msg-error">������ AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>');
			//console.log(request, status, errortext);
		},
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) {
			$("#loading").slideToggle();
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if(result == "OK") {
				$("#info-msg-cab").show();
				$("#OrderForm").html(message);
				$("#BlockForm").slideToggle("slow");
				$("#OrderForm").slideToggle("slow");
				$("#InfoAds").slideToggle("slow");
				$("html, body").animate({scrollTop: $("#ScrollID").offset().top-10}, 700);
				return false;
			} else {
				alert(message);
				return false;
			}
		}
	});
} 

function SaveAdsq(id, type) {
	var tosaccept = $("#tosaccept").prop("checked") == true ? 1 : 0;
	var url = $.trim($("#url").val());
	var description = $.trim($("#description").val());
	var color = $.trim($("#color").val());
	//var method_pay = $.trim($("#method_pay").val());
	var method_pay = 1;
	//alert (method_pay);
	$("#info-msg-cab").html("").hide();

	if(tosaccept!=1) {
		alert("���� �� ��������� �������, � ���� ���������� �����������!");
		return false;
	}

	$.ajax({
		type: "POST", url: "/advertise/ajax/ajax_advertise.php?rnd="+Math.random(), 
		data: {'op':'add', 'type':type, 'url':url, 'description':description, 'method_pay':method_pay, 'color':color }, 
		dataType: 'json',
		error: function(request, status, errortext) {
			$("#loading").slideToggle();
			var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
			$("#info-msg-cab").show().html('<span class="msg-error">������ AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>');
			//console.log(request, status, errortext);
		},
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) {
			$("#loading").slideToggle();
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if(result == "OK") {
				$("#info-msg-cab").show();
				$("#OrderForm").html(message);
				$("#BlockForm").slideToggle("slow");
				$("#OrderForm").slideToggle("slow");
				$("#InfoAds").slideToggle("slow");
				$("html, body").animate({scrollTop: $("#ScrollID").offset().top-10}, 700);
				return false;
			} else {
				alert(message);
				return false;
			}
		}
	});
} 

function PayAds_0(id, type) {
	$.ajax({
		type: "POST", url: "/advertise/ajax/ajax_advertise.php?rnd="+Math.random(), 
		data: {'op':'pay_rek', 'type':type, 'id':id}, 
		dataType: 'json',
		error: function(request, status, errortext) {
			$("#loading").slideToggle();
			var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
			$("#info-msg-cab").show().html('<span class="msg-error">������ AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>');
			//console.log(request, status, errortext);
		},
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) { 
			$("#loading").slideToggle();
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if(result == "OK") {
				$("html, body").animate({scrollTop:0}, 700);
				$("#OrderForm").html('<span class="msg-ok">'+message+'</span>');
				setTimeout(function() {
					location.href = "/advertise.php?ads=quick_mess";
				}, 2000);
				return false;
			} else {
				alert(message);
				return false;
			}
		}
	});
}

function PayAds_1(id, type) {
	$.ajax({
		type: "POST", url: "/advertise/ajax/ajax_advertise.php?rnd="+Math.random(), 
		data: {'op':'pay_os', 'type':type, 'id':id}, 
		dataType: 'json',
		error: function(request, status, errortext) {
			$("#loading").slideToggle();
			var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
			$("#info-msg-cab").show().html('<span class="msg-error">������ AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>');
			//console.log(request, status, errortext);
		},
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) {
			$("#loading").slideToggle();
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if(result == "OK") {
				$("html, body").animate({scrollTop:0}, 700);
				$("#OrderForm").html('<span class="msg-ok">'+message+'</span>');
				setTimeout(function() {
					location.href = "/advertise.php?ads=quick_mess";
				}, 2000);
				return false;
			} else {
				alert(message);
				return false;
			}
		}
	});
}

function DeleteAds(id, type) {
	$.ajax({
		type: "POST", url: "/advertise/ajax/ajax_advertise.php?rnd="+Math.random(), 
		data: {'op':'del', 'type':type, 'id':id}, 
		dataType: 'json',
		error: function(request, status, errortext) {
			$("#loading").slideToggle();
				var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
				$("#info-msg-cab").show().html('<span class="msg-error">������ AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>');
				//console.log(request, status, errortext);
		},
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) {
			$("#loading").slideToggle();
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if(result == "OK") {
				$("html, body").animate({scrollTop:0}, 700);
				$("#BlockForm").slideToggle("slow");
				$("#OrderForm").slideToggle("slow");
				$("#InfoAds").slideToggle("slow");
				ClearForm();
			} else {
				alert(message);
				return false;
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

	$("html, body").animate({scrollTop: $("#ScrollID").offset().top-10}, 700);
	$("#loading").slideToggle();
	return false;
}

function CtrlEnter(event) {
	if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD))) {
		$("#Save").click();
	}
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


function PlanChange(){
	var price_mess = <?=$quick_mess_cena;?>;
	var price_mess_url = <?=$quick_mess_cena_url;?>;
	var price_mess_color = <?=$quick_mess_cena_color;?>;

	var url = $.trim($("#url").val());
	var color = $.trim($("#color").val());

	if(url != "") price_mess += price_mess_url;
	if(color == 1) price_mess += price_mess_color;

	/*$("#price").html('<td align="left">��������� ����������</td><td align="left"><span style="color:#FF0000; font-size:14px;">' + number_format(price_mess, 2, '.', ' ') + ' ���.</span></td>');*/
	$("#price").html('' + number_format(price_mess, 2, '.', ' ') + ' ���.');
	$("#priceq").html('' + number_format(price_mess, 2, '.', ' ') + ' ���.');
	
}
</script>

<?php
echo '<div id="ScrollID"></div>';

echo '<div id="InfoAds" style="display:block; align:justify; margin-bottom:10px;">';
	echo '<span id="adv-title-info" class="adv-title-open" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">������� ��������� - ��� ���?</span>';
	echo '<div id="adv-block-info" style="display:block; padding:5px 7px 3px 7px; text-align:justify; background-color:#F0F8FF;">';
	echo '������� ��������� �� <b style="color:#3A5FCD">'.strtoupper($_SERVER["HTTP_HOST"]).'</b> &mdash; ��� ��������� ������� ����������� � ������ ����� ����� � ������������ �� ���� ���������. � ��������� ����� ��������� - ������������, ��������� ��� ���� �������. ��������� �� ������ �������� ����� <a href="/tos.php" target="_blank">�������</a> �����. ����� ���������� ���� ��������� ����� �� ������ ������� � ������ � ������ ����������� ����� ����� �������� ��� �� ���� ������� ���� �� �������� ��������� �������, ���� ����� �� ������ �� ����.';

	echo '</div>';
echo '</div>';

echo '<div id="BlockForm" style="display:block;">';
echo '<div id="newform" onkeypress="CtrlEnter(event);">';
	echo '<table class="tables" style="border:none; margin:0; padding:0; width:100%;">';
	echo '<thead><tr><th align="center" colspan="3">����� ���������� �������</th></thead></tr>';
	echo '<tr>';
		echo '<td align="left" width="220"><b>���������</b></td>';
		echo '<td align="left"><input type="text" id="description" maxlength="60" value="" class="ok"></td>'; 
		
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>URL �����</b> (�������������)</td>';
		echo '<td align="left"><input type="text" id="url" maxlength="300" value="" class="ok"></td>';
		
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>�������� ������</td>';
		echo '<td align="left"><select name="color" id="color" style="margin-bottom:1px;" onchange="PlanChange();" onclick="PlanChange();"><option value="0">���</option><option value="1">�� (+'.$quick_mess_cena_color.' ���.)</option></select></td>';
		
	echo '</tr>';
	//echo '<tr>';
		//echo '<td align="left">������ ������</td>';
		//echo '<td align="left">';
			//echo '<select id="method_pay" class="ok">'; 
				//require_once(DOC_ROOT."/method_pay/method_pay_mes.php");
			//echo '</select>';
		//echo '</td>';
		
	//echo '</tr>';
	//echo '<tr id="price"></tr>';
	/*echo '<tr>';
		echo '<td align="left" height="26"><b>��������� ����������</b></td>';
		echo '<td align="left"><span style="color:#FF0000; font-size:14px;">'.$cena_pay_row.' ���.</span></td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint4" class="hint-quest"></span></td>';
	echo '</tr>';*/

	echo '<tr>';
		echo '<td align="center" colspan="3" height="24"><input type="checkbox" id="tosaccept" value="1" /> � <a href="/tos.php" target="_blank" title="��������� � ����� ����"><b>���������</b></a> ���������� ������� ��������� ��������(��)</td>';
	echo '</tr>';
	echo '<br>';
echo '<div id="info-msg-cab" style="display:none;"></div>';

	echo '</table>';
echo '</div>';
echo '<div style="text-align:center;">';
	echo '<span id="adv-title3" class="adv-title-open" onclick="ShowHideBlock(3);">������� ������ ������</span>';
	echo '<div id="adv-block3" style="display:block;">';
		

	  
	  
echo '<span id="Save" onClick="SaveAdsq(0, \'quick_mess\');"><button id="method_pay" name="method_pay" value="1" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-os1">';
echo '<div><div><div><span class="line-green"><span id="price"></span> </span></div></div></div>'; 
	  echo '</div> </button></span>';
	  
	  		echo '<span id="Save" onClick="SaveAds(0, \'quick_mess\');"><button id="method_pay"  name="method_pay" value="2" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-rs1 cash-start">';
      echo '<div><div><div><span class="line-green"><span id="priceq"></span> </span></div></div></div>'; 
	  echo '</div> </button></span>';

echo '</div></div></div></div>'; ### END BlockForm ###

echo '<div id="OrderForm" style="display:none;"></div>';
echo '<div id="info-msg-pay" style="display:none;"></div>';

?>
<script language="JavaScript">ClearForm(); PlanChange();</script>