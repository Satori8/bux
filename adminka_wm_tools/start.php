<?php
if(!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
if(!DEFINED("DOC_ROOT")) DEFINE("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
require(DOC_ROOT."/config.php");
require(DOC_ROOT."/funciones.php");
$op = (isset($_GET["op"])) ? limpiar(trim($_GET["op"])) : "site_config";

if(isset($_HTTPS) && $_HTTPS == true) {
	$_SESSION["redirect_url"] = "https://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?op=$op";
}else{
	$_SESSION["redirect_url"] = "http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?op=$op";
}

if(isset($_SESSION["userLog_a"]) && isset($_SESSION["userPas_a"])) {

	$username = (isset($_SESSION["userLog_a"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog_a"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog_a"]))) : false;

	$sql = mysql_query("SELECT * FROM `tb_users` WHERE `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$wmid_user = $row["wmid"];

		if(strtolower($username)!=strtolower($row["username"])) {
			echo '<div style="position:fixed; top:0; left:0; height:100%; width:100%; background: url(/style/img/bg-header2.jpg);">';
				echo '<span class="msg-error" style="margin:150px auto; width:70%; padding:20px;">Логин не верный!</span>';
			echo '</div>';
			exit();

		}elseif(htmlspecialchars($_SESSION["userPas_a"])!=md5($row["password"])) {
			echo '<div style="position:fixed; top:0; left:0; height:100%; width:100%; background: url(/style/img/bg-header2.jpg);">';
				echo '<span class="msg-error" style="margin:150px auto; width:70%; padding:20px;">Пароль не верный!</span>';
			echo '</div>';
			exit();

		}elseif($row["user_status"]!="1") {
			echo '<div style="position:fixed; top:0; left:0; height:100%; width:100%; background: url(/style/img/bg-header2.jpg);">';
				echo '<span class="msg-error" style="margin:150px auto; width:70%; padding:20px;">Вы не являетесь администратором. Доступ закрыт!</span>';
			echo '</div>';
			exit();
		}
	}else{
		echo '<div style="position:fixed; top:0; left:0; height:100%; width:100%; background: url(/style/img/bg-header2.jpg);">';
			echo '<span class="msg-error" style="margin:150px auto; width:70%; padding:20px;">Error: Access denied!</span>';
		echo '</div>';
		exit();
	}
}else{
	echo '<div style="position:fixed; top:0; left:0; height:100%; width:100%; background: url(/style/img/bg-header2.jpg);">';
		echo '<span class="msg-error" style="margin:150px auto; width:70%; padding:20px;">';
			echo 'Для доступа в админ панель необходимо авторизоваться через WebMoney Login';
			echo '<br><br>';
			echo '<div align="center"><form method="GET" action="https://login.wmtransfer.com/GateKeeper.aspx" id="newform">';
				echo '<input type="hidden" name="RID" value="'.$URL_ID_WM_LOGIN.'">';
				echo '<input type="hidden" name="lang" value="ru-RU">';
				echo '<input type="hidden" name="op" value="adminka">';
				echo '<input type="submit" class="sub-blue160" style="float:none;" value="WebMoney Login">';
			echo '</form></div>';
		echo '</span>';
	echo '</div>';

	echo '</body>';
	echo '</html>';

	exit();
}
?>