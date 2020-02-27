<?php
session_start();
header("Content-type: text/html; charset=windows-1251");
error_reporting (E_ALL);

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {

	require("".$_SERVER['DOCUMENT_ROOT']."/config.php");
	require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");

	$id_p = (isset($_GET["post"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["post"]))) ? intval(limpiar(trim($_GET["post"]))) : false;
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

	$sql = mysql_query("SELECT `forum_status` FROM `tb_users` WHERE `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_row($sql);

		$user_f_status = $row["0"];

		$sql_s = mysql_query("SELECT `id` FROM `tb_forum_s` WHERE `id_status`='$user_f_status' AND `moder_post`='1'");
		if(mysql_num_rows($sql_s)>0) {

			$sql_p = mysql_query("SELECT * FROM `tb_forum_p` WHERE `id`='$id_p'");
			if(mysql_num_rows($sql_p)>0) {
				$row_p = mysql_fetch_array($sql_p);
				$id_p = $row_p["id"];
				$id_t = $row_p["ident_t"];
				$id_pr = $row_p["ident_pr"];

				mysql_query("UPDATE `tb_forum_p` SET `moder`='1' WHERE `id`='$id_p'") or die(mysql_error());
				mysql_query("UPDATE `tb_forum_t` SET `moder`=`moder`-'1' WHERE `id`='$id_t'") or die(mysql_error());
				mysql_query("UPDATE `tb_forum_pr` SET `moder`=`moder`-'1' WHERE `id`='$id_pr'") or die(mysql_error());

				echo '<script type="text/javascript"> showmod('.$id_p.'); </script>';
			}
		}
	}
}

?>
