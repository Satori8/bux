<?php
$pagetitle = "����� �������";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");

function count_text($count, $text1, $text2, $text3) {
	if($count>0) {
		if( ($count>=10 && $count<=20) | (substr($count, -2, 2)>=10 && substr($count, -2, 2)<=20) ) {
			return "<b>$count</b> $text3";
		}else{
			switch(substr($count, -1, 1)){
				case 1: return "<b>$count</b> $text1"; break;
				case 2: case 3: case 4: return "<b>$count</b> $text2"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: return "<b>$count</b> $text3"; break;
			}
		}
	}
}

if(!isset($site_action_status)) {
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_status' AND `howmany`='1'");
	$site_action_status = number_format(mysql_result($sql,0,0), 0, ".", "");
}

if($site_action_status==1) {
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_paymin' AND `howmany`='1'");
	$site_action_paymin = number_format(mysql_result($sql,0,0), 2, ".", "");

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_payads' AND `howmany`='1'");
	$site_action_payads = number_format(mysql_result($sql,0,0), 0, ".", "");

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_addreit' AND `howmany`='1'");
	$site_action_addreit = number_format(mysql_result($sql,0,0), 0, ".", "");

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_status_ref' AND `howmany`='1'");
	$site_action_status_ref = number_format(mysql_result($sql,0,0), 0, ".", "");

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_date_ref' AND `howmany`='1'");
	$site_action_date_ref = number_format(mysql_result($sql,0,0), 0, ".", "");

	$sql = mysql_query("SELECT `r_ot` FROM `tb_config_rang` WHERE `id`='$site_action_status_ref'");
	$ref_reit_ot = number_format(mysql_result($sql,0,0), 0, ".", "");

	$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` WHERE `id`='$site_action_status_ref'") or die(mysql_error());
	$row_rang = mysql_fetch_assoc($sql_rang);
	$ref_rang = $row_rang["rang"];

	$sql_ref = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='Admin' AND `user_status`='0' AND `not_get_ref_a`='0' AND `ban_date`='0' AND `reiting`>='$ref_reit_ot' AND `lastlogdate2`>='".(time()-$site_action_date_ref*24*60*60)."'");
	$cnt_ref = number_format(mysql_numrows($sql_ref), 0, ".", "");

}


if($site_action_status==1) echo '<h3 class="sp" style="font-size:17px;">����� "�������� ����"</h3>';
echo '<div style="margin:0 auto; background:#F0F8FF; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:justify; color:'.($site_action_status!=1 ? "#E32636" : "#114C5B").'; font-size:13px;">';
	if($site_action_status!=1) {
		echo '<div align="center" style="font-weight:bold; line-height:20px;">�������� ����� ���.<br>�������� �����, �� ������ ��� ����� ����!!!</div>';
	}else{
		echo '<div align="center" style="margin-bottom:10px; color:#00649E; font-size:15px;">��������� ������������, <b style="color:green; font-size:13px;">'.strtoupper(array_shift(explode(".", $_SERVER["HTTP_HOST"]))).'</b> �������� ������ ����� - "<b>�������� ����</b>"</div>';
		echo '<b>������� ���������� �����:</b> ���������� �������� ����� ������� �� ������� <b>'.strtoupper(array_shift(explode(".", $_SERVER["HTTP_HOST"]))).'</b> (����� ������� � ������������ ������) �� ����� �� ����� <b>'.$site_action_paymin.'</b> ���. ������������ ����� �� ����������. ';
		echo '������� ����� ���������� ��� � ���������� ����� ��� � � ����� �������� ��������� �������. � ����� ����� ������� ����� ������������������ ������������ �������.<br><br>';

		echo '�������� ����� �������������� �� ������� <b>1</b> ������� �� ������ ������ <b>'.$site_action_payads.'</b> ���. ����������� �� ������ �������.<br>';
		echo '��������, ��� ������ ������� �� �����:<br>';
		echo '<b>'.number_format(($site_action_paymin+$site_action_payads*1),2,".","").'</b> ���. �� �������� - '.count_text(floor((($site_action_paymin+$site_action_payads)/$site_action_payads)), "�������", "��������", "���������").';<br>';
		echo '<b>'.number_format(($site_action_paymin+$site_action_payads*2),2,".","").'</b> ���. - '.count_text(floor((($site_action_paymin+$site_action_payads*2)/$site_action_payads)), "�������", "��������", "���������").';<br>';
		echo '<b>'.number_format(($site_action_paymin+$site_action_payads*5),2,".","").'</b> ���. - '.count_text(floor((($site_action_paymin+$site_action_payads*5)/$site_action_payads)), "�������", "��������", "���������").' ���.<br>';
		echo '����� � ������ ����� �� ������ �������� '.count_text($site_action_addreit, "����", "�����", "������").' �������� �� ������ ������ <b>'.$site_action_paymin.'</b> ���. ����������� �� ������ �������.<br><br>';
		echo '� ������ ����� �������� �������� �������� �� �������� <b>'.mb_strtoupper($ref_rang, "CP1251").'</b> � ����. ��������� ���������� ��������� �������� ������� ������� � ������� �� ������� '.count_text($site_action_date_ref, "���", "����", "����").' �����. ';
		echo '<u>�������� �������� �������� �� ������ ��������� ��������������.</u> ��������� ��������� � �������� ������� �� ����������.<br><br>';

		echo '<span align="center" class="warning-info" style="font-weight:normal; line-height:25px; margin-bottom:10px;">';
			echo '��������! �������� �������� ������������� ����� ������ ������� �������� �������� �����.<br>';
			echo '<b>���������� ���������� ���������� ���������, ��� ������ �������.</b><br>';
			echo '���������� ���������� ���������, ������������ � ������ �����: <span style="font-size:14px; font-weight:bold;">'.$cnt_ref.'</span>';
		echo '</span>';

		echo '<span align="center" class="warning-info" style="font-weight:normal; line-height:17px;">';
			echo '�����! ��� ������ ������� � ������� ��������� ������, ����� � ������ ������� ���������� �����������<br><b>������ ����� �����������</b><br>�� �������.';
		echo '</span>';
	}
echo '</div>';

include(ROOT_DIR."/footer.php");
?>