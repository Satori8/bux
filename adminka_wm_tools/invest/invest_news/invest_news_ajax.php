<?php
@session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
require(ROOT_DIR."/config.php");
require(ROOT_DIR."/funciones.php");
sleep(0);

function limpiarez($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_replace("'", "", $mensaje);
	$mensaje = str_replace("`", "", $mensaje);
//	$mensaje = str_replace('"', "&#34;", $mensaje);
	$mensaje = str_replace("?", "&#063;", $mensaje);
	$mensaje = str_replace("$", "&#036;", $mensaje);

	$mensaje = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mensaje);
	$mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);
	$mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);

	$mensaje = mysql_real_escape_string(trim($mensaje));
//	$mensaje = str_replace("\\", "", $mensaje);

	$mensaje = iconv("UTF-8", "CP1251//TRANSLIT", htmlspecialchars(trim($mensaje), NULL, "CP1251"));
	$mensaje = htmlspecialchars(trim($mensaje), NULL, "CP1251");
	$mensaje = str_replace("  ", " ", $mensaje);
	$mensaje = str_replace("&amp amp ", "&", $mensaje);
	$mensaje = str_replace("&amp;amp;", "&", $mensaje);
	$mensaje = str_replace("&&", "&", $mensaje);
	$mensaje = str_replace("http://http://", "http://", $mensaje);
	$mensaje = str_replace("https://https://", "https://", $mensaje);
	$mensaje = str_replace("&#063;", "?", $mensaje);
	$mensaje = str_replace("&amp;", "&", $mensaje);

	return $mensaje;
}

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	if(isset($_SESSION["userLog_a"]) && isset($_SESSION["userPas_a"])) {
		$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;
		$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", limpiar($_POST["op"])) ) ? limpiar($_POST["op"]) : false;
		$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval(limpiar($_POST["id"])))) ? intval(limpiar($_POST["id"])) : false;
		$laip = getRealIP();

		if($option=="AddNews") {
			$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 60) : false;
			$description = (isset($_POST["description"])) ? limpiarez($_POST["description"]) : false;
			if (get_magic_quotes_gpc()) { $description = stripslashes($description); }

			if($title==false) {
				exit("�� �� ������� ��������� �������!");
			}elseif($description==false) {
				exit("�� �� ������� ����� �������!");
			}else{
				mysql_query("INSERT INTO `tb_invest_news` (`id`,`title`,`description`,`time`) 
				VALUES(NULL, '$title','$description','".time()."')") or die(mysql_error());

				exit("OK");
			}

		}elseif($option=="ShowForm") {
			if(!DEFINED("LoadForm")) DEFINE("LoadForm", true);

			$sql = mysql_query("SELECT * FROM `tb_invest_news` WHERE `id`='$id'");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_array($sql);

				include("invest_news_form.php");
			}else{
				exit("ERRORNOID");
			}
			exit();

		}elseif($option=="Save") {
			$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 60) : false;
			$description = (isset($_POST["description"])) ? limpiarez($_POST["description"]) : false;
			if (get_magic_quotes_gpc()) { $description = stripslashes($description); }

			if($title==false) {
				exit("�� �� ������� ��������� �������!");
			}elseif($description==false) {
				exit("�� �� ������� ����� �������!");
			}else{
				mysql_query("UPDATE `tb_invest_news` SET `title`='$title', `description`='$description' WHERE `id`='$id'") or die(mysql_error());

				exit("OK");
			}

		}elseif($option=="DelNews") {
			$sql = mysql_query("SELECT `id` FROM `tb_invest_news` WHERE `id`='$id'");
			if(mysql_num_rows($sql)>0) {
				mysql_query("DELETE FROM `tb_invest_news` WHERE `id`='$id'") or die("ERROR");

				exit("OK");
			}else{
				exit("������� �� �������!");
			}

		}else{
			exit("�� ������� ���������� ������!");
		}
	}else{
		exit("���������� ��������������.");
	}
}else{
	exit("�� ������� ���������� ������.");
}

?>