<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>��������� ����� "�������� ����"</b></h3>';

if(count($_POST)>0 && isset($_POST["site_action_config"])) {
	$site_action_status = ( isset($_POST["site_action_status"]) && preg_match("|^[0-1]{1}$|", trim($_POST["site_action_status"])) ) ? intval(trim($_POST["site_action_status"])) : 0;
	$site_action_paymin = ( isset($_POST["site_action_paymin"]) ) ? floatval(abs(trim($_POST["site_action_paymin"]))) : 0;
	$site_action_payads = ( isset($_POST["site_action_payads"]) ) ? floatval(abs(trim($_POST["site_action_payads"]))) : 0;
	$site_action_addreit = ( isset($_POST["site_action_addreit"]) ) ? intval(abs(trim($_POST["site_action_addreit"]))) : 0;
	$site_action_status_ref = ( isset($_POST["site_action_status_ref"]) ) ? intval(abs(trim($_POST["site_action_status_ref"]))) : 0;
	$site_action_date_ref = ( isset($_POST["site_action_date_ref"]) ) ? intval(abs(trim($_POST["site_action_date_ref"]))) : 0;

	mysql_query("UPDATE `tb_config` SET `price`='$site_action_status' WHERE `item`='site_action_status' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$site_action_paymin' WHERE `item`='site_action_paymin' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$site_action_payads' WHERE `item`='site_action_payads' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$site_action_addreit' WHERE `item`='site_action_addreit' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$site_action_status_ref' WHERE `item`='site_action_status_ref' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$site_action_date_ref' WHERE `item`='site_action_date_ref' AND `howmany`='1'") or die(mysql_error());

/*
	echo '<span id="info-msg" class="msg-ok" style="margin-bottom:0;">��������� ������� ���������.</span>';

	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'");
		}, 150);
		HideMsg("info-msg", 2000);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="2;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'"></noscript>';
*/
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_status' AND `howmany`='1'");
$site_action_status = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_paymin' AND `howmany`='1'");
$site_action_paymin = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_payads' AND `howmany`='1'");
$site_action_payads = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_addreit' AND `howmany`='1'");
$site_action_addreit = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_status_ref' AND `howmany`='1'");
$site_action_status_ref = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_date_ref' AND `howmany`='1'");
$site_action_date_ref = number_format(mysql_result($sql,0,0), 0, ".", "");


echo '<form method="POST" action="" id="newform" autocomplete="off">';
echo '<input type="hidden" name="site_action_config" value="1">';
echo '<table class="tables" style="width:600px; margin-top:1px;">';
echo '<thead>';
echo '<tr>';
	echo '<th>��������</th>';
	echo '<th width="100">��������</th>';
	echo '<th width="30"></th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
echo '<tr>';
	echo '<td align="left"><b>������ �����</b></td>';
	echo '<td align="center">';
		echo '<select name="site_action_status" class="ok12" style="width:124px; text-align:center;">';
			echo '<option value="0" '.($site_action_status == 0 ? 'selected="selected"' : false).'>���������</option>';
			echo '<option value="1" '.($site_action_status == 1 ? 'selected="selected"' : false).'>�������</option>';
		echo '</select>';
	echo '</td>';
	echo '<td align="center"></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>����������� ����������� ����� �� ������� ��� ������� � �����</b></td>';
	echo '<td align="center"><input type="text" name="site_action_paymin" value="'.$site_action_paymin.'" class="ok12" style="text-align:center; width:120px;"></td>';
	echo '<td align="center">���.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>������ 1 �������� �� ������ �����������</b></td>';
	echo '<td align="center"><input type="text" name="site_action_payads" value="'.$site_action_payads.'" class="ok12" style="text-align:center; width:120px;"></td>';
	echo '<td align="center">���.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>���������� �������� �� ������ ����������� �������</b></td>';
	echo '<td align="center"><input type="text" name="site_action_addreit" value="'.$site_action_addreit.'" class="ok12" style="text-align:center; width:120px;"></td>';
	echo '<td align="center">������</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>�������� ��������� �� �������� �� ����</b></td>';
	echo '<td align="center">';
		echo '<select name="site_action_status_ref" class="ok12" style="width:124px; text-align:center;">';
			$sql_rang = mysql_query("SELECT `id`,`rang` FROM `tb_config_rang` ORDER BY `id` ASC") or die(mysql_error());
			while ($row_rang = mysql_fetch_assoc($sql_rang)) {
				echo '<option value="'.$row_rang["id"].'" '.( $row_rang["id"] == $site_action_status_ref ? 'selected="selected"' : false).'>'.$row_rang["rang"].'</option>';
			}
		echo '</select>';
	echo '</td>';
	echo '<td align="center"></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>�������� ��������� ������� ������� � ������� �� ������</b></td>';
	echo '<td align="center"><input type="text" name="site_action_date_ref" value="'.$site_action_date_ref.'" class="ok12" style="text-align:center; width:120px;"></td>';
	echo '<td align="center">���� �����</td>';
echo '</tr>';
echo '<tr align="center"><td colspan="3"><input type="submit" value="C�������� ���������" class="sub-blue160"></td></tr>';
echo '</tbody>';
echo '</table>';
echo '</form>';


if(count($_POST)>0 && isset($_POST["site_action_config"])) {
	echo '<span id="info-msg" class="msg-ok" style="margin-bottom:0;">��������� ������� ���������.</span>';

	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'");
		}, 150);
		HideMsg("info-msg", 2000);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="2;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'"></noscript>';
}

?>