<?php
error_reporting(E_ALL);
require_once(ROOT_DIR."/bbcode/bbcode.lib.php");
require_once(ROOT_DIR."/merchant/func_cache.php");

if(!DEFINED("PAY_ROW_AJAX")) {
	$result_text = "ERROR"; $message_text = "Hacking attempt!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));

}elseif($type_ads != "pay_row") {
	$result_text = "ERROR"; $message_text = "Hacking attempt!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

$message_text = "";

$sql_p = mysql_query("SELECT `sitewmr` FROM `tb_site` WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
$site_wmr = mysql_result($sql_p,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_pay_row' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
$cena_pay_row = number_format(mysql_result($sql,0,0), 2, ".", "");

$method_pay_to[1] = "WebMoney";
$method_pay_to[2] = "RoboKassa";
$method_pay_to[3] = "Wallet One";
$method_pay_to[4] = "InterKassa";
$method_pay_to[5] = "Payeer";
$method_pay_to[6] = "Qiwi";
$method_pay_to[7] = "PerfectMoney";
$method_pay_to[8] = "YandexMoney";
$method_pay_to[9] = "MegaKassa";
$method_pay_to[20] = "FreeKassa";
$method_pay_to[21] = "AdvCash";
$method_pay_to[10] = "��������� ����";

if($option == "del") {
	$sql_check = mysql_query("SELECT `id` FROM `tb_ads_pay_row` WHERE `id`='$id' AND `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql_check)>0) {
		mysql_query("DELETE FROM `tb_ads_pay_row` WHERE `id`='$id' AND `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		$result_text = "OK"; $message_text = "������� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}else{
		$result_text = "ERROR"; $message_text = "������� �� �������, �������� ��� ��� ���� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "add") {
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$description = isset($_POST["description"]) ? limitatexto(limpiarez($_POST["description"]), 60) : false;
	$description = get_magic_quotes_gpc() ? stripslashes($description) : $description;
	$method_pay = (isset($_POST["method_pay"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["method_pay"]))) ? intval(trim($_POST["method_pay"])) : 1;
	$method_pay = isset($method_pay_to[$method_pay]) ? $method_pay : false;
	$black_url = getHost($url);

	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

	if($description == false) {
		$result_text = "ERROR"; $message_text = "�� �� ������� �������� ������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif(mysql_num_rows($sql_bl) > 0 && $black_url != false) {
		$row_bl = mysql_fetch_assoc($sql_bl);
		$result_text = "ERROR"; $message_text = "���� ".$row_bl["domen"]." ������������ � ������� � ������ ������ ������� ".strtoupper($_SERVER["HTTP_HOST"])." �������: ".$row_bl["cause"]."";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($url == false | $url == "http://" | $url == "https://") {
		$result_text = "ERROR"; $message_text = "�� �� ������� URL-����� �����!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
		$result_text = "ERROR"; $message_text = "�� ������� ������� URL-����� �����!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($method_pay == false) {
		$result_text = "ERROR"; $message_text = "�� ������ ������ ������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}else{
		$summa = number_format(($cena_pay_row * (100-$cab_skidka)/100), 2, ".", "");

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		$merch_tran_id = mysql_result($sql_tranid,0,0);

		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		mysql_query("DELETE FROM `tb_ads_pay_row` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_pay_row` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_check)>0) {
			$id_zakaz = mysql_result($sql_check,0,0);

			mysql_query("UPDATE `tb_ads_pay_row` SET `merch_tran_id`='$merch_tran_id',`method_pay`='$method_pay',`wmid`='$user_wmid',`username`='$user_name',`date`='".time()."',`url`='$url',`description`='$description',`money`='$summa',`ip`='$my_lastiplog' WHERE `id`='$id_zakaz'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		}else{
			mysql_query("INSERT INTO `tb_ads_pay_row` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`wmid`,`username`,`date`,`url`,`description`,`money`,`ip`) 
			VALUES('0','".session_id()."','$merch_tran_id','$method_pay','$user_wmid','$user_name','".time()."','$url','$description','$summa','$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

			$sql_last_id = mysql_query("SELECT LAST_INSERT_ID() FROM `tb_ads_pay_row`");
			$id_zakaz = mysql_result($sql_last_id,0,0);
		}

		$shp_item = "31";
		$inv_desc = "������ �������: ������� ������, ����:$merch_tran_id";
		$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
		$inv_desc_en = "Pay advertise: pay row, order:$merch_tran_id";
		$money_add = number_format($summa, 2, ".", "");

		//$message_text.= '<span class="msg-ok" style="margin-bottom:4px;">��� ����� ������ � ����� �������� ������������� ����� ������!</span>';
		$message_text.= '<table class="tables">';
			$message_text.= '<thead><tr><th align="center" colspan="2">���������� � ������</th></tr></thead>';
			$message_text.= '<tr><td align="left" width="190">���� �</td><td align="left">'.number_format($merch_tran_id, 0,".", "").'</td></tr>';
			$message_text.= '<tr><td align="left">ID �������</td><td align="left">'.$id_zakaz.'</td></tr>';
			$message_text.= '<tr><td align="left">�������� ������</td><td align="left">'.$description.'</td></tr>';
			$message_text.= '<tr><td align="left">URL �����</td><td align="left"><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
			$message_text.= isset($cab_text) ? $cab_text : false;
			$message_text.= '<tr><td align="left">������ ������</td><td align="left"><b>'.$method_pay_to[$method_pay].'</b>, ���� ���������� �������� � ������� 24 �����</td></tr>';
			if($method_pay==8) {
				if(($summa*0.005)<0.01) {$money_add_ym = $summa + 0.01;}else{$money_add_ym = number_format(($summa*1.005),2,".","");}

				$message_text.= '<tr><td>��������� ������:</td><td><b style="color:green;">'.number_format($summa,2,".","`").'</b> <b>���.</b></td></tr>';
				$message_text.= '<tr><td>����� � ������</td><td><b style="color:#FF0000;">'.number_format($money_add_ym,2,".","`").'</b> <b>���.</b></td></tr>';

			}elseif($method_pay==3) {
				$money_add_w1 = number_format(($summa * 1.05), 2, ".", "");

				$message_text.= '<tr><td><b>��������� ������</b></td><td><b style="color:#76B15D;">'.number_format($summa,2,".","`").'</b> <b>���.</b></td></tr>';
				$message_text.= '<tr><td><b>����� � ������</b></td><td><b style="color:#76B15D;">'.number_format($money_add_w1,2,".","`").'</b> <b>���.</b></td></tr>';
			}else{
				$message_text.= '<tr><td>����� � ������</td><td><b style="color:#FF0000;">'.number_format($summa,2,".","`").'</b> <b>���.</b></td></tr>';
			}
			$message_text.= '<tr>';
				$message_text.= '<td align="center" style="border-right:none;">';
					if($method_pay == 10 && $user_name != false) {
						$message_text.= '<span onClick="PayAds(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="proc-btn" style="float:none;">��������</span>';
					}elseif($method_pay == 10 && $user_name == false) {
						$message_text.= '<span class="msg-error">��� ������ � ���������� ����� ���������� ��������������!</span>';
					}else{
						require_once(ROOT_DIR."/method_pay/method_pay_json.php");
					}
				$message_text.= '</td>';
				$message_text.= '<td align="center" style="border-left:none;">';
					$message_text.= '<span onClick="DeleteAds(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="sub-red" style="float:right; display:inline-block;">�������</span>';
					$message_text.= '<span onClick="ChangeAds(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="sub-green" style="float:right; display:inline-block;">��������</span>';
				$message_text.= '</td>';
			$message_text.= '</tr>';
		$message_text.= '</table>';

		$result_text = "OK";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "pay") {
	if($user_name==false) {
		$result_text = "ERROR"; $message_text = "��� ������ � ���������� ����� ���������� ��������������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}else{
		$sql_id = mysql_query("SELECT * FROM `tb_ads_pay_row` WHERE `id`='$id' AND `status`='0' AND `username`='$user_name' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_id)>0) {
			$row = mysql_fetch_assoc($sql_id);
			$money_pay = $row["money"];
			$merch_tran_id = $row["merch_tran_id"];

			if($user_money_rb >= $money_pay) {
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$reit_rek = mysql_result($sql,0,0);

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$reit_ref_rek = mysql_result($sql,0,0);

				$reit_add_1 = floor($money_pay/10) * $reit_rek;
				$reit_add_2 = floor($money_pay/10) * $reit_ref_rek;

				if($user_referer_1!=false) mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$user_referer_1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1',`money_rb`=`money_rb`-'$money_pay',`money_rek`=`money_rek`+'$money_pay' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				mysql_query("UPDATE `tb_ads_pay_row` SET `status`='1',`date`='".time()."',`wmid`='$user_wmid' WHERE `id`='$id' AND `status`='0' AND `username`='$user_name'  ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','$money_pay','������ ������� (������� ������, ID:$id)','��������','reklama')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				stat_pay("pay_row", $money_pay);
				ads_wmid($user_wmid, $user_wmr, $user_name, $money_pay);
				konkurs_ads_new($user_wmid, $user_name, $money_pay);
				invest_stat($money_pay, 4);
				BonusSurf($username, $money_pay);
				ActionRef(number_format($money_pay,2,".",""), $user_name);
				cache_pay_row();

				$result_text = "OK"; $message_text = "���� ������� ������� ���������!<br>�������, ��� ����������� �������� ������ �������!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}else{
				$result_text = "ERROR"; $message_text = "�� ����� ��������� ����� ������������ ������� ��� ������ �������!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}else{
			$result_text = "ERROR"; $message_text = "������! ������ ������� � �$id �� ����������, ���� ����� ��� ��� �������!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}
}else{
	$result_text = "ERROR"; $message_text = "ERROR: NO OPTION!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

?>