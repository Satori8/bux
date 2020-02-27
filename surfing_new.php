<?php
session_start();
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<title><?php echo strtoupper(str_replace("www.", "", $_SERVER["HTTP_HOST"]));?> | Просмотр рекламы</title>
	<link type="text/css" rel="stylesheet" href="/style/style-serf.css?v=1.07">
	<!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script type="text/javascript">window.jQuery || document.write('<script src="/js/jquery.min.js?v=1.00"><\/script>')</script>-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/js/jquery/jquery-3.3.1.min.js"><\/script>')</script>
</head>
<body>
<?php
if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	require(ROOT_DIR."/config.php");
	require(ROOT_DIR."/funciones.php");
	require(ROOT_DIR."/merchant/func_mysql.php");

	mysql_query("UPDATE `tb_ads_dlink` SET `status`='3', `date`='".time()."' WHERE `status`>'0' AND `status`<'3' AND ( (`totals`<'1' AND `nolimit`='0') OR ( `nolimit`>'0' AND `nolimit`<='".time()."') ) ");

	$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(trim($_SESSION["userLog"])) : false;
	$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlspecialchars(trim($_SESSION["userPas"])) : false;
	$id_adv_get = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? htmlspecialchars(trim($_GET["id"])) : false;
	$my_lastiplog = getRealIP();

	$token_get = ( isset($_GET["token"]) && preg_match("|^[0-9a-fA-F]{64}$|", trim($_GET["token"])) ) ? md5(htmlspecialchars(trim($_GET["token"]))) : false;
	$token_now = strrev(md5(strtolower($user_name).$id_adv_get.$my_lastiplog)).strrev(md5(strtolower($_SERVER["HTTP_HOST"]).strtolower($_SERVER["HTTP_USER_AGENT"]).$my_lastiplog."### SCORPION ###"));
	$token_to_sess = md5(strrev(md5($token_now)));

	if($token_get==false | strtolower($token_get)!=strtolower(md5($token_now)) ) {
		echo '<script type="text/javascript">location.replace("/");</script>'."\r\n";
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>'."\r\n";
		exit("</body>\r\n</html>");
	}

	$sql_user = mysql_query("SELECT `id`,`username`,`frm_pos` FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'");
	if(mysql_num_rows($sql_user)>0) {
		$row_user = mysql_fetch_assoc($sql_user);
		$username = $row_user["username"];
		$my_frm_position = $row_user["frm_pos"];

		$sql_link = mysql_query("SELECT `id`,`status`,`active`,`timer`,`url` FROM `tb_ads_dlink` WHERE `id`='$id_adv_get' AND `status`>'0' 
			AND (`totals`>'0' OR ( `nolimit`>'0' AND `nolimit`>='".time()."') ) 
			AND (`limit_d`='0' OR (`limit_d`>'0' AND `limit_d_now`>'0') ) 
			AND (`limit_h`='0' OR (`limit_h`>'0' AND `limit_h_now`>'0') ) 
		");
		if(mysql_num_rows($sql_link)>0) {
			$row_link = mysql_fetch_assoc($sql_link);
			$id_adv_get = $row_link["id"];
			$status = $row_link["status"];
			$active = $row_link["active"];
			$timer = $row_link["timer"];
			$url = $row_link["url"];

			$_SESSION["id_adv_serf"] = $id_adv_get;
			$_SESSION["time_end_serf"] = (time()+$timer-2);
			$_SESSION["token_serf"] = $token_to_sess;

			if($status==2) {
				echo '<div align="center" class="block-advertise"><span>Рекламная площадка не активна!</span></div>';
			}elseif($status==3) {
				echo '<div align="center" class="block-advertise"><span>Бюджет рекламной площадки исчерпался!</span></div>';
			}else{
				echo '<script type="text/javascript">'."\r\n";
					echo 'var tm, w = window, d = document, w_focus = true, d_focus = true, mClick = -1, WinWidth = 0, WinHeight = 0, mWinWidth = 640, mWinHeight = 320;'."\r\n";
					echo 'var isActive = '.($active==1 ? "true" : "false").';'."\r\n";
					echo 'var isTimer = '.$timer.';'."\r\n";
					echo 'var isClick = 0;'."\r\n";
					echo 'var id_adv = \''.$id_adv_get.'\';'."\r\n";
					//echo 'var url_adv = \''.$url.'\';'."\r\n";
					echo 'var loadSite = false;'."\r\n";
					echo 'var lcap = false;'."\r\n";
					echo 'var countLoadFrm = 0;'."\r\n";
					echo 'var token = \''.$token_to_sess.'\';'."\r\n";
					echo '$(w).focus(function(){w_focus = true}).blur(function(){w_focus = false}); $(w).focus();'."\r\n";
				echo '</script>'."\r\n";
				echo '<script type="text/javascript" src="/js/js_surfing_verify.js?v='.mt_rand(11111,99999).'"></script>'."\r\n";

				require_once(ROOT_DIR."/includes/frm_links.php");

				if($my_frm_position==1) {
					echo '<div class="block-serf dn-line" style="height:30px;"><table class="tab-frmlink"><tbody><tr><td align="center" valign="middle"><div id="FrmLink">Реклама во фрейме</div></td><td nowrap="nowrap" style="width:140px;"><a href="/advertise.php?ads=frm_links" class="link_adv" target="_blank">Разместить ссылку</a></td></tr></tbody></table></div>';
					echo '<iframe id="framesite" onLoad="OnLoadFrm();" width="100%" hspace="0" vspace="0" marginheight="0" marginwidth="0" frameborder="0" scrolling="auto" src="'.$url.'"></iframe>'."\r\n";
				}
				echo '<div id="BlockDiv" class="block-serf '.($my_frm_position==1 ? "up-line" : "dn-line").'">';
				echo '<table style="height:80px;" class="tab-serf">';
				echo '<tbody>';
				echo '<tr>';
					echo '<td class="td-timer">';
						echo '<div id="BlockVerify">';
							echo '<div id="BlockWait" class="block-wait">Подождите, сайт загружается...</div>';
							echo '<div id="BlockTimer" class="block-timer">';
								echo '<div id="Timer" class="timer"></div><div id="info-timer" class="info-timer"></div>';
							echo '</div>';
						echo '</div>';
					echo '</td>';
					echo '<td align="right" style="width:470px; padding-right:10px; border:0px solid #000;">';
						include(ROOT_DIR."/includes/banner468x60_frm.php");
					echo '</td>';
				echo '</tr>';
				echo '</tbody>';
				echo '</table>';
				echo '</div>';
				if($my_frm_position!=1) {
					echo "\r\n".'<iframe id="framesite" onLoad="OnLoadFrm();" width="100%" hspace="0" vspace="0" marginheight="0" marginwidth="0" frameborder="0" scrolling="auto" src="'.$url.'"></iframe>';
					echo '<div class="block-serf up-line" style="height:30px;"><table class="tab-frmlink"><tbody><tr><td align="center" valign="middle"><div id="FrmLink">Реклама во фрейме</div></td><td nowrap="nowrap" style="width:140px;"><a href="/advertise.php?ads=frm_links" class="link_adv" target="_blank">Разместить ссылку</a></td></tr></tbody></table></div>';
				}
			}		
		}else{
			echo '<div align="center" class="block-advertise"><span>Рекламная площадка не найдена!</span></div>';
		}
	}else{
		echo '<div align="center" class="block-users"><span>';
			echo 'Извините, но эту страничку могут видеть<br>';
			echo 'только пользователи проекта '.strtoupper(array_shift(explode(".", $_SERVER["HTTP_HOST"]))).'<br><br>';
			echo '<a href="/" class="link_aut">Для этого авторизуйтесь пожалуйста</a>';
		echo '</span></div>';
	}
}else{
	echo '<div align="center" class="block-users"><span>';
		echo 'Извините, но эту страничку могут видеть<br>';
		echo 'только пользователи проекта '.strtoupper(array_shift(explode(".", $_SERVER["HTTP_HOST"]))).'<br><br>';
		echo '<a href="/" class="link_aut">Для этого авторизуйтесь пожалуйста</a>';
	echo '</span></div>';
}

?>

</body>
</html>