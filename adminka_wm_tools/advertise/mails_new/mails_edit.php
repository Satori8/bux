<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>�������������� ��������� �����</b></h3>';

mysql_query("UPDATE `tb_ads_mails` SET `status`='3', `date_end`='".time()."' WHERE `status`>'0' AND (`totals`<'1' OR ( `nolimit`>'0' AND `nolimit`<='".time()."') )") or die(mysql_error());

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_hit' AND `howmany`='1'");
$mails_cena_hit = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nacenka' AND `howmany`='1'");
$mails_nacenka = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_min_hits' AND `howmany`='1'");
$mails_min_hits = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_color' AND `howmany`='1'");
$mails_cena_color = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_active' AND `howmany`='1'");
$mails_cena_active = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_timer_ot' AND `howmany`='1'");
$mails_timer_ot = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_timer_do' AND `howmany`='1'");
$mails_timer_do = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_timer' AND `howmany`='1'");
$mails_cena_timer = number_format(mysql_result($sql,0,0), 4, ".", "");

for($i=1; $i<=2; $i++) {
	$mails_cena_revisit[0] = 0;
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_revisit' AND `howmany`='$i'");
	$mails_cena_revisit[$i] = number_format(mysql_result($sql,0,0), 4, ".", "");
}

for($i=1; $i<=2; $i++) {
	$mails_cena_unic_ip[0] = 0;
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_unic_ip' AND `howmany`='$i'");
	$mails_cena_unic_ip[$i] = number_format(mysql_result($sql,0,0), 4, ".", "");
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_gotosite' AND `howmany`='1'");
$mails_cena_gotosite = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_uplist' AND `howmany`='1'");
$mails_cena_uplist = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nolimit_status' AND `howmany`='1'");
$mails_nolimit_status = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nolimit_cena' AND `howmany`='1'");
$mails_nolimit_cena = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nolimit_timer' AND `howmany`='1'");
$mails_nolimit_timer = number_format(mysql_result($sql,0,0), 0, ".", "");

?><script type="text/javascript">
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
</script><?php

function limpiarez($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_replace("'", "", $mensaje);
	$mensaje = str_replace("`", "", $mensaje);
	$mensaje = str_replace('"', "&#34;", $mensaje);
	$mensaje = str_replace("?", "&#063;", $mensaje);
	$mensaje = str_replace("$", "&#036;", $mensaje);

	$mensaje = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mensaje);
	$mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);
	$mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);

	$mensaje = mysql_real_escape_string(trim($mensaje));
	$mensaje = htmlspecialchars(trim($mensaje), NULL, "CP1251");

	$mensaje = str_replace("  ", " ", $mensaje);
	$mensaje = str_replace("&amp amp ", "&", $mensaje);
	$mensaje = str_replace("&amp;amp;", "&", $mensaje);
	$mensaje = str_replace("&&", "&", $mensaje);
	$mensaje = str_replace("http://http://", "http://", $mensaje);
	$mensaje = str_replace("https://https://", "https://", $mensaje);
	$mensaje = str_replace("&#063;", "?", $mensaje);
	$mensaje = str_replace("&amp;", "&", $mensaje);

	return $mensaje;
}

require("navigator/navigator.php");
$perpage = 20;
$sql_p = mysql_query("SELECT `id` FROM `tb_ads_mails` WHERE `status`>'0'");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;


$system_pay[-1] = "�����";
$system_pay[0] = "�������";
$system_pay[1] = "WebMoney";
$system_pay[2] = "RoboKassa";
$system_pay[3] = "LiqPay";
$system_pay[4] = "Interkassa";
$system_pay[5] = "Payeer";
$system_pay[6] = "Qiwi";
$system_pay[7] = "PerfectMoney";
$system_pay[8] = "������.������";
$system_pay[11] = "MEGAKASSA.RU";
$system_pay[10] = "����. ����";

$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiarez(trim($_GET["id"]))) : false;
$option = ( isset($_GET["option"]) && preg_match("|^[a-zA-Z0-9\-_-]{1,20}$|", trim($_GET["option"])) ) ? limpiarez(trim($_GET["option"])) : false;

