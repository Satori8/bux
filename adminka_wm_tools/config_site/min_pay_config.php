<?php
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>Настройка минимального клика для выплат!</b></h1>';

if(count($_POST)>0) {
	
	$pay_min_click = isset($_POST["pay_min_click"]) ? number_format(abs(str_replace(",", ".", trim($_POST["pay_min_click"]))), 2, ".", "") : false;

	
	mysql_query("UPDATE `tb_config` SET `price`='$pay_min_click' WHERE `item`='pay_min_click' AND `howmany`='1'") or die(mysql_error());

	echo '<span id="info-msg" class="msg-ok">Изменения успешно сохранены!</span>';

	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");
		}, 1200);
		HideMsg("info-msg", 1210);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_min_click'");
$pay_min_click = round(number_format(mysql_result($sql,0,0), 0, ".", ""),2);

echo '<form method="post" action="" id="newform">';
echo '<table class="tables" style="width:600px; margin:0px; padding:0px;">';
echo '<thead>';
	echo '<tr align="center"><th>Параметр</th><th width="125">Значение</th></tr>';
echo '</thead>';
echo '<tbody>';
	echo '<tr align="left"><td><b>Минимум кликов в</b> серфинге для выплат</td><td><input type="text" class="ok12" name="pay_min_click" value="'.$pay_min_click.'" style="text-align:center;"></td></tr>';
	echo '<tr align="center"><td colspan="2"><input type="submit" value="Cохранить изменения" class="sub-blue160"></td></tr>';
echo '</tbody>';
echo '</table>';
echo '</form>';

?>