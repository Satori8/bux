<?php
error_reporting(E_ALL);
require_once(ROOT_DIR."/bbcode/bbcode.lib.php");
if(!DEFINED("TESTS_AJAX")) {
	$message_text = "ERROR: Hacking attempt!";
	$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
	exit($js_result);
}elseif($type_ads!="tests") {
	$message_text = "ERROR: Hacking attempt!";
	$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
	exit($js_result);
}

mysql_query("UPDATE `tb_ads_tests` SET `status`='3', `date_edit`='".time()."' WHERE `status`>'0' AND `status`<'4' AND `balance`<`cena_advs`");
mysql_query("UPDATE `tb_ads_tests` SET `status`='1', `date_edit`='".time()."' WHERE `status`='3' AND `balance`>=`cena_advs`");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_hit' AND `howmany`='1'");
$tests_cena_hit = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_nacenka' AND `howmany`='1'");
$tests_nacenka = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_min_pay' AND `howmany`='1'");
$tests_min_pay = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_quest' AND `howmany`='1'");
$tests_cena_quest = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_color' AND `howmany`='1'");
$tests_cena_color = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_comis_del' AND `howmany`='1'");
$tests_comis_del = number_format(mysql_result($sql,0,0), 0, ".", "");

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


if($option == "GoLock") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$status = $row["status"];

		if($status==4) {
			$message_text = '';
			$message_text.= '<div align="left" style="padding-left:40px;"><span style="color:#4F4F4F;">������������:</span> <b>'.$row["user_lock"].'</b></div>';
			$message_text.= '<div align="left" style="padding-left:40px;"><span style="color:#4F4F4F;">������� ����������:</span> <b>'.$row["msg_lock"].'</b></div>';

			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}else{
			$message_text = '';
			$message_text.= '<div align="center" style="float:left; padding:9px 5px; font-weight:bold;">������� ������� ����������:</div>';
			$message_text.= '<div id="newform" align="center" style="float:left; width:calc(100% - 300px); padding:5px 5px 3px 0px;"><input class="ok" type="text" id="msg_lock" value="" maxlength="255" autocomplete="off" onKeyDown="$(this).attr(\'class\', \'ok\');" /></div>';
			$message_text.= '<div align="center" style="float:left; padding-left:5px; padding-top:6px;"><span onClick="Lock(\''.$row["id"].'\', \'tests\', \'Lock\');" class="sub-red" style="float:none;" title="�������������">�������������</span></div>';

			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}
	}else{
		$message_text = "ERROR: ���� �� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "Lock") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$status = $row["status"];
		$msg_lock = (isset($_POST["msg_lock"])) ? limitatexto(limpiarez($_POST["msg_lock"]), 255) : false;

		if($status==4) {
			$message_text = "����, ��� ������������!";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);

		}elseif($msg_lock==false) {
			$message_text = '������� ������� ����������!';
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}else{

			mysql_query("UPDATE `tb_ads_tests` SET `status`='4', `date_edit`='".time()."', `user_lock`='$username', `msg_lock`='$msg_lock' WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

			$message_text = "���� ������� ������������!";

			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}
	}else{
		$message_text = "ERROR: ���� �� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "ViewClaims") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$status = $row["status"];

		$message_text = "";
		$message_text.= '<div class="box-modal" id="ModalClaimsTest" style="text-align:justify; width:900px;">';
			$message_text.= '<div class="box-modal-title">�������� ����� �� ���� �'.$id.'</div>';
			$message_text.= '<div class="box-modal-close modalpopup-close"></div>';
			$message_text.= '<div class="box-modal-content" style="margin:1px; padding:5px 3px; font-size:11px;">';

				$message_text.= '<table class="tables" style="margin:0 auto; padding:0;">';
				$message_text.= '<thead><tr align="center">';
					$message_text.= '<th width="120">�����</th><th width="100">����</th><th width="100">IP</th><th>����� ������</th><th width="18"></th>';
				$message_text.= '</thead></tr>';
				$message_text.= '</table>';

				$message_text.= '<div id="table-content" style="overflow:auto;">';
					$message_text.= '<table class="tables" style="margin:0 auto; padding:0;">';
					$sql = mysql_query("SELECT * FROM `tb_ads_claims` WHERE `ident`='$id' AND `type`='tests' ORDER BY `id` DESC");
					if(mysql_num_rows($sql)>0) {
						while ($row = mysql_fetch_assoc($sql)) {
							$message_text.= '<tr id="claims-'.$row["id"].'" align="center">';
								$message_text.= '<td width="120">'.$row["username"].'</td>';
								$message_text.= '<td width="100">'.DATE("d.m.Y�. H:i", $row["time"]).'</td>';
								$message_text.= '<td width="100">'.$row["ip"].'</td>';
								$message_text.= '<td>'.$row["claims"].'</td>';
								$message_text.= '<td width="20"><span class="clear-claims" title="������� ������" onClick="DelClaims('.$row["id"].', \'tests\', \'DelClaims\');"></span></td>';
							$message_text.= '</tr>';
						}
					}else{
						$message_text.= '<tr align="center"><td colspan="4"><b>������ �� ����������</b></td></tr>';
					}
					$message_text.= '</table>';
				$message_text.= '</div>';

			$message_text.= '</div>';
		$message_text.= '</div>';

		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}else{
		$message_text = "";
		$message_text.= '<div class="box-modal" id="ModalClaimsTest" style="text-align:justify; width:900px;">';
			$message_text.= '<div class="box-modal-title">�������� ����� �� ���� �'.$id.'</div>';
			$message_text.= '<div class="box-modal-close modalpopup-close"></div>';
			$message_text.= '<div class="box-modal-content" style="margin:1px; padding:5px 3px; font-size:11px;">';
				$message_text.= '<div id="table-content" style="overflow:auto;">';
					$message_text.= '<span class="msg-error">���� �� ������!</span>';
				$message_text.= '</div>';
			$message_text.= '</div>';
		$message_text.= '</div>';

		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "DelClaims") {
	$sql = mysql_query("SELECT `ident` FROM `tb_ads_claims` WHERE `id`='$id' AND `type`='tests'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$ident = $row["ident"];

		mysql_query("DELETE FROM `tb_ads_claims` WHERE `id`='$id' AND `type`='tests'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
		mysql_query("UPDATE `tb_ads_tests` SET `claims`=`claims`-'1' WHERE `id`='$ident'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

		$count_claims = mysql_query("SELECT `claims` FROM `tb_ads_tests` WHERE `id`='$ident'");
		$count_claims = number_format(mysql_result($count_claims,0,0), 0, ".", "");

		$message_text = "";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text), "ident" => "$ident", "count_claims" => "$count_claims")) : $message_text;
		exit($js_result);
	}else{
		$message_text = "ERROR: ������ �� �������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "DelAllClaims") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		if($row["claims"] > 0) {
			mysql_query("UPDATE `tb_ads_tests` SET `claims`='0' WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
			mysql_query("DELETE FROM `tb_ads_claims` WHERE `ident`='$id' AND `type`='tests'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

			$message_text = "";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}else{
			$message_text = "ERROR: ������ �� ����������!";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}
	}else{
		$message_text = "ERROR: ���� �� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "Add") {
	$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 55) : false;
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]), 1000) : false;
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	if (get_magic_quotes_gpc()) { $description = stripslashes($description); }
	$revisit = (isset($_POST["revisit"]) && (intval($_POST["revisit"])>=0 && intval($_POST["revisit"])<=4)) ? intval(limpiarez($_POST["revisit"])) : "0";
	$color = (isset($_POST["color"]) && (intval($_POST["color"])==0 | intval($_POST["color"])==1)) ? intval(limpiarez($_POST["color"])) : "0";
	$unic_ip_user = (isset($_POST["unic_ip_user"]) && (intval($_POST["unic_ip_user"])>=0 && intval($_POST["unic_ip_user"])<=2)) ? intval($_POST["unic_ip_user"]) : "0";
	$date_reg_user = (isset($_POST["date_reg_user"]) && (intval($_POST["date_reg_user"])>=0 && intval($_POST["date_reg_user"])<=4)) ? intval($_POST["date_reg_user"]) : "0";
	$sex_user = ( isset($_POST["sex_user"]) && preg_match("|^[\d]{1,11}$|", limpiarez($_POST["sex_user"])) && intval(limpiarez($_POST["sex_user"]))>=0 && intval(limpiarez($_POST["sex_user"]))<=2 ) ? abs(intval(limpiarez($_POST["sex_user"]))) : 0;
	$country = (isset($_POST["country"]) && count($_POST["country"])>0) ? (array_map('mysql_real_escape_string', $_POST["country"])) : false;
	$money_add = ( isset($_POST["money_add"]) && preg_match( "|^[\d]*[\.,]?[\d]{0,2}$|", abs(str_replace(",", ".", limpiarez($_POST["money_add"]))) ) ) ? abs(str_replace(",", ".", limpiarez($_POST["money_add"]))) : false;
	$method_pay = 0;
	$black_url = getHost($url);

	$revisit_tab[0] = "�������� ���� ������ 24 ����";
	$revisit_tab[1] = "�������� ���� ������ 3 ���";
	$revisit_tab[2] = "�������� ���� ������ ������";
	$revisit_tab[3] = "�������� ���� ������ 2 ������";
	$revisit_tab[4] = "�������� ���� ������ �����";

	$color_tab[0] = "���";
	$color_tab[1] = "��";

	$unic_ip_user_tab[0] = "���";
	$unic_ip_user_tab[1] = "��, 100% ����������";
	$unic_ip_user_tab[2] = "��������� �� ����� �� 2 ����� (255.255.X.X)";

	$date_reg_user_tab[0] = "��� ������������ �������";
	$date_reg_user_tab[1] = "�� 7 ���� � ������� �����������";
	$date_reg_user_tab[2] = "�� 7 ���� � ������� �����������";
	$date_reg_user_tab[3] = "�� 30 ���� � ������� �����������";
	$date_reg_user_tab[4] = "�� 90 ���� � ������� �����������";

	$sex_user_tab[0] = "��� ������������ �������";
	$sex_user_tab[1] = "������ �������";
	$sex_user_tab[2] = "������ �������";

	for($i=1; $i<=5; $i++) {
		$quest[$i] = (isset($_POST["quest$i"])) ? limitatexto(limpiarez($_POST["quest$i"]), 300) : false;
	}

	for($i=1; $i<=5; $i++) {
		for($y=1; $y<=3; $y++) {
			$answ[$i][$y] = (isset($_POST["answ$i$y"])) ? limitatexto(limpiarez($_POST["answ$i$y"]), 30) : false;
		}
	}

	if(is_array($country)) {
		foreach($country as $key => $val) {
			if(array_search($val, $geo_cod_arr)) {
				$id_country = array_search($val, $geo_cod_arr);
				$country_arr[] = $val;
				$country_arr_ru[] = $geo_name_arr_ru[$val];
			}
		}
	}
	$country = isset($country_arr) ? trim(strtoupper(implode(", ", $country_arr))) : false;
	$country_to = isset($country_arr_ru) ? trim(strtoupper(implode(', ', $country_arr_ru))) : false;
	if($country_to!=false) {$country_to="$country_to";}else{$country_to="���";}

	if($quest[4]=="" | $answ[4][1]=="" | $answ[4][2]=="" | $answ[4][3]=="") {
		$quest[4] = ""; $answ[4][1] = ""; $answ[4][2] = ""; $answ[4][3] = "";
	}
	if($quest[5]=="" | $answ[5][1]=="" | $answ[5][2]=="" | $answ[5][3]=="") {
		$quest[5] = ""; $answ[5][1] = ""; $answ[5][2] = ""; $answ[5][3] = "";
	}
	if( ($quest[5]!="" && $answ[5][1]!="" && $answ[5][2]!="" && $answ[5][3]!="") && ($quest[4]=="" | $answ[4][1]=="" | $answ[4][2]=="" | $answ[4][3]=="") ) {
		$quest[4] = $quest[5]; $answ[4][1] = $answ[5][1]; $answ[4][2] = $answ[5][2]; $answ[4][3] = $answ[5][3];
		$quest[5] = ""; $answ[5][1] = ""; $answ[5][2] = ""; $answ[5][3] = "";
	}

	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");

	if($title=="") {
		$message_text = "ERROR: �� �� ������� ��������� �����!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);

	}elseif($description=="") {
		$message_text = "ERROR: �� �� ������� ���������� � ���������� �����!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);

	}elseif(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
		$row_bl = mysql_fetch_array($sql_bl);
		$message_text = "ERROR: ���� ".$row_bl["domen"]." ������������ � ������� � ������ ������ ������� ".strtoupper($_SERVER["HTTP_HOST"])." �������: ".$row_bl["cause"]."";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);

	}elseif($url==false | $url=="http://" | $url=="https://") {
		$message_text = "ERROR: �� �� ������� URL-����� �����!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);

	}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
		$message_text = "ERROR: �� ������� ������� URL-����� �����!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);

	}elseif($quest[1]=="") {
		$message_text = "ERROR: �� �� ������� ������ ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);

	}elseif($answ[1][1]=="" | $answ[1][2]=="" | $answ[1][3]=="") {
		$message_text = "ERROR: �� �� ������� �������� ������ �� ������ ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);

	}elseif($quest[2]=="") {
		$message_text = "ERROR: �� �� ������� ������ ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);

	}elseif($answ[2][1]=="" | $answ[2][2]=="" | $answ[2][3]=="") {
		$message_text = "ERROR: �� �� ������� �������� ������ �� ������ ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);

	}elseif($quest[3]=="") {
		$message_text = "ERROR: �� �� ������� ������ ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);

	}elseif($answ[3][1]=="" | $answ[3][2]=="" | $answ[3][3]=="") {
		$message_text = "ERROR: �� �� ������� �������� ������ �� ������ ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);

	}elseif($money_add=="") {
		$message_text = "ERROR: C���� ���������� ������� ��������� �������� ������� �� �����!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);

	}elseif($money_add<$tests_min_pay) {
		$message_text = "ERROR: ����������� ����� ���������� - ".number_format($tests_min_pay,2,".","")." ���.";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);

	}else{
		$summa_add = 0;
		if($quest[4]!="") $summa_add+= $tests_cena_quest;
		if($quest[5]!="") $summa_add+= $tests_cena_quest;

		$cena_user = ($tests_cena_hit + $summa_add) / (($tests_nacenka+100)/100);
		$cena_advs = ($tests_cena_hit + $summa_add + $tests_cena_revisit[$revisit] + $tests_cena_color * $color + $tests_cena_unic_ip[$unic_ip_user]);

		$cena_user = number_format($cena_user, 4, ".", "");
		$cena_advs = number_format($cena_advs, 4, ".", "");
		$money_add = number_format($money_add, 2, ".", "");

		$count_tests = floor(bcdiv($money_add, $cena_advs));

		if($quest[4]=="") unset($quest[4], $answ[4]);
		if($quest[5]=="") unset($quest[5], $answ[5]);

		$questions = serialize($quest);
		$answers = serialize($answ);

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'");
		$merch_tran_id = mysql_result($sql_tranid,0,0);

		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
		mysql_query("DELETE FROM `tb_ads_tests` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_tests` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_check)>0) {
			mysql_query("UPDATE `tb_ads_tests` SET `merch_tran_id`='$merch_tran_id',`method_pay`='$method_pay',`wmid`='$wmid_user',`username`='$username',`date`='".time()."',`date_edit`='".time()."',`title`='$title',`description`='$description',`url`='$url',`questions`='$questions',`answers`='$answers',`geo_targ`='$country',`revisit`='$revisit',`color`='$color',`date_reg_user`='$date_reg_user',`unic_ip_user`='$unic_ip_user',`sex_user`='$sex_user',`cena_user`='$cena_user',`cena_advs`='$cena_advs',`money`='$money_add',`balance`='0',`ip`='$laip' WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
		}else{
			mysql_query("INSERT INTO `tb_ads_tests`(`status`,`session_ident`,`merch_tran_id`,`method_pay`,`wmid`,`username`,`date`,`date_edit`,`title`,`description`,`url`,`questions`,`answers`,`geo_targ`,`revisit`,`color`,`date_reg_user`,`unic_ip_user`,`sex_user`,`cena_user`,`cena_advs`,`money`,`balance`,`ip`) 
			VALUES('0','".session_id()."','$merch_tran_id','$method_pay','$wmid_user','$username','".time()."','".time()."','$title','$description','$url','$questions','$answers','$country','$revisit','$color','$date_reg_user','$unic_ip_user','$sex_user','$cena_user','$cena_advs','$money_add','0','$laip')") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
		}

        	$sql_id = mysql_query("SELECT `id`,`description`,`questions`,`answers`,`geo_targ` FROM `tb_ads_tests` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_id)>0) {
			$row_id = mysql_fetch_array($sql_id);
		        $id_zakaz = $row_id["id"];
		        $description_to = $row_id["description"];
			$questions_to = $row_id["questions"];
			$answers_to = $row_id["answers"];
			$geo_targ = (isset($row_id["geo_targ"]) && trim($row_id["geo_targ"])!=false) ? explode(", ", $row_id["geo_targ"]) : array();
		}else{
			$message_text = "ERROR: NO ID!";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}

		$description_to = new bbcode($description_to);
		$description_to = $description_to->get_html();
		$description_to = str_replace("&amp;", "&", $description_to);

		$message_text = "";
		$message_text.= '<span class="msg-ok" style="margin-bottom:0px;">��������������� ��������!</span>';
		$message_text.= '<table class="tables" style="margin:0; padding:0;">';
		$message_text.= '<tr><td align="left" width="190">���� � (ID)</td><td align="left">'.number_format($merch_tran_id, 0,".", "").' ('.$id_zakaz.')</td></tr>';
		$message_text.= '<tr><td align="left">��������� �����</td><td align="left">'.$title.'</td></tr>';
		$message_text.= '<tr><td align="left">���������� ��� ������������</td><td align="left">'.$description_to.'</td></tr>';
		$message_text.= '<tr><td align="left">URL �����</td><td align="left"><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';

		for($i=1; $i<=count($quest); $i++){
			$message_text.= '<tr><td align="left">������ �'.$i.'</td><td align="left">'.$quest[$i].'</td></tr>';
			$message_text.= '<tr>';
				$message_text.= '<td align="left">�������� ������</td>';
				$message_text.= '<td align="left">';
					for($y=1; $y<=3; $y++){
						$message_text.= '<span style="color: '.($y==1 ? "#009125;" : "#FF0000").'">'.$answ[$i][$y].'</span>'.($y!=3 ? "<br>" : "").'';
					}
				$message_text.= '</td>';
			$message_text.= '</tr>';
		}

		$message_text.= '<tr><td align="left">���������� ������������</td><td align="left">'.$revisit_tab[$revisit].'</td></tr>';
		$message_text.= '<tr><td align="left">�������� ����</td><td align="left">'.$color_tab[$color].'</td></tr>';
		$message_text.= '<tr><td align="left">���������� IP</td><td align="left">'.$unic_ip_user_tab[$unic_ip_user].'</td></tr>';
		$message_text.= '<tr><td align="left">�� ���� �����������</td><td align="left">'.$date_reg_user_tab[$date_reg_user].'</td></tr>';
		$message_text.= '<tr><td align="left">�� �������� ��������</td><td align="left">'.$sex_user_tab[$sex_user].'</td></tr>';

		if(count($geo_targ)>0) {
			$message_text.= '<tr><td align="left">������������</td><td align="left">';
			for($i=0; $i<count($geo_targ); $i++){
				$message_text.= '&nbsp;<img src="//'.$_SERVER["HTTP_HOST"].'/img/flags/'.strtolower($geo_targ[$i]).'.gif" alt="" align="absmiddle" style="margin:0; padding:0; padding-left:1px;" />';
			}
			$message_text.= '</td></tr>';
		}else{
			$message_text.= '<tr><td align="left">������������</td><td align="left"><b>��� ������</b></td></tr>';
		}

		$message_text.= '<tr><td align="left">���������� ����������</td><td align="left">'.$count_tests.'</td></tr>';

		$message_text.= '<tr>';
			$message_text.= '<td align="left"><span onClick="PayAds(\''.$id_zakaz.'\');" class="sub-blue160" style="float:left;">���������� ����</span></td>';
			$message_text.= '<td align="center" style="border-left:none;">';
				$message_text.= '<span onClick="DeleteAds(\''.$id_zakaz.'\');" class="sub-red" style="float:right;">�������</span>';
				$message_text.= '<span onClick="ChangeAds();" class="sub-green" style="float:right;">��������</span>';
			$message_text.= '</td>';
		$message_text.= '</tr>';

		$message_text.= '</table>';

		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "Delete") {
	$sql_check = mysql_query("SELECT `id` FROM `tb_ads_tests` WHERE `id`='$id' AND `status`='0'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql_check)>0) {
		mysql_query("DELETE FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

		$message_text = "OK";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}else{
		$message_text = "ERROR: ���� �� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "Start") {
	$sql_check = mysql_query("SELECT `id` FROM `tb_ads_tests` WHERE `id`='$id' AND `status`='0'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql_check)>0) {
		mysql_query("UPDATE `tb_ads_tests` SET `status`='1',`date_edit`='".time()."',`balance`=`money` WHERE `id`='$id' AND `status`='0'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

		$message_text = "���� ������� ��������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}else{
		$message_text = "ERROR: ���� �� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "PlayPause") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];

		if($status == 0) {
			$status_text = '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="PlayPause('.$row["id"].', \'tests\', \'PlayPause\');"></span>';
			$message_text = "��� �������, ���������� ��������� ��������� ������!";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "status" => iconv("CP1251", "UTF-8", $status_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);

		}elseif($status == 1) {
			mysql_query("UPDATE `tb_ads_tests` SET `status`='2', `date_edit`='".time()."' WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

			$status_text = '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="PlayPause('.$row["id"].', \'tests\', \'PlayPause\');"></span>';
			$message_text = "";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "status" => iconv("CP1251", "UTF-8", $status_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);

		}elseif($status == 2) {
			mysql_query("UPDATE `tb_ads_tests` SET `status`='1', `date_edit`='".time()."' WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

			$status_text = '<span class="adv-pause" title="������������� ����� ��������� ��������" onClick="PlayPause('.$row["id"].', \'tests\', \'PlayPause\');"></span>';
			$message_text = "";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "status" => iconv("CP1251", "UTF-8", $status_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);

		}elseif($status == 3) {
			if($row["balance"]>=$row["cena_advs"]) {
				mysql_query("UPDATE `tb_ads_tests` SET `status`='1', `date_edit`='".time()."' WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

				$status_text = '<span class="adv-pause" title="������������� ����� ��������� ��������" onClick="PlayPause('.$row["id"].', \'tests\', \'PlayPause\');"></span>';
				$message_text = "";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "status" => iconv("CP1251", "UTF-8", $status_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
			}else{
				$status_text = '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="PlayPause('.$row["id"].', \'tests\', \'PlayPause\');"></span>';
				$message_text = "��� �������, ���������� ��������� ��������� ������!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "status" => iconv("CP1251", "UTF-8", $status_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
			}

		}elseif($status == 4) {
			mysql_query("UPDATE `tb_ads_tests` SET `status`='2', `date_edit`='".time()."', `user_lock`='', `msg_lock`='' WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

			$status_text = '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="PlayPause('.$row["id"].', \'tests\', \'PlayPause\');"></span>';
			$message_text = "";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "status" => iconv("CP1251", "UTF-8", $status_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);

		}else{
			$status_text = "";
			$message_text = "ERROR: ������ �� ���������!";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "status" => iconv("CP1251", "UTF-8", $status_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}
	}else{
		$status_text = "";
		$message_text = "ERROR: ���� �� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "status" => iconv("CP1251", "UTF-8", $status_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "GetInfo") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$description = new bbcode($row["description"]);
		$description = $description->get_html();
		$description = str_replace("&amp;", "&", $description);
		$url = $row["url"];
		$questions = unserialize($row["questions"]);
		$answers = unserialize($row["answers"]);
		$geo_targ = (isset($row["geo_targ"]) && trim($row["geo_targ"])!=false) ? explode(", ", $row["geo_targ"]) : array();

		$revisit_tab[0] = "�������� ���� ������ 24 ����";
		$revisit_tab[1] = "�������� ���� ������ 3 ���";
		$revisit_tab[2] = "�������� ���� ������ ������";
		$revisit_tab[3] = "�������� ���� ������ 2 ������";
		$revisit_tab[4] = "�������� ���� ������ �����";
		$color_tab[0] = "���";
		$color_tab[1] = "��";
		$unic_ip_user_tab[0] = "���";
		$unic_ip_user_tab[1] = "��, 100% ����������";
		$unic_ip_user_tab[2] = "��������� �� ����� �� 2 ����� (255.255.X.X)";
		$date_reg_user_tab[0] = "��� ������������ �������";
		$date_reg_user_tab[1] = "�� 7 ���� � ������� �����������";
		$date_reg_user_tab[2] = "�� 7 ���� � ������� �����������";
		$date_reg_user_tab[3] = "�� 30 ���� � ������� �����������";
		$date_reg_user_tab[4] = "�� 90 ���� � ������� �����������";
		$sex_user_tab[0] = "��� ������������ �������";
		$sex_user_tab[1] = "������ �������";
		$sex_user_tab[2] = "������ �������";

		$message_text = '<div align="justify" style="margin:5px; color: #333333; font-size:12px;">';
			$message_text.= '<h3 class="sp" style="margin-top:0px;">���������� ��� ������������:</h3>';
			$message_text.= "$description<br><br>";
			$message_text.= '<h3 class="sp" style="margin-top:0px;">������ ��� ��������:</h3>';
			$message_text.= "$url<br><br>";

			for($i=1; $i<=count($questions); $i++){
				$message_text.= '<h3 class="sp" style="margin-top:0px;">������ �'.$i.':</h3>';
				$message_text.= "$questions[$i]<br>";
				$message_text.= '<div style="margin-left:20px;"><u>������:</u><br>';
				for($y=1; $y<=3; $y++){
					$message_text.= '<span style="color: '.($y==1 ? "#009125;" : "#FF0000").'">'.$answers[$i][$y].'</span>'.($y!=3 ? "<br>" : "").'';
				}
				$message_text.= '</div><br>';
			}

			$message_text.= '<h3 class="sp" style="margin-top:0px;">���������:</h3>';
			$message_text.= '���������� ������������: <b>'.$revisit_tab[$row["revisit"]].'</b><br>';
			$message_text.= '�������� ����: <b>'.$color_tab[$row["color"]].'</b><br>';
			$message_text.= '���������� IP: <b>'.$unic_ip_user_tab[$row["unic_ip_user"]].'</b><br>';
			$message_text.= '�� ���� �����������: <b>'.$date_reg_user_tab[$row["date_reg_user"]].'</b><br>';
			$message_text.= '�� �������� ��������: <b>'.$sex_user_tab[$row["sex_user"]].'</b><br>';
			$message_text.= '���-���������:&nbsp;';

			if(count($geo_targ)>0) {
				for($i=0; $i<count($geo_targ); $i++){
					$message_text.= '&nbsp;<img src="//'.$_SERVER["HTTP_HOST"].'/img/flags/'.strtolower($geo_targ[$i]).'.gif" alt="" align="absmiddle" style="margin:0; padding:0; padding-left:1px;" />';
				}
			}else{
				$message_text.= '<b>��� ������</b>';
			}
		$message_text.= '</div>';

		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}else{
		$message_text = "ERROR: ���� �� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "ClearStat") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];
		$goods_out = $row["goods_out"];
		$bads_out = $row["bads_out"];

		if($status == 0 | ($goods_out == 0 && $bads_out == 0)) {
			$message_text = "ERROR: ������� ���� �������� ��� ����� 0";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}else{
			mysql_query("UPDATE `tb_ads_tests` SET `goods_out`='0',`bads_out`='0' WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

			$message_text = "OK";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}
	}else{
		$message_text = "ERROR: ���� �� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "GoEdit") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];

		if($status==1) {
			$message_text = "����� ��������������� ���������� ������������� ��������� ��������";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}else{
			$message_text = "OK";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}
	}else{
		$message_text = "ERROR: ���� �� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "EditAds") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];

		if($status==1) {
			$message_text = "����� ��������������� ���������� ������������� ��������� ��������";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}else{
			$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 55) : false;
			$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]), 1000) : false;
			$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
			if (get_magic_quotes_gpc()) { $description = stripslashes($description); }
			$revisit = (isset($_POST["revisit"]) && (intval($_POST["revisit"])>=0 && intval($_POST["revisit"])<=4)) ? intval(limpiarez($_POST["revisit"])) : "0";
			$color = (isset($_POST["color"]) && (intval($_POST["color"])==0 | intval($_POST["color"])==1)) ? intval(limpiarez($_POST["color"])) : "0";
			$unic_ip_user = (isset($_POST["unic_ip_user"]) && (intval($_POST["unic_ip_user"])>=0 && intval($_POST["unic_ip_user"])<=2)) ? intval($_POST["unic_ip_user"]) : "0";
			$date_reg_user = (isset($_POST["date_reg_user"]) && (intval($_POST["date_reg_user"])>=0 && intval($_POST["date_reg_user"])<=4)) ? intval($_POST["date_reg_user"]) : "0";
			$sex_user = ( isset($_POST["sex_user"]) && preg_match("|^[\d]{1,11}$|", limpiarez($_POST["sex_user"])) && intval(limpiarez($_POST["sex_user"]))>=0 && intval(limpiarez($_POST["sex_user"]))<=2 ) ? abs(intval(limpiarez($_POST["sex_user"]))) : 0;
			$country = (isset($_POST["country"]) && count($_POST["country"])>0) ? (array_map('mysql_real_escape_string', $_POST["country"])) : false;
			$black_url = getHost($url);

			for($i=1; $i<=5; $i++) {
				$quest[$i] = (isset($_POST["quest$i"])) ? limitatexto(limpiarez($_POST["quest$i"]), 300) : false;

				for($y=1; $y<=3; $y++) {
					$answ[$i][$y] = (isset($_POST["answ$i$y"])) ? limitatexto(limpiarez($_POST["answ$i$y"]), 30) : false;
				}
			}

			if(is_array($country)) {
				foreach($country as $key => $val) {
					if(array_search($val, $geo_cod_arr)) {
						$id_country = array_search($val, $geo_cod_arr);
						$country_arr[] = $val;
					}
				}
			}
			$country = isset($country_arr) ? trim(strtoupper(implode(", ", $country_arr))) : false;

			if($quest[4]=="" | $answ[4][1]=="" | $answ[4][2]=="" | $answ[4][3]=="") {
				$quest[4] = ""; $answ[4][1] = ""; $answ[4][2] = ""; $answ[4][3] = "";
			}
			if($quest[5]=="" | $answ[5][1]=="" | $answ[5][2]=="" | $answ[5][3]=="") {
				$quest[5] = ""; $answ[5][1] = ""; $answ[5][2] = ""; $answ[5][3] = "";
			}
			if( ($quest[5]!="" && $answ[5][1]!="" && $answ[5][2]!="" && $answ[5][3]!="") && ($quest[4]=="" | $answ[4][1]=="" | $answ[4][2]=="" | $answ[4][3]=="") ) {
				$quest[4] = $quest[5]; $answ[4][1] = $answ[5][1]; $answ[4][2] = $answ[5][2]; $answ[4][3] = $answ[5][3];
				$quest[5] = ""; $answ[5][1] = ""; $answ[5][2] = ""; $answ[5][3] = "";
			}

			$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");

			if($title=="") {
				$message_text = "ERROR: �� �� ������� ��������� �����!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($description=="") {
				$message_text = "ERROR: �� �� ������� ���������� � ���������� �����!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
				$row_bl = mysql_fetch_array($sql_bl);
				$message_text = "ERROR: ���� ".$row_bl["domen"]." ������������ � ������� � ������ ������ ������� ".strtoupper($_SERVER["HTTP_HOST"])." �������: ".$row_bl["cause"]."";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($url==false | $url=="http://" | $url=="https://") {
				$message_text = "ERROR: �� �� ������� URL-����� �����!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
				$message_text = "ERROR: �� ������� ������� URL-����� �����!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($quest[1]=="") {
				$message_text = "ERROR: �� �� ������� ������ ������!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($answ[1][1]=="" | $answ[1][2]=="" | $answ[1][3]=="") {
				$message_text = "ERROR: �� �� ������� �������� ������ �� ������ ������!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($quest[2]=="") {
				$message_text = "ERROR: �� �� ������� ������ ������!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($answ[2][1]=="" | $answ[2][2]=="" | $answ[2][3]=="") {
				$message_text = "ERROR: �� �� ������� �������� ������ �� ������ ������!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($quest[3]=="") {
				$message_text = "ERROR: �� �� ������� ������ ������!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($answ[3][1]=="" | $answ[3][2]=="" | $answ[3][3]=="") {
				$message_text = "ERROR: �� �� ������� �������� ������ �� ������ ������!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}else{
				$summa_add = 0;
				if($quest[4]!="") $summa_add+= $tests_cena_quest;
				if($quest[5]!="") $summa_add+= $tests_cena_quest;

				$cena_user = ($tests_cena_hit + $summa_add) / (($tests_nacenka+100)/100);
				$cena_advs = ($tests_cena_hit + $summa_add + $tests_cena_revisit[$revisit] + $tests_cena_color * $color + $tests_cena_unic_ip[$unic_ip_user]);

				$cena_user = number_format($cena_user, 4, ".", "");
				$cena_advs = number_format($cena_advs, 4, ".", "");

				if($quest[4]=="") unset($quest[4], $answ[4]);
				if($quest[5]=="") unset($quest[5], $answ[5]);

				$questions = serialize($quest);
				$answers = serialize($answ);

				mysql_query("DELETE FROM `tb_ads_tests` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'");

				mysql_query("UPDATE `tb_ads_tests` SET 
					`date_edit`='".time()."',`title`='$title',`description`='$description',`url`='$url',`questions`='$questions',`answers`='$answers',
					`geo_targ`='$country',`revisit`='$revisit',`color`='$color',`date_reg_user`='$date_reg_user',
					`unic_ip_user`='$unic_ip_user',`sex_user`='$sex_user',`cena_user`='$cena_user',`cena_advs`='$cena_advs',`ip`='$laip' 
				WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

				if($revisit != $row["revisit"]) {
					$sql_v = mysql_query("SELECT `id` FROM `tb_ads_tests_visits` WHERE `ident`='$id' AND `time_next`>='".time()."'");
					if(mysql_num_rows($sql_v)>0) {
						while($row_v = mysql_fetch_assoc($sql_v)) {
							if($revisit==0) {
								$time_next = (1*24*60*60);
							}elseif($revisit==1) {
								$time_next = (3*24*60*60);
							}elseif($revisit==2) {
								$time_next = (7*24*60*60);
							}elseif($revisit==3) {
								$time_next = (14*24*60*60);
							}elseif($revisit==4) {
								$time_next = (30*24*60*60);
							}else{
								$time_next = (1*24*60*60);
							}

							mysql_query("UPDATE `tb_ads_tests_visits` SET `time_next`=`time_end`+'$time_next' WHERE `id`='".$row_v["id"]."'");
						}
					}
				}

				$message_text = "OK";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
			}
		}
	}else{
		$message_text = "ERROR: ���� �� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "FormAddMoney") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$message_text = '������� �����, ������� �� ������ ������ � ������ ��������� ��������<br>(������� '.count_text($tests_min_pay, "������", "�����", "�����", "").')';
		$message_text.= '<input type="text" maxlength="10" id="money_add" value="100.00" class="payadv" onChange="AddMoney();" onKeyUp="AddMoney();" autocomplete="off" />';
		$message_text.= '<div align="center"><span onClick="AddMoney(\''.$row["id"].'\', \'tests\', \'AddMoney\');" class="sub-green" style="float:none;" title="��������� ������ ��������">���������</span></div>';
		$message_text.= '<div id="info-msg-addmoney" style="display: none"></div>';

		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}else{
		$message_text = "ERROR: ���� �� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "AddMoney") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];
		$cena_advs = $row["cena_advs"];
		$money = $row["money"];
		$balance = $row["balance"];
		$goods_out = $row["goods_out"];
		$bads_out = $row["bads_out"];
		$money_pay = ( isset($_POST["money_add"]) && preg_match( "|^[\d]*[\.,]?[\d]{0,2}$|", abs(str_replace(",", ".", limpiarez($_POST["money_add"]))) ) ) ? abs(str_replace(",", ".", limpiarez($_POST["money_add"]))) : false;

		if($money_pay<$tests_min_pay) {
			$message_text = "ERROR: ����������� ����� ���������� - $tests_min_pay ���.";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}else{
			if($status=="0") {
				mysql_query("UPDATE `tb_ads_tests` SET `date_edit`='".time()."',`money`='$money_pay',`balance`='$money_pay' WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
			}else{
				mysql_query("UPDATE `tb_ads_tests` SET `date_edit`='".time()."',`money`=`money`+'$money_pay',`balance`=`balance`+'$money_pay' WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
			}

			$money = number_format(($money+$money_pay), 2, ".", "`");
			$balance = ( isset($balance) && ($balance+$money_pay)<1 ) ? number_format(($balance+$money_pay), 4, ".", "") : number_format(($balance+$money_pay), 2, ".", "");
			$totals = number_format(floor(bcdiv($balance,$cena_advs)), 0, ".", "`");
			$message_text = "OK";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array(
				"result" => "OK", 
				"totals" => "$totals", 
				"money" => "$money", 
				"balance" => "".($balance < 1 ? number_format($balance, 4, ".", "`") : number_format($balance, 2, ".", "`"))."", 
				"goods_out" => "".number_format($goods_out, 0, ".", "`")."", 
				"bads_out" => "".number_format($bads_out, 0, ".", "`").""
				)) : $message_text;
			exit($js_result);
		}
	}else{
		$message_text = "ERROR: ���� �� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "GoDel") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$status = $row["status"];
		$rek_name = $row["username"];
		$balance = $row["balance"];

		if($status==1) {
			$message_text = "����, ������ ��� �������, ���������� ��������� �� �����!";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}else{
			if($balance >= 1 && $rek_name != false) {
				$message_text = '<b>��� ������� ������� ����?</b><br>';
				$message_text.= '<table id="newform" style="background:none; border:none; width:auto; margin:0 auto"><tr>';
					$message_text.= '<td align="center" style="background:none; border:none;"><input class="ok" type="checkbox" id="cashback" value="1" style="height:16px; width:16px; margin:0px;" autocomplete="off" /></td>';
					$message_text.= '<td align="center" style="background:none; border:none;"> - ������� �������� �� ���� (����� '.$tests_comis_del.'%, �������� �� ������� ������� ��� ��������)</td>';
				$message_text.= '</tr></table>';
				$message_text.= '<div align="center"><span onClick="DelCash(\''.$row["id"].'\', \'tests\', \'DelCash\');" class="sub-red" style="float:none;" title="������� ����">�������</span></div>';

				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
			}else{
				$message_text = '<b>������� ����?</b><br><br>';
				$message_text.= '<div align="center"><span onClick="DelCash(\''.$row["id"].'\', \'tests\', \'DelCash\');" class="sub-red" style="float:none;" title="������� ����">�������</span></div>';

				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
			}
		}
	}else{
		$message_text = "ERROR: ���� �� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}elseif($option == "DelCash") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$status = $row["status"];
		$rek_name = $row["username"];
		$balance = $row["balance"];
		$cashback = (isset($_POST["cashback"]) && preg_match("|^[\d]{1}$|", intval(limpiarez($_POST["cashback"])))) ? intval(limpiarez($_POST["cashback"])) : 0;

		if($status==1) {
			$message_text = "����, ������ ��� �������, ���������� ��������� �� �����!";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}elseif($status==2 | $status==3){
			if($cashback == 1 && $balance >= 1 && $rek_name != false) {

				$money_back = ($balance - $balance * ($tests_comis_del/100));
				$money_back = number_format($money_back, 2, ".", "");

				mysql_query("DELETE FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
				mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'$money_back'  WHERE `username`='$rek_name'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$rek_name','".DATE("d.m.Y H:i")."','".time()."','$money_back','������� ������� � ��������� �������� (�����, ID:$id)', '���������', 'reklama')") or die(json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))));

				$message_text = "��������� �������� �$id ������� �������.\n������������ $rek_name ���������� $money_back ���.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
			}else{
				mysql_query("DELETE FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

				$message_text = "��������� �������� �$id ������� �������.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
			}
		}else{
			mysql_query("DELETE FROM `tb_ads_tests` WHERE `id`='$id'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

			$message_text = "OK";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}
	}else{
		$message_text = "ERROR: ���� �� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}

}else{
	$message_text = "ERROR: NO OPTION!";
	$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
	exit($js_result);
}

?>