<?php
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>��������� ���������� ������� � ������� ������</b></h1>';

if(count($_POST)>0) {
	$cena_pay_row = isset($_POST["cena_pay_row"]) ? number_format(abs(str_replace(",", ".", trim($_POST["cena_pay_row"]))), 2, ".", "") : false;

	mysql_query("UPDATE `tb_config` SET `price`='$cena_pay_row' WHERE `item`='cena_pay_row' AND `howmany`='1'") or die(mysql_error());

	echo '<span id="info-msg" class="msg-ok">��������� ������� ���������!</span>';

	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");
		}, 1200);
		HideMsg("info-msg", 1210);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_pay_row'");
$cena_pay_row = number_format(mysql_result($sql,0,0), 2, ".", "");

echo '<form method="post" action="" id="newform">';
echo '<table class="tables" style="width:500px; margin:0px; padding:0px;">';
echo '<thead>';
	echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
echo '</thead>';
echo '<tbody>';
	echo '<tr align="left"><td><b>��������� ����������</b>, [���.]</td><td><input type="text" class="ok12" name="cena_pay_row" value="'.$cena_pay_row.'" style="text-align:center;"></td></tr>';
	echo '<tr align="center"><td colspan="2"><input type="submit" value="C�������� ���������" class="sub-blue160"></td></tr>';
echo '</tbody>';
echo '</table>';
echo '</form>';

?>