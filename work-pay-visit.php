<?php
session_start();
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<title><?php echo strtoupper(str_replace("www.", "", $_SERVER["HTTP_HOST"]));?> | Просмотр рекламы</title>
	<link rel="icon" type="image/x-icon" href="//<?=$_SERVER["HTTP_HOST"];?>/favicon.ico?v=1.02" />
	<link type="text/css" rel="stylesheet" href="/style/style-pvisits.css?v=1.03">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/js/jquery/jquery-3.3.1.min.js"><\/script>')</script>
</head>
<body>
<?php
$_SESSION["id_pv"] = false;
$_SESSION["status_pv"] = false;

if(isset($_SESSION["userLog"], $_SESSION["userPas"])) {
	require(ROOT_DIR."/config_mysqli.php");
	require(ROOT_DIR."/funciones.php");
	require(ROOT_DIR."/merchant/func_mysqli.php");

	function escape($value) {
		global $mysqli;
		return $mysqli->real_escape_string($value);
	}

	$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? escape(htmlentities(stripslashes(trim($_SESSION["userLog"])))) : false;
	$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? escape(htmlentities(stripslashes(trim($_SESSION["userPas"])))) : false;
	$user_lastip = getRealIP();

	$id = (isset($_GET["id"]) && is_string($_GET["id"]) && preg_match("|^[\d]{1,11}$|", intval(trim($_GET["id"])))) ? intval(trim($_GET["id"])) : false;
	$token_post = (isset($_GET["token"]) && is_string($_GET["token"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_GET["token"]))) ? strtolower(trim($_GET["token"])) : false;
	$security_key = "lilacbux^(*pay_visit7&&9unjhu78*";

	if($id == false | $token_post == false) {
		echo '<script type="text/javascript">location.replace("/");</script>'."\r\n";
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>'."\r\n";
		exit("\r\n</body>\r\n</html>");
	}

	$mysqli->query("UPDATE `tb_ads_pay_vis` SET `status`='3',`date_end`='".time()."' WHERE `status`='1' AND `balance`<`price_adv`") or die($mysqli->error);
	$mysqli->query("UPDATE `tb_ads_pay_vis` SET `status`='1' WHERE `status`='3' AND `balance`>=`price_adv`") or die($mysqli->error);
	$mysqli->query("DELETE FROM `tb_ads_pay_visits` WHERE `ident`='$id' AND `status`='1' AND `user_name`='$user_name' AND `time_next`<='".time()."'") or die($mysqli->error);

	$sql_user = $mysqli->query("SELECT `id`,`username`,`ban_date` FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'") or die($mysqli->error);
	if($sql_user->num_rows > 0) {
		$row_user = $sql_user->fetch_assoc();
		$user_id = $row_user["id"];
		$user_name = $row_user["username"];
		$user_ban_date = $row_user["ban_date"];
		$sql_user->free();
	}else{
		$sql_user->free();
		$mysqli->close();

		if(isset($_SESSION)) session_destroy();

		echo '<div align="center" class="block-users"><span>';
			echo 'Извините, но эту страничку могут видеть<br>только пользователи проекта '.strtoupper(array_shift(explode(".", $_SERVER["HTTP_HOST"]))).'<br><br>';
			echo '<a href="/login" class="link_aut">Для этого авторизуйтесь пожалуйста</a>';
		echo '</span></div>';
		exit("\r\n</body>\r\n</html>");
	}

	$sql = $mysqli->query("SELECT `id`,`status`,`url`,`hide_httpref` FROM `tb_ads_pay_vis` WHERE `id`='$id' AND `status`='1' AND `balance`>=`price_adv`") or die($mysqli->error);
	if($sql->num_rows > 0) {
		$row = $sql->fetch_assoc();
		$id = $row["id"];
		$url_adv = $row["url"];
		$hide_httpref = $row["hide_httpref"];
		$sql->free();

		$token_check = strtolower(md5($id.strtolower($user_name).$_SERVER["HTTP_HOST"]."token-go-work".$security_key));
		$token_next = strtolower(md5($id.strtolower($user_name).$_SERVER["HTTP_HOST"]."token-success-work".$security_key));

		$_SESSION["id_pv"] = $id;
		$_SESSION["status_pv"] = false;
		$url_go = "//".$_SERVER["HTTP_HOST"]."/redir_pay_visits.php?id=$id&type=pay_visits";

		if($token_post == false | $token_post != $token_check) {
			$mysqli->close();

			echo '<script type="text/javascript">location.replace("/");</script>'."\r\n";
			echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>'."\r\n";
			exit("\r\n</body>\r\n</html>");
		}else{
			$sql = $mysqli->query("SELECT `id` FROM `tb_ads_pay_visits` WHERE `ident`='$id' AND `status`='1' AND `user_name`='$user_name' AND `time_next`>='".time()."'") or die($mysqli->error);
			if($sql->num_rows > 0) {
				$sql->free();
				$mysqli->close();

				echo '<div class="block-error"><span class="msg-error" style="padding:20px 30px; width:50%;">Вы уже просматривали эту ссылку за требуемый период.</span></div>';
				exit("\r\n</body>\r\n</html>");
			}else{
				$sql->free();
			}
		}
	}else{
		$sql->free();
		$mysqli->close();

		echo '<div align="center" class="block-advertise"><span>Рекламная площадка не доступна!</span></div>';
		exit("\r\n</body>\r\n</html>");
	}
	$mysqli->close();

	echo "<script>\r\n";
		echo "var timer = 3;\r\n";
		echo "var id = $id;\r\n";
		echo "var op = 'success-work';\r\n";
		echo "var token = '$token_next';\r\n";
		echo "var timeoutID, status_work = false;\r\n";
		echo "var domen = location.hostname || document.domain;\r\n";
		echo "var url_go = '$url_go';\r\n";
	echo "</script>\r\n";
	echo "<script src=\"/js/js_work_pay_visit.js?v=1.03\"></script>\r\n";

	echo '<table>';
	echo '<tbody>';
	echo '<tr><td align="center">';
		echo '<div class="work-info">';
			echo '<div class="text-timer">Переход на сайт через <div class="timer"></div></div>';
			echo '<div><b>1.</b> Прокрутите страницу рекламодателя вниз.</div>';
			echo '<div><b>2.</b> Нажмите на любую <b>ссылку</b>/<b>кнопку</b>/<b>картинку</b> внизу страницы.</div>';
			echo '<div class="work-status" style="display:none;"></div>';
		echo '</div>';
	echo '</td></tr>';
	echo '</tbody>';
	echo '</table>'."\r\n";

}else{
	if(isset($_SESSION)) session_destroy();
	echo '<div align="center" class="block-users"><span>';
		echo 'Извините, но эту страничку могут видеть<br>только пользователи проекта '.strtoupper(array_shift(explode(".", $_SERVER["HTTP_HOST"]))).'<br><br>';
		echo '<a href="/login" class="link_aut">Для этого авторизуйтесь пожалуйста</a>';
	echo '</span></div>'."\r\n";
}
?>
</body>
</html>