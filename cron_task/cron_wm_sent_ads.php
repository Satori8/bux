<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");
require(ROOT_DIR."/auto_pay_req/wmxml.inc.php");

$domen = "https://lilacbux.com";
$subject = "<b>����������� �� ��������� ��������� ��������</b>";
$message = "������������!\n$subject:\n---------------------------\n";

$sql = mysql_query("SELECT * FROM `tb_ads_dlink` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		if($row["type_serf"]==2 | $row["type_serf"]==4) {
			$message = "������������!\n$subject:\n";
			$message.= "---------------------------\n";
			$message.= "���� ������ � ��������� �������� ������� ��������� ���� ����� �� ����� $domen\n";
			$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
			$message.= "URL �����: ".$row["url"]."\n";
			$message.= "URL �������: ".$row["description"]."\n\n";
			$message.= "���� �� ������������������ ������������ � ��������� ������� �� ������ ��������, �� �� ������ �������� ���� ��������� �������� ����� ������� �������������, ��� ����� ��������� �� ������ - $domen/cabinet_ads?ads=dlink \n\n";
			$message.= "���� �� �������������������� ������������, �� �� ������ �������� ����� ������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=dlinks \n\n";
		}else{
			$message = "������������!\n$subject:\n";
			$message.= "---------------------------\n";
			$message.= "���� ������������ ������ ������� ��������� ���� ����� �� ����� $domen\n";
			$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
			$message.= "URL �����: ".$row["url"]."\n";
			$message.= "���������: ".$row["title"]."\n";
			$message.= "��������: ".$row["description"]."\n\n";
			$message.= "���� �� ������������������ ������������ � ��������� ������� �� ������ ��������, �� �� ������ �������� ���� ��������� �������� ����� ������� �������������, ��� ����� ��������� �� ������ - $domen/cabinet_ads?ads=dlink \n\n";
			$message.= "���� �� �������������������� ������������, �� �� ������ �������� ����� ������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=dlinks \n\n";
		}

		$message.= "���������� ��� �� ������������� ������ �������.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>��� �������������� �������������� ���������, �������� �� ���� �� �����������.</i>\n";
		$message.= "<i>� ���������, ������������� �����</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_dlink` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_youtube` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		if($row["type_serf"]==2 | $row["type_serf"]==4) {
			$message = "������������!\n$subject:\n";
			$message.= "---------------------------\n";
			$message.= "���� ������ � ��������� �������� ������� ��������� ���� ����� �� ����� $domen\n";
			$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
			$message.= "URL �����: ".$row["url"]."\n";
			$message.= "URL �������: ".$row["description"]."\n\n";
			$message.= "���� �� ������������������ ������������ � ��������� ������� �� ������ ��������, �� �� ������ �������� ���� ��������� �������� ����� ������� �������������, ��� ����� ��������� �� ������ - $domen/cabinet_ads?ads=youtube \n\n";
			$message.= "���� �� �������������������� ������������, �� �� ������ �������� ����� ������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=youtube \n\n";
		}else{
			$message = "������������!\n$subject:\n";
			$message.= "---------------------------\n";
			$message.= "��� ���������� YouTube ������� �������� ���� ����� �� ����� $domen\n";
			$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
			$message.= "URL �����: ".$row["url"]."\n";
			$message.= "���������: ".$row["title"]."\n";
			$message.= "��������: ".$row["description"]."\n\n";
			$message.= "���� �� ������������������ ������������ � ��������� ������� �� ������ ��������, �� �� ������ �������� ���� ��������� �������� ����� ������� �������������, ��� ����� ��������� �� ������ - $domen/cabinet_ads?ads=youtube \n\n";
			$message.= "���� �� �������������������� ������������, �� �� ������ �������� ����� ������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=youtube \n\n";
		}

		$message.= "���������� ��� �� ������������� ������ �������.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>��� �������������� �������������� ���������, �������� �� ���� �� �����������.</i>\n";
		$message.= "<i>� ���������, ������������� �����</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_youtube` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_psevdo` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "������������!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "���� ������-������������ ������ ������� ��������� ���� ����� �� ����� $domen\n";
		$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
		$message.= "URL �����: ".$row["url"]."\n";
		$message.= "��������: ".$row["description"]."\n\n";
		$message.= "���� �� ������������������ ������������ � ��������� ������� �� ������ ��������, �� �� ������ �������� ���� ��������� �������� ����� ������� �������������, ��� ����� ��������� �� ������ - $domen/cabinet_ads?ads=psevdo \n\n";
		$message.= "���� �� �������������������� ������������, �� �� ������ �������� ����� ������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=psevdo \n\n";

		$message.= "���������� ��� �� ������������� ������ �������.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>��� �������������� �������������� ���������, �������� �� ���� �� �����������.</i>\n";
		$message.= "<i>� ���������, ������������� �����</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_psevdo` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "������������!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "���� ������ � ����-�������� ������� ��������� ���� ����� �� ����� $domen\n";
		$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
		$message.= "URL �����: ".$row["url"]."\n";
		$message.= "��������: ".$row["description"]."\n\n";
		$message.= "���� �� ������������������ ������������ � ��������� ������� �� ������ ��������, �� �� ������ �������� ���� ��������� �������� ����� ������� �������������, ��� ����� ��������� �� ������ - $domen/cabinet_ads?ads=autoserf \n\n";
		$message.= "���� �� �������������������� ������������, �� �� ������ �������� ����� ������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=auto_serf \n\n";

		$message.= "���������� ��� �� ������������� ������ �������.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>��� �������������� �������������� ���������, �������� �� ���� �� �����������.</i>\n";
		$message.= "<i>� ���������, ������������� �����</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_auto` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}


