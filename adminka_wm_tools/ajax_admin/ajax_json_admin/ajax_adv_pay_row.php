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


if($option == "Delete") {
	$sql_check = mysql_query("SELECT `id`,`status` FROM `tb_ads_pay_row` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql_check)>0) {
		$row = mysql_fetch_assoc($sql_check);
		$status = $row["status"];

		mysql_query("DELETE FROM `tb_ads_pay_row` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if($status == 1) mysql_query("UPDATE `tb_ads_pay_row` SET `status`='1' WHERE `status`='3' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

		cache_pay_row();

		$result_text = "OK"; $message_text = "��������� �������� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}else{
		$result_text = "ERROR"; $message_text = "��������� �������� � ID:$id �� �������, �������� ��� ��� ���� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "Add") {
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$description = isset($_POST["description"]) ? limitatexto(limpiarez($_POST["description"]), 60) : false;
	$description = get_magic_quotes_gpc() ? stripslashes($description) : $description;
	$method_pay = 0;
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

	}else{
		$summa = number_format($cena_pay_row, 2, ".", "");

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

			$sql_last_id = mysql_query("SELECT LAST_INSERT_ID() FROM `tb_ads_pay_row`") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$id_zakaz = mysql_result($sql_last_id,0,0);
		}

        	$sql_id = mysql_query("SELECT `id`,`url`,`description` FROM `tb_ads_pay_row` WHERE `id`='$id_zakaz' AND `status`='0'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_id)>0) {
			$row_id = mysql_fetch_assoc($sql_id);
		        $id_zakaz = $row_id["id"];
		        $pay_row_url = $row_id["url"];
			$pay_row_desc = $row_id["description"];
		}else{
		        $pay_row_url = $url;
			$pay_row_desc = $description;
		}

		$message_text.= '<div id="PreView" style="display:block;">';
			$message_text.= '<table class="tables">';
				$message_text.= '<thead><tr><th align="center" colspan="2">���������� � �������</th></tr></thead>';
				$message_text.= '<tr><td align="left" width="200">ID �������</td><td align="left">'.$id_zakaz.'</td></tr>';
				$message_text.= '<tr><td align="left">�������� ������</td><td align="left">'.$pay_row_desc.'</td></tr>';
				$message_text.= '<tr><td align="left">URL �����</td><td align="left"><a href="'.$pay_row_url.'" target="_blank">'.$pay_row_url.'</a></td></tr>';
			$message_text.= '</table>';

			$message_text.= '<div align="center" style="margin:5px auto 10px auto;">';
				$message_text.= '<span onClick="AddAdv(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="sub-blue" style="float:none; display:inline-block;">����������</span>';
				$message_text.= '<span onClick="ChangeAds(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="sub-green" style="float:none; display:inline-block;">��������</span>';
				$message_text.= '<span onClick="DeleteAds(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="sub-red" style="float:none; display:inline-block;">�������</span>';
			$message_text.= '</div>';
		$message_text.= '</div>';

		$result_text = "OK";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "Start") {
	$sql_id = mysql_query("SELECT * FROM `tb_ads_pay_row` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql_id)>0) {
		$row = mysql_fetch_assoc($sql_id);
		$status = $row["status"];
		$money_pay = $row["money"];
		$merch_tran_id = $row["merch_tran_id"];

		mysql_query("UPDATE `tb_ads_pay_row` SET `status`='1', `date`='".time()."' WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		cache_pay_row();

		$result_text = "OK"; $message_text = "��������� �������� ������� ������������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}else{
		$result_text = "ERROR"; $message_text = "������! ������ ������� � ID:$id �� ����������, ���� ����� ��� ��� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "LoadForm") {
	$sql = mysql_query("SELECT * FROM `tb_ads_pay_row` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$message_text.= '<div id="newform" onkeypress="CtrlEnter(event);">';
			$message_text.= '<table class="tables" style="border:none; margin:0; padding:10px; width:100%;">';
			$message_text.= '<thead><tr><th align="center" colspan="2">�������������� ������ ID:'.$id.'</th></thead></tr>';
			$message_text.= '<tr>';
				$message_text.= '<td align="left" width="220"><b>�������� ������</b></td>';
				$message_text.= '<td align="left"><input type="text" id="description" maxlength="60" value="'.$row["description"].'" class="ok"></td>';
			$message_text.= '</tr>';
			$message_text.= '<tr>';
				$message_text.= '<td align="left"><b>URL �����</b> (������� http://)</td>';
				$message_text.= '<td align="left"><input type="text" id="url" maxlength="300" value="'.$row["url"].'" class="ok"></td>';
			$message_text.= '</tr>';
			$message_text.= '</table>';
		$message_text.= '</div>';

		$message_text.= '<div align="center"><span id="Save" onClick="SaveAds(\''.$id.'\', \'pay_row\', \'Save\');" class="sub-blue160" style="float:none; width:160px;">��������� ���������</span></div>';

		$result_text = "OK";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}else{
		$result_text = "ERROR"; $message_text = "������! ��������� �������� � ID:$id �� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "Save") {
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$description = isset($_POST["description"]) ? limitatexto(limpiarez($_POST["description"]), 60) : false;
	$description = get_magic_quotes_gpc() ? stripslashes($description) : $description;
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

	}else{
		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_pay_row` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_check)>0) {

			mysql_query("UPDATE `tb_ads_pay_row` SET `date`='".time()."',`url`='$url',`description`='$description' WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

			cache_pay_row();

			$result_text = "OK"; $message_text = "$description";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}else{
			$result_text = "ERROR"; $message_text = "������! ��������� �������� � ID:$id �� �������!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}

}else{
	$result_text = "ERROR"; $message_text = "ERROR: NO OPTION!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

?>