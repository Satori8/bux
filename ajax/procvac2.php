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
	$month = ( isset($_POST["count_month"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["count_month"])) ) ? intval(htmlspecialchars(trim($_POST["count_month"]))) : false;
	$cnt = (isset($_POST["cnt"]) && preg_match("|^[a-zA-Z0-9]{32,48}$|", trim($_POST["cnt"]))) ? htmlspecialchars(trim($_POST["cnt"])) : false;
	$check_hs1 = preg_match('/^[a-zA-Z0-9\$\!\/]{32,48}$/i', $cnt);
	$check_hs2 = md5($username+DATE("H")+24664);

	if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"]!=false) {

		if($check_hs1==1 && $cnt==$check_hs2 && $month>=1) {
			require("".$_SERVER['DOCUMENT_ROOT']."/config.php");

			$sql_user = mysql_query("SELECT `money_rb`,`vac2` FROM `tb_users` WHERE `username`='$username'");
			if(mysql_num_rows($sql_user)>0) {
				$row_user = mysql_fetch_row($sql_user);

				$money_rb = $row_user["0"];
				$vac2 = $row_user["1"];

				if($money_rb<($month*15)) {
					exit("На вашем рекламном счету не достаточно средств!");
				}elseif($vac2<time()) {
					mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'".($month*15)."', `vac2`='".(time()+$month*30*24*60*60)."' WHERE `username`='$username'") or die(mysql_error());

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`tran_id`,`user`,`wmr`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
					VALUES('1','0','$username','','".DATE("d.m.Y H:i",time())."','".time()."','".($month*15)."','Оплата услуги Обслуживание аккаунта - $month мес.','Списано','prihod')") or die(mysql_error());

					exit("OK");
				}elseif($vac2>=time()) {
					mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'".($month*15)."', `vac2`='".($vac2+$month*30*24*60*60)."' WHERE `username`='$username'") or die(mysql_error());

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`tran_id`,`user`,`wmr`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
					VALUES('1','0','$username','','".DATE("d.m.Y H:i",time())."','".time()."','".($month*15)."','Оплата услуги Обслуживание аккаунта - $month мес.','Списано','prihod')") or die(mysql_error());

					exit("OK");
				}else{
					exit("Вы уже в отпуске!");
				}
			}else{
				exit("Пользователь не найден!");
			}


			//exit("OK");
		}else{
			exit("Запрос не обработан");
		}
	}else{
		exit("NO");
	}
}
?>