$sql = mysql_query("SELECT * FROM `tb_ads_slink` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "������������!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "���� ����������� ������ ������� ��������� ���� ����� �� ����� $domen\n";
		$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
		$message.= "URL �����: ".$row["url"]."\n";
		$message.= "��������: ".$row["description"]."\n\n";
		$message.= "���� �� ������������������ ������������ � ��������� ������� �� ������ ��������, �� �� ������ �������� ���� ��������� �������� ����� ������� �������������, ��� ����� ��������� �� ������ - $domen/cabinet_ads?ads=statlink \n\n";
		$message.= "���� �� �������������������� ������������, �� �� ������ �������� ����� ������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=stat_links \n\n";

		$message.= "���������� ��� �� ������������� ������ �������.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>��� �������������� �������������� ���������, �������� �� ���� �� �����������.</i>\n";
		$message.= "<i>� ���������, ������������� �����</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_slink` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}


$sql = mysql_query("SELECT * FROM `tb_ads_kontext` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "������������!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "���� ������ � ����������� ������� ������� ��������� ���� ����� �� ����� $domen\n";
		$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
		$message.= "URL �����: ".$row["url"]."\n";
		$message.= "���������: ".$row["title"]."\n";
		$message.= "��������: ".$row["description"]."\n\n";
		$message.= "���� �� ������������������ ������������ � ��������� ������� �� ������ ��������, �� �� ������ �������� ���� ��������� �������� ����� ������� �������������, ��� ����� ��������� �� ������ - $domen/cabinet_ads?ads=kontext \n\n";
		$message.= "���� �� �������������������� ������������, �� �� ������ �������� ����� ������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=kontext \n\n";

		$message.= "���������� ��� �� ������������� ������ �������.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>��� �������������� �������������� ���������, �������� �� ���� �� �����������.</i>\n";
		$message.= "<i>� ���������, ������������� �����</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_kontext` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_banner` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "������������!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "��� ������ ������� �������� ���� ����� �� ����� $domen\n";
		$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
		$message.= "URL �����: ".$row["url"]."\n";
		$message.= "URL �������: ".$row["urlbanner"]."\n\n";
		$message.= "���� �� ������������������ ������������ � ��������� ������� �� ������ ��������, �� �� ������ �������� ���� ��������� �������� ����� ������� �������������, ��� ����� ��������� �� ������ - $domen/cabinet_ads?ads=banners \n\n";
		$message.= "���� �� �������������������� ������������, �� �� ������ �������� ����� ������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=banners \n\n";

		$message.= "���������� ��� �� ������������� ������ �������.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>��� �������������� �������������� ���������, �������� �� ���� �� �����������.</i>\n";
		$message.= "<i>� ���������, ������������� �����</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_banner` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_txt` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "������������!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "���� ��������� ���������� ������� ��������� ���� ����� �� ����� $domen\n";
		$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
		$message.= "URL �����: ".$row["url"]."\n";
		$message.= "��������: ".$row["description"]."\n\n";
		$message.= "���� �� ������������������ ������������ � ��������� ������� �� ������ ��������, �� �� ������ �������� ���� ��������� �������� ����� ������� �������������, ��� ����� ��������� �� ������ - $domen/cabinet_ads?ads=txtob \n\n";
		$message.= "���� �� �������������������� ������������, �� �� ������ �������� ����� ������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=txt_ob \n\n";

		$message.= "���������� ��� �� ������������� ������ �������.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>��� �������������� �������������� ���������, �������� �� ���� �� �����������.</i>\n";
		$message.= "<i>� ���������, ������������� �����</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_txt` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_frm` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "������������!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "���� ������ ������ �� ������ ������� ��������� ���� ����� �� ����� $domen\n";
		$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
		$message.= "URL �����: ".$row["url"]."\n";
		$message.= "��������: ".$row["description"]."\n\n";
		$message.= "���� �� ������������������ ������������ � ��������� ������� �� ������ ��������, �� �� ������ �������� ���� ��������� �������� ����� ������� �������������, ��� ����� ��������� �� ������ - $domen/cabinet_ads?ads=frmlink \n\n";
		$message.= "���� �� �������������������� ������������, �� �� ������ �������� ����� ������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=frm_links \n\n";

		$message.= "���������� ��� �� ������������� ������ �������.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>��� �������������� �������������� ���������, �������� �� ���� �� �����������.</i>\n";
		$message.= "<i>� ���������, ������������� �����</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_frm` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}


$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "������������!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "���� ��������� ������ ������� ��������� ���� ����� �� ����� $domen\n";
		$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
		$message.= "URL �����: ".$row["url"]."\n\n";
		$message.= "���� �� ������������������ ������������ � ��������� ������� �� ������ ��������, �� �� ������ �������� ���� ��������� �������� ����� ������� �������������, ��� ����� ��������� �� ������ - $domen/cabinet_ads?ads=mails \n\n";
		$message.= "���� �� �������������������� ������������, �� �� ������ �������� ����� ������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=rek_mails \n\n";

		$message.= "���������� ��� �� ������������� ������ �������.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>��� �������������� �������������� ���������, �������� �� ���� �� �����������.</i>\n";
		$message.= "<i>� ���������, ������������� �����</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_mails` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "������������!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "���� �������� �� e-mail ������������� ����� $domen ������� ���������.\n";
		$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
		$message.= "���� ���������: ".$row["subject"]."\n";
		$message.= "����� ���������: ".$row["message"]."\n\n";
		$message.= "�� ������ �������� ����� �������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=sent_emails \n\n";

		$message.= "���������� ��� �� ������������� ������ �������.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>��� �������������� �������������� ���������, �������� �� ���� �� �����������.</i>\n";
		$message.= "<i>� ���������, ������������� �����</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_emails` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_catalog` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "������������!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "���� ������ � �������� ������� ��������� ���� ����� �� ����� $domen\n";
		$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
		$message.= "URL �����: ".$row["url"]."\n";
		$message.= "���������: ".$row["title"]."\n\n";
		$message.= "���� �� ������������������ ������������ � ��������� ������� �� ������ ��������, �� �� ������ �������� ���� ��������� �������� ����� ������� �������������, ��� ����� ��������� �� ������ - $domen/cabinet_ads?ads=catalog \n\n";
		$message.= "���� �� �������������������� ������������, �� �� ������ �������� ����� ������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=catalog \n\n";

		$message.= "���������� ��� �� ������������� ������ �������.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>��� �������������� �������������� ���������, �������� �� ���� �� �����������.</i>\n";
		$message.= "<i>� ���������, ������������� �����</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_catalog` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_beg_stroka` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "������������!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "���� ������ � ������� ������ ������� ��������� ���� ����� �� ����� $domen\n";
		$message.= "ID ��������� ��������: <b>".$row["id"]."</b>\n";
		$message.= "URL �����: ".$row["url"]."\n";
		$message.= "��������: ".$row["description"]."\n\n";
		$message.= "���� �� ������������������ ������������ � ��������� ������� �� ������ ��������, �� �� ������ �������� ���� ��������� �������� ����� ������� �������������, ��� ����� ��������� �� ������ - $domen/cabinet_ads?ads=begstroka \n\n";
		$message.= "���� �� �������������������� ������������, �� �� ������ �������� ����� ������� �� �������� ������, ��� ����� ��������� �� ������ - $domen/advertise.php?ads=beg_stroka \n\n";

		$message.= "���������� ��� �� ������������� ������ �������.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>��� �������������� �������������� ���������, �������� �� ���� �� �����������.</i>\n";
		$message.= "<i>� ���������, ������������� �����</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_beg_stroka` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

@mysql_close();
?>