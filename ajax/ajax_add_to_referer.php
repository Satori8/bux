<?php
@session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
require("".$_SERVER['DOCUMENT_ROOT']."/config.php");
require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");
sleep(0);

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {

	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;
	$referer_id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval($_POST["id"]))) ? intval($_POST["id"]) : false;
	$referer_login = (isset($_POST["user"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_POST["user"]))) ? htmlspecialchars(stripslashes(trim($_POST["user"]))) : false;


	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && $username!=false && $referer_id!=false && $referer_login!=false) {

		$sql_user = mysql_query("SELECT `id`,`username`,`referer` FROM `tb_users` WHERE `username`='$username'");
		if(mysql_num_rows($sql_user)>0) {
			$row_user = mysql_fetch_array($sql_user);
			$user_id = $row_user["id"];
			$user_name = $row_user["username"];
			$user_referer = $row_user["referer"];

			if($user_referer!=false) {
				exit("У Вас уже есть реферер!");
			}

			$sql_check = mysql_query("SELECT `id`,`username`,`referer`,`referer2`,`ref_back_all` FROM `tb_users` WHERE `id`='$referer_id' AND `username`='$referer_login'");
			if(mysql_num_rows($sql_check)>0) {
				$row_check = mysql_fetch_array($sql_check);
				$referer_id = $row_check["id"];
				$referer_login = $row_check["username"];
				$referer_referer_1 = $row_check["referer"];
				$referer_referer_2 = $row_check["referer2"];
				$referer_ref_back_all = $row_check["ref_back_all"];

				if(strtolower($username)==strtolower($referer_login)) {
					exit("Ошибка!\nВы не можете быть реферером $referer_login");
				}else{
					mysql_query("UPDATE `tb_users` SET `referer`='$referer_login', `referer2`='$referer_referer_1', `referer3`='$referer_referer_2', `ref_back`='$referer_ref_back_all' WHERE `id`='$user_id' AND `referer`=''") or die(mysql_error());
					mysql_query("UPDATE `tb_users` SET `referer2`='$referer_login', `referer3`='$referer_referer_1' WHERE `referer`='$user_name'") or die(mysql_error());
					mysql_query("UPDATE `tb_users` SET `referer3`='$referer_login' WHERE `referer2`='$user_name'") or die(mysql_error());

					exit("Вы успешно стали рефералом пользователя $referer_login");
				}
			}else{
				exit("Ошибка!\n Не удалось обработать запрос!");
			}
		}else{
			exit("Необходимо авторизоваться!");
		}
	}else{
		exit("Необходимо авторизоваться!");
	}
}else{
	exit("Ошибка!\n Не удалось обработать запрос!");
}

?>