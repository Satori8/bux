<?php
session_start();
header("Content-type: text/html; charset=windows-1251");
error_reporting (E_ALL);

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {

	require("".$_SERVER['DOCUMENT_ROOT']."/config.php");
	require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");

	$id_t = (isset($_GET["th"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["th"]))) ? intval(limpiar(trim($_GET["th"]))) : false;
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

	$sql = mysql_query("SELECT `forum_status` FROM `tb_users` WHERE `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_row($sql);

		$user_f_status = $row["0"];

		$sql_s = mysql_query("SELECT `id` FROM `tb_forum_s` WHERE `id_status`='$user_f_status' AND `open_theme`='1'");
		if(mysql_num_rows($sql_s)>0) {

			mysql_query("UPDATE `tb_forum_t` SET `status`='0' WHERE `id`='$id_t'") or die(mysql_error());

			echo '<script type="text/javascript">location.replace("'.getenv("HTTP_REFERER").'");</script>';
			echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.getenv("HTTP_REFERER").'"></noscript>';
		}
	}
}

echo '<script type="text/javascript">location.replace("'.getenv("HTTP_REFERER").'");</script>';
echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.getenv("HTTP_REFERER").'"></noscript>';
?>
