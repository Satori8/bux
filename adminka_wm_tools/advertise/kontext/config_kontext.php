<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>Настройки контекстной рекламы</b></h3>';

if(count($_POST)>0) {

	$cena_kontext = floatval(trim($_POST["cena_kontext"]));
	$cena_kontext_color = floatval(trim($_POST["cena_kontext_color"]));
	$cena_kontext_min = floatval(trim($_POST["cena_kontext_min"]));

	mysql_query("UPDATE `tb_config` SET `price`='$cena_kontext' WHERE `item`='cena_kontext'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$cena_kontext_color' WHERE `item`='cena_kontext_color'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$cena_kontext_min' WHERE `item`='cena_kontext_min'") or die(mysql_error());

	echo '<span class="msg-ok">Изменения успешно сохранены!</span>';

	echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_kontext' AND `howmany`='1'");
$cena_kontext = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_kontext_color' AND `howmany`='1'");
$cena_kontext_color = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_kontext_min' AND `howmany`='1'");
$cena_kontext_min = mysql_result($sql,0,0);


echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'">';
echo '<table>';
	echo '<tr><th width="200" align="left">Стоимость 1 перехода:</th><td><input type="text" size="10" name="cena_kontext" style="text-align:right;" value="'.$cena_kontext.'"> руб./переход</td></tr>';
	echo '<tr><th width="200" align="left">Стоимость выделения:</th><td><input type="text" size="10" name="cena_kontext_color" style="text-align:right;" value="'.$cena_kontext_color.'"> руб./переход</td></tr>';
	echo '<tr><th width="200" align="left">Минимальный заказ:</th><td><input type="text" size="10" name="cena_kontext_min" style="text-align:right;" value="'.$cena_kontext_min.'"> переходов</td></tr>';
	echo '<tr><td width="200" align="left">&nbsp</td><td><input type="submit" value="Сохранить изменения" class="sub-blue160"></td></tr>';
echo '</table>';
echo '</form>';

?>