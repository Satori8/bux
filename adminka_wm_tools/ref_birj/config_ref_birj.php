<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>��������� ����� ���������</b></h3>';


if(count($_POST)>0) {
	$ref_birj_comis_proc = isset($_POST["ref_birj_comis_proc"]) ? number_format(abs(str_replace(",", ".", trim($_POST["ref_birj_comis_proc"]))), 0, ".", "") : false;
	$ref_birj_comis_min = isset($_POST["ref_birj_comis_min"]) ? number_format(abs(str_replace(",", ".", trim($_POST["ref_birj_comis_min"]))), 2, ".", "") : false;

	mysql_query("UPDATE `tb_config` SET `price`='$ref_birj_comis_proc' WHERE `item`='ref_birj_comis_proc'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$ref_birj_comis_min' WHERE `item`='ref_birj_comis_min'") or die(mysql_error());

	echo '<span id="info-msg" class="msg-ok">��������� ������� ���������!</span>';

	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");
		}, 50);
		HideMsg("info-msg", 1500);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_birj_comis_proc'");
$ref_birj_comis_proc = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_birj_comis_min'");
$ref_birj_comis_min = number_format(mysql_result($sql,0,0), 2, ".", "");


echo '<form method="post" action="" id="newform">';
echo '<table class="tables" style="margin-top:1px; width:auto;">';
	echo '<thead><tr><th width="270px">��������</th><th>��������</th></tr></thead>';
	echo '<tr>';
		echo '<td><b>�������� �������</b>, %</td>';
		echo '<td align="center"><input type="text" name="ref_birj_comis_proc" value="'.$ref_birj_comis_proc.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>����������� �������� �������</b>, ���.</td>';
		echo '<td align="center"><input type="text" name="ref_birj_comis_min" value="'.$ref_birj_comis_min.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr><td align="center" colspan="2"><input type="submit" value="C�������� ���������" class="sub-blue160"></tr>';
echo '</table>';
echo '</form><br><br>';

?>