if($option=="dell") {
	$sql = mysql_query("SELECT `id` FROM `tb_ads_mails` WHERE `id`='$id' AND `status`>'0'");
	if(mysql_num_rows($sql)>0) {
		mysql_query("DELETE FROM `tb_ads_mails` WHERE `id`='$id'") or die(mysql_error());

		echo '<span id="info-msg" class="msg-error">������� #'.$id.' �������!</span>';
		echo '<script type="text/javascript"> setTimeout(function() {hidemsg()}, 2000); </script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="2;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page='.$page.'"></noscript>';
	}

}elseif($option=="edit") {
	$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `status`>'0'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$plan_table = $row["plan"];
		$totals_table = $row["totals"];
		$nolimit_table = $row["nolimit"];
		$geo_targ = (isset($row["geo_targ"]) && trim($row["geo_targ"])!=false) ? explode(", ", $row["geo_targ"]) : array();

		if(count($_POST)>0) {

			//$id = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"])) ) ? intval(limpiarez(trim($_POST["id"]))) : false;
			$merch_tran_id = ( isset($_POST["merch_tran_id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["merch_tran_id"])) ) ? intval(limpiarez(trim($_POST["merch_tran_id"]))) : false;
			$wmid = (isset($_POST["wmid"]) && preg_match("|^[\d]{12}$|", trim($_POST["wmid"]))) ? limpiarez(trim($_POST["wmid"])) : false;
			$user_name = (isset($_POST["username"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_POST["username"]))) ? uc($_POST["username"]) : false;

			$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 255) : false;
			$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]), 5000) : false;
			if (get_magic_quotes_gpc()) {$description = stripslashes($description);}
			$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]),300) : false;
			$plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["plan"])) ) ? intval(limpiarez(trim($_POST["plan"]))) : false;
			$timer = ( isset($_POST["timer"]) && preg_match("|^[\d]{1,3}$|", trim($_POST["timer"])) && intval(limpiarez(trim($_POST["timer"]))) >= $mails_timer_ot && intval(limpiarez(trim($_POST["timer"]))) <= $mails_timer_do ) ? intval(limpiarez(trim($_POST["timer"]))) : false;
			$color = (isset($_POST["color"]) && (intval($_POST["color"])==0 | intval($_POST["color"])==1)) ? intval(trim($_POST["color"])) : "0";
			$question = (isset($_POST["question"])) ? limitatexto(limpiarez($_POST["question"]), 255) : false;
			$answer_t = (isset($_POST["answer_t"])) ? limitatexto(limpiarez($_POST["answer_t"]), 255) : false;
			$answer_f_1 = (isset($_POST["answer_f_1"])) ? limitatexto(limpiarez($_POST["answer_f_1"]), 255) : false;
			$answer_f_2 = (isset($_POST["answer_f_2"])) ? limitatexto(limpiarez($_POST["answer_f_2"]), 255) : false;

			$nolimit = (isset($_POST["nolimit"]) && (intval($_POST["nolimit"])==0 | intval($_POST["nolimit"])==1)) ? intval(trim($_POST["nolimit"])) : "0";
			$active = (isset($_POST["active"]) && (intval($_POST["active"])==0 | intval($_POST["active"])==1)) ? intval(trim($_POST["active"])) : "0";
			$gotosite = (isset($_POST["gotosite"]) && (intval($_POST["gotosite"])==0 | intval($_POST["gotosite"])==1)) ? intval(trim($_POST["gotosite"])) : "0";
			$up_list = (isset($_POST["up_list"]) && (intval($_POST["up_list"])>=0 | intval($_POST["up_list"])<=1)) ? intval(trim($_POST["up_list"])) : "0";
			$revisit = (isset($_POST["revisit"]) && (intval($_POST["revisit"])>=0 | intval($_POST["revisit"])<=2)) ? intval(trim($_POST["revisit"])) : "0";
			$new_users = (isset($_POST["new_users"]) && (intval($_POST["new_users"])>=0 | intval($_POST["new_users"])<=1)) ? intval($_POST["new_users"]) : "0";
			$unic_ip = (isset($_POST["unic_ip"]) && (intval($_POST["unic_ip"])>=0 | intval($_POST["unic_ip"])<=2)) ? intval($_POST["unic_ip"]) : "0";
			$no_ref = (isset($_POST["no_ref"]) && (intval($_POST["no_ref"])>=0 | intval($_POST["no_ref"])<=1)) ? intval($_POST["no_ref"]) : "0";
			$sex_adv = (isset($_POST["sex_adv"]) && (intval($_POST["sex_adv"])>=0 | intval($_POST["sex_adv"])<=2)) ? intval($_POST["sex_adv"]) : "0";
			$to_ref = (isset($_POST["to_ref"]) && (intval($_POST["to_ref"])>=0 | intval($_POST["to_ref"])<=2)) ? intval($_POST["to_ref"]) : "0";
			$black_url = getHost($url);
			$wm_check = (isset($_POST["wm_check"]) && preg_match("|^[0-1]{1}$|", trim($_POST["wm_check"])) ) ? intval(trim($_POST["wm_check"])) : "0";

			$new_totals = ($plan - $plan_table);

			if($nolimit_table>0) {
				$plan = $row["plan"];;
				$timer = $row["timer"];
				$up_list = $row["up_list"];
				$revisit = $row["revisit"];
				$color = $row["color"];
				$active = $row["active"];
				$gotosite = $row["gotosite"];
				$unic_ip = $row["unic_ip"];
				$new_users = $row["new_users"];
				$no_ref = $row["no_ref"];
				$sex_adv = $row["sex_adv"];
				$to_ref = $row["to_ref"];
			}

			$country = (isset($_POST["country"]) && count($_POST["country"])>0) ? (array_map('mysql_real_escape_string', $_POST["country"])) : false;
			$geo_cod_arr = array(
				1  => 'RU', 2  => 'UA', 3  => 'BY', 4  => 'MD', 5  => 'KZ', 6  => 'AM', 7  => 'UZ', 8  => 'LV', 
				9  => 'DE', 10 => 'GE',	11 => 'LT', 12 => 'FR', 13 => 'AZ', 14 => 'US', 15 => 'VN', 16 => 'PT', 
				17 => 'GB', 18 => 'BE', 19 => 'ES', 20 => 'CN', 21 => 'TJ', 22 => 'EE', 23 => 'IT', 24 => 'KG', 
				25 => 'IL', 26 => 'CA', 27 => 'TM', 28 => 'BG', 29 => 'IR', 30 => 'GR', 31 => 'TR', 32 => 'PL', 
				33 => 'FI', 34 => 'EG', 35 => 'SE', 36 => 'RO'
			);
			$geo_name_arr_ru = array(
				'RU' => '������', 	'UA' => '�������', 	'BY' => '����������', 	'MD' => '��������', 	'KZ' => '���������', 	'AM' => '�������', 
				'UZ' => '����������',	'LV' => '������',	'DE' => '��������', 	'GE' => '������', 	'LT' => '�����', 	'FR' => '�������', 
				'AZ' => '�����������', 	'US' => '���', 		'VN' => '�������', 	'PT' => '����������', 	'GB' => '������', 	'BE' => '�������', 
				'ES' => '�������', 	'CN' => '�����',	'TJ' => '�����������',  'EE' => '�������', 	'IT' => '������', 	'KG' => '��������',
				'IL' => '�������', 	'CA' => '������', 	'TM' => '������������', 'BG' => '��������',	'IR' => '����', 	'GR' => '������', 
				'TR' => '������', 	'PL' => '������',	'FI' => '���������', 	'EG' => '������', 	'SE' => '������', 	'RO' => '�������'
			);
			if(is_array($country)) {
				foreach($country as $key => $val) {
					if(array_search($val, $geo_cod_arr)) {
						$id_country = array_search($val, $geo_cod_arr);
						$country_arr[] = $val;
					}
				}
			}
			$country = isset($country_arr) ? trim(strtoupper(implode(', ', $country_arr))) : false;

			$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");
			if(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
				$row = mysql_fetch_assoc($sql_bl);
				echo '<span class="msg-error">���� <a href="http://'.$row["domen"].'/" target="_blank" style="color:#0000FF">'.$row["domen"].'</a> ������������ � ������� � ������ ������ ������� '.strtoupper($_SERVER["HTTP_HOST"]).' !<br>�������: '.$row["cause"].'</span>';
			}elseif($url==false | $url=="http://" | $url=="https://") {
				echo '<span class="msg-error">�� ������� ������ �� ����!</span>';
			}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
				echo '<span class="msg-error">�� ����� ������� ������ �� ����!</span>';
			}elseif(is_url($url)!="true") {
				echo is_url($url);
			}elseif($title==false) {
				echo '<span class="msg-error">�� ��������� ���� ��������� ������.</span>';
			}elseif($description==false) {
				echo '<span class="msg-error">�� ��������� ���� ���������� ������.</span>';
			}elseif($question==false) {
				echo '<span class="msg-error">�� ��������� ���� ����������� ������.</span>';
			}elseif($answer_t==false) {
				echo '<span class="msg-error">�� ��������� ���� ������� ������ (����������).</span>';
			}elseif($answer_f_1==false) {
				echo '<span class="msg-error">�� ��������� ���� ������� ������ (������).</span>';
			}elseif($answer_f_2==false) {
				echo '<span class="msg-error">�� ��������� ���� ������� ������ (������).</span>';
			}elseif($timer==false) {
				echo '<span class="msg-error">����� ��������� ������ ���� � �������� �� '.$mails_timer_ot.' ���. �� '.$mails_timer_do.' ���.</span>';
			}elseif($plan<$mails_min_hits) {
				echo '<span class="msg-error">����������� ����� - '.$mails_min_hits.' �����.</span>';
			}else{
				if($plan==$plan_table && $nolimit_table==0) {
					$new_plan = $plan;
					$new_totals = $totals_table;
				}elseif($plan>$plan_table && $nolimit_table==0) {
					$new_plan = $plan;
					$new_totals = $totals_table + ($plan - $plan_table);
				}elseif($plan<$plan_table && $nolimit_table==0) {
					echo '<span class="msg-error">���������� ���������� ������ ���������.</span><br>';
					exit();
				}else{
					$new_plan = $plan;
					$new_totals = $totals_table;
				}

				mysql_query("UPDATE `tb_ads_mails` SET 
					`wm_check`='$wm_check', `wmid`='$wmid',`username`='$user_name',`title`='$title',`url`='$url',`description`='$description',
					`question`='$question',`answer_t`='$answer_t',`answer_f_1`='$answer_f_1',`answer_f_2`='$answer_f_2',
					`plan`='$new_plan',`totals`='$new_totals',`color`='$color',`active`='$active',`gotosite`='$gotosite',
					`geo_targ`='$country',`revisit`='$revisit',`timer`='$timer',`new_users`='$new_users',`unic_ip`='$unic_ip',
					`no_ref`='$no_ref',`sex_adv`='$sex_adv',`to_ref`='$to_ref' 
				WHERE `id`='$id'") or die(mysql_error());

				echo '<span class="msg-ok">��������� ������� ���������.</span>';
				echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page='.$page.'")\', 2000); </script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="2;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page='.$page.'"></noscript>';
				exit();
			}
		}

		?><script type="text/javascript" language="JavaScript"> 
		function SbmFormB() {
			if ( document.forms["formzakaz"].title.value == '' ) {
				alert('�� �� ������� ��������� ������');
				document.forms["formzakaz"].title.style.background = "#FFDBDB";
				document.forms["formzakaz"].title.focus();
				return false;
			}
			if ( document.forms["formzakaz"].url.value == '' | document.forms["formzakaz"].url.value == 'http://' | document.forms["formzakaz"].url.value == 'https://' ) {
				alert('�� �� ������� URL-����� �����');
				document.forms["formzakaz"].url.style.background = "#FFDBDB";
				document.forms["formzakaz"].url.focus();
				return false;
			}
			if ( document.forms["formzakaz"].description.value == '' ) {
				alert('�� �� ������� ������� �������� ������');
				document.forms["formzakaz"].description.style.background = "#FFDBDB";
				document.forms["formzakaz"].description.focus();
				return false;
			}
			if ( document.forms["formzakaz"].question.value == '' ) {
				alert('�� �� ������� ����������� ������');
				document.forms["formzakaz"].description.style.background = "#FFDBDB";
				document.forms["formzakaz"].description.focus();
				return false;
			}
			if (document.forms['formzakaz'].answer_t.value == '') {
				alert('�� �� ������� ������� ������ (����������)');
				document.forms["formzakaz"].answer_t.style.background = "#FFDBDB";
				document.forms["formzakaz"].answer_t.focus();
				return false;
			}
		        if (document.forms['formzakaz'].answer_f_1.value == '') {
				alert('�� �� ������� ������� ������ (������)');
				document.forms["formzakaz"].answer_f_1.style.background = "#FFDBDB";
				document.forms["formzakaz"].answer_f_1.focus();
				return false;
			}
		        if (document.forms['formzakaz'].answer_f_2.value == '') {
				alert('�� �� ������� ������� ������ (������)');
				document.forms["formzakaz"].answer_f_2.style.background = "#FFDBDB";
				document.forms["formzakaz"].answer_f_2.focus();
				return false;
			}
			if ( document.forms["formzakaz"].nolimit.value == '0' && ( document.forms["formzakaz"].plan.value == '' | document.forms["formzakaz"].plan.value < <?=$mails_min_hits;?>) ) {
				alert('�� �� ������� ���������� ����������, ������� <?=$mails_min_hits;?>');
				document.forms["formzakaz"].plan.style.background = "#FFDBDB";
				document.forms["formzakaz"].plan.focus();
				return false;
			}
			if ( document.forms["formzakaz"].timer.value == '' | document.forms["formzakaz"].timer.value < <?=$mails_timer_ot;?> | document.forms["formzakaz"].timer.value > <?=$mails_timer_do;?> ) {
				alert('����� ��������� ������ ���� � �������� �� <?=$mails_timer_ot;?> ���. �� <?=$mails_timer_do;?> ���.');
				document.forms["formzakaz"].timer.style.background = "#FFDBDB";
				document.forms["formzakaz"].timer.focus();
				return false;
			}
			document.forms["formzakaz"].submit();
			return true;
		}

		function descchange(id, elem, count_s) {
			if (elem.value.length > count_s) { elem.value = elem.value.substr(0,count_s); }
			$("#count"+id).html("�������� ��������: " +(count_s-elem.value.length));
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
		</script><?php

		echo '<form method="post" action="" name="formzakaz" onSubmit="return SbmFormB(); return false;" id="newform">';
		echo '<table class="tables" style="margin:0; padding:0;">';
		echo '<thead><tr><th>��������</th><th>��������</th></tr></thead>';
		echo '<tbody>';
			echo '<tr>';
				echo '<td align="left" width="240">����������� �������</td>';
				echo '<td>'.($row["nolimit"]>0 ? "��, �� ".DATE("d.m.Y�. H:i:s", $row["nolimit"]) : "���").'</td>';
			echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>WMID</b></td>';
			echo '<td align="left"><input class="ok" name="wmid" maxlength="255" value="'.$row["wmid"].'" type="text" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�����</b></td>';
			echo '<td align="left"><input class="ok" name="username" maxlength="255" value="'.$row["username"].'" type="text" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������� ������</b></td>';
			echo '<td align="left"><input class="ok" name="title" maxlength="255" value="'.$row["title"].'" type="text" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" style="border-bottom:none;"><b>���������� ������ &darr;</b></td>';
			echo '<td align="left" style="border-bottom:none;">';
				echo '<span class="bbc-bold" style="float:left;" title="�������� ������" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'description\'); return false;">�</span>';
				echo '<span class="bbc-italic" style="float:left;" title="�������� ��������" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'description\'); return false;">�</span>';
				echo '<span class="bbc-uline" style="float:left;" title="�������� ��������������" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'description\'); return false;">�</span>';
				echo '<span class="bbc-tline" style="float:left;" title="������������� �����" onClick="javascript:InsertTags(\'[s]\',\'[/s]\', \'description\'); return false;">ST</span>';
				echo '<span class="bbc-left" style="float:left;" title="��������� �� ������ ����" onClick="javascript:InsertTags(\'[left]\',\'[/left]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-center" style="float:left;" title="��������� �� ������" onClick="javascript:InsertTags(\'[center]\',\'[/center]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-right" style="float:left;" title="��������� �� ������� ����" onClick="javascript:InsertTags(\'[right]\',\'[/right]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-justify" style="float:left;" title="��������� �� ������" onClick="javascript:InsertTags(\'[justify]\',\'[/justify]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-url" style="float:left;" title="�������� URL" onClick="javascript:InsertTags(\'[url]\',\'[/url]\', \'description\'); return false;">URL</span>';
				echo '<span id="count1" style="display: block; float:right; font-size:11px; color:#696969; margin-top:3px; margin-right:15px;">�������� ��������: 5000</span>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="2" style="border-top:none;">';
				echo '<textarea id="description" name="description" class="ok" style="height:120px; width:98.5%;" onKeyup="descchange(\'1\', this, \'5000\');" onKeydown="descchange(\'1\', this, \'5000\'); this.style.background=\'#FFFFFF\';" onClick="descchange(\'1\', this, \'5000\');">'.$row["description"].'</textarea>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">����������� ������ � ������</td>';
			echo '<td align="left"><input class="ok" name="question" maxlength="255" value="'.$row["question"].'" type="text" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">������� ������ <span style="color: #009125;">(����������)</span></td>';
			echo '<td align="left"><input class="ok" style="color: #009125;" name="answer_t" maxlength="255" value="'.$row["answer_t"].'" type="text" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">������� ������ <span style="color: #FF0000;">(������)</span></td>';
			echo '<td align="left"><input class="ok" style="color: #FF0000;" name="answer_f_1" maxlength="255" value="'.$row["answer_f_1"].'" type="text" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">������� ������ <span style="color: #FF0000;">(������)</span></td>';
			echo '<td align="left"><input class="ok" style="color: #FF0000;" name="answer_f_2" maxlength="255" value="'.$row["answer_f_2"].'" type="text" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>URL �����</b></td>';
			echo '<td align="left"><input class="ok" name="url" maxlength="300" value="'.$row["url"].'" type="text" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="31"><b>����� �������</b></td>';
			echo '<td>';
				echo '<select id="wm_check" name="wm_check">';
					echo '<option value="0" '.($row["wm_check"]==0 ? 'selected="selected"' : false).'>���� ������������� �������</option>';
					echo '<option value="1" '.($row["wm_check"]==1 ? 'selected="selected"' : false).'>������ ������������� � ������������� WMID</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">���������� ����������</td>';
			echo '<td align="left">';
				echo '<div id="bl_plan_i">';
					if($row["nolimit"]>0) {
						echo '<b>������������</b>';
					}else{
						echo '<input name="plan" id="plan" maxlength="7" value="'.$row["plan"].'" class="ok12" style="text-align:right;" type="text" autocomplete="off" onChange="PlanChange();" onKeyUp="PlanChange();" onkeydown="this.style.background=\'#FFFFFF\';">';
					}
				echo '</div>';
				echo '<div id="bl_plan_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">����� ���������</td>';
			echo '<td>';
				echo '<div id="bl_timer_i"><input '.($row["nolimit"]>0 ? 'disabled="disabled"' : false).' type="text" name="timer" id="timer" maxlength="3" value="'.$row["timer"].'" class="ok12" style="text-align:right;" onChange="PlanChange();" onKeyUp="PlanChange();" onkeydown="this.style.background=\'#FFFFFF\';">&nbsp;&nbsp;&nbsp;(�� '.$mails_timer_ot.' �� '.$mails_timer_do.' ���.)</div>';
				echo '<div id="bl_timer_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';

	echo '<span id="adv-title1" class="adv-title-'.(($row["up_list"]>0 | $row["revisit"]>0 | $row["color"]>0 | $row["active"]>0 | $row["gotosite"]>0 | $row["unic_ip"]>0 | $row["new_users"]>0 | $row["no_ref"]>0 | $row["sex_adv"]>0 | $row["to_ref"]>0) ? "open" : "close").'" onclick="ShowHideBlock(1);">�������������� ���������</span>';
	echo '<div id="adv-block1" style="'.(($row["up_list"]>0 | $row["revisit"]>0 | $row["color"]>0 | $row["active"]>0 | $row["gotosite"]>0 | $row["unic_ip"]>0 | $row["new_users"]>0 | $row["no_ref"]>0 | $row["sex_adv"]>0 | $row["to_ref"]>0) ? "display:;" : "display:none;").'">';
	echo '<table class="tables" style="margin:0; padding:0;">';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="left" width="240" height="25">���������� �������� ������</td>';
			echo '<td align="left">';
				echo '<div id="bl_revisit_i"><select name="revisit" id="revisit" onChange="PlanChange();" onClick="PlanChange();" '.($row["nolimit"]>0 ? 'disabled="disabled"' : false).'>';
					echo '<option value="0" '.($row["revisit"]==0 ? 'selected="selected"' : false).'>������������ ���� ������ 24 ����</option>';
					echo '<option value="1" '.($row["revisit"]==1 ? 'selected="selected"' : false).'>������������ ���� ������ 48 �����</option>';
					echo '<option value="2" '.($row["revisit"]==2 ? 'selected="selected"' : false).'>������������ ���� 1 ��� � �����</option>';
				echo '</select></div>';
				echo '<div id="bl_revisit_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">�������� ������</td>';
			echo '<td align="left">';
				echo '<div id="bl_color_i"><select name="color" id="color" onChange="PlanChange();" onClick="PlanChange();" '.($row["nolimit"]>0 ? 'disabled="disabled"' : false).'>';
					echo '<option value="0" '.($row["color"]==0 ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.($row["color"]==1 ? 'selected="selected"' : false).'>��</option>';
				echo '</select></div>';
				echo '<div id="bl_color_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">�������� ����</td>';
			echo '<td align="left">';
				echo '<div id="bl_active_i"><select name="active" id="active" onChange="PlanChange();" onClick="PlanChange();" '.($row["nolimit"]>0 ? 'disabled="disabled"' : false).'>';
					echo '<option value="0" '.($row["active"]==0 ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.($row["active"]==1 ? 'selected="selected"' : false).'>��</option>';
				echo '</select></div>';
				echo '<div id="bl_active_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">����������� ������� �� ����</td>';
			echo '<td align="left">';
				echo '<div id="bl_gotosite_i"><select name="gotosite" id="gotosite" onChange="PlanChange();" onClick="PlanChange();" '.($row["nolimit"]>0 ? 'disabled="disabled"' : false).'>';
					echo '<option value="0" '.($row["gotosite"]==0 ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.($row["gotosite"]==1 ? 'selected="selected"' : false).'>��</option>';
				echo '</select></div>';
				echo '<div id="bl_gotosite_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">���������� IP</td>';
			echo '<td align="left">';
				echo '<div id="bl_unic_ip_i"><select name="unic_ip" id="unic_ip" onChange="PlanChange();" onClick="PlanChange();" '.($row["nolimit"]>0 ? 'disabled="disabled"' : false).'>';
					echo '<option value="0" '.($row["unic_ip"]==0 ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.($row["unic_ip"]==1 ? 'selected="selected"' : false).'>��, 100% ����������</option>';
					echo '<option value="2" '.($row["unic_ip"]==2 ? 'selected="selected"' : false).'>��������� �� ����� �� 2 ����� (255.255.X.X)</option>';
				echo '</select></div>';
				echo '<div id="bl_unic_ip_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">���������� ������ ��������</td>';
			echo '<td align="left">';
				echo '<div id="bl_new_users_i"><select id="new_users" name="new_users" class="ok" onChange="PlanChange();" onClick="PlanChange();" '.($row["nolimit"]>0 ? 'disabled="disabled"' : false).'>';
					echo '<option value="0" '.($row["new_users"]==0 ? 'selected="selected"' : false).'>��� ������������ �������</option>';
					echo '<option value="1" '.($row["new_users"]==1 ? 'selected="selected"' : false).'>��, �� 7 ���� � ������� �����������</option>';
				echo '</select></div>';
				echo '<div id="bl_new_users_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">�� ������� ��������</td>';
			echo '<td align="left">';
				echo '<div id="bl_no_ref_i"><select id="no_ref" name="no_ref" onChange="PlanChange();" onClick="PlanChange();" '.($row["nolimit"]>0 ? 'disabled="disabled"' : false).'>';
					echo '<option value="0" '.($row["no_ref"]==0 ? 'selected="selected"' : false).'>��� ������������ �������</option>';
					echo '<option value="1" '.($row["no_ref"]==1 ? 'selected="selected"' : false).'>������������� ��� �������� �� '.$_SERVER["HTTP_HOST"].'</option>';
				echo '</select></div>';
				echo '<div id="bl_no_ref_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">�� �������� ��������</td>';
			echo '<td align="left">';
				echo '<div id="bl_sex_adv_i"><select id="sex_adv" name="sex_adv" class="ok" onChange="PlanChange();" onClick="PlanChange();" '.($row["nolimit"]>0 ? 'disabled="disabled"' : false).'>';
					echo '<option value="0" '.($row["sex_adv"]==0 ? 'selected="selected"' : false).'>��� ������������ �������</option>';
					echo '<option value="1" '.($row["sex_adv"]==1 ? 'selected="selected"' : false).'>������ �������</option>';
					echo '<option value="2" '.($row["sex_adv"]==2 ? 'selected="selected"' : false).'>������ �������</option>';
				echo '</select></div>';
				echo '<div id="bl_sex_adv_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">���������� ������ ���������</td>';
			echo '<td align="left">';
				echo '<div id="bl_to_ref_i"><select id="to_ref" name="to_ref" class="ok" onChange="PlanChange();" onClick="PlanChange();" '.($row["nolimit"]>0 ? 'disabled="disabled"' : false).'>';
					echo '<option value="0" '.($row["to_ref"]==0 ? 'selected="selected"' : false).'>��� ������������ �������</option>';
					echo '<option value="1" '.($row["to_ref"]==1 ? 'selected="selected"' : false).'>��������� 1-�� ������</option>';
					echo '<option value="2" '.($row["to_ref"]==2 ? 'selected="selected"' : false).'>��������� ���� �������</option>';
				echo '</select></div>';
				echo '<div id="bl_to_ref_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
	echo '</div>';

	echo '<span id="adv-title2" class="adv-title-'.(count($geo_targ)>0 ? "open" : "close").'" onclick="ShowHideBlock(2);">��������� �������������</span>';
	echo '<div id="adv-block2" style="'.(count($geo_targ)>0 ? "display:;" : "display:none;").'">';
	echo '<table class="tables" style="margin:0; padding:0;">';
	echo '<tbody>';
	echo '<tr>';
		echo '<td colspan="2" align="center" style="border-right:none;"><a onclick="SetChecked(\'paste\');" style="width:100%; color:#008B00; font-weight:bold; cursor:pointer;"><center>�������� ���</center></a></td>';
		echo '<td colspan="2" align="center" style="border-left:none;"><a onclick="SetChecked();" style="width:100%; color:#FF0000; font-weight:bold; cursor:pointer;"><center>����� ���</center></a></td>';
	echo '</tr>';
	include(DOC_ROOT."/advertise/func_geotarg_edit.php");
	echo '</tbody>';
	echo '</table>';
	echo '</div>';

	echo '<table class="tables" style="margin:0; padding:0;">';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="center"><input type="submit" value="���������" class="sub-blue160" /></td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';

	echo '</form>';
	?><script language="JavaScript"> descchange(); </script><?php

	}else{
		echo '<span class="msg-error">������! ������� � ID#'.$id.' �� �������.</span><br>';
	}
	exit();
}

if($count>$perpage) {echo "<br>"; universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");}

echo '<table>';
echo '<tr align="center">';
	echo '<th></th>';
	echo '<th>ID ����&nbsp;�</th>';
	echo '<th>WMID �����</th>';
	echo '<th>������ ������</th>';
	echo '<th>��������� URL</th>';
	echo '<th>����������</th>';
	echo '<th>���������</th>';
	echo '<th>���������</th>';
	echo '<th></th>';
echo '</tr>';

$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `status`>'0' ORDER BY `id` DESC LIMIT $start_pos,$perpage");
if(mysql_num_rows($sql)>0) {
	$revisit_to[0] = "������ 24 ����";
	$revisit_to[1] = "������ 48 �����";
	$revisit_to[2] = "1 ��� � �����";

	while ($row = mysql_fetch_assoc($sql)) {
		echo '<tr>';
		echo '<td align="center" width="30" class="noborder1">';
			echo '<div id="playpauseimg'.$row["id"].'">';
				if($row["status"]=="1") {
					echo '<span class="adv-pause" title="������������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'mails\');"></span>';
				}elseif($row["status"]=="2") {
					echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'mails\');"></span>';
				}elseif($row["status"]=="3") {
					echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="alert_nostart();"></span>';
				}
			echo '</div>';
		echo '</td>';

		echo '<td align="center">'.$row["id"].'<br>'.$row["merch_tran_id"].'</td>';
		echo '<td align="center">'.$row["wmid"].'<br>'.$row["username"].'</td>';
		echo '<td align="center">'.$system_pay[$row["method_pay"]].'</td>';

		echo '<td>';
			echo $row["title"];
			echo '<br>';
			echo '<a href="'.$row["url"].'" target="_blank" title="'.$row["url"].'">'.(strlen($row["url"])>40 ? limitatexto($row["url"],40)."...." : $row["url"]).'</a>';
		echo '</td>';

		echo '<td align="left">';
			echo '���� ������: <b>'.DATE("d.m.Y H:i", $row["date"]).'</b><br>';
			if($row["nolimit"]>0) {
				echo '��������: �� <b>'.DATE("d.m.Y H:i",$row["nolimit"]).'</b><br>';
			}else{
				echo '��������: <b>'.$row["plan"].'</b> ���������<br>';
			}
			echo '������: <b>'.$row["timer"].' ���.</b>';
		echo '</td>';

		echo '<td>';
			echo '<span style="float:left;">���������� � ������ ������:</span> <span style="float:right; font-weight:bold;">'.($row["up_list"]==1 ? '��' : '���').'</span><br>';
			echo '<span style="float:left;">���������� �������� ������:</span> <span style="float:right; font-weight:bold;">'.$revisit_to[$row["revisit"]].'</span><br>';
			echo '<span style="float:left;">�������� ������:</span> <span style="float:right; font-weight:bold;">'.($row["color"]==1 ? '��' : '���').'</span><br>';
			echo '<span style="float:left;">�������� ����:</span> <span style="float:right; font-weight:bold;">'.($row["active"]==1 ? '��' : '���').'</span><br>';
			echo '<span style="float:left;">������� �� ����:</span> <span style="float:right; font-weight:bold;">'.($row["active"]==1 ? '��' : '���').'</span><br>';
			echo '<span style="float:left;">���������� IP:</span> <span style="float:right; font-weight:bold;">'.($row["unic_ip"]==1 ? '��' : '���').'</span><br>';
			//echo '<span style="float:left;">���������� ��������:</span> <span style="float:right; font-weight:bold;">'.($row["new_users"]==1 ? '��' : '���').'</span><br>';
			//echo '<span style="float:left;">�� ������� ��������:</span> <span style="float:right; font-weight:bold;">'.($row["no_ref"]==1 ? '���' : '��� ��������').'</span><br>';
			//echo '<span style="float:left;">�� �������� ��������:</span> <span style="float:right; font-weight:bold;">'.($row["sex_adv"]!=0 ? '��' : '���').'</span>';
		echo '</td>';

		echo '<td>'.number_format($row["money"], 2, ".", " ").' ���.</td>';

		echo '<td align="center">';
			echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'">';
				echo '<input type="hidden" name="op" value="'.limpiarez($_GET["op"]).'">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="hidden" name="option" value="edit">';
				echo '<input type="hidden" name="page" value="'.$page.'">';
				echo '<input type="submit" value="��������" class="sub-green">';
			echo '</form>';
			echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'" onClick=\'if(!confirm("����������� �������� �������\nID �������: '.$row["id"].'")) return false;\'>';
				echo '<input type="hidden" name="op" value="'.limpiarez($_GET["op"]).'">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="hidden" name="option" value="dell">';
				echo '<input type="hidden" name="page" value="'.$page.'">';
				echo '<input type="submit" value="�������" class="sub-red">';
			echo '</form>';
		echo '</td>';
		echo '</tr>';
	}
}else{
	echo '<tr><td colspan="13"><div style="font-weight: bold; color:#FF0000; text-align:center;">������ �� �������!</div></td></tr>';
}
echo '</table>';

if($count>$perpage) {universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op"); echo "<br>";}
?>