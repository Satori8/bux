<?php
@session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");

sleep(1);

require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");

$username = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? uc($_SESSION["userLog"]) : false;
$password = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? ($_SESSION["userPas"]) : false;

if(!isset($_GET["op"])) {
	exit("������ �� ���������");
}elseif(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"]) && $username==false && $password==false) {
	exit("������ �� ���������");
}else{
	$my_code = "2205837948374";
	$cnt = (isset($_POST["cnt"]) && preg_match("|^[a-zA-Z0-9]{32,48}$|", trim($_POST["cnt"]))) ? htmlspecialchars(trim($_POST["cnt"])) : false;
	$check_hs1 = preg_match('/^[a-zA-Z0-9\$\!\/]{32,48}$/i', $cnt);
	$check_hs2 = md5($username.$password.$my_code);

	if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"]!=false) {

		if($check_hs1==1 && $cnt==$check_hs2) {
			require("".$_SERVER['DOCUMENT_ROOT']."/config.php");
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bonus' AND `howmany`='1'");
			$bonus = mysql_result($sql,0,0);

			$sql_user = mysql_query("SELECT `bonus_act` FROM `tb_users` WHERE `username`='$username'");
			$row_user = mysql_fetch_array($sql_user);
			$bonus_act_usr = $row_user["bonus_act"];

			if(DATE("d.m.Y")!=DATE("d.m.Y", $bonus_act_usr)) {
				mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'$bonus', `bonus_act`='".time()."' WHERE `username`='$username'") or die(mysql_error());
                               mysql_query("INSERT INTO `tb_history` (`user`, `date`, `amount`, `method`, `status`, `tipo`) VALUES('$username', '".DATE("d.m.Y H:i")."', '$bonus', '���������� ����� �� ��������� ��������', '���������', 'popoln')") or die(mysql_error());
 
				echo "OK";
			}else{
				echo "NO";
			}
		}else{
			exit("������ �� ���������");
		}
	}else{
		echo "NO";
	}
}
?>