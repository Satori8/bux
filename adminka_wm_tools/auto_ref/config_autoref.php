<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>��������� ��������� ������� �����������</b></h3>';

if(isset($_POST["autoref"])) {

	$autoref = (isset($_POST["autoref"])) ? abs(floatval(str_replace(",",".",trim($_POST["autoref"])))) : false;
	$autoref = p_floor($autoref,2);

	mysql_query("UPDATE `tb_config` SET `price`='$autoref' WHERE `item`='autoref' AND `howmany`='1'") or die(mysql_error());

	echo '<span class="msg-ok">��������� ������� ���������.</span>';
	echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}


?>

<form method="post" action="" id="newform">
<table>
	<tr><th width="200">���� �� 1 ������:</th><td><input type="text" class="ok12" name="autoref" style="text-align:right;" value="<?php $sql = mysql_query("SELECT price FROM tb_config WHERE item='autoref' and howmany='1'"); echo mysql_result($sql,0,0);?>"> ���.</td></tr>
	<tr><td></td><td><input type="submit" value="��������� ���������" class="sub-blue160"></td></tr>
</table>

</form>