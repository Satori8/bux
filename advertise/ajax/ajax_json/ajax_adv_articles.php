<?php
error_reporting(E_ALL);
require_once(ROOT_DIR."/bbcode/bbcode.lib.php");

if(!DEFINED("ARTICLES_AJAX")) {
	$result_text = "ERROR"; $message_text = "Hacking attempt!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));

}elseif($type_ads != "articles") {
	$result_text = "ERROR"; $message_text = "Hacking attempt!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

$message_text = "";

$sql_p = mysql_query("SELECT `sitewmr` FROM `tb_site` WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
$site_wmr = mysql_result($sql_p,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_articles' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
$cena_articles = number_format(mysql_result($sql,0,0), 2, ".", "");

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
	$sql_check = mysql_query("SELECT `id` FROM `tb_ads_articles` WHERE `id`='$id' AND `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql_check)>0) {
		mysql_query("DELETE FROM `tb_ads_articles` WHERE `id`='$id' AND `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		$result_text = "OK"; $message_text = "������ �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}else{
		$result_text = "ERROR"; $message_text = "������ �� �������, �������� ��� ��� ���� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "add") {
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 100) : false;
	$desc_min = isset($_POST["desc_min"]) ? limitatexto(limpiarez($_POST["desc_min"]), 1000) : false;
	$desc_min = get_magic_quotes_gpc() ? stripslashes($desc_min) : $desc_min;
	$desc_big = isset($_POST["desc_big"]) ? limitatexto(limpiarez($_POST["desc_big"]), 5000) : false;
	$desc_big = get_magic_quotes_gpc() ? stripslashes($desc_big) : $desc_big;
	$method_pay = (isset($_POST["method_pay"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["method_pay"]))) ? intval(trim($_POST["method_pay"])) : 1;
	$method_pay = isset($method_pay_to[$method_pay]) ? $method_pay : false;
	$black_url = getHost($url);

	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

	if($title == false) {
		$result_text = "ERROR"; $message_text = "�� �� ������� ��������� ������!";
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

	}elseif($desc_min == false) {
		$result_text = "ERROR"; $message_text = "�� �� ������� ������� �������� ������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif(strlen($desc_min) < 50) {
		$result_text = "ERROR"; $message_text = "� ������� �������� ������ ������ ���� �� ����� 50 ��������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($desc_big == false) {
		$result_text = "ERROR"; $message_text = "�� �� ������� �������� ������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif(strlen($desc_big) < 100) {
		$result_text = "ERROR"; $message_text = "� �������� ������ ������ ���� �� ����� 100 ��������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($method_pay == false) {
		$result_text = "ERROR"; $message_text = "�� ������ ������ ������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url)!=false) {
		$result_text = "ERROR"; $message_text = SFB_YANDEX($url);
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}else{
		$summa = number_format(($cena_articles * (100-$cab_skidka)/100), 2, ".", "");

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		$merch_tran_id = mysql_result($sql_tranid,0,0);

		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		mysql_query("DELETE FROM `tb_ads_articles` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_articles` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_check)>0) {
			$id_zakaz = mysql_result($sql_check,0,0);

			mysql_query("UPDATE `tb_ads_articles` SET `merch_tran_id`='$merch_tran_id',`method_pay`='$method_pay',`wmid`='$user_wmid',`username`='$user_name',`date_edit`='".time()."',`title`='$title',`url`='$url',`desc_min`='$desc_min',`desc_big`='$desc_big',`money`='$summa',`ip`='$my_lastiplog' WHERE `id`='$id_zakaz'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		}else{
			mysql_query("INSERT INTO `tb_ads_articles` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`wmid`,`username`,`date`,`date_edit`,`title`,`url`,`desc_min`,`desc_big`,`money`,`ip`) 
			VALUES('0','".session_id()."','$merch_tran_id','$method_pay','$user_wmid','$user_name','".time()."','".time()."','$title','$url','$desc_min','$desc_big','$summa','$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

			$sql_last_id = mysql_query("SELECT LAST_INSERT_ID() FROM `tb_ads_articles`");
			$id_zakaz = mysql_result($sql_last_id,0,0);

			mysql_query("UPDATE `tb_ads_articles` SET `up_list`='$id_zakaz' WHERE `id`='$id_zakaz'") or die(mysql_error());
		}

        	$sql_id = mysql_query("SELECT `id`,`url`,`desc_min`,`desc_big`,`date`,`views` FROM `tb_ads_articles` WHERE `id`='$id_zakaz' AND `status`='0'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_id)>0) {
			$row_id = mysql_fetch_assoc($sql_id);
		        $id_zakaz = $row_id["id"];
		        $art_url = $row_id["url"];
		        $art_date = $row_id["date"];
		        $art_views = $row_id["views"];
		        $desc_min_to = desc_bb($row_id["desc_min"]);
			$desc_big_to = desc_bb($row_id["desc_big"]);
		}else{
		        $art_url = $url;
		        $art_date = time();
		        $art_views = 0;
		        $desc_min_to = desc_bb($desc_min);
			$desc_big_to = desc_bb($desc_big);
		}

		$message_text.= '<div id="PreView" style="display:block;">';

			$message_text.= '<h3 class="sp">��������������� �������� ������</h3>';
			$message_text.= '<div style="margin:0 auto 20px auto; padding: 8px 1px 0px 1px; background-color:#F9F9F9; border-radius:5px 5px 10px 10px; box-shadow: 0 0 0 1px rgb(194, 192, 184) inset, 0 5px 0 -4px rgb(255, 255, 255), 0 5px 0 -3px rgb(194, 192, 184), 0 11px 0 -8px rgb(255, 255, 255), 0 11px 0 -7px rgb(194, 192, 184), 0 17px 0 -12px rgb(255, 255, 255), 0 17px 0 -11px rgb(194, 192, 184);">';
				$message_text.= '<div class="test-blank-title" style="width:100%; margin:0 auto; border-radius:0px; text-shadow: 1px 1px 1px #000;">'.$title.'</div>';
				$message_text.= '<div align="center" style="margin:7px auto 0px auto; font-size:13px; color:#828282; text-shadow: 1px 1px 2px #FFF;">������� ���������� ������</div>';
				$message_text.= '<div style="padding:8px 10px 15px 10px;">'.$desc_min_to.'</div>';
				$message_text.= '<div style="padding:0px 10px 15px 10px;">������ �� ����: <a href="'.$art_url.'" target="_blank" class="golinktest">'.$art_url.'</a></div>';

				$message_text.= '<div style="padding:0px 10px 30px 10px;">';
					$message_text.= '<span class="art-calendar">���� �������� '.DATE("d.m.Y � H:i", $art_date).'</span>';
					$message_text.= '<span class="art-eye">'.count_text(number_format($art_views, 0, ".", "`"), "��������", "���������", "����������").'</span>';
				$message_text.= '</div>';
			$message_text.= '</div>';

			$message_text.= '<div style="margin:0 auto 20px auto; padding: 8px 1px 0px 1px; background-color:#F9F9F9; border-radius:5px 5px 10px 10px; box-shadow: 0 0 0 1px rgb(194, 192, 184) inset, 0 5px 0 -4px rgb(255, 255, 255), 0 5px 0 -3px rgb(194, 192, 184), 0 11px 0 -8px rgb(255, 255, 255), 0 11px 0 -7px rgb(194, 192, 184), 0 17px 0 -12px rgb(255, 255, 255), 0 17px 0 -11px rgb(194, 192, 184);">';
				$message_text.= '<div class="test-blank-title" style="width:100%; margin:0 auto; border-radius:0px; text-shadow: 1px 1px 1px #000;">'.$title.'</div>';
				$message_text.= '<div style="padding:8px 10px 15px 10px;">'.$desc_big_to.'</div>';
				$message_text.= '<div style="padding:0px 10px 15px 10px;">������ �� ����: <a href="'.$art_url.'" target="_blank" class="golinktest">'.$art_url.'</a></div>';

				$message_text.= '<div style="padding:0px 10px 30px 10px;">';
					$message_text.= '<span class="art-calendar">���� �������� '.DATE("d.m.Y � H:i", $art_date).'</span>';
					$message_text.= '<span class="art-eye">'.count_text(number_format($art_views, 0, ".", "`"), "��������", "���������", "����������").'</span>';
				$message_text.= '</div>';
			$message_text.= '</div>';

			$message_text.= '<div align="center" style="margin:5px auto 10px auto;">';
				$message_text.= '<span onClick="GoToPay(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="sub-blue" style="float:none; display:inline-block;">����������</span>';
				$message_text.= '<span onClick="ChangeAds(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="sub-green" style="float:none; display:inline-block;">��������</span>';
				$message_text.= '<span onClick="DeleteAds(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="sub-red" style="float:none; display:inline-block;">�������</span>';
			$message_text.= '</div>';

		$message_text.= '</div>';

		$shp_item = "30";
		$inv_desc = "������ �������: ������� ������, ����:$merch_tran_id";
		$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
		$inv_desc_en = "Pay advertise: articles, order:$merch_tran_id";
		$money_add = number_format($summa, 2, ".", "");

		$message_text.= '<div id="ToPaySys" style="display:none;"><table class="tables">';
			$message_text.= '<thead><tr><th align="center" colspan="2">���������� � ������</th></tr></thead>';
			$message_text.= '<tr><td align="left" width="190">���� �</td><td align="left">'.number_format($merch_tran_id, 0,".", "").'</td></tr>';
			$message_text.= '<tr><td align="left">ID �������</td><td align="left">'.$id_zakaz.'</td></tr>';
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
			$message_text.= '<tr><td align="center" colspan="2">';
				if($method_pay == 10 && $user_name != false) {
					$message_text.= '<span onClick="PayAds(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="sub-blue160" style="float:none;">��������</span>';
				}elseif($method_pay == 10 && $user_name == false) {
					$message_text.= '<span class="msg-error">��� ������ � ���������� ����� ���������� ��������������!</span>';
				}else{
					require_once(ROOT_DIR."/method_pay/method_pay_json.php");
				}
			$message_text.= '</td></tr>';
		$message_text.= '</table></div>';

		$result_text = "OK";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "pay") {
	if($user_name==false) {
		$result_text = "ERROR"; $message_text = "��� ������ � ���������� ����� ���������� ��������������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}else{
		$sql_id = mysql_query("SELECT * FROM `tb_ads_articles` WHERE `id`='$id' AND `status`='0' AND `username`='$user_name' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
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
				mysql_query("UPDATE `tb_ads_articles` SET `status`='2',`date`='".time()."',`wmid`='$user_wmid' WHERE `id`='$id' AND `status`='0' AND `username`='$user_name'  ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','$money_pay','������ ������� (������� ������, ID:$id)','��������','reklama')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				stat_pay("articles", $money_pay);
				ads_wmid($user_wmid, $user_wmr, $user_name, $money_pay);
				konkurs_ads_new($user_wmid, $user_name, $money_pay);
				invest_stat($money_pay, 4);
				BonusSurf($username, $money_pay);
				ActionRef(number_format($money_pay,2,".",""), $user_name);

				$result_text = "OK"; $message_text = "������ ������ �������. ���� ������ ���������� �� ���������!";
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