<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>��������� ������</b></h3>';

if(count($_POST)>0) {
	$site_autopay_wm = intval(trim($_POST["site_autopay_wm"]));
	$pay_min = p_floor(floatval($_POST["pay_min"]), 2);
	$pay_max = p_floor(floatval($_POST["pay_max"]), 2);
	$pay_comis1 = p_floor(floatval($_POST["pay_comis1"]), 2);
	$pay_comis2 = p_floor(floatval($_POST["pay_comis2"]), 2);
	if($pay_min<=($pay_comis2+0.01) ) $pay_min = ($pay_comis2 + 0.02);

	$site_autopay_lp = intval(trim($_POST["site_autopay_lp"]));
	$pay_min_lp = p_floor(floatval($_POST["pay_min_lp"]), 2);
	$pay_max_lp = p_floor(floatval($_POST["pay_max_lp"]), 2);
	$pay_comis1_lp = p_floor(floatval($_POST["pay_comis1_lp"]), 2);
	$pay_comis2_lp = p_floor(floatval($_POST["pay_comis2_lp"]), 2);

	$lp_pay_min = round((0.03 + $pay_comis2_lp + 0.05*($pay_comis1_lp+100)/100), 2);
	if($pay_min_lp<$lp_pay_min) $pay_min_lp = $lp_pay_min;

	$site_autopay_payeer = intval(trim($_POST["site_autopay_payeer"]));
	$pay_min_payeer = p_floor(floatval($_POST["pay_min_payeer"]), 2);
	$pay_max_payeer = p_floor(floatval($_POST["pay_max_payeer"]), 2);
	$pay_comis_payeer = p_floor(floatval($_POST["pay_comis_payeer"]), 2);

	$site_autopay_qw = intval(trim($_POST["site_autopay_qw"]));
	$pay_min_qw = p_floor(floatval($_POST["pay_min_qw"]), 2);
	$pay_max_qw = p_floor(floatval($_POST["pay_max_qw"]), 2);
	$pay_comis_qw = p_floor(floatval($_POST["pay_comis_qw"]), 2);

	$site_autopay_ym = intval(trim($_POST["site_autopay_ym"]));
	$pay_min_ym = p_floor(floatval($_POST["pay_min_ym"]), 2);
	$pay_max_ym = p_floor(floatval($_POST["pay_max_ym"]), 2);
	$pay_comis_ym = p_floor(floatval($_POST["pay_comis_ym"]), 2);

	$site_autopay_pm = intval(trim($_POST["site_autopay_pm"]));
	$pay_min_pm = p_floor(floatval($_POST["pay_min_pm"]), 2);
	$pay_max_pm = p_floor(floatval($_POST["pay_max_pm"]), 2);
	$pay_comis_pm = p_floor(floatval($_POST["pay_comis_pm"]), 2);

	mysql_query("UPDATE `tb_config` SET `price`='$site_autopay_wm' WHERE `item`='site_autopay_wm' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_min' WHERE `item`='pay_min' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_max' WHERE `item`='pay_max' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_comis1' WHERE `item`='pay_comis' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_comis2' WHERE `item`='pay_comis' AND `howmany`='2'") or die(mysql_error());

	mysql_query("UPDATE `tb_config` SET `price`='$site_autopay_lp' WHERE `item`='site_autopay_lp' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_min_lp' WHERE `item`='pay_min_lp' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_max_lp' WHERE `item`='pay_max_lp' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_comis1_lp' WHERE `item`='pay_comis_lp' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_comis2_lp' WHERE `item`='pay_comis_lp' AND `howmany`='2'") or die(mysql_error());

	mysql_query("UPDATE `tb_config` SET `price`='$pay_min_payeer' WHERE `item`='pay_min_payeer' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_max_payeer' WHERE `item`='pay_max_payeer' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$site_autopay_payeer' WHERE `item`='site_autopay_payeer' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_comis_payeer' WHERE `item`='pay_comis_payeer' AND `howmany`='1'") or die(mysql_error());

	mysql_query("UPDATE `tb_config` SET `price`='$pay_min_qw' WHERE `item`='pay_min_qw' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_max_qw' WHERE `item`='pay_max_qw' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$site_autopay_qw' WHERE `item`='site_autopay_qw' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_comis_qw' WHERE `item`='pay_comis_qw' AND `howmany`='1'") or die(mysql_error());

	mysql_query("UPDATE `tb_config` SET `price`='$pay_min_ym' WHERE `item`='pay_min_ym' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_max_ym' WHERE `item`='pay_max_ym' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$site_autopay_ym' WHERE `item`='site_autopay_ym' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_comis_ym' WHERE `item`='pay_comis_ym' AND `howmany`='1'") or die(mysql_error());

	mysql_query("UPDATE `tb_config` SET `price`='$pay_min_pm' WHERE `item`='pay_min_pm' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_max_pm' WHERE `item`='pay_max_pm' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$site_autopay_pm' WHERE `item`='site_autopay_pm' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$pay_comis_pm' WHERE `item`='pay_comis_pm' AND `howmany`='1'") or die(mysql_error());

	echo '<span class="msg-ok">��������� ������� ���������.</span>';
	echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 2000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="2;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

	$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_autopay_wm' AND `howmany`='1'");
	$site_autopay_wm = mysql_result($sql,0,0);

	$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_autopay_lp' AND `howmany`='1'");
	$site_autopay_lp = mysql_result($sql,0,0);

	$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_autopay_payeer' AND `howmany`='1'");
	$site_autopay_payeer = mysql_result($sql,0,0);

	$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_autopay_qw' AND `howmany`='1'");
	$site_autopay_qw = mysql_result($sql,0,0);

	$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_autopay_ym' AND `howmany`='1'");
	$site_autopay_ym = mysql_result($sql,0,0);

	$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_autopay_pm' AND `howmany`='1'");
	$site_autopay_pm = mysql_result($sql,0,0);

?>

<form method="post" action="" id="newform">
<table width="100%">

<tr><td width="50%">
	<br><b>��������� ������ �� WebMoney</b><br>
	<table>
	<tr><th width="200" align="left">������� �� WebMoney:</th> <td>
		<select style="width:125px;" name="site_autopay_wm">
			<option value="0" <?php if($site_autopay_wm=="0") echo "selected";?>>���������</option>
			<option value="1" <?php if($site_autopay_wm=="1") echo "selected";?>>��������</option>
			<option value="2" <?php if($site_autopay_wm=="2") echo "selected";?>>���</option>
		</select></td></tr>
	<tr><th width="200" align="left">������� � �������:</th><td><input type="text" class="ok12" name="pay_min" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_min' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> ���.</td></tr>
	<tr><th width="200" align="left">�������� � ������� (��� ����������):</th><td><input type="text" class="ok12" name="pay_max" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_max' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> ���.</td></tr>
	<tr><th width="200" align="left">������� � ���������:</th><td><input type="text" class="ok12" name="pay_comis1" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_comis' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> %</td></tr>
	<tr><th width="200" align="left">�������������� �������:</th><td><input type="text" class="ok12" name="pay_comis2" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_comis' AND `howmany`='2'"); echo mysql_result($sql,0,0); ?>"> ���.</td></tr>
	</table>
</td><td>
	<br><b>��������� ������ �� LiqPay</b><br>
	<table>
	<tr><th width="200" align="left">������� �� LiqPay:</th> <td><select style="width:125px;" name="site_autopay_lp"><option value="0" <?php if($site_autopay_lp=="0") echo "selected";?>>���������</option><option value="1" <?php if($site_autopay_lp=="1") echo "selected";?>>��������</option></select></td></tr>
	<tr><th width="200" align="left">������� � �������:</th><td><input type="text" class="ok12" name="pay_min_lp" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_min_lp' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> ���.</td></tr>
	<tr><th width="200" align="left">�������� � ������� (��� ����������):</th><td><input type="text" class="ok12" name="pay_max_lp" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_max_lp' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> ���.</td></tr>
	<tr><th width="200" align="left">������� � ���������:</th><td><input type="text" class="ok12" name="pay_comis1_lp" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_comis_lp' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> %</td></tr>
	<tr><th width="200" align="left">�������������� �������:</th><td><input type="text" class="ok12" name="pay_comis2_lp" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_comis_lp' AND `howmany`='2'"); echo mysql_result($sql,0,0); ?>"> ���.</td></tr>
	</table>
</td></tr>

<tr><td width="50%">
	<br><b>��������� ������ �� Payeer</b><br>
	<table>
	<tr><th width="200" align="left">������� �� Payeer:</th> <td><select style="width:125px;" name="site_autopay_payeer"><option value="0" <?php if($site_autopay_payeer=="0") echo "selected";?>>���������</option><option value="1" <?php if($site_autopay_payeer=="1") echo "selected";?>>��������</option></select></td></tr>
	<tr><th width="200" align="left">������� � �������:</th><td><input type="text" class="ok12" name="pay_min_payeer" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_min_payeer' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> ���.</td></tr>
	<tr><th width="200" align="left">�������� � ������� (��� ����������):</th><td><input type="text" class="ok12" name="pay_max_payeer" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_max_payeer' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> ���.</td></tr>
	<tr><th width="200" align="left">������� � ���������:</th><td><input type="text" class="ok12" name="pay_comis_payeer" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_comis_payeer' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> %</td></tr>
	</table>
</td><td>
	<br><b>��������� ������ �� QIWI �������</b><br>
	<table>
	<tr><th width="200" align="left">������� �� QIWI �������:</th> <td><select style="width:125px;" name="site_autopay_qw"><option value="0" <?php if($site_autopay_qw=="0") echo "selected";?>>���������</option><option value="1" <?php if($site_autopay_qw=="1") echo "selected";?>>��������</option></select></td></tr>
	<tr><th width="200" align="left">������� � �������:</th><td><input type="text" class="ok12" name="pay_min_qw" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_min_qw' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> ���.</td></tr>
	<tr><th width="200" align="left">�������� � ������� (��� ����������):</th><td><input type="text" class="ok12" name="pay_max_qw" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_max_qw' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> ���.</td></tr>
	<tr><th width="200" align="left">������� � ���������:</th><td><input type="text" class="ok12" name="pay_comis_qw" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_comis_qw' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> %</td></tr>
	</table>
</td></tr>
<tr><td width="50%">
	<br><b>��������� ������ �� ������.������</b><br>
	<table>
	<tr><th width="200" align="left">������� �� ������.������:</th> <td><select style="width:125px;" name="site_autopay_ym"><option value="0" <?php if($site_autopay_ym=="0") echo "selected";?>>���������</option><option value="1" <?php if($site_autopay_ym=="1") echo "selected";?>>��������</option></select></td></tr>
	<tr><th width="200" align="left">������� � �������:</th><td><input type="text" class="ok12" name="pay_min_ym" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_min_ym' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> ���.</td></tr>
	<tr><th width="200" align="left">�������� � ������� (��� ����������):</th><td><input type="text" class="ok12" name="pay_max_ym" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_max_ym' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> ���.</td></tr>
	<tr><th width="200" align="left">������� � ���������:</th><td><input type="text" class="ok12" name="pay_comis_ym" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_comis_ym' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> %</td></tr>
	</table>
</td><td>
	<br><b>��������� ������ �� PerfectMoney</b><br>
	<table>
	<tr><th width="200" align="left">������� �� PerfectMoney:</th> <td><select style="width:125px;" name="site_autopay_pm"><option value="0" <?php if($site_autopay_pm=="0") echo "selected";?>>���������</option><option value="1" <?php if($site_autopay_pm=="1") echo "selected";?>>��������</option></select></td></tr>
	<tr><th width="200" align="left">������� � �������:</th><td><input type="text" class="ok12" name="pay_min_pm" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_min_pm' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> ���.</td></tr>
	<tr><th width="200" align="left">�������� � ������� (��� ����������):</th><td><input type="text" class="ok12" name="pay_max_pm" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_max_pm' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> ���.</td></tr>
	<tr><th width="200" align="left">������� � ���������:</th><td><input type="text" class="ok12" name="pay_comis_pm" style="text-align:right;" value="<? $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_comis_pm' AND `howmany`='1'"); echo mysql_result($sql,0,0); ?>"> %</td></tr>
	</table>
</td></tr>
</table>
<table width="100%">
	<tr align="center"><td align="center"><input type="submit" value="��������� ���������" class="sub-blue160"></td></tr>
</table>
</form>