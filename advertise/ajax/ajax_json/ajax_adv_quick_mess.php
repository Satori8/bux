<?php
error_reporting(E_ALL);
require_once(ROOT_DIR."/bbcode/bbcode.lib.php");
require_once(ROOT_DIR."/merchant/func_cache.php");

if(!DEFINED("PAY_QUICK_MESS")) {
	$result_text = "ERROR"; $message_text = "Hacking attempt!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));

}elseif($type_ads != "quick_mess") {
	$result_text = "ERROR"; $message_text = "Hacking attempt!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

$message_text = "";
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='quick_mess_�ena' AND `howmany`='1'");
	$quick_mess_cena = number_format(mysql_result($sql,0,0), 2, ".", "");
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='quick_mess_url' AND `howmany`='1'");
	$quick_mess_url = number_format(mysql_result($sql,0,0), 2, ".", "");

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='quick_mess_color' AND `howmany`='1'");
	$quick_mess_color = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql_p = mysql_query("SELECT `sitewmr` FROM `tb_site` WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
$site_wmr = mysql_result($sql_p,0,0);

//$method_pay_to[1] = "WebMoney";
//$method_pay_to[2] = "RoboKassa";
//$method_pay_to[3] = "Wallet One";
//$method_pay_to[4] = "InterKassa";
//$method_pay_to[5] = "Payeer";
//$method_pay_to[6] = "Qiwi";
//$method_pay_to[7] = "PerfectMoney";
//$method_pay_to[8] = "YandexMoney";
//$method_pay_to[9] = "MegaKassa";
//$method_pay_to[20] = "FreeKassa";
$method_pay_to[2] = "��������� ����";
$method_pay_to[1] = "Oc������ ����";

if($option == "del") {
	$sql_check = mysql_query("SELECT `id` FROM `tb_ads_quick_mess` WHERE `id`='$id' AND `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 10") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql_check)>0) {
		mysql_query("DELETE FROM `tb_ads_quick_mess` WHERE `id`='$id' AND `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 10") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		$result_text = "OK"; $message_text = "������� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}else{
		$result_text = "ERROR"; $message_text = "������� �� �������, �������� ��� ��� ���� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "add") {
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$color = (isset($_POST["color"])) ? ($_POST["color"]) : 0;
	$description = isset($_POST["description"]) ? limitatexto(limpiarez($_POST["description"]), 60) : false;
	$description = get_magic_quotes_gpc() ? stripslashes($description) : $description;
	$method_pay = (isset($_POST["method_pay"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["method_pay"]))) ? intval(trim($_POST["method_pay"])) : 1;
	$method_pay = isset($method_pay_to[$method_pay]) ? $method_pay : false;
	$black_url = getHost($url);

	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

	if($color==1){
	    $pl_sum=$quick_mess_color;
	}else{
	     $pl_sum=0;
	}
	
	if($url!=''){
	    $pl_sum_u=$quick_mess_url;
	}else{
	     $pl_sum_u=0;
	}
	
	if($description == false) {
		$result_text = "ERROR"; $message_text = "�� �� ������� ���������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif(mysql_num_rows($sql_bl) > 0 && $black_url != false) {
		$row_bl = mysql_fetch_assoc($sql_bl);
		$result_text = "ERROR"; $message_text = "���� ".$row_bl["domen"]." ������������ � ������� � ������ ������ ������� ".strtoupper($_SERVER["HTTP_HOST"])." �������: ".$row_bl["cause"]."";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://") and $url!='') {
		$result_text = "ERROR"; $message_text = "�� ������� ������� URL-����� �����!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($method_pay == false) {
		$result_text = "ERROR"; $message_text = "�� ������ ������ ������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	/*}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url)!=false) {
		$result_text = "ERROR"; $message_text = SFB_YANDEX($url);
		exit(my_json_encode($ajax_json, $result_text, $message_text));
*/
	}else{
		$summa = number_format((($quick_mess_cena * (100-$cab_skidka)/100)+$pl_sum+$pl_sum_u), 2, ".", "");

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		$merch_tran_id = mysql_result($sql_tranid,0,0);

		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		mysql_query("DELETE FROM `tb_ads_quick_mess` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
 
		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_quick_mess` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_check)>0) {
			$id_zakaz = mysql_result($sql_check,0,0);

			mysql_query("UPDATE `tb_ads_quick_mess` SET `merch_tran_id`='$merch_tran_id',`method_pay`='$method_pay',`wmid`='$user_wmid',`username`='$user_name',`date`='".time()."',`url`='$url',`description`='$description',`money`='$summa',`ip`='$my_lastiplog' WHERE `id`='$id_zakaz'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			
		}else{
			$sql_uss = mysql_fetch_array(mysql_query("SELECT `id` FROM `tb_users` WHERE `username`='$user_name'"));
		    $id_us=$sql_uss['id'];
			mysql_query("INSERT INTO `tb_ads_quick_mess` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`wmid`,`username`,`date`,`url`,`description`,`money`,`ip`,`color`,`id_us`) 
			VALUES('0','".session_id()."','$merch_tran_id','$method_pay','$user_wmid','$user_name','".time()."','$url','$description','$summa','$my_lastiplog','$color', '$id_us')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

			$sql_last_id = mysql_query("SELECT LAST_INSERT_ID() FROM `tb_ads_quick_mess`");
			$id_zakaz = mysql_result($sql_last_id,0,0);
		}

		/*$shp_item = "31";
		$inv_desc = "������ �������: ������� ������, ����:$merch_tran_id";
		$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
		$inv_desc_en = "Pay advertise: quick_mess, order:$merch_tran_id";
		$money_add = number_format($summa, 2, ".", "");*/

		//$message_text.= '<span class="msg-ok" style="margin-bottom:4px;">��� ����� ������ � ����� �������� ������������� ����� ������!</span>';
		$message_text.= '<table class="tables">';
			$message_text.= '<thead><tr><th align="center" colspan="2">���������� � ������</th></tr></thead>';
			$message_text.= '<tr><td align="left" width="190">���� �</td><td align="left">'.number_format($merch_tran_id, 0,".", "").'</td></tr>';
			$message_text.= '<tr><td align="left">ID �������</td><td align="left">'.$id_zakaz.'</td></tr>';
			$message_text.= '<tr><td align="left">���������</td><td align="left">'.$description.'</td></tr>';
			if ($url!=''){
			$message_text.= '<tr><td align="left">URL �����</td><td align="left"><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
			}
			$message_text.= isset($cab_text) ? $cab_text : false;
			$message_text.= '<tr><td align="left">������ ������</td><td align="left"><b>'.$method_pay_to[$method_pay].'</b>, ���� ���������� �������� � ������� 24 �����</td></tr>';
			
			$message_text.= '<tr><td>����� � ������</td><td><b style="color:#FF0000;">'.number_format($summa,2,".","`").'</b> <b>���.</b></td></tr>';
			
			$message_text.= '<tr>';
				$message_text.= '<td align="center" style="border-right:none;">';
					if($method_pay == 1 && $user_name != false) {
						$message_text.= '<span onClick="PayAds_1(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="sub-blue160" style="float:none;">��������</span>';
					}elseif($method_pay == 2 && $user_name != false) {
						$message_text.= '<span onClick="PayAds_0(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="sub-blue160" style="float:none;">��������</span>';
					}elseif(($method_pay == 2 or $method_pay == 1) && $user_name == false) {
						$message_text.= '<span class="msg-error">��� ������ � ���������� ��� ��������� ����� ���������� ��������������!</span>';
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

}elseif($option == "pay_rek") {
	if($user_name==false) {
		$result_text = "ERROR"; $message_text = "��� ������ � ���������� ����� ���������� ��������������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}else{
		$sql_id = mysql_query("SELECT * FROM `tb_ads_quick_mess` WHERE `id`='$id' AND `status`='0' AND `username`='$user_name' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
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
				
				mysql_query("UPDATE `tb_ads_quick_mess` SET `status`='1',`date`='".time()."',`wmid`='$user_wmid' WHERE `id`='$id' AND `status`='0' AND `username`='$user_name'  ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','$money_pay','������ ������� (������� ���������, ID:$id)','��������','reklama')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				stat_pay("pay_mes", $money_pay);
				ads_wmid($user_wmid, $user_wmr, $user_name, $money_pay);
				konkurs_ads_new($user_wmid, $user_name, $money_pay);
				invest_stat($money_pay, 7);
				BonusSurf($user_name, $money_pay);
				ActionRef(number_format($money_pay,2,".",""), $user_name);
				cache_quick_mess();

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
}elseif($option == "pay_os"){
	if($user_name==false) {
		$result_text = "ERROR"; $message_text = "��� ������ � ��������� ����� ���������� ��������������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}else{
		$sql_id = mysql_query("SELECT * FROM `tb_ads_quick_mess` WHERE `id`='$id' AND `status`='0' AND `username`='$user_name' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_id)>0) {
			$row = mysql_fetch_assoc($sql_id);
			$money_pay = $row["money"];
			$merch_tran_id = $row["merch_tran_id"];
		$sql_us_s = mysql_fetch_array(mysql_query("SELECT * FROM `tb_users` WHERE `username`='$user_name'"));
			if($sql_us_s['money'] >= $money_pay) {
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$reit_rek = mysql_result($sql,0,0);

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$reit_ref_rek = mysql_result($sql,0,0);

				$reit_add_1 = floor($money_pay/10) * $reit_rek;
				$reit_add_2 = floor($money_pay/10) * $reit_ref_rek;

				if($user_referer_1!=false) mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$user_referer_1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1',`money`=`money`-'$money_pay',`money_rek`=`money_rek`+'$money_pay' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				
				mysql_query("UPDATE `tb_ads_quick_mess` SET `status`='1',`date`='".time()."',`wmid`='$user_wmid' WHERE `id`='$id' AND `status`='0' AND `username`='$user_name'  ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','$money_pay','������ ������� (������� ���������, ID:$id)','��������','reklama')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				stat_pay("pay_mes", $money_pay);
				ads_wmid($user_wmid, $user_wmr, $user_name, $money_pay);
				konkurs_ads_new($user_wmid, $user_name, $money_pay); 
				invest_stat($money_pay, 7);
				BonusSurf($user_name, $money_pay);
				ActionRef(number_format($money_pay,2,".",""), $user_name);
				cache_quick_mess();

				$result_text = "OK"; $message_text = "���� ������� ������� ���������!<br>�������, ��� ����������� �������� ������ �������!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}else{
				$result_text = "ERROR"; $message_text = "�� ����� �������� ����� ������������ ������� ��� ������ �������!";
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