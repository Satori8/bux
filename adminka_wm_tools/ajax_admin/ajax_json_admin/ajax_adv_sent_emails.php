<?php
error_reporting(E_ALL);
require_once(ROOT_DIR."/bbcode/bbcode.lib.php");

if(!DEFINED("SENT_EMAILS_AJAX")) {
	$result_text = "ERROR"; $message_text = "Hacking attempt!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));

}elseif($type_ads != "sent_emails") {
	$result_text = "ERROR"; $message_text = "Hacking attempt!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

$message_text = "";

$sql_p = mysql_query("SELECT `sitewmr` FROM `tb_site` WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
$site_wmr = mysql_result($sql_p,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_sent_emails' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
$cena_sent_emails = number_format(mysql_result($sql,0,0), 2, ".", "");


if($option == "Delete") {
	$sql_check = mysql_query("SELECT `id` FROM `tb_ads_emails` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql_check)>0) {
		mysql_query("DELETE FROM `tb_ads_emails` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

		$result_text = "OK"; $message_text = "�������� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}else{
		$result_text = "ERROR"; $message_text = "�������� � ID:$id �� �������, �������� ��� ��� ���� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "ClearStat") {
	$sql = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];
		$sent = $row["sent"];
		$nosent = $row["nosent"];

		if($status == 0 | ($sent == 0 && $nosent == 0)) {
			$result_text = "ERROR"; $message_text = "������� ���� �������� ��� ����� 0";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}else{
			mysql_query("UPDATE `tb_ads_emails` SET `sent`='0',`nosent`='0' WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

			$result_text = "OK"; $message_text = "������� ������� �������!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}else{
		$result_text = "ERROR"; $message_text = "�������� � ID:$id �� �������, �������� ��� ��� ���� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "Add") {
	$subject = (isset($_POST["subject"])) ? limitatexto(limpiarez($_POST["subject"]), 250) : false;
	$message = isset($_POST["message"]) ? limitatexto(limpiarez($_POST["message"]), 2000) : false;
	$message = get_magic_quotes_gpc() ? stripslashes($message) : $message;
	$method_pay = 0;

	if($subject == false) {
		$result_text = "ERROR"; $message_text = "�� �� ������� ���� ���������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($message == false) {
		$result_text = "ERROR"; $message_text = "�� �� ������� ����� ���������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif(strlen($message) < 100) {
		$result_text = "ERROR"; $message_text = "� ������ ��������� ������ ���� �� ����� 100 ��������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}else{
		$summa = number_format($cena_sent_emails, 2, ".", "");

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		$merch_tran_id = mysql_result($sql_tranid,0,0);

		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		mysql_query("DELETE FROM `tb_ads_emails` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_emails` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_check)>0) {
			$id_zakaz = mysql_result($sql_check,0,0);

			mysql_query("UPDATE `tb_ads_emails` SET `method_pay`='$method_pay',`merch_tran_id`='$merch_tran_id',`wmid`='$user_wmid',`username`='$user_name',`subject`='$subject',`message`='$message',`date`='".time()."',`ip`='$my_lastiplog',`money`='$summa' WHERE `id`='$id_zakaz'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		}else{
			mysql_query("INSERT INTO `tb_ads_emails`(`status`,`method_pay`,`session_ident`,`merch_tran_id`,`wmid`,`username`,`subject`,`message`,`ip`,`date`,`money`) 
			VALUES('0','$method_pay','".session_id()."','$merch_tran_id','$user_wmid','$user_name','$subject','$message','$my_lastiplog','".time()."','$summa')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

			$sql_last_id = mysql_query("SELECT LAST_INSERT_ID() FROM `tb_ads_emails`") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$id_zakaz = mysql_result($sql_last_id,0,0);
		}

        	$sql_id = mysql_query("SELECT `id`,`subject`,`message` FROM `tb_ads_emails` WHERE `id`='$id_zakaz' AND `status`='0'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_id)>0) {
			$row_id = mysql_fetch_assoc($sql_id);
		        $subject_to = $row_id["subject"];
			$message_to = desc_bb($row_id["message"]);
		}else{
		        $subject_to = $subject;
			$message_to = desc_bb($message);
		}

		$message_text.= '<div id="PreView" style="display:block;">';

			$message_text.= '<h3 class="sp"><b>'.$subject_to.'</b> <span style="font-size:13px;">[��������������� �������� ��������]</span></h3>';
			$message_text.= '<table align="center" border="0" cellpadding="6" cellspacing="0" style="width:100%; background-color:#EBF1E7;">';
			$message_text.= "<tbody>";
			$message_text.= '<tr><td align="center">';
				$message_text.= '<table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFF;">';
				$message_text.= "<tbody>";
				$message_text.= '<tr><td style="background-color:#009E58; font-size:14px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">������������, (<i style="font-size:13px;">����� ����� ����� ������������ �������</i>).</td></tr>';
				$message_text.= '<tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px; background-color:#FFF;">';
					$message_text.= "�� �������� ��� ������ �������������, ��� ��� ��������� ������������������ ������������� ������� ".strtoupper($_SERVER["HTTP_HOST"])."<br><br>";
					$message_text.= "<b>���������� ������:</b>"."<br>";
					$message_text.= "$message_to";
				$message_text.= '</td></tr>';
				$message_text.= '<tr><td align="left" style="border-top:2px solid #DDD; font-size:12px; padding:10px 20px; background-color:#FFF;">';
					$message_text.= '����� ���������� �� ��������� �����, ������� � ���� ������� � � ������� ������� ������� � �������� ����������� ��������� ������ �� e-mail�.'."<br><br>";
					$message_text.= '<i>��� �������������� ���������, �������� �� ���� �� ����, �� ��� ����� �� ������� ��� ����� �� ����.</i>'."<br>";
				$message_text.= '</td></tr>';
				$message_text.= "</tbody>";
				$message_text.= "</table>";
			$message_text.= '</td></tr>';
			$message_text.= "</tbody>";
			$message_text.= "</table>";

			$message_text.= '<div align="center" style="margin:5px auto 10px auto;">';
				$message_text.= '<span onClick="AddAdv(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="sub-blue" style="float:none; display:inline-block;">���������</span>';
				$message_text.= '<span onClick="ChangeAds(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="sub-green" style="float:none; display:inline-block;">��������</span>';
				$message_text.= '<span onClick="DeleteAds(\''.$id_zakaz.'\', \''.$type_ads.'\');" class="sub-red" style="float:none; display:inline-block;">�������</span>';
			$message_text.= '</div>';

		$message_text.= '</div>';

		$result_text = "OK";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "Start") {
	$sql_id = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql_id)>0) {
		$row = mysql_fetch_assoc($sql_id);
		$status = $row["status"];
		$money_pay = $row["money"];
		$merch_tran_id = $row["merch_tran_id"];

		mysql_query("UPDATE `tb_ads_emails` SET `status`='1', `date`='".time()."' WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

		$result_text = "OK"; $message_text = "�������� ������� ��������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}else{
		$result_text = "ERROR"; $message_text = "������! ������ ������� � ID:$id �� ����������, ���� ����� ��� ��� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}


}elseif($option == "PlayPause") {
	$sql = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];

		if($status == 0) {
			$result_text = "ERROR"; $message_text = "��� �������, ���������� ��������� ��������� ������!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($status == 1) {
			mysql_query("UPDATE `tb_ads_emails` SET `status`='2', `date`='".time()."' WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

			$result_text = "OK"; $message_text = '<span class="adv-play" title="��������� �������� �� e-mail" onClick="PlayPause('.$row["id"].', \'sent_emails\', \'PlayPause\');"></span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($status == 2) {
			mysql_query("UPDATE `tb_ads_emails` SET `status`='1', `date`='".time()."' WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

			$result_text = "OK"; $message_text = '<span class="adv-pause" title="������������� �������� �� e-mail" onClick="PlayPause('.$row["id"].', \'sent_emails\', \'PlayPause\');"></span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($status == 3) {
			$result_text = "ERROR"; $message_text = "��� �������, ���������� ��������� ��������� ������!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}else{
			$result_text = "ERROR"; $message_text = "������ �� ���������!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}else{
		$result_text = "ERROR"; $message_text = "�������� � ID:$id �� �������, �������� ��� ��� ���� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "GetInfo") {
       	$sql_id = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql_id)>0) {
		$row_id = mysql_fetch_assoc($sql_id);
	        $status = $row_id["status"];
	        $subject_to = $row_id["subject"];
		$message_to = desc_bb($row_id["message"]);

		$message_text.= '<div id="PreView" style="display:block; margin:5px; color: #333333; font-size:12px;">';

			$message_text.= '<h3 class="sp">��������������� �������� ��������</h3>';
			$message_text.= '<table align="center" border="0" cellpadding="6" cellspacing="0" style="width:100%; background-color:#EBF1E7;">';
			$message_text.= "<tbody>";
			$message_text.= '<tr><td align="center">';
				$message_text.= '<table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFF;">';
				$message_text.= "<tbody>";
				$message_text.= '<tr><td style="background-color:#009E58; font-size:14px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:10px; color:#FFF; font-weight: normal;">������������, (<i style="font-size:13px;">����� ����� ����� ������������ �������</i>).</td></tr>';
				$message_text.= '<tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">';
					$message_text.= "�� �������� ��� ������ �������������, ��� ��� ��������� ������������������ ������������� ������� ".strtoupper($_SERVER["HTTP_HOST"])."<br><br>";
					$message_text.= "<b>���������� ������:</b>"."<br>";
					$message_text.= "$message_to";
				$message_text.= '</td></tr>';
				$message_text.= '<tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">';
					$message_text.= '����� ���������� �� ��������� �����, ������� � ���� ������� � � ������� ������� ������� � �������� ����������� ��������� ������ �� e-mail�.'."<br><br>";
					$message_text.= '<i>��� �������������� ���������, �������� �� ���� �� ����, �� ��� ����� �� ������� ��� ����� �� ����.</i>'."<br>";
				$message_text.= '</td></tr>';
				$message_text.= "</tbody>";
				$message_text.= "</table>";
			$message_text.= '</td></tr>';
			$message_text.= "</tbody>";
			$message_text.= "</table>";

		$message_text.= '</div>';

		$result_text = "OK";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}else{
		$result_text = "ERROR"; $message_text = "������! �������� � ID:$id �� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "LoadForm") {
	$sql = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$message_text.= '<div id="newform" onkeypress="CtrlEnter(event);">';
			$message_text.= '<table class="tables" style="border:none; margin:0; padding:10px; width:100%;">';
			$message_text.= '<thead><tr><th align="center" colspan="2">�������������� �������� ID:'.$id.'</th></thead></tr>';
			$message_text.= '<tr>';
				$message_text.= '<td align="left" width="220"><b>���� ���������</b></td>';
				$message_text.= '<td align="left"><input type="text" id="subject" maxlength="250" value="'.$row["subject"].'" class="ok"></td>';
			$message_text.= '</tr>';
			$message_text.= '<tr>';
				$message_text.= '<td align="left" colspan="2" style="padding:4px 5px 4px 5px;"><b>����� ��������� &darr;</b></td>';
			$message_text.= '</tr>';
			$message_text.= '<tr>';
				$message_text.= '<td align="left" colspan="2" style="">';
					$message_text.= '<span class="bbc-bold" style="float:left;" title="�������� ������" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'message\'); return false;">�</span>';
					$message_text.= '<span class="bbc-italic" style="float:left;" title="�������� ��������" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'message\'); return false;">�</span>';
					$message_text.= '<span class="bbc-uline" style="float:left;" title="�������� ��������������" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'message\'); return false;">�</span>';
					$message_text.= '<span class="bbc-tline" style="float:left;" title="������������� �����" onClick="javascript:InsertTags(\'[s]\',\'[/s]\', \'message\'); return false;">ST</span>';
					$message_text.= '<span class="bbc-left" style="float:left;" title="��������� �� ������ ����" onClick="javascript:InsertTags(\'[left]\',\'[/left]\', \'message\'); return false;"></span>';
					$message_text.= '<span class="bbc-center" style="float:left;" title="��������� �� ������" onClick="javascript:InsertTags(\'[center]\',\'[/center]\', \'message\'); return false;"></span>';
					$message_text.= '<span class="bbc-right" style="float:left;" title="��������� �� ������� ����" onClick="javascript:InsertTags(\'[right]\',\'[/right]\', \'message\'); return false;"></span>';
					$message_text.= '<span class="bbc-justify" style="float:left;" title="��������� �� ������" onClick="javascript:InsertTags(\'[justify]\',\'[/justify]\', \'message\'); return false;"></span>';
					$message_text.= '<span class="bbc-url" style="float:left;" title="�������� URL" onClick="javascript:InsertTags(\'[url]\',\'[/url]\', \'message\'); return false;">URL</span>';
					$message_text.= '<span class="bbc-url" style="float:left;" title="�������� �����������" onClick="javascript:InsertTags(\'[img]\',\'[/img]\', \'message\'); return false;">IMG</span>';
					$message_text.= '<span id="count1" style="display: block; float:right; color:#696969; margin-top:2px; margin-right:3px;">�������� ��������: 2000</span>';
					$message_text.= '<br>';
					$message_text.= '<div style="display: block; clear:both; padding-top:4px">';
						$message_text.= '<textarea id="message" class="ok" style="height:200px; width:99%;" onKeyup="descchange(\'1\', this, \'2000\');" onKeydown="descchange(\'1\', this, \'2000\');" onClick="descchange(\'1\', this, \'2000\');">'.$row["message"].'</textarea>';
					$message_text.= '</div>';
				$message_text.= '</td>';
			$message_text.= '</tr>';
			$message_text.= '</table>';
		$message_text.= '</div>';

		$message_text.= '<div align="center"><span id="Save" onClick="SaveAds(\''.$id.'\', \'sent_emails\', \'Save\');" class="sub-blue160" style="float:none; width:160px;">��������� ���������</span></div>';

		$result_text = "OK";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}else{
		$result_text = "ERROR"; $message_text = "������! �������� � ID:$id �� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}elseif($option == "Save") {
	$subject = (isset($_POST["subject"])) ? limitatexto(limpiarez($_POST["subject"]), 250) : false;
	$message = isset($_POST["message"]) ? limitatexto(limpiarez($_POST["message"]), 2000) : false;
	$message = get_magic_quotes_gpc() ? stripslashes($message) : $message;

	if($subject == false) {
		$result_text = "ERROR"; $message_text = "�� �� ������� ���� ���������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($message == false) {
		$result_text = "ERROR"; $message_text = "�� �� ������� ����� ���������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif(strlen($message) < 100) {
		$result_text = "ERROR"; $message_text = "� ������ ��������� ������ ���� �� ����� 100 ��������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}else{
		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_emails` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_check)>0) {

			mysql_query("UPDATE `tb_ads_emails` SET `date`='".time()."',`subject`='$subject',`message`='$message' WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

			$result_text = "OK"; $message_text = "$subject";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}else{
			$result_text = "ERROR"; $message_text = "������! �������� � ID:$id �� �������!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}

}else{
	$result_text = "ERROR"; $message_text = "ERROR: NO OPTION!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

?>