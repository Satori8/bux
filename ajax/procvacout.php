<?php
@session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");

sleep(0);

require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");

$username = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? uc($_SESSION["userLog"]) : false;
$password = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? ($_SESSION["userPas"]) : false;

if(!isset($_GET["op"])) {
	exit("Запрос не обработан");
}elseif(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"]) && $username==false && $password==false) {
	exit("Запрос не обработан");
}else{
	$enter_pass_oper = (isset($_POST["pass_oper"]) && preg_match("|^[a-zA-Z0-9\-_-]{6,20}$|", trim($_POST["pass_oper"]))) ? uc_p($_POST["pass_oper"]) : false;

	if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"]!=false) {

		if($enter_pass_oper!=false) {
			require("".$_SERVER['DOCUMENT_ROOT']."/config.php");

			$sql_user = mysql_query("SELECT `pass_oper` FROM `tb_users` WHERE `username`='$username' AND `vac1`>='".time()."'");
			if(mysql_num_rows($sql_user)>0) {
				$row_user = mysql_fetch_row($sql_user);

				if($row_user["0"]==$enter_pass_oper) {
					mysql_query("UPDATE `tb_users` SET `vac1`='0' WHERE `username`='$username'") or die(mysql_error());

					exit("OK");
				}else{
					exit("Вы не верно указали пароль для операций!");
				}
			}else{
				exit("Не удалось обработать запрос");
			}
		}else{
			exit("NO");
		}
	}else{
		exit("NO");
	}
}
?>