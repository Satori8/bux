<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>��������� ��������:</b></h3>';

if(isset($_POST)) {
	if(count($_POST)>0) {
		$auc_time = intval(trim($_POST["auc_time"]));
		$auc_time_end_add = intval(trim($_POST["auc_time_end_add"]));
		$auc_time_add = intval(trim($_POST["auc_time_add"]));
		$auc_comis = intval(trim($_POST["auc_comis"]));
		$auc_limit_click_user = intval(trim($_POST["auc_limit_click_user"]));
		$auc_limit_activ_last_user = intval(trim($_POST["auc_limit_activ_last_user"]));
		$auc_max = intval(trim($_POST["auc_max"]));
		$auc_limit_activ_all_user = intval(trim($_POST["auc_limit_activ_all_user"]));


		mysql_query("UPDATE `tb_config` SET `price`='$auc_time' WHERE `item`='auc_time' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$auc_time_end_add' WHERE `item`='auc_time_end_add' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$auc_time_add' WHERE `item`='auc_time_add' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$auc_comis' WHERE `item`='auc_comis' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$auc_limit_click_user' WHERE `item`='auc_limit_click_user' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$auc_limit_activ_last_user' WHERE `item`='auc_limit_activ_last_user' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$auc_max' WHERE `item`='auc_max' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$auc_limit_activ_all_user' WHERE `item`='auc_limit_activ_all_user' AND `howmany`='1'") or die(mysql_error());

		echo '<fieldset class="okp">��������� ������� ���������.</fieldset>';
	}
}else{echo "<br><br>";}
?>

<form method="post" action="">
<table>
	<tr><th width="300" align="left">����� ����������:</th><td><input type="text" size="5" name="auc_time" value="<?php $sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_time' AND howmany='1'"); echo mysql_result($sql,0,0);?>" style="text-align:right;">&nbsp;���.</td></tr>
	<tr><th width="300" align="left">���� ������ ������� ����� ��� ��:</th><td><input type="text" size="5" name="auc_time_end_add" value="<?php $sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_time_end_add' AND howmany='1'"); echo mysql_result($sql,0,0);?>" style="text-align:right;">&nbsp;���. �� ���������</td></tr>
	<tr><th width="300" align="left">����� ���������� �������� ������������� ��:</th><td><input type="text" size="5" name="auc_time_add" value="<?php $sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_time_add' AND howmany='1'"); echo mysql_result($sql,0,0);?>" style="text-align:right;">&nbsp;���.</td></tr>
	<tr><th width="300" align="left">������������ ���-�� �������� ���������:</th><td><input type="text" size="5" name="auc_max" value="<?php $sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_max' AND howmany='1'"); echo mysql_result($sql,0,0);?>" style="text-align:right;">&nbsp;��.</td></tr>
	<tr><th width="300" align="left">�������� �������:</th><td><input type="text" size="5" name="auc_comis" value="<?php $sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_comis' AND howmany='1'"); echo mysql_result($sql,0,0);?>" style="text-align:right;">&nbsp;%</td></tr>
	<tr><th width="300" align="left">����������� �� ����������� ������:</th><td><input type="text" size="5" name="auc_limit_click_user" value="<?php $sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_limit_click_user' AND howmany='1'"); echo mysql_result($sql,0,0);?>" style="text-align:right;">&nbsp;������</td></tr>
	<tr><th width="300" align="left">����������� �� ���� ����������, ���� �� ����� ���:</th><td><input type="text" size="5" name="auc_limit_activ_last_user" value="<?php $sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_limit_activ_last_user' AND howmany='1'"); echo mysql_result($sql,0,0);?>" style="text-align:right;">&nbsp;���� �����</td></tr>
	<tr><th width="300" align="left">���������� � ������� �� �����:</th><td><input type="text" size="5" name="auc_limit_activ_all_user" value="<?php $sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_limit_activ_all_user' AND howmany='1'"); echo mysql_result($sql,0,0);?>" style="text-align:right;">&nbsp;����.</td></tr>
	<tr><td width="300" align="left">&nbsp;</td><td><input type="submit" value="��������� ���������" class="button"></td></tr>
</table>
</form>