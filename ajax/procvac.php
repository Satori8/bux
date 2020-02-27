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
	$days = ( isset($_POST["count_days"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["count_days"])) ) ? intval(htmlspecialchars(trim($_POST["count_days"]))) : false;
	$cnt = (isset($_POST["cnt"]) && preg_match("|^[a-zA-Z0-9]{32,48}$|", trim($_POST["cnt"]))) ? htmlspecialchars(trim($_POST["cnt"])) : false;
	$check_hs1 = preg_match('/^[a-zA-Z0-9\$\!\/]{32,48}$/i', $cnt);
	$check_hs2 = md5($username+DATE("H")+24664);

	if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"]!=false) {

		if($check_hs1==1 && $cnt==$check_hs2 && $days>=1 && $days<=180) {
			require("".$_SERVER['DOCUMENT_ROOT']."/config.php");

			$sql_user = mysql_query("SELECT `vac1` FROM `tb_users` WHERE `username`='$username'");
			if(mysql_num_rows($sql_user)>0) {
				$row_user = mysql_fetch_row($sql_user);

				if($row_user["0"]<time()) {
					mysql_query("UPDATE `tb_users` SET `vac1`='".(time()+$days*24*60*60)."' WHERE `username`='$username'") or die(mysql_error());

					exit("OK");
				}else{
					exit("Вы уже в отпуске!");
				}
			}else{
				exit("Пользователь не найден!");
			}
		}else{
			exit("Запрос не обработан");
		}
	}else{
		echo "NO";
	}
}
?>